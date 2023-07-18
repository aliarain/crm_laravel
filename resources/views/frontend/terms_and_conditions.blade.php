@extends('frontend.includes.master')
@section('title',@$data['title'])
@section('crm_menu')
<div class="crm-header">
    <div class="landing_header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light bg-transparent">
            <div class="container">
                @include('backend.auth.backend_logo')
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto">

                    </ul>
                    <div class="responsive-collapse-btn">
                        <a class="primary_btn" href="{{ url('/') }}">Home</a>

                        @if(Config::get('app.APP_CRM'))
                        <a class="primary_btn" href="{{ url('/') }}">Buy Now</a>
                        @endif 
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
@endsection

@section('content')
<div class="new-main-content">

    <div class="container">
        <div class="row py-5">
            <div class="col-md-12">
                <h3>{{ $data['show']->title }}</h3>
                <p>
                    {!! @$data['show']->content !!}
                </p>
            </div>
        </div>
    </div>
</div>

@endsection