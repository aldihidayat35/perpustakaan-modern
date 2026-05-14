{{-- Comic Pagination Partial --}}
{{-- Usage: @include('layouts.partials._pagination', ['paginator' => $items]) --}}
@php
    $paginator = $paginator ?? $items ?? null;
    if (!$paginator) return;
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $onFirst = $paginator->onFirstPage();
    $onLast = !$paginator->hasMorePages();
    $total = $paginator->total();
@endphp

@if($lastPage > 1)
<div class="comic-pagination">
    <ul class="pagination">
        {{-- Prev --}}
        @if($onFirst)
            <li><span class="page-btn page-btn-disabled">◀&nbsp;PREV</span></li>
        @else
            <li><a class="page-btn" href="{{ $paginator->previousPageUrl() }}">◀&nbsp;PREV</a></li>
        @endif

        {{-- Page Numbers --}}
        @php
            $window = 2;
            $start = max(1, $currentPage - $window);
            $end = min($lastPage, $currentPage + $window);
            $pages = range($start, $end);
        @endphp

        @if($start > 1)
            <li><a class="page-btn" href="{{ $paginator->url(1) }}">01</a></li>
            @if($start > 2)
                <li><span class="page-btn" style="pointer-events:none; background:transparent; border:none; box-shadow:none; color:#ccc; font-size:0.9rem;">...</span></li>
            @endif
        @endif

        @foreach($pages as $page)
            @if($page === $currentPage)
                <li><span class="page-btn page-btn-active">{{ str_pad((string)$page, 2, '0', STR_PAD_LEFT) }}</span></li>
            @else
                <li><a class="page-btn" href="{{ $paginator->url($page) }}">{{ str_pad((string)$page, 2, '0', STR_PAD_LEFT) }}</a></li>
            @endif
        @endforeach

        @if($end < $lastPage)
            @if($end < $lastPage - 1)
                <li><span class="page-btn" style="pointer-events:none; background:transparent; border:none; box-shadow:none; color:#ccc; font-size:0.9rem;">...</span></li>
            @endif
            <li><a class="page-btn" href="{{ $paginator->url($lastPage) }}">{{ str_pad((string)$lastPage, 2, '0', STR_PAD_LEFT) }}</a></li>
        @endif

        {{-- Next --}}
        @if($onLast)
            <li><span class="page-btn page-btn-disabled">NEXT&nbsp;▶</span></li>
        @else
            <li><a class="page-btn" href="{{ $paginator->nextPageUrl() }}">NEXT&nbsp;▶</a></li>
        @endif
    </ul>
    <div class="page-info">
        Halaman {{ $currentPage }} dari {{ $lastPage }}
        &nbsp;&bull;&nbsp;
        Total {{ number_format($total) }} data
    </div>
</div>
@endif