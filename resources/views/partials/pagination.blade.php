@if ($paginator->hasPages())
    <div class="pagination-wrap">
        <p class="pagination-summary">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </p>
        <nav class="pagination" aria-label="Pagination">
            @if ($paginator->onFirstPage())
                <span class="disabled">&laquo; Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}">&laquo; Prev</a>
            @endif

            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page === $paginator->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}">Next &raquo;</a>
            @else
                <span class="disabled">Next &raquo;</span>
            @endif
        </nav>
    </div>
@elseif ($paginator->count())
    <p class="pagination-summary">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </p>
@endif
