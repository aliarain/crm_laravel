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
        <section class="content p-0">
            <div class="container-fluid border-radius-5 ">
                <div class="row">
                    <div class="col-md-12">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('company.store') }}" class="card"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row mb-10 align-items-center">
                                    <div class="col-md-12">
                                        <div class="">
                                            <div class="float-right mb-3  text-right">
                                                <a href="{{ route('company.index') }}" class="btn btn-primary "> <i
                                                        class="fa fa-arrow-left"></i> {{ _trans('company.Back') }}</a>
                                            </div>
                                        </div><!-- /.col -->
                                    </div>
                                </div><!-- /.row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('company.Company Name') }}<span class="text-danger">*</span></label>
                                            <input type="text" name="company_name" class="form-control"
                                                placeholder="{{ _trans('company.Company Name') }}" value="{{ old('company_name') }}" required>
                                            @if ($errors->has('company_name'))
                                                <div class="error">{{ $errors->first('company_name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" placeholder="{{ _trans('common.Name') }}"
                                                value="{{ old('name') }}" required>
                                            @if ($errors->has('name'))
                                                <div class="error">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Email') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="email" class="form-control" placeholder="{{ _trans('common.Email') }}"
                                                value="{{ old('email') }}" required>
                                            @if ($errors->has('email'))
                                                <div class="error">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Phone') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" placeholder="{{ _trans('common.Phone') }}"
                                                value="{{ old('phone') }}" required>
                                            @if ($errors->has('phone'))
                                                <div class="error">{{ $errors->first('phone') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Total Employee') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="total_employee" class="form-control"
                                                placeholder="{{ _trans('common.Total Employee') }}" value="{{ old('total_employee') }}"
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
                                                placeholder="{{ _trans('common.Business Type') }}" value="{{ old('business_type') }}" required>
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
                                                value="{{ old('trade_licence_number') }}" required>
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
                                                <option value="1" selected>{{ _trans('common.Active') }}</option>
                                               <option value="2">{{ _trans('common.In-active') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="name" class="form-label">{{ _trans('common.Country') }} <span class="text-danger">*</span></label>
                                        <select name="country_id" class="form-control select2 w-100" id="_country_id" required ></select>
                                        @if ($errors->has('country'))
                                            <div class="error">{{ $errors->first('country') }}</div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <div class="text-center mb-4">
                                <button type="submit" class="btn btn-primary action-btn">{{ _trans('common.Save') }}</button>
                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
<script src="{{url('public/backend/js/pages/__profile.js')}}"></script>
@endsection

