@extends('backend.layouts.app')
@section('title','Income Details')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">{{ _trans('common.Income Details') }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('income.index') }}">{{ _trans('common.Income') }}</a></li>
            <li class="breadcrumb-item active">{{ _trans('common.Income Details') }}</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ @$data['show']->title }}</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-12 col-lg-12 order-2 order-md-1">
            <div class="row">

              <div class="col-sm-3">
                <div class="info-box bg-light">
                  <div class="info-box-content">
                    <span class="info-box-text text-center text-muted">{{ _trans('common.Income Head') }}</span>
                    <span
                      class="info-box-number text-center text-muted mb-0">{{ @$data['show']->income_head->name }}</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="info-box bg-light">
                  <div class="info-box-content">
                    <span class="info-box-text text-center text-muted">{{ _trans('common.Amount') }}</span>
                    <span
                      class="info-box-number text-center text-muted mb-0">{{ format_price(@$data['show']->amount) }}</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="info-box bg-light">
                  <div class="info-box-content">
                    <span class="info-box-text text-center text-muted">{{ _trans('common.Date') }}</span>
                    <span
                      class="info-box-number text-center text-muted mb-0">{{ main_date_format(@$data['show']->created_at) }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="info-box bg-light">
                  <div class="info-box-content">
                    <span class="info-box-text text-center text-muted">{{ _trans('common.Status') }}</span>
                    <span class="info-box-number text-center text-muted mb-0">
                      @if(@$data['show']->status == 1)
                      {{_trans('common.Active')}}
                      @else
                      {{_trans('common.Disable')}}
                      @endif </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-12 order-2 order-md-1 border-right">
            <div class="row">
              <div class="col-12">
                <h4>{{ _trans('common.Note') }}</h4>
                <div class="post">
                  <p>
                    {!! @$data['show']->note !!}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-5 mb-3">
        <a href="{{ route('income.index') }}" class="btn btn-sm btn-default">{{_trans('common.Back')}}</a>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

  </section>
</div>

@endsection
@push('js')

@endpush
