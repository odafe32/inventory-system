{{-- resources/views/vendor/pagination/custom.blade.php --}}
@if ($paginator->hasPages())
    <nav>
        <ul class="pagination d-flex justify-content-center align-items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="btn btn-light btn-sm px-3">
                        <i class="bx bx-chevron-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="btn btn-light btn-sm px-3" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="bx bx-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="btn btn-light btn-sm">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="btn btn-primary btn-sm px-3">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="btn btn-light btn-sm px-3" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="btn btn-light btn-sm px-3" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <i class="bx bx-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="btn btn-light btn-sm px-3">
                        <i class="bx bx-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif

{{-- Add these styles to your CSS --}}
<style>
    .pagination {
        margin: 0;
    }

    .page-item.active .btn-primary {
        background-color: #556ee6;
        border-color: #556ee6;
        color: #fff;
    }

    .page-item .btn-light {
        color: #6c757d;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    .page-item.disabled .btn-light {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        opacity: 0.65;
        pointer-events: none;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
</style>
