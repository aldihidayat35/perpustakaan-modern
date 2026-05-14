@extends('layouts.app')

@section('title', 'Scan QR Code')
@section('page-title', 'Scan QR Code')

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-muted">Transaksi</li>
    <li class="breadcrumb-item"><span class="bullet bg-gray-300 w-5px h-2px"></span></li>
    <li class="breadcrumb-item text-gray-900">Scan QR</li>
</ul>
@endsection

@push('vendor-css')
<link rel="stylesheet" href="https://unpkg.com/html5-qrcode@2.0.3/min/html5-qrcode.min.css">
@endpush

@push('custom-css')
<style>
.scan-card {
    border: 3px solid var(--comic-dark) !important;
    box-shadow: 5px 5px 0 var(--comic-dark) !important;
    border-radius: 0 !important;
}
.scan-card .card-header {
    background: var(--comic-dark) !important;
    border-bottom: 3px solid var(--comic-orange) !important;
}
.scan-card .card-header .card-title {
    font-family: 'Bangers', cursive !important;
    letter-spacing: 2px !important;
    color: var(--comic-orange) !important;
    font-size: 1.2rem !important;
}
.scan-card .btn-comic:hover { transform: translateY(-2px); }
.book-item-scan {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 14px;
    border: 2px solid var(--comic-dark);
    box-shadow: 3px 3px 0 var(--comic-dark);
    background: #fff;
    margin-bottom: 10px;
    transition: all 0.2s;
}
.book-item-scan:hover { transform: translateX(4px); box-shadow: 5px 5px 0 var(--comic-dark); }
.book-item-scan .book-num {
    width: 36px; height: 36px;
    background: var(--comic-orange);
    border: 2px solid var(--comic-dark);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Bangers', cursive;
    font-size: 1.2rem;
    color: #fff;
    flex-shrink: 0;
}
.book-item-scan .book-info strong { font-family: 'Fredoka One', cursive; font-size:0.88rem; color:var(--comic-dark); }
.book-item-scan .book-info small { color:#aaa; font-weight:700; }
.book-item-scan .btn-remove-book {
    background: var(--comic-red) !important;
    border: 2px solid var(--comic-dark) !important;
    box-shadow: 2px 2px 0 var(--comic-dark) !important;
    border-radius: 0 !important;
    color: #fff !important;
    min-width: 32px; min-height: 32px;
    display: flex; align-items: center; justify-content: center;
}
#qr-reader { border: 3px solid var(--comic-dark) !important; box-shadow: 4px 4px 0 var(--comic-dark) !important; }
</style>
@endpush

@section('content')
<div class="row g-5">
    {{-- Left: Scanner --}}
    <div class="col-lg-5">
        <div class="card scan-card">
            <div class="card-header">
                <div class="card-title">📷 SCAN QR BUKU</div>
            </div>
            <div class="card-body">
                <div id="qr-reader" style="width: 100%;"></div>
                <div id="qr-reader-results" class="mt-3"></div>

                <div style="border-top:2px dashed #ddd; margin: 20px 0;"></div>

                <div style="font-family:'Fredoka One', cursive; font-size:0.8rem; color:var(--comic-orange); letter-spacing:2px; margin-bottom:10px;">
                    🔢 INPUT MANUAL
                </div>
                <div class="d-flex gap-2">
                    <input type="text" id="manual-book-code" class="form-control form-control-solid"
                        placeholder="Ketik kode buku..." style="font-weight:800;">
                    <button type="button" class="btn btn-comic" id="btn-manual-add">
                        <i class="ki-duotone ki-plus fs-4"></i> ADD
                    </button>
                </div>
                <div id="manual-error" class="text-danger mt-2 d-none" style="font-weight:700; font-size:0.82rem;"></div>
            </div>
        </div>
    </div>

    {{-- Right: Selected Books --}}
    <div class="col-lg-7">
        <div class="card scan-card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">📕 BUKU DIPILIH</div>
                <span id="book-count" class="badge badge-light-primary d-none" style="font-size:0.85rem; border-radius:0 !important; border:2px solid currentColor !important;">
                    0 buku
                </span>
            </div>
            <div class="card-body">
                <div id="selected-books">
                    <div class="text-center text-muted py-10">
                        <div style="font-size:3rem; margin-bottom:10px;">📚</div>
                        <div style="font-family:'Bangers',cursive; font-size:1.2rem; letter-spacing:2px; color:var(--comic-dark);">
                            BELUM ADA BUKU
                        </div>
                        <div style="font-size:0.82rem; font-weight:700; margin-top:4px;">
                            Scan QR atau ketik kode buku
                        </div>
                    </div>
                </div>

                <form id="borrow-form" class="d-none">
                    <div style="border-top:2px dashed var(--comic-dark); margin: 20px 0;"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:0.78rem; font-family:'Fredoka One', cursive; letter-spacing:1px;">
                                👤 ANGGOTA
                            </label>
                            <select name="member_id" class="form-select" required>
                                <option value="">— Pilih —</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->member_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size:0.78rem; font-family:'Fredoka One', cursive; letter-spacing:1px;">
                                📅 JATUH TEMPO
                            </label>
                            <input type="date" name="due_date" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold" style="font-size:0.78rem; font-family:'Fredoka One', cursive; letter-spacing:1px;">
                                📝 CATATAN
                            </label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-comic w-100" style="padding:14px !important; font-size:1rem !important;">
                                <i class="ki-duotone ki-check fs-4" style="color:#fff !important;"></i>
                                SIMPAN PEMINJAMAN
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('vendor-js')
<script src="https://unpkg.com/html5-qrcode@2.0.3/min/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('custom-js')
<script>
(function () {
    let selectedBooks = [];

    function updateUI() {
        const container = document.getElementById('selected-books');
        const form = document.getElementById('borrow-form');
        const countBadge = document.getElementById('book-count');

        countBadge.textContent = selectedBooks.length + ' buku';
        countBadge.classList.toggle('d-none', selectedBooks.length === 0);

        if (selectedBooks.length === 0) {
            container.innerHTML = `
                <div class="text-center text-muted py-10">
                    <div style="font-size:3rem; margin-bottom:10px;">📚</div>
                    <div style="font-family:'Bangers',cursive; font-size:1.2rem; letter-spacing:2px; color:var(--comic-dark);">BELUM ADA BUKU</div>
                    <div style="font-size:0.82rem; font-weight:700; margin-top:4px;">Scan QR atau ketik kode buku</div>
                </div>
            `;
            form.classList.add('d-none');
            return;
        }

        form.classList.remove('d-none');

        const html = selectedBooks.map((book, index) => `
            <div class="book-item-scan">
                <div class="book-num">${index + 1}</div>
                <div class="book-info flex-grow-1">
                    <strong>📕 ${book.title}</strong><br/>
                    <small>${book.book_code}${book.category ? ' | ' + book.category : ''}</small>
                </div>
                <button type="button" class="btn btn-remove-book" onclick="removeBook(${book.id})">
                    <i class="ki-duotone ki-trash fs-5" style="color:#fff !important;"></i>
                </button>
            </div>
        `).join('');

        container.innerHTML = html;
    }

    window.removeBook = function (bookId) {
        selectedBooks = selectedBooks.filter(b => b.id !== bookId);
        updateUI();
    };

    function addBook(book) {
        if (selectedBooks.find(b => b.id === book.id)) {
            Swal.fire({ icon: 'warning', title: 'Buku sudah ditambahkan', text: book.title, toast: true, position: 'top-end', timer: 2000 });
            return;
        }
        selectedBooks.push(book);
        updateUI();
    }

    async function lookupBook(code) {
        try {
            const response = await fetch(`/admin/books/lookup?code=${encodeURIComponent(code)}`);
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Buku tidak ditemukan');
            addBook(data);
            document.getElementById('manual-error').classList.add('d-none');
        } catch (error) {
            document.getElementById('manual-error').textContent = '⚠️ ' + error.message;
            document.getElementById('manual-error').classList.remove('d-none');
        }
    }

    document.getElementById('btn-manual-add').addEventListener('click', function () {
        const code = document.getElementById('manual-book-code').value.trim();
        if (!code) return;
        lookupBook(code);
    });

    document.getElementById('manual-book-code').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); lookupBook(this.value.trim()); }
    });

    // QR Scanner
    const html5QrCode = new Html5Qrcode("qr-reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText) => { lookupBook(decodedText); },
        () => {}
    ).catch(() => {
        document.getElementById('qr-reader').innerHTML = `
            <div class="alert" style="background:#fff8f0; border:2px solid var(--comic-orange); border-radius:0; padding:12px;">
                <strong style="font-family:'Fredoka One', cursive;">⚠️ Kamera tidak tersedia</strong>
                <div style="font-size:0.82rem; font-weight:700; margin-top:4px;">Gunakan input manual untuk menambahkan buku.</div>
            </div>
        `;
    });

    // Form submission
    document.getElementById('borrow-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        if (selectedBooks.length === 0) {
            Swal.fire({ icon: 'error', title: 'Belum ada buku', text: 'Tambahkan setidaknya satu buku.' });
            return;
        }
        const response = await fetch('{{ route('admin.borrowings.store') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({
                member_id: this.querySelector('[name="member_id"]').value,
                book_ids: selectedBooks.map(b => b.id),
                due_date: this.querySelector('[name="due_date"]').value,
                notes: this.querySelector('[name="notes"]').value || '',
            }),
        });
        const data = await response.json();
        if (response.ok) {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Peminjaman berhasil disimpan.' }).then(() => {
                window.location.href = '{{ route('admin.borrowings.index') }}';
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan.' });
        }
    });

    // Set default due date 7 days from now
    const dueDateInput = document.querySelector('input[name="due_date"]');
    if (dueDateInput) {
        const d = new Date();
        d.setDate(d.getDate() + 7);
        dueDateInput.value = d.toISOString().split('T')[0];
    }
})();
</script>
@endpush