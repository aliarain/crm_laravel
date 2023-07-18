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
            <div
                class="table-toolbar d-flex flex-wrap gap-2 flex-column flex-xl-row justify-content-center justify-content-xxl-between align-content-center pb-3">
                <div class="align-self-center">
                    <div
                        class="d-flex flex-wrap gap-2 flex-column flex-lg-row justify-content-center align-content-center">

                        @include('backend.partials.table.show',['path'=> 'sale/return-sale/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_return_sale_create'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-toggle="modal"
                                    data-bs-target="#add-sale-return" data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>

                        @include('backend.partials.table.search',['path'=> 'sale/return-sale/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/return-sale/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Date')}}</th>
                            <th>{{_trans('file.reference')}}</th>
                            <th>{{_trans('file.Sale Reference')}}</th>
                            <th>{{_trans('file.Warehouse')}}</th>
                            <th>{{_trans('file.Biller')}}</th>
                            <th>{{_trans('file.customer')}}</th>
                            <th>{{_trans('file.grand total')}}</th>
                            <th class="not-exported">{{_trans('file.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['saleReturns'] as $key=>$return)
                        <tr data-id="{{$return->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ date("Y-m-d", strtotime(@$return->created_at->toDateString())) }}</td>
                            <td>{{ @$return->reference_no }}</td>
                            <td>{{ @$return->saleReference->reference_no }}</td>
                            <td>{{ @$return->warehouse->name }}</td>
                            <td>{{ @$return->biller->name }}</td>
                            <td>{{ @$return->customer->name }}</td>
                            <td>{{ number_format(@$return->grand_total, 2) }}</td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_return_sale_edit'))
                                        <li>
                                            <a href="{{ route('saleReturn.edit', $return->id) }}"
                                                class="dropdown-item"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_return_sale_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/return-sale/delete', {{ $return->id }})">
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
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-between">
                        {{$data['saleReturns']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>

<div id="return-details" tabindex="-1" role="dialog" aria-labelledby="return-details" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="container mt-3 pb-2 border-bottom">
                <div class="row">
                    <div class="col-md-6 d-print-none">
                        <button id="print-btn" type="button" class="btn btn-default btn-sm"><i
                                class="dripicons-print"></i> {{_trans('file.Print')}}</button>
                        {{ Form::open(['route' => 'return-sale.sendmail', 'method' => 'post', 'class' =>
                        'sendmail-form'] ) }}
                        <input type="hidden" name="return_id">
                        <button class="btn btn-default btn-sm d-print-none"><i class="dripicons-mail"></i>
                            {{_trans('file.Email')}}</button>
                        {{ Form::close() }}
                    </div>
                    <div class="col-md-6 d-print-none">
                        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                                aria-hidden="true"><i class="las la-times"></i></span></button>
                    </div>
                    <div class="col-md-12">
                        <h3 class="modal-title text-center container-fluid">
                            {{@$general_setting->site_title}}</h3>
                    </div>
                    <div class="col-md-12 text-center">
                        <i style="font-size: 15px;">{{_trans('file.Return Details')}}</i>
                    </div>
                </div>
            </div>
            <div id="return-content" class="modal-body">
            </div>
            <br>
            <table class="table table-bordered product-return-list">
                <thead class="thead">
                    <tr>
                    <th>#</th>
                    <th>{{_trans('file.product')}}</th>
                    <th>{{_trans('file.Batch No')}}</th>
                    <th>{{_trans('file.Qty')}}</th>
                    <th>{{_trans('file.Unit Price')}}</th>
                    <th>{{_trans('file.Tax')}}</th>
                    <th>{{_trans('file.Discount')}}</th>
                    <th>{{_trans('file.Subtotal')}}</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                </tbody>
            </table>
            <div id="return-footer" class="modal-body"></div>
        </div>
    </div>
</div>

<div id="add-sale-return" tabindex="-1" role="dialog" aria-labelledby="add-sale-return" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleReturn.create', 'method' => 'get']) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">Add Sale Return</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">{{_trans('file.Sale Reference')}} <span class="text-danger">*</span></label>
                            <input type="text" name="reference_no" class="form-control ot-form-control ot-input" placeholder="{{_trans('file.Sale Reference')}}"
                                required>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-20">
                {{Form::submit('Submit', ['class' => 'crm_theme_btn '])}}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('script')




@include('sale::layouts.sale-return-sale-script')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@include('sale::layouts.delete-ajax')
@endsection