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

                        @include('backend.partials.table.show',['path'=> 'sale/purchase/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_purchase_create'))
                            <div class="align-self-center">
                                <a href="{{ route('salePurchase.create') }}" role="button" class="btn-add"
                                    data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>

                        @include('backend.partials.table.search',['path'=> 'sale/purchase/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/purchase/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Date')}}</th>
                            <th>{{_trans('file.Reference')}}</th>
                            <th>{{_trans('file.Supplier')}}</th>
                            <th>{{_trans('file.Purchase Status')}}</th>
                            <th>{{_trans('file.Grand Total')}}</th>
                            <th>{{_trans('file.Returned Amount')}}</th>
                            <th>{{_trans('file.Paid')}}</th>
                            <th>{{_trans('file.Due')}}</th>
                            <th>{{_trans('file.Payment Status')}}</th>
                            <th class="not-exported">{{_trans('file.Action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['purchases'] as $key=>$purchase)
                        <tr data-id="{{$purchase->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ date("Y-m-d", strtotime(@$purchase->created_at->toDateString())) }}</td>
                            <td>{{ $purchase->reference_no }}</td>
                            <td>
                                @if ($purchase->supplier_id != null)
                                {{ $purchase->supplier->name }}
                                @else
                                {{ "N/A" }}
                                @endif
                            </td>
                            <td>
                                @if( $purchase->status ==1)
                                <span class="badge-success">{{_trans('common.Recieved')}}</span>
                                @elseif( $purchase->status ==2)
                                <span class="badge-success">{{_trans('common.Partial')}}</span>
                                @elseif( $purchase->status ==3)
                                <span class="badge-pending">{{_trans('common.Pending')}}</span>
                                @else
                                <span class="badge-danger">{{_trans('common.Ordered')}}</span>
                                @endif
                            </td>

                            <td>{{ number_format($purchase->grand_total, 2) }}</td>
                            @php
                            $returned_amount = DB::table('sale_return_purchases')->where('purchase_id',
                            $purchase->id)->sum('grand_total');
                            $due = $purchase->grand_total - $returned_amount - $purchase->paid_amount;
                            @endphp
                            <td>{{ number_format($returned_amount, 2) }}</td>
                            <td>{{ number_format($purchase->paid_amount, 2) }}</td>
                            <td>{{ number_format($due, 2) }}</td>
                            <td>
                                @if($purchase->payment_status ==1)
                                <span class="badge-danger">{{_trans('common.Due')}}</span>
                                @else
                                <span class="badge-success">{{_trans('common.Paid')}}</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">

                                        @if (hasPermission('sales_purchase_edit'))
                                        <li>
                                            <a href="{{ route('salePurchase.edit', $purchase->id) }}"
                                                class="dropdown-item"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_purchase_view_payment'))
                                        <li>
                                            <a href="javascript:void(0);" onclick="getPaymentDetails({{$purchase->id}})"
                                                class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#view-payment"><span class="icon mr-8"><i
                                                        class="fa-solid fa-eye"></i></span>
                                                <span>{{ _trans('common.View Payment') }}</span></a>
                                        </li>
                                        @endif

                                        @if (hasPermission('sales_purchase_add_payment'))
                                        <li>
                                            <a href="javascript:void(0);"
                                                onclick="addPayment({{$purchase->id}},{{$due}})" class="dropdown-item"
                                                data-bs-toggle="modal" data-bs-target="#add-payment"><span
                                                    class="icon mr-8"><i class="fa-solid fa-add"></i></span>
                                                <span>{{ _trans('common.Add Payment') }}</span></a>
                                        </li>
                                        @endif

                                        @if (hasPermission('sales_purchase_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/purchase/delete', {{ $purchase->id }})">
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
                        {{$data['purchases']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>
<div id="purchase-details" tabindex="-1" role="dialog" aria-labelledby="purchase-details" aria-hidden="true"
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
                        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                                aria-hidden="true"><i class="las la-times"></i></span></button>
                    </div>
                    <div class="col-md-12">
                        <h3 class="modal-title text-center container-fluid">
                            {{@$general_setting->site_title}}</h3>
                    </div>
                    <div class="col-md-12 text-center">
                        <i style="font-size: 15px;">{{_trans('file.Purchase Details')}}</i>
                    </div>
                </div>
            </div>
            <div id="purchase-content" class="modal-body"></div>
            <br>
            <table class="table table-bordered product-purchase-list">
                <thead class="thead">
                   <tr>
                        <th>#</th>
                        <th>{{_trans('file.product')}}</th>
                        <th>{{_trans('file.Batch No')}}</th>
                        <th>Qty</th>
                        <th>{{_trans('file.Unit Cost')}}</th>
                        <th>{{_trans('file.Tax')}}</th>
                        <th>{{_trans('file.Discount')}}</th>
                        <th>{{_trans('file.Subtotal')}}</th>
                   </tr>
                </thead>
                <tbody class="tbody">
                </tbody>
            </table>
            <div id="purchase-footer" class="modal-body"></div>
        </div>
    </div>
</div>

<div id="view-payment" tabindex="-1" role="dialog" aria-labelledby="view-payment" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.All Payment')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover payment-list">
                    <thead class="thead">
                        <tr>
                            <th>{{_trans('file.date')}}</th>
                            <th>{{_trans('file.Reference No')}}</th>
                            <th>{{_trans('file.Account')}}</th>
                            <th>{{_trans('file.Amount')}}</th>
                            <th>{{_trans('file.Paid By')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="add-payment" tabindex="-1" role="dialog" aria-labelledby="add-payment" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Add Payment')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'salePurchase.add-payment', 'method' => 'post', 'class' => 'payment-form' ])
                !!}
                <div class="row">
                    <input type="hidden" name="balance">
                    <div class="col-md-6">
                        <label>{{_trans('file.Recieved Amount')}} <span class="text-danger">*</span></label>
                        <input type="text" name="paying_amount" class="form-control ot-form-control ot-input numkey"
                            step="any" required>
                    </div>
                    <div class="col-md-6">
                        <label>{{_trans('file.Paying Amount')}} <span class="text-danger">*</span></label>
                        <input type="text" id="amount" name="amount" class="form-control ot-form-control ot-input"
                            step="any" required>
                    </div>
                    <div class="col-md-6 mt-1">
                        <label>{{_trans('file.Change')}} : </label>
                        <p class="change ml-2">0.00</p>
                    </div>
                    <div class="col-md-6 mt-1">
                        <label>{{_trans('file.Paid By')}}</label>
                        <select name="paid_by_id" class="form-select select2-input ot-input mb-3 modal_select2">
                            <option value="1">{{_trans('common.Cash')}}</option>
                            <option value="2">{{_trans('common.Cheque')}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <div class="card-element form-control ot-form-control ot-input">
                    </div>
                    <div class="card-errors" role="alert"></div>
                </div>
                <div id="cheque">
                    <div class="form-group">
                        <label>{{_trans('file.Cheque Number')}} <span class="text-danger">*</span></label>
                        <input type="text" name="cheque_no" class="form-control ot-form-control ot-input">
                    </div>
                </div>
                <div class="form-group">
                    <label> {{_trans('file.Account')}}</label>
                    <select class="form-select select2-input ot-input mb-3 modal_select2" name="account_id">
                        @foreach($data['accounts'] as $account)
                        @if($account->is_default)
                        <option selected value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]</option>
                        @else
                        <option value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{_trans('file.Payment Note')}}</label>
                    <textarea rows="3" class="form-control ot-form-control ot-input" name="payment_note"></textarea>
                </div>

                <input type="hidden" name="purchase_id">

                <button type="submit" class="crm_theme_btn">{{_trans('file.submit')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<div id="edit-payment" tabindex="-1" role="dialog" aria-labelledby="edit-payment" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Update Payment')}}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i
                            class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'salePurchase.update-payment', 'method' => 'post', 'class' => 'payment-form'
                ]) !!}
                <div class="row">
                    <div class="col-md-6">
                        <label>{{_trans('file.Recieved Amount')}} <span class="text-danger">*</span></label>
                        <input type="text" name="edit_paying_amount" class="form-control numkey" step="any" required>
                    </div>
                    <div class="col-md-6">
                        <label>{{_trans('file.Paying Amount')}} <span class="text-danger">*</span></label>
                        <input type="text" name="edit_amount" class="form-control ot-form-control ot_input" step="any"
                            required>
                    </div>
                    <div class="col-md-6 mt-1">
                        <label>{{_trans('file.Change')}} : </label>
                        <p class="change ml-2">0.00</p>
                    </div>
                    <div class="col-md-6 mt-1">
                        <label>{{_trans('file.Paid By')}}</label>
                        <select name="edit_paid_by_id" class="form-control selectpicker">
                            <option value="1">{{_trans('file.Cash')}}</option>
                            <option value="3">{{_trans('file.Credit Card')}}</option>
                            <option value="4">{{_trans('file.Cheque')}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <div  class="card-element form-control">
                    </div>
                    <div class="card-errors" role="alert"></div>
                </div>
                <div id="edit-cheque">
                    <div class="form-group">
                        <label>{{_trans('file.Cheque Number')}} <span class="text-danger">*</span></label>
                        <input type="text" name="edit_cheque_no" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label> {{_trans('file.Account')}}</label>
                    <select class="form-control selectpicker" name="account_id">
                        @foreach($data['accounts'] as $account)
                        <option value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{_trans('file.Payment Note')}}</label>
                    <textarea rows="3" class="form-control ot-form-control ot_input"
                        name="edit_payment_note"></textarea>
                </div>

                <input type="hidden" name="payment_id">

                <button type="submit" class="crm_theme_btn">{{_trans('file.update')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@include('sale::layouts.sale-purchase-script')
@include('sale::layouts.delete-ajax')
@endsection