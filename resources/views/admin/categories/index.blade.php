@extends('layouts.app')

@section('title', 'Kategori')
@section('page-title', 'Kategori')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Manajemen</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Kategori</li>
</ul>
@endsection

@push('custom-css')
<style>
/── Card ──/
.card {
    border: 3px solid var(--comic-dark) !important;
    box-shadow: 6px 6px 0 var(--comic-dark) !important;
    border-radius: 0 !important;
}
.card .card-header {
    background: var(--comic-dark) !important;
    border-bottom: 3px solid var(--comic-orange) !important;
    padding: 14px 20px;
}
.card .card-header .card-title {
    font-family: 'Bangers', cursive !important;
    color: var(--comic-orange) !important;
    font-size: 1.2rem !important;
    letter-spacing: 3px !important;
    margin: 0;
}

/── Toolbar Button ──/
.btn-toolbar {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    border: 2px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    font-family: 'Fredoka One', cursive;
    font-size: 0.75rem;
    border-radius: 0;
    font-weight: 900;
    letter-spacing: 1px;
    padding: 8px 16px;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-toolbar:hover {
    background: var(--comic-orange);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 4px 4px 0 var(--comic-dark);
}

/── Search Bar ──/
.comic-search-bar {
    background: var(--comic-dark);
    border: 3px solid var(--comic-dark);
    box-shadow: 6px 6px 0 var(--comic-orange);
    padding: 20px 24px;
    margin-bottom: 20px;
    position: relative;
}
.comic-search-bar .form-label {
    font-family: 'Fredoka One', cursive;
    font-size: 0.7rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--comic-orange);
    margin-bottom: 4px;
    display: block;
    font-weight: 900;
}
.comic-search-bar .form-control,
.comic-search-bar .form-select {
    border: 2px solid var(--comic-dark) !important;
    border-radius: 0 !important;
    box-shadow: 3px 3px 0 var(--comic-dark) !important;
    font-family: 'Fredoka One', cursive;
    font-weight: 800;
    color: var(--comic-dark) !important;
    background: #fff !important;
}
.comic-search-bar .form-control:focus {
    border-color: var(--comic-orange) !important;
    box-shadow: 4px 4px 0 var(--comic-orange) !important;
}
.comic-search-bar .form-control::placeholder {
    color: #aaa !important;
    font-weight: 700 !important;
}
.comic-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--comic-orange) !important;
    font-size: 1.2rem;
    z-index: 5;
    pointer-events: none;
}
.comic-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.comic-search-wrap .form-control {
    padding-left: 40px !important;
}

/── Table ──/
.comic-table-wrap {
    overflow-x: auto;
}
.comic-table-wrap table thead tr th {
    background: var(--comic-cream) !important;
    border-bottom: 3px solid var(--comic-dark) !important;
    font-family: 'Fredoka One', cursive !important;
    font-size: 0.68rem !important;
    letter-spacing: 2px !important;
    text-transform: uppercase;
    color: var(--comic-dark) !important;
    padding: 12px 16px !important;
}
.comic-table-wrap table tbody tr:hover td {
    background: rgba(255,107,53,0.06) !important;
}
.comic-table-wrap table tbody tr td {
    border-bottom: 1px solid rgba(26,26,46,0.08) !important;
    padding: 10px 16px !important;
    vertical-align: middle;
}

/── Action Buttons ──/
.action-group {
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: flex-end;
}
.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Fredoka One', cursive;
    font-size: 0.7rem;
    font-weight: 900;
    letter-spacing: 1px;
    padding: 6px 14px;
    border-radius: 0;
    border: 2.5px solid;
    transition: all 0.2s ease;
    text-decoration: none;
    white-space: nowrap;
}
.action-btn-edit {
    background: var(--comic-yellow);
    color: var(--comic-dark);
    border-color: var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
}
.action-btn-edit:hover {
    background: var(--comic-orange);
    color: #fff;
    border-color: var(--comic-orange);
    transform: translateY(-2px);
    box-shadow: 4px 4px 0 var(--comic-dark);
}
.action-btn-delete {
    background: var(--comic-cream);
    color: var(--comic-red);
    border-color: var(--comic-red);
    box-shadow: 3px 3px 0 var(--comic-red);
}
.action-btn-delete:hover {
    background: var(--comic-red);
    color: #fff;
    border-color: var(--comic-red);
    transform: translateY(-2px);
    box-shadow: 4px 4px 0 var(--comic-dark);
}

