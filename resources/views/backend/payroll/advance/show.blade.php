@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ @$data['title'] }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
                            @if (hasPermission('advance_salaries_list'))
                                <li class="breadcrumb-item"><a
                                        href="{{ route('hrm.payroll_advance_salary.index') }}">{{ _trans('common.List') }}</a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active">{{ @$data['title'] }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    {{-- Start profile overview --}}
                    <div class="col-md-4">
                        <div class="card card-with-shadow border-0">
                            <div class="px-primary py-primary">
                                <div id="General-0">
                                    <fieldset class="form-group mb-5">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{ _trans('common.Employee') }}</label>
                                                    <div class="col-sm-9">
                                                        <img class="employee-avater-img" src="{{ uploaded_asset($data['advance']->employee->avatar_id) }}"
                                                            alt="" height="50" width="50"> <br>
                                                            <small>{{ $data['advance']->employee->name }}</small>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{ _trans('common.Designation') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ $data['advance']->employee->designation->title }}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{ _trans('common.Department') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ $data['advance']->employee->department->title }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                @if (hasPermission('advance_salaries_approve') && $data['advance']->pay == 9)
                                    @if ($data['advance']->status_id == 2)
                                        <div class="form-group text-center">
                                            <button type="button" class="btn btn-primary" onclick="mainModalOpen(`{{ route('hrm.payroll_advance_salary.approve_modal', $data['advance']->id) }}`)" > {{_trans('common.Approve')}} </button>
                                        </div>
                                        
                                    @else
                                        <div class="form-group text-center">
                                            <button type="button" class="btn btn-info" onclick="mainModalOpen(`{{ route('hrm.payroll_advance_salary.approve_modal', $data['advance']->id) }}`)" > {{_trans('common.Already Approved')}} </button>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- End profile overview --}}

                    {{-- Start summary overview --}}
                    <div class="col-md-8">
                        <div class="card card-with-shadow border-0">
                            <div class="px-primary py-primary">
                                {{-- Summary  start --}}
                                <div id="General-0">
                                    <fieldset class="form-group mb-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Advance Type') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ @$data['advance']->advance_type->name }} 
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Request Amount') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ currency_format(@$data['advance']->request_amount ?? 0) }} 
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Approved Amount') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ currency_format(@$data['advance']->amount??0 ) }} 
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Returned Amount') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ currency_format(@$data['advance']->paid_amount ?? 0 ) }} 
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Due Amount') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ currency_format(@$data['advance']->due_amount ?? 0 ) }} 
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Request Month') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ date('F Y', strtotime(@$data['advance']->date) ) }}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Recover Mode') }}</label>
                                                    <div class="col-sm-9">
                                                        @if (@$data['advance']->recovery_mode)
                                                        {{ _trans('payroll.Installment')}}
                                                        @else 
                                                        {{ _trans('payroll.One Time')}}
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Recover Cycle') }}</label>
                                                    <div class="col-sm-9">
                                                        @if (@$data['advance']->recovery_cycle)
                                                          {{ _trans('payroll.Monthly')}}
                                                        @else 
                                                          {{ _trans('payroll.Yearly')}}
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Recover From') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ date('F Y', strtotime(@$data['advance']->recover_from) ) }}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Installment Amount') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ currency_format(@$data['advance']->installment_amount ?? 0 ) }} 
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Created At') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ date('d-m-Y', strtotime(@$data['advance']->created_at))}}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Status') }}</label>
                                                    <div class="col-sm-9">
                                                        <?= '<small class="badge badge-' . @$data['advance']->status->class . '">' . @$data['advance']->status->name . '</small>' ?>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Payment') }}</label>
                                                    <div class="col-sm-9">
                                                        <?= '<small class="badge badge-' . @$data['advance']->payment->class . '">' . @$data['advance']->payment->name . '</small>' ?>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Return') }}</label>
                                                    <div class="col-sm-9">
                                                        <?= '<small class="badge badge-' . @$data['advance']->returnPayment->class . '">' . @$data['advance']->returnPayment->name . '</small>' ?>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 text-capitalize">{{_trans('common.Reason') }}</label>
                                                    <div class="col-sm-9">
                                                        {{ @$data['advance']->remarks }}
                                                    </div>
                                                </div>
                                                @if (auth()->user()->role->slug == 'admin')                                                    
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 text-capitalize">{{_trans('common.Approved') }}</label>
                                                        <div class="col-sm-9">
                                                            {{ @$data['advance']->approve->name }}
                                                        </div>
                                                    </div>
                                                @endif
                                                <hr>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>



                                {{-- Summary end --}}
                            </div>
                        </div>
                    </div>
                    {{-- End summary overview --}}
                </div>
            </div>
        </section>
    </div>

@endsection
