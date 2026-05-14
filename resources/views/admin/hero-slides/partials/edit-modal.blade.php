{{-- Edit Modal --}}
<div class="modal fade" id="modal-edit-slide" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Hero Slide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Slide <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ganti Gambar Slide</label>
                        <input type="file" name="image" id="edit-image-input" class="form-control" accept="image/*"/>
                        <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div>
                        <div class="mt-2" style="display:block;">
                            <img id="edit-image-preview" src="" alt="Current" style="max-width:200px; max-height:120px; object-fit:cover; border:2px solid #dee2e6;"/>
                            <div class="form-text">Gambar saat ini</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ilustrasi Samping (opsional)</label>
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="illustration_type" id="edit-ill-type-emoji" value="emoji" class="form-check-input"/>
                                    <label class="form-check-label" for="edit-ill-type-emoji">🤡 Emoji (default)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="illustration_type" id="edit-ill-type-image" value="image" class="form-check-input"/>
                                    <label class="form-check-label" for="edit-ill-type-image">🖼️ Custom Image</label>
                                </div>
                            </div>
                            <div class="col-12" id="edit-ill-image-upload" style="display:none;">
                                <input type="file" name="illustration_image" id="edit-illustration-input" class="form-control" accept="image/*"/>
                                <div class="form-text">Gambar ilustrasi sisi kanan slide. Maks 2 MB.</div>
                                <div class="mt-2" id="edit-illustration-preview-container" style="display:none;">
                                    <img id="edit-illustration-preview" src="" alt="Preview" style="max-width:120px; max-height:80px; object-fit:cover; border:2px solid #dee2e6;"/>
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
                            <input type="text" name="link_text" class="form-control"/>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Urutan Tampil</label>
                            <input type="number" name="order" class="form-control" min="0"/>
                            <div class="form-text">Angka kecil = tampil lebih dulu</div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="edit-is-active" value="1"/>
                                <label class="form-check-label fw-bold" for="edit-is-active">Aktifkan slide</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-duotone ki-check fs-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('edit-image-input')?.addEventListener('change', function(e) {
    var file = e.target.files[0];
    var preview = document.getElementById('edit-image-preview');
    if (file && preview) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            preview.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Toggle illustration image upload
document.getElementById('edit-ill-type-image')?.addEventListener('change', function() {
    document.getElementById('edit-ill-image-upload').style.display = 'block';
});
document.getElementById('edit-ill-type-emoji')?.addEventListener('change', function() {
    document.getElementById('edit-ill-image-upload').style.display = 'none';
});

document.getElementById('edit-illustration-input')?.addEventListener('change', function(e) {
    var file = e.target.files[0];
    var preview = document.getElementById('edit-illustration-preview');
    var container = document.getElementById('edit-illustration-preview-container');
    if (file && preview) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            preview.src = ev.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>