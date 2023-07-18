@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
<div class="d-flex justify-content-end pb-16">
    <nav aria-label="breadcrumb ">
        <ol class="breadcrumb ot-breadcrumb ot-breadcrumb-basic">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ @$data['title'] }}</li>
        </ol>
    </nav>

</div>
    <div class="table-content table-basic">
        <div class="card">
            <div class="card-header">
                {{ @$data['title'] }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                                <div class="row mb-10">
                                    <div class="col-md-12">
                                        <div class=" col-md-7 mx-auto">
                                            <div class="d-flex justify-content-end">
                                                @if (hasPermission('department_read'))
                                                    <a href="{{ route('department.index') }}" class="crm_theme_btn "> <i
                                                            class="fa fa-arrow-left pr-2"></i> {{ _trans('common.Back') }}</a>
                                                @endif
                                            </div>
                                        </div><!-- /.col -->
                                    </div>
                                </div><!-- /.row -->
                                <form method="POST" action="{{ route('department.update', $data['department']->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="department_id" value="{{ $data['department']->id }}">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-7 mx-auto">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="name">{{ _trans('common.Title') }} <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="title" class=" ot-form-control"
                                                        placeholder="{{ _trans('common.Title') }}"
                                                        value="{{ $data['department']->title }}" required>
                                                    @if ($errors->has('title'))
                                                        <div class="error">{{ $errors->first('title') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-7 mx-auto">
                                                <div class="form-group mb-3">
                                                    <label for="name">{{ _trans('common.Status') }} <span
                                                            class="form-label">*</span></label>
                                                    <select name="status_id" class="form-select ot-form-control" required>
                                                        <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                                        <option value="1"
                                                            {{ $data['department']->status_id == 1 ? 'selected' : '' }}>
                                                            {{ _trans('common.Active') }}</option>
                                                        <option value="2"
                                                            {{ $data['department']->status_id == 2 ? 'selected' : '' }}>
                                                            {{ _trans('common.In-active') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-7 mx-auto">
                                                <div class=" d-flex justify-content-end">
                                                    <button type="submit"
                                                        class="crm_theme_btn ">{{ _trans('common.Update') }}</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                        <!-- form start -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
