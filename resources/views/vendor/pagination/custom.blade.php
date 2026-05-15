@if ($paginator->hasPages())
    <div class="d-flex align-items-center justify-content-flex-start gap-2" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="text-dark"><i class="bi bi-chevron-left"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="text-dark"><i class="bi bi-chevron-left"></i></a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="text-muted">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a href="#" class="page-link-custom active">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}" class="page-link-custom">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="text-dark"><i class="bi bi-chevron-right"></i></a>
        @else
            <span class="text-dark"><i class="bi bi-chevron-right"></i></span>
        @endif
    </div>
@endif
