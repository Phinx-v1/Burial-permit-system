<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Geomap Analytics — Super Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
    <link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
    @viteReactRefresh
    @vite(['resources/js/geomap/main.tsx'])
    <style>
        :root {
            --navy: #0f1e3d;
            --navy-mid: #1a2f5e;
            --navy-light: #243459;
            --accent: #3b82f6;
            --surface: #ffffff;
            --surface-2: #f8fafc;
            --border: #e2e8f0;
            --text-1: #0f172a;
            --text-2: #475569;
            --text-3: #94a3b8;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface-2);
            color: var(--text-1);
            overflow: hidden;
            height: 100vh;
        }

        .main { height: 100%; width: 100%; display: flex; flex-direction: column; position: relative; }

        #geomap-analytics-root { flex: 1; position: relative; }

        /* Loader */
        #map-loader {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: #f8fafc; display: flex; flex-direction: column;
            align-items: center; justify-content: center; z-index: 1000;
        }
        .spinner {
            width: 40px; height: 40px; border: 3px solid #e2e8f0; border-top-color: #3b82f6;
            border-radius: 50%; animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

@include(auth()->user()->role === 'super_admin' ? 'superadmin.partials.sidebar' : 'admin.partials.sidebar')

<div class="main">
    <div id="geomap-analytics-root" 
         data-mode="super-admin"
         data-total="{{ $stats['total'] }}"
         data-occupied="{{ $stats['occupied'] }}"
         data-active="{{ $stats['active'] }}"
         data-expiring="{{ $stats['expiring'] }}"
         data-expired="{{ $stats['expired'] }}">
        
        <div id="map-loader">
            <div class="spinner"></div>
            <p style="margin-top: 1rem; font-size: 13px; color: #64748b; font-weight: 500;">Initializing Advanced Geomap Engine...</p>
        </div>
    </div>
</div>

</body>
</html>
