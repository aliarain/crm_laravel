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

                        @include('backend.partials.table.show',['path'=> 'sale/transfer/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_transfer_create'))
                            <div class="align-self-center">
                                <a href="{{ route('saleTransfer.create') }}" class="btn-add" data-bs-placement="right"
                                    data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>

                        @include('backend.partials.table.search',['path'=> 'sale/transfer/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/transfer/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Date')}}</th>
                            <th>{{_trans('file.reference')}} No</th>
                            <th>{{_trans('file.Warehouse')}}({{_trans('file.From')}})</th>
                            <th>{{_trans('file.Warehouse')}}({{_trans('file.To')}})</th>
                            <th>{{_trans('file.Product')}} {{_trans('file.Cost')}}</th>
                            <th>{{_trans('file.Product')}} {{_trans('file.Tax')}}</th>
                            <th>{{_trans('file.Grand Total')}}</th>
                            <th>{{_trans("file.Status")}}</th>
                            <th class="not-exported">{{_trans('file.Action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['transfers'] as $key=>$transfer)
                        <tr data-id="{{$transfer->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ date("Y-m-d", strtotime(@$transfer->created_at->toDateString())) }}</td>
                            <td>{{ @$transfer->reference_no }}</td>
                            <td>{{ @$transfer->fromWarehouse->name }}</td>
                            <td>{{ @$transfer->toWarehouse->name }}</td>
                            <td>{{ number_format(@$transfer->total_cost, 2) }}</td>
                            <td>{{ number_format(@$transfer->total_tax, 2) }}</td>
                            <td>{{ number_format(@$transfer->grand_total, 2) }}</td>
                            <td>
                                @if ($transfer->status == 1)
                                <div class="badge badge-success">{{_trans('file.Completed')}}</div>
                                @elseif($transfer->status == 2)
                                <div class="badge badge-danger">{{_trans('file.Pending')}}</div>
                                @elseif($transfer->status == 3)
                                <div class="badge badge-warning">{{_trans('file.Sent')}}</div>
                                @endif

                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_transfer_view'))

                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$transfer->id}}"
                                                class="view dropdown-item"><span class="icon mr-8"><i
                                                        class="fa fa-eye"></i></span>
                                                <span>{{ _trans('common.View') }}</span></a>
                                        </li>
                                        @endif

                                        @if (hasPermission('sales_transfer_edit'))
                                        <li>
                                            <a href="{{ route('saleTransfer.edit', $transfer->id) }}"
                                                class="dropdown-item"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_transfer_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/transfer/delete', {{ $transfer->id }})">
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
                        {{$data['transfers']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>
<div id="transfer-details" tabindex="-1" role="dialog" aria-labelledby="transfer-details" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="container mt-3 pb-2 border-bottom">
                <div class="row">
                    <div class="col-md-6 d-print-none">
                        <button id="print-btn" type="button" class="btn btn-default btn-sm"><i
                                class="dripicons-print"></i> {{_trans('file.Print')}}</button>
                    </div>
                    <div class="col-md-6 d-print-none">
                        <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="col-md-12">
                        <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">
                            {{@$general_setting->site_title}}</h3>
                    </div>
                    <div class="col-md-12 text-center">
                        <i style="font-size: 15px;">{{_trans('file.Transfer Details')}}</i>
                    </div>
                </div>
            </div>
            <div id="transfer-content" class="modal-body">
                <strong>{{_trans("file.Date")}}: </strong>
                <p id="transfer_date"></p>
                <strong>{{_trans("file.reference")}}: </strong>
                <p id="reference"></p>
                <div class="row">
                    <div class="col-md-6">
                        <strong>{{_trans("file.From")}}:</strong><br>
                        <p id="from"></p>
                        <p id="from_phone"></p>
                        <p id="from_address"></p>
                    </div>
                    <div class="col-md-6">
                        <div class="float-right">
                            <strong>{{_trans("file.To")}}:</strong><br>
                            <p id="to"></p>
                            <p id="to_phone"></p>
                            <p id="to_address"></p>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <table class="table table-bordered product-transfer-list">
                <thead class="thead">
                    <tr>
                        <th>#</th>
                        <th>{{_trans('file.product')}}</th>
                        <th>{{_trans('file.Batch No')}}</th>
                        <th>Qty</th>
                        <th>{{_trans('file.Unit Cost')}}</th>
                        <th>{{_trans('file.Tax')}}</th>
                        <th>{{_trans('file.Subtotal')}}</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                </tbody>
            </table>
            <div id="transfer-footer" class="modal-body">
                <strong>{{_trans("file.Note")}}:</strong>
                <p id="note"></p><strong>{{_trans("file.Created By")}}:</strong><br>
                <p id="created_by"></p><br>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')

@include('sale::layouts.sale-transfer-script')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@include('sale::layouts.delete-ajax')
@endsection