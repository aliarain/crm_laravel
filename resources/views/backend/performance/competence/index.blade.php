@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([
'title' => @$data['title'],
route('admin.dashboard') => _trans('common.Dashboard'),
'#' => @$data['title'],
]) !!}
<div class="table-content table-basic">
    <div class="card">

        <div class="card-body">
            <!-- toolbar table start -->
            <div
                class="table-toolbar d-flex flex-wrap gap-2 flex-xl-row justify-content-center justify-content-xxl-between align-content-center pb-3">
                <div class="align-self-center">
                    <div class="d-flex flex-wrap gap-2  flex-lg-row justify-content-center align-content-center">
                        <!-- show per page -->
                        <div class="align-self-center">
                            <label>
                                <span class="mr-8">{{ _trans('common.Show') }}</span>
                                <select class="form-select d-inline-block" id="entries" onchange="competenceTable()">
                                    <option selected value="10">{{ _trans('10') }}</option>
                                    <option value="25">{{ _trans('25') }}</option>
                                    <option value="50">{{ _trans('50') }}</option>
                                    <option value="100">{{ _trans('100') }}</option>
                                </select>
                                <span class="ml-8">{{ _trans('common.Entries') }}</span>
                            </label>
                        </div>

                        <div class="align-self-center d-flex flex-wrap gap-2">
                            <!-- add btn -->


                            @if (hasPermission('performance_competence_create'))
                            <div class="align-self-center">
                                <a href="javascript:;"
                                    onclick="viewModal(`{{ route('performance.competence.create') }}`)" role="button"
                                    class="btn-add" data-bs-toggle="tooltip" data-bs-placement="right"
                                    data-bs-title="{{ _trans('common.Add') }}">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{ _trans('common.Create') }}</span>
                                </a>
                            </div>
                            @endif
                        </div>

                        <div class="align-self-center">
                            <div class="dropdown dropdown-designation" data-bs-toggle="tooltip"
                                data-bs-placement="right" data-bs-title="{{ _trans('performance.Competence Type') }}">
                                <button type="button" class="btn-designation" data-bs-toggle="dropdown"
                                    aria-expanded="false" data-bs-auto-close="false">
                                    <span class="icon"><i class="fa-solid fa-user-shield"></i></span>

                                    <span class="d-none d-xl-inline">{{ _trans('performance.Competence Type') }}</span>
                                </button>

                                <div class="dropdown-menu align-self-center ">
                                    <select name="type_id" id="type_id" class="form-control pl-2 select2 w-100"
                                        onchange="competenceTable()">
                                        <option value="0" disabled selected>
                                            {{ _trans('performance.Select Competence Type') }}</option>
                                        @foreach ($data['types'] as $role)
                                        <option value="{{ $role->id }}">{{ @$role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- search -->
                        <div class="align-self-center">
                            <div class="search-box d-flex">
                                <input class="form-control" placeholder="{{ _trans('common.Search') }}" name="search"
                                    onkeyup="competenceTable()" autocomplete="off">
                                <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
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
                                        <a class="dropdown-item" href="#"
                                            onclick="tableAction('active', `{{ @$data['status_url'] }}`)"><span
                                                class="icon mr-10"><i class="fa-solid fa-eye"></i></span>
                                            {{ _trans('common.Activate') }} <span class="count">(0)</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" aria-current="true"
                                            onclick="tableAction('inactive', `{{ @$data['status_url'] }}`)">
                                            <span class="icon mr-8"><i class="fa-solid fa-eye-slash"></i></span>
                                            {{ _trans('common.Inactive') }} <span class="count">(0)</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            onclick="tableAction('delete', `{{ @$data['delete_url'] }}`)">
                                            <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>
                                            {{ _trans('common.Delete') }} <span class="count">(0)</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- export -->
                @include('backend.partials.buttons')
            </div>
            <!-- toolbar table end -->
            <!--  table start -->
            <div class="table-responsive">
                @include('backend.partials.table')
            </div>
            <!--  table end -->
        </div>
    </div>
</div>
@endsection
@section('script')
@include('backend.partials.table_js')
@endsection