@if (hasPermission('award_menu'))
<li class="sidebar-menu-item {{ set_menu([route('award.index')]) }}">
    <a href="javascript:void(0)"
        class="parent-item-content has-arrow {{ menu_active_by_route(['award_type.index','award.index','award.create','award.edit','award.view']) }}">
        <i class="las la-award"></i>
        <span class="non-half-expanded">
            {{ _trans('common.Awards') }}
        </span>
    </a>
    <ul class="child-menu-list {{ set_active(['admin/award*']) }}">

        @if (hasPermission('award_type_menu'))
        <li class="sidebar-menu-item {{ menu_active_by_route(['award_type.index']) }}">
            <a href="{{ route('award_type.index') }}">
                <span> {{ _trans('common.Award Type List') }}</span>
            </a>
        </li>
        @endif

        @if (hasPermission('award_list'))
        <li
            class="sidebar-menu-item {{ menu_active_by_route(['award.index','award.create','award.edit','award.view']) }}">
            <a href="{{ route('award.index') }}">
                <span> {{ _trans('common.Award List') }}</span>
            </a>
        </li>
        @endif



    </ul>
</li>
@endif