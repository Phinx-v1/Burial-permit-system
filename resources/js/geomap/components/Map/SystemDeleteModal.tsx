import React, { useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { useMapStore } from '../../store/useMapStore'

export const SystemDeleteModal = () => {
  const { confirmModal, setConfirmModal } = useMapStore()
  const [isDeleting, setIsDeleting] = useState(false)

  if (!confirmModal) return null

  const handleConfirm = async () => {
    setIsDeleting(true)
    try {
      await confirmModal.onConfirm()
    } finally {
      setIsDeleting(false)
      setConfirmModal(null)
    }
  }

  return (
    <div style={{ position: 'fixed', inset: 0, zIndex: 10003, backgroundColor: 'rgba(0,0,0,0.5)', backdropFilter: 'blur(4px)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
      <motion.div
        initial={{ opacity: 0, scale: 0.95, y: 10 }}
        animate={{ opacity: 1, scale: 1, y: 0 }}
        exit={{ opacity: 0, scale: 0.95, y: 10 }}
        transition={{ duration: 0.15 }}
        style={{
          width: '100%',
          maxWidth: '440px',
          background: '#1e293b', // Matches var(--surface) in dark mode
          borderRadius: '12px',
          overflow: 'hidden',
          boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.5)',
          border: '1px solid rgba(255,255,255,0.1)'
        }}
        onClick={(e) => e.stopPropagation()}
      >
        <div style={{ background: '#181b29', borderBottom: '1px solid rgba(255,255,255,0.05)', padding: '1rem 1.5rem', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
          <h3 style={{ color: '#f1f5f9', fontSize: '16px', fontWeight: 600, margin: 0 }}>Confirm Deletion</h3>
          <button onClick={() => setConfirmModal(null)} style={{ color: '#94a3b8', background: 'none', border: 'none', cursor: 'pointer', padding: 0 }}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <div style={{ textAlign: 'center', padding: '2.25rem 2rem' }}>
          <div style={{ width: '60px', height: '60px', background: 'rgba(239, 68, 68, 0.1)', color: '#ef4444', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 1.25rem' }}>
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
              <polyline points="3 6 5 6 21 6" />
              <path d="M19 6l-1 14H6L5 6" />
            </svg>
          </div>
          
          <h4 style={{ fontSize: '16px', fontWeight: 700, color: '#f1f5f9', marginBottom: '.5rem', marginTop: 0 }}>Are you sure?</h4>
          <p style={{ fontSize: '13.5px', color: '#94a3b8', lineHeight: 1.5, marginBottom: '2rem' }}>
            <span>{confirmModal.message || 'This action cannot be undone.'}</span><br />
            <strong style={{ color: '#f1f5f9', wordBreak: 'break-all', display: 'inline-block', marginTop: '.5rem' }}>{confirmModal.itemName}</strong>
          </p>

          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '.75rem' }}>
            <button
              type="button"
              onClick={handleConfirm}
              disabled={isDeleting}
              style={{
                width: '100%', maxWidth: '280px', background: '#ef4444', borderColor: '#ef4444', color: '#fff',
                padding: '.75rem', borderRadius: '8px', fontWeight: 600, cursor: isDeleting ? 'not-allowed' : 'pointer',
                fontSize: '13px', border: 'none', transition: 'background .15s',
                opacity: isDeleting ? 0.7 : 1
              }}
              onMouseEnter={(e) => e.currentTarget.style.background = '#dc2626'}
              onMouseLeave={(e) => e.currentTarget.style.background = '#ef4444'}
            >
              {isDeleting ? 'Deleting...' : 'Yes, delete it'}
            </button>
            <button
              type="button"
              onClick={() => setConfirmModal(null)}
              style={{ border: 'none', background: 'transparent', color: '#94a3b8', fontSize: '12px', cursor: 'pointer', textDecoration: 'underline' }}
            >
              Cancel
            </button>
          </div>
        </div>
      </motion.div>
    </div>
  )
}
