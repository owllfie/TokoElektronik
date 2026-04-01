@if ($paginator->hasPages())
    @php
        $startPage = max($paginator->currentPage() - 2, 1);
        $endPage = min($startPage + 4, $paginator->lastPage());
        $startPage = max($endPage - 4, 1);
    @endphp
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

            @if ($startPage > 1)
                <a href="{{ $paginator->url(1) }}">1</a>
                @if ($startPage > 2)
                    <span class="disabled">...</span>
                @endif
            @endif

            @foreach ($paginator->getUrlRange($startPage, $endPage) as $page => $url)
                @if ($page === $paginator->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($endPage < $paginator->lastPage())
                @if ($endPage < $paginator->lastPage() - 1)
                    <span class="disabled">...</span>
                @endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
            @endif

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
