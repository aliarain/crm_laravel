@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
    <div class="table-content table-basic">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('leaveRequest.store') }}" class="" enctype="multipart/form-data">
                    @csrf
                    <input type="text" hidden value="{{ auth()->id() }}" name="user_id">
                    <div class="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ _trans('common.Leave Type') }} <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2" name="assign_leave_id" required>
                                        <option value="" disabled selected>{{ _trans('common.Choose One') }}
                                        </option>
                                        @foreach ($data['leaveTypes'] as $type)
                                            <option value="{{ $type->id }}">{{ $type->type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ _trans('common.Substitute') }}</label>
                                    <select name="substitute_id" class="form-control select2" id="user_id">

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ _trans('common.Leave from') }}<span
                                            class="text-danger">*</span></label>
                                    <input class="daterange-table-filter form-control ot_input" type="text" name="daterange"
                                        value="{{ date('m/d/Y') }}-{{ date('m/d/Y') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label class="form-label">{{ _trans('common.Attachment') }}</label>
                                    {{-- <input id="upload_file" name="file" type="file"
                                        class="form-control ot-form-control ot_input upload_file"> --}}

                                    <div class="ot_fileUploader left-side mb-3">
                                        <input class="form-control" type="text" placeholder="{{ _trans('common.Attachment') }}" name="" readonly="" id="placeholder">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse') }}</label>
                                            <input type="file" class="d-none form-control" name="file" id="fileBrouse">
                                        </button>
                                    </div>


                                    {{-- <div class="custom-image-upload-wrapper">
                                    <div class="image-area d-flex">
                                        <img id="bruh" src="{{ uploaded_asset(null) }}" alt=""
                                            class="img-fluid mx-auto my-auto">
                                    </div>
                                    <div class="input-area"><label id="upload-label" for="upload_file">
                                            {{ _trans('common.Documents file') }}
                                        </label> 
                                    </div>
                                </div> --}}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-20">
                                    <label class="form-label">{{ _trans('common.Reason') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea name="reason" class="form-control ot_input mt-0" placeholder="{{ _trans('common.Reason') }}" rows="6" required></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="crm_theme_btn ">{{ _trans('common.Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="get_user_url" value="{{ route('user.getUser') }}">
@endsection
