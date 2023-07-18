<div class="profile-body p-40 pl-40 pr-40 pt-10">
    <!-- profile body nav start -->
    <div class="profile-body-form">
        @if (hasPermission('user_update'))
            <form method="POST" action="{{ route('user.authProfile.security', [$data['id']]) }}"
                enctype="multipart/form-data">
                @csrf
                <input type="text" hidden name="user_id" value="{{ $data['id'] }}">
        @endif
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('common.E-mail') }} <span class="text-danger">*</span></label>
            <input type="text" class="form-control ot-form-control ot_input" name="email" value ="{{ @$data['email'] }}">
            @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('common.Old Password') }} <span class="text-danger">*</span></label>
            <input type="password" class="form-control ot-form-control ot_input" name="old_password" placeholder="{{ _trans('common.Old Password') }}">
            @if ($errors->has('old_password'))
                <span class="text-danger">{{ $errors->first('old_password') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('profile.New Password') }} <span class="text-danger">*</span></label>
            <input type="password" class="form-control ot-form-control ot_input" name="password" placeholder="{{ _trans('common.New Password') }}">
            @if ($errors->has('password'))
                <span class="text-danger">{{ $errors->first('password') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('profile.Confirm Password') }} <span class="text-danger">*</span></label>
            <input type="password" class="form-control ot-form-control ot_input" name="password_confirmation" placeholder="{{ _trans('common.Confirm Password') }}">
            @if ($errors->has('password_confirmation'))
                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
            @endif
        </div>
        @if (hasPermission('user_update'))
            <div class="form-group d-flex justify-content-end mt-20">
                <button type="submit" class="crm_theme_btn ">{{ _trans('common.Update') }}</button>
            </div>
            </form>
        @endif
    </div>
</div>
