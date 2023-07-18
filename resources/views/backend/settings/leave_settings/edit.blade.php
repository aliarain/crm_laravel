@extends('backend.layouts.app')
@section('title', @$data['title'])

@section('content')
    <div class="content-wrapper cus-content-wrapper">

        <!-- Main content -->
        <div class="container-fluid border-radius-5 p-imp-30">
            <div class="row mt-4">
                <div class="offset-md-3 col-md-6 pr-md-3">
                    <div class="card card-with-shadow border-0">
                        <div class="px-primary py-primary">
                            <h4>{{ _trans('common.Other leave Information') }}</h4>
                            <hr>
                            <div id="General-0">
                                <fieldset class="form-group mb-5">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="{{ route('leaveSettings.update') }}" method="post">
                                                @csrf
                                                @method('PATCH')
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label text-capitalize">{{_trans('common.Sandwich Leave')}}</label>
                                                    <div class="col-sm-9">
                                                        <div>
                                                            <label class="switch">
                                                                <input type="checkbox" id="sandwich_leave"
                                                                       name="sandwich"
                                                                       {{  $data['leaveSetting']->sandwich_leave == 1 ? 'checked' : '' }}  value=" {{  $data['leaveSetting']->sandwich_leave == 1 ? 1 : '' }}">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label text-capitalize">{{_trans('common.Fiscal Year')}} <span class="text-danger">*</span></label>
                                                    <div class="col-sm-9">
                                                        @php
                                                            $months = ['January','February','March','April','May','June','July','August','September','October','November','December']
                                                        @endphp
                                                        <div>
                                                            <select name="month" class="form-control" required>
                                                                <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                                                @foreach($months as $key => $month)
                                                                    <option value="{{ $key }}" {{  $data['leaveSetting']->month+1 == $key+1 ? 'selected' : ''  }}>{{ $month }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label text-capitalize">{{_trans('common.Leave Prorate')}}</label>
                                                    <div class="col-sm-9">
                                                        <div>
                                                            <label class="switch">
                                                                <input type="checkbox" id="prorate_leave"
                                                                       name="prorate"
                                                                       {{  $data['leaveSetting']->prorate_leave == 1 ? 'checked' : '' }} value=" {{  $data['leaveSetting']->prorate_leave == 1 ? 1 : '' }}">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <div class="offset-md-5 col-md-4">
                                                        @if(hasPermission('leave_settings_update'))
                                                            <button type="submit" class="btn btn-primary pull-right">
                                                                {{ _trans('common.Save') }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
