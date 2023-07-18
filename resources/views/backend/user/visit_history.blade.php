@extends('backend.layouts.app')
@section('title', @$data['title']) 
@section('content')
    <div class="content-wrapper dashboard-wrapper mt-30 has-table-with-td">
        @include('backend.partials.user_navbar')
        <!-- Main content -->
        <section class="content p-0">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
             
                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <table id="table" class="table card-table table-vcenter datatable mb-0 w-100 profile_visit_table">
                            <thead>
                            <tr>
                                <th>{{ _trans('common.Title') }}</th>
                                <th>{{ _trans('common.Date') }}</th>
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
    <input type="hidden" name="" id="profile_visit_data_url" value="{{ route('user.visitHistoryDatatable') }}">

@endsection

@section('script')
    @include('backend.partials.datatable')
@endsection
