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

                        @include('backend.partials.table.show',['path'=> 'sale/product/brand/list' ])
                        <div class="align-self-center d-flex gap-2">

                            @if (hasPermission('sales_product_brand_create'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-toggle="modal"
                                    data-bs-target="#createModal" data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('common.Add')}}</span>
                                </a>
                            </div>
                            @endif
                            @if (hasPermission('sales_product_brand_import'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-placement="right" data-bs-title="Add"
                                    data-bs-toggle="modal" data-bs-target="#importBrand">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('file.Import Brand')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>
                        @include('backend.partials.table.search',['path'=> 'sale/product/brand/list' ])
                    </div>
                </div>

               {{-- @include('backend.partials.table.export',['path'=> 'sale/product/brand/export' ]) --}}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{_trans('file.Image')}}</th>
                            <th>{{_trans('file.Brand')}}</th>
                            <th>{{_trans('file.Status')}}</th>
                            <th class="not-exported">{{_trans('file.Action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['brands'] as $key=>$brand)
                        <tr data-id="{{$brand->id}}">
                            <td>{{$key+1}}</td>
                            @if($brand->image)
                            <td> <img class="staff-profile-image-small"
                                    src="{{url('public/images/brand',$brand->image)}}" height="80" width="80">
                            </td>
                            @else
                            <td class=""><img class="staff-profile-image-small"
                                    src="{{ url('public/static/blank_small.png') }}" alt=""></td>
                            @endif
                            <td>{{ $brand->title }}</td>
                            <td>
                                @if( $brand->is_active ==1)
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
                                        @if (hasPermission('sales_product_brand_edit'))
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$brand->id}}"
                                                class="open-EditbrandDialog dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editModal"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_product_brand_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/product/brand/delete', {{ $brand->id }})">
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
            {{$data['brands']->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>
<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true"
    class="modal  text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleProductBrand.store', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Add Brand')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">{{_trans('file.Title')}} <span class="text-danger">*</span></label>
                    {{Form::text('title',null,array('required' => 'required', 'class' => 'form-control ot-form-control
                    ot-input', 'placeholder'
                    => 'Type brand title...'))}}
                </div>
                <div class="form-group mb-20">
                    <label class="form-label">{{_trans('file.Image')}}</label>
                    {{Form::file('image', array('class' => 'form-control ot-form-control ot-input'))}}
                </div>
                <div class="form-group d-flex justify-content-end">
                    <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div id="importBrand" tabindex="-1" role="dialog" aria-labelledby="importBrand" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleProductBrand.import', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Import Brand')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">

                <p>{{_trans('file.The correct column order is title*, image file name and you must follow this')}}.</p>
                <p>{{_trans('file.To display Image it must be stored in public/images/brand directory')}} 
                    
                </p>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label">{{_trans('file.Upload CSV File')}} <span
                                    class="text-danger">*</span></label>
                            {{Form::file('file', array('class' => 'form-control ot-form-control ot-input','required'))}}
                        </div>
                    </div>
                    <div class="col-md-12 mb-20">
                        <div class="form-group">
                            <label> {{_trans('file.Sample File')}}</label>
                            <a href="{{ url('Modules/Sale/public/sample_file/sample_brand.csv') }}"
                                class="crm_line_btn"><i class="dripicons-download"></i>
                                {{_trans('file.Download')}}</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {{ Form::open(['route' => ['saleProductBrand.update'], 'method' => 'post', 'files' => true] ) }}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title"> {{_trans('file.Update Brand')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{_trans('file.Title')}} <span class="text-danger">*</span></label>
                    {{Form::text('title',null, array('required' => 'required', 'class' => 'form-control ot-form-control
                    ot-input'))}}
                </div>
                <input type="hidden" name="brand_id">
                <img src="#" id="image" alt="" height="50" width="50">
                <div class="form-group">
                    <label>{{_trans('file.Image')}}</label>
                    {{Form::file('image', array('class' => 'form-control ot-form-control ot-input'))}}
                </div>
                <div class="form-group d-flex justify-content-end mt-20">
                    <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@endsection
@section('script')
@include('sale::layouts.sale-product-brand-script')
@include('sale::layouts.delete-ajax')
@endsection