<div class="modal  fade lead-modal bd-example-modal-lg in" id="lead-modal" role="dialog"
    aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content data">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title text-white">{{ @$data['title'] }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-5">
                    <div class="col-md-12">
                        <form action="{{ $data['url'] }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <h4>{{ _trans('common.Basic Info') }}</h4>
                                <hr>
                                <div class="table-content table-basic">
                                    <table width="100%" border="0" cellpadding="5" cellspacing="0"
                                        class="black12">
                                        <tbody>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Employee') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ @$data['salary']->employee->name }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Gross Salary') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ currency_format(@$data['salary']->employee->basic_salary) }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Total Working Day') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ @$data['info']['total_working_days'] }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Total Present') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ @$data['info']['total_present'] }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Total Absent') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">{{ @$data['info']['total_absent'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Total Late') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">{{ @$data['info']['total_late'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Total Early Leave') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">{{ @$data['info']['total_early'] }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <h4>{{ _trans('payroll.Advance Salary') }}</h4>
                                <hr>
                                <div class="table-content table-basic">
                                    <table width="100%" border="0" cellpadding="5" cellspacing="0"
                                        class="black12">

                                        <thead>
                                            <tr>
                                                <th scope="col">{{ _trans('common.Advance Type') }}</th>
                                                <th scope="col">{{ _trans('common.Amount') }}</th>
                                                <th scope="col">{{ _trans('common.Installment') }}</th>
                                                <th scope="col">{{ _trans('common.Payment') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (@$data['info']['advance_salary'])
                                                @foreach (@$data['info']['advance_salary'] as $key => $value)
                                                    <tr>
                                                        <td>{{ @$value->advance_type->name }}</td>
                                                        <td>
                                                            <?= '<small class="text-info">' . _trans('payroll.Requested') . ' : ' . currency_format($value->request_amount) . '</small><br>' ?>
                                                            <?= '<small class="text-success">' . _trans('payroll.Approved') . ' : ' . currency_format($value->amount) . '</small><br>' ?>
                                                        </td>
                                                        <td>
                                                            <small class="text-info">
                                                                @if ($value->recovery_mode == 1)
                                                                    {{ currency_format(@$value->installment_amount) }}
                                                                    [{{ _trans('payroll.Installment') }}]
                                                                @else
                                                                    {{ currency_format(@$value->amount) }} [
                                                                    {{ _trans('payroll.One Time') }} ]
                                                                @endif
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <?= '<small class="badge badge-' . @$value->payment->class . '">' . @$value->payment->name . '</small>' ?>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <td><strong> {{ _trans('common.Total') }}:
                                                    {{ currency_format($data['info']['installment'] + $data['info']['onetime']) }}
                                                </strong></td>
                                        </tfoot>

                                    </table>
                                </div>


                                <h4>{{ _trans('payroll.Addition Salary') }}</h4>
                                <hr>
                                <div class="table-content table-basic">
                                    <table width="100%" border="0" cellpadding="5" cellspacing="0"
                                        class="black12">
                                        <thead>
                                            <tr>
                                                <th colsapn="3" class="text-start">
                                                    {{ _trans('common.Title') }}</th>
                                                <th colsapn="3" class="text-start">
                                                    {{ _trans('common.Type') }}</th>
                                                <th colsapn="3" class="text-start">
                                                    {{ _trans('common.Amount') }}</th>
                                                <th colsapn="3" class="text-end">
                                                    {{ _trans('common.Amount Type') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (@$data['info']['addition_detail'])
                                                @foreach (@$data['info']['addition_detail'] as $key => $value)
                                                    <tr>
                                                        <td class="text-start">{{ @$value['name'] }}</td>
                                                        <td class="text-start">
                                                            @if ($value['type'] == 1)
                                                                <?= '<small class="badge badge-success">' . _trans('payroll.Addition') . '</small>' ?>
                                                            @else
                                                                <?= '<small class="badge badge-danger">' . _trans('payroll.Deduction') . '</small>' ?>
                                                            @endif
                                                        </td>
                                                        <td class="text-start">
                                                            <small class="text-info">
                                                                @if ($value['amount_type'] == 1)
                                                                    {{ currency_format($value['old_amount']) }}
                                                                @else
                                                                    {{ $value['old_amount'] . '%' }}
                                                                @endif
                                                                = {{ currency_format($value['amount']) }}
                                                            </small>
                                                        </td>
                                                        <td class="text-end">
                                                            @if ($value['amount_type'] == 1)
                                                                <?= '<small class="badge badge-success">' . _trans('common.Fixed') . '</small><br>' ?>
                                                            @else
                                                                <?= '<small class="badge badge-info">' . _trans('common.Percentage') . '</small><br>' ?>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <td> <strong>{{ _trans('common.Total') }}:
                                                    {{ currency_format($data['info']['addition']) }} </strong>
                                            </td>
                                        </tfoot>
                                    </table>
                                </div>


                                <h4>{{ _trans('payroll.Deduction Salary') }}</h4>
                                <hr>
                                <div class="table-content table-basic">
                                    <table width="100%" border="0" cellpadding="5" cellspacing="0"
                                    class="black12">
                                    <thead>
                                        <tr>
                                            <th colsapn="3" class="text-start">
                                                {{ _trans('common.Title') }}</th>
                                            <th colsapn="3" class="text-start">
                                                {{ _trans('common.Type') }}</th>
                                            <th colsapn="3" class="text-start">
                                                {{ _trans('common.Amount') }}</th>
                                            <th colsapn="3" class="text-end">
                                                {{ _trans('common.Amount Type') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (@$data['info']['deduction_detail'])
                                            @foreach (@$data['info']['deduction_detail'] as $key => $value)
                                                <tr>
                                                    <td class="text-start">{{ @$value['name'] }}</td>
                                                    <td class="text-start">
                                                        @if ($value['type'] == 1)
                                                            <?= '<small class="badge badge-success">' . _trans('payroll.Addition') . '</small>' ?>
                                                        @else
                                                            <?= '<small class="badge badge-danger">' . _trans('payroll.Deduction') . '</small>' ?>
                                                        @endif
                                                    </td>
                                                    <td class="text-start">
                                                        <small class="text-info">
                                                            @if ($value['amount_type'] == 1)
                                                                {{ currency_format($value['old_amount']) }}
                                                            @else
                                                                {{ $value['old_amount'] . '%' }}
                                                            @endif
                                                            = {{ currency_format($value['amount']) }}
                                                        </small>
                                                    </td>
                                                    <td class="text-end">
                                                        @if ($value['amount_type'] == 1)
                                                            <?= '<small class="badge badge-success">' . _trans('common.Fixed') . '</small><br>' ?>
                                                        @else
                                                            <?= '<small class="badge badge-info">' . _trans('common.Percentage') . '</small><br>' ?>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <td>  {{ _trans('common.Total') }}:
                                                {{ currency_format($data['info']['deduction']) }}
                                        </td>
                                    </tfoot>
                                </table>
                                </div>
                            

                                <div class="table-content table-basic">
                                    <table width="100%" border="0" cellpadding="5" cellspacing="0"
                                    class="black12">
                                        <thead class="card-header-invoice header-color">
                                            <tr>
                                                <td colspan="5" class="w-350 text-start">
                                                        {{ _trans('common.Title') }} </td>
                                                <td colspan="5" class="w-350 text-end">
                                                        {{ _trans('common.Amount') }}</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (@$data['info']['addition_detail'])
                                                @foreach (@$data['info']['addition_detail'] as $key => $value)
                                                    <tr>
                                                        <td colspan="5" class="w-350 text-start">
                                                            {{ @$value['name'] }} [
                                                            <?= '<small class="text-success">' . _trans('payroll.Addition') . '</small>' ?>
                                                            ] </td>
                                                        <td colspan="5" class="w-350 text-end">
                                                            <span class="text-info">
                                                                {{ currency_format($value['amount']) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            @if (@$data['info']['deduction_detail'])
                                                @foreach (@$data['info']['deduction_detail'] as $key => $value)
                                                    <tr>
                                                        <td colspan="5" class="w-350 text-start">
                                                            {{ @$value['name'] }} [
                                                            <?= '<small class="text-danger">' . _trans('payroll.Deduction') . '</small>' ?>
                                                            ] </td>
                                                        <td colspan="5" class="w-350 text-end">
                                                            {{ currency_format($value['amount']) }} </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            @if (@$data['info']['advance_salary'])
                                                @foreach (@$data['info']['advance_salary'] as $key => $value)
                                                    <tr>
                                                        <td colspan="5" class="w-350 text-start">
                                                            {{ $value->advance_type->name }} [
                                                            <?= '<small class="text-danger">' . _trans('payroll.Deduction') . '</small>' ?>
                                                            ] </td>
                                                        <td colspan="5" class="w-350 text-end">
                                                            @if ($value->recovery_mode == 1)
                                                                {{ currency_format(@$value->installment_amount) }}
                                                            @else
                                                                {{ currency_format(@$value->amount) }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            <tr>
                                                <td colspan="5" class="w-350 text-start">
                                                    {{ _trans('payroll.Absent') }} [
                                                    <?= '<small class="text-danger">' . _trans('payroll.Deduction') . '</small>' ?>
                                                    ] </td>
                                                <td colspan="5" class="w-350 text-end">
                                                    {{ currency_format(number_format(@$data['info']['leave_cuts'], 2)) }}
                                                </td>
                                            </tr>

                                        </tbody>
                                        <tfoot class="card-footer-invoice border-none">
                                            <tr>
                                                <td class="text-start border-bottom-0" colspan="1">
                                                    {{ _trans('common.Adjust:') }}
                                                </td>
                                                <td class="text-start border-bottom-0" colspan="2">
                                                    <input type="number" step=any class="form-control ot_input"
                                                        name="adjust" placeholder="{{ _trans('common.500.00') }}">
                                                </td>
                                                <td class="text-end border-bottom-0" colspan="3">
                                                   {{ _trans('common.Total') }}:
                                                </td>
                                                <td class="text-end border-bottom-0" colspan="3">
                                                    {{ currency_format(number_format(@$data['info']['net_salary'], 2)) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>




                            </div>
                            <div class="form-group text-right mt-3 d-flex justify-content-end">
                                <button type="submit"
                                    class="crm_theme_btn pull-right"><b>{{ @$data['button'] }}</b></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('public/backend/js/payroll/__salary_generate.js') }}"></script>
