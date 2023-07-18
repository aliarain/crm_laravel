
@extends('backend.layouts.app')
@section('title','Profile')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ _trans('common.Profile') }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ _trans('common.Profile') }}</li>
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content bg-white">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <table class="table table-responsive table-bordered w-100">
                <thead>
                    <th>{{ _trans('common.SL') }}</th>
                    {{ _trans('common.Name') }}
                    <th>{{ _trans('common.Phone') }}</th>
                    <th>{{ _trans('common.Email') }}</th>
                    <th>{{ _trans('common.Join Date') }}</th>
                </thead>
                <tbody>
                    @foreach ($customers as $key=>$customer)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td><a href="{{ url('dashboard/customer/view-profile', $customer->id) }}">{{ $customer->name }}</a></td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
</div>
@endsection
@push('js')
@endpush
