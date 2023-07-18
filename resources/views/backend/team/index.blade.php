@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    <div class="content-wrapper dashboard-wrapper mt-30">


        <!-- Main content -->
        <section class="content p-0 ">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30 ">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
                    <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                    <div class="d-flex align-items-center flex-wrap">
                     
                        <x-date-picker :label="'Date Range'" />
                        <div class="form-group mb-2 mr-2">
                            <button type="submit"
                                class="btn btn-primary notice_table_form">{{ _trans('common.Submit') }}</button>
                        </div>
                        <a href="{{ route('team.create') }}"
                            class="btn btn-sm btn-primary mb-2">{{ _trans('common.Add team') }}</a>
                    </div> 
                </div>
                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <table id="table" class="table card-table table-vcenter datatable mb-0 w-100 team_table">
                            <thead>
                                <tr>
                                    {{ _trans('common.Name') }}
                                    <th>{{ _trans('common.Team Lead') }}</th>
                                    <th>{{ _trans('common.Number of Persons') }}</th>
                                    <th>{{ _trans('common.Status') }}</th>
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
    <input type="text" hidden id="team_data_url" value="{{ route('team.dataTable') }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
@endsection
