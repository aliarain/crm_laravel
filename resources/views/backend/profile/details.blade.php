@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    @include('backend.profile.partials.new_tab')

    <div class="profile-content">
        <div class="d-flex flex-column flex-lg-row gap-4 gap-lg-0">
            @if (url()->current() === route('user.authProfile', ['personal']))
                <!-- profile body start -->
                @include('backend.profile.partials.info')
            @elseif (url()->current() === route('user.authProfile', ['settings']))
                <!-- profile body start -->
                @include('backend.profile.partials.settings')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['contract']))
                <!-- profile body start -->
                @include('backend.profile.partials.contract')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['attendance']))
                <!-- profile body start -->
                @include('backend.profile.partials.attendance')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['notice']))
                <!-- profile body start -->
                @include('backend.profile.partials.notice')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['leave_request']))
                <!-- profile body start -->
                @include('backend.profile.partials.leave_request')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['visit']))
                <!-- profile body start -->
                @include('backend.profile.partials.visit')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['phonebook']))
                <!-- profile body start -->
                @include('backend.profile.partials.phonebook')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['appointment']))
                <!-- profile body start -->
                @include('backend.profile.partials.appointment')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['ticket']))
                <!-- profile body start -->
                @include('backend.profile.partials.ticket')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['advance']))
                <!-- profile body start -->
                @include('backend.profile.partials.advance')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['commission']))
                <!-- profile body start -->
                @include('backend.profile.partials.commission')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['salary']))
                <!-- profile body start -->
                @include('backend.profile.partials.salary')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['project']))
                <!-- profile body start -->
                @include('backend.profile.partials.project')
            @elseif (url()->current() === route('user.authProfile', ['task']))
                @include('backend.profile.partials.tasks')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['award']))
                @include('backend.profile.partials.award')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.authProfile', ['travel']))
                @include('backend.profile.partials.travel')
                <!-- profile body form end -->
            @endif
        </div>
        <!-- profile body end -->

    </div>

@endsection
@section('script')
    @include('backend.partials.table_js')
@endsection
