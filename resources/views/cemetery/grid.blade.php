<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script>/* dark-mode anti-flash */
        (function () { try { if (localStorage.getItem('lgu_dark') === '1') document.documentElement.classList.add('dark'); } catch (e) { } })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cemetery Grid — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            color: #111827;
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .main {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        .topbar-date {
            font-size: 12px;
            color: #9ca3af;
        }

        .role-tag {
            background: #1a2744;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .content {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* ── TOOLBAR ── */
        .toolbar {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: .85rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .toolbar-section {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .toolbar-label {
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .06em;
            white-space: nowrap;
        }

        .toolbar-divider {
            width: 1px;
            height: 22px;
            background: #e5e7eb;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: .4rem .85rem;
            border-radius: 6px;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #374151;
            transition: all .15s;
            white-space: nowrap;
        }

        .btn:hover {
            background: #f9fafb;
            border-color: #1a2744;
            color: #1a2744;
        }

        .btn-primary {
            background: #1a2744;
            color: #fff;
            border-color: #1a2744;
        }

        .btn-primary:hover {
            background: #243459;
        }

        .inp {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: #111827;
            padding: .38rem .7rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            outline: none;
            width: 60px;
            text-align: center;
        }

        .inp:focus {
            border-color: #1a2744;
            box-shadow: 0 0 0 3px rgba(26, 39, 68, .07);
        }

        /* Paint mode pills */
        .paint-pill {
            padding: .35rem .85rem;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all .15s;
            letter-spacing: .02em;
        }

        .paint-pill.available {
            background: #d1fae5;
            color: #065f46;
            border-color: #d1fae5;
        }

        .paint-pill.available.active {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .2);
        }

        .paint-pill.occupied {
            background: #fee2e2;
            color: #991b1b;
            border-color: #fee2e2;
        }

        .paint-pill.occupied.active {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .2);
        }

        .paint-pill.reserved {
            background: #fef3c7;
            color: #92400e;
            border-color: #fef3c7;
        }

        .paint-pill.reserved.active {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .2);
        }

        .paint-pill.erase {
            background: #f3f4f6;
            color: #6b7280;
            border-color: #f3f4f6;
        }

        .paint-pill.erase.active {
            border-color: #9ca3af;
            box-shadow: 0 0 0 3px rgba(156, 163, 175, .2);
        }

        /* ── STATS BAR ── */
        .stats-bar {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .stat-chip {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: .5rem 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: 13px;
        }

        .stat-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .stat-num {
            font-weight: 700;
            color: #111827;
        }

        .stat-lbl {
            color: #9ca3af;
            font-size: 11px;
        }

        /* ── SECTIONS ── */
        .sections-wrap {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .section-block {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .section-head {
            padding: .75rem 1.1rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }

        .section-meta {
            font-size: 11px;
            color: #9ca3af;
        }

        .section-grid-wrap {
            padding: 1rem 1.1rem;
            overflow-x: auto;
        }

        /* ── NICHE GRID ── */
        .niche-grid {
            display: flex;
            flex-direction: column;
            gap: 4px;
            width: max-content;
        }

        .niche-row {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .row-label {
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            width: 28px;
            text-align: right;
            margin-right: 4px;
            flex-shrink: 0;
            letter-spacing: .05em;
        }

        .col-labels {
            display: flex;
            gap: 4px;
            margin-left: 36px;
            margin-bottom: 2px;
        }

        .col-label {
            width: 36px;
            font-size: 9px;
            font-weight: 600;
            color: #9ca3af;
            text-align: center;
            letter-spacing: .04em;
        }

        .niche {
            width: 36px;
            height: 28px;
            border-radius: 4px;
            border: 1.5px solid #e5e7eb;
            background: #f9fafb;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: 700;
            color: transparent;
            transition: transform .1s, box-shadow .1s, background .1s, border-color .1s;
            position: relative;
            user-select: none;
        }

        .niche:hover {
            transform: scale(1.15);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
            z-index: 2;
        }

        .niche.available {
            background: #d1fae5;
            border-color: #6ee7b7;
        }

        .niche.occupied {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        .niche.reserved {
            background: #fef3c7;
            border-color: #fde68a;
        }

        .niche.occupied::after {
            content: '✕';
            color: #ef4444;
            font-size: 10px;
            font-weight: 900;
        }

        .niche.available::after {
            content: '';
        }

        .niche.reserved::after {
            content: '◐';
            color: #f59e0b;
            font-size: 10px;
        }

        /* Tooltip */
        .niche[data-label]:hover::before {
            content: attr(data-label);
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            background: #111827;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 7px;
            border-radius: 4px;
            white-space: nowrap;
            z-index: 10;
            pointer-events: none;
        }

        /* ── MODAL ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: 12px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            overflow: hidden;
            animation: mIn .15s ease;
        }

        @keyframes mIn {
            from {
                opacity: 0;
                transform: translateY(-8px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .modal-head {
            background: #1a2744;
            padding: .9rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-head h3 {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
        }

        .modal-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, .6);
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }

        .modal-close:hover {
            color: #fff;
        }

        .modal-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: .85rem;
            background: #ffffff;
        }

        .modal-foot {
            padding: .85rem 1.25rem;
            border-top: 1px solid #f3f4f6;
            display: flex;
            justify-content: flex-end;
            gap: .5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-label {
            font-size: 11px;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .form-control {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: #111827;
            padding: .45rem .75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            outline: none;
            width: 100%;
        }

        .form-control:focus {
            border-color: #1a2744;
            box-shadow: 0 0 0 3px rgba(26, 39, 68, .08);
        }

        /* Status selector in modal */
        .status-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .5rem;
        }

        .status-opt {
            padding: .55rem;
            border-radius: 7px;
            border: 2px solid #e5e7eb;
            cursor: pointer;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            transition: all .15s;
        }

        .status-opt.available {
            background: #d1fae5;
            color: #065f46;
        }

        .status-opt.available.sel {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .15);
        }

        .status-opt.occupied {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-opt.occupied.sel {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .15);
        }

        .status-opt.reserved {
            background: #fef3c7;
            color: #92400e;
        }

        .status-opt.reserved.sel {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .15);
        }

        .empty-opt {
            background: #f3f4f6;
            color: #6b7280;
            border-color: #e5e7eb;
            font-size: 11px;
        }

        .empty-opt.sel {
            border-color: #9ca3af;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 2rem;
            left: 1.5rem;
            z-index: 9999;
            background: #1a2744;
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            padding: .7rem 1.1rem;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .25);
            transform: translateY(20px);
            opacity: 0;
            transition: all .3s;
            pointer-events: none;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* ── DARK MODE ── */
        html.dark body {
            background: #0f1117 !important;
            color: #e2e8f0 !important;
        }

        html.dark .main {
            background: #0f1117 !important;
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

        html.dark .role-tag {
            background: #6366f1 !important;
        }

        html.dark .toolbar {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .toolbar-label {
            color: #64748b !important;
        }

        html.dark .toolbar-divider {
            background: #2d3148 !important;
        }

        html.dark .btn {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #cbd5e1 !important;
        }

        html.dark .btn:hover {
            background: #2d3148 !important;
            border-color: #6366f1 !important;
            color: #e2e8f0 !important;
        }

        html.dark .btn-primary {
            background: #6366f1 !important;
            border-color: #6366f1 !important;
            color: #fff !important;
        }

        html.dark .inp {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #e2e8f0 !important;
        }

        html.dark .stat-chip {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .stat-num {
            color: #e2e8f0 !important;
        }

        html.dark .stat-lbl {
            color: #64748b !important;
        }

        html.dark .section-block {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .section-head {
            border-bottom-color: #2d3148 !important;
        }

        html.dark .section-title {
            color: #e2e8f0 !important;
        }

        html.dark .niche {
            background: #252840 !important;
            border-color: #374151 !important;
        }

        html.dark .niche.available {
            background: #052e16 !important;
            border-color: #166534 !important;
        }

        html.dark .niche.occupied {
            background: #450a0a !important;
            border-color: #991b1b !important;
        }

        html.dark .niche.reserved {
            background: #422006 !important;
            border-color: #854d0e !important;
        }

        html.dark .row-label,
        html.dark .col-label {
            color: #4b5563 !important;
        }

        html.dark .modal {
            background: #1e2130 !important;
        }

        html.dark .modal-body {
            background: #1e2130 !important;
        }

        html.dark .modal-foot {
            background: #181b29 !important;
            border-top-color: #2d3148 !important;
        }

        html.dark .form-label {
            color: #94a3b8 !important;
        }

        html.dark .form-control {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #e2e8f0 !important;
        }

        /* ── CONFIRM DELETE MODAL ── */
        #confirmDeleteModal .cdm-modal {
            background: #fff;
            border-radius: 12px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, .25);
            overflow: hidden;
            animation: mIn .15s ease;
        }

        #confirmDeleteModal .cdm-head {
            background: #991b1b;
            padding: .9rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #confirmDeleteModal .cdm-head h3 {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        #confirmDeleteModal .cdm-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, .6);
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }

        #confirmDeleteModal .cdm-close:hover {
            color: #fff;
        }

        #confirmDeleteModal .cdm-body {
            padding: 1.5rem 1.25rem;
            text-align: center;
        }

        #confirmDeleteModal .cdm-icon {
            width: 56px;
            height: 56px;
            background: rgba(239, 68, 68, .1);
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        #confirmDeleteModal .cdm-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: .4rem;
        }

        #confirmDeleteModal .cdm-sub {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        #confirmDeleteModal .cdm-name {
            font-weight: 700;
            color: #111827;
        }

        #confirmDeleteModal .cdm-foot {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .6rem;
            padding-top: .25rem;
        }

        #confirmDeleteModal .cdm-btn-del {
            width: 100%;
            max-width: 260px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .7rem;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
        }

        #confirmDeleteModal .cdm-btn-del:hover {
            background: #dc2626;
        }

        #confirmDeleteModal .cdm-btn-cancel {
            border: none;
            background: transparent;
            color: #6b7280;
            font-size: 12px;
            cursor: pointer;
            text-decoration: underline;
        }

        html.dark #confirmDeleteModal .cdm-modal {
            background: #ffffff !important;
            border: none !important;
        }
        html.dark #confirmDeleteModal .cdm-title,
        html.dark #confirmDeleteModal .cdm-name {
            color: #111827 !important;
        }
        html.dark #confirmDeleteModal .cdm-sub {
            color: #6b7280 !important;
        }

        html.dark #confirmDeleteModal .cdm-btn-cancel {
            color: #94a3b8 !important;
        }
    </style>
