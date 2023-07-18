<!-- profile menu mobile start -->
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
                                src="{{ @$data['show']->original['data']['avatar'] }}"
                                alt="profile image" />
                        </div>
                        <div class="flex-grow-1">
                            <div class="body">
                                <h2 class="title">{{ _trans('common.Robert Downey') }}</h2>
                                <p class="paragraph">{{ _trans('common.UI/UX Designer') }}</p>
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
                                <a class="nav-link {{ menu_active_by_route('staff.profile') }}"
                                    href="{{ route('staff.profile', 'official') }}">
                                    {{ _trans('common.Profile') }}
                                </a>
                            </li>
                            @if (auth()->user()->role_id != 1)
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'contract')) }}"
                                        href="{{ route('staff.profile.info', 'contract') }}">
                                        {{ _trans('common.Contract') }}
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'attendance')) }}"
                                        href="{{ route('staff.profile.info', 'attendance') }}">
                                        {{ _trans('common.Attendance') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'notice')) }}"
                                        href="{{ route('staff.profile.info', 'notice') }}">
                                        {{ _trans('common.Notices') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'leave_request')) }}"
                                        href="{{ route('staff.profile.info', 'leave_request') }}">
                                        {{ _trans('common.Leaves') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'visit')) }}"
                                        href="{{ route('staff.profile.info', 'visit') }}">
                                        {{ _trans('common.Visit') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'phonebook')) }}"
                                        href="{{ route('staff.profile.info', 'phonebook') }}">
                                        {{ _trans('common.Phonebook') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'appointment')) }}"
                                        href="{{ route('staff.profile.info', 'appointment') }}">
                                        {{ _trans('common.Appointment') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'ticket')) }}"
                                        href="{{ route('staff.profile.info', 'ticket') }}">
                                        {{ _trans('common.Support Ticket') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'advance')) }}"
                                        href="{{ route('staff.profile.info', 'advance') }}">
                                        {{ _trans('common.Advance') }}
                                    </a>
                                </li>
            
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'commission')) }}"
                                        href="{{ route('staff.profile.info', 'commission') }}">
                                        {{ _trans('common.Commission') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'salary')) }}"
                                        href="{{ route('staff.profile.info', 'salary') }}">
                                        {{ _trans('common.Salary') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'project')) }}"
                                        href="{{ route('staff.profile.info', 'project') }}">
                                        {{ _trans('common.Project') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'tasks')) }}"
                                        href="{{ route('staff.profile.info', 'tasks') }}">
                                        {{ _trans('common.Tasks') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'award')) }}"
                                        href="{{ route('staff.profile.info', 'award') }}">
                                        {{ _trans('common.Awards') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'travel')) }}"
                                        href="{{ route('staff.profile.info', 'travel') }}">
                                        {{ _trans('common.Travels') }}
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
<!-- profile menu mobile end -->
<div class="profile-menu">
<!-- profile menu head start -->
<div class="profile-menu-head">
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <img class="img-fluid rounded-circle"
                src="{{ @$data['show']->original['data']['avatar'] }}" width="60" alt="profile image" />
        </div>
        <div class="flex-grow-1">
            <div class="body">
                <h2 class="title">{{ _trans('common.Robert Downey') }}</h2>
                <p class="paragraph">{{ _trans('common.UI/UX Designer') }}</p>
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
                <a class="nav-link {{ menu_active_by_route('staff.profile') }}"
                    href="{{ route('staff.profile', 'official') }}">
                    {{ _trans('common.Profile') }}
                </a>
            </li>
            @if (auth()->user()->role_id != 1)
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'contract')) }}"
                        href="{{ route('staff.profile.info', 'contract') }}">
                        {{ _trans('common.Contract') }}
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'attendance')) }}"
                        href="{{ route('staff.profile.info', 'attendance') }}">
                        {{ _trans('common.Attendance') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'notice')) }}"
                        href="{{ route('staff.profile.info', 'notice') }}">
                        {{ _trans('common.Notices') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'leave_request')) }}"
                        href="{{ route('staff.profile.info', 'leave_request') }}">
                        {{ _trans('common.Leaves') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'visit')) }}"
                        href="{{ route('staff.profile.info', 'visit') }}">
                        {{ _trans('common.Visit') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'phonebook')) }}"
                        href="{{ route('staff.profile.info', 'phonebook') }}">
                        {{ _trans('common.Phonebook') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'appointment')) }}"
                        href="{{ route('staff.profile.info', 'appointment') }}">
                        {{ _trans('common.Appointment') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'ticket')) }}"
                        href="{{ route('staff.profile.info', 'ticket') }}">
                        {{ _trans('common.Support Ticket') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'advance')) }}"
                        href="{{ route('staff.profile.info', 'advance') }}">
                        {{ _trans('common.Advance') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'commission')) }}"
                        href="{{ route('staff.profile.info', 'commission') }}">
                        {{ _trans('common.Commission') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'salary')) }}"
                        href="{{ route('staff.profile.info', 'salary') }}">
                        {{ _trans('common.Salary') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'project')) }}"
                        href="{{ route('staff.profile.info', 'project') }}">
                        {{ _trans('common.Project') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'tasks')) }}"
                        href="{{ route('staff.profile.info', 'tasks') }}">
                        {{ _trans('common.Tasks') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'award')) }}"
                        href="{{ route('staff.profile.info', 'award') }}">
                        {{ _trans('common.Awards') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info', 'travel')) }}"
                        href="{{ route('staff.profile.info', 'travel') }}">
                        {{ _trans('common.Travels') }}
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>
<!-- profile menu body end -->
</div>