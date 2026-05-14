<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script>/* dark-mode anti-flash */
    (function(){try{if(localStorage.getItem('lgu_dark')==='1')document.documentElement.classList.add('dark');}catch(e){}})();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burial Permits — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>

        .permit-row td .actions-cell a,
        .permit-row td .actions-cell button,
        .permit-row td .actions-cell form {
            position: relative;
            z-index: 2;
        }
        .permit-row td .actions-cell a,
        .permit-row td .actions-cell button {
            pointer-events: auto;
        }
        .permit-row { cursor: pointer; transition: background-color .15s; }
        .permit-row:hover td { background-color: #eff6ff !important; }
        .permit-row:hover td:first-child { box-shadow: inset 4px 0 0 #2563eb !important; }
        html.dark .permit-row:hover td { background-color: #1e293b !important; }
        html.dark .permit-row:hover td:first-child { box-shadow: inset 4px 0 0 #6366f1 !important; }

        tr.row-expiring td { background: #fffbeb; border-top-color: #fde68a; transition: background-color .15s; }
        tr.row-expiring td:first-child { border-left: 3px solid #f59e0b; }
        html.dark tr.row-expiring td { background: #2a1f00 !important; border-top-color: #854d0e !important; }

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
            --header-h: 72px;
            --title-fs: 20px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; -webkit-font-smoothing: antialiased; }
        .main { width: calc(100% - var(--sb-width, 220px)); display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: var(--header-h); display: flex; align-items: center; justify-content: space-between; padding: 0 1.75rem; width: 100%; }
        .topbar-title { font-size: var(--title-fs); font-weight: 700; color: #0f172a; letter-spacing: -.02em; }
        .topbar-date { font-size: 12px; color: #94a3b8; font-weight: 500; margin-top: 1px; }
        .role-tag { background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 500; padding: 3px 10px; border-radius: 20px; letter-spacing: .06em; text-transform: uppercase; }
        .content { padding: 1.75rem 1.75rem 1.75rem 1rem; background: #f8fafc; display: flex; flex-direction: column; align-items: flex-start; width: 100%; }
        .panel { 
            background: #fff; 
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 10px 15px -3px rgba(0,0,0,0.02);
            overflow: hidden; 
            display: flex;
            flex-direction: column;
            min-height: 600px;
            width: 100%;
            max-width: 1400px;
        }
        .panel-header { position: relative; padding: .9rem 1.25rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; flex-shrink: 0; }
        .panel-header h3 { font-size: 13px; font-weight: 600; color: #0f172a; letter-spacing: -.01em; }

        /* ── Search Bar ── */
        .search-group { position: absolute; right: 1.25rem; top: 50%; transform: translateY(-50%); display: flex; align-items: center; width: 320px; max-width: 100%; }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; transition: color .2s; display: flex; align-items: center; }
        .search-input { flex: 1 !important; width: 100% !important; font-family: 'DM Sans', sans-serif !important; font-size: 13px !important; color: #0f172a !important; padding: .55rem 1rem .55rem 2.5rem !important; background: #f8fafc !important; border: 1.5px solid #e2e8f0 !important; border-radius: 10px !important; outline: none !important; transition: all .2s cubic-bezier(.4,0,.2,1) !important; }
        .search-input::placeholder { color: #94a3b8; opacity: .7; }
        .search-input:hover { border-color: #cbd5e1; background: #ffffff; }
        .search-input:focus { background: #ffffff !important; border-color: #3b82f6 !important; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12) !important; }
        .search-group:focus-within .search-icon { color: #3b82f6; }


        .empty-row td { text-align: center; color: #94a3b8; padding: 2.5rem; font-size: 13px; font-family: 'DM Mono', monospace; }


        /* ── Table Container ── */
        .table-scroll { 
            flex: 1;
            overflow-x: auto; 
            /* overflow-y: hidden;  <-- Removed to allow sticky headers with body scroll */
            scrollbar-width: thin;
        }
        /* Completely hide scrollbars for Chrome, Safari and Opera */
        .table-scroll::-webkit-scrollbar { display: none; }
        .table-scroll thead th { 
            position: sticky; 
            top: 0; /* Changed to 0 as topbar is not sticky by default, or adjust based on topbar */
            z-index: 10; 
            background: #f8fafc;
        }

        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        td, th { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        th { font-size: 10px; font-weight: 500; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; padding: .5rem 1rem; text-align: left; background: #f8fafc; font-family: 'DM Mono', monospace; }
        td { font-size: 13px; color: #475569; padding: .7rem 1rem; border-top: 1px solid #f1f5f9; vertical-align: middle; transition: background-color .15s ease; }


        tr.row-expired td { background: #fff5f5; border-top-color: #fecaca; }
        tr.row-expired td:first-child { border-left: 3px solid #ef4444; }

        .badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 500; padding: 2px 8px; border-radius: 4px; white-space: nowrap; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .permit-no { font-weight: 600; color: #1a2744; font-size: 12px; }

        .btn-action { display: inline-flex; align-items: center; gap: 4px; padding: 4px 9px; border-radius: 6px; border: 1px solid #e2e8f0; font-family: 'DM Sans', sans-serif; font-size: 11px; color: #475569; background: #fff; cursor: pointer; text-decoration: none; transition: all .15s; white-space: nowrap; }
        .btn-action:hover { background: #f8fafc; border-color: #0f1e3d; color: #0f1e3d; }

        .btn-renew { background: #fff1f2; border-color: #fca5a5; color: #b91c1c; font-weight: 600; }
        .btn-renew:hover { background: #fee2e2; border-color: #ef4444; color: #991b1b; }
        .btn-print { background: #f0f9ff; border-color: #7dd3fc; color: #0369a1; font-weight: 500; }
        .btn-print:hover { background: #e0f2fe; border-color: #0ea5e9; color: #0c4a6e; }
        .btn-print.loading { opacity: .6; pointer-events: none; }
        .actions-cell { display: flex; gap: 4px; align-items: center; flex-wrap: nowrap; }

        .btn-primary { display: inline-flex; align-items: center; gap: 5px; padding: .55rem 1rem; border-radius: 6px; border: none; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 500; color: #fff; background: #1a2744; cursor: pointer; text-decoration: none; transition: background .15s; }
        .btn-primary:hover { background: #243459; }

        .pager { display: flex; align-items: center; justify-content: center; padding: .75rem 1.25rem; border-top: 1px solid #f3f4f6; flex-wrap: wrap; gap: 1.5rem; }
        .pager-info { font-size: 12px; color: #6b7280; }
        .pager-btns { display: flex; align-items: center; gap: 3px; }
        .pager-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 30px; height: 30px; padding: 0 9px; border-radius: 5px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 12px; color: #374151; text-decoration: none; background: #fff; cursor: pointer; transition: border-color .15s, color .15s; line-height: 1; }
        .pager-btn:hover { border-color: #1a2744; color: #1a2744; }
        .pager-btn.active { background: #1a2744; color: #fff; border-color: #1a2744; font-weight: 600; cursor: default; }
        .pager-btn.disabled { color: #d1d5db; cursor: not-allowed; pointer-events: none; }

        .search-input { font-family: 'Inter', sans-serif; font-size: 13px; padding: .38rem .75rem; border: 1px solid #e5e7eb; border-radius: 6px; outline: none; width: 220px; color: #111827; }
        .search-input:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.07); }

        .sort-link { display: inline-flex; align-items: center; gap: 4px; color: #9ca3af; text-decoration: none; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: .06em; transition: color .15s; white-space: nowrap; }
        .sort-link:hover { color: #1a2744; }
        .sort-link.active { color: #1a2744; font-weight: 700; }
        .sort-icon { opacity: .4; font-size: 10px; }
        .sort-icon.asc::after  { content: ' ↑'; color: #1a2744; opacity: 1; }
        .sort-icon.desc::after { content: ' ↓'; color: #1a2744; opacity: 1; }
        .sort-icon.none::after { content: ' ↕'; }
        .sort-icon.mid::after  { content: ' ↕'; color: #1a2744; opacity: 1; }
        .sort-icon.t1::after, .sort-icon.t2::after, .sort-icon.t3::after, 
        .sort-icon.t4::after, .sort-icon.t5::after, .sort-icon.t6::after { content: ' ↕'; color: #1a2744; opacity: 1; }

        /* ── TOAST ── */
        .toast-container { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.75rem; align-items: flex-end; pointer-events: none; }
        .toast { position: relative; width: 320px; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 8px 32px rgba(0,0,0,.12); overflow: hidden; transform: translateX(calc(100% + 1.5rem)); transition: transform .35s cubic-bezier(.34,1.56,.64,1); pointer-events: none; display: none; }
        .toast.show { transform: translateX(0); pointer-events: auto; display: block; animation: toastIn .35s cubic-bezier(.34,1.56,.64,1); }
        @keyframes toastIn { from { transform: translateX(calc(100% + 1.5rem)); } to { transform: translateX(0); } }
        .toast-body { display: flex; align-items: flex-start; gap: .75rem; padding: .9rem 1rem; }
        .toast-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .toast-icon.green { background: #d1fae5; }
        .toast-icon.blue  { background: #dbeafe; }
        .toast-icon.red   { background: #fee2e2; }
        .toast-text { flex: 1; }
        .toast-title { font-size: 13px; font-weight: 600; color: #111827; }
        .toast-sub { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .toast-close { background: none; border: none; cursor: pointer; color: #9ca3af; padding: 2px; line-height: 1; transition: color .15s; flex-shrink: 0; }
        .toast-close:hover { color: #374151; }
        .toast-progress { height: 3px; background: #e5e7eb; overflow: hidden; }
        .toast-progress-bar { height: 100%; width: 100%; transform-origin: left; animation: toastDrain 5s linear forwards; }
        .toast-progress-bar.green { background: #10b981; }
        .toast-progress-bar.blue  { background: #3b82f6; }
        .toast-progress-bar.red   { background: #ef4444; animation-duration: 6s; }
        @keyframes toastDrain { from { transform: scaleX(1); } to { transform: scaleX(0); } }

        /* MODAL */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.25); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); z-index: 100; align-items: center; justify-content: center; padding: 2rem 1rem; overflow-y: auto; }
        .modal-overlay.open { display: flex; }
        .modal { background: #ffffff !important; border-radius: 12px; width: 100%; max-width: 580px; box-shadow: 0 20px 60px rgba(0,0,0,.2); overflow: hidden; animation: modalIn .15s ease; margin: auto; }
        @keyframes modalIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { padding: 1rem 1.25rem; border-bottom: 2px solid #1a2744; display: flex; align-items: center; justify-content: space-between; background: #1a2744; }
        .modal-header h3 { font-size: 15px; font-weight: 700; color: #fff; }
        .modal-close { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.7); padding: 4px; line-height: 1; transition: color .15s; }
        .modal-close:hover { color: #fff; }
        .modal-body { padding: 1.25rem; display: flex; flex-direction: column; gap: .85rem; background: #ffffff; }
        .form-group { display: flex; flex-direction: column; gap: 4px; }
        .form-label { font-size: 11px; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: .05em; }
        .form-control { font-family: 'Inter', sans-serif; font-size: 13px; color: #111827; padding: .45rem .75rem; border: 1px solid #d1d5db; border-radius: 6px; outline: none; transition: border-color .15s, box-shadow .15s; width: 100%; background: #fff; }
        .form-control:focus { border-color: #1a2744; box-shadow: 0 0 0 3px rgba(26,39,68,.08); }
        .section-divider { font-size: 11px; font-weight: 700; color: #1a2744; text-transform: uppercase; letter-spacing: .08em; padding: .5rem 0 .25rem; border-bottom: 1px solid #e5e7eb; margin-top: .25rem; }
        .fee-grid { display: flex; flex-direction: column; gap: .4rem; }
        .fee-row { display: flex; align-items: center; justify-content: space-between; padding: .5rem .75rem; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; transition: background .15s; }
        .fee-row:hover { background: #f8faff; border-color: #1a2744; }
        .fee-row input[type=radio] { accent-color: #1a2744; width: 15px; height: 15px; cursor: pointer; }
        .fee-row label { font-size: 13px; font-weight: 500; color: #111827; cursor: pointer; flex: 1; margin-left: .6rem; }
        .fee-amount { font-size: 13px; font-weight: 600; color: #1a2744; }
        .modal-footer { padding: .9rem 1.25rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: .6rem; }
        .btn-cancel { padding: .5rem 1rem; border-radius: 6px; border: 1px solid #e5e7eb; font-family: 'Inter', sans-serif; font-size: 13px; color: #374151; background: #fff; cursor: pointer; }
        .btn-cancel:hover { background: #f9fafb; }

    /* ══════════════════════════════
       DARK MODE OVERRIDES
    ══════════════════════════════ */
    html.dark body { background: #0f1117 !important; color: #e2e8f0 !important; }
    html.dark .topbar { background: #1a1d27 !important; border-bottom-color: #2d3148 !important; }
    html.dark .topbar-title, html.dark .topbar-sub, html.dark .topbar-date { color: #e2e8f0 !important; }
    html.dark .topbar-date { color: #64748b !important; }
    
    html.dark .search-input { background: #1a1d27 !important; border-color: #2d3148 !important; color: #e2e8f0 !important; }
    html.dark .search-input::placeholder { color: #4b5563 !important; }
    html.dark .search-input:hover { background: #1f2231 !important; border-color: #374151 !important; }
    html.dark .search-input:focus { background: #111420 !important; border-color: #6366f1 !important; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15) !important; }
    html.dark .search-group:focus-within .search-icon { color: #818cf8 !important; }

    html.dark .role-tag { background: #6366f1 !important; }
    html.dark .panel, html.dark .card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .panel-head, html.dark .panel-header { background: #181b29 !important; border-bottom-color: #2d3148 !important; }
    html.dark .panel-head h3, html.dark .panel-header h3 { color: #e2e8f0 !important; }
    html.dark .panel-head a { color: #818cf8 !important; }
    html.dark table th { background: #181b29 !important; color: #64748b !important; }
    html.dark table td { color: #cbd5e1 !important; border-top-color: #2d3148 !important; }
    html.dark tr.row-expired td { background: #2a1a1a !important; border-top-color: #7f1d1d !important; }
    html.dark .permit-no { color: #818cf8 !important; }
    html.dark .badge-yellow { background: #422006 !important; color: #fde68a !important; }
    html.dark .badge-green  { background: #052e16 !important; color: #86efac !important; }
    html.dark .badge-blue   { background: #1e3a5f !important; color: #93c5fd !important; }
    html.dark .badge-red    { background: #450a0a !important; color: #fca5a5 !important; }
    html.dark .btn-view, html.dark .btn-action { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .btn-view:hover, html.dark .btn-action:hover { background: #2d3148 !important; border-color: #6366f1 !important; color: #e2e8f0 !important; }
    html.dark .search-input { background: #252840 !important; border-color: #374151 !important; color: #e2e8f0 !important; }
    html.dark .search-input:focus { border-color: #6366f1 !important; }
    html.dark .pager { border-top-color: #2d3148 !important; }
    html.dark .pager-info { color: #64748b !important; }
    html.dark .pager-btn { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .pager-btn:hover { border-color: #6366f1 !important; color: #e2e8f0 !important; }
    html.dark .pager-btn.active { background: #6366f1 !important; border-color: #6366f1 !important; color: #fff !important; }
    html.dark .pager-btn.disabled { color: #374151 !important; }
    html.dark .sort-link { color: #64748b !important; }
    html.dark .sort-link.active, html.dark .sort-link:hover { color: #818cf8 !important; }
    html.dark .section-divider { color: #818cf8 !important; border-bottom-color: #2d3148 !important; }
    html.dark .form-control, html.dark select.form-control { background: #252840 !important; border-color: #374151 !important; color: #e2e8f0 !important; }
    html.dark .form-control:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 3px rgba(99,102,241,.15) !important; }
    html.dark .form-label { color: #94a3b8 !important; }
    html.dark .modal { background: #1e2130 !important; }
    html.dark .modal-header { background: #111827 !important; }
    html.dark #deleteModal .modal-header { background: #991b1b !important; border-bottom-color: #7f1d1d !important; }
    html.dark .modal-body { background: #1e2130 !important; }
    html.dark #deleteModal .modal-body { background: #1e2130 !important; }
    html.dark .modal-footer { background: #181b29 !important; border-top-color: #2d3148 !important; }
    html.dark .table-scroll { scrollbar-color: #374151 transparent; }
    html.dark .table-scroll::-webkit-scrollbar-thumb { background: #374151; }
    html.dark .table-scroll::-webkit-scrollbar-thumb:hover { background: #4b5563; }
    html.dark .btn-cancel { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .fee-row { border-color: #2d3148 !important; }
    html.dark .fee-row:hover { background: #252840 !important; border-color: #6366f1 !important; }
    html.dark .fee-row label { color: #e2e8f0 !important; }
    html.dark .fee-amount { color: #818cf8 !important; }
    html.dark .toast { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .toast-title { color: #e2e8f0 !important; }
    html.dark .toast-sub { color: #94a3b8 !important; }
    html.dark .btn-primary { background: #6366f1 !important; }
    html.dark .btn-primary:hover { background: #4f46e5 !important; }
    html.dark .modal-overlay { background: rgba(0,0,0,.75) !important; }
    html.dark .btn-print { background: rgba(255,255,255,.08) !important; color: #e2e8f0 !important; border-color: rgba(255,255,255,.15) !important; }
    html.dark .btn-print:hover { background: rgba(255,255,255,.14) !important; }
    html.dark .btn-cancel { background: #252840 !important; border-color: #374151 !important; color: #cbd5e1 !important; }
    html.dark .btn-cancel:hover { background: #2d3148 !important; }
    html.dark .btn-renew { background: #450a0a !important; border-color: #991b1b !important; color: #fca5a5 !important; }
    html.dark .btn-renew:hover { background: #7f1d1d !important; }
    html.dark .permit-row td { background: #1e2130 !important; }
    html.dark tr.row-expired td { background: #2a1a1a !important; }
    html.dark .pager { border-top-color: #2d3148 !important; }
    html.dark .pager-info { color: #64748b !important; }

    /* ── Floating Action Button (FAB) ── */
    .fab { position: fixed; bottom: 30px; right: 30px; width: 56px; height: 56px; background: #1a2744; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.25); cursor: pointer; z-index: 1000; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-decoration: none; border: none; outline: none; }
    .fab:hover { transform: scale(1.1) rotate(90deg); background: #2563eb; box-shadow: 0 6px 16px rgba(0,0,0,0.3); }
    .fab svg { width: 24px; height: 24px; }
    html.dark .fab { background: #3b82f6; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
    html.dark .fab:hover { background: #60a5fa; }

    </style>
</head>
<body>

@include('admin.partials.sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">
                Burial Permits 
                <a href="{{ route('support.manual') }}#permits" class="help-link-trigger" title="How to use this page" style="display:inline-flex; vertical-align:middle; margin-left:8px; color:var(--accent); opacity:0.6; transition:opacity .15s;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </a>
            </div>
            <div class="topbar-date">System Management & Issuance</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
            <span class="role-tag">{{ auth()->user()->role === 'cemetery_manager' ? 'Cemetery Manager' : 'Admin' }}</span>
            @if(auth()->user()->role === 'admin')
            <button class="btn-primary" onclick="openPM()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Permit
            </button>
            @endif
        </div>
    </div>

    <div class="content">

        <div class="panel">
            <div class="panel-header">
                <h3>All Burial Permits
                    <span id="totalRecords" style="font-size:11px;font-weight:400;color:#9ca3af;margin-left:.5rem">{{ $permits->total() }} records</span>
                </h3>
                <form action="{{ route('permits.index') }}" method="GET" class="search-group" style="position: absolute !important; right: 1.25rem !important; top: 50% !important; transform: translateY(-50%) !important;">
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                    @if(request('direction')) <input type="hidden" name="direction" value="{{ request('direction') }}"> @endif
                    @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                    
                    <div class="search-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <input type="text" name="q" class="search-input" id="liveSearchInput" autocomplete="off" placeholder="Search by name or permit no…" value="{{ request('q') }}">
                </form>

            </div>
            <div id="table-container" style="transition: opacity 0.2s ease;">
                @include('admin.permits.partials.table')
            </div>

        </div>
    </div>
</div>

{{-- TOAST CONTAINER --}}
<div class="toast-container">
    {{-- SUCCESS TOAST --}}
    @if(session('success'))
<div class="toast" id="successToast">
    <div class="toast-body">
        <div class="toast-icon green">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="toast-text">
            <div class="toast-title">Success</div>
            <div class="toast-sub">{{ session('success') }}</div>
        </div>
        <button class="toast-close" onclick="dismissToast('successToast')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div class="toast-progress"><div class="toast-progress-bar green"></div></div>
</div>
@endif

{{-- ERROR TOAST --}}
@if(session('error'))
<div class="toast" id="errToast">
    <div class="toast-body">
        <div class="toast-icon red">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="toast-text">
            <div class="toast-title" style="color:#dc2626">Not Eligible</div>
            <div class="toast-sub">{{ session('error') }}</div>
        </div>
        <button class="toast-close" onclick="dismissToast('errToast')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div class="toast-progress"><div class="toast-progress-bar red"></div></div>
</div>
@endif

{{-- PRINT TOAST --}}
<div class="toast" id="printToast">
    <div class="toast-body">
        <div class="toast-icon blue">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        </div>
        <div class="toast-text">
            <div class="toast-title">Generating permit…</div>
            <div class="toast-sub" id="printToastSub">Preparing your document</div>
        </div>
    </div>
    <div class="toast-progress"><div class="toast-progress-bar blue" id="printToastBar"></div></div>
</div>

{{-- REDIRECT TOAST --}}
@if(session('redirect_url'))
<div class="toast" id="redirectToast">
    <div class="toast-body">
        <div class="toast-icon blue">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </div>
        <div class="toast-text">
            <div class="toast-title" style="color:#1e40af">Smart Redirect</div>
            <div class="toast-sub">Redirecting to <strong id="redirectPNum">{{ session('redirect_name') }}</strong> in <span id="redirectCount">5</span>s...</div>
        </div>
        <button class="toast-close" onclick="dismissToast('redirectToast')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div class="toast-progress"><div class="toast-progress-bar blue" id="redirectBar"></div></div>
</div>
@endif
</div>

@include('admin.partials.permit-modal')
@include('admin.partials.renewal-modal')

<script>
function closeModal() { document.getElementById('permitModal').classList.remove('open'); }
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeModal(); });

function dismissToast(id) {
    const t = document.getElementById(id);
    if (!t) return;
    clearTimeout(t._timer);
    t.classList.remove('show');
    t.addEventListener('transitionend', () => t.remove(), { once: true });
}

// ── Auto-show toasts on page load ──
(function () {
    const success = document.getElementById('successToast');
    if (success) {
        requestAnimationFrame(() => setTimeout(() => success.classList.add('show'), 50));
        success._timer = setTimeout(() => dismissToast('successToast'), 5000);
    }

    const err = document.getElementById('errToast');
    if (err) {
        requestAnimationFrame(() => setTimeout(() => err.classList.add('show'), 50));
        err._timer = setTimeout(() => dismissToast('errToast'), 6000);
    }
})();

if (window.location.hash === '#new' || @json(session('open_modal'))) {
    document.getElementById('permitModal').classList.add('open');
    history.replaceState(null, '', window.location.pathname);
}

// ── Smart Redirect Logic ──
(function() {
    const redirectUrl = @json(session('redirect_url'));
    if (!redirectUrl) return;

    // Wait 2 seconds before showing the redirection toast
    setTimeout(() => {
        const toast = document.getElementById('redirectToast');
        if (!toast) return;

        toast.classList.add('show');
        let count = 5;
        const countEl = document.getElementById('redirectCount');
        const barEl = document.getElementById('redirectBar');
        
        // Reset and start bar animation
        barEl.style.animation = 'none';
        barEl.offsetHeight; 
        barEl.style.animation = 'toastDrain 5s linear forwards';

        const timer = setInterval(() => {
            count--;
            if (countEl) countEl.textContent = count;
            if (count <= 0) {
                clearInterval(timer);
                window.location.href = redirectUrl;
            }
        }, 1000);

        // Allow closing to cancel redirect
        toast.querySelector('.toast-close').onclick = () => {
            clearInterval(timer);
            dismissToast('redirectToast');
        };
    }, 2500); // 2.5s delay after the error toast appears
})();

function filterTable(q) {
    // Client-side filtering removed in favor of Server-side search for pagination compatibility
}

function handlePrint(e, link, permitNo) {
    const toast = document.getElementById('printToast');
    const subEl = document.getElementById('printToastSub');
    const barEl = document.getElementById('printToastBar');
    subEl.textContent = 'Downloading ' + permitNo + '.docx…';
    barEl.style.animation = 'none';
    barEl.offsetHeight;
    barEl.style.animation = '';
    toast.classList.add('show');
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => toast.classList.remove('show'), 5000);
    link.classList.add('loading');
    setTimeout(() => link.classList.remove('loading'), 2000);
}

// ── AUTO-FOCUS AND LIVE SEARCH ──
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('liveSearchInput');
    const searchForm = document.querySelector('.search-group');
    const tableContainer = document.getElementById('table-container');
    const totalRecords = document.getElementById('totalRecords');
    let timeout = null;

    if (searchForm) {
        // Prevent generic form submission
        searchForm.addEventListener('submit', (e) => e.preventDefault());
    }

    if (searchInput) {
        searchInput.focus();

        searchInput.addEventListener('input', (e) => {
            clearTimeout(timeout);
            const query = e.target.value;
            timeout = setTimeout(() => fetchResults(query), 300);
        });
    }

    // Intercept clicks on pagination or sorting links to keep it seamless
    if (tableContainer) {
        tableContainer.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && (link.classList.contains('pager-btn') || link.classList.contains('sort-link'))) {
                e.preventDefault();
                fetchResultsByUrl(link.href);
            }
        });
    }

    function fetchResultsByUrl(urlString) {
        tableContainer.style.opacity = '0.5';
        
        fetch(urlString, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            const total = response.headers.get('X-Total-Records');
            if(total !== null && totalRecords) {
                totalRecords.textContent = total + ' records';
            }
            return response.text();
        })
        .then(html => {
            tableContainer.innerHTML = html;
            tableContainer.style.opacity = '1';
            window.history.replaceState({}, '', urlString);
        })
        .catch(err => {
            console.error('Fetch failed:', err);
            tableContainer.style.opacity = '1';
        });
    }

    function fetchResults(query) {
        const url = new URL(window.location.href);
        url.searchParams.set('q', query);
        url.searchParams.delete('page'); // reset to page 1 on fresh search
        if (!query) url.searchParams.delete('q');
        fetchResultsByUrl(url.toString());
    }
});
</script>

    <!-- FAB: New Permit -->
    @if(auth()->user()->role === 'admin')
    <button onclick="openPM()" class="fab" title="New Burial Permit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </button>
    @endif

</body>
</html>