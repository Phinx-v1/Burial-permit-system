<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    @include('admin.partials.design-system')
    <link rel="stylesheet" href="{{ asset('css/sa-sidebar.css') }}">
    <style>
        :root {
            --audit-bg: #fff;
            --audit-surface: #f8fafc;
            --audit-text: #0f172a;
            --audit-muted: #64748b;
            --audit-accent: #3b82f6;
            --audit-green: #10b981;
            --audit-amber: #f59e0b;
            --audit-red: #ef4444;
        }

        .main { background: #fff !important; }
        .content { padding: 4rem 5rem !important; background: #fff !important; }

        .topbar { 
            background: #fff; 
            border-bottom: 1px solid #f1f5f9; 
            height: 72px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 0 2rem; 
            width: 100%;
        }
        .topbar-title { font-size: 18px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; }
        .topbar-date { font-size: 12px; color: #94a3b8; font-weight: 500; margin-top: 2px; }
        
        .role-pill-sa { 
            background: #eff6ff; 
            color: #3b82f6; 
            border: 1px solid #bfdbfe; 
            font-family: 'DM Mono', monospace; 
            font-size: 10px; 
            font-weight: 700; 
            padding: 4px 12px; 
            border-radius: 20px; 
            letter-spacing: .08em; 
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .sa-avatar-circle {
            width: 34px;
            height: 34px;
            background: #0f172a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            border: 2px solid #fff;
            box-shadow: 0 0 0 1px #e2e8f0;
        }

        .export-link {
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.15s;
        }
        .export-link.excel { color: #10b981; }
        .export-link.pdf { color: #3b82f6; }
        .export-link:hover { opacity: 0.8; }

        .audit-report-header { margin-bottom: 3.5rem; border-bottom: 2px solid #3b82f6; padding-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: flex-end; }
        .audit-report-title h1 { font-size: 38px; font-weight: 800; color: #0f172a; letter-spacing: -0.03em; margin: 0; }
        .audit-report-title p { font-size: 13px; font-weight: 700; color: #64748b; letter-spacing: 0.05em; margin-top: 8px; font-family: 'DM Mono', monospace; }
        
        .audit-generated { text-align: right; }
        .audit-generated span { display: block; font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; }
        .audit-generated strong { display: block; font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 2px; }

        .kpi-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 3.5rem; }
        .kpi-card-new { 
            background: #fff; 
            border: 1px solid #f1f5f9; 
            border-radius: 24px; 
            padding: 2rem; 
            box-shadow: 0 4px 20px -2px rgba(0,0,0,0.03); 
            display: flex;
            flex-direction: column;
        }
        .kpi-card-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; }
        .kpi-card-value { font-size: 52px; font-weight: 800; color: #0f172a; letter-spacing: -0.04em; margin-bottom: 16px; }
        .kpi-card-pill { 
            font-size: 10px; 
            font-weight: 700; 
            padding: 4px 12px; 
            border-radius: 20px; 
            width: fit-content; 
            text-transform: uppercase; 
            letter-spacing: 0.02em; 
        }

        .analytics-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 2rem; }
        .chart-container-sa { background: #fff; padding: 1.5rem; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px -2px rgba(0,0,0,0.03); }
        .chart-header { font-size: 14px; font-weight: 800; color: #0f172a; margin-bottom: 2rem; display: flex; align-items: center; gap: 8px; }
        
        .compliance-list { display: flex; flex-direction: column; gap: 12px; }
        .compliance-item { 
            background: #fff; 
            padding: 1.5rem; 
            border-radius: 16px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
            border-left-width: 4px;
        }
        .compliance-name { font-size: 14px; font-weight: 700; color: #64748b; }
        .compliance-count { font-size: 28px; font-weight: 800; color: #0f172a; font-family: 'DM Mono', monospace; }


        @media (max-width: 1024px) {
            .kpi-hub, .report-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

@include(auth()->user()->role === 'super_admin' ? 'superadmin.partials.sidebar' : 'admin.partials.sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">
                SuperAdmin Intelligence Audit
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" style="margin-left:4px; opacity:0.8;"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="topbar-date">{{ now()->format('M d, Y · g:i A') }}</div>
        </div>
        <div class="topbar-right" style="display:flex; gap:1.5rem; align-items:center;">
            <span class="role-pill-sa">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M13 10V3L4 14H11V21L20 10H13Z"/></svg>
                SUPER ADMIN
            </span>
            <div class="sa-avatar-circle">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <a href="{{ route('superadmin.reports.excel') }}" class="export-link excel">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                Export Excel
            </a>
            <a href="{{ route('superadmin.reports.pdf') }}" class="export-link pdf">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export PDF
            </a>
        </div>
    </div>

    <div class="content">
        <!-- AUDIT HEADER -->
        <div class="audit-report-header">
            <div class="audit-report-title">
                <h1>{{ $year }} Operational Audit Report</h1>
                <p>INTERNAL REVENUE & COMPLIANCE MONITORING · CARMEN LGU</p>
            </div>
            <div class="audit-generated">
                <span>Generated By</span>
                <strong>Super Admin</strong>
            </div>
        </div>

        <!-- KPI ROW -->
        <div class="kpi-row">
            <div class="kpi-card-new">
                <span class="kpi-card-label">Yearly Permits ({{ $year }})</span>
                <span class="kpi-card-value">{{ $totalYearlyPermits }}</span>
                <span class="kpi-card-pill" style="background: #eff6ff; color: #3b82f6;">System Wide Performance</span>
            </div>
            <div class="kpi-card-new">
                <span class="kpi-card-label">Projected Revenue Pool</span>
                <span class="kpi-card-value">₱{{ number_format($estimatedRevenue) }}</span>
                <span class="kpi-card-pill" style="background: #ecfdf5; color: #10b981;">Financial Sustainability</span>
            </div>
            <div class="kpi-card-new">
                <span class="kpi-card-label">Renewals Finalized</span>
                <span class="kpi-card-value">{{ $renewedPermits }}</span>
                <span class="kpi-card-pill" style="background: #fdf4ff; color: #a855f7;">Governance Compliance</span>
            </div>
        </div>

        <!-- ANALYTICS GRID -->
        <div class="analytics-grid">
            <div class="chart-container-sa">
                <div class="chart-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Registration Trends (Monthly)
                </div>
                <div style="height: 300px;">
                    <canvas id="monthlyTrendChartAudit"></canvas>
                </div>
            </div>

            <div class="chart-container-sa">
                <div class="chart-header">Service Compliance Health</div>
                <div class="compliance-list">
                    <div class="compliance-item" style="border-left-color: #10b981;">
                        <span class="compliance-name">Active & Compliant</span>
                        <span class="compliance-count">{{ $activePermits }}</span>
                    </div>
                    <div class="compliance-item" style="border-left-color: #f59e0b;">
                        <span class="compliance-name">Upcoming Expirations</span>
                        <span class="compliance-count">{{ $expiringSoon }}</span>
                    </div>
                    <div class="compliance-item" style="border-left-color: #ef4444;">
                        <span class="compliance-name">Expired / Overdue</span>
                        <span class="compliance-count">{{ $expiredPermits }}</span>
                    </div>
                </div>
            </div>
        </div>




    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Monthly chart
const monthlyData = @json($monthlyData ?? []);
const months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];

new Chart(document.getElementById('monthlyTrendChartAudit').getContext('2d'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            data: monthlyData,
            borderColor: '#3b82f6',
            backgroundColor: (context) => {
                const chart = context.chart;
                const {ctx, chartArea} = chart;
                if (!chartArea) return null;
                const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
                gradient.addColorStop(1, 'rgba(59, 130, 246, 0.01)');
                return gradient;
            },
            borderWidth: 5,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#3b82f6',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                titleFont: { family: 'DM Mono', size: 12, weight: '700' },
                bodyFont:  { family: 'DM Mono', size: 13 },
                padding: 15,
                displayColors: false,
                callbacks: {
                    label: item => ` ${item.raw} Permits Issuance`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { font: { family: 'DM Mono', size: 10 }, color: '#94a3b8', maxTicksLimit: 6 },
                grid: { color: 'rgba(0,0,0,0.03)' },
                border: { display: false }
            },
            x: {
                ticks: { font: { family: 'DM Mono', size: 10, weight: '700' }, color: '#64748b' },
                grid: { display: false },
                border: { display: false }
            }
        }
    }
});
</script>
</body>
</html>
