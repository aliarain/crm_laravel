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
                        @include('backend.partials.table.show',['path'=> 'sale/sale/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_create'))
                            <div class="align-self-center">
                                <a href="{{ route('saleSale.create') }}" role="button" class="btn-add"
                                    data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>

                        @include('backend.partials.table.search',['path'=> 'sale/sale/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/sale/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Date')}}</th>
                            <th>{{_trans('file.reference')}}</th>
                            <th>{{_trans('file.Biller')}}</th>
                            <th>{{_trans('file.customer')}}</th>
                            <th>{{_trans('file.Sale Status')}}</th>
                            <th>{{_trans('file.Payment Status')}}</th>
                            <th>{{_trans('file.Delivery Status')}}</th>
                            <th>{{_trans('file.grand total')}}</th>
                            <th>{{_trans('file.Returned Amount')}}</th>
                            <th>{{_trans('file.Paid')}}</th>
                            <th>{{_trans('file.Due')}}</th>
                            <th class="not-exported">{{_trans('file.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['sales'] as $key=>$sale)
                        <tr data-id="{{$sale->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ date("Y-m-d", strtotime($sale->created_at->toDateString())) }}</td>
                            <td>{{ $sale->reference_no }}</td>
                            <td>{{ $sale->biller->name }}</td>
                            <td>
                                <p>{{ $sale->customer->name }}</p>
                                <p>{{ $sale->customer->phone_number }}</p>
                                <input type="hidden" class="deposit"
                                    value="{{ $sale->customer->deposit - $sale->customer->expense }}">
                                <input type="hidden" class="points" value="{{ $sale->customer->points }}">

                            </td>
                            <td>
                                @if( $sale->sale_status == 1)
                                <span class="badge-success">Completed</span>
                                @elseif( $sale->sale_status == 2)
                                <span class="badge-danger">Pending</span>
                                @else
                                <span class="badge-warning">Draft</span>
                                @endif
                            </td>

                            <td>
                                @if( $sale->payment_status == 1)
                                <span class="badge-danger">Pending</span>
                                @elseif( $sale->payment_status == 2)
                                <span class="badge-danger">Due</span>
                                @elseif( $sale->payment_status == 3)
                                <span class="badge-warning">Partial</span>
                                @else
                                <span class="badge-success">Paid</span>
                                @endif
                            </td>
                            @php
                            $delivery_data = DB::table('sale_deliveries')->select('status')->where('sale_id',
                            $sale->id)->first();
                            @endphp

                            <td>
                                @if($delivery_data)
                                @if( $delivery_data->status == 1)
                                <span class="badge-danger">Packing</span>
                                @elseif( $delivery_data->status == 2)
                                <span class="badge-danger">Delivering</span>
                                @elseif( $delivery_data->status == 3)
                                <span class="badge-warning">Delivered</span>
                                @endif
                                @else
                                N/A
                                @endif
                            </td>
                            <td>{{ number_format($sale->grand_total, 2) }}</td>
                            @php
                            $returned_amount = DB::table('sale_returns')->where('sale_id',
                            $sale->id)->sum('grand_total');
                            $due = $sale->grand_total - $returned_amount - $sale->paid_amount;
                            @endphp
                            <td>{{ number_format($returned_amount, 2) }}</td>
                            <td>{{ number_format($sale->paid_amount, 2) }}</td>
                            <td>{{ number_format($due, 2) }}</td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_invoice'))
                                        <li>
                                            <a href="{{ route('saleSale.invoice', $sale->id) }}"
                                                class="dropdown-item"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Invoice') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_edit'))
                                        <li>
                                            <a href="{{ route('saleSale.edit', $sale->id) }}"
                                                class="dropdown-item"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_view_payment'))
                                        <li>
                                            <a href="javascript:void(0);" onclick="getPaymentDetails({{$sale->id}})"
                                                class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#view-payment"><span class="icon mr-8"><i
                                                        class="fa-solid fa-eye"></i></span>
                                                <span>{{ _trans('common.View Payment') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_add_payment'))
                                        <li>
                                            <a href="javascript:void(0);" onclick="addPayment({{$sale->id}},{{$due}})"
                                                class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#add-payment"><span class="icon mr-8"><i
                                                        class="fa-solid fa-add"></i></span>
                                                <span>{{ _trans('common.Add Payment') }}</span></a>
                                        </li>
                                        @endif
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$sale->id}}"
                                                class="add-delivery dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#add-delivery"><span class="icon mr-8"><i
                                                        class="fa-solid fa-add"></i></span>
                                                <span>{{ _trans('common.Add Delivery') }}</span></a>
                                        </li>

                                        @if (hasPermission('sales_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/sale/delete', {{ $sale->id }})">
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
                        {{$data['sales']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>

<div id="sale-details" tabindex="-1" role="dialog" aria-labelledby="sale-details" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="container mt-3 pb-2 border-bottom">
                <div class="row">
                    <div class="col-md-6 d-print-none">
                        <button id="print-btn" type="button" class="btn btn-default btn-sm"><i
                                class="dripicons-print"></i> {{_trans('file.Print')}}</button>

                        {{ Form::open(['route' => 'saleSale.sendmail', 'method' => 'post', 'class' => 'sendmail-form'] )
                        }}
                        <input type="hidden" name="sale_id">
                        <button class="btn btn-default btn-sm d-print-none"><i class="dripicons-mail"></i>
                            {{_trans('file.Email')}}</button>
                        {{ Form::close() }}
                    </div>
                    <div class="col-md-6 d-print-none">
                        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                                aria-hidden="true"><i class="las la-times"></i></span></button>
                    </div>
                    <div class="col-md-12 text-center">
                        <i style="font-size: 15px;">{{_trans('file.Sale Details')}}</i>
                    </div>
                </div>
            </div>
            <div id="sale-content" class="modal-body">
            </div>
            <br>
            <table class="table table-bordered product-sale-list">
                <thead class="thead">
                    <tr>
                    <th>#</th>
                    <th>{{_trans('file.product')}}</th>
                    <th>{{_trans('file.Batch No')}}</th>
                    <th>{{_trans('file.Qty')}}</th>
                    <th>{{_trans('file.Unit')}}</th>
                    <th>{{_trans('file.Unit Price')}}</th>
                    <th>{{_trans('file.Tax')}}</th>
                    <th>{{_trans('file.Discount')}}</th>
                    <th>{{_trans('file.Subtotal')}}</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                </tbody>
            </table>
            <div id="sale-footer" class="modal-body"></div>
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
                {!! Form::open(['route' => 'saleSale.add-payment', 'method' => 'post', 'files' => true, 'class' =>
                'payment-form' ]) !!}
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
                        <select name="paid_by_id" class="form-control ot-form-control ot-input">
                            <option value="" disabled>Select One</option>
                            <option value="1">Cash</option>
                            <option value="2">Gift Card</option>
                            <option value="3">Credit Card</option>
                            <option value="4">Cheque</option>
                            <option value="5">Paypal</option>
                            <option value="6">Deposit</option>
                            @if($ot_crm_reward_point_setting_data && $ot_crm_reward_point_setting_data->is_active)
                            <option value="7">Points</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="gift-card form-group">
                    <label> {{_trans('file.Gift Card')}} <span class="text-danger">*</span></label>
                    <select  name="gift_card_id"
                        class="form-select select2-input ot-input mb-3 modal_select2" data-live-search="true"
                        data-live-search-style="begins" title="Select Gift Card...">
                        @php
                        $balance = [];
                        $expired_date = [];
                        @endphp
                        @foreach($ot_crm_gift_card_list as $gift_card)
                        <?php
                                $balance[$gift_card->id] = $gift_card->amount - $gift_card->expense;
                                $expired_date[$gift_card->id] = $gift_card->expired_date;
                            ?>
                        <option value="{{$gift_card->id}}">{{$gift_card->card_no}}</option>
                        @endforeach
                    </select>
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
                        @foreach($ot_crm_account_list as $account)
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

                <input type="hidden" name="sale_id">

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
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'saleSale.update-payment', 'method' => 'post', 'class' => 'payment-form' ])
                !!}
                <div class="row">
                    <div class="col-md-6">
                        <label>{{_trans('file.Recieved Amount')}} <span class="text-danger">*</span></label>
                        <input type="text" name="edit_paying_amount"
                            class="form-control ot-form-control ot-input numkey" step="any" required>
                    </div>
                    <div class="col-md-6">
                        <label>{{_trans('file.Paying Amount')}} <span class="text-danger">*</span></label>
                        <input type="text" name="edit_amount" class="form-control ot-form-control ot-input" step="any"
                            required>
                    </div>
                    <div class="col-md-6 mt-1">
                        <label>{{_trans('file.Change')}} : </label>
                        <p class="change ml-2">0.00</p>
                    </div>
                    <div class="col-md-6 mt-1">
                        <label>{{_trans('file.Paid By')}}</label>
                        <select name="edit_paid_by_id" class="form-select select2-input ot-input mb-3 modal_select2">
                            <option value="" disabled>Select one</option>
                            <option value="1">{{_trans('file.Cash')}}</option>
                            <option value="2">{{_trans('file.Gift Card')}}</option>
                            <option value="3">{{_trans('file.Credit Card')}}</option>
                            <option value="4">{{_trans('file.Cheque')}}</option>
                            <option value="5">{{_trans('file.Paypal')}}</option>
                            <option value="6">{{_trans('file.Deposit')}}</option>
                 
                            
                            @if($ot_crm_reward_point_setting_data && $ot_crm_reward_point_setting_data->is_active)
                            <option value="7">{{_trans('file.Points')}}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="gift-card form-group">
                    <label> {{_trans('file.Gift Card')}} <span class="text-danger">*</span></label>
                    <select id="gift_card_id" name="gift_card_id"
                        class="form-select select2-input ot-input mb-3 modal_select2" data-live-search="true"
                        data-live-search-style="begins" title="Select Gift Card...">
                        @foreach($ot_crm_gift_card_list as $gift_card)
                        <option value="{{$gift_card->id}}">{{$gift_card->card_no}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-2">
                    <div class="card-element form-control ot-form-control ot-input">
                    </div>
                    <div class="card-errors" role="alert"></div>
                </div>
                <div id="edit-cheque">
                    <div class="form-group">
                        <label>{{_trans('file.Cheque Number')}} <span class="text-danger">*</span></label>
                        <input type="text" name="edit_cheque_no" class="form-control ot-form-control ot-input">
                    </div>
                </div>
                <div class="form-group">
                    <label> {{_trans('file.Account')}}</label>
                    <select class="form-select select2-input ot-input mb-3 modal_select2" name="account_id">
                        @foreach($ot_crm_account_list as $account)
                        <option value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{_trans('file.Payment Note')}}</label>
                    <textarea rows="3" class="form-control ot-form-control ot-input"
                        name="edit_payment_note"></textarea>
                </div>

                <input type="hidden" name="payment_id">

                <button type="submit" class="crm_theme_btn">{{_trans('file.update')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<div id="add-delivery" tabindex="-1" role="dialog" aria-labelledby="add-delivery" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Add Delivery')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'saleDelivery.store', 'method' => 'post', 'files' => true]) !!}
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>{{_trans('file.Delivery Reference')}}</label>
                        <p id="dr"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{_trans('file.Sale Reference')}}</label>
                        <p id="sr"></p>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>{{_trans('file.Status')}} <span class="text-danger">*</span></label>
                        <select name="status" required class="form-select select2-input ot-input mb-3 modal_select2">
                            <option value="" disabled>Select One</option>
                            <option value="1">{{_trans('file.Packing')}}</option>
                            <option value="2">{{_trans('file.Delivering')}}</option>
                            <option value="3">{{_trans('file.Delivered')}}</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-2 form-group">
                        <label>{{_trans('file.Delivered By')}}</label>
                        <input type="text" name="delivered_by"
                            class="form-control ot-form-control ot-input ot-form-control ot-form-control ot-input ot-input">
                    </div>
                    <div class="col-md-6 mt-2 form-group">
                        <label>{{_trans('file.Recieved By')}} </label>
                        <input type="text" name="recieved_by"
                            class="form-control ot-form-control ot-input ot-form-control ot-form-control ot-input ot-input">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{_trans('file.customer')}} <span class="text-danger">*</span></label>
                        <p id="customer"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{_trans('file.Attach File')}}</label>
                        <input type="file" name="file"
                            class="form-control ot-form-control ot-input ot-form-control ot-form-control ot-input ot-input">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{_trans('file.Address')}} <span class="text-danger">*</span></label>
                        <textarea rows="3" name="address"
                            class="form-control ot-form-control ot-input ot-form-control ot-form-control ot-input ot-input"
                            required></textarea>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{_trans('file.Note')}}</label>
                        <textarea rows="3" name="note"
                            class="form-control ot-form-control ot-input ot-form-control ot-form-control ot-input ot-input"></textarea>
                    </div>
                </div>
                <input type="hidden" name="reference_no">
                <input type="hidden" name="sale_id">
                <button type="submit" class="crm_theme_btn">{{_trans('file.submit')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')




@include('sale::layouts.sale-script')
@include('sale::layouts.delete-ajax')
@endsection