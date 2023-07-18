@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper">


        <!-- Main content -->
        <section class="content p-0">
            <div class="container-fluid border-radius-5 p-imp-30">
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="">
                            <form method="POST" action="{{ route('user.store') }}" class="card" enctype="multipart/form-data">
                                @csrf
                                <div class="row m-3" >
                                        <div class="col-sm-6 col-12">
                                            <h3 class="m-0 text-dark">{{ @$data['title'] }}</h3>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="float-sm-right mb-3">
                                                @if(hasPermission('role_read'))
                                                    <a href="{{ route('user.index') }}" class="btn btn-primary float-right float-left-sm-device"> <i class="fa fa-arrow-left"></i> {{ _trans('common.Back') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="name" class="form-label">{{ _trans('common.Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control"
                                                       placeholder="{{ _trans('common.Name') }}" value="{{ old('name') }}"
                                                       required>
                                                @if ($errors->has('name'))
                                                    <div class="error">{{ $errors->first('name') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="form-label">{{ _trans('common.Email') }} <span class="text-danger">*</span></label>
                                                <input type="email"
                                                       name="email"
                                                       placeholder={{ _trans("common.Email")}}
                                                               autocomplete="off"
                                                       class="form-control" value="{{ old('email') }}" required>
                                                @if ($errors->has('email'))
                                                    <div class="error">{{ $errors->first('email') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="form-label">{{ _trans('common.Phone') }} <span class="text-danger">*</span></label>
                                                <input type="number"
                                                       name="phone"
                                                       placeholder={{ _trans("common.Phone")}}
                                                               autocomplete="off"
                                                       class="form-control" value="{{ old('phone') }}" required>
                                                @if ($errors->has('phone'))
                                                    <div class="error">{{ $errors->first('phone') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="joining_date" class="form-label">{{ _trans('common.Joining Date') }} <span class="text-danger">*</span></label>
                                                <input type="date"
                                                       name="joining_date"
                                                       autocomplete="off"
                                                       class="form-control" value="{{ old('joining_date') }}" required>
                                                @if ($errors->has('joining_date'))
                                                    <div class="error">{{ $errors->first('joining_date') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name"
                                                       class="form-label">{{ _trans('common.Department') }} <span class="text-danger">*</span></label> <a
                                                        href="{{ route('department.create') }}" target="_blank">{{ _trans('common.Add department') }}</a>
                                                <select name="department_id" class="form-control"
                                                        required>
                                                    <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                                    @foreach ( $data['departments'] as $department)
                                                        <option value="{{$department->id}}">{{ $department->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="designation_id"
                                                       class="form-label">{{ _trans('common.Designation') }} <span class="text-danger">*</span></label> <a
                                                        href="{{ route('designation.create') }}" target="_blank">{{ _trans('common.Add designation') }}</a>
                                                <select name="designation_id" class="form-control"
                                                        required>
                                                    <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                                    @foreach ( $data['designations'] as $designation)
                                                        <option value="{{$designation->id}}">{{ $designation->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="form-label">{{ _trans('common.Gender') }} <span class="text-danger">*</span></label>
                                                <select name="gender" required
                                                        class="form-control demo-select2-placeholder {{ $errors->has('gender') ? 'is-invalid' : ''}}">
                                                    <option disabled selected>{{ _trans('common.Choose One') }}</option>
                                                    <option value="Male" {{old('gender') == 0?'selected':''}}>{{_trans('common.Male')}}</option>
                                                    <option value="Female" {{old('gender') == 1?'selected':''}}>{{_trans('common.Female')}}</option>
                                                    <option value="Unisex" {{old('gender') == 2?'selected':''}}>{{_trans('common.Unisex')}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">{{ _trans('common.Address') }}</label>
                                                <input type="text"
                                                       name="address"
                                                       placeholder={{_trans("common.Address")}}
                                                               autocomplete="off"
                                                       class="form-control" value="{{ old('address') }}">
                                                @if ($errors->has('address'))
                                                    <div class="error">{{ $errors->first('address') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="gender">{{ _trans('common.Date Of Birth') }}</label>
                                                <input type="date"
                                                       name="birth_date"
                                                       autocomplete="off"
                                                       class="form-control" value="{{ old('birth_date') }}">
                                                @if ($errors->has('birth_date'))
                                                    <div class="error">{{ $errors->first('birth_date') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="gender">{{ _trans('common.Religion') }}</label>

                                                <select name="religion" id="religion" class="form-control">
                                                    <option disabled selected>{{ _trans('common.Choose One') }}</option>
                                                    <option value="Islam" {{old('religion') == 'Islam'?'selected':''}}>
                                                        {{ _trans('common.Islam') }}
                                                    </option>
                                                    <option value="Hindu" {{old('religion') == 'Hindu'?'selected':''}}>
                                                        {{ _trans('common.Hindu') }}
                                                    </option>
                                                    <option value="Christan" {{old('religion') == 'Christan'?'selected':''}}>
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
                                                <label for="marital_status">{{ _trans('common.Marital Status') }}</label>
                                                <select name="marital_status" id="religion" class="form-control">
                                                    <option disabled selected>{{ _trans('common.Choose One') }}</option>
                                                    <option value="Married" {{old('marital_status') == 'Married'?'selected':''}}>{{_trans('common.Married')}}</option>
                                                    <option value="Unmarried" {{old('marital_status') == 'Unmarried'?'selected':''}}>{{_trans('common.Unmarried')}}</option>
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
                                                    <option disabled selected>{{ _trans('common.Choose One') }}</option>
                                                    <option value="A+" {{old('blood_group') == 'A+'?'selected':''}}>{{ _trans('common.A+') }}
                                                    </option>
                                                    <option value="A-" {{old('blood_group') == 'A-'?'selected':''}}>{{ _trans('common.A-') }}
                                                    </option>
                                                    <option value="B+" {{old('blood_group') == 'B+'?'selected':''}}>{{ _trans('common.B+') }}
                                                    </option>
                                                    <option value="B-" {{old('blood_group') == 'B-'?'selected':''}}>{{ _trans('common.B-') }}
                                                    </option>
                                                    <option value="O+" {{old('blood_group') == 'O+'?'selected':''}}>{{ _trans('common.O+') }}
                                                    </option>
                                                    <option value="O-" {{old('blood_group') == 'O-'?'selected':''}}>{{ _trans('common.O-') }}
                                                    </option>
                                                    <option value="AB+" {{old('blood_group') == 'AB+'?'selected':''}}>
                                                        {{ _trans('common.AB+') }}
                                                    </option>
                                                    <option value="AB-" {{old('blood_group') == 'AB-'?'selected':''}}>
                                                        {{ _trans('common.AB-') }}
                                                    </option>
                                                </select>
                                                @if ($errors->has('blood_group'))
                                                    <div class="error">{{ $errors->first('blood_group') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="form-label">{{ _trans('common.Basic Salary') }} <span class="text-danger">*</span></label>
                                                <input type="number"
                                                       name="basic_salary"
                                                       placeholder={{_trans("common.Salary")}}
                                                               autocomplete="off"
                                                       class="form-control" value="{{ old('basic_salary') }}" required>
                                                @if ($errors->has('basic_salary'))
                                                    <div class="error">{{ $errors->first('basic_salary') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="form-label">{{ _trans('common.Password') }} <span class="text-danger">*</span></label>
                                                <input type="password"
                                                       name="password"
                                                       placeholder={{_trans("common.Password")}}
                                                               autocomplete="off"
                                                       class="form-control" value="{{ old('password') }}" required>
                                                @if ($errors->has('password'))
                                                    <div class="error">{{ $errors->first('password') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name"
                                                       class="form-label">{{ _trans('common.Confirm Password') }} <span class="text-danger">*</span></label>
                                                <input type="password"
                                                       name="password_confirmation"
                                                       placeholder="{{_trans("common.password")}}"
                                                       autocomplete="off"
                                                       class="form-control" value="{{ old('password_confirmation') }}"
                                                       required>
                                                @if ($errors->has('password_confirmation'))
                                                    <div class="error">{{ $errors->first('password_confirmation') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-8">
                                        <div class="col-md-6 mt-3">
                                            <div class="form-group">
                                                <label for="name" class="form-label">{{ _trans('common.Role') }} <span class="text-danger">*</span></label> <a
                                                        href="{{ route('roles.create') }}" target="_blank">{{ _trans('common.Add role') }}</a>
                                                <select name="role_id" class="form-control change-role select2" required>
                                                    <option value="" disabled
                                                            selected>{{ _trans('common.Choose One') }}</option>
                                                    @foreach ($data['roles'] as $role)
                                                        <option value="{{$role->id}}">{{ $role->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-inner ">
                                            <table class="table role-create-table role-permission "
                                                   id="permissions-table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">{{_trans('common.Module')}}/{{_trans('common.Sub-module')}}</th>
                                                    <th scope="col">{{_trans('common.Permissions')}}  </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($data['permissions'] as $permission)
                                                    <tr>
                                                        <td>
                                                            <span class="text-capitalize">{{ucfirst(str_replace('_',' ',__($permission->attribute)))}}</span>
                                                        </td>

                                                        <td>
                                                            @foreach($permission->keywords as $key=>$keyword)
                                                            <div class="input-check-radio">
                                                                <div class="form-check">
                                                                    @if($keyword != "")
                                                                        <input type="checkbox"
                                                                               class="form-check-input mt-0 read common-key"
                                                                               name="permissions[]" value="{{$keyword}}"
                                                                               id="{{$keyword}}">
                                                                        <label class="form-check-label ml-6"
                                                                               for="{{$keyword}}">{{ucfirst(str_replace('_',' ',__($key)))}}</label>
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
                                                        @if(hasPermission('user_create'))
                                                            <button type="submit"
                                                                    class="btn btn-sm btn-primary submit-margin-right">{{{_trans('common.submit')}}}</button>
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
        </section>
    </div>
@endsection
@section('script')
<script src="{{ asset('public/backend/js/_roles.js') }}"></script>
@endsection

