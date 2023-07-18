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
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="">
                        {{ Form::open(['url' => $data['url'], 'class' => 'card', 'id' => 'add_supplier', 'files' =>  'true']) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <label for="image">{{ _trans('common.Profile Picture')}}</label>
                                        <div class="input-group text-center position-center">
                                            <div class="input-group  justify-content-center">
                                                <div>
                                                    <div class="custom-image-upload-wrapper d-flex justify-center">
                                                        <div class="image-area d-flex"><img id="bruh"
                                                                src="{{ uploaded_asset(@$data['show']->avatar_id) }}"
                                                                class="img-fluid mx-auto my-auto">
                                                        </div>
                                                        <div class="input-area"><label id="upload-label"
                                                                for="appSettings_company_logo">
                                                                Change Avatar
                                                            </label> <input id="appSettings_company_logo" name="avatar"
                                                                type="file" class="form-control d-none">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if (@$data['edit']->avatar_id)
                                        <img class="custom-edit img"
                                            src="{{ uploaded_asset($data['edit']->avatar_id) }}" alt="" width="80"
                                            srcset="">
                                        @endif
                                        @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{ _trans('common.Name')}}</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="{{ _trans('common.Name')}}"
                                            value="{{ @$data['edit']->name ? $data['edit']->name : old('name') }}">
                                        @if ($errors->has('name'))
                                        <div class="error">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">{{ _trans('common.Phone')}}</label>
                                        <input type="number" class="form-control" id="phone" name="phone"
                                            placeholder="{{ _trans('common.Phone')}}"
                                            value="{{ @$data['edit']->phone ? $data['edit']->phone : old('phone') }}">
                                        @if ($errors->has('phone'))
                                        <div class="error">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">{{ _trans('common.Email')}}</label>
                                        <input type="email" class="form-control"
                                            value="{{ @$data['edit']->email ? $data['edit']->email : old('email') }}"
                                            name="email" id="email" placeholder="{{ _trans('common.Email')}}">
                                        @if ($errors->has('email'))
                                        <div class="error">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="permanent_address">{{ _trans('common.Permanent Address')}}</label>
                                        <input class="form-control" id="permanent_address" name="permanent_address"
                                            placeholder="{{ _trans('common.Permanent Address')}}"
                                            value="{{ @$data['edit']->permanent_address ? $data['edit']->permanent_address : old('permanent_address') }}">
                                        @if ($errors->has('permanent_address'))
                                        <div class="error">{{ $errors->first('permanent_address') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="current_address">{{ _trans('common.Current Address')}}</label>
                                        <input class="form-control " id="current_address" name="current_address"
                                            placeholder="{{ _trans('common.Current Address')}}"
                                            value="{{ @$data['edit']->current_address ? $data['edit']->current_address : old('current_address') }}">
                                        @error('current_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>






                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mb-3">{{ _trans('common.Submit')}}</button>
                            </div>
                            {{Form::close()}}
                        </div>
                        <!-- /.card-body -->


                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
</div>
@endsection

@section('script')
<script src="{{url('public/backend/js/image_preview.js')}}"></script>
@endsection
