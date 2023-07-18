@extends('backend.layouts.app')
@section('title', @$data['slug'])
@section('content')

{!! breadcrumb([ 'title' => @$data['slug'], route('admin.dashboard') => _trans('common.Dashboard'), '#' => @$data['slug']]) !!}

    <div class="card ot-card mb-3 p-0">
        @include('backend.task.tab')
    </div>


    <div class="row">
        <div class="col-lg-12">
 
            @if (url()->current() === route('task.view', [$data['view']->id, 'details']) && hasPermission('task_view'))
                @include('backend.task.details')
            @endif

            @if (url()->current() === route('task.view', [$data['view']->id, 'discussions']) &&
                hasPermission('task_discussion_list'))
                @include('backend.task.discussion.index')
            @endif
            @if (url()->current() === route('task.view', [$data['view']->id, 'notes']) && hasPermission('task_notes_list'))
                @include('backend.task.note.index') 
            @endif

            @if (url()->current() === route('task.view', [$data['view']->id, 'activity']) &&
                hasPermission('task_activity_view'))
                <div class="row ">
                    <div class="col-md-12">
                        @foreach ($data['activity'] as $item)
                            <div class="post card ot-card ">
                                <div class="user-block d-flex align-items-center">
                                    <img class="img-circle img-bordered-sm img-thumbnail me-2"
                                        src="{{ uploaded_asset($item->avatar_id) }}" alt="user image">
                                    <div class="mt-4">
                                        <p>
                                            <span class="username">
                                                <b>{{ $item->username }}</b>
                                            </span>
                                            <span class="description">{{ showTimeFromTimeStamp($item->created_at) }}</span>
                                        </p>
                                    </div>

                                </div>

                                <p>
                                    {{ @$item->description }}
                                </p>
                            </div>
                        @endforeach


                    </div>
                </div>

            @endif
            @if (url()->current() === route('task.view', [$data['view']->id, 'members']) && hasPermission('task_assign_view'))
                <div class="row ">
                    <div class="col-md-12">
                        @if (@$data['members'])
                            @foreach ($data['view']->members as $item)
                                <div class="post card ot-card ">
                                    <div class="user-block d-flex align-items-center">
                                        <img class="img-circle img-bordered-sm img-thumbnail me-2"
                                            src="{{ uploaded_asset($item->user->avatar_id) }}" alt="user image">
                                        <div class="mt-4">
                                            <p>
                                                <span class="username">
                                                    <b>{{ $item->user->name }}</b>
                                                    {{ _trans('project.Added By') }} {{ @$item->_by->name }}
                                                    @if (hasPermission('task_assign_delete'))
                                                        <a href="javascript:;" class="float-right btn-tool btn btn-sm btn-danger"
                                                            onclick="__globalDelete(`{{ $item->id }}?task_id={{ $data['view']->id }}`,`admin/task/member-delete/`)">
                                                            X</a>
                                                    @endif
                                                </span>
                                                <span class="description">{{ showTimeFromTimeStamp($item->created_at) }}
                                                </span>
                                            </p>

                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @endif


                    </div>
                </div>

            @endif
            @if (url()->current() === route('task.view', [$data['view']->id, 'files']) && hasPermission('task_file_list'))
                @include('backend.task.file.index')
            @endif
 
        </div>
    </div>
@endsection
@section('script')
    @include('backend.partials.table_js')
    <script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/backend/js/pages/__project.js') }}"></script>
    <script src="{{ asset('public/backend/js/pages/__task.js') }}"></script>
@endsection
