@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message','Forbidden')

@extends('errors::minimal')
@section('details')
<div class="error_area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="error_inner">
                    <div class="thumb ">
                       <h1 class="high-text">403</h1>
                    </div>
                    <h3>{{ _trans('error.Access to the requested resource is forbidden!') }}</h3>
                   <p>
                    <span class="text-left"> {{ _trans('error.The server understood the request, but is refusing to fulfill it due to invalid credentials, insufficient permissions, or other authorization issues, please contact the system administrator or check your access credentials and try again!') }}</span>
                   </p>
                    <br>
                    <a href="{{ url('/') }}" class="theme_btn min_200 style6 f_w_700 radius_3px">{{ _trans('error.Back To Homepage') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection