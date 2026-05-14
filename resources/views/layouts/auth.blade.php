<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <base href="{{ url('/') }}/"/>
    <title>@yield('title', 'Login') - {{ app_setting('app_name', config('app.name')) }}</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="shortcut icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📚</text></svg>"/>
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Nunito:wght@400;600;700;900&family=Fredoka+One&display=swap" rel="stylesheet"/>
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
    <style>
        :root {
            --comic-cream: #FFF8F0;
            --comic-orange: #FF6B35;
            --comic-dark: #1A1A2E;
            --comic-blue: #4ECDC4;
            --comic-yellow: #FFE66D;
            --comic-red: #FF3366;
            --comic-shadow: #000;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif !important;
            background: var(--comic-dark) !important;
        }

        /* ── Comic Login Wrapper ── */
        .comic-auth-wrapper {
            min-height: 100vh;
            display: flex;
            position: relative;
            overflow: hidden;
        }

        /* ── Left Panel (Brand) ── */
        .comic-brand-panel {
            flex: 0 0 45%;
            background: var(--comic-dark);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .brand-bg-pattern {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255,107,53,0.25) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(78,205,196,0.2) 0%, transparent 50%),
                repeating-linear-gradient(0deg, transparent, transparent 50px, rgba(255,255,255,0.03) 50px, rgba(255,255,255,0.03) 51px),
                repeating-linear-gradient(90deg, transparent, transparent 50px, rgba(255,255,255,0.03) 50px, rgba(255,255,255,0.03) 51px);
        }
        .brand-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 40px;
        }
        .brand-logo {
            font-size: 5rem;
            display: block;
            margin-bottom: 20px;
            animation: bounceLogo 3s ease-in-out infinite;
        }
        @keyframes bounceLogo {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .brand-title {
            font-family: 'Bangers', cursive;
            font-size: clamp(2rem, 4vw, 3rem);
            color: var(--comic-orange);
            letter-spacing: 3px;
            text-shadow: 4px 4px 0 rgba(255,107,53,0.3);
            line-height: 1.2;
            margin-bottom: 16px;
        }
        .brand-desc {
            color: rgba(255,255,255,0.7);
            font-weight: 700;
            font-size: 1rem;
            max-width: 340px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }
        .brand-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
        }
        .brand-stat {
            text-align: center;
        }
        .brand-stat-num {
            display: block;
            font-family: 'Bangers', cursive;
            font-size: 2.2rem;
            color: var(--comic-orange);
            line-height: 1;
            text-shadow: 2px 2px 0 rgba(0,0,0,0.3);
        }
        .brand-stat-label {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.5);
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        /* Floating comic elements */
        .comic-deco {
            position: absolute;
            pointer-events: none;
        }
        .deco-book-1 { top: 10%; left: 8%; font-size: 3rem; animation: floatDeco 4s ease-in-out infinite; }
        .deco-book-2 { top: 25%; right: 12%; font-size: 2.5rem; animation: floatDeco 4s 1s ease-in-out infinite; }
        .deco-book-3 { bottom: 20%; left: 15%; font-size: 2rem; animation: floatDeco 4s 0.5s ease-in-out infinite; }
        .deco-book-4 { bottom: 30%; right: 8%; font-size: 3.5rem; animation: floatDeco 4s 1.5s ease-in-out infinite; }
        .deco-pow { top: 40%; right: 5%; font-family: 'Bangers', cursive; font-size: 2rem; color: var(--comic-yellow); text-shadow: 2px 2px 0 var(--comic-shadow); animation: boom 2s ease-in-out infinite; }
        @keyframes floatDeco {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(8deg); }
        }
        @keyframes boom {
            0%, 100% { transform: scale(1) rotate(-5deg); opacity: 0.8; }
            50% { transform: scale(1.2) rotate(5deg); opacity: 1; }
        }

        /* ── Right Panel (Login Form) ── */
        .comic-form-panel {
            flex: 1;
            background: var(--comic-cream);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            overflow-y: auto;
        }
        .login-card {
            background: #fff;
            border: 4px solid var(--comic-dark);
            box-shadow: 8px 8px 0 var(--comic-dark);
            padding: 40px;
            width: 100%;
            max-width: 440px;
            border-radius: 0;
            position: relative;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: -12px;
            left: 20px;
            background: var(--comic-orange);
            border: 3px solid var(--comic-dark);
            padding: 4px 16px;
            font-family: 'Bangers', cursive;
            font-size: 1rem;
            letter-spacing: 2px;
            color: #fff;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
            padding-top: 10px;
        }
        .login-header .form-title {
            font-family: 'Bangers', cursive;
            font-size: 2.5rem;
            color: var(--comic-dark);
            letter-spacing: 2px;
            margin-bottom: 6px;
        }
        .login-header .form-subtitle {
            font-weight: 900;
            color: #888;
            font-size: 0.85rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── Form Inputs ── */
        .comic-form-control {
            border: 2px solid var(--comic-dark) !important;
            border-radius: 0 !important;
            font-weight: 700 !important;
            font-size: 0.95rem !important;
            box-shadow: 3px 3px 0 var(--comic-dark) !important;
            transition: all 0.2s ease !important;
            background: #fff !important;
            padding: 10px 14px !important;
        }
        .comic-form-control:focus {
            border-color: var(--comic-orange) !important;
            box-shadow: 4px 4px 0 var(--comic-orange) !important;
            outline: none !important;
            transform: translateY(-1px);
        }
        .comic-form-control::placeholder {
            color: #aaa !important;
            font-weight: 700 !important;
        }
        .input-icon-wrap {
            position: relative;
        }
        .input-icon-wrap .form-control {
            padding-left: 44px !important;
        }
        .input-icon-wrap .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--comic-orange);
            font-size: 1.1rem;
            z-index: 2;
        }
        .input-icon-wrap .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            z-index: 2;
            background: none;
            border: none;
            padding: 0;
        }
        .input-icon-wrap .toggle-password:hover { color: var(--comic-dark); }

        /* ── Remember Checkbox ── */
        .comic-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .comic-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border: 2px solid var(--comic-dark);
            border-radius: 0;
            accent-color: var(--comic-orange);
            cursor: pointer;
        }
        .comic-check-label {
            font-weight: 900;
            font-size: 0.85rem;
            color: var(--comic-dark);
            cursor: pointer;
        }

        /* ── Submit Button ── */
        .btn-comic-login {
            width: 100%;
            background: var(--comic-orange);
            color: #fff;
            border: 3px solid var(--comic-dark);
            border-radius: 0;
            font-family: 'Fredoka One', cursive;
            font-size: 1.1rem;
            letter-spacing: 2px;
            padding: 14px 20px;
            box-shadow: 5px 5px 0 var(--comic-dark);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-comic-login:hover {
            background: var(--comic-yellow);
            color: var(--comic-dark);
            transform: translateY(-3px);
            box-shadow: 7px 9px 0 var(--comic-dark);
        }
        .btn-comic-login:active {
            transform: translateY(0);
            box-shadow: 3px 3px 0 var(--comic-dark);
        }

        /* ── Alert Boxes ── */
        .comic-alert {
            border: 2px solid var(--comic-dark);
            border-radius: 0;
            font-weight: 700;
            box-shadow: 3px 3px 0 var(--comic-dark);
            padding: 12px 16px;
        }
        .comic-alert-danger {
            background: #fff0f0;
            border-color: var(--comic-red);
            color: var(--comic-red);
            box-shadow: 3px 3px 0 var(--comic-red);
        }
        .comic-alert-success {
            background: #f0fff4;
            border-color: var(--comic-blue);
            color: #00a884;
            box-shadow: 3px 3px 0 var(--comic-blue);
        }

        /* ── Footer ── */
        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8rem;
            color: #aaa;
            font-weight: 700;
        }
        .auth-footer a {
            color: var(--comic-orange);
            text-decoration: none;
            font-weight: 900;
        }
        .auth-footer a:hover { text-decoration: underline; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .comic-brand-panel { display: none; }
            .comic-form-panel { padding: 20px 10px; }
            .login-card { padding: 30px 20px; }
        }

        /* ── Login BG Image (keeps default) ── */
        .bgi-size-cover { background-size: cover !important; }
        .bgi-attachment-fixed { background-attachment: fixed !important; }
        .bgi-position-center { background-position: center !important; }
        .bgi-no-repeat { background-repeat: no-repeat !important; }
    </style>
