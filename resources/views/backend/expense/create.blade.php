
@extends('backend.layouts.app')
@section('title','Expense')
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">@if(@$data['edit']) {{ _trans('common.Edit') }} @else {{ _trans('common.Create') }} @endif {{ _trans('common.Expense') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
              <li class="breadcrumb-item active">@if(@$data['edit']) {{ _trans('common.Edit') }} @else {{ _trans('common.Create') }} @endif {{ _trans('common.Expense') }}</li>
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
              <div class="card card-primary">
                <!-- form start -->
                @if (@$data['edit'])
                    {{Form::open (['route' => ['expense.update', @$data['edit']->id] ,'class' => 'card', 'id' => 'portfolio-form', 'files' => 'true', 'method' => 'PUT'] )}}
                @else
                    {!! Form::open(['route' => 'expense.store', 'enctype' => 'multipart/form-data','method' => 'post', 'languages' => true, 'id' => 'portfolio-form']) !!}
                @endif
                  <div class="card-body">
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              {{Form::label('expense_head','Expense Head', ['class' => 'form-label required'])}}
                              {{Form::select('expense_head', $data['head'], @$data['edit'] ? @$data['edit']->expense_head_id: null,  ['class' => 'form-control select'])}}
                                @error('expense_head')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                                {{Form::label('status','Status', ['class' => 'form-label required'])}}
                                {{Form::select('status', [1 => 'Active',0 => 'Disable'], @$data['edit'] ? @$data['edit']->status: null,  ['class' => 'form-control select2'])}}

                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                                {{Form::label('amount','Amount', ['class' => 'form-label required'])}}
                                {{Form::number('amount',@$data['edit'] ? @$data['edit']->amount :0.00, ['class' => 'form-control'])}}

                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                                {{Form::label('date','Date', ['class' => 'form-label required'])}}
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                  <input type="text" name="date" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                                  <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                </div>

                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>
                          </div>
                          <div class="col-lg-12">
                            {{Form::label('note','Note', ['class' => 'form-label required'])}}
                            {{Form::textarea('note',@$data['edit'] ? @$data['edit']->note :null, ['id'=>'summernote','class' => 'form-control'])}}
                          </div>
                      </div>


                  </div>
                  <div class="card-footer">
                    <a href="{{ url('dashboard/expense') }}" class="btn btn-default float-left">{{ _trans('common.Back') }}</a>
                    <button type="submit" class="btn btn-primary float-right">{{ _trans('common.Submit') }}</button>
                  </div>
                  {{Form::close()}}
              </div>

            </div>
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
  </div>
  @php
      $date = @$data['edit']->date ?  @$data['edit']->date : date('Y-m-d');
  @endphp
@endsection
@push('js')
@include('backend.partials.datatable')
@endpush

@section('scripts')
<script src="{{ asset('public/backend/js/_payment.js') }}"></script>
{{-- <script src="{{ asset('public/backend/js/superfluous_common.js') }}"></script> --}}
@endsection
