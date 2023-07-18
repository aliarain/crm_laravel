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
                                    <select class="form-select d-inline-block" id="entries"
                                        onchange="appointmentDatatable()">
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
                                @if (hasPermission('appointment_create'))
                                    <div class="align-self-center">
                                        <a href="{{ route('appointment.create') }}" role="button" class="btn-add"
                                            data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ _trans('common.Add') }}">
                                            <span><i class="fa-solid fa-plus"></i> </span>
                                            <span class="d-none d-xl-inline">{{ _trans('common.Create') }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="align-self-center">
                                <div class="dropdown dropdown-designation" data-bs-toggle="tooltip"
                                    data-bs-placement="right" data-bs-title="{{ _trans('common.Employee') }}">
                                    <button type="button" class="btn-designation" data-bs-toggle="dropdown"
                                        aria-expanded="false" data-bs-auto-close="false">
                                        <span class="icon"><i class="fa-solid fa-users"></i></span>

                                        <span class="d-none d-xl-inline">{{ _trans('common.Employee') }}</span>
                                    </button>

                                    <div class="dropdown-menu">
                                        <select name="user_id" class="form-control" id="user_id"
                                            onchange="appointmentDatatable()">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- search -->
                            <div class="align-self-center">
                                <div class="search-box d-flex">
                                    <input class="form-control" placeholder="Search" name="search"
                                        onkeyup="appointmentDatatable()" autocomplete="off" >
                                    <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
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
