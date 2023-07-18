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
            {{--  --}}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('appointment.store') }}" class=""
                            enctype="multipart/form-data">
                            @csrf
                            <input type="text" hidden value="{{ auth()->id() }}" name="user_id">
                            <div class="">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="name">{{ _trans('common.Title') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="title"
                                                class="form-control ot-form-control ot_input"
                                                placeholder="{{ _trans('common.Enter Title') }}" value="{{ old('title') }}"
                                                required>
                                            @if ($errors->has('title'))
                                                <div class="error">{{ $errors->first('title') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">{{ _trans('common.Description') }} <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="description" class="form-control mt-0 ot_input" placeholder="{{ _trans('common.Enter Description') }}" rows="6" required></textarea>
                                            @if ($errors->has('description'))
                                                <div class="error">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="name">{{ _trans('common.Location') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="location"
                                                class="form-control ot-form-control ot_input"
                                                placeholder="{{ _trans('common.location') }}" value="{{ old('location') }}"
                                                required>
                                            @if ($errors->has('location'))
                                                <div class="error">{{ $errors->first('location') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="date"
                                                class="form-label">{{ _trans('common.Date Schedule') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="date" id="date"
                                                class="form-control ot-form-control ot_input" placeholder="{{ _trans('common.Date') }}" required>
                                            @if ($errors->has('date'))
                                                <div class="error">{{ $errors->first('date') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">{{ _trans('common.Appointment With') }}</label>
                                            <select name="appoinment_with" class="form-control  select2" id="user_id">

                                            </select>
                                            @if ($errors->has('appoinment_with'))
                                                <div class="error">{{ $errors->first('appoinment_with') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label for="#" class="form-label">{{ _trans('common.Start Time') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="time" class="form-control ot-form-control ot_input"
                                                name="appoinment_start_at" value="{{ old('appoinment_start_at') }}"
                                                required>
                                            @if ($errors->has('appoinment_start_at'))
                                                <div class="error">{{ $errors->first('appoinment_start_at') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label for="#" class="form-label">{{ _trans('common.End Time') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="time" class="form-control ot-form-control ot_input"
                                                name="appoinment_end_at" value="{{ old('appoinment_end_at') }}" required>
                                            @if ($errors->has('appoinment_end_at'))
                                                <div class="error">{{ $errors->first('appoinment_end_at') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12 ">
                                        <div class=" float-right d-flex justify-content-end">
                                            <button type="submit"
                                                class="crm_theme_btn action-btn">{{ _trans('common.Save') }}</button>
                                        </div>
                                    </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <input type="hidden" id="get_user_url" value="{{ route('user.getUser') }}">
    </div>
@endsection
