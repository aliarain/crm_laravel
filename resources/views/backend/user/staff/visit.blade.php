@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
@include('backend.partials.staff_navbar')
    <div class="profile-content">
            <!-- Main content -->           
            <div class="profile-body profile-body-cus">
                @include('backend.partials.default_table')
                <div class="container-fluid table-filter-container border-radius-5 p-imp-30 d-none">
                    <div class="row align-items-center mb-15">
                        <div class="col-sm-6">
                            <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                        </div>
                        <div class="col-sm-6">
                        </div>
                    </div>
                    <div class="row align-items-center mb-30">
                    </div>

                    <div class="row dataTable-btButtons">
                        <div class="col-lg-12">
                            <table id="table"
                                class="table card-table table-vcenter datatable mb-0 w-100 visit_report_table">
                                <thead>
                                    <tr>
                                        <th>{{ _trans('common.Employee') }}</th>
                                        <th>{{ _trans('common.Date') }}</th>
                                        <th>{{ _trans('common.Tile') }}</th>
                                        <th>{{ _trans('common.Description') }}</th>
                                        <th>{{ _trans('common.Cancellation note') }}</th>
                                        <th>{{ _trans('common.File') }}</th>
                                        <th>{{ _trans('common.Status') }}</th>
                                        <th>{{ _trans('common.Action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <input type="text" hidden id="visit_report_table_url" value="{{ route('staff.authUserTable', 'visit') }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
    <script src="{{ url('public/backend/js/pages/__commonUser.js') }}"></script>
@endsection
