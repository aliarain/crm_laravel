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

                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label text-capitalize pt-0 pb-0">{{ _trans('common.Sandwich Leave') }}
                                                    {{-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Leave shall be deducted for weekends & holidays"></i> --}}
                                                </label>
                                                <div class="col-sm-9">
                                                    <div>
                                                        {{ $data['leaveSetting']->sandwich_leave == 1 ? 'Activated' : 'Not Activated' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label text-capitalize pt-0 pb-0">{{ _trans('common.Fiscal Year') }}
                                                    {{-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="One year period that companies and governments use for financial reporting and budgeting"></i> --}}
                                                </label>
                                                <div class="col-sm-9">
                                                    <div>
                                                        @php
                                                            $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                                                        @endphp
                                                        {{ $months[$data['leaveSetting']->month] }} {{- December}}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label text-capitalize pt-0 pb-0">{{ _trans('common.Leave Prorate') }}
                                                    {{-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="System will be automatically prorating employee allocated yearly leave based on his/her joining date configured if this configuration is enabled."></i> --}}
                                                </label>
                                                <div class="col-sm-9">
                                                    <div>
                                                        {{ $data['leaveSetting']->prorate_leave == 1 ? 'Activated' : 'Not Activated' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="offset-md-4 col-md-4">
                                                    @if(hasPermission('leave_settings_update'))
                                                        <a href="{{ route('leaveSettings.edit') }}"
                                                           class="btn btn-primary pull-right">{{ _trans('common.Edit information') }}</a>
                                                    @endif
                                                </div>
                                            </div>
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
