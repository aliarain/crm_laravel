<!-- profile menu mobile start -->
<div class="profile-content mb-3">
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
                                    <a class="nav-link active _lead_nav _Profile" href="javascript:void(0)"
                                        onclick="getLeadDetails('Profile')"> {{ _trans('common.Profile') }} </a>
                                </li>
                                @foreach( config()->get('app.lead_menus') as $key => $value)
                                <li class="nav-item dropdown">
                                    <a class="nav-link  _lead_nav _{{ $value }}" href="javascript:void(0)"
                                        onclick="getLeadDetails('{{ $value }}')"> {{ _trans('common.'.$value) }} </a>
                                </li>
                                @endforeach
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
        <div class="table-basic table-content ">
            <div class="card pb-0 mb-3">
                <div class="crm_profile_wrapper d-flex gap-3 align-items-center flex-wrap">
                   
                    <div class="flex-grow-1">
                        <div class="crm_profile_contactInfo">
                            <h4 class="fs-4 fw-400 mb-0">{{ $data['lead']->name }}</h4>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="#" class="fs-6 fw-500 contactInfo_text">{{ $data['lead']->company }}</a>
                                <div class="horizontal_line"></div>
                                <a href="#" class="fs-6 fw-500 contactInfo_text">{{ $data['lead']->email }}</a>
                                <div class="horizontal_line"></div>
                                <a href="#" class="fs-6 fw-500 contactInfo_text">{{ $data['lead']->phone }}</a>
                            </div>
                        </div>

                    </div>
                </div>
                <nav>
                    <ul class="nav nav-pills crm_navTabs">
                        <input type="hidden" name="lead_id" value="{{ $data['id'] }}" id="lead_id">
                        <li class="nav-item dropdown">
                            <a class="nav-link active _lead_nav _Profile" href="javascript:void(0)"
                                onclick="getLeadDetails('Profile')"> {{ _trans('common.Profile') }} </a>
                        </li>


                        @foreach( config()->get('app.lead_menus') as $key => $value)
                        <li class="nav-item dropdown">
                            <a class="nav-link  _lead_nav _{{ $value }}" href="javascript:void(0)"
                                onclick="getLeadDetails('{{ $value }}')"> {{ _trans('common.'.$value) }} </a>
                        </li>
                        @endforeach

                    </ul>
                </nav>
            </div>
        </div>

        <!-- profile menu body end -->
    </div>
</div>