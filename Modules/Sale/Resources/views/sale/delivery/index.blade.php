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

                        @include('backend.partials.table.show',['path'=> 'sale/sale/delivery/list' ])
                        @include('backend.partials.table.search',['path'=> 'sale/sale/delivery/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/sale/delivery/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Delivery Reference')}}</th>
                            <th>{{_trans('file.Sale Reference')}}</th>
                            <th>{{_trans('file.Customer')}}</th>
                            <th>{{_trans('file.Address')}}</th>
                            <th>{{_trans('file.Products')}}</th>
                            <th>{{_trans('file.Grand Total')}}</th>
                            <th>{{_trans('file.Status')}}</th>
                            <th class="not-exported">{{_trans('file.Action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['deliveries'] as $key=>$delivery)
                        <tr data-id="{{$delivery->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ $delivery->reference_no }}</td>
                            <td>{{ $delivery->sale->reference_no }}</td>
                            <td>{!!$delivery->sale->customer->name .'<br>'. $delivery->sale->customer->phone_number!!}
                            </td>
                            <td>{{ $delivery->address }}</td>
                            <td>
                                @if ($delivery->sale->saleProducts->count() > 0)
                                @foreach ($delivery->sale->saleProducts as $key2=>$item)
                                <p>{{ $key2+1 }}. {{$item->product->name}}</p>
                                @endforeach

                                @endif
                            </td>
                            <td>{{number_format($delivery->sale->grand_total, 2)}}</td>
                            @if($delivery->status == 1)
                            <td>
                                <div class="badge badge-warning">{{ _trans('file.Packing') }}</div>
                            </td>
                            @elseif($delivery->status == 2)
                            <td>
                                <div class="badge badge-warning">{{ _trans('file.Delivering') }}</div>
                            </td>
                            @else
                            <td>
                                <div class="badge badge-success">{{ _trans('file.Delivered')}}</div>
                            </td>
                            @endif

                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_delivery_edit'))
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$delivery->id}}"
                                                class="edit-btn open-EditdeliveryDialog dropdown-item"
                                                data-bs-toggle="modal" data-bs-target="#editModal"><span
                                                    class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_delivery_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/sale/delivery/delete', {{ $delivery->id }})">
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
                        {{$data['deliveries']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>

<!-- Modal -->
<div id="delivery-details" tabindex="-1" role="dialog" aria-labelledby="delivery-details" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="container mt-3 pb-2 border-bottom">
                <div class="row">
                    <div class="col-md-6 d-print-none">
                        <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i
                                class="dripicons-print"></i> {{_trans('file.Print')}}</button>

                        {{ Form::open(['route' => 'saleDelivery.sendMail', 'method' => 'post', 'class' =>
                        'sendmail-form'] ) }}
                        <input type="hidden" name="delivery_id">
                        <button class="btn btn-default btn-sm d-print-none"><i class="dripicons-mail"></i>
                            {{_trans('file.Email')}}</button>
                        {{ Form::close() }}
                    </div>
                    <div class="col-md-6">
                        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                                aria-hidden="true"><i class="las la-times"></i></span></button>
                    </div>
                    <div class="col-md-12">
                        <h3 class="modal-title text-center container-fluid">
                            {{@$general_setting->site_title}}
                        </h3>
                    </div>
                    <div class="col-md-12 text-center">
                        <i style="font-size: 15px;">{{_trans('file.Delivery Details')}}</i>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="delivery-content">
                    <tbody class="tbody"></tbody>
                </table>
                <br>
                <table class="table table-bordered product-delivery-list">
                    <thead class="thead">
                        <tr>
                            <th>{{_trans('file.No')}}</th>
                            <th>{{_trans('file.Code')}}</th>
                            <th>{{_trans('file.Description')}}</th>
                            <th>{{_trans('file.Batch No')}}</th>
                            <th>{{_trans('file.Expired Date')}}</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                    </tbody>
                </table>
                <div id="delivery-footer" class="row">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="edit-delivery" tabindex="-1" role="dialog" aria-labelledby="edit-delivery" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Update Delivery')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'saleDelivery.update', 'method' => 'post', 'files' => true]) !!}
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
                        <input type="text" name="delivered_by" class="form-control ot-form-control ot-input">
                    </div>
                    <div class="col-md-6 mt-2 form-group">
                        <label>{{_trans('file.Recieved By')}}</label>
                        <input type="text" name="recieved_by" class="form-control ot-form-control ot-input">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{_trans('file.customer')}} <span class="text-danger">*</span></label>
                        <p id="customer"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{_trans('file.Attach File')}}</label>
                        <input type="file" name="file" class="form-control ot-form-control ot-input">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{_trans('file.Address')}} <span class="text-danger">*</span></label>
                        <textarea rows="3" name="address" class="form-control ot-form-control ot-input"
                            required></textarea>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{_trans('file.Note')}}</label>
                        <textarea rows="3" name="note" class="form-control ot-form-control ot-input"></textarea>
                    </div>
                </div>
                <input type="hidden" name="reference_no">
                <input type="hidden" name="delivery_id">
                <button type="submit" class="crm_theme_btn">{{_trans('file.submit')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')




@include('sale::layouts.sale-delivery-script')
@include('sale::layouts.delete-ajax')
@endsection