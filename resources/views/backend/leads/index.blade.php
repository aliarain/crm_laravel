@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')


{!! breadcrumb([
'title' => @$data['title'],
route('admin.dashboard') => _trans('common.Dashboard'),
'#' => @$data['title'],
]) !!}
<input type="text" hidden id="is_income" value="{{ @$data['is_income'] }}">
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
                                <select class="form-select d-inline-block" id="entries" onchange="clientDatatable()">
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
                            <div class="align-self-center">
                                <a href="javascript:;" role="button" class="btn-add" data-bs-toggle="tooltip"
                                    data-bs-placement="right"
                                    onclick="mainModalOpen(`{{ url('/') }}/admin/leads/lead-details/1`)">
                                    {{ _trans('common.Details') }}
                                </a>
                            </div>
                        </div>
                        <div class="align-self-center d-flex flex-wrap gap-2">
                            <!-- add btn -->
                            <div class="align-self-center">
                                @if (hasPermission('lead_create'))
                                <a href="#" role="button" class="btn-add" data-bs-toggle="tooltip"
                                    data-bs-placement="right" onclick="mainModalOpen('{{ route('lead.create') }}')"
                                    data-bs-title="{{ _trans('common.Create') }}">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline"> {{ _trans('common.Create') }}</span>
                                </a>
                                @endif
                            </div>
                        </div>

                        @if(@$data['lead_types'])
                        <!-- START:: lead_types dropdown -->
                        <div class="align-self-center">
                            <div class="dropdown dropdown-designation" data-bs-toggle="tooltip"
                                data-bs-placement="right" data-bs-title="{{ _trans('common.Lead Types') }}">
                                <button type="button" class="btn-designation" data-bs-toggle="dropdown"
                                    aria-expanded="false" data-bs-auto-close="false">
                                    <span class="icon"><i class="fa-solid fa-user-shield"></i></span>

                                    <span class="d-none d-xl-inline">{{ _trans('common.Lead Types') }}</span>
                                </button>

                                <div class="dropdown-menu">
                                    <select name="sources" id="lead_types" class="form-control select2"
                                        onchange="usersDatatable()">
                                        <option value="0" selected disabled>
                                            {{ _trans('common.Select sources') }}</option>
                                        @foreach ($data['lead_types'] as $lead_type)
                                        <option value="{{ $lead_type->id }}">{{ $lead_type->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END:: lead_types dropdown -->
                        @endif

                        @if(@$data['lead_sources'])
                        <!-- START:: lead_source dropdown -->
                        <div class="align-self-center">
                            <div class="dropdown dropdown-designation" data-bs-toggle="tooltip"
                                data-bs-placement="right" data-bs-title="{{ _trans('common.Lead Sources') }}">
                                <button type="button" class="btn-designation" data-bs-toggle="dropdown"
                                    aria-expanded="false" data-bs-auto-close="false">
                                    <span class="icon"><i class="fa-solid fa-user-shield"></i></span>

                                    <span class="d-none d-xl-inline">{{ _trans('common.Lead Sources') }}</span>
                                </button>

                                <div class="dropdown-menu">
                                    <select name="sources" id="lead_sources" class="form-control select2"
                                        onchange="usersDatatable()">
                                        <option value="0" selected disabled>
                                            {{ _trans('common.Select sources') }}</option>
                                        @foreach ($data['lead_sources'] as $lead_source)
                                        <option value="{{ @$lead_source->id }}">{{ @$lead_source->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END:: lead_source dropdown -->
                        @endif


                        @if(@$data['lead_statuses'])
                        <!-- START:: lead_source dropdown -->
                        <div class="align-self-center">
                            <div class="dropdown dropdown-designation" data-bs-toggle="tooltip"
                                data-bs-placement="right" data-bs-title="{{ _trans('common.Lead Status') }}">
                                <button type="button" class="btn-designation" data-bs-toggle="dropdown"
                                    aria-expanded="false" data-bs-auto-close="false">
                                    <span class="icon"><i class="fa-solid fa-user-shield"></i></span>

                                    <span class="d-none d-xl-inline">{{ _trans('common.Lead Status') }}</span>
                                </button>

                                <div class="dropdown-menu">
                                    <select name="sources" id="lead_statuses" class="form-control select2"
                                        onchange="usersDatatable()">
                                        <option value="0" selected disabled>
                                            {{ _trans('common.Select Status') }}</option>
                                        @foreach ($data['lead_statuses'] as $lead_status)
                                        <option value="{{ @$lead_status->id }}">{{ @$lead_status->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END:: lead_source dropdown -->
                        @endif


                        <!-- search -->
                        <div class="align-self-center">
                            <div class="search-box d-flex">
                                <input class="form-control" placeholder="{{ _trans('common.Search') }}" name="search"
                                    onkeyup="leads_datatable()" autocomplete="off">
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
                                            onclick="tableAction('active', `{{ $data['status_url'] }}`)"><span
                                                class="icon mr-10"><i class="fa-solid fa-eye"></i></span>
                                            {{ _trans('common.Activate') }} <span class="count">(0)</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" aria-current="true"
                                            onclick="tableAction('inactive',`{{ $data['status_url'] }}`)">
                                            <span class="icon mr-8"><i class="fa-solid fa-eye-slash"></i></span>
                                            {{ _trans('common.Inactive') }} <span class="count">(0)</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            onclick="tableAction('delete', `{{ $data['delete_url'] }}`)">
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