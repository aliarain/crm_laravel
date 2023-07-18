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

                        @include('backend.partials.table.show',['path'=> 'sale/product/stock-count/list' ])
                        <div class="align-self-center d-flex gap-2">
                            @if (hasPermission('sales_product_stock_count_create'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-toggle="modal"
                                    data-bs-target="#createModal" data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('sale.Stock Count')}}</span>
                                </a>
                            </div>
                            @endif

                        </div>

                        @include('backend.partials.table.search',['path'=> 'sale/product/stock-count/list' ])
                    </div>
                </div>
            </div>
       {{-- @include('backend.partials.table.export',['path'=> 'sale/product/stock-count/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Date')}}</th>
                            <th>{{_trans('file.reference')}}</th>
                            <th>{{_trans('file.Warehouse')}}</th>
                            <th>{{_trans('file.Type')}}</th>
                            <th class="not-exported">{{_trans('file.Initial File')}}</th>
                            <th class="not-exported">{{_trans('file.Final File')}}</th>
                            <th class="not-exported">{{_trans('file.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['stockCounts'] as $key=>$stock_count)
                        <?php
                                $category_name = [];
                                $brand_name = [];
                                $initial_file = 'public/stock_count/' . $stock_count->initial_file;
                                $final_file = 'public/stock_count/' . $stock_count->final_file;
                            ?>
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{ date("Y-m-d", strtotime($stock_count->created_at->toDateString())) . ' '.
                                $stock_count->created_at->toTimeString() }}</td>
                            <td>{{ $stock_count->reference_no }}</td>
                            <td>{{ $stock_count->warehouse->name }}</td>
                            @if($stock_count->type == 'full')
                            @php $type = _trans('file.Full') @endphp
                            <td>
                                <div class="badge badge-primary">{{_trans('file.Full')}}</div>
                            </td>
                            @else
                            @php $type = _trans('file.Partial') @endphp
                            <td>
                                <div class="badge badge-info">{{_trans('file.Partial')}}</div>
                            </td>
                            @endif
                            <td class="text-center">
                                <a download href="{{url($stock_count->initial_file)}}"
                                    title="{{_trans('file.Download')}}"><i class="dripicons-copy"></i></a>
                            </td>
                            <td class="text-center">
                                @if($stock_count->final_file)
                                <a download href="{{$stock_count->final_file}}" title="{{_trans('file.Download')}}"><i
                                        class="dripicons-copy"></i></a>
                                @endif
                            </td>
                            <td>
                                @if($stock_count->final_file)
                                <div class="badge badge-success final-report"
                                    data-stock_count='["{{date("Y-m-d", strtotime($stock_count->created_at->toDateString()))}}", "{{$stock_count->reference_no}}", "{{@$warehouse->name}}", "{{$type}}", "{{implode(", ", $category_name)}}", "{{implode(", ", $brand_name)}}", "{{$initial_file}}", "{{$final_file}}", "{{$stock_count->id}}"]'>
                                    {{_trans('file.Final Report')}}
                                </div>
                                @else
                                <div style="cursor: pointer;" class="badge badge-primary finalize"
                                    data-id="{{$stock_count->id}}">{{_trans('file.Finalize')}}
                                </div>
                                @endif
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
                        {{$data['stockCounts']->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>

<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleStockCount.store', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Count Stock')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
              
                <div class="row mb-30">
                    <div class="col-md-6 form-group">
                        <label class="form-label">{{_trans('file.Warehouse')}} <span class="text-danger">*</span></label>
                        <select required name="warehouse_id" id="warehouse_id"
                            class="form-select select2-input ot-input mb-3 modal_select2" data-live-search="true"
                            data-live-search-style="begins" title="Select warehouse...">
                            @foreach($ot_crm_warehouse_list as $warehouse)
                            <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-label">{{_trans('file.Type')}} <span class="text-danger">*</span></label>
                        <select class="form-select select2-input ot-input mb-3 modal_select2" name="type">
                            <option value="full">{{_trans('file.Full')}}</option>
                            <option value="partial">{{_trans('file.Partial')}}</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group" id="category">
                        <label class="form-label">{{_trans('file.category')}}</label>
                        <select name="category_id[]" id="category_id"
                            class="form-select select2-input ot-input mb-3 modal_select2" data-live-search="true"
                            data-live-search-style="begins" title="Select Category..." multiple>
                            @foreach($ot_crm_category_list as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group" id="brand">
                        <label class="form-label">{{_trans('file.Brand')}}</label>
                        <select name="brand_id[]" id="brand_id"
                            class="form-select select2-input ot-input mb-3 modal_select2" data-live-search="true"
                            data-live-search-style="begins" title="Select Brand..." multiple>
                            @foreach($ot_crm_brand_list as $brand)
                            <option value="{{$brand->id}}">{{$brand->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if (hasPermission('sales_product_stock_count_store'))
                <div class="form-group d-flex justify-content-end">
                    <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn border-0">
                </div>
                @endif

            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div id="finalizeModal" tabindex="-1" role="dialog" aria-labelledby="finalizeModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {{ Form::open(['route' => 'saleStockCount.finalize', 'method' => 'POST', 'files' => true] ) }}
            <div class="modal-header modal-header-image mb-3">
                <h5 id="exampleModalLabel" class="modal-title"> {{_trans('file.Finalize Stock Count')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
               
                <div class="form-group">
                    <label>{{_trans('file.Upload File')}} <span class="text-danger">*</span></label>
                    <input required type="file" name="final_file" class="form-control ot-form-control ot_input" />
                </div>
                <input type="hidden" name="stock_count_id">
                <div class="form-group">
                    <label>{{_trans('file.Note')}}</label>
                    <textarea rows="3" name="note" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div id="stock-count-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title"> {{_trans('file.Stock Count')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="container mt-3 pb-3">

                    <div id="stock-count-content">
                    </div>
                    <br>
                    <table class="table table-bordered stockdif-list">
                        <thead class="thead">
                            <tr>
                            <th>#</th>
                            <th>{{_trans('file.product')}}</th>
                            <th>{{_trans('file.Expected')}}</th>
                            <th>{{_trans('file.Counted')}}</th>
                            <th>{{_trans('file.Difference')}}</th>
                            <th>{{_trans('file.Cost')}}</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                        </tbody>
                    </table>
                    <div id="stock-count-footer"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@include('sale::layouts.sale-product-stock-script')
@endsection