<nav class="vertical-menu navbar navbar-expand-lg navbar-light bg-light bg-white mb-3">
    <button class="navbar-toggler ml-3 ml-md-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse ml-sm-4 ml-md-4 ml-lg-1 ml-xl-1" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link {{ menu_active_by_route('staff.staffProfileEditView') }}"
                    href="{{ route('staff.staffProfileEditView', 'official') }}">
                    Profile
                </a>
            </li>
            @if (auth()->user()->role_id != 1)
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info','contract')) }}"
                        href="{{ route('staff.profile.info','contract') }}">
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
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info','notice')) }}"
                        href="{{ route('staff.profile.info','notice') }}">
                        {{ _trans('common.Notices') }}
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info','leave_request')) }}"
                        href="{{ route('staff.profile.info','leave_request') }}">
                        {{ _trans('common.Leaves') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info','visit')) }}"
                        href="{{ route('staff.profile.info','visit') }}">
                        {{ _trans('common.Visit') }}
                    </a>
                </li>
 
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info','phonebook')) }}"
                        href="{{ route('staff.profile.info','phonebook') }}">
                        {{ _trans('common.Phonebook') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info','appointment')) }}"
                        href="{{ route('staff.profile.info','appointment') }}">
                        {{ _trans('common.Appointment') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info','ticket')) }}"
                        href="{{ route('staff.profile.info','ticket') }}">
                        {{ _trans('common.Support Ticket') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info','advance')) }}"
                        href="{{ route('staff.profile.info','advance') }}">
                        {{ _trans('common.Advance') }}
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link  {{ menu_active_by_url(route('staff.profile.info','commission')) }}"
                        href="{{ route('staff.profile.info','commission') }}">
                        {{ _trans('common.Commission') }}
                    </a>
                </li>
            @endif
        </ul>
    </div>
</nav>
