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
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter  w-100 mt-2 table-bordered" id="table">

                            <thead>
                                <tr>
                                    <th> @lang('SL')</th>
                                    <th> @lang('Name')</th>
                                    <th> @lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody >

                                @foreach ($data['translations'] as $key => $item)
                                 <tr>
                                     <td>{{$item->id}}</td>
                                    <td>
                                       <b>{{ __('DEFAULT:') }} </b> <br> {{ str_replace('_',' ', $item->default) }} <hr>
                                       <b>{{ __('ENGLISH:') }} </b> <br> {!! $item->en !!} <hr>
                                       <b>{{ __('BANGLA:') }} </b> <br> {!! $item->bn !!}

                                    </td>
                                    <td>
                                        <a href="{{ route('langEdit', $item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach


                            </tbody>

                        </table>
                        {{-- {{ $data['translations']->links() }} --}}




                    </div>


                </div>
            </div>
        </div>
    </section>
</div>
@endsection
