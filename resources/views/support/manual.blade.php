<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Manual - LGU Burial System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600|dm-mono:400,500" rel="stylesheet" />
    @include('admin.partials.design-system')
    <style>
        html { scroll-behavior: smooth; }
        .manual-layout { display: grid; grid-template-columns: 260px 1fr; min-height: calc(100vh - 56px); }
        .manual-sidebar { background: var(--surface); border-right: 1px solid var(--border); padding: 1.5rem 1rem; position: sticky; top: 56px; height: calc(100vh - 56px); overflow-y: auto; }
        .manual-content { padding: 2.5rem; max-width: 860px; }
        .doc-section { margin-bottom: 3.5rem; scroll-margin-top: 80px; }
        .doc-section h1 { font-size: 26px; font-weight: 700; margin-bottom: .75rem; letter-spacing: -0.02em; color: var(--text-1); }
        .doc-section h2 { font-size: 17px; font-weight: 600; margin: 1.75rem 0 .6rem; color: var(--navy); }
        .doc-section p { font-size: 14.5px; line-height: 1.7; color: var(--text-2); margin-bottom: .75rem; }
        .doc-section ul { padding-left: 1.25rem; margin-bottom: .75rem; }
        .doc-section ul li { font-size: 14px; color: var(--text-2); line-height: 1.7; }
        .feature-card { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 1.25rem 1.5rem; margin: 1rem 0; display: flex; gap: 1.25rem; align-items: flex-start; }
        .feature-icon { width: 42px; height: 42px; background: var(--accent-bg); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent); flex-shrink: 0; }
        .nav-link { display: block; padding: .5rem .9rem; color: var(--text-2); text-decoration: none; font-size: 13px; font-weight: 500; border-radius: 7px; transition: all .15s; margin-bottom: 2px; }
        .nav-link:hover { background: var(--surface-2); color: var(--navy); }
        .nav-link.active { background: var(--accent-bg); color: var(--accent); }
        .nav-group-title { font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .1em; margin: 1.25rem 0 .4rem .9rem; }
        kbd { background: var(--surface-2); border: 1px solid var(--border); border-radius: 4px; padding: 2px 6px; font-family: var(--mono); font-size: 11px; color: var(--navy); }

        /* Role Banner */
        .role-banner { border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 2.5rem; display: flex; align-items: center; gap: 1.25rem; }
        .role-banner.admin-role { background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border: 1px solid #c7d2fe; }
        .role-banner.super-role { background: linear-gradient(135deg, #faf5ff 0%, #ede9fe 100%); border: 1px solid #ddd6fe; }
        .role-avatar { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; flex-shrink: 0; }
        .admin-role .role-avatar { background: #6366f1; color: #fff; }
        .super-role .role-avatar { background: #7c3aed; color: #fff; }
        .role-banner-info h3 { font-size: 16px; font-weight: 700; margin-bottom: 3px; }
        .admin-role .role-banner-info h3 { color: #3730a3; }
        .super-role .role-banner-info h3 { color: #5b21b6; }
        .role-banner-info p { font-size: 13px; line-height: 1.5; margin: 0; }
        .admin-role .role-banner-info p { color: #4338ca; }
        .super-role .role-banner-info p { color: #6d28d9; }
        .role-pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; margin-bottom: 6px; }
        .admin-role .role-pill { background: #6366f1; color: #fff; }
        .super-role .role-pill { background: #7c3aed; color: #fff; }

        /* Access table */
        .access-table { width: 100%; border-collapse: collapse; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; margin: 1rem 0; }
        .access-table th { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); background: var(--surface-2); padding: .5rem .85rem; text-align: left; }
        .access-table td { font-size: 13.5px; color: var(--text-2); padding: .6rem .85rem; border-top: 1px solid var(--border); vertical-align: middle; }
        .access-table td:first-child { font-weight: 600; color: var(--text-1); }
        .check { color: #10b981; font-weight: 700; }
        .cross { color: #d1d5db; }

        /* Dark mode */
        html.dark body { background: #0f172a; color: #f1f5f9; }
        html.dark .manual-sidebar { background: #111827; border-color: #1e293b; }
        html.dark .feature-card { background: #1e293b; border-color: #334155; }
        html.dark .doc-section h1 { color: #f8fafc; }
        html.dark .doc-section h2 { color: #818cf8; }
        html.dark .doc-section p, html.dark .doc-section ul li { color: #cbd5e1; }
        html.dark .nav-link { color: #94a3b8; }
        html.dark .nav-link:hover { background: #1e293b; color: #f8fafc; }
        html.dark .nav-link.active { background: rgba(99,102,241,.15); color: #818cf8; }
        html.dark .nav-group-title { color: #64748b; }
        html.dark kbd { background: #1e293b; border-color: #334155; color: #e2e8f0; }
        html.dark .feature-icon { background: rgba(99,102,241,.15); color: #818cf8; }
        html.dark .access-table th { background: #1e293b; color: #64748b; }
        html.dark .access-table td { color: #cbd5e1; border-top-color: #334155; }
        html.dark .access-table td:first-child { color: #f1f5f9; }
        html.dark .role-banner.admin-role { background: linear-gradient(135deg,#1e1b4b,#1e1b4b88); border-color: #3730a3; }
        html.dark .role-banner.super-role { background: linear-gradient(135deg,#2e1065,#2e106588); border-color: #5b21b6; }
        html.dark .admin-role .role-banner-info h3 { color: #a5b4fc; }
        html.dark .super-role .role-banner-info h3 { color: #c4b5fd; }
        html.dark .admin-role .role-banner-info p { color: #818cf8; }
        html.dark .super-role .role-banner-info p { color: #a78bfa; }
    </style>
</head>
<body>
    @include(auth()->user()->role === 'super_admin' ? 'superadmin.partials.sidebar' : 'admin.partials.sidebar')

    @php $role = auth()->user()->role ?? 'admin'; $isSuperAdmin = $role === 'super_admin'; @endphp

    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <div class="topbar-title">User Manual</div>
                <div class="topbar-date">System Tutorial &amp; Guide</div>
            </div>
            <div class="topbar-right">
                <a href="{{ $isSuperAdmin ? route('superadmin.dashboard') : route('dashboard') }}" class="btn-xs">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Back to Dashboard
                </a>
            </div>
        </header>

        <div class="manual-layout">
            <aside class="manual-sidebar">
                <div class="nav-group-title">Getting Started</div>
                <a href="#role" class="nav-link">Your Role &amp; Access</a>
                <a href="#overview" class="nav-link">System Overview</a>
                <a href="#navigation" class="nav-link">Navigation &amp; Shortcuts</a>

                <div class="nav-group-title">Core Modules</div>
                <a href="#permits" class="nav-link">Burial Permits</a>
                <a href="#cemetery-map" class="nav-link">Cemetery GIS Map</a>

                <div class="nav-group-title">Tools</div>
                <a href="#reports" class="nav-link">Reports &amp; Exports</a>
                <a href="#import" class="nav-link">Import Excel</a>
                <a href="#data-quality" class="nav-link">Data Quality Scanner</a>

                @if($isSuperAdmin)
                <div class="nav-group-title">Super Admin Only</div>
                <a href="#settings" class="nav-link">System Settings</a>
                <a href="#users" class="nav-link">Users &amp; Access</a>
                <a href="#activity" class="nav-link">Activity Log</a>
                @endif
            </aside>

            <main class="manual-content">

                {{-- ROLE BANNER --}}
                <section id="role" class="doc-section">
                    <div class="role-banner {{ $isSuperAdmin ? 'super-role' : 'admin-role' }}">
                        <div class="role-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div class="role-banner-info">
                            <div class="role-pill">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                {{ $isSuperAdmin ? 'Super Administrator' : 'Administrator' }}
                            </div>
                            <h3>Welcome, {{ auth()->user()->name }}</h3>
                            <p>
                                @if($isSuperAdmin)
                                    You have <strong>full system access</strong>. You can manage all burial permits, cemetery plots, reports, import data, configure system settings, manage user accounts, and review the complete activity log.
                                @else
                                    You are logged in as <strong>Admin ({{ auth()->user()->email }})</strong>. You can manage burial permits, assign cemetery niches, run reports, import data, and scan data quality. System configuration and user management are handled by a Super Administrator.
                                @endif
                            </p>
                        </div>
                    </div>

                    <h1>Your Role &amp; Access</h1>
                    <p>Below is a summary of the modules available to your role.</p>

                    <table class="access-table">
                        <thead><tr><th>Module</th><th>Description</th><th>Admin</th>@if($isSuperAdmin)<th>Super Admin</th>@endif</tr></thead>
                        <tbody>
                            <tr><td>Dashboard</td><td>Overview of permits, stats &amp; alerts</td><td class="check">✓</td>@if($isSuperAdmin)<td class="check">✓</td>@endif</tr>
                            <tr><td>Burial Permits</td><td>Create, view, renew &amp; delete permits</td><td class="check">✓</td>@if($isSuperAdmin)<td class="check">✓</td>@endif</tr>
                            <tr><td>Cemetery GIS Map</td><td>Assign &amp; manage niche plots visually</td><td class="check">✓</td>@if($isSuperAdmin)<td class="check">✓</td>@endif</tr>
                            <tr><td>Reports &amp; Exports</td><td>Generate PDF &amp; Excel reports</td><td class="check">✓</td>@if($isSuperAdmin)<td class="check">✓</td>@endif</tr>
                            <tr><td>Import Excel</td><td>Bulk-import permit records from Excel</td><td class="check">✓</td>@if($isSuperAdmin)<td class="check">✓</td>@endif</tr>
                            <tr><td>Data Quality Scanner</td><td>Detect duplicates &amp; data errors</td><td class="check">✓</td>@if($isSuperAdmin)<td class="check">✓</td>@endif</tr>
                            @if($isSuperAdmin)
                            <tr><td>System Settings</td><td>Fees, general config &amp; danger zone</td><td class="cross">—</td><td class="check">✓</td></tr>
                            <tr><td>Users &amp; Access</td><td>Add, remove &amp; manage user accounts</td><td class="cross">—</td><td class="check">✓</td></tr>
                            <tr><td>Activity Log</td><td>Audit trail of all system actions</td><td class="cross">—</td><td class="check">✓</td></tr>
                            @endif
                        </tbody>
                    </table>
                </section>

                {{-- OVERVIEW --}}
                <section id="overview" class="doc-section">
                    <h1>System Overview</h1>
                    <p>The LGU Burial System digitizes the management of burial permits, deceased records, and cemetery plot allocations for the Municipality of Carmen using a modern GIS-based approach.</p>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        </div>
                        <div>
                            <h2>What the system covers</h2>
                            <ul>
                                <li>Issuing and tracking burial permits with auto-generated permit numbers</li>
                                <li>Visual GIS cemetery grid showing real-time niche occupancy</li>
                                <li>Automated permit expiry detection with renewal workflows</li>
                                <li>Municipal-branded PDF and Excel exports for official reporting</li>
                                <li>Data quality scanning to maintain clean records</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- NAVIGATION --}}
                <section id="navigation" class="doc-section">
                    <h1>Navigation &amp; Shortcuts</h1>
                    <p>Use the sidebar on the left to navigate between modules. The sidebar groups features by category based on your role.</p>
                    <h2>Global Keyboard Shortcuts</h2>
                    <table style="max-width:480px; border-collapse:collapse; border:1px solid var(--border); border-radius:8px; overflow:hidden;">
                        <thead><tr><th style="padding:.5rem .85rem; font-size:11px; font-weight:600; text-transform:uppercase; color:var(--muted); background:var(--surface-2);">Action</th><th style="padding:.5rem .85rem; font-size:11px; font-weight:600; text-transform:uppercase; color:var(--muted); background:var(--surface-2);">Shortcut</th></tr></thead>
                        <tbody>
                            <tr><td style="padding:.6rem .85rem; font-size:13.5px; border-top:1px solid var(--border);">Quick Search</td><td style="padding:.6rem .85rem; border-top:1px solid var(--border);"><kbd>Ctrl</kbd> + <kbd>F</kbd></td></tr>
                            <tr><td style="padding:.6rem .85rem; font-size:13.5px; border-top:1px solid var(--border);">Toggle Dark Mode</td><td style="padding:.6rem .85rem; border-top:1px solid var(--border);"><kbd>Alt</kbd> + <kbd>D</kbd></td></tr>
                            <tr><td style="padding:.6rem .85rem; font-size:13.5px; border-top:1px solid var(--border);">Toggle Sidebar</td><td style="padding:.6rem .85rem; border-top:1px solid var(--border);"><kbd>Alt</kbd> + <kbd>B</kbd></td></tr>
                        </tbody>
                    </table>
                </section>

                {{-- BURIAL PERMITS --}}
                <section id="permits" class="doc-section">
                    <h1>Burial Permits</h1>
                    <p>Burial permits are the core record of the system. Every deceased individual interred at the LGU Carmen cemetery must have an associated permit.</p>
                    <h2>What you can do here</h2>
                    <ul>
                        <li>Create a new burial permit linked to a deceased person's details</li>
                        <li>View permit history, renewal dates, and associated documents</li>
                        <li>Renew permits nearing or past their expiry</li>
                        <li>Print or export individual permit records as PDF</li>
                        <li>Delete permits (with confirmation) when required</li>
                    </ul>
                    <h2>Permit Status</h2>
                    <p>Permits automatically change status based on their expiry date:</p>
                    <ul>
                        <li><strong>Active</strong> — valid; no action needed</li>
                        <li><strong>Expiring Soon</strong> — within the warning window (configurable in Settings)</li>
                        <li><strong>Expired</strong> — requires immediate renewal or bone transfer</li>
                    </ul>
                </section>

                {{-- CEMETERY MAP --}}
                <section id="cemetery-map" class="doc-section">
                    <h1>Cemetery GIS Map</h1>
                    <p>The interactive GIS map gives a real-time visual layout of every niche in the cemetery. Greyed slots are available; coloured slots are occupied.</p>
                    <h2>What you can do here</h2>
                    <ul>
                        <li>Browse niche sections (Ground, 1st–4th floor) on the visual grid</li>
                        <li>Click any niche to see who is assigned or to assign a new occupant</li>
                        <li>Link a niche directly to an existing Burial Permit</li>
                        <li>Unassign or transfer a niche when a bone transfer is needed</li>
                    </ul>
                    <h2>Assigning a Niche</h2>
                    <p>Navigate to the map, find an empty (greyed) slot and click <strong>"Assign"</strong>. Follow the prompts to link a Deceased Record or Burial Permit. The slot will immediately show as occupied on the grid.</p>
                </section>

                {{-- REPORTS --}}
                <section id="reports" class="doc-section">
                    <h1>Reports &amp; Exports</h1>
                    <p>Generate official, municipal-branded reports for administrative and financial purposes.</p>
                    <h2>What you can do here</h2>
                    <ul>
                        <li>Export the full permit list to <strong>Excel (XLSX)</strong> with proper headers</li>
                        <li>Generate a <strong>PDF report</strong> with the LGU letterhead and signature blocks</li>
                        <li>Filter reports by date range, permit status, or burial type</li>
                        <li>View consolidated revenue summaries based on fee schedules</li>
                    </ul>
                </section>

                {{-- IMPORT --}}
                <section id="import" class="doc-section">
                    <h1>Import Excel</h1>
                    <p>Bulk-import historical or external burial permit records into the system using a structured Excel template.</p>
                    <h2>What you can do here</h2>
                    <ul>
                        <li>Download the official import template with required column headers</li>
                        <li>Upload a completed Excel file for batch processing</li>
                        <li>Review import results — successful rows, skipped rows, and reasons</li>
                        <li>Fix validation errors and re-upload as needed</li>
                    </ul>
                    <p><strong>Note:</strong> Duplicate permit numbers are automatically skipped. Each import is logged in the system history.</p>
                </section>

                {{-- DATA QUALITY --}}
                <section id="data-quality" class="doc-section">
                    <h1>Data Quality Scanner</h1>
                    <p>The scanner runs an automated 11-point check across all deceased records to identify data integrity issues before they become problems.</p>
                    <h2>What you can do here</h2>
                    <ul>
                        <li>Detect <strong>duplicate permits</strong> — same person issued multiple permits</li>
                        <li>Find <strong>swapped names</strong> — first and last name entered in wrong order</li>
                        <li>Identify <strong>missing links</strong> — permits with no associated deceased record</li>
                        <li>Spot <strong>logical errors</strong> — e.g., death date before birth date</li>
                        <li>Navigate directly to a flagged record and fix it in one click</li>
                    </ul>
                    <p>Run this scanner regularly to maintain clean, reliable records.</p>
                </section>

                @if($isSuperAdmin)
                {{-- SETTINGS (Super Admin only) --}}
                <section id="settings" class="doc-section">
                    <h1>System Settings</h1>
                    <p>As Super Admin, you have full access to system configuration. Changes here affect the entire system for all users.</p>
                    <h2>What you can configure</h2>
                    <ul>
                        <li><strong>General</strong> — Municipality name, Civil Registrar, Mayor, permit prefix &amp; expiry rules</li>
                        <li><strong>Permit Fees</strong> — Set tomb, permit, maintenance, and application fees per burial type</li>
                        <li><strong>Notifications</strong> — Toggle expiry reminders, import toasts, and expired permit highlights</li>
                        <li><strong>Appearance</strong> — Switch between Light and Dark mode system-wide</li>
                        <li><strong>Danger Zone</strong> — Reset fees to defaults, clear import logs, or reset all settings to factory state</li>
                    </ul>
                </section>

                {{-- USERS (Super Admin only) --}}
                <section id="users" class="doc-section">
                    <h1>Users &amp; Access</h1>
                    <p>Manage who can log in to the system and what level of access they have.</p>
                    <h2>What you can do here</h2>
                    <ul>
                        <li>Add new user accounts (Admin or Super Admin)</li>
                        <li>View all registered users and their roles</li>
                        <li>Remove a user account when they leave the office</li>
                    </ul>
                    <p><strong>Roles:</strong> <em>Admin</em> handles day-to-day operations. <em>Super Admin</em> has full control including settings and user management.</p>
                </section>

                {{-- ACTIVITY LOG (Super Admin only) --}}
                <section id="activity" class="doc-section">
                    <h1>Activity Log</h1>
                    <p>A complete audit trail of every action performed in the system — who did what, and when.</p>
                    <h2>What you can see here</h2>
                    <ul>
                        <li>Login and logout events for all users</li>
                        <li>Permit creations, updates, renewals, and deletions</li>
                        <li>Settings changes and danger zone actions</li>
                        <li>Excel imports and their outcomes</li>
                    </ul>
                    <p>Use the activity log to investigate any unexpected changes or to maintain compliance records.</p>
                </section>
                @endif

            </main>
        </div>
    </div>

    <script>
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('.doc-section');
        let isClicking = false;

        const observer = new IntersectionObserver((entries) => {
            if (isClicking) return;
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    navLinks.forEach(l => { l.classList.remove('active'); if (l.getAttribute('href') === '#' + id) l.classList.add('active'); });
                }
            });
        }, { rootMargin: '-10% 0px -80% 0px', threshold: 0 });

        sections.forEach(s => observer.observe(s));

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                isClicking = true;
                const id = link.getAttribute('href').substring(1);
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                setTimeout(() => { isClicking = false; }, 1000);
            });
        });
    </script>
</body>
</html>
