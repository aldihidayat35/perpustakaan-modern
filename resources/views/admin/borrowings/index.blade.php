@extends('layouts.app')

@section('title', 'Peminjaman Buku')
@section('page-title', 'Peminjaman Buku')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Transaksi</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Peminjaman</li>
</ul>
@endsection

@push('custom-css')
<style>
/* ── Compact Status Filter Pills (inline in toolbar) ── */
.status-filter-pills {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: nowrap;
}
.status-filter-pills .sfp-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 10px;
    border: 2px solid rgba(255,255,255,0.25);
    border-radius: 0;
    font-family: 'Fredoka One', cursive;
    font-size: 0.72rem;
    letter-spacing: 1px;
    font-weight: 900;
    text-decoration: none;
    color: rgba(255,255,255,0.65);
    background: rgba(255,255,255,0.05);
    transition: all 0.18s ease;
    white-space: nowrap;
}
.status-filter-pills .sfp-btn:hover {
    background: var(--comic-orange);
    border-color: var(--comic-orange);
    color: #fff;
    transform: translateY(-1px);
}
.status-filter-pills .sfp-btn.active {
    background: var(--comic-orange);
    border-color: var(--comic-orange);
    color: #fff;
    box-shadow: 2px 2px 0 var(--comic-dark);
}
.status-filter-pills .sfp-btn .count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 18px;
    height: 18px;
    padding: 0 5px;
    background: rgba(255,255,255,0.2);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 900;
    border-radius: 0;
}
.status-filter-pills .sfp-btn.active .count-badge {
    background: var(--comic-yellow);
    color: var(--comic-dark);
}
</style>
@endpush

