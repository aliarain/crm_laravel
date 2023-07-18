@if (hasPermission('hr_menu'))
    <li class="sidebar-menu-item ">
        <a href="javascript:void(0)"
            class="parent-item-content has-arrow {{ menu_active_by_route([
                'designation.index',
                'designation.edit',
                'designation.create',
                'department.index',
                'department.edit',
                'department.create',
                'roles.index',
                'roles.edit',
                'roles.create',
                'leave.index',
                'leave.create',
                'leave.edit',
                'assignLeave.index',
                'assignLeave.create',
                'assignLeave.edit',
                'leaveRequest.index',
                'leaveRequest.create',
                'attendance.index',
                'shift.index',
                'hrm.payroll_items.index',
                'hrm.payroll_setup.index',
                'hrm.payroll_setup.user_setup',
                'hrm.payroll_setup.user_commission_setup',
                'hrm.payroll_advance_type.index',
                'hrm.payroll_advance_salary.index',
                'hrm.payroll_advance_salary.create',
                'hrm.payroll_advance_salary.edit',
                'hrm.payroll_advance_salary.show',
                'hrm.payroll_salary.index',
                'hrm.payroll_salary.show',
                'hrm.payroll_salary.invoice',
                'award_type.index',
                'award.index',
                'award.create',
                'award.edit',
                'award.view',
                'travel_type.index',
                'travel.index',
                'travel.create',
                'travel.edit',
                'travel.view',
                'performance.indicator.index',
                'performance.appraisal.index',
                'performance.goal.index',
                'performance.competence.type.index',
                'performance.goal_type.index',
                'performance.competence.index',
                'meeting.index',
                'meeting.create',
                'meeting.edit',
                'meeting.view',
                'appointment.index',
                'appointment.create',
                'visit.index',
                'user.index',
                'user.edit',
                'user.create',
                'company.settings.configuration',
                'weekendSetup.index',
                'holidaySetup.index',
                'dutySchedule.index',
                'shift.index',
                'ipConfig.index',
                'ipConfig.create',
                'company.settings.location',
                'company.settings.locationCreate',
                'company.settings.locationEdit',
                'company.settings.activation',
                'holidaySetup.create',
                'holidaySetup.show',
                'dutySchedule.create',
                'dutySchedule.edit',
            ]) }}">
            <i class="las la-user"></i>
            <span class="on-half-expanded">
                {{ _trans('common.Human Resource') }}
            </span>
        </a>
        <ul
            class="child-menu-list {{ set_active([
                'hrm/designation*',
                'hrm/department*',
                'hrm/roles*',
                'hrm/leave*',
                'hrm/attendance*',
                'hrm/payroll*',
                'admin/award*',
                'admin/travel*',
                'admin/performance*',
                'admin/meeting*',
                'hrm/appointment*',
                'hrm/visit*',
                'dashboard/user*',
                'admin/company-setup*',
                'hrm/weekend/setup*',
                'hrm/holiday/setup*',
                'hrm/duty/schedule*',
                'hrm/shift*',
                'hrm/ip-whitelist*',
                'hrm/location*',
            ]) }}">
            @if (hasPermission('company_setup_menu'))
                <li class="sidebar-menu-item">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route([
                            'company.settings.configuration',
                            'weekendSetup.index',
                            'holidaySetup.index',
                            'dutySchedule.index',
                            'shift.index',
                            'ipConfig.index',
                            'ipConfig.create',
                            'company.settings.location',
                            'company.settings.locationCreate',
                            'company.settings.locationEdit',
                            'company.settings.activation',
                            'holidaySetup.create',
                            'holidaySetup.show',
                            'dutySchedule.create',
                            'dutySchedule.edit',
                            'designation.index',
                            'department.index',
                            'roles.index',
                        ]) }}">
                        <i class="las la-tools"></i>
                        <span class="on-half-expanded">
                            {{ _trans('common.HR Setup') }}

                        </span>
                    </a>
                    <ul
                        class="child-menu-list  {{ set_active([
                            'admin/company-setup*',
                            'hrm/weekend/setup*',
                            'hrm/holiday/setup*',
                            'hrm/duty/schedule*',
                            'hrm/shift*',
                            'hrm/ip-whitelist*',
                            'hrm/location*',
                            'hrm/designation*',
                            'hrm/department*',
                            'hrm/roles*',
                        ]) }}">

                        @if (hasPermission('designation_read'))
                            <li
                                class="sidebar-menu-item {{ menu_active_by_route(['designation.index', 'designation.edit', 'designation.create']) }}">
                                <a href="{{ route('designation.index') }}"
                                    class="  {{ set_active(route('designation.index')) }}">
                                    <span>{{ _trans('common.Designations') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('department_read'))
                            <li
                                class="sidebar-menu-item {{ menu_active_by_route(['department.index', 'department.edit', 'department.create']) }}">
                                <a href="{{ route('department.index') }}"
                                    class="  {{ set_active(route('department.index')) }}">
                                    <span>{{ _trans('common.Departments') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('role_read'))
                            <li
                                class="sidebar-menu-item {{ menu_active_by_route(['roles.index', 'roles.edit', 'roles.create']) }}">
                                <a href="{{ route('roles.index') }}" class=" {{ set_active('dashboard/roles') }}">
                                    <span>{{ _trans('common.Roles') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('company_setup_configuration'))
                            <li class="nav-item {{ menu_active_by_route(['company.settings.configuration']) }}">
                                <a href="{{ route('company.settings.configuration') }}" class=" ">
                                    <span>{{ _trans('common.Configurations') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('weekend_read'))
                            <li class="nav-item {{ menu_active_by_route(['weekendSetup.index']) }}">
                                <a href="{{ route('weekendSetup.index') }}"
                                    class=" {{ set_active([route('weekendSetup.index')]) }}">
                                    <span>{{ _trans('common.Weekend Setup') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('holiday_read'))
                            <li
                                class="nav-item {{ menu_active_by_route(['holidaySetup.index', 'holidaySetup.create', 'holidaySetup.show']) }}">
                                <a href="{{ route('holidaySetup.index') }}"
                                    class=" {{ menu_active_by_route(['holidaySetup.index', 'holidaySetup.create', 'holidaySetup.show']) }}">
                                    <span>{{ _trans('common.Holiday Setup') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('shift_read'))
                            <li class="nav-item {{ menu_active_by_route('shift.index') }}">
                                <a href="{{ route('shift.index') }}" class=" {{ set_active(route('shift.index')) }}">
                                    <span>{{ _trans('common.Shift Setup') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('schedule_read'))
                            <li
                                class="nav-item {{ menu_active_by_route(['dutySchedule.index', 'dutySchedule.create', 'dutySchedule.show']) }}">
                                <a href="{{ route('dutySchedule.index') }}"
                                    class=" {{ menu_active_by_route(['dutySchedule.index', 'dutySchedule.create', 'dutySchedule.show']) }}">
                                    <span>{{ _trans('common.Duty Schedule') }}</span>
                                </a>
                            </li>
                        @endif




                        @if (hasPermission('company_setup_ip_whitelist'))
                            <li class="nav-item {{ menu_active_by_route(['ipConfig.index', 'ipConfig.create']) }}">
                                <a href="{{ route('ipConfig.index') }}" class="">
                                    <span>{{ _trans('common.IP Whiltelist') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('company_setup_location'))
                            <li
                                class="nav-item {{ menu_active_by_route(['company.settings.location', 'company.settings.locationCreate', 'company.settings.locationEdit']) }}">
                                <a href="{{ route('company.settings.location') }}" class="">
                                    <span>{{ _trans('common.Locations') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('company_setup_activation'))
                            <li class="nav-item {{ menu_active_by_route('company.settings.activation') }}">
                                <a href="{{ route('company.settings.activation') }}" class="">
                                    <span>{{ _trans('common.Activation') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- Strat Employee List --}}
            @if (hasPermission('user_menu'))
                <li class="sidebar-menu-item ">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['user.index', 'user.edit', 'user.create']) }}">
                        <i class="las la-user-alt"></i>
                        <span class="on-half-expanded">
                            {{ _trans('common.Employees') }}
                        </span>
                    </a>
                    <ul class="child-menu-list {{ set_active(['dashboard/user*']) }}">
                        @if (hasPermission('leave_type_read'))
                            <li class="nav-item {{ menu_active_by_route(['user.create', 'user.edit']) }}">
                                <a href="{{ route('user.create') }}" class=" {{ set_active(route('user.create')) }}">
                                    <span>{{ _trans('common.Add Employee') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('leave_type_read'))
                            <li class="nav-item {{ menu_active_by_route(['user.index']) }}">
                                <a href="{{ route('user.index') }}" class=" {{ set_active(route('user.index')) }}">
                                    <span>{{ _trans('common.Employee List') }}</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif



            @if (hasPermission('leave_menu'))
                <li class="sidebar-menu-item {{ set_menu([route('leave.index')]) }}">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['leave.index', 'leave.create', 'leave.edit', 'assignLeave.index', 'assignLeave.create', 'assignLeave.edit', 'leaveRequest.index', 'leaveRequest.create']) }}">
                        <i class="las la-sign-out-alt"></i>
                        <span class="on-half-expanded">
                            {{ _trans('common.Leaves') }}

                        </span>
                    </a>
                    <ul
                        class="child-menu-list {{ set_active(['hrm/leave*', 'hrm/leave/assign*', 'hrm/leave/request*']) }}">
                        @if (hasPermission('leave_type_read'))
                            <li
                                class="nav-item {{ menu_active_by_route(['leave.index', 'leave.create', 'leave.edit']) }}">
                                <a href="{{ route('leave.index') }}" class=" {{ set_active(route('leave.index')) }}">
                                    <span>{{ _trans('common.Type') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('leave_assign_read'))
                            <li
                                class="nav-item {{ menu_active_by_route(['assignLeave.index', 'assignLeave.create', 'assignLeave.edit']) }}">
                                <a href="{{ route('assignLeave.index') }}"
                                    class=" {{ set_active(route('assignLeave.index')) }}">
                                    <span> {{ _trans('common.Assign Leave') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('leave_request_read'))
                            <li
                                class="nav-item {{ menu_active_by_route(['leaveRequest.index', 'leaveRequest.create']) }}">
                                <a href="{{ route('leaveRequest.index') }}"
                                    class=" {{ set_active(route('leaveRequest.index')) }}">
                                    <span>{{ _trans('common.Leave Request') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (hasPermission('attendance_menu'))
                <li class="sidebar-menu-item {{ set_menu([route('attendance.index')]) }}">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route('attendance.index', 'shift.index') }}">
                        <i class="las la-user-check"></i>
                        <span class="on-half-expanded">
                            {{ _trans('common.Attendance') }}

                        </span>
                    </a>
                    <ul class="child-menu-list {{ set_active(['hrm/attendance*']) }}">



                        @if (hasPermission('attendance_read'))
                            <li class="nav-item {{ menu_active_by_route('attendance.index') }}">
                                <a href="{{ route('attendance.index') }}"
                                    class=" {{ set_active(route('attendance.index')) }}">
                                    <span>{{ _trans('common.Attendance') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('generate_qr_code') && settings('attendance_method') == 'QR')
                            <li class="nav-item {{ menu_active_by_route('hrm.qr.index') }}">
                                <a href="{{ route('hrm.qr.index') }}"
                                    class=" {{ set_active(route('hrm.qr.index')) }}">
                                    <span>{{ _trans('common.QR Code') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- strat payroll_menu -->
            @if (hasPermission('payroll_menu'))
                <li class="sidebar-menu-item {{ set_menu([route('hrm.payroll_items.index')]) }}">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['hrm.payroll_items.index', 'hrm.payroll_setup.index', 'hrm.payroll_setup.user_setup', 'hrm.payroll_setup.user_commission_setup', 'hrm.payroll_advance_type.index', 'hrm.payroll_advance_salary.index', 'hrm.payroll_advance_salary.create', 'hrm.payroll_advance_salary.edit', 'hrm.payroll_advance_salary.show', 'hrm.payroll_salary.index', 'hrm.payroll_salary.show', 'hrm.payroll_salary.invoice']) }}">
                        <i class="las la-comment-dollar"></i>
                        <span class="on-half-expanded">
                            {{ _trans('common.Payroll') }}

                        </span>
                    </a>
                    <ul class="child-menu-list {{ set_active(['hrm/payroll*']) }}">

                        @if (hasPermission('payroll_item_menu'))
                            <li class="nav-item {{ menu_active_by_route(['hrm.payroll_items.index']) }}">
                                <a href="{{ route('hrm.payroll_items.index') }}" class="">
                                    <span> {{ _trans('common.Commissions') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('payroll_set_menu'))
                            <li
                                class="nav-item {{ menu_active_by_route(['hrm.payroll_setup.index', 'hrm.payroll_setup.user_setup', 'hrm.payroll_setup.user_commission_setup']) }}">
                                <a href="{{ route('hrm.payroll_setup.index') }}" class="">
                                    <span> {{ _trans('common.Setup') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('advance_type_list'))
                            <li class="nav-item {{ menu_active_by_route(['hrm.payroll_advance_type.index']) }}">
                                <a href="{{ route('hrm.payroll_advance_type.index') }}" class="">
                                    <span> {{ _trans('common.Advance Type') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('advance_salaries_list'))
                            <li
                                class="nav-item {{ menu_active_by_route(['hrm.payroll_advance_salary.index', 'hrm.payroll_advance_salary.create', 'hrm.payroll_advance_salary.edit', 'hrm.payroll_advance_salary.show']) }}">
                                <a href="{{ route('hrm.payroll_advance_salary.index') }}" class="">
                                    <span> {{ _trans('common.Advance') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('payroll_set_menu'))
                            <li
                                class="nav-item {{ menu_active_by_route(['hrm.payroll_salary.index', 'hrm.payroll_salary.show', 'hrm.payroll_salary.invoice']) }}">
                                <a href="{{ route('hrm.payroll_salary.index') }}" class="">
                                    <span> {{ _trans('common.Salary') }}</span>
                                </a>
                            </li>
                        @endif



                    </ul>
                </li>
            @endif
            <!-- end payroll_menu -->

            {{-- award management start --}}
            @include('backend.award.sidebar')
            {{-- award management end --}}

            {{-- Start Travel Routes --}}
            @include('backend.travel.sidebar')
            {{-- End Travel Routes --}}



            {{-- Start performance Routes --}}
            @include('backend.performance.sidebar')
            {{-- End performance Routes --}}

            {{-- Start meeting Routes --}}
            @include('backend.meeting.sidebar')
            {{-- End meeting Routes --}}
            @if (hasPermission('appointment_menu'))
                <li class="sidebar-menu-item {{ set_menu([route('appointment.index', 'appointment.create')]) }}">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow  {{ menu_active_by_route(['appointment.index', 'appointment.create']) }}">
                        <i class="las la-calendar-check"></i>
                        <span class="on-half-expanded">
                            {{ _trans('appointment.Appointment') }}

                        </span>
                    </a>
                    <ul class="child-menu-list {{ set_active(['hrm/appointment*']) }}">

                        <li class="nav-item {{ menu_active_by_route(['appointment.index', 'appointment.create']) }}">
                            <a href="{{ route('appointment.index') }}"
                                class="  {{ set_active(route('appointment.index', 'appointment.create')) }}">
                                <span>{{ _trans('common.List') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (hasPermission('visit_menu'))
                <li class="sidebar-menu-item {{ set_menu([route('visit.index')]) }}">
                    <a href="javascript:void(0)"
                        class="parent-item-content has-arrow {{ menu_active_by_route(['visit.index']) }}">
                        <i class="las la-map-marked-alt"></i>
                        <span class="on-half-expanded">
                            {{ _trans('common.Visit') }}

                        </span>
                    </a>
                    <ul class="child-menu-list {{ set_active(['hrm/visit*']) }}">
                        @if (hasPermission('visit_read'))
                            <li class="nav-item {{ menu_active_by_route(['visit.index']) }}">
                                <a href="{{ route('visit.index') }}" class="">
                                    <span>{{ _trans('common.Manage visit') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


        </ul>
    </li>
@endif
<!-- END HR Menu Item -->
