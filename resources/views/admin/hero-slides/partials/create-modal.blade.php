{{-- Create Modal --}}
<div class="modal fade" id="modal-add-slide" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Hero Slide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Slide <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Selamat Datang di Perpustakaan" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" placeholder="Teks deskripsi singkat (opsional)"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar Slide <span class="text-danger">*</span></label>
                        <input type="file" name="image" id="create-image-input" class="form-control" accept="image/*" required/>
                        <div class="form-text">Format: JPG, PNG, WebP, SVG. Maks 5 MB. Disarankan rasio 16:9 atau 3:1.</div>
                        <div class="mt-2" id="create-image-preview" style="display:none;">
                            <img src="" alt="Preview" style="max-width:200px; max-height:120px; object-fit:cover; border:2px solid #dee2e6;"/>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ilustrasi Samping (opsional)</label>
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="illustration_type" id="ill-type-emoji" value="emoji" class="form-check-input" checked/>
                                    <label class="form-check-label" for="ill-type-emoji">🤡 Emoji (default)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="illustration_type" id="ill-type-image" value="image" class="form-check-input"/>
                                    <label class="form-check-label" for="ill-type-image">🖼️ Custom Image</label>
                                </div>
                            </div>
                            <div class="col-12" id="ill-image-upload" style="display:none;">
                                <input type="file" name="illustration_image" id="create-illustration-input" class="form-control" accept="image/*"/>
                                <div class="form-text">Gambar ilustrasi sisi kanan slide. Maks 2 MB.</div>
                                <div class="mt-2" id="create-illustration-preview" style="display:none;">
                                    <img src="" alt="Preview" style="max-width:120px; max-height:80px; object-fit:cover; border:2px solid #dee2e6;"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Link URL</label>
                            <input type="url" name="link_url" class="form-control" placeholder="https://..."/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teks Tombol</label>
                            <input type="text" name="link_text" class="form-control" value="Lihat Selengkapnya"/>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Urutan Tampil</label>
                            <input type="number" name="order" class="form-control" value="0" min="0"/>
                            <div class="form-text">Angka kecil = tampil lebih dulu</div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="create-is-active" value="1" checked/>
                                <label class="form-check-label fw-bold" for="create-is-active">Aktifkan slide</label>
                            </div>
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
<script>
document.getElementById('create-image-input')?.addEventListener('change', function(e) {
    var file = e.target.files[0];
    var preview = document.getElementById('create-image-preview');
    if (file && preview) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            preview.querySelector('img').src = ev.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Toggle illustration image upload
document.getElementById('ill-type-image')?.addEventListener('change', function() {
    document.getElementById('ill-image-upload').style.display = 'block';
});
document.getElementById('ill-type-emoji')?.addEventListener('change', function() {
    document.getElementById('ill-image-upload').style.display = 'none';
});

document.getElementById('create-illustration-input')?.addEventListener('change', function(e) {
    var file = e.target.files[0];
    var preview = document.getElementById('create-illustration-preview');
    if (file && preview) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            preview.querySelector('img').src = ev.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>