@extends('layouts.app')

@section('title', 'Detail Buku')
@section('page-title', 'Detail Buku')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Manajemen</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.books.index') }}" class="text-muted text-hover-primary">Data Buku</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">{{ Str::limit($book->title, 20) }}</li>
</ul>
@endsection

@push('custom-css')
<style>
/* ═══════════════════════════════════════
   DESIGN TOKENS — Comic Theme
   ═══════════════════════════════════════ */
:root {
    --comic-dark:    #1A1A2E;
    --comic-orange: #FF6B35;
    --comic-yellow:  #FFD23F;
    --comic-blue:    #4ECDC4;
    --comic-red:     #FF3366;
    --comic-green:   #06D6A0;
    --comic-cream:   #FFF8F0;
    --shadow-sm:     2px 2px 0 var(--comic-dark);
    --shadow-md:     4px 4px 0 var(--comic-dark);
    --shadow-lg:     6px 6px 0 var(--comic-dark);
    --border-comic:  3px solid var(--comic-dark);
    --font-title:    'Bangers', cursive;
    --font-body:     'Fredoka One', cursive;
}

/* ═══════════════════════════════════════
   CARD — Main Container
   ═══════════════════════════════════════ */
.detail-card {
    border: var(--border-comic) !important;
    box-shadow: var(--shadow-lg) !important;
    border-radius: 0 !important;
    background: var(--comic-cream) !important;
    overflow: hidden;
}
.detail-card .card-header {
    background: var(--comic-dark) !important;
    border-bottom: 4px solid var(--comic-orange) !important;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.detail-card .card-header .card-title {
    font-family: var(--font-title) !important;
    color: var(--comic-orange) !important;
    font-size: 1.4rem !important;
    letter-spacing: 4px !important;
    margin: 0;
}

/* ═══════════════════════════════════════
   HEADER ACTION BUTTONS
   ═══════════════════════════════════════ */
.btn-action-header {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: var(--font-body);
    font-size: 0.78rem;
    font-weight: 900;
    letter-spacing: 1px;
    padding: 9px 18px;
    border-radius: 0;
    border: 2.5px solid;
    transition: all 0.2s ease;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}
.btn-back {
    background: rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.85);
    border-color: rgba(255,255,255,0.25);
}
.btn-back:hover {
    background: rgba(255,255,255,0.18);
    color: #fff;
    border-color: rgba(255,255,255,0.5);
}
.btn-edit {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    border-color: var(--comic-dark);
    box-shadow: var(--shadow-sm);
}
.btn-edit:hover {
    background: var(--comic-orange);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
/* ═══════════════════════════════════════
   LAYOUT — Two Column
   ═══════════════════════════════════════ */
.detail-body {
    padding: 24px !important;
}
.detail-left  { display: flex; flex-direction: column; gap: 20px; }
.detail-right { display: flex; flex-direction: column; gap: 20px; }
@media (min-width: 992px) {
    .detail-right {
        position: sticky;
        top: 24px;
        align-self: flex-start;
    }
}

/* ═══════════════════════════════════════
   SECTION CARD — Reusable Block
   ═══════════════════════════════════════ */
.section-card {
    background: #fff;
    border: var(--border-comic);
    box-shadow: var(--shadow-md);
    padding: 20px;
}
.section-card + .section-card { margin-top: 0; }
.section-title {
    font-family: var(--font-title);
    font-size: 1.1rem;
    color: var(--comic-orange);
    letter-spacing: 3px;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 3px solid var(--comic-orange);
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ═══════════════════════════════════════
   INFO ROW — Key-Value Pairs
   ═══════════════════════════════════════ */
.info-grid {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 0;
}
.info-row {
    display: contents; /* use CSS grid row */
}
.info-row .info-key,
.info-row .info-val {
    padding: 8px 0;
    border-bottom: 2px solid rgba(26,26,46,0.08);
    display: flex;
    align-items: center;
}
.info-row:last-child .info-key,
.info-row:last-child .info-val {
    border-bottom: none;
}
.info-key {
    font-family: var(--font-body);
    font-size: 0.68rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #aaa;
    font-weight: 700;
    padding-right: 16px;
}
.info-val {
    font-family: var(--font-body);
    font-size: 0.88rem;
    color: var(--comic-dark);
    font-weight: 700;
    line-height: 1.5;
}

/* Highlighted value (important fields) */
.info-val.highlight {
    font-size: 0.95rem;
    background: transparent !important;
    color: var(--comic-orange);
}
.info-val.orange {
    color: var(--comic-orange);
}

/* ═══════════════════════════════════════
   BADGES & STATUS
   ═══════════════════════════════════════ */
.badge-comic {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    font-family: var(--font-body);
    font-size: 0.7rem;
    font-weight: 900;
    letter-spacing: 1px;
    border: 2.5px solid currentColor;
    border-radius: 0;
}
.badge-available {
    color: var(--comic-blue);
    background: rgba(78,205,196,0.1);
    border-color: var(--comic-blue);
}
.badge-unavailable {
    color: var(--comic-red);
    background: rgba(255,51,102,0.1);
    border-color: var(--comic-red);
}
.badge-category {
    color: var(--comic-dark);
    background: var(--comic-cream);
    border-color: var(--comic-dark);
}

/* ═══════════════════════════════════════
   STOCK INDICATOR
   ═══════════════════════════════════════ */
.stock-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border: 2.5px solid var(--comic-dark);
    box-shadow: var(--shadow-sm);
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 900;
    border-radius: 0;
}
.stock-pill.in-stock {
    color: var(--comic-blue);
    background: rgba(78,205,196,0.1);
}
.stock-pill.out-stock {
    color: var(--comic-red);
    background: rgba(255,51,102,0.1);
}
.stock-number {
    font-family: var(--font-title);
    font-size: 1.5rem;
    line-height: 1;
}

/* ═══════════════════════════════════════
   BOOK COVER
   ═══════════════════════════════════════ */
.cover-wrapper {
    text-align: center;
}
.cover-img {
    width: 100%;
    max-width: 180px;
    height: 240px;
    object-fit: cover;
    border: var(--border-comic);
    box-shadow: var(--shadow-md);
    border-radius: 0;
    display: inline-block;
}
.cover-placeholder {
    width: 100%;
    max-width: 180px;
    height: 240px;
    background: var(--comic-cream);
    border: var(--border-comic);
    box-shadow: var(--shadow-md);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-title);
    font-size: 4rem;
    color: var(--comic-dark);
}

