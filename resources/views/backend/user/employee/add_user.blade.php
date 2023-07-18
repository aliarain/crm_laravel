@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([
'title' => @$data['title'],
route('admin.dashboard') => _trans('common.Dashboard'),
'#' => @$data['title'],
]) !!}

<div class="card ot-card">
    <div class="card-body">
        <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control ot-form-control ot_input" placeholder="{{ _trans('common.Name') }}" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                            <span class="error text-danger">
                                {{ $errors->first('name') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label  class="form-label">{{ _trans('common.Email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" placeholder="{{ _trans('common.Email') }}" autocomplete="off" class="form-control ot-form-control ot_input" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                            <span class="error text-danger">
                                {{ $errors->first('email') }}
                            </span>

                            @endif

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Phone') }} <span class="text-danger">*</span></label>
                            <input type="number" name="phone" placeholder="{{ _trans('common.Phone') }}" autocomplete="off" class="form-control ot-form-control ot_input" value="{{ old('phone') }}">
                            @if ($errors->has('phone'))
                            <div class="error text-danger">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label"> {{ _trans('common.Country') }} <span class="text-danger">*</span>
                            </label>

                            <select name="country" class="form-control ot-form-control ot_input select2 w-100" id="_country_id">
                            </select>
                            @if ($errors->has('country'))
                            <div class="error text-danger">{{ $errors->first('country') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Joining Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="joining_date" autocomplete="off" class="form-control ot-form-control ot_input" value="{{ old('joining_date') }}">
                            @if ($errors->has('joining_date'))
                            <div class="error text-danger">{{ $errors->first('joining_date') }}</div>
                            @endif
                        </div>
                    </div>







                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Date Of Birth') }}</label>
                            <input type="date" name="birth_date" autocomplete="off" class="form-control ot-form-control ot_input" value="{{ old('birth_date') }}">
                            @if ($errors->has('birth_date'))
                            <div class="error text-danger">{{ $errors->first('birth_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Blood Group') }}</label>{{_trans('common.')}}
                            <select name="blood_group" class="form-select select2">
                                <option disabled selected>{{ _trans('common.Choose One') }}
                                </option>
                                <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>{{ _trans('common.A+') }}
                                </option>
                                <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>{{ _trans('common.A-') }}
                                </option>
                                <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>{{ _trans('common.B+') }}
                                </option>
                                <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>{{ _trans('common.B-') }}
                                </option>
                                <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>{{ _trans('common.O+') }}
                                </option>
                                <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>{{ _trans('common.O-') }}
                                </option>
                                <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>
                                    {{ _trans('common.AB+') }}
                                </option>
                                <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>
                                    {{ _trans('common.AB-') }}
                                </option>
                            </select>
                            @if ($errors->has('blood_group'))
                            <div class="error text-danger">{{ $errors->first('blood_group') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Basic Salary') }} <span class="text-danger">*</span></label>
                            <input type="number" name="basic_salary" placeholder="{{ _trans('common.Salary') }}" autocomplete="off" class="form-control ot-form-control ot_input" value="{{ old('basic_salary') }}">
                            @if ($errors->has('basic_salary'))
                            <div class="error text-danger">{{ $errors->first('basic_salary') }}</div>
                            @endif
                        </div>
                    </div>
                    @if (settings('user_pass_auto_generate')!=1)
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Password') }} <span class="text-danger">*</span></label>
                            <input type="text" name="password" placeholder="{{ _trans('common.Password') }}" autocomplete="off" class="form-control ot-form-control ot_input" value="{{ old('password') }}">
                            @if ($errors->has('password'))
                            <div class="error text-danger">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>

                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Role') }}
                                <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select change-role select2">
                                <option value="" disabled>
                                    {{ _trans('common.Choose One') }}
                                </option>
                                @foreach ($data['roles'] as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('role_id'))
                            <span class="error text-danger">
                                {{ $errors->first('role_id') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Designation') }} <span class="text-danger">*</span></label>
                            <select name="designation_id" class="form-select select2">
                                <option value="" disabled>{{ _trans('common.Choose One') }}
                                </option>
                                @foreach ($data['designations'] as $designation)
                                <option value="{{ $designation->id }}">{{ $designation->title }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('designation_id'))
                            <span class="error text-danger">
                                {{ $errors->first('designation_id') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label  class="form-label">{{ _trans('common.Department') }} <span class="text-danger">*</span></label>
                            <select name="department_id" class="form-select select2">
                                <option value="" disabled>{{ _trans('common.Choose One') }}
                                </option>
                                @foreach ($data['departments'] as $department)
                                <option value="{{ $department->id }}">{{ $department->title }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Shift') }} <span class="text-danger">*</span></label>
                            <select name="shift_id" class="form-select select2">
                                <option value="" disabled>{{ _trans('common.Choose One') }}
                                </option>
                                @foreach ($data['shifts'] as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label  class="form-label">{{ _trans('common.Gender') }} <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select demo-select2-placeholder {{ $errors->has('gender') ? 'is-invalid' : '' }} select2">
                                <option disabled>{{ _trans('common.Choose One') }}</option>
                                <option value="0" {{ old('gender') == 0 ? 'selected' : '' }}>
                                    {{ _trans('common.Male') }}
                                </option>
                                <option value="1" {{ old('gender') == 1 ? 'selected' : '' }}>
                                    {{ _trans('common.Female') }}
                                </option>
                                <option value="2" {{ old('gender') == 2 ? 'selected' : '' }}>
                                    {{ _trans('common.Unisex') }}
                                </option>
                            </select>
                            @if ($errors->has('gender'))
                            <span class="error text-danger">
                                {{ $errors->first('gender') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label" >{{ _trans('common.Address') }}</label>
                            <input type="text" name="address" placeholder={{ _trans('common.Address') }} autocomplete="off" class="form-control ot-form-control ot_input" value="{{ old('address') }}">
                            @if ($errors->has('address'))
                            <div class="error text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Religion') }}</label>

                            <select name="religion" id="religion" class="form-select select2">
                                <option disabled selected>{{ _trans('common.Choose One') }}
                                </option>
                                <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : '' }}>
                                    {{ _trans('common.Islam') }}
                                </option>
                                <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>
                                    {{ _trans('common.Hindu') }}
                                </option>
                                <option value="Christan" {{ old('religion') == 'Christan' ? 'selected' : '' }}>
                                    {{ _trans('common.Christan') }}
                                </option>
                            </select>
                            @if ($errors->has('religion'))
                            <div class="error text-danger">{{ $errors->first('religion') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="marital_status">{{ _trans('common.Marital Status') }}</label>
                            <select name="marital_status" id="marital_status" class="form-select select2">
                                <option disabled selected>{{ _trans('common.Choose One') }}
                                </option>
                                <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>
                                    {{ _trans('common.Married') }}
                                </option>
                                <option value="Unmarried" {{ old('marital_status') == 'Unmarried' ? 'selected' : '' }}>
                                    {{ _trans('common.Unmarried') }}
                                </option>
                            </select>
                            @if ($errors->has('marital_status'))
                            <div class="error text-danger">{{ $errors->first('marital_status') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @if (settings('user_pass_auto_generate')==1)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info" role="alert">
                            {{ _trans('message.On employee creation system will generate 8 digit password and send it to employee email') }}
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-lg-12">
                    <div class="d-flex justify-content-end mt-4">
                        <div class="form-group mb-3">
                            @if (hasPermission('user_create'))
                            <button type="submit" class="crm_theme_btn mr-3">{{ _trans('common.Submit') }}</button>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </form>
    </div>
</div>

@endsection
@section('script')
<script src="{{ url('public/backend/js/pages/__profile.js') }}"></script>
@endsection