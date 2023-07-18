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

                        @include('backend.partials.table.show',['path'=> 'sale/sale/coupon/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_coupon_create'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-toggle="modal"
                                    data-bs-target="#createModal" data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>
                        @include('backend.partials.table.search',['path'=> 'sale/sale/coupon/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/sale/coupon/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Coupon Code')}}</th>
                            <th>{{_trans('file.Type')}}</th>
                            <th>{{_trans('file.Amount')}}</th>
                            <th>{{_trans('file.Minimum Amount')}}</th>
                            <th>Qty</th>
                            <th>{{_trans('file.Available')}}</th>
                            <th>{{_trans('file.Expired Date')}}</th>
                            <th>{{_trans('file.Created By')}}</th>
                            <th class="not-exported">{{_trans('file.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['coupons'] as $key=>$coupon)
                        <tr data-id="{{$coupon->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ $coupon->code }}</td>
                            @if($coupon->type == 'percentage')
                            <td>
                                <div class="badge badge-success">{{$coupon->type}}</div>
                            </td>
                            @else
                            <td>
                                <div class="badge badge-success">{{$coupon->type}}</div>
                            </td>
                            @endif
                            <td>{{ $coupon->amount }}</td>
                            @if($coupon->minimum_amount)
                            <td>{{ $coupon->minimum_amount }}</td>
                            @else
                            <td>{{_trans('common.N/A')}}</td>
                            @endif
                            <td>{{ $coupon->quantity }}</td>
                            @if($coupon->quantity - $coupon->used)
                            <td class="text-center">
                                <div class="badge badge-success">{{ $coupon->quantity - $coupon->used }}</div>
                            </td>
                            @else
                            <td class="text-center">
                                <div class="badge badge-danger">{{ $coupon->quantity - $coupon->used }}</div>
                            </td>
                            @endif
                            @if($coupon->expired_date >= date("Y-m-d"))
                            <td>
                                <div class="badge badge-success">{{date('d-m-Y', strtotime($coupon->expired_date))}}
                                </div>
                            </td>
                            @else
                            <td>
                                <div class="badge badge-danger">{{date('d-m-Y', strtotime($coupon->expired_date))}}
                                </div>
                            </td>
                            @endif
                            <td>{{ $coupon->user->name }}</td>

                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_coupon_edit'))
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$coupon->id}}"
                                                class="edit-btn open-EditcouponDialog dropdown-item"
                                                data-bs-toggle="modal" data-bs-target="#editModal"><span
                                                    class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_coupon_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/sale/coupon/delete', {{ $coupon->id }})">
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
                        {{$data['coupons']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>

<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Add Coupon')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
               
                {!! Form::open(['route' => 'saleCoupon.store', 'method' => 'post']) !!}
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Coupon Code')}} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            {{Form::text('code',null,array('required' => 'required', 'class' => 'form-control
                            ot-form-control ot-input','placeholder'
                    => 'Coupon Code'))}}
                            <div class="input-group-append">
                                <button type="button"
                                    class="crm_theme_btn genbutton">{{_trans('file.Generate')}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Type')}} <span class="text-danger">*</span></label>
                        <select class="form-select select2-input ot-input mb-3 modal_select2" name="type">
                            <option value="percentage">{{_trans('common.Percentage')}}</option>
                            <option value="fixed">{{_trans('common.Fixed Amount')}}</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group minimum-amount">
                        <label class="form-label">{{_trans('file.Minimum Amount')}} <span class="text-danger">*</span></label>
                        <input type="number" name="minimum_amount" step="any" placeholder="{{_trans('file.Minimum Amount')}}"
                            class="form-control ot-form-control ot-input">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Amount')}} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount" step="any" required placeholder="{{_trans('file.Amount')}}"
                                class="form-control ot-form-control ot-input">&nbsp;&nbsp;
                            <div class="input-group-append mt-1">
                                <span class="icon-text" style="font-size: 22px;"><strong>%</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('common.Qty')}} <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" step="any" required placeholder="{{_trans('common.Qty')}}"
                            class="form-control ot-form-control ot-input">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Expired Date')}}</label>
                        <input type="text" name="expired_date"
                            class="expired_date form-control ot-form-control ot-input">
                    </div>
                </div>
                <div class="form-group d-flex justify-content-end mt-20">
                    <button type="submit" class="crm_theme_btn">{{_trans('file.submit')}}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 id="exampleModalLabel" class="modal-title">{{_trans('file.Update Coupon')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
              
                {!! Form::open(['route' => ['saleCoupon.update'], 'method' => 'post']) !!}
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Coupon')}} {{_trans('file.Code')}} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="hidden" name="coupon_id">
                            {{Form::text('code',null,array('required' => 'required', 'class' => 'form-control
                            ot-form-control ot-input'))}}
                            <div class="input-group-append">
                                <button type="button"
                                    class="crm_theme_btn genbutton">{{_trans('file.Generate')}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Type')}} <span class="text-danger">*</span></label>
                        <select class="form-select select2-input ot-input mb-3 modal_select2 " name="type">
                            <option value="percentage">{{_trans('common.Percentage')}}</option>
                            <option value="fixed">{{_trans('common.Fixed Amount')}}</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group minimum-amount mb-3">
                        <label class="form-label">{{_trans('file.Minimum Amount')}} <span class="text-danger">*</span></label>
                        <input type="number" name="minimum_amount" step="any"
                            class="form-control ot-form-control ot-input">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Amount')}} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount" step="any" required
                                class="form-control ot-form-control ot-input">&nbsp;&nbsp;
                            <div class="input-group-append mt-1">
                                <span class="icon-text" style="font-size: 22px;"><strong>%</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('common.Qty')}} <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" step="any" required
                            class="form-control ot-form-control ot-input">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Expired Date')}}</label>
                        <input type="text" name="expired_date"
                            class="expired_date form-control ot-form-control ot-input">
                    </div>
                </div>
                <div class="form-group d-flex justify-content-end">
                    <button type="submit" class="crm_theme_btn">{{_trans('file.submit')}}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')




@include('sale::layouts.sale-coupon-script')
@include('sale::layouts.delete-ajax')
@endsection