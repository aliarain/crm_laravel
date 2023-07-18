@extends('backend.layouts.app')
@section('title','Due Payment List')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">{{ _trans('common.Due Payment List') }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
            <li class="breadcrumb-item active">{{ _trans('common.Due Payment List') }}</li>
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
          <table id="table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>{{ _trans('common.SL')}}</th>
                <th>{{ _trans('common.Name')}}</th>
                <th>{{ _trans('common.Email') }}</th>
                <th>{{ _trans('common.Due Amount')}}</th>
                <th>{{ _trans('common.Created Date')}}</th>
                <th>{{ _trans('common.Status')}}</th>
                <th>{{ _trans('common.Action')}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data['payment'] as $item)
              <tr>
                <td>{{__(@$item->id) }}</td>
                <td>{{__(@$item->user->name) }}</td>
                <td>{{__(@$item->user->email) }}</td>
                <td>{{__(single_price(@$item->due)) }}</td>
                <td>{{ main_date_format(@$item->created_at) }}</td>
                <td>
                  @if($item->status == 1)
                  {{ _trans('common.Partials Paid')}}
                  @endif
                </td>
                <td>
                  <div class="flex-nowrap">
                    <div class="dropdown">
                      <button class="btn btn-white dropdown-toggle align-text-top" data-boundary="viewport"
                        data-toggle="dropdown">{{ _trans('common.Actions') }}</button>
                      <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{route('due.payment.show', $item->id)}}">
                          {{ _trans('common.View') }}
                        </a>
                        <button class="dropdown-item"
                          onclick="__globalDelete('{{$item->id}}',`dashboard/payment-delete/`);">
                          {{ _trans('common.Delete') }}
                        </button>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach


            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
@push('js')
@include('backend.partials.datatable')
@endpush

@section('script')
<script src="{{ asset('public/backend/js/_payment.js') }}"></script>
@endsection
