@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

<div class="content-wrapper has-table-with-td">
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="#">{{  _trans('common.Select Employee')}}</label>
                                <select name="bus" id="driver_id" class="form-control select2">
                                    <option value="">{{  _trans('common.Select')}} {{@$data['filed'] }}</option>
                                    @foreach (@$data['drivers'] as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="#">{{ _trans('common.Start Date') }}</label>
                                <input type="date" id="start" name="start_date" class="form-control" value="{{ date('Y-m-d') }}"> 
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="#">{{ _trans('common.End Date') }}</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-lg-3 filter-btn-margin">
                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-primary account_table_form">{{ _trans('common.Submit')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="select-box-size">
                        <table class="table card-table table-vcenter datatable mb-0 w-100 account_payment_list ">
                            <thead>
                                <tr>
                                    <th class="d-none">{{ _trans('commo.SL') }}</th>
                                    <th>{{ _trans('common.Deal Code')}}</th>
                                    <th>{{@$data['filed']}} {{ _trans('common.Name')}}</th>
                                    <th>{{ _trans('common.Date')}}</th>
                                    <th>{{ _trans('common.Amount')}}</th>
                                    <th>{{ _trans('common.Status')}}</th>
                                    <th>{{ _trans('common.Action')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
<input type="text" hidden id="data_url" value="{{ @$data['url']}}">
@endsection
@section('script')
@include('backend.partials.datatable')
@endsection
