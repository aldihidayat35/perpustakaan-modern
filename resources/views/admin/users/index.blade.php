@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Manajemen</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Daftar User</li>
</ul>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <span class="fw-bold text-white" style="font-family:'Bangers',cursive; letter-spacing:2px; font-size:1.1rem;">👤 DAFTAR PENGGUNA</span>
        </div>
        <div class="card-toolbar d-flex align-items-center gap-2">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> Tambah User
            </a>
        </div>
    </div>

    <div class="card-body py-4 px-4">

        {{-- Comic Search + Filter Bar --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="comic-search-bar">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">🔍 Pencarian</label>
                    <div class="comic-search-wrap">
                        <i class="ki-duotone ki-magnifier comic-search-icon"></i>
                        <input type="text" name="search" class="form-control form-control-solid"
                            placeholder="Nama atau email..." value="{{ request('search') }}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">🎭 Role</label>
                    <select name="role" class="form-select form-select-solid" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">⚡ Status</label>
                    <select name="status" class="form-select form-select-solid" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
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
                        <th style="min-width:200px;">User</th>
                        <th style="min-width:100px;">Role</th>
                        <th style="min-width:80px;">Status</th>
                        <th style="min-width:120px;">Bergabung</th>
                        <th class="text-end" style="min-width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <span class="fw-bold" style="color:var(--comic-orange); font-family:'Bangers',cursive; font-size:1rem;">
                                {{ str_pad((string)($loop->iteration + ($users->currentPage() - 1) * $users->perPage()), 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="symbol symbol-40px flex-shrink-0">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                            style="width:40px; height:40px; object-fit:cover; border:2px solid var(--comic-dark);"/>
                                    @else
                                        <div class="symbol-label fs-5 fw-bold"
                                            style="background:var(--comic-cream); color:var(--comic-dark); border:2px solid var(--comic-dark);">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size:0.88rem;">{{ $user->name }}</span>
                                    <span class="text-muted" style="font-size:0.75rem;">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-light-{{ $user->role === 'admin' ? 'danger' : 'primary' }}"
                                style="font-size:0.72rem;">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-light-{{ $user->is_active ? 'success' : 'secondary' }}"
                                style="font-size:0.72rem;">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:0.82rem;">{{ $user->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-comic-edit" title="Edit">
                                <i class="ki-duotone ki-pencil fs-4"></i>
                            </a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-comic-delete btn-delete" title="Hapus">
                                        <i class="ki-duotone ki-trash fs-4"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="comic-empty">
                                <span class="empty-emoji">👤</span>
                                <div class="empty-title">TIDAK ADA PENGGUNA</div>
                                <div class="empty-sub">Tambah user baru untuk akses sistem</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('layouts.partials._pagination', ['paginator' => $users])
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
                title: 'Hapus user ini?',
                text: 'Aksi ini tidak bisa dibatalkan.',
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
