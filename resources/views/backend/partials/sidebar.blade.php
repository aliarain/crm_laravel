<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}" class="img-tag sidebar_logo">
                @include('backend.auth.backend_logo')
                {{-- half_logo --}}
                <img class="half-logo" src="{{ half_logo(@base_settings('company_icon')) }}" alt="Icon">
            </a>
        </div>
        <button class="half-expand-toggle sidebar-toggle d-none">
            <i class="las la-bars"></i>
        </button>
        <button class="close-toggle sidebar-toggle">
            <i class="las la-chevron-left"></i>
        </button>
    </div>

    <div id="sidebar_scroll" class="sidebar-menu sidebar_scrollbar_active">
        <div class="sidebar-menu-section">
            <!-- parent menu list start  -->
            <ul class="sidebar-dropdown-menu parent-menu-list">
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="parent-item-content {{ set_active(route('admin.dashboard')) }}">
                        <i class="las la-home "></i>
                        <span class="on-half-expanded">{{ _trans('common.Dashboard') }}</span>
                    </a>
                </li>
                {{-- superadmin sidebar --}}
                @if (hasPermission('company_read') && config('app.APP_BRANCH') != 'nonsaas')
                    <li class="sidebar-menu-item">
                        <a href="javascript:void(0)" class="parent-item-content has-arrow">


                            <span class="on-half-expanded">
                                {{ _trans('common.Company') }}
                            </span>
                        </a>
                        <ul class="child-menu-list {{ set_active(['dashboard/companies*']) }}">
                            @if (hasPermission('company_read'))
                                <li class="sidebar-menu-item {{ menu_active_by_route(['dashboard/companies*']) }}">
                                    <a href="{{ route('company.index') }}"
                                        class="  {{ set_active(route('company.index')) }}">
                                        <span>{{ _trans('common.Company List') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif


                {{-- Start Client Module --}}
                @include('backend.client.sidebar')
                {{-- End Client Module --}}

                {{-- project management start --}}
                @include('backend.project.sidebar')
                {{-- project management end --}}

                {{-- task management start --}}
                @include('backend.task.sidebar')
                {{-- project management end --}}

                {{-- Sale start --}}
                @include('backend.sidebar.sale_sidebar')
                {{-- Sale end --}}



                <!-- Start:: Lead Management -->
                @include('backend.leads.lead_sidebar')
                <!-- End:: Lead Management -->

                <!-- Start:: Human Resource Menu -->
                @include('backend.sidebar.hr_sidebar')
                <!-- End:: Human Resource Menu -->





                @if (hasPermission('account_menu'))
                    <li class="sidebar-menu-item {{ set_menu([route('hrm.accounts.index', 'hrm.accounts.create')]) }}">
                        <a href="javascript:void(0)"
                            class="parent-item-content has-arrow {{ menu_active_by_route(['hrm.accounts.index', 'hrm.deposits.index', 'hrm.expenses.index', 'hrm.transactions.index', 'hrm.accounts.create', 'hrm.accounts.edit', 'hrm.deposits.create', 'hrm.deposits.edit', 'hrm.expenses.create', 'hrm.expenses.edit', 'hrm.expenses.show', 'hrm.deposit_category.deposit', 'hrm.deposit_category.expense', 'hrm.payment_method.index']) }}">
                            <i class="las la-file-invoice-dollar"></i>
                            <span class="on-half-expanded">
                                {{ _trans('common.Accounts') }}

                            </span>
                        </a>
                        <ul
                            class="child-menu-list {{ set_active(['hrm/accounts*', 'hrm/transactions*', 'hrm/deposit*', 'hrm/expenses*', 'hrm/account-settings*', 'hrm/payment-method*']) }}">


                            @if (hasPermission('account_menu'))
                                <li
                                    class="nav-item {{ menu_active_by_route(['hrm.accounts.index', 'hrm.accounts.create', 'hrm.accounts.edit']) }}">
                                    <a href="{{ route('hrm.accounts.index') }}" class="">
                                        <span> {{ _trans('common.Account List') }}</span>
                                    </a>
                                </li>
                            @endif

                            @if (hasPermission('deposit_menu'))
                                <li
                                    class="nav-item {{ menu_active_by_route(['hrm.deposits.index', 'hrm.deposits.create', 'hrm.deposits.edit']) }}">
                                    <a href="{{ route('hrm.deposits.index') }}" class="">
                                        <span> {{ _trans('common.Deposit') }}</span>
                                    </a>
                                </li>
                            @endif

                            @if (hasPermission('expense_menu'))
                                <li
                                    class="nav-item {{ menu_active_by_route(['hrm.expenses.index', 'hrm.expenses.create', 'hrm.expenses.edit', 'hrm.expenses.show']) }}">
                                    <a href="{{ route('hrm.expenses.index') }}" class="">
                                        <span> {{ _trans('common.Expense') }}</span>
                                    </a>
                                </li>
                            @endif

                            @if (hasPermission('transaction_menu'))
                                <li class="nav-item {{ menu_active_by_route(['hrm.transactions.index']) }}">
                                    <a href="{{ route('hrm.transactions.index') }}" class="">
                                        <span> {{ _trans('common.Transaction History') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('deposit_category_menu'))
                                <li class="sidebar-menu-item {{ set_menu([route('hrm.deposit_category.expense')]) }}">
                                    <a href="javascript:void(0)"
                                        class="parent-item-content has-arrow {{ menu_active_by_route(['hrm.deposit_category.deposit', 'hrm.deposit_category.expense', 'hrm.payment_method.index']) }}">
                                        <span class="-text font-purple">
                                            {{ _trans('common.Accounts Settings') }}

                                        </span>
                                    </a>
                                    <ul
                                        class="child-menu-list {{ set_active(['hrm/account-settings*', 'hrm/payment-method*']) }}">


                                        @if (hasPermission('deposit_category_menu'))
                                            <li
                                                class="nav-item {{ menu_active_by_route(['hrm.deposit_category.deposit']) }}">
                                                <a href="{{ route('hrm.deposit_category.deposit') }}" class="">
                                                    <span> {{ _trans('common.Deposit Category') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if (hasPermission('deposit_category_menu'))
                                            <li
                                                class="nav-item {{ menu_active_by_route(['hrm.deposit_category.expense']) }}">
                                                <a href="{{ route('hrm.deposit_category.expense') }}" class="">
                                                    <span> {{ _trans('common.Expense Category') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if (hasPermission('payment_method_menu'))
                                            <li
                                                class="nav-item {{ menu_active_by_route(['hrm.payment_method.index']) }}">
                                                <a href="{{ route('hrm.payment_method.index') }}" class="">
                                                    <span> {{ _trans('account.Payment Method') }}</span>
                                                </a>
                                            </li>
                                        @endif

                                    </ul>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif




                @if (hasPermission('support_menu'))
                    <li
                        class="sidebar-menu-item {{ set_menu([route('supportTicket.index', 'supportTicket.create')]) }}">
                        <a href="javascript:void(0)"
                            class="parent-item-content has-arrow {{ menu_active_by_route(['supportTicket.index', 'supportTicket.create', 'supportTicket.reply']) }}">
                            <i class="las la-ticket-alt"></i>
                            <span class="on-half-expanded">
                                {{ _trans('common.Support') }}

                            </span>
                        </a>
                        <ul class="child-menu-list {{ set_active(['hrm/support/ticket*']) }}">
                            @if (hasPermission('support_read'))
                                <li
                                    class="nav-item {{ menu_active_by_route(['supportTicket.create', 'supportTicket.reply']) }}">
                                    <a href="{{ route('supportTicket.create') }}" class="">
                                        <span> {{ _trans('common.Create Ticket') }}</span> </a>
                                </li>
                            @endif
                            @if (hasPermission('support_read'))
                                <li class="nav-item {{ menu_active_by_route(['supportTicket.index']) }}">
                                    <a href="{{ route('supportTicket.index') }}" class="">
                                        <span> {{ _trans('common.Tickets') }}</span> </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (hasPermission('announcement_menu'))
                    <li class="sidebar-menu-item  {{ set_menu([route('notice.index', 'notice.create', 'notice.edit')]) }} ">
                        <a href="javascript:void(0)"
                            class="parent-item-content has-arrow {{ menu_active_by_route(['notice.index', 'notice.create', 'notice.edit']) }}">
                            <i class="las la-bullhorn"></i>
                            <span class="on-half-expanded">
                                {{ _trans('common.Announcement') }}

                            </span>
                        </a>
                        <ul class="child-menu-list  {{ set_active(['announcement/*', 'dashboard/announcement/*']) }}">

                            @if (hasPermission('notice_menu'))
                                <li class="nav-item  {{ menu_active_by_route(['notice.create', 'notice.edit']) }} ">
                                    <a href="{{ route('notice.create') }}"
                                        class="">
                                        <span>{{ _trans('common.Create Notice') }}</span> <span
                                            class="badge badge-info d-none fs-6 p-s">5</span> </a>
                                </li>
                            @endif
                            @if (hasPermission('notice_menu'))
                                <li class="nav-item  {{ menu_active_by_route(['notice.index']) }} ">
                                    <a href="{{ route('notice.index') }}"
                                        class="">
                                        <span>{{ _trans('common.Notice') }}</span> </a>
                                </li>
                            @endif

                            @if (hasPermission('send_email_menu'))
                                <li class="nav-item d-none">
                                    <a href="#" class="">
                                        <span>{{ _trans('common.Send E-mail') }}</span> </a>
                                </li>
                            @endif
                            @if (hasPermission('send_notification_menu'))
                                <li class="nav-item d-none">
                                    <a href="#" class="">
                                        <span>{{ _trans('common.Send Notification') }}</span> </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    {{-- <li
                        class="sidebar-menu-item  {{ set_menu([route('notice.index', 'notice.create', 'notice.edit')]) }} ">
                        <a href="javascript:void(0)"
                            class="parent-item-content has-arrow {{ menu_active_by_route(['notice.index', 'notice.create', 'notice.edit']) }}">
                            <i class="las la-bullhorn"></i>
                            <span class="on-half-expanded">
                                {{ _trans('common.Announcement') }}

                            </span>
                        </a>
                        <ul class="child-menu-list {{ set_active(['dashboard/announcement/*']) }}">
                            @if (hasPermission('notice_menu'))
                                <li class="nav-item {{ menu_active_by_route(['notice.create', 'notice.edit']) }}">
                                    <a href="{{ route('notice.create') }}" class="">
                                        <span> {{ _trans('common.Create Notice') }}</span> </a>
                                </li>
                                <li class="nav-item  {{ menu_active_by_route(['notice.create', 'notice.edit']) }} ">
                                    <a href="{{ route('notice.create') }}"
                                        class="  {{ menu_active_by_route(['notice.create', 'notice.edit']) }} ">
                                        <span>{{ _trans('common.Create Notice') }}</span> <span
                                            class="badge badge-info d-none fs-6 p-s">5</span> </a>
                                </li>
                            @endif
                            @if (hasPermission('notice_menu'))
                                <li class="nav-item {{ menu_active_by_route(['supportTicket.index']) }}">
                                    <a href="{{ route('supportTicket.index') }}" class="">
                                        <span> {{ _trans('common.Tickets') }}</span> </a>
                                </li>
                            @endif
                        </ul>
                    </li> --}}
                @endif
                @if (hasPermission('contact_menu'))
                    @if (auth()->user()->role_id == 1 || Config::get('app.APP_BRANCH') == 'nonsaas')
                        <li class="sidebar-menu-item d-none">
                            <a href="{{ route('contact.index') }}"
                                class="parent-item-content {{ menu_active_by_route(['contact.index']) }}">
                                <i class="las la-inbox"></i>
                                <span class="on-half-expanded">
                                    {{ _trans('common.Contacts') }}
                                </span>
                            </a>
                        </li>
                    @endif
                @endif


                @if (hasPermission('report'))
                    <li class="sidebar-menu-item {{ set_menu([route('attendanceReport.index')]) }}">
                        <a href="javascript:void(0)"
                            class="parent-item-content has-arrow {{ menu_active_by_route(['live_trackingReport.index', 'attendanceReport.index', 'breakReport.index', 'payment.index', 'report_visit.index', 'report_leave']) }}">
                            <i class="las la-clipboard-list"></i>
                            <span class="on-half-expanded">
                                {{ _trans('common.Report') }}

                            </span>
                        </a>
                        <ul
                            class="child-menu-list {{ set_active(['hrm/report/*', 'hrm/break/*', 'hrm/expense/payment']) }}">

                            @if (hasPermission('attendance_report_read'))
                                <li class="nav-item {{ menu_active_by_route(['live_trackingReport.index']) }}">
                                    <a href="{{ route('live_trackingReport.index') }}"
                                        class=" {{ set_active(route('live_trackingReport.index')) }}">
                                        <span>{{ _trans('common.Live Tracking') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ menu_active_by_route(['live_trackingReportHistory.index']) }}">
                                    <a href="{{ route('live_trackingReportHistory.index') }}"
                                        class=" {{ set_active(route('live_trackingReportHistory.index')) }}">
                                        <span>{{ _trans('common.Location History') }}</span>
                                    </a>
                                </li>
                            @endif

                            @if (hasPermission('attendance_report_read'))
                                <li class="nav-item {{ menu_active_by_route(['attendanceReport.index']) }}">
                                    <a href="{{ route('attendanceReport.index') }}"
                                        class=" {{ set_active(route('attendanceReport.index')) }}">
                                        <span>{{ _trans('common.Attendance report') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ menu_active_by_route(['breakReport.index']) }}">
                                    <a href="{{ route('breakReport.index') }}"
                                        class=" {{ set_active(route('breakReport.index')) }}">
                                        <span>{{ _trans('common.Break report') }}</span>
                                    </a>
                                </li>
                            @endif


                            @if (hasPermission('payment_read'))
                                <li class="nav-item {{ menu_active_by_route(['payment.index']) }}">
                                    <a href="{{ route('payment.index') }}"
                                        class=" {{ set_active(route('payment.index')) }}">
                                        <span>{{ _trans('common.Payment Report') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('visit_read'))
                                <li class="nav-item {{ menu_active_by_route(['report_visit.index']) }}">
                                    <a href="{{ route('report_visit.index') }}" class="">
                                        <span>{{ _trans('common.Visit Reports') }}</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif





                @if (hasPermission('general_settings_read'))
                    <li class="sidebar-menu-item">
                        <a href="javascript:void(0)"
                            class="parent-item-content has-arrow {{ menu_active_by_route(['manage.settings.view', 'appScreenSetup', 'ipConfig.create', 'language.index', 'language.setup']) }}">
                            <i class="las la-cog"></i>
                            <span class="on-half-expanded">
                                {{ _trans('common.Settings') }}
                            </span>
                        </a>
                        <ul
                            class="child-menu-list  {{ set_active(['admin/settings*', 'admin/settings/list', 'admin/settings/leave*', 'admin/settings/ip-configuration*', 'company/settings', 'admin/settings/app-setting/dashboard', 'admin/settings/language-setup']) }}">
                            @if (hasPermission('general_settings_read'))
                                <li class="nav-item {{ menu_active_by_route('manage.settings.view') }}">
                                    <a href="{{ route('manage.settings.view') }}"
                                        class=" {{ set_active('admin/settings/list') }}">
                                        <span>{{ _trans('common.General Settings') }}</span>
                                    </a>
                                </li>
                            @endif

                            {{-- get config file value --}}
                            @if (auth()->user()->role_id == 1 || Config::get('app.APP_BRANCH') == 'nonsaas')
                                @if (hasPermission('app_settings_menu'))
                                    <li class="nav-item {{ menu_active_by_route('appScreenSetup') }}">
                                        <a href="{{ route('appScreenSetup') }}"
                                            class=" {{ set_active('admin/settings/contact/*') }}">
                                            <span>{{ _trans('common.App Setting') }}</span>
                                        </a>
                                    </li>
                                @endif



                                @if (hasPermission('language_menu'))
                                    <li
                                        class="nav-item {{ menu_active_by_route(['language.index', 'language.setup']) }}">
                                        <a href="{{ route('language.index') }}"
                                            class=" {{ set_active('admin/settings/language/*') }}">
                                            <span>{{ _trans('common.Language') }}</span>
                                        </a>
                                    </li>
                                @endif


                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
            <!-- parent menu list end  -->
        </div>
    </div>
</aside>
