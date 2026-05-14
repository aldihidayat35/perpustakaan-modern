<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <base href="{{ url('/') }}/"/>
    <title>@yield('title', 'Dashboard') - {{ app_setting('app_name', config('app.name')) }}</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="shortcut icon" href="{{ app_setting('favicon', 'assets/media/logos/favicon.ico') }}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700;Bangers&family=Nunito:wght@400;600;700;900;900&family=Fredoka+One&display=swap"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'"/>
    @stack('vendor-css')
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
    @stack('custom-css')
    <style>
        :root {
            --comic-cream: #FFF8F0;
            --comic-orange: #FF6B35;
            --comic-dark: #1A1A2E;
            --comic-blue: #4ECDC4;
            --comic-yellow: #FFE66D;
            --comic-red: #FF3366;
            --comic-green: #00C896;
        }
        * { box-sizing: border-box; }
        /* Comic override for all admin pages */
        .card {
            border-radius: 0 !important;
            border: 3px solid var(--comic-dark) !important;
            box-shadow: 4px 4px 0 var(--comic-dark) !important;
        }
        .card-header {
            background: var(--comic-dark) !important;
            border-bottom: 3px solid var(--comic-orange) !important;
        }
        .card-header .card-title {
            font-family: 'Bangers', cursive !important;
            letter-spacing: 2px !important;
            color: var(--comic-orange) !important;
            font-size: 1.2rem !important;
        }
        .btn {
            border-radius: 0 !important;
            font-weight: 800 !important;
        }
        .btn-primary {
            background: var(--comic-orange) !important;
            border-color: var(--comic-dark) !important;
            box-shadow: 3px 3px 0 var(--comic-dark) !important;
            color: #fff !important;
        }
        .btn-primary:hover {
            background: var(--comic-yellow) !important;
            color: var(--comic-dark) !important;
            transform: translateY(-2px);
            box-shadow: 5px 5px 0 var(--comic-dark) !important;
        }
        .btn-secondary {
            border-color: var(--comic-dark) !important;
            box-shadow: 3px 3px 0 var(--comic-dark) !important;
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 5px 5px 0 var(--comic-dark) !important;
        }
        .btn-light-primary {
            border: 2px solid var(--comic-dark) !important;
            box-shadow: 3px 3px 0 var(--comic-dark) !important;
        }
        .btn-light-primary:hover {
            background: var(--comic-cream) !important;
            transform: translateY(-2px);
            box-shadow: 5px 5px 0 var(--comic-dark) !important;
        }
        .btn-light-danger {
            border: 2px solid var(--comic-red) !important;
            color: var(--comic-red) !important;
            box-shadow: 3px 3px 0 var(--comic-red) !important;
        }
        .btn-light-danger:hover {
            background: var(--comic-red) !important;
            color: #fff !important;
            transform: translateY(-2px);
        }
        .form-control, .form-select {
            border-radius: 0 !important;
            border: 2px solid var(--comic-dark) !important;
            box-shadow: 3px 3px 0 var(--comic-dark) !important;
            font-weight: 700 !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--comic-orange) !important;
            box-shadow: 4px 4px 0 var(--comic-orange) !important;
            outline: none !important;
        }
        .form-label {
            font-weight: 900 !important;
        }
        .table {
            font-weight: 700 !important;
        }
        .table thead th {
            background: var(--comic-dark) !important;
            color: var(--comic-orange) !important;
            border-bottom: 3px solid var(--comic-orange) !important;
            font-family: 'Fredoka One', cursive !important;
            font-size: 0.8rem !important;
            letter-spacing: 1px !important;
            text-transform: uppercase !important;
        }
        .table tbody tr:hover {
            background: rgba(255,107,53,0.08) !important;
        }
        .badge {
            border-radius: 0 !important;
            font-weight: 900 !important;
            border: 2px solid currentColor !important;
        }
        .modal-content {
            border-radius: 0 !important;
            border: 4px solid var(--comic-dark) !important;
            box-shadow: 8px 8px 0 var(--comic-dark) !important;
        }
        .modal-header {
            background: var(--comic-dark) !important;
            border-bottom: 3px solid var(--comic-orange) !important;
            color: #fff !important;
        }
        .modal-title {
            font-family: 'Bangers', cursive !important;
            color: var(--comic-orange) !important;
            letter-spacing: 2px !important;
            font-size: 1.3rem !important;
        }
        .btn-close { filter: invert(1); }
        .page-title h1 {
            font-family: 'Bangers', cursive !important;
            letter-spacing: 3px !important;
            color: #fff !important;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.5) !important;
        }
        .breadcrumb-item a { font-weight: 800 !important; }
        .page-link {
            border-radius: 0 !important;
            border: 2px solid var(--comic-dark) !important;
            font-weight: 800 !important;
        }
        .page-item.active .page-link {
            background: var(--comic-orange) !important;
            border-color: var(--comic-dark) !important;
            color: #fff !important;
        }
        /* Sidebar scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--comic-orange); border-radius: 0; }

        /* ── Shared Admin Comic Components ── */
        .page { background: var(--comic-cream) !important; }
        .header-brand { background: var(--comic-dark) !important; }
        .header { background-color: var(--comic-dark) !important; }
        .toolbar { background: var(--comic-dark) !important; }
        #kt_toolbar { background: transparent !important; }
        .content { background: var(--comic-cream) !important; }

        /* Comic Search Bar */
        .comic-search-bar {
            background: var(--comic-dark);
            border: 3px solid var(--comic-dark);
            box-shadow: 6px 6px 0 var(--comic-orange);
            padding: 18px 22px;
            margin-bottom: 20px;
            position: relative;
        }
        .comic-search-bar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(45deg, transparent, transparent 30px, rgba(255,107,53,0.05) 30px, rgba(255,107,53,0.05) 31px);
            pointer-events: none;
        }
        .comic-search-bar .form-control,
        .comic-search-bar .form-select {
            border: 2px solid var(--comic-dark) !important;
            border-radius: 0 !important;
            font-weight: 800;
            box-shadow: 3px 3px 0 var(--comic-dark) !important;
            background: #fff !important;
            color: var(--comic-dark) !important;
        }
        .comic-search-bar .form-control:focus,
        .comic-search-bar .form-select:focus {
            border-color: var(--comic-orange) !important;
            box-shadow: 4px 4px 0 var(--comic-orange) !important;
            outline: none !important;
        }
        .comic-search-bar .form-control::placeholder { color: #bbb !important; font-weight: 700 !important; }
        .comic-search-bar .form-label {
            font-family: 'Fredoka One', cursive;
            font-size: 0.68rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--comic-orange);
            margin-bottom: 4px;
            display: block;
            font-weight: 900;
        }
        .comic-search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--comic-orange) !important;
            font-size: 1.1rem;
            z-index: 5;
            pointer-events: none;
        }
        .comic-search-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .comic-search-wrap .form-control { padding-left: 40px !important; }

        /* Comic Button */
        .btn-comic {
            background: var(--comic-orange) !important;
            border: 2px solid var(--comic-dark) !important;
            border-radius: 0 !important;
            box-shadow: 4px 4px 0 var(--comic-dark) !important;
            color: #fff !important;
            font-family: 'Fredoka One', cursive !important;
            font-size: 0.85rem !important;
            letter-spacing: 1px;
            font-weight: 900 !important;
            padding: 9px 16px !important;
            transition: all 0.2s ease;
        }
        .btn-comic:hover {
            background: var(--comic-yellow) !important;
            color: var(--comic-dark) !important;
            transform: translateY(-2px);
            box-shadow: 5px 6px 0 var(--comic-dark) !important;
        }

        /* Comic Filter Buttons */
        .btn-filter {
            border: 2px solid rgba(255,255,255,0.3) !important;
            border-radius: 0 !important;
            font-family: 'Fredoka One', cursive !important;
            font-size: 0.75rem !important;
            letter-spacing: 1px;
            font-weight: 900 !important;
            padding: 7px 14px !important;
            box-shadow: 3px 3px 0 rgba(255,255,255,0.1) !important;
            transition: all 0.2s ease;
            color: rgba(255,255,255,0.7) !important;
            background: rgba(255,255,255,0.05) !important;
        }
        .btn-filter:hover, .btn-filter.active {
            background: var(--comic-orange) !important;
            border-color: var(--comic-orange) !important;
            color: #fff !important;
            box-shadow: 4px 4px 0 var(--comic-dark) !important;
            transform: translateY(-2px);
        }

        /* Comic Stat / Summary Cards */
        .comic-stat {
            border: 3px solid var(--comic-dark) !important;
            box-shadow: 5px 5px 0 var(--comic-dark) !important;
            border-radius: 0 !important;
            padding: 20px 22px;
            background: #fff;
            position: relative;
            overflow: hidden;
        }
        .comic-stat:hover { transform: translateY(-3px); box-shadow: 7px 9px 0 var(--comic-dark) !important; }
        .comic-stat .stat-icon { font-size: 2.2rem; }
        .comic-stat .stat-value {
            font-family: 'Bangers', cursive;
            font-size: 2.2rem;
            line-height: 1;
            text-shadow: 2px 2px 0 rgba(0,0,0,0.1);
        }
        .comic-stat .stat-label {
            font-family: 'Fredoka One', cursive;
            font-size: 0.68rem;
            color: #aaa;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* Table improvements */
        .comic-table-wrap {
            overflow-x: auto !important;
            max-width: 100%;
        }
        .comic-table-wrap .table { min-width: 100%; white-space: nowrap; }
        .comic-table-wrap .table > thead > tr > th:first-child,
        .comic-table-wrap .table > tbody > tr > td:first-child { padding-left: 18px; }
        .comic-table-wrap .table > thead > tr > th:last-child,
        .comic-table-wrap .table > tbody > tr > td:last-child { padding-right: 22px; }
        .comic-table-wrap .table tbody tr:hover { background: rgba(255,107,53,0.06) !important; }

        /* Comic Action Buttons */
        .btn-comic-edit,
        .btn-comic-delete {
            min-width: 38px;
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 !important;
            font-family: 'Fredoka One', cursive !important;
            font-size: 0.78rem !important;
            font-weight: 900 !important;
            letter-spacing: 1px;
            padding: 5px 10px !important;
            transition: all 0.2s ease;
        }
        .btn-comic-edit {
            background: var(--comic-orange) !important;
            border: 2px solid var(--comic-dark) !important;
            box-shadow: 3px 3px 0 var(--comic-dark) !important;
            color: #fff !important;
        }
        .btn-comic-edit:hover {
            background: var(--comic-yellow) !important;
            color: var(--comic-dark) !important;
            transform: translateY(-2px);
            box-shadow: 4px 4px 0 var(--comic-dark) !important;
        }
        .btn-comic-delete {
            background: #fff !important;
            border: 2px solid var(--comic-red) !important;
            box-shadow: 3px 3px 0 var(--comic-red) !important;
            color: var(--comic-red) !important;
        }
        .btn-comic-delete:hover {
            background: var(--comic-red) !important;
            color: #fff !important;
            transform: translateY(-2px);
            box-shadow: 4px 4px 0 var(--comic-dark) !important;
        }
        /* Fix ki-duotone icon colors inside comic buttons */
        .btn-comic-edit i, .btn-comic-edit i span,
        .btn-comic-delete i, .btn-comic-delete i span {
            color: inherit !important;
            fill: currentColor !important;
        }

        /* Comic Pagination */
        .comic-pagination {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 24px;
        }
        .comic-pagination .pagination {
            gap: 4px;
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .page-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            height: 42px;
            padding: 5px 10px;
            background: #fff;
            border: 3px solid var(--comic-dark) !important;
            box-shadow: 3px 3px 0 var(--comic-dark);
            font-family: 'Fredoka One', cursive;
            font-size: 0.82rem;
            color: var(--comic-dark);
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border-radius: 0 !important;
            white-space: nowrap;
        }
        .page-btn:hover:not(.page-btn-disabled):not(.page-btn-active) {
            background: var(--comic-yellow);
            color: var(--comic-dark);
            transform: translateY(-2px);
            box-shadow: 4px 5px 0 var(--comic-dark);
        }
        .page-btn-active {
            background: var(--comic-orange) !important;
            color: #fff !important;
            border-color: var(--comic-dark) !important;
            box-shadow: 4px 4px 0 var(--comic-dark) !important;
            transform: translateY(-2px);
            cursor: default;
        }
        .page-btn-disabled {
            background: #eee !important;
            color: #aaa !important;
            border-color: #ccc !important;
            box-shadow: 2px 2px 0 #ccc !important;
            cursor: not-allowed;
            pointer-events: none;
        }
        .page-info {
            font-family: 'Fredoka One', cursive;
            font-size: 0.7rem;
            color: #aaa;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Comic Empty State */
        .comic-empty {
            text-align: center;
            padding: 40px 20px;
        }
        .comic-empty .empty-emoji { font-size: 3.5rem; margin-bottom: 12px; display: block; }
        .comic-empty .empty-title {
            font-family: 'Bangers', cursive;
            font-size: 1.3rem;
            letter-spacing: 3px;
            color: var(--comic-dark);
        }
        .comic-empty .empty-sub {
            font-size: 0.82rem;
            color: #aaa;
            font-weight: 700;
            margin-top: 6px;
        }
    </style>
</head>
<body id="kt_body" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on" class="aside-enabled"
    style="background-color: var(--comic-cream) !important;">
    @include('layouts.partials._theme-mode')
    @include('layouts.partials._loader')

    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            @include('layouts.partials._aside')

            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                @include('layouts.partials._header')

                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    @include('layouts.partials._page-title')

                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div id="kt_content_container" class="container-fluid">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                                    <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                                    <div>{{ session('success') }}</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                                    <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                                    <div>{{ session('error') }}</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @yield('content')
                        </div>
                    </div>
                </div>

                @include('layouts.partials._footer')
            </div>
        </div>
    </div>

    @include('layouts.partials._scrolltop')

    @php
    /**
     * Comic Pagination Component
     * Usage: @include('layouts.partials._pagination', ['paginator' => $items])
     */
    @endphp

    <script>var hostUrl = "assets/";</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    @stack('vendor-js')
    @stack('custom-js')
</body>
</html>
