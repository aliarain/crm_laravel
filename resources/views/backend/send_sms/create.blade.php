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
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class=" col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="row mb-10">
                                    <div class="col-md-12">
                                        <div class="col-md-7 mx-auto">
                                            <div class="float-sm-right mb-3  ">
                                                @if (hasPermission('send_sms_list'))
                                                    <a href="{{ route('sms.index') }}"
                                                        class="btn btn-primary"> <i
                                                            class="fa fa-arrow-left pr-2"></i> {{  _trans('common.Back') }}</a>
                                                @endif
                                            </div>
                                        </div><!-- /.col -->
                                    </div>
                                </div><!-- /.row -->
                                <form action="{{ route('sms.store') }}" enctype="multipart/form-data" method="post"
                                    id="attendanceForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-7 mx-auto">
                                               
                                                <div class="form-group">
                                                    <label for="name" class="form-label">{{ _trans('common.Department') }} <span class="text-danger">*</span></label>
                                                    <select name="department_id[]" class="form-control select2"
                                                            multiple="multiple" required="required">
                                                        <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                                        @foreach($data['departments'] as $key => $department)
                                                            <option value="{{ $department->id }}">{{ $department->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('department_id'))
                                                        <div class="error">{{ $errors->first('department_id') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-7 mx-auto">
                                                <div class="form-group">
                                                    <label for="message" class="form-label">{{ _trans('common.Message') }} <span class="text-danger">*</span></label>
                                                    <textarea name="message" id="message" class="form-control" cols="30" rows="5" placeholder="Enter Message"
                                                        required></textarea>
                                                    @if ($errors->has('message'))
                                                        <div class="error">{{ $errors->first('message') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-7 mx-auto">
                                                <div class=" float-right">
                                                    <button type="submit" class="btn btn-primary action-btn">{{ _trans('common.Save') }}</button>
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
