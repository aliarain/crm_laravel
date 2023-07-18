@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}

    <div class=" table-content table-basic ">
        <div class="card">
            <div class="card-body">
                <div class="vertical-tab">
                    <div class="row no-gutters">

                        <div class="col-md-12  tab-menu mb-0">
                            <div class="card ot-card card-with-shadow border-0">
                                <div class="px-primary py-primary">
                                    <div class="form-group">
                                        <input type="hidden" name="id" id="id"
                                            value="{{ $data['language']->id }}">
                                        <label class="form-label form-label">{{ _trans('common.Choose') }}
                                            {{ _trans('common.File') }} <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="file_name" id="file_name"
                                            class="custom-select" onchange="get_translate_file()" required>
                                            <option value="">{{ _trans('common.Select') }}
                                                {{ _trans('settings.Language') }}</option>
                                            @foreach (scandir(base_path('resources/lang/default/')) as $key => $value)
                                                @php
                                                    $explode = explode('.', $value);
                                                @endphp
                                                @if ($key > 1)
                                                    @if ($explode[1] == 'json')
                                                        <option value="{{ $value }}"
                                                            @if ($key == 2) selected @endif>
                                                            {{ Str::title(substr($value, 0, -5)) }}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 pl-md-3 pt-md-0 pt-sm-4 lol">
                            <div class="card ot-card card-with-shadow border-0">
                                <div class="tab-content px-primary">
                                    <div id="General" class="tab-pane active">
                                        <div class="content py-primary">
                                            <div id="General-0">
                                                <div id="translate_form"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="translate_file" class="translate_file" value="{{ route('language.get_translate_file') }}">
@endsection
@section('script')
    @include('backend.partials.table_js')
    <script src="{{ asset('public/backend/js/language.js') }}"></script>
@endsection
