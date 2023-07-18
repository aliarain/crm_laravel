@extends('frontend.layouts.master')
@php
$metaInformation = DB::table('meta_information')
    ->where('type', 'register')
    ->first();
$data['metaInformation'] = $metaInformation;
@endphp
@section('title', _trans('register'))
@section('meta_title', @$data['metaInformation']->meta_title)
@section('meta_description', @$data['metaInformation']->meta_description)
@section('meta_keyword', @$data['metaInformation']->meta_keywords)
@section('meta_image', uploaded_asset(@$data['metaInformation']->image_id))


@section('content')
    <!-- BREADCRUMB AREA START -->
    <div class="custom-breadcrumb p-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 align-self-center">
                    <div class="row align-items-center">

                        <div class="col-lg-6 header-content">
                            <h2>{{ _trans('auth.register_hero_title') }}</h2>


                        </div>

                        <div class="col-lg-6">
                            <div class="slide-item-img with-parallelogram breadcrumb-bg">
                                <div class="with-parallelogram-1 reg-breadcrumb">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->


    <!-- CONTACT MESSAGE AREA START -->
    <div class="ltn__contact-message-area  pt-50">
        <div class="container">
            <div class="ltn__form-box contact-form-box box-shadow card-bg-new ">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-12 pt-30 pb-30">
                            <h2 class="text-center">
                                {{ _trans('auth.regiser_form') }}
                            </h2>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="reg-as-btns">

                                    <label class="mr-4 radiobtn d-flex align-items-center">
                                        <input type="radio" id="regAsDriverBtn" name="key" value="value" checked />
                                        <span class="fs-18">{{ _trans('auth.have_car') }}</span>
                                    </label>
                                    <label class="radiobtn d-flex align-items-center">
                                        <input type="radio" id="registerAsBrandAmbassadorBtn" name="key" value="value" />
                                        <span class="fs-18">{{ _trans('auth.brand_ambassador') }}</span>
                                    </label>


                                </div>

                            </div>
                        </div>

                        <form class="earn-today-form " id="registerAsDriver" action="" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <div class="mb-3">
                                        <input type="text" name="name" placeholder="{{ _trans('auth.reg_form_name') }}"
                                            class="mb-1">
                                        <small class="driver_name text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-8 mx-auto">
                                    <div class="mb-3">
                                        <input type="email" name="email" placeholder="{{ _trans('auth.reg_form_mail') }}"
                                            class="mb-1">
                                        <small class="driver_email text-danger"></small>
                                    </div>
                                </div>

                                <div class="col-md-8 mx-auto">
                                    <div class="mb-3">
                                        <input type="number" name="phone" placeholder="{{ _trans('auth.reg_form_num') }}"
                                            class="mb-1">
                                        <small class="driver_phone text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-8 mx-auto">
                                    <div class="ps-relative mb-3">
                                        <input type="password" name="password"
                                            placeholder="{{ _trans('auth.reg_form_pass') }}" class="mb-1">
                                        <span class="show-hide-password">
                                            <img class="ps-absolute eye-icon show-password"
                                                src="{{ url('public/frontend/img/body/eye.png') }}" alt="">
                                            <img class="ps-absolute eye-icon hide-password "
                                                src="{{ url('public/frontend/img/body/eye-slash.png') }}" alt="">
                                        </span>
                                        <small class="driver_password text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-8 mx-auto">
                                    <div class="ps-relative mb-3">
                                        <input type="password" name="password_confirmation"
                                            placeholder="{{ _trans('auth.reg_form_confirm_pass') }}"
                                            class="mb-1">
                                        <span class="show-hide-password">
                                            <img class="ps-absolute eye-icon show-password"
                                                src="{{ url('public/frontend/img/body/eye.png') }}" alt="">
                                            <img class="ps-absolute eye-icon hide-password"
                                                src="{{ url('public/frontend/img/body/eye-slash.png') }}" alt="">
                                        </span>
                                        <small class="driver_confirm_password text-danger"></small>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-danger text-center fs-18 driver_reg_btn" type="submit"
                                        name="user" value="driver">{{ _trans('auth.reg_crt_acc') }}</button>
                                    <div class="d-flex align-items-center  justify-content-center mt-20">
                                        <div class="left-border"></div>
                                        <div class="middle-content ml-2 mr-2 ">{{ _trans('auth.reg_or') }}</div>
                                        <div class="right-border"></div>
                                    </div>

                                    <h4 class="mt-20">{{ _trans('auth.reg_with') }}</h4>
                                    <div class="d-flex justify-content-center">

                                        <a href="{{ route('social.login', 'google') }}"> <img
                                                src="{{ url('public/frontend/img/body/google.png') }}" alt=""></a>
                                        <a href="{{ route('social.login', 'facebook') }}"> <img
                                                src="{{ url('public/frontend/img/body/fb.png') }}" alt=""></a>
                                    </div>
                                    <div class="checkbox text-capitalize">

                                        <span class="fs-16">{{ _trans('auth.reg_havent_acc') }}? <a
                                                href="{{ route('adminLogin') }}"> <span
                                                    class="red-color fs-16">{{ _trans('auth.reg_login') }}</span></a></span>

                                    </div>
                                </div>

                            </div>
                            <p class="form-messege mb-0 mt-20"></p>

                        </form>
                        <form class="earn-today-form " id="registerAsBrandAmbassador" action="" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <div class="mb-3">
                                        <input type="text" name="name" placeholder="{{ _trans('auth.reg_form_name') }}"
                                            class="mb-1">
                                        <small class="client_name text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-8 mx-auto">
                                    <div class="mb-3">
                                        <input type="email" name="email" placeholder="{{ _trans('auth.reg_form_mail') }}"
                                            class="mb-1">
                                        <small class="client_email text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-8 mx-auto">
                                    <div class="mb-3">
                                        <input type="number" name="phone" placeholder="{{ _trans('auth.reg_form_num') }}"
                                            class="mb-1">
                                        <small class="client_phone text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-8 mx-auto">
                                    <div class="mb-3">
                                        <input type="text" name="company_name"
                                            placeholder="{{ _trans('auth.reg_form_co_name') }}" class="mb-1">
                                        <small class="client_company_name text-danger"></small>
                                    </div>
                                </div>

                                <div class="col-md-8 mx-auto mb-3">
                                    <div class="mb-3">
                                        <select name="industry" class="nice-select mb-1">
                                            <option value="" disabled selected>{{ _trans('auth.reg_form_select_type') }}
                                                *
                                            </option>
                                            <option value="fashion">{{_trans('auth.Fashion')}}</option>
                                            <option value="finance">{{_trans('auth.Finance')}}</option>
                                            <option value="hospital">{{_trans('auth.Hospital')}}</option>
                                            <option value="agriculture_industry">{{_trans('auth.Agriculture_Industry')}}</option>
                                            <option value="construction_industry">{{_trans('auth.Construction')}}</option>
                                            <option value="entertainment_industry">{{_trans('auth.Entertainment_Industry')}}</option>
                                            <option value="technology_industry">{{_trans('auth.Technology_Industry')}}</option>
                                        </select>
                                        <small class="client_industry text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-8 mx-auto">
                                    <div class="mb-3">
                                        <input type="text" name="company_brand"
                                            placeholder="{{ _trans('auth.reg_form_co_brand') }}*" class="mb-1">
                                        <small class="client_brand text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-8 mx-auto mb-3">
                                    <div class="mb-3">
                                        <select name="company_size" class="nice-select mb-1"
                                            value="{{ old('company_size') }}">
                                            <option value="">{{ _trans('auth.reg_company_size') }}*</option>
                                            <option value="big">{{ _trans('auth.big') }} </option>
                                            <option value="small">{{ _trans('auth.small') }}</option>
                                            <option value="medium">{{ _trans('auth.medium') }}</option>
                                        </select>
                                        <small class="client_company_size text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-8 mx-auto">
                                    <div class="ps-relative mb-3">
                                        <input type="password" name="password"
                                            placeholder="{{ _trans('auth.reg_form_pass') }}" class="mb-1">
                                        <span class="show-hide-password">
                                            <img class="ps-absolute eye-icon show-password"
                                                src="{{ url('public/frontend/img/body/eye.png') }}" alt="">
                                            <img class="ps-absolute eye-icon hide-password"
                                                src="{{ url('public/frontend/img/body/eye-slash.png') }}" alt="">
                                        </span>
                                        <small class="client_password text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-8 mx-auto">
                                    <div class="ps-relative mb-3">
                                        <input type="password" name="password_confirmation"
                                            placeholder="{{ _trans('auth.reg_form_confirm_pass') }}"
                                            class="mb-1">
                                        <span class="show-hide-password">
                                            <img class="ps-absolute eye-icon show-password"
                                                src="{{ url('public/frontend/img/body/eye.png') }}" alt="">
                                            <img class="ps-absolute eye-icon hide-password"
                                                src="{{ url('public/frontend/img/body/eye-slash.png') }}" alt="">
                                        </span>
                                        <small class="client_confirm_password text-danger"></small>
                                    </div>
                                </div>
                            </div>


                            <div class="text-center">
                                <button
                                    class="btn btn-danger text-center fs-18 client_reg_btn">{{ _trans('auth.reg_crt_acc') }}</button>
                                <div class="d-flex align-items-center  justify-content-center mt-20">
                                    <div class="left-border"></div>
                                    <div class="middle-content ml-2 mr-2 ">{{ _trans('auth.reg_or') }}</div>
                                    <div class="right-border"></div>
                                </div>

                                <h4 class="mt-20">{{ _trans('auth.reg_with') }}</h4>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('social.login', 'google') }}"> <img
                                            src="{{ url('public/frontend/img/body/google.png') }}" alt=""></a>
                                    <a href="{{ route('social.login', 'facebook') }}"> <img
                                            src="{{ url('public/frontend/img/body/fb.png') }}" alt=""></a>
                                </div>
                                <div class="checkbox">


                                    <span class="fs-16 text-capitalize">{{ _trans('auth.reg_havent_acc') }}? <a
                                            href="{{ route('adminLogin') }}"> <span
                                                class="red-color fs-16">{{ _trans('auth.reg_login') }}</span></a></span>

                                </div>

                            </div>
                            <p class="form-messege mb-0 mt-20"></p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('public/frontend/assets/loginReg.js') }}"></script>
@endsection
