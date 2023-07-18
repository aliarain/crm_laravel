@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
@include('backend.partials.staff_navbar')
<div class="profile-content">
     
        <!-- Main content -->
        <section class="profile-body profile-body-cus">
            @include('backend.partials.default_table')
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30 d-none">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
                    <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                    <div class="d-flex align-items-center flex-wrap">
                        @if (hasPermission('salary_search'))
                            <div class="form-group mb-2 mr-2">
                                <input type="date" id="start" name="start_date" class="form-control">
                            </div>
                            <div class="form-group mr-2 mb-2">
                                <select name="status" id="status" class="form-control select2">
                                    <option value="9">{{ _trans('common.Unpaid') }}</option>
                                    <option value="8">{{ _trans('common.Paid') }}</option>
                                    <option value="20">{{ _trans('common.Partially Paid') }}</option>
                                </select>
                            </div>
                            
                            <div class="form-group mr-2 mb-2">
                                <button type="button"
                                    class="btn btn-primary salary_table_form">{{ _trans('common.Search') }}</button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="table" class="table card-table table-vcenter datatable mb-0 w-100 salary_datatable_class">
                                    <thead>
                                        <tr>
                                            <th>{{ _trans('common.Employee ID') }}</th>
                                            <th>{{_trans('common.Name') }}</th>
                                            <th>{{_trans('payroll.Salary Type') }}</th>
                                            <th>{{_trans('payroll.Calculation') }}</th>
                                            <th>{{_trans('payroll.Salary') }}</th>
                                            <th>{{_trans('payroll.Month') }}</th>
                                            <th>{{_trans('payroll.Status') }}</th>
                                            <th>{{_trans('payroll.Action') }}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

</div>
    <input type="text" hidden id="salary_datatable_url" value="{{ route('staff.authUserTable','salary') }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
@endsection
