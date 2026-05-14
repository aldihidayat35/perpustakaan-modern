{{-- Comic-styled Pagination Component --}}
@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $onFirst = $paginator->onFirstPage();
    $onLast = $paginator->hasMorePages() === false;
@endphp

@if($lastPage > 1)
<nav aria-label="{{ $ariaLabel ?? 'Navigasi halaman' }}" class="comic-pagination">
    <ul class="pagination justify-content-center flex-wrap gap-2">

        {{-- Previous --}}
        @if($onFirst)
            <li class="page-item">
                <span class="page-link page-btn page-btn-disabled">
                    <span aria-hidden="true">◀</span>&nbsp;PREV
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link page-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <span aria-hidden="true">◀</span>&nbsp;PREV
                </a>
            </li>
        @endif

        {{-- Page Numbers --}}
        @php
            $window = 2; // pages around current
            $start = max(1, $currentPage - $window);
            $end = min($lastPage, $currentPage + $window);

            // Always show first page
            $pages = [];
            for ($i = $start; $i <= $end; $i++) {
                $pages[] = $i;
            }
        @endphp

        {{-- First page if not in window --}}
        @if($start > 1)
            <li class="page-item">
                <a class="page-link page-btn" href="{{ $paginator->url(1) }}">01</a>
            </li>
            @if($start > 2)
                <li class="page-item">
                    <span class="page-link page-btn page-btn-dots">...</span>
                </li>
            @endif
        @endif

        {{-- Page numbers in window --}}
        @foreach($pages as $page)
            @if($page === $currentPage)
                <li class="page-item">
                    <span class="page-link page-btn page-btn-active">{{ str_pad((string)$page, 2, '0', STR_PAD_LEFT) }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link page-btn" href="{{ $paginator->url($page) }}">{{ str_pad((string)$page, 2, '0', STR_PAD_LEFT) }}</a>
                </li>
            @endif
        @endforeach

        {{-- Last page if not in window --}}
        @if($end < $lastPage)
            @if($end < $lastPage - 1)
                <li class="page-item">
                    <span class="page-link page-btn page-btn-dots">...</span>
                </li>
            @endif
            <li class="page-item">
                <a class="page-link page-btn" href="{{ $paginator->url($lastPage) }}">{{ str_pad((string)$lastPage, 2, '0', STR_PAD_LEFT) }}</a>
            </li>
        @endif

        {{-- Next --}}
        @if($onLast)
            <li class="page-item">
                <span class="page-link page-btn page-btn-disabled">
                    NEXT&nbsp;<span aria-hidden="true">▶</span>
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link page-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    NEXT&nbsp;<span aria-hidden="true">▶</span>
                </a>
            </li>
        @endif

    </ul>

    {{-- Page Info --}}
    <div class="page-info">
        <span>Halaman {{ $currentPage }} dari {{ $lastPage }}</span>
        <span>&bull;</span>
        <span>Total {{ number_format($paginator->total()) }} data</span>
    </div>
</nav>
@endif