<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="{{ env('APP_URL') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ @base_settings('company_name') }} - @yield('title')</title>
    @if (env('APP_ENV') == 'server')
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif
    <link rel="shortcut icon" href="{{ company_fav_icon(@base_settings('company_icon')) }}" type="image/x-icon">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/fontawesome/css/all.min.css">
    <!-- Line Awesome -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/lineawesome/css/line-awesome.min.css">
    <!--  Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/bootstrap/css/bootstrap.min.css">
    <!--  sweet-alert -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/sweet-alert/css/sweet-alert.min.css">
    <!-- style -->
    <link rel="stylesheet" href="{{ url('public/css/style.css') }}">
    @yield('style')

</head>

<body class="default-theme">
    <!-- MAIN_CONTENT_START -->
    <main class="auth-page flex-column align-items-center">
        <section class="auth-container">
            <div class="form-wrapper pv-80 ph-100 bg-white d-flex justify-content-center align-items-center flex-column">
                <div class="form-container d-flex justify-content-center align-items-start flex-column">
                    <div class="form-logo mb-40">
                        @include('frontend.partials.white_logo')
                    </div>
                    @yield('content')
                </div>
            </div>
        </section>
        <div class="row gy-5">
            <div class="col-md-12 col-lg-12">
                <div class="footer-logo mb-3">
                    <p class="privacy_policy_text">
                        <a href="{{url('privacy-policy')}}">Privacy Policy</a> |
                        <a href="{{url('terms-and-conditions')}}">Terms & Conditions</a>
                    </p>
                </div>
            </div>

        </div>
    </main>







    <!--/ MAIN_CONTENT_END -->
    <script src="{{ asset('public/vendors/') }}/sweet-alert/js/sweetalert2@11.min.js"></script>
    @yield('script')
</body>