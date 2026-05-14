@extends('layouts.app')

@section('title', 'Data Anggota')
@section('page-title', 'Data Anggota')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Manajemen</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Data Anggota</li>
</ul>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <span class="fw-bold text-white" style="font-family:'Bangers',cursive; letter-spacing:2px; font-size:1.1rem;">👥 DAFTAR ANGGOTA</span>
        </div>
        <div class="card-toolbar d-flex align-items-center gap-2">
            <a href="{{ route('admin.export.members') }}" class="btn btn-light-primary">
                <i class="ki-duotone ki-tablet-ks fs-2"></i> Export
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-member">
                <i class="ki-duotone ki-plus fs-2"></i> Tambah
            </button>
        </div>
    </div>

    <div class="card-body py-4 px-4">

        {{-- Comic Search Bar --}}
        <form method="GET" action="{{ route('admin.members.index') }}" class="comic-search-bar">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">🔍 Pencarian</label>
                    <div class="comic-search-wrap">
                        <i class="ki-duotone ki-magnifier comic-search-icon"></i>
                        <input type="text" name="search" class="form-control form-control-solid"
                            placeholder="Cari nama atau kode anggota..." value="{{ request('search') }}"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-comic w-100">
                        🔍 CARI
                    </button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="comic-table-wrap">
            <table class="table align-middle table-row-dashed fs-6 gy-4">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th style="min-width:100px;">Kode</th>
                        <th style="min-width:160px;">Nama</th>
                        <th style="min-width:70px;">QR</th>
                        <th style="min-width:100px;">Kelas</th>
                        <th style="min-width:120px;">WhatsApp</th>
                        <th style="min-width:80px;">Status</th>
                        <th class="text-end" style="min-width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($members as $member)
                    <tr>
                        <td>
                            <span class="fw-bold text-dark" style="font-size:0.82rem;">{{ $member->member_code }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}"
                                        class="symbol symbol-35px flex-shrink-0"
                                        style="width:35px; height:35px; object-fit:cover; border-radius:0; border:2px solid var(--comic-dark);"/>
                                @else
                                    <div class="symbol symbol-35px flex-shrink-0">
                                        <div class="symbol-label fs-5 fw-bold"
                                            style="background:var(--comic-cream); color:var(--comic-dark); border:2px solid var(--comic-dark);">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                    </div>
                                @endif
                                <span class="fw-bold text-dark" style="font-size:0.88rem;">{{ $member->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($member->qr_code)
                                <img src="{{ asset('storage/' . $member->qr_code) }}" alt="QR"
                                    style="width:40px; height:40px; object-fit:contain; border:2px solid #eee;"/>
                            @else
                                <form method="POST" action="{{ route('admin.members.regenerate-qr', $member) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-comic" style="padding:4px 8px !important; font-size:0.72rem !important;">
                                        <i class="ki-duotone ki-qrcode fs-4"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:0.82rem;">{{ $member->class ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:0.82rem;">{{ $member->whatsapp ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-light-{{ $member->status->value === 'active' ? 'success' : 'secondary' }}"
                                style="font-size:0.72rem;">
                                {{ ucfirst($member->status->value) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.members.show', $member) }}" class="btn btn-comic" style="padding:4px 8px !important; font-size:0.72rem !important;" title="Detail">
                                <i class="ki-duotone ki-eye fs-4"></i>
                            </a>
                            <button type="button" class="btn btn-comic-edit"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-edit-member"
                                data-member='@json($member)'
                                title="Edit">
                                <i class="ki-duotone ki-pencil fs-4"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.members.destroy', $member) }}" class="d-inline">
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
                        <td colspan="7">
                            <div class="comic-empty">
                                <span class="empty-emoji">👥</span>
                                <div class="empty-title">TIDAK ADA ANGGOTA</div>
                                <div class="empty-sub">Tambah anggota baru untuk memulai</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Comic Pagination --}}
        @include('layouts.partials._pagination', ['paginator' => $members])
    </div>
</div>

@include('admin.members.partials.create-modal')
@include('admin.members.partials.edit-modal')
@endsection

@push('vendor-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Delete confirmation
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus anggota?',
                text: 'Data tidak bisa dikembalikan.',
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

    // Edit modal populate
    var editModal = document.getElementById('modal-edit-member');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (e) {
            var btn = e.relatedTarget;
            var member = JSON.parse(btn.getAttribute('data-member'));
            var form = editModal.querySelector('form');
            form.action = '/admin/members/' + member.id;

            form.querySelector('[name="member_code"]').value = member.member_code || '';
            form.querySelector('[name="name"]').value = member.name || '';
            form.querySelector('[name="class"]').value = member.class || '';
            form.querySelector('[name="nis_nim"]').value = member.nis_nim || '';
            form.querySelector('[name="major"]').value = member.major || '';
            form.querySelector('[name="address"]').value = member.address || '';
            form.querySelector('[name="whatsapp"]').value = member.whatsapp || '';
            form.querySelector('[name="email"]').value = member.email || '';
            form.querySelector('[name="status"]').value = member.status;
            form.querySelector('[name="remove_photo"]').value = '0';

            // Reset photo preview
            var preview = document.getElementById('edit-photo-preview');
            var removeWrap = document.getElementById('edit-photo-remove-wrap');
            var input = document.getElementById('edit-photo-input');
            if (input) input.value = '';

            if (member.photo) {
                preview.innerHTML = '<img src="/storage/' + member.photo + '" style="width:100%;height:100%;object-fit:cover;"/>';
                removeWrap.style.display = 'block';
            } else {
                preview.innerHTML = '<span style="font-size:2rem; color:#ccc;">📷</span>';
                removeWrap.style.display = 'block';
            }
        });
    }
});

// ── Photo Preview / Remove Helpers ──
function previewPhoto(input, previewId, removeWrapId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var preview = document.getElementById(previewId);
            preview.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;"/>';
            var removeWrap = document.getElementById(removeWrapId);
            if (removeWrap) removeWrap.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removePhoto(inputId, previewId, removeWrapId, isEdit) {
    var input = document.getElementById(inputId);
    var preview = document.getElementById(previewId);
    var removeWrap = document.getElementById(removeWrapId);
    if (input) input.value = '';
    if (preview) {
        preview.innerHTML = '<span style="font-size:2rem; color:#ccc;">📷</span>';
    }
    if (removeWrap) {
        removeWrap.style.display = 'none';
    }
    if (isEdit) {
        document.getElementById('edit-remove-photo-flag').value = '1';
    }
}
</script>
@endpush
