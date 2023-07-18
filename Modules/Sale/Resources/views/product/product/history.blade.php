@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([
'title' => @$data['title'],
route('admin.dashboard') => _trans('common.Dashboard'),
'#' => @$data['title'],
]) !!}
<div class="table-content table-basic">

    <div class="card">
        <div class="card-body">
            <div class="product-history-tab">
                <nav class="mb-20">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="product-sale-tab" data-bs-toggle="tab"
                            data-bs-target="#product-sale" type="button" role="tab" aria-controls="product-sale"
                            aria-selected="true">{{ _trans('file.Sale') }}</button>
                        <button class="nav-link" id="product-purchase-tab" data-bs-toggle="tab"
                            data-bs-target="#product-purchase" type="button" role="tab" aria-controls="product-purchase"
                            aria-selected="false">{{ _trans('file.Purchase')
                            }}</button>
                        <button class="nav-link" id="product-sale-return-tab" data-bs-toggle="tab"
                            data-bs-target="#product-sale-return" type="button" role="tab"
                            aria-controls="product-sale-return" aria-selected="false">{{ _trans('file.Sale Return')
                            }}</button>
                        <button class="nav-link" id="product-purchase-return-tab" data-bs-toggle="tab"
                            data-bs-target="#product-purchase-return" type="button" role="tab"
                            aria-controls="product-purchase-return" aria-selected="false">{{ _trans('file.Purchase
                            Return') }}</button>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">
                    <!-- sale table -->
                    <div class="tab-pane fade show active" id="product-sale" role="tabpanel"
                        aria-labelledby="product-sale-tab" tabindex="0">
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="thead">
                                    <tr>
                                        <th class="not-exported-sale">SL</th>
                                        <th>{{ _trans('file.Date') }}</th>
                                        <th>{{ _trans('file.reference') }}</th>
                                        <th>{{ _trans('file.Warehouse') }}</th>
                                        <th>{{ _trans('file.customer') }}</th>
                                        <th>{{ _trans('file.qty') }}</th>
                                        <th>{{ _trans('file.Unit Price') }}</th>
                                        <th>{{ _trans('file.Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                    @foreach($data['sales'] as $key=>$sale)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{@$sale['date']}}</td>
                                        <td>{{@$sale['reference_no']}}</td>
                                        <td>{{@$sale['warehouse']}}</td>
                                        <td>{{@$sale['customer']}}</td>
                                        <td>{{@$sale['qty']}}</td>
                                        <td>{{number_format((float)@$sale['unit_price'], 2, '.', '')}}</td>
                                        <td>{{number_format((float)@$sale['sub_total'], 2, '.', '')}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- purchase table -->
                    <div class="tab-pane fade" id="product-purchase" role="tabpanel"
                        aria-labelledby="product-purchase-tab" tabindex="0">
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered" style="width: 100%">
                                <thead class="thead">
                                    <tr>
                                        <th class="not-exported-purchase">SL</th>
                                        <th>{{ _trans('file.Date') }}</th>
                                        <th>{{ _trans('file.reference') }}</th>
                                        <th>{{ _trans('file.Warehouse') }}</th>
                                        <th>{{ _trans('file.Supplier') }}</th>
                                        <th>{{ _trans('file.qty') }}</th>
                                        <th>{{ _trans('file.Unit Price') }}</th>
                                        <th>{{ _trans('file.Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                    @foreach($data['purchases'] as $key=>$purchase)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{@$purchase['date']}}</td>
                                        <td>{{@$purchase['reference_no']}}</td>
                                        <td>{{@$purchase['warehouse']}}</td>
                                        <td>{{@$purchase['supplier']}}</td>
                                        <td>{{@$purchase['qty']}}</td>
                                        <td>{{number_format((float)@$purchase['unit_price'], 2, '.', '')}}</td>
                                        <td>{{number_format((float)@$purchase['sub_total'], 2, '.', '')}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- sale return table -->
                    <div class="tab-pane fade" id="product-sale-return" role="tabpanel"
                        aria-labelledby="product-sale-return-tab" tabindex="0">
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered" style="width: 100%">
                                <thead class="thead">
                                    <tr>
                                        <th class="not-exported-sale-return">SL</th>
                                        <th>{{ _trans('file.Date') }}</th>
                                        <th>{{ _trans('file.reference') }}</th>
                                        <th>{{ _trans('file.Warehouse') }}</th>
                                        <th>{{ _trans('file.customer') }}</th>
                                        <th>{{ _trans('file.qty') }}</th>
                                        <th>{{ _trans('file.Unit Price') }}</th>
                                        <th>{{ _trans('file.Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">

                                    @foreach($data['sale_returns'] as $key=>$saleReturn)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{@$saleReturn['date']}}</td>
                                        <td>{{@$saleReturn['reference_no']}}</td>
                                        <td>{{@$saleReturn['warehouse']}}</td>
                                        <td>{{@$saleReturn['customer']}}</td>
                                        <td>{{@$saleReturn['qty']}}</td>
                                        <td>{{number_format((float)@$saleReturn['unit_price'], 2, '.', '')}}</td>
                                        <td>{{number_format((float)@$saleReturn['sub_total'], 2, '.', '')}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>

                    <!-- purchase return table -->
                    <div class="tab-pane fade" id="product-purchase-return" role="tabpanel"
                        aria-labelledby="product-purchase-return-tab" tabindex="0">
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered" style="width: 100%">
                                <thead class="thead">
                                    <tr>
                                        <th class="not-exported-purchase-return"></th>
                                        <th>{{ _trans('file.Date') }}</th>
                                        <th>{{ _trans('file.reference') }}</th>
                                        <th>{{ _trans('file.Warehouse') }}</th>
                                        <th>{{ _trans('file.Supplier') }}</th>
                                        <th>{{ _trans('file.qty') }}</th>
                                        <th>{{ _trans('file.Unit Price') }}</th>
                                        <th>{{ _trans('file.Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                    @foreach($data['purchase_returns'] as $key=>$purchaseReturn)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{@$purchaseReturn['date']}}</td>
                                        <td>{{@$purchaseReturn['reference_no']}}</td>
                                        <td>{{@$purchaseReturn['warehouse']}}</td>
                                        <td>{{@$purchaseReturn['supplier']}}</td>
                                        <td>{{@$purchaseReturn['qty']}}</td>
                                        <td>{{number_format((float)@$purchaseReturn['unit_price'], 2, '.', '')}}</td>
                                        <td>{{number_format((float)@$purchaseReturn['sub_total'], 2, '.', '')}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="importProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleProduct.import', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 id="exampleModalLabel" class="modal-title">{{ _trans('common.Import Product') }}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
              
                <p>{{ _trans('file.The correct column order is') }} (image, name*, code*, type*, brand, category*,
                    unit_code*, cost*, price*, product_details, variant_name, item_code, additional_price)
                    {{ _trans('file.and you must follow this') }}.</p>
                <p>{{ _trans('file.To display Image it must be stored in') }} public/images/product
                    {{ _trans('file.directory') }}. {{ _trans('file.Image name must be same as product name') }}</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ _trans('file.Upload CSV File') }} <span class="text-danger">*</span></label>
                            {{ Form::file('file', ['class' => 'form-control', 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ _trans('file.Sample File') }}</label>
                            <a href="{{ url('Modules\Sale\public\sample_file\sample_products.csv') }}"
                                class="crm_line_btn"><i class="dripicons-download"></i>
                                {{ _trans('file.Download') }}</a>
                        </div>
                    </div>
                </div>
                {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

{{-- product details modal --}}
<div id="product-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 id="exampleModalLabel" class="modal-title mr-10">{{ _trans('Product Details') }}</h5>
                <button id="print-btn" type="button" class="btn btn-default btn-sm ml-3"><i class="dripicons-print"></i>
                    {{ _trans('file.Print') }}</button>

                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="product-details-img text-center">
                            <img id="image" src="" alt="" height="300" width="300">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="product-details-info">
                            <ul class="list-unstyled">
                                <li><b>{{ _trans('common.Type :') }}</b><span id="type"></span></li>
                                <li><b>{{ _trans('common.Name :') }}</b><span id="name"></span></li>
                                <li><b>{{ _trans('common.Code :') }}</b><span id="code"></span></li>
                                <li><b>{{ _trans('common.Brand :') }}</b><span id="brand"></span></li>
                                <li><b>{{ _trans('common.Category :') }}</b><span id="category"></span></li>
                                <li><b>{{ _trans('common.Quantity :') }}</b><span id="quantity"></span></li>
                                <li><b>{{ _trans('common.Unit :') }}</b><span id="unit"></span></li>
                                <li><b>{{ _trans('common.Cost :') }}</b><span id="cost"></span></li>
                                <li><b>{{ _trans('common.Price :') }}</b><span id="price"></span></li>
                                <li><b>{{ _trans('common.Tax :') }}</b><span id="tax"></span></li>
                                <li><b>{{ _trans('common.Tax Method :') }}</b><span id="tax_method"></span></li>
                                <li><b>{{ _trans('common.Alert Quantity :') }}</b><span id="alert_quantity"></span></li>
                                <li><b>{{ _trans('common.Product Details :') }}</b><span id="product_details"></span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-warehouse-quantity mb-30">
                            <div class="table-responsive">
                                <h3>{{ _trans('common.Warehouse Quantity') }}</h3>
                                <table class="ot-basic-table ot-table-bg" id="warehouseTable">
                                    <thead class="thead">
                                        <tr>
                                            <th>{{ _trans('common.Warehouse') }}</th>
                                            <th>{{ _trans('common.Batch No') }}</th>
                                            <th>{{ _trans('common.Quantity') }}</th>
                                            <th>{{ _trans('common.IMEI or Serial Number') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="warehouse_dev">
                    <div class="col-lg-6">
                        <div class="product-variant-info mb-30">
                            <div class="table-responsive">
                                <h4>{{ _trans('common.Product Variant Information') }}</h4>
                                <table class="ot-basic-table ot-table-bg" id="variantTable">
                                    <thead class="thead">
                                        <tr>
                                            <th>{{ _trans('common.Variant') }}</th>
                                            <th>{{ _trans('common.Item code') }}</th>
                                            <th>{{ _trans('common.Additional Cose') }}</th>
                                            <th>{{ _trans('common.Additional Price') }}</th>
                                            <th>{{ _trans('common.QTY') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="product-quantity-variants mb-30">
                            <div class="table-responsive">
                                <h4>{{ _trans('common.Warehouse quantity of product variants') }}</h4>
                                <table class="ot-basic-table ot-table-bg" id="warehouseVariantTable">
                                    <thead class="thead">
                                        <tr>
                                            <th>{{ _trans('common.Warehouse') }}</th>
                                            <th>{{ _trans('common.Variant') }}</th>
                                            <th>{{ _trans('common.Quantity') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
@section('script')

<script>
    $("ul#product").siblings('a').attr('aria-expanded', 'true');
        $("ul#product").addClass("show");
        $("ul#product #product-list-menu").addClass("active");

        function confirmDelete() {
            if (confirm("Are you sure want to delete?")) {
                return true;
            }
            return false;
        }

        var warehouse = [];
        var variant = [];
        var qty = [];
        var htmltext;
        var slidertext;
        var product_id = [];
        var role_id = '';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#select_all").on("change", function() {
            if ($(this).is(':checked')) {
                $("tbody input[type='checkbox']").prop('checked', true);
            } else {
                $("tbody input[type='checkbox']").prop('checked', false);
            }
        });

        function productDetails(product_id) {


            $.ajax({
                url: 'details-data/' + product_id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#product-details').modal('show');
                    $('#image').attr('src', data['product'].image);
                    $('#type').text(data['product'].type);
                    $('#name').text(data['product'].name);
                    $('#code').text(data['product'].code);
                    $('#brand').text(data['product'].brand);
                    $('#category').text(data['product'].category);
                    $('#quantity').text(data['product'].qty);
                    $('#unit').text(data['product'].unit);
                    $('#cost').text(data['product'].cost);
                    $('#price').text(data['product'].price);
                    $('#tax').text(data['product'].tax);
                    $('#tax_method').text(data['product'].tax_method);
                    $('#alert_quantity').text(data['product'].alert_quantity);
                    $('#product_details').text(data['product'].product_details);

                    var warehouseTableBody = $('#warehouseTable tbody');
                    warehouseTableBody.html('');
                    var rowsHtml = '';
                    if (data['warehouses'].length > 0) {
                        $.each(data['warehouses'], function(index, item) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(item['name']));
                            newRow.append($('<td>').text(item['batch_no']));
                            newRow.append($('<td>').text(item['qty']));
                            newRow.append($('<td>').text(item['imei_number']));
                            rowsHtml += newRow.prop('outerHTML');
                        });
                    }
                    warehouseTableBody.append(rowsHtml);

                    var variantTableBody = $('#variantTable tbody');
                    variantTableBody.html('');
                    var rowsHtml = '';

                    if (data['variants'].length > 0) {
                        $.each(data['variants'], function(index, item) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(item['variant']));
                            newRow.append($('<td>').text(item['item_code']));
                            newRow.append($('<td>').text(item['additional_cost']));
                            newRow.append($('<td>').text(item['additional_price']));
                            newRow.append($('<td>').text(item['qty']));
                            rowsHtml += newRow.prop('outerHTML');
                        });
                        variantTableBody.append(rowsHtml);
                    }


                    var variantTableBody = $('#warehouseVariantTable tbody');
                    variantTableBody.html('');
                    var rowsHtml = '';

                    if (data['warehouse_variants'].length > 0) {
                        $.each(data['warehouse_variants'], function(index, item) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(item['name']));
                            newRow.append($('<td>').text(item['variant']));
                            newRow.append($('<td>').text(item['qty']));
                            rowsHtml += newRow.prop('outerHTML');
                        });
                        variantTableBody.append(rowsHtml);
                    }

                }
            });

        }

        $("#print-btn").on("click", function() {
            var divToPrint = document.getElementById('product-details');
            var newWin = window.open('', 'Print-Window');
            newWin.document.open();
            newWin.document.write(
                '<link rel="stylesheet" href="<?php echo asset('vendor/bootstrap/css/bootstrap.min.css'); ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">' +
                divToPrint.innerHTML + '</body>');
            newWin.document.close();
            setTimeout(function() {
                newWin.close();
            }, 10);
        });
</script>
@include('sale::layouts.delete-ajax')
@endsection