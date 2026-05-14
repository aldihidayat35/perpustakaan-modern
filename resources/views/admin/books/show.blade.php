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
.detail-card {
    border: 3px solid var(--comic-dark) !important;
    box-shadow: 6px 6px 0 var(--comic-dark) !important;
    border-radius: 0 !important;
}
.detail-card .card-header {
    background: var(--comic-dark) !important;
    border-bottom: 3px solid var(--comic-orange) !important;
}
.detail-card .card-header .card-title {
    font-family: 'Bangers', cursive !important;
    color: var(--comic-orange) !important;
    font-size: 1.2rem !important;
    letter-spacing: 2px !important;
}
.detail-row {
    display: flex;
    align-items: flex-start;
    padding: 10px 0;
    border-bottom: 2px solid rgba(26,26,46,0.08);
}
.detail-row:last-child { border-bottom: none; }
.detail-key {
    font-family: 'Fredoka One', cursive;
    font-size: 0.72rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #aaa;
    min-width: 130px;
    padding-top: 2px;
    flex-shrink: 0;
}
.detail-val {
    font-weight: 800;
    font-size: 0.88rem;
    color: var(--comic-dark);
    flex: 1;
}
.book-cover-large {
    width: 140px;
    height: 190px;
    object-fit: cover;
    border: 3px solid var(--comic-dark);
    box-shadow: 4px 4px 0 var(--comic-dark);
    border-radius: 0;
}
.book-cover-placeholder-lg {
    width: 140px;
    height: 190px;
    background: var(--comic-cream);
    border: 3px solid var(--comic-dark);
    box-shadow: 4px 4px 0 var(--comic-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Bangers', cursive;
    font-size: 3.5rem;
    color: var(--comic-dark);
    border-radius: 0;
}
.qr-large {
    width: 120px;
    height: 120px;
    object-fit: contain;
    border: 2px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
}
.synopsis-box {
    background: var(--comic-cream);
    border: 2px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    padding: 14px;
    font-size: 0.85rem;
    color: var(--comic-dark);
    line-height: 1.6;
    font-weight: 600;
}
.stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border: 2px solid var(--comic-dark);
    box-shadow: 2px 2px 0 var(--comic-dark);
    font-family: 'Fredoka One', cursive;
    font-size: 1rem;
}
</style>
@endpush

