@if ($paginator->hasPages())
    <div class="ot-pagination d-flex justify-content-end align-content-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination">

                @if ($paginator->onFirstPage())
                    <li class="disabled page-item">
                        <a class="page-link" aria-label="Previous" href="javascript:;">
                            <span aria-hidden="true"><i class="fa fa-angle-left"></i></span>
                        </a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" aria-label="Previous" href="javascript:;"
                            onclick="pagination({{ $paginator->currentPage() - 1 }})">
                            <span aria-hidden="true"><i class="fa fa-angle-left"></i></span>
                        </a>
                    </li>
                @endif



                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="disabled page-item">
                            <a class="page-link" href="javascript:;">
                                <span aria-hidden="true">{{ $element }}</span>
                            </a>
                        </li>
                    @endif



                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="active page-item">
                                    <a class="page-link active" href="javascript:;">
                                        <span aria-hidden="true">{{ $page }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="javascript:;" onclick="pagination({{ $page }})">
                                        <span aria-hidden="true">{{ $page }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach



                @if ($paginator->hasMorePages())
                    <li class="page-item"><a class="page-link" href="javascript:;"
                            onclick="pagination({{ $paginator->currentPage() + 1 }})" rel="next"><span
                                aria-hidden="true"><i class="fa fa-angle-right"></i></span></a></li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link" href="javascript:;" aria-label="Next">
                            <span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
