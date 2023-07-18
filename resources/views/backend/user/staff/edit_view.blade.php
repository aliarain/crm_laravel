@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ @$data['title'] }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ @$data['title'] }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>


        <!-- Main content -->
        <section class="content ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ route('user.update', $data['show']->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row">
                                        <div class=" col-12">
                                            <div class="float-right mb-3  text-right">
                                                @if (hasPermission('role_read'))
                                                    <a href="{{ route('user.index') }}" class="btn btn-primary"> <i
                                                            class="fa fa-arrow-left"></i>{{ _trans('common.Back') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label for="name"
                                                        class="form-label">{{ _trans('common.Name') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control"
                                                        placeholder="{{ _trans('common.Name') }}"
                                                        value="{{ $data['show']->name }}" required>
                                                    @if ($errors->has('name'))
                                                        <div class="error">{{ $errors->first('name') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name"
                                                        class="form-label">{{ _trans('common.Email') }} <span class="text-danger">*</span></label>
                                                    <input type="email" name="email"
                                                        placeholder="{{ _trans('common.Email') }}" autocomplete="off"
                                                        class="form-control" value="{{ $data['show']->email }}" required>
                                                    @if ($errors->has('email'))
                                                        <div class="error">{{ $errors->first('email') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name"
                                                        class="form-label">{{ _trans('common.Phone') }} <span class="text-danger">*</span></label>
                                                    <input type="number" name="phone"
                                                        placeholder="{{ _trans('common.Phone') }}" autocomplete="off"
                                                        class="form-control" value="{{ $data['show']->phone }}" required>
                                                    @if ($errors->has('phone'))
                                                        <div class="error">{{ $errors->first('phone') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="joining_date"
                                                        class="form-label">{{ _trans('common.Joining Date') }} <span class="text-danger">*</span></label>
                                                    <input type="date" name="joining_date" autocomplete="off"
                                                        class="form-control" value="{{ $data['show']->joining_date }}"
                                                        required>
                                                    @if ($errors->has('joining_date'))
                                                        <div class="error">{{ $errors->first('joining_date') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="shift_id"
                                                        class="form-label">{{ _trans('common.Shift') }} <span class="text-danger">*</span></label>

                                                    <select name="shift_id" class="form-control" required>
                                                        <option value="" disabled>{{ _trans('common.Choose One') }}
                                                        </option>
                                                        @foreach ($data['shifts'] as $shift)
                                                            <option value="{{ $shift->id }}"
                                                                {{ $data['show']->shift_id == $shift->id ? 'selected' : '' }}>
                                                                {{ $shift->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name"
                                                        class="form-label">{{ _trans('common.Department') }} <span class="text-danger">*</span></label> <a
                                                        href="{{ route('department.create') }}"
                                                        target="_blank">{{ _trans('common.Add department') }}</a>
                                                    <select name="department_id" class="form-control" required>
                                                        <option value="" disabled>{{ _trans('common.Choose One') }}
                                                        </option>
                                                        @foreach ($data['departments'] as $department)
                                                            <option value="{{ $department->id }}"
                                                                {{ $data['show']->department_id == $department->id ? 'selected' : '' }}>
                                                                {{ $department->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="designation_id"
                                                        class="form-label">{{ _trans('common.Designation') }} <span class="text-danger">*</span></label> <a
                                                        href="{{ route('designation.create') }}"
                                                        target="_blank">{{ _trans('common.Add designation') }} </a>
                                                    <select name="designation_id" class="form-control" required>
                                                        <option value="" disabled>{{ _trans('common.Choose One') }}
                                                        </option>
                                                        @foreach ($data['designations'] as $designation)
                                                            <option value="{{ $designation->id }}"
                                                                {{ $data['show']->designation_id == $designation->id ? 'selected' : '' }}>
                                                                {{ $designation->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name"
                                                        class="form-label">{{ _trans('common.Gender') }} <span class="text-danger">*</span></label>
                                                    <select name="gender" required
                                                        class="form-control demo-select2-placeholder {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                                                        <option disabled selected>{{ _trans('common.Choose One') }}
                                                        </option>
                                                        <option value="Male"
                                                            {{ $data['show']->gender == 'Male' ? 'selected' : '' }}>
                                                            {{ _trans('common.Male') }}</option>
                                                        <option value="Female"
                                                            {{ $data['show']->gender == 'Female' ? 'selected' : '' }}>
                                                            {{ _trans('common.Female') }}</option>
                                                        <option value="Unisex"
                                                            {{ $data['show']->gender == 'Unisex' ? 'selected' : '' }}>
                                                            {{ _trans('common.Unisex') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name">{{ _trans('common.Address') }}</label>
                                                    <input type="text" name="address"
                                                        placeholder={{ _trans('common.Address') }} autocomplete="off"
                                                        class="form-control" value="{{ $data['show']->address }}">
                                                    @if ($errors->has('address'))
                                                        <div class="error">{{ $errors->first('address') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="gender">{{ _trans('common.Date Of Birth') }}</label>
                                                    <input type="date" name="birth_date" autocomplete="off"
                                                        class="form-control" value="{{ $data['show']->birth_date }}">
                                                    @if ($errors->has('birth_date'))
                                                        <div class="error">{{ $errors->first('birth_date') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="gender">{{ _trans('common.Religion') }}</label>

                                                    <select name="religion" id="religion" class="form-control">
                                                        <option disabled selected>{{ _trans('common.Choose One') }}
                                                        </option>
                                                        <option value="Islam"
                                                            {{ $data['show']->religion == 'Islam' ? 'selected' : '' }}>
                                                            {{ _trans('common.Islam') }}
                                                        </option>
                                                        <option value="Hindu"
                                                            {{ $data['show']->religion == 'Hindu' ? 'selected' : '' }}>
                                                            {{ _trans('common.Hindu') }}
                                                        </option>
                                                        <option value="Christan"
                                                            {{ $data['show']->religion == 'Christan' ? 'selected' : '' }}>
                                                            {{ _trans('common.Christan') }}
                                                        </option>
                                                    </select>
                                                    @if ($errors->has('religion'))
                                                        <div class="error">{{ $errors->first('religion') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label
                                                        for="marital_status">{{ _trans('common.Marital Status') }}</label>
                                                    <select name="marital_status" id="religion" class="form-control">
                                                        <option disabled selected>{{ _trans('common.Choose One') }}
                                                        </option>
                                                        <option value="Married"
                                                            {{ $data['show']->marital_status == 'Married' ? 'selected' : '' }}>
                                                            {{ _trans('Married') }}</option>
                                                        <option value="Unmarried"
                                                            {{ $data['show']->marital_status == 'Unmarried' ? 'selected' : '' }}>
                                                            {{ _trans('Unmarried') }}</option>
                                                    </select>
                                                    @if ($errors->has('marital_status'))
                                                        <div class="error">{{ $errors->first('marital_status') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="blood_group">{{ _trans('common.Blood Group') }}</label>
                                                    <select name="blood_group" class="form-control">
                                                        <option disabled selected>{{ _trans('common.Choose One') }}
                                                        </option>
                                                        <option value="A+"
                                                            {{ $data['show']->blood_group == 'A+' ? 'selected' : '' }}>
                                                            {{ _trans('A+') }}
                                                        </option>
                                                        <option value="A-"
                                                            {{ $data['show']->blood_group == 'A-' ? 'selected' : '' }}>
                                                            {{ _trans('A-') }}
                                                        </option>
                                                        <option value="B+"
                                                            {{ $data['show']->blood_group == 'B+' ? 'selected' : '' }}>
                                                            {{ _trans('B+') }}
                                                        </option>
                                                        <option value="B-"
                                                            {{ $data['show']->blood_group == 'B-' ? 'selected' : '' }}>
                                                            {{ _trans('B-') }}
                                                        </option>
                                                        <option value="O+"
                                                            {{ $data['show']->blood_group == 'O+' ? 'selected' : '' }}>
                                                            {{ _trans('O+') }}
                                                        </option>
                                                        <option value="O-"
                                                            {{ $data['show']->blood_group == 'O-' ? 'selected' : '' }}>
                                                            {{ _trans('O-') }}
                                                        </option>
                                                        <option value="AB+"
                                                            {{ $data['show']->blood_group == 'AB+' ? 'selected' : '' }}>
                                                            {{ _trans('AB+') }}
                                                        </option>
                                                        <option value="AB-"
                                                            {{ $data['show']->blood_group == 'AB-' ? 'selected' : '' }}>
                                                            {{ _trans('AB-') }}
                                                        </option>
                                                    </select>
                                                    @if ($errors->has('blood_group'))
                                                        <div class="error">{{ $errors->first('blood_group') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name"
                                                        class="form-label">{{ _trans('common.Basic Salary') }} <span class="text-danger">*</span></label>
                                                    <input type="number" name="basic_salary"
                                                        placeholder={{ _trans('common.Basic Salary') }}"
                                                        autocomplete="off" class="form-control"
                                                        value="{{ $data['show']->basic_salary }}" required>
                                                    @if ($errors->has('basic_salary'))
                                                        <div class="error">{{ $errors->first('basic_salary') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-8">
                                            <div class="col-md-6 mt-3">
                                                <div class="form-group">
                                                    <label for="name"
                                                        class="form-label">{{ _trans('common.Role') }}</label> <a
                                                        href="{{ route('roles.create') }}"
                                                        target="_blank">{{ _trans('common.Add Role') }}</a>
                                                    <select name="role_id" class="form-control change-role" required>
                                                        <option value="" disabled selected>
                                                            {{ _trans('common.Choose One') }}</option>
                                                        @foreach ($data['roles'] as $role)
                                                            <option value="{{ $role->id }}"
                                                                {{ $data['show']->role_id == $role->id ? 'selected' : '' }}>
                                                                {{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card-inner ">
                                                <table class="table role-create-table role-permission "
                                                    id="permissions-table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">{{ _trans('common.Module') }}/
                                                                {{ _trans('common.Sub Module') }}</th>
                                                            <th scope="col">{{ _trans('common.Permissions') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data['permissions'] as $permission)
                                                            <tr>
                                                                <td>
                                                                    <span
                                                                        class="text-capitalize">{{ __($permission->attribute) }}</span>
                                                                </td>

                                                                <td>
                                                                    @foreach ($permission->keywords as $key => $keyword)
                                                                        <div class="input-check-radio">
                                                                            <div class="form-check">
                                                                                @if ($keyword != '')
                                                                                    <input type="checkbox"
                                                                                        class="form-check-input mt-0 read"
                                                                                        id="{{ $keyword }}"
                                                                                        name="permissions[]"
                                                                                        value="{{ $keyword }}"
                                                                                        {{ in_array($keyword, @$data['show']->permissions) ? 'checked' : '' }}>
                                                                                    <label class="form-check-label ml-6"
                                                                                        for="{{ $keyword }}">{{ Str::title(str_replace('_', ' ', __($key))) }}</label>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach

                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                                <div class="row">
                                                    <div class="col-md-12 text-right mt-4 mr-5">
                                                        <div class="form-group">
                                                            @if (hasPermission('user_create'))
                                                                <button  type="submit"
                                                                    class="btn btn-sm btn-primary mt-4">{{ _trans('common.Submit') }}</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ url('public/backend/js/pages/__profile.js') }}"></script>
@endsection
