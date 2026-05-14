{{-- 
    Generic Delete Confirmation Modal
    Provides a system-themed confirmation for deletions.
--}}

<div class="modal-overlay" id="deleteModal" style="z-index: 10003;">
    <div class="modal" style="max-width: 440px; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <div class="modal-header" style="background: #991b1b; border-bottom: 1px solid #7f1d1d; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="color: #fff; font-size: 16px; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 8px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                Confirm Deletion
            </h3>
            <button class="modal-close" onclick="closeDeleteModal()" style="color: rgba(255,255,255,0.6); background: none; border: none; cursor: pointer; padding: 0; transition: color .15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 2.25rem 2rem; background: #ffffff;">
            <div style="width: 60px; height: 60px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14H6L5 6" />
                </svg>
            </div>
            
            <h4 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: .5rem; margin-top: 0;">Are you sure?</h4>
            <p style="font-size: 13.5px; color: #6b7280; line-height: 1.5; margin-bottom: 2rem;">
                <span id="deleteMessage">This action cannot be undone.</span><br>
                <strong id="deleteItemName" style="color: #111827; word-break: break-all; display: inline-block; margin-top: .5rem;"></strong>
            </p>

            <div style="display: flex; flex-direction: column; align-items: center; gap: .75rem;">
                <button type="button" class="btn-primary" id="confirmDeleteBtn" style="width: 100%; max-width: 280px; background: #ef4444; border-color: #ef4444; color: #fff; padding: .75rem; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px; border: none; transition: background .15s; justify-content: center; text-align: center;">Yes, delete it</button>
                <button type="button" class="btn-cancel" onclick="closeDeleteModal()" style="border: none; background: transparent; color: var(--text-2); font-size: 12px; cursor: pointer; text-decoration: underline;">Cancel</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Delete modal always stays white — never inherits dark mode body colour */
    #deleteModal .modal { background: #ffffff !important; border: none !important; }
    #deleteModal .modal-body { background: #ffffff !important; }
    #deleteModal h4 { color: #111827 !important; }
    #deleteModal #deleteMessage { color: #6b7280 !important; }
    #deleteModal #deleteItemName { color: #111827 !important; }
    #confirmDeleteBtn:hover { background: #dc2626 !important; }
</style>

<script>
let currentDeleteAction = null;

function openDeleteModal(message, itemName, action) {
    document.getElementById('deleteMessage').textContent = message || 'This action cannot be undone.';
    document.getElementById('deleteItemName').textContent = itemName || '';
    currentDeleteAction = action;
    document.getElementById('deleteModal').classList.add('open');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
    currentDeleteAction = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (currentDeleteAction) {
        this.disabled = true;
        this.textContent = 'Deleting...';
        this.style.opacity = '0.7';
        this.style.cursor = 'not-allowed';
        
        if (typeof currentDeleteAction === 'string') {
            const form = document.getElementById(currentDeleteAction);
            if (form) form.submit();
        } else if (typeof currentDeleteAction === 'function') {
            currentDeleteAction();
        }
    }
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>
