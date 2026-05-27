@if ($paginator->hasPages())
    <div class="px-4 py-3 border-t border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-white">

        {{-- Showing Text --}}
        <div class="text-[12px] text-slate-500">
            Showing
            @if ($paginator->firstItem())
                <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}</span>
                to
                <span class="font-semibold text-slate-700">{{ $paginator->lastItem() }}</span>
            @else
                <span class="font-semibold text-slate-700">0</span>
            @endif
            of
            <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span>
            entries
        </div>

        {{-- Pagination --}}
        <div class="flex items-center gap-1 flex-wrap">

            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span
                    class="h-8 px-3 border border-slate-200 rounded-md
                           text-[12px] text-slate-400 bg-slate-100
                           flex items-center justify-center cursor-not-allowed">
                    Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   rel="prev"
                   class="no-underline h-8 px-3 border border-slate-300 rounded-md
                          text-[12px] text-slate-600 hover:bg-slate-100
                          flex items-center justify-center transition">
                    Prev
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)

                {{-- Dots --}}
                @if (is_string($element))
                    <span
                        class="h-8 min-w-[32px] px-2 rounded-md border border-slate-200
                               text-[12px] text-slate-400 bg-slate-50
                               flex items-center justify-center">
                        {{ $element }}
                    </span>
                @endif

                {{-- Page Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)

                        {{-- Current Page --}}
                        @if ($page == $paginator->currentPage())
                            <span
                                class="h-8 min-w-[32px] px-2 rounded-md
                                       bg-amber-500 text-white text-[12px] font-semibold
                                       flex items-center justify-center shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                               class="no-underline h-8 min-w-[32px] px-2 rounded-md border border-slate-300
                                      text-[12px] text-slate-700 hover:bg-slate-100
                                      flex items-center justify-center transition">
                                {{ $page }}
                            </a>
                        @endif

                    @endforeach
                @endif

            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   rel="next"
                   class="no-underline h-8 px-3 border border-slate-300 rounded-md
                          text-[12px] text-slate-600 hover:bg-slate-100
                          flex items-center justify-center transition">
                    Next
                </a>
            @else
                <span
                    class="h-8 px-3 border border-slate-200 rounded-md
                           text-[12px] text-slate-400 bg-slate-100
                           flex items-center justify-center cursor-not-allowed">
                    Next
                </span>
            @endif

        </div>

    </div>
@endif