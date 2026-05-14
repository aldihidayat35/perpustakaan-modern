@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan Aplikasi')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Pengaturan</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Data Aplikasi</li>
</ul>
@endsection

@push('custom-css')
<style>
.settings-card {
    border: 3px solid var(--comic-dark) !important;
    box-shadow: 5px 5px 0 var(--comic-dark) !important;
    border-radius: 0 !important;
}
.settings-card .card-header {
    background: var(--comic-dark) !important;
    border-bottom: 3px solid var(--comic-orange) !important;
}
.settings-card .card-header .card-title {
    font-family: 'Bangers', cursive !important;
    letter-spacing: 2px !important;
    color: var(--comic-orange) !important;
    font-size: 1.1rem !important;
}
.setting-row {
    display: flex;
    align-items: center;
    padding: 14px 0;
    border-bottom: 2px solid rgba(26,26,46,0.1);
}
.setting-row:last-child { border-bottom: none; }
.setting-label {
    font-family: 'Fredoka One', cursive;
    font-size: 0.8rem;
    letter-spacing: 1px;
    color: var(--comic-dark);
    font-weight: 900;
    min-width: 180px;
}
.setting-preview-img {
    max-width: 80px; max-height: 60px;
    object-fit: contain;
    border: 2px solid var(--comic-dark);
    box-shadow: 2px 2px 0 var(--comic-dark);
}
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @foreach($settings as $group => $items)
    <div class="card settings-card mb-5">
        <div class="card-header">
            <div class="card-title">
                📋 {{ strtoupper($group) }}
            </div>
        </div>
        <div class="card-body p-4">
            @foreach($items as $setting)
            <div class="setting-row">
                <label class="setting-label">
                    {{ $setting->label }}
                </label>
                <div class="flex-grow-1">
                    @if($setting->type === 'text')
                        <input type="text" name="settings[{{ $setting->key }}]"
                            class="form-control"
                            value="{{ old('settings.' . $setting->key, $setting->value) }}"
                            placeholder="{{ $setting->label }}"/>

                    @elseif($setting->type === 'textarea')
                        <textarea name="settings[{{ $setting->key }}]"
                            class="form-control"
                            rows="3"
                            placeholder="{{ $setting->label }}">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>

                    @elseif($setting->type === 'image')
                        <div class="d-flex align-items-center gap-3">
                            @if($setting->value)
                                <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->label }}"
                                    class="setting-preview-img"/>
                            @endif
                            <div class="flex-grow-1">
                                <input type="file" name="settings[{{ $setting->key }}]"
                                    class="form-control"
                                    accept=".png,.jpg,.jpeg,.svg,.ico"/>
                                <div style="font-size:0.72rem; color:#aaa; font-weight:700; margin-top:4px;">
                                    Format: JPG, PNG, SVG, ICO. Max: 2MB
                                </div>
                            </div>
                        </div>

                    @elseif($setting->type === 'boolean')
                        <label class="form-check" style="cursor:pointer;">
                            <input class="form-check-input" type="checkbox" name="settings[{{ $setting->key }}]"
                                value="1" {{ $setting->value ? 'checked' : '' }}/>
                            <span style="font-size:0.82rem; font-weight:700; color:var(--comic-dark);">
                                {{ $setting->value ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </label>

                    @elseif($setting->type === 'color')
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="settings[{{ $setting->key }}]"
                                class="form-control form-control-color"
                                value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                style="width:60px; height:40px; padding:2px; border:2px solid var(--comic-dark) !important;"/>
                            <span style="font-family:monospace; font-size:0.8rem; font-weight:700; color:#888;">
                                {{ old('settings.' . $setting->key, $setting->value) }}
                            </span>
                        </div>

                    @else
                        <input type="text" name="settings[{{ $setting->key }}]"
                            class="form-control"
                            value="{{ old('settings.' . $setting->key, $setting->value) }}"/>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div style="display:flex; justify-content:flex-end;">
        <button type="submit" class="btn btn-comic" style="padding:14px 28px !important; font-size:1rem !important;">
            <i class="ki-duotone ki-check fs-4" style="color:#fff !important;"></i>
            SIMPAN PENGATURAN
        </button>
    </div>
</form>
@endsection