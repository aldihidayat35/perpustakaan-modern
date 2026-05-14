<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <base href="{{ url('/') }}/"/>
    <title>Login — {{ app_setting('app_name', config('app.name')) }}</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="shortcut icon" href="{{ app_setting('favicon', 'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📚</text></svg>') }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Nunito:wght@400;600;700;900&family=Fredoka+One&display=swap" rel="stylesheet"/>
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
    <style>
        :root {
            --comic-cream: #FFF8F0;
            --comic-orange: #FF6B35;
            --comic-yellow: #FFE66D;
            --comic-dark: #1A1A2E;
            --comic-blue: #4ECDC4;
            --comic-red: #FF3366;
            --comic-green: #00C896;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Nunito', sans-serif !important;
            background: var(--comic-dark) !important;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Animated Background ── */
        .bg-dots {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(255,107,53,0.18) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(78,205,196,0.15) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(255,230,109,0.06) 0%, transparent 60%),
                repeating-linear-gradient(0deg, transparent, transparent 60px, rgba(255,255,255,0.025) 60px, rgba(255,255,255,0.025) 61px),
                repeating-linear-gradient(90deg, transparent, transparent 60px, rgba(255,255,255,0.025) 60px, rgba(255,255,255,0.025) 61px);
        }
        .floating-emoji {
            position: fixed;
            font-size: 2.5rem;
            opacity: 0.12;
            pointer-events: none;
            z-index: 0;
            animation: floatEmoji 6s ease-in-out infinite;
        }
        .fe-1 { top: 8%;  left: 5%;  animation-delay: 0s;    font-size: 3rem; }
        .fe-2 { top: 15%; right: 8%; animation-delay: 1s;    font-size: 2rem; }
        .fe-3 { top: 60%; left: 3%;  animation-delay: 0.5s;  font-size: 2.2rem; }
        .fe-4 { top: 70%; right: 5%; animation-delay: 1.5s;  font-size: 3.5rem; }
        .fe-5 { top: 40%; left: 1%;  animation-delay: 2s;    font-size: 2rem; }
        .fe-6 { top: 85%; left: 15%; animation-delay: 0.8s;  font-size: 2rem; }
        .fe-7 { top: 30%; right: 2%; animation-delay: 2.5s;  font-size: 2.5rem; }
        .fe-8 { bottom: 10%; left: 8%; animation-delay: 1.2s; font-size: 2rem; }
        @keyframes floatEmoji {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.12; }
            50%       { transform: translateY(-20px) rotate(8deg); opacity: 0.22; }
        }

        /* ── Centered Card ── */
        .login-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        /* ── Main Card ── */
        .comic-card {
            width: 100%;
            max-width: 460px;
            background: var(--comic-cream);
            border: 4px solid var(--comic-dark);
            box-shadow: 10px 10px 0 var(--comic-orange);
            position: relative;
            overflow: visible;
            animation: cardSlideUp 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }
        @keyframes cardSlideUp {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Top Ribbon ── */
        .card-ribbon {
            background: var(--comic-orange);
            border-bottom: 4px solid var(--comic-dark);
            padding: 28px 32px 26px;
            text-align: center;
            position: relative;
        }
        .card-ribbon::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            width: 42px;
            height: 42px;
            background: var(--comic-cream);
            border: 4px solid var(--comic-dark);
            border-top: none;
            border-left: none;
            z-index: -1;
        }
        .ribbon-logo {
            display: inline-block;
            margin-bottom: 10px;
            animation: bounceLogo 3s ease-in-out infinite;
        }
        @keyframes bounceLogo {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-10px); }
        }
        .ribbon-logo .logo-img {
            height: 68px;
            width: auto;
            object-fit: contain;
            border: 3px solid var(--comic-dark);
            border-radius: 10px;
            box-shadow: 3px 3px 0 var(--comic-dark);
        }
        .ribbon-logo .logo-emoji {
            font-size: 3.5rem;
            display: block;
            line-height: 1;
        }
        .ribbon-title {
            font-family: 'Bangers', cursive;
            font-size: 2rem;
            color: #fff;
            letter-spacing: 3px;
            line-height: 1.2;
            text-shadow: 3px 3px 0 rgba(0,0,0,0.3);
            animation: titlePop 0.5s ease 0.3s both;
        }
        @keyframes titlePop {
            from { opacity: 0; transform: scale(0.8); }
            to   { opacity: 1; transform: scale(1); }
        }
        .ribbon-sub {
            font-family: 'Fredoka One', cursive;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.8);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* ── Decorative Badge ── */
        .deco-badge {
            position: absolute;
            top: -18px;
            right: -18px;
            background: var(--comic-yellow);
            border: 3px solid var(--comic-dark);
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 4px 4px 0 var(--comic-dark);
            animation: boomBadge 2.5s ease-in-out infinite;
            z-index: 10;
        }
        @keyframes boomBadge {
            0%, 100% { transform: rotate(-6deg) scale(1); }
            50%       { transform: rotate(6deg) scale(1.1); }
        }

        /* ── Card Body ── */
        .card-body-login {
            padding: 32px 32px 28px;
        }
        .section-label {
            font-family: 'Fredoka One', cursive;
            font-size: 0.7rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--comic-orange);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 2px;
            background: var(--comic-orange);
            opacity: 0.3;
        }

        /* ── Alert Comic ── */
        .comic-alert {
            border: 2px solid var(--comic-dark) !important;
            border-radius: 0 !important;
            font-weight: 700;
            box-shadow: 3px 3px 0 var(--comic-dark) !important;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .comic-alert-danger {
            background: #fff0f0 !important;
            border-color: var(--comic-red) !important;
            color: var(--comic-red) !important;
            box-shadow: 3px 3px 0 var(--comic-red) !important;
        }
        .comic-alert-success {
            background: #f0fff4 !important;
            border-color: var(--comic-blue) !important;
            color: #00a884 !important;
            box-shadow: 3px 3px 0 var(--comic-blue) !important;
        }

        /* ── Field Labels ── */
        .field-label {
            font-family: 'Fredoka One', cursive;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--comic-dark);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 900;
        }
        .field-wrap {
            position: relative;
            margin-bottom: 16px;
        }
        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            z-index: 2;
            pointer-events: none;
        }
        .field-icon-email { color: var(--comic-orange); }
        .field-icon-pw    { color: var(--comic-blue); }

        /* ── Comic Input ── */
        .comic-input {
            border: 2px solid var(--comic-dark) !important;
            border-radius: 0 !important;
            font-weight: 700 !important;
            font-size: 0.88rem !important;
            box-shadow: 4px 4px 0 var(--comic-dark) !important;
            transition: all 0.2s ease !important;
            background: #fff !important;
            padding: 12px 14px 12px 44px !important;
            color: var(--comic-dark) !important;
            height: auto;
            width: 100%;
        }
        .comic-input:focus {
            border-color: var(--comic-orange) !important;
            box-shadow: 5px 5px 0 var(--comic-orange) !important;
            outline: none !important;
            transform: translateY(-1px);
        }
        .comic-input::placeholder {
            color: #bbb !important;
            font-weight: 700 !important;
            font-size: 0.82rem;
        }
        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #aaa;
            padding: 4px;
            z-index: 3;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        .toggle-pw:hover { color: var(--comic-dark); }

        /* ── Error message ── */
        .text-danger {
            color: var(--comic-red) !important;
            font-size: 0.78rem;
            font-weight: 700;
            margin-top: 4px;
            display: block;
        }

        /* ── Checkbox ── */
        .comic-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            margin-bottom: 20px;
        }
        .comic-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border: 2px solid var(--comic-dark) !important;
            border-radius: 0 !important;
            accent-color: var(--comic-orange);
            cursor: pointer;
            flex-shrink: 0;
        }
        .comic-check-label {
            font-weight: 900;
            font-size: 0.78rem;
            color: var(--comic-dark);
        }

        /* ── Submit Button (comic style like btn-cari) ── */
        .btn-comic-submit {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 20px;
            background: var(--comic-orange) !important;
            border: 3px solid var(--comic-dark) !important;
            border-radius: 0 !important;
            box-shadow: 5px 5px 0 var(--comic-dark) !important;
            color: #fff !important;
            font-family: 'Fredoka One', cursive !important;
            font-size: 1rem !important;
            letter-spacing: 2px;
            font-weight: 900 !important;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-comic-submit:hover {
            background: var(--comic-yellow) !important;
            color: var(--comic-dark) !important;
            transform: translateY(-3px);
            box-shadow: 7px 8px 0 var(--comic-dark) !important;
        }
        .btn-comic-submit:active {
            transform: translateY(0);
            box-shadow: 3px 3px 0 var(--comic-dark) !important;
        }

        /* ── Footer Link ── */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            font-family: 'Fredoka One', cursive;
            font-weight: 700;
            font-size: 0.78rem;
            color: var(--comic-orange);
            text-decoration: none;
            letter-spacing: 1px;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--comic-dark); text-decoration: underline; }

        /* ── Divider with comic effect ── */
        .comic-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 18px 0;
        }
        .comic-divider::before,
        .comic-divider::after {
            content: '';
            flex: 1;
            height: 2px;
            background: var(--comic-dark);
            opacity: 0.15;
        }
        .comic-divider span {
            font-family: 'Fredoka One', cursive;
            font-size: 0.7rem;
            color: var(--comic-dark);
            opacity: 0.4;
            letter-spacing: 2px;
        }

        /* ── Version badge ── */
        .version-badge {
            position: absolute;
            bottom: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--comic-dark);
            color: rgba(255,255,255,0.5);
            font-family: 'Fredoka One', cursive;
            font-size: 0.65rem;
            letter-spacing: 2px;
            padding: 4px 14px;
            border-radius: 20px;
            white-space: nowrap;
            z-index: 5;
        }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .card-body-login { padding: 24px 20px 28px; }
            .card-ribbon { padding: 22px 20px 18px; }
            .ribbon-title { font-size: 1.6rem; }
            .comic-input { padding: 10px 12px 10px 40px !important; }
            .deco-badge { width: 48px; height: 48px; font-size: 1.6rem; top: -14px; right: -14px; }
        }
    </style>
