@if (hasPermission('task_menu'))
<li class="sidebar-menu-item {{ set_menu([route('task.index')]) }}">
    <a href="javascript:void(0)" class="parent-item-content has-arrow {{ menu_active_by_route(['task.create','task.index','task.view','task.edit']) }}">
        <i class="las la-tasks"></i>
        <span class="on-half-expanded">
            {{ _trans('common.Tasks') }}
          
        </span>
    </a>
    <ul class="child-menu-list {{ set_active(['admin/task*']) }}">

        @if (hasPermission('task_create'))
            <li class="sidebar-menu-item {{ menu_active_by_route(['task.create']) }}">
                <a href="{{ route('task.create') }}">
                    <span> {{ _trans('common.Task Create') }}</span>
                </a>
            </li>
        @endif

        @if (hasPermission('task_list'))
            <li class="sidebar-menu-item {{ menu_active_by_route(['task.index','task.view','task.edit']) }}">
                <a href="{{ route('task.index') }}">
                    <span> {{ _trans('common.Task List') }}</span>
                </a>
            </li>
        @endif

    </ul>
</li>
@endif