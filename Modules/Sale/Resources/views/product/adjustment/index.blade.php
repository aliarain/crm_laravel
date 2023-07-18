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

                            @include('backend.partials.table.show',['path'=> 'sale/product/adjustment/list' ])
                            <div class="align-self-center d-flex gap-2">
                                @if (hasPermission('sales_product_stock_adjustment_create'))
                                <div class="align-self-center">
                                    <a href="{{ route('saleAdjustment.create') }}" role="button" class="btn-add" data-bs-placement="right" data-bs-title="Add">
                                        <span><i class="fa-solid fa-plus"></i> </span>
                                        <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                    </a>
                                </div>
                                @endif
                            </div>

                            @include('backend.partials.table.search',['path'=> 'sale/product/adjustment/list' ])

                        </div>
                    </div>

                 {{-- @include('backend.partials.table.export',['path'=> 'sale/product/adjustment/export' ]) --}}
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered user-table">
                        <thead class="thead">
                            <tr>
                                <th class="not-exported"></th>
                                <th>{{_trans('file.Date')}}</th>
                                <th>{{_trans('file.reference')}}</th>
                                <th>{{_trans('file.Warehouse')}}</th>
                                <th>{{_trans('file.product')}}s</th>
                                <th>{{_trans('file.Note')}}</th>
                                <th class="not-exported">{{_trans('file.action')}}</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            @foreach($data['adjustments'] as $key=>$adjustment)
                            <tr data-id="{{$adjustment->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{ date('d-m-Y', strtotime($adjustment->created_at->toDateString())) . ' '. $adjustment->created_at->toTimeString() }}</td>
                                <td>{{ $adjustment->reference_no }}</td>
                                <?php $warehouse = DB::table('sale_warehouses')->find($adjustment->warehouse_id) ?>
                                <td>{{ $warehouse->name }}</td>
                                <td>
                                <?php
                                    $product_adjustment_data = DB::table('sale_product_adjustments')->where('adjustment_id', $adjustment->id)->get();
                                    foreach ($product_adjustment_data as $key => $product_adjustment) {
                                        if($product_adjustment->variant_id) {
                                            $product = DB::table('sale_products')
                                                    ->join('sale_product_variants', 'sale_products.id', '=', 'sale_product_variants.product_id')
                                                    ->select('sale_products.name','sale_product_variants.item_code as code')
                                                    ->where([
                                                        ['product_id', $product_adjustment->product_id],
                                                        ['variant_id', $product_adjustment->variant_id]
                                                    ])->first();
                                        }
                                        else {
                                            $product = DB::table('sale_products')->select('name','code')->find($product_adjustment->product_id);
                                        }

                                        if($key)
                                            echo '<br>';
                                        echo $product->name.' ['.$product->code.']';
                                    }
                                ?>
                                </td>
                                <td>{{$adjustment->note}}</td>
                                <td>
                                    @if( $adjustment->is_active ==1)
                                    <span class="badge-success">{{_trans('common.Active')}}</span>
                                    @else
                                    <span class="badge-danger">{{_trans('common.Inactive')}}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown dropdown-action">
                                        <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if (hasPermission('sales_product_stock_adjustment_edit'))
                                            <li>
                                                <a href="{{ route('saleAdjustment.edit', $adjustment->id) }}"
                                                    class="dropdown-item"><span class="icon mr-8"><i
                                                            class="fa-solid fa-pen-to-square"></i></span>
                                                    <span>{{ _trans('common.Edit') }}</span></a>
                                            </li>
                                            @endif
                                            @if (hasPermission('sales_product_stock_adjustment_delete'))
                                            <li>

                                                <a class="dropdown-item" href="javascript:void(0);"
                                                    onclick="delete_row('sale/product/adjustment/delete', {{ $adjustment->id }})">
                                                    <span class="icon mr-12"><i
                                                            class="fa-solid fa-trash-can"></i></span>
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
                            {{$data['adjustments']->appends(request()->input())->links('pagination::bootstrap-4') }}
                        </ul>
                    </nav>
                </div>
                <!--  pagination end -->
            </div>
        </div>
    </div>
@endsection
@section('script')
@include('sale::layouts.sale-product-adjustment-script')
@include('sale::layouts.delete-ajax')
@endsection