<div class="table-scroll">
    <table>
        <colgroup>
            <col style="width: 140px;">
            <col> <!-- Flexible: Deceased -->
            <col style="width: 120px;">
            <col style="width: 140px;">
            <col style="width: 100px;">
            <col style="width: 150px;">
            <col style="width: 250px;">
        </colgroup>
        <thead>
            <tr>
                <th><a href="{{ $sortUrl('permit_number') }}" class="sort-link {{ request('sort')==='permit_number'?'active':'' }}">Permit No. {!! $sortIcon('permit_number') !!}</a></th>
                <th><a href="{{ $sortUrl('last_name') }}"     class="sort-link {{ request('sort')==='last_name'?'active':'' }}">Deceased {!! $sortIcon('last_name') !!}</a></th>
                <th><a href="{{ $sortUrl('permit_type') }}"   class="sort-link {{ request('sort')==='permit_type'?'active':'' }}">Type {!! $sortIcon('permit_type') !!}</a></th>
                <th><a href="{{ $sortUrl('date_of_death') }}" class="sort-link {{ request('sort')==='date_of_death'?'active':'' }}">Date of Death {!! $sortIcon('date_of_death') !!}</a></th>
                <th style="text-align:center">
                    <a href="{{ $sortUrl('renewal_count') }}" class="sort-link {{ request('sort')==='renewal_count'?'active':'' }}" style="justify-content:center">
                        Renewals {!! $sortIcon('renewal_count') !!}
                    </a>
                </th>
                <th><a href="{{ $sortUrl('status') }}" class="sort-link {{ request('sort', 'status')==='status'?'active':'' }}">Status {!! $sortIcon('status') !!}</a></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($permits as $permit)
            @php
                $cs = $permit->status;
            @endphp
            <tr class="permit-row {{ $cs === 'expired' ? 'row-expired' : ($cs === 'expiring' ? 'row-expiring' : '') }}"
                onclick="window.location='{{ route('permits.show', $permit) }}'"
                style="cursor:pointer;">
                <td>
                    <span class="permit-no">{{ $permit->permit_number }}</span>
                    @if($cs === 'expired')
                        <span style="font-size:10px;font-weight:700;color:#ef4444;margin-left:4px;vertical-align:middle">⚠</span>
                    @elseif($cs === 'expiring')
                        <span style="font-size:10px;font-weight:700;color:#f59e0b;margin-left:4px;vertical-align:middle">⏰</span>
                    @endif
                </td>
                <td>
                    {{ optional($permit->deceased)->full_name ?? 'Unknown Deceased' }}
                </td>
                <td style="font-size:12px;color:#6b7280;text-transform:capitalize">{{ ucfirst(str_replace('_',' ',$permit->permit_type)) }}</td>
                <td style="font-size:12px;color:#6b7280">{{ optional(optional($permit->deceased)->date_of_death)->format('M d, Y') ?? '—' }}</td>
                <td style="text-align:center">
                    @if(($permit->renewal_count ?? 0) > 0)
                        <span style="font-size:12px;font-weight:700;color:#f59e0b;background:#fef3c7;padding:2px 8px;border-radius:4px">{{ $permit->renewal_count }}×</span>
                    @else
                        <span style="font-size:12px;color:#d1d5db">—</span>
                    @endif
                </td>
                <td>
                    @if($cs === 'expired')
                        <span class="badge badge-red" style="font-weight:700">⚠ Expired</span>
                    @elseif($cs === 'expiring')
                        <span class="badge badge-yellow" style="font-weight:700">⏰ Expiring Soon</span>
                    @else
                        <span class="badge badge-green">✓ Active</span>
                    @endif
                </td>
                <td>
                    <div class="actions-cell" onclick="event.stopPropagation()">
                        <a href="{{ route('permits.show', $permit) }}" class="btn-action">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            View
                        </a>
                        <a href="{{ route('permits.print', $permit) }}"
                           class="btn-action btn-print"
                           onclick="handlePrint(event, this, '{{ $permit->permit_number }}')"
                           title="Download filled permit as .docx">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            Print
                        </a>
                        @if($cs === 'expired' || $cs === 'expiring')
                        <form id="renew-form-{{ $permit->id }}" method="POST" action="{{ route('permits.renew', $permit) }}" style="display:inline">
                            @csrf
                            <button type="button" class="btn-action btn-renew" onclick="openRenewModal('{{ $permit->permit_number }}', '{{ str_replace('\'', '\\\'', optional($permit->deceased)->full_name ?? 'Unknown Deceased') }}', 'renew-form-{{ $permit->id }}')">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 109-9"/><polyline points="3 3 3 9 9 9"/></svg>
                                Renew
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;color:#9ca3af;padding:2.5rem">No permits yet.</td>
            </tr>
            @endforelse

            {{-- Pad with invisible rows to keep pagination locked in place --}}
            @if($permits->count() > 0 && $permits->count() < 10)
                @for($i = $permits->count(); $i < 10; $i++)
                    <tr style="visibility: hidden;">
                        <td colspan="7">&nbsp;</td>
                    </tr>
                @endfor
            @elseif($permits->count() === 0)
                @for($i = 1; $i < 10; $i++)
                    <tr style="visibility: hidden;">
                        <td colspan="7">&nbsp;</td>
                    </tr>
                @endfor
            @endif
        </tbody>
    </table>
</div>

@if($permits->hasPages())
    <div class="pager">
        <div class="pager-btns">
            @if ($permits->onFirstPage())
                <span class="pager-btn disabled">&laquo; Prev</span>
            @else
                <a href="{{ $permits->previousPageUrl() }}" class="pager-btn">&laquo; Prev</a>
            @endif

            @foreach ($permits->getUrlRange(max(1, $permits->currentPage() - 2), min($permits->lastPage(), $permits->currentPage() + 2)) as $page => $url)
                @if ($page == $permits->currentPage())
                    <span class="pager-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pager-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if ($permits->hasMorePages())
                <a href="{{ $permits->nextPageUrl() }}" class="pager-btn">Next &raquo;</a>
            @else
                <span class="pager-btn disabled">Next &raquo;</span>
            @endif
        </div>
    </div>
@endif
