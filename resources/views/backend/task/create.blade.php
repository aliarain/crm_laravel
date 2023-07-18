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
                <form action="{{ $data['url'] }}" enctype="multipart/form-data" method="post" id="attendanceForm">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ @$request->project_id }}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control ot-form-control ot_input" placeholder="{{ _trans('common.Name') }}" required
                                    value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('project.Priority') }} <span
                                        class="text-danger">*</span></label>
                                <select name="priority" class="form-control select2" required>
                                    <option value="" disabled >Select an option</option>
                                    <option value="32">{{ _trans('project.Low') }}</option>
                                    <option value="31">{{ _trans('project.Medium') }}</option>
                                    <option value="30">{{ _trans('project.High') }}</option>
                                    <option value="29" selected>{{ _trans('project.Urgent') }}
                                    </option>
                                </select>
                                @if ($errors->has('priority'))
                                    <div class="error">{{ $errors->first('priority') }}</div>
                                @endif
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('project.Progress') }}
                                    <small id="progress_percentage"> 0% </small> </label>
                                <input type="range" name="progress" class="" min="0" max="100"
                                    value="0" oninput="progressValue(this.value)" onchange="progressValue(this.value)">
                                @if ($errors->has('progress'))
                                    <div class="error">{{ $errors->first('progress') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Status') }} <span
                                        class="text-danger">*</span></label>
                                <select name="status" class="form-control select2" required>
                                    <option value="" disabled>Select an option</option>
                                    <option value="24">{{ _trans('project.Not Started') }}</option>
                                    <option value="26" selected>{{ _trans('project.In Progress') }}
                                    </option>
                                    <option value="25">{{ _trans('project.On Hold') }}</option>
                                    <option value="28">{{ _trans('project.Cancelled') }}</option>
                                    <option value="27">{{ _trans('project.Completed') }}</option>
                                </select>
                                @if ($errors->has('status'))
                                    <div class="error">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>



                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Start Date') }} <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control ot-form-control ot_input"
                                    value="{{ date('Y-m-d') }}" required>
                                @if ($errors->has('start_date'))
                                    <div class="error">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('project.End Date') }} <span
                                        class="text-danger">*</span>
                                    </label>
                                        <input type="date" name="end_date" class="form-control ot-form-control ot_input"
                                            value="{{ date('Y-m-d') }}" required>
                                        @if ($errors->has('end_date'))
                                            <div class="error">{{ $errors->first('end_date') }}</div>
                                        @endif
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('project.Assignees') }} <span class="text-danger">*</span></label>
                                <input hidden value="{{ _trans('project.Select Members') }}" id="select_members">
                                <select name="user_ids[]" class="form-control  select2" id="members" required multiple>
                                </select>
                                @if ($errors->has('user_ids'))
                                    <div class="error">{{ $errors->first('user_ids') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label">{{ _trans('common.Description') }} <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control content ot-form-control" required>{{ old('content') }}</textarea>
                                @if ($errors->has('content'))
                                    <div class="error">{{ $errors->first('content') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (@$data['url'])
                        <div class="row  mt-20">
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


    <input type="hidden" id="get_user_url" value="{{ route('user.getUser') }}">
@endsection
@section('script')
    <script src="{{ asset('public/backend/js/pages/__project.js') }}"></script>
    <script src="{{ asset('public/frontend/assets/js/iziToast.js') }}"></script>
    <script src="{{ asset('public/backend/js/image_preview.js') }}"></script>
    <script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/ckeditor/config.js') }}"></script>
    <script src="{{ asset('public/ckeditor/styles.js') }}"></script>
    <script src="{{ asset('public/ckeditor/build-config.js') }}"></script>
    <script src="{{ asset('public/backend/js/global_ckeditor.js') }}"></script>
@endsection
