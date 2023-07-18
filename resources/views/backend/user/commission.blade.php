@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper dashboard-wrapper mt-30">
        <!-- Main content -->
        <section class="content p-0">
            @include('backend.partials.user_navbar')
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
                <div class="main-panel">
                    <div class="vertical-tab">
                        <div class="row no-gutters">
                            <div class="col-12 pl-md-3 pt-md-0 pt-sm-4 pt-4">
                                @if (url()->current() === route('userProfile.info',['user_id'=>$data['id'],'type'=>'commission']))
                                  
                                    <div class="d-flex justify-content-between pb-3">
                                        <h5 class="d-flex align-items-center text-capitalize mb-0 title">
                                            <span class="pl-2">{{ _trans('Addition') }} </span>
                                        </h5>
                                    </div>
                                    <div class="card  border-0">
                                        <div class="tab-content px-primary">
                                            <div id="Contract" class="tab-pane active">
                                                <div
                                                    class="content mb-3 mt-3 table-responsive bg-transparent box-shadow-none">
                                                    <table class="table ">
                                                        <thead>
                                                            <tr class="border-bottom-2">
                                                                <th>{{ _trans('common.Title') }}</th>
                                                                <th>{{ _trans('common.Type') }}</th>
                                                                <th>{{ _trans('common.Amount') }}</th>
                                                                <th>{{ _trans('common.Status') }}</th>
                                                                <th>{{ _trans('common.Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $salary_commission = @$data['show']->original['data']['salary_commission'];
                                                            @endphp
                                                            @if (@$salary_commission->salarySetupAdditionDetails)
                                                                @foreach (@$salary_commission->salarySetupAdditionDetails as $setupDetails)
                                                                    <tr>
                                                                        <td>{{ @$setupDetails->commission->name }}</td>
                                                                        <td>
                                                                            @if (@$setupDetails->amount_type == 1)
                                                                                <span
                                                                                    class="badge badge-success">{{ _trans('common.Fixed') }}</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge badge-info">{{ _trans('common.Percentage') }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if (@$setupDetails->amount_type == 1)
                                                                                {{ currency_format(@$setupDetails->amount) }}
                                                                            @else
                                                                                {{ @$setupDetails->amount }}%
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span
                                                                                class="badge badge-{{ $setupDetails->status->class }}">{{ $setupDetails->status->name }}</span>
                                                                        </td>
                                                                        <td>
                                                                            @if (hasPermission('edit_payroll_set'))
                                                                                <button class="btn btn-success btn-sm"
                                                                                    onclick="viewModal(`{{ route('hrm.payroll_setup.edit_salary_setup', $setupDetails->id) }}`)">
                                                                                    <i class="fa fa-edit"></i>
                                                                                </button>
                                                                            @endif
                                                                            @if (hasPermission('delete_payroll_set'))
                                                                                <button class="btn btn-danger btn-sm"
                                                                                    onclick="delete_item(`{{ route('hrm.payroll_setup.delete_salary_setup', $setupDetails->id) }}`)">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>
                                                                            @endif

                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr class="text-center">
                                                                    <td colspan="4">{{ _trans('common.No Data') }}</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between pb-3">
                                        <h5 class="d-flex align-items-center text-capitalize mb-0 title">
                                            <span class="pl-2">{{ _trans('Deduction') }} </span>
                                        </h5>
                                        
                                    </div>
                                    <div class="card  border-0">
                                        <div class="tab-content px-primary">
                                            <div id="Contract" class="tab-pane active">
                                                <div
                                                    class="content mb-3 mt-3 table-responsive bg-transparent box-shadow-none">
                                                    <table class="table ">
                                                        <thead>
                                                            <tr class="border-bottom-2">
                                                                <th>{{ _trans('common.Title') }}</th>
                                                                <th>{{ _trans('common.Type') }}</th>
                                                                <th>{{ _trans('common.Amount') }}</th>
                                                                <th>{{ _trans('common.Status') }}</th>
                                                                <th>{{ _trans('common.Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (@$salary_commission->salarySetupDeductionDetails)
                                                                @foreach (@$salary_commission->salarySetupDeductionDetails as $setupDetails)
                                                                    <tr>
                                                                        <td>{{ @$setupDetails->commission->name }}</td>
                                                                        <td>
                                                                            @if (@$setupDetails->amount_type == 1)
                                                                                <span
                                                                                    class="badge badge-success">{{ _trans('common.Fixed') }}</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge badge-info">{{ _trans('common.Percentage') }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if (@$setupDetails->amount_type == 1)
                                                                                {{ currency_format(@$setupDetails->amount) }}
                                                                            @else
                                                                                {{ @$setupDetails->amount }}%
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <span
                                                                                class="badge badge-{{ $setupDetails->status->class }}">{{ $setupDetails->status->name }}</span>
                                                                        </td>
                                                                        <td class="Action">
                                                                            @if (hasPermission('edit_payroll_set'))
                                                                                <button class="btn btn-success btn-sm"
                                                                                    onclick="viewModal(`{{ route('hrm.payroll_setup.edit_salary_setup', $setupDetails->id) }}`)">
                                                                                    <i class="fa fa-edit"></i>
                                                                                </button>
                                                                            @endif
                                                                            @if (hasPermission('delete_payroll_set'))
                                                                                <button class="btn btn-danger btn-sm"
                                                                                    onclick="delete_item(`{{ route('hrm.payroll_setup.delete_salary_setup', $setupDetails->id) }}`)">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr class="text-center">
                                                                    <td colspan="4">{{ _trans('common.No Data') }}</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
@endsection
@section('script')
    <script src="{{ url('public/backend/js/pages/__profile.js') }}"></script>
@endsection
