@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    <div class="content-wrapper dashboard-wrapper mt-30 has-table-with-td">
        @include('backend.partials.user_navbar')
        <!-- Main content -->
        <section class="content p-0">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
                @if(hasPermission('leave_request_read'))
                {{-- <form action="{{ route('leaveRequest.profileDataTable') }}" method="get"> --}}
                <div class="row align-items-end mb-30 table-filter-data mb-3">

                    <div class="col-lg-3">
                        <x-date-picker :label="'Date Range'"/>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group mb-2">
                            <button type="submit" class="btn btn-primary profile_notice_table_search_form">{{ _trans('common.Submit') }}</button>
                        </div>
                    </div>
                    <div class="col-lg-6 text-right">
                        <div class="form-group">
                            {{-- <a href="javascript:void(0)" onclick="clearNotice()" class="btn btn-primary ">Clear All</a> --}}
                        </div>
                    </div>

                </div>
            {{-- </form> --}}
                @endif
                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <table id="table" class="table card-table table-vcenter datatable mb-0 w-100 profile_notice_table">
                            <thead>
                            <tr>
                                <th>{{ _trans('common.Date') }}</th>
                                <th>{{ _trans('common.Department') }}</th>
                                <th>{{ _trans('common.Subject') }}</th>
                                <th>{{ _trans('common.Description') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>


    <input type="hidden" name="department_id" id="department_id" value="{{ $data['user']->department_id}}">
    <input type="hidden" name="" id="profile_notice_data_url" value="{{ route('user.noticeDatatable') }}">

@endsection

@section('script')
    @include('backend.partials.datatable')
    <script src="{{url('public/backend/js/pages/__profile.js')}}"></script>
@endsection
