@extends('layouts.app')

@section('title', 'Kelola Hero Slide')
@section('page-title', 'Kelola Hero Slide')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Pengaturan</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Hero Slide</li>
</ul>
@endsection

@push('custom-css')
<style>
.slide-image-preview {
    width: 100px; height: 56px;
    object-fit: cover;
    border: 2px solid var(--comic-dark);
    box-shadow: 2px 2px 0 var(--comic-dark);
}
.slide-thumb {
    width: 100px; height: 56px;
    overflow: hidden;
    border: 2px solid var(--comic-dark);
    box-shadow: 2px 2px 0 var(--comic-dark);
}
.slide-thumb img { width: 100%; height: 100%; object-fit: cover; }
.slide-thumb-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: var(--comic-cream);
    font-size: 1.5rem;
}
.ill-thumb {
    width: 52px; height: 52px;
    overflow: hidden;
    border: 2px solid var(--comic-dark);
    box-shadow: 2px 2px 0 var(--comic-dark);
}
.ill-thumb img { width: 100%; height: 100%; object-fit: cover; }
.toggle-status-btn {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    font-weight: 900 !important;
    font-size: 0.75rem !important;
    padding: 0 !important;
    cursor: pointer;
}
.toggle-status-btn:hover { opacity: 0.7; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <span class="fw-bold text-white" style="font-family:'Bangers',cursive; letter-spacing:2px; font-size:1.1rem;">🖼️ KELOLA HERO SLIDE</span>
        </div>
        <div class="card-toolbar d-flex align-items-center gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-slide">
                <i class="ki-duotone ki-plus fs-2"></i> Tambah Slide
            </button>
        </div>
    </div>

    <div class="card-body py-4 px-4">

        {{-- Table --}}
        <div class="comic-table-wrap">
            <table class="table align-middle table-row-dashed fs-6 gy-4">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th style="min-width:100px;">Gambar</th>
                        <th style="min-width:52px;">Ilustrasi</th>
                        <th style="min-width:180px;">Judul</th>
                        <th style="min-width:180px;">Subtitle</th>
                        <th style="min-width:50px;">Urutan</th>
                        <th style="min-width:70px;">Status</th>
                        <th class="text-end" style="min-width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($slides as $slide)
                    <tr>
                        <td>
                            <div class="slide-thumb">
                                <img src="{{ asset('storage/' . $slide->image_url) }}" alt="{{ $slide->title }}"/>
                            </div>
                        </td>
                        <td>
                            <div class="ill-thumb">
                                @if($slide->illustration_type === 'image' && $slide->illustration_image)
                                    <img src="{{ asset('storage/' . $slide->illustration_image) }}" alt="Ilustrasi"/>
                                @else
                                    <div class="ill-thumb-placeholder" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:var(--comic-cream); font-size:1.3rem;">
                                        📚
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-dark" style="font-size:0.88rem;">{{ $slide->title }}</span>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:0.82rem;">{{ Str::limit($slide->subtitle, 30) ?: '-' }}</span>
                        </td>
                        <td>
                            <span style="font-family:'Bangers',cursive; font-size:1.1rem; color:var(--comic-orange);">
                                {{ $slide->order }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.hero-slides.toggle', $slide) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="toggle-status-btn">
                                    @if($slide->is_active)
                                        <span class="badge badge-light-success" style="font-size:0.72rem; border-radius:0 !important; border:2px solid #00C896 !important; color:#00C896 !important;">
                                            ✅ AKTIF
                                        </span>
                                    @else
                                        <span class="badge badge-light-secondary" style="font-size:0.72rem; border-radius:0 !important;">
                                            ⏰ NONAKTIF
                                        </span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-comic-edit"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-edit-slide"
                                data-slide='@json($slide)'
                                title="Edit">
                                <i class="ki-duotone ki-pencil fs-4"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.hero-slides.destroy', $slide) }}" class="d-inline">
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
                                <span class="empty-emoji">🖼️</span>
                                <div class="empty-title">TIDAK ADA SLIDE</div>
                                <div class="empty-sub">Tambah slide untuk banner halaman utama</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('layouts.partials._pagination', ['paginator' => $slides])
    </div>
</div>

@include('admin.hero-slides.partials.create-modal')
@include('admin.hero-slides.partials.edit-modal')
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
                title: 'Hapus slide ini?',
                text: 'Gambar slide juga akan dihapus permanen.',
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

    var editModal = document.getElementById('modal-edit-slide');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (e) {
            var btn = e.relatedTarget;
            var slide = JSON.parse(btn.getAttribute('data-slide'));
            var form = editModal.querySelector('form');
            form.action = '/admin/hero-slides/' + slide.id;

            form.querySelector('[name="title"]').value = slide.title || '';
            form.querySelector('[name="subtitle"]').value = slide.subtitle || '';
            form.querySelector('[name="link_url"]').value = slide.link_url || '';
            form.querySelector('[name="link_text"]').value = slide.link_text || '';
            form.querySelector('[name="order"]').value = slide.order || 0;
            form.querySelector('[name="is_active"]').checked = Boolean(slide.is_active);

            var preview = editModal.querySelector('#edit-image-preview');
            if (preview) preview.src = '/storage/' + slide.image_url;

            var illType = slide.illustration_type || 'emoji';
            var illTypeEmoji = document.getElementById('edit-ill-type-emoji');
            var illTypeImage = document.getElementById('edit-ill-type-image');
            var illImageUpload = document.getElementById('edit-ill-image-upload');
            if (illTypeEmoji && illTypeImage && illImageUpload) {
                if (illType === 'image') {
                    illTypeImage.checked = true;
                    illImageUpload.style.display = 'block';
                } else {
                    illTypeEmoji.checked = true;
                    illImageUpload.style.display = 'none';
                }
            }

            var illPreview = document.getElementById('edit-illustration-preview');
            var illContainer = document.getElementById('edit-illustration-preview-container');
            if (slide.illustration_image && illPreview && illContainer) {
                illPreview.src = '/storage/' + slide.illustration_image;
                illContainer.style.display = 'block';
            } else if (illContainer) {
                illContainer.style.display = 'none';
            }
        });
    }
});
</script>
@endpush