@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))

@section('details')<div class="error_area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="error_inner">
                    <div class="thumb">
                        <img class="img-fluid" src="{{url('/public/assets/images/500.png')}}" alt="">
                    </div>
                    <h3>{{ _trans('error.Server Error') }}</h3>
                    <p>{{ _trans('error.Sorry, the server encountered an internal error and could not complete your
                        request') }}, {{ _trans('Please try again later or contact our customer support team if the problem
                        persists') }}</p>

                    <a href="{{ url('/') }}" class="theme_btn min_200 style6 f_w_700 radius_3px">Back To Homepage</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection