@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ @$data['title'] }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ @$data['title'] }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            {{-- Start profile overview --}}
                            <div class="col-md-6">
                                <div class="card card-with-shadow border-0">
                                    <div class="px-primary py-primary">
                                        <h4>{{ _trans('common.Employee Info') }}</h4>
                                        <hr>
                                        <div id="General-0">
                                            <fieldset class="form-group mb-5">
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="form-group row">
                                                            <label
                                                                class="col-sm-3 text-capitalize">{{ _trans('common.Employee Name') }}</label>
                                                            <div class="col-sm-9">
                                                                <img class="employee-avater-img"
                                                                    src="{{ uploaded_asset($data['show']->avatar_id) }}"
                                                                    alt="" height="50" width="50">
                                                                {{ $data['show']->name }}
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-sm-3 text-capitalize">{{ _trans('common.Designation') }}</label>
                                                            <div class="col-sm-9">
                                                                {{ $data['show']->designation->title }}
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-sm-3 text-capitalize">{{ _trans('common.Department') }}</label>
                                                            <div class="col-sm-9">
                                                                {{ $data['show']->department->title }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- End profile overview --}}

                            {{-- Start summary overview --}}
                            <div class="col-md-6">
                                <div class="card card-with-shadow border-0">
                                    <div class="px-primary py-primary">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {{ _trans('common.Summary') }}
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="month" class="form-control selected_month"
                                                        onchange="getSummary()" name="month" value="{{ date('Y-m') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        {{-- Summary  start --}}
                                        <div id="attendance_summary">
                                        </div>
                                        {{-- Summary end --}}
                                    </div>
                                </div>
                            </div>
                            {{-- End summary overview --}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="table"
                            class="table card-table table-vcenter datatable mb-0 w-100 single_attendance_report_table">
                            <thead>
                                <tr>
                                    <th>{{ _trans('common.Date') }}</th>
                                    <th>{{ _trans('attendance.Check in') }}</th>
                                    <th>{{ _trans('attendance.Check Out') }}</th>
                                    <th>{{ _trans('attendance.Break') }}</th>
                                    <th>{{ _trans('attendance.Break Duration') }}</th>
                                    <th>{{ _trans('attendance.Hours') }}</th>
                                    <th>{{ _trans('attendance.Overtime') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <input type="text" hidden id="single_attendance_report_data_url"
        value="{{ route('singleAttendanceReport.dataTable', $data['show']->id) }}">
    <input type="text" hidden id="singleAttendanceSummaryReport"
        value="{{ route('singleAttendanceSummaryReport', $data['show']->id) }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
    <script src="{{ asset('public/backend/js/attendance_summary_report.js') }}"></script>
@endsection
