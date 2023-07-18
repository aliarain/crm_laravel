@extends('backend.layouts.app')
@section('title', $data['title'])
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $data['title'] }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ $data['title'] }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-12">
                                        <div class="row">


                                            <div class="px-15px px-lg-25px w-100 ">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                    </div>
                                                </div>

                                                <style>
                                                    .borer_bottom{
                                                        border-bottom: 1px solid #dee2e6
                                                    }
                                                    @media print {
                                                        body * {
                                                            visibility: hidden;
                                                        }

                                                        .visible-header * {
                                                            visibility: visible;
                                                        }
                                                        .visible-body * {
                                                            visibility: visible;
                                                        }



                                                        .aiz-topbar {
                                                            display: none !important;
                                                        }

                                                        .footer-text {
                                                            display: none !important;
                                                        }

                                                        .card {
                                                            width: 100% !important;
                                                        }

                                                        html {
                                                            padding: 0 !important;
                                                            margin: 0 !important;
                                                        }

                                                        body {
                                                            padding: 0 !important;
                                                            margin: 0 !important;
                                                        }

                                                        .aiz-content-wrapper {
                                                            padding: 0 !important;
                                                            margin: 0 !important;

                                                        }
                                                    }

                                                </style>


                                                <div class="ml-3 mr-3">
                                                    <div class="card-heading bord-btm clearfix h-100 ">
                                                        <div class="card-heading invoice_header bord-btm clearfix pad-all visible-header row h-100">
                                                            <div class="col-sm-6 new_invoice pad-no">
                                                                <img 
                                                                    src="/public/frontend/img/icons/header/Adgari-web-logo.png" alt=""
                                                                    class="img img-responsive">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-body invoice_body visible-body">

                                                        <!-- View Rurchase Voucher -->
                                                        <div class="row">
                                                            <div class="col-12">

                                                                <div class="row">
                                                                    <div class="col-sm-6 invoice_description">
                                                                        <p>
                                                                            <strong>{{ _trans('common.Issue Name') }} :</strong> {{ @$data['payment']->user->name }}
                                                                        </p>
                                                                    </div>
                                                                    <div
                                                                        class="col-sm-6 invoice_description invoice_right_description pull-right text-right">
                                                                        <p>
                                                                            <strong>{{ _trans('common.Issue Date :') }} </strong> {{ date_format_for_view($data['payment']->created_at) }} <br>
                                                                        </p>
                                                                        <p>
                                                                            <strong>{{ _trans('common.Invoice No :') }} </strong> {{ $data['payment']->invoice_number }} <br>
                                                                        </p>
                                                                    </div>

                                                                </div>

                                                                <div class="card-heading text-center">
                                                                </div>
                                                                <table
                                                                    class="table table-bordered table-inverse table-hover">
                                                                    <thead>
                                                                        <tr class="borer_bottom">
                                                                            <th class="text-center" width="85%">{{ _trans('common.Month') }}</th>

                                                                            <th>{{ _trans('common.Action') }}</th>>{{ _trans('common.Amount') }}</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-center"> {{  date("F", strtotime($data['payment']->payment_date)) }} </td>
                                                                            <td class="text-right"> {{ $data['payment']->paid_amount }}৳ </td>
                                                                        </tr>
                                                                    <tr>
                                                                        <td class="text-right">{{ _trans('common.Sub Total :') }}</td>
                                                                        <td class="text-right">{{ $data['payment']->paid_amount }}৳</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-right">{{ _trans('common.Total Due:') }}</td>
                                                                        <td class="text-right">{{ $data['payment']->due_amount }}৳</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-right">{{ _trans('common.Total Paid Amount:') }}</td>
                                                                        <td class="text-right">{{ $data['payment']->paid_amount }}৳</td>
                                                                    </tr>
                                                                    </tbody>

                                                                </table>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="text-uppercase"> <strong>{{_trans('common.In Words:')}}</strong>{{ numberTowords($data['payment']->paid_amount) }} {{ _trans('common.Only .') }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- View Rurchase Voucher -->
                                                    <div class="card-footer invoice_footer_area clearfix row">

                                                        <div class="col-sm-12 text-right">

                                                            <button type="button" id="printBtn"
                                                                class="btn btn-rounded invoice_print_btn btn-success"><i
                                                                    class="fa fa-print" aria-hidden="true"></i>
                                                                {{ _trans('common.Print') }}</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
        </section>
    </div>

@endsection

@section('script')
<script src="{{ asset('public/backend/js/_payment.js') }}"></script>
@endsection
