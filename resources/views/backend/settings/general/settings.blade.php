@extends('backend.layouts.app')
@section('title', @$data['title'])


@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
    <div class="content-wrapper">
        @php
            $general_setting = request()->has('general_setting');
            $email_setup = request()->has('email_setup');
            $storage_setup = request()->has('storage_setup');
            $db_backup = request()->has('db_backup');
            $about_system = request()->has('about_system');
            
            $default_tab = '';
            if (!$general_setting && !$email_setup && !$storage_setup && !$db_backup && !$about_system) {
                $default_tab = 'active';
            } else {
                $default_tab = '';
            }
        @endphp
        <!-- Main content -->

    </div>
    <div class="table-basic table-content">
        <div class="card mb-3 ">
            <div class="card-body p-2">
                <ul class="nav ">
                    <li class="nav-item">
                        <a class="nav-link settings-nav link-secondary {{ $general_setting ? 'active' : '' }} {{ $default_tab }}"
                            id="general-tab" data-bs-toggle="tab" data-bs-target="#general"
                            href="#">{{ _trans('settings.General') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link settings-nav link-secondary {{ $email_setup ? 'active' : '' }}" id="email-tab"
                            data-bs-toggle="tab" data-bs-target="#email"
                            href="#">{{ _trans('settings.Email setup') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link settings-nav link-secondary {{ $storage_setup ? 'active' : '' }}"
                            id="storage-tab" data-bs-toggle="tab" data-bs-target="#storage"
                            href="#">{{ _trans('settings.Storage setup') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link settings-nav link-secondary {{ $db_backup ? 'active' : '' }}" id="db-backup-tab"
                            data-bs-toggle="tab" data-bs-target="#db-backup"
                            href="#">{{ _trans('settings.Database Backup') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link settings-nav link-secondary {{ $about_system ? 'active' : '' }}"
                            id="about_system-tab" data-bs-toggle="tab" data-bs-target="#about_system"
                            href="#">{{ _trans('settings.About System') }}</a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <div class="table-content table-basic">
        <div class="card">
            <div class="card-body">
                <div class="tab-content" id="tabContent">
                    <div id="general" class="tab-pane {{ $general_setting ? 'active' : '' }}  {{ $default_tab }}">
                        <div class="d-flex justify-content-between">
                            <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                General</h5>
                            <div class="d-flex align-items-center mb-0">
                            </div>
                        </div>
                        <hr>
                        <div class="content py-primary">
                            <div id="General-0">
                                <form action="{{ route('manage.settings.update') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <fieldset class="form-group mb-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row mb-3"><label class="col-sm-3 col-form-label">
                                                        {{ _trans('common.Name') }} <span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-sm-9">
                                                        <div><input type="text" name="company_name" id="company_name"
                                                                required="required"
                                                                placeholder="{{ _trans('settings.Type your company name') }}"
                                                                autocomplete="off"
                                                                class="form-control ot-form-control ot_input"
                                                                value="{{ config('settings.app')['company_name'] }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="col-sm-3 col-form-label h-fit-content">
                                                        {{ _trans('settings.Company logo backend (white)') }} <br>
                                                        <small
                                                            class="text-muted font-italic">{{ _trans('settings.(Recommended
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                size: 210 x 50 px)') }}</small></label>
                                                    <div class="col-sm-9">
                                                        <input id="appSettings_company_logo" type="file"
                                                            name="dark_logo"
                                                            class="form-control ot-form-control ot_input">
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3"><label
                                                        class="col-sm-3 col-form-label h-fit-content">
                                                        {{ _trans('settings.Company logo frontend (black)') }}
                                                        <br> <small
                                                            class="text-muted font-italic">{{ _trans('settings.(Recommended
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                size: 210 x 50 px)') }}</small></label>
                                                    <div class="col-sm-9">
                                                        <div>
                                                            <input id="appSettings_company_logo_black" type="file"
                                                                name="white_logo"
                                                                class="form-control ot-form-control ot_input">
                                                        </div>
                                                        <!---->
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3"><label
                                                        class="col-sm-3 col-form-label h-fit-content">
                                                        {{ _trans('settings.Company icon') }}<br>
                                                        <small
                                                            class="text-muted font-italic">{{ _trans('settings.(Recommended
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                size: 50 x 50 px)') }}
                                                        </small></label>
                                                    <div class="col-sm-9">
                                                        <div>
                                                            <input id="appSettings_company_icon" type="file"
                                                                name="company_icon"
                                                                class="form-control ot-form-control ot_input">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row mb-3"><label class="col-sm-3 col-form-label">
                                                        {{ _trans('settings.Android url') }}
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <div>
                                                            <input type="url"
                                                                class="form-control ot-form-control ot_input"
                                                                name="android_url"
                                                                value="{{ config('settings.app')['android_url'] }}"
                                                                placeholder="{{ _trans('common.Android url') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="col-sm-3 col-form-label">
                                                        {{ _trans('settings.Ios url') }}
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <div>
                                                            <input type="url"
                                                                class="form-control ot-form-control ot_input"
                                                                name="ios_url"
                                                                value="{{ config('settings.app')['ios_url'] }}"
                                                                placeholder="{{ _trans('settings.Ios url') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-3">
                                                    <label class="col-sm-3 col-form-label">
                                                        {{ _trans('settings.Site maintenance') }}
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <div>

                                                            <div class="radio-button-group">
                                                                <div data-toggle="buttons"
                                                                    class="btn-group btn-group-toggle">
                                                                    @php
                                                                        $active = config('settings.app')['site_under_maintenance'] === '1';
                                                                        $inactive = config('settings.app')['site_under_maintenance'] === '0';
                                                                    @endphp
                                                                    <label @class([
                                                                        'btn border radio-checkbox-switch',
                                                                        'radio-active' => $active,
                                                                    ])><input
                                                                            class="d-none" type="radio"
                                                                            name="site_under_maintenance"
                                                                            id="site_under_maintenance-0" value="1"
                                                                            {{ $active ? 'checked' : '' }}>
                                                                        <span>
                                                                            {{ _trans('settings.Enable') }}
                                                                        </span></label>
                                                                    <label @class([
                                                                        'btn border radio-checkbox-switch',
                                                                        'radio-active' => $inactive,
                                                                    ])><input
                                                                            class="d-none" type="radio"
                                                                            name="site_under_maintenance"
                                                                            id="site_under_maintenance-1" value="0"
                                                                            {{ $inactive ? 'checked' : '' }}>
                                                                        <span>{{ _trans('settings.Disable') }}</span></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            @if (hasPermission('general_settings_update'))
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
                    <div id="email" class="tab-pane fade {{ $email_setup ? 'active' : '' }}">
                        <div class="d-flex justify-content-between">
                            <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                {{ _trans('settings.Email setup [SMTP]') }}</h5>
                            <div class="d-flex align-items-center mb-0">
                                <!---->
                            </div>
                        </div>
                        <hr>
                        <div class="content py-primary">
                            <form action="{{ route('manage.settings.update.email') }}" method="post" class="mb-0">
                                @csrf
                                <input type="text" value="smtp" name="MAIL_MAILER" hidden>
                                <div class="form-group row align-items-center mb-3"><label for="emailSettingsFromName"
                                        class="col-lg-3 col-xl-3 mb-lg-0">
                                        {{ _trans('settings.MAIL HOST') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div><input type="text" name="MAIL_HOST" id="emailSettingsFromName"
                                                required="required" placeholder="{{ _trans('settings.MAIL HOST') }}"
                                                autocomplete="off" value="{{ env('MAIL_HOST') }}"
                                                class="form-control ot-form-control ot_input">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center mb-3"><label for="emailSettingsFromName"
                                        class="col-lg-3 col-xl-3 mb-lg-0">
                                        {{ _trans('settings.MAIL PORT') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div><input type="text" name="MAIL_PORT" id="emailSettingsFromName"
                                                required="required" placeholder="{{ _trans('settings.MAIL PORT') }}"
                                                autocomplete="off" value="{{ env('MAIL_PORT') }}"
                                                class="form-control ot-form-control ot_input">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center mb-3"><label for="emailSettingsFromName"
                                        class="col-lg-3 col-xl-3 mb-lg-0">
                                        {{ _trans('settings.MAIL USERNAME') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div><input type="text" name="MAIL_USERNAME" id="emailSettingsFromName"
                                                required="required" placeholder="{{ _trans('settings.MAIL USERNAME') }}"
                                                autocomplete="off" value="{{ env('MAIL_USERNAME') }}"
                                                class="form-control ot-form-control ot_input">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center mb-3"><label for="emailSettingsFromName"
                                        class="col-lg-3 col-xl-3 mb-lg-0">
                                        {{ _trans('settings.MAIL FROM ADDRESS') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div><input type="text" name="MAIL_FROM_ADDRESS" id="emailSettingsFromName"
                                                required="required"
                                                placeholder="{{ _trans('settings.MAIL FROM ADDRESS') }}"
                                                autocomplete="off" value="{{ env('MAIL_FROM_ADDRESS') }}"
                                                class="form-control ot-form-control ot_input ">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center mb-3"><label for="emailSettingsFromName"
                                        class="col-lg-3 col-xl-3 mb-lg-0">
                                        {{ _trans('settings.MAIL PASSWORD') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div><input type="text" name="MAIL_PASSWORD" id="emailSettingsFromName"
                                                required="required" placeholder="{{ _trans('settings.MAIL PASSWORD') }}"
                                                autocomplete="off" value="{{ env('MAIL_PASSWORD') }}"
                                                class="form-control ot-form-control ot_input">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center mb-3"><label for="emailSettingsFromName"
                                        class="col-lg-3 col-xl-3 mb-lg-0">
                                        {{ _trans('settings.MAIL ENCRYPTION') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div><input type="text" name="MAIL_ENCRYPTION" id="emailSettingsFromName"
                                                required="required"
                                                placeholder="{{ _trans('settings.MAIL ENCRYPTION') }}" autocomplete="off"
                                                value="{{ env('MAIL_ENCRYPTION') }}"
                                                class="form-control  ot-form-control ot_input">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center mb-3"><label for="emailSettingsFromName"
                                        class="col-lg-3 col-xl-3 mb-lg-0">
                                        {{ _trans('settings.MAIL FROM NAME') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div><input type="text" name="MAIL_FROM_NAME" id="emailSettingsFromName"
                                                required="required" placeholder="{{ _trans('settings.MAIL FROM NAME') }}"
                                                autocomplete="off" value="{{ env('MAIL_FROM_NAME') }}"
                                                class="form-control ot-form-control ot_input">
                                            <!---->
                                        </div>
                                    </div>
                                </div>
                                <!---->
                                <div class="d-flex justify-content-end">
                                    <button class="crm_theme_btn mr-2">
                                        {{ _trans('common.Save') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="storage" class="tab-pane fade {{ $storage_setup ? 'active' : '' }}">
                        <div class="d-flex justify-content-between">
                            <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                {{ _trans('settings.Storage setup') }}</h5>
                            <div class="d-flex align-items-center mb-0">
                                <!---->
                            </div>
                        </div>
                        <hr>
                        <div class="content py-primary">
                            <form action="{{ route('manage.settings.update.storage') }}" method="post" class="mb-0">
                                @csrf
                                <div class="form-group row align-items-center"><label for="emailSettingsProvider"
                                        class="col-lg-3 col-xl-3 mb-lg-0">
                                        {{ _trans('settings.Default storage') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="form-group">
                                            <select name="FILESYSTEM_DRIVER" id="select_storage" class="form-control"
                                                required>
                                                <option value="local">{{ _trans('common.Local') }}</option>
                                                <option value="s3">{{ _trans('common.S3') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="s3_section d-none">
                                    <div class="form-group row align-items-center"><label for="emailSettingsFromName"
                                            class="col-lg-3 col-xl-3 mb-lg-0">
                                            {{ _trans('common.AWS ACCESS KEY ID') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9 col-xl-9">
                                            <div><input type="text" name="AWS_ACCESS_KEY_ID"
                                                    id="emailSettingsFromName" required="required"
                                                    placeholder="{{ _trans('common.AWS ACCESS KEY ID') }}" autocomplete="off"
                                                    value="{{ env('AWS_ACCESS_KEY_ID') }}" class="form-control ">
                                                <!---->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center"><label for="emailSettingsFromEmail"
                                            class="col-lg-3 col-xl-3 mb-lg-0">
                                            {{ _trans('common.AWS SECRET ACCESS KEY') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9 col-xl-9">
                                            <div><input type="text" name="AWS_SECRET_ACCESS_KEY"
                                                    value="{{ env('AWS_SECRET_ACCESS_KEY') }}" required="required"
                                                    placeholder="{{ _trans('common.AWS SECRET ACCESS KEY') }}" autocomplete="off"
                                                    class="form-control ">
                                                <!---->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center"><label for="emailSettingsFromEmail"
                                            class="col-lg-3 col-xl-3 mb-lg-0">
                                            {{ _trans('common.AWS DEFAULT REGION') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9 col-xl-9">
                                            <div><input type="text" name="AWS_DEFAULT_REGION"
                                                    value="{{ env('AWS_DEFAULT_REGION') }}" required="required"
                                                    placeholder="{{ _trans('common.AWS DEFAULT REGION') }}" autocomplete="off"
                                                    class="form-control ">
                                                <!---->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center"><label for="emailSettingsFromEmail"
                                            class="col-lg-3 col-xl-3 mb-lg-0">
                                            {{ _trans('common.AWS BUCKET') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9 col-xl-9">
                                            <div><input type="text" name="AWS_BUCKET" value="{{ env('AWS_BUCKET') }}"
                                                    required="required" placeholder="{{ _trans('common.AWS BUCKET') }}" autocomplete="off"
                                                    class="form-control ">
                                                <!---->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center"><label for="emailSettingsFromEmail"
                                            class="col-lg-3 col-xl-3 mb-lg-0">
                                            {{ _trans('common.AWS ENDPOINT') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9 col-xl-9">
                                            <div><input type="text" name="AWS_ENDPOINT"
                                                    value="{{ env('AWS_ENDPOINT') }}" required="required"
                                                    placeholder="{{ _trans('common.AWS ENDPOINT') }}" autocomplete="off" class="form-control ">
                                                <!---->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!---->
                                <div class="d-flex justify-content-end mt-3">
                                    <button class="crm_theme_btn mr-2">
                                        {{ _trans('common.Save') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="db-backup" class="tab-pane fade {{ $db_backup ? 'active' : '' }}">
                        <div class="d-flex justify-content-between">
                            <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                {{ _trans('settings.Database backup') }} </h5>
                            <div class="d-flex align-items-center mb-0">
                                <a href="{{ route('database.export') }}"
                                    class="btn btn-sm btn-gradian">{{ _trans('settings.Backup Database') }}</a>
                            </div>
                        </div>

                        <hr>
                        <div class="content py-primary">
                            <div class="dataTable-btButtons">
                                <table id="table" class="table card-table table-vcenter datatable mb-0 w-100">
                                    <thead>
                                        <tr>
                                            <td>{{ _trans('common.File name') }}</td>
                                            <td>{{ _trans('common.Action') }}</td>
                                        </tr>
                                    </thead>
                                    <tbody id="__database_tbody">
                                        @foreach ($data['databases'] as $key => $item)
                                            <tr>
                                                <td>{{ $item->path }}</td>
                                                <td>
                                                    <div class="flex-nowrap">
                                                        <div class="dropdown position-static">
                                                            <button
                                                                class="btn btn-white dropdown-toggle align-text-top action-dot-btn"
                                                                data-boundary="viewport" data-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a target="_blank" href="{{ url($item->path) }}"
                                                                    class="dropdown-item" onclick="">
                                                                    {{ _trans('common.Download') }}
                                                                </a>
                                                                <a href="{{ route('database.destroy', $item->id) }}"
                                                                    class="dropdown-item" onclick="">
                                                                    {{ _trans('common.Delete') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div id="about_system" class="tab-pane fade ">
                        <div class="d-flex justify-content-between">
                            <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                {{ _trans('settings.About System') }} </h5>

                        </div>

                        <hr>
                        <div class="col-lg-6">
                            <div class="form-group row align-items-center"><label for="emailSettingsFromEmail"
                                    class="col-lg-3 col-xl-3 mb-lg-0">
                                    {{ _trans('common.Version') }}
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <div>
                                        <span class="text-muted">
                                            {{ @aboutSystem()['version'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row align-items-center"><label for="emailSettingsFromEmail"
                                    class="col-lg-3 col-xl-3 mb-lg-0">
                                    {{ _trans('common.Release Date') }}
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <div>
                                        <span class="text-muted">
                                            {{ showDate(@aboutSystem()['release_date']) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script src="{{ url('public/backend/js/image_preview.js') }}"></script>
    @endsection
