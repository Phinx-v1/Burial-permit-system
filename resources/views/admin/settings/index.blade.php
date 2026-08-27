<!DOCTYPE html>
<html lang="en">

<head>
    @livewireStyles

    <meta charset="UTF-8">
    <script>/* dark-mode anti-flash */
        (function () { try { if (localStorage.getItem('lgu_dark') === '1') document.documentElement.classList.add('dark'); } catch (e) { } })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Settings — LGU Carmen</title>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f0f2f5;
            color: #111827;
            display: flex;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        .main {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        .topbar-date {
            font-size: 12px;
            color: #9ca3af;
        }

        .role-tag {
            background: #1a2744;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .content {
            padding: 1.5rem;
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .settings-nav {
            width: 200px;
            flex-shrink: 0;
            position: sticky;
            top: 124px;
        }

        .settings-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            min-width: 0;
        }

        /* NAV */
        .snav-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .snav-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: .6rem 1rem;
            font-size: 13px;
            color: #6b7280;
            border-left: 2px solid transparent;
            transition: all .15s;
            cursor: pointer;
            user-select: none;
        }

        .snav-item:hover {
            background: #f9fafb;
            color: #111827;
        }

        .snav-item.active {
            background: #f0f4ff;
            color: #1a2744;
            font-weight: 600;
            border-left-color: #1a2744;
        }

        .snav-item svg {
            flex-shrink: 0;
        }

        .snav-divider {
            height: 1px;
            background: #f3f4f6;
        }

        .snav-badge {
            margin-left: auto;
            font-size: 10px;
            font-weight: 700;
            background: #ef4444;
            color: #fff;
            padding: 1px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
            line-height: 1.6;
        }

        .snav-badge.warn {
            background: #f59e0b;
        }

        /* SECTION */
        .section-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            display: none;
        }

        .section-card.active {
            display: block;
        }

        .section-head {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .section-head h2 {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .section-head p {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .section-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .section-footer {
            padding: .9rem 1.25rem;
            border-top: 1px solid #f3f4f6;
            background: #fafafa;
            display: flex;
            justify-content: flex-end;
            gap: .6rem;
        }

        /* FORM */
        .field-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .field-row {
            display: grid;
            gap: .85rem;
        }

        .field-row.cols-2 {
            grid-template-columns: 1fr 1fr;
        }

        .field-row.cols-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .field-label {
            font-size: 11px;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .field-hint {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .form-control {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: #111827;
            padding: .45rem .75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            outline: none;
            width: 100%;
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-control:focus {
            border-color: #1a2744;
            box-shadow: 0 0 0 3px rgba(26, 39, 68, .08);
        }

        select.form-control {
            cursor: pointer;
        }

        .divider-label {
            font-size: 11px;
            font-weight: 700;
            color: #1a2744;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .4rem 0 .2rem;
            border-bottom: 1.5px solid #e5e7eb;
            margin-bottom: .25rem;
        }

        /* TOGGLE */
        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            transition: background .15s;
        }

        .toggle-row:hover {
            background: #fafafa;
        }

        .toggle-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .toggle-label {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
        }

        .toggle-sub {
            font-size: 11px;
            color: #9ca3af;
        }

        .toggle {
            position: relative;
            width: 38px;
            height: 22px;
            flex-shrink: 0;
        }

        .toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            inset: 0;
            background: #d1d5db;
            border-radius: 22px;
            cursor: pointer;
            transition: background .2s;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            left: 3px;
            bottom: 3px;
            background: #fff;
            border-radius: 50%;
            transition: transform .2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
        }

        .toggle input:checked+.toggle-slider {
            background: #1a2744;
        }

        .toggle input:checked+.toggle-slider::before {
            transform: translateX(16px);
        }

        /* Dark mode toggle uses indigo when on */
        .toggle-dark input:checked+.toggle-slider {
            background: #6366f1;
        }

        /* FEE TABLE */
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .fee-table th {
            font-size: 10px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: .5rem .75rem;
            text-align: left;
            background: #fafafa;
        }

        .fee-table td {
            font-size: 13px;
            padding: .55rem .75rem;
            border-top: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .fee-table input {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: #111827;
            padding: .35rem .65rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            width: 100%;
            outline: none;
        }

        .fee-table input:focus {
            border-color: #1a2744;
            box-shadow: 0 0 0 2px rgba(26, 39, 68, .08);
        }

        .fee-type-badge {
            display: inline-flex;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
            background: #f0f4ff;
            color: #1a2744;
        }

        /* USER TABLE */
        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th {
            font-size: 10px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: .5rem .75rem;
            text-align: left;
            background: #fafafa;
        }

        .user-table td {
            font-size: 13px;
            color: #374151;
            padding: .65rem .75rem;
            border-top: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .user-avatar-sm {
            width: 28px;
            height: 28px;
            background: #1a2744;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .role-pill {
            display: inline-flex;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 4px;
        }

        .role-admin {
            background: #dbeafe;
            color: #1e40af;
        }

        .role-super {
            background: #ede9fe;
            color: #5b21b6;
        }

        .role-cemetery {
            background: #fff7ed;
            color: #ea580c;
        }

        /* BUTTONS */
        .btn-sm {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 500;
            color: #374151;
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
            white-space: nowrap;
        }

        .btn-sm:hover {
            background: #f9fafb;
            border-color: #1a2744;
            color: #1a2744;
        }

        .btn-sm-danger {
            border-color: #fca5a5;
            color: #991b1b;
        }

        .btn-sm-danger:hover {
            background: #fee2e2;
            border-color: #ef4444;
        }

        .btn-sm-warn {
            border-color: #fde68a;
            color: #92400e;
        }

        .btn-sm-warn:hover {
            background: #fef3c7;
            border-color: #f59e0b;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: .5rem 1.1rem;
            border-radius: 6px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: #fff;
            background: #1a2744;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-primary:hover {
            background: #243459;
        }

        .btn-cancel {
            padding: .5rem 1rem;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: #374151;
            background: #fff;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #f9fafb;
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: .45rem .9rem;
            border-radius: 6px;
            border: 1.5px solid #fca5a5;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #991b1b;
            background: #fff;
            cursor: pointer;
            transition: all .15s;
            white-space: nowrap;
        }

        .btn-danger:hover {
            background: #fee2e2;
            border-color: #ef4444;
        }

        /* Appearance preview box */
        .appearance-preview {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .ap-light,
        .ap-dark {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: background .15s;
        }

        .ap-light {
            background: #fff;
        }

        .ap-dark {
            background: #0f1117;
        }

        .ap-light:hover {
            background: #f9fafb;
        }

        .ap-dark:hover {
            background: #181b29;
        }

        .ap-divider {
            height: 1px;
            background: #e5e7eb;
        }

        .ap-swatch {
            width: 48px;
            height: 36px;
            border-radius: 6px;
            border: 2px solid #e5e7eb;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .ap-swatch-bar {
            height: 10px;
            background: #1a2744;
        }

        .ap-swatch-body {
            flex: 1;
            background: #f0f2f5;
        }

        .ap-swatch.dark .ap-swatch-bar {
            background: #111827;
        }

        .ap-swatch.dark .ap-swatch-body {
            background: #0f1117;
        }

        .ap-info {
            flex: 1;
        }

        .ap-name {
            font-size: 13px;
            font-weight: 600;
        }

        .ap-light .ap-name {
            color: #111827;
        }

        .ap-dark .ap-name {
            color: #e2e8f0;
        }

        .ap-sub {
            font-size: 11px;
            margin-top: 2px;
        }

        .ap-light .ap-sub {
            color: #9ca3af;
        }

        .ap-dark .ap-sub {
            color: #64748b;
        }

        .ap-check {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ap-check.selected {
            background: #6366f1;
            border-color: #6366f1;
        }

        /* DATA QUALITY */
        .dq-summary-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: .75rem;
        }

        .dq-stat {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: .75rem 1rem;
            text-align: center;
        }

        .dq-stat-val {
            font-size: 26px;
            font-weight: 800;
            line-height: 1;
        }

        .dq-stat-lbl {
            font-size: 10px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-top: 3px;
        }

        .dq-stat.red .dq-stat-val {
            color: #ef4444;
        }

        .dq-stat.amber .dq-stat-val {
            color: #f59e0b;
        }

        .dq-stat.blue .dq-stat-val {
            color: #3b82f6;
        }

        .dq-stat.green .dq-stat-val {
            color: #10b981;
        }

        .dq-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .dq-filters {
            display: flex;
            gap: .4rem;
            flex-wrap: wrap;
        }

        .dq-filter {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            border: 1.5px solid #e5e7eb;
            background: #fff;
            color: #6b7280;
            cursor: pointer;
            transition: all .15s;
        }

        .dq-filter:hover {
            border-color: #1a2744;
            color: #1a2744;
        }

        .dq-filter.f-all.active {
            background: #1a2744;
            color: #fff;
            border-color: #1a2744;
        }

        .dq-filter.f-dup.active {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
        }

        .dq-filter.f-miss.active {
            background: #f59e0b;
            color: #fff;
            border-color: #f59e0b;
        }

        .dq-filter.f-incon.active {
            background: #3b82f6;
            color: #fff;
            border-color: #3b82f6;
        }

        .dq-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .75rem;
            padding: 3rem 1rem;
            color: #9ca3af;
            font-size: 13px;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #e5e7eb;
            border-top-color: #1a2744;
            border-radius: 50%;
            animation: spin .6s linear infinite;
            flex-shrink: 0;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .dq-empty {
            text-align: center;
            padding: 3rem 1rem;
        }

        .dq-empty-icon {
            width: 52px;
            height: 52px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto .75rem;
        }

        .dq-empty h3 {
            font-size: 14px;
            font-weight: 700;
            color: #065f46;
        }

        .dq-empty p {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 3px;
        }

        .dq-issue {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: .6rem;
        }

        .dq-issue.sev-high {
            border-left: 4px solid #ef4444;
        }

        .dq-issue.sev-medium {
            border-left: 4px solid #f59e0b;
        }

        .dq-issue.sev-low {
            border-left: 4px solid #3b82f6;
        }

        .dq-issue-head {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem 1rem;
            background: #fafafa;
            cursor: pointer;
        }

        .dq-issue-head:hover {
            background: #f3f4f6;
        }

        .dq-issue.open .dq-issue-head {
            background: #f3f4f6;
        }

        .dq-sev-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dq-type-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 3px;
            letter-spacing: .04em;
            white-space: nowrap;
        }

        .type-duplicate {
            background: #fee2e2;
            color: #991b1b;
        }

        .type-missing {
            background: #fef3c7;
            color: #92400e;
        }

        .type-inconsistent {
            background: #dbeafe;
            color: #1e40af;
        }

        .dq-issue-title {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            flex: 1;
        }

        .dq-issue-count {
            font-size: 11px;
            color: #9ca3af;
            white-space: nowrap;
        }

        .dq-chevron {
            color: #9ca3af;
            transition: transform .2s;
            flex-shrink: 0;
        }

        .dq-issue.open .dq-chevron {
            transform: rotate(90deg);
        }

        .dq-issue-body {
            display: none;
            border-top: 1px solid #f3f4f6;
        }

        .dq-issue.open .dq-issue-body {
            display: block;
        }

        .dq-desc {
            padding: .55rem 1rem;
            background: #fafafa;
            font-size: 11px;
            color: #6b7280;
            border-bottom: 1px solid #f3f4f6;
        }

        .dq-record {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem 1rem;
            border-bottom: 1px solid #f9fafb;
            flex-wrap: wrap;
        }

        .dq-record:last-child {
            border-bottom: none;
        }

        .dq-record-info {
            flex: 1;
            min-width: 180px;
        }

        .dq-record-title {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
        }

        .dq-record-sub {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 1px;
        }

        .dq-field-chip {
            font-size: 11px;
            font-family: monospace;
            padding: 2px 7px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .dq-field-chip.missing {
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #92400e;
        }

        .dq-field-chip.bad {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .dq-field-chip.info {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e40af;
        }

        .dq-record-actions {
            display: flex;
            gap: .4rem;
            flex-shrink: 0;
        }

        /* TOAST */
        .toast {
            position: fixed;
            top: 1.1rem;
            right: 1.25rem;
            z-index: 9999;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .12);
            width: 300px;
            overflow: hidden;
            transform: translateX(calc(100% + 2rem));
            transition: transform .35s cubic-bezier(.34, 1.56, .64, 1);
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast-body {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .85rem 1rem;
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

        .toast-icon.green {
            background: #d1fae5;
        }

        .toast-icon.red {
            background: #fee2e2;
        }

        .toast-title {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        .toast-sub {
            font-size: 11px;
            color: #6b7280;
            margin-top: 1px;
        }

        .toast-bar {
            height: 3px;
        }

        .toast-bar-fill {
            height: 100%;
            width: 100%;
            transform-origin: left;
            animation: toastDrain 4s linear forwards;
        }

        @keyframes toastDrain {
            from {
                transform: scaleX(1)
            }

            to {
                transform: scaleX(0)
            }
        }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: 10px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            overflow: hidden;
            animation: modalIn .15s ease;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(-10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .modal-header {
            padding: 1rem 1.25rem;
            background: #1a2744;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
        }

        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255, 255, 255, .6);
            font-size: 18px;
            line-height: 1;
        }

        .modal-close:hover {
            color: #fff;
        }

        .modal-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: .85rem;
            background: #ffffff;
        }

        .modal-footer {
            padding: .85rem 1.25rem;
            border-top: 1px solid #f3f4f6;
            display: flex;
            justify-content: flex-end;
            gap: .6rem;
        }

        /* DANGER */
        .danger-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .9rem 1rem;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            background: #fff5f5;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .danger-title {
            font-size: 13px;
            font-weight: 600;
            color: #991b1b;
        }

        .danger-sub {
            font-size: 12px;
            color: #b91c1c;
            margin-top: 2px;
            opacity: .7;
        }

        /* ══════════════════════════════
       DARK MODE OVERRIDES
    ══════════════════════════════ */
        html.dark body {
            background: #0f1117 !important;
            color: #e2e8f0 !important;
        }

        html.dark .topbar {
            background: #1a1d27 !important;
            border-bottom-color: #2d3148 !important;
        }

        html.dark .topbar-title,
        html.dark .topbar-sub,
        html.dark .topbar-date {
            color: #e2e8f0 !important;
        }

        html.dark .topbar-date {
            color: #64748b !important;
        }

        html.dark .role-tag {
            background: #6366f1 !important;
        }

        html.dark .panel,
        html.dark .card {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .panel-head,
        html.dark .panel-header {
            background: #181b29 !important;
            border-bottom-color: #2d3148 !important;
        }

        html.dark .panel-head h3,
        html.dark .panel-header h3 {
            color: #e2e8f0 !important;
        }

        html.dark .panel-head a {
            color: #818cf8 !important;
        }

        html.dark table th {
            background: #181b29 !important;
            color: #64748b !important;
        }

        html.dark table td {
            color: #cbd5e1 !important;
            border-top-color: #2d3148 !important;
        }

        html.dark tr.row-expired td {
            background: #2a1a1a !important;
            border-top-color: #7f1d1d !important;
        }

        html.dark .permit-no {
            color: #818cf8 !important;
        }

        html.dark .badge-yellow {
            background: #422006 !important;
            color: #fde68a !important;
        }

        html.dark .badge-green {
            background: #052e16 !important;
            color: #86efac !important;
        }

        html.dark .badge-blue {
            background: #1e3a5f !important;
            color: #93c5fd !important;
        }

        html.dark .badge-red {
            background: #450a0a !important;
            color: #fca5a5 !important;
        }

        html.dark .btn-view,
        html.dark .btn-action {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #cbd5e1 !important;
        }

        html.dark .btn-view:hover,
        html.dark .btn-action:hover {
            background: #2d3148 !important;
            border-color: #6366f1 !important;
            color: #e2e8f0 !important;
        }

        html.dark .welcome {
            background: #111827 !important;
        }

        html.dark .name-main {
            color: #e2e8f0 !important;
        }

        html.dark .name-sub {
            color: #64748b !important;
        }

        html.dark .search-input {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #e2e8f0 !important;
        }

        html.dark .search-input:focus {
            border-color: #6366f1 !important;
        }

        html.dark .pager {
            border-top-color: #2d3148 !important;
        }

        html.dark .pager-info {
            color: #64748b !important;
        }

        html.dark .pager-btn {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #cbd5e1 !important;
        }

        html.dark .pager-btn:hover {
            border-color: #6366f1 !important;
            color: #e2e8f0 !important;
        }

        html.dark .pager-btn.active {
            background: #6366f1 !important;
            border-color: #6366f1 !important;
            color: #fff !important;
        }

        html.dark .pager-btn.disabled {
            color: #374151 !important;
        }

        html.dark .sort-link {
            color: #64748b !important;
        }

        html.dark .sort-link.active,
        html.dark .sort-link:hover {
            color: #818cf8 !important;
        }

        html.dark .hero {
            background: #111827 !important;
        }

        html.dark .card-head {
            background: #181b29 !important;
            border-bottom-color: #2d3148 !important;
        }

        html.dark .card-head-title {
            color: #94a3b8 !important;
        }

        html.dark .card-body {
            background: #1e2130 !important;
        }

        html.dark .fl {
            color: #64748b !important;
        }

        html.dark .fv,
        html.dark .fv-lg {
            color: #e2e8f0 !important;
        }

        html.dark .fee-box {
            background: #111827 !important;
        }

        html.dark .section-divider {
            color: #818cf8 !important;
            border-bottom-color: #2d3148 !important;
        }

        html.dark .form-control,
        html.dark select.form-control {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #e2e8f0 !important;
        }

        html.dark .form-control:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .15) !important;
        }

        html.dark .form-label {
            color: #94a3b8 !important;
        }

        html.dark .modal {
            background: #1e2130 !important;
        }

        html.dark .modal-header {
            background: #111827 !important;
        }

        html.dark #deleteModal .modal-header {
            background: #991b1b !important;
            border-bottom-color: #7f1d1d !important;
        }

        html.dark .modal-body {
            background: #1e2130 !important;
        }

        /* Keep delete modal body always white even in dark mode */
        html.dark #deleteModal .modal {
            background: #ffffff !important;
            border: none !important;
        }

        html.dark #deleteModal .modal-body {
            background: #ffffff !important;
        }

        html.dark .modal-footer {
            background: #181b29 !important;
            border-top-color: #2d3148 !important;
        }

        html.dark .btn-cancel {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #cbd5e1 !important;
        }

        html.dark .fee-row {
            border-color: #2d3148 !important;
        }

        html.dark .fee-row:hover {
            background: #252840 !important;
            border-color: #6366f1 !important;
        }

        html.dark .fee-row label {
            color: #e2e8f0 !important;
        }

        html.dark .fee-amount {
            color: #818cf8 !important;
        }

        html.dark .upload-card {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .dropzone {
            border-color: #374151 !important;
        }

        html.dark .dropzone:hover,
        html.dark .dropzone.drag-over {
            border-color: #6366f1 !important;
            background: #1e2d6b !important;
        }

        html.dark .dropzone-icon {
            background: #252840 !important;
        }

        html.dark .dropzone-title {
            color: #cbd5e1 !important;
        }

        html.dark .dropzone-sub {
            color: #64748b !important;
        }

        html.dark .docs-card {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .docs-head {
            background: #181b29 !important;
            border-bottom-color: #2d3148 !important;
        }

        html.dark .docs-head-title {
            color: #e2e8f0 !important;
        }

        html.dark .docs-head-sub {
            color: #64748b !important;
        }

        html.dark .docs-col-files {
            border-right-color: #2d3148 !important;
        }

        html.dark .docs-col-upload {
            background: #181b29 !important;
        }

        html.dark .doc-item {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .doc-item:hover {
            background: #252840 !important;
            border-color: #6366f1 !important;
        }

        html.dark .doc-name {
            color: #e2e8f0 !important;
        }

        html.dark .doc-meta {
            color: #64748b !important;
        }

        html.dark .btn-doc {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #cbd5e1 !important;
        }

        html.dark .btn-doc:hover {
            background: #2d3148 !important;
            border-color: #6366f1 !important;
            color: #e2e8f0 !important;
        }

        html.dark .info-item {
            border-color: #2d3148 !important;
        }

        html.dark .info-label {
            color: #64748b !important;
        }

        html.dark .info-value {
            color: #e2e8f0 !important;
        }

        html.dark .info-value.empty {
            color: #374151 !important;
        }

        html.dark .panel-head {
            background: #181b29 !important;
        }

        html.dark .danger-panel {
            background: #1e2130 !important;
            border-color: #7f1d1d !important;
        }

        html.dark .danger-head {
            background: #2a1a1a !important;
            border-bottom-color: #7f1d1d !important;
        }

        html.dark .danger-desc {
            color: #e2e8f0 !important;
        }

        html.dark .danger-sub {
            color: #94a3b8 !important;
        }

        html.dark .topbar-back {
            color: #94a3b8 !important;
        }

        html.dark .topbar-back:hover {
            color: #e2e8f0 !important;
        }

        html.dark .topbar-sep {
            color: #334155 !important;
        }

        html.dark .btn-print {
            background: rgba(255, 255, 255, .08) !important;
        }

        html.dark .toggle-row {
            border-color: #2d3148 !important;
        }

        html.dark .toggle-row:hover {
            background: #252840 !important;
        }

        html.dark .toggle-label {
            color: #e2e8f0 !important;
        }

        html.dark .toggle-sub {
            color: #64748b !important;
        }

        html.dark .snav-card {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .snav-item {
            color: #94a3b8 !important;
        }

        html.dark .snav-item:hover {
            background: #252840 !important;
            color: #e2e8f0 !important;
        }

        html.dark .snav-item.active {
            background: #1e2d6b !important;
            color: #818cf8 !important;
            border-left-color: #6366f1 !important;
        }

        html.dark .snav-divider {
            background: #2d3148 !important;
        }

        html.dark .section-card {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .section-head {
            border-bottom-color: #2d3148 !important;
        }

        html.dark .section-head h2 {
            color: #e2e8f0 !important;
        }

        html.dark .section-head p {
            color: #64748b !important;
        }

        html.dark .section-footer {
            background: #181b29 !important;
            border-top-color: #2d3148 !important;
        }

        html.dark .fee-table {
            border-color: #2d3148 !important;
        }

        html.dark .fee-table th {
            background: #181b29 !important;
            color: #64748b !important;
        }

        html.dark .fee-table td {
            border-top-color: #2d3148 !important;
            color: #cbd5e1 !important;
        }

        html.dark .fee-table input {
            background: #252840 !important;
            border-color: #374151 !important;
            color: #e2e8f0 !important;
        }

        html.dark .fee-type-badge {
            background: #1e2d6b !important;
            color: #818cf8 !important;
        }

        html.dark .user-table th {
            background: #181b29 !important;
            color: #64748b !important;
        }

        html.dark .user-table td {
            color: #cbd5e1 !important;
            border-top-color: #2d3148 !important;
        }

        html.dark .appearance-preview {
            border-color: #2d3148 !important;
        }

        html.dark .ap-light {
            background: #252840 !important;
        }

        html.dark .ap-light:hover {
            background: #2d3148 !important;
        }

        html.dark .ap-light .ap-name {
            color: #e2e8f0 !important;
        }

        html.dark .ap-light .ap-sub {
            color: #64748b !important;
        }

        html.dark .ap-divider {
            background: #2d3148 !important;
        }

        html.dark .danger-item {
            background: #2a1a1a !important;
            border-color: #7f1d1d !important;
        }

        html.dark .danger-title {
            color: #fca5a5 !important;
        }

        html.dark .danger-sub {
            color: #f87171 !important;
        }

        html.dark .badge-male {
            background: #1e3a5f !important;
            color: #93c5fd !important;
        }

        html.dark .badge-female {
            background: #3b0764 !important;
            color: #e9d5ff !important;
        }

        html.dark .toast {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .toast-title {
            color: #e2e8f0 !important;
        }

        html.dark .toast-sub {
            color: #94a3b8 !important;
        }

        html.dark .upload-note {
            color: #64748b !important;
        }

        html.dark .btn-upload {
            background: #6366f1 !important;
        }

        html.dark .btn-upload:hover {
            background: #4f46e5 !important;
        }

        html.dark .btn-primary {
            background: #6366f1 !important;
        }

        html.dark .btn-primary:hover {
            background: #4f46e5 !important;
        }

        html.dark .dq-stat {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .dq-issue {
            border-color: #2d3148 !important;
        }

        html.dark .dq-issue-head {
            background: #181b29 !important;
        }

        html.dark .dq-issue-head:hover,
        html.dark .dq-issue.open .dq-issue-head {
            background: #252840 !important;
        }

        html.dark .dq-issue-title {
            color: #e2e8f0 !important;
        }

        html.dark .dq-issue-count {
            color: #64748b !important;
        }

        html.dark .dq-issue-body {
            border-top-color: #2d3148 !important;
        }

        html.dark .dq-desc {
            background: #181b29 !important;
            color: #94a3b8 !important;
            border-bottom-color: #2d3148 !important;
        }

        html.dark .dq-record {
            border-bottom-color: #2d3148 !important;
        }

        html.dark .dq-record-title {
            color: #e2e8f0 !important;
        }

        html.dark .dq-record-sub {
            color: #64748b !important;
        }

        html.dark hr {
            border-color: #2d3148 !important;
        }

        html.dark .docs-empty {
            color: #374151 !important;
        }

        html.dark .lightbox {
            background: rgba(0, 0, 0, .92) !important;
        }

        html.dark #photoDropzone {
            background: #181b29 !important;
            border-color: #374151 !important;
        }

        html.dark #dropzoneTxt {
            color: #94a3b8 !important;
        }

        html.dark #avatarPreview {
            border-color: #2d3148 !important;
        }

        html.dark .profile-name-display {
            color: #e2e8f0 !important;
        }
    </style>
</head>

<body>

    @include('admin.partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">
                    Settings
                    <a href="{{ route('support.manual') }}#settings" class="help-link-trigger"
                        title="How to use settings"
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
            <span
                class="role-tag">{{ auth()->user()->role === 'super_admin' ? 'Super Admin' : (auth()->user()->role === 'cemetery_manager' ? 'Cemetery Manager' : 'Admin') }}</span>
        </div>

        <div class="content">

            {{-- LEFT NAV --}}
            <div class="settings-nav">
                <div class="snav-card">
                    <div class="snav-item" onclick="showSection('profile',this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        My Profile
                    </div>
                    <div class="snav-divider"></div>
                    <div class="snav-item active" onclick="showSection('general',this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
                        </svg>
                        General
                    </div>
                    <div class="snav-divider"></div>
                    <div class="snav-item" onclick="showSection('appearance',this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <circle cx="12" cy="12" r="5" />
                            <line x1="12" y1="1" x2="12" y2="3" />
                            <line x1="12" y1="21" x2="12" y2="23" />
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                            <line x1="1" y1="12" x2="3" y2="12" />
                            <line x1="21" y1="12" x2="23" y2="12" />
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                        </svg>
                        Appearance
                    </div>
                    <div class="snav-divider"></div>
                    <div class="snav-item" onclick="showSection('fees',this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                        </svg>
                        Permit Fees
                    </div>
                    <div class="snav-divider"></div>
                    <div class="snav-item" onclick="showSection('users',this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                        </svg>
                        Users & Access
                    </div>
                    <div class="snav-divider"></div>
                    <div class="snav-item" onclick="showSection('notifications',this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 01-3.46 0" />
                        </svg>
                        Notifications
                    </div>

                    <div class="snav-divider"></div>
                    <div class="snav-item" onclick="showSection('danger',this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.8">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                        <span style="color:#ef4444">Danger Zone</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT BODY --}}
            <div class="settings-body">

                {{-- MY PROFILE --}}
                <div class="section-card" id="section-profile">
                    <div class="section-head">
                        <h2>My Profile</h2>
                        <p>Update your profile picture displayed in the sidebar.</p>
                    </div>
                    <div class="section-body">
                        <div class="divider-label">Profile Picture</div>

                        {{-- Current avatar preview --}}
                        <div style="display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap">
                            <div id="avatarPreview"
                                style="width:80px;height:80px;border-radius:12px;background:#1a2744;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:#fff;overflow:hidden;flex-shrink:0;border:3px solid #e5e7eb;">
                                @if(auth()->user()->profile_photo)
                                    <img id="avatarPreviewImg" src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                        style="width:100%;height:100%;object-fit:cover;" alt="Profile">
                                @else
                                    <span id="avatarInitials">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    <img id="avatarPreviewImg" src=""
                                        style="width:100%;height:100%;object-fit:cover;display:none;" alt="Profile">
                                @endif
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:#111827" class="profile-name-display">
                                    {{ auth()->user()->name }}</div>
                                <div style="font-size:12px;color:#9ca3af;margin-top:2px">
                                    {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                                <div style="font-size:11px;color:#9ca3af;margin-top:6px">JPG, PNG, GIF or WebP · Max 2MB
                                </div>
                            </div>
                        </div>

                        {{-- Upload form --}}
                        <form method="POST" action="{{ route('settings.profile.update') }}"
                            enctype="multipart/form-data" id="photoUploadForm">
                            @csrf
                            <div id="photoDropzone"
                                style="border:2px dashed #d1d5db;border-radius:10px;padding:2rem 1rem;text-align:center;cursor:pointer;transition:all .15s;position:relative;background:#fafafa;">
                                <input type="file" name="photo" id="photoInput" accept="image/*"
                                    style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;">
                                <div
                                    style="width:40px;height:40px;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"
                                        stroke-width="1.8">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                </div>
                                <div style="font-size:13px;font-weight:500;color:#374151" id="dropzoneTxt">Click to
                                    upload or drag & drop a photo</div>
                                <div id="dropzoneFilename"
                                    style="font-size:12px;color:#6366f1;margin-top:4px;display:none;"></div>
                            </div>

                            @error('photo')
                                <div style="color:#ef4444;font-size:12px;margin-top:.4rem">{{ $message }}</div>
                            @enderror

                            <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:.85rem">
                                @if(auth()->user()->profile_photo)
                                    <form method="POST" action="{{ route('settings.profile.delete') }}"
                                        id="deleteProfilePhotoForm" style="margin:0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-danger"
                                            onclick="openDeleteModal('Remove your profile photo?', 'Profile Photo', 'deleteProfilePhotoForm')">Remove
                                            Photo</button>
                                    </form>
                                @endif
                                <button type="submit" class="btn-primary" id="uploadBtn" disabled>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                    Upload Photo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- GENERAL --}}
                <div class="section-card active" id="section-general">
                    <div class="section-head">
                        <h2>General Configuration</h2>
                        <p>Basic system identity and operational settings.</p>
                    </div>
                    <form method="POST" action="{{ route('settings.update', 'general') }}">
                        @csrf @method('PUT')
                        <div class="section-body">
                            <div class="divider-label">Office Information</div>
                            <div class="field-row cols-2">
                                <div class="field-group"><label class="field-label">Municipality Name</label><input
                                        type="text" name="municipality_name" class="form-control"
                                        value="{{ $settings['municipality_name'] ?? 'Municipality of Carmen' }}"></div>
                                <div class="field-group"><label class="field-label">Province</label><input type="text"
                                        name="province" class="form-control"
                                        value="{{ $settings['province'] ?? 'Davao del Norte' }}"></div>
                            </div>
                            <div class="field-row cols-2">
                                <div class="field-group"><label class="field-label">Civil Registrar Name</label><input
                                        type="text" name="registrar_name" class="form-control"
                                        value="{{ $settings['registrar_name'] ?? '' }}"
                                        placeholder="e.g. Juan Dela Cruz"></div>
                                <div class="field-group"><label class="field-label">Municipal Mayor</label><input
                                        type="text" name="mayor_name" class="form-control"
                                        value="{{ $settings['mayor_name'] ?? 'Leonidas R. Bahague' }}"></div>
                            </div>
                            <div class="field-group"><label class="field-label">Office Address</label><input type="text"
                                    name="office_address" class="form-control"
                                    value="{{ $settings['office_address'] ?? 'Tuganay, Carmen, Davao del Norte' }}">
                            </div>
                            <div class="divider-label">Permit Numbering</div>
                            <div class="field-row cols-3">
                                <div class="field-group"><label class="field-label">Permit Prefix</label><input
                                        type="text" name="permit_prefix" class="form-control"
                                        value="{{ $settings['permit_prefix'] ?? 'BP' }}"><span class="field-hint">e.g.
                                        BP-2026-00001</span></div>
                                <div class="field-group"><label class="field-label">Permit Expiry (Years)</label><select
                                        name="permit_expiry_years" class="form-control">@foreach(range(1, 10) as $yr)
                                            <option value="{{ $yr }}" {{ ($settings['permit_expiry_years'] ?? 5) == $yr ? 'selected' : '' }}>{{ $yr }} Year{{ $yr > 1 ? 's' : '' }}</option>
                                        @endforeach</select></div>
                                <div class="field-group"><label class="field-label">Expiry Warning (Days)</label><input
                                        type="number" name="expiry_warning_days" class="form-control" min="7" max="90"
                                        value="{{ $settings['expiry_warning_days'] ?? 30 }}"><span
                                        class="field-hint">Show "Expiring Soon" badge</span></div>
                            </div>
                            <div class="divider-label">Display</div>
                            <div class="field-row cols-2">
                                <div class="field-group"><label class="field-label">Date Format</label><select
                                        name="date_format" class="form-control">
                                        <option value="M d, Y" {{ ($settings['date_format'] ?? 'M d, Y') === 'M d, Y' ? 'selected' : '' }}>Mar 20, 2026</option>
                                        <option value="d/m/Y" {{ ($settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : '' }}>20/03/2026</option>
                                        <option value="Y-m-d" {{ ($settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' }}>2026-03-20</option>
                                        <option value="F d, Y" {{ ($settings['date_format'] ?? '') === 'F d, Y' ? 'selected' : '' }}>March 20, 2026</option>
                                    </select></div>
                                <div class="field-group"><label class="field-label">Records Per Page</label><select
                                        name="per_page" class="form-control">@foreach([10, 15, 20, 25, 50] as $n)<option
                                            value="{{ $n }}" {{ ($settings['per_page'] ?? 15) == $n ? 'selected' : '' }}>
                                        {{ $n }} records</option>@endforeach</select></div>
                            </div>
                        </div>
                        <div class="section-footer"><button type="submit" class="btn-primary"><svg width="13"
                                    height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>Save Changes</button></div>
                    </form>
                </div>

                {{-- ══ APPEARANCE ══ --}}
                <div class="section-card" id="section-appearance">
                    <div class="section-head">
                        <h2>Appearance</h2>
                        <p>Personalise how the system looks. Your preference is saved per user and persists across
                            sessions.</p>
                    </div>
                    <div class="section-body">
                        <div class="divider-label">Theme</div>

                        <div class="appearance-preview">
                            {{-- Light option --}}
                            <div class="ap-light" id="ap-light" onclick="setTheme('light')">
                                <div class="ap-swatch light">
                                    <div class="ap-swatch-bar"></div>
                                    <div class="ap-swatch-body"></div>
                                </div>
                                <div class="ap-info">
                                    <div class="ap-name">Light Mode</div>
                                    <div class="ap-sub">Clean white interface — default</div>
                                </div>
                                <div class="ap-check" id="check-light">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff"
                                        stroke-width="3">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                            </div>

                            <div class="ap-divider"></div>

                            {{-- Dark option --}}
                            <div class="ap-dark" id="ap-dark" onclick="setTheme('dark')">
                                <div class="ap-swatch dark">
                                    <div class="ap-swatch-bar"></div>
                                    <div class="ap-swatch-body"></div>
                                </div>
                                <div class="ap-info">
                                    <div class="ap-name">Dark Mode</div>
                                    <div class="ap-sub">Easy on the eyes in low-light environments</div>
                                </div>
                                <div class="ap-check" id="check-dark">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff"
                                        stroke-width="3">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <p style="font-size:11px;color:#9ca3af">
                            Your theme is saved in your browser and tied to your account. It will persist even after
                            logging out and back in.
                        </p>
                    </div>
                </div>

                {{-- FEES --}}
                <div class="section-card" id="section-fees">
                    <div class="section-head">
                        <h2>Permit Fee Schedule</h2>
                        <p>Configure burial fees applied to each permit type.</p>
                    </div>
                    <form method="POST" action="{{ route('settings.update', 'fees') }}">
                        @csrf @method('PUT')
                        <div class="section-body">
                            <table class="fee-table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Tomb/Space (₱)</th>
                                        <th>Permit (₱)</th>
                                        <th>Maint. (₱)</th>
                                        <th>App. (₱)</th>
                                        <th style="color:#1a2744;font-weight:700">Total (₱)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $fd = ['cemented' => ['label' => 'Cemented', 'tomb' => 910, 'permit' => 20, 'maint' => 50, 'app' => 20], 'niche_1st' => ['label' => 'Niche 1st', 'tomb' => 7960, 'permit' => 20, 'maint' => 0, 'app' => 20], 'niche_2nd' => ['label' => 'Niche 2nd', 'tomb' => 6560, 'permit' => 20, 'maint' => 0, 'app' => 20], 'niche_3rd' => ['label' => 'Niche 3rd', 'tomb' => 5660, 'permit' => 20, 'maint' => 0, 'app' => 20], 'niche_4th' => ['label' => 'Niche 4th', 'tomb' => 5260, 'permit' => 20, 'maint' => 0, 'app' => 20], 'bone_niches' => ['label' => 'Bone Niches', 'tomb' => 4960, 'permit' => 20, 'maint' => 0, 'app' => 20]]; @endphp
                                    @foreach($fd as $key => $fee)
                                        @php $s = $settings['fees'][$key] ?? $fee;
                                        $t = ($s['tomb'] ?? 0) + ($s['permit'] ?? 0) + ($s['maint'] ?? 0) + ($s['app'] ?? 0); @endphp
                                        <tr>
                                            <td><span class="fee-type-badge">{{ $fee['label'] }}</span></td>
                                            <td><input type="number" name="fees[{{ $key }}][tomb]"
                                                    value="{{ $s['tomb']   }}" min="0" class="fee-input"
                                                    data-row="{{ $key }}"></td>
                                            <td><input type="number" name="fees[{{ $key }}][permit]"
                                                    value="{{ $s['permit'] }}" min="0" class="fee-input"
                                                    data-row="{{ $key }}"></td>
                                            <td><input type="number" name="fees[{{ $key }}][maint]"
                                                    value="{{ $s['maint']  }}" min="0" class="fee-input"
                                                    data-row="{{ $key }}"></td>
                                            <td><input type="number" name="fees[{{ $key }}][app]" value="{{ $s['app']    }}"
                                                    min="0" class="fee-input" data-row="{{ $key }}"></td>
                                            <td><span id="total-{{ $key }}"
                                                    style="font-size:13px;font-weight:700;color:#1a2744">₱{{ number_format($t) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <p style="font-size:11px;color:#9ca3af">Changes apply to new permits only.</p>
                        </div>
                        <div class="section-footer"><button type="submit" class="btn-primary"><svg width="13"
                                    height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>Save Fees</button></div>
                    </form>
                </div>

                {{-- USERS --}}
                <div class="section-card" id="section-users">
                    <div class="section-head">
                        <h2>Users & Access</h2>
                        <p>Manage system accounts and role assignments.</p>
                    </div>
                    <div class="section-body">
                        <div style="display:flex;justify-content:flex-end"><button class="btn-primary"
                                onclick="document.getElementById('addUserModal').classList.add('open')"><svg width="12"
                                    height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>Add User</button></div>
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users ?? [] as $user)
                                    <tr>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:8px">
                                                <div class="user-avatar-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                                <span
                                                    style="font-weight:500">{{ $user->name }}</span>@if($user->id === auth()->id())<span
                                                    style="font-size:10px;color:#9ca3af">(you)</span>@endif
                                            </div>
                                        </td>
                                        <td style="color:#6b7280">{{ $user->email }}</td>
                                        <td><span
                                                class="role-pill {{ $user->role === 'super_admin' ? 'role-super' : ($user->role === 'cemetery_manager' ? 'role-cemetery' : 'role-admin') }}">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                                        </td>
                                        <td style="color:#6b7280;font-size:12px">{{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td>@if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('settings.users.destroy', $user) }}"
                                                id="deleteUserForm_{{ $user->id }}">@csrf @method('DELETE')<button
                                                    type="button" class="btn-sm btn-sm-danger"
                                                    onclick="openDeleteModal('Remove {{ $user->name }} permanently?', 'User: {{ $user->name }}', 'deleteUserForm_{{ $user->id }}')">Remove</button>
                                        </form>@endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align:center;color:#9ca3af;padding:1.5rem">No users
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- NOTIFICATIONS --}}
                <div class="section-card" id="section-notifications">
                    <div class="section-head">
                        <h2>Notification Settings</h2>
                        <p>Control which events trigger alerts and reminders.</p>
                    </div>
                    <form method="POST" action="{{ route('settings.update', 'notifications') }}">
                        @csrf @method('PUT')
                        <div class="section-body">
                            <div class="divider-label">Permit Alerts</div>
                            <div class="toggle-row">
                                <div class="toggle-info"><span class="toggle-label">Expiring Permit
                                        Reminders</span><span class="toggle-sub">Show badge when permits are near
                                        expiry</span></div><label class="toggle"><input type="checkbox"
                                        name="notify_expiring" value="1" {{ ($settings['notify_expiring'] ?? true) ? 'checked' : '' }}><span
                                        class="toggle-slider"></span></label>
                            </div>
                            <div class="toggle-row">
                                <div class="toggle-info"><span class="toggle-label">New Permit Submission
                                        Alert</span><span class="toggle-sub">Highlight new pending permits on
                                        dashboard</span></div><label class="toggle"><input type="checkbox"
                                        name="notify_new_permit" value="1" {{ ($settings['notify_new_permit'] ?? true) ? 'checked' : '' }}><span
                                        class="toggle-slider"></span></label>
                            </div>
                            <div class="toggle-row">
                                <div class="toggle-info"><span class="toggle-label">Expired Permit Highlight</span><span
                                        class="toggle-sub">Show red row for expired permits in lists</span></div><label
                                    class="toggle"><input type="checkbox" name="highlight_expired" value="1" {{ ($settings['highlight_expired'] ?? true) ? 'checked' : '' }}><span
                                        class="toggle-slider"></span></label>
                            </div>
                            <div class="divider-label">Import Alerts</div>
                            <div class="toggle-row">
                                <div class="toggle-info"><span class="toggle-label">Import Toast
                                        Notifications</span><span class="toggle-sub">Show success/error toasts after
                                        Excel imports</span></div><label class="toggle"><input type="checkbox"
                                        name="notify_import" value="1" {{ ($settings['notify_import'] ?? true) ? 'checked' : '' }}><span
                                        class="toggle-slider"></span></label>
                            </div>
                            <div class="toggle-row">
                                <div class="toggle-info"><span class="toggle-label">Show Skipped Row Details</span><span
                                        class="toggle-sub">Display reasons for skipped rows after import</span></div>
                                <label class="toggle"><input type="checkbox" name="notify_skip_reasons" value="1" {{ ($settings['notify_skip_reasons'] ?? true) ? 'checked' : '' }}><span
                                        class="toggle-slider"></span></label>
                            </div>
                        </div>
                        <div class="section-footer"><button type="submit" class="btn-primary"><svg width="13"
                                    height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>Save Preferences</button></div>
                    </form>
                </div>

                {{-- DANGER --}}
                <div class="section-card" id="section-danger">
                    <div class="section-head">
                        <h2>Danger Zone</h2>
                        <p>Irreversible actions — proceed with caution.</p>
                    </div>
                    <div class="section-body" style="gap:.75rem">
                        <div class="danger-item">
                            <div>
                                <div class="danger-title">Reset All Permit Fees</div>
                                <div class="danger-sub">Restores all fee values back to the original defaults.</div>
                            </div>
                            <form method="POST" action="{{ route('settings.reset', 'fees') }}" id="resetFeesForm">
                                @csrf<button type="button" class="btn-danger"
                                    onclick="openDeleteModal('Reset all permit fees to defaults?', 'Permit Fees', 'resetFeesForm')"><svg
                                        width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path d="M3 12a9 9 0 109-9" />
                                        <polyline points="3 3 3 9 9 9" />
                                    </svg>Reset Fees</button></form>
                        </div>
                        <div class="danger-item">
                            <div>
                                <div class="danger-title">Clear Import History</div>
                                <div class="danger-sub">Permanently deletes all Excel import log records.</div>
                            </div>
                            <form method="POST" action="{{ route('settings.reset', 'import-logs') }}" id="clearLogsForm">
                                @csrf<button type="button" class="btn-danger"
                                    onclick="openDeleteModal('Delete all import history logs?', 'Import Logs', 'clearLogsForm')"><svg
                                        width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14H6L5 6" />
                                    </svg>Clear Logs</button></form>
                        </div>
                        <div class="danger-item">
                            <div>
                                <div class="danger-title">Reset All Settings</div>
                                <div class="danger-sub">Resets every configuration option back to factory defaults.
                                </div>
                            </div>
                            <form method="POST" action="{{ route('settings.reset', 'all') }}" id="resetAllForm">
                                @csrf<button type="button" class="btn-danger"
                                    onclick="openDeleteModal('Reset EVERYTHING to factory defaults?', 'All Settings', 'resetAllForm')"><svg
                                        width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path
                                            d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                        <line x1="12" y1="9" x2="12" y2="13" />
                                        <line x1="12" y1="17" x2="12.01" y2="17" />
                                    </svg>Reset Everything</button></form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ADD USER MODAL --}}
    <div class="modal-overlay" id="addUserModal" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="modal">
            <div class="modal-header">
                <h3>Add New User</h3><button class="modal-close"
                    onclick="document.getElementById('addUserModal').classList.remove('open')">×</button>
            </div>
            <form method="POST" action="{{ route('settings.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="field-group"><label class="field-label">Full Name *</label><input type="text"
                            name="name" class="form-control" required placeholder="Juan Dela Cruz"></div>
                    <div class="field-group"><label class="field-label">Email *</label><input type="email" name="email"
                            class="form-control" required placeholder="user@lgucarmen.gov.ph"></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
                        <div class="field-group"><label class="field-label">Password *</label><input type="password"
                                name="password" class="form-control" required minlength="8" placeholder="Min. 8 chars">
                        </div>
                        <div class="field-group"><label class="field-label">Role *</label><select name="role"
                                class="form-control" required>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                                <option value="cemetery_manager">Cemetery Manager</option>
                            </select></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn-cancel"
                        onclick="document.getElementById('addUserModal').classList.remove('open')">Cancel</button><button
                        type="submit" class="btn-primary">Create User</button></div>
            </form>
        </div>
    </div>

    {{-- CROP MODAL --}}
    <div class="modal-overlay" id="cropModal">
        <div class="modal" style="max-width:500px">
            <div class="modal-header">
                <h3>Crop Profile Photo</h3>
            </div>
            <div class="modal-body"
                style="padding:0;background:#000;display:flex;align-items:center;justify-content:center;height:400px;overflow:hidden">
                <img id="cropImage" src="" style="max-width:100%;max-height:100%;display:block;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="cancelCrop()">Cancel</button>
                <button type="button" class="btn-primary" onclick="applyCrop()">Apply Crop</button>
            </div>
        </div>
    </div>

    {{-- TOASTS --}}
    @if(session('success'))
        <div class="toast" id="sToast">
            <div class="toast-body">
                <div class="toast-icon green"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#065f46"
                        stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12" />
                    </svg></div>
                <div>
                    <div class="toast-title">Saved</div>
                    <div class="toast-sub">{{ session('success') }}</div>
                </div>
            </div>
            <div class="toast-bar" style="background:#e5e7eb">
                <div class="toast-bar-fill" style="background:#10b981"></div>
            </div>
        </div>
    @endif
    <div class="toast" id="aToast">
        <div class="toast-body">
            <div class="toast-icon green" id="aToastIcon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="#065f46" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg></div>
            <div>
                <div class="toast-title" id="aToastTitle">Done</div>
                <div class="toast-sub" id="aToastSub"></div>
            </div>
        </div>
        <div class="toast-bar" style="background:#e5e7eb">
            <div class="toast-bar-fill" id="aToastBar" style="background:#10b981"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const DK_KEY = 'lgu_dark';

        /* ── Section nav ── */
        function showSection(id, el) {
            document.querySelectorAll('.section-card').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.snav-item').forEach(i => i.classList.remove('active'));
            document.getElementById('section-' + id).classList.add('active');
            el.classList.add('active');

            // Update hash for deep linking
            if (window.history.pushState) {
                window.history.replaceState(null, null, '#' + id);
            } else {
                window.location.hash = id;
            }
        }

        // Auto-restore tab on load
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const tabId = params.get('tab') || window.location.hash.substring(1);

            if (tabId) {
                // Find the nav item that corresponds to this section
                const navItem = Array.from(document.querySelectorAll('.snav-item')).find(item => {
                    return item.getAttribute('onclick')?.includes(`'${tabId}'`);
                });

                if (navItem) {
                    showSection(tabId, navItem);
                }
            }
        });

        /* ── Live fee totals ── */
        document.querySelectorAll('.fee-input').forEach(i => {
            i.addEventListener('input', () => {
                const row = i.dataset.row;
                let t = 0;
                document.querySelectorAll(`[data-row="${row}"]`).forEach(x => t += parseFloat(x.value) || 0);
                document.getElementById('total-' + row).textContent = '₱' + t.toLocaleString();
            });
        });

        /* ── Session toast ── */
        (() => { const t = document.getElementById('sToast'); if (!t) return; setTimeout(() => t.classList.add('show'), 50); setTimeout(() => t.classList.remove('show'), 4500); })();

        function toast(title, sub, ok) {
            if (ok === undefined) ok = true;
            if (!sub) sub = '';
            try {
                const t = document.getElementById('aToast');
                const ico = document.getElementById('aToastIcon');
                const ttl = document.getElementById('aToastTitle');
                const sb = document.getElementById('aToastSub');
                const bar = document.getElementById('aToastBar');
                if (!t || !ico || !ttl || !sb || !bar) return;
                ttl.textContent = title;
                sb.textContent = sub;
                ico.className = 'toast-icon ' + (ok ? 'green' : 'red');
                ico.innerHTML = ok
                    ? '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>'
                    : '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#991b1b" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
                bar.style.background = ok ? '#10b981' : '#ef4444';
                /* restart progress bar animation */
                const clone = bar.cloneNode(true);
                bar.parentNode.replaceChild(clone, bar);
                t.classList.remove('show');
                clearTimeout(t._toastTimer);
                requestAnimationFrame(() => {
                    t.classList.add('show');
                    t._toastTimer = setTimeout(() => t.classList.remove('show'), 4500);
                });
            } catch (e) { console.warn('toast error', e); }
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open')); });

        /* ══════════════════════════
           DARK MODE
        ══════════════════════════ */
        function syncAppearanceUI() {
            const isDark = document.documentElement.classList.contains('dark');
            const cLight = document.getElementById('check-light');
            const cDark = document.getElementById('check-dark');
            if (cLight) cLight.classList.toggle('selected', !isDark);
            if (cDark) cDark.classList.toggle('selected', isDark);
        }

        function setTheme(theme) {
            const isDark = theme === 'dark';
            /* 1. Apply to current page immediately */
            document.documentElement.classList.toggle('dark', isDark);
            /* 2. Persist to localStorage */
            try { localStorage.setItem(DK_KEY, isDark ? '1' : '0'); } catch (e) { }
            /* 3. Update the checkmark UI */
            syncAppearanceUI();
            /* 4. Show confirmation toast */
            toast(isDark ? '🌙 Dark Mode On' : '☀️ Light Mode On', 'Theme saved to your preferences.');
        }

        // Sync UI on load
        syncAppearanceUI();

        /* ══════════════════════════
           PROFILE PHOTO UPLOAD
        ══════════════════════════ */
        (function () {
            const input = document.getElementById('photoInput');
            const uploadBtn = document.getElementById('uploadBtn');
            const dropzone = document.getElementById('photoDropzone');
            const dropzoneTxt = document.getElementById('dropzoneTxt');
            const filename = document.getElementById('dropzoneFilename');
            const previewImg = document.getElementById('avatarPreviewImg');
            const initials = document.getElementById('avatarInitials');

            if (!input) return; // Profile section might not exist (superadmin)

            let cropper = null;
            let croppedBlob = null;
            const cropModal = document.getElementById('cropModal');
            const cropImg = document.getElementById('cropImage');

            window.cancelCrop = function () {
                cropModal.classList.remove('open');
                if (cropper) { cropper.destroy(); cropper = null; }
                input.value = ''; // Reset input
            };

            window.applyCrop = function () {
                if (!cropper) return;
                const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
                canvas.toBlob(blob => {
                    croppedBlob = blob;
                    const url = URL.createObjectURL(blob);

                    // Update preview
                    if (previewImg) {
                        previewImg.src = url;
                        previewImg.style.display = 'block';
                    }
                    if (initials) initials.style.display = 'none';

                    dropzoneTxt.textContent = 'Photo ready to upload (Cropped)';
                    uploadBtn.disabled = false;
                    dropzone.style.borderColor = '#6366f1';
                    dropzone.style.background = '#f5f3ff';

                    cropModal.classList.remove('open');
                    cropper.destroy();
                    cropper = null;
                }, 'image/jpeg', 0.9);
            };

            function handleFile(file) {
                if (!file || !file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = e => {
                    cropImg.src = e.target.result;
                    cropModal.classList.add('open');

                    // Initialize cropper after modal is visible to ensure proper dimensions
                    setTimeout(() => {
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(cropImg, {
                            aspectRatio: 1,
                            viewMode: 1,
                            dragMode: 'move',
                            background: false,
                            autoCropArea: 1,
                            responsive: true,
                        });
                    }, 100);
                };
                reader.readAsDataURL(file);
            }

            input.addEventListener('change', () => { if (input.files[0]) handleFile(input.files[0]); });

            // Drag and drop
            dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.style.borderColor = '#6366f1'; dropzone.style.background = '#f5f3ff'; });
            dropzone.addEventListener('dragleave', () => { dropzone.style.borderColor = '#d1d5db'; dropzone.style.background = '#fafafa'; });
            dropzone.addEventListener('drop', e => {
                e.preventDefault();
                const file = e.dataTransfer.files[0];
                if (file) handleFile(file);
            });

            // Intercept form submission to send cropped blob
            document.getElementById('photoUploadForm').onsubmit = function (e) {
                if (!croppedBlob) return true; // Fallback to normal upload if no crop
                e.preventDefault();

                const formData = new FormData(this);
                // Replace the "photo" field with our cropped blob
                formData.set('photo', croppedBlob, 'profile_photo.jpg');

                uploadBtn.disabled = true;
                uploadBtn.innerHTML = '<div class="spinner"></div>Uploading...';

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': CSRF }
                })
                    .then(r => r.ok ? window.location.reload() : Promise.reject())
                    .catch(() => {
                        toast('Upload Failed', 'There was an error updating your photo.', false);
                        uploadBtn.disabled = false;
                        uploadBtn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>Upload Photo';
                    });

                return false;
            };
        })();

        /* ══════════════════════════
           DATA QUALITY SCANNER
        ══════════════════════════ */
        let DQ = { issues: [], ignored: new Set(), resolved: new Set(), filter: 'all', done: false };

        function runScan(force = false) {
            if (DQ.done && !force) return;
            DQ.done = false; DQ.issues = []; DQ.ignored.clear(); DQ.resolved.clear();
            show('dq-loading'); hide('dq-summary'); hide('dq-toolbar'); hide('dq-empty');
            document.getElementById('dq-list').innerHTML = '';
            fetch('{{ route("settings.dataquality.scan") }}', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
                .then(r => r.json())
                .then(d => { DQ.issues = d.issues || []; DQ.done = true; renderDQ(); })
                .catch(() => { hide('dq-loading'); document.getElementById('dq-list').innerHTML = '<div style="text-align:center;padding:2rem;color:#ef4444;font-size:13px">Scan failed. Please try again.</div>'; });
        }

        function renderDQ() {
            hide('dq-loading');
            const active = DQ.issues.filter(i => !DQ.resolved.has(i.id) && !allRecordsIgnored(i));
            const visible = active.filter(i => DQ.filter === 'all' || i.type === DQ.filter);
            const c = { high: 0, medium: 0, low: 0 };
            active.forEach(i => c[i.severity]++);
            document.getElementById('cnt-high').textContent = c.high;
            document.getElementById('cnt-med').textContent = c.medium;
            document.getElementById('cnt-low').textContent = c.low;
            document.getElementById('cnt-res').textContent = DQ.resolved.size + countAllIgnored();
            const badge = document.getElementById('dq-badge');
            const total = c.high + c.medium + c.low;
            if (total > 0) { badge.textContent = total; badge.className = 'snav-badge' + (c.high ? '' : ' warn'); badge.style.display = 'inline-block'; }
            else badge.style.display = 'none';
            show('dq-summary'); show('dq-toolbar');
            const list = document.getElementById('dq-list'); list.innerHTML = '';
            if (active.length === 0) { show('dq-empty'); return; } hide('dq-empty');
            if (visible.length === 0) { list.innerHTML = '<div style="text-align:center;padding:1.5rem;color:#9ca3af;font-size:13px">No issues match this filter.</div>'; return; }
            visible.forEach(issue => {
                const wrap = document.createElement('div');
                wrap.className = `dq-issue sev-${issue.severity}`; wrap.id = 'dqi-' + issue.id;
                const typeLabel = { duplicate: 'Duplicate', missing: 'Missing Data', inconsistent: 'Inconsistent' }[issue.type] || issue.type;
                const typeClass = { duplicate: 'type-duplicate', missing: 'type-missing', inconsistent: 'type-inconsistent' }[issue.type] || '';
                const dotColor = { high: '#ef4444', medium: '#f59e0b', low: '#3b82f6' }[issue.severity] || '#9ca3af';
                const visRecs = issue.records.filter(r => !DQ.ignored.has(r.id));
                wrap.innerHTML = `
            <div class="dq-issue-head" onclick="toggleDQ('${issue.id}')">
                <div class="dq-sev-dot" style="background:${dotColor}"></div>
                <span class="dq-type-badge ${typeClass}">${esc(typeLabel)}</span>
                <span class="dq-issue-title">${esc(issue.title)}</span>
                <span class="dq-issue-count">${visRecs.length} record${visRecs.length !== 1 ? 's' : ''}</span>
                <svg class="dq-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
            <div class="dq-issue-body">
                <div class="dq-desc">${esc(issue.description)}</div>
                ${visRecs.map(r => renderRec(issue, r)).join('')}
            </div>`;
                list.appendChild(wrap);
            });
        }

        function renderRec(issue, rec) {
            const chipClass = (rec.field_value === null || rec.field_value === '') ? 'missing' : (issue.type === 'inconsistent' ? 'info' : 'bad');
            const chip = rec.field_name ? `<code class="dq-field-chip ${chipClass}">${esc(rec.field_name)}: ${esc(rec.field_value ?? 'null')}</code>` : '';
            let actions = '';
            if (issue.type === 'duplicate') {
                actions = `<button class="btn-sm btn-sm-danger" onclick="deletePerm('${rec.permit_id}','${rec.label}','${issue.id}','${rec.id}')"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>Delete</button><button class="btn-sm" onclick="ignoreDQ('${issue.id}','${rec.id}')">Ignore</button>`;
            } else if (issue.type === 'missing') {
                actions = `<a href="${esc(rec.edit_url || '#')}" class="btn-sm btn-sm-warn"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z"/></svg>Fill In</a><button class="btn-sm" onclick="ignoreDQ('${issue.id}','${rec.id}')">Ignore</button>`;
            } else {
                actions = `<a href="${esc(rec.edit_url || '#')}" class="btn-sm btn-sm-warn">Review</a><button class="btn-sm" onclick="ignoreDQ('${issue.id}','${rec.id}')">Ignore</button>`;
            }
            return `<div class="dq-record" id="dqr-${rec.id}"><div class="dq-record-info"><div class="dq-record-title">${esc(rec.label)}</div><div class="dq-record-sub">${esc(rec.sub || '')}</div></div>${chip}<div class="dq-record-actions">${actions}</div></div>`;
        }

        function toggleDQ(id) { document.getElementById('dqi-' + id)?.classList.toggle('open'); }
        function filterDQ(type, btn) { DQ.filter = type; document.querySelectorAll('.dq-filter').forEach(b => b.classList.remove('active')); btn.classList.add('active'); renderDQ(); }
        function deletePerm(permitId, label, issueId, recId) {
            // Store pending delete info
            window._pendingDelete = { permitId, label, issueId, recId };
            openDeleteModal(
                'Delete this permit and its deceased record permanently?',
                label,
                confirmDelete
            );
        }

        function confirmDelete() {
            const { permitId, label, issueId, recId } = window._pendingDelete;
            document.getElementById('deleteModal').classList.remove('open');

            fetch(`/permits/${permitId}?source=data_quality`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } })

                .then(r => r.ok ? r.json() : Promise.reject())
                .then(() => {
                    // Remove the record row from DOM instantly
                    const rowEl = document.getElementById('dqr-' + recId);
                    if (rowEl) rowEl.remove();

                    DQ.ignored.add(recId);

                    // Update count in header
                    const issue = DQ.issues.find(i => i.id === issueId);
                    if (issue) {
                        const visRecs = issue.records.filter(r => !DQ.ignored.has(r.id));
                        const countEl = document.querySelector(`#dqi-${issueId} .dq-issue-count`);
                        if (countEl) countEl.textContent = `${visRecs.length} record${visRecs.length !== 1 ? 's' : ''}`;
                        if (visRecs.length === 0) {
                            const issueEl = document.getElementById('dqi-' + issueId);
                            if (issueEl) issueEl.remove();
                            DQ.resolved.add(issueId);
                        }
                    }

                    toast('Deleted', `Permit "${label}" and its deceased record were permanently removed.`);

                    // Background rescan — updates DQ.issues silently, then patches open panels
                    fetch('{{ route("settings.dataquality.scan") }}', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
                        .then(r => r.json())
                        .then(d => {
                            DQ.issues = d.issues || [];

                            // For each issue in the fresh data, update the count and records
                            DQ.issues.forEach(issue => {
                                const issueEl = document.getElementById('dqi-' + issue.id);
                                if (!issueEl) return; // already removed from DOM

                                const visRecs = issue.records.filter(r => !DQ.ignored.has(r.id));

                                // Update count
                                const countEl = issueEl.querySelector('.dq-issue-count');
                                if (countEl) countEl.textContent = `${visRecs.length} record${visRecs.length !== 1 ? 's' : ''}`;

                                // Replace body records only (keep open/closed state)
                                const body = issueEl.querySelector('.dq-issue-body');
                                if (body) {
                                    const desc = body.querySelector('.dq-desc');
                                    const descHtml = desc ? desc.outerHTML : '';
                                    body.innerHTML = descHtml + visRecs.map(r => renderRec(issue, r)).join('');
                                }

                                // Remove issue block if no records left
                                if (visRecs.length === 0) {
                                    issueEl.remove();
                                    DQ.resolved.add(issue.id);
                                }
                            });

                            // Update summary counters
                            const active = DQ.issues.filter(i => !DQ.resolved.has(i.id) && !allRecordsIgnored(i));
                            const c = { high: 0, medium: 0, low: 0 };
                            active.forEach(i => c[i.severity]++);
                            document.getElementById('cnt-high').textContent = c.high;
                            document.getElementById('cnt-med').textContent = c.medium;
                            document.getElementById('cnt-low').textContent = c.low;
                            document.getElementById('cnt-res').textContent = DQ.resolved.size + countAllIgnored();

                            if (active.length === 0) show('dq-empty');
                        })
                        .catch(() => { }); // silent fail — UI already updated
                })
                .catch(() => toast('Error', 'Could not delete — try again.', false));
        }
        function ignoreDQ(issueId, recId) {
            DQ.ignored.add(recId);

            // Remove the record row from DOM instantly
            const rowEl = document.getElementById('dqr-' + recId);
            if (rowEl) rowEl.remove();

            // Update the issue record count in the header
            const issue = DQ.issues.find(i => i.id === issueId);
            if (issue) {
                const visRecs = issue.records.filter(r => !DQ.ignored.has(r.id));
                const countEl = document.querySelector(`#dqi-${issueId} .dq-issue-count`);
                if (countEl) countEl.textContent = `${visRecs.length} record${visRecs.length !== 1 ? 's' : ''}`;

                if (visRecs.length === 0) {
                    const issueEl = document.getElementById('dqi-' + issueId);
                    if (issueEl) issueEl.remove();
                    DQ.resolved.add(issueId);
                }
            }

            // Update summary counters
            const active = DQ.issues.filter(i => !DQ.resolved.has(i.id) && !allRecordsIgnored(i));
            const c = { high: 0, medium: 0, low: 0 };
            active.forEach(i => c[i.severity]++);
            document.getElementById('cnt-high').textContent = c.high;
            document.getElementById('cnt-med').textContent = c.medium;
            document.getElementById('cnt-low').textContent = c.low;
            document.getElementById('cnt-res').textContent = DQ.resolved.size + countAllIgnored();

            // Check if all issues are resolved
            if (active.length === 0) {
                show('dq-empty');
            }

            toast('Ignored', 'This record will not be flagged again.');
        }
        function checkResolved(issueId) { const issue = DQ.issues.find(i => i.id === issueId); if (issue && issue.records.every(r => DQ.ignored.has(r.id))) DQ.resolved.add(issueId); }
        function allRecordsIgnored(issue) { return issue.records.length > 0 && issue.records.every(r => DQ.ignored.has(r.id)); }
        function countAllIgnored() { return DQ.issues.reduce((n, i) => n + i.records.filter(r => DQ.ignored.has(r.id)).length, 0); }
        function show(id) { const el = document.getElementById(id); if (el) el.style.display = ''; }
        function hide(id) { const el = document.getElementById(id); if (el) el.style.display = 'none'; }
        function esc(s) { return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
    </script>


    @include('admin.partials.delete-modal')
    @livewireScripts
</body>

</html>