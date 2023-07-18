<!-- profile menu mobile start -->
<div class="profile-content">
    <div class="profile-menu-mobile">
        <button class="btn-menu-mobile" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasWithBothOptionsMenuMobile" aria-controls="offcanvasWithBothOptionsMenuMobile">
            <span class="icon"><i class="fa-solid fa-bars"></i></span>
        </button>

        <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1"
            id="offcanvasWithBothOptionsMenuMobile">
            <div class="offcanvas-header">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                    <span class="icon"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="offcanvas-body">
                <!-- profile menu start -->
                <div class="profile-menu">
                    <!-- profile menu head start -->
                    <div class="profile-menu-head">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img class="img-fluid rounded-circle"
                                src="{{ @$data['show']->original['data']['avatar'] }}" width="60"
                                    alt="profile image" />
                            </div>
                            <div class="flex-grow-1">
                                <div class="body">
                                    <h2 class="title">{{ @$data['name'] }}</h2>
                                    <p class="paragraph">{{ @$data['name'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- profile menu head end -->

                    <!-- profile menu body start -->
                    <div class="profile-menu-body">
                        <nav>
                            <ul class="nav flex-column">
                                <li class="nav-item dropdown">
                                    <a class="nav-link {{ menu_active_by_url(route('user.profile', [$data['id'], 'personal'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'personal']) }}">
                                        {{ _trans('common.Profile') }}
                                    </a>
                                </li>
                                @if (hasPermission('contract_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'contract'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'contract']) }}">
                                            {{ _trans('profile.Contract') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('attendance_profile'))
                                    <li class="nav-item dropdown">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'attendance'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'attendance']) }}">
                                            {{ _trans('attendance.Attendance') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('notice_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'notice'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'notice']) }}">
                                            {{ _trans('common.Notices') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('leave_request_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'leave_request'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'leave_request']) }}">
                                            {{ _trans('common.Leaves') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('visit_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'visit'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'visit']) }}">
                                            {{ _trans('common.Visit') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('phonebook_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'phonebook'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'phonebook']) }}">
                                            {{ _trans('common.Phonebook') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('appointment_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'appointment'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'appointment']) }}">
                                            {{ _trans('appointment.Appointment') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('support_ticket_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'ticket'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'ticket']) }}">
                                            {{ _trans('common.Support') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('advance_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'advance'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'advance']) }}">
                                            {{ _trans('payroll.Advance') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('commission_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'commission'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'commission']) }}">
                                            {{ _trans('payroll.Commission') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('salary_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'salary'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'salary']) }}">
                                            {{ _trans('payroll.Salary') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('project_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'project'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'project']) }}">
                                            {{ _trans('project.Projects') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('task_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'task'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'task']) }}">
                                            {{ _trans('project.Tasks') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('award_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'award'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'award']) }}">
                                            {{ _trans('award.Awards') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('travel_profile'))
                                    <li class="nav-item">
                                        <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'travel'])) }}"
                                            href="{{ route('user.profile', [$data['id'], 'travel']) }}">
                                            {{ _trans('travel.Travels') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    <!-- profile menu body end -->
                </div>
                <!-- profile menu end -->
            </div>
        </div>
    </div>
</div>

<!-- profile menu mobile end -->
<div class="new-profile-content">
    <div class="profile-menu">
        <div class="table-basic table-content mb-24">
            <div class="card pb-0">
                <div class="card-body">
                    <div class="profile-menu-head">
                        <div class="crm_profile_wrapper d-flex gap-3 align-items-center flex-wrap">
                            <div class="thumb">
                                <img class="img-fluid"
                                    src="{{ @$data['show']->original['data']['avatar'] }}"
                                        alt="profile image" />
                            </div>
                            <div class="flex-grow-1">
                                <div class="crm_profile_contactInfo">
                                    <h4 class="fs-4 fw-400 mb-0">{{ @$data['name'] }}</h4>
                                    <div class="d-flex gap-2 align-items-center">
                                        <a href="#" class="fs-6 fw-500 contactInfo_text">Developer</a>
                                        <div class="horizontal_line"></div>
                                        <a href="#" class="fs-6 fw-500 contactInfo_text">onest@gmail.com</a>
                                    </div>
                                </div>
                                <div class="crm_profile_boxes d-flex flex-wrap">
                                    <div class="single_crm_profile_box">
                                        <div class="d-flex align-items-center gap-2">
                                            <h4 class="fs-4">$4,500</h4>
                                        </div>
                                        <p>Earnings</p>
                                    </div>
                                    <div class="single_crm_profile_box">
                                        <div class="d-flex align-items-center gap-2">
                                            <h4 class="fs-4">$4,500</h4>
                                        </div>
                                        <p>Projects</p>
                                    </div>
                                    <div class="single_crm_profile_box">
                                        <div class="d-flex align-items-center gap-2">
                                            <h4 class="fs-4">$4,500</h4>
                                        </div>
                                        <p>Success Rate</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-pills crm_navTabs">
                            <li class="nav-item dropdown">
                                <a class="nav-link {{ menu_active_by_url(route('user.profile', [$data['id'], 'personal'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'personal']) }}">
                                    {{ _trans('common.Profile') }}
                                </a>
                            </li>
                            @if (hasPermission('contract_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'contract'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'contract']) }}">
                                        {{ _trans('profile.Contract') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('attendance_profile'))
                                <li class="nav-item dropdown">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'attendance'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'attendance']) }}">
                                        {{ _trans('attendance.Attendance') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('notice_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'notice'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'notice']) }}">
                                        {{ _trans('common.Notices') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('leave_request_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'leave_request'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'leave_request']) }}">
                                        {{ _trans('common.Leaves') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('visit_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'visit'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'visit']) }}">
                                        {{ _trans('common.Visit') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('phonebook_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'phonebook'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'phonebook']) }}">
                                        {{ _trans('common.Phonebook') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('appointment_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'appointment'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'appointment']) }}">
                                        {{ _trans('appointment.Appointment') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('support_ticket_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'ticket'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'ticket']) }}">
                                        {{ _trans('common.Support') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('advance_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'advance'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'advance']) }}">
                                        {{ _trans('payroll.Advance') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('commission_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'commission'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'commission']) }}">
                                        {{ _trans('payroll.Commission') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('salary_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'salary'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'salary']) }}">
                                        {{ _trans('payroll.Salary') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('project_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'project'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'project']) }}">
                                        {{ _trans('project.Projects') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('task_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'task'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'task']) }}">
                                        {{ _trans('project.Tasks') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('award_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'award'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'award']) }}">
                                        {{ _trans('award.Awards') }}
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('travel_profile'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'travel'])) }}"
                                        href="{{ route('user.profile', [$data['id'], 'travel']) }}">
                                        {{ _trans('travel.Travels') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    
    
        <!-- profile menu body end -->
    </div>
</div>

