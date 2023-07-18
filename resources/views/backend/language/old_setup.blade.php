@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <div class="container-fluid  border-radius-5 p-imp-30">
            <div class="main-panel">
                <div class="">
                    <div class="vertical-tab">
                        <div class="row no-gutters">
                            
                            <div class="col-md-3 pr-md-3 tab-menu mb-3">
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

                            <div class="col-md-9 pl-md-3 pt-md-0 pt-sm-4">
                                <div class="card ot-card card-with-shadow border-0">
                                    <div class="tab-content px-primary">
                                        <div id="General" class="tab-pane active">
                                            <div class="d-flex justify-content-between">
                                                <h5
                                                    class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                                    {{ @$data['title'] }}</h5>
                                                <div class="d-flex align-items-center mb-0">
                                                </div>
                                            </div>
                                            <hr>
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
    </div>
    </div>
    <input type="hidden" name="translate_file" class="translate_file" value="{{ route('language.get_translate_file') }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
    <script src="{{ asset('public/backend/js/language.js') }}"></script>
@endsection
