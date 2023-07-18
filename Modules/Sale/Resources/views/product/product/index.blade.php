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
            <div class="table-toolbar d-flex flex-wrap gap-2 flex-column flex-xl-row justify-content-center justify-content-xxl-between align-content-center pb-3">
                <div class="align-self-center">
                    <div class="d-flex flex-wrap gap-2 flex-column flex-lg-row justify-content-center align-content-center">
                        @include('backend.partials.table.show',['path'=> 'sale/product/product/list' ])
                        <div class="align-self-center d-flex gap-2">
                            <div class="align-self-center d-flex gap-2">
                                @if (hasPermission('sales_products_create'))
                                <div class="align-self-center">
                                    <a href="{{ route('saleProduct.create') }}" role="button" class="btn-add" data-bs-placement="right" data-bs-title="Add">
                                        <span><i class="fa-solid fa-plus"></i> </span>
                                        <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                    </a>
                                </div>
                                @endif
                                @if (hasPermission('sales_products_import'))
                                <div class="align-self-center">
                                    <a href="#" role="button" class="btn-add" data-bs-placement="right" data-bs-title="Add" data-bs-toggle="modal" data-bs-target="#importProduct">
                                        <span><i class="fa-solid fa-plus"></i> </span>
                                        <span class="d-none d-xl-inline">{{ _trans('file.Import Product') }}</span>
                                    </a>
                                </div>
                                @endif
                            </div>
                            @include('backend.partials.table.search',['path'=> 'sale/product/product/list' ])
                        </div>
                    </div>
                </div>
           {{-- @include('backend.partials.table.export',['path'=> 'sale/product/product/export' ]) --}}

            </div>


        </div>
        <div class="table-responsive">
            <table class="table table-bordered user-table">
                <thead class="thead">
                    <tr>
                        <th class="not-exported">SL</th>
                        <th>{{ _trans('file.Image') }}</th>
                        <th>{{ _trans('file.name') }}</th>
                        <th>{{ _trans('file.Code') }}</th>
                        <th>{{ _trans('file.Brand') }}</th>
                        <th>{{ _trans('file.category') }}</th>
                        <th>{{ _trans('file.Quantity') }}</th>
                        <th>{{ _trans('file.Unit') }}</th>
                        <th>{{ _trans('file.Price') }}</th>
                        <th>{{ _trans('file.Cost') }}</th>
                        <th>{{ _trans('file.Stock Worth (Price/Cost)') }}</th>
                        <th>{{ _trans('file.Status') }}</th>
                        <th class="not-exported">{{ _trans('file.action') }}</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    @foreach ($data['products'] as $key => $product)
                    <tr data-id="{{ $product->id }}">
                        <td>{{ $key + 1 }}</td>

                        @if ($product->image)
                        <td> <img class="staff-profile-image-small" src="{{ asset('public/images/product/' . $product->image) }}" alt="#" height="80" width="80"> </td>
                        @else
                        <td class=""><img class="staff-profile-image-small" src="{{ url('public/static/blank_small.png') }}" alt="#"></td>
                        @endif
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->code }}</td>
                        <td>
                            @if ($product->brand_id != null)
                            {{ $product->brand->title }}
                            @else
                            {{ 'N/A' }}
                            @endif
                        </td>
                        <td>
                            @if ($product->category_id != null)
                            {{ $product->category->name }}
                            @else
                            {{ 'N/A' }}
                            @endif
                        </td>
                        <td>{{ $product->qty }}</td>

                        <td>
                            @if ($product->unit_id != null)
                            {{ $product->unit->unit_name }}
                            @else
                            {{ 'N/A' }}
                            @endif
                        </td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->cost }}</td>
                        <td>{{ $product->price * $product->qty }} / {{ $product->cost * $product->qty }}
                        </td>
                        <td>
                            @if ($product->is_active == 1)
                            <span class="badge-success">Active</span>
                            @else
                            <span class="badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown dropdown-action">
                                <button type="button" class="btn-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if (hasPermission('sales_products_view'))

                                    <li>
                                        <a href="javascript:void(0);" data-id="{{ $product->id }}" onclick="productDetails({{ $product->id }})" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#product-details"><span class="icon mr-8"><i class="fa-solid fa-eye"></i></span>
                                            <span>{{ _trans('common.View') }}</span></a>
                                    </li>
                                    @endif

                                    @if (hasPermission('sales_products_history'))

                                    <li>
                                        <a href="{{ route('saleProduct.history', $product->id) }}" class="dropdown-item"><span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>
                                            <span>{{ _trans('common.History') }}</span></a>
                                    </li>
                                    @endif

                                    @if (hasPermission('sales_products_edit'))
                                    <li>
                                        <a href="{{ route('saleProduct.edit', $product->id) }}" class="dropdown-item"><span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>
                                            <span>{{ _trans('common.Edit') }}</span></a>
                                    </li>
                                    @endif
                                    @if (hasPermission('sales_products_delete'))
                                    <li>

                                        <a class="dropdown-item" href="javascript:void(0);" onclick="delete_row('sale/product/product/delete', {{ $product->id }})">
                                            <span class="icon mr-12"><i class="fa-solid fa-trash-can"></i></span>
                                            <span>{{ _trans('common.delete') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <!--  pagination start -->
        <div class="ot-pagination pagination-content d-flex justify-content-end align-content-center py-3">
            {{ $data['products']->appends(request()->input())->links('pagination::bootstrap-4') }}
        </div>
        <!--  pagination end -->
    </div>
</div>

<div id="importProduct" tabindex="-1" role="dialog" aria-labelledby="importProduct" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleProduct.import', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{ _trans('common.Import Product') }}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <p class="italic">
                    <small>{{ trans('file.The field labels marked with * are required input
                        fields') }}.</small>
                </p>
                <p>{{ trans('file.The correct column order is') }} (image, name*, code*, type*, brand, category*,
                    unit_code*, cost*, price*, product_details, variant_name, item_code, additional_price)
                    {{ trans('file.and you must follow this') }}.
                </p>
                <p>{{ trans('file.To display Image it must be stored in') }} public/images/product
                    {{ trans('file.directory') }}. {{ trans('file.Image name must be same as product name') }}
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('file.Upload CSV File') }} <span class="text-danger">*</span></label>
                            {{ Form::file('file', ['class' => 'form-control', 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ trans('file.Sample File') }}</label>
                            <a href="{{ url('Modules/Sale/public/sample_file/sample_products.csv') }}" class="crm_line_btn"><i class="dripicons-download"></i>
                                {{ trans('file.Download') }}</a>
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
<div id="product-details" tabindex="-1" role="dialog" aria-labelledby="product-details" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title mr-10">{{ trans('Product Details') }}</h5>
                <button id="print-btn" type="button" class="btn btn-default btn-sm ml-3"><i class="dripicons-print"></i>
                    {{ trans('file.Print') }}</button>

                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="product-details-img text-center">
                            <img id="image" src="#" alt="#" height="300" width="300">
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
@include('sale::layouts.sale-product-script')
@include('sale::layouts.delete-ajax')
@endsection