/* ═══════════════════════════════════════
   QR SECTION
   ═══════════════════════════════════════ */
.qr-card {
    background: #fff;
    border: var(--border-comic);
    box-shadow: var(--shadow-md);
    padding: 20px;
    text-align: center;
}
.qr-img {
    width: 130px;
    height: 130px;
    object-fit: contain;
    border: 2.5px solid var(--comic-dark);
    box-shadow: var(--shadow-sm);
    margin: 0 auto 8px;
    display: block;
    border-radius: 0;
}
.qr-label {
    font-family: var(--font-body);
    font-size: 0.65rem;
    color: #aaa;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 16px;
}
.qr-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.qr-empty-state {
    background: #f9f9f9;
    border: 2px dashed #ccc;
    padding: 24px 16px;
    font-family: var(--font-body);
    font-size: 0.8rem;
    color: #aaa;
    letter-spacing: 1px;
    margin-bottom: 12px;
}

/* ═══════════════════════════════════════
   QR ACTION BUTTONS
   ═══════════════════════════════════════ */
.btn-qr {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    font-family: var(--font-body);
    font-size: 0.8rem;
    font-weight: 900;
    letter-spacing: 1px;
    padding: 10px 16px;
    border-radius: 0;
    border: 2.5px solid var(--comic-dark);
    transition: all 0.2s ease;
    text-decoration: none;
}
.btn-qr-print {
    background: var(--comic-orange);
    color: #fff;
    box-shadow: var(--shadow-sm);
}
.btn-qr-print:hover {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.btn-qr-regen {
    background: var(--comic-green);
    color: #fff;
    box-shadow: var(--shadow-sm);
}
.btn-qr-regen:hover {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.btn-qr-generate {
    background: var(--comic-green);
    color: #fff;
    box-shadow: var(--shadow-sm);
}
.btn-qr-generate:hover {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* ═══════════════════════════════════════
   SINOPSIS
   ═══════════════════════════════════════ */
.synopsis-text {
    background: var(--comic-cream);
    border: var(--border-comic);
    box-shadow: var(--shadow-sm);
    padding: 16px;
    font-family: var(--font-body);
    font-size: 0.85rem;
    color: var(--comic-dark);
    line-height: 1.8;
    font-weight: 600;
}

/* ═══════════════════════════════════════
   SECTION DIVIDER
   ═══════════════════════════════════════ */
.section-divider {
    height: 3px;
    background: linear-gradient(
        to right,
        var(--comic-orange) 0%,
        var(--comic-yellow) 50%,
        transparent 100%
    );
    margin: 0;
    border: none;
}

/* ═══════════════════════════════════════
   EMPTY STATE
   ═══════════════════════════════════════ */
.empty-slot {
    background: #f9f9f9;
    border: 2px dashed #ccc;
    padding: 20px;
    font-family: var(--font-body);
    font-size: 0.82rem;
    color: #aaa;
    letter-spacing: 1px;
    text-align: center;
}
</style>
@endpush

@section('content')
<div class="card detail-card">
    {{-- ═══ HEADER ═══ --}}
    <div class="card-header">
        <div class="card-title">📕 DETAIL BUKU</div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.books.index') }}" class="btn-action-header btn-back">
                <i class="ki-duotone ki-left fs-4"></i> Kembali
            </a>
            <a href="{{ route('admin.books.edit', $book) }}" class="btn-action-header btn-edit">
                <i class="ki-duotone ki-pencil fs-4"></i> Edit
            </a>
        </div>
    </div>

    {{-- ═══ BODY ═══ --}}
    <div class="card-body detail-body">
        <div class="row g-4">

            {{-- ══════════════════════════════
                 LEFT COLUMN — Info Sections
                 ══════════════════════════════ --}}
            <div class="col-lg-7 detail-left">

             
                {{-- Section 1: Identitas Buku --}}
                <div class="section-card">
                    <div class="section-title">📖 IDENTITAS BUKU</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-key">📖 Kode</span>
                            <span class="info-val highlight" style="letter-spacing:1px; font-size:0.95rem;">{{ $book->book_code }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">📋 ISBN</span>
                            <span class="info-val">{{ $book->isbn ?? '<span style="color:#ccc;">—</span>' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">📂 Kategori</span>
                            <span class="info-val">
                                @if($book->category)
                                    <span class="badge-comic badge-category">
                                        {{ $book->category->icon ?? '' }} {{ $book->category->name }}
                                    </span>
                                @else
                                    <span style="color:#ccc;">—</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">⚡ Status</span>
                            <span class="info-val">
                                <span class="badge-comic {{ $book->status->value === 'available' ? 'badge-available' : 'badge-unavailable' }}">
                                    {{ $book->status->value === 'available' ? '✅ Available' : '⛔ Unavailable' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Info Penerbitan --}}
                <div class="section-card">
                    <div class="section-title">🏢 INFO PENERBITAN</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-key">✍️ Penulis</span>
                            <span class="info-val">{{ $book->author ?? '<span style="color:#ccc;">—</span>' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">🏢 Penerbit</span>
                            <span class="info-val">{{ $book->publisher ?? '<span style="color:#ccc;">—</span>' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">📅 Tahun</span>
                            <span class="info-val">{{ $book->year ?? '<span style="color:#ccc;">—</span>' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">📄 Halaman</span>
                            <span class="info-val">{{ $book->pages ? $book->pages . ' hal.' : '<span style="color:#ccc;">—</span>' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Info Perpustakaan --}}
                <div class="section-card">
                    <div class="section-title">📍 INFO PERPUSTAKAAN</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-key">📍 Rak</span>
                            <span class="info-val orange" style="font-size:1rem;">{{ $book->rack_location ?? '<span style="color:#ccc;">—</span>' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">📦 Stok</span>
                            <span class="info-val">
                                <span class="stock-pill {{ $book->stock > 0 ? 'in-stock' : 'out-stock' }}">
                                    <span class="stock-number">{{ $book->stock }}</span>
                                    BUKU
                                    @if($book->stock > 0)
                                        <i class="ki-duotone ki-check-circle" style="font-size:1.1rem;"></i>
                                    @else
                                        <i class="ki-duotone ki-cross-circle" style="font-size:1.1rem;"></i>
                                    @endif
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Sinopsis --}}
                @if($book->synopsis)
                <div class="section-card">
                    <div class="section-title">📝 SINOPSIS</div>
                    <div class="synopsis-text">{{ $book->synopsis }}</div>
                </div>
                @endif

            </div>

            {{-- ══════════════════════════════
                 RIGHT COLUMN — Cover & QR
                 ══════════════════════════════ --}}
            <div class="col-lg-5 detail-right">

                {{-- Book Cover --}}
                <div class="section-card cover-wrapper">
                    <div class="section-title" style="justify-content:center;">🖼️ SAMPUL BUKU</div>
                    @if($book->cover)
                        <img src="{{ asset('storage/' . $book->cover) }}"
                            alt="{{ $book->title }}"
                            class="cover-img"/>
                    @else
                        <div class="cover-placeholder">
                            {{ substr($book->title, 0, 1) }}
                        </div>
                    @endif
                </div>

                {{-- QR Code Section --}}
                <div class="qr-card">
                    <div class="section-title" style="justify-content:center;">📱 QR CODE</div>

                    @if($book->qr_code)
                        <img src="{{ asset('storage/' . $book->qr_code) }}"
                            alt="QR Code"
                            class="qr-img"/>
                        <div class="qr-label">SCAN UNTUK DETAIL BUKU</div>
                        <div class="qr-actions">
                            <button type="button" onclick="printBookQr()" class="btn-qr btn-qr-print">
                                <i class="ki-duotone ki-printer fs-5"></i> PRINT QR CODE
                            </button>
                            <form method="POST" action="{{ route('admin.books.regenerate-qr', $book) }}">
                                @csrf
                                <button type="submit" class="btn-qr btn-qr-regen">
                                    <i class="ki-duotone ki-refresh fs-5"></i> REGENERATE QR
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="qr-empty-state">
                            <div style="font-size:2rem; margin-bottom:6px;">📭</div>
                            <div>QR Code belum tersedia</div>
                        </div>
                        <form method="POST" action="{{ route('admin.books.regenerate-qr', $book) }}">
                            @csrf
                            <button type="submit" class="btn-qr btn-qr-generate" style="width:100%;">
                                <i class="ki-duotone ki-qrcode fs-5"></i> GENERATE QR
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@push('custom-js')
<script>
function printBookQr() {
    var qrSrc = document.querySelector('.qr-img')?.src || '';
    if (!qrSrc) return;

    var title    = {{ Js::from($book->title) }};
    var code     = {{ Js::from($book->book_code) }};
    var isbn     = {{ Js::from($book->isbn ?? '-') }};
    var author   = {{ Js::from($book->author ?? '-') }};
    var category = {{ Js::from($book->category?->name ?? '-') }};

    var html = `<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<title>Print QR — ${title}</title>
<link href="https://fonts.googleapis.com/css2?family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Fredoka One',sans-serif;padding:24px;background:#fff;}
#wrap{display:flex;align-items:center;gap:20px;border:3px solid #1A1A2E;padding:20px;box-shadow:6px 6px 0 #1A1A2E;background:#fff;}
#wrap img{width:160px;height:160px;object-fit:contain;}
.info{border-left:3px solid #1A1A2E;padding-left:20px;flex:1;}
.title{font-family:Bangers,sans-serif;font-size:22px;color:#1A1A2E;margin-bottom:12px;letter-spacing:2px;}
.row{display:flex;gap:10px;margin:5px 0;font-size:13px;}
.lbl{color:#aaa;font-size:11px;min-width:60px;text-transform:uppercase;}
.val{color:#1A1A2E;font-weight:900;}
@media print{body{padding:0;}}
</style>
</head>
<body>
<div id="wrap">
    <img src="${qrSrc}" alt="QR"/>
    <div class="info">
        <div class="title">${title}</div>
        <div class="row"><span class="lbl">KODE</span><span class="val">${code}</span></div>
        <div class="row"><span class="lbl">ISBN</span><span class="val">${isbn}</span></div>
        <div class="row"><span class="lbl">PENULIS</span><span class="val">${author}</span></div>
        <div class="row"><span class="lbl">KATEGORI</span><span class="val">${category}</span></div>
    </div>
</div>
<script>window.onload=function(){window.print();}<\/script>
</body></html>`;

    var win = window.open('', '_blank', 'width=650,height=350');
    if (win) { win.document.write(html); win.document.close(); }
}
</script>
@endpush
@endsection
