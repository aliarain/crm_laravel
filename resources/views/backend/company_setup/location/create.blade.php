@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}

    <div class="table-content table-basic ">
        <div class="card">
            <div class="card-body">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ _trans('settings.Distance') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="distance" class="form-control ot-form-control ot_input"
                                        value="{{ old('distance') }}" required placeholder="0">
                                    <small class="form-label fs-10">
                                        {{ _trans('settings.Distance measure in meters') }}</small>
                                    @if ($errors->has('distance'))
                                        <div class="error">{{ $errors->first('distance') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3" id="_st">
                                    <label class="form-label" for="status_id">{{ _trans('common.Status') }} <span
                                            class="text-danger">*</span></label>
                                    <select id="status_id" class="form-control select2" required>
                                        <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                        <option value="1" selected>{{ _trans('common.Active') }}</option>
                                        <option value="4">{{ _trans('common.In-active') }}</option>
                                    </select>
                                    <span class="status_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ _trans('settings.Location') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-6 d-none">
                                        <input id="pac-input"
                                            class="form-control ot-form-control ot_input controls location" type="text"
                                            placeholder="{{ _trans('common.Enter a location') }}" required name="location"
                                            onkeydown="return (event.keyCode!=13);">
                                    </div>
                                    <div class="row dataTable-btButtons">
                                        <div class="col-lg-12">
                                            <div class="ltn__map-area">
                                                <div class="ltn__map-inner">
                                                    <div id="map" class="mapH_500"></div>
                                                    <div class="mt-5" id="directions_panel"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        @if (@$data['url'])
                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="text-right d-flex justify-content-end">
                                        <button class="crm_theme_btn "
                                            onclick="locationPickerStore(`{{ $data['url'] }}`)">{{ _trans('common.Save') }}</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="latitude" value="{{ @$data['location']->latitude ?? 40.7127753 }}">
    <input type="hidden" id="longitude" value="{{ @$data['location']->longitude ?? -74.0059728 }}">
@endsection
@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key={{ @settings('google') }}"></script>
    <script src="{{ asset('public/backend/js/__location_find.js') }}"></script>
@endsection
