<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Kartu Anggota - {{ $member->member_code }} - {{ app_setting('app_name', config('app.name')) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet">
    <style>
        :root {
            --comic-cream: #FFF8F0;
            --comic-orange: #FF6B35;
            --comic-dark: #1A1A2E;
            --comic-blue: #4ECDC4;
            --comic-yellow: #FFE66D;
            --comic-red: #FF3366;
            --comic-green: #00C896;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Fredoka One', cursive, sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* ── CR-80 Card Size: 86mm × 54mm ── */
        .id-card {
            width: 86mm;
            min-height: 54mm;
            border: 2.5px solid var(--comic-dark);
            box-shadow: 4px 4px 0 rgba(26,26,46,0.25);
            border-radius: 0;
            overflow: hidden;
            background: linear-gradient(160deg, var(--comic-dark) 0%, #2d2d4a 100%);
            display: flex;
            flex-direction: column;
        }

        /* ── Header ── */
        .card-header {
            background: var(--comic-orange);
            border-bottom: 2.5px solid var(--comic-dark);
            padding: 7px 10px 6px;
            position: relative;
            flex-shrink: 0;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: repeating-linear-gradient(
                90deg,
                var(--comic-dark) 0px, var(--comic-dark) 6px,
                transparent 6px, transparent 12px
            );
        }
        .card-org {
            font-family: 'Bangers', cursive;
            color: #fff;
            font-size: 10pt;
            letter-spacing: 2px;
            line-height: 1;
            text-shadow: 1px 1px 0 rgba(0,0,0,0.3);
        }
        .card-subtitle {
            font-size: 5.5pt;
            color: rgba(255,255,255,0.8);
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* ── Body ── */
        .card-body {
            display: flex;
            gap: 8px;
            padding: 8px 10px;
            background: var(--comic-cream);
            flex: 1;
            align-items: flex-start;
        }
        .card-photo {
            width: 28mm;
            height: 34mm;
            flex-shrink: 0;
            border: 2.5px solid var(--comic-dark);
            box-shadow: 2px 2px 0 var(--comic-dark);
            overflow: hidden;
            background: var(--comic-dark);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .card-photo .photo-placeholder {
            font-size: 16pt;
            color: rgba(255,255,255,0.4);
        }
        .card-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .card-name {
            font-family: 'Bangers', cursive;
            color: var(--comic-dark);
            font-size: 11pt;
            letter-spacing: 0.5px;
            line-height: 1.1;
            word-break: break-word;
        }
        .card-meta-row {
            display: flex;
            align-items: flex-start;
            gap: 4px;
        }
        .card-meta-icon {
            font-size: 7.5pt;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .card-meta-content { display: flex; flex-direction: column; gap: 1px; }
        .card-meta-label {
            font-size: 5pt;
            color: #bbb;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            line-height: 1;
        }
        .card-meta-value {
            font-size: 7.5pt;
            color: var(--comic-dark);
            font-weight: 700;
            line-height: 1.2;
            word-break: break-word;
        }

        /* ── Footer ── */
        .card-footer {
            background: var(--comic-cream);
            border-top: 2px dashed rgba(26,26,46,0.2);
            padding: 6px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            flex-shrink: 0;
        }
        .card-qr img {
            width: 22mm;
            height: 22mm;
            border: 2px solid var(--comic-dark);
            box-shadow: 1.5px 1.5px 0 var(--comic-dark);
            display: block;
        }
        .card-footer-info {
            text-align: right;
            flex: 1;
        }
        .card-code {
            font-family: 'Bangers', cursive;
            font-size: 11pt;
            color: var(--comic-dark);
            letter-spacing: 1px;
        }
        .card-status {
            font-size: 6pt;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-weight: 700;
        }
        .status-active   { color: var(--comic-green); }
        .status-inactive { color: var(--comic-red); }

        /* ── Print ── */
        @media print {
            body {
                background: white;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .id-card {
                box-shadow: none;
                border: 2px solid #1A1A2E;
            }
            @page {
                size: 86mm 54mm;
                margin: 0;
            }
        }
        @media screen {
            /* Show trim guide on screen */
            body::before {
                content: '';
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 86mm;
                height: 54mm;
                border: 1px dashed rgba(0,0,0,0.15);
                pointer-events: none;
                z-index: -1;
            }
        }
    </style>
</head>
<body>
<div class="id-card">
    {{-- Header --}}
    <div class="card-header">
        <div class="card-org">{{ app_setting('app_name', 'PERPUSTAKAAN SEKOLAH') }}</div>
        <div class="card-subtitle">KARTU ANGGOTA &bull; {{ app_setting('app_tagline', 'Perpustakaan Digital') }}</div>
    </div>

    {{-- Body: Photo + Info --}}
    <div class="card-body">
        <div class="card-photo">
            @if($member->photo)
                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}"/>
            @else
                <span class="photo-placeholder">👤</span>
            @endif
        </div>
        <div class="card-info">
            <div class="card-name">{{ $member->name }}</div>

            <div class="card-meta-row">
                <span class="card-meta-icon">🏫</span>
                <div class="card-meta-content">
                    <div class="card-meta-label">Kelas</div>
                    <div class="card-meta-value">{{ $member->class ?? '-' }}</div>
                </div>
            </div>
            <div class="card-meta-row">
                <span class="card-meta-icon">📚</span>
                <div class="card-meta-content">
                    <div class="card-meta-label">Jurusan</div>
                    <div class="card-meta-value">{{ $member->major ?? '-' }}</div>
                </div>
            </div>
            <div class="card-meta-row">
                <span class="card-meta-icon">🔢</span>
                <div class="card-meta-content">
                    <div class="card-meta-label">NIS / NIM</div>
                    <div class="card-meta-value">{{ $member->nis_nim ?? '-' }}</div>
                </div>
            </div>
            <div class="card-meta-row">
                <span class="card-meta-icon">📱</span>
                <div class="card-meta-content">
                    <div class="card-meta-label">WhatsApp</div>
                    <div class="card-meta-value">{{ $member->whatsapp ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer: QR + Code --}}
    <div class="card-footer">
        <div class="card-qr">
            @if($member->qr_code)
                <img src="{{ asset('storage/' . $member->qr_code) }}" alt="QR Code" />
            @endif
        </div>
        <div class="card-footer-info">
            <div class="card-code">{{ $member->member_code }}</div>
            <div class="card-status {{ $member->status->value === 'active' ? 'status-active' : 'status-inactive' }}">
                {{ $member->status->value === 'active' ? '✓ AKTIF' : '✕ NONAKTIF' }}
            </div>
        </div>
    </div>
</div>
<script>
    window.onload = function () {
        window.print();
    };
</script>
</body>
</html>