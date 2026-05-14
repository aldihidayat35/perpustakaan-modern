<!--begin::Header-->
<div id="kt_header" class="header align-items-stretch">
    <!--begin::Brand-->
    <div class="header-brand">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2">
            @if(app_setting('app_logo'))
            <img src="{{ asset('storage/' . app_setting('app_logo')) }}" alt="Logo"
                style="height:30px; width:auto; object-fit:contain; border-radius:4px; border:2px solid var(--comic-orange);"/>
            @else
            <span style="font-size:1.4rem;">📚</span>
            @endif
            <span class="text-white fw-bold fs-5 d-none d-md-inline" style="font-family:'Fredoka One',sans-serif; letter-spacing:1px;">
                {{ app_setting('app_name', config('app.name')) }}
            </span>
        </a>

        <div id="kt_aside_toggle"
            class="btn btn-icon w-auto px-0 btn-active-color-primary aside-minimize"
            data-kt-toggle="true"
            data-kt-toggle-state="active"
            data-kt-toggle-target="body"
            data-kt-toggle-name="aside-minimize">
            <i class="ki-duotone ki-entrance-right fs-2 text-white"><span class="path1"></span><span class="path2"></span></i>
            <i class="ki-duotone ki-entrance-left fs-2 text-white minimize-active"><span class="path1"></span><span class="path2"></span></i>
        </div>

        <div class="d-flex align-items-center d-lg-none me-n2" title="Show aside menu">
            <div class="btn btn-icon btn-active-color-primary w-30px h-30px" id="kt_aside_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-1"><span class="path1"></span><span class="path2"></span></i>
            </div>
        </div>
    </div>
    <!--end::Brand-->

    <!--begin::Toolbar-->
    <div class="toolbar d-flex align-items-stretch">
        <div class="container-fluid py-4 py-lg-0 d-flex flex-column flex-lg-row align-items-lg-stretch justify-content-lg-between w-100">

            {{-- Page Title Area --}}
            <div class="page-title d-flex justify-content-center flex-column me-5">
                <h1 class="d-flex flex-column text-white fw-bold fs-3 mb-0" style="font-family:'Bangers',cursive; letter-spacing:2px;">
                    @yield('page-title', 'Dashboard')
                </h1>
                @yield('breadcrumb')
            </div>

            {{-- Right Actions --}}
            <div class="d-flex align-items-stretch overflow-auto pt-3 pt-lg-0">
                <div class="d-flex align-items-center gap-3">

                    {{-- View Website Button --}}
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-sm d-none d-md-flex align-items-center gap-1"
                        style="background:rgba(255,255,255,0.1); color:#fff; border:2px solid rgba(255,255,255,0.2); border-radius:0; font-weight:800;">
                        <i class="ki-duotone ki-external-up fs-2"></i>
                        <span>Lihat Website</span>
                    </a>

                    {{-- Quick Add Book --}}
                    <a href="{{ route('admin.books.index') }}" class="btn btn-sm d-none d-lg-flex align-items-center gap-1"
                        style="background:var(--comic-orange); color:#fff; border:2px solid var(--comic-dark); border-radius:0; font-weight:800; box-shadow:3px 3px 0 var(--comic-dark);">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        <span>Buku Baru</span>
                    </a>

                    {{-- Theme Toggle --}}
                    <div class="d-flex align-items-center">
                        <a href="#" class="btn btn-icon btn-custom btn-active-color-primary"
                            data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
                            data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-night-day theme-light-show fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span></i>
                            <i class="ki-duotone ki-moon theme-dark-show fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                            data-kt-menu="true" data-kt-element="theme-mode-menu">
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                    <span class="menu-icon"><i class="ki-duotone ki-night-day fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span></i></span>
                                    <span class="menu-title">Light</span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                    <span class="menu-icon"><i class="ki-duotone ki-moon fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                                    <span class="menu-title">Dark</span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                    <span class="menu-icon"><i class="ki-duotone ki-screen fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                                    <span class="menu-title">System</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- User Menu --}}
                    <div class="d-flex align-items-center">
                        <div class="cursor-pointer symbol symbol-35px symbol-md-45px"
                            data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
                            data-kt-menu-placement="bottom-end">
                            @if(auth()->user()->avatar)
                                <img alt="avatar" src="{{ asset('storage/' . auth()->user()->avatar) }}" style="border:2px solid var(--comic-orange);"/>
                            @else
                                <div class="symbol-label fs-4 fw-bold" style="background:var(--comic-orange); color:#fff; border:2px solid var(--comic-dark);">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-300px"
                            data-kt-menu="true" style="border:3px solid var(--comic-dark); box-shadow:5px 5px 0 var(--comic-dark);">
                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">
                                    <div class="symbol symbol-45px me-3">
                                        @if(auth()->user()->avatar)
                                            <img alt="avatar" src="{{ asset('storage/' . auth()->user()->avatar) }}"/>
                                        @else
                                            <div class="symbol-label fs-3 fw-bold" style="background:var(--comic-orange); color:#fff; border:2px solid var(--comic-dark);">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="fw-bold d-flex align-items-center fs-5">
                                            {{ auth()->user()->name }}
                                            <span class="badge ms-2 fw-bold fs-8 px-2 py-1" style="background:var(--comic-orange); color:#fff; border:2px solid var(--comic-dark);">{{ ucfirst(auth()->user()->role ?? 'admin') }}</span>
                                        </div>
                                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->email }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="menu-link px-5 border-0 bg-transparent w-100 text-start fw-bold" style="border-radius:0; color:var(--comic-red);">
                                        <i class="ki-duotone ki-entrance-left me-2"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->
</div>
<!--end::Header-->

<style>
    /* Comic Header */
    #kt_header {
        background: var(--comic-dark) !important;
        border-bottom: 4px solid var(--comic-orange) !important;
        box-shadow: 0 4px 0 rgba(255,107,53,0.3) !important;
    }
    .toolbar {
        background: var(--comic-dark) !important;
    }
</style>