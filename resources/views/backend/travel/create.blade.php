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
                                        <select name="user_id" class="form-control" id="members" required>
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
                                        <select name="travel_type" class="form-control select2" id="travel_type" required>
                                            @foreach ($data['types'] as $client)
                                                <option value="{{ @$client->id }}">
                                                    {{ @$client->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('travel_type'))
                                            <div class="error">{{ $errors->first('travel_type') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">{{ _trans('travel.Motive') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="motive" class="form-control ot-form-control ot_input"
                                            required value="{{ old('motive') }}" placeholder="{{ _trans('travel.Motive') }}">
                                        @if ($errors->has('motive'))
                                            <div class="error">{{ $errors->first('motive') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">{{ _trans('travel.Place') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="place" class="form-control ot-form-control ot_input"
                                            required value="{{ old('place') }}" placeholder="{{ _trans('travel.Place') }}">
                                        @if ($errors->has('place'))
                                            <div class="error">{{ $errors->first('place') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Start Date') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="start_date"
                                            class="form-control ot-form-control ot_input" value="{{ date('Y-m-d') }}"
                                            required>
                                        @if ($errors->has('start_date'))
                                            <div class="error">{{ $errors->first('start_date') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.End Date') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="end_date" class="form-control ot-form-control ot_input"
                                            value="{{ date('Y-m-d', strtotime('+7 day')) }}" required>
                                        @if ($errors->has('end_date'))
                                            <div class="error">{{ $errors->first('end_date') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('travel.Expected Amount') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="expect_amount"
                                            class="form-control ot-form-control ot_input" id="amount"
                                            value="{{ old('expect_amount') }}" required placeholder="{{ _trans('travel.Expected Amount') }}">
                                        @if ($errors->has('expect_amount'))
                                            <div class="error">{{ $errors->first('expect_amount') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('travel.Actual Amount') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="actual_amount"
                                            class="form-control ot-form-control ot_input" id="amount"
                                            value="{{ old('actual_amount') }}" required placeholder="{{ _trans('travel.Actual Amount') }}">
                                        @if ($errors->has('actual_amount'))
                                            <div class="error">{{ $errors->first('actual_amount') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('travel.Mode') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="mode" class="form-control select2" required>
                                            <option value="bus">{{ _trans('travel.Bus') }}</option>
                                            <option value="train">{{ _trans('travel.Train') }}</option>
                                            <option value="plane">{{ _trans('travel.Plane') }}</option>
                                        </select>
                                        @if ($errors->has('mode'))
                                            <div class="error">{{ $errors->first('mode') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Attachment') }}</label>
                                       
                                        
                                        <div class="ot_fileUploader left-side mb-3">
                                            <input class="form-control" type="text" placeholder="{{ _trans('common.Attachment') }}" name="" readonly="" id="placeholder">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse') }}</label>
                                                <input type="file" class="d-none form-control" name="attachment" id="fileBrouse">
                                            </button>
                                        </div>
                                        @if ($errors->has('attachment'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('attachment') }}</div>
                                        @endif


                                    </div>
                                </div>



                                <div class="col-lg-12">
                                    <div class="form-group mb-20">
                                        <label class="form-label">{{ _trans('common.Description') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea type="text" name="content" class="form-control content">{{ old('content') }}</textarea>
                                        @if ($errors->has('content'))
                                            <div class="error">{{ $errors->first('content') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if (@$data['url'])
                                <div class="row ">
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
