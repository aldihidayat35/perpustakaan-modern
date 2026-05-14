@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.categories.index') }}" class="text-muted text-hover-primary">Kategori</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Tambah</li>
</ul>
@endsection

@push('custom-css')
<style>
.form-card { border: 3px solid var(--comic-dark) !important; box-shadow: 6px 6px 0 var(--comic-dark) !important; border-radius:0 !important; }
.form-card .card-header { background: var(--comic-dark) !important; border-bottom: 3px solid var(--comic-orange) !important; }
.form-card .card-header .card-title { font-family: 'Bangers', cursive !important; color: var(--comic-orange) !important; font-size: 1.2rem !important; letter-spacing: 2px !important; }
.form-label { font-family: 'Fredoka One', cursive; font-size: 0.78rem; letter-spacing: 2px; text-transform: uppercase; color: var(--comic-dark); font-weight: 900 !important; margin-bottom: 6px; }
</style>
@endpush

@section('content')
<div class="card form-card">
    <div class="card-header">
        <div class="card-title">📂 TAMBAH KATEGORI BARU</div>
    </div>
    <div class="card-body p-5">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label class="form-label">📖 NAMA KATEGORI <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Contoh: Fiksi, Non-Fiksi, Sains" required/>
                        @error('name')
                            <div class="text-danger mt-1" style="font-weight:700; font-size:0.82rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">😀 ICON (EMOJI)</label>
                        <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                            value="{{ old('icon') }}" placeholder="📕" maxlength="10"/>
                        <div style="font-size:0.72rem; color:#aaa; font-weight:700; margin-top:4px;">Masukkan emoji tunggal, contoh: 📕, 🔬, 💻</div>
                        @error('icon')
                            <div class="text-danger mt-1" style="font-weight:700; font-size:0.82rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">📝 DESKRIPSI</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="4" placeholder="Deskripsi singkat tentang kategori ini">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1" style="font-weight:700; font-size:0.82rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-comic">
                    <i class="ki-duotone ki-check fs-4" style="color:#fff !important;"></i> SIMPAN
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection