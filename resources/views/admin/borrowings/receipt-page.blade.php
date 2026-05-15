@extends('layouts.app')

@section('title', 'Struk Peminjaman - ' . $borrowing->transaction_code)
@section('page-title', 'Struk Peminjaman')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.borrowings.index') }}" class="text-muted text-hover-primary">Peminjaman</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">{{ $borrowing->transaction_code }}</li>
</ul>
@endsection

@push('custom-css')
<style>
.receipt-page-wrap {
    max-width: 420px;
    margin: 0 auto;
}
.receipt-action-bar {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.btn-receipt-action {
    border: 3px solid var(--comic-dark) !important;
    box-shadow: 4px 4px 0 var(--comic-dark) !important;
    border-radius: 0 !important;
    font-family: 'Fredoka One', cursive !important;
    font-size: 0.88rem !important;
    font-weight: 900 !important;
    letter-spacing: 1px;
    padding: 10px 20px !important;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}
.btn-receipt-action:hover {
    transform: translateY(-2px);
    box-shadow: 5px 6px 0 var(--comic-dark) !important;
}
.success-banner {
    background: var(--comic-green);
    border: 3px solid var(--comic-dark) !important;
    box-shadow: 4px 4px 0 var(--comic-dark) !important;
    border-radius: 0 !important;
    padding: 16px 20px;
    text-align: center;
    margin-bottom: 20px;
}
.success-banner .title {
    font-family: 'Bangers', cursive;
    font-size: 1.5rem;
    letter-spacing: 3px;
    color: #fff;
}
.success-banner .subtitle {
    font-family: 'Fredoka One', cursive;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.85);
}

@media print {
    /* FORSA ALL WRAPPER PARENTS TO DISPLAY BLOCK SO .receipt-thermal BECOMES VISIBLE */
    body,
    #kt_body,
    .d-flex,
    .flex-column,
    .flex-root,
    .flex-row,
    .flex-row-fluid,
    .flex-column-fluid,
    .flex-column-reverse,
    .page,
    .page.d-flex,
    .wrapper,
    .content,
    .content.d-flex,
    .content.flex-column-fluid,
    .post,
    .post.d-flex,
    #kt_post,
    #kt_content_container,
    .container-fluid,
    .receipt-page-wrap,
    .card,
    .card-body,
    .receipt-thermal {
        display: block !important;
        visibility: visible !important;
        width: auto !important;
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
        position: static !important;
        clip: auto !important;
        float: none !important;
        background: #fff !important;
    }
    /* SEMBUNYIKAN KOMPONEN WEB YANG TIDAK PERLU */
    .success-banner,
    .receipt-action-bar,
    .text-center,
    .breadcrumb,
    .page-title,
    .page-title-section,
    .breadcrumb-separatorless,
    .breadcrumb-item,
    .aside,
    .header,
    .header-brand,
    .toolbar,
    #kt_toolbar,
    #kt_header,
    .footer,
    #kt_footer,
    .scrolltop,
    nav,
    .kt_app_toggle,
    .btn,
    a[href]:after,
    .alert,
    .toast,
    .modal,
    .modal-backdrop {
        display: none !important;
        visibility: hidden !important;
        width: 0 !important;
        height: 0 !important;
        max-height: 0 !important;
        overflow: hidden !important;
        position: absolute !important;
        clip: rect(0,0,0,0) !important;
        float: none !important;
        pointer-events: none !important;
    }
    /* STRUK — UKURAN KERTAS 80MM THERMAL */
    .receipt-thermal {
        max-width: 320px !important;
        margin: 0 auto !important;
        padding: 10px 8px !important;
    }
}
</style>
@endpush

@section('content')
<div class="receipt-page-wrap">
    {{-- Success Banner --}}
    <div class="success-banner">
        <div class="title">✅ PEMINJAMAN BERHASIL!</div>
        <div class="subtitle">Transaksi {{ $borrowing->transaction_code }}</div>
    </div>

    {{-- Action Buttons --}}
    <div class="receipt-action-bar">
        <a href="{{ route('admin.borrowings.receipt.pdf', $borrowing) }}"
            class="btn btn-primary btn-receipt-action"
            style="background:var(--comic-orange) !important; color:#fff !important;"
            target="_blank">
            <i class="ki-duotone ki-file-down fs-5" style="color:#fff !important;"></i>
            Download PDF
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-receipt-action"
            style="background:var(--comic-yellow) !important; color:var(--comic-dark) !important;">
            <i class="ki-duotone ki-printer fs-5" style="color:var(--comic-dark) !important;"></i>
            Cetak Struk
        </button>
        <a href="{{ route('admin.borrowings.create') }}"
            class="btn btn-primary btn-receipt-action"
            style="background:#fff !important; color:var(--comic-dark) !important;">
            <i class="ki-duotone ki-plus fs-5" style="color:var(--comic-dark) !important;"></i>
            Pinjaman Baru
        </a>
        <a href="{{ route('admin.borrowings.index') }}"
            class="btn btn-secondary btn-receipt-action"
            style="background:var(--comic-dark) !important; color:var(--comic-orange) !important;">
            <i class="ki-duotone ki-arrow-left fs-5" style="color:var(--comic-orange) !important;"></i>
            Kembali
        </a>
    </div>

    {{-- Receipt Content --}}
    @php
        $receiptData = app(\App\Services\ReceiptService::class)->generateReceiptData($borrowing);
    @endphp

    <div class="card" style="border:3px solid var(--comic-dark) !important; box-shadow:5px 5px 0 var(--comic-dark) !important; border-radius:0 !important;">
        <div class="card-body p-0">
            @include('admin.borrowings.receipt', $receiptData)
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('admin.borrowings.index') }}"
            style="font-family:'Fredoka One', cursive; font-size:0.82rem; color:#aaa; text-decoration:none;">
            ← Kembali ke Daftar Peminjaman
        </a>
    </div>
</div>
@endsection

@push('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Optionally auto-trigger print after page loads
    // window.addEventListener('load', () => window.print());
});
</script>
@endpush