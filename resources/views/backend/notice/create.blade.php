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
                        <form action="{{ route('notice.store') }}" enctype="multipart/form-data" method="post"
                            id="attendanceForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Subject') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="subject" class="form-control ot-form-control ot_input"
                                            placeholder="{{ _trans('common.Subject') }}" value="{{ old('subject') }}"
                                            required>
                                        @if ($errors->has('subject'))
                                            <div class="error">{{ $errors->first('subject') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Date') }} <span
                                            class="text-danger">*</span></label>
                                        <input type="date" name="date" id="date"
                                            class="form-control ot-form-control ot_input"
                                            required>
                                        @if ($errors->has('date'))
                                            <div class="error">{{ $errors->first('date') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Department') }} <span
                                            class="text-danger">*</span></label>
                                        <select name="department_id[]" class="form-control select2" multiple="multiple"
                                            required="required">
                                            <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                            @foreach ($data['departments'] as $key => $department)
                                                <option value="{{ $department->id }}">{{ $department->title }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('department_id'))
                                            <div class="error">{{ $errors->first('department_id') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label
                                            class="form-label">{{ _trans('common.Description') }} <span
                                            class="text-danger">*</span></label>
                                        <textarea name="description" id="description" class="form-control mt-0 ot_input" cols="30" rows="5"
                                            placeholder="{{ _trans('common.Description') }}" required></textarea>
                                        @if ($errors->has('description'))
                                            <div class="error">{{ $errors->first('description') }}</div>
                                        @endif
                                    </div>
                                </div>
                                {{-- <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ _trans('common.Status') }} <span
                                                            class="text-danger">*</span></label>
                                                <select name="status_id" class="form-control" required>
                                                    <option value="" disabled
                                                            selected>{{ _trans('common.Choose One') }}</option>
                                                    <option value="1" selected>{{ _trans('common.Active') }}</option>
                                                    <option value="2">{{ _trans('common.In-active') }}</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>{{ _trans('common.Attachment') }}</label>
                                        {{-- <input id="upload_file" name="file" type="file"
                                            class="form-control ot-form-control ot_input  upload_file"> --}}
                                        <div class="ot_fileUploader left-side mb-3">
                                            <input class="form-control" type="text" placeholder="{{ _trans('common.Attachment') }}" readonly="" id="placeholder">
                                            <div class="primary-btn-small-input">
                                                <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse') }}</label>
                                                <input type="file" class="d-none form-control" name="file" id="fileBrouse">
                                            </div>
                                        </div>
                                        @if ($errors->has('file'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('file') }}</div>
                                        @endif



                                        <div>
                                            {{-- <div class="custom-image-upload-wrapper"> --}}
                                                {{-- <div class="image-area d-flex">
                                                    <img id="bruh" src="{{ uploaded_asset(null) }}" alt=""
                                                        class="img-fluid mx-auto my-auto">
                                                </div> --}}
                                                {{-- <label id="upload-label" for="upload_file">
                                                    {{ _trans('common.Documents file') }}
                                                </label> --}}
                                            {{-- </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="float-right d-flex justify-content-end">
                                        <button type="submit"
                                            class="crm_theme_btn action-btn">{{ _trans('common.Save') }}</button>
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
