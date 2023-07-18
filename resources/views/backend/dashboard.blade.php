@extends('backend.layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="d-flex justify-content-between flex-wrap dashboard-heading  align-items-center pb-24 gap-3">
    <h3 class="mb-0 dashboard_welcome_title">{{ _trans('common.Welcome to') }} {{ config('settings.app.company_name') }}
        [{{ Auth::user()->name }}]</h3>
    {{-- dropdown menu --}}
    <div class="dropdown card-button">
        <button class="ot-dropdown-btn dropdown-toggle" type="button" id="revenueBtn" data-bs-toggle="dropdown"
            aria-expanded="false">
            <span id="__selected_dashboard">
                @if (auth()->user()->role->slug == 'admin')
                {{ _trans('common.Company Dashboard') }}
                @else
                {{ _trans('common.My Dashboard') }}
                @endif
            </span>
            <i class="las la-angle-down"></i>
        </button>
        <ul class="dropdown-menu c-dropdown-menu" aria-labelledby="revenueBtn">
            <li>
                <a class="dropdown-item profile_option" onclick="dashboardAdmin('My Dashboard')"
                    href="javascript:void(0)">{{ _trans('common.My Dashboard') }}
                    <input type="hidden" id="my_dashboard_used_for_translation" value="{{ _trans('common.My Dashboard') }}">
                </a>
                @if (auth()->user()->role->slug == 'superadmin')
                <a class="dropdown-item super_dashboard" onclick="dashboardAdmin('Superadmin Dashboard')"
                    href="javascript:void(0)">{{ _trans('common.Dashboard') }}
                    <input type="hidden" id="super_dashboard_used_for_translation"
                        value="{{ _trans('common.My Dashboard') }}">
                </a>
                @endif
                @if (auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'superadmin')
                <a class="dropdown-item company_option" onclick="dashboardAdmin('Company Dashboard')"
                    href="javascript:void(0)">{{ _trans('common.Company Dashboard') }}</a>
                    <input type="hidden" id="company_dashboard_used_for_translation" value="{{ _trans('common.Company Dashboard') }}">
                @endif
            </li>

        </ul>
    </div>
</div>
<div class="content p-0 mt-4">
    <!-- SCROLLBAR_EVERY_TABLE  -->

    <div class="__MyProfileDashboardView" id="__MyProfileDashboardView"></div>
</div>
<input type="hidden" id="user_slug" value="{{ auth()->user()->role->slug }}">
<input type="hidden" id="profileWiseDashboard" value="{{ route('dashboard.profileWiseDashboard') }}">
@endsection
@section('script')
<script src="{{ asset('public/backend/js/fs_d_ecma/chart/echarts.min.js') }}"></script>
<script type="module" src="{{ asset('public/backend/js/fs_d_ecma/components/dashboard.js') }}"></script>
@endsection