import { create } from 'zustand'
// Using any for the Map object to resolve IDE typing conflicts

export interface GridSection {
  id: string
  name: string
  rows: number
  cols: number
  labelFormat: string
  color: string
  cells: Record<string, { status: string; label?: string; notes?: string; deceased_id?: number | null; deceased_name?: string | null; permit_number?: string | null; permit_status?: string | null }>
  // Line endpoints (primary representation)
  lineStart?: { lat: number; lng: number }
  lineEnd?:   { lat: number; lng: number }
  // Legacy / derived midpoint
  position?: { lat: number; lng: number }
  rotation?: number
  widthScale?: number
}

// Pending config state when user has drawn a line but not yet confirmed
export interface PendingGrid {
  lineStart: { lat: number; lng: number }
  lineEnd:   { lat: number; lng: number }
  bearing: number
  distanceM: number
}

export interface ConfirmState {
  message: string;
  itemName: string;
  onConfirm: () => void | Promise<void>;
}

interface MapState {
  map: any | null
  setMap: (map: any | null) => void
  isLoaded: boolean
  setIsLoaded: (isLoaded: boolean) => void
  zoom: number
  setZoom: (zoom: number) => void
  center: { lng: number; lat: number }
  setCenter: (center: { lng: number; lat: number }) => void

  // Drawing State (Transient)
  drawingStartPos: { lat: number; lng: number } | null
  setDrawingStartPos: (pos: { lat: number; lng: number } | null) => void

  // Pending config modal state
  pendingGrid: PendingGrid | null
  setPendingGrid: (pg: PendingGrid | null) => void

  // Global Confirmation Modal State
  confirmModal: ConfirmState | null
  setConfirmModal: (modal: ConfirmState | null) => void

  // View Settings
  mapStyle: 'vector' | 'satellite'
  setMapStyle: (style: 'vector' | 'satellite') => void

  // Management Mode
  editMode: 'view' | 'create-plot' | 'create-grid' | 'edit-item'
  setEditMode: (mode: 'view' | 'create-plot' | 'create-grid' | 'edit-item') => void

  // Interaction State
  hoveredGridId: string | null
  selectedGridId: string | null
  selectedFeature: any | null
  setHoveredGridId: (id: string | null) => void
  setSelectedGridId: (id: string | null) => void
  setSelectedFeature: (feature: any | null) => void
  filterStatus: 'all' | 'occupied' | 'available'
  setFilterStatus: (status: 'all' | 'occupied' | 'available') => void

  // Niche Grid Overlay State
  gridOverlays: GridSection[]
  isGridConfigOpen: boolean
  setIsGridConfigOpen: (isOpen: boolean) => void
  setGridOverlays: (overlays: GridSection[]) => void
  addGridSection: (section: GridSection) => Promise<void>
  removeGridSection: (id: string) => Promise<void>
  updateGridStatus: (secId: string, key: string, status: string) => void
  updateGridCell: (secId: string, key: string, data: { label?: string; status?: string; notes?: string; deceased_id?: number | null; deceased_name?: string | null; permit_number?: string | null; permit_status?: string | null }) => void
}

export const useMapStore = create<MapState>((set, get) => ({
  map: null,
  setMap: (map: any | null) => set({ map }),
  isLoaded: false,
  setIsLoaded: (isLoaded) => set({ isLoaded }),
  zoom: 19,
  setZoom: (zoom) => set({ zoom }),
  center: { lng: 125.714882, lat: 7.370672 },
  setCenter: (center) => set({ center }),

  drawingStartPos: null,
  setDrawingStartPos: (drawingStartPos) => set({ drawingStartPos }),

  pendingGrid: null,
  setPendingGrid: (pendingGrid) => set({ pendingGrid }),

  confirmModal: null,
  setConfirmModal: (confirmModal) => set({ confirmModal }),

  mapStyle: 'satellite',
  setMapStyle: (mapStyle) => set({ mapStyle }),

  editMode: 'view',
  setEditMode: (editMode) => set({ editMode }),

  hoveredGridId: null,
  selectedGridId: null,
  selectedFeature: null,
  setHoveredGridId: (id) => set({ hoveredGridId: id }),
  setSelectedGridId: (id) => set({ selectedGridId: id }),
  setSelectedFeature: (feature) => set({ selectedFeature: feature }),
  filterStatus: 'all',
  setFilterStatus: (filterStatus) => set({ filterStatus }),

  gridOverlays: [],
  isGridConfigOpen: false,
  setIsGridConfigOpen: (isGridConfigOpen) => set({ isGridConfigOpen }),
  setGridOverlays: (gridOverlays) => set({ gridOverlays }),

  addGridSection: async (section) => {
    try {
      const csrf = (document.querySelector('meta[name="csrf-token"]') as any)?.content
      const res = await fetch('/niche-grids', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({
          name:         section.name,
          rows:         section.rows,
          cols:         section.cols,
          label_format: section.labelFormat,
          color:        section.color || '#ef4444',
          start_lat:    section.lineStart?.lat,
          start_lng:    section.lineStart?.lng,
          end_lat:      section.lineEnd?.lat,
          end_lng:      section.lineEnd?.lng,
          rotation:     section.rotation    || 0,
          width_scale:  section.widthScale  || 1.0,
          cells:        section.cells
        })
      })
      if (!res.ok) throw new Error('API returned ' + res.status)
      const data = await res.json()
      // Normalise the saved record back into our shape
      const saved: GridSection = {
        ...section,
        id: String(data.id),
        color: data.color || '#ef4444',
        lineStart: data.start_lat != null ? { lat: data.start_lat, lng: data.start_lng } : section.lineStart,
        lineEnd:   data.end_lat   != null ? { lat: data.end_lat,   lng: data.end_lng   } : section.lineEnd,
        position:  { lat: data.latitude, lng: data.longitude }
      }
      set((state) => ({ gridOverlays: [...state.gridOverlays, saved] }))
    } catch (e) { console.error('Failed to save grid', e) }
  },

  removeGridSection: async (id) => {
    try {
      const csrf = (document.querySelector('meta[name="csrf-token"]') as any)?.content
      await fetch(`/niche-grids/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf } })
      set((state) => ({ gridOverlays: state.gridOverlays.filter(g => String(g.id) !== String(id)) }))
    } catch (e) { console.error('Failed to delete grid', e) }
  },

  updateGridStatus: (secId, key, status) => {
    get().updateGridCell(secId, key, { status })
  },

  updateGridCell: async (secId, key, data) => {
    const s = get().gridOverlays.find(g => String(g.id) === String(secId))
    if (!s) return

    const newCells = { ...(s.cells || {}) }
    if (data.status === 'empty') {
      delete newCells[key]
    } else {
      newCells[key] = { ...(newCells[key] || { status: 'available' }), ...data }
    }

    set((state) => ({
      gridOverlays: state.gridOverlays.map(g => String(g.id) === String(secId) ? { ...g, cells: newCells } : g)
    }))

    try {
      const csrf = (document.querySelector('meta[name="csrf-token"]') as any)?.content
      const res = await fetch(`/niche-grids/${secId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ cells: newCells })
      })
      if (!res.ok) throw new Error(`API returned ${res.status}: ${await res.text()}`)
    } catch (e) { console.error('Failed to update grid cell', e) }
  }
}))
