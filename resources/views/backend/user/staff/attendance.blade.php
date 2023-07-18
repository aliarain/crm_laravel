@extends('backend.layouts.app')
@section('title', @$data['title'])

@section('content')
@include('backend.partials.staff_navbar')
<div class="profile-content">
    <!-- profile body start -->
    <div class="profile-body profile-body-cus">
        <h2 class="title">{{ _trans('common.Attendance') }}</h2>
        <div class="profile-table-content">
            <!--  toolbar table  start  -->
            <div
                class="table-toolbar d-flex flex-wrap gap-2 flex-column flex-xxl-row justify-content-center justify-content-xxl-between align-content-center pb-3">
                <div class="align-self-center">
                    <div class="d-flex flex-wrap gap-2  flex-lg-row justify-content-center align-content-center">
                        <!-- show per page -->
                        <div class="align-self-center">
                            <label>
                                <span class="mr-8">{{ _trans('common.Show') }}</span>
                                <select class="form-select d-inline-block">
                                    <option value="10">{{ _trans('common10') }}</option>
                                    <option value="25">{{ _trans('common25') }}</option>
                                    <option value="50">{{ _trans('common50') }}</option>
                                    <option value="100">{{ _trans('common100') }}</option>
                                </select>
                                <span class="ml-8">{{ _trans('common.Entries') }}</span>
                            </label>
                        </div>

                        <div class="align-self-center d-flex flex-wrap gap-2">
                            <!-- daterange -->
                            <div class="align-self-center">
                                <button type="button" class="btn-daterange" id="daterange" data-bs-toggle="tooltip"
                                    data-bs-placement="right" data-bs-title="{{ _trans('common.Date Range') }}">
                                    <span class="icon"><i class="fa-solid fa-calendar-days"></i>
                                    </span>
                                    <span class="d-none d-xl-inline">{{ _trans('common.Date Range') }}</span>
                                </button>
                            </div>
                            <!-- Designation -->
                            <div class="align-self-center">
                                <div class="dropdown dropdown-designation" data-bs-toggle="tooltip"
                                    data-bs-placement="right" data-bs-title="Designation">
                                    <button type="button" class="btn-designation" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span class="icon"><i class="fa-solid fa-user-shield"></i></span>

                                        <span class="d-none d-xl-inline">{{ _trans('commonDesignation') }}</span>
                                    </button>

                                    <div class="dropdown-menu">
                                        <div class="search-content">
                                            <div class="search-box d-flex">
                                                <input class="form-control" placeholder="{{ _trans('commonSearch') }}"
                                                    name="search" />
                                                <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                            </div>
                                        </div>

                                        <div class="dropdown-divider"></div>

                                        <ul class="list">
                                            <li class="list-item">
                                                <a class="dropdown-item" href="#">{{ _trans('commonMain Department')
                                                    }}</a>
                                            </li>

                                            <li class="list-item">
                                                <a class="dropdown-item" href="#">{{ _trans('commonAdmin & HRM') }}</a>
                                            </li>

                                            <li class="list-item">
                                                <a class="dropdown-item" href="#">{{ _trans('commonAccounts') }}</a>
                                            </li>

                                            <li class="list-item">
                                                <a class="dropdown-item" href="#">{{ _trans('commonDevelopment') }}</a>
                                            </li>

                                            <li class="list-item">
                                                <a class="dropdown-item" href="#">{{ _trans('commonSoftware') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- export -->
                            <div class="align-self-center">
                                <div class="d-flex justify-content-center justify-content-xl-end align-content-center">
                                    <div class="dropdown dropdown-export" data-bs-toggle="tooltip"
                                        data-bs-placement="right" data-bs-title="{{ _trans('common.Export') }}">
                                        <button type="button" class="btn-export" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span class="icon"><i
                                                    class="fa-solid fa-arrow-up-right-from-square"></i></span>

                                            <span class="d-none d-xl-inline">{{ _trans('common.Export') }}</span>
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#"><span class="icon mr-8"><i
                                                            class="fa-solid fa-copy"></i></span>
                                                    {{ _trans('commonCopy') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" aria-current="true"><span
                                                        class="icon mr-10"><i class="fa-solid fa-file-excel"></i></span>
                                                    {{ _trans('commonExel File') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <span class="icon mr-14"><i class="fa-solid fa-file-csv"></i></span>
                                                    {{ _trans('commonCsv File') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <span class="icon mr-14"><i class="fa-solid fa-file-pdf"></i></span>
                                                    {{ _trans('commonPdf File') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <span class="icon mr-10"><i
                                                            class="fa-solid fa-table-columns"></i></span>{{_trans('commonColumn
                                                    Header')}}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- dropdown action -->
                            <div class="align-self-center">
                                <div class="dropdown dropdown-action" data-bs-toggle="tooltip" data-bs-placement="right"
                                    data-bs-title="{{ _trans('common.Action') }}">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#"><span class="icon mr-10"><i
                                                        class="fa-solid fa-eye"></i></span>
                                                {{ _trans('commonMake Publish') }}</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" aria-current="true"><span
                                                    class="icon mr-8"><i class="fa-solid fa-eye-slash"></i></span>
                                                {{ _trans('commonMake Unpublish') }}</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>{{
                                                _trans('commonDelete') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="align-self-center d-flex flex-wrap gap-2">
                    <!-- search -->
                    <div class="align-self-center">
                        <div class="search-box d-flex">
                            <input class="form-control" placeholder="{{ _trans('commonSearch') }}" name="search" />
                            <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                        </div>
                    </div>
                    <!-- add btn -->
                    <div class="align-self-center">
                        <a href="{{ route('attendance.check-in') }}" role="button" class="btn-add"
                            data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="{{ _trans('common.Add') }}">
                            <span><i class="fa-solid fa-plus"></i> </span>
                            <span class="d-none d-xl-inline">{{ _trans('commonAdd') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--toolbar table end -->
            <!--  table start -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead">
                        <tr>
                            <th class="sorting_asc">
                                <div class="check-box">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" />
                                    </div>
                                </div>
                            </th>
                            <th class="sorting_desc">{{ _trans('commonSR No.') }}</th>
                            <th class="sorting_desc">{{ _trans('commonDate') }}</th>
                            <th class="sorting_desc">{{ _trans('commonName') }}</th>
                            <th class="sorting_desc">{{ _trans('commonDepartment') }}</th>
                            <th class="sorting_desc">{{ _trans('commonBreak') }}</th>
                            <th class="sorting_desc">{{ _trans('commonBreak Time') }}</th>
                            <th class="sorting_desc">{{ _trans('commonCheck In') }}</th>
                            <th class="sorting_desc">{{ _trans('commonCheck Out') }}</th>
                            <th class="sorting_desc">{{ _trans('commonHours') }}</th>
                            <th class="sorting_desc">{{ _trans('commonOvertime') }}</th>
                            <th class="sorting_desc">{{ _trans('commonAction') }}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        <tr>
                            <td>
                                <div class="check-box">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" />
                                    </div>
                                </div>
                            </td>
                            <td>{{ _trans('common01') }}</td>
                            <td>{{ _trans('commonJuly 04') }}</td>
                            <td>{{ _trans('commonJohn Depp') }}</td>
                            <td>{{ _trans('commonDesign') }}</td>
                            <td>{{ _trans('common02') }}</td>
                            <td>{{ _trans('common30 Mins') }}</td>
                            <td>
                                <span class="badge-light-success">{{ _trans('common09:25 AM') }}</span>
                            </td>
                            <td>
                                <span class="badge-success">{{ _trans('common06:40 PM') }}</span>
                            </td>
                            <td>{{ _trans('common09:33 Hrs') }}</td>
                            <td class="overtime">{{ _trans('common01:33 Hrs') }}</td>

                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="/basic-datatable.html"><span
                                                    class="icon mr-12"><i class="fa-solid fa-pen-to-square"></i></span>
                                                {{ _trans('commonEdit') }}</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/basic-datatable.html">
                                                <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>{{
                                                _trans('commonDelete') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="check-box">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" />
                                    </div>
                                </div>
                            </td>
                            <td>{{ _trans('common01') }}</td>
                            <td>{{ _trans('commonJuly 04') }}</td>
                            <td>{{ _trans('commonJohn Depp') }}</td>
                            <td>{{ _trans('commonDesign') }}</td>
                            <td>{{ _trans('common02') }}</td>
                            <td>{{ _trans('common30 Mins') }}</td>
                            <td>
                                <span class="badge-light-success">{{ _trans('common09:25 AM') }}</span>
                            </td>
                            <td>
                                <span class="badge-success">{{ _trans('common06:40 PM') }}</span>
                            </td>
                            <td>{{ _trans('common09:33 Hrs') }}</td>
                            <td>{{ _trans('common01:33 Hrs') }}</td>

                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="/basic-datatable.html"><span
                                                    class="icon mr-12"><i class="fa-solid fa-pen-to-square"></i></span>
                                                {{ _trans('commonEdit') }}</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/basic-datatable.html">
                                                <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>{{
                                                _trans('commonDelete') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="check-box">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" />
                                    </div>
                                </div>
                            </td>
                            <td>{{ _trans('common01') }}</td>
                            <td>{{ _trans('commonJuly 04') }}</td>
                            <td>{{ _trans('commonJohn Depp') }}</td>
                            <td>{{ _trans('commonDesign') }}</td>
                            <td>{{ _trans('common02') }}</td>
                            <td>{{ _trans('common30 Mins') }}</td>
                            <td>
                                <span class="badge-light-success">{{ _trans('common09:25 AM') }}</span>
                            </td>
                            <td>
                                <span class="badge-success">{{ _trans('common06:40 PM') }}</span>
                            </td>
                            <td>{{ _trans('common09:33 Hrs') }}</td>
                            <td>{{ _trans('common01:33 Hrs') }}</td>

                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="/basic-datatable.html"><span
                                                    class="icon mr-12"><i class="fa-solid fa-pen-to-square"></i></span>
                                                {{ _trans('commonEdit') }}</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/basic-datatable.html">
                                                <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>{{
                                                _trans('commonDelete') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="check-box">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" />
                                    </div>
                                </div>
                            </td>
                            <td>{{ _trans('common01') }}</td>
                            <td>{{ _trans('commonJuly 04') }}</td>
                            <td>{{ _trans('commonJohn Depp') }}</td>
                            <td>{{ _trans('commonDesign') }}</td>
                            <td>{{ _trans('common02') }}</td>
                            <td>{{ _trans('common30 Mins') }}</td>
                            <td>
                                <span class="badge-light-success">{{ _trans('common09:25 AM') }}</span>
                            </td>
                            <td>
                                <span class="badge-success">{{ _trans('common06:40 PM') }}</span>
                            </td>
                            <td>{{ _trans('common09:33 Hrs') }}</td>
                            <td>{{ _trans('common01:33 Hrs') }}</td>

                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="/basic-datatable.html"><span
                                                    class="icon mr-12"><i class="fa-solid fa-pen-to-square"></i></span>
                                                {{ _trans('commonEdit') }}</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/basic-datatable.html">
                                                <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>{{
                                                _trans('commonDelete') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!--  table end -->
            <!--  pagination start -->
            <div class="ot-pagination d-flex justify-content-end align-content-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true"><i class="fa fa-angle-left"></i></span>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link active" href="#">{{ _trans('common1') }}</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">{{ _trans('common2') }}</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">{{ _trans('common3') }}</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
    <!-- profile body form end -->
</div>
<input type="hidden" name="" id="attendance_report_data_url" value="{{ @$data['url'] }}">
<input type="hidden" name="" id="__user_id" value="{{ @$data['id'] }}">

@endsection

@section('script')
@include('backend.partials.datatable')
@endsection