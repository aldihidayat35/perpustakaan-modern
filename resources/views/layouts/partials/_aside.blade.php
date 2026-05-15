<!--begin::Aside-->
<div id="kt_aside" class="aside"
    data-kt-drawer="true"
    data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'240px', '300px': '280px'}"
    data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">

    {{-- Aside Header (brand + user) --}}
    <div class="aside-header flex-column-auto py-4 px-4" id="kt_aside_header">
   
        <div class="aside-user d-flex align-items-center gap-3 py-4">
            <div class="symbol symbol-45px">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="avatar"/>
                @else
                    <div class="symbol-label fs-3 fw-bold" style="background:var(--comic-orange); color:#fff; border:2px solid var(--comic-dark);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div>
                <div class="fw-bold text-white" style="font-size:0.9rem;">{{ auth()->user()->name }}</div>
                <div class="fs-8 text-gray-500 fw-semibold">{{ ucfirst(auth()->user()->role ?? 'admin') }}</div>
            </div>
        </div>
    </div>
    {{-- /Aside Header --}}

    {{-- Aside Menu --}}
    <div class="aside-menu flex-column-fluid">
        @include('layouts.partials._menu')
    </div>
    {{-- /Aside Menu --}}

    {{-- Aside Footer --}}
    <div class="aside-footer flex-column-auto py-4 px-4" id="kt_aside_footer">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-light btn-sm w-100 text-start mb-2" style="border-radius:0; border-color:rgba(255,255,255,0.3);">
            <i class="ki-duotone ki-external-up fs-2 me-2"></i> Lihat Website
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm w-100 fw-bold text-start"
                style="background:var(--comic-red); color:#fff; border-radius:0; border:2px solid var(--comic-dark); box-shadow:3px 3px 0 var(--comic-dark);">
                <i class="ki-duotone ki-entrance-left me-2 fs-2"></i> Logout
            </button>
        </form>
    </div>
    {{-- /Aside Footer --}}
</div>
{{--/end::Aside--}}

<style>
    /* Comic Aside Overrides */
    #kt_aside {
        background: var(--comic-dark) !important;
        border-right: 4px solid var(--comic-orange) !important;
    }
    #kt_aside .aside-header {
        border-bottom: 3px solid rgba(255,107,53,0.3) !important;
    }
    /* Active menu item */
    #kt_aside .menu-link.active {
        background: var(--comic-orange) !important;
        color: #fff !important;
        border-radius: 0 !important;
        border-left: 4px solid var(--comic-yellow) !important;
    }
    #kt_aside .menu-link:hover {
        background: rgba(255,107,53,0.15) !important;
        border-radius: 0 !important;
    }
    /* Menu items text */
    #kt_aside .menu-title {
        font-weight: 800 !important;
        font-size: 0.85rem !important;
    }
    /* Section label */
    #kt_aside .menu-heading {
        color: var(--comic-orange) !important;
        letter-spacing: 2px !important;
        font-size: 0.7rem !important;
    }
    /* Icon color */
    #kt_aside .menu-icon {
        color: rgba(255,255,255,0.6) !important;
    }
    #kt_aside .menu-link:hover .menu-icon,
    #kt_aside .menu-link.active .menu-icon {
        color: #fff !important;
    }
</style>
