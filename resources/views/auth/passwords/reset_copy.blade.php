@extends('backend.auth.app')
@section('title', 'Forgot password')
@section('content')
    <div class="container">
        <div class=" cus-card">
            <div class="card-body login-card-body">
                <div class="mb-4">
                    <p class="login-box-msg cus-login-box-msg mt-3">{{ __('Change password!!') }}</p>
                </div>
                <form action="#" method="post" id="login" class="password_reset_form">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="code" class="form-control" placeholder="Verification Code">
                    </div>
                    <p class="text-danger __code small-text"></p>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="{{ _trans('common.Email') }}"
                               value="{{ Session::get('email') }}" readonly>
                    </div>
                    <p class="text-danger __email small-text"></p>
                    <div class="input-group mb-3">
                        <input type="text" name="password" placeholder="{{ _trans('auth.password') }}" class="form-control" placeholder="Password">
                    </div>
                    <p class="text-danger __password small-text"></p>
                    <div class="input-group mb-3">
                        <input type="text" name="password_confirmation" class="form-control" placeholder="{{ _trans('auth.Confirm password') }}">
                    </div>
                    <p class="text-danger __password_confirmation small-text"></p>
                    <div class="row">
                        <div class="col-md-12 mx-auto text-center">
                            <button type="button"
                                    class="login-panel-btn btn-block mb-3 submit_btn_change">{{ _trans('auth.Change password') }}</button>
                        </div>
                    </div>
                    <div class="form-row form-row flex-column flex-md-row justify-content-center justify-content-md-between justify-content-lg-between">
                        <a href="{{ route('adminLogin') }}"
                           class="bluish-text d-flex align-items-center justify-content-center justify-content-lg-end ml-1">
                             {{ _trans('auth.Back to Sign in') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="{{ asset('public/frontend/js/auth.js') }}"></script>
@endsection
