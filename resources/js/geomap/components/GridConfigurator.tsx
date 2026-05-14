import { useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { X, Plus, Trash2, MapPin, Layout, Layers, Type, Hash, Save, AlertCircle } from 'lucide-react'
import { useMapStore } from '../store/useMapStore'

export const GridConfigurator = () => {
  const { 
    setIsGridConfigOpen, 
    gridOverlays, 
    setGridOverlays,
    map,
    setConfirmModal
  } = useMapStore()

  const [newSection, setNewSection] = useState({
    name: '',
    rows: 4,
    cols: 6,
    labelFormat: 'R{row}-C{col}'
  })

  const handleAddSection = () => {
    if (!map || !newSection.name) return
    const center = map.getCenter()
    
    const id = crypto.randomUUID()
    const section = {
      id,
      name: newSection.name,
      rows: newSection.rows,
      cols: newSection.cols,
      labelFormat: newSection.labelFormat,
      position: [center.lng, center.lat],
      rotation: 0,
      overrides: {}
    }

    const updated = [...gridOverlays, section]
    setGridOverlays(updated)
    localStorage.setItem('lgu_cemetery_grid_1', JSON.stringify(updated))
    setNewSection({ ...newSection, name: '' })
  }

  const handleDelete = (id: string) => {
    const updated = gridOverlays.filter(s => s.id !== id)
    setGridOverlays(updated)
    localStorage.setItem('lgu_cemetery_grid_1', JSON.stringify(updated))
  }

  return (
    <motion.div 
      initial={{ x: -400, opacity: 0 }}
      animate={{ x: 0, opacity: 1 }}
      exit={{ x: -400, opacity: 0 }}
      style={{
        position: 'fixed',
        top: '20px',
        left: '20px',
        bottom: '20px',
        width: '380px',
        zIndex: 2000000, // Above everything
        pointerEvents: 'auto'
      }}
    >
      <div style={{
        height: '100%',
        backgroundColor: 'rgba(15, 23, 42, 0.95)',
        backdropFilter: 'blur(32px)',
        border: '1px solid rgba(255, 255, 255, 0.1)',
        borderRadius: '32px',
        boxShadow: '0 50px 100px -20px rgba(0, 0, 0, 0.7)',
        display: 'flex',
        flexDirection: 'column',
        overflow: 'hidden',
        color: 'white'
      }}>
        {/* Header */}
        <div style={{ padding: '24px', borderBottom: '1px solid rgba(255,255,255,0.05)', display: 'flex', alignItems: 'center', justifyBetween: 'space-between' }}>
           <div style={{ flex: 1 }}>
              <h2 style={{ margin: 0, fontSize: '18px', fontWeight: '900', letterSpacing: '-0.02em' }}>Grid Configurator</h2>
              <p style={{ margin: 0, fontSize: '11px', color: '#64748b', fontWeight: 'bold', textTransform: 'uppercase' }}>Cemetery Spatial Manager</p>
           </div>
           <button 
             onClick={() => setIsGridConfigOpen(false)}
             style={{ background: 'rgba(255,255,255,0.05)', border: 'none', color: '#94a3b8', padding: '8px', borderRadius: '12px', cursor: 'pointer' }}
           >
             <X size={20} />
           </button>
        </div>

        <div style={{ flex: 1, overflowY: 'auto', padding: '24px' }}>
          {/* Create New Form */}
          <div style={{ marginBottom: '32px' }}>
             <h3 style={{ fontSize: '12px', color: '#3b82f6', textTransform: 'uppercase', letterSpacing: '0.1em', marginBottom: '16px', fontWeight: 'black' }}>Create New Section</h3>
             
             <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '6px' }}>
                   <label style={{ fontSize: '10px', fontWeight: 'bold', color: '#64748b', display: 'flex', alignItems: 'center', gap: '6px' }}>
                      <Type size={12} /> SECTION NAME
                   </label>
                   <input 
                      type="text" 
                      placeholder="e.g. Niche Wall A" 
                      value={newSection.name}
                      onChange={e => setNewSection({...newSection, name: e.target.value})}
                      style={{ background: 'rgba(255,255,255,0.03)', border: '1px solid rgba(255,255,255,0.1)', padding: '12px', borderRadius: '12px', color: 'white', fontSize: '13px', outline: 'none' }}
                   />
                </div>

                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' }}>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '6px' }}>
                      <label style={{ fontSize: '10px', fontWeight: 'bold', color: '#64748b', display: 'flex', alignItems: 'center', gap: '6px' }}>
                          <Layout size={12} /> ROWS
                      </label>
                      <input 
                          type="number" 
                          value={newSection.rows}
                          onChange={e => setNewSection({...newSection, rows: parseInt(e.target.value) || 1})}
                          style={{ background: 'rgba(255,255,255,0.03)', border: '1px solid rgba(255,255,255,0.1)', padding: '12px', borderRadius: '12px', color: 'white', fontSize: '13px', outline: 'none' }}
                      />
                    </div>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '6px' }}>
                      <label style={{ fontSize: '10px', fontWeight: 'bold', color: '#64748b', display: 'flex', alignItems: 'center', gap: '6px' }}>
                          <Layers size={12} /> COLS
                      </label>
                      <input 
                          type="number" 
                          value={newSection.cols}
                          onChange={e => setNewSection({...newSection, cols: parseInt(e.target.value) || 1})}
                          style={{ background: 'rgba(255,255,255,0.03)', border: '1px solid rgba(255,255,255,0.1)', padding: '12px', borderRadius: '12px', color: 'white', fontSize: '13px', outline: 'none' }}
                      />
                    </div>
                </div>

                <div style={{ display: 'flex', flexDirection: 'column', gap: '6px' }}>
                   <label style={{ fontSize: '10px', fontWeight: 'bold', color: '#64748b', display: 'flex', alignItems: 'center', gap: '6px' }}>
                      <Hash size={12} /> LABEL PATTERN
                   </label>
                   <input 
                      type="text" 
                      value={newSection.labelFormat}
                      onChange={e => setNewSection({...newSection, labelFormat: e.target.value})}
                      style={{ background: 'rgba(255,255,255,0.03)', border: '1px solid rgba(255,255,255,0.1)', padding: '12px', borderRadius: '12px', color: 'white', fontSize: '13px', outline: 'none' }}
                   />
                   <span style={{ fontSize: '9px', color: '#475569' }}>Use &#123;row&#125; and &#123;col&#125; placeholders</span>
                </div>

                <button 
                  onClick={handleAddSection}
                  style={{ 
                    marginTop: '8px',
                    backgroundColor: '#2563eb', 
                    color: 'white', 
                    border: 'none', 
                    padding: '16px', 
                    borderRadius: '16px', 
                    fontWeight: 'bold', 
                    fontSize: '12px', 
                    cursor: 'pointer',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: '10px',
                    boxShadow: '0 10px 20px -10px rgba(37,99,235,0.5)'
                  }}
                >
                  <MapPin size={16} /> ADD TO MAP CENTER
                </button>
             </div>
          </div>

          {/* List of sections */}
          <div>
             <h3 style={{ fontSize: '12px', color: '#64748b', textTransform: 'uppercase', letterSpacing: '0.1em', marginBottom: '16px', fontWeight: 'black' }}>Active Grid Sections</h3>
             
             {gridOverlays.length === 0 ? (
               <div style={{ padding: '24px', backgroundColor: 'rgba(255,255,255,0.02)', borderRadius: '20px', border: '1px dashed rgba(255,255,255,0.1)', textAlign: 'center' }}>
                  <p style={{ color: '#475569', fontSize: '12px', margin: 0 }}>No grids created yet.</p>
               </div>
             ) : (
               <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                  {gridOverlays.map(sec => (
                    <div key={sec.id} style={{ padding: '16px', backgroundColor: 'rgba(255,255,255,0.03)', borderRadius: '16px', border: '1px solid rgba(255,255,255,0.05)', display: 'flex', alignItems: 'center', gap: '12px' }}>
                       <div style={{ flex: 1 }}>
                          <div style={{ fontSize: '13px', fontWeight: 'bold' }}>{sec.name}</div>
                          <div style={{ fontSize: '10px', color: '#64748b' }}>{sec.rows}x{sec.cols} Grid • {sec.labelFormat}</div>
                       </div>
                       <button 
                         onClick={() => {
                           setConfirmModal({
                             message: 'Delete this block and all its niches permanently?',
                             itemName: `Block: ${sec.name}`,
                             onConfirm: () => handleDelete(sec.id)
                           });
                         }}
                         style={{ background: 'none', border: 'none', color: '#ef4444', padding: '8px', cursor: 'pointer', opacity: 0.6 }}
                       >
                         <Trash2 size={16} />
                       </button>
                    </div>
                  ))}
               </div>
             )}
          </div>
        </div>

        {/* Footer Info */}
        <div style={{ padding: '20px 24px', backgroundColor: 'rgba(37,99,235,0.05)', borderTop: '1px solid rgba(255,255,255,0.05)' }}>
            <div style={{ display: 'flex', gap: '12px', alignItems: 'flex-start' }}>
               <AlertCircle size={16} style={{ color: '#3b82f6', marginTop: '2px' }} />
               <p style={{ margin: 0, fontSize: '11px', color: '#94a3b8', lineHeight: '1.5' }}>
                  Use the <span style={{ color: 'white', fontWeight: 'bold' }}>Red Handle</span> on the map to drag entire grids into their final resting positions.
               </p>
            </div>
        </div>
      </div>
    </motion.div>
  )
}
