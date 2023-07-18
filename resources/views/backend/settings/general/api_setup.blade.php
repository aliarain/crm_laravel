@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper cus-content-wrapper">

        <!-- Main content -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="">
                    <div class="vertical-tab">
                        <div class="row no-gutters mt-4">
                            <div class="col-md-3 pr-md-3 tab-menu">
                                <div class="card card-with-shadow border-0">
                                    <div class="header-icon">
                                        <div class="icon-position d-flex justify-content-center">
                                            <div class="tab-icon d-flex justify-content-center align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-settings">
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                    <path
                                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-primary py-primary">
                                        <div role="tablist" aria-orientation="vertical" class="nav flex-column nav-pills"><a
                                                href="{{ route('company.settings.view') }}"
                                                class="text-capitalize tab-item-link d-flex justify-content-between my-2 my-sm-3 "><span>
                                                    {{ _trans('settings.Company Config') }}</span>
                                                <span class="active-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-chevron-right">
                                                        <polyline points="9 18 15 12 9 6"></polyline>
                                                    </svg></span></a>

                                        </div>
                                        <div role="tablist" aria-orientation="vertical" class="nav flex-column nav-pills"><a
                                                data-toggle="tab" href="{{ route('company.settings.locationApi') }}"
                                                class="text-capitalize tab-item-link d-flex justify-content-between my-2 my-sm-3 active"><span>{{ _trans('settings.API Setup') }}</span>
                                                <span class="active-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-chevron-right">
                                                        <polyline points="9 18 15 12 9 6"></polyline>
                                                    </svg></span></a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9 pl-md-3 pt-md-0 pt-sm-4 pt-4">
                                <div class="card card-with-shadow border-0">
                                    <div class="tab-content px-primary">
                                        <div id="General" class="tab-pane active  ">
                                            <div class="d-flex justify-content-between">
                                                <h5
                                                    class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                                    {{ $data['title'] }}</h5>
                                                <div class="d-flex align-items-center mb-0">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="content py-primary">
                                                <div id="General-0">
                                                    <div>
                                                        @csrf

                                                        <fieldset class="form-group">
                                                            <div class="row">
                                                                <legend class="col-2 col-form-label text-primary pt-0 mb-3">
                                                                   {{ _trans('common.Location Api') }}
                                                                </legend>
                                                                @php
                                                                    $barikoi_data = $data['company_apis']->where('name', 'barikoi')->first();
                                                                    $google_data = $data['company_apis']->where('name', 'google')->first();
                                                                @endphp
                                                                <div class="col-sm-8">
                                                                    <div>
                                                                        <div class="radio-button-group">
                                                                            <div data-toggle="buttons"
                                                                                class="btn-group btn-group-toggle">
                                                                                <label class="btn border"><input
                                                                                        type="radio"
                                                                                        {{ @$google_data->status_id == 1 ? 'checked' : '' }}
                                                                                        name="location_api" id="google_api"
                                                                                        value="google">
                                                                                    <span>{{ _trans('common.Google') }}</span></label><label
                                                                                    class="btn border"><input type="radio"
                                                                                        {{ @$barikoi_data->status_id == 1 ? 'checked' : '' }}
                                                                                        name="location_api" id="barikoi_api"
                                                                                        value="barikoi">
                                                                                    <span>{{ _trans('common.BariKoi') }}</span></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-12 mt-20 {{ @$barikoi_data->status_id == 1 ? 'checked' : 'd-none' }}"
                                                                        id="barikoi_section">
                                                                        <fieldset>
                                                                            <legend>
                                                                                {{ _trans('common.BariKoi API Setup') }}
                                                                                @if(@$barikoi_data->status_id==1)
                                                                                    <i class="fas fa-check-circle text-success"></i>
                                                                                @endif
                                                                            </legend>
                                                                            <form
                                                                                action="{{ route('company.settings.updateApi') }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="name"
                                                                                    value="barikoi">
                                                                                <input type="hidden" name="other[]"
                                                                                    value="google">

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="api_key">{{ _trans('settings.API Key') }}</label>
                                                                                        <input type="text"
                                                                                            class="form-control"id="api_key"
                                                                                            name="api_key"
                                                                                            value="{{ @$barikoi_data->key }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="api_endpoint">{{ _trans('settings.End Point') }}</label>
                                                                                        <input type="text"
                                                                                            class="form-control"id="api_endpoint"
                                                                                            name="api_endpoint"
                                                                                            value="{{ @$barikoi_data->endpoint }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary text-center mt-30"><span
                                                                                            class="w-100">
                                                                                        </span> {{ _trans('common.Save') }}
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </fieldset>
                                                                    </div>

                                                                    <div class="col-sm-12 mt-20 {{ @$google_data->status_id == 1 ? 'checked' : 'd-none' }}"
                                                                        id="google_section">
                                                                        <fieldset>
                                                                            <legend>
                                                                                {{ _trans('common.Google API Setup') }}
                                                                                @if(@$google_data->status_id==1)
                                                                                    <i class="fas fa-check-circle text-success"></i>
                                                                                @endif

                                                                            </legend>
                                                                            <form
                                                                                action="{{ route('company.settings.updateApi') }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="name"
                                                                                    value="google">
                                                                                <input type="hidden" name="other[]"
                                                                                    value="barikoi">

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="api_key">{{ _trans('settings.API Key') }}</label>
                                                                                        <input type="text"
                                                                                            class="form-control"id="api_key"
                                                                                            name="api_key"
                                                                                            value="{{ @$google_data->key }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary text-center mt-30"><span
                                                                                            class="w-100">
                                                                                        </span> {{ _trans('common.Save') }}
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                        </fieldset>
                                                        <hr>
                                                        <fieldset class="form-group">
                                                            <div class="row">
                                                                <legend
                                                                    class="col-2 col-form-label text-primary pt-0 mb-3">
                                                                    {{ _trans('settings.Firebase') }}
                                                                </legend>
                                                                @php
                                                                    $firebase_data = $data['company_apis']->where('name', 'firebase')->first();
                                                                @endphp
                                                                <div class="col-sm-8">

                                                                    <div class="col-sm-12">
                                                                        <fieldset>
                                                                            <legend></legend>
                                                                            @php
                                                                                $firebase_data = $data['company_apis']->where('name', 'firebase')->first();
                                                                            @endphp
                                                                            <form
                                                                                action="{{ route('company.settings.updateApi') }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="name"
                                                                                    value="firebase">

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="api_key">
                                                                                            {{ _trans('settings.API Key') }}</label>
                                                                                        <input type="text"
                                                                                            class="form-control"id="api_key"
                                                                                            name="api_key"
                                                                                            value="{{ @$firebase_data->key }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary text-center mt-30"><span
                                                                                            class="w-100">
                                                                                        </span> {{ _trans('common.Save') }}
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                        </fieldset>
                                                         
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
        </div>
    </div>
    <input type="hidden" id="currencyInfo" value="{{ route('company.settings.currencyInfo') }}">
@endsection
@section('script')
    <script src="{{ url('public/backend/js/image_preview.js') }}"></script>
@endsection