/── Empty State ──/
.comic-empty {
    text-align: center;
    padding: 40px 20px;
}
.empty-emoji {
    display: block;
    font-size: 3rem;
    margin-bottom: 8px;
}
.empty-title {
    font-family: 'Bangers', cursive;
    font-size: 1.2rem;
    color: var(--comic-dark);
    letter-spacing: 2px;
}
.empty-sub {
    font-size: 0.82rem;
    color: #888;
    margin-top: 4px;
}
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <span class="fw-bold text-white" style="font-family:'Bangers',cursive; letter-spacing:2px; font-size:1.1rem;">📂 DAFTAR KATEGORI</span>
        </div>
        <div class="card-toolbar d-flex align-items-center gap-2">
            <a href="{{ route('admin.categories.create') }}" class="btn-toolbar">
                <i class="ki-duotone ki-plus fs-2"></i> Tambah Kategori
            </a>
        </div>
    </div>

    <div class="card-body py-4 px-4">

        {{-- Comic Search Bar --}}
        <form method="GET" action="{{ route('admin.categories.index') }}" class="comic-search-bar">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">🔍 Pencarian</label>
                    <div class="comic-search-wrap">
                        <i class="ki-duotone ki-magnifier comic-search-icon"></i>
                        <input type="text" name="search" class="form-control form-control-solid"
                            placeholder="Cari nama kategori..." value="{{ request('search') }}"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-comic w-100">🔍 CARI</button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="comic-table-wrap">
            <table class="table align-middle table-row-dashed fs-6 gy-4">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th style="min-width:50px;">#</th>
                        <th style="min-width:150px;">Nama</th>
                        <th style="min-width:140px;">Slug</th>
                        <th style="min-width:80px;">Jumlah</th>
                        <th style="min-width:180px;">Deskripsi</th>
                        <th class="text-end" style="min-width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($categories as $index => $category)
                    <tr>
                        <td>
                            <span class="fw-bold" style="color:var(--comic-orange); font-family:'Bangers',cursive; font-size:1rem;">
                                {{ str_pad((string)($categories->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark" style="font-size:0.9rem;">{{ $category->name }}</span>
                        </td>
                        <td>
                            <span class="badge badge-light-primary" style="font-size:0.72rem; border-radius:0 !important;">{{ $category->slug }}</span>
                        </td>
                        <td>
                            <span class="fw-black" style="color:var(--comic-blue); font-family:'Bangers',cursive; font-size:1.1rem;">
                                {{ $category->books_count }}
                            </span>
                            <span style="font-size:0.7rem; color:#aaa; font-weight:700;">buku</span>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:0.82rem;">{{ Str::limit($category->description, 40) ?: '-' }}</span>
                        </td>
                        <td class="text-end">
                            <div class="action-group">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="action-btn action-btn-edit" title="Edit">
                                    <i class="ki-duotone ki-pencil fs-5"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete btn-delete" title="Hapus">
                                        <i class="ki-duotone ki-trash fs-5"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="comic-empty">
                                <span class="empty-emoji">📂</span>
                                <div class="empty-title">TIDAK ADA KATEGORI</div>
                                <div class="empty-sub">Tambah kategori untuk organize koleksi buku</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('layouts.partials._pagination', ['paginator' => $categories])
    </div>
</div>
@endsection

@push('vendor-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus kategori?',
                text: 'Buku dengan kategori ini tidak akan terhapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#FF3366',
            }).then(function (r) {
                if (r.isConfirmed) btn.closest('form').submit();
            });
        });
    });
});
</script>
@endpush
