<div class="align-self-center">
    <div class="search-box d-flex">
        <input class="form-control" placeholder="{{ _trans('sale.Search') }}"
            value="{{ $data['search'] ? $data['search']:'' }}" name="search" id="searchInput" onkeypress="updateCurrentTableWithNumber('<?= @$path?>')" >
        <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
    </div>
</div>