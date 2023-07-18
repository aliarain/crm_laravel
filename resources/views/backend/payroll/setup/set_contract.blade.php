@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([ 'title' => @$data['title'], route('admin.dashboard') => _trans('common.Dashboard'), '#' => @$data['title']]) !!}

    <!-- Main content -->
    <div class="table-content table-basic">
        <div class="card">
             
            <div class="card-body">
                <form action="{{ route('hrm.payroll_setup.user_setup_update', [$data['id'], $data['slug']]) }}"
                    method="post">
                    @csrf
                    <input type="text" hidden name="user_id" value="{{ $data['id'] }}">
                    <div class="" data-select2-id="698">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label class="form-label"> {{ _trans('common.Contract Date') }}
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control ot-form-control ot_input date" name="contract_start_date"
                                            placeholder="{{ _trans('common.Contract Date') }}"
                                            value="{{ @$data['show']->original['data']['contract_start_date'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label class="form-label"> {{ _trans('common.Contract End') }}
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control ot-form-control ot_input date" name="contract_end_date"
                                            placeholder="{{ _trans('common.Contract Date') }}"
                                            value="{{ @$data['show']->original['data']['contract_end_date'] }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" data-select2-id="697">
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        {{ _trans('common.Basic Salary') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control ot-form-control ot_input" name="basic_salary"
                                            value="{{ @$data['show']->original['data']['basic_salary'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6" data-select2-id="696">
                                <div class="form-group mb-3" data-select2-id="695">
                                    <label for="payslip_type" class="form-label">
                                        {{ _trans('common.Payslip Type') }} <i class="text-danger">*</i></label>
                                    <select name="payslip_type" id="payslip_type" class="form-control ot-form-control ot_input select2">
                                        <option value="1" selected="selected">
                                            {{ _trans('common.Per Month') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label class="form-label"> {{ _trans('common.Late Check In') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control ot-form-control ot_input" name="late_check_in"
                                        value="{{ @$data['show']->original['data']['late_check_in'] }}">
                                    <small class="form-text text-muted">
                                        {{ _trans('message.Total late check in count.') }}</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="early_check_out">
                                        {{ _trans('common.Early Check out') }} <i class="text-danger">*</i></label>
                                    <input type="number" class="form-control ot-form-control ot_input" name="early_check_out"
                                        value="{{ @$data['show']->original['data']['early_check_out'] }}">
                                    <small class="form-text text-muted">
                                        {{ _trans('message.Total early check out count.') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label class="form-label"> {{ _trans('common.Extra Leave') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control ot-form-control ot_input" name="extra_leave"
                                        value="{{ @$data['show']->original['data']['extra_leave'] }}">
                                    <small class="form-text text-muted">
                                        {{ _trans('message.Total extra leave in count.') }}</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="monthly_leave">
                                        {{ _trans('common.Monthly Leave') }} <i class="text-danger">*</i></label>
                                    <input type="number" class="form-control ot-form-control ot_input" name="monthly_leave"
                                        value="{{ @$data['show']->original['data']['monthly_leave'] }}">
                                    <small class="form-text text-muted">
                                        {{ _trans('message.Total monthly leave count.') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="crm_theme_btn ">
                                {{ _trans('common.Update') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('script')
    <script src="{{ url('public/backend/js/pages/__profile.js') }}"></script>
@endsection
