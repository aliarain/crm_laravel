@extends('backend.layouts.app')
@section('title', @$data['title']) 
@section('content')

    <div class="content-wrapper dashboard-wrapper mt-30">
        @include('backend.partials.user_navbar')

        <!-- Main content -->
        <section class="content p-0">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <table id="table" class="table card-table table-vcenter datatable mb-0 w-100 attendance_report_table">
                            <thead class="thead">
                            <tr>
                                
                                <th>{{ _trans('common.Employee') }}</th>
                                <th>{{ _trans('common.Department') }}</th>
                                 <th>{{ _trans('attendance.Check in') }}</th>
                                 <th>{{ _trans('attendance.Check Out') }}</th>
                                 <th>{{ _trans('attendance.Hours') }}</th>
                                 <th>{{ _trans('attendance.Overtime') }}</th>
                                <th>{{ _trans('common.Action') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>


    <input type="hidden" name="" id="attendance_report_data_url" value="{{ route('user.attendanceTable') }}">
    <input type="hidden" name="user_id" id="user_id" value="{{ $data['id']}}">

@endsection

@section('script')
    @include('backend.partials.datatable')
@endsection
