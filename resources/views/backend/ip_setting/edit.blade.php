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
             <div class="container-fluid ">
                <div class="row ">
                    <div class=" col-md-12">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" action="{{ route('ipConfig.update',[@$data['show']->id ]) }}"
                              class="card" enctype="multipart/form-data">
                              {{-- @method('patch') --}}
                            @csrf
                            <div class="card-body">
                                <div class="row mb-10">
                                    <div class=" col-12">

                                            <a href="{{ route('ipConfig.index') }}" class="btn btn-primary float-right "> <i class="fa fa-arrow-left"></i> Back</a>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                                <div class="row">


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.location') }}<span
                                                        class="text-danger">*</span></label>
                                            <input type="text" name="location" class="form-control" placeholder="{{ _trans('common.location') }}"
                                                   value="{{ @$data['show']->location }}" required>
                                            @if ($errors->has('location'))
                                                <div class="error">{{ $errors->first('location') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('settings.IP Address') }} <span
                                                        class="text-danger">*</span></label>
                                            <input type="text" name="ip_address" class="form-control" placeholder="{{ _trans('common.IP Address') }}"
                                                   value="{{ @$data['show']->ip_address }}" required>
                                            @if ($errors->has('ip_address'))
                                                <div class="error">{{ $errors->first('ip_address') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Status') }}  <span
                                                        class="text-danger">*</span></label>
                                            <select name="status_id" class="form-control" required>
                                                <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                                <option value="1" {{ @$data['show']->status_id == 1 ? 'selected':'' }}>{{ _trans('common.Active') }}</option>
                                                <option value="4" {{ @$data['show']->status_id == 4 ? 'selected':'' }}>{{ _trans('common.In-Active') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn  btn-primary action-btn">{{ _trans('common.Update') }}</button>
                            </div>
                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

