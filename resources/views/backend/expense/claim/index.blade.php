@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')

    <div class="content-wrapper dashboard-wrapper mt-30">

        <!-- Main content -->
        <section class="content p-0">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">

                <div class="row align-items-center mb-15">
                    <div class="col-sm-6">
                        <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                    </div>

                    <div class="col-sm-6">
                    </div>
                </div>

                <div class="row align-items-end mb-30 table-filter-data justify-content-center">
                    @if(hasPermission('expense_read'))
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="#">{{ _trans('common.Purpose') }}</label>
                                <select name="purpose" id="category_id" multiple class="form-control select2">
                                    @foreach($data['purposes'] as $purpose)
                                        <option value="{{ $purpose->id }}">{{ $purpose->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="#">{{ _trans('common.Employees') }}</label>
                                <select name="purpose" id="employee_id" multiple class="form-control select2">
                                    @foreach($data['employees'] as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="#">{{ _trans('common.Start Date') }}</label>
                                       <x-date-picker :label="'Date Range'" />
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary expense_claim_table_form">{{ _trans('common.Submit') }}</button>
                            </div>
                        </div>
                    @endif
                </div>

                @if(hasPermission('claim_read'))
                    <div class="row dataTable-btButtons">
                        <div class="col-lg-12">
                            <table id="table"
                                   class="table card-table table-vcenter datatable mb-0 w-100 expense_claim_table">
                                <thead>
                                <tr>
                                    <th>{{ _trans('common.Date') }}</th>
                                    <th>{{ _trans('common.Employee name') }}</th>
                                    <th>{{ _trans('common.Remarks') }}</th>
                                    <th>{{ _trans('common.Amount') }}</th>
                                    <th>{{ _trans('common.File') }}</th>
                                    <th>{{ _trans('common.Status') }}</th>
                                    <th>{{ _trans('common.Action') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
    <input type="hidden" name="" id="expense_claim_list_data_url" value="{{ route('claim.dataTable') }}">


    {{--  Modal start  --}}
    <div class="modal fade paymentModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="tab-content px-primary">
                    <div class="d-flex justify-content-between">
                        <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header mt-3">
                            {{ _trans('common.Pay now') }}</h5>
                        <a href="#" class="close mt-3 close-btn-size" data-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                    <hr>
                    <div class="content py-primary">
                        <form action="#"
                              enctype="multipart/form-data" method="post" id="paymentModal">
                            @csrf
                            <div class="row">
                                <label class="col-sm-3 col-form-label">{{ _trans('common.Amount') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div><input type="text"
                                                name="amount"
                                                id="amount"
                                                required="required"
                                                placeholder="{{ _trans('common.Amount') }}"
                                                autocomplete="off"
                                                readonly
                                                class="form-control">
                                    </div>
                                    @if ($errors->has('amount'))
                                        <div class="error">{{ $errors->first('amount') }}</div>
                                    @endif
                                </div>
                                <label class="col-sm-3 col-form-label mt-3 mb-5">{{ _trans('account.Payment Method') }}</label>
                                <div class="col-sm-9  mt-3 mb-5">
                                    <div>
                                        <select name="payment_method" id="payment_method" class="form-control select2">
                                            <option value="">{{ _trans('common.Choose One') }}</option>
                                            @foreach($data['payment_methods'] as $item){
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('amount'))
                                        <div class="error">{{ $errors->first('amount') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">{{ _trans('common.Pay now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--  Modal end  --}}
@endsection

@section('script')
    @include('backend.partials.datatable')
@endsection
