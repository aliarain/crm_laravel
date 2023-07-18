@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([ 'title' => @$data['title'], route('admin.dashboard') => _trans('common.Dashboard'), '#' => @$data['title']]) !!}
    <div class="table-content table-basic">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('assignLeave.update', $data['show']->id) }}" class=""
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-7 mx-auto">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">{{ _trans('common.Days') }}</label>
                                        <input type="number" name="days" class="ot-form-control ot_input form-control"
                                            placeholder="{{ _trans('common.Days') }}" value="{{ $data['show']->days }}"
                                            required>
                                        @if ($errors->has('days'))
                                            <div class="error">{{ $errors->first('days') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-7 mx-auto">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">{{ _trans('common.Department') }}</label>
                                        <select name="department_id" class="form-select select2" required="required">
                                            <option value="" disabled selected>{{ _trans('common.Choose One') }}
                                            </option>
                                            @foreach ($data['departments'] as $department)
                                                <option value="{{ $department->id }}"
                                                    {{ $data['show']->department_id == $department->id ? 'selected' : '' }}>
                                                    {{ $department->title }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('department_id'))
                                            <div class="error">{{ $errors->first('department_id') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-7 mx-auto">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">{{ _trans('leave.Leave type') }}</label>
                                        <select name="type_id" class="form-select select2" required="required">
                                            <option value="" disabled selected>{{ _trans('common.Choose One') }}
                                            </option>
                                            @foreach ($data['leaveTypes'] as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ $data['show']->type_id == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}</option>
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
                                            <option value="" disabled selected>{{ _trans('common.Choose One') }}
                                            </option>
                                            <option value="1" {{ $data['show']->status_id == 1 ? 'selected' : '' }}>
                                                {{ _trans('common.Active') }}</option>
                                            <option value="2" {{ $data['show']->status_id == 2 ? 'selected' : '' }}>
                                                {{ _trans('common.In-active') }}</option>
                                        </select>
                                        @if ($errors->has('status_id'))
                                            <div class="error">{{ $errors->first('status_id') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-7 mx-auto">
                                    <div class=" d-flex justify-content-end">
                                        @if (hasPermission('leave_assign_update'))
                                            <button type="submit"
                                                class="crm_theme_btn ">{{ _trans('common.Update') }}</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>

    </div>
@endsection
