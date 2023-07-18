@extends('frontend.auth.app')
@section('title', 'Forgot password')
@section('content')
    <!-- form heading  -->
    <div class="form-heading mb-40">
        <h1 class="title mb-8">{{ __('Change password!!') }}</h1>
    </div>

    <input type="hidden" id="password_change_successfully" value="{{ _trans('frontend.Password change successfully') }}" />
    <form action="#" method="post" id="login"
        class="auth-form d-flex justify-content-center align-items-start flex-column password_reset_form">
        @csrf
        <!-- username input field  -->
        <div class="input-field-group">
            <label for="username">{{ _trans('auth.Verification Code') }} <sup>*</sup></label><br >
            <div class="custom-input-field login__field">
                <input type="text" name="code" class="form-control" placeholder="Verification Code" id="username">
            </div>
            <p class="text-danger cus-error __code small-text"></p>
        </div>
        <div class="input-field-group">
            <label for="email">{{ _trans('auth.Email') }} <sup>*</sup></label><br >
            <div class="custom-input-field login__field">
                <input type="email" name="email" class="form-control" id="email" placeholder="{{ _trans('common.Email') }}"
                    value="{{ Session::get('email') }}" readonly>
            </div>
            <p class="text-danger cus-error __email small-text"></p>
        </div>

        <div class="input-field-group">
            <label for="password">{{ _trans('auth.Password') }} <sup>*</sup></label><br >
            <div class="custom-input-field login__field">
                <input type="password" name="password" id="password" placeholder="{{ _trans('auth.password') }}" class="form-control"
                    placeholder="Password">
            </div>
            <p class="text-danger cus-error __password small-text"></p>
        </div>

        <div class="input-field-group">
            <label for="Confirm_p">{{ _trans('auth.Confirm Password') }} <sup>*</sup></label><br >
            <div class="custom-input-field login__field">
                <input id="Confirm_p" type="password" name="password_confirmation" class="form-control"
                    placeholder="{{ _trans('auth.Confirm password') }}">
            </div>
            <p class="text-danger cus-error __password_confirmation small-text"></p>
        </div>

        <button type="button"
            class="submit-btn mb-3 pv-16 mt-32 mb-20 submit_btn_change">{{ _trans('auth.Change password') }}</button>

        <p class="authenticate-now mb-0">
            <a class="link-text" href="{{ route('adminLogin') }}"> {{ _trans('auth.Back to Sign in') }}</a>
        </p>

    </form>

@endsection
@section('script')
    <script src="{{ asset('/') }}public/frontend/assets/jquery.min.js"></script>
    <script src="{{ asset('/') }}public/frontend/assets/bootstrap/bootstrap.min.js"></script>
    @include('backend.partials.message')
    <script src="{{ asset('public/js/toastr.js') }}"></script>
    {!! Toastr::message() !!}
    <script src="{{ asset('public/frontend/js/auth.js') }}"></script>
@endsection
