{{-- 
    Permit Renewal Confirmation Modal
    Provides a system-themed confirmation for permit renewals.
--}}

<div class="modal-overlay" id="renewModal" style="z-index: 10002;">
    <div class="modal" style="max-width: 440px;">
        <div class="modal-header" style="background: var(--navy); border-bottom-color: var(--navy-mid);">
            <h3 style="color: #ffffff;">Confirm Permit Renewal</h3>
            <button class="modal-close" onclick="closeRenewModal()" style="color: rgba(255,255,255,0.7);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 2.25rem 2rem;">
            <div style="width: 60px; height: 60px; background: var(--accent-bg); color: var(--navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 1011.66-8.58M3 3v9h9"/></svg>
            </div>
            
            <h4 style="font-size: 16px; font-weight: 700; color: var(--text-1); margin-bottom: .5rem;">Renew Permit?</h4>
            <p style="font-size: 13.5px; color: var(--text-2); line-height: 1.5; margin-bottom: 1.5rem;">
                You are about to renew the permit for <br>
                <strong id="renewDeceasedName" style="color: var(--text-1);">[Loading...]</strong> <br>
                <span id="renewPermitNo" style="font-family: var(--mono); font-size: 12px; opacity: 0.7;">[Loading...]</span>
            </p>

            <div style="background: var(--surface-2); border: 1px dashed var(--border); border-radius: 8px; padding: .85rem; margin-bottom: 2rem;">
                <div style="font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px;">Projected Extension</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-1);">+5 Years from Current Expiry</div>
            </div>

            <div style="display: flex; flex-direction: column; align-items: center; gap: .75rem;">
                <button type="button" class="btn-primary" id="confirmRenewBtn" style="width: 100%; max-width: 280px; background: var(--navy); border-color: var(--navy); padding: .75rem;">Confirm Renewal</button>
                <button type="button" class="btn-cancel" onclick="closeRenewModal()" style="border: none; background: transparent; color: var(--text-2); font-size: 12px; cursor: pointer; text-decoration: underline;">Wait, Cancel</button>
            </div>
        </div>
    </div>
</div>

<style>
    #renewModal .modal { background: #ffffff !important; border-radius: 12px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    #renewModal .modal-body { background: #ffffff !important; }
    html.dark #renewModal .modal-header { background: var(--surface-2) !important; border-bottom: 1px solid var(--border) !important; }
    html.dark #renewModal .modal-header h3 { color: var(--text-1); }
    html.dark #renewModal .modal-body { background: #1e2130 !important; }
    html.dark #renewModal style div[style*="background: var(--surface-2)"] { background: var(--surface-2) !important; border-color: var(--border) !important; }
    html.dark #renewModal .btn-cancel:hover { color: var(--text-1); }
    html.dark #renewModal .btn-primary { background: var(--accent) !important; border-color: var(--accent) !important; color: #fff !important; }
</style>

<script>
let currentRenewFormId = null;

function openRenewModal(permitNo, deceasedName, formId) {
    document.getElementById('renewPermitNo').textContent = permitNo;
    document.getElementById('renewDeceasedName').textContent = deceasedName;
    currentRenewFormId = formId;
    document.getElementById('renewModal').classList.add('open');
}

function closeRenewModal() {
    document.getElementById('renewModal').classList.remove('open');
    currentRenewFormId = null;
}

document.getElementById('confirmRenewBtn').addEventListener('click', function() {
    if (currentRenewFormId) {
        const form = document.getElementById(currentRenewFormId);
        if (form) {
            // Disable button to prevent double submission
            this.disabled = true;
            this.textContent = 'Processing...';
            form.submit();
        }
    }
});

// Close on ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeRenewModal();
});
</script>
