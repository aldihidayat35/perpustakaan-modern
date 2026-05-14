<div class="modal fade" id="modal-edit-member" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Anggota</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form class="form" method="POST" action="#" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    {{-- Photo Upload ─────────────────────────────────────── --}}
                    <div class="d-flex align-items-start gap-4 mb-5">
                        <div class="photo-preview-container text-center">
                            <div class="photo-upload-box" id="edit-photo-preview"
                                style="width:110px; height:130px; border:3px dashed #aaa; border-radius:0;
                                       display:flex; align-items:center; justify-content:center;
                                       flex-direction:column; cursor:pointer; background:#f9f9f9; transition:all 0.2s;"
                                onclick="document.getElementById('edit-photo-input').click()">
                                <span class="edit-preview-placeholder" style="font-size:2rem; color:#ccc;">📷</span>
                            </div>
                            <input type="file" name="photo" id="edit-photo-input" accept="image/*" style="display:none;"
                                onchange="previewPhoto(this, 'edit-photo-preview', 'edit-photo-remove-wrap')"/>
                            <div id="edit-photo-remove-wrap" style="margin-top:6px;">
                                <button type="button" class="btn btn-sm" style="background:var(--comic-red); color:#fff; border-radius:0; border:2px solid var(--comic-dark); box-shadow:2px 2px 0 var(--comic-dark); font-family:'Fredoka One',cursive; font-size:0.68rem;"
                                    onclick="event.stopPropagation(); removePhoto('edit-photo-input', 'edit-photo-preview', 'edit-photo-remove-wrap', true)">
                                    🗑 Hapus Foto
                                </button>
                            </div>
                            <input type="hidden" name="remove_photo" id="edit-remove-photo-flag" value="0"/>
                            <div style="font-family:'Fredoka One',cursive; font-size:0.6rem; color:#bbb; margin-top:4px; letter-spacing:0.5px;">
                                JPG, PNG, WebP<br>Maks 2MB
                            </div>
                        </div>
                        <div style="flex:1;">
                            <div class="form-label" style="font-family:'Fredoka One',cursive; font-size:0.75rem; letter-spacing:1px; text-transform:uppercase; color:var(--comic-dark);">
                                📷 Foto Anggota
                            </div>
                            <p style="font-size:0.78rem; color:#888; margin-bottom:8px;">
                                Pasang foto anggota untuk kartu ID. Klik kotak foto untuk memilih file baru.
                                Klik <strong>"Hapus Foto"</strong> untuk menghapus foto yang ada.
                            </p>
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                <div style="border:2px solid #ddd; border-radius:0; padding:6px 10px; font-family:'Fredoka One',cursive; font-size:0.7rem; color:#888;">
                                    📷 Ganti Foto
                                </div>
                                <div style="border:2px solid var(--comic-green); background:rgba(0,200,150,0.1); border-radius:0; padding:6px 10px; font-family:'Fredoka One',cursive; font-size:0.7rem; color:var(--comic-green);">
                                    ✓ Tampil di Kartu
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5">
                        <div class="col-md-6">
                            <label class="form-label">Kode Anggota <span class="text-danger">*</span></label>
                            <input type="text" name="member_code" class="form-control form-control-solid" required/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-solid" required/>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="class" class="form-control form-control-solid" placeholder="cth: X IPA 1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NIS / NIM</label>
                            <input type="text" name="nis_nim" class="form-control form-control-solid" placeholder="cth: 00123"/>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="major" class="form-control form-control-solid" placeholder="cth: IPA / IPS"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control form-control-solid" placeholder="cth: 081234567890"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control form-control-solid" placeholder="cth: nama@email.com"/>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control form-control-solid" rows="2" placeholder="Alamat lengkap anggota..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-solid" required>
                                <option value="active">✅ Aktif</option>
                                <option value="inactive">❌ Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-comic">
                        <i class="ki-duotone ki-check fs-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>