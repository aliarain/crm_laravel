@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
@include('backend.partials.staff_navbar')
<div class="profile-content">
        <!-- Main content -->
        <section class="profile-body profile-body-cus">
            @include('backend.partials.default_table')
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30 d-none">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap parent-select2-width">
                    <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                    <div class="d-flex align-items-center flex-wrap">
                        
                        
                    </div>
                   
                </div>

                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="table"
                                    class="table card-table table-vcenter datatable mb-0 w-100 project_table">
                                    <thead>
                                        <tr>
                                            <th>{{ _trans('common.ID') }}</th>
                                            <th>{{ _trans('project.Project Name') }}</th>
                                            <th>{{ _trans('project.Client') }}</th>
                                            <th>{{ _trans('project.Priority') }}</th>
                                            <th>{{ _trans('common.Start Date') }}</th>
                                            <th>{{ _trans('project.Deadline') }}</th>
                                            <th>{{ _trans('project.Members') }}</th>
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
    <input type="text" hidden id="project_report_data_url" value="{{ route('staff.authUserTable','project') }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
@endsection
