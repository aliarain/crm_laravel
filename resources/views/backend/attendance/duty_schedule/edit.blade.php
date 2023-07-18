@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
    <div class="table-content table-basic ">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('dutySchedule.update', $data['duty_schedule']->id) }}"
                            enctype="multipart/form-data" method="post" id="holidayModal">
                            @csrf
                            @method('PATCH')
                            @php
                                $now = \Carbon\Carbon::now();
                            @endphp
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="#">
                                        {{ _trans('common.Shift') }} <span class="text-danger">*</span></label>
                                        <select name="shift_id" class="form-control select2 " required="required">
                                            <option value="" disabled selected>{{ _trans('common.Choose One') }}
                                            </option>
                                            @foreach ($data['shifts'] as $shift)
                                                <option
                                                    {{ $data['duty_schedule']->shift_id == $shift->id ? 'selected' : '' }}
                                                    value="{{ $shift->id }}">{{ $shift->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('shift_id'))
                                            <div class="error">{{ $errors->first('shift_id') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="#">{{ _trans('common.Consider Time') }}</label>
                                        <input type="number" max="60" min="0"
                                            value="{{ $data['duty_schedule']->consider_time }}"
                                            class="form-control ot-form-control ot_input" name="consider_time"
                                            value="0">
                                        @if ($errors->has('consider_time'))
                                            <div class="error">{{ $errors->first('consider_time') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="#">{{ _trans('common.Start Time') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="time" class="form-control ot-form-control ot_input"
                                            name="start_time" value="{{ $data['duty_schedule']->start_time }}" required>
                                        @if ($errors->has('start_time'))
                                            <div class="error">{{ $errors->first('start_time') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="#">{{ _trans('common.End Time') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="time" class="form-control ot-form-control ot_input" name="end_time"
                                            value="{{ $data['duty_schedule']->end_time }}" required>
                                        @if ($errors->has('end_time'))
                                            <div class="error">{{ $errors->first('end_time') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Schedule End') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="end_on_same_date" class="form-control select2" required="required">
                                            <option value="" disabled selected>{{ _trans('common.Choose One') }}
                                            </option>
                                            <option {{ $data['duty_schedule']->end_on_same_date == 1 ? 'selected' : '' }}
                                                value="1">
                                                {{ _trans('common. Same Date') }}
                                            </option>
                                            <option {{ $data['duty_schedule']->end_on_same_date == 0 ? 'selected' : '' }}
                                                value="0">
                                                {{ _trans('common.Next Date') }}
                                            </option>
                                        </select>
                                        @if ($errors->has('end_on_same_date'))
                                            <div class="error">{{ $errors->first('end_on_same_date') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Status') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="status_id" class="form-control select2" required="required">
                                            <option value="" disabled selected>{{ _trans('common.Choose One') }}
                                            </option>
                                            <option {{ $data['duty_schedule']->status_id == 1 ? 'selected' : '' }}
                                                value="1">
                                                {{ _trans('common. Active') }}
                                            </option>
                                            <option {{ $data['duty_schedule']->status_id == 2 ? 'selected' : '' }}
                                                value="2">
                                                {{ _trans('common.In-active') }}
                                            </option>
                                        </select>
                                        @if ($errors->has('status_id'))
                                            <div class="error">{{ $errors->first('status_id') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="text-right d-flex justify-content-end mt-4">
                                        <button type="submit" class="crm_theme_btn ">{{ _trans('common.Update') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
    @include('backend.partials.datatable')
@endsection
