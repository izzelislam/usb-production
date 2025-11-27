@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="text-sm text-gray-700">
            <span class="hidden sm:inline-block">
                Menampilkan
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-medium">{{ $paginator->total() }}</span>
                hasil
            </span>
        </div>

        <div class="flex-1 flex justify-between sm:justify-end">
            <div class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <!-- Previous Link -->
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-gray-400 cursor-not-allowed">
                        <i class="ph ph-caret-left"></i>
                        <span class="sr-only">Previous</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="ph ph-caret-left"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                @endif

                <!-- Pagination Elements -->
                @foreach ($elements as $element)
                    <!-- "Three Dots" Separator -->
                    @if (is_string($element))
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            {{ $element }}
                        </span>
                    @endif

                    <!-- Array Of Links -->
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-primary-500 bg-primary-50 text-sm font-medium text-primary-600 z-10">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                <!-- Next Link -->
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="ph ph-caret-right"></i>
                        <span class="sr-only">Next</span>
                    </a>
                @else
                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-gray-400 cursor-not-allowed">
                        <i class="ph ph-caret-right"></i>
                        <span class="sr-only">Next</span>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif

<!-- Simple Pagination -->
@if (isset($simple) && $simple)
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between items-center">
            <div>
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed rounded-md">
                        Previous
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 rounded-md">
                        Previous
                    </a>
                @endif
            </div>

            <div>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 rounded-md">
                        Next
                    </a>
                @else
                    <span class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed rounded-md">
                        Next
                    </span>
                @endif
            </div>
        </nav>
    @endif
@endif

<!-- Jump to Page Pagination -->
@if (isset($jumpTo) && $jumpTo)
    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </div>

        <div class="flex items-center space-x-2">
            <label for="page-jump" class="text-sm text-gray-700">Go to page:</label>
            <input type="number" id="page-jump" min="1" max="{{ $paginator->lastPage() }}" value="{{ $paginator->currentPage() }}"
                   class="w-16 px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <button onclick="window.location.href='?page=' + document.getElementById('page-jump').value"
                    class="px-3 py-1 bg-primary-600 text-white text-sm rounded-md hover:bg-primary-700">
                Go
            </button>
        </div>
    </div>
@endif