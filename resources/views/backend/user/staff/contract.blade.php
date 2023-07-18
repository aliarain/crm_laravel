@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
@include('backend.partials.staff_navbar')
<div class="profile-content">
        <!-- profile body start -->
        <div class="profile-body profile-body-cus">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
                <div class="main-panel">
                    <div class="vertical-tab">
                        <div class="row no-gutters">
                            <div class="col-12 pl-md-3 pt-md-0 pt-sm-4 pt-4">

                                @if (url()->current() === route('staff.profile.info','contract'))
                                  
                                    <div class="">
                                        <div class="tab-content px-primary">
                                            <div id="Contract" class="tab-pane active">
                                                <div class="content mb-3">
                                                        <input type="text" hidden name="user_id" value="{{ $data['id'] }}">
                                                        <div class="card-body" data-select2-id="698">
                                                            <div class="row">
                                                                <div class="col-lg-6 d-flex">
                                                                    <div class="col-lg-6">
                                                                        <div class="fw-bold title-color" for="#"><h4>{{ _trans('common.Contract Date') }}</h4></div> 
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <p class="content-color">{{ showDate(@$data['show']->original['data']['contract_start_date'] ) }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 d-flex">
                                                                    <div class="col-lg-6">
                                                                        <div class="fw-bold title-color" for="#"><h4>{{ _trans('common.Contract End') }}</h4></div> 
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <p class="content-color">{{ showDate($data['show']->original['data']['contract_end_date']) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-lg-6 d-flex">
                                                                    <div class="col-lg-6">
                                                                        <div class="fw-bold title-color" for="#"><h4>{{ _trans('common.Basic Salary') }}</h4></div> 
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <p class="content-color">{{ showAmount($data['show']->original['data']['basic_salary']) }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 d-flex">
                                                                    <div class="col-lg-6">
                                                                        <div class="fw-bold title-color" for="#"><h4>{{ _trans('payroll.Payslip Type') }}</h4></div> 
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <p class="content-color">{{ _trans('payroll.Per Month') }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-lg-6 d-flex">
                                                                    <div class="col-lg-6">
                                                                        <div class="fw-bold title-color" for="#"><h4>{{ _trans('payroll.Late Check In') }}</h4></div> 
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <p class="content-color">{{ @$data['show']->original['data']['late_check_in'] }} {{_trans('common.Days')}}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 d-flex">
                                                                    <div class="col-lg-6">
                                                                        <div class="fw-bold title-color" for="#"><h4>{{ _trans('payroll.Late Check out') }}</h4></div> 
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <p class="content-color">{{ @$data['show']->original['data']['early_check_out'] }} {{_trans('common.Days')}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-lg-6 d-flex">
                                                                    <div class="col-lg-6">
                                                                        <div class="fw-bold title-color" for="#"><h4>{{ _trans('payroll.Extra Leave') }}</h4></div> 
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <p class="content-color">{{ @$data['show']->original['data']['monthly_leave'] }} {{_trans('common.Days')}}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 d-flex">
                                                                    <div class="col-lg-6">
                                                                        <div class="fw-bold title-color" for="#"><h4>{{ _trans('payroll.Monthly Leave') }}</h4></div> 
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <p class="content-color">{{ @$data['show']->original['data']['monthly_leave'] }} {{_trans('common.Days')}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                               

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- profile body form end -->
    <!-- profile body end -->

</div>
   
@endsection
@section('script')
    <script src="{{ url('public/backend/js/pages/__profile.js') }}"></script>
@endsection
