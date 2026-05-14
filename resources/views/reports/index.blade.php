<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — LGU Carmen</title>
    <script>/* dark-mode anti-flash */
        (function () { try { if (localStorage.getItem('lgu_dark') === '1') document.documentElement.classList.add('dark'); } catch (e) { } })();
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    @include('admin.partials.design-system')
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* ── PROFESSIONAL DOCUMENT-STYLE REPORT THEME ── */
        :root {
            --report-bg: #fdfdfd;
            --section-title: #1e293b;
            --data-val: #0f172a;
            --data-label: #64748b;
        }

        .dark {
            --report-bg: #0f1117;
            --section-title: #f1f5f9;
            --data-val: #f8fafc;
            --data-label: #94a3b8;
        }

        .report-section {
            margin-bottom: 2.5rem;
            page-break-inside: avoid;
        }

        .formal-section-header {
            background-color: var(--section-title);
            color: #ffffff;
            padding: 0.5rem 1rem;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }

        .dark .formal-section-header {
            background-color: #1e293b;
            color: #f1f5f9;
        }

        /* Condensed Data Grid */
        .data-grid {
            width: 100%;
            border-collapse: collapse;
            background: var(--surface);
            border: 1px solid var(--border);
            border-top: none;
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
            overflow: hidden;
        }

        .data-grid th {
            text-align: left;
            padding: 0.75rem 1rem;
            background: var(--surface-2);
            font-size: 11px;
            font-weight: 700;
            color: var(--data-label);
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }

        .data-grid td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-2);
            font-size: 13px;
            color: var(--data-val);
            vertical-align: middle;
        }

        .data-grid tr:last-child td {
            border-bottom: none;
        }

        .data-grid .val-col {
            font-family: var(--mono);
            font-weight: 600;
        }

        /* Summary Strip */
        .summary-strip {
            display: flex;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--surface);
            margin-bottom: 2rem;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .summary-item {
            flex: 1;
            padding: 1.25rem 1.5rem;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .summary-item:last-child {
            border-right: none;
        }

        .summary-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--data-label);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--data-val);
            line-height: 1;
            margin-top: 4px;
        }

        .summary-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* Print Layouts */
        @media print {
            body {
                background: white;
            }

            .print\:hidden {
                display: none !important;
            }

            .print\:block {
                display: block !important;
            }

            .main {
                margin-left: 0 !important;
            }

            .content {
                padding: 0 !important;
            }

            .formal-section-header {
                background: #1e293b !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .summary-strip,
            .data-grid {
                border: 1px solid #cbd5e1 !important;
            }

            .data-grid th {
                background: #f8fafc !important;
            }
        }
    </style>
</head>

<body>

    <div class="print:hidden">
        @include('admin.partials.sidebar')
    </div>

    <div class="main print:m-0">
        <!-- TOPBAR (Hidden on Print) -->
        <div class="topbar print:hidden">
            <div class="topbar-left">
                <div class="topbar-title">
                    Statistical Analysis
                    <a href="{{ route('support.manual') }}#reports" class="help-link-trigger" title="How to use reports"
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
            <div class="topbar-right" style="display:flex; gap:10px; align-items:center;">
                <span
                    class="role-pill">{{ auth()->user()->role === 'cemetery_manager' ? 'Cemetery Manager' : 'Admin' }}</span>
                <!-- Style matches New Permit button -->
                <a href="{{ route('reports.export.excel') }}" class="btn btn-primary"
                    style="font-weight: 600; padding: 0.6rem 1.2rem; font-size: 14px; background: #10b981; border-color: #059669;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        style="margin-right: 6px;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="12" y1="18" x2="12" y2="12" />
                        <line x1="9" y1="15" x2="15" y2="15" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('reports.export') }}" class="btn btn-primary"
                    style="font-weight: 600; padding: 0.6rem 1.2rem; font-size: 14px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        style="margin-right: 6px;">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>

        <!-- MAIN REPORT CONTENT -->
        <div class="content" style="padding: 2.5rem;">

            <!-- FORMAL MUNICIPAL HEADER (Visible on Print) -->
            <div
                class="hidden print:flex flex-col items-center justify-center mb-8 border-b-2 border-slate-800 pb-6 text-center">
                <h1 class="text-2xl font-bold uppercase tracking-wide text-slate-900 m-0">Republic of the Philippines
                </h1>
                <h2 class="text-lg font-semibold text-slate-800 m-0">Province of Davao del Norte</h2>
                <h3 class="text-lg font-bold text-slate-900 m-0">Municipality of Carmen</h3>
                <p class="text-sm font-medium mt-1 text-slate-600 uppercase tracking-widest">Office of the Municipal
                    Civil Registrar</p>
                <h4 class="mt-4 text-xl font-bold">Statistical Analysis & Operations Report</h4>
                <p class="text-xs mt-1">Generated: {{ now()->format('F d, Y') }}</p>
            </div>

            <!-- ON-SCREEN HEADER (Hidden on Print) -->
            <div class="mb-8 border-b-2 border-slate-200 dark:border-slate-800 pb-4 print:hidden">
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white m-0 tracking-tight">System Operations
                    Report</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-widest mt-1">LGU
                    Carmen — Statistical Analysis</p>
            </div>

            <!-- SUMMARY STRIP -->
            <div class="summary-strip">
                <div class="summary-item">
                    <span class="summary-label">Total Permits Issued ({{ now()->year }})</span>
                    <span class="summary-value">{{ number_format($totalYearlyPermits) }}</span>
                    <span class="summary-sub">Across all burial operations</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Expected Annual Revenue</span>
                    <span
                        class="summary-value text-emerald-600 dark:text-emerald-500">₱{{ number_format($estimatedRevenue) }}</span>
                    <span class="summary-sub">Active & pending bookings</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Total Registered Deceased</span>
                    <span class="summary-value">{{ number_format($totalDeceased) }}</span>
                    <span class="summary-sub">Historical system records</span>
                </div>
            </div>

            <!-- PERMIT PERFORMANCE DATA GRID -->
            <div class="report-section">
                <div class="formal-section-header">Permit Performance & Status Distribution</div>
                <table class="data-grid w-full">
                    <thead>
                        <tr>
                            <th style="width: 25%">Status Category</th>
                            <th style="width: 15%">Count</th>
                            <th style="width: 25%">Condition</th>
                            <th style="width: 35%">6-Month Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <span class="font-bold">Valid & Active</span>
                                </div>
                            </td>
                            <td class="val-col">{{ number_format($activePermits) }}</td>
                            <td class="text-slate-500 dark:text-slate-400 text-xs uppercase font-semibold">Fully
                                Compliant</td>
                            <td>
                                <div style="height: 30px; width: 100%;"><canvas id="sparklineActive"></canvas></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                    <span class="font-bold text-amber-700 dark:text-amber-500">Expiring Soon</span>
                                </div>
                            </td>
                            <td class="val-col">{{ number_format($expiringSoon) }}</td>
                            <td class="text-slate-500 dark:text-slate-400 text-xs uppercase font-semibold">
                                < 30 Days Remaining</td>
                            <td>
                                <div style="height: 30px; width: 100%;"><canvas id="sparklineExpiring"></canvas></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                                    <span class="font-bold text-rose-700 dark:text-rose-500">Expired / Overdue</span>
                                </div>
                            </td>
                            <td class="val-col">{{ number_format($expiredPermits) }}</td>
                            <td class="text-slate-500 dark:text-slate-400 text-xs uppercase font-semibold">Requires
                                Action</td>
                            <td>
                                <div style="height: 30px; width: 100%;"><canvas id="sparklineExpired"></canvas></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"
                                class="bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
                                <div class="flex items-center justify-between text-xs py-1">
                                    <span class="font-semibold text-slate-600 dark:text-slate-400 uppercase">General
                                        System Volume Trend ({{ now()->year }})</span>
                                    <span class="font-mono text-slate-500">Peak: {{ $busiestMonth }}
                                        ({{ $busiestCount }})</span>
                                </div>
                                <div style="height: 100px; width: 100%; margin-top: 10px;">
                                    <canvas id="reportsMonthlyChart"></canvas>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- REVENUE BREAKDOWN GRID -->
            <div class="report-section mt-8">
                <div class="formal-section-header">Financial Summary Breakdown</div>
                <table class="data-grid">
                    <thead>
                        <tr>
                            <th style="width: 40%">Burial Category</th>
                            <th style="text-align: center; width: 20%">Permit Count</th>
                            <th style="text-align: right; width: 20%">Unit Tariff</th>
                            <th style="text-align: right; width: 20%">Projected Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revenueBreakdown as $row)
                            <tr>
                                <td style="font-weight: 600;">{{ $row['label'] }}</td>
                                <td style="text-align: center;" class="val-col">{{ number_format($row['count']) }}</td>
                                <td style="text-align: right; color: var(--muted); font-size: 12px;" class="val-col">
                                    ₱{{ number_format($row['unitPrice']) }}</td>
                                <td style="text-align: right; color: var(--green);" class="val-col text-sm">
                                    ₱{{ number_format($row['total']) }}</td>
                            </tr>
                        @endforeach
                        <tr style="background: var(--surface-2);">
                            <td class="font-bold text-slate-800 dark:text-slate-200">(((TOTAL GROSS ESTIMATE)))</td>
                            <td style="text-align: center;" class="val-col font-bold">
                                {{ number_format(array_sum(array_column($revenueBreakdown, 'count'))) }}</td>
                            <td></td>
                            <td style="text-align: right;"
                                class="val-col font-bold text-base text-emerald-600 dark:text-emerald-400">
                                ₱{{ number_format($estimatedRevenue) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div><!-- /content -->
    </div><!-- /main -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const createSparkline = (ctxId, data, color) => {
            new Chart(document.getElementById(ctxId).getContext('2d'), {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        data: data,
                        backgroundColor: color,
                        borderRadius: 2,
                        barPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    scales: {
                        x: { display: false },
                        y: { display: false, beginAtZero: true }
                    },
                    animation: false
                }
            });
        };

        createSparkline('sparklineActive', @json($activeMonthlyData ?? []), '#10b981');
        createSparkline('sparklineExpiring', @json($expiringMonthlyData ?? []), '#f59e0b');
        createSparkline('sparklineExpired', @json($expiredMonthlyData ?? []), '#f43f5e');

        // Main Annual Chart
        const monthlyData = @json($monthlyData ?? []);
        const maxVal = Math.max(...monthlyData, 1);

        new Chart(document.getElementById('reportsMonthlyChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    data: monthlyData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'DM Mono', size: 11 },
                        bodyFont: { family: 'DM Mono', size: 12 },
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            title: items => months[items[0].dataIndex],
                            label: item => ` ${item.raw} permits`,
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { font: { family: 'DM Mono', size: 9 }, color: '#94a3b8', maxTicksLimit: 4 },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        border: { display: false }
                    },
                    x: {
                        ticks: { font: { family: 'DM Mono', size: 9 }, color: '#94a3b8' },
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });
    </script>
</body>

</html>