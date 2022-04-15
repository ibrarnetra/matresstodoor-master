@php
// config
$link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
@endphp

<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-end">
        <li class="page-item @if ($paginator->currentPage() == 1) disabled @endif">
            <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            @php
            $half_total_links = floor($link_limit / 2);
            $from = $paginator->currentPage() - $half_total_links;
            $to = $paginator->currentPage() + $half_total_links;
            if ($paginator->currentPage() < $half_total_links) { $to +=$half_total_links - $paginator->currentPage();
                }
                if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) { $from -=$half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
                    }
                    @endphp
                    @if ($from < $i && $i < $to) <li class="page-item {{ ($paginator->currentPage() == $i) ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                        </li>
                        @endif
                        @endfor

                        <li class="page-item @if ($paginator->currentPage() == $paginator->lastPage()) disabled @endif">
                            <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
    </ul>
</nav>