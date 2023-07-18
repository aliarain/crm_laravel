@extends('frontend.includes.master')
@section('title',@$data['title'])
@section('crm_menu')
<div class="crm-header">
    <div class="header-shape"></div>
    <div class="landing_header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light bg-transparent">
            <div class="container">
                <a class="logo" href="#">
                    <img class="full-logo  light_logo " src="{{ asset('public/assets/images/white.png') }}" alt="white">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto">
                        <li class=" nav-item ocrm-item active">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class=" nav-item ocrm-item">
                            <a class="nav-link" href="#feature">Features</a>
                        </li>
                        
                        <li class=" nav-item ocrm-item">
                            <a class="nav-link" href="#choose-us">Choose us</a>
                        </li>
                        <li class=" nav-item ocrm-item">
                            <a class="nav-link" href="#download-app">Download App</a>
                        </li>
                    </ul>
                    <div class="responsive-collapse-btn">
                        <a class="primary_btn" href="{{ url('login') }}">Login</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div class="container">
        <div class="col-12">
            <div class="crm-header-title">
                <h2>{{ _trans('landing.Satisfy Your Clients') }}</h2>
                <h1>{{ _trans('landing.Make Your Business Thrive') }}</h1>
            </div>
            <div class="crm-header-content">
                <p>{{ _trans('landing.Solidify Customer relationships with the most reliable, effective & specialized
                    CRM platform
                    in the market') }}
                </p>
            </div>
            <div class="d-flex justify-content-center flex-wrap gap-3 position-relative">
                <a href="{{ url('login') }}" class="primary_btn">CRM Web Browse</a>
                <a target="_brank" href="https://apps.apple.com/us/app/onest-crm/id1661275705" class="primary_btn"><i
                        class="fab fa-app-store-ios"></i> IOS App</a>
                <a target="_brank" href="https://play.google.com/store/apps/details?id=com.onest.crm&hl=en&gl=US"
                    class="primary_btn"><i class="fab fa-google-play"></i> Android App</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

<div class="teams-section gray_bg_1">
    <div class="container">
        <div class="dashboard-demo d-block mx-auto position-relative">
            <img src="{{ url('/public/assets/CRM - img (Hero).png') }}" alt="">
        </div>
    </div>
</div>
<!-- TASKS::START  -->
<section id="feature" class="section_padding gray_bg_1 section_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section__title text-center mb_30">
                    <h3>{{ _trans('landing.Manage Your Clients With Ease') }}</h3>
                    <p>{{ _trans('landing.Take a look at all the aspects that effectively elevates the management of
                        clients to the next level') }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="feature_boxes">
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.40723 28.5924C3.40723 25.9206 4.4686 23.3582 6.35785 21.4689C8.24711 19.5797 10.8095 18.5183 13.4813 18.5183C16.1531 18.5183 18.7155 19.5797 20.6047 21.4689C22.494 23.3582 23.5554 25.9206 23.5554 28.5924H21.0369C21.0369 26.5885 20.2408 24.6667 18.8239 23.2498C17.4069 21.8329 15.4852 21.0368 13.4813 21.0368C11.4774 21.0368 9.55566 21.8329 8.13872 23.2498C6.72177 24.6667 5.92575 26.5885 5.92575 28.5924H3.40723ZM13.4813 17.2591C9.30686 17.2591 5.92575 13.8779 5.92575 9.7035C5.92575 5.52906 9.30686 2.14795 13.4813 2.14795C17.6557 2.14795 21.0369 5.52906 21.0369 9.7035C21.0369 13.8779 17.6557 17.2591 13.4813 17.2591ZM13.4813 14.7405C16.2643 14.7405 18.5183 12.4865 18.5183 9.7035C18.5183 6.92054 16.2643 4.66647 13.4813 4.66647C10.6983 4.66647 8.44426 6.92054 8.44426 9.7035C8.44426 12.4865 10.6983 14.7405 13.4813 14.7405ZM23.913 19.4036C25.6827 20.2006 27.1845 21.4917 28.238 23.1218C29.2915 24.7519 29.8519 26.6515 29.8517 28.5924H27.3332C27.3334 27.1367 26.9133 25.7118 26.1231 24.4892C25.333 23.2666 24.2065 22.2982 22.8792 21.7005L23.9117 19.4036H23.913ZM23.0466 5.18654C24.3154 5.70951 25.4001 6.59759 26.1633 7.73809C26.9265 8.87859 27.3337 10.2201 27.3332 11.5924C27.3337 13.3205 26.688 14.9864 25.523 16.2628C24.358 17.5392 22.7578 18.3338 21.0369 18.4906V15.9557C21.9699 15.8221 22.8355 15.3929 23.5066 14.731C24.1778 14.0692 24.6191 13.2097 24.7657 12.2786C24.9124 11.3475 24.7567 10.394 24.3215 9.55788C23.8863 8.7218 23.1945 8.04726 22.3477 7.63328L23.0466 5.18654Z"
                                    fill="#5653F3" />
                            </svg>
                        </div>
                        <h3>{{ _trans('common.Clients')}}</h3>
                        <p>{{_trans('landing.Keep track of existing and prospective clients as you have access to all
                            the mandatory data')}}</p>
                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.0371 23.0741V2.92599H2.92599V21.8149C2.92599 22.1489 3.05866 22.4692 3.29482 22.7053C3.53097 22.9415 3.85127 23.0741 4.18525 23.0741H18.0371ZM21.8149 25.5927H4.18525C3.18332 25.5927 2.22243 25.1946 1.51396 24.4862C0.805486 23.7777 0.407471 22.8168 0.407471 21.8149V1.66673C0.407471 1.33275 0.540142 1.01246 0.776299 0.776299C1.01246 0.540142 1.33275 0.407471 1.66673 0.407471H19.2964C19.6303 0.407471 19.9506 0.540142 20.1868 0.776299C20.4229 1.01246 20.5556 1.33275 20.5556 1.66673V10.4815H25.5927V21.8149C25.5927 22.8168 25.1946 23.7777 24.4862 24.4862C23.7777 25.1946 22.8168 25.5927 21.8149 25.5927ZM20.5556 13.0001V21.8149C20.5556 22.1489 20.6883 22.4692 20.9244 22.7053C21.1606 22.9415 21.4809 23.0741 21.8149 23.0741C22.1489 23.0741 22.4692 22.9415 22.7053 22.7053C22.9415 22.4692 23.0741 22.1489 23.0741 21.8149V13.0001H20.5556ZM5.44451 5.44451H13.0001V13.0001H5.44451V5.44451ZM7.96303 7.96303V10.4815H10.4815V7.96303H7.96303ZM5.44451 14.2593H15.5186V16.7778H5.44451V14.2593ZM5.44451 18.0371H15.5186V20.5556H5.44451V18.0371Z"
                                    fill="#F4A001" />
                            </svg>


                        </div>
                        <h3>Projects</h3>
                        <p>{{_trans('landing.Efficient management of current and potential projects gives you a vivid
                            idea regarding project status, progress and timeline')}}</p>


                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="26" height="25" viewBox="0 0 26 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M6.70377 9.22229C7.53871 9.22229 8.33945 8.89061 8.92984 8.30021C9.52024 7.70982 9.85192 6.90908 9.85192 6.07414C9.85192 5.2392 9.52024 4.43845 8.92984 3.84806C8.33945 3.25767 7.53871 2.92599 6.70377 2.92599C5.86883 2.92599 5.06808 3.25767 4.47769 3.84806C3.8873 4.43845 3.55562 5.2392 3.55562 6.07414C3.55562 6.90908 3.8873 7.70982 4.47769 8.30021C5.06808 8.89061 5.86883 9.22229 6.70377 9.22229ZM6.70377 11.7408C5.95961 11.7408 5.22274 11.5942 4.53523 11.3095C3.84772 11.0247 3.22303 10.6073 2.69683 10.0811C2.17063 9.55488 1.75323 8.93019 1.46845 8.24268C1.18367 7.55516 1.0371 6.81829 1.0371 6.07414C1.0371 5.32998 1.18367 4.59311 1.46845 3.9056C1.75323 3.21809 2.17063 2.5934 2.69683 2.0672C3.22303 1.541 3.84772 1.1236 4.53523 0.83882C5.22274 0.554043 5.95961 0.407471 6.70377 0.407471C8.20666 0.407471 9.648 1.00449 10.7107 2.0672C11.7734 3.1299 12.3704 4.57124 12.3704 6.07414C12.3704 7.57703 11.7734 9.01837 10.7107 10.0811C9.648 11.1438 8.20666 11.7408 6.70377 11.7408ZM19.926 14.2593C20.5939 14.2593 21.2345 13.994 21.7069 13.5217C22.1792 13.0494 22.4445 12.4088 22.4445 11.7408C22.4445 11.0729 22.1792 10.4323 21.7069 9.95994C21.2345 9.48763 20.5939 9.22229 19.926 9.22229C19.258 9.22229 18.6174 9.48763 18.1451 9.95994C17.6728 10.4323 17.4075 11.0729 17.4075 11.7408C17.4075 12.4088 17.6728 13.0494 18.1451 13.5217C18.6174 13.994 19.258 14.2593 19.926 14.2593ZM19.926 16.7778C18.5901 16.7778 17.3089 16.2472 16.3643 15.3025C15.4196 14.3579 14.889 13.0767 14.889 11.7408C14.889 10.4049 15.4196 9.12371 16.3643 8.17908C17.3089 7.23445 18.5901 6.70377 19.926 6.70377C21.2619 6.70377 22.5431 7.23445 23.4877 8.17908C24.4323 9.12371 24.963 10.4049 24.963 11.7408C24.963 13.0767 24.4323 14.3579 23.4877 15.3025C22.5431 16.2472 21.2619 16.7778 19.926 16.7778ZM23.0741 24.3334V23.7038C23.0741 22.8688 22.7425 22.0681 22.1521 21.4777C21.5617 20.8873 20.7609 20.5556 19.926 20.5556C19.091 20.5556 18.2903 20.8873 17.6999 21.4777C17.1095 22.0681 16.7778 22.8688 16.7778 23.7038V24.3334H14.2593V23.7038C14.2593 22.9596 14.4059 22.2227 14.6907 21.5352C14.9754 20.8477 15.3929 20.223 15.9191 19.6968C16.4452 19.1706 17.0699 18.7532 17.7575 18.4685C18.445 18.1837 19.1818 18.0371 19.926 18.0371C20.6701 18.0371 21.407 18.1837 22.0945 18.4685C22.782 18.7532 23.4067 19.1706 23.9329 19.6968C24.4591 20.223 24.8765 20.8477 25.1613 21.5352C25.4461 22.2227 25.5927 22.9596 25.5927 23.7038V24.3334H23.0741ZM10.4815 24.3334V19.2964C10.4815 18.2944 10.0835 17.3335 9.37506 16.6251C8.66659 15.9166 7.7057 15.5186 6.70377 15.5186C5.70184 15.5186 4.74095 15.9166 4.03247 16.6251C3.324 17.3335 2.92599 18.2944 2.92599 19.2964V24.3334H0.407471V19.2964C0.407471 17.6265 1.07083 16.025 2.25161 14.8442C3.4324 13.6634 5.03389 13.0001 6.70377 13.0001C8.37365 13.0001 9.97514 13.6634 11.1559 14.8442C12.3367 16.025 13.0001 17.6265 13.0001 19.2964V24.3334H10.4815Z"
                                    fill="#09B134" />
                            </svg>

                        </div>
                        <h3>Employees</h3>
                        <p>{{_trans('landing.Create and manage employee profiles, such as name, contact information, job
                            title, and performance metrics')}}</p>


                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="29" height="25" viewBox="0 0 29 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.6378 12.3704H13.3704C14.8733 12.3704 16.3146 12.9675 17.3774 14.0302C18.4401 15.0929 19.0371 16.5342 19.0371 18.0371H10.221L10.2223 19.2964H20.2963V18.0371C20.2924 16.6972 19.9052 15.3864 19.1806 14.2593H22.8149C24.0052 14.259 25.1712 14.5961 26.1778 15.2315C27.1843 15.867 27.9901 16.7747 28.5017 17.8495C25.5235 21.7796 20.7018 24.3334 15.2593 24.3334C11.7825 24.3334 8.83708 23.5904 6.44449 22.2871V10.571C7.97652 10.7904 9.42323 11.4112 10.6378 12.3704ZM5.18523 21.8149C5.18523 22.1489 5.05256 22.4692 4.8164 22.7053C4.58024 22.9415 4.25995 23.0741 3.92597 23.0741H1.40745C1.07348 23.0741 0.753179 22.9415 0.517022 22.7053C0.280865 22.4692 0.148193 22.1489 0.148193 21.8149V10.4815C0.148193 10.1476 0.280865 9.82727 0.517022 9.59111C0.753179 9.35496 1.07348 9.22229 1.40745 9.22229H3.92597C4.25995 9.22229 4.58024 9.35496 4.8164 9.59111C5.05256 9.82727 5.18523 10.1476 5.18523 10.4815V21.8149ZM21.5556 4.18525C22.5575 4.18525 23.5184 4.58326 24.2269 5.29173C24.9354 6.0002 25.3334 6.9611 25.3334 7.96303C25.3334 8.96496 24.9354 9.92585 24.2269 10.6343C23.5184 11.3428 22.5575 11.7408 21.5556 11.7408C20.5537 11.7408 19.5928 11.3428 18.8843 10.6343C18.1758 9.92585 17.7778 8.96496 17.7778 7.96303C17.7778 6.9611 18.1758 6.0002 18.8843 5.29173C19.5928 4.58326 20.5537 4.18525 21.5556 4.18525ZM12.7408 0.407471C13.7427 0.407471 14.7036 0.805486 15.4121 1.51396C16.1205 2.22243 16.5186 3.18332 16.5186 4.18525C16.5186 5.18718 16.1205 6.14807 15.4121 6.85654C14.7036 7.56501 13.7427 7.96303 12.7408 7.96303C11.7389 7.96303 10.778 7.56501 10.0695 6.85654C9.36102 6.14807 8.96301 5.18718 8.96301 4.18525C8.96301 3.18332 9.36102 2.22243 10.0695 1.51396C10.778 0.805486 11.7389 0.407471 12.7408 0.407471Z"
                                    fill="#08BED2" />
                            </svg>

                        </div>
                        <h3>Sales</h3>
                        <p>{{_trans('landing.Track and manage leads, sales opportunities, project future sales revenue
                            based on historical data and current trends')}}</p>

                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="26" height="24" viewBox="0 0 26 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.66673 0.666748H24.3334C24.6674 0.666748 24.9877 0.79942 25.2238 1.03558C25.46 1.27173 25.5927 1.59203 25.5927 1.92601V22.0742C25.5927 22.4081 25.46 22.7284 25.2238 22.9646C24.9877 23.2007 24.6674 23.3334 24.3334 23.3334H1.66673C1.33275 23.3334 1.01246 23.2007 0.776299 22.9646C0.540142 22.7284 0.407471 22.4081 0.407471 22.0742V1.92601C0.407471 1.59203 0.540142 1.27173 0.776299 1.03558C1.01246 0.79942 1.33275 0.666748 1.66673 0.666748ZM2.92599 3.18527V20.8149H23.0741V3.18527H2.92599ZM8.59266 14.5186H15.5186C15.6856 14.5186 15.8457 14.4523 15.9638 14.3342C16.0819 14.2161 16.1482 14.056 16.1482 13.889C16.1482 13.722 16.0819 13.5618 15.9638 13.4438C15.8457 13.3257 15.6856 13.2593 15.5186 13.2593H10.4815C9.6466 13.2593 8.84586 12.9277 8.25547 12.3373C7.66508 11.7469 7.3334 10.9461 7.3334 10.1112C7.3334 9.27625 7.66508 8.47551 8.25547 7.88511C8.84586 7.29472 9.6466 6.96304 10.4815 6.96304H11.7408V4.44453H14.2593V6.96304H17.4075V9.48156H10.4815C10.3146 9.48156 10.1544 9.5479 10.0363 9.66598C9.91825 9.78405 9.85192 9.9442 9.85192 10.1112C9.85192 10.2782 9.91825 10.4383 10.0363 10.5564C10.1544 10.6745 10.3146 10.7408 10.4815 10.7408H15.5186C16.3535 10.7408 17.1543 11.0725 17.7447 11.6629C18.3351 12.2533 18.6667 13.054 18.6667 13.889C18.6667 14.7239 18.3351 15.5247 17.7447 16.115C17.1543 16.7054 16.3535 17.0371 15.5186 17.0371H14.2593V19.5556H11.7408V17.0371H8.59266V14.5186Z"
                                    fill="#F1657A" />
                            </svg>

                        </div>
                        <h3>Income</h3>
                        <p>{{_trans('landing.Effectively manage your business income related to customer interactions,
                            sales and marketing efforts')}}</p>

                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="27" height="24" viewBox="0 0 27 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M25.5927 5.70378H26.8519V18.2964H25.5927V22.0742C25.5927 22.4081 25.46 22.7284 25.2238 22.9646C24.9877 23.2007 24.6674 23.3334 24.3334 23.3334H1.66673C1.33275 23.3334 1.01246 23.2007 0.776299 22.9646C0.540142 22.7284 0.407471 22.4081 0.407471 22.0742V1.92601C0.407471 1.59203 0.540142 1.27173 0.776299 1.03558C1.01246 0.79942 1.33275 0.666748 1.66673 0.666748H24.3334C24.6674 0.666748 24.9877 0.79942 25.2238 1.03558C25.46 1.27173 25.5927 1.59203 25.5927 1.92601V5.70378ZM23.0741 18.2964H15.5186C13.8487 18.2964 12.2472 17.633 11.0664 16.4522C9.88564 15.2714 9.22229 13.67 9.22229 12.0001C9.22229 10.3302 9.88564 8.72871 11.0664 7.54793C12.2472 6.36714 13.8487 5.70378 15.5186 5.70378H23.0741V3.18527H2.92599V20.8149H23.0741V18.2964ZM24.3334 15.7779V8.2223H15.5186C14.5167 8.2223 13.5558 8.62032 12.8473 9.32879C12.1388 10.0373 11.7408 10.9982 11.7408 12.0001C11.7408 13.002 12.1388 13.9629 12.8473 14.6714C13.5558 15.3798 14.5167 15.7779 15.5186 15.7779H24.3334ZM15.5186 10.7408H19.2964V13.2593H15.5186V10.7408Z"
                                    fill="#136FC8" />
                            </svg>

                        </div>
                        <h3>Accounts</h3>
                        <p>{{_trans('landing.Improve customer service by having access to in-depth customer data,
                            amplifying sales and business growth')}}</p>

                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="24" height="26" viewBox="0 0 24 26" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22.0742 25.5927H1.92601C1.59203 25.5927 1.27173 25.46 1.03558 25.2238C0.79942 24.9877 0.666748 24.6674 0.666748 24.3334V1.66673C0.666748 1.33275 0.79942 1.01246 1.03558 0.776299C1.27173 0.540142 1.59203 0.407471 1.92601 0.407471H22.0742C22.4081 0.407471 22.7284 0.540142 22.9646 0.776299C23.2007 1.01246 23.3334 1.33275 23.3334 1.66673V24.3334C23.3334 24.6674 23.2007 24.9877 22.9646 25.2238C22.7284 25.46 22.4081 25.5927 22.0742 25.5927ZM20.8149 23.0741V2.92599H3.18527V23.0741H20.8149ZM5.70378 5.44451H10.7408V10.4815H5.70378V5.44451ZM5.70378 13.0001H18.2964V15.5186H5.70378V13.0001ZM5.70378 18.0371H18.2964V20.5556H5.70378V18.0371ZM13.2593 6.70377H18.2964V9.22229H13.2593V6.70377Z"
                                    fill="#0996AB" />
                            </svg>

                        </div>
                        <h3>Tasks</h3>
                        <p>{{_trans('landing.Ability to assign members to specific tasks, track task progress and
                            status, as well as the option to communicate')}}</p>

                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="26" height="25" viewBox="0 0 26 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.0001 0.407471C15.0039 0.407471 16.9257 1.2035 18.3426 2.62044C19.7596 4.03738 20.5556 5.95917 20.5556 7.96303V9.22229H25.5927V11.7408H24.1231L23.1698 23.1787C23.1436 23.4933 23.0002 23.7867 22.7679 24.0006C22.5356 24.2144 22.2314 24.3332 21.9156 24.3334H4.08451C3.76874 24.3332 3.46457 24.2144 3.23227 24.0006C2.99997 23.7867 2.85649 23.4933 2.83029 23.1787L1.87577 11.7408H0.407471V9.22229H5.44451V7.96303C5.44451 5.95917 6.24054 4.03738 7.65748 2.62044C9.07442 1.2035 10.9962 0.407471 13.0001 0.407471ZM21.5958 11.7408H4.4031L5.24303 21.8149H20.7558L21.5958 11.7408ZM14.2593 14.2593V19.2964H11.7408V14.2593H14.2593ZM9.22229 14.2593V19.2964H6.70377V14.2593H9.22229ZM19.2964 14.2593V19.2964H16.7778V14.2593H19.2964ZM13.0001 2.92599C11.7078 2.92599 10.4649 3.42269 9.52852 4.31334C8.59216 5.204 8.03394 6.42049 7.96932 7.71117L7.96303 7.96303V9.22229H18.0371V7.96303C18.0371 6.67072 17.5404 5.42785 16.6497 4.49149C15.7591 3.55512 14.5426 2.9969 13.2519 2.93229L13.0001 2.92599Z"
                                    fill="#8E6DFB" />
                            </svg>
                        </div>
                        <h3>Stocks</h3>
                        <p>{{_trans('landing.Access to stock tracking, reordering, and inventory forecasting tools to
                            optimize inventory management processes')}}</p>


                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="26" height="25" viewBox="0 0 26 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.09585 0.577271L5.87644 2.35786C4.9396 3.29233 4.19665 4.40273 3.69032 5.62524C3.18398 6.84775 2.92423 8.15827 2.926 9.48149C2.926 12.2645 4.05304 14.783 5.87644 16.6051L4.09711 18.3845C2.92597 17.2167 1.99712 15.829 1.36393 14.3011C0.730746 12.7733 0.405703 11.1354 0.407481 9.48149C0.405372 7.8275 0.730136 6.1894 1.36311 4.66132C1.99608 3.13324 2.92479 1.7453 4.09585 0.577271ZM21.9043 0.577271C23.0754 1.7453 24.0041 3.13324 24.637 4.66132C25.27 6.1894 25.5948 7.8275 25.5927 9.48149C25.5948 11.1355 25.27 12.7736 24.637 14.3017C24.0041 15.8298 23.0754 17.2177 21.9043 18.3857L20.1237 16.6051C21.0606 15.6707 21.8035 14.5603 22.3098 13.3377C22.8162 12.1152 23.0759 10.8047 23.0741 9.48149C23.0741 6.69853 21.9471 4.18001 20.1237 2.35786L21.903 0.57853L21.9043 0.577271ZM7.65704 4.13846L9.43889 5.92031C8.9704 6.38739 8.59884 6.94246 8.34556 7.55361C8.09228 8.16476 7.96228 8.81994 7.96304 9.48149C7.96304 10.873 8.52718 12.1322 9.43889 13.0427L7.65704 14.8245C6.95441 14.1236 6.39723 13.2908 6.01753 12.3738C5.63783 11.4569 5.44309 10.4739 5.44452 9.48149C5.44452 7.3949 6.29074 5.50601 7.65704 4.13846ZM18.3431 4.13846C19.0457 4.83936 19.6029 5.67222 19.9826 6.58916C20.3623 7.5061 20.5571 8.48904 20.5556 9.48149C20.5571 10.4739 20.3623 11.4569 19.9826 12.3738C19.6029 13.2908 19.0457 14.1236 18.3431 14.8245L16.5613 13.0427C17.0297 12.5756 17.4013 12.0205 17.6546 11.4094C17.9079 10.7982 18.0379 10.143 18.0371 9.48149C18.0379 8.81994 17.9079 8.16476 17.6546 7.55361C17.4013 6.94246 17.0297 6.38739 16.5613 5.92031L18.3431 4.13846ZM13.0001 12C12.3321 12 11.6915 11.7347 11.2192 11.2624C10.7469 10.79 10.4816 10.1494 10.4816 9.48149C10.4816 8.81354 10.7469 8.17295 11.2192 7.70063C11.6915 7.22832 12.3321 6.96297 13.0001 6.96297C13.668 6.96297 14.3086 7.22832 14.7809 7.70063C15.2532 8.17295 15.5186 8.81354 15.5186 9.48149C15.5186 10.1494 15.2532 10.79 14.7809 11.2624C14.3086 11.7347 13.668 12 13.0001 12ZM11.7408 14.5185H14.2593V24.5926H11.7408V14.5185Z"
                                    fill="#F4A001" />
                            </svg>

                        </div>
                        <h3>Meetings</h3>
                        <p>{{_trans('landing.Enables scheduling of meetings with customers, prospects, and team members
                            to optimize the communication process')}}</p>


                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.0001 11.7408C14.6699 11.7408 16.2714 12.4042 17.4522 13.5849C18.633 14.7657 19.2964 16.3672 19.2964 18.0371V25.5927H16.7778V18.0371C16.7779 17.0735 16.4097 16.1463 15.7487 15.4452C15.0876 14.7441 14.1836 14.3221 13.2217 14.2656L13.0001 14.2593C12.0365 14.2593 11.1093 14.6274 10.4082 15.2885C9.70709 15.9496 9.28511 16.8535 9.22858 17.8155L9.22229 18.0371V25.5927H6.70377V18.0371C6.70377 16.3672 7.36712 14.7657 8.54791 13.5849C9.72869 12.4042 11.3302 11.7408 13.0001 11.7408ZM4.81488 15.5186C5.16621 15.5186 5.50747 15.5601 5.83488 15.637C5.61957 16.2779 5.49207 16.9451 5.45584 17.6203L5.44451 18.0371V18.1454C5.29976 18.0936 5.1492 18.0598 4.99621 18.0447L4.81488 18.0371C4.34532 18.0371 3.89261 18.212 3.54502 18.5277C3.19743 18.8434 2.97988 19.2773 2.9348 19.7447L2.92599 19.926V25.5927H0.407471V19.926C0.407471 18.7571 0.871821 17.636 1.69837 16.8095C2.52492 15.9829 3.64596 15.5186 4.81488 15.5186ZM21.1852 15.5186C22.3542 15.5186 23.4752 15.9829 24.3018 16.8095C25.1283 17.636 25.5927 18.7571 25.5927 19.926V25.5927H23.0741V19.926C23.0741 19.4564 22.8992 19.0037 22.5835 18.6561C22.2678 18.3085 21.834 18.091 21.3666 18.0459L21.1852 18.0371C20.9649 18.0371 20.7533 18.0749 20.5556 18.1441V18.0371C20.5556 17.1984 20.4196 16.3925 20.1665 15.6395C20.4927 15.5601 20.8339 15.5186 21.1852 15.5186ZM4.81488 7.96303C5.64982 7.96303 6.45056 8.2947 7.04095 8.8851C7.63135 9.47549 7.96303 10.2762 7.96303 11.1112C7.96303 11.9461 7.63135 12.7469 7.04095 13.3373C6.45056 13.9276 5.64982 14.2593 4.81488 14.2593C3.97994 14.2593 3.17919 13.9276 2.5888 13.3373C1.99841 12.7469 1.66673 11.9461 1.66673 11.1112C1.66673 10.2762 1.99841 9.47549 2.5888 8.8851C3.17919 8.2947 3.97994 7.96303 4.81488 7.96303ZM21.1852 7.96303C22.0202 7.96303 22.8209 8.2947 23.4113 8.8851C24.0017 9.47549 24.3334 10.2762 24.3334 11.1112C24.3334 11.9461 24.0017 12.7469 23.4113 13.3373C22.8209 13.9276 22.0202 14.2593 21.1852 14.2593C20.3503 14.2593 19.5496 13.9276 18.9592 13.3373C18.3688 12.7469 18.0371 11.9461 18.0371 11.1112C18.0371 10.2762 18.3688 9.47549 18.9592 8.8851C19.5496 8.2947 20.3503 7.96303 21.1852 7.96303ZM4.81488 10.4815C4.64789 10.4815 4.48774 10.5479 4.36966 10.666C4.25158 10.784 4.18525 10.9442 4.18525 11.1112C4.18525 11.2782 4.25158 11.4383 4.36966 11.5564C4.48774 11.6745 4.64789 11.7408 4.81488 11.7408C4.98187 11.7408 5.14201 11.6745 5.26009 11.5564C5.37817 11.4383 5.44451 11.2782 5.44451 11.1112C5.44451 10.9442 5.37817 10.784 5.26009 10.666C5.14201 10.5479 4.98187 10.4815 4.81488 10.4815ZM21.1852 10.4815C21.0183 10.4815 20.8581 10.5479 20.74 10.666C20.622 10.784 20.5556 10.9442 20.5556 11.1112C20.5556 11.2782 20.622 11.4383 20.74 11.5564C20.8581 11.6745 21.0183 11.7408 21.1852 11.7408C21.3522 11.7408 21.5124 11.6745 21.6305 11.5564C21.7485 11.4383 21.8149 11.2782 21.8149 11.1112C21.8149 10.9442 21.7485 10.784 21.6305 10.666C21.5124 10.5479 21.3522 10.4815 21.1852 10.4815ZM13.0001 0.407471C14.336 0.407471 15.6172 0.938157 16.5618 1.88278C17.5064 2.82741 18.0371 4.1086 18.0371 5.44451C18.0371 6.78041 17.5064 8.0616 16.5618 9.00623C15.6172 9.95086 14.336 10.4815 13.0001 10.4815C11.6642 10.4815 10.383 9.95086 9.43834 9.00623C8.49371 8.0616 7.96303 6.78041 7.96303 5.44451C7.96303 4.1086 8.49371 2.82741 9.43834 1.88278C10.383 0.938157 11.6642 0.407471 13.0001 0.407471ZM13.0001 2.92599C12.3321 2.92599 11.6915 3.19133 11.2192 3.66365C10.7469 4.13596 10.4815 4.77656 10.4815 5.44451C10.4815 6.11246 10.7469 6.75306 11.2192 7.22537C11.6915 7.69768 12.3321 7.96303 13.0001 7.96303C13.668 7.96303 14.3086 7.69768 14.7809 7.22537C15.2532 6.75306 15.5186 6.11246 15.5186 5.44451C15.5186 4.77656 15.2532 4.13596 14.7809 3.66365C14.3086 3.19133 13.668 2.92599 13.0001 2.92599Z"
                                    fill="#09B134" />
                            </svg>

                        </div>
                        <h3>Visit</h3>
                        <p>{{_trans('landing.This feature allows businesses to track, analyze and plan visits to
                            customers, prospects, or other locations')}}</p>

                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="26" height="24" viewBox="0 0 26 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.5214 3.18527H24.3334C24.6674 3.18527 24.9877 3.31794 25.2238 3.5541C25.46 3.79025 25.5927 4.11055 25.5927 4.44453V22.0742C25.5927 22.4081 25.46 22.7284 25.2238 22.9646C24.9877 23.2007 24.6674 23.3334 24.3334 23.3334H1.66673C1.33275 23.3334 1.01246 23.2007 0.776299 22.9646C0.540142 22.7284 0.407471 22.4081 0.407471 22.0742V1.92601C0.407471 1.59203 0.540142 1.27173 0.776299 1.03558C1.01246 0.79942 1.33275 0.666748 1.66673 0.666748H11.0029L13.5214 3.18527ZM2.92599 3.18527V20.8149H23.0741V5.70378H12.4787L9.96021 3.18527H2.92599ZM11.7408 8.2223H14.2593V18.2964H11.7408V8.2223ZM16.7778 12.0001H19.2964V18.2964H16.7778V12.0001ZM6.70377 14.5186H9.22229V18.2964H6.70377V14.5186Z"
                                    fill="#08BED2" />
                            </svg>

                        </div>
                        <h3>Attendances</h3>
                        <p>{{_trans('landing.View the attendance records of employees for a better understanding of
                            their presence and absences')}}</p>


                    </div>
                    <div class="feature_box d-flex align-items-center justify-content-center flex-column">
                        <div class="icon_box">
                            <svg width="26" height="24" viewBox="0 0 26 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.92599 3.18527V20.8149H23.0741V3.18527H2.92599ZM1.66673 0.666748H24.3334C24.6674 0.666748 24.9877 0.79942 25.2238 1.03558C25.46 1.27173 25.5927 1.59203 25.5927 1.92601V22.0742C25.5927 22.4081 25.46 22.7284 25.2238 22.9646C24.9877 23.2007 24.6674 23.3334 24.3334 23.3334H1.66673C1.33275 23.3334 1.01246 23.2007 0.776299 22.9646C0.540142 22.7284 0.407471 22.4081 0.407471 22.0742V1.92601C0.407471 1.59203 0.540142 1.27173 0.776299 1.03558C1.01246 0.79942 1.33275 0.666748 1.66673 0.666748ZM5.44451 5.70378H7.96303V8.2223H5.44451V5.70378ZM5.44451 10.7408H7.96303V13.2593H5.44451V10.7408ZM5.44451 15.7779H20.5556V18.2964H5.44451V15.7779ZM11.7408 10.7408H14.2593V13.2593H11.7408V10.7408ZM11.7408 5.70378H14.2593V8.2223H11.7408V5.70378ZM18.0371 5.70378H20.5556V8.2223H18.0371V5.70378ZM18.0371 10.7408H20.5556V13.2593H18.0371V10.7408Z"
                                    fill="#F1657A" />
                            </svg>


                        </div>
                        <h3>Leaves</h3>
                        <p>{{_trans('landing.Ability to view and approve the number of leaves a certain employee has
                            taken and requested for')}}</p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ TASKS::END  -->

<!-- FEATURE::START  -->
<section class="essential_feature theme_bg_1 section_padding">
    <div class="container-fluid max_1450">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9">
                <div class="section__title text-center mb_30">
                    <h3 class="text-white max_480 mx-auto">Essential Features of The CRM Software </h3>
                    <p class="text-white">These important features help in effective management of clients, both
                        existing and prospective </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="nav feature_tabs justify-content-center" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="Projects-tab" data-bs-toggle="tab"
                            data-bs-target="#Projects" type="button" role="tab" aria-controls="Projects"
                            aria-selected="true">Projects</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Clients-tab" data-bs-toggle="tab" data-bs-target="#Clients"
                            type="button" role="tab" aria-controls="Clients" aria-selected="false">Clients</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Sales-tab" data-bs-toggle="tab" data-bs-target="#Sales"
                            type="button" role="tab" aria-controls="Sales" aria-selected="false">Sales</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Income-tab" data-bs-toggle="tab" data-bs-target="#Income"
                            type="button" role="tab" aria-controls="Income" aria-selected="false">Income &
                            Expenses</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Income-tab" data-bs-toggle="tab" data-bs-target="#Management"
                            type="button" role="tab" aria-controls="Management" aria-selected="false">Tasks
                            Management</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Stocks-tab" data-bs-toggle="tab" data-bs-target="#Stocks"
                            type="button" role="tab" aria-controls="Management" aria-selected="false">Stocks</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="HR-tab" data-bs-toggle="tab" data-bs-target="#HR" type="button"
                            role="tab" aria-controls="HR" aria-selected="false">HR</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Meetings-tab" data-bs-toggle="tab" data-bs-target="#Meetings"
                            type="button" role="tab" aria-controls="Meetings" aria-selected="false">Meetings</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Attendance-tab" data-bs-toggle="tab" data-bs-target="#Attendance"
                            type="button" role="tab" aria-controls="Attendance"
                            aria-selected="false">Attendance</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="Projects" role="tabpanel" aria-labelledby="Projects-tab">
                        <!-- content ::start  -->
                        <div class="feature_img_wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/5-projects-add-l.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/5-projects-add-d.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content ::end  -->
                    </div>
                    <div class="tab-pane fade" id="Clients" role="tabpanel" aria-labelledby="Clients-tab">
                        <!-- content ::start  -->
                        <div class="feature_img_wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/3-client-add-l.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/3-client-add-d.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content ::end  -->
                    </div>
                    <div class="tab-pane fade" id="Sales" role="tabpanel" aria-labelledby="Sales-tab">
                        <!-- content ::start  -->
                        <div class="feature_img_wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/9-sale-add-l.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/9-sale-add-d.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content ::end  -->
                    </div>
                    <div class="tab-pane fade" id="Income" role="tabpanel" aria-labelledby="Income-tab">
                        <!-- content ::start  -->
                        <div class="feature_img_wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/29-deposit-l.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/29-deposit-d.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content ::end  -->
                    </div>
                    <div class="tab-pane fade" id="Management" role="tabpanel" aria-labelledby="Management-tab">
                        <!-- content ::start  -->
                        <div class="feature_img_wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/7-task-add-l.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/7-task-add-d.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content ::end  -->
                    </div>
                    <div class="tab-pane fade" id="Stocks" role="tabpanel" aria-labelledby="Stocks-tab">
                        <!-- content ::start  -->
                        <div class="feature_img_wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/30-stock-l.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/30-stock-d.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content ::end  -->
                    </div>
                    <div class="tab-pane fade" id="HR" role="tabpanel" aria-labelledby="HR-tab">
                        <!-- content ::start  -->
                        <div class="feature_img_wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/14-configuration-l.png') }}"
                                            alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/14-configuration-d.png') }}"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content ::end  -->
                    </div>
                    <div class="tab-pane fade" id="Meetings" role="tabpanel" aria-labelledby="Meetings-tab">
                        <!-- content ::start  -->
                        <div class="feature_img_wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/31-meeting-l.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/31-meeting-d.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content ::end  -->
                    </div>
                    <div class="tab-pane fade" id="Attendance" role="tabpanel" aria-labelledby="Attendance-tab">
                        <!-- content ::start  -->
                        <div class="feature_img_wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/32-attendance-l.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="feature_img mb_30">
                                        <img class="img-fluid feature-image"
                                            src="{{ url('/public/assets/features_tab/32-attendance-d.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content ::end  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ FEATURE::START  -->

<!-- WHY_CHOSE_US::START  -->
<section class="section_padding">
    <div class="why-choose-section p-0" id="choose-us">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="section__title text-center mb_30">
                        <h3>Why Choose Us?</h3>
                    </div>
                </div>
            </div>
            <div class="choose-us-list">
                <div class="row g-5">
                    <div class="col-md-3 col-6">
                        <div class="items-card">
                            <div class="items-icon">
                                <img src="{{ url('/public/assets/Why choose us/1.png') }}" alt="">
                            </div>
                            <div class="items-body">
                                <h6>24/7 support</h6>
                                <p>Chat with Us</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="items-card">
                            <div class="items-icon">
                                <img src="{{ url('/public/assets/Why choose us/2.png') }}" alt="">
                            </div>
                            <div class="items-body">
                                <h6>up to date technology</h6>
                                <p>We’ve Got You Covered</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="items-card">
                            <div class="items-icon">
                                <img src="{{ url('/public/assets/Why choose us/3.png') }}" alt="">
                            </div>
                            <div class="items-body">
                                <h6>10+ years experienced</h6>
                                <p>Team of Dedicated Professionals</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="items-card">
                            <div class="items-icon">
                                <img src="{{ url('/public/assets/Why choose us/4.png') }}" alt="">
                            </div>
                            <div class="items-body">
                                <h6>Simple minded Operation</h6>
                                <p>Easy to Operate</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ WHY_CHOSE_US::END  -->

<!-- TESTIMONIAL::START   -->
<div class="testimonial-section section_padding">
    <div class="container">
        <div class="section__title text-center mb_30">
            <h3>What Our Clients Say?</h3>
            <p>Take a look at what some of our many Clients had to say regarding our services!</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="testimonial-slider">
                    <!-- testimonial_card  -->
                    <div class="testimonial_card">
                        <div class="quote">
                            <img src="{{ url('/public/assets/quote.png') }}" alt="">
                        </div>
                        <div class="testimonial_body">
                            <div class="testimonial_comment">
                                <p>
                                    I've been using your CRM software for a few months now and I have to say I'm really
                                    impressed. It's made it so much easier to manage my customer interactions and keep
                                    track of my sales pipeline. The interface is intuitive and user-friendly, and the
                                    automation features have saved me a lot of time. Thanks for creating such a great
                                    product!
                                </p>
                            </div>
                        </div>
                        <div class="testimonial_footer">
                            <h6 class="mb-0">Mikaela Simpson </h6>
                            <p>VP of Product</p>
                        </div>
                    </div>
                    <!-- testimonial_card  -->
                    <div class="testimonial_card">
                        <div class="quote">
                            <img src="{{ url('/public/assets/quote.png') }}" alt="">
                        </div>
                        <div class="testimonial_body">
                            <div class="testimonial_comment">
                                <p>
                                    Your CRM software has exceeded our expectations in every way. The ease of use, the
                                    robust feature set, and the customer support have all been outstanding. We've seen a
                                    significant improvement in our sales efficiency and customer retention since
                                    implementing the software. Thank you for creating such a fantastic product!
                                </p>
                            </div>
                        </div>
                        <div class="testimonial_footer">
                            <h6 class="mb-0">Stephanie Peers</h6>
                            <p>Relationship Management Specialist</p>
                        </div>
                    </div>
                    <!-- testimonial_card  -->
                    <div class="testimonial_card">
                        <div class="quote">
                            <img src="{{ url('/public/assets/quote.png') }}" alt="">
                        </div>
                        <div class="testimonial_body">
                            <div class="testimonial_comment">
                                <p>
                                    I've used several different CRM systems in the past, but yours is by far the best.
                                    The customization options are extensive, which has allowed us to tailor the software
                                    to our specific needs. The analytics features have also been extremely helpful in
                                    identifying areas where we can improve our customer engagement. Overall, we're
                                    really happy with the software and would recommend it to anyone looking for a
                                    powerful CRM solution.
                                </p>
                            </div>
                        </div>
                        <div class="testimonial_footer">
                            <h6 class="mb-0">Tahity M. Abdullah</h6>
                            <p>Entrepreneur</p>
                        </div>
                    </div>
                    <!-- testimonial_card  -->
                    <div class="testimonial_card">
                        <div class="quote">
                            <img src="{{ url('/public/assets/quote.png') }}" alt="">
                        </div>
                        <div class="testimonial_body">
                            <div class="testimonial_comment">
                                <p>
                                    Your CRM software has been a game changer for our sales team. We're now able to
                                    track our leads and opportunities more effectively, and the reporting features have
                                    helped us identify areas where we can improve our sales process. The support team
                                    has also been incredibly helpful in getting us up and running and answering any
                                    questions we've had. Highly recommend this software!
                                </p>
                            </div>
                        </div>
                        <div class="testimonial_footer">
                            <h6 class="mb-0">Levy Henderson</h6>
                        </div>
                    </div>
                    <!-- testimonial_card  -->
                    <div class="testimonial_card">
                        <div class="quote">
                            <img src="{{ url('/public/assets/quote.png') }}" alt="">
                        </div>
                        <div class="testimonial_body">
                            <div class="testimonial_comment">
                                <p>
                                    We've been using your CRM software for a couple months now and it's been
                                    instrumental in helping us grow our business. The automation features have saved us
                                    countless hours, and the reporting features have allowed us to make data-driven
                                    decisions. We appreciate the ongoing updates and improvements to the software, and
                                    the support team has been fantastic in addressing any issues that have come up.
                                    Highly recommend this software to anyone looking for a top-notch CRM solution.
                                </p>
                            </div>
                        </div>
                        <div class="testimonial_footer">
                            <h6 class="mb-0">Mike Tyler</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- TESTIMONIAL::END     -->

<!-- CTA::START  -->
<div class="bg-download-white p-0">
    <div class="download-app-section" id="download-app">
        <div class="shape-img">
            {{-- <img src="{{url('/public/Promotional_Banner.png')}}" alt=""> --}}
        </div>
        <div class="container">
            <div class="col-md-12">
                <div class="download-app-content">

                    <h6>Download Our</h6>
                    <h2>Mobile App</h2>
                    <ul class="list-style-none ps-0">
                        <li class="mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="app-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="app-details">
                                    <p class="mb-0">Projects / Task Management</p>
                                </div>

                            </div>


                        </li>
                        <li class="mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="app-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="app-details">
                                    <p class="mb-0">Sales / Employee / income / Accounts</p>
                                </div>

                            </div>


                        </li>
                        <li class="mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="app-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="app-details">
                                    <p class="mb-0">Meeting / Appoinment / Support / Notice</p>
                                </div>

                            </div>


                        </li>
                    </ul>
                    <div class="store-button">
                        <a href="#">
                            <img src="{{url('/public/assets/app.png')}}" alt="">
                            <div class="app-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="rating-count">(4.9)</span>
                            </div>
                        </a>
                        <a href="#">
                            <img src="{{url('/public/assets/play.png')}}" alt="">
                            <div class="app-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="rating-count">(4.9)</span>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!--/ CTA::END  -->

<!-- FAQ::START  -->
<div class="bg-white">
    <div class="faq-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="section__title mb_30">
                        <h3>Any questions? <br> We got you.</h3>
                        <p class="faq_content_text">Our dedicated and relentless support team is always available to
                            assist you. For any queries, concerns or confusion, you can reach us without any hesitation
                            and get your issues resolved.Email us at <a href="#">onesttechbd@gmail.com</a> or call us at
                            <a href="#">+880 18100 22230 </a> right away.
                        </p>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="theme_accordian">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    What is CRM?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        Gartner describe CRM as <br>
                                        “A business strategy whose outcomes optimize profitability, revenue and customer
                                        satisfaction…CRM technologies should enable greater customer insight, increased
                                        customer access, more effective customer interactions, and integration
                                        throughout all customer channels and back-office enterprise functions”.

                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Why are companies interested in CRM?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        Companies are interested in CRM because they recognise that the customer is
                                        their primary strategic asset and thus seek to better understand the behavior
                                        and needs of that customer and therefore enhance their relationship.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    How can CRM software help my organization?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        CRM software by itself cannot help your organization improve performance. CRM is
                                        a philosophy and a business strategy focused on acquiring, developing and
                                        retaining the right customers. There is nothing new or ‘special’ about CRM. It
                                        is simply the current term for doing good business, well, and practicing ‘best
                                        practice’ business processes.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    What are the business benefits of investing in CRM – or in other words what will CRM
                                    do for me?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        Actual returns vary from one company to the next, but common benefits we have
                                        seen our customers achieve are:

                                    <ul>
                                        <li>Higher customer retention</li>
                                        <li>Improved sales productivity</li>
                                        <li>Improved efficiency between different departments</li>
                                        <li>Shorter sales cycles</li>
                                        <li>Increased profitability</li>
                                        <li>Lower marketing costs</li>
                                        <li>Reduction in “lost” sales leads</li>
                                        <li>Increased customer service response times</li>
                                    </ul>

                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    How much will it cost?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        As there is not one fixed price for all CRM solutions, unfortunately there is
                                        not one fixed answer. However the things that affect how much your CRM will cost
                                        largely boil down to, which software you choose, who will install it and how
                                        many licenses or people will need to be using it, together with how much
                                        customisation and training you require.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSix">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                    How can we integrate with legacy systems?
                                </button>
                            </h2>
                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        Often companies who are thinking about implementing a new CRM system already
                                        have a number of financial and contact management databases or spreadsheets that
                                        they are already using. In short, many financial systems may have an associated
                                        CRM system that will integrate with that package. Most often, we find that the
                                        people who come to us who are looking for a new CRM system, are usually
                                        expanding (employing extra staff), or have outgrown their existing system. In
                                        these cases, it is most likely that we would recommend taking the information
                                        out of the old system and putting it into the new one, most of the time this can
                                        be done by the CRM consultancy you are working with.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSeven">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                    Will my sales team be able to download our customer database, and leave the company!
                                </button>
                            </h2>
                            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        Many CRM systems have security profiles that enable businesses to prevent their
                                        staff from performing certain tasks. For example, if you were worried that a
                                        disgruntled employee might want to delete the company database, deletion rights
                                        can be removed. In most CRM systems there is also the option to be able to
                                        export data into an excel spreadsheet, again if this was something you wanted to
                                        stop, then this part of the system can be turned off for certain users. If this
                                        is something you are concerned about, remember to mention it to the company whom
                                        you are purchasing your CRM from.

                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingEight">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                    Will my remote/home based workers be able to access the system?
                                </button>
                            </h2>
                            <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        The answer in short is yes, most CRM systems include the option to cater for
                                        remote users. However, there are four main ways to do this:
                                    <ul>
                                        <li>Remote web access – this enables your remote worker to go onto the internet
                                            and login to your live CRM system from home.</li>
                                        <li>Remote Installation – this enables remote workers to have a copy of the
                                            software on their laptop, so they can update your CRM database regardless of
                                            whether they have internet access. When they do have access to the internet
                                            and your server, they can then synchronize their data.</li>
                                        <li>Mobile CRM – this enables remote workers to access and update your CRM
                                            system, via a PDA or mobile phone.</li>
                                        <li>VPN – Virtual Private Network – where users connect to the work server, via
                                            the internet from home.</li>
                                    </ul>
                                    Many CRM systems will come with a remote working option, however you will need to
                                    decide which way you would prefer your remote workers to be able to update the CRM
                                    system and see which CRM solution provides your chosen method. Even when your chosen
                                    method isn’t part of the CRM software price, there are often complementary software
                                    solutions that will enable you to do this.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingNine">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                    How long will it take?
                                </button>
                            </h2>
                            <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        Things that affect the amount of time a CRM installation will take include:
                                        number of records, amount of configuration, number of users and number of
                                        laptops. So if you have very few or no records that need importing, are happy
                                        with the fields that come, ‘out of the box’ and have just a couple of users and
                                        no laptops – it could take as little as a day. But…!
                                        To reduce the amount of time a CRM installation and project will take, if you
                                        have a list of things that you want to be able to do, a list of fields that you
                                        want to be filled in and have checked that your existing hardware meets the
                                        minimum requirements for the software – this will all help with reducing the
                                        amount of time setting up your system.

                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTen">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                    What’s the difference between the current CRM systems available today?
                                </button>
                            </h2>
                            <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>
                                        In summary, when you come to decide which CRM software is best suited to your
                                        business needs, these are some of the things you will need to be checking:

                                    <ul>
                                        <li>Is the relationship between our company and our clients, B2B or B2C?</li>
                                        <li>How do my staff need to be able to access and update the system (i.e. from
                                            home, on the road, in the office)</li>
                                        <li>What is the main objective of having the system? (e.g. sales lead
                                            management, marketing campaigns)</li>
                                        <li>Which teams need to be able to use the system; sales, marketing, customer
                                            service, operational etc…</li>
                                        <li>Does it need to link to other systems?</li>
                                        <li>How will our emails link to the CRM system</li>
                                        <li>Do we need the phones to link to the CRM</li>
                                    </ul>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection