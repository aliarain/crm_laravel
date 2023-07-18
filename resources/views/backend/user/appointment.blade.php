@extends('backend.layouts.app')
@section('title', @$data['title'])

@section('content')
    <div class="content-wrapper dashboard-wrapper mt-30 ">
    @include('backend.partials.user_navbar')

    <!-- Main content -->
        <section class="content p-0 has-table-with-t">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
                @if(hasPermission('leave_request_read'))
                    <div class="d-flex align-items-center flex-wrap justify-content-between">
                        <div class="d-flex flex-wrap">
                            <x-date-picker :label="'Date Range'"/>
                            <div class="form-group mb-2">
                                <button type="submit"
                                        class="btn btn-primary profile_appointment_table_search_form">{{ _trans('common.Submit') }}</button>
                            </div>
                        </div>
                        @if(auth()->user()->id==$data['id'])
                                <div class="form-group mb-2">
                                    <a href="{{ route('appointment.create') }}"
                                       class="btn btn-primary ">{{ _trans('common.Add Appointment') }}</a>
                                </div>
                        @endif
                    </div>
                @endif
                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <table id="table"
                               class="table card-table table-vcenter datatable mb-0 w-100 profile_appointment_table">
                            <thead>
                            <tr>
                                <th>{{ _trans('common.Title') }}</th>
                                <th>{{ _trans('common.Appointment With') }}</th>
                                <th>{{ _trans('common.Date') }}</th>
                                <th>{{ _trans('common.Start At') }}</th>
                                <th>{{ _trans('common.End At') }}</th>
                                <th>{{ _trans('common.Location') }}</th>
                                <th>{{ _trans('common.File') }}</th>
                                <th>{{ _trans('common.Status') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <input type="hidden" name="user_id" id="user_id" value="{{ $data['id']}}">
    <input type="hidden" name="" id="profile_appointment_data_url"
           value="{{ route('appointment.profileDataTable') }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
@endsection
