@extends('layouts.app')

@section('title', 'Audit Log')
@section('page-title', 'Riwayat Aktivitas')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Pengaturan</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Audit Log</li>
</ul>
@endsection

@push('custom-css')
<style>
.action-badge { font-size:0.72rem !important; border-radius:0 !important; font-weight:900 !important; }
.action-badge-create { border:2px solid #00C896 !important; color:#00C896 !important; }
.action-badge-update { border:2px solid var(--comic-yellow) !important; color:#b07d00 !important; background:#fffbe6 !important; }
.action-badge-delete { border:2px solid var(--comic-red) !important; color:var(--comic-red) !important; }
.change-item { background: var(--comic-cream); border:2px solid var(--comic-dark); padding:6px 10px; margin-bottom:4px; font-size:0.78rem; font-weight:700; box-shadow:2px 2px 0 var(--comic-dark); }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <span class="fw-bold text-white" style="font-family:'Bangers',cursive; letter-spacing:2px; font-size:1.1rem;">📋 RIWAYAT AKTIVITAS</span>
        </div>
        <div class="card-toolbar d-flex align-items-center gap-2">
            <a href="{{ route('admin.audit-logs.index') }}"
                class="btn-filter {{ !request('action') ? 'active' : '' }}">📋 Semua</a>
            <a href="{{ route('admin.audit-logs.index', ['action' => 'create']) }}"
                class="btn-filter {{ request('action') === 'create' ? 'active' : '' }}">✅ Buat</a>
            <a href="{{ route('admin.audit-logs.index', ['action' => 'update']) }}"
                class="btn-filter {{ request('action') === 'update' ? 'active' : '' }}">✏️ Update</a>
            <a href="{{ route('admin.audit-logs.index', ['action' => 'delete']) }}"
                class="btn-filter {{ request('action') === 'delete' ? 'active' : '' }}">🗑️ Hapus</a>
        </div>
    </div>

    <div class="card-body py-4 px-4">

        {{-- Comic Search Bar --}}
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="comic-search-bar">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">🔍 Pencarian</label>
                    <div class="comic-search-wrap">
                        <i class="ki-duotone ki-magnifier comic-search-icon"></i>
                        <input type="text" name="search" class="form-control form-control-solid"
                            placeholder="Nama user..." value="{{ request('search') }}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">📦 Tipe</label>
                    <select name="type" class="form-select form-select-solid" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="Book" {{ request('type') === 'Book' ? 'selected' : '' }}>Buku</option>
                        <option value="Member" {{ request('type') === 'Member' ? 'selected' : '' }}>Anggota</option>
                        <option value="Category" {{ request('type') === 'Category' ? 'selected' : '' }}>Kategori</option>
                        <option value="User" {{ request('type') === 'User' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-comic w-100">🔍 CARI</button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="comic-table-wrap">
            <table class="table align-middle table-row-dashed fs-6 gy-4">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th style="min-width:130px;">Waktu</th>
                        <th style="min-width:150px;">User</th>
                        <th style="min-width:130px;">Aksi</th>
                        <th style="min-width:220px;">Detail Perubahan</th>
                        <th style="min-width:100px;">IP</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <span class="text-muted" style="font-size:0.8rem; font-weight:700;">
                                📅 {{ $log->created_at->format('d/m/Y') }}
                            </span>
                            <div style="font-size:0.75rem; color:#aaa; font-weight:800; margin-top:2px;">
                                🕐 {{ $log->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="symbol symbol-30px flex-shrink-0">
                                    <div class="symbol-label fs-5 fw-bold"
                                        style="background:var(--comic-cream); color:var(--comic-dark); border:2px solid var(--comic-dark); font-size:0.75rem;">
                                        {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                    </div>
                                </div>
                                <span class="fw-bold text-dark" style="font-size:0.85rem;">
                                    {{ $log->user?->name ?? 'System' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($log->action === 'create')
                                <span class="badge action-badge action-badge-create">✅ MEMBUAT</span>
                            @elseif($log->action === 'update')
                                <span class="badge action-badge action-badge-update">✏️ UPDATE</span>
                            @elseif($log->action === 'delete')
                                <span class="badge action-badge action-badge-delete">🗑️ HAPUS</span>
                            @endif
                            <div style="margin-top:4px;">
                                <span class="badge badge-light-secondary" style="font-size:0.65rem; border-radius:0 !important;">
                                    {{ class_basename($log->model_type) }}
                                </span>
                            </div>
                        </td>
                        <td style="font-size:0.8rem;">
                            @if($log->action === 'update' && $log->changes)
                                @foreach($log->changes as $field => $change)
                                    <div class="change-item">
                                        <strong>{{ $field }}:</strong>
                                        <span style="color:var(--comic-red);">{{ $change['old'] ?? 'null' }}</span>
                                        →
                                        <span style="color:var(--comic-green);">{{ $change['new'] ?? 'null' }}</span>
                                    </div>
                                @endforeach
                            @elseif($log->action === 'create')
                                <div style="padding:6px 10px; background:#f0fff4; border:2px solid var(--comic-green); font-weight:700; font-size:0.78rem; box-shadow:2px 2px 0 var(--comic-green);">
                                    ✅ Data baru dibuat
                                </div>
                            @elseif($log->action === 'delete')
                                <div style="padding:6px 10px; background:#fff0f0; border:2px solid var(--comic-red); font-weight:700; font-size:0.78rem; box-shadow:2px 2px 0 var(--comic-red); color:var(--comic-red);">
                                    🗑️ Data dihapus
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:0.75rem; font-family:monospace; font-weight:700;">
                                {{ $log->ip_address ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="comic-empty">
                                <span class="empty-emoji">📋</span>
                                <div class="empty-title">TIDAK ADA AKTIVITAS</div>
                                <div class="empty-sub">Aktivitas sistem akan tercatat di sini</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('layouts.partials._pagination', ['paginator' => $logs])
    </div>
</div>
@endsection