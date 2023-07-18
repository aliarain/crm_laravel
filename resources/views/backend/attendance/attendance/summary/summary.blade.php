<div id="General-0">
    <fieldset class="form-group mb-5">
        <div class="row">
            <div class="col-md-12">

                <div class="form-group row">
                    <label class="col-sm-3 text-capitalize">{{ _trans('attendance.Working Days') }}</label>
                    <div class="col-sm-9">
                        {{ $monthlySummary['working_days'] }}
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-3 text-capitalize">{{ _trans('attendance.Present') }}</label>
                    <div class="col-sm-9">
                        {{ $monthlySummary['present'] }}
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-3 text-capitalize">{{ _trans('attendance.Work Time') }}</label>
                    <div class="col-sm-9">
                        {{ $monthlySummary['work_time'] }}
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-3">{{ _trans('attendance.Absent') }}</div>
                    <div class="col-md-9">
                        {{ $monthlySummary['absent'] }}
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-3">{{ _trans('attendance.On Leave') }}</div>
                    <div class="col-md-9">
                        {{ $monthlySummary['total_leave'] }}
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-3">{{ _trans('attendance.On Time') }}</div>
                    <div class="col-md-9">
                        {{ $monthlySummary['total_on_time_in'] }}
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-3">{{ _trans('attendance.Early') }}</div>
                    <div class="col-md-9">
                        {{ $monthlySummary['total_early_in'] }}
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-3">{{ _trans('attendance.Late') }}</div>
                    <div class="col-md-9">
                        {{ $monthlySummary['total_late_in'] }}
                    </div>
                </div>
                <hr>

                <div class="form-group row">
                    <div class="col-md-3">{{ _trans('attendance.Left Timely') }}</div>
                    <div class="col-md-9">
                        {{ $monthlySummary['total_left_timely'] }}
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-3">{{ _trans('attendance.Left Early') }}</div>
                    <div class="col-md-9">
                        {{ $monthlySummary['total_left_early'] }}
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-3">{{ _trans('attendance.Left Later') }}</div>
                    <div class="col-md-9">
                        {{ $monthlySummary['total_left_later'] }}
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>
