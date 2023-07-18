@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
@include('backend.partials.staff_navbar')
<div class="profile-content">
        
        <!-- Main content -->
        <div class="profile-body profile-body-cus">
            @include('backend.partials.default_table')
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30 d-none">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex">
                        <h5 class="fm-poppins m-0 text-dark mr-2 mb-2">{{ @$data['title'] }}</h5>
                    </div>
                    <div class="d-flex flex-wrap">
                        @if (hasPermission('support_read'))
                        <div class="form-group mb-2 mr-2">
                            <input class="daterange-table-filter" type="text" name="daterange" value="" />
                        </div>
                        <div class="form-group mb-2 mr-2">
                            <button type="submit" class="btn btn-primary support_ticket_table_form">{{ _trans('common.Submit') }}</button>
                        </div>
                    @endif
                    @if (hasPermission('support_create'))
                        <a href="{{ route('supportTicket.create') }}"
                            class="btn btn-primary mb-2"> {{ _trans('common.Create ticket') }}</a>
                    @endif
                    </div>
                </div>
                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <table id="table" class="table card-table table-vcenter datatable mb-0 w-100 support_ticket_table">
                            <thead>
                                <tr>
                                    <th>{{ _trans('common.Date') }}</th>
                                     <th>{{ _trans('common.Code') }}</th>
                                    <th>{{ _trans('common.Employee name') }}</th>
                                     <th>{{ _trans('common.Subject') }}</th>
                                    <th>{{ _trans('common.Type') }}</th>
                                     <th>{{ _trans('common.Priority') }}</th>
                                    <th>{{ _trans('common.Action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="text" hidden id="support_ticket_data_url" value="{{ route('staff.authUserTable','ticket') }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
@endsection
