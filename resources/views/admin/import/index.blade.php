<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel — LGU Carmen</title>
    <script>if (localStorage.getItem('lgu_dark') === '1') document.documentElement.classList.add('dark');</script>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    @include('admin.partials.design-system')
    <style>
        /* Component specific overrides if any */
        html,
        body {
            overflow: hidden !important;
            height: 100vh !important;
        }

        .main {
            overflow: hidden !important;
            height: 100vh !important;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        * {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .dropzone {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 2.5rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all .15s;
            position: relative;
            background: var(--surface);
        }

        .dropzone:hover,
        .dropzone.drag-over {
            border-color: var(--navy);
            background: var(--surface-2);
        }

        .content {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }



        .upload-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 1.25rem 1.5rem;
        }

        .upload-card h3 {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
        }

        .dropzone {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 2.5rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            position: relative;
        }

        .dropzone:hover,
        .dropzone.drag-over {
            border-color: #1a2744;
            background: #f8faff;
        }

        .dropzone input[type=file] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .dropzone-icon {
            width: 44px;
            height: 44px;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto .75rem;
        }

        .dropzone-title {
            font-size: 14px;
            font-weight: 500;
            color: #111827;
        }

        .dropzone-sub {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .dropzone-file {
            font-size: 13px;
            font-weight: 600;
            color: #1a2744;
            margin-top: .5rem;
            display: none;
        }

        .upload-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .upload-note {
            font-size: 12px;
            color: #9ca3af;
        }

        .btn-upload {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: .55rem 1.1rem;
            background: #1a2744;
            color: #fff;
            border: none;
            border-radius: 7px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-upload:hover {
            background: #243459;
        }

        .btn-upload:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .panel-head {
            padding: .85rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-head h3 {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        .panel-head-sub {
            font-size: 12px;
            color: #9ca3af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            font-size: 11px;
            font-weight: 500;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: .5rem .75rem;
            text-align: left;
            background: #fafafa;
        }

        td {
            font-size: 13px;
            color: #374151;
            padding: .65rem .75rem;
            border-top: 1px solid #f3f4f6;
            vertical-align: top;
        }

        .badge-green {
            display: inline-flex;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 4px;
            background: #d1fae5;
            color: #065f46;
        }

        .badge-yellow {
            display: inline-flex;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 4px;
            background: #fef3c7;
            color: #92400e;
        }

        .badge-red {
            display: inline-flex;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 4px;
            background: #fee2e2;
            color: #991b1b;
        }

        .reasons-toggle {
            font-size: 11px;
            color: #1a2744;
            cursor: pointer;
            text-decoration: underline;
            display: block;
            margin-top: 3px;
        }

        .reasons-list {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
            display: none;
            list-style: none;
        }

        .reasons-list li {
            padding: 1px 0;
        }

        .reasons-list.open {
            display: block;
        }

        .empty-row td {
            text-align: center;
            color: #9ca3af;
            padding: 2rem;
            font-size: 13px;
        }

        /* Pagination */
        .pager {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: .75rem 1.25rem;
            border-top: 1px solid #f3f4f6;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .pager-info {
            font-size: 12px;
            color: #6b7280;
        }

        .pager-btns {
            display: flex;
            gap: 3px;
        }

        .pager-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
            padding: 0 9px;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            color: #374151;
            text-decoration: none;
            background: #fff;
            transition: border-color .15s;
        }

        .pager-btn:hover {
            border-color: #1a2744;
            color: #1a2744;
        }

        .pager-btn.active {
            background: #1a2744;
            color: #fff;
            border-color: #1a2744;
            font-weight: 600;
        }

        .pager-btn.disabled {
            color: #d1d5db;
            pointer-events: none;
        }

        /* Toast */
        .toast-stack {
            position: fixed;
            top: 1rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: .6rem;
            pointer-events: none;
        }

        .toast {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            padding: .85rem 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .13);
            min-width: 300px;
            max-width: 440px;
            pointer-events: auto;
            transform: translateX(120%);
            transition: transform .35s cubic-bezier(.34, 1.56, .64, 1);
            position: relative;
            overflow: hidden;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast-green {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .toast-yellow {
            background: #fffbeb;
            border: 1px solid #fde68a;
        }

        .toast-red {
            background: #fef2f2;
            border: 1px solid #fecaca;
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

        .toast-green .toast-icon {
            background: #dcfce7;
        }

        .toast-yellow .toast-icon {
            background: #fef9c3;
        }

        .toast-red .toast-icon {
            background: #fee2e2;
        }

        .toast-body {
            flex: 1;
        }

        .toast-title {
            font-size: 13px;
            font-weight: 600;
        }

        .toast-green .toast-title {
            color: #15803d;
        }

        .toast-yellow .toast-title {
            color: #92400e;
        }

        .toast-red .toast-title {
            color: #991b1b;
        }

        .toast-msg {
            font-size: 12px;
            margin-top: 2px;
            color: #374151;
        }

        .toast-close {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #9ca3af;
            padding: 0;
            flex-shrink: 0;
        }

        .toast-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            transform-origin: left;
            animation: drainBar 7s linear forwards;
        }

        .toast-green .toast-bar {
            background: #22c55e;
        }

        .toast-yellow .toast-bar {
            background: #f59e0b;
        }

        .toast-red .toast-bar {
            background: #ef4444;
        }

        @keyframes drainBar {
            from {
                transform: scaleX(1)
            }

            to {
                transform: scaleX(0)
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    @include(auth()->user()->role === 'super_admin' ? 'superadmin.partials.sidebar' : 'admin.partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div class="topbar-left">
                <div class="topbar-title">
                    Import Excel
                    <a href="{{ route('support.manual') }}#import" class="help-link-trigger" title="How to import data"
                        style="display:inline-flex; vertical-align:middle; margin-left:8px; color:var(--accent); opacity:0.6; transition:opacity .15s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    </a>
                </div>
                <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
            </div>
            <div class="topbar-right">
                <span
                    class="role-pill">{{ auth()->user()->role === 'cemetery_manager' ? 'Cemetery Manager' : 'Admin' }}</span>
            </div>
        </div>


        <div class="content">



            {{-- Upload --}}
            <div class="upload-card">
                <h3>Import Permits from Excel / CSV</h3>
                <form method="POST" action="{{ route('import.excel') }}" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="dropzone" id="dropzone"
                        ondragover="event.preventDefault();this.classList.add('drag-over')"
                        ondragleave="this.classList.remove('drag-over')" ondrop="handleDrop(event)">
                        <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv"
                            onchange="handleFile(this)">
                        <div class="dropzone-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"
                                stroke-width="2">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                        </div>
                        <div class="dropzone-title">Click to upload or drag &amp; drop</div>
                        <div class="dropzone-sub">.xlsx, .xls, or .csv &nbsp;·&nbsp; Max 10 GB</div>
                        <div class="dropzone-file" id="fileName"></div>
                    </div>
                    <div class="upload-footer">
                        <span class="upload-note">Rows missing first_name, last_name, or date_of_death will be
                            skipped.</span>
                        <button type="submit" class="btn-upload" id="submitBtn" disabled>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                            Import Permits
                        </button>
                    </div>
                </form>
            </div>

            {{-- History — 100% server-rendered, no JS dependency --}}
            <div class="panel">
                <div class="panel-head">
                    <h3>Upload History</h3>
                    <span class="panel-head-sub">{{ $logs->total() }} total
                        upload{{ $logs->total() !== 1 ? 's' : '' }}</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Uploaded By</th>
                            <th>Date &amp; Time</th>
                            <th style="text-align:center">Total Rows</th>
                            <th style="text-align:center">Imported</th>
                            <th>Skipped</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td style="font-weight:500;color:#1a2744;max-width:220px;word-break:break-all">
                                    {{ $log->file_name }}
                                </td>
                                <td style="color:#6b7280">{{ optional($log->user)->name ?? 'Admin' }}</td>
                                <td style="color:#6b7280;white-space:nowrap">
                                    {{ $log->created_at->format('M d, Y · g:i A') }}
                                </td>
                                <td style="text-align:center">{{ $log->total_rows }}</td>
                                <td style="text-align:center">
                                    @if($log->imported > 0)
                                        <span class="badge-green">{{ $log->imported }} imported</span>
                                    @else
                                        <span class="badge-red">0 imported</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->skipped > 0)
                                        <span class="badge-yellow">{{ $log->skipped }} skipped</span>
                                        @php
                                            $reasons = is_array($log->skip_reasons)
                                                ? $log->skip_reasons
                                                : json_decode($log->skip_reasons ?? '[]', true);
                                        @endphp
                                        @if(!empty($reasons))
                                            <span class="reasons-toggle" onclick="toggleReasons(this)">show reasons</span>
                                            <ul class="reasons-list">
                                                @foreach($reasons as $r)<li>·
                                                    {{ is_array($r) ? "Row {$r['row']}: {$r['reason']}" : $r }}
                                                </li>@endforeach
                                            </ul>
                                        @endif
                                    @else
                                        <span style="color:#6b7280;font-size:12px;font-weight:500;">0 skipped</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="6">No uploads yet.</td>
                            </tr>
                        @endforelse

                        {{-- Pad with invisible rows to keep pagination locked in place --}}
                        @if(count($logs) > 0 && count($logs) < 5)
                            @for($i = count($logs); $i < 5; $i++)
                                <tr style="visibility: hidden;">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            @endfor
                        @elseif(count($logs) === 0)
                            @for($i = 1; $i < 5; $i++)
                                <tr style="visibility: hidden;">
                                    <td colspan="6">&nbsp;</td>
                                </tr>
                            @endfor
                        @endif
                    </tbody>
                </table>

                @if($logs->hasPages())
                    <div class="pager">
                        <div class="pager-btns">
                            @if($logs->onFirstPage())
                                <span class="pager-btn disabled">‹ Prev</span>
                            @else
                                <a href="{{ $logs->previousPageUrl() }}" class="pager-btn">‹ Prev</a>
                            @endif
                            @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                                <a href="{{ $url }}"
                                    class="pager-btn {{ $page == $logs->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                            @endforeach
                            @if($logs->hasMorePages())
                                <a href="{{ $logs->nextPageUrl() }}" class="pager-btn">Next ›</a>
                            @else
                                <span class="pager-btn disabled">Next ›</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <div class="toast-stack" id="toastStack"></div>

    <script>
        function handleFile(input) {
            const file = input.files[0];
            if (!file) return;
            document.getElementById('fileName').style.display = 'block';
            document.getElementById('fileName').textContent = '📎 ' + file.name;
            document.getElementById('submitBtn').disabled = false;
        }
        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('dropzone').classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (!file) return;
            const dt = new DataTransfer(); dt.items.add(file);
            document.getElementById('fileInput').files = dt.files;
            handleFile(document.getElementById('fileInput'));
        }
        function toggleReasons(el) {
            const list = el.nextElementSibling;
            list.classList.toggle('open');
            el.textContent = list.classList.contains('open') ? 'hide reasons' : 'show reasons';
        }
        document.getElementById('importForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin .7s linear infinite"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0"/></svg> Importing…';
        });

        function showToast(type, title, msg, dur) {
            dur = dur || 7000;
            const ic = { green: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>', yellow: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>', red: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#991b1b" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>' };
            var t = document.createElement('div');
            t.className = 'toast toast-' + type;
            t.innerHTML = '<div class="toast-icon">' + ic[type] + '</div><div class="toast-body"><div class="toast-title">' + title + '</div>' + (msg ? '<div class="toast-msg">' + msg + '</div>' : '') + '</div><button class="toast-close" onclick="(function(el){el.closest(\'.toast\').remove()})(this)">×</button><div class="toast-bar"></div>';
            document.getElementById('toastStack').appendChild(t);
            requestAnimationFrame(function () { setTimeout(function () { t.classList.add('show'); }, 30); });
            setTimeout(function () { t.classList.remove('show'); }, dur);
        }

        window.addEventListener('DOMContentLoaded', function () {
            @if(session('import_success'))
                    @php
                        $imp = (int) session('_import_imported', 0);
                        $skip = (int) session('_import_skipped', 0);
                        $rsns = session('skip_reasons', []);
                        $prev = collect($rsns)->take(3)->map(fn($r) => is_array($r) ? e("Row {$r['row']}: {$r['reason']}") : e($r))->implode(' | ');
                        $xtra = count($rsns) > 3 ? ' +' . (count($rsns) - 3) . ' more' : '';
                    @endphp
                     @if($imp > 0 && $skip === 0)
                        showToast('green', '{{ $imp }} Permit(s) Imported Successfully', 'All rows saved to the database.');
                       @elseif($imp > 0 && $skip > 0)
                        showToast('yellow', '{{ $imp }} Imported, {{ $skip }} Skipped', '{{ addslashes($prev . $xtra) }}', 10000);
                    @elseif($imp === 0 && $skip > 0)
                    showToast('yellow', 'Nothing Imported — {{ $skip }} Skipped', '{{ addslashes($prev . $xtra) }}', 10000);
                  @else
                    showToast('red', 'Nothing Imported', 'No valid rows found.');
                @endif
            @endif
        @if(session('import_error'))
            showToast('red', 'Import Failed', '{{ addslashes(session("import_error")) }}');
        @elseif($errors->has('file'))
            showToast('red', 'Invalid File', '{{ addslashes($errors->first("file")) }}');
        @endif
    });
        < /scrip
t>
 </body >
</html>