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
            <div
                class="table-toolbar d-flex flex-wrap gap-2 flex-column flex-xl-row justify-content-center justify-content-xxl-between align-content-center pb-3">
                <div class="align-self-center">
                    <div
                        class="d-flex flex-wrap gap-2 flex-column flex-lg-row justify-content-center align-content-center">
                          <!-- show per page -->
                          @include('backend.partials.table.show',['path'=> 'sale/expense/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_expense_create'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-toggle="modal"
                                    data-bs-target="#createModal" data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>

                        @include('backend.partials.table.search',['path'=> 'sale/expense/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/expense/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Date')}}</th>
                            <th>{{_trans('file.reference')}} No</th>
                            <th>{{_trans('file.Warehouse')}}</th>
                            <th>{{_trans('file.category')}}</th>
                            <th>{{_trans('file.Amount')}}</th>
                            <th>{{_trans('file.Note')}}</th>
                            <th class="not-exported">{{_trans('file.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['expenses'] as $key=>$expense)
                        <tr data-id="{{$expense->id}}">
                            <td>{{$key+1}}</td>
                            <td>{{ date("Y-m-d", strtotime($expense->created_at->toDateString())) }}</td>
                            <td>{{ $expense->reference_no }}</td>
                            <td>{{ $expense->warehouse->name }}</td>
                            <td>{{ $expense->expenseCategory->name }}</td>
                            <td>{{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->note }}</td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_expense_edit'))
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$expense->id}}"
                                                class="open-EditexpenseDialog dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editModal"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_expense_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/expense/delete', {{ $expense->id }})">
                                                <span class="icon mr-12"><i class="fa-solid fa-trash-can"></i></span>
                                                <span>{{ _trans('common.delete') }}</span>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <!--  pagination start -->
            <div class="ot-pagination pagination-content d-flex justify-content-end align-content-center py-3">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-between">
                        {{$data['expenses']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>
<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 id="exampleModalLabel" class="modal-title">{{_trans('file.Add Expense')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
         
                {!! Form::open(['route' => ['saleExpense.store'], 'method' => 'post']) !!}

                <div class="row">
                    <div class="col-md-12 form-group mb-3">
                        <label class="form-label">{{_trans('file.Date')}}</label>
                        <input type="date" name="created_at" class="form-control ot-form-control ot-input date"/>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label class="form-label">{{_trans('file.Expense Category')}} <span class="text-danger">*</span></label>
                        <select name="expense_category_id" class="form-select select2-input ot-input mb-3 modal_select2"
                            required data-live-search="true" data-live-search-style="begins"
                            title="Select Expense Category...">
                            @foreach($categories as $expense_category)
                            <option value="{{$expense_category->id}}">{{$expense_category->name . ' (' .
                                $expense_category->code. ')'}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label class="form-label">{{_trans('file.Warehouse')}} <span class="text-danger">*</span></label>
                        <select name="warehouse_id" class="form-select select2-input ot-input mb-3 modal_select2"
                            required data-live-search="true" data-live-search-style="begins">
                            <option value="" disabled>Select One</option>
                            @foreach($warehouses as $warehouse)
                            <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label class="form-label">{{_trans('file.Amount')}} <span class="text-danger">*</span></label>
                        <input type="number" name="amount" step="any" required placeholder="{{_trans('file.Amount')}}"
                            class="form-control ot-form-control ot-input">
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label class="form-label"> {{_trans('file.Account')}}</label>
                        <select class="form-select select2-input ot-input mb-3 modal_select2" name="account_id">
                            @foreach($accounts as $account)
                            @if($account->is_default)
                            <option selected value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]
                            </option>
                            @else
                            <option value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">{{_trans('file.Note')}}</label>
                    <textarea name="note" rows="3" class="form-control ot-form-control ot-input" placeholder="{{_trans('file.Note')}}"></textarea>
                </div>
                <div class="form-group d-flex justify-content-end mt-20">
                    <button type="submit" class="crm_theme_btn">{{_trans('file.submit')}}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 id="exampleModalLabel" class="modal-title">{{_trans('file.Update Expense')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
      
                {!! Form::open(['route' => ['saleExpense.update'], 'method' => 'post']) !!}
                <div class="form-group mb-3">
                    <input type="hidden" name="expense_id">
                    <label class="form-label">{{_trans('file.reference')}}</label>
                    <p id="reference">{{'er-' . date("Ymd") . '-'. date("his")}}</p>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Date')}}</label>
                        <input type="date" name="created_at" class="form-control ot-form-control ot-input date" />
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Expense Category')}} <span class="text-danger">*</span></label>
                        <select name="expense_category_id" class="form-select select2-input ot-input mb-3 modal_select2"
                            required data-live-search="true" data-live-search-style="begins"
                            title="Select Expense Category...">
                            @foreach($categories as $expense_category)
                            <option value="{{$expense_category->id}}">{{$expense_category->name . ' (' .
                                $expense_category->code. ')'}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Warehouse')}} <span class="text-danger">*</span></label>
                        <select name="warehouse_id" class="form-select select2-input ot-input mb-3 modal_select2"
                            required data-live-search="true" data-live-search-style="begins"
                            title="Select Warehouse...">
                            @foreach($warehouses as $warehouse)
                            <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">{{_trans('file.Amount')}} <span class="text-danger">*</span></label>
                        <input type="number" name="amount" step="any" required
                            class="form-control ot-form-control ot-input">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label"> {{_trans('file.Account')}}</label>
                        <select class="form-select select2-input ot-input mb-3 modal_select2" name="account_id">
                            @foreach($accounts as $account)
                            @if($account->is_default)
                            <option selected value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]
                            </option>
                            @else
                            <option value="{{$account->id}}">{{$account->name}} [{{$account->account_no}}]</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group mb-20">
                    <label class="form-label">{{_trans('file.Note')}}</label>
                    <textarea name="note" rows="3" class="form-control ot-form-control ot-input"></textarea>
                </div>
                <div class="form-group d-flex justify-content-end">
                    <button type="submit" class="crm_theme_btn">{{_trans('file.submit')}}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

@include('sale::layouts.sale-expense-script')


@include('sale::layouts.delete-ajax')
@endsection