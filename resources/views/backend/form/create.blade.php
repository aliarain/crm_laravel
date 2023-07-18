@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
    <div class="table-content table-basic">
        <div class="card">
            <div class="card-body">
                <form action="{{ $data['url'] }}" class="row p-0" method="post" enctype="multipart/form-data">
                    @csrf
                    {{-- dynamic attributes --}}
                    @if (@$data['attributes'])
                        @foreach (@$data['attributes'] as $key => $attribute)
                            <div class="{{ @$attribute['col'] }}">
                                <label class="col-form-label">
                                    @if (@$attribute['required'])
                                        <span class="text-danger">*</span>
                                    @endif
                                    {{ @$attribute['label'] }}
                                </label>
                                @if (@$attribute['type'] == 'text')
                                    <input type="text" class="{{ @$attribute['class'] }}" name="{{ @$key }}"
                                        id="{{ @$key }}" placeholder="{{ @$attribute['label'] }}"
                                        @if (@$attribute['required']) required @endif autocomplete="off"
                                        value="{{ @$attribute['value'] ?? old(@$key)  }}">

                                @elseif (@$attribute['type'] == 'select')
                                    <select name="{{ @$key }}" id="{{ @$attribute['id'] }}"
                                        class="{{ @$attribute['class'] }}" aria-label="Default select example"
                                        @if (@$attribute['required']) required @endif {{ @$attribute['multiple'] }}>
                                        @foreach (@$attribute['options'] as $option)
                                            <option value="{{ $option['value'] }}"
                                                {{ @$option['active'] ? 'selected' : '' }}>
                                                <?= $option['text'] ?>
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif (@$attribute['type'] == 'number')
                                    <input type="number" class="{{ @$attribute['class'] }}" name="{{ @$key }}"
                                        id="{{ @$key }}" @if (@$attribute['required']) required @endif
                                        value="{{ @$attribute['value'] ?? old(@$key) }}" autocomplete="off">
                                @elseif (@$attribute['type'] == 'date')
                                    <input type="text" class="{{ @$attribute['class'] }}" name="{{ @$key }}"
                                        id="{{ @$attribute['id'] }}" @if (@$attribute['required']) required @endif
                                        value="{{ @$attribute['value'] ?? old(@$key) }}" autocomplete="off">



                                @elseif (@$attribute['type'] == 'file')
                                    
                                
                                    {{-- <input type="file" class="{{ @$attribute['class'] }}" name="{{ @$key }}"
                                        id="{{ @$attribute['id'] }}" @if (@$attribute['required']) required @endif
                                        value="{{ @$attribute['value'] ?? old(@$key) }}" autocomplete="off"> --}}
                                        
                                        <div class="ot_fileUploader left-side mb-3">
                                            <input class="form-control" type="text" placeholder="{{ @$attribute['placeholder'] }}" name="" readonly="" id="placeholder">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse') }}</label>
                                                <input type="file" class="d-none form-control" name="{{ @$key }}" id="fileBrouse">
                                            </button>
                                        </div>



                                @elseif (@$attribute['type'] == 'checkbox')
                                    <div class="form-check">
                                        <input type="checkbox" class="{{ @$attribute['class'] }}"
                                            name="{{ @$key }}" id="{{ @$key }}" value="1">
                                        <label class="form-check-label">{{ @$attribute['label'] }}</label>
                                    </div>
                                @elseif (@$attribute['type'] == 'textarea')
                                    <textarea class="{{ @$attribute['class'] }}" name="{{ @$key }}" rows="{{ @$attribute['row'] ?? 1 }}"
                                        placeholder="{{ @$attribute['label'] }}" @if (@$attribute['required']) required @endif>{{  @$attribute['value'] ?? old($key) }}</textarea>
                                @endif

                                @error($key)
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach

                    @endif
                    <div class="form-group d-flex justify-content-end">
                        <button type="submit" class="crm_theme_btn pull-right">{{ @$data['button'] }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
@endsection
