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
                <form action="{{ route('supportTicket.reply.store', $data['show']->id) }}" enctype="multipart/form-data"
                    method="post" id="attendanceForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <p>#{{ $data['show']->code }} | {{ $data['show']->subject }}</p>
                                    <p>{!! $data['show']->description !!}</p>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-options float-right">
                                        <a href="{{ uploaded_asset($data['show']->attachment_file_id) }}" target="_blank"
                                            class="mr-1 mt-1">
                                            {{ _trans('common.Attachment') }} <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </a>
                                        <span class="badge badge-{{ $data['show']->priority->class }} mr-1">
                                            @if ($data['show']->priority->id == 14)
                                                {{ __('High') }}
                                            @elseif($data['show']->priority->id == 15)
                                                {{ __('Medium') }}
                                            @elseif($data['show']->priority->id == 16)
                                                {{ __('Low') }}
                                            @endif
                                        </span>
                                        <span class="badge badge-{{ $data['show']->type->class }}">
                                            @if ($data['show']->type->id == 12)
                                                {{ __('Open') }}
                                            @elseif($data['show']->type->id == 13)
                                                {{ __('Close') }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                @foreach ($data['show']->supportTickets as $item)
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body pt-2 readmores px-6 mx-1">
                                                <div class="">
                                                    <span>{{ @$item->user->name }} | {{ @$item->user->role->name }}</span>
                                                    <span>
                                                        <p>{!! $item->message !!}</p>
                                                    </span>
                                                </div>
                                                <div class="d-block">
                                                    @php
                                                        $now = Carbon\Carbon::now();
                                                        $now->parse($item->created_at)->format('g:i A');
                                                    @endphp
                                                    <small class="fs-13"><i
                                                            class="feather feather-clock text-muted me-1"> <i>{{ _trans('common.Last Updated on') }}<span
                                                            class="text-muted">{{ $now }}</span></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if ($data['show']->type_id == 12)
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">{{ _trans('common.Type') }} <span class="text-danger">*</span></label>
                                            <select name="type_id" class="form-control select2" required="required">
                                                <option value="" disabled selected>{{ _trans('common.Choose One') }}
                                                </option>
                                                <option value="12" {{ $data['show']->type_id == 12 ? 'selected' : '' }}>
                                                    {{ __('Open') }}
                                                </option>
                                                <option value="13" {{ $data['show']->type_id == 13 ? 'selected' : '' }}>
                                                    {{ __('Close') }}
                                                </option>
                                            </select>
                                            @if ($errors->has('type_id'))
                                                <div class="error">{{ $errors->first('type_id') }}</div>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="description" class="form-label">{{ _trans('common.Message') }}</label>
                                            <textarea class="form-control" name="message" placeholder="{{ _trans('common.Message') }}" rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end">
                                            <button class="crm_theme_btn ">{{ _trans('common.Reply') }}</button>
                                        </div>

                                    </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('public/frontend/assets/js/iziToast.js') }}"></script>
    <script src="{{ url('public/backend/js/image_preview.js') }}"></script>


    <script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/ckeditor/config.js') }}"></script>
    <script src="{{ asset('public/ckeditor/styles.js') }}"></script>
    <script src="{{ asset('public/ckeditor/build-config.js') }}"></script>
    <script src="{{ asset('public/backend/js/ticket_replay_ckeditor.js') }}"></script>

@endsection
