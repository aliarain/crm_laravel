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
        <form method="POST" action="{{ route('user.update', $data['show']->id) }}" enctype="multipart/form-data"> 
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ _trans('common.Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control ot-form-control ot_input"
                                placeholder="{{ _trans('common.Name') }}" value="{{ $data['show']->name }}" required>
                            @if ($errors->has('name'))
                            <div class="error">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ _trans('common.Email') }} <span
                                    class="text-danger">*</span></label>
                            <input type="email" name="email" placeholder="{{ _trans('common.Email') }}"
                                autocomplete="off" class="form-control ot-form-control ot_input"
                                value="{{ $data['show']->email }}" required>
                            @if ($errors->has('email'))
                            <div class="error">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ _trans('common.Phone') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="phone" placeholder="{{ _trans('common.Phone') }}"
                                autocomplete="off" class="form-control ot-form-control ot_input"
                                value="{{ $data['show']->phone }}" required>
                            @if ($errors->has('phone'))
                            <div class="error">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label"> {{ _trans('common.Country') }} <span
                                    class="text-danger">*</span>
                            </label>

                            <select name="country" class="form-control ot-form-control ot_input select2 w-100"
                                id="_country_id" required>
                                <option selected value="{{ $data['show']->country_id }}">{{
                                    @$data['show']->country->name }} </option>
                            </select>
                            @if ($errors->has('country'))
                            <div class="error">{{ $errors->first('country') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="joining_date" class="form-label">{{ _trans('common.Joining Date') }} <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="joining_date" autocomplete="off"
                                class="form-control ot-form-control ot_input" value="{{ $data['show']->joining_date }}"
                                required>
                            @if ($errors->has('joining_date'))
                            <div class="error">{{ $errors->first('joining_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Date Of Birth') }}</label>
                            <input type="date" name="birth_date" autocomplete="off"
                                class="form-control ot-form-control ot_input" value="{{ $data['show']->birth_date }}">
                            @if ($errors->has('birth_date'))
                            <div class="error">{{ $errors->first('birth_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="blood_group">{{ _trans('common.Blood Group') }}</label>
                            <select name="blood_group" class="form-select select2">
                                <option disabled selected>{{ _trans('common.Choose One') }}
                                </option>
                                <option value="A+" {{ $data['show']->blood_group == 'A+' ? 'selected' : '' }}>
                                    {{ _trans('common.A+') }}
                                </option>
                                <option value="A-" {{ $data['show']->blood_group == 'A-' ? 'selected' : '' }}>
                                    {{ _trans('common.A-') }}
                                </option>
                                <option value="B+" {{ $data['show']->blood_group == 'B+' ? 'selected' : '' }}>
                                    {{ _trans('common.B+') }}
                                </option>
                                <option value="B-" {{ $data['show']->blood_group == 'B-' ? 'selected' : '' }}>
                                    {{ _trans('common.B-') }}
                                </option>
                                <option value="O+" {{ $data['show']->blood_group == 'O+' ? 'selected' : '' }}>
                                    {{ _trans('common.O+') }}
                                </option>
                                <option value="O-" {{ $data['show']->blood_group == 'O-' ? 'selected' : '' }}>
                                    {{ _trans('common.O-') }}
                                </option>
                                <option value="AB+" {{ $data['show']->blood_group == 'AB+' ? 'selected' : '' }}>
                                    {{ _trans('common.AB+') }}
                                </option>
                                <option value="AB-" {{ $data['show']->blood_group == 'AB-' ? 'selected' : '' }}>
                                    {{ _trans('common.AB-') }}
                                </option>
                            </select>
                            @if ($errors->has('blood_group'))
                            <div class="error">{{ $errors->first('blood_group') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ _trans('common.Basic Salary') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="basic_salary" placeholder="{{ _trans('common.Basic Salary') }}"
                                autocomplete="off" class="form-control ot-form-control ot_input"
                                value="{{ $data['show']->basic_salary }}" required>
                            @if ($errors->has('basic_salary'))
                            <div class="error">{{ $errors->first('basic_salary') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ _trans('settings.TimeZone') }}</label>
                            <select id="time_zone" name="time_zone"
                                class="custom-select form-select ot-form-control select2">
                                @foreach ($data['timezones'] as $key => $timezone)
                                <option value="{{ $timezone->time_zone }}" {{ @$data['show']->time_zone ==
                                    $timezone->time_zone ? 'selected' : '' }}>
                                    {{ $timezone->time_zone }}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('timezone'))
                            <div class="error">{{ $errors->first('timezone') }}</div>
                            @endif
                        </div>
                    </div>


                </div>
                <div class="col-lg-6">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ _trans('common.Role') }} <span
                                    class="text-danger">*</span></label>[<a href="javascript:;" role="button"
                                onclick="mainModalOpen(`{{ route('role.create_modal') }}`)"> <span><i
                                        class="fa-solid fa-plus"></i> </span>
                                <span class="d-none d-xl-inline"> <strong> {{ _trans('common.Add Role') }}
                                    </strong> </span> </a>]
                            <select name="role_id" class="form-select select2 change-role" required>
                                <option value="" disabled selected>
                                    {{ _trans('common.Choose One') }}</option>
                                @foreach ($data['roles'] as $role)
                                <option value="{{ $role->id }}" {{ $data['show']->role_id == $role->id ? 'selected' : ''
                                    }}>
                                    {{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="shift_id" class="form-label">{{ _trans('common.Shift') }} <span
                                    class="text-danger">*</span></label>

                            <select name="shift_id" class="form-select select2" required>
                                <option value="" disabled>{{ _trans('common.Choose One') }}
                                </option>
                                @foreach ($data['shifts'] as $shift)
                                <option value="{{ $shift->id }}" {{ $data['show']->shift_id == $shift->id ? 'selected' :
                                    '' }}>
                                    {{ $shift->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ _trans('common.Department') }} <span
                                    class="text-danger">*</span></label>
                            @if (hasPermission('department_create'))
                            [<a href="javascript:;" role="button" class="btn-add" data-bs-toggle="tooltip"
                                onclick="mainModalOpen(`{{ route('department.create_modal') }}`)"
                                data-bs-placement="right" data-bs-title="{{ _trans('common.Add') }}">
                                <span><i class="fa-solid fa-plus"></i> </span>
                                <span class="d-none d-xl-inline"> <strong> {{ _trans('common.Department') }}
                                    </strong> </span>
                            </a>]
                            @endif
                            <select name="department_id" class="form-select select2" required>
                                <option value="" disabled>{{ _trans('common.Choose One') }}
                                </option>
                                @foreach ($data['departments'] as $department)
                                <option value="{{ $department->id }}" {{ $data['show']->department_id == $department->id
                                    ? 'selected' : '' }}>
                                    {{ $department->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="designation_id" class="form-label">{{ _trans('common.Designation') }} <span
                                    class="text-danger">*</span></label>
                            @if (hasPermission('designation_create'))
                            [<a href="javascript:;" role="button" class="btn-add" data-bs-toggle="tooltip"
                                onclick="mainModalOpen(`{{ route('designation.create_modal') }}`)"
                                data-bs-placement="right" data-bs-title="{{ _trans('common.Add') }}">
                                <span><i class="fa-solid fa-plus"></i> </span>
                                <span class="d-none d-xl-inline"> <strong> {{ _trans('common.Designation') }}
                                    </strong> </span>
                            </a>]
                            @endif
                            <select name="designation_id" class="form-select select2" required>
                                <option value="" disabled>{{ _trans('common.Choose One') }}
                                </option>
                                @foreach ($data['designations'] as $designation)
                                <option value="{{ $designation->id }}" {{ $data['show']->designation_id ==
                                    $designation->id ? 'selected' : '' }}>
                                    {{ $designation->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ _trans('common.Gender') }} <span
                                    class="text-danger">*</span></label>
                            <select name="gender" required
                                class="form-select select2 demo-select2-placeholder {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                                <option disabled selected>{{ _trans('common.Choose One') }}
                                </option>
                                <option value="Male" {{ $data['show']->gender == 'Male' ? 'selected' : '' }}>
                                    {{ _trans('common.Male') }}</option>
                                <option value="Female" {{ $data['show']->gender == 'Female' ? 'selected' : '' }}>
                                    {{ _trans('common.Female') }}</option>
                                <option value="Unisex" {{ $data['show']->gender == 'Unisex' ? 'selected' : '' }}>
                                    {{ _trans('common.Unisex') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Address') }}</label>
                            <input type="text" name="address" placeholder={{ _trans('common.Address') }}
                                autocomplete="off" class="form-control ot-form-control ot_input"
                                value="{{ $data['show']->address }}">
                            @if ($errors->has('address'))
                            <div class="error">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ _trans('common.Religion') }}</label>

                            <select name="religion" id="religion" class="form-select select2">
                                <option value="Islam" {{ $data['show']->religion == 'Islam' ? 'selected' : '' }}>
                                    {{ _trans('common.Islam') }}
                                </option>
                                <option value="Hindu" {{ $data['show']->religion == 'Hindu' ? 'selected' : '' }}>
                                    {{ _trans('common.Hindu') }}
                                </option>
                                <option value="Christan" {{ $data['show']->religion == 'Christan' ? 'selected' : '' }}>
                                    {{ _trans('common.Christan') }}
                                </option>
                            </select>
                            @if ($errors->has('religion'))
                            <div class="error">{{ $errors->first('religion') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="marital_status">{{ _trans('common.Marital Status') }}</label>
                            <select name="marital_status" id="marital_status" class="form-select select2">
                                <option disabled selected>{{ _trans('common.Choose One') }}
                                </option>
                                <option value="Married" {{ $data['show']->marital_status == 'Married' ? 'selected' : ''
                                    }}>
                                    {{ _trans('common.Married') }}</option>
                                <option value="Unmarried" {{ $data['show']->marital_status == 'Unmarried' ? 'selected' :
                                    '' }}>
                                    {{ _trans('common.Unmarried') }}</option>
                            </select>
                            @if ($errors->has('marital_status'))
                            <div class="error">{{ $errors->first('marital_status') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ _trans('common.Is free Location?') }} <span
                                    class="text-danger">*</span></label>
                            <select name="is_free_location" id="is_free_location" class="form-select ot_input select2"
                                required>
                                <option value="" disabled>{{ _trans('common.Choose One') }}
                                </option>
                                <option value="1" {{ $data['show']->is_free_location == 1 ? 'selected' : '' }}>
                                    {{ _trans('common.Yes') }}</option>
                                <option value="0" {{ $data['show']->is_free_location == 0 ? 'selected' : '' }}>
                                    {{ _trans('common.No') }}</option>
                            </select>
                            @if ($errors->has('is_free_location'))
                            <div class="error">{{ $errors->first('is_free_location') }}</div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
            <div class="col-lg-12">
                <div class="col-md-12 text-right mt-3 mb-3 mr-5">
                    <div class="form-group d-flex justify-content-end">
                        @if (hasPermission('user_create'))
                        <button type="submit" class="crm_theme_btn ">{{ _trans('common.Update')
                            }}</button>
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