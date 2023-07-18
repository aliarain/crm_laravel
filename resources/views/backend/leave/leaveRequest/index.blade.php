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
            <!-- toolbar table start -->
            <div
                class="table-toolbar d-flex flex-wrap gap-2 flex-xl-row justify-content-center justify-content-xxl-between align-content-center pb-3">
                <div class="align-self-center">
                    <div class="d-flex flex-wrap gap-2  flex-lg-row justify-content-center align-content-center">
                        <!-- show per page -->
                        <div class="align-self-center">
                            <label>
                                <span class="mr-8">{{ _trans('common.Show') }}</span>
                                <select class="form-select d-inline-block" id="entries"
                                    onchange="leaveRequestDatatable()">
                                    <option selected value="10">{{ _trans('10') }}</option>
                                    <option value="25">{{ _trans('25') }}</option>
                                    <option value="50">{{ _trans('50') }}</option>
                                    <option value="100">{{ _trans('100') }}</option>
                                </select>
                                <span class="ml-8">{{ _trans('common.Entries') }}</span>
                            </label>
                        </div>



                        <div class="align-self-center d-flex flex-wrap gap-2">
                            <!-- add btn -->
                            @if (hasPermission('leave_request_create'))
                            <div class="align-self-center">
                                <a href="{{ route('leaveRequest.create') }}" role="button" class="btn-add"
                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                    data-bs-title="{{ _trans('common.Add') }}">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{ _trans('common.Create') }}</span>
                                </a>
                            </div>
                            @endif
                            <div class="align-self-center">
                                <button type="button" class="btn-daterange" id="daterange" data-bs-toggle="tooltip"
                                    data-bs-placement="right" data-bs-title="{{ _trans('common.Date Range') }}">
                                    <span class="icon"><i class="fa-solid fa-calendar-days"></i>
                                    </span>
                                    <span class="d-none d-xl-inline">{{ _trans('common.Date Range') }}</span>
                                </button>
                                <input type="hidden" id="daterange-input" onchange="leaveRequestDatatable()">
                            </div>
                            <!-- Designation -->
                            <div class="align-self-center">
                                <div class="dropdown dropdown-designation" data-bs-toggle="tooltip"
                                    data-bs-placement="right" data-bs-title="Designation">
                                    <button type="button" class="btn-designation" data-bs-toggle="dropdown"
                                        aria-expanded="false" data-bs-auto-close="false">
                                        <span class="icon"><i class="fa-solid fa-user-shield"></i></span>

                                        <span class="d-none d-xl-inline">{{ _trans('common.Department') }}</span>
                                    </button>

                                    <div class="dropdown-menu  align-self-center ">
                                        <select name="department_id" id="department_id"
                                            class="form-control pl-2 select2 w-100" onchange="selectDepartmentUsers()">
                                            <option value="0" selected disabled class="">
                                                {{ _trans('common.Select Department') }}</option>
                                            @foreach ($data['departments'] as $role)
                                            <option value="{{ $role->id }}">{{ @$role->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="align-self-center">
                                <div class="dropdown dropdown-designation" data-bs-toggle="tooltip"
                                    data-bs-placement="right" data-bs-title="Employee">
                                    <button type="button" class="btn-designation" data-bs-toggle="dropdown"
                                        aria-expanded="false" data-bs-auto-close="false">
                                        <span class="icon"><i class="fa-solid fa-users"></i></span>
                                        <span class="d-none d-xl-inline">{{ _trans('common.Employee') }}</span>
                                    </button>

                                    <div class="dropdown-menu align-self-center ">
                                        <select name="user_id" class="form-control select2" id="__user_id"
                                            onchange="leaveRequestDatatable()">
                                            <option value="0" selected disabled class="">
                                                {{ _trans('common.Select Employee') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>



                            <div class="align-self-center">
                                <div class="dropdown dropdown-designation" data-bs-toggle="tooltip"
                                    data-bs-placement="right" data-bs-title="Type">
                                    <button type="button" class="btn-designation" data-bs-toggle="dropdown"
                                        aria-expanded="false" data-bs-auto-close="false">
                                        <span class="icon"><i class="fa-solid fa-user-tag"></i></span>
                                        <span class="d-none d-xl-inline">{{ _trans('common.Type') }}</span>
                                    </button>

                                    <div class="dropdown-menu  align-self-center ">
                                        <select name="type" class="form-control select2" id="type"
                                            onchange="leaveRequestDatatable()">
                                            <option value="0" selected disabled>{{ _trans('common.Select Type') }}
                                            </option>
                                            <option value="1">{{ _trans('common.Approve') }}</option>
                                            <option value="2">{{ _trans('common.Pending') }}</option>
                                            <option value="6">{{ _trans('common.Reject') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <!-- dropdown action -->
                        <div class="align-self-center">
                            <div class="dropdown dropdown-action" data-bs-toggle="tooltip" data-bs-placement="right"
                                data-bs-title="{{ _trans('common.Action') }}">
                                <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            onclick="tableAction('active', `{{ route('leaveRequest.statusUpdate') }}`)"><span
                                                class="icon mr-10"><i class="fa-solid fa-eye"></i></span>
                                            {{ _trans('common.Activate') }} <span class="count">(0)</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" aria-current="true"
                                            onclick="tableAction('inactive', `{{ route('leaveRequest.statusUpdate') }}`)">
                                            <span class="icon mr-8"><i class="fa-solid fa-eye-slash"></i></span>
                                            {{ _trans('common.Inactive') }} <span class="count">(0)</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            onclick="tableAction('delete', `{{ route('leaveRequest.delete_data') }}`)">
                                            <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>
                                            {{ _trans('common.Delete') }} <span class="count">(0)</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- export -->
                <div class="align-self-center">
                    <div class="d-flex justify-content-center justify-content-xl-end align-content-center">

                        <div class="dropdown dropdown-export" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="{{ _trans('common.Export') }}">
                            <button type="button" class="btn-export" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>

                                <span class="d-none d-xl-inline">{{ _trans('common.Export') }}</span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#"
                                        onclick="selectElementContents(document.getElementById('table'))">
                                        <span class="icon mr-8"><i class="fa-solid fa-copy"></i>
                                        </span>
                                        {{ _trans('common.Copy') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="exportJSON">
                                        <span class="icon mr-8">
                                            <i class="fa-solid fa-code"></i>
                                        </span>
                                        {{ _trans('common.Json File') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="btnExcelExport" href="#" aria-current="true"><span
                                            class="icon mr-10"><i class="fa-solid fa-file-excel"></i></span>
                                        {{ _trans('common.Excel File') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="exportCSV">
                                        <span class="icon mr-14"><i class="fa-solid fa-file-csv"></i></span>
                                        {{ _trans('common.Csv File') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="exportPDF">
                                        <span class="icon mr-14"><i class="fa-solid fa-file-pdf"></i></span>
                                        {{ _trans('common.Pdf File') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- toolbar table end -->
            <!--  table start -->
            <div class="table-responsive">
                @include('backend.partials.table')
            </div>
            <!--  table end -->
        </div>
    </div>
</div>
@endsection
@section('script')
@include('backend.partials.table_js')
@endsection