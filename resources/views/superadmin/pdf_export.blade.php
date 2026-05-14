<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
@php
    $settingsPath = storage_path('app/settings.json');
    $cfg = file_exists($settingsPath) ? (json_decode(file_get_contents($settingsPath), true) ?? []) : [];
    $mun       = $cfg['municipality_name']  ?? 'Carmen';
    $prov      = $cfg['province']           ?? 'Davao del Norte';
    $registrar = $cfg['registrar_name']     ?? 'Municipal Civil Registrar';
    $mayor     = $cfg['mayor_name']         ?? '';
    $address   = $cfg['office_address']     ?? 'Municipal Hall, Carmen, Davao del Norte';
    $prepBy    = auth()->user()?->name      ?? 'Administrator';
@endphp
<style>
    /* ── PAGE SETUP ───────────────────────────────────────────────── */
    @page {
        margin: 0.7in 0.75in 1in 0.75in;
        size: A4 portrait;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: Helvetica, Arial, 'DejaVu Sans', sans-serif;
        font-size: 10pt;
        color: #1e293b;
        background: #fff;
        line-height: 1.55;
    }

    /* ── LETTERHEAD ─────────────────────────────────────────────── */
    .lh-wrapper {
        width: 100%;
        border-bottom: 3px double #1e293b;
        padding-bottom: 12px;
        margin-bottom: 18px;
        position: relative;
    }
    .lh-seal {
        position: absolute;
        left: 0;
        top: 0;
        width: 68px;
        height: 68px;
    }
    .lh-center {
        text-align: center;
        padding: 0 80px;
    }
    .lh-republic {
        font-size: 9pt;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        color: #475569;
    }
    .lh-province {
        font-size: 9.5pt;
        font-style: italic;
        color: #64748b;
    }
    .lh-municipality {
        font-size: 17pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #0c1a4a;
        margin: 3px 0;
    }
    .lh-office {
        font-size: 10pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #1e293b;
        border-top: 1px solid #94a3b8;
        padding-top: 4px;
        margin-top: 5px;
        display: inline-block;
    }
    .lh-address {
        font-size: 8.5pt;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* ── DOCUMENT TITLE BLOCK ─────────────────────────────────── */
    .doc-title-block {
        text-align: center;
        margin-bottom: 20px;
    }
    .doc-title-block h1 {
        font-size: 13pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #0f172a;
    }
    .doc-title-block .year-badge {
        display: inline-block;
        border: 1.5px solid #1e293b;
        background: #f8fafc;
        padding: 2px 18px;
        font-size: 10pt;
        font-weight: bold;
        margin-top: 6px;
    }
    .doc-meta {
        font-size: 8.5pt;
        color: #94a3b8;
        text-align: right;
        margin-bottom: 16px;
    }

    /* ── SECTION HEADERS ──────────────────────────────────────── */
    .section-header {
        background: #1e293b;
        color: #ffffff;
        font-size: 9.5pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 5px 10px;
        margin: 18px 0 0;
        border-left: 5px solid #3b82f6;
        page-break-after: avoid;
    }

    /* ── OVERVIEW TABLE ───────────────────────────────────────── */
    .overview-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0 0 4px;
        page-break-inside: avoid;
    }
    .overview-table td {
        padding: 4px 8px;
        font-size: 10pt;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
    }
    .overview-table td.label { width: 260px; font-weight: bold; color: #334155; }
    .overview-table td.colon { width: 16px; text-align: center; color: #94a3b8; }
    .overview-table td.val   { font-family: 'Courier New', monospace; font-weight: bold; }
    .overview-table tr:nth-child(odd) td { background: #f8fafc; }

    /* ── DATA TABLES ──────────────────────────────────────────── */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0 0 4px;
        font-size: 9.5pt;
        page-break-inside: avoid;
    }
    .data-table th, .data-table td {
        border: 1px solid #cbd5e1;
        padding: 5px 8px;
        vertical-align: middle;
    }
    .data-table thead tr {
        background: #1e293b;
        color: #fff;
    }
    .data-table thead th {
        font-size: 8.5pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .data-table tbody tr:nth-child(even) td { background: #f8fafc; }
    .data-table .center { text-align: center; }
    .data-table .right  { text-align: right; font-family: 'Courier New', monospace; }
    .data-table .bold   { font-weight: bold; }
    .data-table tfoot td {
        background: #eff6ff;
        font-weight: bold;
        border-top: 2px solid #93c5fd;
        font-size: 10pt;
        color: #1d4ed8;
    }

    /* ── MONTHLY VOLUME TABLE ─────────────────────────────────── */
    .month-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9pt;
        page-break-inside: avoid;
    }
    .month-table th {
        background: #334155;
        color: #fff;
        border: 1px solid #475569;
        padding: 5px 4px;
        text-align: center;
        font-size: 8pt;
        font-weight: bold;
    }
    .month-table td {
        border: 1px solid #cbd5e1;
        padding: 6px 4px;
        text-align: center;
        font-weight: bold;
        font-family: 'Courier New', monospace;
        font-size: 11pt;
    }

    /* ── CERTIFICATION TEXT ───────────────────────────────────── */
    .certification-text {
        text-align: justify;
        font-size: 9.5pt;
        margin-top: 22px;
        line-height: 1.6;
        color: #334155;
        page-break-inside: avoid;
        border-left: 3px solid #e2e8f0;
        padding-left: 12px;
        font-style: italic;
    }

    /* ── SIGNATURE BLOCK ──────────────────────────────────────── */
    .signature-area {
        margin-top: 40px;
        width: 100%;
        page-break-inside: avoid;
    }
    .sig-row {
        width: 100%;
    }
    .sig-box {
        display: inline-block;
        width: 44%;
        vertical-align: top;
    }
    .sig-spacer {
        display: inline-block;
        width: 12%;
    }
    .sig-line {
        border-bottom: 1px solid #1e293b;
        margin-bottom: 5px;
        height: 55px;
    }
    .sig-name {
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 10pt;
        letter-spacing: 0.3px;
        color: #0f172a;
    }
    .sig-title {
        text-align: center;
        font-size: 8.5pt;
        color: #64748b;
        margin-top: 2px;
    }

    /* ── FIXED FOOTER ─────────────────────────────────────────── */
    .footer {
        position: fixed;
        bottom: -0.5in;
        left: 0;
        width: 100%;
        font-size: 7.5pt;
        text-align: center;
        border-top: 1px solid #cbd5e1;
        padding-top: 4px;
        color: #94a3b8;
        letter-spacing: 0.5px;
    }
</style>
</head>
<body>

{{-- ── FIXED FOOTER (renders on every page) ── --}}
<div class="footer">
    Municipality of {{ $mun }}, {{ $prov }} &nbsp;·&nbsp; Office of the Municipal Civil Registrar &nbsp;·&nbsp; Burial Permit Management System &nbsp;·&nbsp; <strong>CONFIDENTIAL GOVERNMENT RECORD</strong>
</div>

{{-- ── LETTERHEAD ── --}}
<div class="lh-wrapper">
    <img class="lh-seal" src="{{ public_path('images/carmen-seal.png') }}" alt="LGU Seal">
    <div class="lh-center">
        <div class="lh-republic">Republic of the Philippines</div>
        <div class="lh-province">Province of {{ $prov }}</div>
        <div class="lh-municipality">Municipality of {{ $mun }}</div>
        <div class="lh-office">Office of the Municipal Civil Registrar</div>
        <div class="lh-address">{{ $address }}</div>
    </div>
</div>

{{-- ── DOCUMENT TITLE ── --}}
<div class="doc-title-block">
    <h1>Annual Statistical Summary Report</h1>
    <div class="year-badge">Calendar Year {{ $year }}</div>
</div>

<p class="doc-meta">Report Generated: {{ now()->format('F d, Y \a\t h:i A') }}</p>

{{-- ── SECTION I: GENERAL STATISTICS ── --}}
<div class="section-header">Section I &mdash; General Statistics</div>
<table class="overview-table">
    <tr><td class="label">Total Permits Registered (All Time)</td><td class="colon">:</td><td class="val">{{ number_format($totalPermits) }}</td></tr>
    <tr><td class="label">Total New Permits This Year ({{ $year }})</td><td class="colon">:</td><td class="val">{{ number_format($totalYearlyPermits) }}</td></tr>
    <tr><td class="label">Active / Valid Permits</td><td class="colon">:</td><td class="val">{{ number_format($activePermits) }}</td></tr>
    <tr><td class="label">Expiring / Due for Renewal (Within 30 Days)</td><td class="colon">:</td><td class="val">{{ number_format($expiringSoon) }}</td></tr>
    <tr><td class="label">Expired / Overdue Permits</td><td class="colon">:</td><td class="val">{{ number_format($expiredPermits) }}</td></tr>
    <tr><td class="label">Renewal Operations Tracked</td><td class="colon">:</td><td class="val">{{ number_format($renewedPermits) }}</td></tr>
    <tr><td class="label">Total Deceased Persons on Record</td><td class="colon">:</td><td class="val">{{ number_format($totalDeceased) }}</td></tr>
</table>

{{-- ── SECTION II: MONTHLY ACTIVITY ── --}}
<div class="section-header">Section II &mdash; Monthly Permit Registrations ({{ $year }})</div>
<table class="month-table">
    <thead>
        <tr>
            <th>JAN</th><th>FEB</th><th>MAR</th><th>APR</th><th>MAY</th><th>JUN</th>
            <th>JUL</th><th>AUG</th><th>SEP</th><th>OCT</th><th>NOV</th><th>DEC</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach($monthlyData as $count)
            <td>{{ $count ?: '—' }}</td>
            @endforeach
        </tr>
    </tbody>
</table>
<p style="font-size:8.5pt; color:#64748b; margin-top:4px; text-align:right;">
    Peak month: <strong>{{ $busiestMonth }}</strong> ({{ $busiestCount }} permits)
</p>

{{-- ── SECTION III: REVENUE & BURIAL TYPE ── --}}
<div class="section-header">Section III &mdash; Revenue &amp; Burial Type Distribution</div>
@php
    $grandTotal  = 0;
@endphp
<table class="data-table">
    <thead>
        <tr>
            <th style="width:38%; text-align:left;">Burial Category</th>
            <th style="width:20%;">Unit Fee (PHP)</th>
            <th style="width:16%;">Permit Count</th>
            <th style="width:26%;">Est. Revenue (PHP)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($revenueBreakdown as $row)
        @php $grandTotal += $row['total']; @endphp
        <tr>
            <td class="bold">{{ $row['label'] }}</td>
            <td class="right">{{ number_format($row['unitPrice'], 2) }}</td>
            <td class="center">{{ number_format($row['count']) }}</td>
            <td class="right bold">{{ number_format($row['total'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="bold">GRAND TOTAL ESTIMATED REVENUE</td>
            <td class="center bold">{{ number_format(array_sum(array_column($revenueBreakdown, 'count'))) }}</td>
            <td class="right bold" style="font-size:11pt;">PHP {{ number_format($grandTotal, 2) }}</td>
        </tr>
    </tfoot>
</table>

{{-- ── CERTIFICATION ── --}}
<div class="certification-text">
    <strong>CERTIFICATION:</strong> This is to certify that the statistical data and figures presented
    in this report are accurate and reflect the records maintained by the Office of the Municipal Civil
    Registrar of {{ $mun }}, {{ $prov }} for the calendar year {{ $year }}. This document is
    system-generated and intended for official audit, administrative review, and budgetary planning.
</div>

{{-- ── SIGNATURE BLOCK ── --}}
<div class="signature-area">
    <div class="sig-box">
        <div class="sig-line"></div>
        <div class="sig-name">{{ $prepBy }}</div>
        <div class="sig-title">System Administrator / Prepared By</div>
    </div>
    <div class="sig-spacer"></div>
    <div class="sig-box">
        <div class="sig-line"></div>
        <div class="sig-name">{{ strtoupper($registrar) }}</div>
        <div class="sig-title">Municipal Civil Registrar &mdash; Certifying Officer</div>
    </div>
</div>

@if($mayor)
<div style="margin-top: 28px; text-align: right; font-size: 9pt; color: #64748b;">
    Noted by: <strong style="color:#1e293b;">{{ $mayor }}</strong>, Municipal Mayor &mdash; LGU {{ $mun }}
</div>
@endif

</body>
</html>