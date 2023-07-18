@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ @$data['title'] }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">{{ _translate('Dashboard') }}</a></li>
                            @if (hasPermission('salary_list'))
                                <li class="breadcrumb-item"><a
                                        href="{{ route('hrm.payroll_salary.index') }}">{{ _trans('common.List') }}</a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active">{{ @$data['title'] }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                
                <div class="row align-items-center">

                    {{-- Start summary overview --}}
                    <div class="col-md-12" id="invoicePd">
                        <div class="invoice p-3 mb-3">

                            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap parent-select2-width">
                                <div class=" invoice-col">
                                    From
                                    <address>
                                        <strong>Admin, Inc.</strong><br>
                                        795 Folsom Ave, Suite 600<br>
                                        San Francisco, CA 94107<br>
                                        Phone: (804) 123-5432<br>
                                        Email: info@almasaeedstudio.com
                                    </address>
                                </div>
                                <div class=" invoice-col mr-1">
                                    To
                                    <address>
                                         <b>Invoice #007612</b><br>
                                        <strong>John Doe</strong><br>
                                        795 Folsom Ave, Suite 600<br>
                                        San Francisco, CA 94107<br>
                                        Phone: (555) 539-1037<br>
                                        Email: john.doe@example.com
                                    </address>
                                </div>
                        </div>



                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-striped w-100 table-border">
                                        <thead class="w-100">
                                            <tr>
                                                <th width="20%">{{ _trans('common.ID') }}</th>
                                                <th width="50%">{{ _trans('account.Payment Method') }}</th>
                                                <th width="30%" class="text-right border-bottom-0">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="w-100">
                                            @if (@$data['project']->payments)
                                                @foreach ($data['project']->payments as $key => $payment)
                                                    <tr>
                                                        <td width="20%">{{ $key +1  }}</td>
                                                        <td width="50%">{{ $payment->payment_method->name }}</td>
                                                        <td width="30%" class="text-right">
                                                            {{ currency_format(number_format(@$payment->amount, 2)) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                
                                            @endif
                                        </tbody>
                                        <tfoot class="card-footer-invoice w-100">
                                            <tr>
                                                <td class="text-end border-bottom-0" colspan="3">
                                                    <strong>{{ _trans('common.Total') }}:</strong>
                                                </td>
                                                <td colspan="1">
                                                    {{ currency_format(number_format(@$data['project']->amount, 2)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-end border-bottom-0" colspan="3">
                                                    <strong>{{ _trans('common.Total Paid') }}:</strong>
                                                </td>
                                                <td colspan="2">
                                                    {{ currency_format(number_format(@$data['project']->paid, 2)) }}
                                                </td>
                                            </tr>
                                            <tr class="text-danger">
                                                <td class="text-end border-bottom-0" colspan="3">
                                                    <strong>{{ _trans('common.Total Due') }}:</strong>
                                                </td>
                                                <td colspan="2">
                                                    {{ currency_format(number_format($data['project']->due, 2)) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- End summary overview --}}
                </div>
            </div>
        </section>
    </div>

@endsection
@section('script')
    <script>
        printDiv = () => {
            var printContents = document.getElementById('invoicePdf').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
        $(document).ready(function() {
            printDiv()
        });
    </script>
@endsection
