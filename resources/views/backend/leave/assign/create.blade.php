@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([ 'title' => @$data['title'], route('admin.dashboard') => _trans('common.Dashboard'), '#' => @$data['title']]) !!}

    <div class="table-content table-basic">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('assignLeave.store') }}" class=""
                enctype="multipart/form-data">
                @csrf
                <div class="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-7 mx-auto">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">{{ _trans('common.Days') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="days" class="form-control ot-form-control ot_input"
                                        placeholder="{{ _trans('common.Days') }}" value="{{ old('days') }}"
                                        required>
                                    @if ($errors->has('days'))
                                        <div class="error">{{ $errors->first('days') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-7 mx-auto">
                                <div class="form-group mb-3">
                                    <label for="name"
                                        class="form-label">{{ _trans('common.Department') }} <span class="text-danger">*</span></label>
                                    <select name="department_id[]" class="form-control select2"
                                        multiple="multiple" required="required">
                                        <option value="" disabled>{{ _trans('common.Choose One') }}
                                        </option>
                                        @foreach ($data['departments'] as $key => $department)
                                            <option value="{{ $department->id }}">{{ $department->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('department_id'))
                                        <div class="error">{{ $errors->first('department_id') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-7 mx-auto">
                                <div class="form-group mb-3">
                                    <label for="name"
                                        class="form-label">{{ _trans('leave.Leave type') }} <span class="text-danger">*</span></label>
                                    <select name="type_id" class="form-select select2" required="required">
                                        <option value="" disabled selected>
                                            {{ _trans('common.Choose One') }}
                                        </option>
                                        @foreach ($data['leaveTypes'] as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('type_id'))
                                        <div class="error">{{ $errors->first('type_id') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-7 mx-auto">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Status') }} <span
                                            class="text-danger">*</span></label>
                                    <select name="status_id" class="form-select select2" required>
                                        <option value="" disabled selected>
                                            {{ _trans('common.Choose One') }}
                                        </option>
                                        <option value="1" selected>{{ _trans('common.Active') }}</option>
                                        <option value="2">{{ _trans('common.In-active') }}</option>
                                    </select>
                                    @if ($errors->has('status_id'))
                                        <div class="error">{{ $errors->first('status_id') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-7 mx-auto">
                                <div class=" d-flex justify-content-end">
                                    @if (hasPermission('leave_assign_create'))
                                        <button type="submit"
                                            class="crm_theme_btn ">{{ _trans('common.Save') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.card-body -->
            </form>
            </div>
        </div>
    </div>
@endsection
