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
                                        href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard')}}</a></li>
                            <li class="breadcrumb-item active">{{ @$data['title'] }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ">
                <form action="{{ route('roles.store')}}" class="form-validate" method="POST">
                    @csrf
                    <div class="card">

                       <div class="card-body">
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <div class="float-right mb-3  text-right"> 
                                        @if(hasPermission('role_read'))
                                                <a href="{{ route('roles.index') }}" class="btn btn-primary"> <i class="fa fa-arrow-left mr-2"></i>{{ _trans('common.Back')}}</a>
                                        @endif
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label class="form-label form-label" for="fv-full-name">{{ _trans('common.Name')}} <span class="text-danger">*</span></label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="fv-full-name" name="name"
                                                   required placeholder="{{ _trans('common.Name')}}" value="{{ old('name') }}">
                                        </div>
                                        @if($errors->has('name'))
                                            <p class="text-danger">{{ $errors->first('name') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="fv-email">{{ _trans('common.Status')}} <span class="text-danger">*</span></label>
                                        <div class="form-control-wrap">
                                            <select name="status_id" id="status_id" class="form-control select2" required>
                                                <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                                <option value="1">{{ _trans('common.Active') }}</option>
                                                <option value="2">{{ _trans('common.in-active') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-inner table-responsive">
                                    <table class="table role-create-table role-permission ">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{ _trans('common.Module') }}/ {{ _trans('common.Sub Module') }}</th>
                                            <th scope="col">{{ _trans('common.Permissions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{ _trans('common.Check all') }}</td>
                                            <td>
                                                <div class="input-check-radio">
                                                    <div class="form-check">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input mt-0 read check_all"
                                                            name="check_all"
                                                            id="check_all">
                                                    <label class="form-check-label ml-6"
                                                           for="check_all">{{ _trans('common.Check all') }}</label>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                        @foreach($data['permissions'] as $permission)
                                            <tr>
                                                <td><span class="text-capitalize">{{__($permission->attribute)}}</span>
                                                </td>

                                                <td>
                                                    @foreach($permission->keywords as $key=>$keyword)
                                                    <div class="input-check-radio">
                                                        <div class="form-check">
                                                            @if($keyword != "")
                                                                <input type="checkbox"
                                                                       class="form-check-input mt-0 read common-key"
                                                                       name="permissions[]" value="{{$keyword}}"
                                                                       id="{{$keyword}}">
                                                                <label class="form-check-label ml-6"
                                                                       for="{{$keyword}}">{{Str::title(Str::replace('_',' ',$key))}}</label>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endforeach

                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12 text-right mt-4 mr-5">
                                            <div class="form-group">
                                                @if(hasPermission('role_create'))
                                                    <button type="submit"
                                                            class="btn btn-sm btn-primary mr-3">{{ _trans('common.Submit') }}</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
@section('script')
<script src="{{ asset('public/backend/js/_roles.js') }}"></script>
@endsection

