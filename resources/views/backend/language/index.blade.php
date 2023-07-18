@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([
'title' => @$data['title'],
route('admin.dashboard') => _trans('common.Dashboard'),
'#' => @$data['title'],
]) !!}

<div class=" table-content table-basic ">
    <div class="card">
        {{-- <div class="card-header">{{ @$data['title'] }}</div> --}}
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
                                <select class="form-select d-inline-block" id="entries" onchange="languageTable()">
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
                            @if (auth()->user()->role_id == 1 || hasPermission('language_create'))
                            <div class="align-self-center">
                                <a href="javascript:;" role="button" class="btn-add" data-bs-toggle="tooltip"
                                    onclick="mainModalOpen(`{{ route('language.create') }}`)" data-bs-placement="right"
                                    data-bs-title="{{ _trans('common.Add') }}">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{ _trans('common.Create') }}</span>
                                </a>
                            </div>
                            @endif

                        </div>

                        <!-- search -->
                        <div class="align-self-center">
                            <div class="search-box d-flex">
                                <input class="form-control" placeholder="{{ _trans('common.Search') }}" name="search"
                                    onkeyup="languageTable()" autocomplete="off">
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