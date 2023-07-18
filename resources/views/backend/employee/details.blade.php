@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    @include('backend.employee.partials.new_tab')
    <div class="profile-content">
        <div class="d-flex flex-column flex-lg-row gap-4 gap-lg-0">
            {{-- @include('backend.employee.tab') --}}
            @if (url()->current() === route('user.profile', [$data['id'], 'personal']))
                <!-- profile body start -->
                @include('backend.employee.partials.info')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'contract']))
                <!-- profile body start -->
                @include('backend.employee.partials.contract')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'attendance']))
                <!-- profile body start -->
                @include('backend.employee.partials.attendance')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'notice']))
                <!-- profile body start -->
                @include('backend.employee.partials.notice')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'leave_request']))
                <!-- profile body start -->
                @include('backend.employee.partials.leave_request')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'visit']))
                <!-- profile body start -->
                @include('backend.employee.partials.visit')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'phonebook']))
                <!-- profile body start -->
                @include('backend.employee.partials.phonebook')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'appointment']))
                <!-- profile body start -->
                @include('backend.employee.partials.appointment')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'ticket']))
                <!-- profile body start -->
                @include('backend.employee.partials.ticket')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'advance']))
                <!-- profile body start -->
                @include('backend.employee.partials.advance')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'commission']))
                <!-- profile body start -->
                @include('backend.employee.partials.commission')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'salary']))
                <!-- profile body start -->
                @include('backend.employee.partials.salary')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'project']))
                <!-- profile body start -->
                @include('backend.employee.partials.project')
            @elseif (url()->current() === route('user.profile', [$data['id'], 'task']))
                @include('backend.employee.partials.tasks')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'award']))
                @include('backend.employee.partials.award')
                <!-- profile body form end -->
            @elseif (url()->current() === route('user.profile', [$data['id'], 'travel']))
                @include('backend.employee.partials.travel')
                <!-- profile body form end -->
            @endif
        </div>
        <!-- profile body end -->

    </div>

@endsection
@section('script')
    @include('backend.partials.table_js')
@endsection
