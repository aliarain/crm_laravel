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
                    <div class="col-lg-12">
                        <form action="{{ $data['url'] }}" enctype="multipart/form-data" method="post" id="attendanceForm">
                            @csrf
                            <div class="row">

                                <div class="col-lg-8">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">{{ _trans('common.Name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control ot-form-control ot_input"
                                            required value="{{ @$data['edit'] ? $data['edit']->name : old('name') }}">
                                        @if ($errors->has('name'))
                                            <div class="error">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Type') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="type" class="form-control select2" required>
                                            <option {{ @$data['edit']->type == 1 ? 'selected' : '' }} value="1">
                                                {{ _trans('common.Header Menu') }}</option>
                                            <option {{ @$data['edit']->type == 2 ? 'selected' : '' }} value="2">
                                                {{ _trans('common.Footer Menu') }}</option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <div class="error">{{ $errors->first('status') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.URL Type') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="type" class="form-control select2" required
                                            onchange="MenuType(this.value)">
                                            <option {{ @$data['edit']->url ? 'selected' : '' }} value="1">
                                                {{ _trans('common.URL') }}</option>
                                            <option {{ @$data['edit']->all_content_id ? 'selected' : '' }} value="2">
                                                {{ _trans('common.Page') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4" id="menu_url_link">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">{{ _trans('common. URL Link') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="url" class="form-control ot-form-control ot_input"
                                            required value="{{ @$data['edit'] ? @$data['edit']->url : old('url') }}">
                                        @if ($errors->has('url'))
                                            <div class="error">{{ $errors->first('url') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4 d-none" id="menu_page_id">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Page') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="page_id" class="form-control select2" required>
                                            @foreach (@$data['contents'] as $item)
                                                <option {{ @$data['edit']->all_content_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">{{ @$item->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('page_id'))
                                            <div class="error">{{ $errors->first('page_id') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ _trans('common.Status') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="status" class="form-control select2" required>
                                            <option {{ @$data['edit']->type == 1 ? 'selected' : '' }} value="1">
                                                {{ _trans('payroll.Active') }}</option>
                                            <option {{ @$data['edit']->type == 4 ? 'selected' : '' }} value="4">
                                                {{ _trans('payroll.Inactive') }}</option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="error">{{ $errors->first('status') }}</div>
                                        @endif
                                    </div>
                                </div>


                                @if (@$data['url'])
                                    <div class="col-md-12">
                                        <div class="text-end mt-12">
                                            <button class="crm_theme_btn ">{{ _trans('common.Save') }}</button>
                                        </div>
                                    </div>
                                @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
