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
                            <label for="name" class="form-label">{{ _trans('common.Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control ot-form-control ot_input"
                                placeholder="{{ _trans('common.Name') }}" value="{{ old('name') }}" required>
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
                                value="{{ old('email') }}" required>
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
                                value="{{ old('phone') }}" required>
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
                                id="_country_id" required></select>
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
                                class="form-control ot-form-control ot_input" value="{{ old('joining_date') }}"
                                required>
                            @if ($errors->has('joining_date'))
                            <div class="error">{{ $errors->first('joining_date') }}</div>
                            @endif
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
                                <option value="{{ $department->id }}">{{ $department->title }}
                                </option>
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
                                <option value="{{ $shift->id }}">{{ $shift->name }}
                                </option>
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
                                <option value="{{ $designation->id }}">
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
                                class="form-select demo-select2-placeholder {{ $errors->has('gender') ? 'is-invalid' : '' }} select2">
                                <option disabled selected>{{ _trans('common.Choose One') }}
                                </option>
                                <option value="Male" {{ old('gender')==0 ? 'selected' : '' }}>
                                    {{ _trans('common.Male') }}</option>
                                <option value="Female" {{ old('gender')==1 ? 'selected' : '' }}>
                                    {{ _trans('common.Female') }}</option>
                                <option value="Unisex" {{ old('gender')==2 ? 'selected' : '' }}>
                                    {{ _trans('common.Unisex') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="name">{{ _trans('common.Address') }}</label>
                            <input type="text" name="address" placeholder={{ _trans('common.Address') }}
                                autocomplete="off" class="form-control ot-form-control ot_input"
                                value="{{ old('address') }}">
                            @if ($errors->has('address'))
                            <div class="error">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="gender">{{ _trans('common.Date Of Birth') }}</label>
                            <input type="date" name="birth_date" autocomplete="off"
                                class="form-control ot-form-control ot_input" value="{{ old('birth_date') }}">
                            @if ($errors->has('birth_date'))
                            <div class="error">{{ $errors->first('birth_date') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="gender">{{ _trans('common.Religion') }}</label>

                            <select name="religion" id="religion" class="form-select select2">
                                <option disabled selected>{{ _trans('common.Choose One') }}
                                </option>
                                <option value="Islam" {{ old('religion')=='Islam' ? 'selected' : '' }}>
                                    {{ _trans('common.Islam') }}
                                </option>
                                <option value="Hindu" {{ old('religion')=='Hindu' ? 'selected' : '' }}>
                                    {{ _trans('common.Hindu') }}
                                </option>
                                <option value="Christan" {{ old('religion')=='Christan' ? 'selected' : '' }}>
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
                                <option value="Married" {{ old('marital_status')=='Married' ? 'selected' : '' }}>
                                    {{ _trans('common.Married') }}</option>
                                <option value="Unmarried" {{ old('marital_status')=='Unmarried' ? 'selected' : '' }}>
                                    {{ _trans('common.Unmarried') }}</option>
                            </select>
                            @if ($errors->has('marital_status'))
                            <div class="error">{{ $errors->first('marital_status') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="blood_group">{{ _trans('common.Blood Group') }}</label>
                            <select name="blood_group" class="form-select select2">
                                <option disabled selected>{{ _trans('common.Choose One') }}
                                </option>
                                <option value="A+" {{ old('blood_group')=='A+' ? 'selected' : '' }}>{{
                                    _trans('common.A+') }}
                                </option>
                                <option value="A-" {{ old('blood_group')=='A-' ? 'selected' : '' }}>{{
                                    _trans('common.A-') }}
                                </option>
                                <option value="B+" {{ old('blood_group')=='B+' ? 'selected' : '' }}>{{
                                    _trans('common.B+') }}
                                </option>
                                <option value="B-" {{ old('blood_group')=='B-' ? 'selected' : '' }}>{{
                                    _trans('common.B-') }}
                                </option>
                                <option value="O+" {{ old('blood_group')=='O+' ? 'selected' : '' }}>{{
                                    _trans('common.O+') }}
                                </option>
                                <option value="O-" {{ old('blood_group')=='O-' ? 'selected' : '' }}>{{
                                    _trans('common.O-') }}
                                </option>
                                <option value="AB+" {{ old('blood_group')=='AB+' ? 'selected' : '' }}>
                                    {{ _trans('common.AB+') }}
                                </option>
                                <option value="AB-" {{ old('blood_group')=='AB-' ? 'selected' : '' }}>
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
                            <label class="form-label" for="name" class="form-label">{{ _trans('common.Basic Salary') }}
                                <span class="text-danger">*</span></label>
                            <input type="number" name="basic_salary" placeholder="{{ _trans('common.Salary') }}"
                                autocomplete="off" class="form-control ot-form-control ot_input"
                                value="{{ old('basic_salary') }}" required>
                            @if ($errors->has('basic_salary'))
                            <div class="error">{{ $errors->first('basic_salary') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="name" class="form-label">{{ _trans('common.Password') }}
                                <span class="text-danger">*</span></label>
                            <input type="password" name="password" placeholder="{{ _trans('common.Password') }}"
                                autocomplete="off" class="form-control ot-form-control ot_input"
                                value="{{ old('password') }}" required>
                            @if ($errors->has('password'))
                            <div class="error">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="name" class="form-label">{{ _trans('common.Confirm Password')
                                }} <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation"
                                placeholder="{{ _trans('common.Confirm Password') }}" autocomplete="off"
                                class="form-control ot-form-control ot_input" value="{{ old('password_confirmation') }}"
                                required>
                            @if ($errors->has('password_confirmation'))
                            <div class="error">{{ $errors->first('password_confirmation') }}
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="role-permisssion-control">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name" class="form-label">{{ _trans('common.Role') }}
                                    <span class="text-danger">*</span></label> [<a href="javascript:;" role="button"
                                    onclick="mainModalOpen(`{{ route('role.create_modal') }}`)"> <span><i
                                            class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline"> <strong> {{ _trans('common.Add Role') }}
                                        </strong> </span> </a>]
                                <select name="role_id" class="form-select change-role select2" required>
                                    <option value="" disabled selected>
                                        {{ _trans('common.Choose One') }}</option>
                                    @foreach ($data['roles'] as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="table-content table-basic">

                            <div class="table-responsive ">
                                <table class="table role-create-table role-permission " id="permissions-table">
                                    <thead class="thead border-bottom-0">
                                        <tr class="border-bottom-0">
                                            <th class="border-bottom-0" scope="col">{{ _trans('common.Module') }}/
                                                {{ _trans('common.Sub Module') }}</th>
                                            <th class="border-bottom-0" scope="col">{{ _trans('common.Permissions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach ($data['permissions'] as $permission)
                                        <tr class="bg-transparent border-bottom-0">
                                            <td colspan="5" class="p-0 border-bottom-0">
                                                <div class="accordion accordion-role mb-3">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#toggle-{{ $permission->id }}"
                                                                aria-expanded="false"
                                                                aria-controls="toggle-{{ $permission->id }}">
                                                                <div class="input-check-radio">
                                                                    <div class="form-check">
                                                                        <input type="checkbox"
                                                                            class="form-check-input mt-0 read check_all outer-check-item"
                                                                            name="check_all" id="check_all">
                                                                        <label class="form-check-label ml-6"
                                                                            for="#"><span>{{ ucfirst(str_replace('_', '
                                                                                ', __($permission->attribute)))
                                                                                }}</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="toggle-{{ $permission->id }}"
                                                            class="accordion-collapse collapse">
                                                            <div class="accordion-body d-flex flex-wrap">
                                                                @foreach ($permission->keywords as $key => $keyword)
                                                                <div class="input-check-radio mr-16">
                                                                    <div class="form-check">
                                                                        @if ($keyword != '')
                                                                        <input type="checkbox"
                                                                            class="form-check-input mt-0 read common-key inner-check-item"
                                                                            name="permissions[]" value="{{ $keyword }}"
                                                                            id="{{ $keyword }}">
                                                                        <label class="form-check-label"
                                                                            for="{{ $keyword }}">{{
                                                                            ucfirst(str_replace('_', ' ', __($key)))
                                                                            }}</label>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="d-flex justify-content-end">
                        <div class="form-group mt-3 mb-3">
                            @if (hasPermission('user_create'))
                            <button type="submit" class="crm_theme_btn mr-3">{{ _trans('common.Submit')
                                }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
</div>

@endsection
@section('script')
<script src="{{ url('public/backend/js/pages/__profile.js') }}"></script>
@endsection