@extends('layouts.app')

@section('title', 'Pengembalian Buku')
@section('page-title', 'Pengembalian Buku')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Transaksi</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Pengembalian</li>
</ul>
@endsection

@push('vendor-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('custom-css')
<style>
.return-card {
    border: 3px solid var(--comic-dark) !important;
    box-shadow: 5px 5px 0 var(--comic-dark) !important;
    border-radius: 0 !important;
}
.return-card .card-header {
    background: var(--comic-dark) !important;
    border-bottom: 3px solid var(--comic-orange) !important;
}
.return-card .card-header .card-title {
    font-family: 'Bangers', cursive !important;
    letter-spacing: 2px !important;
    color: var(--comic-orange) !important;
    font-size: 1.1rem !important;
}
.return-row:hover { background: rgba(255,107,53,0.05) !important; }
.return-member-avatar {
    width: 38px; height: 38px;
    background: var(--comic-cream);
    border: 2px solid var(--comic-dark);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Bangers', cursive;
    font-size: 1rem;
    color: var(--comic-dark);
    flex-shrink: 0;
}
.due-chip {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
}
.due-chip-main {
    font-family: 'Fredoka One', cursive;
    font-size: 0.82rem;
    font-weight: 900;
}
.due-chip-late {
    font-family: 'Fredoka One', cursive;
    font-size: 0.65rem;
    font-weight: 900;
    color: var(--comic-red);
}
.status-badge-ret {
    font-size:0.72rem !important;
    border-radius:0 !important;
    font-weight:900 !important;
    border:2px solid currentColor !important;
}
.status-active { border-color: var(--comic-blue) !important; color: var(--comic-blue) !important; background: rgba(78,205,196,0.08) !important; }
.status-late   { border-color: var(--comic-red) !important; color: var(--comic-red) !important; background: rgba(255,51,102,0.08) !important; }
.status-returned { border-color: var(--comic-green) !important; color: var(--comic-green) !important; background: rgba(0,200,150,0.08) !important; }
.btn-process {
    background: var(--comic-orange) !important;
    border: 2px solid var(--comic-dark) !important;
    box-shadow: 3px 3px 0 var(--comic-dark) !important;
    color: #fff !important;
    border-radius: 0 !important;
    font-family: 'Fredoka One', cursive !important;
    font-size: 0.78rem !important;
    font-weight: 900 !important;
    letter-spacing: 1px;
    padding: 6px 14px !important;
    transition: all 0.2s ease;
}
.btn-process:hover {
    background: var(--comic-yellow) !important;
    color: var(--comic-dark) !important;
    transform: translateY(-2px);
    box-shadow: 4px 5px 0 var(--comic-dark) !important;
}
.btn-process i { color: #fff !important; }
.btn-process:hover i { color: var(--comic-dark) !important; }
</style>
@endpush

@section('content')
<div class="card return-card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <span class="fw-bold text-white" style="font-family:'Bangers',cursive; letter-spacing:2px; font-size:1.1rem;">📥 DAFTAR PENGEMBALIAN</span>
        </div>
        <div class="card-toolbar d-flex align-items-center gap-2">
            {{-- Status Filter Tabs --}}
            <a href="{{ route('admin.returns.index') }}"
                class="btn-filter {{ !$status ? 'active' : '' }}">📋 Semua</a>
            <a href="{{ route('admin.returns.index', ['status' => 'active']) }}"
                class="btn-filter {{ $status === 'active' ? 'active' : '' }}">📤 Aktif</a>
            <a href="{{ route('admin.returns.index', ['status' => 'late']) }}"
                class="btn-filter {{ $status === 'late' ? 'active' : '' }}">⚠️ Terlambat</a>
            <a href="{{ route('admin.returns.index', ['status' => 'returned']) }}"
                class="btn-filter {{ $status === 'returned' ? 'active' : '' }}">✅ Dikembalikan</a>
        </div>
    </div>

    <div class="card-body py-4 px-4">

        {{-- Table --}}
        <div class="comic-table-wrap">
            <table class="table align-middle table-row-dashed fs-6 gy-4">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th style="min-width:130px;">Kode Transaksi</th>
                        <th style="min-width:170px;">Anggota</th>
                        <th style="min-width:80px;">Jumlah</th>
                        <th style="min-width:140px;">Jatuh Tempo</th>
                        <th style="min-width:100px;">Status</th>
                        <th class="text-end" style="min-width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($borrowings as $borrowing)
                    <tr class="return-row">
                        {{-- Kode Transaksi --}}
                        <td>
                            <span class="fw-bold text-dark" style="font-family:'Fredoka One',cursive; font-size:0.85rem; letter-spacing:1px;">
                                {{ $borrowing->transaction_code }}
                            </span>
                        </td>

                        {{-- Anggota --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="return-member-avatar">
                                    {{ strtoupper(substr($borrowing->member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size:0.88rem;">
                                        {{ $borrowing->member->name }}
                                    </span>
                                    <span class="text-muted" style="font-size:0.72rem; font-weight:800;">
                                        {{ $borrowing->member->member_code }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Jumlah Buku --}}
                        <td>
                            <span class="badge badge-light-primary" style="font-size:0.78rem; border-radius:0 !important; font-weight:900; border:2px solid var(--comic-blue) !important; color:var(--comic-blue) !important;">
                                📕 {{ $borrowing->details->count() }} buku
                            </span>
                        </td>

                        {{-- Jatuh Tempo --}}
                        <td>
                            <div class="due-chip">
                                @if($borrowing->isOverdue())
                                    <span class="due-chip-main" style="color:var(--comic-red);">
                                        {{ $borrowing->due_date->format('d M Y') }}
                                    </span>
                                    <span class="due-chip-late">
                                        ⚠️ {{ $borrowing->daysOverdue() }} hari terlambat
                                    </span>
                                @else
                                    <span class="due-chip-main" style="color:#888;">
                                        {{ $borrowing->due_date->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Status --}}
                        <td>
                            @php
                                $s = match($borrowing->status->value) {
                                    'active'   => ['cls' => 'status-active',   'txt' => '📤 AKTIF'],
                                    'late'     => ['cls' => 'status-late',     'txt' => '⚠️ TERLAMBAT'],
                                    'returned' => ['cls' => 'status-returned', 'txt' => '✅ KEMBALI'],
                                    default    => ['cls' => '', 'txt' => ucfirst($borrowing->status->value)],
                                };
                            @endphp
                            <span class="badge status-badge-ret {{ $s['cls'] }}">{{ $s['txt'] }}</span>
                        </td>

                        {{-- Aksi --}}
                        <td class="text-end">
                            @if($borrowing->status->value !== 'returned')
                                <button type="button" class="btn-process"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-return-{{ $borrowing->id }}">
                                    <i class="ki-duotone ki-check-square fs-4"></i>
                                    PROSES
                                </button>
                            @else
                                <span style="font-family:'Fredoka One',cursive; font-size:0.75rem; color:var(--comic-green); letter-spacing:1px;">
                                    ✅ SELESAI
                                </span>
                            @endif
                        </td>
                    </tr>

                    {{-- Modal Proses Return per Borrowing --}}
                    <tr>
                        <td colspan="6" style="padding:0 !important; border:none !important;">
                            <div class="modal fade" id="modal-return-{{ $borrowing->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-900px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 style="font-family:'Bangers',cursive; letter-spacing:2px; color:var(--comic-orange);">
                                                📥 PROSES PENGEMBALIAN
                                            </h2>
                                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('admin.returns.store', $borrowing) }}">
                                            @csrf
                                            <div class="modal-body">

                                                {{-- Header Info --}}
                                                <div style="background:var(--comic-cream); border:2px solid var(--comic-dark); box-shadow:4px 4px 0 var(--comic-dark); padding:14px 18px; margin-bottom:20px;">
                                                    <div style="display:flex; align-items:center; gap:12px;">
                                                        <div class="return-member-avatar" style="width:44px; height:44px; font-size:1.1rem;">
                                                            {{ strtoupper(substr($borrowing->member->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div style="font-family:'Bangers',cursive; font-size:1.1rem; letter-spacing:1px; color:var(--comic-dark);">
                                                                {{ $borrowing->transaction_code }}
                                                            </div>
                                                            <div style="font-family:'Fredoka One',cursive; font-size:0.8rem; color:#888;">
                                                                {{ $borrowing->member->name }} — {{ $borrowing->member->member_code }}
                                                            </div>
                                                        </div>
                                                        <div style="margin-left:auto; text-align:right;">
                                                            <div style="font-family:'Fredoka One',cursive; font-size:0.75rem; color:#888; letter-spacing:1px;">JATUH TEMPO</div>
                                                            <div style="font-family:'Bangers',cursive; font-size:0.95rem; color:{{ $borrowing->isOverdue() ? 'var(--comic-red)' : 'var(--comic-dark)' }};">
                                                                {{ $borrowing->due_date->format('d M Y') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Book Checklist --}}
                                                <div style="margin-bottom:20px;">
                                                    <div style="font-family:'Fredoka One',cursive; font-size:0.75rem; letter-spacing:2px; text-transform:uppercase; color:var(--comic-dark); font-weight:900; margin-bottom:10px;">
                                                        📕 PILIH BUKU YANG DIKEMBALIKAN
                                                    </div>
                                                    <div class="comic-table-wrap">
                                                        <table class="table align-middle" style="border:2px solid var(--comic-dark); box-shadow:3px 3px 0 var(--comic-dark);">
                                                            <thead>
                                                                <tr style="background:var(--comic-dark); color:var(--comic-orange); text-align:center;">
                                                                    <th style="width:40px; padding:8px 12px;">
                                                                        <input type="checkbox" class="form-check-input select-all-{{ $borrowing->id }}"
                                                                            data-target="details-{{ $borrowing->id }}"/>
                                                                    </th>
                                                                    <th class="text-start" style="padding:8px 12px; font-weight:900; font-size:0.75rem;">JUDUL BUKU</th>
                                                                    <th style="padding:8px 12px; font-weight:900; font-size:0.75rem;">KODE</th>
                                                                    <th style="padding:8px 12px; font-weight:900; font-size:0.75rem;">STATUS</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($borrowing->details as $detail)
                                                                <tr>
                                                                    <td class="text-center" style="padding:8px 12px;">
                                                                        <input type="checkbox" name="detail_ids[]" value="{{ $detail->id }}"
                                                                            class="form-check-input detail-check-{{ $borrowing->id }}"
                                                                            data-target="details-{{ $borrowing->id }}"
                                                                            {{ $detail->status->value === 'returned' ? 'disabled checked' : '' }}/>
                                                                    </td>
                                                                    <td style="padding:8px 12px;">
                                                                        <span style="font-weight:900; font-size:0.85rem; color:var(--comic-dark);">
                                                                            📕 {{ Str::limit($detail->book->title, 40) }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center" style="padding:8px 12px;">
                                                                        <span style="font-family:monospace; font-size:0.75rem; color:#888; font-weight:700;">
                                                                            {{ $detail->book->book_code }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center" style="padding:8px 12px;">
                                                                        @if($detail->status->value === 'returned')
                                                                            <span class="badge status-badge-ret status-returned" style="font-size:0.68rem !important;">✅ SUDAH</span>
                                                                        @else
                                                                            <span class="badge status-badge-ret status-late" style="font-size:0.68rem !important;">⏳ BELUM</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                {{-- Form Fields --}}
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <label class="form-label" style="font-family:'Fredoka One',cursive; font-size:0.75rem; letter-spacing:2px; text-transform:uppercase; color:var(--comic-dark); font-weight:900;">
                                                            📅 TANGGAL KEMBALI
                                                        </label>
                                                        <input type="date" name="return_date" class="form-control"
                                                            value="{{ now()->toDateString() }}" required/>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" style="font-family:'Fredoka One',cursive; font-size:0.75rem; letter-spacing:2px; text-transform:uppercase; color:var(--comic-dark); font-weight:900;">
                                                            📋 KONDISI BUKU
                                                        </label>
                                                        <select name="condition" class="form-select" required>
                                                            <option value="">— Pilih —</option>
                                                            <option value="Baik">✅ Baik — Normal</option>
                                                            <option value="Rusak Ringan">⚠️ Rusak Ringan — Ada lecet/catatan</option>
                                                            <option value="Rusak Berat">❌ Rusak Berat — Perlu ganti</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" style="font-family:'Fredoka One',cursive; font-size:0.75rem; letter-spacing:2px; text-transform:uppercase; color:var(--comic-dark); font-weight:900;">
                                                            📝 CATATAN (OPSIONAL)
                                                        </label>
                                                        <textarea name="notes" class="form-control" rows="2"
                                                            placeholder="Catatan pengembalian..."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer" style="border-top:2px solid rgba(26,26,46,0.1); padding:16px 20px;">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-comic">
                                                    <i class="ki-duotone ki-check fs-4" style="color:#fff !important;"></i>
                                                    SIMPAN PENGEMBALIAN
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="comic-empty">
                                <span class="empty-emoji">📥</span>
                                <div class="empty-title">TIDAK ADA DATA PENGEMBALIAN</div>
                                <div class="empty-sub">Semua buku sudah dikembalikan atau belum ada peminjaman aktif</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('layouts.partials._pagination', ['paginator' => $borrowings])
    </div>
</div>
@endsection

@push('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Select All per borrowing modal ──
    document.querySelectorAll('[class^="select-all-"]').forEach(function (cb) {
        cb.addEventListener('change', function () {
            var target = this.getAttribute('data-target');
            var checked = this.checked;
            document.querySelectorAll('[data-target="' + target + '"]').forEach(function (el) {
                if (!el.disabled) el.checked = checked;
            });
        });
    });

    // ── Update select-all when individual checkboxes change ──
    document.querySelectorAll('[class^="detail-check-"]').forEach(function (cb) {
        cb.addEventListener('change', function () {
            var target = this.getAttribute('data-target');
            var all = Array.from(document.querySelectorAll('[data-target="' + target + '"]'));
            var checked = Array.from(document.querySelectorAll('[data-target="' + target + '"]:checked:not(:disabled)'));
            var selectAll = document.querySelector('.select-all-' + target.split('-')[1]);
            if (!selectAll) return;
            if (checked.length === 0) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            } else if (checked.length === all.filter(function (el) { return !el.disabled; }).length) {
                selectAll.checked = true;
                selectAll.indeterminate = false;
            } else {
                selectAll.checked = false;
                selectAll.indeterminate = true;
            }
        });
    });
});
</script>
@endpush