</head>
<body>

<div class="bg-dots"></div>
<span class="floating-emoji fe-1">📕</span>
<span class="floating-emoji fe-2">📗</span>
<span class="floating-emoji fe-3">📓</span>
<span class="floating-emoji fe-4">📙</span>
<span class="floating-emoji fe-5">📚</span>
<span class="floating-emoji fe-6">🔑</span>
<span class="floating-emoji fe-7">🔐</span>
<span class="floating-emoji fe-8">🗝️</span>

<div class="login-wrap">
    <div class="comic-card">
        {{-- Decorative Badge --}}
        <div class="deco-badge">💥</div>

        {{-- Top Ribbon --}}
        <div class="card-ribbon">
            <div class="ribbon-logo">
                @if(app_setting('app_logo'))
                    <img src="{{ asset('storage/' . app_setting('app_logo')) }}" alt="Logo" class="logo-img"/>
                @else
                    <span class="logo-emoji">📚</span>
                @endif
            </div>
            <h1 class="ribbon-title">{{ app_setting('app_name', 'Aplikasi Perpustakaan') }}</h1>
            <p class="ribbon-sub">Panel Administrator</p>
        </div>

        {{-- Form Body --}}
        <div class="card-body-login">

            <div class="section-label">MASUK AKUN</div>

            {{-- Error Alert --}}
            @if($errors->any())
            <div class="alert comic-alert comic-alert-danger d-flex align-items-center" role="alert">
                <i class="ki-duotone ki-shield-cross fs-2 me-2 flex-shrink-0" style="color:var(--comic-red);"></i>
                <div>
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Success Alert --}}
            @if(session('status'))
            <div class="alert comic-alert comic-alert-success d-flex align-items-center" role="alert">
                <i class="ki-duotone ki-shield-tick fs-2 me-2 flex-shrink-0" style="color:var(--comic-blue);"></i>
                <div>{{ session('status') }}</div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="field-wrap">
                    <label class="field-label" for="email">📧 ALAMAT EMAIL</label>
                    <div style="position:relative;">
                        <i class="ki-duotone ki-mail field-icon field-icon-email"></i>
                        <input type="email"
                            id="email"
                            name="email"
                            class="form-control comic-input @error('email') is-invalid @enderror"
                            placeholder="email@sekolah.sch.id"
                            value="{{ old('email') }}"
                            required autofocus autocomplete="email"/>
                    </div>
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field-wrap">
                    <label class="field-label" for="pw">🔐 KATA SANDI</label>
                    <div style="position:relative;">
                        <i class="ki-duotone ki-lock field-icon field-icon-pw"></i>
                        <input type="password"
                            id="pw"
                            name="password"
                            class="form-control comic-input @error('password') is-invalid @enderror"
                            placeholder="Masukkan kata sandi..."
                            required autocomplete="current-password"/>
                        <button type="button" class="toggle-pw" onclick="togglePw()" aria-label="Toggle password">
                            <i class="ki-duotone ki-eye fs-4" id="pw-icon"></i>
                        </button>
                    </div>
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember --}}
                <label class="comic-check">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}/>
                    <span class="comic-check-label">Ingat sesi login saya</span>
                </label>

                {{-- Submit --}}
                <button type="submit" class="btn-comic-submit">
                    🚀&nbsp;&nbsp;MASUK KE DASHBOARD
                </button>
            </form>

            <div class="comic-divider"><span>ATAU</span></div>

            <a href="{{ url('/') }}" class="back-link">
                ← Kembali ke Halaman Utama
            </a>
        </div>

        {{-- Version badge --}}
        <div class="version-badge">perpustakaan modern v1.0</div>
    </div>
</div>

<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<script>
    function togglePw() {
        var p = document.getElementById('pw');
        var icon = document.getElementById('pw-icon');
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
