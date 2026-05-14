<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            scrollbar-gutter: stable;
        }

        .main { width: 100%; display: flex; flex-direction: column; min-width: 0; background: var(--surface-2); }   
        
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; height: 56px; display: flex; align-items: center; justify-content: space-between; padding: 0 1.75rem; }
        .topbar-left { display: flex; flex-direction: column; gap: 1px; }
        .topbar-title { font-size: 15px; font-weight: 600; color: var(--text-1); letter-spacing: -.01em; }
        .topbar-date  { font-size: 11px; color: var(--text-3); font-weight: 400; }
        .topbar-right { display: flex; align-items: center; gap: .75rem; }

        .btn-new {
            display: inline-flex; align-items: center; gap: 6px;
            padding: .45rem 1rem; background: var(--navy); color: #fff;
            border: none; border-radius: 8px;
            font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;
            cursor: pointer; transition: background .15s, transform .12s; text-decoration: none;
        }
        .btn-new:hover { background: var(--navy-light); transform: translateY(-1px); }

        .content { padding: 1.75rem; display: flex; flex-direction: column; gap: 1rem; }

        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px; overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .panel-head {
            padding: .9rem 1.25rem; border-bottom: 1px solid var(--border-2);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-title { font-size: 13px; font-weight: 600; color: var(--text-1); letter-spacing: -.01em; }

        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th { 
            text-align: left; padding: 0.75rem 1.25rem; 
            background: var(--surface-2); color: var(--text-3);
            font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
        }
        td { 
            padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-2); 
            font-size: 13px; color: var(--text-2);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--surface-2); }

        .user-cell { display: flex; align-items: center; gap: 10px; }
        .user-avatar { 
            width: 32px; height: 32px; border-radius: 8px; background: var(--accent-bg);
            color: var(--accent); display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 12px; flex-shrink: 0;
        }
        .user-name { font-weight: 500; color: var(--text-1); }
        .user-email { font-size: 11px; color: var(--text-3); font-family: var(--mono); }

        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 600;
            font-family: var(--mono); text-transform: uppercase;
        }
        .badge-super { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }
        .badge-admin { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .badge-scanner { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .badge-cemetery { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; }

        .actions { display: flex; gap: 8px; }
        .btn-icon {
            width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-3); background: #fff; cursor: pointer; transition: all 0.15s;
        }
        .btn-icon:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-bg); }
        .btn-icon.delete:hover { border-color: var(--red); color: var(--red); background: #fef2f2; }

        .empty-state { padding: 3rem; text-align: center; color: var(--text-3); font-size: 13px; }

        /* Pagination */
        .pagination-container { padding: 1rem 1.25rem; background: var(--surface-2); border-top: 1px solid var(--border); }

        /* Dark Mode */
        html.dark body { background: #0f1117; color: #e2e8f0; }
        html.dark .main { background: #0f1117; }
        html.dark .topbar { background: #1a1d27; border-bottom-color: #2d3148; }
        html.dark .topbar-title { color: #e2e8f0; }
        html.dark .panel { background: #1e2130; border-color: #2d3148; }
        html.dark th { background: #181b29; color: #64748b; border-bottom-color: #2d3148; }
        html.dark td { color: #cbd5e1; border-bottom-color: #2d3148; }
        html.dark tr:hover td { background: #252840; }
        html.dark .user-name { color: #e2e8f0; }
        html.dark .user-avatar { background: #1e2d6b; color: #818cf8; }
        html.dark .btn-icon { background: #1e2130; border-color: #2d3148; }
    </style>
</head>
<body>

@include('superadmin.partials.sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">User Management</div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <div class="topbar-right">
            <button class="btn-new">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Create New User
            </button>
        </div>
    </div>

    <div class="content">
        <div class="panel">
            <div class="panel-head">
                <div class="panel-title">System Users ({{ $users->total() }})</div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 35%;">User</th>
                        <th style="width: 20%;">Role</th>
                        <th style="width: 25%;">Registered</th>
                        <th style="width: 20%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $roleClass = match($user->role) {
                                    'super_admin' => 'badge-super',
                                    'admin'       => 'badge-admin',
                                    'scanner'     => 'badge-scanner',
                                    'cemetery_manager' => 'badge-cemetery',
                                    default       => 'badge-admin',
                                };
                                $roleLabel = match($user->role) {
                                    'super_admin' => 'Super Admin',
                                    'admin'       => 'Administrator',
                                    'scanner'     => 'Data Scanner',
                                    'cemetery_manager' => 'Cemetery Manager',
                                    default       => ucfirst($user->role),
                                };
                            @endphp
                            <span class="badge {{ $roleClass }}">{{ $roleLabel }}</span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td style="text-align: right;">
                            <div class="actions" style="justify-content: flex-end;">
                                <button class="btn-icon" title="Edit User">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <button class="btn-icon delete" title="Delete User">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">No users found in the system.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-container">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        if (localStorage.getItem('lgu_dark') === '1') {
            document.documentElement.classList.add('dark');
        }
    })();
</script>

</body>
</html>
