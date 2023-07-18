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

                        @include('backend.partials.table.show',['path'=> 'sale/product/supplier/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_product_supplier_create'))
                            <div class="align-self-center">
                                <a href="{{ route('saleSupplier.create') }}" role="button" class="btn-add"
                                    data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                            @if (hasPermission('sales_product_supplier_import'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-placement="right" data-bs-title="Add"
                                    data-bs-toggle="modal" data-bs-target="#importSupplier">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('file.Import Supplier')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>

                        @include('backend.partials.table.search',['path'=> 'sale/product/supplier/list' ])
                    </div>
                </div>
               {{-- @include('backend.partials.table.export',['path'=> 'sale/product/supplier/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Image')}}</th>
                            <th>{{_trans('file.Supplier Details')}}</th>
                            <th>{{_trans('file.Total Due')}}</th>
                            <th class="not-exported">{{_trans('file.Action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['suppliers'] as $key=>$supplier)
                        <?php
                                $returned_amount = DB::table('sale_purchases')
                                                ->join('sale_return_purchases', 'sale_purchases.id', '=', 'sale_return_purchases.purchase_id')
                                                ->where([
                                                    ['sale_purchases.supplier_id', $supplier->id],
                                                    ['sale_purchases.payment_status', 1]
                                                ])
                                                ->sum('sale_return_purchases.grand_total');
                                $purchaseData = Modules\Sale\Entities\SalePurchase::where([
                                                ['supplier_id', $supplier->id],
                                                ['payment_status', 1]
                                            ])
                                            ->selectRaw('SUM(grand_total) as grand_total,SUM(paid_amount) as paid_amount')
                                            ->first();
                            ?>
                        <tr data-id="{{$supplier->id}}">
                            <td>{{$key+1}}</td>
                            @if($supplier->image)
                            <td> <img class="staff-profile-image-small" src="{{url('public/images/supplier',$supplier->image)}}" height="80" width="80">
                            </td>
                            @else
                            <td class=""><img class="staff-profile-image-small" src="{{ url('public/static/blank_small.png') }}" alt=""></td>
                            @endif
                            <td>
                                {{$supplier->name}}
                                <br>{{$supplier->company_name}}
                                @if($supplier->vat_number)
                                <br>{{$supplier->vat_number}}
                                @endif
                                <br>{{$supplier->email}}
                                <br>{{$supplier->phone_number}}
                                <br>{{$supplier->address}}, {{$supplier->city}}
                                @if($supplier->state){{','.$supplier->state}}@endif
                                @if($supplier->postal_code){{','.$supplier->postal_code}}@endif
                                @if($supplier->country){{','.$supplier->country}}@endif
                            </td>
                            <td>{{number_format($purchaseData->grand_total - $returned_amount -
                                $purchaseData->paid_amount, 2)}}</td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_product_supplier_clear_due'))
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$supplier->id}}"
                                                class="clear-due dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#clearDueModal"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Clear Due') }}</span></a>
                                        </li>
                                        @endif

                                        @if (hasPermission('sales_product_supplier_edit'))
                                        <li>
                                            <a href="{{ route('saleSupplier.edit', $supplier->id) }}"
                                                class="dropdown-item"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif

                                        @if (hasPermission('sales_product_supplier_delete'))
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/product/supplier/delete', {{ $supplier->id }})">
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
                        {{$data['suppliers']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>

<div id="clearDueModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleSupplier.clearDue', 'method' => 'post']) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 id="exampleModalLabel" class="modal-title">{{_trans('file.Clear Due')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
               
                <div class="form-group">
                    <input type="hidden" name="supplier_id">
                    <label>{{_trans('file.Amount')}} <span class="text-danger">*</span></label>
                    <input type="number" name="amount" step="any" class="form-control ot-form-control ot_input"
                        required>
                </div>
                <div class="form-group">
                    <label>{{_trans('file.Note')}}</label>
                    <textarea name="note" rows="4" class="form-control"></textarea>
                </div>
                <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn" id="submit-button">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('script')
@include('sale::layouts.sale-product-supplier-script')
@include('sale::layouts.delete-ajax')
@endsection