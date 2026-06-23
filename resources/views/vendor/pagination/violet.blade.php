@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between flex-col sm:flex-row gap-3 w-full">
        <div>
            <p class="text-sm text-gray-500">
                Showing
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                to
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                of
                <span class="font-medium">{{ $paginator->total() }}</span>
                results
            </p>
        </div>

        <div class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="inline-flex items-center gap-1 px-3 py-2 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-400 cursor-not-allowed" aria-hidden="true">
                        &laquo; Previous
                    </span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors" aria-label="{{ __('pagination.previous') }}">
                    &laquo; Previous
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true">
                        <span class="inline-flex items-center justify-center min-w-[2.25rem] px-3 py-2 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-400">{{ $element }}</span>
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page">
                                <span class="inline-flex items-center justify-center min-w-[2.25rem] px-3 py-2 rounded-lg bg-violet-700 text-white text-xs font-bold shadow-sm shadow-violet-900/30">{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[2.25rem] px-3 py-2 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg bg-violet-700 text-white text-xs font-bold hover:bg-violet-800 transition-colors shadow-sm shadow-violet-900/30" aria-label="{{ __('pagination.next') }}">
                    Next &raquo;
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="inline-flex items-center gap-1 px-3 py-2 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-400 cursor-not-allowed" aria-hidden="true">
                        Next &raquo;
                    </span>
                </span>
            @endif
        </div>
    </nav>
@endif
