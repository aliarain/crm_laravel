<!-- start lead parent sidebar menu  -->
@if (hasPermission('lead_menu'))
<li class="sidebar-menu-item {{ set_menu([route('lead.index'), route('source.index')]) }}">
    <a href="javascript:void(0)"
        class="parent-item-content has-arrow {{ menu_active_by_route(['lead.index', 'lead.create', 'lead.edit', 'source.index', 'source.create', 'source.edit']) }}">
        <i class="las la-user-friends"></i>
        <span class="on-half-expanded">
            {{ _trans('common.Leads') }}
        </span>
    </a>
    <ul class="child-menu-list {{ set_active(['admin/leads*', 'admin/types*','admin/proposal*','admin/sources*', 'admin/statuses*']) }}">


        <!-- start lead type sidebar menu  -->
        @if (hasPermission('lead_type_menu'))
        <li class="sidebar-menu-item {{ menu_active_by_route(['type.index', 'type.create', 'type.edit']) }}">
            <a href="{{ route('type.index') }}">
                <span> {{ _trans('common.Lead Types') }}</span>
            </a>
        </li>
        @endif
        <!-- end source sidebar menu  -->

        <!-- start lead type sidebar menu  -->
        @if (hasPermission('lead_source_menu'))
        <li class="sidebar-menu-item {{ menu_active_by_route(['source.index', 'source.create', 'source.edit']) }}">
            <a href="{{ route('source.index') }}">
                <span> {{ _trans('common.Lead Sources') }}</span>
            </a>
        </li>
        @endif
        <!-- end source sidebar menu  -->


        <!-- start lead status sidebar menu  -->
        @if (hasPermission('lead_status_menu'))
        <li class="sidebar-menu-item {{ menu_active_by_route(['status.index', 'status.create', 'status.edit']) }}">
            <a href="{{ route('status.index') }}">
                <span> {{ _trans('common.Lead Status') }}</span>
            </a>
        </li>
        @endif
        <!-- end source sidebar menu  -->


        <!-- start lead sidebar menu  -->
        @if (hasPermission('lead_menu'))
        <li class="sidebar-menu-item {{ menu_active_by_route(['lead.index', 'lead.create', 'lead.edit']) }}">
            <a href="{{ route('lead.index') }}">
                <span> {{ _trans('common.Leads') }}</span>
            </a>
        </li>
        @endif
        <!-- end lead sidebar menu  -->




       

    </ul>
</li>
@endif
<!-- end lead parent sidebar menu  -->