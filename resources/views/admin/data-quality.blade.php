{{-- ══════════════════════════════════════════════════════════
Data Quality Scanner — Standalone Page
Route: admin.dataquality
Controller should pass: $scanResults (optional, can be null — JS fetches live)
══════════════════════════════════════════════════════════ --}}
<!DOCTYPE html>
<html lang="en">

<head>
    @livewireStyles

    <meta charset="UTF-8">
    {{-- Dark-mode anti-flash --}}
    <script>
        (function () { try { if (localStorage.getItem('lgu_dark') === '1') document.documentElement.classList.add('dark'); } catch (e) { } })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    @include('admin.partials.design-system')
    <style>
        /* ── DATA QUALITY SPECIFIC OVERRIDES ── */
        .main { background: var(--bg); color: var(--text); }
        .topbar { background: var(--surface); border-bottom: 1px solid var(--border); height: 56px; }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f0f2f5;
            color: #111827;
            display: flex;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* ── LAYOUT ─────────────────────────────────────────────── */
        .main {
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - 144px);
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

        .topbar-left {
            display: flex;
            flex-direction: row !important;
            align-items: center;
            gap: .75rem;
        }

        .topbar-back {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
            transition: color .15s;
        }

        .topbar-back:hover {
            color: #1a2744;
        }

        .topbar-sep {
            color: #d1d5db;
            font-size: 16px;
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
            flex: 1;
        }

        /* ── STAT CARDS ─────────────────────────────────────────── */
        .dq-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: .75rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: .85rem 1rem;
            text-align: center;
        }

        .stat-val {
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
        }

        .stat-lbl {
            font-size: 10px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-top: 4px;
        }

        .stat-card.red .stat-val {
            color: #ef4444;
        }

        .stat-card.amber .stat-val {
            color: #f59e0b;
        }

        .stat-card.blue .stat-val {
            color: #3b82f6;
        }

        .stat-card.green .stat-val {
            color: #10b981;
        }

        /* ── SCANNER PANEL ──────────────────────────────────────── */
        .panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .panel-head {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            background: #fafafa;
        }

        .panel-head-info h2 {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .panel-head-info p {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .panel-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        /* ── TOOLBAR ────────────────────────────────────────────── */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .6rem;
        }

        .filters {
            display: flex;
            gap: .4rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            border: 1.5px solid #e5e7eb;
            background: #fff;
            color: #6b7280;
            cursor: pointer;
            transition: all .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .filter-btn:hover {
            border-color: #1a2744;
            color: #1a2744;
        }

        .filter-btn.f-all.active {
            background: #1a2744;
            color: #fff;
            border-color: #1a2744;
        }

        .filter-btn.f-dup.active {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
        }

        .filter-btn.f-miss.active {
            background: #f59e0b;
            color: #fff;
            border-color: #f59e0b;
        }

        .filter-btn.f-incon.active {
            background: #3b82f6;
            color: #fff;
            border-color: #3b82f6;
        }

        /* ── BUTTONS ────────────────────────────────────────────── */
        .btn-sm {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 11px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            font-weight: 500;
            color: #374151;
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
            white-space: nowrap;
        }

        .btn-sm:hover {
            background: #f9fafb;
            border-color: #1a2744;
            color: #1a2744;
        }

        .btn-sm.danger {
            border-color: #fca5a5;
            color: #991b1b;
        }

        .btn-sm.danger:hover {
            background: #fee2e2;
            border-color: #ef4444;
        }

        .btn-sm.btn-primary {
            background: #1a2744;
            color: #fff;
            border-color: #1a2744;
        }

        .btn-sm.btn-primary:hover {
            background: #2a3a5a;
            color: #fff;
        }

        .btn-sm.warn {
            border-color: #fde68a;
            color: #92400e;
        }

        .btn-sm.warn:hover {
            background: #fef3c7;
            border-color: #f59e0b;
        }

        .btn-rescan {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: .45rem 1rem;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            background: #fff;
            cursor: pointer;
            transition: all .15s;
        }

        .btn-rescan:hover {
            background: #f0f4ff;
            border-color: #1a2744;
            color: #1a2744;
        }

        /* ── ISSUE CARDS ────────────────────────────────────────── */
        .issue {
            border: 1px solid #e5e7eb;
            border-left-width: 4px;
            border-radius: 8px;
            overflow: hidden;
        }

        .issue.sev-high {
            border-left-color: #ef4444;
        }

        .issue.sev-medium {
            border-left-color: #f59e0b;
        }

        .issue.sev-low {
            border-left-color: #3b82f6;
        }

        .issue-head {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .75rem 1rem;
            background: #fafafa;
            cursor: pointer;
            user-select: none;
        }

        .issue-head:hover {
            background: #f3f4f6;
        }

        .issue.open .issue-head {
            background: #f3f4f6;
        }

        .sev-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .type-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            letter-spacing: .03em;
            white-space: nowrap;
        }

        .badge-dup {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-miss {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-incon {
            background: #dbeafe;
            color: #1e40af;
        }

        .issue-title {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            flex: 1;
        }

        .issue-count {
            font-size: 11px;
            color: #9ca3af;
            white-space: nowrap;
        }

        .chevron {
            color: #9ca3af;
            transition: transform .2s;
            flex-shrink: 0;
        }

        .issue.open .chevron {
            transform: rotate(90deg);
        }

        .issue-body {
            display: none;
            border-top: 1px solid #f3f4f6;
        }

        .issue.open .issue-body {
            display: block;
        }

        .issue-desc {
            padding: .5rem 1rem;
            font-size: 11px;
            color: #6b7280;
            background: #fafafa;
            border-bottom: 1px solid #f3f4f6;
        }

        /* ── RECORDS ────────────────────────────────────────────── */
        .record {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .65rem 1rem;
            border-bottom: 1px solid #f9fafb;
            flex-wrap: wrap;
        }

        .record:last-child {
            border-bottom: none;
        }

        .rec-info {
            flex: 1;
            min-width: 180px;
        }

        .rec-title {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
        }

        .rec-sub {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 1px;
        }

        .field-chip {
            font-size: 11px;
            font-family: 'Courier New', monospace;
            padding: 2px 7px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .chip-miss {
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #92400e;
        }

        .chip-bad {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .chip-info {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e40af;
        }

        .rec-actions {
            display: flex;
            gap: .4rem;
            flex-shrink: 0;
        }

        /* ── STATES ─────────────────────────────────────────────── */
        .dq-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .75rem;
            padding: 3rem 1rem;
            color: #9ca3af;
            font-size: 13px;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #e5e7eb;
            border-top-color: #1a2744;
            border-radius: 50%;
            animation: spin .6s linear infinite;
            flex-shrink: 0;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .dq-empty {
            text-align: center;
            padding: 3rem 1rem;
        }

        .dq-empty-icon {
            width: 54px;
            height: 54px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto .75rem;
        }

        .dq-empty h3 {
            font-size: 15px;
            font-weight: 700;
            color: #065f46;
        }

        .dq-empty p {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .dq-no-match {
            text-align: center;
            padding: 1.5rem 1rem;
            font-size: 13px;
            color: #9ca3af;
        }

        /* ── TOAST ──────────────────────────────────────────────── */
        .toast {
            position: fixed;
            top: 1.1rem;
            right: 1.25rem;
            z-index: 9999;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .12);
            width: 300px;
            overflow: hidden;
            transform: translateX(calc(100% + 2rem));
            transition: transform .35s cubic-bezier(.34, 1.56, .64, 1);
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast-body {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .85rem 1rem;
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
            background: #d1fae5;
        }

        .toast-icon.red {
            background: #fee2e2;
        }

        .toast-icon.amber {
            background: #fef3c7;
        }

        .toast-title {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        .toast-sub {
            font-size: 11px;
            color: #6b7280;
            margin-top: 1px;
        }

        .toast-bar {
            height: 3px;
        }

        .toast-bar-fill {
            height: 100%;
            width: 100%;
            transform-origin: left;
            animation: toastDrain 4s linear forwards;
        }

        @keyframes toastDrain {
            from {
                transform: scaleX(1)
            }

            to {
                transform: scaleX(0)
            }
        }

        /* ── DELETE MODAL ───────────────────────────────────────── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
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
            border-radius: 10px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            overflow: hidden;
            animation: modalIn .15s ease;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(-10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .modal-header {
            padding: 1rem 1.25rem;
            background: #991b1b;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255, 255, 255, .6);
            font-size: 20px;
            line-height: 1;
        }

        .modal-close:hover {
            color: #fff;
        }

        .modal-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .modal-footer {
            padding: .85rem 1.25rem;
            border-top: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            gap: .6rem;
        }

        .btn-cancel {
            padding: .5rem 1rem;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: #374151;
            background: #fff;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #f9fafb;
        }

        .btn-delete-confirm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: .5rem 1.1rem;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-delete-confirm:hover {
            background: #b91c1c;
        }

        /* ── RESPONSIVE ─────────────────────────────────────────── */
        @media (max-width: 768px) {
            .main {
                margin-left: 0;
            }

            .dq-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* ══════════════════════════════
           DARK MODE
        ══════════════════════════════ */
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
            color: #475569 !important;
        }

        html.dark .help-link-trigger {
            color: #818cf8 !important;
        }

        html.dark .role-tag {
            background: #6366f1 !important;
        }

        html.dark .stat-card {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .stat-lbl {
            color: #64748b !important;
        }

        html.dark .stat-card.red .stat-val {
            color: #f87171 !important;
        }

        html.dark .stat-card.amber .stat-val {
            color: #fbbf24 !important;
        }

        html.dark .stat-card.blue .stat-val {
            color: #60a5fa !important;
        }

        html.dark .stat-card.green .stat-val {
            color: #34d399 !important;
        }

        html.dark .panel {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .panel-head {
            background: #181b29 !important;
            border-bottom-color: #2d3148 !important;
        }

        html.dark .panel-head-info h2 {
            color: #e2e8f0 !important;
        }

        html.dark .panel-head-info p {
            color: #64748b !important;
        }

        html.dark .filter-btn {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #94a3b8 !important;
        }

        html.dark .filter-btn:hover {
            border-color: #6366f1 !important;
            color: #e2e8f0 !important;
        }

        html.dark .filter-btn.f-all.active {
            background: #1a2744 !important;
            border-color: #1a2744 !important;
            color: #fff !important;
        }

        html.dark .filter-btn.f-dup.active {
            background: #ef4444 !important;
            border-color: #ef4444 !important;
            color: #fff !important;
        }

        html.dark .filter-btn.f-miss.active {
            background: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #fff !important;
        }

        html.dark .filter-btn.f-incon.active {
            background: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: #fff !important;
        }

        html.dark .btn-rescan {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #94a3b8 !important;
        }

        html.dark .btn-rescan:hover {
            background: #2d3148 !important;
            border-color: #6366f1 !important;
            color: #e2e8f0 !important;
        }

        html.dark .btn-sm {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #94a3b8 !important;
        }

        html.dark .btn-sm:hover {
            background: #2d3148 !important;
            border-color: #6366f1 !important;
            color: #e2e8f0 !important;
        }

        html.dark .btn-sm.danger {
            border-color: #7f1d1d !important;
            color: #f87171 !important;
        }

        html.dark .btn-sm.danger:hover {
            background: #2a1a1a !important;
            border-color: #ef4444 !important;
        }

        html.dark .btn-sm.btn-primary {
            background: #3730a3 !important;
            border-color: #4f46e5 !important;
            color: #fff !important;
        }

        html.dark .btn-sm.btn-primary:hover {
            background: #4338ca !important;
            border-color: #6366f1 !important;
        }

        html.dark .btn-sm.warn {
            border-color: #78350f !important;
            color: #fbbf24 !important;
        }

        html.dark .btn-sm.warn:hover {
            background: #1c1200 !important;
            border-color: #f59e0b !important;
        }

        html.dark .issue {
            border-color: #2d3148 !important;
        }

        html.dark .issue-head {
            background: #181b29 !important;
        }

        html.dark .issue-head:hover,
        html.dark .issue.open .issue-head {
            background: #252840 !important;
        }

        html.dark .issue-title {
            color: #e2e8f0 !important;
        }

        html.dark .issue-count {
            color: #64748b !important;
        }

        html.dark .issue-body {
            border-top-color: #2d3148 !important;
        }

        html.dark .issue-desc {
            background: #181b29 !important;
            color: #94a3b8 !important;
            border-bottom-color: #2d3148 !important;
        }

        html.dark .badge-dup {
            background: #450a0a !important;
            color: #fca5a5 !important;
        }

        html.dark .badge-miss {
            background: #422006 !important;
            color: #fde68a !important;
        }

        html.dark .badge-incon {
            background: #1e3a5f !important;
            color: #93c5fd !important;
        }

        html.dark .record {
            border-bottom-color: #1e2130 !important;
        }

        html.dark .rec-title {
            color: #e2e8f0 !important;
        }

        html.dark .rec-sub {
            color: #64748b !important;
        }

        html.dark .chip-miss {
            background: #422006 !important;
            border-color: #78350f !important;
            color: #fde68a !important;
        }

        html.dark .chip-bad {
            background: #450a0a !important;
            border-color: #7f1d1d !important;
            color: #fca5a5 !important;
        }

        html.dark .chip-info {
            background: #1e3a5f !important;
            border-color: #1e40af !important;
            color: #93c5fd !important;
        }

        html.dark .spinner {
            border-color: #2d3148 !important;
            border-top-color: #6366f1 !important;
        }

        html.dark .dq-empty-icon {
            background: #052e16 !important;
        }

        html.dark .dq-empty h3 {
            color: #34d399 !important;
        }

        html.dark .toast {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .toast-title {
            color: #e2e8f0 !important;
        }

        html.dark .toast-sub {
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

        html.dark #deleteModal .modal-body > div:first-child {
            background: #2a1a1a !important;
            border-color: #7f1d1d !important;
        }

        html.dark #deleteModal .modal-body > div:first-child > div:first-child {
            background: #450a0a !important;
        }

        html.dark #deleteModal .modal-body > div:first-child > div:last-child > div:first-child {
            color: #fca5a5 !important;
        }

        html.dark #deleteModal .modal-body > div:first-child > div:last-child > div:last-child {
            color: #f87171 !important;
        }

        html.dark #deleteModal .modal-body > div:nth-child(2) > div {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #94a3b8 !important;
        }

        html.dark #deleteModal .modal-body > div:nth-child(2) > div strong {
            color: #e2e8f0 !important;
        }

        html.dark #deleteModal .modal-body p {
            color: #64748b !important;
        }

        html.dark #quickEditModal > .modal {
            background: #1e2130 !important;
            border: 1px solid #2d3148 !important;
        }

        html.dark #qe-info {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #94a3b8 !important;
        }

        html.dark #qe-label {
            color: #94a3b8 !important;
        }

        html.dark #qe-input {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #e2e8f0 !important;
        }

        html.dark #quickEditModal .modal-footer {
            background: #181b29 !important;
            border-top-color: #2d3148 !important;
        }

        html.dark #qe-save-btn {
            background: #3730a3 !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25) !important;
        }

        html.dark .dq-no-match {
            color: #64748b !important;
        }

        @media print {

            .sa-sidebar,
            .topbar {
                display: none;
            }

            .main {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    <div class="main">

        {{-- Top Bar --}}
        <div class="topbar">
            <div class="topbar-left">
                <div class="topbar-title">
                    Data Quality Scanner
                    <a href="{{ route('support.manual') }}#data-quality" class="help-link-trigger" title="How to use data quality tools" style="display:inline-flex; vertical-align:middle; margin-left:8px; color:var(--accent); opacity:0.6; transition:opacity .15s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </a>
                </div>
                <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
            </div>
            <div class="topbar-right">
                <span class="role-tag">{{ auth()->user()->role === 'cemetery_manager' ? 'Cemetery Manager' : 'Admin' }}</span>
            </div>
        </div>


        {{-- Main Content --}}
        <div class="content">

            {{-- Summary Stat Cards --}}
            <div class="dq-stats">
                <div class="stat-card red">
                    <div class="stat-val" id="cnt-high">—</div>
                    <div class="stat-lbl">Critical</div>
                </div>
                <div class="stat-card amber">
                    <div class="stat-val" id="cnt-med">—</div>
                    <div class="stat-lbl">Warnings</div>
                </div>
                <div class="stat-card blue">
                    <div class="stat-val" id="cnt-low">—</div>
                    <div class="stat-lbl">Info</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-val" id="cnt-res">0</div>
                    <div class="stat-lbl">Resolved</div>
                </div>
            </div>

            {{-- Scanner Panel --}}
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-info">
                        <h2>Issue Report</h2>
                        <p>Click any issue to expand it, then choose what to do with each affected record.</p>
                    </div>
                    <div class="toolbar">
                        <div class="filters">
                            <button class="filter-btn f-all active" onclick="setFilter('all', this)">All</button>
                            <button class="filter-btn f-dup" onclick="setFilter('duplicate', this)">🔴
                                Duplicates</button>
                            <button class="filter-btn f-miss" onclick="setFilter('missing', this)">🟡 Missing
                                Data</button>
                            <button class="filter-btn f-incon" onclick="setFilter('inconsistent', this)">🔵
                                Inconsistent</button>
                        </div>
                        <button class="btn-rescan" onclick="runScan(true)">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M3 12a9 9 0 109-9" />
                                <polyline points="3 3 3 9 9 9" />
                            </svg>
                            Re-scan
                        </button>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="dq-loading" id="dq-loading">
                        <div class="spinner"></div>
                        Scanning database for issues…
                    </div>
                    <div id="dq-list"></div>
                    <div class="dq-empty" id="dq-empty" style="display:none">
                        <div class="dq-empty-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#065f46"
                                stroke-width="2.5">
                                <path d="M9 11l3 3L22 4" />
                                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                            </svg>
                        </div>
                        <h3>All Clear!</h3>
                        <p>No data quality issues found. Your records are clean.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ══ DELETE CONFIRMATION MODAL ══ --}}
    <div class="modal-overlay" id="deleteModal" onclick="if(event.target===this)closeDeleteModal()">
        <div class="modal">
            <div class="modal-header">
                <h3>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                    Final Warning
                </h3>
                <button class="modal-close" onclick="closeDeleteModal()">×</button>
            </div>
            <div class="modal-body">
                <div
                    style="display:flex;align-items:center;gap:12px;padding:.75rem 1rem;background:#fff5f5;border:1px solid #fecaca;border-radius:8px">
                    <div
                        style="width:38px;height:38px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14H6L5 6" />
                            <path d="M10 11v6M14 11v6" />
                            <path d="M9 6V4h6v2" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:#991b1b">You are about to permanently delete:
                        </div>
                        <div style="font-size:12px;color:#b91c1c;margin-top:3px">This action <strong>cannot be
                                undone.</strong></div>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:6px">
                    <div
                        style="display:flex;align-items:center;gap:8px;font-size:13px;color:#374151;padding:.5rem .75rem;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        Permit: <strong id="deleteModal-label" style="color:#111827;margin-left:4px">—</strong>
                    </div>
                    <div
                        style="display:flex;align-items:center;gap:8px;font-size:13px;color:#374151;padding:.5rem .75rem;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        The deceased person linked to this permit
                    </div>
                </div>
                <p style="font-size:12px;color:#9ca3af;text-align:center">Are you absolutely sure you want to proceed?
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button class="btn-delete-confirm" onclick="confirmDelete()">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14H6L5 6" />
                    </svg>
                    Yes, Delete Permanently
                </button>
            </div>
        </div>
    </div>

    <!-- MERGE MODAL -->
    <div class="modal-overlay" id="mergeModal" onclick="if(event.target===this) document.getElementById('mergeModal').classList.remove('open')">
        <div class="modal" style="max-width:480px">
            <div class="modal-header">
                <h3 style="display:flex; align-items:center; gap:8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M12 22V12m0 0l-4 4m4-4l4 4M2 12h10m0 0l-4-4m4 4l4-4M12 2v10m0 0l4-4m-4 4l-4-4M22 12H12m0 0l4 4m-4-4l-4-4"/></svg>
                    Consolidate Duplicates
                </h3>
                <button class="modal-close" onclick="document.getElementById('mergeModal').classList.remove('open')">&times;</button>
            </div>
            <div class="modal-body" id="merge-body">
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="document.getElementById('mergeModal').classList.remove('open')">Cancel</button>
                <button class="btn-delete-confirm" id="btn-confirm-merge" style="background:var(--navy); color:#fff; border:none;">Confirm Merge</button>
            </div>
        </div>
    </div>

    {{-- ══ QUICK EDIT MODAL ══ --}}
    <div class="modal-overlay" id="quickEditModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; transition: all 0.3s;"
        onclick="if(event.target===this)closeQuickEditModal()">
        <div class="modal"
            style="background: #fff; border-radius: 16px; width: 100%; max-width: 420px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; transform: translateY(0); transition: all 0.3s ease-out;">
            <div class="modal-header"
                style="background: linear-gradient(135deg, #1a2744, #2a3a5a); padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <h3
                    style="color: #fff; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 10px; margin: 0;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z" />
                    </svg>
                    Quick Edit
                </h3>
                <button class="modal-close" style="color:#9ca3af" onclick="closeQuickEditModal()">×</button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <div id="qe-info"
                    style="font-size: 13px; color: #475569; margin-bottom: 1.25rem; padding: 0.75rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; line-height: 1.5;">
                </div>

                <div class="field-group" style="margin-bottom: 0.5rem;">
                    <label id="qe-label" class="field-label"
                        style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; display: block;"></label>
                    <div id="qe-input-container">
                        {{-- Input will be injected here --}}
                    </div>
                    <p style="font-size: 11px; color: #94a3b8; margin-top: 8px;">Enter the corrected information for
                        this record.</p>
                </div>
            </div>
            <div class="modal-footer"
                style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button class="btn-cancel"
                    style="padding: 0.6rem 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                    onclick="closeQuickEditModal()">Cancel</button>
                <button id="qe-save-btn" class="btn-sm"
                    style="background: #1a2744; color: #fff; padding: 0.6rem 1.5rem; border-radius: 8px; border: none; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(26, 39, 68, 0.2); transition: all 0.2s;"
                    onclick="saveQuickEdit()">Save Changes</button>
            </div>
        </div>
    </div>

    {{-- ══ TOAST ══ --}}
    <div class="toast" id="dqToast">
        <div class="toast-body">
            <div class="toast-icon green" id="dqToastIcon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
            <div>
                <div class="toast-title" id="dqToastTitle">Done</div>
                <div class="toast-sub" id="dqToastSub"></div>
            </div>
        </div>
        <div class="toast-bar" style="background:#e5e7eb">
            <div class="toast-bar-fill" id="dqToastBar" style="background:#10b981"></div>
        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        /* ══════════════════════════════════════
           STATE
        ══════════════════════════════════════ */
        let DQ = {
            issues: [],
            ignored: new Set(),
            resolved: new Set(),
            filter: 'all',
            resolvedCount: 0,
            pending: null      // { permitId, label, issueId, recId }
        };

        /* ══════════════════════════════════════
           SCAN
        ══════════════════════════════════════ */
        function runScan(force = false) {
            DQ.issues = [];
            DQ.ignored = new Set();
            DQ.resolved = new Set();
            DQ.resolvedCount = 0;

            show('dq-loading');
            hide('dq-empty');
            document.getElementById('dq-list').innerHTML = '';
            ['cnt-high', 'cnt-med', 'cnt-low'].forEach(id => document.getElementById(id).textContent = '—');
            document.getElementById('cnt-res').textContent = '0';

            fetch('{{ route("settings.dataquality.scan") }}', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
            })
                .then(r => r.json())
                .then(d => {
                    DQ.issues = d.issues || [];
                    hide('dq-loading');
                    render();
                })
                .catch(() => {
                    hide('dq-loading');
                    document.getElementById('dq-list').innerHTML =
                        '<div style="text-align:center;padding:2rem;color:#ef4444;font-size:13px">Scan failed. Please try again.</div>';
                });
        }

        /* ══════════════════════════════════════
           RENDER
        ══════════════════════════════════════ */
        function render() {
            const list = document.getElementById('dq-list');
            const active = DQ.issues.filter(i => !DQ.resolved.has(i.id) && !allIgnored(i));
            const visible = active.filter(i => DQ.filter === 'all' || i.type === DQ.filter);

            updateCounters(active);

            list.innerHTML = '';

            if (active.length === 0) { show('dq-empty'); return; }
            hide('dq-empty');

            if (visible.length === 0) {
                list.innerHTML = '<div class="dq-no-match">No issues match this filter.</div>';
                return;
            }

            visible.forEach(issue => {
                const visRecs = issue.records.filter(r => !DQ.ignored.has(r.id));
                if (!visRecs.length) return;

                const typeLabel = { duplicate: 'Duplicate', missing: 'Missing Data', inconsistent: 'Inconsistent' }[issue.type] || issue.type;
                const badgeCls = { duplicate: 'badge-dup', missing: 'badge-miss', inconsistent: 'badge-incon' }[issue.type] || '';
                const dotColor = { high: '#ef4444', medium: '#f59e0b', low: '#3b82f6' }[issue.severity] || '#9ca3af';

                const div = document.createElement('div');
                div.className = `issue sev-${issue.severity}`;
                div.id = 'dqi-' + issue.id;

                div.innerHTML = `
            <div class="issue-head" onclick="toggleIssue('${issue.id}')">
                <div class="sev-dot" style="background:${dotColor}"></div>
                <span class="type-badge ${badgeCls}">${esc(typeLabel)}</span>
                <span class="issue-title" style="flex:1">${esc(issue.title)}</span>
                <div style="display:flex; align-items:center; gap:12px;" onclick="event.stopPropagation()">
                    <span class="issue-count" id="ic-${issue.id}">${visRecs.length} record${visRecs.length !== 1 ? 's' : ''}</span>
                    <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </div>
            </div>
            <div class="issue-body">
                <div class="issue-desc">${esc(issue.description)}</div>
                ${visRecs.map(r => renderRecord(issue, r)).join('')}
            </div>`;

                list.appendChild(div);
            });
        }

        function renderRecord(issue, rec) {
            const chipClass = (rec.field_value === null || rec.field_value === '')
                ? 'chip-miss'
                : (issue.type === 'inconsistent' ? 'chip-info' : 'chip-bad');

            const chip = rec.field_name
                ? `<code class="field-chip ${chipClass}">${esc(rec.field_name)}: ${esc(rec.field_value ?? 'null')}</code>`
                : '';

            let actions = '';
            if (issue.type === 'duplicate') {
                actions = `
            <button class="btn-sm danger" onclick="event.stopPropagation(); openDeleteModal('${rec.permit_id}','${esc(rec.label)}','${issue.id}','${rec.id}')">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                </svg>
                Delete
            </button>
            <button class="btn-sm btn-primary" onclick="event.stopPropagation(); requestMerge('${issue.id}', '${esc(rec.label)}')">Merge</button>
            <button class="btn-sm" onclick="event.stopPropagation(); ignoreRecord('${issue.id}','${rec.id}')">Ignore</button>`;
            } else if (issue.type === 'missing') {
                const recType = rec.id.includes('dec-') ? 'deceased' : 'permit';
                const recId = rec.permit_id || rec.id.split('-').pop();

                actions = `
            <button class="btn-sm warn" onclick="event.stopPropagation(); openQuickEdit('${recType}','${recId}','${rec.field_name}','${esc(rec.field_value)}','${esc(rec.label)}','${issue.id}','${rec.id}')">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z"/>
                </svg>
                Fill In
            </button>
            <button class="btn-sm" onclick="event.stopPropagation(); ignoreRecord('${issue.id}','${rec.id}')">Ignore</button>`;
            } else {
                const recType = rec.id.includes('dec-') ? 'deceased' : 'permit';
                const recId = rec.permit_id || rec.id.split('-').pop();

                actions = `
            <button class="btn-sm warn" onclick="event.stopPropagation(); openQuickEdit('${recType}','${recId}','${rec.field_name}','${esc(rec.field_value)}','${esc(rec.label)}','${issue.id}','${rec.id}')">Review</button>
            <button class="btn-sm" onclick="event.stopPropagation(); ignoreRecord('${issue.id}','${rec.id}')">Ignore</button>`;
            }

            return `
        <div class="record" id="dqr-${rec.id}">
            <div class="rec-info">
                <div class="rec-title">${esc(rec.label)}</div>
                <div class="rec-sub">${esc(rec.sub || '')}</div>
            </div>
            ${chip}
            <div class="rec-actions">${actions}</div>
        </div>`;
        }

        /* ══════════════════════════════════════
           FILTER
        ══════════════════════════════════════ */
        function setFilter(type, btn) {
            DQ.filter = type;
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            render();
        }

        /* ══════════════════════════════════════
           ISSUE TOGGLE
        ══════════════════════════════════════ */
        function toggleIssue(id) {
            document.getElementById('dqi-' + id)?.classList.toggle('open');
        }

        /* ══════════════════════════════════════
           IGNORE
        ══════════════════════════════════════ */
        // ── Consolidate / Merge ──
        async function requestMerge(issueId, groupLabel) {
            const issue = DQ.issues.find(i => i.id === issueId);
            if (!issue) return;

            // Only merge records that belong to the same logical group (e.g. same name)
            const visRecs = issue.records.filter(r => !DQ.ignored.has(r.id) && r.label === groupLabel);
            if (visRecs.length < 2) {
                showToast('Cannot Merge', 'At least 2 records are needed to merge for ' + groupLabel + '.', 'amber');
                return;
            }

            const body = document.getElementById('merge-body');
            body.innerHTML = `<div style="padding:2rem; text-align:center;"><div class="spinner" style="margin:0 auto 10px;"></div>Analysing duplicates...</div>`;
            document.getElementById('mergeModal').classList.add('open');

            // Determine type
            let type = 'deceased';
            if (issue.id === 'dup-same-deceased' || issue.id === 'dup-permit-numbers') {
                type = 'permit';
            } else if (issue.id === 'dup-deceased-names') {
                type = 'deceased';
            }

            const targetIds = visRecs.map(r => r.id.split('-').pop());

            try {
                const res = await fetch('{{ route("settings.dataquality.preview") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ ids: targetIds, type })
                });
                const data = await res.json();

                if (data.error) throw new Error(data.error);

                let updatesHtml = '';
                if (data.updates.length > 0) {
                    updatesHtml = `
                        <div style="margin-top:1.25rem; padding:1rem; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px;">
                            <div style="font-size:12px; font-weight:700; color:#15803d; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                Data to be Consolidated
                            </div>
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                ${data.updates.map(u => `
                                    <div style="font-size:11px; color:#166534; display:flex; justify-content:space-between; align-items:center;">
                                        <span>${esc(u.field)}</span>
                                        <span style="font-weight:600; color:#15803d; max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${esc(u.value)}</span>
                                    </div>
                                `).join('')}
                            </div>
                            <div style="font-size:10px; color:#16a34a; margin-top:10px; font-style:italic;">* Missing information in the oldest record will be filled using data from the duplicates.</div>
                        </div>
                    `;
                } else {
                    updatesHtml = `
                        <div style="margin-top:1.25rem; padding:1rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; text-align:center;">
                            <div style="font-size:11px; color:#64748b;">The oldest record already has all necessary information. Duplicates will be removed.</div>
                        </div>
                    `;
                }

                body.innerHTML = `
                    <div style="display:flex; flex-direction:column; gap:1.25rem;">
                        <div style="padding:1rem; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px;">
                            <div style="font-size:11px; font-weight:700; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:10px;">Consolidation Plan</div>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:36px; height:36px; background:#dbeafe; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2.5"><path d="M12 22V12m0 0l-4 4m4-4l4 4M2 12h10m0 0l-4-4m4 4l4-4"/></svg>
                                </div>
                                <div>
                                    <div style="font-size:14px; font-weight:700; color:#1e3a8a;">Merging ${data.victims.length + 1} Records</div>
                                    <div style="font-size:12px; color:#3b82f6;">Oldest record will be kept as the Master.</div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Master Record (Oldest)</div>
                            <div style="padding:10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <div style="font-size:13px; font-weight:600; color:#1e293b;">${esc(data.master.label)}</div>
                                    <div style="font-size:10px; color:#64748b; margin-top:2px;">Created: ${data.master.date}</div>
                                </div>
                                <span style="font-size:10px; font-weight:700; color:#64748b; background:#fff; border:1px solid #e2e8f0; padding:2px 6px; border-radius:4px;">ID: ${data.master.id}</span>
                            </div>
                        </div>

                        <div>
                            <div style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Duplicates to be removed</div>
                            <div style="display:flex; flex-direction:column; gap:4px;">
                                ${data.victims.map(v => `
                                    <div style="padding:8px 12px; background:#fff; border:1px solid #f1f5f9; border-radius:8px; display:flex; justify-content:space-between; align-items:center; opacity:0.7;">
                                        <div>
                                            <div style="font-size:12px; color:#475569;">${esc(v.label)}</div>
                                            <div style="font-size:10px; color:#94a3b8; margin-top:1px;">Created: ${v.date}</div>
                                        </div>
                                        <span style="font-size:10px; color:#94a3b8;">ID: ${v.id}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>

                        ${updatesHtml}
                    </div>
                `;

                document.getElementById('btn-confirm-merge').onclick = () => doMerge(targetIds, type, issueId);

            } catch (e) {
                body.innerHTML = `<div style="padding:2rem; text-align:center; color:#ef4444;">Error loading preview: ${esc(e.message)}</div>`;
            }
        }

        async function doMerge(ids, type, issueId) {
            const btn = document.getElementById('btn-confirm-merge');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.innerHTML = `<svg class="spinner-inline" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="margin-right:8px;"><circle cx="12" cy="12" r="10" opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10"></path></svg> Merging...`;

            try {
                const res = await fetch('{{ route("settings.dataquality.merge") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids, type })
                });

                const data = await res.json();
                if (data.success) {
                    document.getElementById('mergeModal').classList.remove('open');
                    showToast('Success', 'Records consolidated successfully.', 'green');
                    // Mark issue as resolved and re-render
                    DQ.resolved.add(issueId);
                    render();
                } else {
                    showToast('Error', data.error || 'Merge failed', 'red');
                }
            } catch (e) {
                showToast('Error', 'Connection error — please try again', 'red');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }

        function ignoreRecord(issueId, recId) {
            DQ.ignored.add(recId);
            DQ.resolvedCount++;

            const rowEl = document.getElementById('dqr-' + recId);
            if (rowEl) rowEl.remove();

            afterRecordChange(issueId);
            showToast('Ignored', 'This record won\'t be flagged again.', 'green');
        }

        /* ══════════════════════════════════════
           DELETE MODAL
        ══════════════════════════════════════ */
        function openDeleteModal(permitId, label, issueId, recId) {
            DQ.pending = { permitId, label, issueId, recId };
            document.getElementById('deleteModal-label').textContent = label;
            document.getElementById('deleteModal').classList.add('open');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
            DQ.pending = null;
        }

        function confirmDelete() {
            if (!DQ.pending) return;
            const { permitId, label, issueId, recId } = DQ.pending;
            closeDeleteModal();

            fetch(`/permits/${permitId}?source=data_quality`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
                .then(r => r.ok ? r.json() : Promise.reject())
                .then(() => {
                    ignoreRecord(issueId, recId); // Re-use ignore logic to remove from UI and update counters
                    showToast('Deleted', `Permit "${label}" and its deceased record were permanently removed.`, 'green');
                })
                .catch(() => showToast('Error', 'Could not delete — please try again.', 'red'));
        }

        /* ══════════════════════════════════════
           QUICK EDIT
           ══════════════════════════════════════ */
        let QE = { type: null, id: null, field: null, issueId: null, recId: null };

        function openQuickEdit(type, id, field, val, label, issueId, recId) {
            QE = { type, id, field, issueId, recId };

            document.getElementById('qe-info').textContent = label;
            document.getElementById('qe-label').textContent = field.replace(/_/g, ' ');

            const container = document.getElementById('qe-input-container');
            container.innerHTML = '';

            let input;
            if (field.includes('date')) {
                input = document.createElement('input');
                input.type = 'date';
                input.value = val !== 'null' ? val : '';
            } else if (field === 'permit_type') {
                input = document.createElement('select');
                const types = [
                    { v: 'cemented', l: 'Cemented' }, { v: 'niche_1st', l: 'Niche 1st' },
                    { v: 'niche_2nd', l: 'Niche 2nd' }, { v: 'niche_3rd', l: 'Niche 3rd' },
                    { v: 'niche_4th', l: 'Niche 4th' }, { v: 'bone_niches', l: 'Bone Niches' }
                ];
                types.forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t.v; opt.textContent = t.l;
                    if (val === t.v) opt.selected = true;
                    input.appendChild(opt);
                });
            } else {
                input = document.createElement('input');
                input.type = 'text';
                input.value = val !== 'null' ? val : '';
            }

            input.className = 'form-control';
            input.id = 'qe-input';
            input.style.width = '100%';
            input.style.padding = '8px';
            input.style.border = '1px solid #ddd';
            input.style.borderRadius = '5px';
            input.style.marginTop = '5px';

            container.appendChild(input);
            document.getElementById('quickEditModal').classList.add('open');

            // Focus
            setTimeout(() => input.focus(), 100);
        }

        function closeQuickEditModal() {
            document.getElementById('quickEditModal').classList.remove('open');
        }

        function saveQuickEdit() {
            const val = document.getElementById('qe-input').value;

            fetch('{{ route("settings.dataquality.update") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ ...QE, value: val })
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        closeQuickEditModal();
                        ignoreRecord(QE.issueId, QE.recId); // Removes it from UI
                        showToast('Updated', 'Record has been corrected.', 'green');
                    } else {
                        showToast('Error', d.error || 'Update failed', 'red');
                    }
                })
                .catch(() => showToast('Error', 'Server error — please try again', 'red'));
        }

        /* ══════════════════════════════════════
           HELPERS
        ══════════════════════════════════════ */
        function afterRecordChange(issueId) {
            const issue = DQ.issues.find(i => i.id === issueId);
            if (!issue) return;

            // For duplicates, if a sub-group (grouped by the field_value they share) only has 1 record left, it's no longer a duplicate
            if (issue.type === 'duplicate') {
                const counts = {};
                issue.records.forEach(r => {
                    if (!DQ.ignored.has(r.id)) {
                        const key = r.field_value;
                        counts[key] = (counts[key] || 0) + 1;
                    }
                });

                let autoCleared = false;
                issue.records.forEach(r => {
                    if (!DQ.ignored.has(r.id) && counts[r.field_value] < 2) {
                        DQ.ignored.add(r.id);
                        autoCleared = true;
                    }
                });

                if (autoCleared) {
                    afterRecordChange(issueId);
                    return;
                }
            }

            const visRecs = issue.records.filter(r => !DQ.ignored.has(r.id));

            if (visRecs.length === 0) {
                if (!DQ.resolved.has(issueId)) {
                    DQ.resolved.add(issueId);
                    DQ.resolvedCount++;
                }
                document.getElementById('dqi-' + issueId)?.remove();
            } else {
                const countEl = document.getElementById('ic-' + issueId);
                if (countEl) countEl.textContent = `${visRecs.length} record${visRecs.length !== 1 ? 's' : ''}`;
                
                // Also update individual row visibility
                issue.records.forEach(r => {
                    const el = document.getElementById('dqr-' + r.id);
                    if (el && DQ.ignored.has(r.id)) el.remove();
                });
            }

            const active = DQ.issues.filter(i => !DQ.resolved.has(i.id) && !allIgnored(i));
            updateCounters(active);

            if (active.length === 0) show('dq-empty');
        }

        function updateCounters(active) {
            let high = 0, med = 0, low = 0;
            active.forEach(i => {
                if (i.severity === 'high') high++;
                else if (i.severity === 'medium') med++;
                else low++;
            });
            document.getElementById('cnt-high').textContent = high;
            document.getElementById('cnt-med').textContent = med;
            document.getElementById('cnt-low').textContent = low;
            document.getElementById('cnt-res').textContent = DQ.resolvedCount;
        }

        function allIgnored(issue) {
            return issue.records.length > 0 && issue.records.every(r => DQ.ignored.has(r.id));
        }

        function show(id) { const el = document.getElementById(id); if (el) el.style.display = ''; }
        function hide(id) { const el = document.getElementById(id); if (el) el.style.display = 'none'; }
        function esc(s) { return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }

        /* ══════════════════════════════════════
           TOAST
        ══════════════════════════════════════ */
        function showToast(title, sub, type) {
            const t = document.getElementById('dqToast');
            const ico = document.getElementById('dqToastIcon');
            const ttl = document.getElementById('dqToastTitle');
            const sb = document.getElementById('dqToastSub');
            const bar = document.getElementById('dqToastBar');

            ttl.textContent = title;
            sb.textContent = sub || '';
            ico.className = 'toast-icon ' + (type || 'green');
            const svgMap = {
                green: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>',
                red: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#991b1b" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
                amber: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            };
            ico.innerHTML = svgMap[type] || svgMap.green;

            const barColor = { green: '#10b981', red: '#ef4444', amber: '#f59e0b' }[type] || '#10b981';

            /* restart animation by replacing the node */
            const newBar = bar.cloneNode(true);
            newBar.style.background = barColor;
            bar.parentNode.replaceChild(newBar, bar);

            t.classList.remove('show');
            clearTimeout(t._timer);
            requestAnimationFrame(() => {
                t.classList.add('show');
                t._timer = setTimeout(() => t.classList.remove('show'), 4500);
            });
        }

        /* ══════════════════════════════════════
           KEYBOARD
        ══════════════════════════════════════ */
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeDeleteModal();
        });

        /* ══════════════════════════════════════
           AUTO-RUN ON LOAD
        ══════════════════════════════════════ */
        document.addEventListener('DOMContentLoaded', () => runScan());
    </script>

    @livewireScripts
</body>

</html>