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
                        @include('backend.partials.table.show',['path'=> 'sale/product/category/list' ])
                        <div class="align-self-center d-flex gap-2">

                            @if (hasPermission('sales_product_category_create'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-toggle="modal"
                                    data-bs-target="#createModal" data-bs-placement="right" data-bs-title="Add">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{ _trans('sale.Add') }}</span>
                                </a>
                            </div>
                            @endif
                            @if (hasPermission('sales_product_category_import'))
                            <div class="align-self-center">
                                <a href="#" role="button" class="btn-add" data-bs-placement="right" data-bs-title="Add"
                                    data-bs-toggle="modal" data-bs-target="#importCategory">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline">{{_trans('file.Import Category')}}</span>
                                </a>
                            </div>
                            @endif
                        </div>

                        @include('backend.partials.table.search',['path'=> 'sale/product/category/list' ])
                    </div>
                </div>
                {{-- @include('backend.partials.table.export',['path'=> 'sale/product/category/export' ]) --}}

            </div>
            <div class="table-responsive">
                <table class="table table-bordered user-table">
                    <thead class="thead">
                        <tr>
                            <th class="not-exported">{{ _trans('sale.SL') }}</th>
                            <th>{{ _trans('sale.Image') }}</th>
                            <th>{{ _trans('sale.category') }}</th>
                            <th>{{ _trans('sale.Parent Category') }}</th>
                            <th>{{ _trans('sale.Number of Product') }}</th>
                            <th>{{ _trans('sale.Stock Quantity') }}</th>
                            <th>{{ _trans('sale.Cost/Price') }}</th>
                            <th>{{ _trans('sale.Status') }}</th>
                            <th class="not-exported">{{ _trans('sale.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach($data['categories'] as $key=>$category)
                        <tr data-id="{{$category->id}}">
                            <td>{{$key+1}}</td>

                            @if($category->image)
                            <td> <img class="staff-profile-image-small"
                                    src="{{asset('public/images/category/'.$category->image)}}" height="80" width="80">
                            </td>
                            @else
                            <td class=""><img class="staff-profile-image-small"
                                    src="{{ url('public/static/blank_small.png') }}" alt=""></td>
                            @endif
                            <td>{{ $category->name }}</td>
                            <td>
                                @if ($category->parent_id != null)
                                {{ $category->parent->name }}
                                @else
                                {{ "N/A" }}
                                @endif
                            </td>
                            <td>{{ $category->product()->where('is_active', true)->count() }}</td>
                            <td>{{ $category->product()->where('is_active', true)->sum('qty') }}</td>
                            <td>
                                @php
                                $total_price = $category->product()->where('is_active', true)->sum(DB::raw('price *
                                qty'));
                                $total_cost = $category->product()->where('is_active', true)->sum(DB::raw('cost *
                                qty'));
                                $stock_worth = $total_price.' '.config('currency').' / '.$total_cost.'
                                '.config('currency');
                                @endphp
                                {{ @$stock_worth }}</td>
                            <td>
                                @if( $category->is_active ==1)
                                <span class="badge-success">{{ _trans('sale.Active') }}</span>
                                @else
                                <span class="badge-danger">{{ _trans('sale.Inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if (hasPermission('sales_product_category_edit'))
                                        <li>
                                            <a href="javascript:void(0);" data-id="{{$category->id}}"
                                                class="open-EditCategoryDialog dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editModal"><span class="icon mr-8"><i
                                                        class="fa-solid fa-pen-to-square"></i></span>
                                                <span>{{ _trans('common.Edit') }}</span></a>
                                        </li>
                                        @endif
                                        @if (hasPermission('sales_product_category_delete'))
                                        <li>

                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="delete_row('sale/product/category/delete', {{ $category->id }})">
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
                {{$data['categories']->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
            <!--  pagination end -->
        </div>
    </div>
</div>

<!-- Create Modal -->

<div id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            @php
            $trans = '_trans';
            @endphp
            <form id="createForm" action="{{ route('saleProductCategory.store') }}" method="POST"
                enctype="multipart/form-data">
                <div class="modal-header modal-header-image mb-3">
                    <h5 id="#" class="modal-title">{{ $trans('file.Add Category') }}</h5>
                    <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                            aria-hidden="true"><i class="las la-times"></i></span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label>{{ $trans('file.Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" required class="form-control ot-form-control"
                                placeholder="{{ $trans('sale.Category Name') }}">
                            @error('name')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label>{{ $trans('file.Image') }}</label>
                            <input type="file" name="image" class="form-control ot-input">
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label>{{ $trans('file.Parent Category') }}</label>
                            <select name="parent_id" class="form-select select2-input ot-form-controlmb-3 modal_select2">
                                <option value="">{{ $trans('file.No Parent Category') }}</option>
                                @foreach ($ot_crm_categories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-end">
                        <input type="submit" value="{{ $trans('file.submit') }}" class="crm_theme_btn">
                    </div>
                </div>
                @csrf
            </form>

        </div>
    </div>
</div>


<!-- Edit Modal -->
<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {{ Form::open(['route' => ['saleProductCategory.update'], 'method' => 'Post', 'files' => true] ) }}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Update Category')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 form-group mb-3">
                        <label class="form-label">{{_trans('file.name')}} <span class="text-danger">*</span></label>
                        {{Form::text('name',null, array('required' => 'required', 'class' => 'form-control
                        ot-form-control ot-input'))}}
                    </div>
                    <input type="hidden" name="category_id">

                    <div class="col-md-12 form-group mb-3">
                        <img src="#" id="image" alt="" height="50" width="50">
                        <label>{{_trans('file.Image')}}</label>
                        <input type="file" name="image" class="form-control ot-form-control ot-input">
                    </div>
                    <div class="col-md-12 form-group mb-20">
                        <label class="form-label">{{_trans('file.Parent Category')}}</label>
                        <select name="parent_id" class="form-select select2-input ot-form-controlmb-3 modal_select2"
                            id="parent">
                            <option value="">No {{_trans('file.parent')}}</option>
                            @foreach($data['categories'] as $category)
                            <option value="{{$category['id']}}">{{$category['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-end">
                    <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!-- Import Modal -->
<div id="importCategory" tabindex="-1" role="dialog" aria-labelledby="importCategory" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {!! Form::open(['route' => 'saleProductCategory.import', 'method' => 'post', 'files' => true]) !!}
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title">{{_trans('file.Import Category')}}</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close btn-close"><span
                        aria-hidden="true"><i class="las la-times"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-30">
                        <div class="form-group">
                            <label class="form-label">{{_trans('file.Upload CSV File')}} <span
                                    class="text-danger">*</span></label>
                            {{-- File uplode Button --}}
                            <div class="ot_fileUploader left-side mb-3">
                                <input class="form-control" type="text"
                                    placeholder="{{ _trans('common.Background Image') }}" readonly="" id="placeholder">
                                <div class="primary-btn-small-input">
                                    <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse')
                                        }}</label>
                                    <input type="file" class="d-none form-control" name="file" id="fileBrouse" required>
                                </div>
                            </div>
                            @if ($errors->has('file'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('file') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between gap-10">
                    <a href="{{ url('Modules\Sale\public\sample_file\sample_category.csv')}}" class="crm_line_btn"><i
                            class="las la-arrow-circle-down"></i> {{_trans('file.Sample Download')}}</a>
                    <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>



@endsection
@section('script')
@include('sale::layouts.sale-product-category-script')
@include('sale::layouts.delete-ajax')
@endsection