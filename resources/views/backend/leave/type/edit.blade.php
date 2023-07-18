@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([ 'title' => @$data['title'], route('admin.dashboard') => _trans('common.Dashboard'), '#' => @$data['title']]) !!}
    <div class="table-content table-basic">
        <div class="card">
            <div class="card-header">
                {{ @$data['title'] }}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('leave.update', $data['show']->id) }}" class=""
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="">

                        <div class="row mb-10">
                                <div class="col-md-7 mx-auto">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('leave.index') }}"
                                            class="crm_theme_btn ">{{ _trans('common.Back') }}</a>
                                    </div>
                                </div><!-- /.col -->
                        </div><!-- /.row -->

                        <div class="row">
                            <div class="col-md-7 mx-auto">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Name') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="ot-form-control"
                                        placeholder="{{ _trans('common.Name') }}"
                                        value="{{ $data['show']->name }}" required>
                                    @if ($errors->has('name'))
                                        <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-7 mx-auto">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Status') }}<span class="text-danger">*</span></label>
                                    <select name="status_id" class="form-select ot-form-control" required>
                                        <option value="" disabled selected>
                                            {{ _trans('common.Choose One') }}
                                        </option>
                                        <option value="1"
                                            {{ $data['show']->status_id == 1 ? 'selected' : '' }}>
                                            {{ _trans('common.Active') }}
                                        </option>
                                        <option value="2"
                                            {{ $data['show']->status_id == 2 ? 'selected' : '' }}>
                                            {{ _trans('common.In-active') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-7 mx-auto">
                                <div class=" d-flex justify-content-end">
                                    @if (hasPermission('leave_type_update'))
                                        <button type="submit"
                                            class="crm_theme_btn ">{{ _trans('common.Save') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                </form>

            </div>
        </div>
        <!-- Content Header (Page header) -->
    @endsection
