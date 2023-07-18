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
                        @include('backend.partials.tablePartial')
                    </div>
                </div>
            </div>
        </section>
    </div>
    <input type="text" hidden id="{{ @$data['url_id'] }}" value="{{ @$data['table'] }}">
@endsection
@section('script')
<script src="{{ asset('public/backend/js/pages/__task.js') }}"></script>
@endsection
