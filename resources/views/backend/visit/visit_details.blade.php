@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper dashboard-wrapper mt-30">
        <!-- Main content -->
        <section class="content p-0">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
                <div class="row align-items-center mb-15">
                    <div class="col-sm-6">
                        <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                    </div>
                    <div class="col-md-12 col-xl-7 col-lg-8 pt-2">
                        <div class="order-track">
                            @foreach ($data['visit']->schedules as $item)
                                <div class="order-track-step">
                                    <div class="timeline-info">
                                        <p class="timeline-info__date">{{ _trans('common.31 Mar') }}</p>
                                        <p class="timeline-info__time">{{ _trans('common.12:20 PM') }}</p>
                                    </div>
                                    <div class="order-track-status">
                                        <span class="order-track-status-dot"></span>
                                        <span class="order-track-status-line"></span>
                                    </div>
                                    <a href="javascript:void(0)" onclick="getRouteInfo(`{{$item->latitude}},{{ $item->longitude }}`)">
                                        <div class="order-track-text">
                                            <p class="order-track-text-stat">{{ _trans('common.Visit Rescheduled') }}</p>
                                            <span class="order-track-text-sub">{{$item->start_location}} {{ $item->latitude }}</span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-md-12 col-xl-5 col-lg-4 pt-2">
                        <div class="ltn__map-area">
                            <div class="ltn__map-inner">
                                <div id="map" class="height_600"></div>
                                <div id="directions_panel"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <input type="text" hidden id="data_url" value="{{ @$data['url'] }}">
@endsection
@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ @api_setup('google','key') }}" async defer></script>
    <script src="{{ asset('public/js/googleMap.js') }}"></script>
@endsection
