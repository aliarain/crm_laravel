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


                    <div class="col-md-6 mb-24">
                        <div class="form-group">
                            <label for="name">{{ _trans('settings.Distance') }} <span class="text-danger">*</span></label>
                            <input type="number" id="distance" class="form-control ot-form-control ot_input"
                                value="{{ @$data['location']->distance }}" required placeholder="0">
                            <p> {{ _trans('settings.Distance measure in meters') }}</p>
                            @if ($errors->has('distance'))
                                <div class="error">{{ $errors->first('distance') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-24">
                        <div class="form-group" id="_st">
                            <label for="status_id">{{ _trans('common.Status')}} <span class="text-danger">*</span></label>
                            <select id="status_id" class="form-select ot_input select2" required>
                                <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                <option {{ @$data['location']->status_id == 1 ? 'selected' : '' }} value="1" selected>
                                    {{ _trans('common.Active') }}</option>
                                <option {{ @$data['location']->status_id == 4 ? 'selected' : '' }} value="4">
                                    {{ _trans('common.In-active') }}</option>
                            </select>
                            <span class="status_error"></span>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-24">
                        <div class="form-group">
                            <label for="name">{{ _trans('settings.Location') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6 d-none">
                                <input id="pac-input" class="form-control controls location ot-form-control ot_input"
                                    type="text" placeholder="Enter a location" value="{{ @$data['location']->address }}"
                                    required name="location" onkeydown="return (event.keyCode!=13);">
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


                    @if (@$data['url'])
                        <div class="col-md-12">
                            <div class="text-end mt-24">
                                <button class="btn btn-primary"
                                    onclick="locationPickerStore(`{{ $data['url'] }}`)">{{ _trans('common.Update') }}</button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="latitude" value="{{ @$data['location']->latitude }}">
    <input type="hidden" id="longitude" value="{{ @$data['location']->longitude }}">
@endsection
@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key={{ @settings('google') }}"></script>
    <script src="{{ asset('public/backend/js/__location_find.js') }}"></script>
@endsection
