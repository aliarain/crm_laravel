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
                                        <label class="form-label">{{ _trans('project.Employee') }} <span
                                                class="text-danger">*</span></label>
                                        <input hidden value="{{ _trans('project.Select Employee') }}" id="select_members">
                                        <select name="user_id" class="form-control " id="members" required>
                                            @if (@$data['edit'])
                                                <option value="{{ @$data['edit']->user->id }}" selected>
                                                    {{ @$data['edit']->user->name }}</option>
                                            @endif
                                        </select>
                                        @if ($errors->has('user_id'))
                                            <div class="error">{{ $errors->first('user_id') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Type') }} <span
                                                class="text-danger">*</span></label>
                                        <input hidden value="{{ _trans('award.Select Type') }}" id="select_award_type">
                                        <select name="award_type" class="form-control select2" id="award_type" required>
                                            @foreach ($data['award_types'] as $award_type)
                                                <option value="{{ @$award_type->id }}"
                                                    {{ @$data['edit']->award_type_id == $award_type->id ? ' selected' : '' }}>
                                                    {{ @$award_type->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('award_type'))
                                            <div class="error">{{ $errors->first('award_type') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">{{ _trans('award.Gift') }}</label>
                                        <input type="text" name="gift" class="form-control ot-form-control ot_input"
                                            required value="{{ @$data['edit'] ? @$data['edit']->gift : old('gift') }}">
                                        @if ($errors->has('gift'))
                                            <div class="error">{{ $errors->first('gift') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Date') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="date" class="form-control ot-form-control ot_input"
                                            value="{{ @$data['edit'] ? @$data['edit']->date : date('Y-m-d') }}" required>
                                        @if ($errors->has('date'))
                                            <div class="error">{{ $errors->first('date') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Status') }} <span
                                                class="text-danger">*</span></label>
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





                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Amount') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="amount" class="form-control ot-form-control ot_input"
                                            id="amount"
                                            value="{{ @$data['edit'] ? $data['edit']->amount : old('amount') }}" required>
                                        @if ($errors->has('amount'))
                                            <div class="error">{{ $errors->first('amount') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Attachment') }}
                                            <a data-toggle="tooltip"
                                                title="<img width='100' src='{{ uploaded_asset($data['edit']->attachment) }}' />">
                                                <i class="fas fa-link ml-1"></i>
                                            </a>
                                        </label>

                                        <div class="ot_fileUploader left-side mb-3">
                                            <input class="form-control" type="text" placeholder="{{ _trans('common.Attachment') }}"
                                                name="" readonly="" id="placeholder">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse') }}</label>
                                                <input type="file" class="d-none form-control" name="attachment"
                                                    id="fileBrouse">
                                            </button>
                                        </div>
                                        @if ($errors->has('attachment'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('attachment') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Award Information') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="award_info"
                                            class="form-control ot-form-control ot_input" id="award_info"
                                            value="{{ @$data['edit'] ? $data['edit']->gift_info : old('award_info') }}"
                                            required>
                                        @if ($errors->has('award_info'))
                                            <div class="error">{{ $errors->first('award_info') }}</div>
                                        @endif
                                    </div>
                                </div>

                                @include('backend.performance.goal.goal_select')

                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Description') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea type="text" name="content" class="form-control content" required>{!! @$data['edit']->description !!}</textarea>
                                        @if ($errors->has('content'))
                                            <div class="error">{{ $errors->first('content') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if (@$data['url'])
                                <div class="row">
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
        </div>
    </div>

    <input type="hidden" id="get_user_url" value="{{ route('user.getUser') }}">
@endsection
@section('script')
    <script src="{{ asset('public/backend/js/pages/__award.js') }}"></script>
    <script src="{{ asset('public/frontend/assets/js/iziToast.js') }}"></script>
    <script src="{{ asset('public/backend/js/image_preview.js') }}"></script>
    <script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/ckeditor/config.js') }}"></script>
    <script src="{{ asset('public/ckeditor/styles.js') }}"></script>
    <script src="{{ asset('public/ckeditor/build-config.js') }}"></script>
    <script src="{{ asset('public/backend/js/global_ckeditor.js') }}"></script>
@endsection
