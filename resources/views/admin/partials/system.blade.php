<style>
    :root {
        --navy: #0f1e3d;
        --navy-mid: #1a2f5e;
        --navy-light: #243459;
        --accent: #3b82f6;
        --accent-bg: #eff6ff;
        --red: #ef4444;
        --amber: #f59e0b;
        --green: #10b981;
        --surface: #ffffff;
        --surface-2: #f8fafc;
        --border: #e2e8f0;
        --border-2: #f1f5f9;
        --text-1: #0f172a;
        --text-2: #475569;
        --text-3: #94a3b8;
        --mono: 'DM Mono', monospace;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--surface-2);
        color: var(--text-1);
        display: flex;
        min-height: 100vh;
        -webkit-font-smoothing: antialiased;
    }

    .main {
        width: 100%;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    /* ── TOPBAR ── */
    .topbar {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.75rem;
    }

    .topbar-left {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .topbar-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-1);
        letter-spacing: -.01em;
    }

    .topbar-date {
        font-size: 11px;
        color: var(--text-3);
        font-weight: 400;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .topbar-back {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        color: var(--text-3);
        text-decoration: none;
        transition: color .15s;
    }

    .topbar-back:hover {
        color: var(--navy);
    }

    .topbar-sep {
        color: var(--border);
        font-size: 16px;
    }

    .role-pill {
        font-family: var(--mono);
        font-size: 10px;
        font-weight: 500;
        color: var(--accent);
        background: var(--accent-bg);
        border: 1px solid #bfdbfe;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    /* ── CONTENT ── */
    .content {
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* ── BADGES ── */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
        font-family: var(--mono);
        letter-spacing: .03em;
        white-space: nowrap;
    }

    .badge-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .badge-yellow {
        background: #fef9c3;
        color: #854d0e;
    }

    .badge-yellow .badge-dot {
        background: #ca8a04;
    }

    .badge-green {
        background: #dcfce7;
        color: #166534;
    }

    .badge-green .badge-dot {
        background: #16a34a;
    }

    .badge-blue {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-blue .badge-dot {
        background: #3b82f6;
    }

    .badge-red {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-red .badge-dot {
        background: #ef4444;
    }

    .badge-orange {
        background: #fff7ed;
        color: #9a3412;
    }

    .badge-orange .badge-dot {
        background: #f97316;
    }

    /* ── PANEL ── */
    .panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .panel-head {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid var(--border-2);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .panel-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-1);
        letter-spacing: -.01em;
    }

    .panel-sub {
        font-size: 11px;
        color: var(--text-3);
        font-family: var(--mono);
        margin-top: 2px;
    }

    /* ── BUTTONS ── */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: .45rem .9rem;
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid transparent;
        transition: all .15s;
        background: none;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-primary {
        background: var(--navy);
        color: #fff;
        border-color: var(--navy);
    }

    .btn-primary:hover {
        background: var(--navy-light);
    }

    .btn-ghost {
        background: rgba(255, 255, 255, .1);
        color: #fff;
        border-color: rgba(255, 255, 255, .2);
    }

    .btn-ghost:hover {
        background: rgba(255, 255, 255, .18);
        border-color: rgba(255, 255, 255, .4);
    }

    .btn-danger {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }

    .btn-danger:hover {
        background: #fecaca;
    }

    .btn-warn {
        background: #fff1f2;
        color: #b91c1c;
        border-color: #fca5a5;
    }

    .btn-warn:hover {
        background: #fee2e2;
    }

    .btn-success {
        background: #dcfce7;
        color: #166534;
        border-color: #a7f3d0;
    }

    .btn-success:hover {
        background: #bbf7d0;
    }

    .btn-info {
        background: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
    }

    .btn-info:hover {
        background: #bfdbfe;
    }

    .btn-xs {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 6px;
        border: 1px solid var(--border);
        font-family: 'DM Sans', sans-serif;
        font-size: 11px;
        font-weight: 500;
        color: var(--text-2);
        background: var(--surface);
        cursor: pointer;
        text-decoration: none;
        transition: all .15s;
        white-space: nowrap;
    }

    .btn-xs:hover {
        border-color: var(--navy);
        color: var(--navy);
        background: #f8fafc;
    }

    .btn-xs.danger {
        border-color: #fca5a5;
        color: #dc2626;
        background: #fff5f5;
    }

    .btn-xs.danger:hover {
        background: #fee2e2;
        border-color: var(--red);
    }

    /* ── TABLE ── */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        font-size: 10px;
        font-weight: 500;
        color: var(--text-3);
        text-transform: uppercase;
        letter-spacing: .07em;
        padding: .5rem 1rem;
        text-align: left;
        background: var(--surface-2);
        font-family: var(--mono);
        white-space: nowrap;
    }

    td {
        font-size: 13px;
        color: var(--text-2);
        padding: .7rem 1rem;
        border-top: 1px solid var(--border-2);
        vertical-align: middle;
    }

    tbody tr:hover td {
        background: #f8fafc;
    }

    tr.row-expired td {
        background: #fff5f5;
        border-top-color: #fecaca;
    }

    tr.row-expired:hover td {
        background: #fef2f2;
    }

    tr.row-expired td:first-child {
        border-left: 3px solid var(--red);
    }

    .permit-mono {
        font-family: var(--mono);
        font-size: 11px;
        font-weight: 500;
        color: var(--navy);
        letter-spacing: .02em;
    }

    /* ── FORM CONTROLS ── */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-2);
        text-transform: uppercase;
        letter-spacing: .07em;
        font-family: var(--mono);
    }

    .form-control {
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        color: var(--text-1);
        padding: .5rem .75rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        width: 100%;
        background: var(--surface);
    }

    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px #3b82f615;
    }

    .form-divider {
        font-size: 10px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: .1em;
        padding: .5rem 0 .25rem;
        border-bottom: 1px solid var(--border-2);
        margin-top: .25rem;
        font-family: var(--mono);
    }

    /* ── MODAL ── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 30, 61, 0.4);
        /* less opaque */
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        overflow-y: auto;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .modal-overlay.open {
        display: flex;
    }

    .modal {
        background: var(--surface);
        border-radius: 14px;
        width: 100%;
        max-width: 560px;
        box-shadow: 0 24px 64px rgba(0, 0, 0, .18);
        overflow: hidden;
        animation: modalIn .2s cubic-bezier(.34, 1.3, .64, 1);
        margin: auto;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: translateY(-12px) scale(.97);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .modal-header {
        padding: 1.1rem 1.25rem;
        background: var(--navy);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h3 {
        font-size: 15px;
        font-weight: 600;
        color: #fff;
        letter-spacing: -.01em;
    }

    .modal-close {
        background: none;
        border: none;
        cursor: pointer;
        color: rgba(255, 255, 255, .5);
        padding: 4px;
        line-height: 1;
        transition: color .15s;
    }

    .modal-close:hover {
        color: #fff;
    }

    .modal-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: .85rem;
        max-height: 72vh;
        overflow-y: auto;
    }

    .modal-footer {
        padding: .9rem 1.25rem;
        border-top: 1px solid var(--border-2);
        display: flex;
        justify-content: flex-end;
        gap: .6rem;
        background: var(--surface-2);
    }

    .btn-cancel {
        padding: .5rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border);
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        color: var(--text-2);
        background: var(--surface);
        cursor: pointer;
        transition: all .15s;
    }

    .btn-cancel:hover {
        border-color: var(--navy);
        color: var(--navy);
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: .5rem 1.1rem;
        border-radius: 8px;
        border: none;
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        background: var(--navy);
        cursor: pointer;
        transition: background .15s;
    }

    .btn-submit:hover {
        background: var(--navy-light);
    }

    /* ── TOAST ── */
    .toast {
        position: fixed;
        top: 1.1rem;
        right: 1.1rem;
        z-index: 9999;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, .12);
        width: 300px;
        overflow: hidden;
        transform: translateX(calc(100% + 1.5rem));
        transition: transform .4s cubic-bezier(.34, 1.4, .64, 1);
        pointer-events: none;
    }

    .toast.show {
        transform: translateX(0);
        pointer-events: auto;
    }

    .toast-body {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .9rem 1rem;
    }

    .toast-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .toast-icon.green {
        background: #dcfce7;
    }

    .toast-icon.red {
        background: #fee2e2;
    }

    .toast-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-1);
    }

    .toast-msg {
        font-size: 12px;
        color: var(--text-2);
        margin-top: 1px;
    }

    .toast-bar {
        height: 3px;
        transform-origin: left;
        animation: drainToast 5s linear forwards;
    }

    .toast-bar.green {
        background: var(--green);
    }

    .toast-bar.red {
        background: var(--red);
    }

    @keyframes drainToast {
        from {
            transform: scaleX(1);
        }

        to {
            transform: scaleX(0);
        }
    }

    /* ── PAGINATION ── */
    .pager {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .65rem 1.25rem;
        border-top: 1px solid var(--border-2);
    }

    .pager-info {
        font-size: 11px;
        color: var(--text-3);
        font-family: var(--mono);
    }

    .pager-btns {
        display: flex;
        align-items: center;
        gap: 2px;
    }

    .pager-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 26px;
        height: 26px;
        padding: 0 6px;
        border-radius: 5px;
        border: 1px solid var(--border);
        font-family: var(--mono);
        font-size: 11px;
        color: var(--text-2);
        text-decoration: none;
        background: var(--surface);
        cursor: pointer;
        transition: all .15s;
        line-height: 1;
    }

    .pager-btn:hover {
        border-color: var(--navy);
        color: var(--navy);
    }

    .pager-btn.active {
        background: var(--navy);
        color: #fff;
        border-color: var(--navy);
    }

    .pager-btn.disabled {
        color: var(--border);
        cursor: not-allowed;
        pointer-events: none;
    }

    /* ── ANIMATIONS ── */
    .fade-up {
        opacity: 0;
        transform: translateY(12px);
        animation: fadeUp .4s cubic-bezier(.4, 0, .2, 1) forwards;
    }

    @keyframes fadeUp {
        to {
            opacity: 1;
            transform: none;
        }
    }

    .d1 {
        animation-delay: .04s
    }

    .d2 {
        animation-delay: .08s
    }

    .d3 {
        animation-delay: .12s
    }

    .d4 {
        animation-delay: .16s
    }

    .d5 {
        animation-delay: .20s
    }

    .d6 {
        animation-delay: .24s
    }

    /* ── DARK MODE ── */
    html.dark body {
        background: #0f1117 !important;
        color: #e2e8f0 !important;
    }

    html.dark .topbar {
        background: #1a1d27 !important;
        border-bottom-color: #2d3148 !important;
    }

    html.dark .topbar-title {
        color: #e2e8f0 !important;
    }

    html.dark .topbar-date {
        color: #64748b !important;
    }

    html.dark .topbar-back {
        color: #94a3b8 !important;
    }

    html.dark .topbar-back:hover {
        color: #e2e8f0 !important;
    }

    html.dark .topbar-sep {
        color: #334155 !important;
    }

    html.dark .role-pill {
        background: #1e2d6b !important;
        color: #818cf8 !important;
        border-color: #3730a3 !important;
    }

    html.dark .content {
        background: #0f1117 !important;
    }

    html.dark .panel {
        background: #1e2130 !important;
        border-color: #2d3148 !important;
    }

    html.dark .panel-head {
        background: #181b29 !important;
        border-bottom-color: #2d3148 !important;
    }

    html.dark .panel-title {
        color: #e2e8f0 !important;
    }

    html.dark .panel-sub {
        color: #64748b !important;
    }

    html.dark table th {
        background: #181b29 !important;
        color: #64748b !important;
    }

    html.dark table td {
        color: #cbd5e1 !important;
        border-top-color: #2d3148 !important;
    }

    html.dark tbody tr:hover td {
        background: #252840 !important;
    }

    html.dark tr.row-expired td {
        background: #2a1a1a !important;
        border-top-color: #7f1d1d !important;
    }

    html.dark tr.row-expired:hover td {
        background: #3b1515 !important;
    }

    html.dark .permit-mono {
        color: #818cf8 !important;
    }

    html.dark .badge-yellow {
        background: #422006 !important;
        color: #fde68a !important;
    }

    html.dark .badge-green {
        background: #052e16 !important;
        color: #86efac !important;
    }

    html.dark .badge-blue {
        background: #1e3a5f !important;
        color: #93c5fd !important;
    }

    html.dark .badge-red {
        background: #450a0a !important;
        color: #fca5a5 !important;
    }

    html.dark .badge-orange {
        background: #431407 !important;
        color: #fdba74 !important;
    }

    html.dark .btn-xs {
        background: #252840 !important;
        border-color: #374151 !important;
        color: #cbd5e1 !important;
    }

    html.dark .btn-xs:hover {
        background: #2d3148 !important;
        border-color: #6366f1 !important;
        color: #e2e8f0 !important;
    }

    html.dark .btn-xs.danger {
        background: #450a0a !important;
        border-color: #7f1d1d !important;
        color: #fca5a5 !important;
    }

    html.dark .form-control {
        background: #252840 !important;
        border-color: #374151 !important;
        color: #e2e8f0 !important;
    }

    html.dark .form-control:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .15) !important;
    }

    html.dark .form-label {
        color: #94a3b8 !important;
    }

    html.dark .modal {
        background: #1e2130 !important;
    }

    html.dark .modal-body {
        background: #1e2130 !important;
    }

    html.dark .modal-footer {
        background: #181b29 !important;
        border-top-color: #2d3148 !important;
    }

    html.dark .btn-cancel {
        background: #252840 !important;
        border-color: #374151 !important;
        color: #cbd5e1 !important;
    }

    html.dark .toast {
        background: #1e2130 !important;
        border-color: #2d3148 !important;
    }

    html.dark .toast-title {
        color: #e2e8f0 !important;
    }

    html.dark .toast-msg {
        color: #94a3b8 !important;
    }

    html.dark .pager-btn {
        background: #252840 !important;
        border-color: #374151 !important;
        color: #cbd5e1 !important;
    }

    html.dark .pager-btn:hover {
        border-color: #6366f1 !important;
        color: #e2e8f0 !important;
    }

    html.dark .pager-btn.active {
        background: #6366f1 !important;
        border-color: #6366f1 !important;
        color: #fff !important;
    }

    html.dark .pager-btn.disabled {
        color: #374151 !important;
    }

    html.dark .pager-info {
        color: #64748b !important;
    }
</style>