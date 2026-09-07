@if ($paginator->hasPages())
    <div class="pagination-container">
        <div class="pagination-info">
            Hiển thị {{ $paginator->firstItem() ?? 0 }} – {{ $paginator->lastItem() ?? 0 }} / {{ $paginator->total() }} mục
        </div>
        <div class="pagination-buttons">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="btn-page" disabled style="opacity:.4; cursor:not-allowed;">&laquo;</button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn-page" rel="prev">&laquo;</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <button class="btn-page" disabled style="opacity:.4; cursor:not-allowed;">{{ $element }}</button>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="btn-page active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="btn-page">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn-page" rel="next">&raquo;</a>
            @else
                <button class="btn-page" disabled style="opacity:.4; cursor:not-allowed;">&raquo;</button>
            @endif
        </div>
    </div>
@endif
