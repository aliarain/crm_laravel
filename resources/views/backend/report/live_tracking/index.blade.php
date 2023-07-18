@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([
    'title' => @$data['title'],
    route('admin.dashboard') => _trans('common.Dashboard'),
    '#' => @$data['title'],
]) !!}
    <div class="table-content table-basic">
        <section class="card">
            <div class="card-body">
                <div class="row align-items-center mb-15 mt-16">
                    <div class="col-sm-6">
                    </div>
                </div>

                <div class="row align-items-end mb-30 table-filter-data">
                    <div class="col-lg-12">
                        @if (hasPermission('role_read'))
                           <form method="GET" action="#" >
                                <div class="row align-items-center">
                                    <div class="col-xl-3">
                                        <div class="form-group">
                                            <input type="date" id="start" name="date" class="form-control ot-form-control ot_input"
                                                value="{{  @$data['date'] ?  @$data['date'] :  date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-2">
                                        <div class="form-group">
                                            <button type="submit" class="crm_theme_btn attendance_table_form height48">{{ _trans('common.Submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
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
        </section>
    </div>

    <input type="text" hidden id="data_url" value="{{ route('live_trackingReportEmployee.index','date='. @$data['date']) }}">
    <input type="text" hidden id="token" value="{{ csrf_token() }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
<script src="https://maps.googleapis.com/maps/api/js?key={{ @settings('google') }}"></script>
<script src="{{ asset('public/backend/js/__geo_location.js') }}"></script>
@endsection
