@if ($paginator->hasPages())
<nav class="pagination">

    @if ($paginator->onFirstPage())
        <span class="page-item disabled"><span class="page-link">‹</span></span>
    @else
        <span class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">‹</a></span>
    @endif

    @foreach ($elements as $element)

        @if (is_string($element))
            <span class="page-item disabled"><span class="page-link">{{ $element }}</span></span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-item active"><span class="page-link">{{ $page }}</span></span>
                @else
                    <span class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></span>
                @endif
            @endforeach
        @endif

    @endforeach

    @if ($paginator->hasMorePages())
        <span class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">›</a></span>
    @else
        <span class="page-item disabled"><span class="page-link">›</span></span>
    @endif

</nav>
@endif
