<!DOCTYPE html>
<html lang="en">
<head>
    @livewireStyles
    <meta charset="UTF-8">
    <script>/* dark-mode anti-flash */
    (function(){try{if(localStorage.getItem('lgu_dark')==='1')document.documentElement.classList.add('dark');}catch(e){}})();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    @include('admin.partials.design-system')
    <style>
        /* Specific Dashboard Tweak: Hover effect for name cells */
        tbody tr:hover td { background: #eff6ff !important; }
        tbody tr:hover td:first-child { box-shadow: inset 4px 0 0 #2563eb !important; }
        html.dark tbody tr:hover td { background: #1e293b !important; }
        html.dark tbody tr:hover td:first-child { box-shadow: inset 4px 0 0 #6366f1 !important; }

        .deceased-name { font-weight: 500; color: var(--text-1); }

        /* ── Floating Action Button (FAB) ── */
        .fab { position: fixed; bottom: 2rem; right: 30px; width: 56px; height: 56px; background: #1a2744; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.25); cursor: pointer; z-index: 1000; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-decoration: none; border: none; outline: none; }
        .fab:hover { transform: scale(1.1) rotate(90deg); background: #2563eb; box-shadow: 0 6px 16px rgba(0,0,0,0.3); }
        .fab svg { width: 24px; height: 24px; }
        html.dark .fab { background: #3b82f6; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
        html.dark .fab:hover { background: #60a5fa; }
    </style>
</head>
<body>

@include('admin.partials.sidebar')

<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title">
                Dashboard
                <a href="{{ route('support.manual') }}#overview" class="help-link-trigger" title="How to use this dashboard" style="display:inline-flex; vertical-align:middle; margin-left:8px; color:var(--accent); opacity:0.6; transition:opacity .15s;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </a>
            </div>
            <div class="topbar-date">{{ now()->format('l, F d, Y') }}</div>
        </div>
        <div class="topbar-right">
            <span class="role-pill">{{ auth()->user()->role === 'cemetery_manager' ? 'Cemetery Manager' : 'Admin' }}</span>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('permits.index') }}#new" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 13px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 4px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Permit
            </a>
            @endif
        </div>
    </div>

    <div class="content">

        <!-- HERO BANNER -->
        <div class="hero fade-up">
            <div class="hero-text">
                <h2>{{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }}, {{ auth()->user()->name }}</h2>
                <p>MCR · MUNICIPALITY OF CARMEN · DAVAO DEL NORTE · {{ strtoupper(now()->format('F Y')) }}</p>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-val">{{ $stats['this_month'] }}</div>
                    <div class="hero-stat-label">This Month</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-val">{{ $stats['total'] }}</div>
                    <div class="hero-stat-label">All Time</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-val" style="color:#fbbf24">{{ $stats['expired'] + $stats['expiring'] }}</div>
                    <div class="hero-stat-label">Need Action</div>
                </div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="stat-grid">
            <a href="{{ route('permits.index') }}" class="stat-card fade-up d1">
                <div class="stat-top">
                    <div class="stat-icon blue">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span class="stat-pill neu">ALL TIME</span>
                </div>
                <div class="stat-value blue">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Permits</div>
                <div class="stat-sub">{{ $stats['active'] }} active</div>
            </a>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('reports.index') }}" class="stat-card fade-up d2">
                <div class="stat-top">
                    <div class="stat-icon green">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <span class="stat-pill neu">{{ strtoupper(now()->format('M Y')) }}</span>
                </div>
                <div class="stat-value green">{{ $stats['this_month'] }}</div>
                <div class="stat-label">This Month</div>
                <div class="stat-sub">Issued {{ now()->format('F') }}</div>
            </a>
            @else
            <div class="stat-card fade-up d2">
                <div class="stat-top">
                    <div class="stat-icon green">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <span class="stat-pill neu">{{ strtoupper(now()->format('M Y')) }}</span>
                </div>
                <div class="stat-value green">{{ $stats['this_month'] }}</div>
                <div class="stat-label">This Month</div>
                <div class="stat-sub">Issued {{ now()->format('F') }}</div>
            </div>
            @endif

            <a href="{{ route('permits.index', ['status' => 'expired']) }}" class="stat-card fade-up d3">
                <div class="stat-top">
                    <div class="stat-icon red">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <span class="stat-pill {{ $stats['expired'] > 0 ? 'bad' : 'ok' }}">{{ $stats['expired'] > 0 ? 'URGENT' : 'NONE' }}</span>
                </div>
                <div class="stat-value red">{{ $stats['expired'] }}</div>
                <div class="stat-label">Expired</div>
                <div class="stat-sub">Renewal required</div>
            </a>

            <a href="{{ route('permits.index', ['status' => 'expiring']) }}" class="stat-card fade-up d4">
                <div class="stat-top">
                    <div class="stat-icon amber">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <span class="stat-pill {{ $stats['expiring'] > 0 ? 'bad' : 'ok' }}">{{ $stats['expiring'] > 0 ? 'SOON' : 'CLEAR' }}</span>
                </div>
                <div class="stat-value amber">{{ $stats['expiring'] }}</div>
                <div class="stat-label">Expiring Soon</div>
                <div class="stat-sub">Within 30 days</div>
            </a>
        </div>

        <!-- THREE-COLUMN PANELS -->
        <div class="three-col">

            <!-- Monthly Chart -->
            <div class="panel fade-up d5">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Monthly Permits</div>
                        <div class="panel-sub">{{ now()->year }} — permits issued per month</div>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyChart" height="150"></canvas>
                </div>
            </div>

            <!-- Needs Attention -->
            @php
                $needsAction = \App\Models\BurialPermit::with('deceased')
                    ->where('status', 'expired')
                    ->latest()->limit(4)->get();
            @endphp
            <div class="panel fade-up d6">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Needs Attention</div>
                        <div class="panel-sub">Expired permits requiring renewal</div>
                    </div>
                    <a href="{{ route('permits.index', ['sort' => 'status', 'direction' => 'asc']) }}" class="link-arrow">All →</a>
                </div>
                <div class="alert-list">
                    @forelse($needsAction as $item)
                    <a href="{{ route('permits.show', $item) }}" class="alert-row">
                        <div class="alert-indicator red"></div>
                        <div class="alert-info">
                            <div class="alert-name">{{ optional($item->deceased)->last_name }}, {{ optional($item->deceased)->first_name }}</div>
                            <div class="alert-meta">{{ $item->permit_number }} · {{ $item->created_at->format('M d, Y') }}</div>
                        </div>
                        <span class="badge badge-red"><span class="badge-dot"></span>Expired</span>
                    </a>
                    @empty
                    <div style="padding:1.5rem;text-align:center;font-size:12px;color:var(--text-3)">
                        ✓ &nbsp;All clear — nothing needs attention
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Permit Type Breakdown -->
            @php
                $feeBreakdown = \App\Models\BurialPermit::selectRaw('permit_type, COUNT(*) as total')
                    ->groupBy('permit_type')->pluck('total','permit_type');
                $totalP = $feeBreakdown->sum() ?: 1;
                $feeLabels = [
                    'cemented'    => 'Cemented',
                    'niche_1st'   => '1st Floor',
                    'niche_2nd'   => '2nd Floor',
                    'niche_3rd'   => '3rd Floor',
                    'niche_4th'   => '4th Floor',
                    'bone_niches' => 'Bone Niches',
                ];
                $feeColors = ['#3b82f6','#8b5cf6','#ec4899','#f59e0b','#10b981','#ef4444'];
            @endphp
            <div class="panel fade-up d7">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Permit Type Breakdown</div>
                        <div class="panel-sub">Distribution across burial categories</div>
                    </div>
                </div>
                <div class="breakdown-body">
                    @foreach($feeLabels as $key => $label)
                    @php
                        $cnt = $feeBreakdown[$key] ?? 0;
                        $pct = round(($cnt / $totalP) * 100);
                        $col = $feeColors[$loop->index % count($feeColors)];
                    @endphp
                    <div class="prog-row">
                        <div class="prog-labels">
                            <span class="prog-name">{{ $label }}</span>
                            <span class="prog-count">{{ $cnt }} &nbsp;<span style="color:var(--border)">|</span>&nbsp; {{ $pct }}%</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill" style="width:{{ $pct }}%;background:{{ $col }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div><!-- /three-col -->

    </div><!-- /content -->

</div><!-- /main -->

<!-- TOAST -->
@if(session('success'))
<div class="toast" id="successToast">
    <div class="toast-body">
        <div class="toast-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
            <div class="toast-title">Success</div>
            <div class="toast-msg">{{ session('success') }}</div>
        </div>
    </div>
    <div class="toast-bar"></div>
</div>
@endif

<script>
// Toast
(function(){
    const t = document.getElementById('successToast');
    if (!t) return;
    requestAnimationFrame(() => setTimeout(() => t.classList.add('show'), 60));
    setTimeout(() => t.classList.remove('show'), 5300);
})();

// Monthly chart
const monthlyData = @json($stats['monthly']);
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const maxVal = Math.max(...monthlyData, 1);

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
                    label: item => ` ${item.raw} permit${item.raw !== 1 ? 's' : ''}`,
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
</script>
@livewireScripts
    <!-- FAB: New Permit -->
    @if(auth()->user()->role === 'admin')
    <a href="{{ route('permits.index') }}#new" class="fab" title="New Burial Permit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </a>
    @endif

</body>
</html>