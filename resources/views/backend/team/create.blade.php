@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper">

        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content p-0">
            <div class="container-fluid border-radius-5 p-imp-30">
                <div class="row mt-4">
                    <div class="col-md-2"></div>
                    <div class="col-md-12">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('team.store') }}"
                              class="card" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row mb-10">
                                    <div class="col-sm-6 col-12">
                                        <h3 class="m-0 text-dark">{{ @$data['title'] }}</h3>
                                    </div><!-- /.col -->
                                    <div class="col-sm-6 col-12">
                                        <div class="float-sm-right mb-3">

                                            <a href="{{ route('team.index') }}" class="btn btn-primary float-left-sm-device float-right" >{{ _trans('common.Team list') }}</a>
                                        </div>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Name') }} <span
                                                        class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" placeholder="{{ _trans('common.Name') }}"
                                                   value="{{ old('name') }}" required>
                                            @if ($errors->has('name'))
                                                <div class="error">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="#" class="form-label">{{ _trans('common.Select Team Lead') }}</label>
                                            <select name="team_lead_id" class="form-control select2" id="user_id">

                                            </select>
                                            @if ($errors->has('team_lead_id'))
                                                <div class="error">{{ $errors->first('team_lead_id') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>
                                            <div class="custom-image-upload-wrapper">
                                                <div class="image-area d-flex">
                                                    <img id="bruh" src="{{ uploaded_asset(null) }}" alt=""
                                                         class="img-fluid mx-auto my-auto">
                                                </div>
                                                <div class="input-area"><label
                                                            id="upload-label"
                                                            for="upload_file">
                                                        {{ _trans('common.Documents file') }}
                                                    </label> <input
                                                            id="upload_file"
                                                            name="file"
                                                            type="file"
                                                            class="form-control d-none upload_file">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-40">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Status') }} <span
                                                        class="text-danger">*</span></label>
                                            <select name="status_id" class="form-control" required>
                                                <option value="" disabled
                                                        selected>{{ _trans('common.Choose One') }}</option>
                                                <option value="1" selected>{{ _trans('common.Active') }}</option>
                                                <option value="2">{{ _trans('common.In-active') }}</option>
                                            </select>
                                            @if ($errors->has('status_id'))
                                                <div class="error">{{ $errors->first('status_id') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="card-footer float-right">
                                            @if(hasPermission('leave_type_create'))
                                                <button type="submit"
                                                        class="btn btn-primary action-btn">{{ _trans('common.Save') }}</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <input type="hidden" id="get_user_url" value="{{ route('user.getUser') }}">
@endsection

