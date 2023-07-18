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

                        @include('backend.partials.table.show',['path'=> 'sale/expense/category/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_expense_category_create'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-toggle="modal"
                                    data-bs-target="#createModal" data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                            @if (hasPermission('sales_expense_category_import'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-placement="right" data-bs-title="Add"
                                    data-bs-toggle="modal" data-bs-target="#importExpenseCategory">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('file.Import Category')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>
                        @include('backend.partials.table.search',['path'=> 'sale/expense/category/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/expense/category/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Code')}}</th>
                            <th>{{_trans('file.name')}}</th>
                            <th class="not-exported">{{_trans('file.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['categories'] as $key=>$category)
                        <tr data-id="{{$category->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ $category->code }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_expense_category_edit'))
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$category->id}}"
                                                class="open-Editexpense_categoryDialog dropdown-item"
                                                data-bs-toggle="modal" data-bs-target="#editModal"><span
                                                    class="icon mr-8"><i class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_expense_category_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/expense/category/delete', {{ $category->id }})">
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
                        {{$data['categories']->appends(request()->input())->links('pagination::bootstrap-4') }}
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
            {!! Form::open(['route' => 'saleProductExpenseCategory.store', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{ _trans('file.Add Expense Category') }}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
             

                <div class="col-md-12 form-group mb-3">
                    <label class="form-label">{{ _trans('file.Code') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        {{ Form::text('code', null, ['required' => 'required', 'class' => 'form-control ot-form-control
                        ot-input', 'placeholder' => 'Type expense category code...']) }}
                        <div class="input-group-append">
                            <button id="genbutton" type="button" class="crm_theme_btn">{{ _trans('file.Generate')
                                }}</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 form-group mb-3">
                    <label class="form-label">{{ _trans('file.name') }} <span class="text-danger">*</span></label>
                    {{ Form::text('name', null, ['required' => 'required', 'class' => 'form-control ot-form-control
                    ot-input', 'placeholder' => 'Type expense category name...']) }}
                </div>
                <input type="hidden" name="is_active" value="1">

                <div class="col-md-12 form-group mb-3 d-flex justify-content-end">
                    <input type="submit" value="{{ _trans('file.submit') }}" class="crm_theme_btn">
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
            {{ Form::open(['route' => ['saleProductExpenseCategory.update'], 'method' => 'post', 'files' => true]) }}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title"> {{ _trans('file.Update Expense Category') }}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
               
                <div class="col-md-12 form-group mb-3">
                    <label class="form-label">{{ _trans('file.Code') }} <span class="text-danger">*</span></label>
                    {{ Form::text('code', null, ['required' => 'required', 'class' => 'form-control ot-form-control
                    ot-input', 'placeholder' => 'Type expense category code...']) }}
                </div>

                <div class="col-md-12 form-group mb-3">
                    <label class="form-label">{{ _trans('file.name') }} <span class="text-danger">*</span></label>
                    {{ Form::text('name', null, ['required' => 'required', 'class' => 'form-control ot-form-control
                    ot-input', 'placeholder' => 'Type expense category name...']) }}
                </div>
                <input type="hidden" name="expense_category_id">

                <div class="col-md-12 form-group d-flex justify-content-end">
                    <input type="submit" value="{{ _trans('file.submit') }}" class="crm_theme_btn">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div id="importExpenseCategory" tabindex="-1" role="dialog" aria-labelledby="importExpenseCategory" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleProductExpenseCategory.import', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{ _trans('file.Import Expense Category') }}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
            
                <p>{{ _trans('file.The correct column order is') }} (code*, name*)
                    {{ _trans('file.and you must follow this') }}.</p>

                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-12 form-group mb-3">
                            <label class="form-label">{{ _trans('file.Upload CSV File') }} <span class="text-danger">*</span></label>
                            {{ Form::file('file', ['class' => 'form-control ot-form-control ot-input', 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12 form-group mb-3">
                            <label> {{ _trans('file.Sample File') }}</label>
                            <a href="{{ url('Modules/Sale/public/sample_file/sample_expense_category.csv') }}"
                                class="crm_line_btn"><i class="dripicons-download"></i>
                                {{ _trans('file.Download') }}</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <input type="submit" value="{{ _trans('file.submit') }}" class="crm_theme_btn">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@endsection
@section('script')
@include('sale::layouts.sale-expense-category-script')

@include('sale::layouts.delete-ajax')

@endsection