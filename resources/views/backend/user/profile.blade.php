@extends('backend.layouts.app')
@section('title', 'Profile')
@section('content')
    <div class="content-wrapper">
        <div class="content-header d-none">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ _trans('common.Profile') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ _trans('common.Profile') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content profile-custom-design">
            <div class="container-fluid p-imp-profile">
                @include('backend.partials.user_navbar')
                <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle"
                                         src="{{ uploaded_asset($data['show']->avatar_id) }}"
                                         alt="User profile picture">
                                </div>

                                <h3 class="profile-username text-center">{{ @$data['show']->name }}</h3>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active info-btn" href="#personal"
                                                            data-toggle="tab">{{ _trans('common.Personal Info') }}</a></li>
                                    <li class="nav-item"><a class="nav-link info-btn" href="#security"
                                                            data-toggle="tab">{{ _trans('common.Security Info') }}</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="personal">
                                        <form class="form-horizontal" action="{{ route('admin.profile_update') }}"
                                              method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="text" hidden name="id" value="{{ @$data['show']->id }}">
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-2 col-form-label">{{ _trans('common.Name') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="name" id="name"
                                                           placeholder="{{ _trans('common.Name') }}" value="{{ @$data['show']->name }}">
                                                    @if ($errors->has('name'))
                                                        <div class="error">{{ $errors->first('name') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="email" class="col-sm-2 col-form-label">{{ _trans('common.Email') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="email" id="email"
                                                           placeholder="{{ _trans('common.Email') }}" value="{{ @$data['show']->email }}">
                                                    @if ($errors->has('email'))
                                                        <div class="error">{{ $errors->first('email') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 col-form-label">{{ _trans('common.Date Of Birth') }} <span class="text-danger">*</span></label>
                                                <div class="col-sm-10">
                                                    <div class="input-group date">
                                                        <input type="date" name="birth_date" required
                                                               class="form-control"
                                                               value="{{  $data['show']->birth_date }}"/>
                                                    </div>
                                                    @if ($errors->has('birth_date'))
                                                        <div class="error">{{ $errors->first('birth_date') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <input type="text" name="type" value="1" hidden>
                                            <div class="form-group row">
                                                <label for="inputEmail" class="col-sm-2 col-form-label">{{ _trans('common.Gender') }} <span class="text-danger">*</span></label>
                                                <div class="col-sm-10">
                                                    <select name="gender" id="" class="form-control" required>
                                                        <option {{ @$data['show']->gender == 'Male' ? 'selected' : '' }}
                                                                value="Male">{{ _trans('common.Male') }}
                                                        </option>
                                                        <option
                                                                {{ @$data['show']->gender == 'Female' ? 'selected' : '' }}
                                                                value="Female">{{ _trans('common.Female') }}
                                                        </option>
                                                        <option
                                                                {{ @$data['show']->gender == 'Others' ? 'selected' : '' }}
                                                                value="others">{{ _trans('common.Others') }}
                                                        </option>
                                                    </select>
                                                    @if ($errors->has('gender'))
                                                        <div class="error">{{ $errors->first('gender') }}</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="phone" class="col-sm-2 col-form-label">{{ _trans('common.Phone') }} <span class="text-danger">*</span></label>
                                                <div class="col-sm-10">
                                                    <input type="number" class="form-control" name="phone" id="phone"
                                                           placeholder="{{ _trans('common.Phone') }}" value="{{ @$data['show']->phone }}" required>
                                                    @if ($errors->has('phone'))
                                                        <div class="error">{{ $errors->first('phone') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="image" class="col-sm-2 col-form-label">{{ _trans('common.Profile Picture') }}</label>
                                                <div class="col-sm-10">
                                                    <div>
                                                        <div class="custom-image-upload-wrapper">
                                                            <div class="image-area d-flex">
                                                                <img id="bruh"
                                                                     src="{{ uploaded_asset($data['show']->avatar_id) }}"
                                                                     alt="" class="img-fluid mx-auto my-auto">
                                                            </div>
                                                            <div class="input-area"><label
                                                                        id="upload-label"
                                                                        for="appSettings_company_logo">
                                                                    {{ _trans('common.Change avatar') }}
                                                                </label> <input
                                                                        id="appSettings_company_logo"
                                                                        name="avatar"
                                                                        type="file"
                                                                        class="form-control d-none">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if (@$data['edit']->avatar_id)
                                                    <img src="{{ uploaded_asset($data['edit']->avatar_id) }}" alt=""
                                                         width="80" srcset="">
                                                @endif
                                                @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary action-btn py-2 px-3">
                                                        {{ _trans('common.Submit') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="security">
                                        <form class="form-horizontal password_reset_form"  action="{{ route('password.change') }}" method="POST">
                                            @csrf
                                            <div class="form-group row">
                                                <label for="old_password" class="col-sm-2 col-form-label">{{ _trans('common.Old Password') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="password" class="form-control" name="old_password"
                                                           id="old_password" placeholder="{{ _trans('common.********') }}">
                                                    <small class="text-danger __old_password"></small>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputSkills" class="col-sm-2 col-form-label">{{ _trans('common.New Password') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="password" class="form-control" name="password"
                                                           id="inputSkills" placeholder="{{ _trans('common.********') }}">
                                                    <small class="text-danger __password"></small>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="password_confirmation" class="col-sm-2 col-form-label">{{ _trans('common.Confirm Password') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="password" class="form-control"
                                                           id="password_confirmation"
                                                           name="password_confirmation" placeholder="{{ _trans('common.********') }}">
                                                    <small class="text-danger __password_confirmation"></small>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit"
                                                            class="btn btn-primary action-btn submit_btn">{{ _trans('common.Submit') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
    </div>
@endsection
@section('style')
    {{--  iziToast   --}}
    <link rel="stylesheet" href="{{ asset('public/frontend/assets/css/iziToast.css')}}">
@endsection
@section('script')
    {{--  iziToast  --}}
    <script src="{{ asset('public/frontend/assets/js/iziToast.js') }}"></script>
   
    <script src="{{url('public/backend/js/pages/__profile.js')}}"></script>

@endsection
