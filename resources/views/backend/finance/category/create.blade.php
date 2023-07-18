@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper">
        <div class="content-header mb-20">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ @$data['title'] }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb d-flex justify-content-end mb-0">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
                            @if (@$data['list_url'])
                                <li class="breadcrumb-item"><a
                                        href="{{ @$data['list_url'] }}">{{ _trans('common.List') }}</a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active">{{ @$data['title'] }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card ot-card">

                            <div class="card-body">


                                <form action="{{ $data['url'] }}" enctype="multipart/form-data" method="post"
                                    id="attendanceForm">
                                    @csrf
                                    <input type="hidden" name="is_income" value="{{ @$data['type'] }}">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="">{{ _trans('common.Name') }}</label>
                                                <input type="text" name="name" class="form-control ot-form-control ot_input"
                                                    value="{{ @$data['edit'] ? $data['edit']->name : old('name') }}"
                                                    placeholder="{{ _trans('common.Enter Name') }}">
                                                @if ($errors->has('name'))
                                                    <div class="error">{{ $errors->first('name') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ _trans('account.Status') }} <span class="text-danger">*</span></label>
                                                <select name="status_id" class="form-control select2" required>
                                                    <option value="1"
                                                        {{ @$data['edit'] ? ($data['edit']->status_id == '1' ? 'Selected' : '') : '' }}>
                                                        {{ _trans('account.Active') }}</option>
                                                    <option value="4"
                                                        {{ @$data['edit'] ? ($data['edit']->status_id == '4' ? 'Selected' : '') : '' }}>
                                                        {{ _trans('account.Inactive') }}</option>
                                                </select>
                                                @if ($errors->has('status_id'))
                                                    <div class="error">{{ $errors->first('status_id') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                    @if (@$data['url'])
                                    <div class="col-md-12 mx-auto">
                                        <div class=" float-right">
                                            <button type="submit" class="btn btn-primary action-btn">{{ _trans('account.Save') }}</button>
                                        </div>
                                    </div>
                                    @endif


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <input type="hidden" id="get_user_url" value="{{ route('user.getUser') }}">
@endsection
