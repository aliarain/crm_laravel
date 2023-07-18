@extends('backend.layouts.app')
@section('title','Permission Lists')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">{{ @$data['role']->name }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item active">{{ _trans('common.Permission Lists')}}</li>
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
          <div class="card">
            <div class="card-header bg-main">
              <div class="d-flex">
                <h3 class="card-title text-white">
                  <div class="form-group clearfix">
                    <div class="icheck-primary d-inline">
                      <input type="checkbox" name="select-all" id="select-all" />
                      <label for="select-all">
                        {{ _trans('common.Select All')}}
                      </label>
                    </div>
                  </div>
                </h3>

                <div class="icheck-primary d-inline">

                </div>
                {{-- <button type="button" class="btn btn-success btn-xs ml-auto" data-toggle="modal"
                  data-target="#add_permission"><i class="fas fa-plus"></i> {{_translate('Add Permission')}}</button> --}}
              </div>
            </div>
            {{ Form::model($data['role'], array('route' => array('roles.update', $data['role']->id), 'method' => 'post')) }}
            <div class="card-body">
              <!-- Minimal style -->
              <div class="row">
                @foreach ($data['permissions'] as $permission)
                <div class="col-lg-3">
                  <div class="form-group clearfix">
                    <div class="icheck-primary d-inline">
                      {{Form::checkbox('permissions[]',  $permission->id, $data['role']->permissions ,['id' => $permission->name]) }}
                      {{Form::label($permission->name, ucfirst($permission->name)) }}
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
              <div class="card-footer">
                <div class="d-flex">
                  <button type="submit" class="btn  btn-primary ml-auto">{{ _trans('common.Submit')}}</button>
                </div>
              </div>
            </div>
            {{ Form::close() }}

          </div>
        </div>
      </div>
    </div>
  </section>
</div>


<div class="modal modal-blur fade" id="add_permission" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_title">{{ _trans('common.Add Permission') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" />
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" /></svg>
        </button>
      </div>
      {{ Form::model($data['role'], array('route' => array('permissions.store', $data['role']->id), 'method' => 'post')) }}
      <div class="modal-body">
        <div class="card-body d-flex flex-column">

          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', '', array('class' => 'form-control required')) }}
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ _trans('common.Cancel')}}</button>
        <button type="submit" class="btn  btn-primary ml-auto">{{ _trans('common.Submit')}}</button>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
@endsection

