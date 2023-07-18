@if (hasPermission('travel_menu'))
<li class="sidebar-menu-item {{ set_menu([route('travel.index')]) }}">
    <a href="javascript:void(0)"
        class="parent-item-content has-arrow {{ menu_active_by_route(['travel_type.index','travel.index','travel.create','travel.edit','travel.view']) }}">
        <i class="las la-route"></i>
        <span class="on-half-expanded">
            {{ _trans('common.Travels') }}

        </span>
    </a>
    <ul class="child-menu-list {{ set_active(['admin/travel*']) }}">

        @if (hasPermission('travel_type_menu'))
        <li class="sidebar-menu-item {{ menu_active_by_route(['travel_type.index']) }}">
            <a href="{{ route('travel_type.index') }}">
                <span> {{ _trans('common.Type') }}</span>
            </a>
        </li>
        @endif

        @if (hasPermission('travel_list'))
        <li
            class="sidebar-menu-item {{ menu_active_by_route(['travel.index','travel.create','travel.edit','travel.view']) }}">
            <a href="{{ route('travel.index') }}">
                <span> {{ _trans('common.Travel List') }}</span>
            </a>
        </li>
        @endif
    </ul>
</li>
@endif