@section('content')
<div class="card detail-card">
    <div class="card-header">
        <div class="card-title">📕 DETAIL BUKU</div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary btn-sm">
                <i class="ki-duotone ki-left fs-4"></i> Kembali
            </a>
            <button type="button" class="btn btn-comic-edit btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modal-edit-book"
                data-book='@json($book)'>
                <i class="ki-duotone ki-pencil fs-4"></i> Edit
            </button>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="row g-4">
            {{-- Left Column: Info Detail --}}
            <div class="col-md-7">
                {{-- Info Grid --}}
                <div style="background:#fff;border:3px solid var(--comic-dark);box-shadow:4px 4px 0 var(--comic-dark);padding:20px;margin-bottom:16px;">
                    <div class="detail-row">
                        <span class="detail-key">📖 KODE</span>
                        <span class="detail-val" style="font-family:'Fredoka One', cursive;font-size:0.95rem;letter-spacing:1px;">{{ $book->book_code }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">📚 JUDUL</span>
                        <span class="detail-val" style="font-family:'Fredoka One', cursive;font-size:1rem;">{{ $book->title }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">📂 KATEGORI</span>
                        @if($book->category)
                            <span class="badge badge-light-primary" style="font-size:0.78rem; border-radius:0 !important;">{{ $book->category->icon ?? '' }} {{ $book->category->name }}</span>
                        @else
                            <span class="detail-val text-muted">-</span>
                        @endif
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">✍️ PENULIS</span>
                        <span class="detail-val">{{ $book->author ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">🏢 PENERBIT</span>
                        <span class="detail-val">{{ $book->publisher ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">📅 TAHUN</span>
                        <span class="detail-val">{{ $book->year ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">📄 HALAMAN</span>
                        <span class="detail-val">{{ $book->pages ? $book->pages . ' halaman' : '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">📋 ISBN</span>
                        <span class="detail-val">{{ $book->isbn ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">📍 LOKASI RAK</span>
                        <span class="detail-val" style="font-family:'Fredoka One', cursive;color:var(--comic-orange);">{{ $book->rack_location ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">📦 STOK</span>
                        <span class="stat-badge" style="color:{{ $book->stock > 0 ? 'var(--comic-blue)' : 'var(--comic-red)' }}; background:{{ $book->stock > 0 ? 'rgba(78,205,196,0.1)' : 'rgba(255,51,102,0.1)' }};">
                            @if($book->stock > 0)
                                <i class="ki-duotone ki-check fs-4"></i>
                            @else
                                <i class="ki-duotone ki-cross fs-4"></i>
                            @endif
                            {{ $book->stock }} BUKU TERSEDIA
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">⚡ STATUS</span>
                        <span class="badge badge-light-{{ $book->status->value === 'available' ? 'success' : 'secondary' }}"
                            style="font-size:0.78rem; border-radius:0 !important; border:2px solid currentColor !important;">
                            {{ $book->status->value === 'available' ? '✅ Available' : '⛔ Unavailable' }}
                        </span>
                    </div>
                </div>

                {{-- Synopsis --}}
                @if($book->synopsis)
                <div style="background:#fff;border:3px solid var(--comic-dark);box-shadow:4px 4px 0 var(--comic-dark);padding:20px;">
                    <div style="font-family:'Bangers',cursive;font-size:1rem;color:var(--comic-orange);letter-spacing:2px;margin-bottom:12px;">📝 SINOPSIS</div>
                    <div class="synopsis-box">{{ $book->synopsis }}</div>
                </div>
                @endif
            </div>

            {{-- Right Column: Cover & QR --}}
            <div class="col-md-5 d-flex flex-column align-items-center gap-3">
                {{-- Cover --}}
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}"
                        alt="{{ $book->title }}"
                        class="book-cover-large"/>
                @else
                    <div class="book-cover-placeholder-lg">{{ substr($book->title, 0, 1) }}</div>
                @endif

                {{-- QR Code Section --}}
                @if($book->qr_code)
                    <div class="text-center" style="width:100%;">
                        <img src="{{ asset('storage/' . $book->qr_code) }}"
                            alt="QR"
                            class="qr-large"
                            style="width:130px;height:130px;"/>
                        <div style="font-family:'Fredoka One', cursive; font-size:0.68rem; color:#aaa; letter-spacing:2px; margin-top:6px;">QR CODE BUKU</div>
                    </div>

                    <div class="d-flex flex-column gap-2 w-100">
                        <button type="button" onclick="printBookQr()" class="btn" style="background:var(--comic-orange);color:#fff;border:2px solid var(--comic-dark);box-shadow:3px 3px 0 var(--comic-dark);font-family:'Fredoka One',cursive;font-size:0.82rem;border-radius:0;font-weight:900;letter-spacing:1px;">
                            <i class="ki-duotone ki-printer fs-4" style="color:#fff !important;"></i> PRINT QR CODE
                        </button>
                        <form method="POST" action="{{ route('admin.books.regenerate-qr', $book) }}">
                            @csrf
                            <button type="submit" class="btn w-100" style="background:var(--comic-green);color:#fff;border:2px solid var(--comic-dark);box-shadow:3px 3px 0 var(--comic-dark);font-family:'Fredoka One',cursive;font-size:0.82rem;border-radius:0;font-weight:900;letter-spacing:1px;">
                                <i class="ki-duotone ki-refresh fs-4" style="color:#fff !important;"></i> REGENERATE QR
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center" style="background:#fff;border:2px dashed #ccc;padding:20px;width:100%;">
                        <div style="font-family:'Fredoka One',cursive;color:#aaa;font-size:0.8rem;">Belum ada QR Code</div>
                    </div>
                    <form method="POST" action="{{ route('admin.books.regenerate-qr', $book) }}">
                        @csrf
                        <button type="submit" class="btn w-100" style="background:var(--comic-green);color:#fff;border:2px solid var(--comic-dark);box-shadow:3px 3px 0 var(--comic-dark);font-family:'Fredoka One',cursive;font-size:0.82rem;border-radius:0;font-weight:900;letter-spacing:1px;">
                            <i class="ki-duotone ki-qrcode fs-4" style="color:#fff !important;"></i> GENERATE QR
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@include('admin.books.partials.edit-modal')

@push('custom-js')
<script>
function printBookQr() {
    var qrSrc = document.querySelector('.qr-large').src || '';
    var title = {{ Js::from($book->title) }};
    var code = {{ Js::from($book->book_code) }};
    var isbn = {{ Js::from($book->isbn ?? '-') }};
    var author = {{ Js::from($book->author ?? '-') }};
    var category = {{ Js::from($book->category?->name ?? '-') }};

    var html = '<!DOCTYPE html><html><head>';
    html += '<meta charset="UTF-8"/>';
    html += '<title>Print QR - ' + title + '</title>';
    html += '<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">';
    html += '<style>body{margin:0;padding:20px;font-family:sans-serif;background:#fff;}';
    html += '#qr-wrap{border:3px solid #1A1A2E;padding:20px;display:flex;align-items:center;gap:20px;box-shadow:4px 4px 0 #1A1A2E;background:#fff;}';
    html += '#qr-wrap img{width:150px;height:150px;object-fit:contain;}';
    html += '.info{border-left:3px solid #1A1A2E;padding-left:20px;flex:1;}';
    html += '.title{font-family:Fredoka One,sans-serif;font-size:18px;color:#1A1A2E;margin-bottom:10px;}';
    html += '.row{display:flex;gap:8px;margin:4px 0;font-family:Fredoka One,sans-serif;font-size:13px;}';
    html += '.lbl{color:#aaa;font-size:11px;min-width:55px;text-transform:uppercase;}';
    html += '.val{color:#1A1A2E;font-weight:900;}';
    html += '@media print{body{padding:0;}}</style>';
    html += '</head><body>';
    html += '<div id="qr-wrap">';
    html += '<img src="' + qrSrc + '" alt="QR"/>';
    html += '<div class="info">';
    html += '<div class="title">' + title + '</div>';
    html += '<div class="row"><span class="lbl">KODE</span><span class="val">' + code + '</span></div>';
    html += '<div class="row"><span class="lbl">ISBN</span><span class="val">' + isbn + '</span></div>';
    html += '<div class="row"><span class="lbl">PENULIS</span><span class="val">' + author + '</span></div>';
    html += '<div class="row"><span class="lbl">KATEGORI</span><span class="val">' + category + '</span></div>';
    html += '</div></div>';
    html += '<script>window.onload=function(){window.print();}<\/' + 'script>';
    html += '</body></html>';

    var win = window.open('', '_blank', 'width=600,height=400');
    if (win) { win.document.write(html); win.document.close(); }
}
</script>
@endpush
@endsection