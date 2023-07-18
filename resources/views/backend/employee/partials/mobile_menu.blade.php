<div class="profile-menu-mobile">
    <button class="btn-menu-mobile" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasWithBothOptionsMenuMobile" aria-controls="offcanvasWithBothOptionsMenuMobile">
        <span class="icon"><i class="fa-solid fa-bars"></i></span>
    </button>

    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptionsMenuMobile">
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
                            <img class="img-fluid rounded-circle" src="{{ @$data['show']->original['data']['avatar'] }}"
                                width="60" alt="profile image" />
                        </div>
                        <div class="flex-grow-1">
                            <div class="body">
                                <h2 class="title">{{ @$data['name'] }}</h2>
                                <p class="paragraph">{{ @$data['department'] }}</p>
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
                                <a class="nav-link {{ menu_active_by_url(route('user.profile', [$data['id'], 'official'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'official']) }}">
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
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'notice'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'notice']) }}">
                                    {{ _trans('common.Notices') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'leave_request'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'leave_request']) }}">
                                    {{ _trans('common.Leaves') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'visit'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'visit']) }}">
                                    {{ _trans('common.Visit') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'phonebook'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'phonebook']) }}">
                                    {{ _trans('common.Phonebook') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'appointment'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'appointment']) }}">
                                    {{ _trans('appointment.Appointment') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'ticket'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'ticket']) }}">
                                    {{ _trans('common.Support') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'advance'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'advance']) }}">
                                    {{ _trans('payroll.Advance') }}
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'commission'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'commission']) }}">
                                    {{ _trans('payroll.Commission') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'salary'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'salary']) }}">
                                    {{ _trans('payroll.Salary') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'project'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'project']) }}">
                                    {{ _trans('project.Projects') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'task'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'task']) }}">
                                    {{ _trans('project.Tasks') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'award'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'award']) }}">
                                    {{ _trans('award.Awards') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ menu_active_by_url(route('user.profile', [$data['id'], 'travel'])) }}"
                                    href="{{ route('user.profile', [$data['id'], 'travel']) }}">
                                    {{ _trans('travel.Travels') }}
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- profile menu body end -->
            </div>
            <!-- profile menu end -->
        </div>
    </div>
</div>
