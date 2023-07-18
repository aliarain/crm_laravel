<header class="header">
    <div class="header_control_left d-flex gap-3 align-items-center">
        <button class="half-expand-toggle sidebar-toggle d-none d-lg-flex align-items-center justify-content-center ">
            <i class="las la-angle-double-left"></i>
        </button>
        <div class="header-search tab-none">
            <div class="search-icon">
                <i class="las la-search"></i>
            </div>
            <input class="search-field ot_input" id="search_field" type="text"
                placeholder="{{ _trans('common.Search Page') }}" onkeyup="searchMenu()">
            <div id="autoCompleteData" class="d-none">
                <ul class="search_suggestion">

                </ul>
            </div>
        </div>
    </div>
    <button class="close-toggle sidebar-toggle">
        <img src="{{ url('public/assets/images/icons/hammenu-2.svg') }}" alt="">
    </button>
    <div class="header-controls flex-fill justify-content-end">
        <!-- language dropdown  -->
        <div class="header-control-item">
            <x-language-dropdown />
        </div>
        <div class="dropdown theme_dropdown">
            <a id="button" class="btn " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
            <ul class="dropdown-menu">
                <li class="dropdown-item active theme_list " data-theme-style="default-theme"> <i
                        class="lar la-sun"></i> {{ _trans('common.Light') }}</li>
                <li class="dropdown-item theme_list" data-theme-style="dark-theme"> <i class="lar la-moon"></i> {{
                    _trans('common.Dark') }}
                </li>
            </ul>
        </div>
        <div class="header-control-item md-none">
            <div class="item-content dropdown  ">
                <button class="header_square_box" onclick="javascript:screenToggle()">
                    <i class="expand_icon las la-expand"></i>
                </button>
            </div>
        </div>
        <!-- checkin checkout  -->
        <div class="header-control-item ">
            <div class="item-content d-flex align-items-center">
                <div class="d-flex  align-items-between cursor-pointer pointer gap-2 " role="navigation" id="topbar_messages" aria-expanded="false">
                    @if (!isAttendee()['checkin'] && auth()->user()->role_id != 1)
                    <a id="demo" onclick="viewModal(`{{ route('admin.ajaxDashboardCheckinModal') }}`)" class="header_square_box checkin d-flex align-items-center">
                        <span class="checkin-btn"><i class="las la-sign-in-alt"></i></span>
                    </a>
                    @endif
                    @if (isAttendee()['checkin'] && !isAttendee()['checkout'])
                    <a onclick="viewModal(`{{ route('admin.ajaxDashboardCheckOutModal') }}`)"  class="header_square_box">
                        <span class="checkout-btn"><i class="las la-sign-out-alt"></i></span>
                    </a>
                    <input type="text" hidden value="{{ url('public/assets/coffee-break.png') }}" id="break_icon">
                    <span class="break_back_button">
                        @if (isBreakRunning() == 'start')
                        <a onclick="breakBack(`{{ route('admin.ajaxDashboardBreakModal_Back') }}`, `{{ route('admin.ajaxDashboardBreakModal') }}`)"  class="header_square_box">
                            <span class="break-btn"><i class="las la-coffee"></i></span>
                        </a>
                        @else
                        <a onclick="breakStart(`{{ route('admin.ajaxDashboardBreakModal') }}`)" class="header_square_box">
                            <span class="break-btn"><i class="las la-coffee"></i></span>
                        </a>
                        @endif
                    </span>

                    @endif
                    @if (isAttendee()['checkin'] && isAttendee()['checkout'])
                    <a id="demo" onclick="viewModal(`{{ route('admin.ajaxDashboardCheckinModal') }}`)" class="header_square_box checkin">
                        <span class="checkin-btn"><i class="las la-sign-in-alt"></i></span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="header-control-item  d-none d-lg-block">
            <div class="item-content">
                <button class="header_square_box" type="button" id="topbar_notifications" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="las la-bell"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end topbar-dropdown-menu ot-card pv-32 ph-16 topbar_notifications" aria-labelledby="topbar_notifications">
                    <div class="topbar-dropdown-header">
                        <h1>{{ _trans('common.Notifications') }}</h1>
                    </div>
                    <div class="topbar-dropdown-content">
                        @forelse (auth()->user()->unreadNotifications as $key => $notification)
                        <a class="dropdown-item topbar-dropdown-item d-flex align-items-start" href="#">
                            @php
                            if ($key > 5) {
                            continue;
                            }
                            @endphp
                            <div class="d-flex flex-column">
                                <div class="d-flex gap-3 flex-row">
                                    <div class="item-avater">
                                        <img src="{{ uploaded_asset_with_user(@$notification->data['sender_id']) }}"
                                            alt="User" />
                                        <div class="item-badge item-icon-badge">
                                            <i class="fa-solid fa-star">&starf;</i>
                                        </div>
                                    </div>
                                    <div class="item-content">
                                        <h6 class="notification">
                                            {{ @$notification->data['title'] }}
                                            <p>{!! @$notification->data['body'] !!}</p>
                                        </h6>
                                    </div>
                                </div>
                                <div class="item-status ml-62">
                                    <span class="time"> {{ @$notification->created_at->diffForHumans() }} </span>
                                    <span class="status-dot active"></span>
                                </div>
                            </div>
                        </a>
                        @empty
                        <p>{{ _trans('common.No Notification Found') }}</p>



                        @endforelse
                        <div class="d-flex align-items-center">
                            <a class="topbar-dropdown-footer ot-btn-primary w-100 "
                                href="{{ route('notification.index') }}">{{ _trans('common.See All Notifications') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- header__profile  -->
        <div class="header-control-item header__profile">
            <div class="item-content">
                <div class="profile-navigate mt-0 cursor-pointer " id="profile_expand" data-bs-toggle="dropdown"
                    role="navigation">
                    <div class="profile-photo">
                        <img src="{{ uploaded_asset(@Auth::user()->avatar_id) }}" alt="profile">
                    </div>
                </div>

                <div class="dropdown-menu dropdown-menu-end profile-expand-dropdown top-navbar-dropdown-menu ot-card pa-0"
                    aria-labelledby="profile_expand">
                    <div class="profile-expand-container">

                        <div class="profile-expand-list d-flex flex-column">
                            <div class="d-flex gap-3 align-items-center profile_inner_img profile-border">
                                <div class="thumb">
                                    <img class="img-fluid" src="{{ uploaded_asset(@Auth::user()->avatar_id) }}"
                                        alt="profile">
                                </div>
                                <div class="profile-info align-items-end">
                                    <span>{{ @Auth::user()->name }}</span>
                                    <h6> {{ @Auth::user()->designation->title }}, {{
                                        auth()->user()->company->company_name }}</h6>
                                </div>
                            </div>

                            @if (hasPermission('profile_view'))
                            <a class="profile-expand-item " href="{{ route('user.authProfile', 'personal') }}">

                                <span><i class="las la-user-alt"></i> {{ _trans('common.My Profile') }}</span>
                            </a>
                            @endif
                            <a class="profile-expand-item" href="{{ route('notification.index') }}" >
                                <span><i class="las la-bell"></i>{{ _trans('common.Notification') }}</span>
                            </a>
                            <a class="profile-expand-item " href="{{ route('user.authProfile', ['settings']) }}">
                                <span><i class="las la-cog"></i> {{ _trans('common.Settings') }}</span>
                            </a>
                            <div class="profile-border p-0"></div>
                            <a class="profile-expand-item " href="{{ route('dashboard.logout') }}">
                                <span>{{ _trans('common.Logout') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>