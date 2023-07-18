@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

<div class="content-wrapper dashboard-wrapper mt-30">

    <!-- Main content -->
    <section class="content p-0">
        @include('backend.partials.user_navbar')
        <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap parent-select2-width">
                <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                <div class="d-flex align-items-center flex-wrap">
                    @if (hasPermission('advance_salaries_search'))
                    <div class="form-group mb-2 mr-2">
                        <input type="date" id="start" name="start_date" class="form-control"
                            value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <select name="status" id="status" class="form-control select2 custom-select-width">
                            <option value="0">{{ _trans('common.Status') }}</option>
                            <option value="2">{{ _trans('common.Pending') }}</option>
                            <option value="5">{{ _trans('common.Approve') }}</option>
                            <option value="6">{{ _trans('common.Reject') }}</option>
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <select name="payment" id="payment" class="form-control select2 custom-select-width">
                            <option value="0">{{ _trans('common.Payment Status') }}</option>
                            <option value="9">{{ _trans('common.Unpaid') }}</option>
                            <option value="8">{{ _trans('common.Paid') }}</option>
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <button type="button" class="btn btn-primary advance_table_form">{{ _trans('common.Search')
                            }}</button>
                    </div>
                    @endif
                    @if (hasPermission('task_create'))
                    <div class="form-group mr-2 mb-2">
                        <a href="{{ route('task.create') }}" class="btn btn-primary ">
                            <i class="fa fa-plus-square pr-2"></i> {{ _trans('common.Create') }}</a>
                    </div>
                    @endif
                </div>

            </div>

            <div class="row dataTable-btButtons">
                <div class="col-lg-12">
                    @include('backend.partials.tablePartial')
                </div>
            </div>
        </div>
    </section>

</div>
<input type="text" hidden id="{{ @$data['url_id'] }}"
    value="{{ route('user.profileDataTable',['user_id'=>$data['id'],'type'=>'tasks']) }}">
@endsection
@section('script')
<script src="{{ asset('public/backend/js/pages/__task.js') }}"></script>
@endsection