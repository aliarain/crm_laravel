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

                        @include('backend.partials.table.show',['path'=> 'sale/quotation/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_quotation_create'))
                            <div class="align-self-center">
                                <a href="{{ route('saleQuotation.create') }}" role="button" class="btn-add"
                                    data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>

                        @include('backend.partials.table.search',['path'=> 'sale/quotation/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/quotation/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Date')}}</th>
                            <th>{{_trans('file.reference')}}</th>
                            <th>{{_trans('file.Warehouse')}}</th>
                            <th>{{_trans('file.Biller')}}</th>
                            <th>{{_trans('file.customer')}}</th>
                            <th>{{_trans('file.Supplier')}}</th>
                            <th>{{_trans('file.Quotation Status')}}</th>
                            <th>{{_trans('file.grand total')}}</th>
                            <th class="not-exported">{{_trans('file.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['quotations'] as $key=>$quotation)
                        <tr data-id="{{$quotation->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ date("Y-m-d", strtotime($quotation->created_at->toDateString())) }}</td>
                            <td>{{ $quotation->reference_no }}</td>
                            <td>{{ $quotation->warehouse->name }}</td>
                            <td>{{ $quotation->biller->name }}</td>
                            <td>{{ $quotation->customer->name }}</td>

                            <td>
                                @if ($quotation->supplier_id != null)
                                {{ $quotation->supplier->name }}
                                @else
                                {{ "N/A" }}
                                @endif
                            </td>
                            <td>
                                @if( $quotation->status ==1)
                                <span class="badge-success">Sent</span>
                                @else
                                <span class="badge-danger">Pending</span>
                                @endif
                            </td>
                            <td>{{ number_format($quotation->grand_total, 2) }}</td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_quotation_edit'))
                                        <li>
                                            <a href="{{ route('saleQuotation.edit', $quotation->id) }}"
                                                class="dropdown-item"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_quotation_sale_create'))

                                        <li>
                                            <a href="{{ route('saleQuotation.create_sale', $quotation->id) }}"
                                                class="dropdown-item"><span class="icon mr-8"><i
                                                        class="fa fa-shopping-cart"></i></span>
                                                <span>{{ _trans('common.Create Sale') }}</span></a>
                                        </li>
                                        @endif

                                        @if (hasPermission('sales_quotation_purchase_create'))

                                        <li>
                                            <a href="{{ route('saleQuotation.create_purchase', $quotation->id) }}"
                                                class="dropdown-item"><span class="icon mr-8"><i
                                                        class="fa fa-shopping-basket"></i></span>
                                                <span>{{ _trans('common.Create Purchase') }}</span></a>
                                        </li>
                                        @endif

                                        @if (hasPermission('sales_quotation_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/quotation/delete', {{ $quotation->id }})">
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
                        {{$data['quotations']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>

<div id="quotation-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="container mt-3 pb-2 border-bottom">
                <div class="row">
                    <div class="col-md-6 d-print-none">
                        <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close"
                            class="close d-print-none"><span aria-hidden="true"><i
                                    class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="col-md-12">
                        <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">{{
                            @base_settings('company_name') }}</h3>
                    </div>
                    <div class="col-md-12 text-center">
                        <i style="font-size: 15px;">{{_trans('file.Quotation Details')}}</i>
                    </div>
                </div>
            </div>
            <div id="quotation-content" class="modal-body">
            </div>
            <br>
            <table class="table table-bordered product-quotation-list">
                <thead class="thead">
                    <tr>
                        <th>#</th>
                        <th>{{_trans('file.product')}}</th>
                        <th>{{_trans('file.Batch No')}}</th>
                        <th>{{_trans('file.QTY')}}</th>
                        <th>{{_trans('file.Unit Price')}}</th>
                        <th>{{_trans('file.Tax')}}</th>
                        <th>{{_trans('file.Discount')}}</th>
                        <th>{{_trans('file.Subtotal')}}</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                </tbody>
            </table>
            <div id="quotation-footer" class="modal-body"></div>
        </div>
    </div>
</div>
@endsection
@section('script')
@include('sale::layouts.sale-quotation-script')
@include('sale::layouts.delete-ajax')
@endsection