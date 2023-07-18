
<div class="align-self-center">
    <div class="d-flex justify-content-center justify-content-xl-end align-content-center">
        <div class="dropdown dropdown-export " data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-title="Export">
            <button type="button" class="btn-export" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                <span class="d-none d-xl-inline">{{ _trans('sale.Export') }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end"> 
                <li>
                    <a class="dropdown-item" href="javascript:void(0)" onclick="exportCSV('<?= @$path?>')" >
                        <span class="icon mr-14"><i class="fa-solid fa-file-csv"></i></span>{{  _trans('sale.CSV File') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>