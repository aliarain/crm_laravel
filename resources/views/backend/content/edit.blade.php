@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
    <div class="table-basic table-content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('content.update', $data['show']->id) }}" class=""
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="department_id" value="{{ $data['show']->id }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="name">{{ _trans('common.Title') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control ot-form-control ot_input"
                                            placeholder="{{ _trans('common.Title') }}" value="{{ $data['show']->title }}"
                                            required>
                                        @if ($errors->has('title'))
                                            <div class="error">{{ $errors->first('title') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="name">{{ _trans('common.Content') }}<span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control en mt-0" name="content" rows="10">{{ $data['show']->content }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="name">{{ _trans('common.Meta Title') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="meta_title"
                                            class="form-control ot-form-control ot_input" placeholder="{{ _trans('common.Meta Title') }}"
                                            value="{{ $data['show']->meta_title }}" required>
                                        @if ($errors->has('meta_title'))
                                            <div class="error">{{ $errors->first('meta_title') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="name">{{_trans('common.Meta Description')}}</label>
                                        <textarea class="form-control mt-0 en" name="meta_description" rows="10">{{ $data['show']->meta_description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="name">{{_trans('common.Keywords')}}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="keywords" class="form-control ot-form-control ot_input"
                                            placeholder="{{ _trans('common.Keywords') }}" value="{{ $data['show']->keywords }}" required>
                                        @if ($errors->has('keywords'))
                                            <div class="error">{{ $errors->first('keywords') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row mb-3">
                                        <label class="form-label" for="image" class="col-sm-2 col-form-label">{{_trans('common.Meta Image')}}</label>
                                        <div class="col-sm-12">
                                            <div>
                                                <div class="ot_fileUploader left-side mb-3">
                                                    <input class="form-control" type="text" placeholder="{{ _trans('common.Meta Image') }}" name="" readonly="" id="placeholder">
                                                    <button class="primary-btn-small-input" type="button">
                                                        <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{_trans('common.Browse')}}</label>
                                                        <input type="file" class="d-none form-control" name="meta_image" id="fileBrouse">
                                                    </button>
                                                </div> 
                                            </div>
                                        </div>
                                      
                                        @error('meta_image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Status') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="status_id" class="form-control select2" required>
                                            <option value="1"
                                                {{ @$data['show'] ? ($data['show']->status_id == 1 ? 'Selected' : '') : '' }}>
                                                {{ _trans('payroll.Active') }}</option>
                                            <option value="4"
                                                {{ @$data['show'] ? ($data['show']->status_id == 4 ? 'Selected' : '') : '' }}>
                                                {{ _trans('payroll.Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col md-12">
                                    <div class="float-right d-flex justify-content-end">
                                        <button type="submit" class="crm_theme_btn action-btn">{{_trans('common.Update')}}</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- /.card-body -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    {{-- iziToast --}}
    <link rel="stylesheet" href="{{ asset('public/frontend/assets/css/iziToast.css') }}">
@endsection
@section('script')
    {{-- iziToast --}}
    <script src="{{ asset('public/frontend/assets/js/iziToast.js') }}"></script>
    <script src="{{ asset('public/backend/js/image_preview.js') }}"></script>
    <script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/ckeditor/config.js') }}"></script>
    <script src="{{ asset('public/ckeditor/styles.js') }}"></script>
    <script src="{{ asset('public/ckeditor/build-config.js') }}"></script>
    <script src="{{ asset('public/backend/js/global_ckeditor.js') }}"></script>

@endsection
