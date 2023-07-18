
@extends('backend.layouts.app')
@section('title','Payment')
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"> {{ _trans('common.Create Payment') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
              <li class="breadcrumb-item active"> {{ _trans('common.Create Payment') }}</li>
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
                  {!! Form::open(['route' => ['payment.store'], 'enctype' => 'multipart/form-data','method' => 'post', 'languages' => true, 'id' => 'payment-form']) !!}
                  <div class="card-body">
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ _trans('common.User') }}</label>
                                <select class="form-control w-100" name="payment" data-dropdown-css-class="select2-primary">
                                  @foreach ($data['payment'] as $item)
                                  <option {{ old('payment') == $item->id? 'selected':null }} value="{{ $item->id }}">{{ @$item->user->name }}</option>
                                 @endforeach
                                </select>
                                @error('country')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                          </div>
                          @php
                              $data_am = \App\Payment::where('status',0)->orderBy('id','desc')->first();
                              $due = floatval($data_am->user->balance) - floatval($data_am->withdraw->amount);
                          @endphp
                          <div class="form-group">
                            {{Form::label('amount','Amount', ['class' => 'form-label required'])}}
                            {{Form::number('amount', @$data_am->withdraw->amount, ['max' =>  @$data['user']->balance, 'class' => 'form-control'])}}
                              @error('amount')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>

                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                                {{Form::label('due','Created Date', ['class' => 'form-label required'])}}
                                <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                  <input type="text" name="date" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                                  <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                  </div>
                                </div>
                                  @error('due')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>

                            <div class="form-group">
                                {{Form::label('due','Due', ['class' => 'form-label required'])}}
                                {{Form::number('due',@$due , ['max' =>  @$data['user']->balance, 'class' => 'form-control'])}}
                                  @error('due')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                          </div>
                      </div>


                  </div>
                  <div class="card-footer">
                    <a href="{{ url('dashboard/payment') }}" class="btn btn-default float-left">{{ _trans('common.Back') }}</a>
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

@endsection
@push('js')
@endpush

@section('script')
<script src="{{ asset('public/backend/js/_payment.js') }}"></script>
@endsection
