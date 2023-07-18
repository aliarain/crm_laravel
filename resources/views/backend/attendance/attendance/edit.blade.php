@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    <div class="content-wrapper">
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
                                    href="{{ route('admin.dashboard') }}">{{ _trans('attendance.Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ @$data['title'] }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="float-sm-right mb-3">
                            <a href="{{ route('attendance.index') }}"
                                class="btn btn-primary">{{ _trans('common.Attendance List') }}</a>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        <!-- Main content -->


        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="offset-lg-2 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ _trans('attendance.Add New Attendance') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('attendance.update', $data['attendance_data']->id) }}"
                                    enctype="multipart/form-data" method="post" id="attendanceForm">
                                    @csrf
                                    @method('PATCH')
                                    @php
                                        $now = \Carbon\Carbon::now();
                                    @endphp
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="#" class="form-label">{{ _trans('attendance.Check in location') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="check_in_location" class="form-control"
                                                    placeholder="Check in location"
                                                    value="{{ $data['attendance_data']->check_in_location }}" required>
                                                @if ($errors->has('check_in_location'))
                                                    <div class="error">{{ $errors->first('check_in_location') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="#">{{ _trans('attendance.Select Employee') }} <span
                                                        class="text-danger">*</span></label>
                                                <select name="user_id" class="form-control" required="required">
                                                    <option value="" disabled selected>
                                                        {{ _trans('attendance.Choose One') }}</option>
                                                    @foreach ($data['users'] as $user)
                                                        <option
                                                            {{ $data['attendance_data']->user_id == $user->id ? 'selected' : '' }}
                                                            value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('user_id'))
                                                    <div class="error">{{ $errors->first('user_id') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="#">{{ _trans('attendance.Date') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="date"
                                                    value="{{ $data['attendance_data']->date }}" required>
                                                @if ($errors->has('date'))
                                                    <div class="error">{{ $errors->first('date') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="#">{{ _trans('attendance.Check In') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="time" class="form-control" name="check_in"
                                                    value="{{ date('H:i', strtotime($data['attendance_data']->check_in)) }}"
                                                    required>
                                                @if ($errors->has('check_in'))
                                                    <div class="error">{{ $errors->first('check_in') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="#">{{ _trans('attendance.Check Out') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="time" class="form-control" name="check_out"
                                                    value="{{ date('H:i', strtotime($data['attendance_data']->check_out)) }}"
                                                    required>
                                                @if ($errors->has('check_out'))
                                                    <div class="error">{{ $errors->first('check_out') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row  mt-20">
                                        <div class="col-md-12">
                                            <div class="text-right">
                                                <button class="btn btn-primary">{{ _trans('attendance.Update') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@section('script')
    @include('backend.partials.datatable')
@endsection
