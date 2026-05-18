@if ($paginator->hasPages())
    <div class="d-flex align-items-center justify-content-start gap-2 flex-wrap" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-item-custom text-dark" aria-disabled="true" aria-label="Previous page">
                <i class="bi bi-chevron-left fs-6 lh-1"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-item-custom text-dark" aria-label="Previous page">
                <i class="bi bi-chevron-left fs-6 lh-1"></i>
            </a>
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
                        <a href="#" class="page-item-custom active" aria-current="page">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}" class="page-item-custom">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-item-custom text-dark" aria-label="Next page">
                <i class="bi bi-chevron-right fs-6 lh-1"></i>
            </a>
        @else
            <span class="page-item-custom text-dark" aria-disabled="true" aria-label="Next page">
                <i class="bi bi-chevron-right fs-6 lh-1"></i>
            </span>
        @endif
    </div>
@endif
