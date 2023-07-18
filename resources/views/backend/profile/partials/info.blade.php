<div class="profile-body p-40 pl-40 pr-40 pt-10">
    <!-- profile body nav start -->
    <div class="profile-body-form">
        @if (hasPermission('user_update'))
            <form method="POST" action="{{ route('user.update.profile', [$data['id'], $data['slug']]) }}"
                enctype="multipart/form-data">
                @csrf
                <input type="text" hidden name="user_id" value="{{ $data['id'] }}">
        @endif
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('common.Phone') }} <span class="text-danger">*</span></label>
            <input type="text" class="form-control ot-form-control ot_input" name="phone"
                value="{{ $data['show']->original['data']['phone'] ?? 'N/A' }}">
            @if ($errors->has('phone'))
                <span class="text-danger">{{ $errors->first('phone') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('profile.Date of Birth') }}</label>
            <input type="text" class="form-control ot-form-control ot_input s_date" name="birth_date"
                value="{{ date('m/d/y', strtotime(@$data['show']->original['data']['birth_date_db'])) }}">
            @if ($errors->has('birth_date'))
                <span class="text-danger">{{ $errors->first('birth_date') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('common.Gender') }} <span class="text-danger">*</span></label>
            <select name="gender"  
                class="form-select select2 demo-select2-placeholder {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                <option value="" disabled selected>{{ _trans('common.Choose One') }}
                </option>
                @foreach (config('hrm.gender') as $gender)
                    <option value="{{ $gender }}"
                        {{ $gender == $data['show']->original['data']['gender'] ? 'selected' : '' }}>
                        {{ $gender }}</option>
                @endforeach
            </select>
            @if ($errors->has('gender'))
                <span class="text-danger">{{ $errors->first('gender') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('common.Address') }}</label>
            <input type="text" class="form-control ot-form-control ot_input" name="address"
                value="{{ @$data['show']->original['data']['address'] ?? 'N/A' }}">
            @if ($errors->has('address'))
                <span class="text-danger">{{ $errors->first('address') }}</span>
            @endif
        </div>

        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('profile.Nationality') }}</label>
            <input type="text" class="form-control ot-form-control ot_input" name="nationality"
                value="{{ @$data['show']->original['data']['nationality'] }}">
            @if ($errors->has('nationality'))
                <span class="text-danger">{{ $errors->first('nationality') }}</span>
            @endif
        </div>

        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('profile.Passport Number') }}
            </label>
            <input type="text" class="form-control ot-form-control ot_input" name="passport_number"
                value="{{ @$data['show']->original['data']['passport_number'] }}">
            @if ($errors->has('passport_number'))
                <span class="text-danger">{{ $errors->first('passport_number') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('profile.Passport File') }}
                @if (@$data['show']->original['data']['passport_file_id'])
                    [<span>
                        <a href="javascript:;"
                            onclick="mainModalOpen(`{{ route('user.fileView', @$data['show']->original['data']['passport_file_id']) }}`) ">
                            <i class="fa fa-eye"></i>
                        </a>
                    </span>]
                @endif
            </label>

            <div class="ot_fileUploader left-side mb-3">
                <input class="form-control" type="text" placeholder="{{ _trans('profile.Passport File') }}"  readonly="" id="placeholder">
                <div class="primary-btn-small-input">
                    <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse') }}</label>
                    <input type="file" class="d-none form-control" name="passport_file" id="fileBrouse">
                </div>
            </div>


            @if ($errors->has('passport_file'))
                <span class="text-danger">{{ $errors->first('passport_file') }}</span>
            @endif
        </div>

        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('profile.NID Number') }}
            </label>
            <input type="text" class="form-control ot-form-control ot_input" name="nid_card_number"
                value="{{ $data['show']->original['data']['nid_card_number'] }}">
            @if ($errors->has('nid_card_number'))
                <span class="text-danger">{{ $errors->first('nid_card_number') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('profile.NID File') }}
                @if (@$data['show']->original['data']['nid_file'])
                    [<span>
                        <a href="javascript:;"
                            onclick="mainModalOpen(`{{ route('user.fileView', @$data['show']->original['data']['nid_file']) }}`) ">
                            <i class="fa fa-eye"></i>
                        </a>
                    </span>]
                @endif
            </label>
            <div class="ot_fileUploader left-side mb-3">
                <input class="form-control" type="text" placeholder="{{ _trans('profile.NID File') }}"  readonly="" id="placeholder2">
                <div class="primary-btn-small-input">
                    <label class="btn btn-lg ot-btn-primary" for="fileBrouse2">{{ _trans('common.Browse') }}</label>
                    <input type="file" class="d-none form-control" name="nid_file" id="fileBrouse2">
                </div>
            </div>

            @if ($errors->has('nid_file'))
                <span class="text-danger">{{ $errors->first('nid_file') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('common.Blood Group') }} <span class="text-danger">*</span></label>

            <select name="blood_group" 
                class="form-select select2 demo-select2-placeholder {{ $errors->has('blood_group') ? 'is-invalid' : '' }}">
                <option value="" disabled selected>{{ _trans('common.Choose One') }}
                </option>
                @foreach (config('hrm.blood_group') as $blood)
                    <option value="{{ $blood }}"
                        {{ $blood == $data['show']->original['data']['blood_group'] ? 'selected' : '' }}>
                        {{ $blood }}</option>
                @endforeach
            </select>
            @if ($errors->has('blood_group'))
                <span class="text-danger">{{ $errors->first('blood_group') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('profile.Avatar') }}</label>
            <div class="ot_fileUploader left-side mb-3">
                <input class="form-control" type="text" placeholder="{{ _trans('profile.Avatar') }}" name="backend_image" readonly="" id="placeholder3">
                <div class="primary-btn-small-input">
                    <label class="btn btn-lg ot-btn-primary" for="fileBrouse3">{{ _trans('common.Browse') }}</label>
                    <input type="file" class="d-none form-control" name="avatar" id="fileBrouse3">
                </div>
            </div>
            @if ($errors->has('avatar'))
                <span class="text-danger">{{ $errors->first('avatar') }}</span>
            @endif
        </div>
        <div class="form-group mt-20">
            <label class="mb-10">{{ _trans('profile.TIN') }}
            </label>
            <input type="text" class="form-control ot-form-control ot_input" name="tin"
                value="{{ $data['show']->original['data']['tin'] }}">
            @if ($errors->has('tin'))
                <span class="text-danger">{{ $errors->first('tin') }}</span>
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
