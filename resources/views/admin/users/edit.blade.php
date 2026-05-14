@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.users.index') }}" class="text-muted text-hover-primary">Manajemen User</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Edit User</li>
</ul>
@endsection

@push('custom-css')
<style>
.form-card { border: 3px solid var(--comic-dark) !important; box-shadow: 6px 6px 0 var(--comic-dark) !important; border-radius:0 !important; }
.form-card .card-header { background: var(--comic-dark) !important; border-bottom: 3px solid var(--comic-orange) !important; }
.form-card .card-header .card-title { font-family: 'Bangers', cursive !important; color: var(--comic-orange) !important; font-size: 1.2rem !important; letter-spacing: 2px !important; }
.form-label { font-family: 'Fredoka One', cursive; font-size: 0.78rem; letter-spacing: 2px; text-transform: uppercase; color: var(--comic-dark); font-weight: 900 !important; margin-bottom: 6px; display:block; }
.form-hint { font-size:0.72rem; color:#aaa; font-weight:700; margin-top:4px; display:block; }
.avatar-preview {
    width: 80px; height: 80px;
    border: 3px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    object-fit: cover;
    background: var(--comic-cream);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Bangers', cursive;
    font-size: 2rem;
    color: var(--comic-dark);
}
</style>
@endpush

@section('content')
<div class="card form-card">
    <div class="card-header">
        <div class="card-title">✏️ EDIT USER: {{ $user->name }}</div>
    </div>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body p-5">
            <div class="row g-4">
                <div class="col-md-8">
                    {{-- Avatar --}}
                    <div class="mb-4">
                        <label class="form-label">🖼️ AVATAR</label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-preview" id="avatarPreview">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" style="width:80px;height:80px;object-fit:cover;border:3px solid var(--comic-dark);box-shadow:3px 3px 0 var(--comic-dark);"/>
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <input type="file" name="avatar" class="form-control" accept=".png,.jpg,.jpeg"
                                    onchange="document.getElementById('avatarPreview').innerHTML = '<img src=\'' + URL.createObjectURL(this.files[0]) + '\' style=\'width:80px;height:80px;object-fit:cover;border:3px solid var(--comic-dark);box-shadow:3px 3px 0 var(--comic-dark);\''/>'"/>
                                <span class="form-hint">Format: JPG, PNG. Max 2MB.</span>
                            </div>
                        </div>
                        @error('avatar')
                            <div class="text-danger mt-1" style="font-weight:700; font-size:0.82rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Name --}}
                    <div class="mb-4">
                        <label class="form-label">👤 NAMA LENGKAP <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required/>
                        @error('name')
                            <div class="text-danger mt-1" style="font-weight:700; font-size:0.82rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="form-label">📧 EMAIL <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required/>
                        @error('email')
                            <div class="text-danger mt-1" style="font-weight:700; font-size:0.82rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4" style="background:var(--comic-cream); border:2px dashed var(--comic-dark); padding:12px 16px; margin-bottom:16px;">
                        <label class="form-label">🔐 PASSWORD BARU</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Kosongkan jika tidak ingin mengubah"/>
                        <span class="form-hint">Minimal 8 karakter. Kosongkan jika tidak diubah.</span>
                        @error('password')
                            <div class="text-danger mt-1" style="font-weight:700; font-size:0.82rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-4">
                        <label class="form-label">🔐 KONFIRMASI PASSWORD BARU</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password baru"/>
                    </div>

                    {{-- Role --}}
                    <div class="mb-4">
                        <label class="form-label">🎭 ROLE <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>👤 User — Akses biasa</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>🛡️ Admin — Akses penuh</option>
                        </select>
                        @error('role')
                            <div class="text-danger mt-1" style="font-weight:700; font-size:0.82rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label class="form-label">⚡ STATUS AKTIF</label>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                id="isActiveCheck"
                                style="width:18px; height:18px; accent-color:var(--comic-orange);"/>
                            <label for="isActiveCheck" style="font-weight:700; font-size:0.85rem; color:var(--comic-dark); cursor:pointer; margin:0;">
                                User ini aktif dan bisa login
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer p-4" style="display:flex; justify-content:flex-end; gap:12px; border-top:2px solid rgba(26,26,46,0.1);">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-comic">
                <i class="ki-duotone ki-check fs-4" style="color:#fff !important;"></i> PERBARUI USER
            </button>
        </div>
    </form>
</div>
@endsection