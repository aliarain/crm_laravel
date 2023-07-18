@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    <div class="content-wrapper">

        <div class="container-fluid invoice-container">
            <div id="invoice">
      <!-- Header -->
      <header>
        <div class="row align-items-center">
            <div class="col-sm-7  text-sm-start mb-3 mb-sm-0">
                <img id="logo" src="{{url('/')}}/public/assets/images/logo.png" title="Koice" alt="Koice">
            </div>
            <div class="col-sm-5 text-sm-end">
                <h4 class="text-7 mb-0">{{ __('Invoice') }}</h4>
            </div>
        </div>
        <hr>
    </header>

    <!-- Main Content -->
    <main>
        <div class="row">
            <div class="col-sm-6"><strong>{{ __('Date:') }}</strong> {{ __('05/12/2020') }}</div>
            <div class="col-sm-6 text-sm-end"> <strong>{{ __('Invoice No:') }}</strong> {{ __('16835') }}</div>

        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6 text-sm-end order-sm-1"> <strong>{{ __('Pay To:') }}</strong>
                <address>
                    {{ __('Koice Inc') }}<br>
                    {{ __('2705 N. Enterprise St') }}<br>
                    {{ __('Orange, CA 92865') }}<br>
                    {{ __('contact@koiceinc.com') }}
                </address>
            </div>
            <div class="col-sm-6 order-sm-0"> <strong>{{ __('Invoiced To:') }}</strong>
                <address>
                    {{ __('Smith Rhodes') }}<br>
                    {{ __('15 Hodges Mews, High Wycombe') }}<br>
                    {{ __('HP12 3JL') }}<br>
                    {{ __('United Kingdom') }}
                </address>
            </div>
        </div>

        <div class="card box-shadow-none border-none">
            <div class="card-body p-0">
                <div class=" box-shadow-none">
                    <table class="table mb-0">
                        <thead class="card-header-invoice header-color">
                            <tr>
                                <td class="w-150"><strong> {{ _trans('common.Advance Type') }}</strong></td>
                                <td class="w-150"><strong>{{ _trans('common.Request Amount') }}</strong></td>
                                <td class="text-center w-150"><strong>{{ _trans('common.Approved Amount') }}</strong>
                                </td>
                                <td class="text-center w-150"><strong>{{ _trans('common.Returned Amount') }}</strong>
                                </td>
                                <td class="text-end w-150"><strong>{{ _trans('common.Due Amount') }}</strong></td>
                                <td class="text-end w-150"><strong>{{ _trans('common.Request Month') }}</strong></td>
                                <td class="text-end w-150"><strong>{{ _trans('common.Recover Mode') }}</strong></td>
                                <td class="text-end w-150"><strong>{{ _trans('common.Installment Amount') }}</strong>
                                </td>
                                <td class="text-end w-150"><strong>{{ _trans('common.Status') }}</strong></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ @$data['advance']->advance_type->name }} </td>
                                <td>{{ currency_format(@$data['advance']->request_amount ?? 0) }} </td>
                                <td class=" text-center">{{ currency_format(@$data['advance']->amount ?? 0) }}
                                </td>
                                <td class="text-center">
                                    {{ currency_format(@$data['advance']->paid_amount ?? 0) }} </td>
                                <td class=" text-end">{{ currency_format(@$data['advance']->due_amount ?? 0) }}
                                </td>
                                <td class=" text-end">{{ date('F Y', strtotime(@$data['advance']->date)) }}
                                </td>
                                <td class=" text-end">
                                    @if (@$data['advance']->recovery_mode)
                                        {{ _trans('payroll.Installment') }}
                                    @else
                                        {{ _trans('payroll.One Time') }}
                                    @endif
                                </td>
                                <td class=" text-end">
                                    {{ currency_format(@$data['advance']->installment_amount ?? 0) }} </td>
                                <td class=" text-end">{{ @$data['advance']->remarks }} </td>
                            </tr>

                        </tbody>
                        <tfoot class="card-footer-invoice border-none">
                            <tr>
                                <td class="text-end" colspan="8"><strong>{{ __('Sub Total:') }}</strong></td>
                                <td class="text-end" colspan="8">{{ __('$2150.00') }}</td>
                            </tr>
                            <tr>
                                <td class="text-end" colspan="8"><strong>{{ __('Tax:') }}</strong></td>
                                <td class="text-end" colspan="8">{{ __('$215.00') }}</td>
                            </tr>
                            <tr>
                                <td class="text-end border-bottom-0" colspan="8"><strong>{{ __('Total:') }}</strong></td>
                                <td class="text-end border-bottom-0" colspan="8">{{ __('$2365.00') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>
            </div>
            <!-- Footer -->
            <footer class="text-center mt-4">
                <p class="text-1"><strong>{{ __('NOTE :') }}</strong> {{ __('This is computer generated receipt and does not require physical signature.') }}</p>
                <div class="btn-group btn-group-sm d-print-none"> <a href="javascript:window.print()"
                        class="btn btn-light border text-black-50 shadow-none"><i class="fa fa-print"></i> {{ __('Print') }}</a> <a
                        href="javascript:void(0)" class="btn btn-light border text-black-50 shadow-none" id="downloadPDF"><i class="fa fa-download"></i>
                        {{ __('Download') }}</a> </div>
            </footer>
        </div>
    </div>
    @endsection
