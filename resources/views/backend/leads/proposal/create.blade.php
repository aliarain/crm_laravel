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
                @if (url()->current() == route('client.create'))
                    <form method="POST" action="{{ route('client.store') }}" class="" enctype="multipart/form-data">
                    @else
                        <form method="POST" action="{{ route('client.update') }}" class=""
                            enctype="multipart/form-data">
                            <input type="hidden" name="id" value="{{ $data['show']->id }}">
                @endif

                @csrf
                <div class="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ _trans('common.Name') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control ot-form-control ot_input"
                                    placeholder="{{ _trans('common.Name') }}"
                                    value="{{ @$data['show'] ? $data['show']->name : old('name') }}" required>
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="form-label" for="name">{{ _trans('common.Email') }}</label>
                                <input type="email" name="email" class="form-control ot-form-control ot_input"
                                    placeholder="{{ _trans('common.Email') }}"
                                    value="{{ @$data['show'] ? $data['show']->email : old('email') }}">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ _trans('common.Phone') }} </label>
                                <input type="text" name="phone" class="form-control ot-form-control ot_input"
                                    placeholder="{{ _trans('common.Phone') }}"
                                    value="{{ @$data['show'] ? $data['show']->phone : old('phone') }}" >
                                @if ($errors->has('phone'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ _trans('client.Website') }}</label>
                                <input type="text" name="website" class="form-control ot-form-control ot_input"
                                    placeholder="{{ _trans('common.Website') }}"
                                    value="{{ @$data['show'] ? $data['show']->website : old('website') }}" >
                                @if ($errors->has('website'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('website') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ _trans('common.Address') }} </label>
                                <input type="text" name="address" class="form-control ot-form-control ot_input"
                                    placeholder="{{ _trans('common.Address') }}"
                                    value="{{ @$data['show'] ? $data['show']->address : old('address') }}">
                                @if ($errors->has('address'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ _trans('common.City') }} </label>
                                <input type="text" name="city" class="form-control ot-form-control ot_input"
                                    placeholder="{{ _trans('common.City') }}"
                                    value="{{ @$data['show'] ? $data['show']->city : old('city') }}">
                                @if ($errors->has('city'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('city') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ _trans('common.State') }} </label>
                                <input type="text" name="state" class="form-control ot-form-control ot_input"
                                    placeholder="{{ _trans('common.State') }}"
                                    value="{{ @$data['show'] ? $data['show']->state : old('state') }}">
                                @if ($errors->has('state'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('state') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ _trans('common.Zip') }} </label>
                                <input type="text" name="zip" class="form-control ot-form-control ot_input"
                                    placeholder="{{ _trans('common.Zip') }}"
                                    value="{{ @$data['show'] ? $data['show']->zip : old('zip') }}">
                                @if ($errors->has('zip'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('zip') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ _trans('common.Country') }} </label>
                                <select name="country_id" class="form-control select2 w-100" id="_country_id">
                                    <option value="{{ @$data['show'] ? $data['show']->country : '' }}">
                                        {{ @$data['show'] ? @$data['show']->countryInfo->name : '' }}</option>
                                </select>
                                @if ($errors->has('country_id'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('country_id') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ _trans('common.Status') }} <span
                                        class="text-danger">*</span></label>
                                <select name="status" class="form-select select2">
                                    <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                    <option value="1"
                                        {{ @$data['show'] ? ($data['show']->status_id == 1 ? 'selected' : '') : '' }}>
                                        {{ _trans('common.Active') }}</option>
                                    <option value="4"
                                        {{ @$data['show'] ? ($data['show']->status_id == 4 ? 'selected' : '') : '' }}>
                                        {{ _trans('common.In-active') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if (@$data['show']->avatar_id != null)
                                <img class="profile-user-img img-fluid" width="100px" height="auto" src="{{ uploaded_asset($data['show']->avatar_id) }}" alt="">
                            @endif
                            <div class="form-group mb-3">
                                <label class="mb-10">{{ _trans('profile.Avatar') }}</label>
                                <div class="ot_fileUploader left-side mb-3">
                                    <input class="form-control" type="text" placeholder="{{ _trans('profile.Avatar') }}" name="backend_image" readonly="" id="placeholder3">
                                    <button class="primary-btn-small-input" type="button">
                                        <label class="btn btn-lg ot-btn-primary" for="fileBrouse3">{{ _trans('common.Browse') }}</label>
                                        <input type="file" class="d-none form-control" name="avatar" id="fileBrouse3">
                                    </button>
                                </div>
                                
                                @if ($errors->has('avatar'))
                                    <span class="text-danger">{{ $errors->first('avatar') }}</span>
                                @endif
                            </div>
                        </div>
                        {{-- @if ($data['show']->avatar_id != null)
                            <div class="col-md-6">
                                <img class="profile-user-img img-fluid" width="100px" height="auto" src="{{ uploaded_asset($data['show']->avatar_id) }}" alt="">
                            </div>
                        @endif --}}
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-gradian">
                        {{ _trans('common.Save') }}
                    </button>
                </div>
                <!-- /.card-body -->
                </form>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ url('public/backend/js/pages/__profile.js') }}"></script>
@endsection
