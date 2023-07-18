@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ @$data['title'] }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ @$data['title'] }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form class="form-horizontal" action="" method="post">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="#">{{ _trans('placeholder.Select Type') }}</label>
                                        <select name="bus" id="driver_id" class="form-control">
                                            <option value="1">{{ _trans('payment.Driver') }}</option>
                                            <option value="2">{{ _trans('payment.Client') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="#">{{ _trans('payment.Payment Date') }}</label>
                                        <input type="date" id="start" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-4">
                                    <div class="form-group mt-2">
                                        <button type="submit"
                                            class="btn btn-primary account_table_form">{{ _trans('common.Submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
@section('script')
@endsection
