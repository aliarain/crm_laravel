@extends('frontend.auth.app')
@section('title', _trans('auth.Sign In'))
@section('content')
<!-- form heading  -->
<div class="form-heading mb-40">
    <h1 class="title mb-8">{{ _trans('auth.Sign In') }}</h1>
    <p class="subtitle mb-0 d-none d-md-block text-capitalize">{{ _trans('auth.welcome back please login to your account') }}</p>
</div>
<input type="hidden" id="login_success_fully" value="{{ _trans('frontend.Login successfully') }}">
<!-- Start With form -->
<form action="#" method="post" id="login"
    class="auth-form d-flex justify-content-center align-items-start flex-column __login_form">
    @csrf

    <input type="hidden" hidden name="is_email" value="1">
    <!-- username input field  -->
    <div class="input-field-group">
        <label for="username">{{ _trans('auth.Email') }} <sup>*</sup></label><br>
        <div class="custom-input-field login__field">
            <input type="email" name="email" class="login__input " id="username" placeholder="Email">
        </div>
        <p class="text-danger cus-error __phone small-text"></p>
    </div>
    <!-- password input field  -->
    <div class="input-field-group ">
        <label for="passwordLoginInput">{{ _trans('auth.Password') }} <sup>*</sup></label><br>
        <div class="custom-input-field password-input login__field">
            <input type="password" name="password" class="login__input " id="passwordLoginInput" placeholder="Password">
            <i class="lar la-eye"></i>
        </div>
        <p class="text-danger cus-error __password small-text"></p>
    </div>
    <div class="d-flex justify-content-between w-100">
        <!-- Forget Password link  -->
        <div class="authenticate-now">
            <a href="{{ route('password.forget') }}" class=" text-link ">
                {{ _trans('auth.Forgot password?') }}</a>
        </div>
    </div>
    <button type="button" class="submit-btn  __login_btn mb-3 pv-16 mt-32 mb-20">
        {{ _trans('auth.Sign In') }}
    </button>
</form>

@if ( env('APP_CRM') && env('APP_ENV') == 'local' )
@if($users != "")
<!-- START:: Login with demo credentials -->
<h4 class="fs-6 font-semibold text-capitalize mt-3">Login with demo credentials</h4>
<div class="app_sync_btns">
    @foreach ($users as $user)
    <form action="#" class="form d-flex justify-content-center align-items-start flex-column mb-3" method="post">
        @csrf
        <input type="hidden" name="email" value="{{ @$user->email }}">
        <input type="hidden" name="password" value="12345678">
        <button type="button" class="submit-button submit-button-only-border pv-14 __demo_login_btn admin-login-btn">
            {{ @$user->name }}</button>
    </form>
    @endforeach
</div>
<!--END:: Login with demo credentials -->
@endif
@endif


@endsection

@section('script')
<script src="{{ asset('/') }}public/frontend/assets/jquery.min.js"></script>
<script src="{{ asset('/') }}public/frontend/assets/bootstrap/bootstrap.min.js"></script>
<script src="{{ asset('/') }}public/backend/js/select2.min.js"></script>
@include('backend.partials.message')
<script src="{{ asset('public/js/toastr.js') }}"></script>
{!! Toastr::message() !!}
<script src="{{ asset('public/frontend/js/registration.js') }}"></script>
<script src="{{ asset('public/frontend/js/show-hide-password.js') }}"></script>
@endsection