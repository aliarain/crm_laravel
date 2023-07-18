@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([ 'title' => @$data['title'], route('admin.dashboard') => _trans('common.Dashboard'), '#' => @$data['title']]) !!}
    <div class="table-basic table-content">
        <div class="card">
            <div class="card-body">
                <div class="">
                    <div class="">
                        <div class="px-primary py-primary">
                            <div id="General-0">
                                <fieldset class="form-group mb-5">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @foreach ($data['settings'] as $key => $setting)
                                                <div class="row py-3 align-items-center @if ($loop->odd) btn_odd_row @else btn_even_row @endif">
                                                    <div class="col-12 col-md-3 m-0  my-2">
                                                        <label>{{ $setting->name }}</label>
                                                        <form action="{{ route('appSettingsIcon') }}" method="POST" enctype="multipart/form-data" id="icon-settings{{$setting->id}}">
                                                            @csrf
                                                            <div class="icon-upload">
                                                                <div class="sizing">
                                                                    <img class="p-2" src="{{ my_asset($setting->icon) }}" alt="">
                                                                    <label for="icon{{ $setting->id }}" class="download-icon">
                                                                        <i class="fa fa-upload" aria-hidden="true"></i>
                                                                    </label>
                                                                    <input type="hidden" name="id" value="{{ $setting->id }}" >
                                                                    <input class="opacity-0 invisible" type="file" accept=".png, .jpg, .jpeg, .svg" id='icon{{ $setting->id }}' name="icon"  onchange="submit()">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-9 col-md-6 mt-0">
                                                        <form action="{{ route('appSettingsTitle') }}" method="POST" enctype="multipart/form-data" class="input-box">
                                                            @csrf
                                                                <input name="id" type="hidden" value="{{ $setting->id }}">
                                                                <input name="title" type="text" class="form-control" value="{{ $setting->name }}">
                                                        </form>
                                                    </div>
                                                    <div class="col-3 col-md-3 mt-0 text-center">
                                                        <div>
                                                            <label class="switch">
                                                                <input type="checkbox" class="setup_switch" data-name="{{ $setting->name }}" data-id="{{  $setting->id }}"
                                                                     name="{{ $setting->name }}" {{  $setting->status_id == 1 ? 'checked' : '' }}  value=" {{  $setting->status_id == 1 ? 1 : '' }}">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="appScreenSetupUpdate" value="{{ route('appScreenSetupUpdate') }}">
    <input type="hidden" id="token" value="{{ csrf_token() }}">
@endsection
