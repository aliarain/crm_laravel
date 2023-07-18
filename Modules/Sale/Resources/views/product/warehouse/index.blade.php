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

                            @include('backend.partials.table.show',['path'=> 'sale/product/warehouse/list' ])
                            <div class="align-self-center d-flex gap-2">
                                @if (hasPermission('sales_product_warehouse_create'))
                                <div class="align-self-center">
                                    <a href="#" role="button" class="btn-add"  data-bs-toggle="modal" data-bs-target="#createModal"
                                        data-bs-placement="right" data-bs-title="Add">
                                        <span><i class="fa-solid fa-plus"></i> </span>
                                        <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                    </a>
                                </div>
                                @endif
                                @if (hasPermission('sales_product_warehouse_import'))
                                <div class="align-self-center">
                                    <a href="#" role="button" class="btn-add" 
                                        data-bs-placement="right" data-bs-title="Add" data-bs-toggle="modal" data-bs-target="#importWarehouse">
                                        <span><i class="fa-solid fa-plus"></i> </span>
                                        <span class="d-none d-xl-inline">{{_trans('file.Import Warehouse')}}</span>
                                    </a>
                                </div>
                                @endif
                            </div>
                            @include('backend.partials.table.search',['path'=> 'sale/product/warehouse/list' ])
                        </div>
                    </div>
                   {{-- @include('backend.partials.table.export',['path'=> 'sale/product/warehouse/export' ]) --}}
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered user-table">
                        <thead class="thead">
                            <tr>
                                <th class="not-exported"></th>
                                <th>{{_trans('file.Warehouse')}}</th>
                                <th>{{_trans('file.Phone Number')}}</th>
                                <th>{{_trans('file.Email')}}</th>
                                <th>{{_trans('file.Address')}}</th>
                                <th>{{_trans('file.Number of Product')}}</th>
                                <th>{{_trans('file.Stock Quantity')}}</th>
                                <th>{{_trans('file.Status')}}</th>
                                <th class="not-exported">{{_trans('file.Action')}}</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            @foreach($data['warehouses'] as $key=>$warehouse)
                            <tr data-id="{{$warehouse->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{ $warehouse->name }}</td>
                                <td>{{ $warehouse->phone}}</td>
                                <td>{{ $warehouse->email}}</td>
                                <td>{{ $warehouse->address}}</td>
                                <td>{{ @$number_of_product??0}}</td>
                                <td>{{ @$stock_qty??0}}</td>
                                <td>
                                    @if( $warehouse->is_active ==1)
                                    <span class="badge-success">Active</span>
                                    @else
                                    <span class="badge-danger">Inactive</span>
                                    @endif 
                                </td>
                                <td>
                                    <div class="dropdown dropdown-action">
                                        <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if (hasPermission('sales_product_warehouse_edit'))
                                            <li>
                                                <a href="javascript:void(0);" data-id="{{$warehouse->id}}"
                                                    class="open-EditWarehouseDialog dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#editModal"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                            </li>
                                            @endif
                                            @if (hasPermission('sales_product_warehouse_delete'))
                                            <li>
                                                
                                                <a class="dropdown-item" href="javascript:void(0);"
                                                    onclick="delete_row('sale/product/warehouse/delete', {{ $warehouse->id }})">
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
                            {{$data['warehouses']->appends(request()->input())->links('pagination::bootstrap-4') }}
                        </ul>
                    </nav>
                </div>
                <!--  pagination end -->
            </div>
        </div>
    </div>
<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
          {!! Form::open(['route' => 'saleProductWarehouse.store', 'method' => 'post']) !!}
        <div class="modal-header modal-header-image mb-3">
          <h5 class="modal-title">{{_trans('file.Add Warehouse')}}</h5>
          <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
            aria-hidden="true"><i class="fa fa-times "></i></span></button>
        </div>
        <div class="modal-body">
            <div class="form-group mb-3">
              <label class="form-label">{{_trans('file.Name')}} <span class="text-danger">*</span></label>
              <input type="text" placeholder="{{_trans('file.Warehouse Name')}}" name="name" required="required" class="form-control ot-form-control ot-input">
            </div>
            <div class="form-group mb-3">
              <label class="form-label">{{_trans('file.Phone Number')}} <span class="text-danger">*</span></label>
              <input type="text" name="phone" placeholder="{{_trans('file.Phone')}}" class="form-control ot-form-control ot-input" required>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">{{_trans('file.Email')}}</label>
              <input type="email" name="email" placeholder="{{_trans('file.Email')}}" class="form-control ot-form-control ot-input">
            </div>
            <div class="form-group mb-20">
              <label class="form-label">{{_trans('file.Address')}} <span class="text-danger">*</span></label>
              <textarea required placeholder="{{_trans('file.Address')}}" class="form-control ot-form-control ot-input" rows="3" name="address"></textarea>
            </div>
            <div class="form-group d-flex justify-content-end">
              <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
            </div>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
  
  <div id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
          {!! Form::open(['route' => ['saleProductWarehouse.update'], 'method' => 'post']) !!}
        <div class="modal-header modal-header-image mb-3">
          <h5 class="modal-title"> {{_trans('file.Update Warehouse')}}</h5>
          <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
            aria-hidden="true"><i class="fa fa-times "></i></span></button>
        </div>
        <div class="modal-body">
        
            <div class="form-group mb-3">
                <input type="hidden"  name="warehouse_id">
              <label class="form-label">{{_trans('file.name')}} <span class="text-danger">*</span></label>
              <input type="text" placeholder="Type WareHouse Name..." name="name" required="required" class="form-control ot-form-control ot-input">
            </div>
            <div class="form-group mb-3">
              <label class="form-label">{{_trans('file.Phone Number')}} <span class="text-danger">*</span></label>
              <input type="text" name="phone" class="form-control ot-form-control ot-input" required>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">{{_trans('file.Email')}}</label>
              <input type="email" name="email" placeholder="example@example.com" class="form-control ot-form-control ot-input">
            </div>
            <div class="form-group mb-20">
              <label class="form-label">{{_trans('file.Address')}} <span class="text-danger">*</span></label>
              <textarea class="form-control ot-form-control ot-input" rows="3" name="address" required></textarea>
            </div>
            <div class="form-group d-flex justify-content-end">
              <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
            </div>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
  
  <div id="importWarehouse" tabindex="-1" role="dialog" aria-labelledby="importWarehouse" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
          {!! Form::open(['route' => 'saleProductWarehouse.import', 'method' => 'post', 'files' => true]) !!}
        <div class="modal-header modal-header-image mb-3">
          <h5 class="modal-title">{{_trans('file.Import Warehouse')}}</h5>
          <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
            aria-hidden="true"><i class="fa fa-times "></i></span></button>
        </div>
        <div class="modal-body">
           <p>{{_trans('file.The correct column order is')}} (name*, phone, email, address*) {{_trans('file.and you must follow this')}}.</p>
          <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label class="form-label">{{_trans('file.Upload CSV File')}} <span class="text-danger">*</span></label>
                        {{Form::file('file', array('class' => 'form-control ot-form-control ot-input','required'))}}
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label class="form-label"> {{_trans('file.Sample File')}}</label>
                        <a href="{{ url('Modules/Sale/public/sample_file/sample_warehouse.csv')}}" class="crm_line_btn"><i class="dripicons-download"></i>  {{_trans('file.Download')}}</a>
                    </div>
                </div>
          </div>
          <div class="d-flex justify-content-end mt-20">
            <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
          </div>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
@endsection
@section('script')
@include('sale::layouts.sale-product-warehouse-script')
@include('sale::layouts.delete-ajax')
@endsection