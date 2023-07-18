<div class="table-content table-basic">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-right justify-content-end mb-4 flex-wrap parent-select2-width">
                <div class="d-flex align-items-right flex-wrap">
                    @if (hasPermission('project_notes_create'))
                        <div class="form-group d-flex justify-content-end">
                            <a onclick="viewModal(`{{ route('project.note.create', 'project_id=' . $data['view']->id) }}`)"
                                href="javascript:;" class="crm_theme_btn "> <i class="fa fa-plus-square pr-2"></i>
                                {{ _trans('common.Create') }}</a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
        <div class="card-body">

            <div class="row d-flex align-items-start mb-5">
                @foreach ($data['notes'] as $item)
                    <div class="col-md-3">
                        <div class="sticky-note">
                            @if (hasPermission('project_notes_delete'))
                                <span>
                                    <small class="d-flex justify-content-end text-danger cursor-pointer"
                                        onclick="__globalDelete({{ $item->id }},`admin/project/note/delete/`)">
                                        <i class="fas fa-times"></i>
                                    </small>
                                </span>
                            @endif

                            @if (hasPermission('project_notes_edit'))
                                <span
                                    onclick="viewModal(`{{ route('project.note.edit', 'project_id=' . $data['view']->id) . '&note_id=' . $item->id }}`)">
                                    {!! \Str::limit(strip_tags($item->description), 100) !!}
                                </span>
                            @else
                                {!! \Str::limit(strip_tags($item->description), 100) !!}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
