@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-10">
                                    <div class="col-md-12">
                                        <div class=" col-md-7 mx-auto">
                                            <div class="float-right mb-3  text-right">
                                                @if(hasPermission('shift_read'))
                                                    <a href="{{ route('shift.index') }}" class="btn btn-primary "> <i class="fa fa-arrow-left pr-2"></i>   {{ _trans('common.Back') }}</a>
                                            @endif
                                            </div>
                                        </div><!-- /.col -->
                                    </div>
                                </div><!-- /.row -->
                                <!-- form start -->
                                <form method="POST" action="{{ route('shift.update', $data['shift']->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="col-md-7 mx-auto">
                                                <div class="form-group">
                                                    <label for="name">{{ _trans('common.Name') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control"
                                                        placeholder="{{ _trans('common.Name') }}" value="{{ $data['shift']->name }}"
                                                        required>
                                                    @if ($errors->has('name'))
                                                        <div class="error">{{ $errors->first('name') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-7 mx-auto">
                                                <div class="form-group">
                                                    <label for="name">Status<span class="text-danger">*</span></label>
                                                    <select name="status_id w-100" class="form-control select2" required >
                                                        <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                                        <option value="1"
                                                            {{ $data['shift']->status_id == 1 ? 'selected' : '' }}>
                                                            {{ _trans('common.Active') }}</option>
                                                        <option value="2"
                                                            {{ $data['shift']->status_id == 2 ? 'selected' : '' }}>
                                                            {{ _trans('common.In-active') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-7 mx-auto">
                                                <div class=" float-right">
                                                    <button type="submit" class="btn btn-primary action-btn">{{ _trans('common.Update') }}</button>
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
