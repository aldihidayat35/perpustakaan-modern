@extends('layouts.app')

@section('title', 'WhatsApp API')
@section('page-title', 'WhatsApp API')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Pengaturan</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">WhatsApp API</li>
</ul>
@endsection

@push('custom-css')
<style>
.wa-card {
    border: 3px solid var(--comic-dark) !important;
    box-shadow: 5px 5px 0 var(--comic-dark) !important;
    border-radius: 0 !important;
}
.wa-card .card-header {
    background: var(--comic-dark) !important;
    border-bottom: 3px solid var(--comic-orange) !important;
}
.wa-card .card-header .card-title {
    font-family: 'Bangers', cursive !important;
    letter-spacing: 2px !important;
    color: var(--comic-orange) !important;
    font-size: 1.1rem !important;
}
.toggle-wa-active {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    border: 2px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    background: #fff;
    cursor: pointer;
}
.toggle-wa-active.is-active {
    background: #f0fff4;
    border-color: var(--comic-green);
    box-shadow: 3px 3px 0 var(--comic-green);
}
.toggle-wa-switch {
    width: 50px;
    height: 26px;
    background: #ccc;
    border-radius: 0 !important;
    border: 2px solid var(--comic-dark) !important;
    position: relative;
    transition: all 0.2s;
    flex-shrink: 0;
}
.toggle-wa-switch::after {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    background: #fff;
    border: 2px solid var(--comic-dark);
    top: 1px;
    left: 1px;
    transition: all 0.2s;
}
.is-active .toggle-wa-switch {
    background: var(--comic-green);
}
.is-active .toggle-wa-switch::after {
    left: calc(100% - 20px);
}
</style>
@endpush

@section('content')
<div class="row g-5">
    {{-- Left: WA Settings --}}
    <div class="col-xl-7">
        <div class="card wa-card">
            <div class="card-header">
                <div class="card-title">⚙️ PENGATURAN WHATSAPP</div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.settings.whatsapp.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-family:'Fredoka One', cursive; font-size:0.78rem; letter-spacing:1px;">
                            🔑 API KEY
                        </label>
                        <input type="text" name="api_key" class="form-control"
                            value="{{ $settings['api_key'] }}"
                            placeholder="Masukkan API key dari provider WA"/>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-family:'Fredoka One', cursive; font-size:0.78rem; letter-spacing:1px;">
                            📱 NOMOR PENGIRIM
                        </label>
                        <input type="text" name="sender" class="form-control"
                            value="{{ $settings['sender'] }}"
                            placeholder="08xxxxxxxxx"/>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-family:'Fredoka One', cursive; font-size:0.78rem; letter-spacing:1px;">
                            📅 HARI REMINDER SEBELUM JATUH TEMPO
                        </label>
                        <input type="number" name="reminder_days" class="form-control"
                            value="{{ $settings['reminder_days'] }}" min="0"
                            placeholder="Contoh: 2 (hari sebelum jatuh tempo)"/>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-family:'Fredoka One', cursive; font-size:0.78rem; letter-spacing:1px;">
                            ⚡ AKTIFKAN WHATSAPP
                        </label>
                        <div class="toggle-wa-active {{ $settings['is_active'] ? 'is-active' : '' }}" onclick="this.classList.toggle('is-active'); this.querySelector('input[name=is_active]').checked = this.classList.contains('is-active');">
                            <div class="toggle-wa-switch"></div>
                            <span style="font-family:'Fredoka One', cursive; font-size:0.85rem; font-weight:900; color:var(--comic-dark);">
                                {{ $settings['is_active'] ? '✅ AKTIF — Notifikasi aktif' : '⏸️ NONAKTIF — Notifikasi dinonaktifkan' }}
                            </span>
                            <input type="hidden" name="is_active" value="{{ $settings['is_active'] ? '1' : '0' }}"/>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end;">
                        <button type="submit" class="btn btn-comic">
                            <i class="ki-duotone ki-check fs-4" style="color:#fff !important;"></i>
                            SIMPAN PENGATURAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Right: Test Connection --}}
    <div class="col-xl-5">
        <div class="card wa-card">
            <div class="card-header">
                <div class="card-title">🧪 TEST KONEKSI</div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.settings.whatsapp.test') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-family:'Fredoka One', cursive; font-size:0.78rem; letter-spacing:1px;">
                            📱 NOMOR TUJUAN
                        </label>
                        <input type="text" name="phone" class="form-control"
                            placeholder="08xxxxxxxxx" required
                            style="font-weight:800;"/>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-family:'Fredoka One', cursive; font-size:0.78rem; letter-spacing:1px;">
                            💬 PESAN
                        </label>
                        <textarea name="message" class="form-control" rows="4" required
                            style="font-weight:700;">Test koneksi WhatsApp berhasil! 🎉</textarea>
                    </div>
                    <button type="submit" class="btn btn-comic w-100" style="padding:12px !important;">
                        📤 KIRIM PESAN TEST
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection