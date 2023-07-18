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

                        @include('backend.partials.table.show',['path'=> 'sale/product/tax/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_product_tax_create'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-toggle="modal"
                                    data-bs-target="#createModal" data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                            @if (hasPermission('sales_product_tax_import'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-placement="right" data-bs-title="Add"
                                    data-bs-toggle="modal" data-bs-target="#importTax">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('file.Import Tax')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>
                        @include('backend.partials.table.search',['path'=> 'sale/product/tax/list' ])
                    </div>
                </div>

                {{-- @include('backend.partials.table.export',['path'=> 'sale/product/tax/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.name')}}</th>
                            <th>{{_trans('file.Rate')}}(%)</th>
                            <th>{{_trans('file.Status')}}</th>
                            <th class="not-exported">{{_trans('file.Action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['taxs'] as $key=>$tax)
                        <tr data-id="{{$tax->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ $tax->name }}</td>
                            <td>{{ $tax->rate }}</td>
                            <td>
                                @if( $tax->is_active ==1)
                                <span class="badge-success">{{_trans('common.Active')}}</span>
                                @else
                                <span class="badge-danger">{{_trans('common.Inactive')}}</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_product_tax_edit'))
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$tax->id}}"
                                                class="open-EditTaxDialog dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editModal"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_product_tax_delete'))
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/product/tax/delete', {{ $tax->id }})">
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
                        {{$data['taxs']->appends(request()->input())->links('pagination::bootstrap-4') }}
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
            {!! Form::open(['route' => 'saleProductTax.store', 'method' => 'post']) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Add Tax')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                    <div class="form-group mb-10">
                        <label class="form-label">{{_trans('file.Tax Name')}} <span class="text-danger">*</span></label>
                        {{Form::text('name',null,array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input','placeholder'
                    => 'Tax Name'))}}
                    </div>
                    <div class="form-group mb-30">
                        <label class="form-label">{{_trans('file.Rate')}}(%) <span class="text-danger">*</span></label>
                        {{Form::number('rate',null,array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input', 'step' => 'any','placeholder'
                    => 'Rate'))}}
                    </div>
                    <div class="d-flex justify-content-end">
                        <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                    </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => ['saleProductTax.update'], 'method' => 'post']) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title"> {{_trans('file.Update Tax')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                    <input type="hidden" name="tax_id">
                    <div class="form-group mb-3">
                        <label class="form-label">{{_trans('file.Tax Name')}} <span class="text-danger">*</span></label>
                        {{Form::text('name',null,array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input'))}}
                    </div>
                    <div class="form-group mb-20">
                        <label class="form-label">{{_trans('file.Rate')}}(%) <span class="text-danger">*</span></label>
                        {{Form::number('rate',null,array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input', 'step' => 'any'))}}
                    </div>
                    <div class="d-flex justify-content-end">
                        <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                    </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div id="importTax" tabindex="-1" role="dialog" aria-labelledby="importTax" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleProductTax.import', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Import Tax')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">

                <p>{{_trans('file.The correct column order is')}} (name*, rate*) {{_trans('file.and you must follow
                    this')}}.</p>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{_trans('file.Upload CSV File')}} <span
                                    class="text-danger">*</span></label>
                            {{Form::file('file', array('class' => 'form-control ot-form-control ot-input','required'))}}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-20 d-flex align-items-center gap-2">
                            <label class="form-label"> {{_trans('file.Sample File')}}</label>
                            <a href="{{ url('Modules/Sale/public/sample_file/sample_tax.csv')}}" class="crm_line_btn"><i
                                    class="dripicons-download"></i>
                                {{_trans('file.Download')}}</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@endsection
@section('script')
@include('sale::layouts.sale-product-tax-script')
@include('sale::layouts.delete-ajax')

@endsection