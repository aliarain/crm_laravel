@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper dashboard-wrapper mt-30">
        <!-- Main content -->
        <section class="content p-0">
            @include('backend.partials.user_navbar')
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
            <div class="main-panel">
                <div class="vertical-tab">
                    <div class="row no-gutters">
                        <div class="col-md-3 pr-md-3 tab-menu">
                            <div class="card card-with-shadow border-0">
                            
                                <div class="px-primary py-primary">
                                    <div role="tablist" aria-orientation="vertical"
                                         class="nav flex-column nav-pills">
                                         <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="profile-user-img img-fluid img-circle"
                                                     src="{{ $data['show']->original['data']['avatar'] }}"
                                                     alt="User profile picture">
                                            </div>

                                            <h3 class="profile-username text-center">{{ @$data['show']->name }}</h3>

                                        </div>
                                        {{-- Vertical Tab start here --}}

                                        <a href="{{ route('user.edit.profile',[$data['id'],'official']) }}"
                                           class="text-capitalize tab-item-link d-flex justify-content-between my-2 my-sm-3 {{ url()->current() === route('user.edit.profile',[$data['id'],'official']) ? 'active' : '' }}"><span>{{ _trans('common.Official') }}</span>
                                            <span class="active-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                                           width="24" height="24"
                                                                           viewBox="0 0 24 24" fill="none"
                                                                           stroke="currentColor" stroke-width="2"
                                                                           stroke-linecap="round"
                                                                           stroke-linejoin="round"
                                                                           class="feather feather-chevron-right">
                                                                    <polyline points="9 18 15 12 9 6"></polyline>
                                                                </svg></span></a>

                                        <a href="{{ route('user.edit.profile',[$data['id'],'personal']) }}"
                                           class="text-capitalize tab-item-link d-flex justify-content-between my-2 my-sm-3 {{ url()->current() === route('user.edit.profile',[$data['id'],'personal']) ? 'active' : '' }}"><span>{{ _trans('common.Personal') }}</span>
                                            <span class="active-icon"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="feather feather-chevron-right">
                                                                    <polyline points="9 18 15 12 9 6"></polyline>
                                                                </svg></span>
                                        </a>


                                        @if(auth()->user()->role_id!=1)
                                        <a href="{{ route('user.edit.profile',[$data['id'],'financial']) }}"
                                           class="text-capitalize tab-item-link d-flex justify-content-between my-2 my-sm-3 {{ url()->current() === route('user.edit.profile',[$data['id'],'financial']) ? 'active' : '' }}"><span>{{ _trans('common.Financial') }}</span>
                                            <span class="active-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                                           width="24" height="24"
                                                                           viewBox="0 0 24 24" fill="none"
                                                                           stroke="currentColor" stroke-width="2"
                                                                           stroke-linecap="round"
                                                                           stroke-linejoin="round"
                                                                           class="feather feather-chevron-right">
                                                                    <polyline points="9 18 15 12 9 6"></polyline>
                                                                </svg></span></a>
                                                
                                            <a href="{{ route('user.edit.profile',[$data['id'],'emergency']) }}"
                                                class="text-capitalize tab-item-link d-flex justify-content-between my-2 my-sm-3 {{ url()->current() === route('user.edit.profile',[$data['id'],'emergency']) ? 'active' : '' }}"><span>{{ _trans('common.Emergency') }}</span>
                                                <span class="active-icon"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-chevron-right">
                                                            <polyline points="9 18 15 12 9 6"></polyline>
                                                        </svg></span>
                                            </a>
                                            @endif
                                            <a href="{{ route('user.edit.profile',[$data['id'],'security']) }}"
                                                class="text-capitalize tab-item-link d-flex justify-content-between my-2 my-sm-3 {{ url()->current() === route('user.edit.profile',[$data['id'],'security']) ? 'active' : '' }}"><span>{{ _trans('common.Security') }}</span>
                                                <span class="active-icon"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-chevron-right">
                                                            <polyline points="9 18 15 12 9 6"></polyline>
                                                        </svg></span>
                                            </a>
                                        @if(auth()->user()->is_admin==1)
                                            <a href="{{ route('user.edit.profile',[$data['id'],'company']) }}"
                                            class="text-capitalize tab-item-link d-flex justify-content-between my-2 my-sm-3 {{ url()->current() === route('user.edit.profile',[$data['id'],'company']) ? 'active' : '' }}"><span>{{ _trans('common.Company') }}</span>
                                                <span class="active-icon"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-chevron-right">
                                                            <polyline points="9 18 15 12 9 6"></polyline>
                                                        </svg></span>
                                            </a>
                                        @endif


                                        {{-- Vertical Tab end here --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 pl-md-3 pt-md-0 pt-sm-4 pt-4">
                            <div class="card card-with-shadow border-0">
                                <div class="tab-content px-primary">

                                    @if(url()->current() === route('user.edit.profile',[$data['id'],'official']))
                                        <div id="Official" class="tab-pane active">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                                    {{ _trans('common.Official') }}</h5>
                                            </div>
                                            <div class="content py-primary">
                                                <div id="General-0">
                                                    <form action="{{ route('user.update.profile',[$data['id'],$data['slug']]) }}"
                                                          method="post">
                                                        @csrf
                                                        <input type="text" hidden name="user_id"
                                                               value="{{ $data['id'] }}">
                                                        <fieldset class="form-group mb-5">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize form-label">{{ _trans('common.name') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text" class="form-control"
                                                                                       name="name"
                                                                                       value="{{ $data['show']->original['data']['name'] ?? 'N/A' }}">
                                                                                @if ($errors->has('name'))
                                                                                    <small class="error">{{ $errors->first('name') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize form-label">{{ _trans('common.email') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text" class="form-control"
                                                                                       name="email"
                                                                                       value="{{ $data['show']->original['data']['email'] ?? 'N/A' }}">
                                                                                @if ($errors->has('email'))
                                                                                    <small class="error">{{ $errors->first('email') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row {{ !appSuperUser() ?'disabledbutton':'' }}" >
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.department') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <select name="department_id"
                                                                                        class="form-control">
                                                                                    <option value="" disabled>{{ _trans('common.Choose One') }}
                                                                                    </option>
                                                                                    @foreach($data['departments']->original['data']['departments'] as $department)
                                                                                        <option value="{{ $department->id }}" {{ $data['show']->original['data']['department_id'] == $department->id ? 'selected' :'' }}>{{ $department->title }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @if ($errors->has('department_id'))
                                                                                    <small class="error">{{ $errors->first('department_id') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row {{ !appSuperUser() ?'disabledbutton':'' }}">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.designation') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <select name="designation_id"
                                                                                        class="form-control">
                                                                                    <option value="" disabled>{{ _trans('common.Choose One') }}
                                                                                    </option>
                                                                                    @foreach($data['designations']->original['data']['designations'] as $designation)
                                                                                        <option value="{{ $designation->id }}" {{ $data['show']->original['data']['designation_id'] == $designation->id ? 'selected' :'' }}>{{ $designation->title }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @if ($errors->has('designation_id'))
                                                                                    <small class="error">{{ $errors->first('designation_id') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row {{ !appSuperUser() ?'disabledbutton':'' }}">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.joining date') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="date" name="joining_date"
                                                                                       class="form-control"
                                                                                       value="{{ dateFormet($data['show']->original['data']['joining_date_db'],'Y-m-d') }}">
                                                                                @if ($errors->has('joining_date'))
                                                                                    <small class="error">{{ $errors->first('joining_date') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row {{ !appSuperUser() ?'disabledbutton':'' }}">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.employee type') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <select name="employee_type"
                                                                                        class="form-control">
                                                                                    <option value="" disabled>{{ _trans('common.Choose One') }}
                                                                                    </option>
                                                                                    @foreach(config('hrm.employee_type') as $item)
                                                                                        <option value="{{ $item }}" {{ $data['show']->original['data']['employee_type'] == $item ? 'selected' :'' }}>{{ $item }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @if ($errors->has('employee_type'))
                                                                                    <small class="error">{{ $errors->first('employee_type') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.employee id') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text" name="employee_id"
                                                                                       class="form-control"
                                                                                       value="{{ $data['show']->original['data']['employee_id'] }}">
                                                                                @if ($errors->has('employee_id'))
                                                                                    <small class="error">{{ $errors->first('employee_id') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.manager') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <select name="manager_id"
                                                                                        class="form-control">
                                                                                    <option value="" disabled>{{ _trans('common.Choose One') }}
                                                                                    </option>
                                                                                    @foreach($data['managers'] as $manager)
                                                                                        <option value="{{ $manager->id }}" {{ $data['show']->original['data']['manager_id'] === $manager->id ? 'selected' :'' }}>{{ $manager->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @if ($errors->has('manager_id'))
                                                                                    <small class="error">{{ $errors->first('manager_id') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label
                                                                            class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.Free Location') }} <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <select name="is_free_location"
                                                                                    class="form-control" required>
                                                                                    <option value="4" {{ @$data['show']->original['data']['is_free_location'] == 4 ? 'Selected' : '' }}>
                                                                                        {{ _trans('common.In-active') }}
                                                                                    </option>
                                                                                    <option value="1" {{ @$data['show']->original['data']['is_free_location'] == 1 ? 'Selected' : '' }}>
                                                                                        {{ _trans('common.Active') }}
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.grade') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text" name="grade"
                                                                                       class="form-control"
                                                                                       value="{{ $data['show']->original['data']['grade'] }}">
                                                                                @if ($errors->has('grade'))
                                                                                    <small class="error">{{ $errors->first('grade') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <button type="submit"
                                                                        class="btn btn-primary float-right"><span>
                                                                </span> {{ _trans('common.Update') }}
                                                                </button>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(url()->current() === route('user.edit.profile',[$data['id'],'personal']))
                                        <div id="Official" class="tab-pane active">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                                    {{ _trans('common.Personal') }}</h5>
                                            </div>
                                            <div class="content py-primary">
                                                <div id="General-0">
                                                    <form action="{{ route('user.update.profile',[$data['id'],$data['slug']]) }}"
                                                          method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="text" hidden name="user_id"
                                                               value="{{ $data['id'] }}">
                                                        <fieldset class="form-group mb-5">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.gender') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <select name="gender"
                                                                                        class="form-control">
                                                                                    <option value="" disabled>{{ _trans('common.Choose One') }}
                                                                                    </option>
                                                                                    @foreach(config('hrm.gender') as $gender)
                                                                                        <option value="{{ $gender }}" {{ $gender == $data['show']->original['data']['gender'] ? 'selected' : '' }}>{{ $gender }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @if ($errors->has('gender'))
                                                                                    <small class="text-danger">{{ $errors->first('gender') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.phone') }} <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text"
                                                                                       class="form-control"
                                                                                       name="phone"
                                                                                       value="{{ $data['show']->original['data']['phone'] ?? '' }}"
                                                                                       required>
                                                                                @if ($errors->has('phone'))
                                                                                    <small class="text-danger">{{ $errors->first('phone') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.date of birth') }} </label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="date" class="form-control"
                                                                                       name="birth_date"
                                                                                       value="{{ dateFormet($data['show']->original['data']['birth_date_db'],'Y-m-d') }}">
                                                                                @if ($errors->has('birth_date'))
                                                                                    <small class="error">{{ $errors->first('birth_date') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.address') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text" class="form-control"
                                                                                       name="address"
                                                                                       value="{{ $data['show']->original['data']['address'] }}">
                                                                                @if ($errors->has('address'))
                                                                                    <small class="error">{{ $errors->first('address') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.nationality') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text" class="form-control"
                                                                                       name="nationality"
                                                                                       value="{{ $data['show']->original['data']['nationality'] }}">
                                                                                @if ($errors->has('nationality'))
                                                                                    <small class="error">{{ $errors->first('nationality') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.nid card number') }}</label>
                                                                        <div class="col-sm-7">
                                                                            <div>
                                                                                <input type="text" class="form-control"
                                                                                       name="nid_card_number"
                                                                                       value="{{ $data['show']->original['data']['nid_card_number'] }}">
                                                                                @if ($errors->has('nid_card_number'))
                                                                                    <small class="error">{{ $errors->first('nid_card_number') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <a href="{{ @$data['show']->original['data']['nid_card_id'] }}"
                                                                               target="_blank">
                                                                                <i class="fa fa-download"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.NID Picture') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <div class="custom-image-upload-wrapper">
                                                                                    <div class="image-area d-flex">
                                                                                        <img id="nid_card_file_preview" src="{{ @$data['show']->original['data']['nid_card_id'] }}" alt=""
                                                                                            class="img-fluid mx-auto my-auto">
                                                                                    </div>
                                                                                  <div class="input-area"><label id="upload-label" for="nid_card_file">
                                                                                            {{ _trans('common.Change NID') }}
                                                                                        </label> <input id="nid_card_file" name="nid_file" type="file" class="form-control d-none">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.passport') }}</label>
                                                                        <div class="col-sm-7">
                                                                            <div>
                                                                                <input type="text" class="form-control"
                                                                                       name="passport_number"
                                                                                       value="{{ $data['show']->original['data']['passport_number'] }}">
                                                                                @if ($errors->has('passport_number'))
                                                                                    <small class="error">{{ $errors->first('passport_number') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <a href="{{ @$data['show']->original['data']['passport_file'] }}"
                                                                               target="_blank">
                                                                                <i class="fa fa-download"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.Passport Picture') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <div class="custom-image-upload-wrapper">
                                                                                    <div class="image-area d-flex">
                                                                                        <img id="passport_card_file_preview" src="{{ @$data['show']->original['data']['passport_file'] }}" alt=""
                                                                                            class="img-fluid mx-auto my-auto">
                                                                                    </div>
                                                                                  <div class="input-area"><label id="upload-label" for="passport_card_file">
                                                                                            {{ _trans('common.Change Passport') }}
                                                                                        </label> <input id="passport_card_file" name="passport_file" type="file" class="form-control d-none">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.blood group') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <select name="blood_group"
                                                                                        class="form-control">
                                                                                    <option value="" disabled>{{ _trans('common.Choose One') }}
                                                                                    </option>
                                                                                    @foreach(config('hrm.blood_group') as $blood)
                                                                                        <option value="{{ $blood }}" {{ $blood == $data['show']->original['data']['blood_group'] ? 'selected' : '' }}>{{ $blood }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @if ($errors->has('blood_group'))
                                                                                    <small class="error">{{ $errors->first('blood_group') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.Facebook') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="url" name="facebook_link"
                                                                                       class="form-control"
                                                                                       value="{{ $data['show']->original['data']['facebook_link'] }}">
                                                                                @if ($errors->has('facebook_link'))
                                                                                    <small class="error">{{ $errors->first('facebook_link') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.Linkedin') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="url" name="linkedin_link"
                                                                                       class="form-control"
                                                                                       value="{{ $data['show']->original['data']['linkedin_link'] }}">
                                                                                @if ($errors->has('linkedin_link'))
                                                                                    <small class="error">{{ $errors->first('linkedin_link') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.Instagram') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="url" name="instagram_link"
                                                                                       class="form-control"
                                                                                       value="{{ $data['show']->original['data']['instagram_link'] }}">
                                                                                @if ($errors->has('instagram_link'))
                                                                                    <small class="error">{{ $errors->first('instagram_link') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.Profile Picture') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <div class="custom-image-upload-wrapper">
                                                                                    <div class="image-area d-flex">
                                                                                        <img id="uploaded_image_viewer" src="{{ @$data['show']->original['data']['avatar'] }}" alt=""
                                                                                            class="img-fluid mx-auto my-auto">
                                                                                    </div>
                                                                                  <div class="input-area"><label id="upload-label" for="image_upload_input">
                                                                                            {{ _trans('common.Change avatar') }}
                                                                                        </label> <input id="image_upload_input" name="avatar" type="file" class="form-control d-none">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div>
                                                                <button type="submit"
                                                                        class="btn btn-primary float-right"><span>
                                                                </span> {{ _trans('common.Update') }}
                                                                </button>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(url()->current() === route('user.edit.profile',[$data['id'],'financial']))
                                        <div id="Official" class="tab-pane active">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                                    {{ _trans('common.Financial') }}</h5>
                                            </div>
                                            <div class="content py-primary">
                                                <div id="General-0">
                                                    <form action="{{ route('user.update.profile',[$data['id'],$data['slug']]) }}"
                                                          method="post">
                                                        @csrf
                                                        <input type="text" hidden name="user_id"
                                                               value="{{ $data['id'] }}">
                                                        <fieldset class="form-group mb-5">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">tin</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text" name="tin"
                                                                                       class="form-control"
                                                                                       value="{{ $data['show']->original['data']['tin'] }}">
                                                                                @if ($errors->has('tin'))
                                                                                    <small class="error">{{ $errors->first('tin') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.bank name') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text" name="bank_name"
                                                                                       class="form-control"
                                                                                       value="{{ $data['show']->original['data']['bank_name'] }}">
                                                                                @if ($errors->has('bank_name'))
                                                                                    <small class="error">{{ $errors->first('bank_name') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.bank account') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text" name="bank_account"
                                                                                       class="form-control"
                                                                                       value="{{ $data['show']->original['data']['bank_account'] }}">
                                                                                @if ($errors->has('bank_account'))
                                                                                    <small class="error">{{ $errors->first('bank_account') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <button type="submit"
                                                                        class="btn btn-primary float-right"><span>
                                                                </span> {{ _trans('common.Update') }}
                                                                </button>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(url()->current() === route('user.edit.profile',[$data['id'],'salary']))
                                        <div id="Official" class="tab-pane active">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                                    {{ _trans('common.Salary') }}</h5>
                                            </div>
                                            <div class="content py-primary">
                                                <div id="General-0">
                                                    <form action="{{ route('user.update.profile',[$data['id'],$data['slug']]) }}"
                                                          method="post">
                                                        @csrf
                                                        <input type="text" hidden name="user_id"
                                                               value="{{ $data['id'] }}">
                                                        <fieldset class="form-group mb-5">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group row {{ !appSuperUser() ?'disabledbutton':'' }}">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">salary</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="number"
                                                                                       class="form-control"
                                                                                       name="basic_salary"
                                                                                       value="{{ $data['show']->original['data']['basic_salary'] }}">
                                                                                @if ($errors->has('basic_salary'))
                                                                                    <small class="error">{{ $errors->first('basic_salary') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <button type="submit"
                                                                        class="btn btn-primary float-right"><span>
                                                                </span> {{ _trans('common.Update') }}
                                                                </button>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(url()->current() === route('user.edit.profile',[$data['id'],'emergency']))
                                        <div id="Official" class="tab-pane active">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                                    {{ _trans('common.Emergency') }}</h5>
                                            </div>
                                            <div class="content py-primary">
                                                <div id="General-0">
                                                    <form action="{{ route('user.update.profile',[$data['id'],$data['slug']]) }}"
                                                          method="post">
                                                        @csrf
                                                        <input type="text" hidden name="user_id"
                                                               value="{{ $data['id'] }}">
                                                        <fieldset class="form-group mb-5">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.name') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text"
                                                                                       class="form-control"
                                                                                       name="emergency_name"
                                                                                       value="{{ $data['show']->original['data']['emergency_name'] }}">
                                                                                @if ($errors->has('emergency_name'))
                                                                                    <small class="error">{{ $errors->first('emergency_name') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ ('mobile number') }} <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="number"
                                                                                       class="form-control"
                                                                                       name="emergency_mobile_number"
                                                                                       value="{{ $data['show']->original['data']['emergency_mobile_number'] }}">
                                                                                @if ($errors->has('emergency_mobile_number'))
                                                                                    <small class="error">{{ $errors->first('emergency_mobile_number') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('common.relationship') }}</label>
                                                                        <div class="col-sm-9">
                                                                            <div>
                                                                                <input type="text"
                                                                                       class="form-control"
                                                                                       name="emergency_mobile_relationship"
                                                                                       value="{{ $data['show']->original['data']['emergency_mobile_relationship'] }}">
                                                                                @if ($errors->has('emergency_mobile_relationship'))
                                                                                    <small class="error">{{ $errors->first('emergency_mobile_relationship') }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                @if(hasPermission('user_update'))
                                                                    <button type="submit"
                                                                            class="btn btn-primary float-right"><span>
                                                                </span> {{ _trans('common.Update') }}
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(url()->current() === route('user.edit.profile',[$data['id'],'security']))
                                        <div id="Official" class="tab-pane active">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                                    {{ _trans('Security Info') }}</h5>
                                            </div>
                                            <div class="content py-primary">
                                                <div id="General-0">
                                                    <form action="{{ route('user.update.profile',[$data['id'],$data['slug']]) }}" method="post">
                                                        @csrf
                                                        <input type="text" hidden name="user_id"
                                                               value="{{ $data['id'] }}">
                                                        <fieldset class="form-group mb-5">
                                                            <div class="form-group row d-none">
                                                                <label for="old_password" class="col-sm-2 col-form-label">{{ _trans('Old Password') }} <span class="text-danger">*</span> </label>
                                                                <div class="col-sm-10">
                                                                    <input type="password" class="form-control" name="old_password"
                                                                           id="old_password" value="12345678" placeholder="********">
                                                                    <small class="text-danger __old_password"></small>
                                                                     {{-- error message --}}
                                                                    @if ($errors->has('old_password'))
                                                                        <small class="error">{{ $errors->first('old_password') }}</small>
                                                                    @endif
                                                                    @if(session()->has('password_errors'))
                                                                        <small class="error">{{ session()->get('password_errors') }}</small>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                            {{-- session has --}}

                                                            <div class="form-group row">
                                                                <label for="inputSkills" class="col-sm-2 col-form-label">{{ _trans('New Password') }} <span class="text-danger">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <input type="password" class="form-control" name="password"
                                                                           id="inputSkills" placeholder="********">
                                                                    <small class="text-danger __password"></small>
                                                                    {{-- error message --}}
                                                                    @if ($errors->has('password'))
                                                                        <small class="error">{{ $errors->first('password') }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="password_confirmation" class="col-sm-2 col-form-label">{{ _trans('Confirm Password') }} <span class="text-danger">*</span></label>
                                                                <div class="col-sm-10">
                                                                    <input type="password" class="form-control"
                                                                           id="password_confirmation"
                                                                           name="password_confirmation" placeholder="********">
                                                                    <small class="text-danger __password_confirmation"></small>
                                                                    {{-- error message --}}
                                                                    @if ($errors->has('password_confirmation'))
                                                                        <small class="error">{{ $errors->first('password_confirmation') }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div>
                                                                {{-- @if(hasPermission('user_update')) --}}
                                                                    <button type="submit"
                                                                            class="btn btn-primary float-right"><span>
                                                                </span> {{ _trans('Update') }}
                                                                    </button>
                                                                {{-- @endif --}}
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (url()->current() === route('user.edit.profile', [$data['id'], 'company']))
                                    <div id="Official" class="tab-pane active">
                                        <div class="d-flex justify-content-between">
                                            <h5
                                                    class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                                {{ _trans('Company Info') }}</h5>
                                        </div>
                                        <div class="content py-primary">
                                            <div id="General-0">
                                              <form action="{{ route('user.update.profile',[$data['id'],$data['slug']]) }}" method="post">
                                                    @csrf
                                                    <input type="text" hidden name="user_id" value="{{ $data['id'] }}">
                                                <fieldset class="form-group mb-5">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group row">
                                                                <label
                                                                        class="col-sm-3 col-form-label text-capitalize">{{ _trans('name') }}</label>
                                                                <div class="col-sm-9">
                                                                        <div>
                                                                            <input type="text" class="form-control" name="company_name"
                                                                                value="{{ $data['show']->original['data']['company_info']['company_name'] }}">
                                                                            @if ($errors->has('company_info'))
                                                                            <small class="error">{{ $errors->first('company_info') }}</small>
                                                                            @endif
                                                                        </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 col-form-label text-capitalize">{{ _trans('email') }}</label>
                                                                <div class="col-sm-9">
                                                                    <div>
                                                                        <input type="text" class="form-control" name="email"
                                                                            value="{{ @$data['show']->original['data']['company_info']['email'] }}">
                                                                        @if ($errors->has('email'))
                                                                        <small class="error">{{ $errors->first('email') }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group row">
                                                                <label
                                                                        class="col-sm-3 col-form-label text-capitalize">{{ _trans('phone') }}</label>
                                                                <div class="col-sm-9">
                                                                    <div>
                                                                        <input type="text" class="form-control" name="phone"
                                                                            value="{{ @$data['show']->original['data']['company_info']['phone'] }}">
                                                                        @if ($errors->has('phone'))
                                                                        <small class="error">{{ $errors->first('phone') }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group row">
                                                                <label
                                                                        class="col-sm-3 col-form-label text-capitalize">{{ _trans('total employee') }}</label>
                                                                <div class="col-sm-9">
                                                                    <div>
                                                                        <input type="text" class="form-control" name="total_employee"
                                                                            value="{{ @$data['show']->original['data']['company_info']['total_employee'] }}">
                                                                        @if ($errors->has('total_employee'))
                                                                        <small class="error">{{ $errors->first('total_employee') }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group row">
                                                                <label
                                                                        class="col-sm-3 col-form-label text-capitalize">{{ _trans('Business Type') }}</label>
                                                                <div class="col-sm-9">
                                                                    <div>
                                                                      <input type="text" class="form-control" name="business_type"
                                                                            value="{{ @$data['show']->original['data']['company_info']['business_type']}}">
                                                                        @if ($errors->has('business_type'))
                                                                        <small class="error">{{ $errors->first('business_type') }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group row">
                                                                <label
                                                                        class="col-sm-3 col-form-label text-capitalize">{{ _trans('trade licence number') }}</label>
                                                                <div class="col-sm-9">
                                                                    <div>
                                                                        <input type="text" class="form-control" name="trade_licence_number"
                                                                            value="{{ @$data['show']->original['data']['company_info']['trade_licence_number'] }}">
                                                                        @if ($errors->has('trade_licence_number'))
                                                                        <small class="error">{{ $errors->first('trade_licence_number') }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        @if(hasPermission('user_update'))
                                                            <button type="submit"
                                                                    class="btn btn-primary float-right"><span>
                                                        </span> {{ _trans('Update') }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('script')
<script src="{{url('public/backend/js/pages/__profile.js')}}"></script>
@endsection
