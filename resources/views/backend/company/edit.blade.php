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
        <section class="content p-0">
            <div class="container-fluid border-radius-5">
               <div class="row">
                    <div class="col-md-12">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('company.update', $data['show']->id) }}" class="card"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">
                                <div class="row mb-10">
                                    <div class="col-md-12">
                                        <div class="float-right mb-3  text-right">
                                            <a href="{{ route('company.index') }}"  class="btn btn-primary "> <i class="fa fa-arrow-left"></i> {{ _trans('common.Back') }}</a>
                                        </div>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Company Name') }}<span class="text-danger">*</span></label>
                                            <input type="text" name="company_name" class="form-control"
                                                placeholder="{{ _trans('common.Company Name') }}" value="{{ $data['show']->company_name }}"
                                                required>
                                            @if ($errors->has('company_name'))
                                                <div class="error">{{ $errors->first('company_name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" placeholder="{{ _trans('common.Name') }}"
                                                value="{{ $data['show']->name }}" required>
                                            @if ($errors->has('name'))
                                                <div class="error">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Email') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="email" class="form-control" placeholder="{{ _trans('common.Email') }}"
                                                value="{{ $data['show']->email }}" required>
                                            @if ($errors->has('email'))
                                                <div class="error">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Phone') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" placeholder="Phone"
                                                value="{{ $data['show']->phone }}" required>
                                            @if ($errors->has('phone'))
                                                <div class="error">{{ $errors->first('phone') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6"> 
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Total Employee') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="total_employee" class="form-control"
                                                placeholder="{{ _trans('common.Total Employee') }}" value="{{ $data['show']->total_employee }}"
                                                required>
                                            @if ($errors->has('total_employee'))
                                                <div class="error">{{ $errors->first('total_employee') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Business Type') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="business_type" class="form-control"
                                                placeholder="{{ _trans('common.Business Type') }}" value="{{ $data['show']->business_type }}"
                                                required>
                                            @if ($errors->has('business_type'))
                                                <div class="error">{{ $errors->first('business_type') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Trade Licence Number') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="trade_licence_number" class="form-control"
                                                placeholder="{{ _trans('common.Trade Licence Number') }}"
                                                value="{{ $data['show']->trade_licence_number }}" required>
                                            @if ($errors->has('trade_licence_number'))
                                                <div class="error">{{ $errors->first('trade_licence_number') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Status') }} <span class="text-danger">*</span></label>
                                            <select name="status_id" class="form-control" required>
                                                <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                                <option value="1" {{ $data['show']->status_id == 1 ? 'selected' : '' }}>
                                                    {{ _trans('common.Active') }}</option>
                                                <option value="2" {{ $data['show']->status_id == 2 ? 'selected' : '' }}>
                                                    {{ _trans('common.In-active') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-5">
                                        <div>
                                            <div class="custom-image-upload-wrapper">
                                                <div class="image-area d-flex">
                                                    <img id="bruh"
                                                        src="{{ uploaded_asset($data['show']->trade_licence_id) }}"
                                                        alt="" class="img-fluid mx-auto my-auto">
                                                </div>
                                                <div class="input-area"><label id="upload-label" for="upload_file">
                                                        {{ _trans('common.Trade licence file') }}
                                                    </label> <input id="upload_file" name="file" type="file"
                                                        class="form-control d-none upload_file">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="submit" class="btn btn-primary action-btn">{{ _trans('common.Update') }}</button>
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
