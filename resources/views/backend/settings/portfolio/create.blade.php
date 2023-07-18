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
                        <form action="{{ $data['url'] }}" enctype="multipart/form-data" method="post" id="attendanceForm">
                            @csrf
                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">{{ _trans('common.Title') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control ot-form-control ot_input"
                                            required value="{{ @$data['edit'] ? $data['edit']->title : old('title') }}">
                                        @if ($errors->has('title'))
                                            <div class="error">{{ $errors->first('title') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">{{ _trans('common.Attachment') }} <span class="text-danger">*</span></label>
                                        
                                        {{-- <input type="file" name="attachment" class="form-control ot-form-control ot_input"
                                            required value="{{ @$data['edit'] ? $data['edit']->attachment : old('attachment') }}"> --}}
                                        
                                        <div class="ot_fileUploader left-side mb-3">
                                            <input class="form-control" type="text" placeholder="{{ _trans('common.Attachment') }}" name="" readonly="" id="placeholder">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse') }}</label>
                                                <input type="file" class="d-none form-control" name="attachment" id="fileBrouse">
                                            </button>
                                        </div>
                                        
                                        @if ($errors->has('attachment'))
                                            <div class="error">{{ $errors->first('attachment') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Status') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="status" class="form-control select2" required>
                                            <option {{ @$data['edit']->type == 1 ? 'selected' : '' }} value="1">
                                                {{ _trans('payroll.Active') }}</option>
                                            <option {{ @$data['edit']->type == 4 ? 'selected' : '' }} value="4">
                                                {{ _trans('payroll.Inactive') }}</option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="error">{{ $errors->first('status') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label">{{ _trans('common.Description') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea type="text" name="content" class="form-control content" required>{{ old('content') }}</textarea>
                                        @if ($errors->has('content'))
                                            <div class="error">{{ $errors->first('content') }}</div>
                                        @endif
                                    </div>
                                </div>


                                @if (@$data['url'])
                                    <div class="col-md-12">
                                        <div class="text-end mt-24">
                                            <button class="crm_theme_btn ">{{ _trans('common.Save') }}</button>
                                        </div>
                                    </div>
                                @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/ckeditor/config.js') }}"></script>
    <script src="{{ asset('public/ckeditor/styles.js') }}"></script>
    <script src="{{ asset('public/ckeditor/build-config.js') }}"></script>
    <script src="{{ asset('public/backend/js/global_ckeditor.js') }}"></script>
@endsection
