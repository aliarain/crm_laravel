@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([ 'title' => @$data['title'], route('admin.dashboard') => _trans('common.Dashboard'), '#' => @$data['title']]) !!}
    <div class="table-content table-basic">
        <div class="card">
            <div class="card-body">
                <form action="{{ $data['url'] }}" enctype="multipart/form-data" method="post" id="attendanceForm">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">{{ _trans('common.Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['edit'] ? $data['edit']->name : old('name') }}" required
                                    placeholder="{{ _trans('common.Enter Name') }}">
                                @if ($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Balance') }} <span class="text-danger">*</span></label>
                                <input type="number" name="balance" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['edit'] ? $data['edit']->amount : old('balance') }}" required
                                    placeholder="{{ _trans('common.2000.00') }}">
                                @if ($errors->has('balance'))
                                    <div class="error">{{ $errors->first('balance') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('account.Account Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="ac_name" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['edit'] ? $data['edit']->ac_name : old('ac_name') }}" required
                                    placeholder="{{ _trans('common.Enter Account Name') }}">
                                @if ($errors->has('ac_name'))
                                    <div class="error">{{ $errors->first('ac_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('account.Account Number') }} <span class="text-danger">*</span></label>
                                <input type="text" name="ac_number" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['edit'] ? $data['edit']->ac_number : old('ac_number') }}"
                                    placeholder="{{ _trans('common.Enter Account Number') }}">
                                @if ($errors->has('ac_number'))
                                    <div class="error text-danger">{{ $errors->first('ac_number') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('account.Code') }} <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['edit'] ? $data['edit']->code : old('code') }}" required
                                    placeholder="{{ _trans('common.Enter Code') }}">
                                @if ($errors->has('code'))
                                    <div class="error">{{ $errors->first('code') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('account.Branch') }} <span class="text-danger">*</span></label>
                                <input type="text" name="branch" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['edit'] ? $data['edit']->branch : old('branch') }}" required
                                    placeholder="{{ _trans('common.Enter Branch Name') }}">
                                @if ($errors->has('branch'))
                                    <div class="error">{{ $errors->first('branch') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">{{ _trans('account.Status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-control select2" required>
                                    <option value="1"
                                        {{ @$data['edit'] ? ($data['edit']->status_id == 1 ? 'Selected' : '') : '' }}>
                                        {{ _trans('payroll.Active') }}</option>
                                    <option value="4"
                                        {{ @$data['edit'] ? ($data['edit']->status_id == 4 ? 'Selected' : '') : '' }}>
                                        {{ _trans('payroll.Inactive') }}</option>
                                </select>
                                @if ($errors->has('status'))
                                    <div class="error">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (@$data['url'])
                        <div class="row  mt-20">
                            <div class="col-md-12">
                                <div class="text-right d-flex justify-content-end">
                                    <button class="crm_theme_btn ">{{ _trans('common.Save') }}</button>
                                </div>
                            </div>
                        </div>
                    @endif


                </form>
            </div>
        </div>
        </div>

        <input type="hidden" id="get_user_url" value="{{ route('user.getUser') }}">
    @endsection