@section('content')
{{-- Table Card ─────────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <span class="fw-bold text-white" style="font-family:'Bangers',cursive; letter-spacing:2px; font-size:1.1rem;">📤 DAFTAR PEMINJAMAN</span>
        </div>
        <div class="card-toolbar d-flex align-items-center gap-2">
            {{-- Status Filter Pills ── --}}
            <div class="status-filter-pills">
                <a href="{{ route('admin.borrowings.index') }}"
                    class="sfp-btn {{ !$statusParam ? 'active' : '' }}">
                    📋 Semua
                    <span class="count-badge">{{ $totalAll }}</span>
                </a>
                <a href="{{ route('admin.borrowings.index', ['status' => 'active']) }}"
                    class="sfp-btn {{ $statusParam === 'active' ? 'active' : '' }}">
                    📤 Aktif
                    <span class="count-badge">{{ $countActive }}</span>
                </a>
                <a href="{{ route('admin.borrowings.index', ['status' => 'late']) }}"
                    class="sfp-btn {{ $statusParam === 'late' ? 'active' : '' }}">
                    ⚠️ Terlambat
                    <span class="count-badge">{{ $countLate }}</span>
                </a>
                <a href="{{ route('admin.borrowings.index', ['status' => 'returned']) }}"
                    class="sfp-btn {{ $statusParam === 'returned' ? 'active' : '' }}">
                    ✅ Kembali
                    <span class="count-badge">{{ $countReturned }}</span>
                </a>
            </div>

            <div style="width:1px; height:28px; background:rgba(255,255,255,0.2); margin:0 4px;"></div>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-borrowing">
                <i class="ki-duotone ki-plus fs-2"></i> Tambah
            </button>
        </div>
    </div>

    <div class="card-body py-4 px-4">

        {{-- Table --}}
        <div class="comic-table-wrap">
            <table class="table align-middle table-row-dashed fs-6 gy-4">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th style="min-width:130px;">Kode</th>
                        <th style="min-width:160px;">Anggota</th>
                        <th style="min-width:180px;">Buku</th>
                        <th style="min-width:110px;">Tgl Pinjam</th>
                        <th style="min-width:110px;">Jatuh Tempo</th>
                        <th style="min-width:80px;">Status</th>
                        <th class="text-end" style="min-width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($borrowings as $borrowing)
                    <tr>
                        <td>
                            <span class="fw-bold text-dark" style="font-size:0.82rem;">{{ $borrowing->transaction_code }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="symbol symbol-35px flex-shrink-0">
                                    <div class="symbol-label fs-5 fw-bold"
                                        style="background:var(--comic-cream); color:var(--comic-dark); border:2px solid var(--comic-dark);">
                                        {{ strtoupper(substr($borrowing->member->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size:0.85rem;">{{ $borrowing->member->name }}</span>
                                    <span class="text-muted" style="font-size:0.72rem;">{{ $borrowing->member->member_code }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <ul class="mb-0 ps-3" style="list-style:none; padding:0;">
                                @foreach($borrowing->details->take(2) as $detail)
                                    <li style="font-size:0.8rem; color:#555; font-weight:700;">
                                        📕 {{ Str::limit($detail->book->title, 28) }}
                                    </li>
                                @endforeach
                                @if($borrowing->details->count() > 2)
                                    <li style="font-size:0.75rem; color:#aaa; font-weight:700;">+{{ $borrowing->details->count() - 2 }} buku lagi</li>
                                @endif
                            </ul>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:0.82rem;">{{ $borrowing->loan_date->format('d M Y') }}</span>
                        </td>
                        <td>
                            @if($borrowing->status->value === 'late')
                                <span class="fw-bold text-danger" style="font-size:0.82rem;">{{ $borrowing->due_date->format('d M Y') }}</span>
                                <div><span class="badge badge-light-danger" style="font-size:0.65rem; border-radius:0 !important;">⚠️ Terlambat</span></div>
                            @else
                                <span class="text-muted" style="font-size:0.82rem;">{{ $borrowing->due_date->format('d M Y') }}</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'active' => ['class' => 'badge-light-primary', 'text' => 'Aktif'],
                                    'late' => ['class' => 'badge-light-danger', 'text' => 'Terlambat'],
                                    'returned' => ['class' => 'badge-light-success', 'text' => 'Kembali'],
                                ];
                                $s = $statusMap[$borrowing->status->value] ?? ['class' => 'badge-light-secondary', 'text' => ucfirst($borrowing->status->value)];
                            @endphp
                            <span class="badge {{ $s['class'] }}" style="font-size:0.72rem;">{{ $s['text'] }}</span>
                        </td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.borrowings.remind', $borrowing) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-comic" style="padding:5px 10px !important; font-size:0.72rem !important;" title="Kirim Reminder WA">
                                    <i class="ki-duotone ki-message-text fs-4" style="color:#fff !important;"></i>
                                    WA
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="comic-empty">
                                <span class="empty-emoji">📤</span>
                                <div class="empty-title">TIDAK ADA PEMINJAMAN</div>
                                <div class="empty-sub">Mulai dengan menambah transaksi peminjaman baru</div>
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

{{-- Modal Tambah Peminjaman --}}
<div class="modal fade" id="modal-add-borrowing" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" style="font-family:'Bangers',cursive; letter-spacing:2px;">
                    📤 TAMBAH PEMINJAMAN
                </h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form class="form" method="POST" action="{{ route('admin.borrowings.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">👤 Anggota</label>
                            <select name="member_id" class="form-select" required>
                                <option value="">— Pilih Anggota —</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->member_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">📅 Tanggal Kembali</label>
                            <input type="date" name="due_date" class="form-control" value="{{ now()->addDays(7)->toDateString() }}" required/>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">📕 Pilih Buku</label>
                            <select name="book_ids[]" class="form-select" multiple required size="6">
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}" {{ $book->stock <= 0 ? 'disabled' : '' }}>
                                        {{ $book->title }} (Stok: {{ $book->stock }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted" style="font-size:0.75rem; font-weight:700;">Tahan Ctrl/Cmd untuk memilih lebih dari satu buku.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">📝 Catatan (opsional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Catatan peminjaman..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-duotone ki-check fs-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // No DataTable needed — using custom pagination
});
</script>
@endpush
