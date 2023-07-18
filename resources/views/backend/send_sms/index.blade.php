@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    <div class="content-wrapper dashboard-wrapper mt-30">
        <!-- Main content -->
        <section class="content  ">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30 ">
                <!-- section 01  -->
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex">
                        <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                    </div>
                    <div class="d-flex flex-wrap">
                        @if (hasPermission('notice_list'))
                            <x-date-picker :label="'Date Range'" />

                            <div class="form-group mb-2 mr-2">
                                <button type="submit"
                                    class="btn btn-primary notice_table_form">{{ _trans('common.Submit') }}</button>
                            </div>
                        @endif
                        @if (hasPermission('notice_create'))
                            <a href="{{ route('sms.create') }}"
                                class="btn btn-sm btn-primary  mb-2">{{ _trans('common.Send SMS') }}</a>
                        @endif
                    </div>
                </div>
                <!-- section 03  -->
                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <table id="table" class="table card-table table-vcenter datatable mb-0 w-100 notice_table">
                            <thead>
                                <tr>
                                    <th>{{ _trans('common.Date') }}</th>
                                     <th>{{ _trans('common.Subject') }}</th>
                                    <th>{{ _trans('common.Department') }}</th>
                                    <th>{{ _trans('common.Description') }}</th>
                                    <th>{{ _trans('common.File') }}</th>
                                    <th>{{ _trans('common.Action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

    </div>
    </section>
    </div>

    <input type="text" hidden id="notice_data_url" value="{{ route('notice.dataTable') }}">
    <input type="text" hidden id="company_id" value="{{ auth()->user()->company_id }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
@endsection
