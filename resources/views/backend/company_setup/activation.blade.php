@extends('backend.layouts.app')
@section('title', @$data['title'])


@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
    <div class="table-content table-basic">
        <div class="card">
            <div class="card-body">
                <div class="">
                    <div class="vertical-tab">
                        <div class="row no-gutters mt-4">
                            <div class="col-md-12 pl-md-3 pt-md-0 pt-sm-4 pt-4">
                                <div class="tab-content px-primary">
                                    <div id="General" class="tab-pane active  ">

                                        <div class="content py-primary">
                                            <div id="General-0">
                                                <form action="{{ route('company.settings.update') }}" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf

                                                    <div class="form-group btn_odd_row pt-0 px-3 mb-10">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">
                                                                        {{ _trans('settings.Time format') }}
                                                                    </label>
                                                                    <div class="col-sm-6">
                                                                        <div>
                                                                            <div class="radio-button-group">
                                                                                <div data-toggle="buttons"
                                                                                    class="btn-group btn-group-toggle">
                                                                                    @php
                                                                                        $active = @$data['configs']['time_format'] === 'h';
                                                                                        $inactive = @$data['configs']['time_format'] === 'H';
                                                                                    @endphp
                                                                                    <label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch border-end-0',
                                                                                            'radio-active' => $active,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="time_format"
                                                                                            id="appSettings_time_format-0"
                                                                                            value="h"
                                                                                            {{ $active ? 'checked' : '' }}>
                                                                                        <span>
                                                                                            {{ _trans('settings.12 HOURS') }}</span></label><label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch',
                                                                                            'radio-active' => $inactive,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="time_format"
                                                                                            id="appSettings_time_format-1"
                                                                                            value="H"
                                                                                            {{ $inactive ? 'checked' : '' }}>
                                                                                        <span>{{ _trans('settings.24 HOURS') }}</span></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group btn_even_row pt-0 px-3  mb-10">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group row"><label
                                                                        class="col-sm-6 col-form-label">
                                                                        {{ _trans('settings.IP Address Bind') }}
                                                                    </label>
                                                                    <div class="col-sm-6">
                                                                        <div>
                                                                            <div class="radio-button-group">
                                                                                <div data-toggle="buttons"
                                                                                    class="btn-group btn-group-toggle">
                                                                                    @php
                                                                                        $active = @$data['configs']['ip_check'] === '1';
                                                                                        $inactive = @$data['configs']['ip_check'] === '0';
                                                                                    @endphp
                                                                                    <label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch border-end-0',
                                                                                            'radio-active' => $active,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="ip_check"
                                                                                            id="appSettings_ip_check-0"
                                                                                            value="1"
                                                                                            {{ $active ? 'checked' : '' }}>
                                                                                        <span>{{ _trans('settings.Enable') }}</span></label><label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch border-start-0',
                                                                                            'radio-active' => $inactive,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="ip_check"
                                                                                            id="appSettings_ip_check-1"
                                                                                            value="0"
                                                                                            {{ $inactive ? 'checked' : '' }}>
                                                                                        <span>{{ _trans('settings.Disable') }}</span></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group btn_odd_row pt-0 px-3 mb-10">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group row"><label
                                                                        class="col-sm-6 col-form-label">
                                                                        {{ _trans('settings.Location Bind') }}
                                                                    </label>
                                                                    <div class="col-sm-6">
                                                                        <div>
                                                                            <div class="radio-button-group">
                                                                                <div data-toggle="buttons"
                                                                                    class="btn-group btn-group-toggle">
                                                                                    @php
                                                                                        $active = @$data['configs']['location_check'] === '1';
                                                                                        $inactive = @$data['configs']['location_check'] === '0';
                                                                                    @endphp
                                                                                    <label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch border-end-0',
                                                                                            'radio-active' => $active,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="location_check"
                                                                                            value="1"
                                                                                            {{ $active ? 'checked' : '' }}>
                                                                                        <span>{{ _trans('settings.Enable') }}</span></label><label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch border-start-0',
                                                                                            'radio-active' => $inactive,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="location_check"
                                                                                            value="0"
                                                                                            {{ $inactive ? 'checked' : '' }}>
                                                                                        <span>{{ _trans('settings.Disable') }}</span></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group btn_even_row pt-0 px-3 mb-10">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">
                                                                        {{ _trans('settings.Multiple Check In & Check Out') }}
                                                                    </label>
                                                                    <div class="col-sm-6">
                                                                        <div>
                                                                            <div class="radio-button-group">
                                                                                <div data-toggle="buttons"
                                                                                    class="btn-group btn-group-toggle">
                                                                                    @php
                                                                                        $active = @$data['configs']['multi_checkin'] === '1';
                                                                                        $inactive = @$data['configs']['multi_checkin'] === '0';
                                                                                    @endphp
                                                                                    <label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch border-end-0',
                                                                                            'radio-active' => $active,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="multi_checkin"
                                                                                            id="appSettings_multi_checkin-0"
                                                                                            value="1"
                                                                                            {{ $active ? 'checked' : '' }}>
                                                                                        <span>
                                                                                            {{ _trans('settings.Enable') }}</span></label><label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch border-start-0',
                                                                                            'radio-active' => $inactive,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="multi_checkin"
                                                                                            id="appSettings_multi_checkin-1"
                                                                                            value="0"
                                                                                            {{ $inactive ? 'checked' : '' }}>
                                                                                        <span>
                                                                                            {{ _trans('settings.Disable') }}</span></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group btn_odd_row pt-0 px-3 mb-10">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group row"><label
                                                                        class="col-sm-6 col-form-label">
                                                                        {{ _trans('settings.Live Tracking') }}
                                                                    </label>
                                                                    <div class="col-sm-6">
                                                                        <div>
                                                                            <div class="radio-button-group">
                                                                                <div data-toggle="buttons"
                                                                                    class="btn-group btn-group-toggle">
                                                                                    @php
                                                                                        $active = @$data['configs']['location_service'] === '1';
                                                                                        $inactive = @$data['configs']['location_service'] === '0';
                                                                                    @endphp
                                                                                    <label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch',
                                                                                            'radio-active' => $active,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="location_service"
                                                                                            id="location_service-0"
                                                                                            value="1"
                                                                                            {{ $active ? 'checked' : '' }}>
                                                                                        <span>
                                                                                            {{ _trans('settings.Enable') }}</span></label><label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch',
                                                                                            'radio-active' => $inactive,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="location_service"
                                                                                            id="location_service-1"
                                                                                            value="0"
                                                                                            {{ $inactive ? 'checked' : '' }}>
                                                                                        <span>
                                                                                            {{ _trans('settings.Disable') }}</span></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group btn_odd_row pt-0 px-3 mb-10">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group row"><label
                                                                        class="col-sm-6 col-form-label">
                                                                        {{ _trans('settings.Auto Generate User Password') }}
                                                                    </label>
                                                                    <div class="col-sm-6">
                                                                        <div>
                                                                            <div class="radio-button-group">
                                                                                <div data-toggle="buttons"
                                                                                    class="btn-group btn-group-toggle">
                                                                                    @php
                                                                                        $active = @$data['configs']['user_pass_auto_generate'] === '1';
                                                                                        $inactive = @$data['configs']['user_pass_auto_generate'] === '0';
                                                                                    @endphp
                                                                                    <label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch',
                                                                                            'radio-active' => $active,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="user_pass_auto_generate"
                                                                                            id="user_pass_auto_generate-0"
                                                                                            value="1"
                                                                                            {{ $active ? 'checked' : '' }}>
                                                                                        <span>
                                                                                            {{ _trans('settings.Enable') }}</span></label><label
                                                                                        @class([
                                                                                            'btn border radio-checkbox-switch',
                                                                                            'radio-active' => $inactive,
                                                                                        ])><input
                                                                                            class="d-none" type="radio"
                                                                                            name="user_pass_auto_generate"
                                                                                            id="user_pass_auto_generate-1"
                                                                                            value="0"
                                                                                            {{ $inactive ? 'checked' : '' }}>
                                                                                        <span>
                                                                                            {{ _trans('settings.Disable') }}</span></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-warning mt-3 text-center">
                                                                            ({{ _trans('message.If you enable this option, the system will automatically generate a password for the user and send it to employee via email.') }})
                                                                        </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        @if (hasPermission('company_settings_update'))
                                                            <div class="d-flex justify-content-end">

                                                                <button class="crm_theme_btn mr-2"><span class="w-100">
                                                                    </span> {{ _trans('common.Save') }}
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="currencyInfo" value="{{ route('company.settings.currencyInfo') }}">
@endsection
@section('script')
    <script src="{{ url('public/backend/js/image_preview.js') }}"></script>
   
@endsection
