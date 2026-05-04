@props([
'paginator',
])

<div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-6 py-4 bg-white border-t border-gray-200 rounded-b-[20px]">
    {{-- Left: Data info --}}
    <div class="text-sm text-gray-500">
        @if ($paginator->total() > 0)
        Showing
        <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
        –
        <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
        of
        <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
        entries
        @else
        No entries found
        @endif
    </div>

    {{-- Right: Custom pagination links --}}
    @if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
        <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed select-none">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Prev
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Prev
        </a>
        @endif

        {{-- Page Numbers --}}
        @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();

        // Show max 5 pages with ellipsis
        $start = max(1, $currentPage - 2);
        $end = min($lastPage, $currentPage + 2);

        // Adjust range edges
        if ($currentPage <= 3) {
            $end=min($lastPage, 5);
            }
            if ($currentPage>= $lastPage - 2) {
            $start = max(1, $lastPage - 4);
            }
            @endphp

            {{-- First page + ellipsis --}}
            @if ($start > 1)
            <a href="{{ $paginator->url(1) }}" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">
                1
            </a>
            @if ($start > 2)
            <span class="inline-flex items-center justify-center w-9 h-9 text-sm text-gray-400 select-none">…</span>
            @endif
            @endif

            {{-- Page range --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page==$currentPage)
                <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-bold text-white bg-[#205252] rounded-lg shadow-sm shadow-[#205252]/30">
                {{ $page }}
                </span>
                @else
                <a href="{{ $paginator->url($page) }}" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">
                    {{ $page }}
                </a>
                @endif
                @endfor

                {{-- Last page + ellipsis --}}
                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                    <span class="inline-flex items-center justify-center w-9 h-9 text-sm text-gray-400 select-none">…</span>
                    @endif
                    <a href="{{ $paginator->url($lastPage) }}" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">
                        {{ $lastPage }}
                    </a>
                    @endif

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-[#205252] bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-[#205252] hover:text-white hover:border-[#205252] hover:shadow-md">
                        Next
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    @else
                    <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed select-none">
                        Next
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                    @endif
    </nav>
    @endif
</div>