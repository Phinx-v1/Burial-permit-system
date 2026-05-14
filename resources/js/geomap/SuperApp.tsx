import React, { useState, useEffect } from 'react'
import { MapView } from './components/Map/MapView'
import { NicheGrid } from './components/Map/NicheGrid'
import { GridConfigModal } from './components/Map/GridConfigModal'
import { motion, AnimatePresence } from 'framer-motion'
import {
  X, PlusCircle, LayoutGrid, BarChart3, Wrench,
  MousePointer2, User, Plus, Minus, MapPin
} from 'lucide-react'
import { useMapStore } from './store/useMapStore'
import { PlotSearch } from './components/Map/PlotSearch'
import { AdvancedSidebar } from './components/Map/AdvancedSidebar'
import { SystemDeleteModal } from './components/Map/SystemDeleteModal'

// ─── Geo helpers ───────────────────────────────────────────────────────────────
function calcBearing(lat1: number, lng1: number, lat2: number, lng2: number) {
  const R2D = 180 / Math.PI
  const la1 = lat1 / R2D, la2 = lat2 / R2D
  const dl  = (lng2 - lng1) / R2D
  const y   = Math.sin(dl) * Math.cos(la2)
  const x   = Math.cos(la1) * Math.sin(la2) - Math.sin(la1) * Math.cos(la2) * Math.cos(dl)
  return (Math.atan2(y, x) * R2D + 360) % 360
}

function calcDistanceM(lat1: number, lng1: number, lat2: number, lng2: number) {
  const R  = 6371000
  const la1 = lat1 * Math.PI / 180, la2 = lat2 * Math.PI / 180
  const dla = (lat2 - lat1) * Math.PI / 180
  const dlo = (lng2 - lng1) * Math.PI / 180
  const a   = Math.sin(dla/2)**2 + Math.cos(la1)*Math.cos(la2)*Math.sin(dlo/2)**2
  return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a))
}

// ─── MapLibre source/layer helpers ─────────────────────────────────────────────
function buildLinesGeoJSON(grids: any[]) {
  return {
    type: 'FeatureCollection' as const,
    features: grids
      .filter(g => g.lineStart && g.lineEnd)
      .map(g => ({
        type: 'Feature' as const,
        id: g.id,
        properties: { id: String(g.id), color: g.color || '#ef4444', name: g.name },
        geometry: {
          type: 'LineString' as const,
          coordinates: [
            [g.lineStart.lng, g.lineStart.lat],
            [g.lineEnd.lng,   g.lineEnd.lat],
          ],
        },
      })),
  }
}

function buildGhostGeoJSON(start: any, end: any) {
  if (!start) return { type: 'FeatureCollection' as const, features: [] }
  const coords = [[start.lng, start.lat]]
  if (end) coords.push([end.lng, end.lat])
  return {
    type: 'FeatureCollection' as const,
    features: [{ type: 'Feature' as const, properties: {}, geometry: { type: 'LineString' as const, coordinates: coords } }],
  }
}

