@extends('layouts.app')

@section('title', 'Data Buku')
@section('page-title', 'Data Buku')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Manajemen</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Data Buku</li>
</ul>
@endsection

@push('vendor-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('custom-css')
<style>
/.* ── Search Bar ── .*/
.admin-search-bar {
    background: var(--comic-dark);
    border: 3px solid var(--comic-dark);
    box-shadow: 6px 6px 0 var(--comic-orange);
    padding: 20px 24px;
    margin-bottom: 20px;
    position: relative;
}
.admin-search-bar::before {
    content: '';
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(45deg, transparent, transparent 30px, rgba(255,107,53,0.05) 30px, rgba(255,107,53,0.05) 31px);
    pointer-events: none;
}
.admin-search-bar .form-control,
.admin-search-bar .form-select {
    border: 2px solid var(--comic-dark) !important;
    border-radius: 0 !important;
    font-weight: 800;
    box-shadow: 3px 3px 0 var(--comic-dark) !important;
    background: #fff !important;
    color: var(--comic-dark) !important;
    padding-right: 14px !important;
}
.admin-search-bar .form-control:focus,
.admin-search-bar .form-select:focus {
    border-color: var(--comic-orange) !important;
    box-shadow: 4px 4px 0 var(--comic-orange) !important;
    outline: none !important;
}
.admin-search-bar .form-control::placeholder {
    color: #aaa !important;
    font-weight: 700 !important;
}
.admin-search-bar .form-label {
    font-family: 'Fredoka One', cursive;
    font-size: 0.7rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--comic-orange);
    margin-bottom: 4px;
    display: block;
    font-weight: 900;
}
.admin-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--comic-orange) !important;
    font-size: 1.2rem;
    z-index: 5;
    pointer-events: none;
}
.admin-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.admin-search-wrap .form-control {
    padding-left: 40px !important;
}
/.* ── Table ── */
.table tbody tr:hover {
    background: rgba(255,107,53,0.06) !important;
}
.table td {
    vertical-align: middle;
}
.table .text-gray-800 {
    font-weight: 800;
}
.card-body-table {
    overflow-x: auto !important;
}
.card-body-table .table {
    min-width: 100%;
    white-space: nowrap;
}
.card-body-table .table > thead > tr > th:first-child,
.card-body-table .table > tbody > tr > td:first-child {
    padding-left: 18px;
}
.card-body-table .table > thead > tr > th:last-child,
.card-body-table .table > tbody > tr > td:last-child {
    padding-right: 22px;
}
/.* ── Toolbar Buttons ── */
.btn-toolbar-comic {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    border: 2px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    font-family: 'Fredoka One', cursive;
    font-size: 0.78rem;
    border-radius: 0;
    font-weight: 900;
    letter-spacing: 1px;
    padding: 8px 14px;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-toolbar-comic:hover {
    background: var(--comic-orange);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 4px 4px 0 var(--comic-dark);
}
.btn-toolbar-green {
    background: var(--comic-green);
    color: #fff;
    border: 2px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    font-family: 'Fredoka One', cursive;
    font-size: 0.78rem;
    border-radius: 0;
    font-weight: 900;
    letter-spacing: 1px;
    padding: 8px 14px;
    transition: all 0.2s ease;
}
.btn-toolbar-green:hover {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    transform: translateY(-2px);
    box-shadow: 4px 4px 0 var(--comic-dark);
}
.btn-toolbar-blue {
    background: var(--comic-blue);
    color: #fff;
    border: 2px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    font-family: 'Fredoka One', cursive;
    font-size: 0.78rem;
    border-radius: 0;
    font-weight: 900;
    letter-spacing: 1px;
    padding: 8px 14px;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-toolbar-blue:hover {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    transform: translateY(-2px);
    box-shadow: 4px 4px 0 var(--comic-dark);
}
/.* ── Action Buttons ── */
.btn-action-detail {
    background: #fff;
    border: 2px solid var(--comic-blue);
    box-shadow: 3px 3px 0 var(--comic-blue);
    color: var(--comic-blue);
    border-radius: 0;
    font-weight: 800;
    font-size: 0.8rem;
    padding: 6px 12px;
    min-width: 38px;
    min-height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.btn-action-detail:hover {
    background: var(--comic-blue);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 4px 4px 0 var(--comic-dark);
}
.btn-action-edit {
    background: var(--comic-orange);
    border: 2px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    color: #fff;
    border-radius: 0;
    font-weight: 800;
    font-size: 0.8rem;
    padding: 6px 12px;
    min-width: 38px;
    min-height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.btn-action-edit:hover {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    transform: translateY(-2px);
    box-shadow: 4px 4px 0 var(--comic-dark);
}
.btn-action-delete {
    background: #fff;
    border: 2px solid var(--comic-red);
    box-shadow: 3px 3px 0 var(--comic-red);
    color: var(--comic-red);
    border-radius: 0;
    font-weight: 800;
    font-size: 0.8rem;
    padding: 6px 12px;
    min-width: 38px;
    min-height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.btn-action-delete:hover {
    background: var(--comic-red);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 4px 4px 0 var(--comic-dark);
}
/.* ── Pagination ── */
.admin-pagination {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    margin-top: 28px;
}
.admin-pagination .pagination {
    gap: 5px;
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}
.page-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    height: 42px;
    padding: 5px 10px;
    background: #fff;
    border: 3px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    font-family: 'Fredoka One', cursive;
    font-size: 0.82rem;
    color: var(--comic-dark);
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    border-radius: 0;
    white-space: nowrap;
}
.page-btn:hover:not(.page-btn-disabled):not(.page-btn-active) {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    transform: translateY(-2px);
    box-shadow: 4px 5px 0 var(--comic-dark);
}
.page-btn-active {
    background: var(--comic-orange) !important;
    color: #fff !important;
    border-color: var(--comic-dark) !important;
    box-shadow: 4px 4px 0 var(--comic-dark) !important;
    transform: translateY(-2px);
    cursor: default;
}
.page-btn-disabled {
    background: #eee !important;
    color: #aaa !important;
    border-color: #ccc !important;
    box-shadow: 2px 2px 0 #ccc !important;
    cursor: not-allowed;
    pointer-events: none;
}
.page-info {
    font-family: 'Fredoka One', cursive;
    font-size: 0.7rem;
    color: #aaa;
    letter-spacing: 1px;
    text-transform: uppercase;
}
/.* ── Misc ── */
.result-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--comic-orange);
    border: 2px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    padding: 6px 14px;
    font-family: 'Fredoka One', cursive;
    font-size: 0.8rem;
    color: #fff;
    margin-bottom: 16px;
}
.book-cover-thumb {
    width: 44px;
    height: 56px;
    object-fit: cover;
    border: 2px solid var(--comic-dark);
    box-shadow: 2px 2px 0 var(--comic-dark);
    border-radius: 0;
    display: block;
}
.book-cover-placeholder {
    width: 44px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--comic-cream);
    border: 2px solid var(--comic-dark);
    box-shadow: 2px 2px 0 var(--comic-dark);
    font-family: 'Bangers', cursive;
    font-size: 1.3rem;
    color: var(--comic-dark);
}
.qr-thumb {
    width: 44px;
    height: 44px;
    object-fit: contain;
    border: 2px solid var(--comic-dark);
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: block;
}
.qr-thumb:hover {
    transform: scale(1.1);
    box-shadow: 3px 3px 0 var(--comic-dark);
}
.qr-no-img {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eee;
    border: 2px dashed #ccc;
    font-size: 0.7rem;
    color: #aaa;
    font-weight: 900;
    font-family: 'Fredoka One', cursive;
    text-decoration: none;
}
</style>
@endpush

@section('content')
{{-- ── Card ── --}}
<div class="card">
    <div class="card-header border-0 pt-4 pb-0">
        <div class="card-title">
            <span class="fw-bold text-white" style="font-family:'Bangers',cursive; letter-spacing:2px; font-size:1.1rem;">📕 DAFTAR BUKU</span>
        </div>
        <div class="card-toolbar d-flex align-items-center gap-2">
            <a href="{{ route('admin.books.create') }}" class="btn-toolbar-comic btn-sm">
                <i class="ki-duotone ki-plus fs-4" style="color:inherit;"></i> Tambah Buku
            </a>
            <a href="{{ route('admin.export.books') }}" class="btn-toolbar-blue btn-sm">
                <i class="ki-duotone ki-tablet-ks fs-4" style="color:inherit;"></i> Export CSV
            </a>
            <form method="POST" action="{{ route('admin.books.bulk-qr') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn-toolbar-green btn-sm"
                    onclick="return confirm('Generate QR Code untuk semua buku yang belum punya?')">
                    <i class="ki-duotone ki-qrcode fs-4" style="color:inherit;"></i> Generate QR Masal
                </button>
            </form>
            <a href="{{ route('admin.books.bulk-qr.print') }}" target="_blank" class="btn-toolbar-comic btn-sm">
                <i class="ki-duotone ki-printer fs-4" style="color:var(--comic-dark);"></i> Print All QR
            </a>
        </div>
    </div>

    <div class="card-body py-4 px-4">
        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('admin.books.index') }}" class="admin-search-bar">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">🔍 Pencarian</label>
                    <div class="admin-search-wrap">
                        <i class="ki-duotone ki-magnifier admin-search-icon"></i>
                        <input type="text" name="search"
                            class="form-control form-control-solid"
                            placeholder="Cari judul, penulis, atau kode..."
                            value="{{ request('search') }}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">🗂️ Kategori</label>
                    <select name="category" class="form-select form-select-solid"
                        onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn" style="
                        background:var(--comic-orange); color:#fff;
                        border:2px solid var(--comic-dark); box-shadow:3px 3px 0 var(--comic-dark);
                        font-family:'Fredoka One',cursive; font-size:0.9rem;
                        border-radius:0; font-weight:900; letter-spacing:1px; padding:10px 16px;">
                        🔍 CARI
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.books.index') }}" class="btn" style="
                        background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.8);
                        border:2px solid rgba(255,255,255,0.4); box-shadow:3px 3px 0 rgba(255,255,255,0.1);
                        font-family:'Fredoka One',cursive; font-size:0.9rem;
                        border-radius:0; font-weight:900; letter-spacing:1px; padding:10px 16px;
                        text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
                        ↺ RESET
                    </a>
                </div>
            </div>
        </form>

        {{-- Result Info --}}
        @if(request('search') || request('category'))
        <div>
            <span class="result-badge">
                📋 Hasil:
                @if(request('search'))"<strong>{{ request('search') }}</strong>"@endif
                @if(request('category'))
                    di kategori <strong>{{ $categories->find(request('category'))?->name }}</strong>
                @endif
                &mdash; {{ $books->total() }} buku ditemukan
            </span>
        </div>
        @endif

        {{-- Table --}}
        <div class="table-responsive card-body-table">
            <table class="table align-middle table-row-dashed fs-6 gy-4">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th style="min-width:60px;">Sampul</th>
                        <th style="min-width:100px;">Kode</th>
                        <th style="min-width:200px;">Judul</th>
                        <th style="min-width:120px;">Kategori</th>
                        <th style="min-width:140px;">Penulis</th>
                        <th style="min-width:60px;">Stok</th>
                        <th style="min-width:80px;">Status</th>
                        <th style="min-width:60px;">QR</th>
                        <th class="text-end" style="min-width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($books as $book)
                    <tr>
                        <td>
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}"
                                    alt="{{ $book->title }}"
                                    class="book-cover-thumb"/>
                            @else
                                <div class="book-cover-placeholder">
                                    {{ substr($book->title, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark" style="font-size:0.82rem; font-family:'Fredoka One', cursive; letter-spacing:1px;">
                                {{ $book->book_code }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.books.show', $book) }}"
                                class="text-gray-800 fw-bold text-hover-primary text-decoration-none"
                                style="font-size:0.88rem;">
                                {{ Str::limit($book->title, 40) }}
                            </a>
                        </td>
                        <td>
                            @if($book->category)
                                <span class="badge badge-light-primary"
                                    style="font-size:0.72rem; border-radius:0; border:2px solid currentColor;">
                                    {{ $book->category->name }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:0.82rem;">
                                {{ Str::limit($book->author ?? '-', 25) }}
                            </span>
                        </td>
                        <td>
                            <span style="
                                font-family:'Bangers',cursive; font-size:1.1rem;
                                color: {{ $book->stock > 0 ? 'var(--comic-blue)' : 'var(--comic-red)' }};">
                                {{ $book->stock }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-light-{{ $book->status->value === 'available' ? 'success' : 'secondary' }}"
                                style="font-size:0.72rem; border-radius:0; border:2px solid currentColor;">
                                {{ ucfirst($book->status->value) }}
                            </span>
                        </td>
                        <td>
                            @if($book->qr_code)
                                <a href="{{ route('admin.books.show', $book) }}" title="Lihat detail & QR">
                                    <img src="{{ asset('storage/' . $book->qr_code) }}"
                                        alt="QR"
                                        class="qr-thumb"/>
                                </a>
                            @else
                                <a href="{{ route('admin.books.show', $book) }}" class="qr-no-img" title="Belum ada QR">?</a>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.books.show', $book) }}"
                                class="btn-action-detail" title="Detail">
                                <i class="ki-duotone ki-eye fs-4"></i>
                            </a>
                            <a href="{{ route('admin.books.show', $book) }}?edit=1"
                                class="btn-action-edit" title="Edit">
                                <i class="ki-duotone ki-pencil fs-4"></i>
                            </a>
                            <form method="POST"
                                action="{{ route('admin.books.destroy', $book) }}"
                                class="d-inline btn-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete" title="Hapus">
                                    <i class="ki-duotone ki-trash fs-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="text-center py-10">
                                <div style="font-size:3rem; margin-bottom:10px;">📭</div>
                                <div style="font-family:'Bangers',cursive; font-size:1.2rem; letter-spacing:2px; color:var(--comic-dark);">
                                    BUKU TIDAK DITEMUKAN
                                </div>
                                <div class="text-muted fw-bold mt-2" style="font-size:0.85rem;">
                                    Coba kata kunci lain atau
                                    <a href="{{ route('admin.books.index') }}"
                                        class="text-orange fw-bold text-decoration-none">reset pencarian</a>.
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @php
            $currentPage = $books->currentPage();
            $lastPage = $books->lastPage();
            $onFirst = $books->onFirstPage();
            $onLast = !$books->hasMorePages();
            $total = $books->total();
        @endphp

        @if($lastPage > 1)
        <div class="admin-pagination">
            <ul class="pagination">
                @if($onFirst)
                    <li><span class="page-btn page-btn-disabled">◀&nbsp;PREV</span></li>
                @else
                    <li><a class="page-btn" href="{{ $books->previousPageUrl() }}">◀&nbsp;PREV</a></li>
                @endif

                @php
                    $window = 2;
                    $start = max(1, $currentPage - $window);
                    $end = min($lastPage, $currentPage + $window);
                    $pages = range($start, $end);
                @endphp

                @if($start > 1)
                    <li><a class="page-btn" href="{{ $books->url(1) }}">01</a></li>
                    @if($start > 2)
                        <li><span class="page-btn" style="background:#fff; border-color:transparent; box-shadow:none; cursor:default; color:#aaa;">...</span></li>
                    @endif
                @endif

                @foreach($pages as $page)
                    @if($page === $currentPage)
                        <li><span class="page-btn page-btn-active">{{ str_pad((string)$page, 2, '0', STR_PAD_LEFT) }}</span></li>
                    @else
                        <li><a class="page-btn" href="{{ $books->url($page) }}">{{ str_pad((string)$page, 2, '0', STR_PAD_LEFT) }}</a></li>
                    @endif
                @endforeach

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <li><span class="page-btn" style="background:#fff; border-color:transparent; box-shadow:none; cursor:default; color:#aaa;">...</span></li>
                    @endif
                    <li><a class="page-btn" href="{{ $books->url($lastPage) }}">{{ str_pad((string)$lastPage, 2, '0', STR_PAD_LEFT) }}</a></li>
                @endif

                @if($onLast)
                    <li><span class="page-btn page-btn-disabled">NEXT&nbsp;▶</span></li>
                @else
                    <li><a class="page-btn" href="{{ $books->nextPageUrl() }}">NEXT&nbsp;▶</a></li>
                @endif
            </ul>
            <div class="page-info">
                <span>Halaman {{ $currentPage }} dari {{ $lastPage }}</span>
                <span>&bull;</span>
                <span>Total {{ number_format($total) }} buku</span>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Delete confirmation ──
    document.querySelectorAll('.btn-delete-form').forEach(function (form) {
        var btn = form.querySelector('button[type="submit"]');
        if (!btn) return;
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus buku ini?',
                text: 'Data tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#FF3366',
            }).then(function (r) {
                if (r.isConfirmed) form.submit();
            });
        });
    });

    // ── Auto-open edit modal if ?edit=1 ──
    var params = new URLSearchParams(window.location.search);
    if (params.get('edit') === '1') {
        var modal = document.getElementById('modal-edit-book');
        if (modal) {
            var bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            // Clean URL param after showing modal
            params.delete('edit');
            var newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.replaceState({}, '', newUrl);
        }
    }
});
</script>
@endpush
