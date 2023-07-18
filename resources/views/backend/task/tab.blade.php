<div class="horizontal-tabs">
    <ul class="nav nav-tabs no-margin task-tabs nav-tabs-horizontal" role="tablist">
        @if (hasPermission('task_view'))
            <li
                class="{{ url()->current() === route('task.view', [$data['view']->id, 'details']) ? 'active' : '' }} task_tab_task_overview">
                <a data-group="task_overview" role="tab"
                    href="{{ route('task.view', [$data['view']->id, 'details']) }}">
                    <i class="fa fa-th" aria-hidden="true"></i>
                    {{ _trans('common.Details') }}

                </a>
            </li>
        @endif
        @if (hasPermission('task_file_list'))
            <li
                class="{{ url()->current() === route('task.view', [$data['view']->id, 'files']) ? 'active' : '' }} task_tab_task_files">
                <a data-group="task_files" role="tab"
                    href="{{ route('task.view', [$data['view']->id, 'files']) }}">
                    <i class="fas fa-copy" aria-hidden="true"></i>
                    {{ _trans('task.Files') }}

                </a>
            </li>
        @endif
        @if (hasPermission('task_discussion_list'))
            <li
                class="{{ url()->current() === route('task.view', [$data['view']->id, 'discussions']) ? 'active' : '' }} task_tab_task_discussions">
                <a data-group="task_discussions" role="tab"
                    href="{{ route('task.view', [$data['view']->id, 'discussions']) }}">
                    <i class="fas fa-comment" aria-hidden="true"></i>
                    {{ _trans('task.Discussions') }}

                </a>
            </li>
        @endif
        @if (hasPermission('task_notes_list'))
            <li
                class="{{ url()->current() === route('task.view', [$data['view']->id, 'notes']) ? 'active' : '' }} task_tab_task_notes">
                <a data-group="task_notes" role="tab"
                    href="{{ route('task.view', [$data['view']->id, 'notes']) }}">
                    <i class="fas fa-file" aria-hidden="true"></i>
                    {{ _trans('task.Notes') }}

                </a>
            </li>
        @endif
        @if (hasPermission('task_member_view'))
            <li
                class="{{ url()->current() === route('task.view', [$data['view']->id, 'members']) ? 'active' : '' }} task_tab_task_members">
                <a data-group="task_members" role="tab"
                    href="{{ route('task.view', [$data['view']->id, 'members']) }}">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    {{ _trans('task.Assigned Members') }}

                </a>
            </li>
            @endif
            @if (hasPermission('task_activity_view'))
                <li
                    class="{{ url()->current() === route('task.view', [$data['view']->id, 'activity']) ? 'active' : '' }} task_tab_task_activity">
                    <a data-group="task_activity" role="tab"
                        href="{{ route('task.view', [$data['view']->id, 'activity']) }}">
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                        {{ _trans('task.Activity') }}

                    </a>
                </li>
            @endif
    </ul>
</div>
