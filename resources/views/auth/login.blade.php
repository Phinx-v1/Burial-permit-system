<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LGU Carmen — Civil Registrar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: url('{{ asset("images/lgu-building.jpg") }}') center/cover no-repeat;
            filter: blur(6px);
            transform: scale(1.05);
            z-index: -1;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(15, 31, 61, 0.45);
            z-index: -1;
        }

        .top-bar {
            width: 100%;
            max-width: 440px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeIn .4s ease .1s forwards;
        }

        .gov-seal {
            width: 46px;
            height: 46px;
            flex-shrink: 0;
        }

        .gov-text h1 {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            line-height: 1.3;
        }

        .gov-text p {
            font-size: 11px;
            color: rgba(255, 255, 255, .7);
            margin-top: 2px;
        }

        .card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 1.75rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06), 0 4px 16px rgba(0, 0, 0, .04);
            opacity: 0;
            animation: fadeIn .4s ease .2s forwards;
        }

        .card-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .card-header h2 {
            font-size: 17px;
            font-weight: 600;
            color: #111827;
        }

        .card-header p {
            font-size: 13px;
            color: #6b7280;
            margin-top: 3px;
        }

        .alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 6px;
            padding: 9px 12px;
            margin-bottom: 1.25rem;
            font-size: 13px;
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 5px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            display: flex;
            pointer-events: none;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            height: 41px;
            padding: 0 38px 0 35px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #111827;
            background: #fff;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        input:focus {
            border-color: #1a2744;
            box-shadow: 0 0 0 3px rgba(26, 39, 68, .07);
        }

        input.error {
            border-color: #dc2626;
        }

        .pw-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            display: flex;
            padding: 2px;
            transition: color .15s;
        }

        .pw-toggle:hover {
            color: #374151;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 1rem 0 1.25rem;
        }

        .remember input[type="checkbox"] {
            width: 14px;
            height: 14px;
            accent-color: #1a2744;
            cursor: pointer;
            flex-shrink: 0;
        }

        .remember label {
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }

        .btn-submit {
            width: 100%;
            height: 41px;
            background: #1a2744;
            color: #fff;
            border: none;
            border-radius: 7px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .15s, box-shadow .15s;
        }

        .btn-submit:hover {
            background: #243459;
            box-shadow: 0 4px 12px rgba(26, 39, 68, .18);
        }

        .btn-submit:active {
            opacity: .9;
        }

        .btn-submit:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .spinner {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, .3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .5s linear infinite;
            display: none;
        }

        .card-footer {
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            font-size: 11.5px;
            color: #9ca3af;
            line-height: 1.6;
        }

        .bottom-strip {
            margin-top: 18px;
            font-size: 11px;
            color: rgba(255, 255, 255, .5);
            opacity: 0;
            animation: fadeIn .4s ease .35s forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 480px) {
            .card {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="top-bar">
        <div style="position: relative; width: 46px; height: 46px; flex-shrink: 0;">
            <img src="{{ asset('images/carmen-seal.png') }}" alt="Municipality of Carmen Seal" class="gov-seal"
                style="border-radius:50%;object-fit:cover; position: absolute; inset: 0;">
            <svg width="46" height="46" viewBox="0 0 48 48" fill="none" style="position: absolute; inset: 0;">
                <circle cx="24" cy="24" r="22" stroke="#1a2744" stroke-width="1.5" />
                <circle cx="24" cy="24" r="15" stroke="#1a2744" stroke-width="1" />
                <circle cx="24" cy="24" r="4" fill="#1a2744" />
                <line x1="24" y1="9" x2="24" y2="15" stroke="#1a2744" stroke-width="1.5" stroke-linecap="round" />
                <line x1="24" y1="33" x2="24" y2="39" stroke="#1a2744" stroke-width="1.5" stroke-linecap="round" />
                <line x1="9" y1="24" x2="15" y2="24" stroke="#1a2744" stroke-width="1.5" stroke-linecap="round" />
                <line x1="33" y1="24" x2="39" y2="24" stroke="#1a2744" stroke-width="1.5" stroke-linecap="round" />
                <line x1="13" y1="13" x2="17.5" y2="17.5" stroke="#1a2744" stroke-width="1.2" stroke-linecap="round" />
                <line x1="30.5" y1="30.5" x2="35" y2="35" stroke="#1a2744" stroke-width="1.2" stroke-linecap="round" />
                <line x1="35" y1="13" x2="30.5" y2="17.5" stroke="#1a2744" stroke-width="1.2" stroke-linecap="round" />
                <line x1="17.5" y1="30.5" x2="13" y2="35" stroke="#1a2744" stroke-width="1.2" stroke-linecap="round" />
            </svg>
        </div>
        <div class="gov-text">
            <h1>Municipality of Carmen — Civil Registrar</h1>
            <p>Republic of the Philippines &nbsp;·&nbsp; Burial Permit Processing System</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Sign in to your account</h2>
            <p>Use your official credentials to continue</p>
        </div>

        @if($errors->any())
            <div class="alert">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="your@email.com"
                        class="{{ $errors->has('email') ? 'error' : '' }}" required autofocus autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0110 0v4" />
                        </svg>
                    </span>
                    <input type="password" id="password" name="password" placeholder="••••••••"
                        class="{{ $errors->has('password') ? 'error' : '' }}" required autocomplete="current-password">
                    <button type="button" class="pw-toggle" onclick="togglePw()" tabindex="-1">
                        <svg id="eyeIcon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="remember">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Keep me signed in</label>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <div class="spinner" id="spinner"></div>
                <span id="btnText">Sign In</span>
            </button>
        </form>

        <div class="card-footer">
            For account access, contact your system administrator.<br>
            Authorized government personnel only.
        </div>
    </div>

    <div class="bottom-strip">
        LGU Carmen &nbsp;·&nbsp; Municipal Civil Registrar &nbsp;·&nbsp; © {{ date('Y') }}
    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('password');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            document.getElementById('eyeIcon').innerHTML = show
                ? '<line x1="1" y1="1" x2="23" y2="23"/><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>'
                : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const rememberCheckbox = document.getElementById('remember');
            const loginForm = document.getElementById('loginForm');

            // 1. Auto-populate from localStorage on load
            const savedEmail = localStorage.getItem('r_email');
            const savedPassword = localStorage.getItem('r_password');

            if (savedEmail) {
                emailInput.value = savedEmail;
            }
            if (savedPassword) {
                passwordInput.value = savedPassword;
                rememberCheckbox.checked = true;
            }

            // 2. Form submission handler
            loginForm.addEventListener('submit', function () {
                // Save the email regardless of checkbox
                localStorage.setItem('r_email', emailInput.value);

                // Conditionally save password
                if (rememberCheckbox.checked) {
                    localStorage.setItem('r_password', passwordInput.value);
                } else {
                    localStorage.removeItem('r_password');
                }

                // Show spinner
                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                document.getElementById('spinner').style.display = 'block';
                document.getElementById('btnText').textContent = 'Signing in...';
            });
        });
    </script>
</body>

</html>