// ─── Component ─────────────────────────────────────────────────────────────────
function SuperApp() {
  const {
    isLoaded, map,
    gridOverlays, setGridOverlays,
    editMode, setEditMode,
    drawingStartPos, setDrawingStartPos,
    pendingGrid, setPendingGrid,
    hoveredGridId, setHoveredGridId,
    selectedGridId, setSelectedGridId,
  } = useMapStore()

  const [selectedFeature, setSelectedFeature] = useState<any>(null)
  const [pointsData,      setPointsData]       = useState<any>(null)
  const [viewStats,       setViewStats]        = useState({ total: 0, active: 0, expiring: 0, expired: 0, occupied: 0 })
  const [mouseGeoPos,     setMouseGeoPos]      = useState<{ lat: number; lng: number } | null>(null)

  // ─── Data Fetch ──────────────────────────────────────────────────────────────
  useEffect(() => {
    const fetchPlots = async () => {
      try {
        const res  = await fetch('/cemetery/plots')
        const data = await res.json()
        if (data?.type === 'FeatureCollection') setPointsData(data)
      } catch {}
    }

    const fetchGrids = async () => {
      try {
        const res  = await fetch('/niche-grids')
        const data = await res.json()
        setGridOverlays(data.map((g: any) => ({
          id:          String(g.id),
          name:        g.name,
          rows:        g.rows,
          cols:        g.cols,
          labelFormat: g.label_format,
          color:       g.color || '#ef4444',
          lineStart:   (g.start_lat != null) ? { lat: parseFloat(g.start_lat), lng: parseFloat(g.start_lng) } : undefined,
          lineEnd:     (g.end_lat   != null) ? { lat: parseFloat(g.end_lat),   lng: parseFloat(g.end_lng)   } : undefined,
          position:    { lat: parseFloat(g.latitude), lng: parseFloat(g.longitude) },
          rotation:    g.rotation,
          widthScale:  g.width_scale,
          cells:       g.cells || {},
        })))
      } catch {}
    }

    fetchPlots()
    fetchGrids()
  }, [setGridOverlays])

  // ─── Cemetery plots on map ────────────────────────────────────────────────────
  useEffect(() => {
    if (!map || !isLoaded || !pointsData) return

    if (!map.getSource('plots')) {
      map.addSource('plots', { type: 'geojson', data: pointsData, cluster: true, clusterMaxZoom: 14, clusterRadius: 50 })

      map.addLayer({ id: 'clusters', type: 'circle', source: 'plots', filter: ['has', 'point_count'],
        paint: {
          'circle-color': ['step', ['get', 'point_count'], '#3b82f6', 100, '#8b5cf6', 750, '#f59e0b'],
          'circle-radius': ['step', ['get', 'point_count'], 22, 100, 28, 750, 36],
          'circle-stroke-width': 3, 'circle-stroke-color': 'rgba(255,255,255,0.1)'
        }
      })
      map.addLayer({ id: 'cluster-count', type: 'symbol', source: 'plots', filter: ['has', 'point_count'],
        layout: { 'text-field': '{point_count}', 'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'], 'text-size': 12 },
        paint: { 'text-color': '#ffffff' }
      })
      map.addLayer({ id: 'unclustered-point', type: 'circle', source: 'plots', filter: ['!', ['has', 'point_count']],
        paint: {
          'circle-color': ['match', ['get', 'permit_status'], 'active', '#10b981', 'expiring', '#f59e0b', 'expired', '#ef4444', '#1e293b'],
          'circle-radius': 8, 'circle-stroke-width': 2, 'circle-stroke-color': '#ffffff'
        }
      })

      map.on('click', 'unclustered-point', (e: any) => {
        if (e.features?.[0]) setSelectedFeature(e.features[0].properties)
      })
      map.on('mouseenter', 'unclustered-point', () => { map.getCanvas().style.cursor = 'pointer' })
      map.on('mouseleave', 'unclustered-point', () => { map.getCanvas().style.cursor = '' })

      const updateStats = () => {
        const features = map.queryRenderedFeatures({ layers: ['unclustered-point'] })
        const gridFeatures = map.queryRenderedFeatures({ layers: ['niche-lines-halo'] })
        
        const s = { total: 0, active: 0, expiring: 0, expired: 0, occupied: 0 }
        
        // 1. Static Plot Markers
        features.forEach((f: any) => {
          s.total++
          if (f.properties.status === 'occupied')         s.occupied++
          if (f.properties.permit_status === 'active')    s.active++
          if (f.properties.permit_status === 'expiring')  s.expiring++
          if (f.properties.permit_status === 'expired')   s.expired++
        })

        // 2. Niche Grid Matrices (Dynamic Cells)
        if (gridFeatures.length > 0) {
          const visibleGridIds = new Set(gridFeatures.map((f: any) => String(f.properties.id)))
          const overlays = useMapStore.getState().gridOverlays || []
          
          overlays.forEach(grid => {
            if (visibleGridIds.has(String(grid.id))) {
              const totalCells = (grid.rows || 0) * (grid.cols || 0)
              s.total += totalCells
              
              if (grid.cells) {
                Object.values(grid.cells).forEach((c: any) => {
                  if (c.status === 'occupied') s.occupied++
                  // Note: If cells eventually store active/expired permit states, they can be calculated here too
                })
              }
            }
          })
        }

        setViewStats(s)
      }
      map.on('moveend', updateStats)
      updateStats()
    } else {
      (map.getSource('plots') as any).setData(pointsData)
    }
  }, [map, isLoaded, pointsData])

  // ─── Niche-grid lines layer ────────────────────────────────────────────────────
  useEffect(() => {
    if (!map || !isLoaded) return

    const geojson = buildLinesGeoJSON(gridOverlays)

    if (!map.getSource('niche-lines')) {
      map.addSource('niche-lines', { type: 'geojson', data: geojson })

      // Halo (wide invisible hit area + glow)
      map.addLayer({
        id: 'niche-lines-halo', type: 'line', source: 'niche-lines',
        paint: { 'line-color': ['get', 'color'], 'line-width': 18, 'line-opacity': 0.0 }
      })
      // Solid-colored line
      map.addLayer({
        id: 'niche-lines-base', type: 'line', source: 'niche-lines',
        paint: {
          'line-color':   ['get', 'color'],
          'line-width':   ['case', ['==', ['get', 'id'], ['literal', hoveredGridId ?? '']], 8, 5],
          'line-blur':    0,
          'line-opacity': 1,
        }
      })
      // Dot endpoints
      map.addLayer({
        id: 'niche-lines-dots', type: 'circle', source: 'niche-lines',
        paint: { 'circle-color': ['get', 'color'], 'circle-radius': 7, 'circle-stroke-color': 'white', 'circle-stroke-width': 2 }
      })

      // ── Hover handling ──────────────────────────────────────────
      map.on('mousemove', 'niche-lines-halo', (e: any) => {
        if (e.features?.[0]) {
          map.getCanvas().style.cursor = 'pointer'
          const id = String(e.features[0].properties.id)
          if (useMapStore.getState().selectedGridId !== id)
            useMapStore.getState().setHoveredGridId(id)
        }
      })
      map.on('mouseleave', 'niche-lines-halo', () => {
        map.getCanvas().style.cursor = ''
        useMapStore.getState().setHoveredGridId(null)
      })

      // ── Click handling ──────────────────────────────────────────
      map.on('click', 'niche-lines-halo', (e: any) => {
        if (e.features?.[0]) {
          e.preventDefault()
          const id = String(e.features[0].properties.id)
          const state = useMapStore.getState()
          state.setSelectedGridId(state.selectedGridId === id ? null : id)
        }
      })
    } else {
      (map.getSource('niche-lines') as any).setData(geojson)
    }
  }, [map, isLoaded, gridOverlays])

  // Keep line width reactive to hover/selected
  useEffect(() => {
    if (!map || !isLoaded || !map.getLayer('niche-lines-base')) return
    map.setPaintProperty('niche-lines-base', 'line-width', [
      'case',
      ['==', ['get', 'id'], ['literal', selectedGridId ?? '']], 10,
      ['==', ['get', 'id'], ['literal', hoveredGridId  ?? '']], 8,
      5
    ])
  }, [map, isLoaded, hoveredGridId, selectedGridId])

  // ─── Ghost-line preview source ─────────────────────────────────────────────────
  useEffect(() => {
    if (!map || !isLoaded) return
    if (!map.getSource('ghost-line')) {
      map.addSource('ghost-line', { type: 'geojson', data: buildGhostGeoJSON(null, null) })
      map.addLayer({ id: 'ghost-line-layer', type: 'line', source: 'ghost-line',
        paint: { 'line-color': '#60a5fa', 'line-width': 3, 'line-dasharray': [3, 3], 'line-opacity': 0.85 }
      })
      map.addLayer({ id: 'ghost-start-dot', type: 'circle', source: 'ghost-line',
        paint: { 'circle-color': '#3b82f6', 'circle-radius': 6, 'circle-stroke-color': 'white', 'circle-stroke-width': 2 }
      })
    }
  }, [map, isLoaded])

  useEffect(() => {
    if (!map || !isLoaded || !map.getSource('ghost-line')) return
    ;(map.getSource('ghost-line') as any).setData(buildGhostGeoJSON(drawingStartPos, mouseGeoPos))
  }, [drawingStartPos, mouseGeoPos, map, isLoaded])

  // ─── Drawing interactions ────────────────────────────────────────────────────
  useEffect(() => {
    if (!map || !isLoaded) return

    const handleClick = (e: any) => {
      const state = useMapStore.getState()
      if (state.editMode !== 'create-grid') return

      // Prevent firing when user clicked on a niche line
      if (map.queryRenderedFeatures(e.point, { layers: ['niche-lines-halo'] }).length > 0) return

      const { lng, lat } = e.lngLat

      if (!state.drawingStartPos) {
        state.setDrawingStartPos({ lat, lng })
      } else {
        const start = state.drawingStartPos
        const bearing  = calcBearing(start.lat, start.lng, lat, lng)
        const distance = calcDistanceM(start.lat, start.lng, lat, lng)

        state.setPendingGrid({ lineStart: start, lineEnd: { lat, lng }, bearing, distanceM: distance })
        state.setDrawingStartPos(null)
        setMouseGeoPos(null)
      }
    }

    const handleMove = (e: any) => {
      if (useMapStore.getState().editMode === 'create-grid' && useMapStore.getState().drawingStartPos) {
        setMouseGeoPos(e.lngLat)
      }
    }

    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === 'Escape') {
        const state = useMapStore.getState()
        state.setDrawingStartPos(null)
        state.setPendingGrid(null)
        setMouseGeoPos(null)
        if (state.editMode === 'create-grid') state.setEditMode('view')
      }
    }

    map.on('click',     handleClick)
    map.on('mousemove', handleMove)
    window.addEventListener('keydown', handleKeyDown)

    return () => {
      map.off('click',     handleClick)
      map.off('mousemove', handleMove)
      window.removeEventListener('keydown', handleKeyDown)
    }
  }, [map, isLoaded])

  // Set cursor for create-grid mode
  useEffect(() => {
    if (!map) return
    map.getCanvas().style.cursor = editMode === 'create-grid' ? 'crosshair' : ''
  }, [map, editMode])

  const handleFlyTo = (res: any) => {
    if (!map) return
    if (res.longitude && res.latitude) {
      map.flyTo({ center: [res.longitude, res.latitude], zoom: 20, speed: 1.2, essential: true })
    }
    setSelectedFeature({ ...res })
  }

  // ─── UI Styles ─────────────────────────────────────────────────────────────────
  const island: React.CSSProperties = {
    background: 'rgba(10, 16, 33, 0.95)',
    backdropFilter: 'blur(32px)',
    border: '1px solid rgba(255,255,255,0.08)',
    borderRadius: '18px',
    boxShadow: '0 20px 50px rgba(0,0,0,0.6)',
    padding: '16px 24px',
  }

  const drawingMsg = drawingStartPos
    ? '📍 Click end point — Esc to cancel'
    : '📍 Click start point'

  return (
    <div style={{ position: 'relative', width: '100%', height: '100vh', overflow: 'hidden', background: '#020617' }}>
      <MapView />

      {/* ─── Top Bar ─── */}
      <div style={{ position: 'absolute', top: '12px', left: '12px', right: '12px', display: 'flex', justifyContent: 'center', pointerEvents: 'none', zIndex: 1000 }}>
        <div style={{ ...island, display: 'flex', alignItems: 'center', gap: '20px', pointerEvents: 'auto', maxWidth: '960px' }}>
          <PlotSearch pointsData={pointsData} onSelect={handleFlyTo} />
          <div style={{ width: '1px', height: '28px', background: 'rgba(255,255,255,0.08)' }} />
          <div style={{ display: 'flex', background: 'rgba(0,0,0,0.3)', padding: '4px', borderRadius: '12px', gap: '4px' }}>
            {[
              { id: 'view',      label: 'Analytics', icon: <BarChart3 size={14} /> },
              { id: 'edit-item', label: 'Manage',    icon: <Wrench size={14} />    },
            ].map(tab => (
              <button
                key={tab.id}
                onClick={() => setEditMode(tab.id as any)}
                style={{
                  padding: '8px 18px', borderRadius: '8px', border: 'none', fontSize: '11px', fontWeight: 900, cursor: 'pointer',
                  background: editMode === tab.id || (tab.id !== 'view' && editMode !== 'view') ? '#3b82f6' : 'transparent',
                  color: editMode === tab.id || (tab.id !== 'view' && editMode !== 'view') ? 'white' : 'rgba(255,255,255,0.4)',
                  transition: 'all 0.2s', display: 'flex', alignItems: 'center', gap: '6px',
                }}
              >
                {tab.icon}{tab.label}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* ─── Builder Toolbar (left) ─── */}
      <AnimatePresence>
        {editMode !== 'view' && (
          <motion.div
            initial={{ x: -80, opacity: 0 }} animate={{ x: 0, opacity: 1 }} exit={{ x: -80, opacity: 0 }}
            style={{ position: 'absolute', left: '12px', top: '80px', bottom: '12px', pointerEvents: 'none', zIndex: 1000, display: 'flex', flexDirection: 'column' }}
          >
            <div style={{ ...island, pointerEvents: 'auto', display: 'flex', flexDirection: 'column', gap: '14px', padding: '18px', alignItems: 'center' }}>
              <div style={{ fontSize: '9px', fontWeight: 900, color: '#3b82f6', textTransform: 'uppercase', letterSpacing: '0.12em', paddingBottom: '8px', borderBottom: '1px solid rgba(255,255,255,0.06)', width: '100%', textAlign: 'center' }}>Builder</div>

              {[
                { id: 'create-plot', icon: <PlusCircle size={20} />, title: 'Add Plot' },
                { id: 'create-grid', icon: <LayoutGrid size={20} />, title: 'Draw Niche Line' },
                { id: 'item-edit',   icon: <MousePointer2 size={20} />, title: 'Select / Edit' },
              ].map(tool => (
                <button
                  key={tool.id}
                  onClick={() => {
                    if (editMode === tool.id) { setEditMode('edit-item'); setDrawingStartPos(null) }
                    else setEditMode(tool.id as any)
                  }}
                  title={tool.title}
                  style={{
                    width: '52px', height: '52px', borderRadius: '14px', border: '1px solid rgba(255,255,255,0.06)',
                    background: editMode === tool.id ? '#3b82f6' : 'rgba(255,255,255,0.04)',
                    color: 'white', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center',
                    transition: 'all 0.2s', boxShadow: editMode === tool.id ? '0 0 20px rgba(59,130,246,0.4)' : 'none',
                  }}
                >
                  {tool.icon}
                </button>
              ))}

              <div style={{ marginTop: 'auto', paddingTop: '12px', borderTop: '1px solid rgba(255,255,255,0.06)', width: '100%' }}>
                <button
                  onClick={() => { setEditMode('view'); setDrawingStartPos(null) }}
                  style={{ width: '52px', height: '52px', background: 'rgba(239,68,68,0.1)', color: '#ef4444', border: '1px solid rgba(239,68,68,0.2)', borderRadius: '14px', cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center' }}
                >
                  <X size={20} />
                </button>
              </div>
            </div>

            {/* Instructions tooltip */}
            {editMode === 'create-grid' && (
              <motion.div
                initial={{ opacity: 0, x: 10 }} animate={{ opacity: 1, x: 0 }}
                style={{ ...island, padding: '12px 16px', position: 'absolute', left: 'calc(100% + 12px)', top: '70px', width: '200px', pointerEvents: 'none' }}
              >
                <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <MapPin size={14} color="#3b82f6" style={{ flexShrink: 0 }} />
                  <p style={{ color: 'white', fontSize: '11px', margin: 0, lineHeight: 1.5 }}>{drawingMsg}</p>
                </div>
              </motion.div>
            )}
          </motion.div>
        )}
      </AnimatePresence>

      {/* ─── Right Sidebar ─── */}
      <div style={{ position: 'absolute', right: '0', top: '0', bottom: '0', zIndex: 1000, pointerEvents: 'none' }}>
        <div style={{ pointerEvents: 'auto', height: '100%' }}>
          <AdvancedSidebar stats={viewStats} />
        </div>
      </div>

      {/* ─── Zoom Controls ─── */}
      <div style={{ position: 'absolute', left: '80px', bottom: '24px', display: 'flex', gap: '8px', zIndex: 1000 }}>
        <button onClick={() => map?.zoomIn()} style={zoomBtnStyle}><Plus  size={18} /></button>
        <button onClick={() => map?.zoomOut()} style={zoomBtnStyle}><Minus size={18} /></button>
      </div>

      {/* ─── Niche grid HTML popups (one per saved grid) ─── */}
      <div style={{ position: 'absolute', inset: 0, pointerEvents: 'none', zIndex: 20 }}>
        {gridOverlays.map(sec => (
          <NicheGrid key={sec.id} section={sec} />
        ))}
      </div>

      {/* ─── Config Modal (appears after drawing a line) ─── */}
      <AnimatePresence>
        {pendingGrid && <GridConfigModal pending={pendingGrid} />}
      </AnimatePresence>

      {/* ─── Plot Detail Modal ─── */}
      <AnimatePresence>
        {selectedFeature && (
          <div style={{ position: 'fixed', inset: 0, backgroundColor: 'rgba(2,6,23,0.5)', zIndex: 3000, display: 'flex', alignItems: 'center', justifyContent: 'center', backdropFilter: 'blur(10px)' }}>
            <motion.div
              initial={{ scale: 0.9, opacity: 0 }} animate={{ scale: 1, opacity: 1 }} exit={{ scale: 0.9, opacity: 0 }}
              style={{ width: '400px', background: 'rgba(10,16,33,0.98)', backdropFilter: 'blur(50px)', borderRadius: '28px', border: '1px solid rgba(255,255,255,0.12)', padding: '36px', boxShadow: '0 60px 120px rgba(0,0,0,0.8)', position: 'relative' }}
            >
              <button onClick={() => setSelectedFeature(null)} style={{ position: 'absolute', top: '28px', right: '28px', background: 'none', border: 'none', color: '#64748b', cursor: 'pointer' }}>
                <X size={20} />
              </button>
              <p style={{ color: '#3b82f6', fontSize: '10px', fontWeight: 900, textTransform: 'uppercase', letterSpacing: '0.12em', marginBottom: '8px' }}>Plot</p>
              <h2 style={{ fontSize: '34px', fontWeight: 900, color: 'white', margin: '0 0 24px' }}>{selectedFeature.plot_code}</h2>

              <div style={{ padding: '20px', backgroundColor: 'rgba(255,255,255,0.04)', borderRadius: '18px', marginBottom: '24px', display: 'flex', alignItems: 'center', gap: '14px' }}>
                <div style={{ width: '44px', height: '44px', background: '#3b82f620', borderRadius: '12px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                  <User size={20} color="#3b82f6" />
                </div>
                <div>
                  <p style={{ color: '#64748b', fontSize: '9px', fontWeight: 800, textTransform: 'uppercase', margin: '0 0 4px' }}>Occupant</p>
                  <p style={{ color: 'white', fontSize: '16px', fontWeight: 700, margin: 0 }}>{selectedFeature.deceased_name || 'Available'}</p>
                </div>
              </div>

              <div style={{ display: 'flex', gap: '12px' }}>
                <a href={selectedFeature.deceased_id ? `/deceased/${selectedFeature.deceased_id}` : '#'}
                   style={{ flex: 1, textDecoration: 'none', backgroundColor: '#3b82f6', color: 'white', padding: '14px', borderRadius: '14px', fontWeight: 900, fontSize: '12px', textTransform: 'uppercase', textAlign: 'center' }}>
                  View Profile
                </a>
                <button onClick={() => setSelectedFeature(null)}
                        style={{ flex: 1, backgroundColor: 'rgba(255,255,255,0.06)', color: 'white', border: 'none', padding: '14px', borderRadius: '14px', fontWeight: 900, fontSize: '12px', textTransform: 'uppercase', cursor: 'pointer' }}>
                  Close
                </button>
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>

      <SystemDeleteModal />
    </div>
  )
}

const zoomBtnStyle: React.CSSProperties = {
  width: '44px', height: '44px', borderRadius: '12px',
  background: 'rgba(10,16,33,0.95)', color: 'white',
  border: '1px solid rgba(255,255,255,0.08)', cursor: 'pointer',
  display: 'flex', alignItems: 'center', justifyContent: 'center',
  boxShadow: '0 8px 24px rgba(0,0,0,0.5)',
}

export default SuperApp
