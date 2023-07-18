@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
    <div class="table-content table-basic">
        <!-- Main content -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('supportTicket.store') }}" enctype="multipart/form-data" method="post"
                    id="attendanceForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Type') }} <span class="text-danger">*</span></label>
                                <select name="type_id" class="form-control select2" required="required">
                                    <option value="" disabled selected>{{ _trans('common.Choose One') }}</option>
                                    <option value="12">{{ _trans('common.Open') }}</option>
                                    <option value="13">{{ _trans('common.Close') }}</option>
                                </select>
                                @if ($errors->has('type_id'))
                                    <div class="error">{{ $errors->first('type_id') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Priority') }} <span class="text-danger">*</span></label>
                                <select name="priority_id" class="form-control select2" required="required">
                                    <option value="" disabled selected>{{ _trans('common.Choose One') }}</option>
                                    <option value="14">{{ _trans('common.High') }}</option>
                                    <option value="15">{{ _trans('common.Medium') }}</option>
                                    <option value="16">{{ _trans('common.Low') }}</option>
                                </select>
                                @if ($errors->has('priority_id'))
                                    <div class="error">{{ $errors->first('priority_id') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Subject') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control ot-form-control ot_input " name="subject"
                                    id="subject" value="{{ old('subject') }}" required placeholder="{{ _trans('common.Subject') }}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Description') }}</label>
                                <textarea class="form-control ot_input" name="description" placeholder="{{ _trans('common.Description') }}" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="form-label" id="upload-label">
                                    {{ _trans('common.Add Attachment') }}
                                </label> 
                                <div class="ot_fileUploader left-side mb-3">
                                    <input class="form-control" type="text" placeholder="{{ _trans('common.Attachment') }}" readonly="" id="placeholder">
                                    <div class="primary-btn-small-input">
                                        <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse') }}</label>
                                        <input type="file" class="d-none form-control" name="attachment_file" id="fileBrouse">
                                    </div>
                                </div>
                                @if ($errors->has('attachment_file'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('attachment_file') }}</div>
                                @endif


 
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="text-right d-flex justify-content-end">
                                <button class="crm_theme_btn ">{{ _trans('common.Save') }}</button>
                            </div>
                        </div>


                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('public/frontend/assets/js/iziToast.js') }}"></script>
    <script src="{{ url('public/backend/js/image_preview.js') }}"></script>


    <script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/ckeditor/config.js') }}"></script>
    <script src="{{ asset('public/ckeditor/styles.js') }}"></script>
    <script src="{{ asset('public/ckeditor/build-config.js') }}"></script>
    <script src="{{ asset('public/backend/js/ticket_ckeditor.js') }}"></script>

@endsection
