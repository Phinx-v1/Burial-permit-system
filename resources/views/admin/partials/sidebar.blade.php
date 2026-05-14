<style>
    :root {
        --sb-width: 220px;
        --sb-width-collapsed: 68px;
        --sb-transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    html.collapsed {
        --sb-width: var(--sb-width-collapsed);
    }

    /* Isolated Reset: Guarantees sidebar stays exactly the same on pages without global resets (like Cemetery Map) */
    .sidebar, .sidebar *, .sidebar *::before, .sidebar *::after {
        box-sizing: border-box !important;
    }

    .sidebar {
        width: var(--sb-width);
        min-height: 100vh;
        background: #1a2744;
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 50;
        transition: var(--sb-transition);
        overflow: hidden;
    }

    /* Push main content to respect sidebar width */
    .main {
        margin-left: var(--sb-width) !important;
        transition: var(--sb-transition) !important;
    }


    /* Small screens or explicit hide */
    @media (max-width: 768px) {
        html.collapsed .sidebar { transform: translateX(-100%); }
        html.collapsed .main { margin-left: 0 !important; }
    }

    .sidebar-brand {
        padding: 1.25rem 17px 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, .08);
        height: 88px; /* Fixed height */
        box-sizing: border-box !important;
        overflow: hidden;
        white-space: nowrap;
        cursor: pointer;
        transition: background 0.15s;
    }

    .sidebar-brand:hover {
        background: rgba(255, 255, 255, 0.03);
    }


    .sidebar-brand-top {
        display: flex;
        align-items: center;
        justify-content: flex-start; /* Strictly left-aligned */
        gap: 10px;
        margin-bottom: .3rem;
    }

    .sidebar-brand-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }

    .sidebar-seal {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 1.5px solid rgba(255, 255, 255, 0.2);
    }

    /* Burger Toggle Button - REMOVED per user request */
    .sb-toggle {
        display: none;
    }


    /* Locked Brand Title Style */
    .sidebar-brand-left h1 {
        font-family: 'DM Sans', sans-serif !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #fff !important;
        line-height: 1.3 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
        transition: opacity 0.2s;
    }

    .sidebar-brand p {
        font-family: 'DM Sans', sans-serif;
        font-size: 10px;
        color: rgba(255, 255, 255, .4);
        margin: 0 !important;
        margin-top: 4px !important; /* Increased gap */
        padding: 0 !important;
        padding-left: 44px !important;
        box-sizing: border-box !important;
        transition: opacity 0.2s;
    }

    html.collapsed .sidebar-brand-left h1,
    html.collapsed .sidebar-brand p {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.1s;
    }

    html.collapsed .sidebar-brand {
        padding: 1.25rem 17px;
    }

    html.collapsed .sidebar-brand-top {
        justify-content: flex-start;
    }

    /* Force the inner flex container to stay left-aligned when collapsed to keep the logo fixed */
    html.collapsed .sidebar-brand-left {
        justify-content: flex-start;
    }

    html.collapsed .sb-toggle {
        display: none !important;
    }

    .sidebar-nav {
        flex: 1;
        padding: .75rem 0;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Allow tooltips to pop out when collapsed */
    html.collapsed .sidebar { overflow: visible !important; }
    html.collapsed .sidebar-nav { overflow: visible !important; }

    .nav-section {
        font-family: 'DM Sans', sans-serif;
        font-size: 9px;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .3);
        padding: .5rem 14px .2rem 1rem; /* Matched to nav items */
        transition: all 0.2s;
        white-space: nowrap;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
    }

    .nav-section:hover {
        color: rgba(255, 255, 255, .6);
    }

    html.collapsed .nav-section {
        opacity: 0;
        pointer-events: none;
    }

    .section-chevron {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0.5;
        flex-shrink: 0;
    }

    .nav-group.is-collapsed .section-chevron {
        transform: rotate(180deg);
    }

    .nav-section-content {
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s;
        max-height: 1000px;
        overflow: hidden;
    }

    .nav-group.is-collapsed .nav-section-content {
        max-height: 0;
        opacity: 0;
        pointer-events: none;
    }

    /* Pre-spaced Nav Items (Constant Weight) */
    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: .65rem 14px; /* Centered in 68px bar: 12px margin + 14px padding = 26px offset (Center for ~16px icons) */
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        color: rgba(255, 255, 255, .65);
        text-decoration: none;
        border-radius: 8px;
        margin: 2px .75rem;
        transition: var(--sb-transition);
        font-weight: 500;
        white-space: nowrap;
    }

    html.collapsed .nav-item {
        margin: 2px 0.75rem;
        padding: 0.65rem 14px;
        justify-content: flex-start;
        gap: 0;
    }

    .nav-item span {
        transition: opacity 0.2s, transform 0.2s;
    }

    html.collapsed .nav-item {
        position: relative; /* Anchor for the tooltip */
    }

    html.collapsed .nav-item span {
        opacity: 0;
        display: block !important;
        position: absolute;
        left: calc(100% + 15px); /* Push completely outside the sidebar */
        top: 50%;
        transform: translateY(-50%) translateX(-10px); /* Start slightly inward for the slide effect */
        background: #3b82f6; /* Matching accent color */
        color: #fff;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
        pointer-events: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
    }

    /* Small pointer triangle pointing towards the icon */
    html.collapsed .nav-item span::before {
        content: '';
        position: absolute;
        left: -4px;
        top: 50%;
        transform: translateY(-50%);
        border-width: 5px 5px 5px 0;
        border-style: solid;
        border-color: transparent #3b82f6 transparent transparent;
    }

    /* Show tooltip on hover */
    html.collapsed .nav-item:hover span {
        opacity: 1;
        transform: translateY(-50%) translateX(0); /* Slide into position */
    }

    .nav-item:hover {
        background: rgba(255, 255, 255, .08);
        color: #fff;
    }

    .nav-item.active {
        background: rgba(255, 255, 255, .12);
        color: #fff;
    }

    .nav-item svg {
        flex-shrink: 0;
        opacity: .7;
        width: 18px;
        height: 18px;
    }

    .nav-item.active svg {
        opacity: 1;
    }

    .sidebar-footer {
        padding: .75rem;
        border-top: 1px solid rgba(255, 255, 255, .08);
        transition: var(--sb-transition);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: .6rem .75rem;
        background: rgba(255, 255, 255, .04);
        border-radius: 8px;
        margin-bottom: .6rem;
        transition: var(--sb-transition);
        overflow: hidden;
    }

    html.collapsed .user-info {
        padding: 0.6rem 6px; /* Centered 32px avatar: 12px footer padding + 6px = 18px offset */
        justify-content: flex-start;
        background: transparent;
    }

    .user-info > div:last-child {
        transition: opacity 0.2s;
        white-space: nowrap;
    }

    html.collapsed .user-info > div:last-child {
        opacity: 0;
        width: 0;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, .1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        flex-shrink: 0;
        overflow: hidden;
        position: relative;
    }

    .user-name {
        font-size: 12px;
        color: #fff;
        font-weight: 500;
    }

    .user-role {
        font-size: 10px;
        color: rgba(255, 255, 255, .4);
    }

    .user-actions {
        display: flex;
        gap: 10px;
        margin-left: auto;
        opacity: 1;
        transition: opacity 0.2s;
    }

    .action-btn {
        color: rgba(255, 255, 255, 0.35);
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    html.collapsed .user-actions {
        opacity: 0;
        pointer-events: none;
    }

    .btn-logout {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: .65rem;
        background: rgba(239, 68, 68, .1);
        border: 1px solid rgba(239, 68, 68, .2);
        color: #ef4444;
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all .2s;
    }

    html.collapsed .btn-logout {
        justify-content: flex-start;
        padding: 0.65rem 15.5px; /* Center 13px icon: 12px padding + 15.5px = 27.5px offset */
    }

    html.collapsed .btn-logout span { display: none; }

    .btn-logout:hover {
        border-color: #ef4444;
        color: #ef4444;
        background: rgba(239, 68, 68, 0.05);
    }

    /* Dark mode toggle button */
    .dark-toggle {
        width: 100%;
        background: none;
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 8px;
        padding: .5rem .75rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 11px;
        color: rgba(255, 255, 255, .45);
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: .6rem;
    }

    html.collapsed .dark-toggle #darkToggleLabel { display: none; }
    html.collapsed .dark-toggle {
        justify-content: flex-start;
        padding: 8px 6px; /* Center 32px pill: 12px padding + 6px = 18px offset */
    }

    .dark-toggle:hover {
        border-color: rgba(255, 255, 255, .2);
        color: rgba(255, 255, 255, .8);
    }

    .dark-toggle-pill {
        width: 30px;
        height: 16px;
        background: rgba(255, 255, 255, .15);
        border-radius: 8px;
        position: relative;
        transition: background .2s;
        flex-shrink: 0;
    }

    .dark-toggle-pill::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 12px;
        height: 12px;
        background: #fff;
        border-radius: 50%;
        transition: transform .2s;
    }

    html.dark .dark-toggle-pill {
        background: #6366f1;
    }

    html.dark .dark-toggle-pill::after {
        transform: translateX(14px);
    }

    /* ═══ GLOBAL DARK MODE STYLES ═══
       Applied to EVERY page via this shared partial */
    html.dark body { background: #0f1117 !important; color: #e2e8f0 !important; }
    html.dark .main { background: #0f1117 !important; }

    /* Topbar */
    html.dark .topbar { background: #1a1d27 !important; border-bottom-color: #2d3148 !important; }
    html.dark .topbar-title, html.dark .topbar-left .topbar-title { color: #e2e8f0 !important; }
    html.dark .topbar-date { color: #64748b !important; }
    html.dark .role-tag { background: #4f46e5 !important; }

    /* Panels / Cards */
    html.dark .panel, html.dark .upload-card, html.dark .stat-card, html.dark .card, html.dark .docs-card, html.dark .guide-card { background: #1e2130 !important; border-color: #2d3148 !important; }
    html.dark .panel-head, html.dark .panel-header, html.dark .card-head, html.dark .docs-head { background: #181b29 !important; border-bottom-color: #2d3148 !important; }
    html.dark .panel-head h3, html.dark .panel-header h3, html.dark .card-head-title, html.dark .upload-card h3, html.dark .docs-head-title { color: #e2e8f0 !important; }
    html.dark .panel-head-sub, html.dark .panel-head a, html.dark .docs-head-sub { color: #64748b !important; }
    html.dark .view-all { color: #818cf8 !important; }

    /* Tables */
    html.dark table th { background: #181b29 !important; color: #64748b !important; }
    html.dark table td { color: #cbd5e1 !important; border-top-color: #2d3148 !important; }
    html.dark tr.row-expired td { background: #2a1515 !important; border-top-color: #7f1d1d !important; }
    html.dark tr.row-expired td:first-child { border-left-color: #ef4444 !important; }
    html.dark .empty-row td, html.dark .empty-state { color: #4b5563 !important; }

    /* Permit number & file name blue links */
    html.dark .permit-no { color: #818cf8 !important; }

    /* Badges */
    html.dark .badge-yellow { background: #422006 !important; color: #fde68a !important; }
    html.dark .badge-green { background: #052e16 !important; color: #86efac !important; }
    html.dark .badge-blue { background: #0c1a4a !important; color: #93c5fd !important; }
    html.dark .badge-red { background: #450a0a !important; color: #fca5a5 !important; }
    html.dark .badge-male { background: #0c1a4a !important; color: #93c5fd !important; }
    html.dark .badge-female { background: #3b0764 !important; color: #d8b4fe !important; }

    /* Buttons */
    html.dark .btn-action { background: #252840 !important; border-color: #2d3148 !important; color: #94a3b8 !important; }
    html.dark .btn-action:hover { border-color: #6366f1 !important; color: #818cf8 !important; background: #1e2d6b !important; }
    html.dark .btn-renew { background: #2a0a0a !important; border-color: #7f1d1d !important; color: #fca5a5 !important; }
    html.dark .btn-print { background: #0c1a2e !important; border-color: #1e3a5f !important; color: #7dd3fc !important; }
    html.dark .btn-view { background: #252840 !important; border-color: #2d3148 !important; color: #94a3b8 !important; }
    html.dark .btn-view:hover { border-color: #6366f1 !important; color: #818cf8 !important; }
    html.dark .btn-upload { background: #4f46e5 !important; }
    html.dark .btn-upload:hover { background: #4338ca !important; }

    /* Sort links */
    html.dark .sort-link { color: #64748b !important; }
    html.dark .sort-link:hover, html.dark .sort-link.active { color: #818cf8 !important; }

    /* Search inputs */
    html.dark .search-input { background: #252840 !important; border-color: #2d3148 !important; color: #e2e8f0 !important; }
    html.dark .search-input:focus { border-color: #6366f1 !important; }

    /* Pagination */
    html.dark .pager-info { color: #64748b !important; }
    html.dark .pager-btn { background: #1e2130 !important; border-color: #2d3148 !important; color: #94a3b8 !important; }
    html.dark .pager-btn:hover { border-color: #6366f1 !important; color: #818cf8 !important; }
    html.dark .pager-btn.active { background: #6366f1 !important; border-color: #6366f1 !important; color: #fff !important; }
    html.dark .pager-btn.disabled { color: #374151 !important; border-color: #2d3148 !important; }

    /* Dropzone */
    html.dark .dropzone { border-color: #374151 !important; background: #181b29 !important; }
    html.dark .dropzone:hover, html.dark .dropzone.drag-over { border-color: #6366f1 !important; background: #1e2d6b !important; }
    html.dark .dropzone-icon { background: #252840 !important; }
    html.dark .dropzone-title { color: #cbd5e1 !important; }
    html.dark .dropzone-sub { color: #64748b !important; }
    html.dark .dropzone-file { color: #818cf8 !important; }
    html.dark .upload-note { color: #64748b !important; }

    /* Upload history specific */
    html.dark .reasons-toggle { color: #818cf8 !important; }
    html.dark .reasons-list { color: #94a3b8 !important; }

    /* Stat cards */
    html.dark .sc-label { color: #64748b !important; }
    html.dark .sc-main { color: #cbd5e1 !important; }
    html.dark .sc-sub { color: #64748b !important; }
    html.dark .sc-value.navy { color: #818cf8 !important; }
    html.dark .sc-value.red { color: #f87171 !important; }
    html.dark .sc-value.amber { color: #fbbf24 !important; }
    html.dark .sc-value.orange { color: #fb923c !important; }

    /* Forms / Modals */
    html.dark .modal { background: #1e2130 !important; }
    html.dark .modal-body { background: #1e2130 !important; }
    html.dark .modal-footer { background: #181b29 !important; border-top-color: #2d3148 !important; }
    html.dark .form-control, html.dark select.form-control { background: #252840 !important; border-color: #2d3148 !important; color: #e2e8f0 !important; }
    html.dark .form-control:focus { border-color: #6366f1 !important; }
    html.dark .form-label, html.dark .field-label { color: #94a3b8 !important; }
    html.dark .section-divider, html.dark .divider-label { color: #818cf8 !important; border-bottom-color: #2d3148 !important; }
    html.dark .fee-row { border-color: #2d3148 !important; background: #1e2130 !important; }
    html.dark .fee-row:hover { background: #1e2d6b !important; border-color: #6366f1 !important; }
    html.dark .fee-row label { color: #cbd5e1 !important; }
    html.dark .fee-amount { color: #818cf8 !important; }
    html.dark .btn-cancel { background: #252840 !important; border-color: #2d3148 !important; color: #94a3b8 !important; }

    /* Guide card on import page */
    html.dark .guide-card { background: #0c1a4a !important; border-color: #1e3a8a !important; }
    html.dark .guide-card h3 { color: #93c5fd !important; }
    html.dark .col-tag { background: #0c1a4a !important; color: #93c5fd !important; border-color: #1e3a8a !important; }
    html.dark .col-tag.required { background: #422006 !important; color: #fde68a !important; border-color: #854d0e !important; }
    html.dark .guide-note { color: #60a5fa !important; }

    /* Info rows (deceased show page) */
    html.dark .info-item { border-bottom-color: #2d3148 !important; }
    html.dark .info-label { color: #64748b !important; }
    html.dark .info-value { color: #e2e8f0 !important; }
    html.dark .info-value.empty { color: #374151 !important; }

    /* ── SCROLLBAR ──────────────────────────────────────────────── */
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-track { background: transparent; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, .15); border-radius: 2px; }
    .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, .25); }
</style>

<aside class="sidebar">
    <div class="sidebar-brand" onclick="toggleSidebar()" title="Toggle Sidebar (Alt + B)">
        <div class="sidebar-brand-top">
            <div class="sidebar-brand-left">
                <img src="{{ asset('images/carmen-seal.png') }}" alt="Carmen Seal" class="sidebar-seal">
                <h1>LGU Carmen<br>Burial System</h1>
            </div>
        </div>
        <p>Municipal Civil Registrar</p>
    </div>


    <nav class="sidebar-nav">
        <div class="nav-group" id="group-overview">
            <div class="nav-section" onclick="toggleSection(this)">
                OVERVIEW
                <svg class="section-chevron" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
            </div>
            <div class="nav-section-content">
                    <a href="{{ auth()->user()->role === 'super_admin' ? route('superadmin.dashboard') : route('dashboard') }}"
                        class="nav-item {{ request()->routeIs('dashboard') || request()->routeIs('superadmin.dashboard') ? 'active' : '' }}"
                        title="Dashboard (Alt + 1)">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        @if(auth()->user()->role === 'super_admin')
            <div class="nav-group" id="group-analytics">
                <div class="nav-section" onclick="toggleSection(this)">
                    ANALYTICS
                    <svg class="section-chevron" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                </div>
                <div class="nav-section-content">
                    <a href="{{ route('superadmin.reports') }}"
                        class="nav-item {{ request()->routeIs('superadmin.reports') ? 'active' : '' }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('superadmin.activity') }}"
                        class="nav-item {{ request()->routeIs('superadmin.activity') ? 'active' : '' }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>
                        <span>Activity Log</span>
                    </a>
                </div>
            </div>
        @else
            <div class="nav-group" id="group-permits">
                <div class="nav-section" onclick="toggleSection(this)">
                    PERMITS
                    <svg class="section-chevron" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                </div>
                <div class="nav-section-content">
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'cemetery_manager')
                    <a href="{{ route('permits.index') }}" class="nav-item {{ request()->routeIs('permits.*') ? 'active' : '' }}"
                        title="Burial Permits (Alt + 2)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        <span>Burial Permits</span>
                    </a>
                    @endif
                </div>
            </div>

            <div class="nav-group" id="group-cemetery">
                <div class="nav-section" onclick="toggleSection(this)">
                    CEMETERY
                    <svg class="section-chevron" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                </div>
                <div class="nav-section-content">
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'cemetery_manager')
                    <a href="{{ route('cemetery.map') }}" class="nav-item {{ request()->routeIs('cemetery.*') ? 'active' : '' }}"
                        title="Cemetery Map (Alt + 3)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span>Cemetery Map</span>
                    </a>
                    @endif
                </div>
            </div>

            <div class="nav-group" id="group-tools">
                <div class="nav-section" onclick="toggleSection(this)">
                    TOOLS
                    <svg class="section-chevron" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                </div>
                <div class="nav-section-content">
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                        title="Reports (Alt + 4)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('import.show') }}" class="nav-item {{ request()->routeIs('import.*') ? 'active' : '' }}"
                        title="Import Excel (Alt + 5)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        <span>Import Excel</span>
                    </a>
                    @endif
                </div>
            </div>

            <div class="nav-group" id="group-system">
                <div class="nav-section" onclick="toggleSection(this)">
                    SYSTEM
                    <svg class="section-chevron" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                </div>
                <div class="nav-section-content">
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dataquality') }}"
                        class="nav-item {{ request()->routeIs('admin.dataquality') ? 'active' : '' }}"
                        title="Data Quality (Alt + 6)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M9 11l3 3L22 4" />
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                        </svg>
                        <span>Data Quality</span>
                    </a>
                    @endif
                </div>
            </div>
        @endif

    </nav>

    <div class="sidebar-footer">
        {{-- Dark mode toggle --}}
        <button class="dark-toggle" onclick="toggleDark()" id="darkToggleBtn">
            <span id="darkToggleLabel">🌙 Dark Mode</span>
            <div class="dark-toggle-pill" id="darkPill"></div>
        </button>

        <div class="user-info">
            <div class="user-avatar" id="sidebarAvatar">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role === 'cemetery_manager' ? 'Cemetery Manager' : ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
            </div>

            <div class="user-actions">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('settings.index') }}" class="action-btn" title="Settings">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="3" />
                            <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
                        </svg>
                    </a>
                @endif
                <a href="{{ route('support.manual') }}" class="action-btn" title="User Manual">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </a>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</aside>

<script>
    // ── Dark mode & Sidebar persistence ──
    (function () {
        if (localStorage.getItem('lgu_dark') === '1') {
            document.documentElement.classList.add('dark');
        }
        if (localStorage.getItem('lgu_sidebar_collapsed') === '1') {
            document.documentElement.classList.add('collapsed');
        }
    })();

    function toggleDark() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('lgu_dark', isDark ? '1' : '0');
    }

    function toggleSidebar() {
        const isCollapsed = document.documentElement.classList.toggle('collapsed');
        localStorage.setItem('lgu_sidebar_collapsed', isCollapsed ? '1' : '0');
    }

    function toggleSection(header) {
        const group = header.closest('.nav-group');
        group.classList.toggle('is-collapsed');
        
        // Save state to localStorage
        const groupId = group.id;
        const isCollapsed = group.classList.contains('is-collapsed');
        localStorage.setItem('lgu_nav_' + groupId, isCollapsed ? '1' : '0');
    }

    // Restore section states
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.nav-group').forEach(group => {
            if (localStorage.getItem('lgu_nav_' + group.id) === '1') {
                group.classList.add('is-collapsed');
            }
        });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', e => {
        // Alt + B to toggle sidebar
        if (e.altKey && e.key.toLowerCase() === 'b') {
            toggleSidebar();
        }
        // Alt + 1, 2, 3... for nav items
        if (e.altKey && !isNaN(e.key)) {
            const index = parseInt(e.key) - 1;
            const items = document.querySelectorAll('.nav-item');
            if (items[index]) items[index].click();
        }
    });
</script>
