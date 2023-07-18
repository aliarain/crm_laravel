@extends('frontend.auth.app')
@section('title', 'Forgot password')

@section('content')
<div class="form-heading mb-40">
    <h1 class="title mb-8 text-capitalize">{{ _trans('common.forgot password') }}</h1>
    <p class="subtitle mb-0 text-capitalize">{{ _trans('common.Enter your email to recover your password') }}</p>
</div>

<input type="hidden" id="send_reset_link_successfully" value="{{ _trans('frontend.Send reset link successfully')}}" />

<div   class="auth-form d-flex justify-content-center align-items-start flex-column">

    <!-- username input field  -->
    <div class="input-field-group mb-20">
        <label for="email">{{ _trans('common.Email') }} <sup>*</sup></label><br >
        <div class="custom-input-field">
            <input type="email" name="email" id="email" class="form-control"
            placeholder="{{ _trans('common.Email') }}">
        </div>
        <p class="text-danger cus-error __email small-text"></p>
    </div>
    <!-- submit button  -->
    <button type="submit" class="submit-btn pv-16 mb-20 submit_btn">
        {{ _trans('common.Send Code') }}
    </button>
</div>
<!-- End form -->
<p class="authenticate-now mb-0">
    <a class="link-text" href="{{ route('adminLogin') }}"> {{ _trans('auth.Back to Sign in') }}</a>
</p>



@endsection
@section('script')
<script src="{{ asset('/') }}public/frontend/assets/jquery.min.js"></script>
<script src="{{ asset('/') }}public/frontend/assets/bootstrap/bootstrap.min.js"></script>
<script src="{{ asset('/') }}public/backend/js/select2.min.js"></script>
@include('backend.partials.message')
<script src="{{ asset('public/js/toastr.js') }}"></script>
{!! Toastr::message() !!}
    <script src="{{ asset('public/frontend/js/auth.js') }}"></script>
@endsection
