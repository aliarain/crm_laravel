@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
    <div class="table-content table-basic ">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="POST" action="{{ route('notice.update', $data['notice']->id) }}" class=""
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="name">{{ _trans('common.Subject') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="subject" class="form-control ot-form-control ot_input"
                                            placeholder="{{ _trans('common.subject') }}" value="{{ $data['notice']->subject }}"
                                            required>
                                        @if ($errors->has('subject'))
                                            <div class="error">{{ $errors->first('subject') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="date" class="form-label">{{ _trans('common.Date') }}</label>
                                        <input type="date" name="date" id="date"
                                            value="{{ $data['notice']->date }}"
                                            class="form-control ot-form-control ot_input"
                                            placeholder="{{ _trans('common.Date') }}" required>
                                        @if ($errors->has('date'))
                                            <div class="error">{{ $errors->first('date') }}</div>
                                        @endif
                                    </div>
                                </div>

                               

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">{{ _trans('common.Department') }}</label>
                                        <select name="department_id[]" class="form-control select2" multiple="multiple"
                                            required="required">
                                            <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                            @foreach ($data['departments'] as $key => $department)
                                                <option value="{{ $department->id }}"
                                                    {{ in_array($department->id, $data['notice']->departmentFor()) ? 'selected' : '' }}>
                                                    {{ $department->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('department_id'))
                                            <div class="error">{{ $errors->first('department_id') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label for="description"
                                            class="form-label">{{ _trans('common.Description') }}</label>
                                        <textarea name="description" id="description" class="form-control ot_input mt-0"
                                            placeholder="{{ _trans('common.Description') }}" required>{!! $data['notice']->description !!}</textarea>
                                        @if ($errors->has('description'))
                                            <div class="error">{{ $errors->first('description') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="name">Status<span class="text-danger">*</span></label>
                                        <select name="status_id" class="form-control select2" required>
                                            <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                            <option value="1" {{ $data['notice']->status_id == 1 ? 'selected' : '' }}>
                                                {{ _trans('common.Active') }}</option>
                                            <option value="2" {{ $data['notice']->status_id == 2 ? 'selected' : '' }}>
                                                {{ _trans('common.In-active') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="status">{{ _trans('common.Attachment') }}</label>
                                        {{-- <input id="upload_file" name="file" type="file"
                                                            class="form-control ot-form-control ot_input upload_file"> --}}

                                        <div class="ot_fileUploader left-side mb-3">
                                            <input class="form-control" type="text" placeholder="{{ _trans('common.Attachment') }}"
                                                name="" readonly="" id="placeholder">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse') }}</label>
                                                <input type="file" class="d-none form-control" name="file"
                                                    id="fileBrouse">
                                            </button>
                                        </div>
                                        @if ($errors->has('file'))
                                            <span class="text-danger">{{ $errors->first('file') }}</span>
                                        @endif

 

                                    </div>
                                </div>
                                <div class="col-md-12 mt-130">
                                    <div class=" float-right d-flex justify-content-end">
                                        <button type="submit"
                                            class="crm_theme_btn action-btn">{{ _trans('common.Update') }}</button>
                                    </div>
                                </div>
                            </div>

                            <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
