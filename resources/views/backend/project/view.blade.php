@extends('backend.layouts.app')
@section('title', @$data['sub_title'])
@section('content')
{!! breadcrumb([
'title' => @$data['sub_title'],
route('admin.dashboard') => _trans('common.Dashboard'),
'#' => @$data['sub_title'],
]) !!}
<div class="table-content table-basic">
    <div class="">
        <div class="">

            <div class="row">
                <div class="col-lg-12">
                    <div class="">
                        @include('backend.project.tab')
                    </div>
                    @if (url()->current() === route('project.view', [$data['view']->id, 'overview']) && hasPermission('project_view'))
                    @include('backend.project.overview')
                    @endif

                    @if (url()->current() === route('project.view', [$data['view']->id, 'discussions']) &&
                    hasPermission('project_discussion_list'))
                    @include('backend.project.discussion.index')
                    @endif
                    @if (url()->current() === route('project.view', [$data['view']->id, 'notes']) &&
                    hasPermission('project_notes_list'))
                    @include('backend.project.note.index')
                    @endif
                    @if (url()->current() === route('project.view', [$data['view']->id, 'tasks']) && hasPermission('task_list'))
                    @include('backend.project.task.index')
                    @endif

                    @if (url()->current() === route('project.view', [$data['view']->id, 'activity']) &&
                    hasPermission('project_activity_view'))
                    <div class="row ">
                        <div class="col-md-12">
                            @foreach ($data['activity'] as $item)
                            <div class="post card ot-card mt-20">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm img-thumbnail" src="{{ uploaded_asset($item->avatar_id) }}" alt="user image">
                                    <div class="d-block mt-10">
                                        <span class="username">
                                            <b>{{ $item->username }}</b>
                                        </span>
                                        <span class="description">{{ showTimeFromTimeStamp($item->created_at) }}</span>
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
                    @if (url()->current() === route('project.view', [$data['view']->id, 'members']) &&
                    hasPermission('project_member_view'))
                    <div class="row ">
                        <div class="col-md-12">
                            @forelse ($data['members'] as $item)
                            @if (isset($item->user))
                            <div class="post card ot-card mt-20">
                                <div class="user-block d-flex align-items-center">
                                    <img class="img-circle img-bordered-sm img-thumbnail me-2" src="{{ uploaded_asset(@$item->user->avatar_id) }}" alt="user image">
                                    <div class="d-block">
                                        <span class="username">
                                            <b>{{ @$item->user->name }}</b>
                                            {{ _trans('project.Added By') }} {{ @$item->_by->name?? '...' }}
                                            @if (hasPermission('project_member_delete'))
                                            <a href="javascript:;" class="float-right btn-tool btn btn-danger btn-sm" onclick="__globalDelete(`{{ $item->id }}?project_id={{ $data['view']->id }}`,`admin/project/member-delete/`)">
                                                <i class="fas fa-times"></i></a>
                                            @endif
                                        </span>
                                        <span class="description">{{ showTimeFromTimeStamp($item->created_at) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @empty
                            <p class="text-center mt-20">
                                {{_trans('message.Members Not Found')}}
                            </p>
                            @endforelse


                        </div>
                    </div>

                    @endif
                    @if (url()->current() === route('project.view', [$data['view']->id, 'files']) &&
                    hasPermission('project_file_list'))
                    @include('backend.project.file.index')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <input type="text" hidden id="{{ @$data['url_id'] }}" value="{{ $data['datatable_url'] }}"> --}}
@endsection
@section('script')
@include('backend.partials.table_js')
<script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/backend/js/pages/__project.js') }}"></script>
<script src="{{ asset('public/backend/js/pages/__task.js') }}"></script>


@endsection