</head>

<body>

    @include('admin.partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Cemetery Grid Manager</div>
                <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
            </div>
            <span class="role-tag">{{ auth()->user()->role === 'super_admin' ? 'Super Admin' : (auth()->user()->role === 'cemetery_manager' ? 'Cemetery Manager' : 'Admin') }}</span>
        </div>

        <div class="content">

            {{-- TOOLBAR --}}
            <div class="toolbar">
                <div class="toolbar-section">
                    <span class="toolbar-label">Paint Mode</span>
                    <span class="paint-pill available active" id="pill-available" onclick="setPaint('available')">●
                        Available</span>
                    <span class="paint-pill occupied" id="pill-occupied" onclick="setPaint('occupied')">✕
                        Occupied</span>
                    <span class="paint-pill reserved" id="pill-reserved" onclick="setPaint('reserved')">◐
                        Reserved</span>
                    <span class="paint-pill erase" id="pill-erase" onclick="setPaint('erase')">⌫ Clear</span>
                </div>
                <div class="toolbar-divider"></div>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'cemetery_manager')
                <div class="toolbar-section">
                    <span class="toolbar-label">Add Section</span>
                    <input id="newSectionName" class="inp" style="width:130px" placeholder="e.g. Niche Wall A">
                    <input id="newRows" class="inp" type="number" value="4" min="1" max="20" title="Rows">
                    <input id="newCols" class="inp" type="number" value="20" min="1" max="60" title="Columns">
                    <button class="btn btn-primary" onclick="addSection()">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Add Section
                    </button>
                </div>
                <div class="toolbar-divider"></div>
                @endif
                <div class="toolbar-section">
                    <button class="btn" onclick="saveAll()">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Save All
                    </button>
                    <button class="btn" onclick="exportCSV()">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                        </svg>
                        Export CSV
                    </button>
                </div>
            </div>

            {{-- STATS --}}
            <div class="stats-bar" id="statsBar">
                <div class="stat-chip">
                    <div class="stat-dot" style="background:#e5e7eb"></div><span class="stat-num"
                        id="s-empty">0</span><span class="stat-lbl">Empty</span>
                </div>
                <div class="stat-chip">
                    <div class="stat-dot" style="background:#10b981"></div><span class="stat-num"
                        id="s-available">0</span><span class="stat-lbl">Available</span>
                </div>
                <div class="stat-chip">
                    <div class="stat-dot" style="background:#ef4444"></div><span class="stat-num"
                        id="s-occupied">0</span><span class="stat-lbl">Occupied</span>
                </div>
                <div class="stat-chip">
                    <div class="stat-dot" style="background:#f59e0b"></div><span class="stat-num"
                        id="s-reserved">0</span><span class="stat-lbl">Reserved</span>
                </div>
                <div class="stat-chip">
                    <div class="stat-dot" style="background:#1a2744"></div><span class="stat-num"
                        id="s-total">0</span><span class="stat-lbl">Total Niches</span>
                </div>
            </div>

            {{-- SECTIONS --}}
            <div class="sections-wrap" id="sectionsWrap">
                <div style="text-align:center;padding:3rem;color:#9ca3af;font-size:13px;background:#fff;border:1px dashed #e5e7eb;border-radius:10px"
                    id="emptyMsg">
                    No sections yet. Add a section using the toolbar above.<br>
                    <span style="font-size:11px;margin-top:.5rem;display:block">Example: "Niche Wall A", 4 rows × 20
                        columns</span>
                </div>
            </div>

        </div>
    </div>

    {{-- NICHE DETAIL MODAL --}}
    <div class="modal-overlay" id="nicheModal">
        <div class="modal">
            <div class="modal-head">
                <h3 id="modalTitle">Niche Detail</h3>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Name / Label <span
                            style="font-size:9px;color:#9ca3af">(optional)</span></label>
                    <input type="text" id="nicheLabel" class="form-control" placeholder="e.g. Santos, Juan">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="status-grid">
                        <div class="status-opt empty-opt sel" id="sopt-empty" onclick="selStatus('empty')">Empty</div>
                        <div class="status-opt available" id="sopt-available" onclick="selStatus('available')">Available
                        </div>
                        <div class="status-opt occupied" id="sopt-occupied" onclick="selStatus('occupied')">Occupied
                        </div>
                        <div class="status-opt reserved" id="sopt-reserved" onclick="selStatus('reserved')">Reserved
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes <span style="font-size:9px;color:#9ca3af">(optional)</span></label>
                    <input type="text" id="nicheNotes" class="form-control" placeholder="Any notes…">
                </div>
            </div>
            <div class="modal-foot">
                <button class="btn" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" onclick="applyModal()">Apply</button>
            </div>
        </div>
    </div>

    {{-- SECTION DELETE CONFIRM MODAL --}}
    <div class="modal-overlay" id="confirmDeleteModal" onclick="if(event.target===this)closeSectionDeleteModal()">
        <div class="cdm-modal">
            <div class="cdm-head">
                <h3>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14H6L5 6" />
                        <path d="M10 11v6M14 11v6" />
                        <path d="M9 6V4h6v2" />
                    </svg>
                    Confirm Deletion
                </h3>
                <button class="cdm-close" onclick="closeSectionDeleteModal()">×</button>
            </div>
            <div class="cdm-body">
                <div class="cdm-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14H6L5 6" />
                        <path d="M10 11v6M14 11v6" />
                        <path d="M9 6V4h6v2" />
                    </svg>
                </div>
                <div class="cdm-title">Are you sure?</div>
                <p class="cdm-sub">Remove this section and <strong>all its grid data</strong>? This cannot be
                    undone.<br><span class="cdm-name" id="cdmSectionName"></span></p>
                <div class="cdm-foot">
                    <button class="cdm-btn-del" id="cdmConfirmBtn" onclick="confirmSectionDelete()">Yes, remove
                        it</button>
                    <button class="cdm-btn-cancel" onclick="closeSectionDeleteModal()">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        // ── State ──
        let paintMode = 'available';
        let sections = [];       // [{id, name, rows, cols, cells:{}}]
        let isDragging = false;
        let modalTarget = null;     // {sectionId, cellKey}

        // Load from localStorage
        const STORE_KEY = 'lgu_cemetery_grid_{{ auth()->id() }}';
        function persist() {
            try { localStorage.setItem(STORE_KEY, JSON.stringify(sections)); } catch (e) { }
            updateStats();
        }
        function load() {
            try {
                const d = localStorage.getItem(STORE_KEY);
                if (d) sections = JSON.parse(d);
            } catch (e) { sections = []; }
        }

        // ── Paint mode ──
        function setPaint(mode) {
            paintMode = mode;
            document.querySelectorAll('.paint-pill').forEach(p => p.classList.remove('active'));
            document.getElementById('pill-' + mode).classList.add('active');
        }

        // ── Add section ──
        function addSection() {
            const name = document.getElementById('newSectionName').value.trim() || ('Section ' + (sections.length + 1));
            const rows = Math.max(1, Math.min(20, parseInt(document.getElementById('newRows').value) || 4));
            const cols = Math.max(1, Math.min(60, parseInt(document.getElementById('newCols').value) || 20));
            const id = 'sec_' + Date.now();
            sections.push({ id, name, rows, cols, cells: {} });
            document.getElementById('newSectionName').value = '';
            persist();
            renderSections();
            showToast(`Section "${name}" added (${rows}×${cols})`);
        }

        // ── Render ──
        function renderSections() {
            const wrap = document.getElementById('sectionsWrap');
            const empty = document.getElementById('emptyMsg');
            if (!sections.length) {
                wrap.innerHTML = '';
                wrap.appendChild(empty);
                return;
            }
            wrap.innerHTML = '';
            sections.forEach(sec => {
                const block = document.createElement('div');
                block.className = 'section-block';
                block.id = 'block_' + sec.id;

                // Count stats for this section
                let av = 0, oc = 0, re = 0, em = 0;
                for (let r = 1; r <= sec.rows; r++) for (let c = 1; c <= sec.cols; c++) {
                    const st = (sec.cells[`${r}-${c}`] || {}).status || 'empty';
                    if (st === 'available') av++; else if (st === 'occupied') oc++; else if (st === 'reserved') re++; else em++;
                }
                const total = sec.rows * sec.cols;

                block.innerHTML = `
            <div class="section-head">
                <div>
                    <span class="section-title">${esc(sec.name)}</span>
                    <span class="section-meta" style="margin-left:.5rem">${sec.rows} rows × ${sec.cols} cols · ${total} niches</span>
                </div>
                <div style="display:flex;align-items:center;gap:.5rem">
                    <span style="font-size:11px;color:#10b981;font-weight:600">${av} avail</span>
                    <span style="font-size:11px;color:#ef4444;font-weight:600">${oc} occ</span>
                    <span style="font-size:11px;color:#f59e0b;font-weight:600">${re} res</span>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'cemetery_manager')
                    <button class="btn" style="font-size:11px;padding:3px 9px;color:#991b1b;border-color:#fca5a5" onclick="removeSection('${sec.id}')">Remove</button>
                    @endif
                </div>
            </div>
            <div class="section-grid-wrap">
                <div class="niche-grid" id="grid_${sec.id}"></div>
            </div>
        `;
                wrap.appendChild(block);
                renderGrid(sec);
            });
            updateStats();
        }

        function renderGrid(sec) {
            const grid = document.getElementById('grid_' + sec.id);
            if (!grid) return;
            grid.innerHTML = '';

            // Column labels
            const colLabels = document.createElement('div');
            colLabels.className = 'col-labels';
            for (let c = 1; c <= sec.cols; c++) {
                const l = document.createElement('div');
                l.className = 'col-label';
                l.textContent = c;
                colLabels.appendChild(l);
            }
            grid.appendChild(colLabels);

            // Rows
            for (let r = 1; r <= sec.rows; r++) {
                const row = document.createElement('div');
                row.className = 'niche-row';

                const rl = document.createElement('div');
                rl.className = 'row-label';
                rl.textContent = 'R' + r;
                row.appendChild(rl);

                for (let c = 1; c <= sec.cols; c++) {
                    const key = `${r}-${c}`;
                    const cell = sec.cells[key] || {};
                    const niche = document.createElement('div');
                    niche.className = 'niche ' + (cell.status || '');
                    niche.dataset.sec = sec.id;
                    niche.dataset.key = key;
                    niche.dataset.label = cell.label ? `${cell.label}` : `${sec.name} R${r}-${c}`;
                    if (cell.label) niche.title = cell.label;

                    // Paint on click
                    niche.addEventListener('click', (e) => {
                        if (paintMode === 'erase') {
                            applyPaint(sec.id, key, 'empty', null);
                        } else if (e.shiftKey || e.altKey) {
                            // Shift/Alt+click = open detail modal
                            openModal(sec.id, key, cell);
                        } else {
                            applyPaint(sec.id, key, paintMode, cell.label || null);
                        }
                    });

                    // Right-click = open detail modal
                    niche.addEventListener('contextmenu', (e) => {
                        e.preventDefault();
                        openModal(sec.id, key, cell);
                    });

                    // Drag paint
                    niche.addEventListener('mousedown', () => { isDragging = true; });
                    niche.addEventListener('mouseover', () => {
                        if (isDragging && paintMode !== 'erase') {
                            applyPaint(sec.id, key, paintMode, cell.label || null);
                        }
                    });

                    row.appendChild(niche);
                }
                grid.appendChild(row);
            }
        }

        document.addEventListener('mouseup', () => { isDragging = false; });

        function applyPaint(secId, key, status, label) {
            const sec = sections.find(s => s.id === secId);
            if (!sec) return;
            if (status === 'empty') {
                delete sec.cells[key];
            } else {
                sec.cells[key] = { status, label: label || null };
            }
            // Update just this niche visually without full re-render
            const niche = document.querySelector(`.niche[data-sec="${secId}"][data-key="${key}"]`);
            if (niche) {
                niche.className = 'niche ' + (status === 'empty' ? '' : status);
                const cell = sec.cells[key] || {};
                niche.dataset.label = cell.label || `${sec.name} ${key}`;
            }
            // Update section header counts
            updateSectionMeta(sec);
            persist();
        }

        function updateSectionMeta(sec) {
            let av = 0, oc = 0, re = 0;
            for (let r = 1; r <= sec.rows; r++) for (let c = 1; c <= sec.cols; c++) {
                const st = (sec.cells[`${r}-${c}`] || {}).status || 'empty';
                if (st === 'available') av++; else if (st === 'occupied') oc++; else if (st === 'reserved') re++;
            }
            const block = document.getElementById('block_' + sec.id);
            if (!block) return;
            const metas = block.querySelectorAll('.section-head span[style]');
            if (metas[0]) metas[0].textContent = av + ' avail';
            if (metas[1]) metas[1].textContent = oc + ' occ';
            if (metas[2]) metas[2].textContent = re + ' res';
        }

        function updateStats() {
            let em = 0, av = 0, oc = 0, re = 0;
            sections.forEach(sec => {
                for (let r = 1; r <= sec.rows; r++) for (let c = 1; c <= sec.cols; c++) {
                    const st = (sec.cells[`${r}-${c}`] || {}).status || 'empty';
                    if (st === 'available') av++; else if (st === 'occupied') oc++; else if (st === 'reserved') re++; else em++;
                }
            });
            document.getElementById('s-empty').textContent = em;
            document.getElementById('s-available').textContent = av;
            document.getElementById('s-occupied').textContent = oc;
            document.getElementById('s-reserved').textContent = re;
            document.getElementById('s-total').textContent = em + av + oc + re;
        }

        // ── Section Delete Confirm Modal ──
        let pendingRemoveId = null;
        function removeSection(id) {
            const sec = sections.find(s => s.id === id);
            pendingRemoveId = id;
            document.getElementById('cdmSectionName').textContent = sec ? sec.name : '';
            document.getElementById('confirmDeleteModal').classList.add('open');
        }
        function closeSectionDeleteModal() {
            document.getElementById('confirmDeleteModal').classList.remove('open');
            pendingRemoveId = null;
        }
        function confirmSectionDelete() {
            if (!pendingRemoveId) return;
            const btn = document.getElementById('cdmConfirmBtn');
            btn.disabled = true; btn.textContent = 'Removing…';
            sections = sections.filter(s => s.id !== pendingRemoveId);
            persist();
            renderSections();
            closeSectionDeleteModal();
            btn.disabled = false; btn.textContent = 'Yes, remove it';
            showToast('Section removed.');
        }

        // ── Modal ──
        function openModal(secId, key, cell) {
            modalTarget = { secId, key };
            const sec = sections.find(s => s.id === secId);
            document.getElementById('modalTitle').textContent = `${sec?.name || ''} — ${key}`;
            document.getElementById('nicheLabel').value = cell.label || '';
            document.getElementById('nicheNotes').value = cell.notes || '';
            const st = cell.status || 'empty';
            ['empty', 'available', 'occupied', 'reserved'].forEach(s => {
                document.getElementById('sopt-' + s).classList.toggle('sel', s === st);
            });
            document.getElementById('nicheModal').classList.add('open');
        }
        function closeModal() { document.getElementById('nicheModal').classList.remove('open'); }
        function selStatus(s) {
            ['empty', 'available', 'occupied', 'reserved'].forEach(x => document.getElementById('sopt-' + x).classList.remove('sel'));
            document.getElementById('sopt-' + s).classList.add('sel');
        }
        function applyModal() {
            if (!modalTarget) return;
            const { secId, key } = modalTarget;
            const sec = sections.find(s => s.id === secId);
            const status = ['available', 'occupied', 'reserved'].find(s => document.getElementById('sopt-' + s).classList.contains('sel')) || 'empty';
            const label = document.getElementById('nicheLabel').value.trim() || null;
            const notes = document.getElementById('nicheNotes').value.trim() || null;
            if (status === 'empty') {
                delete sec.cells[key];
            } else {
                sec.cells[key] = { status, label, notes };
            }
            persist();
            // Re-render just this niche
            const niche = document.querySelector(`.niche[data-sec="${secId}"][data-key="${key}"]`);
            if (niche) {
                niche.className = 'niche ' + (status === 'empty' ? '' : status);
                niche.dataset.label = label || `${sec.name} ${key}`;
            }
            updateSectionMeta(sec);
            closeModal();
            showToast(label ? `"${label}" — ${status}` : `Niche ${key} → ${status}`);
        }

        // ── Save All (sends to server as cemetery plots) ──
        function saveAll() {
            const CSRF = document.querySelector('meta[name="csrf-token"]').content;
            let promises = [];
            sections.forEach(sec => {
                Object.entries(sec.cells).forEach(([key, cell]) => {
                    if (!cell.status || cell.status === 'empty') return;
                    const [row, col] = key.split('-');
                    const plotCode = `${sec.name.replace(/\s+/g, '-')}-R${row}-C${col}`;
                    // We'll batch-save via a simple POST for each occupied/reserved niche
                    // (Only saves ones that have status set — empty niches are not saved)
                });
            });
            // For now just persist to localStorage and show confirmation
            persist();
            showToast('✓ All changes saved to your browser!');
        }

        // ── Export CSV ──
        function exportCSV() {
            let rows = [['Section', 'Row', 'Column', 'Plot Code', 'Status', 'Label', 'Notes']];
            sections.forEach(sec => {
                for (let r = 1; r <= sec.rows; r++) {
                    for (let c = 1; c <= sec.cols; c++) {
                        const key = `${r}-${c}`;
                        const cell = sec.cells[key] || {};
                        const code = `${sec.name.replace(/\s+/g, '-')}-R${r}-C${c}`;
                        rows.push([sec.name, r, c, code, cell.status || 'empty', cell.label || '', cell.notes || '']);
                    }
                }
            });
            const csv = rows.map(r => r.map(v => `"${String(v).replace(/"/g, '""')}"`).join(',')).join('\n');
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a'); a.href = url; a.download = 'cemetery-grid.csv'; a.click();
            URL.revokeObjectURL(url);
            showToast('CSV exported!');
        }

        function esc(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }

        let toastT;
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            clearTimeout(toastT);
            toastT = setTimeout(() => t.classList.remove('show'), 2800);
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
            // Keyboard shortcuts
            if (e.key === '1') setPaint('available');
            if (e.key === '2') setPaint('occupied');
            if (e.key === '3') setPaint('reserved');
            if (e.key === '4') setPaint('erase');
        });

        // ── Boot ──
        load();
        renderSections();
    </script>

</body>

</html>