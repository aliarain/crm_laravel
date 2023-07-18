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
           <div class="card">
                <form class="form-horizontal" action="{{ @$data['url']  }}" method="POST"
                enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="expense_date">{{ _trans('common.Date')}}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="expense_date" id="expense_date"
                                    placeholder="{{ _trans('common.Date') }}" value="{{ @$data['edit']->date }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label required">{{ _trans('common.Select a supplier')}}</label>
                            <div class="col-sm-8">
                                <select name="supplier_id" id="supplier_id" class="form-control select2">
                                    @foreach ($data['suppliers'] as $key=>$supplier )
                                    <option value="{{$supplier->user->id}}" {{ @$data['edit']->user_id == $supplier->user->id ? ' selected="selected"' : ''}} >{{$supplier->contact_person_name}}
                                        [{{$supplier->name}}]
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="type">{{ _trans('common.Payment Type')}}</label>
                            <div class="col-sm-8">
                                <select name="payment_type_id" class="form-control" required>
                                    @foreach (\DB::table('payment_types')->where('status_id',1)->get() as $item)
                                        <option  {{ @$data['edit']->payment_type_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                     @endforeach
                                </select>
                                @if ($errors->has('payment_type_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong class="text-danger">{{ $errors->first('payment_type_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 control-label required" for="type">{{ _trans('common.Type')}}</label>
                            <div class="col-sm-8">
                                <select name="type" id="income_expense_type" class="form-control" required>
                                    <option value="">---- {{ _trans('common.Select Type')}} ----</option>
                                    <option {{ @$data['edit']->type == 1 ? 'selected' : '' }} value="1">{{ _trans('common.Income')}}</option>
                                    <option {{ @$data['edit']->type == 2 ? 'selected' : '' }} value="2">{{ _trans('common.Expense')}}</option>
                                </select>
                                @if ($errors->has('type'))
                                <span class="invalid-feedback" role="alert">
                                    <strong class="text-danger">{{ $errors->first('type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group" id="category">
                            <label class="col-sm-2 control-label required">{{ _trans('common.Category')}}</label>
                            <div class="col-sm-8">
                                <select class="form-control demo-select2-placeholder category-select" name="category_id"
                                    id="category_id" required>
                                    <option value="">---- {{ _trans('common.Select Category')}} ----</option>
                                    @if (@$data['edit']->category_id)
                                        <option value="{{$data['edit']->category_id}}" selected>{{$data['edit']->category->name}}</option>
                                    @endif
                                </select>
                                @if ($errors->has('category_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong class="text-danger">{{ $errors->first('category_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="amount">{{ _trans('common.Amount')}}</label>
                            <div class="col-sm-8">
                                <input type="number" placeholder="{{ _trans('common.Amount')}}" id="amount" name="amount"
                                    class="form-control" value="{{ @$data['edit']->amount }}" required>
                                @if ($errors->has('amount'))
                                <span class="invalid-feedback" role="alert">
                                    <strong class="text-danger">{{ $errors->first('amount') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-12 card-footer text-right sa-flex">
                            <a href="{{ URL::previous() }}" class="btn btn-danger" type="cancel">{{ _trans('common.Cancel')}}</a>
                            <button class="btn btn-success" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                                {{ _trans('common.Save')}}
                            </button>
                        </div>
                    </div>
                </form>
           </div>
        </div>
    </section>
</div>
@endsection