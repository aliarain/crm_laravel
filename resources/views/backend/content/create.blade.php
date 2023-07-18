@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    <div class="content-wrapper">

        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row mt-4">
                    <div class=" col-lg-12 mt-4">
                        <div class="card">

                            <div class="card-body">
                                <div class="row mb-10">
                                    <div class="col-sm-6 col-12">
                                        <h3 class="m-0 text-dark">{{ @$data['title'] }}</h3>
                                    </div><!-- /.col -->
                                    <div class="col-sm-6 col-12">
                                        <div class="float-sm-right mb-3">
                                            @if(hasPermission('department_read'))
                                                <a href="{{ route('department.index') }}" class="btn btn-primary float-right float-left-sm-device"> <i class="fa fa-arrow-left pr-2"></i>  Back</a>
                                        @endif
                                        </div>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->


                                <form action="{{ route('department.store') }}"
                                      enctype="multipart/form-data"
                                      method="post" id="attendanceForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">{{ _trans('common.Title') }} <span  class="text-danger">*</span></label>
                                                <input type="text" name="title" class="form-control" placeholder="{{ _trans('common.Title') }}" value="{{ old('title') }}" required>
                                                @if ($errors->has('title'))
                                                    <div class="error">{{ $errors->first('title') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">{{ _trans('common.Status') }} <span
                                                            class="text-danger">*</span></label>
                                                <select name="status_id" class="form-control" required>
                                                    <option value="" disabled selected>{{ _trans('common.Choose One') }}</option>
                                                    <option value="1" selected>{{ _trans('common.Active') }}</option>
                                                    <option value="2">{{ _trans('common.In-active') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col md-12">
                                            <div class="card-footer float-right">
                                                <button type="submit" class="btn btn-primary action-btn">{{ _trans('common.Save') }}</button>
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
