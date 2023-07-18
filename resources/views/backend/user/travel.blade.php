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
                    @if (hasPermission('travel_create'))
                        <div class="form-group mr-2 mb-2">
                            <a href="{{ route('travel.create') }}" class="btn btn-primary ">
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
<input type="text" hidden id="{{ @$data['url_id'] }}" value="{{ @$data['table'] }}">
@endsection
@section('script')
<script src="{{ asset('public/backend/js/pages/__travel.js') }}"></script>
@endsection