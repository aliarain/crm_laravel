<div class="profile-body p-40 pl-40 pr-40 pt-40">
    <!-- profile body nav start -->
    <div class="profile-body-form 0">
        <div class="card-body" data-select2-id="698">
            <div class="row">
                <div class="col-lg-6 d-flex">
                    <div class="col-lg-6">
                        <div class="fw-bold title-color" >
                            <h4>{{ _trans('common.Contract Date') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="content-color">
                            {{ showDate(@$data['show']->original['data']['contract_start_date']) }}</p>
                    </div>
                </div>
                <div class="col-lg-6 d-flex">
                    <div class="col-lg-6">
                        <div class="fw-bold title-color">
                            <h4>{{ _trans('common.Contract End') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="content-color">
                            {{ showDate($data['show']->original['data']['contract_end_date']) }}</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6 d-flex">
                    <div class="col-lg-6">
                        <div class="fw-bold title-color">
                            <h4>{{ _trans('common.Basic Salary') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="content-color">
                            {{ showAmount($data['show']->original['data']['basic_salary']) }}</p>
                    </div>
                </div>
                <div class="col-lg-6 d-flex">
                    <div class="col-lg-6">
                        <div class="fw-bold title-color" >
                            <h4>{{ _trans('payroll.Payslip Type') }}</h4>
                        </div>
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
                        <div class="fw-bold title-color">
                            <h4>{{ _trans('payroll.Late Check In') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="content-color">{{ @$data['show']->original['data']['late_check_in'] }}
                            {{ _trans('common.Days') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 d-flex">
                    <div class="col-lg-6">
                        <div class="fw-bold title-color">
                            <h4>{{ _trans('payroll.Late Check out') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="content-color">{{ @$data['show']->original['data']['early_check_out'] }}
                            {{ _trans('common.Days') }}</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6 d-flex">
                    <div class="col-lg-6">
                        <div class="fw-bold title-color" >
                            <h4>{{ _trans('payroll.Extra Leave') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="content-color">{{ @$data['show']->original['data']['monthly_leave'] }}
                            {{ _trans('common.Days') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 d-flex">
                    <div class="col-lg-6">
                        <div class="fw-bold title-color">
                            <h4>{{ _trans('payroll.Monthly Leave') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="content-color">{{ @$data['show']->original['data']['monthly_leave'] }}
                            {{ _trans('common.Days') }}</p>
                    </div>
                </div>
            </div>



        </div>
    </div>
<!-- profile body form end -->
</div>
