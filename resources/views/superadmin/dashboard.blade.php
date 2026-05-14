<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>

        
        :root {
            --navy:       #0f1e3d;
            --navy-mid:   #1a2f5e;
            --navy-light: #243459;
            --accent:     #3b82f6;
            --accent-bg:  #eff6ff;
            --red:        #ef4444;
            --amber:      #f59e0b;
            --green:      #10b981;
            --violet:     #8b5cf6;
            --cyan:       #06b6d4;
            --teal:       #14b8a6;
            --surface:    #ffffff;
            --surface-2:  #f8fafc;
            --border:     #e2e8f0;
            --border-2:   #f1f5f9;
            --text-1:     #0f172a;
            --text-2:     #475569;
            --text-3:     #94a3b8;
            --mono:       'DM Mono', monospace;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface-2);
            color: var(--text-1);
            -webkit-font-smoothing: antialiased;
            /* Prevent content shift when scrollbar appears */
            scrollbar-gutter: stable;
        }

        .main { width: 100%; display: flex; flex-direction: column; min-width: 0; background: var(--surface-2); }   
        /* ── TOPBAR ── */
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 56px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.75rem; }
        .topbar-left { display: flex; flex-direction: column; gap: 1px; }
        .topbar-title { font-size: 15px; font-weight: 600; color: var(--text-1); letter-spacing: -.01em; }
        .topbar-date  { font-size: 11px; color: var(--text-3); font-weight: 400; }
        .topbar-right { display: flex; align-items: center; gap: .75rem; }

        .role-pill {
            font-family: var(--mono);
            font-size: 10px; font-weight: 500;
            color: var(--accent);
            background: var(--accent-bg);
            border: 1px solid #bfdbfe;
            padding: 3px 10px; border-radius: 20px;
            letter-spacing: .06em; text-transform: uppercase;
        }
        .role-pill.super {
            color: #7c3aed;
            background: #f5f3ff;
            border-color: #ddd6fe;
        }

        .btn-new {
            display: inline-flex; align-items: center; gap: 6px;
            padding: .45rem 1rem; background: var(--navy); color: #fff;
            border: none; border-radius: 8px;
            font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;
            cursor: pointer; transition: background .15s, transform .12s; text-decoration: none;
        }
        .btn-new:hover { background: var(--navy-light); transform: translateY(-1px); }

        /* ── CONTENT ── */
        .content { padding: 1.75rem; display: flex; flex-direction: column; gap: 1rem; }

        /* ── HERO ── */
        .hero {
            background: var(--navy);
            border-radius: 16px; padding: 1.5rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; top: -80px; right: -40px;
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(59,130,246,.12) 0%, transparent 65%);
            pointer-events: none;
        }
        .hero::after {
            content: ''; position: absolute; bottom: -60px; left: 25%;
            width: 280px; height: 180px;
            background: radial-gradient(ellipse, rgba(29,78,216,.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-text h2 { font-size: 20px; font-weight: 600; color: #fff; letter-spacing: -.025em; }
        .hero-text p  { font-size: 12px; color: rgba(255,255,255,.4); margin-top: .3rem; font-weight: 300; font-family: var(--mono); letter-spacing: .02em; }
        .hero-actions { display: flex; gap: .6rem; flex-wrap: wrap; flex-shrink: 0; position: relative; z-index: 1; }

        .btn-export {
            display: inline-flex; align-items: center; gap: 6px;
            padding: .45rem 1rem; border-radius: 8px;
            font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;
            cursor: pointer; text-decoration: none; transition: all .15s;
            background: rgba(255,255,255,.1); color: rgba(255,255,255,.85);
            border: 1px solid rgba(255,255,255,.15);
        }
        .btn-export:hover { background: rgba(255,255,255,.2); }
        .btn-export.primary {
            background: var(--accent); color: #fff; border-color: var(--accent);
        }
        .btn-export.primary:hover { background: #2563eb; }

        /* ── STAT GRID ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px; padding: 1.1rem 1.25rem;
            display: flex; flex-direction: column; gap: .5rem;
            transition: box-shadow .2s, transform .2s; cursor: default;
        }
        .stat-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.07); transform: translateY(-2px); }
        .stat-top { display: flex; align-items: center; justify-content: space-between; }
        .stat-icon {
            width: 34px; height: 34px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-icon.blue   { background: #eff6ff; }
        .stat-icon.green  { background: #f0fdf4; }
        .stat-icon.amber  { background: #fffbeb; }
        .stat-icon.red    { background: #fef2f2; }
        .stat-icon.cyan   { background: #ecfeff; }
        .stat-icon.violet { background: #f5f3ff; }
        .stat-icon.rose   { background: #fff1f2; }
        .stat-icon.teal   { background: #f0fdfa; }

        .stat-pill {
            font-family: var(--mono); font-size: 9px; font-weight: 600;
            padding: 2px 7px; border-radius: 20px;
            letter-spacing: .04em; text-transform: uppercase;
        }
        .stat-pill.ok  { background: #f0fdf4; color: #15803d; }
        .stat-pill.bad { background: #fef2f2; color: #dc2626; }
        .stat-pill.neu { background: var(--surface-2); color: var(--text-3); }
        .stat-pill.warn { background: #fffbeb; color: #92400e; }

        .stat-value { font-size: 34px; font-weight: 700; letter-spacing: -.04em; line-height: 1; }
        .stat-value.blue   { color: var(--navy); }
        .stat-value.green  { color: var(--green); }
        .stat-value.amber  { color: var(--amber); }
        .stat-value.red    { color: var(--red); }
        .stat-value.cyan   { color: var(--cyan); }
        .stat-value.violet { color: var(--violet); }
        .stat-value.rose   { color: #f43f5e; }
        .stat-value.teal   { color: var(--teal); }

        .stat-label { font-size: 12px; color: var(--text-2); font-weight: 400; }
        .stat-sub   { font-size: 11px; color: var(--text-3); font-family: var(--mono); }

        /* ── CHART GRID ── */
        .chart-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 1rem; }

        /* ── PANEL ── */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px; overflow: hidden;
        }
        .panel-head {
            padding: .9rem 1.25rem; border-bottom: 1px solid var(--border-2);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-title { font-size: 13px; font-weight: 600; color: var(--text-1); letter-spacing: -.01em; }
        .panel-sub   { font-size: 11px; color: var(--text-3); font-family: var(--mono); margin-top: 2px; }
        .panel-body  { padding: 1.25rem; }

        .link-arrow {
            font-size: 12px; font-weight: 500; color: var(--accent);
            text-decoration: none; display: inline-flex; align-items: center; gap: 3px;
            white-space: nowrap; transition: gap .15s;
        }
        .link-arrow:hover { gap: 6px; }

        /* ── CHART BODY ── */
        .chart-body { padding: 1rem 1.25rem 1.25rem; }

        /* ── DOUGHNUT LEGEND ── */
        .legend-list { padding: .5rem 1.25rem 1.25rem; display: flex; flex-direction: column; gap: 0; }
        .legend-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: .5rem 0; border-bottom: 1px solid var(--border-2);
        }
        .legend-row:last-child { border: none; }
        .legend-left { display: flex; align-items: center; gap: 8px; }
        .legend-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
        .legend-label { font-size: 12px; color: var(--text-2); }
        .legend-right { display: flex; align-items: center; gap: 8px; }
        .legend-count { font-size: 14px; font-weight: 700; color: var(--text-1); font-family: var(--mono); }
        .legend-pct   { font-size: 11px; color: var(--text-3); width: 32px; text-align: right; font-family: var(--mono); }

        /* ── BOTTOM ROW ── */
        .bottom-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        /* ── ACTIVITY FEED ── */
        .activity-feed { display: flex; flex-direction: column; }
        .activity-item {
            display: flex; align-items: flex-start; gap: .75rem;
            padding: .7rem 1.25rem; border-top: 1px solid var(--border-2);
            transition: background .15s;
        }
        .activity-item:first-child { border-top: none; }
        .activity-item:hover { background: var(--surface-2); }

        .activity-dot {
            width: 28px; height: 28px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; flex-shrink: 0; margin-top: 1px;
        }
        .dot-green  { background: #dcfce7; color: #16a34a; }
        .dot-indigo { background: #e0e7ff; color: #4338ca; }
        .dot-teal   { background: #ccfbf1; color: #0d9488; }
        .dot-red    { background: #fee2e2; color: #dc2626; }
        .dot-gray   { background: var(--surface-2); color: var(--text-3); border: 1px solid var(--border); }

        .activity-body { flex: 1; min-width: 0; }
        .activity-desc {
            font-size: 12.5px; color: var(--text-2); line-height: 1.45;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .activity-desc strong { color: var(--text-1); font-weight: 600; }
        .activity-meta {
            display: flex; align-items: center; gap: 5px;
            font-size: 10.5px; color: var(--text-3); margin-top: 3px; font-family: var(--mono);
        }
        .activity-meta-sep { opacity: .4; }

        /* ── BADGES ── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 20px;
            font-family: var(--mono); letter-spacing: .03em; white-space: nowrap;
        }
        .badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-yellow .badge-dot { background: #ca8a04; }
        .badge-green  { background: #dcfce7; color: #166534; }
        .badge-green  .badge-dot { background: #16a34a; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-blue   .badge-dot { background: #3b82f6; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-red    .badge-dot { background: #ef4444; }

        /* ── PROGRESS BARS ── */
        .breakdown-body { padding: .75rem 1.25rem 1.1rem; display: flex; flex-direction: column; gap: .65rem; }
        .prog-labels { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px; }
        .prog-name   { color: var(--text-2); font-weight: 500; }
        .prog-count  { color: var(--text-3); font-family: var(--mono); font-size: 10px; }
        .prog-track  { height: 4px; background: var(--border-2); border-radius: 10px; overflow: hidden; }
        .prog-fill   { height: 100%; border-radius: 10px; transition: width .7s cubic-bezier(.4,0,.2,1); }

        /* ── EMPTY STATE ── */
        .empty-activity { padding: 2rem 1.25rem; text-align: center; font-size: 12px; color: var(--text-3); }

        /* ── ANIMATIONS ── */
        .fade-up { opacity: 0; transform: translateY(12px); animation: fadeUp .4s cubic-bezier(.4,0,.2,1) forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: none; } }
        .d1 { animation-delay: .04s; } .d2 { animation-delay: .08s; }
        .d3 { animation-delay: .12s; } .d4 { animation-delay: .16s; }
        .d5 { animation-delay: .20s; } .d6 { animation-delay: .24s; }
        .d7 { animation-delay: .28s; } .d8 { animation-delay: .32s; }
        .d9 { animation-delay: .36s; } .d10 { animation-delay: .40s; }

        @media (max-width: 1300px) {
            .stat-grid  { grid-template-columns: repeat(4, 1fr); }
            .chart-grid { grid-template-columns: 1fr; }
            .bottom-row { grid-template-columns: 1fr; }
        }

        /* ══════════════════════════════
        DARK MODE
        ══════════════════════════════ */
        html.dark body { background: #0f1117 !important; color: #e2e8f0 !important; }
        html.dark .main { background: #0f1117 !important; }
        html.dark .topbar { background: #1a1d27 !important; border-bottom-color: #2d3148 !important; }
        html.dark .topbar-title { color: #e2e8f0 !important; }
        html.dark .topbar-date  { color: #64748b !important; }
        html.dark .role-pill { background: #1e2d6b !important; color: #818cf8 !important; border-color: #374191 !important; }
        html.dark .role-pill.super { background: #2e1065 !important; color: #c4b5fd !important; border-color: #4c1d95 !important; }
        html.dark .hero { background: #111827 !important; }
        html.dark .stat-card { background: #1e2130 !important; border-color: #2d3148 !important; }
        html.dark .stat-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.3) !important; }
        html.dark .stat-label { color: #94a3b8 !important; }
        html.dark .stat-sub   { color: #64748b !important; }
        html.dark .stat-icon.blue   { background: #1e2d6b !important; }
        html.dark .stat-icon.green  { background: #052e16 !important; }
        html.dark .stat-icon.amber  { background: #422006 !important; }
        html.dark .stat-icon.red    { background: #450a0a !important; }
        html.dark .stat-icon.cyan   { background: #083344 !important; }
        html.dark .stat-icon.violet { background: #2e1065 !important; }
        html.dark .stat-icon.rose   { background: #4c0519 !important; }
        html.dark .stat-icon.teal   { background: #042f2e !important; }
        html.dark .stat-pill.neu  { background: #252840 !important; color: #64748b !important; }
        html.dark .stat-pill.ok   { background: #052e16 !important; color: #86efac !important; }
        html.dark .stat-pill.bad  { background: #450a0a !important; color: #fca5a5 !important; }
        html.dark .stat-pill.warn { background: #422006 !important; color: #fde68a !important; }
        html.dark .panel { background: #1e2130 !important; border-color: #2d3148 !important; }
        html.dark .panel-head { background: #181b29 !important; border-bottom-color: #2d3148 !important; }
        html.dark .panel-title { color: #e2e8f0 !important; }
        html.dark .panel-sub   { color: #64748b !important; }
        html.dark .link-arrow  { color: #818cf8 !important; }
        html.dark .legend-row  { border-bottom-color: #2d3148 !important; }
        html.dark .legend-label { color: #94a3b8 !important; }
        html.dark .legend-count { color: #e2e8f0 !important; }
        html.dark .legend-pct   { color: #64748b !important; }
        html.dark .activity-item { border-top-color: #2d3148 !important; }
        html.dark .activity-item:hover { background: #252840 !important; }
        html.dark .activity-desc { color: #cbd5e1 !important; }
        html.dark .activity-desc strong { color: #e2e8f0 !important; }
        html.dark .activity-meta { color: #64748b !important; }
        html.dark .dot-green  { background: #052e16 !important; color: #86efac !important; }
        html.dark .dot-indigo { background: #1e2d6b !important; color: #a5b4fc !important; }
        html.dark .dot-teal   { background: #042f2e !important; color: #5eead4 !important; }
        html.dark .dot-red    { background: #450a0a !important; color: #fca5a5 !important; }
        html.dark .dot-gray   { background: #252840 !important; color: #64748b !important; border-color: #2d3148 !important; }
        html.dark .badge-yellow { background: #422006 !important; color: #fde68a !important; }
        html.dark .badge-green  { background: #052e16 !important; color: #86efac !important; }
        html.dark .badge-blue   { background: #0c1a4a !important; color: #93c5fd !important; }
        html.dark .badge-red    { background: #450a0a !important; color: #fca5a5 !important; }
        html.dark .prog-name  { color: #94a3b8 !important; }
        html.dark .prog-count { color: #64748b !important; }
        html.dark .prog-track { background: #252840 !important; }
        html.dark .empty-activity { color: #4b5563 !important; }

        /* ── Floating Action Button (FAB) ── */
        .fab { position: fixed; bottom: 6rem; right: 30px; width: 56px; height: 56px; background: #1a2744; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.25); cursor: pointer; z-index: 1000; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-decoration: none; border: none; outline: none; }
        .fab:hover { transform: scale(1.1) rotate(90deg); background: #2563eb; box-shadow: 0 6px 16px rgba(0,0,0,0.3); }
        .fab svg { width: 24px; height: 24px; }
        html.dark .fab { background: #3b82f6; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
        html.dark .fab:hover { background: #60a5fa; }
        html.dark .btn-export { background: rgba(255,255,255,.07) !important; border-color: rgba(255,255,255,.12) !important; }
        html.dark .btn-export.primary { background: #3b82f6 !important; border-color: #3b82f6 !important; }

        /* ── DARK MODE STAT CARDS ── */
html.dark .stat-card { background: #1e2130 !important; border-color: #2d3148 !important; }
html.dark .stat-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.3) !important; }
html.dark .stat-label { color: #94a3b8 !important; }
html.dark .stat-sub   { color: #64748b !important; }
html.dark .stat-icon.blue   { background: #1e2d6b !important; }
html.dark .stat-icon.green  { background: #052e16 !important; }
html.dark .stat-icon.amber  { background: #422006 !important; }
html.dark .stat-icon.red    { background: #450a0a !important; }
html.dark .stat-icon.cyan   { background: #083344 !important; }
html.dark .stat-icon.violet { background: #2e1065 !important; }
html.dark .stat-icon.rose   { background: #4c0519 !important; }
html.dark .stat-icon.teal   { background: #042f2e !important; }
html.dark .stat-pill.neu  { background: #252840 !important; color: #64748b !important; }
html.dark .stat-pill.ok   { background: #052e16 !important; color: #86efac !important; }
html.dark .stat-pill.bad  { background: #450a0a !important; color: #fca5a5 !important; }
html.dark .stat-pill.warn { background: #422006 !important; color: #fde68a !important; }

/* ── DARK MODE PANELS ── */
html.dark .panel { background: #1e2130 !important; border-color: #2d3148 !important; }
html.dark .panel-head { background: #181b29 !important; border-bottom-color: #2d3148 !important; }
html.dark .panel-title { color: #e2e8f0 !important; }
html.dark .panel-sub   { color: #64748b !important; }

/* ── DARK MODE CONTENT AREA ── */
html.dark .content { background: #0f1117 !important; }
html.dark .main { background: #0f1117 !important; }

/* ── DARK MODE HERO ── */
html.dark .hero { background: #111827 !important; }
html.dark .hero-text h2 { color: #e2e8f0 !important; }

/* ── DARK MODE TOPBAR ── */
html.dark .topbar { background: #1a1d27 !important; border-bottom-color: #2d3148 !important; }
html.dark .topbar-title { color: #e2e8f0 !important; }
html.dark .topbar-date  { color: #64748b !important; }

/* ── DARK MODE CHARTS ── */
html.dark .chart-grid .panel { background: #1e2130 !important; }
html.dark .bottom-row .panel { background: #1e2130 !important; }
html.dark .legend-row  { border-bottom-color: #2d3148 !important; }
html.dark .legend-label { color: #94a3b8 !important; }
html.dark .legend-count { color: #e2e8f0 !important; }
html.dark .legend-pct   { color: #64748b !important; }

/* ── DARK MODE ACTIVITY FEED ── */
html.dark .activity-item { border-top-color: #2d3148 !important; }
html.dark .activity-item:hover { background: #252840 !important; }
html.dark .activity-desc { color: #cbd5e1 !important; }
html.dark .activity-desc strong { color: #e2e8f0 !important; }
html.dark .activity-meta { color: #64748b !important; }

/* ── DARK MODE PROGRESS BARS ── */
html.dark .prog-name  { color: #94a3b8 !important; }
html.dark .prog-count { color: #64748b !important; }
html.dark .prog-track { background: #252840 !important; }

/* ── DARK MODE BREAKDOWN ── */
html.dark .breakdown-body { background: #1e2130 !important; }
html.dark .empty-activity { color: #4b5563 !important; }
    </style>
</head>
<body>

@include(auth()->user()->role === 'super_admin' ? 'superadmin.partials.sidebar' : 'admin.partials.sidebar')

<div class="main">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">System Overview</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y · H:i') }}</div>
        </div>
        <div class="topbar-right">
            <span class="role-pill super">⚡ Super Admin</span>
            <a href="{{ route('reports.index') }}" class="btn-new">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Reports
            </a>
        </div>
    </div>

    <div class="content">

        {{-- HERO --}}
        <div class="hero fade-up">
            <div class="hero-text">
                <h2>{{ now()->hour < 12 ? 'Good morning' : (now()->hour < 18 ? 'Good afternoon' : 'Good evening') }}, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
                <p>MUNICIPALITY OF CARMEN · DAVAO DEL NORTE · MUNICIPAL CIVIL REGISTRAR · {{ strtoupper(now()->format('F Y')) }}</p>
            </div>
            <div class="hero-actions">
                <a href="{{ route('superadmin.reports.excel') }}" class="btn-export" style="background: var(--green); border-color: #059669;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    Export Excel Report
                </a>
                <a href="{{ route('superadmin.reports.pdf') }}" class="btn-export primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export Premium PDF
                </a>
            </div>
        </div>

        {{-- STAT CARDS (8) --}}
        <div class="stat-grid">
            <a href="{{ route('permits.index') }}" class="stat-card fade-up d1" style="text-decoration:none">
                <div class="stat-top">
                    <div class="stat-icon blue">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span class="stat-pill neu">ALL TIME</span>
                </div>
                <div class="stat-value blue">{{ $totalPermits }}</div>
                <div class="stat-label">Total Permits</div>
                <div class="stat-sub">↑ {{ $permitsThisMonth }} this month</div>
            </a>

            <a href="{{ route('permits.index', ['status' => 'active']) }}" class="stat-card fade-up d2" style="text-decoration:none">
                <div class="stat-top">
                    <div class="stat-icon green">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span class="stat-pill ok">VALID</span>
                </div>
                <div class="stat-value green">{{ $activePermits }}</div>
                <div class="stat-label">Active Permits</div>
                <div class="stat-sub">Current valid permits</div>
            </a>

            <a href="{{ route('permits.index', ['status' => 'expiring']) }}" class="stat-card fade-up d3" style="text-decoration:none">
                <div class="stat-top">
                    <div class="stat-icon amber">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <span class="stat-pill {{ $expiringPermits > 0 ? 'warn' : 'neu' }}">{{ $expiringPermits > 0 ? 'ATTENTION' : 'NONE' }}</span>
                </div>
                <div class="stat-value amber">{{ $expiringPermits }}</div>
                <div class="stat-label">Expiring Soon</div>
                <div class="stat-sub">Expiring within 30 days</div>
            </a>

            <div class="stat-card fade-up d4" style="opacity:0.5; pointer-events:none;">
                <div class="stat-top">
                    <div class="stat-icon cyan">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                    </div>
                    <span class="stat-pill neu">OFFLINE</span>
                </div>
                <div class="stat-value cyan">0</div>
                <div class="stat-label">Legacy Status</div>
                <div class="stat-sub">Reserved slot</div>
            </div>

            <a href="{{ route('permits.index', ['status' => 'expired']) }}" class="stat-card fade-up d5" style="text-decoration:none">
                <div class="stat-top">
                    <div class="stat-icon red">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                    <span class="stat-pill {{ $expiredPermits > 0 ? 'bad' : 'ok' }}">{{ $expiredPermits > 0 ? 'URGENT' : 'NONE' }}</span>
                </div>
                <div class="stat-value red">{{ $expiredPermits }}</div>
                <div class="stat-label">Expired</div>
                <div class="stat-sub">Need renewal</div>
            </a>

            <div class="stat-card fade-up d6">
                <div class="stat-top">
                    <div class="stat-icon violet">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <span class="stat-pill neu">RECORDS</span>
                </div>
                <div class="stat-value violet">{{ $totalDeceased }}</div>
                <div class="stat-label">Deceased Records</div>
                <div class="stat-sub">↑ {{ $deceasedThisMonth }} this month</div>
            </div>

            <a href="{{ route('reports.index') }}" class="stat-card fade-up d7" style="text-decoration:none">
                <div class="stat-top">
                    <div class="stat-icon rose">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <span class="stat-pill neu">{{ now()->year }}</span>
                </div>
                <div class="stat-value rose">{{ $newPermits }}</div>
                <div class="stat-label">New This Year</div>
                <div class="stat-sub">Detailed in Reports</div>
            </a>

            <div class="stat-card fade-up d8">
                <div class="stat-top">
                    <div class="stat-icon teal">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="2"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                    </div>
                    <span class="stat-pill neu">{{ now()->year }}</span>
                </div>
                <div class="stat-value teal">{{ $renewedPermits }}</div>
                <div class="stat-label">Renewed This Year</div>
                <div class="stat-sub">{{ now()->year }} renewals</div>
            </div>
        </div>

        {{-- CHARTS ROW --}}
        <div class="chart-grid">

            {{-- Monthly Bar Chart --}}
            <div class="panel fade-up d9">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Monthly Permit Applications</div>
                        <div class="panel-sub">{{ now()->year }} — permits per month</div>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyChart" height="180"></canvas>
                </div>
            </div>

            {{-- Status Doughnut --}}
            <div class="panel fade-up d10">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Status Distribution</div>
                        <div class="panel-sub">All permits</div>
                    </div>
                </div>

                {{-- FIX: properly closed chart-body div, fixed-size canvas wrapper --}}
                <div class="chart-body" style="display:flex;justify-content:center;align-items:center;padding:1rem 0;">
                    <div style="position:relative;width:180px;height:180px;flex-shrink:0;">
                        <canvas id="statusChart" width="180" height="180"></canvas>
                    </div>
                </div>

                <div class="legend-list">
                    <div class="legend-row">
                        <div class="legend-left"><div class="legend-dot" style="background:#10b981"></div><span class="legend-label">Active</span></div>
                        <div class="legend-right"><span class="legend-count">{{ $activePermits }}</span><span class="legend-pct">{{ $totalPermits > 0 ? round(($activePermits/$totalPermits)*100) : 0 }}%</span></div>
                    </div>
                    <div class="legend-row">
                        <div class="legend-left"><div class="legend-dot" style="background:#f59e0b"></div><span class="legend-label">Expiring Soon</span></div>
                        <div class="legend-right"><span class="legend-count">{{ $expiringPermits }}</span><span class="legend-pct">{{ $totalPermits > 0 ? round(($expiringPermits/$totalPermits)*100) : 0 }}%</span></div>
                    </div>
                    <div class="legend-row">
                        <div class="legend-left"><div class="legend-dot" style="background:#ef4444"></div><span class="legend-label">Expired</span></div>
                        <div class="legend-right"><span class="legend-count">{{ $expiredPermits }}</span><span class="legend-pct">{{ $totalPermits > 0 ? round(($expiredPermits/$totalPermits)*100) : 0 }}%</span></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- BOTTOM ROW --}}
        <div class="bottom-row">

            {{-- Recent Activity --}}
            <div class="panel fade-up">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Recent Activity</div>
                        <div class="panel-sub">Latest system events</div>
                    </div>
                    <a href="{{ route('superadmin.activity') }}" class="link-arrow">
                        Full log
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
                <div class="activity-feed">
                    @forelse($recentActivity as $log)
                    @php
                        $dotClass = match($log->action) {
                            'created'  => 'dot-green',
                            'active'   => 'dot-green',
                            'expiring' => 'dot-gray',
                            'expired'  => 'dot-red',
                            'renewed'  => 'dot-indigo',
                            default    => 'dot-gray',
                        };
                        $icon = match($log->action) {
                            'created'  => '+',
                            'active'   => '✓',
                            'expired'  => '!',
                            'renewed'  => '↻',
                            default    => '•',
                        };
                    @endphp
                    <div class="activity-item">
                        <div class="activity-dot {{ $dotClass }}">{{ $icon }}</div>
                        <div class="activity-body">
                            <div class="activity-desc">
                                {{ $log->description }}
                                @if($log->model_label)
                                    — <strong>{{ $log->model_label }}</strong>
                                @endif
                            </div>
                            <div class="activity-meta">
                                <span>{{ optional($log->user)->name ?? 'System' }}</span>
                                <span class="activity-meta-sep">·</span>
                                <span>{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-activity">No activity yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- Permit Type Breakdown --}}
            <div class="panel fade-up">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Permit Type Breakdown</div>
                        <div class="panel-sub">Distribution across burial categories</div>
                    </div>
                </div>
                <div class="breakdown-body">
                    @php
                        $feeLabels = ['Cemented','1st Floor','2nd Floor','3rd Floor','4th Floor','Bone Niches','Other'];
                        $feeColors = ['#3b82f6','#8b5cf6','#ec4899','#f59e0b','#10b981','#ef4444','#94a3b8'];
                        $feeMax    = max(array_filter((array)$feeTypeData, fn($v) => $v > 0) ?: [1]);
                    @endphp
                    @foreach($feeLabels as $i => $label)
                    @php $cnt = $feeTypeData[$i] ?? 0; $pct = $feeMax > 0 ? round(($cnt/$feeMax)*100) : 0; @endphp
                    <div>
                        <div class="prog-labels">
                            <span class="prog-name">{{ $label }}</span>
                            <span class="prog-count">{{ $cnt }}</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill" style="width:{{ $pct }}%;background:{{ $feeColors[$i] }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Horizontal bar chart --}}
                <div class="chart-body" style="padding-top:0">
                    <canvas id="feeChart" height="180"></canvas>
                </div>
            </div>

        </div>

    </div>{{-- /content --}}
</div>{{-- /main --}}

<script>
// ── Monthly bar chart ──────────────────────────────────────────
const monthlyData = @json($monthlyData);
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const maxVal  = Math.max(...monthlyData, 1);

new Chart(document.getElementById('monthlyChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            data: monthlyData,
            backgroundColor: monthlyData.map(v => v === maxVal && v > 0 ? '#0f1e3d' : '#e2e8f0'),
            hoverBackgroundColor: '#3b82f6',
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f1e3d',
                titleFont: { family: 'DM Mono', size: 10 },
                bodyFont:  { family: 'DM Mono', size: 12 },
                padding: 10,
                displayColors: false,
                callbacks: {
                    title: items => months[items[0].dataIndex],
                    label: item  => ` ${item.raw} permit${item.raw !== 1 ? 's' : ''}`,
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { family: 'DM Mono', size: 10 }, color: '#94a3b8', maxTicksLimit: 5 },
                grid: { color: '#f1f5f9' },
                border: { display: false }
            },
            x: {
                ticks: { font: { family: 'DM Mono', size: 10 }, color: '#94a3b8' },
                grid: { display: false },
                border: { display: false }
            }
        }
    }
});

// ── Status Doughnut ────────────────────────────────────────────
// FIX: responsive:false so Chart.js uses the explicit 180x180 canvas size
new Chart(document.getElementById('statusChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Active','Expiring Soon','Expired'],
        datasets: [{
            data: [{{ $activePermits }},{{ $expiringPermits }},{{ $expiredPermits }}],
            backgroundColor: ['#10b981','#f59e0b','#ef4444'],
            borderWidth: 3,
            borderColor: '#ffffff',
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: false,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f1e3d',
                titleFont: { family: 'DM Mono', size: 10 },
                bodyFont:  { family: 'DM Mono', size: 12 },
                padding: 10,
                displayColors: true,
            }
        }
    }
});

// ── Permit type horizontal bar ─────────────────────────────────
new Chart(document.getElementById('feeChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Cemented','1st Floor','2nd Floor','3rd Floor','4th Floor','Bone Niches','Other'],
        datasets: [{
            label: 'Count',
            data: @json($feeTypeData),
            backgroundColor: ['#3b82f6','#8b5cf6','#ec4899','#f59e0b','#10b981','#ef4444','#e2e8f0'],
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f1e3d',
                titleFont: { family: 'DM Mono', size: 10 },
                bodyFont:  { family: 'DM Mono', size: 12 },
                padding: 10,
                displayColors: false,
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { maxTicksLimit: 5, precision: 0, font: { family: 'DM Mono', size: 10 }, color: '#94a3b8' },
                grid: { color: '#f1f5f9' },
                border: { display: false }
            },
            y: {
                ticks: { font: { size: 11 }, color: '#64748b' },
                grid: { display: false },
                border: { display: false }
            }
        }
    }
});
</script>

    <!-- FAB: New Permit -->
    <a href="{{ route('permits.index') }}#new" class="fab" title="New Burial Permit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </a>

    <!-- FAB: New Permit -->
    <a href="{{ route('permits.index') }}#new" class="fab" title="New Burial Permit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </a>

</body>
</html>
