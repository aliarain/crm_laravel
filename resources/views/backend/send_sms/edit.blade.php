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
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('notice.update', $data['notice']->id) }}"
                            class="card" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">{{ _trans('common.Subject') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="subject" class="form-control"
                                                placeholder="{{ _trans('common.subject') }}"
                                                value="{{ $data['notice']->subject }}" required>
                                            @if ($errors->has('subject'))
                                                <div class="error">{{ $errors->first('subject') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="date" class="form-label">{{ _trans('common.Date') }} <span class="text-danger">*</span></label>
                                            <input type="date" name="date" id="date" value="{{ $data['notice']->date }}"
                                                class="form-control" placeholder="{{ _trans('common.Date') }}" required>
                                            @if ($errors->has('date'))
                                                <div class="error">{{ $errors->first('date') }}</div>
                                            @endif
                                        </div>
                                    </div>
 

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name" class="form-label">{{ _trans('common.Department') }} <span class="text-danger">*</span></label>
                                            <select name="department_id" class="form-control" required="required">
                                                <option value="" disabled selected>{{ _trans('common.Choose One') }}</option>
                                                @foreach ($data['departments'] as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ $data['notice']->department_id == $department->id ? 'selected' : '' }}>
                                                        {{ $department->title }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('department_id'))
                                                <div class="error">{{ $errors->first('department_id') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">{{ _trans('common.Description') }} <span class="text-danger">*</span></label>
                                            <textarea name="description" id="description" class="form-control" placeholder="{{ _trans('common.Description') }}"
                                                required>{!! $data['notice']->description !!}</textarea>
                                            @if ($errors->has('description'))
                                                <div class="error">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Status<span class="text-danger">*</span></label>
                                            <select name="status_id" class="form-control" required>
                                                <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                                <option value="1"
                                                    {{ $data['notice']->status_id == 1 ? 'selected' : '' }}>
                                                    {{ _trans('common.Active') }}</option>
                                                <option value="2"
                                                    {{ $data['notice']->status_id == 2 ? 'selected' : '' }}>
                                                    {{ _trans('common.In-active') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">{{ _trans('common.Attachment') }}</label>
                                            <div>
                                                <div class="custom-image-upload-wrapper">
                                                    <div class="image-area d-flex">
                                                        <img id="bruh" src="{{ uploaded_asset(null) }}" alt=""
                                                            class="img-fluid mx-auto my-auto">
                                                    </div>
                                                    <div class="input-area"><label id="upload-label" for="upload_file">
                                                            {{ _trans('common.Documents file') }}
                                                        </label>
                                                        <input id="upload_file" name="file" type="file"
                                                            class="form-control d-none upload_file">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col md-6 mt-130">
                                        <div class="card-footer float-right">
                                            <button type="submit"
                                                class="btn btn-primary action-btn">{{ _trans('common.Update') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
