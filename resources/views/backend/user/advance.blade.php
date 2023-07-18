@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

<div class="content-wrapper dashboard-wrapper mt-30">

    <!-- Main content -->
    <section class="content p-0">
        @include('backend.partials.user_navbar')
        <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap parent-select2-width">
                <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                <div class="d-flex align-items-center flex-wrap">
                    @if (hasPermission('advance_salaries_search'))
                    <div class="form-group mb-2 mr-2">
                        <input type="date" id="start" name="start_date" class="form-control"
                            value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <select name="return_status" id="return_status"
                            class="form-control select2 custom-select-width">
                            <option value="0">{{ _trans('common.Return Status') }}</option>
                            <option value="22">{{ _trans('common.No') }}</option>
                            <option value="21">{{ _trans('common.Partially Returned') }}</option>
                            <option value="23">{{ _trans('common.Returned') }}</option>
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <select name="status" id="status" class="form-control select2 custom-select-width">
                            <option value="0">{{ _trans('common.Status') }}</option>
                            <option value="2">{{ _trans('common.Pending') }}</option>
                            <option value="5">{{ _trans('common.Approve') }}</option>
                            <option value="6">{{ _trans('common.Reject') }}</option>
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <select name="payment" id="payment" class="form-control select2 custom-select-width">
                            <option value="0">{{ _trans('common.Payment Status') }}</option>
                            <option value="9">{{ _trans('common.Unpaid') }}</option>
                            <option value="8">{{ _trans('common.Paid') }}</option>
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <button type="button" class="btn btn-primary advance_table_form">{{ _trans('common.Search')
                            }}</button>
                    </div>
                    @endif
                   
                </div>

            </div>

            <div class="row dataTable-btButtons">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="table"
                                class="table card-table table-vcenter datatable mb-0 w-100 payroll_advance_table">
                                <thead>
                                    <tr>
                                        <th>{{ _trans('common.Employee') }}</th>
                                        <th>{{ _trans('common.Advance Type') }}</th>
                                        <th>{{ _trans('common.Amount') }}</th>
                                        <th>{{ _trans('common.Month') }}</th>
                                        <th>{{ _trans('common.Payment') }}</th>
                                        <th>{{ _trans('common.Return') }}</th>
                                        <th>{{ _trans('common.Installment') }}</th>
                                        <th>{{ _trans('common.Created At') }}</th>
                                        <th>{{ _trans('common.Status') }}</th>
                                        <th class="">{{ _trans('common.Action') }}</th>
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
<input type="text" hidden id="advance_salary_report_data_url"
    value="{{ route('user.profileDataTable',['user_id'=>$data['id'],'type'=>'advance']) }}">
@endsection
@section('script')
@include('backend.partials.datatable')
@endsection