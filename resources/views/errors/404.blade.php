@extends('errors::minimal')
@section('title', __('Not Found'))

@section('details')
<div class="error_area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="error_inner">
                    <div class="thumb">
                        <img class="img-fluid" src="{{url('/public/assets/images/404.png')}}" alt="">
                    </div>
                    <h3>Opps! Page Not Found</h3>
                    <p>Perhaps you can try to refresh the page, sometimes it works.</p>
                    <a href="{{ url('/') }}" class="theme_btn min_200 style6 f_w_700 radius_3px">Back To Homepage</a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection