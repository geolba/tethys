@if ($paginator->hasPages())
    <nav class="pagination is-rounded is-small" role="navigation" aria-label="pagination">
        <ul class="pagination-list">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li><a class="pagination-previous disabled" disabled href="#" rel="prev">&laquo;</a></li>
            @else
                <li><a class="pagination-previous" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)            
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><a class="pagination-link is-current">{{ $page }}</a></li>
                        @else
                            <li><a class="pagination-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a  class="pagination-next" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li><a  class="pagination-next disabled" disabled href="#" rel="next">&raquo;</a></li>
            @endif
        </ul>
    </nav>
@endif