</head>
<body>
    @include('layouts.partials._theme-mode')

    <div class="comic-auth-wrapper">
        {{-- Left Brand Panel --}}
        <div class="comic-brand-panel d-none d-lg-flex">
            <div class="brand-bg-pattern"></div>
            <div class="comic-deco deco-book-1">📕</div>
            <div class="comic-deco deco-book-2">📗</div>
            <div class="comic-deco deco-book-3">📓</div>
            <div class="comic-deco deco-book-4">📙</div>
            <div class="comic-deco deco-pow">POW!</div>

            <div class="brand-content">
                <span class="brand-logo">📚</span>
                <h1 class="brand-title">{{ app_setting('app_name', 'Aplikasi Perpustakaan') }}</h1>
                <p class="brand-desc">{{ app_setting('app_description', 'Sistem manajemen perpustakaan digital modern dengan nuansa komik interaktif.') }}</p>
                <div class="brand-stats">
                    <div class="brand-stat">
                        <span class="brand-stat-num">📖</span>
                        <span class="brand-stat-label">Buku</span>
                    </div>
                    <div class="brand-stat">
                        <span class="brand-stat-num">👥</span>
                        <span class="brand-stat-label">Anggota</span>
                    </div>
                    <div class="brand-stat">
                        <span class="brand-stat-num">🔄</span>
                        <span class="brand-stat-label">Pinjam</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Form Panel --}}
        <div class="comic-form-panel">
            <div class="login-card">
                <div class="login-header">
                    <div class="form-title">SIGN IN</div>
                    <div class="form-subtitle">Masuk ke Panel Admin</div>
                </div>

                @if($errors->any())
                <div class="alert comic-alert comic-alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="ki-duotone ki-shield-cross fs-2 me-2"></i>
                    <div>
                        @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(session('status'))
                <div class="alert comic-alert comic-alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="ki-duotone ki-shield-tick fs-2 me-2"></i>
                    <div>{{ session('status') }}</div>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-black text-dark fw-bold mb-1">📧 Email</label>
                        <div class="input-icon-wrap">
                            <i class="ki-duotone ki-mail input-icon"></i>
                            <input type="email" placeholder="email@sekolah.sch.id" name="email"
                                class="form-control comic-form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required autofocus autocomplete="email"/>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-black text-dark fw-bold mb-1">🔐 Password</label>
                        <div class="input-icon-wrap">
                            <i class="ki-duotone ki-lock input-icon"></i>
                            <input type="password" placeholder="••••••••" name="password" id="password-field"
                                class="form-control comic-form-control @error('password') is-invalid @enderror" required autocomplete="current-password"/>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="ki-duotone ki-eye fs-4" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="comic-check">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}/>
                            <span class="comic-check-label">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-comic-login">
                        🚀 MASUK KE DASHBOARD
                    </button>
                </form>

                <div class="auth-footer mt-4">
                    <a href="{{ url('/') }}">← Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <script>
        function togglePassword() {
            var p = document.getElementById('password-field');
            var icon = document.getElementById('eye-icon');
            if (p.type === 'password') {
                p.type = 'text';
                icon.className = 'ki-duotone ki-eye-slash fs-4';
            } else {
                p.type = 'password';
                icon.className = 'ki-duotone ki-eye fs-4';
            }
        }
    </script>
</body>
</html>