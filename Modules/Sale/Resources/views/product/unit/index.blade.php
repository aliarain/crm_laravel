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
            <div class="table-toolbar d-flex flex-wrap gap-2 flex-column flex-xl-row justify-content-center justify-content-xxl-between align-content-center pb-3">
                <div class="align-self-center">
                    <div class="d-flex flex-wrap gap-2 flex-column flex-lg-row justify-content-center align-content-center">

                        @include('backend.partials.table.show',['path'=> 'sale/product/unit/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_product_unit_create'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#createModal" data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                            @if (hasPermission('sales_product_unit_import'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-placement="right" data-bs-title="Add" data-bs-toggle="modal" data-bs-target="#importUnit">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('file.Import Unit')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>
                        @include('backend.partials.table.search',['path'=> 'sale/product/unit/list' ])
                    </div>
                </div>
                    {{-- @include('backend.partials.table.export',['path'=> 'sale/product/unit/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Code')}}</th>
                            <th>{{_trans('file.name')}}</th>
                            <th>{{_trans('file.Base Unit')}}</th>
                            <th>{{_trans('file.Operator')}}</th>
                            <th>{{_trans('file.Operation Value')}}</th>
                            <th class="not-exported">{{_trans('file.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['units'] as $key=>$unit)
                        <tr data-id="{{$unit->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ $unit->unit_code }}</td>
                            <td>{{ $unit->unit_name }}</td>
                            @if($unit->base_unit)
                            <?php $base_unit = DB::table('sale_units')->where('id', $unit->base_unit)->first(); ?>
                            <td>{{ $base_unit->unit_name }}</td>
                            @else
                            <td>N/A</td>
                            @endif
                            @if($unit->operator)
                            <td>{{ $unit->operator }}</td>
                            @else
                            <td>N/A</td>
                            @endif
                            @if($unit->operation_value)
                            <td>{{ $unit->operation_value }}</td>
                            @else
                            <td>N/A</td>
                            @endif
                            <td>
                                @if( $unit->is_active ==1)
                                <span class="badge-success">Active</span>
                                @else
                                <span class="badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_product_unit_edit'))
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$unit->id}}" class="open-EditUnitDialog dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal"><span class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_product_unit_delete'))
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="delete_row('sale/product/unit/delete', {{ $unit->id }})">
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
                {{$data['units']->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>


<!-- Modal -->

<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleProductUnit.store', 'method' => 'post']) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Add Unit')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">{{_trans('file.Code')}} <span class="text-danger">*</span></label>
                        {{Form::text('unit_code',null,array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input','placeholder'
                    => 'Code'))}}
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{_trans('common.Name')}} <span class="text-danger">*</span></label>
                        {{Form::text('unit_name',null,array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input','placeholder'
                    => 'Name'))}}
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{_trans('file.Base Unit')}}</label>
                        <select class="form-select select2-input ot-input mb-3 modal_select2" id="base_unit_create" name="base_unit">
                            <option value="">No Base Unit</option>
                            @foreach($data['units'] as $unit)
                            @if($unit->base_unit==null)
                            <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group operator mb-3">
                        <label class="form-label">{{_trans('file.Operator')}}</label> <input type="text" name="operator" placeholder="Enter your Name" class="form-control ot-form-control ot-input" />
                    </div>
                    <div class="form-group operation_value mb-3">
                        <label class="form-label">{{_trans('file.Operation Value')}}</label><input type="number" name="operation_value" placeholder="Enter operation value" class="form-control ot-form-control ot-input" step="any" />
                    </div>
                    <div class="d-flex justify-content-end mt-20">
                        <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                    </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => ['saleProductUnit.update'], 'method' => 'post']) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title"> {{_trans('file.Update Unit')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">

                    <input type="hidden" name="unit_id">
                    <div class="form-group mb-3">
                        <label class="form-label">{{_trans('file.Code')}} <span class="text-danger">*</span></label>
                        {{Form::text('unit_code',null,array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input'))}}
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{_trans('file.name')}} <span class="text-danger">*</span></label>
                        {{Form::text('unit_name',null,array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input'))}}
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{_trans('file.Base Unit')}}</label>
                        <select class="form-select select2-input ot-input mb-3 modal_select2" id="base_unit_edit" name="base_unit">
                            <option value="">No Base Unit</option>
                            @foreach($data['units'] as $unit)
                            @if($unit->base_unit==null)
                            <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group operator mb-3">
                        <label class="form-label">{{_trans('file.Operator')}}</label> <input type="text" name="operator" placeholder="Enter your Name" class="form-control ot-form-control ot-input" />
                    </div>
                    <div class="form-group operation_value mb-20">
                        <label class="form-label">{{_trans('file.Operation Value')}}</label><input type="number" name="operation_value" placeholder="Enter operation value" class="form-control ot-form-control ot-input" step="any" />
                    </div>
                    <div class="d-flex align-items-center justify-content-end">
                        <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                    </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div id="importUnit" tabindex="-1" role="dialog" aria-labelledby="importUnit" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleProductUnit.import', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title"> {{_trans('file.Import Unit')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">

                <p>{{_trans('file.The correct column order is')}} (unit_code*, unit_name*, base_unit [unit code],
                    operator, operation_value) {{_trans('file.and you must follow this')}}.</p>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label">{{_trans('file.Upload CSV File')}} <span class="text-danger">*</span></label>
                            {{Form::file('file', array('class' => 'form-control ot-form-control ot-input','required'))}}
                        </div>
                    </div>
                    <div class="col-md-12 mb-20">
                        <div class="form-group d-flex align-items-center gap-2">
                            <label class="form-label"> {{_trans('file.Sample File')}}</label>
                            <a href="{{ url('Modules/Sale/public/sample_file/sample_unit.csv')}}" class="crm_line_btn"><i class="dripicons-download"></i>
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
@include('sale::layouts.sale-product-unit-script')
@include('sale::layouts.delete-ajax')

@endsection