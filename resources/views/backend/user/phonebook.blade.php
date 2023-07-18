@extends('backend.layouts.app')
@section('title', @$data['title']) 
@section('content')

    <div class="content-wrapper dashboard-wrapper mt-30 has-table-with-td">
        @include('backend.partials.user_navbar')
        <!-- Main content -->
        <section class="content p-0">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
             
                <div class="row dataTable-btButtons">
                    <div class="col-lg-12">
                        <table id="table" class="table card-table table-vcenter datatable mb-0 w-100 profile_Phonebook_table">
                            <thead>
                            <tr>
                                <th>{{ _trans('common.Name') }}</th>
                                <th>{{ _trans('common.Email') }}</th>
                                <th>{{ _trans('common.Phone') }}</th>
                                <th>{{ _trans('common.Designation') }}</th>
                                <th>{{ _trans('common.Department') }}</th>
                                <th>{{ _trans('common.Role') }}</th>
                                <th>{{ _trans('common.Status') }}</th>
                                {{-- <th>Profile</th> --}}
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <input type="hidden" name="department_id" id="department_id" value="{{ $data['user']->department_id}}">
    <input type="hidden" name="" id="profile_Phonebook_data_url" value="{{ route('user.phonebookDatatable') }}">
    <input type="hidden" name="user_id" id="staff_user_id" value="{{ auth()->user()->role->slug === 'staff' ? auth()->id() :'' }}">

@endsection

@section('script')
    @include('backend.partials.datatable')
@endsection
