@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    <div class="content-wrapper dashboard-wrapper mt-30">


        <!-- Main content -->
        <section class="content p-0 ">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30 ">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
                        <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                        <div class="d-flex align-items-center flex-wrap">
                    @if(hasPermission('company_create'))
                    <a href="{{ route('company.create') }}" class="btn btn-sm btn-primary ">{{ _trans('common.Add Company') }} </a>
                @endif
                </div>
                </div>

                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <table id="table" class="table card-table table-vcenter datatable mb-0 w-100 company_table">
                            <thead>
                                <tr>
                                    <th>{{ _trans('common.Company Name') }}</th>
                                    <th>{{ _trans('common.Name') }}</th>
                                    <th>{{ _trans('common.Email') }}</th>
                                    <th>{{ _trans('common.Phone') }}</th>
                                    <th>{{ _trans('common.Total Employee') }}</th>
                                    <th>{{ _trans('common.Business Type') }}</th>
                                    <th>{{ _trans('common.Trade Licence Number') }}</th>
                                    <th>{{ _trans('common.Status') }}</th>
                                    <th>{{ _trans('common.Action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            </div>
        </section>
    </div>
    <input type="text" hidden id="company_data_url" value="{{ route('company.dataTable') }}">
@endsection
@section('script')
    @include('backend.partials.datatable')
@endsection
