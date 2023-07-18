<div class="card ot-card p-0 mb-3">
    <ul class="nav nav-tabs no-margin project-tabs nav-tabs-horizontal" role="tablist">
        @if (hasPermission('project_view'))
            <li
                class="{{ url()->current() === route('project.view', [$data['view']->id, 'overview']) ? 'active' : '' }} project_tab_project_overview">
                <a data-group="project_overview" role="tab"
                    href="{{ route('project.view', [$data['view']->id, 'overview']) }}">
                    <i class="fa fa-th" aria-hidden="true"></i>
                    {{ _trans('project.Overview') }}

                </a>
            </li>
        @endif
        @if (hasPermission('project_view'))
            <li
                class="{{ url()->current() === route('project.view', [$data['view']->id, 'tasks']) ? 'active' : '' }} project_tab_project_tasks">
                <a data-group="project_tasks" role="tab"
                    href="{{ route('project.view', [$data['view']->id, 'tasks']) }}">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    {{ _trans('task.Tasks') }}

                </a>
            </li>
        @endif
        @if (hasPermission('project_file_list'))
            <li
                class="{{ url()->current() === route('project.view', [$data['view']->id, 'files']) ? 'active' : '' }} project_tab_project_files">
                <a data-group="project_files" role="tab"
                    href="{{ route('project.view', [$data['view']->id, 'files']) }}">
                    <i class="fas fa-copy" aria-hidden="true"></i>
                    {{ _trans('project.Files') }}

                </a>
            </li>
        @endif
        @if (hasPermission('project_discussion_list'))
            <li
                class="{{ url()->current() === route('project.view', [$data['view']->id, 'discussions']) ? 'active' : '' }} project_tab_project_discussions">
                <a data-group="project_discussions" role="tab"
                    href="{{ route('project.view', [$data['view']->id, 'discussions']) }}">
                    <i class="fas fa-comment" aria-hidden="true"></i>
                    {{ _trans('project.Discussions') }}

                </a>
            </li>
        @endif
        @if (hasPermission('project_notes_list'))
            <li
                class="{{ url()->current() === route('project.view', [$data['view']->id, 'notes']) ? 'active' : '' }} project_tab_project_notes">
                <a data-group="project_notes" role="tab"
                    href="{{ route('project.view', [$data['view']->id, 'notes']) }}">
                    <i class="fas fa-file" aria-hidden="true"></i>
                    {{ _trans('project.Notes') }}

                </a>
            </li>
        @endif
        @if (hasPermission('project_member_view'))
            <li
                class="{{ url()->current() === route('project.view', [$data['view']->id, 'members']) ? 'active' : '' }} project_tab_project_members">
                <a data-group="project_members" role="tab"
                    href="{{ route('project.view', [$data['view']->id, 'members']) }}">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    {{ _trans('project.Members') }}

                </a>
            </li>
            @endif
            @if (hasPermission('project_activity_view'))
                <li
                    class="{{ url()->current() === route('project.view', [$data['view']->id, 'activity']) ? 'active' : '' }} project_tab_project_activity">
                    <a data-group="project_activity" role="tab"
                        href="{{ route('project.view', [$data['view']->id, 'activity']) }}">
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                        {{ _trans('project.Activity') }}

                    </a>
                </li>
            @endif
    </ul>
</div>
