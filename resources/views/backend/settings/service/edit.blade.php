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

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">{{ _trans('common.Title') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control ot-form-control ot_input"
                                            required value="{{ @$data['edit'] ? $data['edit']->title : old('title') }}">
                                        @if ($errors->has('title'))
                                            <div class="error">{{ $errors->first('title') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Status') }} <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control select2" required>
                                            <option {{ @$data['edit']->status_id == 1 ? 'selected' : '' }} value="1">
                                                {{ _trans('payroll.Active') }}</option>
                                            <option {{ @$data['edit']->status_id == 4 ? 'selected' : '' }} value="4">
                                                {{ _trans('payroll.Inactive') }}</option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="error">{{ $errors->first('status') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label">{{ _trans('common.Description') }} <span class="text-danger">*</span></label>
                                        <textarea type="text" name="content" class="form-control content" required>{!!  @$data['edit']->description  !!}</textarea>
                                        @if ($errors->has('content'))
                                            <div class="error">{{ $errors->first('content') }}</div>
                                        @endif
                                    </div>
                                </div>


                                @if (@$data['url'])
                                    <div class="row mt-20">
                                        <div class="col-md-12">
                                            <div class="text-right d-flex justify-content-end">
                                                <button class="crm_theme_btn ">{{ $data['button'] }}</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                        </form>
                    </div>
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
