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
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
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
                      <div class="col-sm-12">
                          <div class="float-sm-right mb-5">
                              <a href="{{ route('employee.create') }}"
                                  class="btn btn-primary">{{ _trans('common.Add Employee') }}</a>
                          </div>
                      </div>
                    <div class="col-lg-12">
                        <table id="table" class="table card-table table-vcenter datatable mb-0 w-100 employee_datatable">
                            <thead>
                                <tr>
                                    <th>{{_trans('common.SL') }}</th>
                                     <th>{{ _trans('common.Name') }}</th>
                                    <th>{{_trans('common.Phone') }}</th>
                                    <th>{{_trans('common.Role') }}</th>
                                     <th>{{ _trans('common.Status') }}</th>
                                    <th>{{ _trans('common.Action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
<input type="text" hidden id="employee_table_url" value="{{ @$data['url']}}">
@endsection
@section('script')
@include('backend.partials.datatable')
@endsection
