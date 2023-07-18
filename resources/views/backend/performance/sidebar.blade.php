@if (hasPermission('performance_menu'))
<li class="sidebar-menu-item">
    <a href="javascript:void(0)"
        class="parent-item-content has-arrow {{ menu_active_by_route(['performance.indicator.index','performance.appraisal.index','performance.goal.index','performance.competence.type.index','performance.goal_type.index','performance.competence.index']) }}">
        <i class="las la-user-shield"></i>
        <span class="on-half-expanded">
            {{ _trans('common.Performance') }}
        </span>
    </a>
    <ul class="child-menu-list {{ set_active(['admin/performance*']) }}">

        @if (hasPermission('performance_indicator_menu'))
        <li class="sidebar-menu-item {{ menu_active_by_route(['performance.indicator.index']) }}">
            <a href="{{ route('performance.indicator.index') }}">
                <span> {{ _trans('common.Indicator') }}</span>
            </a>
        </li>
        @endif

        @if (hasPermission('performance_appraisal_menu'))
        <li class="sidebar-menu-item {{ menu_active_by_route(['performance.appraisal.index']) }}">
            <a href="{{ route('performance.appraisal.index') }}">
                <span> {{ _trans('common.Appraisal') }}</span>
            </a>
        </li>
        @endif

        @if (hasPermission('performance_goal_menu'))
        <li class="sidebar-menu-item {{ menu_active_by_route(['performance.goal.index']) }}">
            <a href="{{ route('performance.goal.index') }}">
                <span> {{ _trans('common.Goal') }}</span>
            </a>
        </li>
        @endif
        @if (hasPermission('performance_settings'))
        <li class="sidebar-menu-item {{ set_menu([route('performance.competence.type.index')]) }} ">
            <a href="javascript:void(0)"
                class="parent-item-content has-arrow nav-item {{ menu_active_by_route(['performance.competence.type.index','performance.competence.index','performance.goal_type.index']) }}">
                <span class="">
                    {{ _trans('common.Settings') }}

                </span>
            </a>
            <ul class="child-menu-list {{ set_active(['admin/performance/settings*']) }} pl-2">


                @if (hasPermission('performance_competence_type_menu'))
                <li class="nav-item {{ menu_active_by_route(['performance.competence.type.index']) }}">
                    <a href="{{ route('performance.competence.type.index') }}">
                        <span> {{ _trans('common.Competence Type') }}</span>
                    </a>
                </li>
                @endif
                @if (hasPermission('performance_competence_menu'))
                <li class="nav-item {{ menu_active_by_route(['performance.competence.index']) }}">
                    <a href="{{ route('performance.competence.index') }}">
                        <span> {{ _trans('common.Competencies') }}</span>
                    </a>
                </li>
                @endif

                @if (hasPermission('performance_goal_type_list'))
                <li class="nav-item {{ menu_active_by_route(['performance.goal_type.index']) }}">
                    <a href="{{ route('performance.goal_type.index') }}">
                        <span> {{ _trans('common.Goal Type') }}</span>
                    </a>
                </li>
                @endif

            </ul>
        </li>
        @endif
        {{-- Competencies --}}



    </ul>
</li>
@endif