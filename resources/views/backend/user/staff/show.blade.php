@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
@include('backend.partials.staff_navbar')
    <!-- profile content start -->
    <div class="profile-content">
        <!-- profile body start -->
        <div class="profile-body profile-body-cus">
            <h2 class="title">{{ _trans('My Profile') }}</h2>

            <!-- profile body nav start -->
            <nav>
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" id="nav-personal-tab" data-bs-toggle="tab" data-bs-target="#nav-personal"
                            role="tab" aria-controls="nav-personal" aria-selected="true">{{ _trans('user.Personal') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-official-tab" data-bs-toggle="tab" data-bs-target="#nav-official"
                            role="tab" aria-controls="nav-official" aria-selected="true">{{ _trans('user.Official') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-financial-tab" data-bs-toggle="tab" data-bs-target="#nav-financial"
                            role="tab" aria-controls="nav-financial" aria-selected="true">{{ _trans('user.Financial') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-emergency-tab" data-bs-toggle="tab" data-bs-target="#nav-emergency"
                            role="tab" aria-controls="nav-emergency" aria-selected="true">{{ _trans('user.Emergency') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-security-tab" data-bs-toggle="tab" data-bs-target="#nav-security"
                            role="tab" aria-controls="nav-security" aria-selected="true">{{ _trans('user.Security') }}</a>
                    </li>
                </ul>
            </nav>

            <!-- profile body nav end -->
            <!-- profile body form start -->
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade active show" id="nav-personal" role="tabpanel" aria-labelledby="nav-personal-tab">
                    <div class="profile-body-form">
                        <div class="form-item border-bottom-0 pb-0">
                            <div class="image-box">
                                <img class="img-fluid rounded-circle"
                                    src="{{ url('/public/assets/images/profile/personal-avatar.jpeg') }}"
                                    alt="profile avatar" />
                                <span class="icon"><i class="las la-user-edit"></i></i></span>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Name') }}</h2>
                                    <p class="paragraph"> {{ $data['show']->original['data']['name'] ?? 'N/A' }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseName" aria-expanded="false" aria-controls="collapseName">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseName">
                                <div class="form-box">
                                    <form action="{{ route('user.update.profile', [$data['id'], $data['slug']]) }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="text" hidden name="user_id" value="{{ $data['id'] }}">
                                        <input name="name" type="text" class="form-control"
                                            placeholder="Robert Downey JR." />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.E-mail Address') }}</h2>
                                    <p class="paragraph"> {{ $data['show']->original['data']['email'] ?? 'N/A' }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseEmail" aria-expanded="false" aria-controls="collapseEmail">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseEmail">
                                <div class="form-box">
                                    <form>
                                        <input name="email" type="email" class="form-control"
                                            placeholder="{{ _trans('user.Email') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Date of Birth') }}</h2>
                                    <p class="paragraph">{{ $data['show']->original['data']['birth_date'] ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseDateOfBirth" aria-expanded="false"
                                        aria-controls="collapseDateOfBirth">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseDateOfBirth">
                                <div class="form-box">
                                    <form>
                                        <input name="date_of_birth" type="date" class="form-control" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Gender') }}</h2>
                                    <p class="paragraph">{{ $data['show']->original['data']['gender'] ?? 'N/A' }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseGender" aria-expanded="false"
                                        aria-controls="collapseGender">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseGender">
                                <div class="form-box">
                                    <form>
                                        <input name="gender" type="text" class="form-control" placeholder="{{ _trans('user.Male') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Phone Number') }}</h2>
                                    <p class="paragraph">{{ $data['show']->original['data']['phone'] ?? 'N/A' }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapsePhone" aria-expanded="false"
                                        aria-controls="collapsePhone">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapsePhone">
                                <div class="form-box">
                                    <form>
                                        <input name="phone" type="text" class="form-control"
                                            placeholder="{{ _trans('user.+880 (249) 897 632') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Address Line') }}</h2>
                                    <p class="paragraph">
                                        {{ $data['show']->original['data']['address'] ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseAddress" aria-expanded="false"
                                        aria-controls="collapseAddress">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseAddress">
                                <div class="form-box">
                                    <form>
                                        <input name="address" type="text" class="form-control"
                                            placeholder="{{ _trans('user.78/2 Razia Tower, Manhattan, NewYork - 69006') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Nationality') }}</h2>
                                    <p class="paragraph">{{ $data['show']->original['data']['nationality'] ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseNationality" aria-expanded="false"
                                        aria-controls="collapseNationality">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseNationality">
                                <div class="form-box">
                                    <form>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected>{{ _trans('user.Select a Country') }}</option>
                                            <option value="1">{{ _trans('user.Bangladesh') }}</option>
                                            <option value="2">{{ _trans('user.Iceland') }}</option>
                                            <option value="3">{{ _trans('user.Moldova') }}</option>
                                        </select>
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.NID Number') }}</h2>
                                    <p class="paragraph">
                                        {{ $data['show']->original['data']['nid_card_number'] ?? 'N/A' }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseNid" aria-expanded="false" aria-controls="collapseNid">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseNid">
                                <div class="form-box">
                                    <form>
                                        <input name="nid" type="text" class="form-control"
                                            placeholder="{{ _trans('user.963 - 687 - 596') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Passport') }}</h2>
                                    <p class="paragraph">
                                        {{ $data['show']->original['data']['passport_number'] ?? 'N/A' }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapsePassport" aria-expanded="false"
                                        aria-controls="collapsePassport">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapsePassport">
                                <div class="form-box">
                                    <form>
                                        <input name="passport" type="text" class="form-control"
                                            placeholder="{{ _trans('user.Q963687596') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Blood Group') }}</h2>
                                    <p class="paragraph">
                                        {{ $data['show']->original['data']['blood_group'] ?? 'N/A' }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBloodGroup" aria-expanded="false"
                                        aria-controls="collapseBloodGroup">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseBloodGroup">
                                <div class="form-box">
                                    <form>
                                        <input name="blood_group" type="text" class="form-control"
                                            placeholder="{{ _trans('user.A+ (Posative)') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-official" role="tabpanel" aria-labelledby="nav-official-tab">
                    <div class="profile-body-form">
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Name') }}</h2>
                                    <p class="paragraph">{{ _trans('user.Robert Downey JR.') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseName" aria-expanded="false"
                                        aria-controls="collapseName">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseName">
                                <div class="form-box">
                                    <form>
                                        <input name="name" type="text" class="form-control"
                                            placeholder="{{ _trans('user.Robert Downey JR.') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.E-mail Address') }}</h2>
                                    <p class="paragraph">{{ _trans('user.Email Address') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseEmail" aria-expanded="false"
                                        aria-controls="collapseEmail">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseEmail">
                                <div class="form-box">
                                    <form>
                                        <input name="email" type="email" class="form-control"
                                            placeholder="{{ _trans('user.Email') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Department') }}</h2>
                                    <p class="paragraph">{{ _trans('user.Design') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseDepartment" aria-expanded="false"
                                        aria-controls="collapseDepartment">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseDepartment">
                                <div class="form-box">
                                    <form>
                                        <input name="department" type="text" class="form-control"
                                            placeholder="{{ _trans('user.Design') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Designation') }}</h2>
                                    <p class="paragraph">{{ _trans('user.Sr. UI/UX Designer') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseDesignation" aria-expanded="false"
                                        aria-controls="collapseDesignation">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseDesignation">
                                <div class="form-box">
                                    <form>
                                        <input name="designation" type="text" class="form-control"
                                            placeholder="{{ _trans('user.Sr. UI/UX Designer') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Joing Date') }}</h2>
                                    <p class="paragraph">{{ _trans('user.23 - 06 - 1995') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseJoingDate" aria-expanded="false"
                                        aria-controls="collapseJoingDate">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseJoingDate">
                                <div class="form-box">
                                    <form>
                                        <input name="joing_date" type="date" class="form-control" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Employee Type') }}</h2>
                                    <p class="paragraph">{{ _trans('user.On Probation') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseEmployeeType" aria-expanded="false"
                                        aria-controls="collapseEmployeeType">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseEmployeeType">
                                <div class="form-box">
                                    <form>
                                        <input name="employee_type" type="text" class="form-control"
                                            placeholder="{{ _trans('user.On Probation') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Employee ID') }}</h2>
                                    <p class="paragraph">{{ _trans('user.6099') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseEmployeeID" aria-expanded="false"
                                        aria-controls="collapseEmployeeID">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseEmployeeID">
                                <div class="form-box">
                                    <form>
                                        <input name="employee_id" type="text" class="form-control"
                                            placeholder="{{ _trans('user.6099') }}" />
                                        <button type="button" class="btn-update">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Manager') }}</h2>
                                    <p class="paragraph">{{ _trans('user.paragraph') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseManager" aria-expanded="false"
                                        aria-controls="collapseManager">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseManager">
                                <div class="form-box">
                                    <form>
                                        <input name="manager" type="text" class="form-control"
                                            placeholder="{{ _trans('user.paragraph') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Grade') }}</h2>
                                    <p class="paragraph">{{ _trans('user.N/A') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseGrade" aria-expanded="false"
                                        aria-controls="collapseGrade">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseGrade">
                                <div class="form-box">
                                    <form>
                                        <input name="grade" type="text" class="form-control" placeholder="{{ _trans('user.A (+)') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-financial" role="tabpanel" aria-labelledby="nav-financial-tab">
                    <!-- profile body form start -->
                    <div class="profile-body-form">
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.TIN') }}</h2>
                                    <p class="paragraph">{{ _trans('user.N/A') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTIN" aria-expanded="false" aria-controls="collapseTIN">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseTIN">
                                <div class="form-box">
                                    <form>
                                        <input name="tin" type="text" class="form-control" placeholder="{{ _trans('user.N/A') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Bank Name') }}</h2>
                                    <p class="paragraph">{{ _trans('user.N/A') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBankName" aria-expanded="false"
                                        aria-controls="collapseBankName">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseBankName">
                                <div class="form-box">
                                    <form>
                                        <input name="bank_name" type="text" class="form-control" placeholder="{{ _trans('user.N/A') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Bank Account') }}</h2>
                                    <p class="paragraph">{{ _trans('user.N/A') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBankAccount" aria-expanded="false"
                                        aria-controls="collapseBankAccount">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseBankAccount">
                                <div class="form-box">
                                    <form>
                                        <input name="bank_account" type="text" class="form-control"
                                            placeholder="{{ _trans('user.N/A') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-emergency" role="tabpanel" aria-labelledby="nav-emergency-tab">
                    <!-- profile body form start -->
                    <div class="profile-body-form">
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Name') }}</h2>
                                    <p class="paragraph">{{ _trans('user.Johnney Depp') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseName" aria-expanded="false"
                                        aria-controls="collapseName">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseName">
                                <div class="form-box">
                                    <form>
                                        <input name="name" type="text" class="form-control"
                                            placeholder="{{ _trans('user.Johnney Depp') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Mobile Number') }}</h2>
                                    <p class="paragraph">{{ _trans('user.+880 (698) 324 986') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseMobile" aria-expanded="false"
                                        aria-controls="collapseMobile">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseMobile">
                                <div class="form-box">
                                    <form>
                                        <input name="mobile" type="text" class="form-control"
                                            placeholder="{{ _trans('user.+880 (698) 324 986') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Relationship with Employee') }}</h2>
                                    <p class="paragraph">{{ _trans('user.Brother') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseMobileRelationshipWithEmployee" aria-expanded="false"
                                        aria-controls="collapseMobileRelationshipWithEmployee">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseMobileRelationshipWithEmployee">
                                <div class="form-box">
                                    <form>
                                        <input name="relationship_with_employee" type="text" class="form-control"
                                            placeholder="{{ _trans('user.Brother') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-security" role="tabpanel" aria-labelledby="nav-security-tab">
                    <!-- profile body form start -->
                    <div class="profile-body-form">
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Current Password') }}</h2>
                                    <p class="paragraph">{{ _trans('user.*************') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCurrentPassword" aria-expanded="false"
                                        aria-controls="collapseCurrentPassword">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseCurrentPassword">
                                <div class="form-box">
                                    <form>
                                        <input name="current_password" type="password" class="form-control"
                                            placeholder="{{ _trans('user.*************') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.New Password') }}</h2>
                                    <p class="paragraph">{{ _trans('user.*************') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseNewPassword" aria-expanded="false"
                                        aria-controls="collapseNewPassword">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseNewPassword">
                                <div class="form-box">
                                    <form>
                                        <input name="new_password" type="password" class="form-control"
                                            placeholder="{{ _trans('user.*************') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="d-flex justify-content-between align-content-center">
                                <div class="align-self-center">
                                    <h2 class="title">{{ _trans('user.Re-enter Password') }}</h2>
                                    <p class="paragraph">{{ _trans('user.*************') }}</p>
                                </div>
                                <div class="align-self-center">
                                    <button type="button" class="btn-edit" data-bs-toggle="collapse"
                                        data-bs-target="#collapseRe-enterPassword" aria-expanded="false"
                                        aria-controls="collapseRe-enterPassword">
                                        {{ _trans('user.Edit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="collapseRe-enterPassword">
                                <div class="form-box">
                                    <form>
                                        <input name="reenter_password" type="password" class="form-control"
                                            placeholder="{{ _trans('user.*************') }}" />
                                        <button type="button" class="btn-update">
                                            {{ _trans('user.Update') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- profile body form end -->
        </div>
        <!-- profile body end -->

    </div>

@endsection
