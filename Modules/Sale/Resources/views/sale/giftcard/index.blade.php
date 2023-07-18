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

                        @include('backend.partials.table.show',['path'=> 'sale/sale/giftcard/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_giftcard_create'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-toggle="modal"
                                    data-bs-target="#createModal" data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>
                        @include('backend.partials.table.search',['path'=> 'sale/sale/giftcard/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/sale/giftcard/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Card No')}}</th>
                            <th>{{_trans('file.customer')}}</th>
                            <th>{{_trans('file.Amount')}}</th>
                            <th>{{_trans('file.Expense')}}</th>
                            <th>{{_trans('file.Balance')}}</th>
                            <th>{{_trans('file.Created By')}}</th>
                            <th>{{_trans('file.Expired Date')}}</th>
                            <th class="not-exported">{{_trans('file.Action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['giftcards'] as $key=>$giftcard)
                        <tr data-id="{{@$giftcard->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ @$giftcard->card_no }}</td>
                            <td>{{ @$giftcard->customer->name??@$giftcard->user->name }}</td>
                            <td>{{ @$giftcard->amount }}</td>
                            <td>{{ @$giftcard->expense }}</td>
                            <td>{{ @$giftcard->amount - @$giftcard->expense }}</td>
                            <td>{{ @$giftcard->createdBy->name }}</td>
                            <td>
                                @if( @$giftcard->expired_date >= date("Y-m-d"))
                                <span class="badge-success">{{date('d-m-Y',
                                    strtotime(@$giftcard->expired_date))}}</span>
                                @else
                                <span class="badge-danger">{{date('d-m-Y',
                                    strtotime(@$giftcard->expired_date))}}</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_giftcard_edit'))
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{@$giftcard->id}}"
                                                class="open-EditgiftcardDialog dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editModal"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_giftcard_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/sale/giftcard/delete', {{ @$giftcard->id }})">
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
                        {{$data['giftcards']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>

<div id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title d-print-none"> {{_trans('file.Card Details')}}
                    &nbsp;&nbsp;</h5>
                <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i
                        class="dripicons-print"></i> {{_trans('file.Print')}}</button>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="gift-card" style="margin: 0 auto; max-width: 350px; position: relative; color:#fff;">
                    <img src="{{url('public/images/gift_card/front.jpg')}}" alt="#" width="350" height="200">
                    <div style="position: absolute; padding: 15px; top:0; left: 0; width: 350px;">
                        <h3 class="d-inline">Gift Card</h3>
                        <h3 class="d-inline float-right">{{@$currency->code}} <span id="balance"></span></h3>
                        <p class="card-number" style="font-size: 28px;letter-spacing: 3px; margin-top: 15px;"></p>
                        <p class="client" style="text-transform: capitalize; margin-bottom: 10px;"></p>
                        <span class="valid" style="font-size: 11px;">Valid Thru</span>
                        <p class="valid-date" style="font-size: 11px;"></p>
                    </div>
                </div>
                <br>
                <div class="gift-card" style="margin: 0 auto; max-width: 350px; position: relative; color:#fff;">
                    <img src="{{url('public/images/gift_card/back.png')}}" alt="#" width="350" height="200">
                    <div class="site-title" style="position: absolute; top: 50%; left: 50%; _transform: _translate(-50%, -50%);">
                        @if(@$general_setting->site_logo)
                        <img src="{{url('public/logo', @$general_setting->site_logo)}}" alt="#" height="38px"
                            width="38px">
                        <span style="font-size: 25px;">@endif{{@$general_setting->site_title}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Add Gift Card')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
             
                {!! Form::open(['route' => 'saleGiftcard.store', 'method' => 'post']) !!}
                <div class="form-group mb-3">
                    <label class="form-label">{{_trans('file.Card No')}} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        {{Form::text('card_no',null,array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input', 'placeholder'
                    => 'Card No'))}}
                        <div class="input-group-append">
                            <button type="button" class="crm_theme_btn genbutton">{{_trans('file.Generate')}}</button>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">{{_trans('file.Amount')}} <span class="text-danger">*</span></label>
                    <input type="number" name="amount" step="any" required placeholder="{{_trans('file.Amount')}}"
                        class="form-control ot-form-control ot-input">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">{{_trans('file.User List')}}</label>&nbsp;
                    <input type="checkbox" id="user" name="user" value="1">
                </div>
                <div class="form-group user_list mb-3">
                    <label class="form-label">{{_trans('file.User')}} <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select select2-input ot-input mb-3 modal_select2" required
                        data-live-search="true" data-live-search-style="begins">
                        <option value="" disabled>Select One</option>
                        @foreach($data['users'] as $user)
                        <option value="{{$user->id}}">{{$user->name .' ('.$user->email.')'}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group customer_list mb-3">
                    <label class="form-label">{{_trans('file.customer')}} <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select select2-input ot-input mb-3 modal_select2" required
                        data-live-search="true" data-live-search-style="begins">
                        <option value="" disabled>Select One</option>
                        @foreach($data['customers'] as $customer)
                        <option value="{{$customer->id}}">{{$customer->name .' ('.$customer->phone_number.')'}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">{{_trans('file.Expired Date')}}</label>
                    <input type="text" id="expired_date" name="expired_date"
                        class="form-control ot-form-control ot-input">
                </div>
                <div class="form-group d-flex justify-content-end mt-20 ">
                    <button type="submit" class="crm_theme_btn">{{_trans('file.submit')}}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Update Gift Card')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
               
                {!! Form::open(['route' => ['saleGiftcard.update'], 'method' => 'post']) !!}
                <div class="form-group mb-3">
                    <input type="hidden" name="gift_card_id">
                    <label class="form-label">{{_trans('file.Card No')}} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        {{Form::text('card_no_edit',null,array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input'))}}
                        <div class="input-group-append">
                            <button type="button" class="crm_theme_btn genbutton">{{_trans('file.Generate')}}</button>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">{{_trans('file.Amount')}} <span class="text-danger">*</span></label>
                    <input type="number" name="amount_edit" step="any" required
                        class="form-control ot-form-control ot-input">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">{{_trans('file.User List')}} </label>&nbsp;
                    <input type="checkbox" id="user_edit" name="user_edit" value="1">
                </div>
                <div class="form-group user_list_edit mb-3">
                    <label class="form-label">{{_trans('file.User')}} <span class="text-danger">*</span></label>
                    <select name="user_id_edit" class="form-select select2-input ot-input mb-3 modal_select2" required
                        data-live-search="true" data-live-search-style="begins" title="Select User...">
                        <option value="" disabled>Select One</option>
                        @foreach($data['users'] as $user)
                        <option value="{{$user->id}}">{{$user->name .' ('.$user->email.')'}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group customer_list_edit mb-3">
                    <label class="form-label">{{_trans('file.customer')}} <span class="text-danger">*</span></label>
                    <select name="customer_id_edit" class="form-select select2-input ot-input mb-3 modal_select2"
                        required data-live-search="true" data-live-search-style="begins">
                        <option value="" disabled>Select One</option>
                        @foreach($data['customers'] as $customer)
                        <option value="{{$customer->id}}">{{$customer->name .' ('.$customer->phone_number.')'}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-20">
                    <label class="form-label">{{_trans('file.Expired Date')}}</label>
                    <input type="text" id="expired_date_edit" name="expired_date_edit"
                        class="form-control ot-form-control ot-input">
                </div>
                <div class="form-group d-flex justify-content-end">
                    <button type="submit" class="crm_theme_btn">{{_trans('file.submit')}}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<div id="rechargeModal" tabindex="-1" role="dialog" aria-labelledby="rechargeModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title"> {{_trans('file.Card No')}}: <span id="card-no"></span>
                </h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
              
                {!! Form::open(['route' => ['saleGiftcard.recharge', 1], 'method' => 'post']) !!}
                <div class="form-group">
                    <input type="hidden" name="gift_card_id">
                    <label>{{_trans('file.Amount')}} <span class="text-danger">*</span></label>
                    <input type="number" name="amount" step="any" required
                        class="form-control ot-form-control ot-input">
                </div>
                <div class="form-group">
                    <button type="submit" class="crm_theme_btn">{{_trans('file.submit')}}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')




@include('sale::layouts.sale-giftcard-script')
@include('sale::layouts.delete-ajax')
@endsection