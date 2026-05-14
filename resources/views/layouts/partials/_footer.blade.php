<!--begin::Footer-->
<br><br><br>
<div class="footer py-3 d-flex flex-lg-column" id="kt_footer" style="background: var(--comic-dark); border-top: 4px solid var(--comic-orange);">
    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
        <div class="text-white order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">{{ date('Y') }} &copy;</span>
            <a href="{{ url('/') }}" class="text-orange text-decoration-none fw-bold" style="color: var(--comic-orange) !important;">{{ app_setting('app_name', config('app.name')) }}</a>
            <span class="text-muted fw-semibold ms-1">{{ app_setting('app_tagline', 'Perpustakaan Digital Sekolah') }}</span>
        </div>
        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <a href="{{ url('/') }}" target="_blank" class="menu-link px-2" style="color: var(--comic-orange); font-size:0.75rem; font-weight:900;">🌐 Lihat Website</a>
            </li>
        </ul>
    </div>
</div>
<!--end::Footer-->
