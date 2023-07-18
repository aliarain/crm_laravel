@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([
    'title' => @$data['title'],
    route('admin.dashboard') => _trans('common.Dashboard'),
    route('hrm.payroll_salary.index') =>  _trans('common.List'),
    '#' => @$data['title'],
]) !!}
    <div class="table-content table-basic">
        <section class="card">
            <div class="card-body">
                <div class="row">
                    {{-- Start summary overview --}}
                    <div class="col-md-12">
                        <div class="">
                            <div class="px-primary py-primary">
                                {{-- Summary  start --}}
                                <div id="General-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">
                                            @include('backend.auth.backend_logo')
                                        </h4>
                                        <a href="javascript:window.print()" class="btn btn-primary btn-sm">
                                            <i class="fa fa-print"></i>
                                        </a>
                                    </div>
                                    <hr>
                                    <table width="100%" border="0" cellpadding="5" cellspacing="0" class="black12 table">
                                        <tbody class="tbody">
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Employee') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ @$data['show']->user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Designation') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ @$data['show']->user->designation->title }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Date') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ main_date_format(@$data['show']->created_at) }}</td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Reason') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    {{ @$data['show']->remarks }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Status') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    <?= '<small class="badge badge-' . @$data['show']->status->class . '">' . @$data['show']->status->name . '</small>' ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" class="black12">
                                                    {{ _trans('common.Payment') }}</td>
                                                <td align="center" valign="middle" class="black12">:</td>
                                                <td align="left" valign="middle">
                                                    <?= '<small class="badge badge-' . @$data['show']->payment->class . '">' . @$data['show']->payment->name . '</small>' ?>
                                                </td>
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
                                            <tr>
                                                <td colspan="5" class="w-350 text-start">
                                                    {{ @$data['show']->category->name }} [
                                                    <?= '<small class="text-danger">' . _trans('payroll.Expense') . '</small>' ?>]
                                                </td>
                                                <td colspan="5" class="w-350 text-end">
                                                    <span class="">
                                                        {{ currency_format(number_format(@$data['show']->amount, 2)) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        <tfoot class="card-footer-invoice border-none tbody">
                                            <tr>
                                                <td class="text-end border-bottom-0" colspan="8">
                                                    <strong>{{ _trans('common.Total') }}:</strong>
                                                </td>
                                                <td class="text-end border-bottom-0" colspan="8">
                                                    {{ currency_format(number_format(@$data['show']->amount, 2)) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>


                                {{-- Summary end --}}
                            </div>
                        </div>
                    </div>
                    {{-- End summary overview --}}
                </div>
            </div>
        </section>
    </div>

@endsection
