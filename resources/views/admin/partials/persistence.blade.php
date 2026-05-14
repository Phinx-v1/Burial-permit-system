{{--
Persistence Partial
Provides:
1. Unsaved changes warning
2. LocalStorage auto-save (Drafts)
3. Draft restoration toast
--}}

<style>
    #draftToast {
        position: fixed;
        bottom: 6rem; /* Clear the bottom floating dock */
        left: 2rem;
        z-index: 10000;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        display: none;
        align-items: center;
        gap: 1rem;
        padding: .8rem 1.25rem;
        animation: draftSlideIn .4s cubic-bezier(0.34, 1.56, 0.64, 1);
        transition: transform .3s, opacity .3s, left .2s;
    }

    @keyframes draftSlideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    #draftToast.show {
        display: flex;
    }

    .draft-msg {
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
    }

    .draft-actions {
        display: flex;
        gap: .5rem;
    }

    .btn-restore {
        background: #6366f1;
        color: #fff;
        border: none;
        padding: .4rem .8rem;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-ignore {
        background: #f1f5f9;
        color: #64748b;
        border: none;
        padding: .4rem .8rem;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    html.dark #draftToast {
        background: #1e2930;
        border-color: #2d3148;
        color: #e2e8f0;
    }

    html.dark .draft-msg {
        color: #e2e8f0;
    }

    html.dark .btn-ignore {
        background: #181b29;
        color: #94a3b8;
    }

    #unSavedModal .modal {
        max-width: 400px;
    }

    .leave-icon {
        width: 50px;
        height: 50px;
        background: #fff1f2;
        color: #ef4444;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    html.dark .leave-icon {
        background: #450a0a;
    }

    /* Base Modal Styles (Self-contained for compatibility) */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 999999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-overlay.open {
        display: flex;
    }

    .modal-overlay .modal {
        background: #fff;
        border-radius: 14px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        animation: modalIn .2s ease-out;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .modal-overlay .btn-cancel {
        padding: .6rem 1rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-family: sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        background: #fff;
        cursor: pointer;
        transition: all .15s;
    }

    .modal-overlay .btn-primary {
        padding: .6rem 1rem;
        border-radius: 8px;
        border: none;
        font-family: sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        background: #ef4444;
        cursor: pointer;
        transition: all .15s;
    }

    .modal-overlay h3 {
        font-family: sans-serif;
        font-weight: 700;
        color: #0f172a;
    }

    .modal-overlay p {
        font-family: sans-serif;
    }

    html.dark .modal-overlay {
        background: rgba(0, 0, 0, 0.8);
    }

    html.dark .modal-overlay .modal {
        background: #1e2130;
        border: 1px solid #2d3148;
    }

    html.dark .modal-overlay h3 {
        color: #f1f5f9;
    }

    html.dark .modal-overlay p {
        color: #94a3b8;
    }

    html.dark .modal-overlay .btn-cancel {
        background: #252840;
        border-color: #374151;
        color: #cbd5e1;
    }
</style>

<div id="draftToast">
    <div class="draft-msg">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            style="vertical-align:middle;margin-right:5px;color:#6366f1">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
        </svg>
        Unsaved progress found
    </div>
    <div class="draft-actions">
        <button type="button" class="btn-restore" onclick="lguPersistence.restore()">Restore</button>
        <button type="button" class="btn-ignore" onclick="lguPersistence.dismiss()">Dismiss</button>
    </div>
</div>

{{-- CUSTOM LEAVE MODAL --}}
<div class="modal-overlay" id="unSavedModal" style="z-index: 10001;">
    <div class="modal" style="text-align: center; padding: 2rem;">
        <div class="leave-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                <line x1="12" y1="9" x2="12" y2="13" />
                <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
        </div>
        <h3 style="margin-bottom: .5rem; font-size: 18px;">Unsaved Changes</h3>
        <p style="color: #64748b; font-size: 14px; margin-bottom: 2rem;">You have unfinished work on this page. If you
            leave now, your changes might not be fully synchronized.</p>

        <div style="display: flex; flex-direction: column; align-items: center; gap: .75rem; margin-top: 2rem;">
            <button type="button" class="btn-primary" onclick="lguPersistence.leave()"
                style="width: 100%; max-width: 280px; background: #ef4444; border-color: #ef4444; padding: .75rem; text-align: center;">Discard
                & Leave</button>
            <button type="button" class="btn-cancel" onclick="lguPersistence.stay()"
                style="border: none; background: transparent; color: #94a3b8; font-size: 13px; cursor: pointer; text-decoration: underline; text-align: center;">Stay
                & Continue</button>
        </div>
    </div>
</div>

<script>
    const lguPersistence = (() => {
        const STORAGE_KEY_PREFIX = 'lgu_draft_';
        const currentPath = window.location.pathname;
        const storageKey = STORAGE_KEY_PREFIX + btoa(currentPath).replace(/=/g, '');
        let isDirty = false;
        let saveTimeout = null;
        let pendingUrl = null;
        let lastActiveElement = null;
        let lastOpenModals = [];

        // We only care about POST forms (adding/changing data)
        const getTargetForms = () => Array.from(document.querySelectorAll('form[method="POST"]'));

        const saveDraft = () => {
            const forms = getTargetForms();
            const data = forms.map(form => {
                const formData = new FormData(form);
                return Object.fromEntries(formData.entries());
            });

            // Remove sensitive or irrelevant fields
            data.forEach(d => {
                delete d._token;
                delete d._method;
                delete d.password;
                // Filter out empty values to keep storage clean
                Object.keys(d).forEach(k => { if (!d[k]) delete d[k]; });
            });

            // Only save if there's actually some data
            if (data.some(d => Object.keys(d).length > 0)) {
                localStorage.setItem(storageKey, JSON.stringify(data));
            } else {
                localStorage.removeItem(storageKey);
            }
        };

        const snapshotForm = (form) => {
            const fd = new FormData(form);
            const obj = Object.fromEntries(fd.entries());
            delete obj._token;
            delete obj._method;
            delete obj.password;
            return JSON.stringify(obj);
        };

        let initialSnapshots = [];

        const init = () => {
            const forms = getTargetForms();
            if (forms.length === 0) return;

            // Capture original state immediately on load
            initialSnapshots = forms.map(f => snapshotForm(f));

            // Move modal and toast to body root to avoid sidebar clipping
            const modal = document.getElementById('unSavedModal');
            const toast = document.getElementById('draftToast');
            if (modal) document.body.appendChild(modal);
            if (toast) document.body.appendChild(toast);

            // 1. Listen for changes — compare against snapshot each time
            const formSnapshots = new WeakMap();
            document.addEventListener('input', (e) => {
                const form = e.target.closest('form[method="POST"]');
                if (!form) return;
                clearTimeout(saveTimeout);
                // Lazily capture baseline for forms not present at init (e.g. modal forms)
                if (!formSnapshots.has(form)) {
                    // Snapshot BEFORE this input is applied by reading prior value
                    // We defer to next tick so the input value is already updated, then compare
                    formSnapshots.set(form, null); // mark as pending
                    const prevValue = e.target.defaultValue ?? e.target.value;
                    // Build snapshot from the form's server-rendered defaults
                    const fd = new FormData(form);
                    const obj = Object.fromEntries(fd.entries());
                    // The current field change hasn't been applied to defaultValue yet
                    // so we restore it to get the true "original" state
                    const savedCurrent = e.target.value;
                    e.target.value = e.target.defaultValue || '';
                    const fd2 = new FormData(form);
                    const obj2 = Object.fromEntries(fd2.entries());
                    delete obj2._token; delete obj2._method; delete obj2.password;
                    e.target.value = savedCurrent;
                    formSnapshots.set(form, JSON.stringify(obj2));
                }
                // Compare current state to baseline
                const fd = new FormData(form);
                const obj = Object.fromEntries(fd.entries());
                delete obj._token; delete obj._method; delete obj.password;
                const current = JSON.stringify(obj);
                const baseline = formSnapshots.get(form);
                isDirty = baseline !== null && current !== baseline;
                if (isDirty) saveTimeout = setTimeout(saveDraft, 1000);
            });

            // 2. Warn before navigation/unload (Standard browser fallback)
            window.addEventListener('beforeunload', (e) => {
                if (isDirty) {
                    const msg = "You have unsaved changes. Are you sure you want to leave?";
                    e.returnValue = msg;
                    return msg;
                }
            });

            // 3. State Tracking: Capture state BEFORE clicks can close modals
            document.addEventListener('mousedown', (e) => {
                if (!isDirty) return;
                lastActiveElement = document.activeElement;

                // Capture all elements that look like an active modal
                const modals = document.querySelectorAll('.open, .modal-overlay.open, .em-overlay.open, .modal.open');
                lastOpenModals = Array.from(modals)
                    .filter(el => el.id && el.id !== 'unSavedModal')
                    .map(el => el.id);
            }, true);

            // 4. Intercept internal navigation clicks
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link || !isDirty) return;

                const href = link.getAttribute('href');
                // Ignore hash-only links, javascript: links, and logout
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.closest('form') || link.classList.contains('no-warn')) return;

                // Only intercept if its an actual navigation away from current state
                e.preventDefault();
                pendingUrl = href;
                const warningModal = document.getElementById('unSavedModal');
                if (warningModal) {
                    warningModal.style.display = 'flex';
                    warningModal.classList.add('open');
                } else {
                    console.error('UnsavedChanges: Modal element missing');
                }
            });

            // Add backdrop click to stay
            document.getElementById('unSavedModal').addEventListener('click', function (e) {
                if (e.target === this) lguPersistence.stay();
            });

            // 5. Clear on success
            forms.forEach(form => {
                form.addEventListener('submit', () => {
                    isDirty = false;
                    localStorage.removeItem(storageKey);
                });
            });

            // 5. Check for existing draft
            const savedData = localStorage.getItem(storageKey);
            if (savedData) {
                try {
                    const parsed = JSON.parse(savedData);
                    if (parsed.some(d => Object.keys(d).length > 0)) {
                        document.getElementById('draftToast').classList.add('show');
                    }
                } catch (e) { console.error('Draft error', e); }
            }
        };

        const restore = () => {
            const savedData = localStorage.getItem(storageKey);
            if (!savedData) return;

            try {
                const data = JSON.parse(savedData);
                const forms = getTargetForms();

                data.forEach((formData, idx) => {
                    const form = forms[idx];
                    if (!form) return;

                    for (const [name, value] of Object.entries(formData)) {
                        const input = form.querySelector(`[name="${name}"]`);
                        if (!input || input.type === 'file') continue;

                        if (input.type === 'checkbox' || input.type === 'radio') {
                            input.checked = (String(input.value) === String(value));
                        } else {
                            input.value = value;
                        }
                        // Trigger input event to let other scripts (like fee totals) know
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                });
                isDirty = true;

                // Auto-open containing modal if any
                const affectedModals = new Set();
                data.forEach((formData, idx) => {
                    const form = forms[idx];
                    if (form) {
                        const modal = form.closest('.modal, .modal-overlay, .em-overlay, [id*="Modal"]');
                        if (modal) affectedModals.add(modal);
                    }
                });
                affectedModals.forEach(m => m.classList.add('open'));

                dismiss();
            } catch (e) { console.error('Restore failed', e); }
        };

        const dismiss = () => {
            document.getElementById('draftToast').classList.remove('show');
        };

        const stay = () => {
            pendingUrl = null;
            const warningModal = document.getElementById('unSavedModal');
            if (warningModal) {
                warningModal.style.display = 'none';
                warningModal.classList.remove('open');
            }

            // Restore focus
            if (lastActiveElement && typeof lastActiveElement.focus === 'function') {
                lastActiveElement.focus();
            }

            // Robust Restoration: Re-open any modal containing our targeted forms
            const forms = getTargetForms();
            forms.forEach(form => {
                const modal = form.closest('.modal, .modal-overlay, .em-overlay, .open, [id*="Modal"]');
                if (modal) modal.classList.add('open');
            });

            // Secondary Restoration: Any modals that were explicitly tracked by ID
            setTimeout(() => {
                if (lastOpenModals.length > 0) {
                    lastOpenModals.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.classList.add('open');
                    });
                    lastOpenModals = [];
                }
            }, 50);
        };

        const leave = () => {
            isDirty = false; // Prevent beforeunload trip
            if (pendingUrl) window.location.href = pendingUrl;
        };

        // Auto-init
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

        return { restore, dismiss, saveDraft, stay, leave };
    })();
</script>