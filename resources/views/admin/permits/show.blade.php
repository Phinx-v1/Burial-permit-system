<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script>
        (function () { try { if (localStorage.getItem('lgu_dark') === '1') document.documentElement.classList.add('dark'); } catch (e) { } })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $permit->permit_number }} — LGU Carmen</title>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    @include('admin.partials.design-system')

    <style>
        /* Safety: ensure fade-up never gets stuck invisible */
        .fade-up {
            opacity: 1 !important;
            transform: none !important;
        }

        /* Topbar Breadcrumb alignment */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.75rem;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--navy);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            flex-direction: row !important;
            gap: 0.5rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-back {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            color: rgba(255, 255, 255, 0.55);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.15s;
        }

        .topbar-back:hover {
            color: #fff;
        }

        .topbar-sep {
            color: rgba(255, 255, 255, 0.2);
            font-size: 11px;
        }

        .topbar .topbar-title {
            color: #fff !important;
        }

        .topbar .role-pill {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
        }

        .topbar .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            color: #fff;
        }

        .topbar .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        html.dark .topbar {
            background: var(--navy);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* ── Page layout ── */
        .hero {
            background: var(--navy);
            border-radius: 16px;
            padding: 1.4rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -40px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(59, 130, 246, .1) 0%, transparent 65%);
            pointer-events: none;
        }

        .hero-eyebrow {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255, 255, 255, .3);
            text-transform: uppercase;
            letter-spacing: .1em;
            font-family: var(--mono);
        }

        .hero-no {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.03em;
            line-height: 1.1;
            margin-top: .2rem;
        }

        .hero-meta {
            font-size: 11px;
            color: rgba(255, 255, 255, .35);
            margin-top: .3rem;
            font-family: var(--mono);
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1.3fr 1fr 1fr;
            gap: 1rem;
        }

        .info-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .info-card-head {
            padding: .75rem 1.1rem;
            border-bottom: 1px solid var(--border-2);
            display: flex;
            align-items: center;
            gap: .5rem;
            background: var(--surface-2);
        }

        .info-card-title {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-3);
            text-transform: uppercase;
            letter-spacing: .08em;
            font-family: var(--mono);
        }

        .info-card-body {
            padding: 1.1rem;
            display: flex;
            flex-direction: column;
            gap: .85rem;
            flex: 1;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .fl {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-3);
            text-transform: uppercase;
            letter-spacing: .07em;
            font-family: var(--mono);
        }

        .fv {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-1);
        }

        .fv-lg {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-1);
            letter-spacing: -.02em;
        }

        .g2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }

        .g3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: .75rem;
        }

        .fee-box {
            background: var(--navy);
            border-radius: 10px;
            padding: 1.25rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .4rem;
            flex: 1;
            justify-content: center;
        }

        .fee-box-eyebrow {
            font-size: 9px;
            font-weight: 700;
            color: rgba(255, 255, 255, .3);
            text-transform: uppercase;
            letter-spacing: .1em;
            font-family: var(--mono);
        }

        .fee-box-type {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, .85);
        }

        .fee-box-divider {
            width: 28px;
            height: 1.5px;
            background: rgba(255, 255, 255, .12);
            border-radius: 2px;
        }

        .fee-box-amount {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.04em;
        }

        .fee-box-na {
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, .25);
        }

        .docs-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .docs-head {
            padding: .85rem 1.25rem;
            border-bottom: 1px solid var(--border-2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface-2);
        }

        .docs-head-left {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .docs-head-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-1);
        }

        .docs-head-sub {
            font-size: 11px;
            color: var(--text-3);
            font-family: var(--mono);
        }

        .docs-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 240px;
        }

        .docs-col-files {
            border-right: 1px solid var(--border-2);
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: .45rem;
            overflow-y: auto;
            max-height: 380px;
        }

        .docs-col-upload {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: .75rem;
            background: var(--surface-2);
        }

        .docs-col-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-3);
            text-transform: uppercase;
            letter-spacing: .07em;
            font-family: var(--mono);
            margin-bottom: .2rem;
        }

        .doc-item {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .55rem .75rem;
            border: 1px solid var(--border-2);
            border-radius: 8px;
            background: var(--surface);
            transition: all .15s;
            cursor: pointer;
        }

        .doc-item:hover {
            background: var(--accent-bg);
            border-color: #bfdbfe;
        }

        .doc-icon {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .doc-icon.img {
            background: #dbeafe;
        }

        .doc-icon.pdf {
            background: #fee2e2;
        }

        .doc-icon.word {
            background: #dcfce7;
        }

        .doc-icon.other {
            background: var(--surface-2);
        }

        .doc-info {
            flex: 1;
            min-width: 0;
        }

        .doc-name {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-1);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .doc-meta {
            font-size: 10px;
            color: var(--text-3);
            font-family: var(--mono);
            margin-top: 1px;
        }

        .doc-actions {
            display: flex;
            gap: .3rem;
            flex-shrink: 0;
        }

        .btn-doc {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 3px 8px;
            border-radius: 5px;
            border: 1px solid var(--border);
            font-family: 'DM Sans', sans-serif;
            font-size: 10px;
            color: var(--text-2);
            background: var(--surface);
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
            white-space: nowrap;
        }

        .btn-doc:hover {
            border-color: var(--navy);
            color: var(--navy);
        }

        .btn-doc-delete {
            border-color: #fca5a5;
            color: #dc2626;
        }

        .btn-doc-delete:hover {
            background: #fee2e2;
            border-color: var(--red);
        }

        .docs-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            color: var(--border);
            font-size: 12px;
            gap: .4rem;
            padding: 1.5rem;
        }

        .img-thumb {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid var(--border-2);
            cursor: zoom-in;
        }

        .dropzone {
            border: 2px dashed var(--border);
            border-radius: 10px;
            padding: 1.5rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all .15s;
            position: relative;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .dropzone:hover,
        .dropzone.drag-over {
            border-color: var(--accent);
            background: var(--accent-bg);
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
            width: 34px;
            height: 34px;
            background: var(--surface-2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto .5rem;
        }

        .dropzone-title {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-2);
        }

        .dropzone-sub {
            font-size: 11px;
            color: var(--text-3);
            margin-top: 3px;
        }

        .dropzone-file {
            font-size: 11px;
            font-weight: 600;
            color: var(--accent);
            margin-top: .4rem;
            display: none;
        }

        .btn-upload {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: .5rem 1rem;
            background: var(--navy);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: background .15s;
            width: 100%;
        }

        .btn-upload:hover {
            background: var(--navy-light);
        }

        .btn-upload:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .upload-note {
            font-size: 10px;
            color: var(--text-3);
            text-align: center;
            font-family: var(--mono);
        }

        .lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .88);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .lightbox.open {
            display: flex;
        }

        .lightbox img {
            max-width: 90vw;
            max-height: 90vh;
            border-radius: 10px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, .5);
        }

        .lightbox-close {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            background: rgba(255, 255, 255, .12);
            border: none;
            color: #fff;
            font-size: 22px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
        }

        .lightbox-close:hover {
            background: rgba(255, 255, 255, .22);
        }

        /* ── Dark mode overrides for page elements ── */
        html.dark .hero {
            background: #111827 !important;
        }

        html.dark .info-card {
            background: #1e2130 !important;
            border-color: #2d3148 !important;
        }

        html.dark .info-card-head {
            background: #181b29 !important;
            border-bottom-color: #2d3148 !important;
        }

        html.dark .info-card-title {
            color: #64748b !important;
        }

        html.dark .info-card-body {
            background: #1e2130 !important;
        }

        html.dark .fl {
            color: #64748b !important;
        }

        html.dark .fv {
            color: #e2e8f0 !important;
        }

        html.dark .fv-lg {
            color: #f1f5f9 !important;
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

        html.dark .docs-col-files {
            border-right-color: #2d3148 !important;
            background: #1e2130 !important;
        }

        html.dark .docs-col-upload {
            background: #181b29 !important;
        }

        html.dark .docs-col-label {
            color: #64748b !important;
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

        html.dark .btn-doc-delete {
            border-color: #7f1d1d !important;
            color: #fca5a5 !important;
        }

        html.dark .dropzone {
            border-color: #374151 !important;
        }

        html.dark .dropzone:hover {
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

        html.dark .btn-upload {
            background: #6366f1 !important;
        }

        html.dark .btn-upload:hover {
            background: #4f46e5 !important;
        }

        html.dark .upload-note {
            color: #64748b !important;
        }

        /* ══════════════════════════════
           EDIT MODAL — clean light mode
        ══════════════════════════════ */
        .em-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            pointer-events: none;
        }

        .em-overlay.open {
            display: flex;
            pointer-events: auto;
        }

        .em-box {
            background: #1a2744;
            border: none;
            border-radius: 14px;
            width: 100%;
            max-width: 660px;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 8px 48px rgba(0, 0, 0, .14), 0 2px 6px rgba(0, 0, 0, .06);
            animation: emIn .18s cubic-bezier(.34, 1.2, .64, 1);
            overflow: hidden;
        }

        @keyframes emIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(.98);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* Header */
        .em-header {
            padding: .85rem 1.1rem;
            margin: 0 !important;
            width: 100%;
            box-sizing: border-box;
            border-radius: 14px 14px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #1a2744;
            background: linear-gradient(135deg, #1a2744 0%, #243564 100%);
            flex-shrink: 0;
        }

        .em-header-left {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .em-header-icon {
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .em-title {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            line-height: 1.2;
        }

        .em-subtitle {
            font-size: 11px;
            color: rgba(255, 255, 255, .45);
            font-family: var(--mono);
            margin-top: 1px;
        }

        .em-close {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            color: rgba(255, 255, 255, .7);
            width: 26px;
            height: 26px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
            flex-shrink: 0;
        }

        .em-close:hover {
            background: rgba(255, 255, 255, .2);
            color: #fff;
        }

        .em-close:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
        }

        /* Scrollable body — thin scrollbar */

        .em-body {
            overflow-y: auto;
            flex: 1;
            padding: 1rem 1.1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background: #fff;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .em-body::-webkit-scrollbar {
            width: 5px;
        }

        .em-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .em-body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 99px;
        }

        .em-body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        html.dark .em-body::-webkit-scrollbar-thumb {
            background: #2d3148;
        }

        html.dark .em-body::-webkit-scrollbar-thumb:hover {
            background: #374151;
        }





        /* Sections */
        .em-section {
            display: flex;
            flex-direction: column;
            gap: .65rem;
        }

        .em-section-label {
            font-size: 9.5px;
            font-weight: 700;
            color: #6366f1;
            text-transform: uppercase;
            letter-spacing: .1em;
            font-family: var(--mono);
            padding-bottom: .4rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-bottom: .1rem;
        }

        /* Grids */
        .em-grid {
            display: grid;
            gap: .5rem;
        }

        .em-grid.g1 {
            grid-template-columns: 1fr;
        }

        .em-grid.g2 {
            grid-template-columns: 1fr 1fr;
        }

        .em-grid.g3 {
            grid-template-columns: 1fr 1fr 1fr;
        }

        /* Field */
        .em-field {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .em-label {
            font-size: 9.5px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .07em;
            font-family: var(--mono);
        }

        .em-input {
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: #0f172a;
            padding: .42rem .65rem;
            background: #f8fafc;
            border: 1px solid #e8ecf0;
            border-radius: 7px;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
            width: 100%;
        }

        .em-input:focus {
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
        }

        .em-input::placeholder {
            color: #c8d0db;
        }

        .em-input option {
            background: #fff;
            color: #0f172a;
        }

        /* Footer */
        .em-footer {
            padding: .75rem 1.1rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafbfc;
            flex-shrink: 0;
        }

        .em-change-badge {
            font-size: 11px;
            color: #cbd5e1;
            font-family: var(--mono);
            display: flex;
            align-items: center;
            gap: .35rem;
            transition: all .2s;
        }

        .em-change-badge.has-changes {
            color: #6366f1;
        }

        .em-change-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #6366f1;
            opacity: 0;
            transform: scale(0);
            transition: all .2s;
        }

        .em-change-badge.has-changes .em-change-dot {
            opacity: 1;
            transform: scale(1);
        }

        .em-actions {
            display: flex;
            gap: .45rem;
        }

        .em-btn-cancel {
            padding: .42rem .9rem;
            border-radius: 7px;
            border: 1px solid #e2e8f0;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            color: #64748b;
            background: #fff;
            cursor: pointer;
            transition: all .15s;
        }

        .em-btn-cancel:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #374151;
        }

        .em-btn-save {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: .42rem 1rem;
            border-radius: 7px;
            border: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
            background: #1a2744;
            cursor: pointer;
            transition: all .2s;
            box-shadow: 0 2px 6px rgba(26, 39, 68, .2);
        }

        .em-btn-save:hover:not(:disabled) {
            background: #243459;
            box-shadow: 0 4px 12px rgba(26, 39, 68, .3);
            transform: translateY(-1px);
        }

        .em-btn-save:disabled {
            opacity: .3;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Dark mode overrides for Edit Modal */
        html.dark .em-box {
            background: #1e2130;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        }

        html.dark .em-header {
            background: #111827;
            border-bottom: 1px solid #2d3148;
        }

        html.dark #editForm {
            background: #1e2130;
            border-color: #2d3148;
        }

        html.dark .em-body {
            background: #1e2130;
        }

        html.dark .em-section-label {
            border-bottom-color: #2d3148;
            color: #818cf8;
        }

        html.dark .em-footer {
            background: #181b29;
            border-top-color: #2d3148;
        }

        html.dark .em-input {
            background: #252840;
            border-color: #374151;
            color: #e2e8f0;
        }

        html.dark .em-input:focus {
            border-color: #6366f1;
            background: #1e2130;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }

        html.dark .em-btn-cancel {
            background: #252840;
            border-color: #374151;
            color: #cbd5e1;
        }

        html.dark .em-btn-cancel:hover {
            background: #1e2130;
            border-color: #64748b;
            color: #e2e8f0;
        }

        html.dark .em-btn-save {
            background: #6366f1;
        }

        html.dark .em-btn-save:hover:not(:disabled) {
            background: #4f46e5;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        html.dark .em-change-badge.has-changes {
            color: #818cf8;
        }

        html.dark .em-change-dot {
            background: #818cf8;
        }

        html.dark .em-close:hover {
            background: #7f1d1d;
            border-color: #991b1b;
            color: #fca5a5;
        }
    </style>
</head>

<body>

    @include(auth()->user()->role === 'super_admin' ? 'superadmin.partials.sidebar' : 'admin.partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div class="topbar-left">
                <a href="{{ route('permits.index') }}" class="topbar-back">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Permits
                </a>
                <span class="topbar-sep">/</span>
                <div class="topbar-title" style="font-weight: 700;">
                    {{ optional($permit->deceased)->full_name ?? 'Unknown Deceased' }}
                </div>
            </div>
            <div class="topbar-right">
                <span
                    class="role-pill">{{ auth()->user()->role === 'cemetery_manager' ? 'Cemetery Manager' : 'Admin' }}</span>
                @if(auth()->user()->role === 'admin')
                    <button type="button" class="btn btn-secondary" onclick="openEditModal()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            style="margin-right: 4px;">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Edit
                    </button>
                @endif
            </div>
        </div>


        <div class="content">

            @php
                $settingsPath = storage_path('app/settings.json');
                $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : [];

                $defaultFees = [
                    'cemented' => ['tomb' => 1000, 'permit' => 20, 'maint' => 100, 'app' => 20],
                    'niche_1st' => ['tomb' => 8000, 'permit' => 20, 'maint' => 100, 'app' => 20],
                    'niche_2nd' => ['tomb' => 6600, 'permit' => 20, 'maint' => 100, 'app' => 20],
                    'niche_3rd' => ['tomb' => 5700, 'permit' => 20, 'maint' => 100, 'app' => 20],
                    'niche_4th' => ['tomb' => 5300, 'permit' => 20, 'maint' => 100, 'app' => 20],
                    'bone_niches' => ['tomb' => 5000, 'permit' => 20, 'maint' => 100, 'app' => 20],
                ];

                $feeLabels = [
                    'cemented' => 'Cemented',
                    'niche_1st' => '1st Floor Niche',
                    'niche_2nd' => '2nd Floor Niche',
                    'niche_3rd' => '3rd Floor Niche',
                    'niche_4th' => '4th Floor Niche',
                    'bone_niches' => 'Bone Niches',
                ];

                $ptype = $permit->permit_type ?? 'cemented';
                $rawFees = $settings['fees'][$ptype] ?? $defaultFees[$ptype] ?? $defaultFees['cemented'];
                $totalFee = ($rawFees['tomb'] ?? 0) + ($rawFees['permit'] ?? 0) + ($rawFees['maint'] ?? 0) + ($rawFees['app'] ?? 0);

                $feeInfo = [
                    'label' => $feeLabels[$ptype] ?? ucfirst(str_replace(['_', '-'], ' ', $ptype)),
                    'amount' => '₱' . number_format($totalFee, 2)
                ];
            @endphp

            @php
                $cs = $permit->status;
            @endphp

            {{-- HERO --}}
            <div class="hero">
                <div>
                    <div class="hero-eyebrow">Burial Permit</div>
                    <div class="hero-no">{{ $permit->permit_number }}</div>
                    <div class="hero-meta">Issued {{ $permit->created_at->format('F d, Y') }} ·
                        {{ $permit->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="hero-actions">
                    @if($cs === 'expired')
                        <span class="badge badge-red"><span class="badge-dot"></span>Expired</span>
                    @elseif($cs === 'expiring')
                        <span class="badge badge-orange"><span class="badge-dot"></span>Expiring Soon</span>
                    @else
                        <span class="badge badge-green"><span class="badge-dot"></span>Active</span>
                    @endif

                    <a href="{{ route('permits.print', $permit) }}" class="btn btn-ghost">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="6 9 6 2 18 2 18 9" />
                            <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                            <rect x="6" y="14" width="12" height="8" />
                        </svg>
                        Print Permit
                    </a>

                    @if($cs === 'expired' || $cs === 'expiring')
                        <form id="renew-form-main" method="POST" action="{{ route('permits.renew', $permit) }}"
                            style="display:contents">
                            @csrf
                            <button type="button" class="btn btn-warn"
                                onclick="openRenewModal('{{ $permit->permit_number }}', '{{ str_replace('\'', '\\\'', $permit->deceased->full_name) }}', 'renew-form-main')">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M3 12a9 9 0 109-9" />
                                    <polyline points="3 3 3 9 9 9" />
                                </svg>
                                Renew Permit
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('permits.destroy', $permit) }}" style="display:contents"
                        id="deletePermitForm_{{ $permit->id }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-danger"
                            onclick="openDeleteModal('Delete this permit permanently?', '{{ $permit->permit_number }}', 'deletePermitForm_{{ $permit->id }}')">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6l-1 14H6L5 6" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            {{-- INFO GRID --}}
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-card-head">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        <span class="info-card-title">Deceased</span>
                    </div>
                    <div class="info-card-body">
                        @php
                            $assigned = optional($permit->deceased)->getAssignedLocation();
                        @endphp
                        <div class="field">
                            <div class="fl">Full Name</div>
                            <div class="fv-lg">{{ optional($permit->deceased)->first_name }}
                                {{ optional($permit->deceased)->last_name }}
                            </div>
                        </div>
                        <div class="g3">
                            <div class="field">
                                <div class="fl">Nationality</div>
                                <div class="fv">{{ optional($permit->deceased)->nationality ?: '—' }}</div>
                            </div>
                            <div class="field">
                                <div class="fl">Age</div>
                                <div class="fv">{{ optional($permit->deceased)->age ?: '—' }}</div>
                            </div>
                            <div class="field">
                                <div class="fl">Sex</div>
                                <div class="fv">{{ optional($permit->deceased)->sex ?: '—' }}</div>
                            </div>
                        </div>
                        <div class="g2">
                            <div class="field">
                                <div class="fl">Date of Death</div>
                                <div class="fv">
                                    {{ optional(optional($permit->deceased)->date_of_death)->format('M d, Y') ?? '—' }}
                                </div>
                            </div>
                            <div class="field">
                                <div class="fl">Kind of Burial</div>
                                <div class="fv">{{ optional($permit->deceased)->kind_of_burial ?: '—' }}</div>
                            </div>
                            <div class="g2">
                                <div class="field">
                                    <div class="fl">Place of Death</div>
                                    <div class="fv">{{ optional($permit->deceased)->place_of_death ?: '—' }}</div>
                                </div>
                                <div class="field">
                                    <div class="fl">Residence</div>
                                    <div class="fv">{{ optional($permit->deceased)->address ?: '—' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Map Assignment Button --}}
                        <div style="margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px dashed var(--border-2);">
                            @if($assigned)
                                <a href="{{ route('cemetery.map') }}?search={{ urlencode($permit->permit_number) }}"
                                    id="view-on-map-btn" style="
                                                   display: flex; align-items: center; justify-content: center; gap: 8px;
                                                   width: 100%; box-sizing: border-box;
                                                   padding: 0.8rem 1rem;
                                                   background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 60%, #3b82f6 100%);
                                                   color: #fff;
                                                   border: none;
                                                   border-radius: 12px;
                                                   font-family: 'DM Sans', sans-serif;
                                                   font-size: 13px;
                                                   font-weight: 700;
                                                   letter-spacing: 0.02em;
                                                   text-decoration: none;
                                                   cursor: pointer;
                                                   box-shadow: 0 4px 16px rgba(37, 99, 235, 0.35), 0 1px 3px rgba(0,0,0,0.12);
                                                   transition: transform .15s ease, box-shadow .15s ease;
                                               "
                                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(37,99,235,0.45), 0 1px 3px rgba(0,0,0,0.12)'"
                                    onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 16px rgba(37,99,235,0.35), 0 1px 3px rgba(0,0,0,0.12)'">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                    <span>View on Map</span>
                                    <span
                                        style="opacity:0.7; font-size:11px; font-family:'DM Mono',monospace; margin-left: 2px;">—
                                        {{ $assigned['label'] }}</span>
                                </a>
                            @else
                                <div style="
                                                display: flex; align-items: center; justify-content: center; gap: 8px;
                                                width: 100%; box-sizing: border-box;
                                                padding: 0.75rem 1rem;
                                                border-radius: 12px;
                                                border: 1.5px dashed var(--border);
                                                color: var(--text-3);
                                                font-size: 12px;
                                                font-weight: 600;
                                                font-family: 'DM Sans', sans-serif;
                                                background: var(--surface-2);
                                            ">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.75">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                    Not Assigned to Cemetery Map
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-head">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                        </svg>
                        <span class="info-card-title">Permit Fee</span>
                    </div>
                    <div class="info-card-body">
                        <div class="fee-box">
                            <div class="fee-box-eyebrow">Selected Type</div>
                            <div class="fee-box-type">{{ $feeInfo['label'] }}</div>
                            <div class="fee-box-divider"></div>
                            @if($feeInfo['amount'])
                                <div class="fee-box-amount">{{ $feeInfo['amount'] }}</div>
                            @else
                                <div class="fee-box-na">No fee on record</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-head">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        <span class="info-card-title">Application Info</span>
                    </div>
                    <div class="info-card-body">
                        <div class="field">
                            <div class="fl">Requestor</div>
                            <div class="fv">{{ $permit->applicant_name ?: '—' }}</div>
                        </div>
                        <div class="g2">
                            <div class="field">
                                <div class="fl">Contact</div>
                                <div class="fv">{{ $permit->applicant_contact ?: '—' }}</div>
                            </div>
                            <div class="field">
                                <div class="fl">Processed By</div>
                                <div class="fv">{{ optional($permit->processedBy)->name ?? 'Admin' }}</div>
                            </div>
                        </div>
                        @if($permit->expiry_date)
                            <div class="field">
                                <div class="fl">Expiry Date</div>
                                <div class="fv"
                                    style="{{ $cs === 'expired' ? 'color:var(--red);font-weight:700' : ($cs === 'expiring' ? 'color:#f59e0b;font-weight:700' : '') }}">
                                    {{ $permit->expiry_date->format('F d, Y') }}
                                    @if($cs === 'expired') <span style="font-size:11px;font-family:var(--mono)"> —
                                        Expired</span>
                                    @elseif($cs === 'expiring') <span style="font-size:11px;font-family:var(--mono)"> —
                                        Expiring Soon</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if($permit->or_number)
                            <div class="field">
                                <div class="fl">O.R. Number</div>
                                <div class="fv" style="font-family:var(--mono);color:var(--accent);font-weight:600">
                                    {{ $permit->or_number }}
                                </div>
                            </div>
                        @endif
                        @if($permit->remarks)
                            <div class="field">
                                <div class="fl">Remarks</div>
                                <div class="fv" style="font-size:12px">{{ $permit->remarks }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- DOCUMENTS --}}
            <div class="docs-card">
                <div class="docs-head">
                    <div class="docs-head-left">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        <span class="docs-head-title">Attached Documents</span>
                        <span class="docs-head-sub">— {{ $permit->documents->count() }}
                            file{{ $permit->documents->count() !== 1 ? 's' : '' }}</span>
                    </div>
                    <span style="font-size:11px;color:var(--text-3);font-family:var(--mono)">Optional · photos,
                        certificates, receipts, IDs</span>
                </div>
                <div class="docs-body">
                    <div class="docs-col-files">
                        <div class="docs-col-label">Uploaded Files</div>
                        @forelse($permit->documents as $doc)
                            @php
                                $ext = $doc->file_name ? strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION)) : '';
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                $isPdf = $ext === 'pdf';
                                $isWord = in_array($ext, ['doc', 'docx']);
                                $iconClass = $isImage ? 'img' : ($isPdf ? 'pdf' : ($isWord ? 'word' : 'other'));
                                $iconColor = match ($iconClass) { 'img' => '#3b82f6', 'pdf' => '#ef4444', 'word' => '#16a34a', default => '#94a3b8'};
                                $viewUrl = route('documents.download', $doc);
                            @endphp
                            <div class="doc-item"
                                onclick="openDoc('{{ $viewUrl }}','{{ $isImage ? 'image' : 'file' }}','{{ addslashes($doc->file_name) }}')"
                                title="Click to view">
                                @if($isImage)
                                    <img src="{{ $viewUrl }}" class="img-thumb" alt="{{ $doc->file_name }}"
                                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                    <div class="doc-icon img" style="display:none"><svg width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor }}" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg></div>
                                @else
                                    <div class="doc-icon {{ $iconClass }}"><svg width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="{{ $iconColor }}" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                        </svg></div>
                                @endif
                                <div class="doc-info">
                                    <div class="doc-name">{{ $doc->file_name }}</div>
                                    <div class="doc-meta">{{ strtoupper($ext) }} ·
                                        {{ $doc->created_at->format('M d, Y') }}@if($doc->uploadedBy) ·
                                        {{ $doc->uploadedBy->name }}@endif
                                    </div>
                                </div>
                                <div class="doc-actions" onclick="event.stopPropagation()">
                                    <a href="{{ $viewUrl }}" class="btn-doc" target="_blank">↓ Download</a>
                                    <form method="POST" action="{{ route('documents.destroy', $doc) }}"
                                        id="deleteDocForm_{{ $doc->id }}" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-doc btn-doc-delete"
                                            onclick="openDeleteModal('Delete this file permanently?', '{{ addslashes($doc->file_name) }}', 'deleteDocForm_{{ $doc->id }}')">✕
                                            Delete</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="docs-empty">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg>
                                <span>No files yet</span>
                            </div>
                        @endforelse
                    </div>
                    <div class="docs-col-upload">
                        <div class="docs-col-label">Add a File</div>
                        <form id="uploadForm" method="POST" action="{{ route('documents.upload', $permit) }}"
                            enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:.75rem;flex:1">
                            @csrf
                            <div class="dropzone" id="dropzone"
                                ondragover="event.preventDefault();this.classList.add('drag-over')"
                                ondragleave="this.classList.remove('drag-over')" ondrop="handleDrop(event)">
                                <input type="file" name="document" id="docInput"
                                    accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx" onchange="handleFile(this)">
                                <div class="dropzone-icon"><svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                        stroke="#94a3b8" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg></div>
                                <div class="dropzone-title">Click to upload or drag & drop</div>
                                <div class="dropzone-sub">JPG, PNG, PDF, Word — max 10MB</div>
                                <div class="dropzone-file" id="fileName"></div>
                            </div>
                            <button type="submit" class="btn-upload" id="uploadBtn" disabled>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                                Attach File
                            </button>
                            <span class="upload-note">Stored privately · only visible to admins</span>
                        </form>
                    </div>
                </div>
            </div>


            {{-- RENEWAL HISTORY TABLE --}}
            @if($renewalHistory->isNotEmpty())
                <div class="docs-card">
                    <div class="docs-head">
                        <div class="docs-head-left">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M3 12a9 9 0 109-9" />
                                <polyline points="3 3 3 9 9 9" />
                            </svg>
                            <span class="docs-head-title">Renewal History</span>
                            <span class="docs-head-sub">— {{ $renewalHistory->count() }}
                                renewal{{ $renewalHistory->count() !== 1 ? 's' : '' }}</span>
                        </div>
                        <span style="font-size:11px;color:var(--text-3);font-family:var(--mono)">Full audit trail</span>
                    </div>
                    <div style="overflow-x:auto">
                        <table style="width:100%;border-collapse:collapse">
                            <thead>
                                <tr>
                                    <th
                                        style="padding:.6rem 1.25rem;font-size:10px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.07em;text-align:left;background:var(--surface-2);border-bottom:1px solid var(--border-2);white-space:nowrap">
                                        #</th>
                                    <th
                                        style="padding:.6rem 1.25rem;font-size:10px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.07em;text-align:left;background:var(--surface-2);border-bottom:1px solid var(--border-2);white-space:nowrap">
                                        Date Renewed</th>
                                    <th
                                        style="padding:.6rem 1.25rem;font-size:10px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.07em;text-align:left;background:var(--surface-2);border-bottom:1px solid var(--border-2);white-space:nowrap">
                                        Previous Expiry</th>
                                    <th
                                        style="padding:.6rem 1.25rem;font-size:10px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.07em;text-align:left;background:var(--surface-2);border-bottom:1px solid var(--border-2);white-space:nowrap">
                                        New Expiry</th>
                                    <th
                                        style="padding:.6rem 1.25rem;font-size:10px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.07em;text-align:left;background:var(--surface-2);border-bottom:1px solid var(--border-2);white-space:nowrap">
                                        Renewed By</th>
                                    <th
                                        style="padding:.6rem 1.25rem;font-size:10px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.07em;text-align:left;background:var(--surface-2);border-bottom:1px solid var(--border-2);white-space:nowrap">
                                        Remarks / Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($renewalHistory as $i => $log)
                                    @php
                                        $old = is_string($log->old_values) ? json_decode($log->old_values, true) : (array) ($log->old_values ?? []);
                                        $new = is_string($log->new_values) ? json_decode($log->new_values, true) : (array) ($log->new_values ?? []);
                                        $prevExpiry = isset($old['expiry_date']) ? \Carbon\Carbon::parse($old['expiry_date'])->format('M d, Y') : '—';
                                        $newExpiry = isset($new['expiry_date']) ? \Carbon\Carbon::parse($new['expiry_date'])->format('M d, Y') : '—';
                                        $renewedBy = $log->user?->name ?? 'System';
                                    @endphp
                                    <tr style="border-bottom:1px solid var(--border-2);transition:background .12s"
                                        onmouseover="this.style.background='var(--surface-2)'"
                                        onmouseout="this.style.background=''">
                                        <td style="padding:.75rem 1.25rem;vertical-align:middle">
                                            <span
                                                style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:#fef3c7;color:#d97706;font-size:10px;font-weight:700;font-family:var(--mono)">
                                                {{ $renewalHistory->count() - $i }}
                                            </span>
                                        </td>
                                        <td style="padding:.75rem 1.25rem;vertical-align:middle">
                                            <div style="font-size:12.5px;font-weight:600;color:var(--text-1)">
                                                {{ $log->created_at->format('M d, Y') }}
                                            </div>
                                            <div
                                                style="font-size:10.5px;color:var(--text-3);font-family:var(--mono);margin-top:2px">
                                                {{ $log->created_at->format('h:i A') }} ·
                                                {{ $log->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td style="padding:.75rem 1.25rem;vertical-align:middle">
                                            <span
                                                style="display:inline-block;font-size:11.5px;color:#dc2626;font-family:var(--mono);background:#fee2e2;padding:3px 10px;border-radius:5px;font-weight:600;border:1px solid #fca5a5">
                                                {{ $prevExpiry }}
                                            </span>
                                        </td>
                                        <td style="padding:.75rem 1.25rem;vertical-align:middle">
                                            <span
                                                style="display:inline-block;font-size:11.5px;color:#16a34a;font-family:var(--mono);background:#dcfce7;padding:3px 10px;border-radius:5px;font-weight:600;border:1px solid #86efac">
                                                {{ $newExpiry }}
                                            </span>
                                        </td>
                                        <td style="padding:.75rem 1.25rem;vertical-align:middle">
                                            <div style="display:inline-flex;align-items:center;gap:6px">
                                                <div
                                                    style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#fff;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                                    {{ strtoupper(substr($renewedBy, 0, 1)) }}
                                                </div>
                                                <span
                                                    style="font-size:12px;color:var(--text-1);font-weight:500">{{ $renewedBy }}</span>
                                            </div>
                                        </td>
                                        <td style="padding:.75rem 1.25rem;vertical-align:middle;max-width:280px">
                                            <span
                                                style="font-size:12px;color:var(--text-2);display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                                                title="{{ $log->description ?? '' }}">
                                                {{ $log->description ?? '—' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="lightbox" id="lightbox" onclick="closeLightbox()">
                <button class="lightbox-close" onclick="closeLightbox()">×</button>
                <img id="lightboxImg" src="" alt="">
            </div>

        </div>
    </div>



    @if(session('success'))
        <div class="toast show" id="sToast">
            <div class="toast-body">
                <div class="toast-icon green"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                        stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12" />
                    </svg></div>
                <div>
                    <div class="toast-title">Success</div>
                    <div class="toast-msg">{{ session('success') }}</div>
                </div>
            </div>
            <div class="toast-bar green"></div>
        </div>
    @endif
    @if(session('error'))
        <div class="toast show" id="errToast">
            <div class="toast-body">
                <div class="toast-icon red">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div>
                    <div class="toast-title" style="color:#dc2626">Not Eligible</div>
                    <div class="toast-msg">{{ session('error') }}</div>
                </div>
            </div>
            <div class="toast-bar red"></div>
        </div>
    @endif

    @if($errors->has('sms'))
        <div class="toast show" id="smsErrToast">
            <div class="toast-body">
                <div class="toast-icon red"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626"
                        stroke-width="2.5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg></div>
                <div>
                    <div class="toast-title" style="color:var(--red)">SMS Failed</div>
                    <div class="toast-msg">{{ $errors->first('sms') }}</div>
                </div>
            </div>
            <div class="toast-bar red"></div>
        </div>
    @endif

    <script>
        function handleFile(input) {
            const file = input.files[0]; if (!file) return;
            document.getElementById('fileName').style.display = 'block';
            document.getElementById('fileName').textContent = '📎 ' + file.name;
            document.getElementById('uploadBtn').disabled = false;
        }
        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('dropzone').classList.remove('drag-over');
            const file = e.dataTransfer.files[0]; if (!file) return;
            const input = document.getElementById('docInput');
            const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
            handleFile(input);
        }
        document.getElementById('uploadForm').addEventListener('submit', function () {
            document.getElementById('uploadBtn').disabled = true;
            document.getElementById('uploadBtn').textContent = 'Uploading…';
        });
        function openDoc(url, type, name) {
            if (type === 'image') {
                document.getElementById('lightboxImg').src = url;
                document.getElementById('lightboxImg').alt = name;
                document.getElementById('lightbox').classList.add('open');
                document.body.style.overflow = 'hidden';
            } else { window.open(url, '_blank'); }
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeLightbox(); closeEditModal(); } });
        (function () {
            const s = document.getElementById('sToast');
            if (s) setTimeout(() => s.classList.remove('show'), 5000);
            const e = document.getElementById('smsErrToast');
            if (e) setTimeout(() => e.classList.remove('show'), 6000);
        })();
    </script>

    {{-- EDIT MODAL --}}
    <div class="em-overlay" id="editModal">
        <div class="em-box">

            {{-- Header --}}
            <div class="em-header">
                <div class="em-header-left">
                    <div class="em-header-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#a5b4fc" stroke-width="2.5">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 013 3L8 19l-4 1 1-4L16.5 3.5z" />
                        </svg>
                    </div>
                    <div>
                        <div class="em-title">Edit Permit</div>
                        <div class="em-subtitle">{{ $permit->permit_number }}</div>
                    </div>
                </div>
                <button class="em-close" onclick="closeEditModal()" title="Close">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('permits.update', $permit) }}" id="editForm"
                style="display:flex; flex-direction:column; flex:1; overflow:hidden; background:#fff; border-left:1px solid #e2e8f0; border-right:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0; border-radius:0 0 14px 14px;">
                @csrf @method('PUT')

                {{-- Scrollable body --}}
                <div class="em-body">

                    {{-- DECEASED INFO --}}
                    <div class="em-section">
                        <div class="em-section-label">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                            </svg>
                            Deceased Info
                        </div>
                        <div class="em-grid g2">
                            <div class="em-field">
                                <label class="em-label">First Name</label>
                                <input class="em-input" name="first_name"
                                    value="{{ optional($permit->deceased)->first_name }}" required
                                    placeholder="First name">
                            </div>
                            <div class="em-field">
                                <label class="em-label">Last Name</label>
                                <input class="em-input" name="last_name"
                                    value="{{ optional($permit->deceased)->last_name }}" required
                                    placeholder="Last name">
                            </div>
                        </div>
                        <div class="em-grid g3">
                            <div class="em-field">
                                <label class="em-label">Age</label>
                                <input class="em-input" name="age" type="number" min="0" max="150"
                                    value="{{ optional($permit->deceased)->age }}" placeholder="Age">
                            </div>
                            <div class="em-field">
                                <label class="em-label">Sex</label>
                                <select class="em-input" name="sex">
                                    <option value="">— Select —</option>
                                    <option value="Male" {{ optional($permit->deceased)->sex === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ optional($permit->deceased)->sex === 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="em-field">
                                <label class="em-label">Nationality</label>
                                <input class="em-input" name="nationality"
                                    value="{{ optional($permit->deceased)->nationality }}" placeholder="Nationality">
                            </div>
                        </div>
                        <div class="em-grid g2">
                            <div class="em-field">
                                <label class="em-label">Date of Death</label>
                                <input class="em-input" name="date_of_death" type="date"
                                    value="{{ optional(optional($permit->deceased)->date_of_death)->format('Y-m-d') }}">
                            </div>
                            <div class="em-field">
                                <label class="em-label">Kind of Burial</label>
                                <input class="em-input" name="kind_of_burial" value="{{ $permit->kind_of_burial }}"
                                    placeholder="e.g. Ground, Niche">
                            </div>
                            <div class="em-field">
                                <label class="em-label">Place of Death</label>
                                <input class="em-input" name="place_of_death"
                                    value="{{ optional($permit->deceased)->place_of_death }}"
                                    placeholder="e.g. Carmen, Davao del Norte">
                            </div>
                            <div class="em-field">
                                <label class="em-label">Residence / Address</label>
                                <input class="em-input" name="address"
                                    value="{{ optional($permit->deceased)->address }}"
                                    placeholder="e.g. Brgy. Poblacion">
                            </div>
                        </div>
                    </div>

                    {{-- PERMIT DETAILS --}}
                    <div class="em-section">
                        <div class="em-section-label">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="3" width="18" height="14" rx="2" />
                                <line x1="3" y1="9" x2="21" y2="9" />
                            </svg>
                            Permit Details
                        </div>
                        <div class="em-grid g2">
                            <div class="em-field">
                                <label class="em-label">Permit Type</label>
                                <select class="em-input" name="permit_type">
                                    @foreach(['cemented' => 'Cemented (₱1,000)', 'niche_1st' => '1st Floor Niche (₱8,000)', 'niche_2nd' => '2nd Floor Niche (₱6,600)', 'niche_3rd' => '3rd Floor Niche (₱5,700)', 'niche_4th' => '4th Floor Niche (₱5,300)', 'bone_niches' => 'Bone Niches (₱5,000)'] as $val => $lbl)
                                        <option value="{{ $val }}" {{ $permit->permit_type === $val ? 'selected' : '' }}>
                                            {{ $lbl }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="em-field">
                                <label class="em-label">Expiry Date</label>
                                <input class="em-input" name="expiry_date" type="date"
                                    value="{{ optional($permit->expiry_date)->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    {{-- APPLICANT --}}
                    <div class="em-section">
                        <div class="em-section-label">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            Applicant / Requestor Info
                        </div>
                        <div class="em-grid g2">
                            <div class="em-field">
                                <label class="em-label">Requestor Name</label>
                                <input class="em-input" name="applicant_name" value="{{ $permit->applicant_name }}"
                                    placeholder="Full name">
                            </div>
                            <div class="em-field">
                                <label class="em-label">Contact Number</label>
                                <input class="em-input" name="applicant_contact"
                                    value="{{ $permit->applicant_contact }}" placeholder="e.g. 09123456789">
                            </div>
                        </div>
                        <div class="em-grid g1">
                            <div class="em-field">
                                <label class="em-label">Requestor Address</label>
                                <input class="em-input" name="applicant_address"
                                    value="{{ $permit->applicant_address }}" placeholder="e.g. Brgy. Sto. Niño, Carmen">
                            </div>
                        </div>
                        <div class="em-grid g1">
                            <div class="em-field">
                                <label class="em-label">Official Receipt (O.R.) Number</label>
                                <input class="em-input" name="or_number" value="{{ $permit->or_number }}"
                                    placeholder="e.g. 1234567">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="em-footer">
                    <div class="em-change-badge" id="emChangeBadge">
                        <div class="em-change-dot"></div>
                        <span id="emChangeText">No changes</span>
                    </div>
                    <div class="em-actions">
                        <button type="button" class="em-btn-cancel" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="em-btn-save" id="emSaveBtn" disabled>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>




    <script>
        function openEditModal() {
            document.getElementById('editModal').classList.add('open');
            document.body.style.overflow = 'hidden';
            emInitBaseline();
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.getElementById('editModal').addEventListener('click', function (e) {
            if (e.target === this) closeEditModal();
        });

        let emBaseline = {};

        function emInitBaseline() {
            emBaseline = {};
            document.querySelectorAll('#editForm .em-input').forEach(el => {
                emBaseline[el.name] = el.value;
            });
            emCheckChanges();
        }

        function emCheckChanges() {
            let changed = 0;
            document.querySelectorAll('#editForm .em-input').forEach(el => {
                if ((el.value.trim()) !== ((emBaseline[el.name] || '').trim())) changed++;
            });
            const btn = document.getElementById('emSaveBtn');
            const badge = document.getElementById('emChangeBadge');
            const text = document.getElementById('emChangeText');
            if (changed > 0) {
                btn.disabled = false;
                badge.classList.add('has-changes');
                text.textContent = changed + ' field' + (changed > 1 ? 's' : '') + ' changed';
            } else {
                btn.disabled = true;
                badge.classList.remove('has-changes');
                text.textContent = 'No changes';
            }
        }

        document.querySelectorAll('#editForm .em-input').forEach(el => {
            el.addEventListener('input', emCheckChanges);
            el.addEventListener('change', emCheckChanges);
        });

        const err = document.getElementById('errToast');
        if (err) setTimeout(() => err.classList.remove('show'), 6000);

        @if(session('open_edit'))
            setTimeout(() => openEditModal(), 300);
        @endif
    </script>

    {{-- FLOATING EDIT BUTTON --}}
    <button onclick="openEditModal()" style="
    position:fixed; bottom:1.75rem; right:1.75rem;
    width:52px; height:52px; border-radius:14px;
    background:var(--navy); border:none; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 4px 20px rgba(26,39,68,.35);
    transition:all .2s; z-index:50;
" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5">
            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
        </svg>
    </button>



    @include('admin.partials.renewal-modal')
    @include('admin.partials.delete-modal')
</body>

</html>