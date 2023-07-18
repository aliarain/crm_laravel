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
<input type="text" hidden id="award_table_url" value="{{ route('user.profileDataTable',['user_id'=>$data['id'],'type'=>'award']) }}">
@endsection
@section('script')
<script src="{{ asset('public/backend/js/pages/__award.js') }}"></script>
@endsection