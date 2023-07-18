@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}

                <div class="row">
                    {{-- Start profile overview --}}
                    <div class="col-md-4">
                        <div class="table-content table-basic">
                            <div class="card">
                                <div class="card-body">
                                    <div id="General-0">
                                        <fieldset class="form-group mb-5">
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="form-group row">
                                                        <h4
                                                            class="col-sm-3 text-capitalize">{{ _trans('common.Employee') }}</h4>
                                                        <div class="col-sm-9">
                                                            <img class="employee-avater-img"
                                                                src="{{ uploaded_asset($data['salary']->employee->avatar_id) }}"
                                                                alt="" height="50" width="50"> <br>
                                                            <p>{{ $data['salary']->employee->name }}</p>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group row">
                                                        <h4
                                                            class="col-sm-3 text-capitalize">{{ _trans('common.Designation') }}</h4>
                                                        <div class="col-sm-9">
                                                           <p>{{ $data['salary']->employee->designation->title }}</p> 
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group row">
                                                        <h4
                                                            class="col-sm-3 text-capitalize">{{ _trans('common.Department') }}</h4>
                                                        <div class="col-sm-9">
                                                           <p>{{ $data['salary']->employee->department->title }}</p> 
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
                    {{-- End profile overview --}}

                    {{-- Start summary overview --}}
                    <div class="col-md-8">
                        <div class="table-content table-basic">
                            <div class="card">
                                <div class="card-body">
                                    <div id="General-0">
                                        <h4>{{ _trans('common.Basic Info') }}</h4>
                                    <hr>
                                    <table  width="100%" border="0" cellpadding="5" cellspacing="0" class="black12 table table-bordered ">
                                        <tbody class="tbody">
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
                                                <td align="left" valign="middle">{{ @$data['salary']->total_working_day }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Total Present') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">{{ @$data['salary']->present }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Total Absent') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">{{ @$data['salary']->absent }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Total Late') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">{{ @$data['salary']->late }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Total Early Leave') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">{{ @$data['salary']->left_early }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
    
    
                                    <table class="table mb-0">
                                        <thead class="card-header-invoice header-color thead">
                                            <tr>
                                                <td colspan="5" class="w-350 text-start"><h5>
                                                        {{ _trans('common.Title') }} </h5></td>
                                                <td colspan="5" class="w-350 text-end"><h5>
                                                        {{ _trans('common.Amount') }} </h5></td>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            @if (@$data['salary']->advance_amount > 0)
                                                    <tr>
                                                        <td colspan="5" class="w-350 text-start">{{ _trans('payroll.Advance') }} [
                                                            <?= '<small class="text-success">' . _trans('payroll.Deduction') . '</small>' ?>] </td>
                                                        <td colspan="5" class="w-350 text-end">
                                                            <span class="text-danger">
                                                                {{ currency_format($data['salary']->advance_amount) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                            @endif
    
                                            @if (@$data['salary']->deduction_details)
                                                @foreach (@$data['salary']->deduction_details as $key => $value)
                                                    <tr>
                                                        <td colspan="5" class="w-350 text-start">{{ @$value['name'] }} [
                                                            <?= '<small class="text-danger">' . _trans('payroll.Deduction') . '</small>' ?>
                                                            ] </td>
                                                        <td colspan="5" class="w-350 text-end">
                                                            <span class="text-danger">
                                                            {{ currency_format($value['amount']) }} 
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            @if (@$data['salary']->allowance_details)
                                                @foreach (@$data['salary']->allowance_details as $key => $value)
                                                    <tr>
                                                        <td colspan="5" class="w-350 text-start">{{ @$value['name'] }} [
                                                            <?= '<small class="text-success">' . _trans('payroll.Addition') . '</small>' ?>
                                                            ] </td>
                                                        <td colspan="5" class="w-350 text-end">
                                                            <span class="text-success">
                                                                {{ currency_format($value['amount']) }} 
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            @if (@$data['salary']->absent_amount)
                                                <tr>
                                                    <td colspan="5" class="w-350 text-start">{{ _trans('payroll.Absent') }} [
                                                        <?= '<small class="text-danger">' . _trans('payroll.Deduction') . '</small>' ?>
                                                        ] </td>
                                                    <td colspan="5" class="w-350 text-end">
                                                        <span class="text-danger">
                                                        {{ currency_format(number_format(@$data['salary']->absent_amount, 2)) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (@$data['salary']->adjust)
                                            <tr>
                                                <td colspan="5" class="w-350 text-start">{{ _trans('payroll.Adjust Salary') }} [
                                                    <?= '<small class="text-success">' . _trans('payroll.Addition') . '</small>' ?>
                                                    ] </td>
                                                <td colspan="5" class="w-350 text-end">
                                                    <span class="text-success">
                                                    {{ currency_format(number_format(@$data['salary']->adjust, 2)) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endif
    
                                        </tbody>
                                        <tfoot class="card-footer-invoice border-none tbody">
                                            <tr>
                                                <td class="text-end border-bottom-0" colspan="8"><strong>{{ _trans('common.Total') }}:</strong>
                                                </td>
                                                <td class="text-end border-bottom-0" colspan="8">
                                                    {{ currency_format(number_format(@$data['salary']->net_salary, 2)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-end border-bottom-0" colspan="8"><strong>{{ _trans('common.Total Paid') }}:</strong>
                                                </td>
                                                <td class="text-end border-bottom-0" colspan="8">
                                                    {{ currency_format(number_format((@$data['salary']->net_salary - $data['salary']->due_amount), 2)) }}
                                                </td>
                                            </tr>
                                            <tr class="text-danger">
                                                <td class="text-end border-bottom-0" colspan="8"><strong>{{ _trans('common.Total Due') }}:</strong>
                                                </td>
                                                <td class="text-end border-bottom-0" colspan="8">
                                                    {{ currency_format(number_format(($data['salary']->due_amount), 2)) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- End summary overview --}}
                </div>
@endsection
