<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sa-sidebar.css') }}">
    <style>
        :root {
            --navy:       #0f1e3d;
            --navy-mid:   #1a2f5e;
            --navy-light: #243459;
            --accent:     #3b82f6; /* Changed to match dashboard blue */
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
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .main { width: 100%; display: flex; flex-direction: column; min-width: 0; }

        /* ── TOPBAR ── */
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 56px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.75rem; }
        .topbar-title { font-size: 16px; font-weight: 700; color: var(--text-1); letter-spacing: -.02em; }
        .topbar-date  { font-size: 11px; color: var(--text-3); font-family: var(--mono); margin-top: 2px; }

        .role-pill {
            font-family: var(--mono);
            font-size: 10px; font-weight: 600;
            color: var(--accent);
            background: var(--accent-bg);
            border: 1px solid #ddd6fe;
            padding: 4px 12px; border-radius: 20px;
            letter-spacing: .06em; text-transform: uppercase;
        }

        .back-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 34px; height: 34px; border-radius: 9px;
            color: var(--text-2); text-decoration: none;
            border: 1px solid var(--border);
            background: var(--surface);
            transition: all .2s;
        }
        .back-btn:hover { background: var(--surface-2); color: var(--accent); border-color: #bfdbfe; transform: translateX(-2px); }

        /* ── CONTENT ── */
        .content { padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem; }

        .page-header h2 { font-size: 24px; font-weight: 800; color: var(--text-1); letter-spacing: -.03em; }
        .page-header p  { font-size: 12px; color: var(--text-3); margin-top: 4px; font-family: var(--mono); letter-spacing: 0.05em; }

        /* ── FILTER BAR ── */
        .filter-bar {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: .75rem 1.25rem;
            display: flex; align-items: center; gap: .6rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .filter-label {
            font-size: 11px; font-weight: 700; color: var(--text-3);
            text-transform: uppercase; letter-spacing: .1em; margin-right: .5rem;
        }
        .filter-btn {
            padding: .4rem .9rem; border-radius: 8px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; border: 1px solid transparent;
            background: transparent; color: var(--text-2);
            transition: all .2s;
        }
        .filter-btn:hover  { background: var(--surface-2); color: var(--text-1); }
        .filter-btn.active { background: var(--accent-bg); border-color: #ddd6fe; color: var(--accent); }

        /* ── PANEL ── */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }
        .panel-head {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-2);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-title { font-size: 15px; font-weight: 700; color: var(--text-1); }
        .panel-count {
            font-size: 11px; font-family: var(--mono); color: var(--text-2);
            background: #f1f5f9; padding: 4px 10px; border-radius: 20px;
            font-weight: 600;
        }

        /* ── TABLE ── */
        .log-table { width: 100%; border-collapse: collapse; }
        .log-table thead th {
            padding: .85rem 1.5rem;
            font-size: 11px; font-weight: 700;
            color: var(--text-3); text-transform: uppercase; letter-spacing: .1em;
            text-align: left; background: var(--surface-2);
        }
        .log-table thead th:last-child { text-align: right; }

        .log-table tbody tr { border-bottom: 1px solid var(--border-2); transition: background .2s; }
        .log-table tbody tr:hover { background: #fbfcfe; }

        .log-table tbody td { padding: 1rem 1.5rem; vertical-align: middle; }

        /* col: Action */
        .td-action { display: flex; align-items: center; gap: .75rem; }
        .action-dot {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 800; flex-shrink: 0;
            box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);
        }
        .dot-green  { background: #dcfce7; color: #16a34a; }
        .dot-indigo { background: #e0e7ff; color: #4338ca; }
        .dot-teal   { background: #ccfbf1; color: #0d9488; }
        .dot-red    { background: #fee2e2; color: #dc2626; }
        .dot-amber  { background: #fef3c7; color: #d97706; }
        .dot-violet { background: #ede9fe; color: #7c3aed; }
        .dot-blue   { background: #dbeafe; color: #1d4ed8; }

        .badge {
            font-size: 10px; font-weight: 700;
            padding: 3px 8px; border-radius: 6px;
            text-transform: uppercase; letter-spacing: .05em; font-family: var(--mono);
        }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-indigo { background: #e0e7ff; color: #4338ca; }
        .badge-teal   { background: #ccfbf1; color: #0d9488; }
        .badge-red    { background: #fee2e2; color: #dc2626; }
        .badge-amber  { background: #fef3c7; color: #d97706; }
        .badge-violet { background: #ede9fe; color: #7c3aed; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }

        /* col: Description */
        .td-desc { max-width: 400px; }
        .td-desc-text {
            color: var(--text-1); font-size: 14px; line-height: 1.5;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .td-desc-text strong { color: var(--navy); font-weight: 700; }

        /* col: User Chip */
        .td-user { white-space: nowrap; width: 180px; }
        .user-chip {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 4px 12px 4px 5px; border-radius: 40px;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            transition: all .2s;
        }
        .user-chip:hover { border-color: var(--accent); background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        
        .user-avatar {
            width: 26px; height: 26px; border-radius: 0;
            background: transparent;
            font-size: 11px; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .user-name { font-size: 13px; color: var(--text-1); font-weight: 700; letter-spacing: -0.01em; }

        /* Role Variations */
        .user-chip.role-super-admin { background: #f5f3ff; border-color: #ddd6fe; }
        .user-chip.role-super-admin .user-name, .user-chip.role-super-admin .user-avatar { color: #5b21b6; }

        .user-chip.role-admin { background: #eff6ff; border-color: #dbeafe; }
        .user-chip.role-admin .user-name, .user-chip.role-admin .user-avatar { color: #1e40af; }

        .user-chip.role-system { background: #f8fafc; border-color: #e2e8f0; }
        .user-chip.role-system .user-name, .user-chip.role-system .user-avatar { color: #475569; font-weight: 500; }

        /* Dark Mode Overrides */
        html.dark .user-chip.role-super-admin { background: #2e1065; border-color: #4c1d95; }
        html.dark .user-chip.role-super-admin .user-name, html.dark .user-chip.role-super-admin .user-avatar { color: #ddd6fe; }
        html.dark .user-chip.role-admin { background: #172554; border-color: #1e3a8a; }
        html.dark .user-chip.role-admin .user-name, html.dark .user-chip.role-admin .user-avatar { color: #dbeafe; }
        html.dark .user-chip.role-system { background: #1e293b; border-color: #334155; }
        html.dark .user-chip.role-system .user-name, html.dark .user-chip.role-system .user-avatar { color: #94a3b8; }

        /* col: Time */
        .td-time { text-align: right; white-space: nowrap; min-width: 140px; }
        .time-ago  { font-size: 13px; color: var(--text-2); font-weight: 600; }
        .time-full { font-size: 11px; color: var(--text-3); margin-top: 3px; font-family: var(--mono); }

        /* ── PAGINATION ── */
        .pagination-wrap {
            padding: 1.25rem 1.5rem;
            background: var(--surface-2);
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem;
        }
        .pagination-info { font-size: 12px; color: var(--text-2); font-weight: 500; }
        .pagination-links { display: flex; gap: .4rem; }
        .pagination-links a, .pagination-links span {
            padding: .5rem .85rem; border-radius: 8px;
            font-size: 13px; font-weight: 600; text-decoration: none;
            border: 1px solid var(--border);
            background: var(--surface); color: var(--text-2);
            transition: all .2s;
        }
        .pagination-links a:hover         { border-color: var(--accent); color: var(--accent); background: var(--accent-bg); }
        .pagination-links span.current    { background: var(--accent); border-color: var(--accent); color: #fff; }
        .pagination-links span.disabled   { opacity: .5; background: #f1f5f9; cursor: not-allowed; }

        /* ── ANIMATIONS ── */
        .fade-up { opacity: 0; transform: translateY(12px); animation: fadeUp .4s cubic-bezier(0,0,0.2,1) forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: none; } }
        .d1 { animation-delay: .05s; }
        .d2 { animation-delay: .12s; }
        .d3 { animation-delay: .2s; }
    </style>
</head>
<body>

@include(auth()->user()->role === 'super_admin' ? 'superadmin.partials.sidebar' : 'admin.partials.sidebar')

<div class="main">

    <div class="topbar">
        <div class="topbar-left" style="display:flex; align-items:center; gap: 1rem;">
            <a href="{{ route('superadmin.dashboard') }}" class="back-btn" title="Back to Dashboard">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
            <div style="width: 1px; height: 24px; background: var(--border);"></div>
            <div>
                <div class="topbar-title">Central Activity Log</div>
                <div class="topbar-date">{{ now()->format('l, j F Y · H:i') }}</div>
            </div>
        </div>
        <div class="topbar-right" style="display:flex;align-items:center;gap:10px;">
            <span class="role-pill">⚡ Super Admin</span>
            <div style="width:34px;height:34px;border-radius:50%;overflow:hidden;border:2px solid #e2e8f0;flex-shrink:0;background:#1a2744;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
        </div>
    </div>

    <div class="content">

        <div class="page-header fade-up d1">
            <div>
                <h2>System-Wide Audit Log</h2>
                <p>TRACKING EVERY TRANSACTION · MUNICIPALITY OF CARMEN</p>
            </div>
        </div>

        <div class="filter-bar fade-up d2">
            <span class="filter-label">Quick Filter</span>
            <button class="filter-btn active" onclick="filterTable('all', this)">All Activity</button>
            <button class="filter-btn" onclick="filterTable('created', this)">Creations</button>
            <button class="filter-btn" onclick="filterTable('updated', this)">Updates</button>
            <button class="filter-btn" onclick="filterTable('deleted', this)">Deletions</button>
            <button class="filter-btn" onclick="filterTable('approved', this)">Approvals</button>
            <button class="filter-btn" onclick="filterTable('released', this)">Releases</button>
            <button class="filter-btn" onclick="filterTable('renewed', this)">Renewals</button>
            <button class="filter-btn" onclick="filterTable('imported', this)">Imports</button>
        </div>

        <div class="panel fade-up d3">
            <div class="panel-head">
                <div class="panel-title">Recent Transactions</div>
                <span class="panel-count" id="recordCount">{{ $activity->total() }} total entries</span>
            </div>

            @if($activity->total() > 0)
            <table class="log-table" id="logTable">
                <thead>
                    <tr>
                        <th style="width: 160px">Action</th>
                        <th>Event Description</th>
                        <th>Performed By</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activity as $log)
                    @php
                        $action = $log->action ?? 'updated';
                        $dotClass = match($action) {
                            'created'  => 'dot-green',
                            'approved' => 'dot-indigo',
                            'released' => 'dot-teal',
                            'expired'  => 'dot-red',
                            'renewed'  => 'dot-amber',
                            'imported' => 'dot-violet',
                            'updated'  => 'dot-blue',
                            'deleted'  => 'dot-red',
                            default    => 'dot-gray',
                        };
                        $icon = match($action) {
                            'created'  => '+',
                            'approved' => '✓',
                            'released' => '↑',
                            'expired'  => '!',
                            'renewed'  => '↻',
                            'imported' => '⇩',
                            'updated'  => '✎',
                            'deleted'  => '✕',
                            default    => '•',
                        };
                        $badgeClass = match($action) {
                            'created'  => 'badge-green',
                            'approved' => 'badge-indigo',
                            'released' => 'badge-teal',
                            'expired'  => 'badge-red',
                            'renewed'  => 'badge-amber',
                            'imported' => 'badge-violet',
                            'updated'  => 'badge-blue',
                            'deleted'  => 'badge-red',
                            default    => 'badge-gray',
                        };

                        $userName = $log->user?->name ?? 'System';
                        $role = $log->user?->role ?? 'system';
                        $isSys = ($userName === 'System' || trim($userName) === '' || $role === 'system');
                        $avatar = strtoupper(substr($userName ?: 'S', 0, 1));
                        
                        $chipRoleClass = match($role) {
                            'super_admin' => 'role-super-admin',
                            'admin'       => 'role-admin',
                            default       => 'role-system',
                        };
                    @endphp
                    <tr data-action="{{ $action }}">
                        <td>
                            <div class="td-action">
                                <div class="action-dot {{ $dotClass }}">{{ $icon }}</div>
                                <span class="badge {{ $badgeClass }}">{{ $action }}</span>
                            </div>
                        </td>
                        <td class="td-desc">
                            <div class="td-desc-text">
                                {{ $log->description }}
                                @if($log->model_label) — <strong>{{ $log->model_label }}</strong> @endif
                            </div>
                        </td>
                        <td class="td-user">
                            <div class="user-chip {{ $chipRoleClass }}">
                                <div class="user-avatar" style="overflow:hidden;">
                                    @if(!$isSys && $log->user?->profile_photo)
                                        <img src="{{ asset('storage/' . $log->user->profile_photo) }}" alt="{{ $userName }}" style="width:100%;height:100%;object-fit:cover;border-radius:0;">
                                    @else
                                        {{ $avatar }}
                                    @endif
                                </div>
                                <span class="user-name">
                                    {{ $isSys ? 'System Action' : $userName }}
                                </span>
                            </div>
                        </td>
                        <td class="td-time">
                            <div class="time-ago">{{ $log->created_at->diffForHumans() }}</div>
                            <div class="time-full">{{ $log->created_at->format('M d, Y · H:i') }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>No activity logs found matching your filters.</p>
            </div>
            @endif

            @if($activity->hasPages())
            <div class="pagination-wrap">
                <div class="pagination-info">
                    Records {{ $activity->firstItem() }} to {{ $activity->lastItem() }} of {{ $activity->total() }}
                </div>
                <div class="pagination-links">
                    {{-- Previous --}}
                    @if($activity->onFirstPage())
                        <span class="disabled">Previous</span>
                    @else
                        <a href="{{ $activity->previousPageUrl() }}">Previous</a>
                    @endif

                    {{-- Simple pagination logic for demo --}}
                    @for($i = max(1, $activity->currentPage() - 2); $i <= min($activity->lastPage(), $activity->currentPage() + 2); $i++)
                        @if($i == $activity->currentPage())
                            <span class="current">{{ $i }}</span>
                        @else
                            <a href="{{ $activity->url($i) }}">{{ $i }}</a>
                        @endif
                    @endfor

                    {{-- Next --}}
                    @if($activity->hasMorePages())
                        <a href="{{ $activity->nextPageUrl() }}">Next Page</a>
                    @else
                        <span class="disabled">Next Page</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterTable(action, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    let visible = 0;
    document.querySelectorAll('#logTable tbody tr').forEach(row => {
        const match = (action === 'all') || (row.dataset.action === action);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    const total = {{ $activity->total() }};
    document.getElementById('recordCount').textContent = action === 'all' 
        ? `${total} total entries` 
        : `${visible} shown · ${total} total`;
}
</script>

</body>
</html>