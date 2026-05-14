import React, { useState, useEffect, useRef } from 'react'
import { MapView } from './components/Map/MapView'
import { NicheGrid } from './components/Map/NicheGrid'
import { PlotSearch } from './components/Map/PlotSearch'
import { motion, AnimatePresence } from 'framer-motion'
import { X, ZoomIn, ZoomOut } from 'lucide-react'
import { useMapStore } from './store/useMapStore'

function buildLinesGeoJSON(grids: any[]) {
  const features = grids
    .filter(g => g.lineStart && g.lineEnd)
    .map(g => ({
      type: 'Feature',
      properties: {
        id: String(g.id),
        color: g.color || '#ef4444'
      },
      geometry: {
        type: 'LineString',
        coordinates: [
          [g.lineStart.lng, g.lineStart.lat],
          [g.lineEnd.lng, g.lineEnd.lat]
        ]
      }
    }))
  return { type: 'FeatureCollection', features }
}

function App() {
  const {
    isLoaded, map,
    gridOverlays, setGridOverlays,
    selectedFeature, setSelectedFeature
  } = useMapStore()

  const [pointsData, setPointsData] = useState<any>(null)
  const urlSearchHandledRef = useRef(false)

  // Real-time Data Sync
  useEffect(() => {
    const fetchPlots = async () => {
      try {
        const res = await fetch('/cemetery/plots')
        const data = await res.json()
        if (data && data.type === 'FeatureCollection') setPointsData(data)
      } catch (err) { /* silent */ }
    }
    const fetchGrids = async () => {
      try {
        const res = await fetch('/niche-grids')
        const data = await res.json()
        setGridOverlays(data.map((g: any) => ({
          id: String(g.id),
          name: g.name,
          rows: g.rows,
          cols: g.cols,
          labelFormat: g.label_format,
          color: g.color || '#ef4444',
          lineStart: (g.start_lat != null) ? { lat: parseFloat(g.start_lat), lng: parseFloat(g.start_lng) } : undefined,
          lineEnd: (g.end_lat != null) ? { lat: parseFloat(g.end_lat), lng: parseFloat(g.end_lng) } : undefined,
          position: { lat: parseFloat(g.latitude), lng: parseFloat(g.longitude) },
          rotation: g.rotation,
          widthScale: g.width_scale,
          cells: g.cells || {},
        })))
      } catch { }
    }
    fetchPlots()
    fetchGrids()

    // Setup real-time polling every 5 seconds
    const interval = setInterval(() => {
      fetchPlots()
      fetchGrids()
    }, 5000)

    return () => clearInterval(interval)
  }, [setGridOverlays])

  // Sync Data to Map Engine
  useEffect(() => {
    if (!map || !isLoaded || !pointsData) return
    if (!map.getSource('plots')) {
      map.addSource('plots', {
        type: 'geojson',
        data: pointsData,
        cluster: true,
        clusterMaxZoom: 14,
        clusterRadius: 50
      })
      map.addLayer({
        id: 'unclustered-point',
        type: 'circle',
        source: 'plots',
        filter: ['!', ['has', 'point_count']],
        paint: {
          'circle-color': '#111827',
          'circle-radius': 7,
          'circle-stroke-width': 2,
          'circle-stroke-color': '#3b82f6'
        }
      })
    } else {
      (map.getSource('plots') as any).setData(pointsData)
    }

    // 🖱️ Click Plot Handle
    const mapAny = map as any
    mapAny.off('click', 'unclustered-point')
    mapAny.on('click', 'unclustered-point', (e: any) => {
      if (e.features?.[0]) {
        useMapStore.getState().setSelectedFeature({ ...e.features[0].properties })
      }
    })
  }, [map, isLoaded, pointsData])

  // ─── Niche-grid lines layer ────────────────────────────────────────────────────
  useEffect(() => {
    if (!map || !isLoaded) return

    const geojson = buildLinesGeoJSON(gridOverlays)

    if (!map.getSource('niche-lines')) {
      map.addSource('niche-lines', { type: 'geojson', data: geojson as any })

      // Halo (wide invisible hit area + glow)
      map.addLayer({
        id: 'niche-lines-halo', type: 'line', source: 'niche-lines',
        paint: { 'line-color': ['get', 'color'], 'line-width': 18, 'line-opacity': 0.0 }
      })
      // Solid-colored line
      map.addLayer({
        id: 'niche-lines-base', type: 'line', source: 'niche-lines',
        paint: { 'line-color': ['get', 'color'], 'line-width': 5, 'line-blur': 0, 'line-opacity': 1 }
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

      // Click handling
      map.on('click', 'niche-lines-halo', (e: any) => {
        if (e.features?.[0]) {
          e.preventDefault()
          const id = String(e.features[0].properties.id)
          const state = useMapStore.getState()
          state.setSelectedGridId(state.selectedGridId === id ? null : id)
        }
      })
    } else {
      (map.getSource('niche-lines') as any).setData(geojson as any)
    }
  }, [map, isLoaded, gridOverlays])

  // ─── Exact Search Pinpoint ──────────────────────────────────────────────────
  useEffect(() => {
    if (!map || !isLoaded) return

    // Restore line opacity unconditionally as requested by user
    if (map.getLayer('niche-lines-base')) {
      map.setPaintProperty('niche-lines-base', 'line-opacity', 1.0)
      map.setPaintProperty('niche-lines-dots', 'circle-opacity', 1.0)
    }
  }, [map, isLoaded, selectedFeature])


  // Keep line width reactive to hover/selected
  const hoveredGridId = useMapStore(s => s.hoveredGridId)
  const selectedGridId = useMapStore(s => s.selectedGridId)
  useEffect(() => {
    if (!map || !isLoaded || !map.getLayer('niche-lines-base')) return
    map.setPaintProperty('niche-lines-base', 'line-width', [
      'case',
      ['==', ['get', 'id'], ['literal', selectedGridId ?? '']], 10,
      ['==', ['get', 'id'], ['literal', hoveredGridId ?? '']], 8,
      5
    ])
  }, [map, isLoaded, hoveredGridId, selectedGridId])

  // ─── URL Search Trigger ────────────────────────────────────────────────────────
  // Fires on initial load, AND again whenever grids arrive (retry logic)
  useEffect(() => {
    if (!isLoaded || !map) return
    const params = new URLSearchParams(window.location.search)
    const q = params.get('search')
    if (!q) return

    // If grids are already there, handle immediately; otherwise wait for them
    const hasGrids = gridOverlays.length > 0
    if (!hasGrids) return // Will re-run when gridOverlays changes

    if (urlSearchHandledRef.current) return // Don't re-run if already handled
    urlSearchHandledRef.current = true

    // Remove ?search= from URL so navigating away and back doesn't re-trigger
    const cleanUrl = window.location.pathname
    window.history.replaceState({}, '', cleanUrl)

    const fetchAndFly = async () => {
      try {
        const res = await fetch(`/cemetery/search-permits?q=${encodeURIComponent(q)}`)
        const results = await res.json()
        if (results && results.length > 0) {
          handleFlyTo(results[0])  // auto-open modal as expected
        }
      } catch (err) { /* silent */ }
    }
    // Small delay to ensure map has settled after grid load
    setTimeout(fetchAndFly, 600)
  }, [isLoaded, map, gridOverlays])

  // ─── Fly-to from search ────────────────────────────────────────────────────────
  const handleFlyTo = (res: any, skipAutoOpen = false) => {
    if (!map) return
    if (res.longitude && res.latitude) {
      map.flyTo({ center: [res.longitude, res.latitude], zoom: 19.5, speed: 1.2, essential: true })
    }

    // If it's a grid niche, ONLY open the full grid popup if it's NOT an exact pinpoint search
    if (res.grid_id && !res.is_exact) {
      useMapStore.getState().setSelectedGridId(String(res.grid_id))
    }

    // Sync to store for pulse animation + auto-open
    useMapStore.getState().setSelectedFeature({ ...res, fromSearch: true, skipAutoOpen })
  }

  const island: React.CSSProperties = {
    background: 'rgba(10, 16, 33, 0.95)',
    backdropFilter: 'blur(32px)',
    border: '1px solid rgba(255,255,255,0.08)',
    borderRadius: '18px',
    boxShadow: '0 20px 50px rgba(0,0,0,0.6)',
    padding: '16px 24px',
  }

  return (
    <div style={{ position: 'relative', width: '100%', height: '100vh', overflow: 'hidden' }}>
      {/* 🧩 MAP ENGINE - BOTTOM LAYER */}
      <MapView />

      {/* 📡 ENGINE STATUS & SEARCH BAR */}
      <div style={{ position: 'absolute', top: '24px', left: '24px', zIndex: 1000, pointerEvents: 'none' }}>
        <div style={{ ...island, display: 'flex', alignItems: 'center', gap: '20px', pointerEvents: 'auto', maxWidth: '600px' }}>
          <PlotSearch pointsData={pointsData} onSelect={handleFlyTo} />
          <a
            href="/support/manual#cemetery-map"
            title="How to use the map"
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: 'rgba(255,255,255,0.4)',
              transition: 'color 0.2s',
              cursor: 'pointer'
            }}
            onMouseOver={(e) => (e.currentTarget.style.color = '#3b82f6')}
            onMouseOut={(e) => (e.currentTarget.style.color = 'rgba(255,255,255,0.4)')}
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          </a>
        </div>
      </div>


      {/* 🔍 MAP CONTROLS (STYLE + ZOOM) */}
      <div style={{ position: 'fixed', bottom: '24px', right: '24px', zIndex: 1000, display: 'flex', alignItems: 'flex-end', gap: '12px' }}>
        {/* Map Style Toggle */}
        <div style={{ ...island, display: 'flex', gap: '8px', padding: '8px 12px' }}>
          {(['vector', 'satellite'] as const).map(s => {
            const currentStyle = useMapStore(state => state.mapStyle)
            return (
              <button
                key={s}
                onClick={() => useMapStore.getState().setMapStyle(s)}
                style={{
                  background: currentStyle === s ? '#3b82f6' : 'rgba(255,255,255,0.05)',
                  color: 'white',
                  border: 'none',
                  padding: '6px 12px',
                  borderRadius: '10px',
                  fontSize: '11px',
                  fontWeight: 700,
                  textTransform: 'uppercase',
                  cursor: 'pointer',
                  transition: 'all 0.2s',
                  minWidth: '80px'
                }}
              >
                {s === 'vector' ? 'Roads' : 'Satellite'}
              </button>
            )
          })}
        </div>

        <div style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
          <button onClick={() => useMapStore.getState().map?.zoomIn()} style={{ background: 'rgba(15, 23, 42, 0.8)', border: '1px solid rgba(255,255,255,0.1)', color: 'white', padding: '12px', borderRadius: '12px', cursor: 'pointer', display: 'flex' }}><ZoomIn size={18} /></button>
          <button onClick={() => useMapStore.getState().map?.zoomOut()} style={{ background: 'rgba(15, 23, 42, 0.8)', border: '1px solid rgba(255,255,255,0.1)', color: 'white', padding: '12px', borderRadius: '12px', cursor: 'pointer', display: 'flex' }}><ZoomOut size={18} /></button>
        </div>
      </div>

      {/* 🪟 NICHE GRID LAYER (Z-10) */}
      <div style={{ position: 'absolute', inset: 0, pointerEvents: 'none', zIndex: 10 }}>
        {gridOverlays.map((sec) => (
          <NicheGrid key={sec.id} section={sec} />
        ))}
      </div>

      {/* ℹ️ SELECTED PLOT OVERLAY (LEGEND) */}
      <AnimatePresence>
        {selectedFeature && !selectedFeature.fromSearch && (
          <div style={{ position: 'fixed', inset: 0, backgroundColor: 'rgba(0,0,0,0.4)', zIndex: 2000, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '20px' }}>
            <motion.div
              initial={{ scale: 0.9, opacity: 0 }}
              animate={{ scale: 1, opacity: 1 }}
              exit={{ scale: 0.9, opacity: 0 }}
              style={{
                width: '380px',
                backgroundColor: '#0f172a',
                borderRadius: '32px',
                border: '1px solid rgba(255, 255, 255, 0.1)',
                padding: '40px',
                boxShadow: '0 50px 100px -20px rgba(0, 0, 0, 1)',
                position: 'relative'
              }}
            >
              <button
                onClick={() => setSelectedFeature(null)}
                style={{ position: 'absolute', top: '24px', right: '24px', background: 'none', border: 'none', color: '#64748b', cursor: 'pointer' }}
              >
                <X size={20} />
              </button>

              <div style={{ marginBottom: '24px' }}>
                <div style={{ fontSize: '10px', color: '#3b82f6', fontWeight: '900', letterSpacing: '0.2em', textTransform: 'uppercase', marginBottom: '8px' }}>Plot Registry</div>
                <h2 style={{ fontSize: '42px', fontWeight: '900', color: 'white', margin: 0, letterSpacing: '-0.04em' }}>{selectedFeature.plot_code}</h2>
              </div>

              <div style={{ padding: '24px', backgroundColor: 'rgba(255,255,255,0.03)', borderRadius: '24px', border: '1px solid rgba(255,255,255,0.05)', marginBottom: '32px' }}>
                <div style={{ fontSize: '10px', color: '#64748b', fontWeight: 'bold', textTransform: 'uppercase', marginBottom: '8px' }}>Status / Occupant</div>
                <div style={{ fontSize: '18px', color: 'white', fontWeight: 'bold' }}>
                  {selectedFeature.deceased_name || 'Assignment: Available'}
                </div>
              </div>

              <button style={{ width: '100%', backgroundColor: 'white', color: '#0f172a', border: 'none', padding: '18px', borderRadius: '18px', fontWeight: 'bold', fontSize: '12px', textTransform: 'uppercase', letterSpacing: '0.1em', cursor: 'pointer' }}>
                Open Permitting Record
              </button>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  )
}

export default App
