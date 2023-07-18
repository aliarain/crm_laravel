@extends('backend.layouts.app')
@section('title', $data['title'])
@section('content')


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ $data['title'] }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard')
                                }}</a></li>
                        <li class="breadcrumb-item active">{{ $data['title'] }}</li>
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

                        <table class="table card-table table-vcenter  w-100 mt-2 table-bordered" id="table">

                            <thead>
                                <tr>
                                    <th> @lang('SL')</th>
                                    <th> @lang('Name')</th>
                                    <th> @lang('Translations')</th>
                                </tr>
                            </thead>
                            <tbody >

                                @foreach ($data['translations'] as $key => $item)
                                 <tr>
                                     <td>{{$item->id}}</td>
                                     <td class="default">{{ str_replace('_',' ', $item->default) }}</td>
                                    <td>
                                        <form action="{{ route('languages.value_store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="default" value="{{ $item->default }}">
                                            <div class="form-group row">
                                                <textarea class="form-control en" name="en" rows="10"   >{{ $item->en }}</textarea>
                                                <textarea class="form-control bn" name="bn" rows="10" >{{ $item->bn }}</textarea>
                                            </div>
                                            <button class="btn btn-primary btn-sm col-sm-2" type="submit">{{ _trans('common.Update') }}</button>
                                        </form>
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

@section('script')
<script src="{{ url('public/ckeditor/ckeditor.js') }}"></script>
<script src="{{ url('public/ckeditor/config.js') }}"></script>
<script src="{{ url('public/ckeditor/style.js') }}"></script>
<script src="{{ url('public/ckeditor/build-config.js') }}"></script>
<script src="{{ asset('public/backend/js/language_ckeditor.js') }}"></script>
@endsection
