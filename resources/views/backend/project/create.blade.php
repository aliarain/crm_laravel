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
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ $data['url'] }}" enctype="multipart/form-data" method="post" id="attendanceForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control ot-form-control ot_input" placeholder="{{_trans('common.name')}}"
                                             value="{{ old('name') }}">
                                        @if ($errors->has('name'))
                                            <div class="error">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('client.Client') }} <span class="text-danger">*</span></label>
                                        <select name="client_id" class="form-control select2" >
                                            @foreach ($data['clients'] as $client)
                                                <option value="{{ @$client->id }}">
                                                    {{ @$client->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('client_id'))
                                            <div class="error">{{ $errors->first('client_id') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('project.Progress') }}
                                            <small id="progress_percentage"> 0% </small> </label>
                                        <input type="range" name="progress" class="" min="0" max="100"
                                            value="0" oninput="progressValue(this.value)"
                                            onchange="progressValue(this.value)">
                                        @if ($errors->has('progress'))
                                            <div class="error">{{ $errors->first('progress') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Status') }} <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control select2" >
                                            <option value="24">{{ _trans('project.Not Started') }}</option>
                                            <option value="26" selected>{{ _trans('project.In Progress') }}
                                            </option>
                                            <option value="25">{{ _trans('project.On Hold') }}</option>
                                            <option value="28">{{ _trans('project.Cancelled') }}</option>
                                            <option value="27">{{ _trans('project.Completed') }}</option>
                                        </select>
                                        @if ($errors->has('month'))
                                            <div class="error">{{ $errors->first('month') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('project.Billing Type') }} <span class="text-danger">*</span></label>
                                        <select name="billing_type" class="form-control select2" 
                                            onchange="billingType(this.value)" id="billing_type">
                                            <option value="fixed" selected>{{ _trans('project.Fixed Rate') }}
                                            </option>
                                            <option value="hourly">{{ _trans('project.Project Hours') }}</option>
                                        </select>
                                        @if ($errors->has('month'))
                                            <div class="error">{{ $errors->first('month') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 total_rate">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('project.Total Rate') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="total_rate"
                                            class="form-control ot-form-control ot_input" value="{{ old('total_rate') }}" placeholder="{{_trans('project.Total Rate')}}"
                                             id="total_rate" onkeyup="calculateAmount(this.value)">
                                        @if ($errors->has('total_rate'))
                                            <div class="error">{{ $errors->first('total_rate') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 per_rate d-none">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('project.Per Rate') }}</label>
                                        <input type="number" name="per_rate" class="form-control ot-form-control ot_input"
                                            value="{{ old('per_rate') }}" id="per_rate"
                                            onkeyup="calculateAmount(this.value)">
                                        @if ($errors->has('per_rate'))
                                            <div class="error">{{ $errors->first('per_rate') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('project.Estimated Hours') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="estimated_hour"
                                            class="form-control ot-form-control ot_input" placeholder="{{_trans('project.Estimated Hours')}}"
                                            value="{{ old('estimated_hour') }}" id="estimated_hour" 
                                            onkeyup="calculateAmount(this.value)">
                                        @if ($errors->has('estimated_hour'))
                                            <div class="error">{{ $errors->first('estimated_hour') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ _trans('project.Members') }} <span class="text-danger">*</span></label>
                                        <input hidden value="{{ _trans('project.Select Members') }}" name="members" id="select_members">
            
                                        <select name="user_ids[]" class="form-control select2" id="_employees"  multiple onchange="validateSelect(this)">
                                        </select>
                                        @if ($errors->has('members'))
                                        <div id="user_ids_error" class="error">{{ $errors->first('members') }}</div>
                                      @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Start Date') }} <span class="text-danger">*</span></label>
                                        <input type="date" name="start_date"
                                            class="form-control ot-form-control ot_input" value="{{ date('Y-m-d') }}"
                                            >
                                        @if ($errors->has('start_date'))
                                            <div class="error">{{ $errors->first('start_date') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('project.Deadline') }} <span class="text-danger">*</span></label>
                                        <input type="date" name="end_date"
                                            class="form-control ot-form-control ot_input" value="{{ date('Y-m-d') }}"
                                            >
                                        @if ($errors->has('end_date'))
                                            <div class="error">{{ $errors->first('end_date') }}</div>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Amount') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="amount"
                                            class="form-control ot-form-control ot_input" id="amount" placeholder="{{_trans('common.Amount')}}"
                                            value="{{ old('amount') }}" >
                                        @if ($errors->has('amount'))
                                            <div class="error">{{ $errors->first('amount') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ _trans('project.Priority') }} <span class="text-danger">*</span></label>
                                        <select name="priority" class="form-control select2" >
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





                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label">{{ _trans('common.Description') }} <span class="text-danger">*</span></label>
                                        <textarea name="content" class="form-control content" placeholder="{{ _trans('common.Description') }}" >{{ old('content') }}</textarea>
                                        @if ($errors->has('content'))
                                            <div class="error">{{ $errors->first('content') }}</div>
                                        @endif
                                    </div>
                                </div>
                           

                            </div>

                            @if (@$data['url'])
                                <div class="row  mt-20">
                                    <div class="col-md-12">
                                        <div class="text-end">
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
    <script src="{{ asset('public/backend/js/pages/__project.js') }}"></script>
    <script src="{{ asset('public/frontend/assets/js/iziToast.js') }}"></script>
    <script src="{{ asset('public/backend/js/image_preview.js') }}"></script>
    <script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/ckeditor/config.js') }}"></script>
    <script src="{{ asset('public/ckeditor/styles.js') }}"></script>
    <script src="{{ asset('public/ckeditor/build-config.js') }}"></script>
    <script src="{{ asset('public/backend/js/global_ckeditor.js') }}"></script>
@endsection
