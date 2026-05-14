<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script>
    (function(){try{if(localStorage.getItem('lgu_dark')==='1')document.documentElement.classList.add('dark');}catch(e){}})();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $deceased->first_name }} {{ $deceased->last_name }} — LGU Carmen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    @include('admin.partials.design-system')

    <style>
        .hero { background:var(--navy); border-radius:16px; padding:1.4rem 1.75rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; position:relative; overflow:hidden; }
        .hero::before { content:''; position:absolute; top:-60px; right:-40px; width:240px; height:240px; background:radial-gradient(circle,rgba(59,130,246,.1) 0%,transparent 65%); pointer-events:none; }
        .hero-eyebrow { font-size:10px; font-weight:600; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:.1em; font-family:var(--mono); }
        .hero-no { font-size:28px; font-weight:700; color:#fff; letter-spacing:-.03em; line-height:1.1; margin-top:.2rem; }
        .hero-meta { font-size:11px; color:rgba(255,255,255,.35); margin-top:.3rem; font-family:var(--mono); }
        .hero-actions { display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }

        .info-grid { display:grid; grid-template-columns:1.4fr 1fr; gap:1rem; }
        .info-card { background:var(--surface); border:1px solid var(--border); border-radius:12px; overflow:hidden; display:flex; flex-direction:column; }
        .info-card-head { padding:.75rem 1.1rem; border-bottom:1px solid var(--border-2); display:flex; align-items:center; gap:.5rem; background:var(--surface-2); }
        .info-card-title { font-size:10px; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:.08em; font-family:var(--mono); }
        .info-card-body { padding:1.1rem; display:flex; flex-direction:column; gap:.85rem; flex:1; }
        .field { display:flex; flex-direction:column; gap:3px; }
        .fl { font-size:10px; font-weight:600; color:var(--text-3); text-transform:uppercase; letter-spacing:.07em; font-family:var(--mono); }
        .fv { font-size:13px; font-weight:500; color:var(--text-1); }
        .fv-lg { font-size:18px; font-weight:700; color:var(--text-1); letter-spacing:-.02em; }
        .g2 { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
        .g3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:.75rem; }

        .permits-card { background:var(--surface); border:1px solid var(--border); border-radius:12px; overflow:hidden; }
        .permits-head { padding:.85rem 1.25rem; border-bottom:1px solid var(--border-2); display:flex; align-items:center; justify-content:space-between; background:var(--surface-2); }
        .permits-head-left { display:flex; align-items:center; gap:.5rem; }
        .permits-head-title { font-size:13px; font-weight:600; color:var(--text-1); }

        .permit-row { display:flex; align-items:center; gap:1rem; padding:.85rem 1.25rem; border-bottom:1px solid var(--border-2); transition:background .12s; }
        .permit-row:last-child { border-bottom:none; }
        .permit-row:hover { background:var(--surface-2); }
        .permit-row-num { font-family:var(--mono); font-size:13px; font-weight:700; color:var(--accent); flex-shrink:0; }
        .permit-row-info { flex:1; display:flex; flex-direction:column; gap:2px; }
        .permit-row-title { font-size:13px; font-weight:600; color:var(--text-1); }
        .permit-row-sub { font-size:11px; color:var(--text-3); font-family:var(--mono); }

        .avatar { width:56px; height:56px; border-radius:50%; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:#fff; font-size:22px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }

        html.dark .info-card { background:#1e2130 !important; border-color:#2d3148 !important; }
        html.dark .info-card-head { background:#181b29 !important; border-bottom-color:#2d3148 !important; }
        html.dark .info-card-title,.dark .fl { color:#64748b !important; }
        html.dark .fv { color:#e2e8f0 !important; }
        html.dark .fv-lg { color:#f1f5f9 !important; }
        html.dark .permits-card { background:#1e2130 !important; border-color:#2d3148 !important; }
        html.dark .permits-head { background:#181b29 !important; border-bottom-color:#2d3148 !important; }
        html.dark .permits-head-title { color:#e2e8f0 !important; }
        html.dark .permit-row:hover { background:#252840 !important; }
        html.dark .permit-row { border-bottom-color:#2d3148 !important; }
        html.dark .permit-row-title { color:#e2e8f0 !important; }
        html.dark .permit-row-sub { color:#64748b !important; }
    </style>
</head>
<body>

@include(auth()->user()->role === 'super_admin' ? 'superadmin.partials.sidebar' : 'admin.partials.sidebar')

<div class="main">
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:.6rem">
            <a href="{{ url()->previous() }}" class="topbar-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Back
            </a>
            <span class="topbar-sep">/</span>
            <span class="topbar-title">{{ $deceased->first_name }} {{ $deceased->last_name }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:.5rem">
            <span class="role-pill">{{ auth()->user()->role === 'super_admin' ? 'Super Admin' : (auth()->user()->role === 'cemetery_manager' ? 'Cemetery Manager' : 'Admin') }}</span>
        </div>
    </div>

    <div class="content">

        {{-- HERO --}}
        <div class="hero">
            <div style="display:flex;align-items:center;gap:1.1rem">
                <div class="avatar">{{ strtoupper(substr($deceased->first_name, 0, 1)) }}</div>
                <div>
                    <div class="hero-eyebrow">Deceased Record #{{ $deceased->id }}</div>
                    <div class="hero-no">{{ $deceased->first_name }} {{ $deceased->middle_name ? $deceased->middle_name.' ' : '' }}{{ $deceased->last_name }}{{ $deceased->name_extension ? ', '.$deceased->name_extension : '' }}</div>
                    <div class="hero-meta">Added {{ $deceased->created_at->format('F d, Y') }} · {{ $deceased->created_at->diffForHumans() }}</div>
                </div>
            </div>
            <div class="hero-actions">
                @if($deceased->permits->isNotEmpty())
                    <a href="{{ route('permits.show', $deceased->permits->first()) }}" class="btn btn-ghost">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="14" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                        View Permit
                    </a>
                @endif
                <form method="POST" action="{{ route('deceased.destroy', $deceased) }}" id="deleteDeceasedForm" style="display:none">
                    @csrf @method('DELETE')
                </form>
                <button type="button" class="btn btn-danger" onclick="openDeleteModal('Permanently delete this deceased record and all linked data?', '{{ $deceased->first_name }} {{ $deceased->last_name }}', 'deleteDeceasedForm')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                    Delete
                </button>
            </div>
        </div>

        {{-- INFO GRID --}}
        <div class="info-grid">

            {{-- Personal Info Card --}}
            <div class="info-card">
                <div class="info-card-head">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <span class="info-card-title">Personal Information</span>
                </div>
                <div class="info-card-body">
                    <div class="field">
                        <div class="fl">Full Name</div>
                        <div class="fv-lg">{{ $deceased->first_name }} {{ $deceased->middle_name }} {{ $deceased->last_name }}{{ $deceased->name_extension ? ', '.$deceased->name_extension : '' }}</div>
                    </div>
                    <div class="g3">
                        <div class="field"><div class="fl">Sex</div><div class="fv">{{ $deceased->sex ?: '—' }}</div></div>
                        <div class="field"><div class="fl">Age</div><div class="fv">{{ $deceased->age ?: '—' }}</div></div>
                        <div class="field"><div class="fl">Civil Status</div><div class="fv">{{ $deceased->civil_status ?: '—' }}</div></div>
                    </div>
                    <div class="g2">
                        <div class="field"><div class="fl">Nationality</div><div class="fv">{{ $deceased->nationality ?: '—' }}</div></div>
                        <div class="field"><div class="fl">Religion</div><div class="fv">{{ $deceased->religion ?: '—' }}</div></div>
                    </div>
                    <div class="field"><div class="fl">Address / Residence</div><div class="fv">{{ $deceased->address ?: '—' }}</div></div>
                    @if($deceased->phone_number)
                    <div class="field"><div class="fl">Contact Number</div><div class="fv" style="font-family:var(--mono)">{{ $deceased->phone_number }}</div></div>
                    @endif
                </div>
            </div>

            {{-- Death Info Card --}}
            <div class="info-card">
                <div class="info-card-head">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <span class="info-card-title">Death Information</span>
                </div>
                <div class="info-card-body">
                    <div class="g2">
                        <div class="field">
                            <div class="fl">Date of Death</div>
                            <div class="fv" style="font-weight:700;color:var(--accent)">
                                {{ optional($deceased->date_of_death)->format('M d, Y') ?? '—' }}
                            </div>
                        </div>
                        <div class="field">
                            <div class="fl">Date of Birth</div>
                            <div class="fv">{{ optional($deceased->date_of_birth)->format('M d, Y') ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="field"><div class="fl">Place of Death</div><div class="fv">{{ $deceased->place_of_death ?: '—' }}</div></div>
                    <div class="field"><div class="fl">Cause of Death</div><div class="fv">{{ $deceased->cause_of_death ?: '—' }}</div></div>
                    <div class="field"><div class="fl">Kind of Burial</div><div class="fv">{{ $deceased->kind_of_burial ?: '—' }}</div></div>
                    <div style="margin-top:auto;padding-top:.75rem;border-top:1px solid var(--border-2)">
                        <div class="fl" style="margin-bottom:.4rem">Total Permits</div>
                        <div style="font-size:28px;font-weight:700;color:#fff;font-family:var(--mono)">{{ $deceased->permits->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PERMITS TABLE --}}
        <div class="permits-card">
            <div class="permits-head">
                <div class="permits-head-left">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="14" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                    <span class="permits-head-title">Burial Permits</span>
                    <span style="font-size:11px;color:var(--text-3);font-family:var(--mono)">— {{ $deceased->permits->count() }} record{{ $deceased->permits->count() !== 1 ? 's' : '' }}</span>
                </div>
            </div>

            @forelse($deceased->permits as $permit)
            @php $cs = $permit->status; @endphp
            <div class="permit-row">
                <div class="permit-row-num">{{ $permit->permit_number }}</div>
                <div class="permit-row-info">
                    <div class="permit-row-title">{{ ucfirst(str_replace(['_','-'],' ', $permit->permit_type ?? 'Standard')) }}</div>
                    <div class="permit-row-sub">
                        Issued {{ $permit->created_at->format('M d, Y') }}
                        @if($permit->expiry_date) · Expires {{ $permit->expiry_date->format('M d, Y') }} @endif
                    </div>
                </div>
                <div>
                    @if($cs === 'expired')
                        <span class="badge badge-red"><span class="badge-dot"></span>Expired</span>
                    @elseif($cs === 'expiring')
                        <span class="badge badge-orange"><span class="badge-dot"></span>Expiring</span>
                    @else
                        <span class="badge badge-green"><span class="badge-dot"></span>Active</span>
                    @endif
                </div>
                <a href="{{ route('permits.show', $permit) }}" class="btn btn-ghost" style="font-size:11px;padding:.3rem .75rem">
                    View
                </a>
            </div>
            @empty
            <div style="padding:2.5rem;text-align:center;color:var(--text-3);font-size:13px">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto .75rem;display:block;opacity:.3"><rect x="3" y="3" width="18" height="14" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                No burial permits on record.
            </div>
            @endforelse
        </div>

    </div>
</div>

@if(session('success'))
<div class="toast show" id="sToast">
    <div class="toast-body">
        <div class="toast-icon green"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div><div class="toast-title">Success</div><div class="toast-msg">{{ session('success') }}</div></div>
    </div>
    <div class="toast-bar green"></div>
</div>
@endif

@include('admin.partials.delete-modal')

<script>
(function(){
    const s = document.getElementById('sToast');
    if (s) setTimeout(() => s.classList.remove('show'), 5000);
})();
</script>

</body>
</html>
