@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
@include('backend.partials.staff_navbar')
<div class="profile-content">
       
        <!-- Main content -->
        <div class="profile-body profile-body-cus">
            @include('backend.partials.default_table')
           
        </div>
</div>


    <input type="hidden" name="department_id" id="department_id" value="{{ auth()->user()->department_id}}">
    <input type="hidden" name="" id="profile_notice_data_url" value="{{ route('staff.authUserTable','notice') }}">

@endsection

@section('script')
    @include('backend.partials.datatable')
    <script src="{{url('public/backend/js/pages/__profile.js')}}"></script>
@endsection
