@if ($paginator->hasPages())
    <div style="text-align: center">
        <ul class="flex-pagination">
            <li class="flex-item pn @if($paginator->onFirstPage()) disabled-link @endif">
                <a class="flex-link" href="{{ $paginator->previousPageUrl() }}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>

            <?php
            $start = $paginator->currentPage() - $paginator->onEachSide; // show 3 pagination links before current
            $end = $paginator->currentPage() + $paginator->onEachSide; // show 3 pagination links after current
            if($start < 1) {
                $start = 1; // reset start to 1
                $end += 1;
            }
            if($end >= $paginator->lastPage() ) $end = $paginator->lastPage(); // reset end to last page
            ?>

            @if($start > 1)
                <li class="flex-item">
                    <a class="flex-link" href="{{ $paginator->url(1) }}">
                        1
                    </a>
                </li>
                @if($paginator->currentPage() != 4)
                    {{-- "Three Dots" Separator --}}
                    <li class="flex-item">
                        <a class="flex-link disabled-link" href="javascript:void(0);">
                            ...
                        </a>
                    </li>
                @endif
            @endif
            @for ($i = $start; $i <= $end; $i++)
                <li class="flex-item {{ ($paginator->currentPage() == $i) ? ' disabled-link' : '' }}">
                    <a class="flex-link" href="{{ $paginator->url($i) }}">
                        {{ $i }}
                    </a>
                </li>
            @endfor
            @if($end < $paginator->lastPage())
                @if($paginator->currentPage() + 3 != $paginator->lastPage())
                    <li class="flex-item">
                        <a class="flex-link disabled-link" href="javascript:void(0);">
                            ...
                        </a>
                    </li>
                @endif
                <li class="flex-item">
                    <a class="flex-link" href="{{ $paginator->url($paginator->lastPage()) }}">
                        {{$paginator->lastPage()}}
                    </a>
                </li>
            @endif

            <li class="flex-item pn @if(!$paginator->hasMorePages()) disabled-link @endif">
                <a class="flex-link" href="{{ $paginator->nextPageUrl() }}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
    </div>
@endif

<style>

</style>