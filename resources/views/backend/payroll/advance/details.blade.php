@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
   
    <div class="table-content table-basic">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    {{-- Start summary overview --}}
                    <div class="col-md-12 ">
                        <div class="border-0">
                            <div class="px-primary py-primary">
                                {{-- Summary  start --}}
                                <div id="General-0">
                                    <h4>
                                        @include('backend.auth.hrm_logo')
                                    </h4>
                                    <hr>
                                    <table width="100%" border="0" cellpadding="5" cellspacing="0" class="black12 table">
                                        <tbody class="tbody">
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Employee') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ @$data['advance']->employee->name }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Designation') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ @$data['advance']->employee->designation->title }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Date') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ main_date_format(@$data['advance']->created_at) }}</td>
                                            </tr>

                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Recover Mode') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    @if (@$data['advance']->recovery_mode)
                                                        {{ _trans('payroll.Installment') }}
                                                    @else
                                                        {{ _trans('payroll.One Time') }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Recover Cycle') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    @if (@$data['advance']->recovery_cycle)
                                                        {{ _trans('payroll.Monthly') }}
                                                    @else
                                                        {{ _trans('payroll.Yearly') }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Recover From') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ @$data['advance']->recover_from }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Recover Amount') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ currency_format(@$data['advance']->installment_amount ?? 0) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Reason') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ @$data['advance']->remarks }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Status') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    <?= '<small class="badge badge-' . @$data['advance']->status->class . '">' . @$data['advance']->status->name . '</small>' ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Payment') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    <?= '<small class="badge badge-' . @$data['advance']->payment->class . '">' . @$data['advance']->payment->name . '</small>' ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table mb-0">
                                        <thead class="card-header-invoice header-color thead">
                                            <tr>
                                                <td colspan="0" class="w-350 text-start"><h5>
                                                        {{ _trans('common.Title') }} </h5></td>
                                                <td colspan="5" class=""><h5>
                                                        {{ _trans('common.Amount') }} </h5></td>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            <tr>
                                                <td colspan="" class="">
                                                    {{ @$data['advance']->advance_type->name }} [
                                                    <?= '<small class="text-danger">' . _trans('payroll.Advance') . '</small>' ?>]
                                                </td>
                                                <td colspan="" class="">
        
                                                        {{ currency_format(number_format(@$data['advance']->amount, 2)) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="card-footer-invoice border-none tbody">
                                            <tr>
                                                <td class="text-end border-bottom-0" colspan="">
                                                    <h5>{{ _trans('common.Total') }}:</h5>
                                                </td>
                                                <td class="border-bottom-0" colspan="">
                                                    {{ currency_format(number_format(@$data['advance']->amount, 2)) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <footer class="text-center mt-4">
                                    <div class="btn-group btn-group-sm d-print-none"> <a href="javascript:window.print()"
                                            class="btn btn-light border text-black-50 shadow-none"><i
                                                class="fa fa-print"></i> Print</a> </div>
                                </footer>

                                {{-- Summary end --}}
                            </div>
                        </div>
                    </div>
                    {{-- End summary overview --}}
                </div>
            </div>
        </div>
    </div>

@endsection
