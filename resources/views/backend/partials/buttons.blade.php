<div class="align-self-center">
    <div class="d-flex justify-content-center justify-content-xl-end align-content-center">

        <div class="dropdown dropdown-export" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-title="{{ _trans('common.Export') }}">
            <button type="button" class="btn-export" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>

                <span class="d-none d-xl-inline">{{ _trans('common.Export') }}</span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#" onclick="selectElementContents(document.getElementById('table'))">
                        <span class="icon mr-8"><i class="fa-solid fa-copy"></i>
                        </span>
                        {{ _trans('common.Copy') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item exportJSON" href="#">
                        <span class="icon mr-8">
                            <i class="fa-solid fa-code"></i>
                        </span>
                        {{ _trans('common.Json File') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item btnExcelExport" href="#" aria-current="true"><span class="icon mr-10"><i
                                class="fa-solid fa-file-excel"></i></span>
                        {{ _trans('common.Excel File') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item exportCSV" href="#">
                        <span class="icon mr-14"><i class="fa-solid fa-file-csv"></i></span>
                        {{ _trans('common.Csv File') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item exportPDF" href="#">
                        <span class="icon mr-14"><i class="fa-solid fa-file-pdf"></i></span>
                        {{ _trans('common.Pdf File') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>