@extends('backend.layouts.app')
@section('title', @$data['title'])

@section('content')
<div class="profile-content">
    <div class="d-flex flex-column flex-lg-row gap-4 gap-lg-0">
        @include('backend.partials.user_navbar')
        <!-- profile body start -->
        <div class="profile-body padding-reduce">
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
                                        <option value="10">{{ _trans('common.10') }}</option>
                                        <option value="25">{{ _trans('common.25') }}</option>
                                        <option value="50">{{ _trans('common.50') }}</option>
                                        <option value="100">{{ _trans('common.100') }}</option>
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

                                            <span class="d-none d-xl-inline">{{ _trans('common.Designation') }}</span>
                                        </button>

                                        <div class="dropdown-menu">
                                            <div class="search-content">
                                                <div class="search-box d-flex">
                                                    <input class="form-control"
                                                        placeholder="{{ _trans('common.Search') }}" name="search" />
                                                    <span class="icon"><i
                                                            class="fa-solid fa-magnifying-glass"></i></span>
                                                </div>
                                            </div>

                                            <div class="dropdown-divider"></div>

                                            <ul class="list">
                                                <li class="list-item">
                                                    <a class="dropdown-item" href="#">{{ _trans('common.Main
                                                        Department') }}</a>
                                                </li>

                                                <li class="list-item">
                                                    <a class="dropdown-item" href="#">{{ _trans('common.Admin & HRM')
                                                        }}</a>
                                                </li>

                                                <li class="list-item">
                                                    <a class="dropdown-item" href="#">{{ _trans('common.Accounts')
                                                        }}</a>
                                                </li>

                                                <li class="list-item">
                                                    <a class="dropdown-item" href="#">{{ _trans('common.Development')
                                                        }}</a>
                                                </li>

                                                <li class="list-item">
                                                    <a class="dropdown-item" href="#">{{ _trans('common.Software')
                                                        }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- export -->
                                <div class="align-self-center">
                                    <div
                                        class="d-flex justify-content-center justify-content-xl-end align-content-center">
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
                                                        {{ _trans('common.Copy') }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" aria-current="true"><span
                                                            class="icon mr-10"><i
                                                                class="fa-solid fa-file-excel"></i></span>
                                                        {{ _trans('common.Exel File') }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <span class="icon mr-14"><i
                                                                class="fa-solid fa-file-csv"></i></span>{{
                                                        _trans('common.Csv File') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <span class="icon mr-14"><i
                                                                class="fa-solid fa-file-pdf"></i></span>{{
                                                        _trans('common.Pdf File') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <span class="icon mr-10"><i
                                                                class="fa-solid fa-table-columns"></i></span>{{
                                                        _trans('common.Column Header') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- dropdown action -->
                                <div class="align-self-center">
                                    <div class="dropdown dropdown-action" data-bs-toggle="tooltip"
                                        data-bs-placement="right" data-bs-title="{{ _trans('common.Action') }}">
                                        <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="#"><span class="icon mr-10"><i
                                                            class="fa-solid fa-eye"></i></span>
                                                    {{ _trans('common.Make Publish') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" aria-current="true"><span
                                                        class="icon mr-8"><i class="fa-solid fa-eye-slash"></i></span>
                                                    {{ _trans('common.Make Unpublish') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <span class="icon mr-16"><i
                                                            class="fa-solid fa-trash-can"></i></span>{{
                                                    _trans('common.Delete') }}
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
                                <input class="form-control" placeholder="{{ _trans('common.Search') }}" name="search" />
                                <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </div>
                        </div>
                        <!-- add btn -->
                        <div class="align-self-center">
                            <a href="/profile-add-attendance.html" role="button" class="btn-add"
                                data-bs-toggle="tooltip" data-bs-placement="right"
                                data-bs-title="{{ _trans('common.Add') }}">
                                <span><i class="fa-solid fa-plus"></i> </span>
                                <span class="d-none d-xl-inline">{{ _trans('common.Add') }}</span>
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
                                <th class="sorting_desc">{{ _trans('common.SR No.') }}</th>
                                <th class="sorting_desc">{{ _trans('common.Date') }}</th>
                                <th class="sorting_desc">{{ _trans('common.Name') }}</th>
                                <th class="sorting_desc">{{ _trans('common.Department') }}</th>
                                <th class="sorting_desc">{{ _trans('common.Break') }}</th>
                                <th class="sorting_desc">{{ _trans('common.Break Time') }}</th>
                                <th class="sorting_desc">{{ _trans('common.Check In') }}</th>
                                <th class="sorting_desc">{{ _trans('common.Check Out') }}</th>
                                <th class="sorting_desc">{{ _trans('common.Hours') }}</th>
                                <th class="sorting_desc">{{ _trans('common.Overtime') }}</th>
                                <th class="sorting_desc">{{ _trans('common.Action') }}</th>
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
                                <td>{{ _trans('common.01') }}</td>
                                <td>{{ _trans('common.July 04') }}</td>
                                <td>{{ _trans('common.John Depp') }}</td>
                                <td>{{ _trans('common.Design') }}</td>
                                <td>{{ _trans('common.02') }}</td>
                                <td>{{ _trans('common.30 Mins') }}</td>
                                <td>
                                    <span class="badge-light-success">{{ _trans('common.09:25 AM') }}</span>
                                </td>
                                <td>
                                    <span class="badge-success">{{ _trans('common.06:40 PM') }}</span>
                                </td>
                                <td>{{ _trans('common.09:33 Hrs') }}</td>
                                <td class="overtime">{{ _trans('common.01:33 Hrs') }}</td>

                                <td>
                                    <div class="dropdown dropdown-action">
                                        <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="/basic-datatable.html"><span
                                                        class="icon mr-12"><i
                                                            class="fa-solid fa-pen-to-square"></i></span>
                                                    {{ _trans('common.Edit') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="/basic-datatable.html">
                                                    <span class="icon mr-16"><i
                                                            class="fa-solid fa-trash-can"></i></span>{{
                                                    _trans('common.Delete') }}
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
                                <td>{{ _trans('common.01') }}</td>
                                <td>{{ _trans('common.July 04') }}</td>
                                <td>{{ _trans('common.John Depp') }}</td>
                                <td>{{ _trans('common.Design') }}</td>
                                <td>{{ _trans('common.02') }}</td>
                                <td>{{ _trans('common.30 Mins') }}</td>
                                <td>
                                    <span class="badge-light-success">{{ _trans('common.09:25 AM') }}</span>
                                </td>
                                <td>
                                    <span class="badge-success">{{ _trans('common.06:40 PM') }}</span>
                                </td>
                                <td>{{ _trans('common.09:33 Hrs') }}</td>
                                <td>{{ _trans('common.01:33 Hrs') }}</td>

                                <td>
                                    <div class="dropdown dropdown-action">
                                        <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="/basic-datatable.html"><span
                                                        class="icon mr-12"><i
                                                            class="fa-solid fa-pen-to-square"></i></span>
                                                    {{ _trans('common.Edit') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="/basic-datatable.html">
                                                    <span class="icon mr-16"><i
                                                            class="fa-solid fa-trash-can"></i></span>{{
                                                    _trans('common.Delete') }}
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
                                <td>{{ _trans('common.01') }}</td>
                                <td>{{ _trans('common.July 04') }}</td>
                                <td>{{ _trans('common.John Depp') }}</td>
                                <td>{{ _trans('common.Design') }}</td>
                                <td>{{ _trans('common.02') }}</td>
                                <td>{{ _trans('common.30 Mins') }}</td>
                                <td>
                                    <span class="badge-light-success">{{ _trans('common.09:25 AM') }}</span>
                                </td>
                                <td>
                                    <span class="badge-success">{{ _trans('common.06:40 PM') }}</span>
                                </td>
                                <td>{{ _trans('common.09:33 Hrs') }}</td>
                                <td>{{ _trans('common.01:33 Hrs') }}</td>

                                <td>
                                    <div class="dropdown dropdown-action">
                                        <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="/basic-datatable.html"><span
                                                        class="icon mr-12"><i
                                                            class="fa-solid fa-pen-to-square"></i></span>
                                                    {{ _trans('common.Edit') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="/basic-datatable.html">
                                                    <span class="icon mr-16"><i
                                                            class="fa-solid fa-trash-can"></i></span>{{
                                                    _trans('common.Delete') }}
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
                                <td>{{ _trans('common.01') }}</td>
                                <td>{{ _trans('common.July 04') }}</td>
                                <td>{{ _trans('common.John Depp') }}</td>
                                <td>{{ _trans('common.Design') }}</td>
                                <td>{{ _trans('common.02') }}</td>
                                <td>{{ _trans('common.30 Mins') }}</td>
                                <td>
                                    <span class="badge-light-success">{{ _trans('common.09:25 AM') }}</span>
                                </td>
                                <td>
                                    <span class="badge-success">{{ _trans('common.06:40 PM') }}</span>
                                </td>
                                <td>{{ _trans('common.09:33 Hrs') }}</td>
                                <td>{{ _trans('common.01:33 Hrs') }}</td>

                                <td>
                                    <div class="dropdown dropdown-action">
                                        <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="/basic-datatable.html"><span
                                                        class="icon mr-12"><i
                                                            class="fa-solid fa-pen-to-square"></i></span>
                                                    {{ _trans('common.Edit') }}</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="/basic-datatable.html">
                                                    <span class="icon mr-16"><i
                                                            class="fa-solid fa-trash-can"></i></span>{{
                                                    _trans('common.Delete') }}
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
                                <a class="page-link active" href="#">{{ _trans('common.1') }}</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">{{ _trans('common.2') }}</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">{{ _trans('common.3') }}</a>
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
</div>

<input type="hidden" name="" id="attendance_report_data_url" value="{{ @$data['url'] }}">
<input type="hidden" name="" id="__user_id" value="{{ @$data['id'] }}">

@endsection

@section('script')
@include('backend.partials.datatable')
@endsection