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

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <span class="fw-bold text-white" style="font-family:'Bangers',cursive; letter-spacing:2px; font-size:1.1rem;">📂 DAFTAR KATEGORI</span>
        </div>
        <div class="card-toolbar d-flex align-items-center gap-2">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
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
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-comic-edit" title="Edit">
                                <i class="ki-duotone ki-pencil fs-4"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-comic-delete btn-delete" title="Hapus">
                                    <i class="ki-duotone ki-trash fs-4"></i>
                                </button>
                            </form>
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
