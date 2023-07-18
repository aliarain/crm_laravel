@extends('backend.layouts.app')
@section('content')
{!! breadcrumb([
'title' => @$data['title'],
route('admin.dashboard') => _trans('common.Dashboard'),
'#' => @$data['title'],
]) !!}
<div class="row  table-content table-basic">
    <div class="col-md-12">
        <div class="card ot-card">
            <div class="card-header d-flex align-items-center">
                <h4>{{_trans('file.Update Product')}}</h4>
            </div>
            <div class="card-body">
              
                <form id="product-form">
                    <input type="hidden" name="id" value="{{$ot_crm_product_data->id}}" />
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Product Type')}} *</strong> </label>
                                <div class="input-group">
                                    <select name="type" required
                                        class="form-select select2-input ot-input mb-3 modal_select2" id="type">
                                        <option value="standard">{{_trans('common.Standard')}}</option>
                                        <option value="combo">{{_trans('common.Combo')}}</option>
                                        <option value="digital">{{_trans('common.Digital')}}</option>
                                        <option value="service">{{_trans('common.Service')}}</option>
                                    </select>
                                    <input type="hidden" name="type_hidden" value="{{$ot_crm_product_data->type}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Product Name')}} *</strong> </label>
                                <input type="text" name="name" value="{{$ot_crm_product_data->name}}" required
                                    class="form-control ot-form-control ot-input">
                                <span class="validation-msg" id="name-error"></span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Product Code')}} *</strong> </label>
                                <div class="input-group">
                                    <input type="text" name="code" id="code" value="{{$ot_crm_product_data->code}}"
                                        class="form-control ot-form-control ot-input" required>
                                    <div class="input-group-append">
                                        <button id="genbutton" type="button" class="btn btn-sm btn-default"
                                            title="{{_trans('file.Generate')}}"><i class="fa fa-refresh"></i></button>
                                    </div>
                                </div>
                                <span class="validation-msg" id="code-error"></span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Barcode Symbology')}} *</strong> </label>
                                <div class="input-group">
                                    <input type="hidden" name="barcode_symbology_hidden"
                                        value="{{$ot_crm_product_data->barcode_symbology}}">
                                    <select name="barcode_symbology" required
                                        class="form-select select2-input ot-input mb-3 modal_select2">
                                        <option value="UPCE">{{_trans('common.UPC-E')}}</option>
                                        <option value="C128">{{_trans('common.Code 128')}}</option>
                                        <option value="C39">{{_trans('common.Code 39')}}</option>
                                        <option value="UPCA">{{_trans('common.UPC-A')}}</option>
                                        <option value="EAN8">{{_trans('common.EAN-8')}}</option>
                                        <option value="EAN13">{{_trans('common.EAN-13')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="digital" class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Attach File')}}</strong> </label>
                                <div class="input-group">
                                    <input type="file" name="file" class="form-control ot-form-control ot-input">
                                </div>
                                <span class="validation-msg"></span>
                            </div>
                        </div>
                        <div id="combo" class="col-md-9 mb-1 table-content table-basic">
                            <label>{{_trans('file.add_product')}}</label>
                            <div class="search-box input-group mb-3">
                                <button class="btn btn-secondary"><i class="fa fa-barcode"></i></button>
                                <input type="text" name="product_code_name" id="ot_crm_productcodeSearch"
                                    placeholder="Please type product code and select..."
                                    class="form-control ot-form-control ot-input" />
                            </div>
                            <label>{{_trans('file.Combo Products')}}</label>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-hover order-list">
                                    <thead class="thead">
                                        <tr>
                                            <th>{{_trans('file.product')}}</th>
                                            <th>{{_trans('file.Quantity')}}</th>
                                            <th>{{_trans('file.Unit Price')}}</th>
                                            <th><i class="dripicons-trash"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @if($ot_crm_product_data->type == 'combo')
                                        <?php
                                                    $product_list = explode(",", $ot_crm_product_data->product_list);
                                                    $qty_list = explode(",", $ot_crm_product_data->qty_list);
                                                    $variant_list = explode(",", $ot_crm_product_data->variant_list);
                                                    $price_list = explode(",", $ot_crm_product_data->price_list);
                                                ?>
                                        @foreach($product_list as $key=>$id)
                                        <tr>
                                            <?php
                                                        $product = Modules\Sale\Entities\SaleProduct::find($id);
                                                        if($ot_crm_product_data->variant_list && $variant_list[$key]) {
                                                            $product_variant_data = Modules\Sale\Entities\SaleProductVariant::select('item_code')->FindExactProduct($id, $variant_list[$key])->first();
                                                            $product->code = $product_variant_data->item_code;
                                                        }
                                                        else
                                                            $variant_list[$key] = "";
                                                    ?>
                                            <td>{{$product->name}} [{{$product->code}}]</td>
                                            <td><input type="number" class="form-control qty" name="product_qty[]"
                                                    value="{{$qty_list[$key]}}" step="any"></td>
                                            <td><input type="number" class="form-control unit_price" name="unit_price[]"
                                                    value="{{$price_list[$key]}}" step="any" />
                                            </td>
                                            <td><button type="button" class="ibtnDel btn btn-danger btn-sm">X</button>
                                            </td>
                                            <input type="hidden" class="product-id" name="product_id[]"
                                                value="{{$id}}" />
                                            <input type="hidden" class="variant-id" name="variant_id[]"
                                                value="{{$variant_list[$key]}}" />
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Brand')}}</strong> </label>
                                <div class="input-group">
                                    <input type="hidden" name="brand" value="{{ $ot_crm_product_data->brand_id}}">
                                    <select name="brand_id"
                                        class="form-select select2-input ot-input mb-3 modal_select2"
                                        data-live-search="true" data-live-search-style="begins" title="Select Brand...">
                                        @foreach($ot_crm_brand_list as $brand)
                                        <option value="{{$brand->id}}">{{$brand->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <input type="hidden" name="category" value="{{$ot_crm_product_data->category_id}}">
                                <label>{{_trans('file.category')}} *</strong> </label>
                                <div class="input-group">
                                    <select name="category_id" required
                                        class="form-select select2-input ot-input mb-3 modal_select2"
                                        data-live-search="true" data-live-search-style="begins"
                                        title="Select Category...">
                                        @foreach($ot_crm_category_list as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="unit" class="col-md-12 mb-3">
                            <div class="row ">
                                <div class="col-md-4">
                                    <label>{{_trans('file.Product Unit')}} *</strong> </label>
                                    <div class="input-group">
                                        <select required class="form-select select2-input ot-input mb-3 modal_select2"
                                            data-live-search="true" data-live-search-style="begins"
                                            title="Select unit..." name="unit_id">
                                            @foreach($ot_crm_unit_list as $unit)
                                            @if($unit->base_unit==null)
                                            <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="unit" value="{{ $ot_crm_product_data->unit_id}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>{{_trans('file.Sale Unit')}}</strong> </label>
                                    <div class="input-group">
                                        <select class="form-select select2-input ot-input mb-3 modal_select2"
                                            name="sale_unit_id" id="sale-unit">
                                        </select>
                                        <input type="hidden" name="sale_unit"
                                            value="{{ $ot_crm_product_data->sale_unit_id}}">
                                    </div>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <div class="form-group">
                                        <label>{{_trans('file.Purchase Unit')}}</strong> </label>
                                        <div class="input-group">
                                            <select class="form-select select2-input ot-input mb-3 modal_select2"
                                                name="purchase_unit_id">
                                            </select>
                                            <input type="hidden" name="purchase_unit"
                                                value="{{ $ot_crm_product_data->purchase_unit_id}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="cost" class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Product Cost')}} *</strong> </label>
                                <input type="number" name="cost" value="{{$ot_crm_product_data->cost}}" required
                                    class="form-control ot-form-control ot-input" step="any">
                                <span class="validation-msg"></span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Product Price')}} *</strong> </label>
                                <input type="number" name="price" value="{{$ot_crm_product_data->price}}" required
                                    class="form-control ot-form-control ot-input" step="any">
                                <span class="validation-msg"></span>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="qty" value="{{ $ot_crm_product_data->qty }}"
                                    class="form-control ot-form-control ot-input">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Daily Sale Objective')}}</strong> </label>
                                <input type="number" name="daily_sale_objective"
                                    class="form-control ot-form-control ot-input" step="any"
                                    value="{{$ot_crm_product_data->daily_sale_objective}}">
                            </div>
                        </div>
                        <div id="alert-qty" class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Alert Quantity')}}</strong> </label>
                                <input type="number" name="alert_quantity"
                                    value="{{$ot_crm_product_data->alert_quantity}}"
                                    class="form-control ot-form-control ot-input" step="any">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <input type="hidden" name="tax" value="{{$ot_crm_product_data->tax_id}}">
                                <label>{{_trans('file.product')}} {{_trans('file.Tax')}}</strong> </label>
                                <select name="tax_id" class="form-select select2-input ot-input mb-3 modal_select2">
                                    <option value="">{{_trans('common.No Tax')}}</option>
                                    @foreach($ot_crm_tax_list as $tax)
                                    <option value="{{$tax->id}}">{{$tax->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <input type="hidden" name="tax_method_id" value="{{$ot_crm_product_data->tax_method}}">
                                <label>{{_trans('file.Tax Method')}}</strong> </label>
                                <select name="tax_method" class="form-select select2-input ot-input mb-3 modal_select2">
                                    <option value="1">{{_trans('file.Exclusive')}}</option>
                                    <option value="2">{{_trans('file.Inclusive')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio">
                                    <div class="form-check d-flex align-items-center">
                                        @if($ot_crm_product_data->featured)
                                        <input type="checkbox" name="featured" value="1"
                                            class="form-check-input mt-0 mr-4 read common-key" checked>
                                        @else
                                        <input type="checkbox" name="featured"
                                            class="form-check-input mt-0 mr-4 read common-key" value="1">
                                        @endif
                                        <label class="custom-control-label">{{_trans('common.Featured')}}</label>
                                    </div>
                                </div>
                                <div class="timeline-info">
                                    <span class="info-details fw-light">{{_trans('common.Featured product will be displayed in POS')}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio">
                                    <div class="form-check d-flex align-items-center">
                                        @if($ot_crm_product_data->is_embeded)
                                        <input type="checkbox" name="is_embeded" value="1"
                                            class="form-check-input mt-0 mr-4 read common-key" checked>
                                        @else
                                        <input type="checkbox" name="is_embeded" value="1"
                                            class="form-check-input mt-0 mr-4 read common-key">
                                        @endif
                                        <label class="custom-control-label">{{_trans('common.Embedded Barcode')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Product Image')}}</strong> </label> <i class="dripicons-question"
                                    data-toggle="tooltip"
                                    title="{{_trans('file.You can upload multiple image. Only .jpeg, .jpg, .png, .gif file can be uploaded. First image will be base image.')}}"></i>
                                <div id="imageUpload" class="dropzone"></div>
                                <span class="validation-msg" id="image-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <table class="table table-hover">
                                    <thead class="thead">
                                        <tr>
                                            <th><button type="button" class="btn btn-sm"><i
                                                        class="fa fa-list"></i></button></th>
                                            <th>{{_trans('common.Image')}}</th>
                                            <th>{{_trans('common.Remove')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        <?php $images = explode(",", $ot_crm_product_data->image)?>
                                        @foreach($images as $key => $image)
                                        <tr>
                                            <td><button type="button" class="btn btn-sm"><i
                                                        class="fa fa-list"></i></button></i></td>
                                            @if ($image)
                                            <td>
                                                <img src="{{url('public/images/product', $image)}}" height="60"
                                                    width="60">
                                                <input type="hidden" name="prev_img[]" value="{{$image}}">
                                            </td>
                                            @else
                                            <td>
                                                <img src="{{url('public/static/blank_small.png')}}" height="60"
                                                    width="60">
                                            </td>
                                            @endif            
                                        
                                            <td><button type="button"
                                                    class="btn btn-sm btn-danger remove-img">X</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Product Details')}}</label>
                                <textarea name="product_details" class="form-control ot-form-control ot_input"
                                    rows="5">{{str_replace('@', '"', $ot_crm_product_data->product_details)}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="permission-list-td form-group">
                                <div class="input-check-radio" id="diffPrice-option">
                                    <div class="form-check d-flex align-items-center">
                                        @if($ot_crm_product_data->is_diffPrice)
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_diffPrice" id="is-diffPrice" value="1" checked>
                                        @else
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_diffPrice" id="is-diffPrice" value="1">
                                        @endif
                                        <label class="custom-control-label" for="user_create">{{_trans('common.This product has different price for different warehouse')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 " id="diffPrice-section">
                            <div class="table-responsive ml-2">
                                <table id="diffPrice-table" class="table table-hover table-bordered">
                                    <thead class="thead">
                                        <tr>
                                            <th>{{_trans('file.Warehouse')}}</th>
                                            <th>{{_trans('file.Price')}}</th>
                                        </tr>

                                    </thead>
                                    <tbody class="tbody">
                                        @foreach($ot_crm_warehouse_list as $warehouse)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="warehouse_id[]" value="{{$warehouse->id}}">
                                                {{$warehouse->name}}
                                            </td>
                                            <td>
                                                <?php
                                                            $product_warehouse = Modules\Sale\Entities\SaleProductWarehouse::FindProductWithoutVariant($ot_crm_product_data->id, $warehouse->id)->first();
                                                        ?>
                                                @if($product_warehouse)
                                                <input type="number" name="diff_price[]"
                                                    class="form-control ot-form-control ot_input"
                                                    value="{{$product_warehouse->price}}">
                                                @else
                                                <input type="number" name="diff_price[]" class="form-control ot_input">
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="permission-list-td form-group">
                                <div class="input-check-radio" id="batch-option">
                                    <div class="form-check d-flex align-items-center">
                                        @if($ot_crm_product_data->is_batch)
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_batch" id="is-batch" value="1" checked>
                                        @else
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_batch" id="is-batch" value="1">
                                        @endif
                                        <label class="custom-control-label" for="user_create">{{_trans('file.This product has batch and expired date')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" id="imei-option">
                                    <div class="form-check d-flex align-items-center">
                                        @if($ot_crm_product_data->is_imei)
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_imei" name="is_imei" checked>
                                        @else
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_imei" name="is_imei">
                                        @endif
                                        <label class="custom-control-label" for="user_create">{{_trans('file.This product has IMEI or Serial numbers')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" id="variant-option">
                                    <div class="form-check d-flex align-items-center">
                                        @if($ot_crm_product_data->is_variant)
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_variant" id="is-variant" value="1" checked>
                                        @else
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_variant" id="is-variant" value="1">
                                        @endif

                                        <label class="custom-control-label" for="user_create">{{_trans('file.This product has variant')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3" id="variant-section">
                            @if($ot_crm_product_data->variant_option)
                            <div class="row" id="variant-input-section">
                                @foreach($ot_crm_product_data->variant_option as $key => $variant_option)
                                <?php 
                                            $noOfVariantValue += count(explode(",", $ot_crm_product_data->variant_value[$key]));
                                        ?>
                                <div class="col-md-4 form-group mt-2">
                                    <label>{{_trans('file.Option')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="variant_option[]" class="form-control variant-field"
                                        value="{{$ot_crm_product_data->variant_option[$key]}}">
                                </div>
                                <div class="col-md-6 form-group mt-2">
                                    <label>{{_trans('file.Value')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="variant_value[]"
                                        class="type-variant form-control variant-field"
                                        value="{{$ot_crm_product_data->variant_value[$key]}}">
                                </div>
                                @endforeach
                            </div>
                            @endif
                            <div class="col-md-12 form-group">
                                <button type="button" class="btn btn-info add-more-variant"><i
                                        class="dripicons-plus"></i> {{_trans('file.Add More Variant')}}</button>
                            </div>
                            <div class="table-responsive ml-2">
                                <table id="variant-table" class="table table-hover variant-list">
                                    <thead class="thead">
                                        <tr>
                                            <th>{{_trans('file.name')}}</th>
                                            <th>{{_trans('file.Item Code')}}</th>
                                            <th>{{_trans('file.Additional Cost')}}</th>
                                            <th>{{_trans('file.Additional Price')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach($ot_crm_product_variant_data as $key=> $variant)
                                        <tr>
                                            <td>{{$variant->name}}
                                                <input type="hidden" class="form-control variant-name"
                                                    name="variant_name[]" value="{{$variant->name}}" />
                                            </td>
                                            <td><input type="text" class="form-control ot-form-control ot_input"
                                                    name="item_code[]" value="{{$variant->pivot['item_code']}}" />
                                            </td>
                                            <td><input type="number" class="form-control additional-cost"
                                                    name="additional_cost[]"
                                                    value="{{$variant->pivot['additional_cost']}}" step="any" />
                                            </td>
                                            <td><input type="number" class="form-control additional-price"
                                                    name="additional_price[]"
                                                    value="{{$variant->pivot['additional_price']}}" step="any" />
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" id="imei-option">
                                    <div class="form-check d-flex align-items-center">

                                        <input type="hidden" name="promotion_hidden"
                                            value="{{$ot_crm_product_data->promotion}}">
                                        <input name="promotion" type="checkbox" id="promotion" value="1"
                                            class="form-check-input mt-0 mr-4 read common-key">&nbsp;
                                        <label class="custom-control-label" for="user_create">{{_trans('common.Add Promotional Price')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-4" id="promotion_price"> <label>{{_trans('file.Promotional Price')}}</label>
                                    <input type="number" name="promotion_price"
                                        value="{{$ot_crm_product_data->promotion_price}}"
                                        class="form-control ot-form-control ot_input" step="any" />
                                </div>
                                <div id="start_date" class="col-md-4">
                                    <div class="form-group">
                                        <label>{{_trans('file.Promotion Starts')}}</label>
                                        <input type="text" name="starting_date"
                                            value="{{$ot_crm_product_data->starting_date}}" id="starting_date"
                                            class="form-control ot-form-control ot_input" />
                                    </div>
                                </div>
                                <div id="last_date" class="col-md-4">
                                    <div class="form-group">
                                        <label>{{_trans('file.Promotion Ends')}}</label>
                                        <input type="text" name="last_date" value="{{$ot_crm_product_data->last_date}}"
                                            id="ending_date" class="form-control ot-form-control ot_input" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (hasPermission('sales_products_update'))

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="button" value="{{_trans('file.submit')}}" class="crm_theme_btn"
                                    id="submit-btn">
                            </div>
                        </div>
                        @endif

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ url('Modules\Sale\public\tinymce\js\tinymce\tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ url('Modules\Sale\public\js\dropzone.js') }}"></script>
<script type="text/javascript">
    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    var product_id = <?php echo json_encode($ot_crm_product_data->id) ?>;
    var is_batch = <?php echo json_encode($ot_crm_product_data->is_batch) ?>;
    var is_variant = <?php echo json_encode($ot_crm_product_data->is_variant) ?>;
    var redirectUrl = <?php echo json_encode(url('sale/product/product/list')); ?>;
    var variantPlaceholder = <?php echo json_encode(_trans('file.Enter variant value seperated by comma')); ?>;
    var variantIds = [];
    var combinations = [];
    var oldCombinations = [];
    var step;
    var count = 1;
    var customizedVariantCode = 1;
    var noOfVariantValue = <?php echo json_encode($noOfVariantValue); ?>;
    $('[data-toggle="tooltip"]').tooltip();

    $(".remove-img").on("click", function () {
        $(this).closest("tr").remove();
    });

    $("#digital").hide();
    $("#combo").hide();
    $("select[name='type']").val($("input[name='type_hidden']").val());
    variantShowHide();
    diffPriceShowHide();
    if(is_batch)
        $("#variant-option").hide();
    if(is_variant) {
        var customizedVariantCode = 0;
        $("#batch-option").hide();
    }

    if($("input[name='type_hidden']").val() == "digital"){
        $("input[name='cost']").prop('required',false);
        $("select[name='unit_id']").prop('required',false);
        hide();
        $("#digital").show();
    }
    else if($("input[name='type_hidden']").val() == "service"){
        $("input[name='cost']").prop('required',false);
        $("select[name='unit_id']").prop('required',false);
        hide();
        $("#variant-section, #variant-option").hide();
    }
    else if($("input[name='type_hidden']").val() == "combo"){
        $("input[name='cost']").prop('required', false);
        $("input[name='price']").prop('disabled', true);
        $("select[name='unit_id']").prop('required', false);
        hide();
        $("#combo").show();
    }

    var promotion = $("input[name='promotion_hidden']").val();
    if(promotion){
        $("input[name='promotion']").prop('checked', true);
        $("#promotion_price").show(300);
        $("#start_date").show(300);
        $("#last_date").show(300);
    }
    else {
        $("#promotion_price").hide(300);
        $("#start_date").hide(300);
        $("#last_date").hide(300);
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#genbutton').on("click", function(){
      $.get('gencode', function(data){
        $("input[name='code']").val(data);
      });
    });

    $('.type-variant').on('input', function() {
        alert('dadffff');
    });

    $('.add-more-variant').on("click", function() {                       
        var htmlText = '<div class="col-md-4 form-group mt-2"><label>Option <span class="text-danger">*</span></label><input type="text" name="variant_option[]" class="form-control variant-field" placeholder="Size, Color etc..."></div><div class="col-md-6 form-group mt-2"><label>Value <span class="text-danger">*</span></label><input type="text" name="variant_value[]" class="type-variant form-control variant-field"></div>';
        $("#variant-input-section").append(htmlText);
        $('.type-variant').tagsInput();
    });

    //start variant related js
    $(function() {
        $('.type-variant').tagsInput();
    });

    (function($) {
        var delimiter = [];
        var inputSettings = [];
        var callbacks = [];

        $.fn.addTag = function(value, options) {
            if(count == noOfVariantValue)
                customizedVariantCode = 1;
            options = jQuery.extend({
                focus: false,
                callback: true
            }, options);
            
            this.each(function() {
                var id = $(this).attr('id');
                var tagslist = $(this).val().split(_getDelimiter(delimiter[id]));
                if (tagslist[0] === '') tagslist = [];

                value = jQuery.trim(value);
                
                if ((inputSettings[id].unique && $(this).tagExist(value)) || !_validateTag(value, inputSettings[id], tagslist, delimiter[id])) {
                    $('#' + id + '_tag').addClass('error');
                    return false;
                }
                
                $('<span>', {class: 'tag'}).append(
                    $('<span>', {class: 'tag-text'}).text(value),
                    $('<button>', {class: 'tag-remove'}).click(function() {
                        return $('#' + id).removeTag(encodeURI(value));
                    })
                ).insertBefore('#' + id + '_addTag');

                tagslist.push(value);

                $('#' + id + '_tag').val('');
                if (options.focus) {
                    $('#' + id + '_tag').focus();
                } else {
                    $('#' + id + '_tag').blur();
                }

                $.fn.tagsInput.updateTagsField(this, tagslist);

                if (options.callback && callbacks[id] && callbacks[id]['onAddTag']) {
                    var f = callbacks[id]['onAddTag'];
                    f.call(this, this, value);
                }
                
                if (callbacks[id] && callbacks[id]['onChange']) {
                    var i = tagslist.length;
                    var f = callbacks[id]['onChange'];
                    f.call(this, this, value);
                }

                $(".type-variant").each(function(index) {
                    variantIds.splice(index, 1, $(this).attr('id'));
                });
                count++;
                if(customizedVariantCode) {
                    first_variant_values = $('#'+variantIds[0]).val().split(_getDelimiter(delimiter[variantIds[0] ]));
                    combinations = first_variant_values;
                    step = 1;
                    while(step < variantIds.length) {
                        var newCombinations = [];
                        for (var i = 0; i < combinations.length; i++) {
                            new_variant_values = $('#'+variantIds[step]).val().split(_getDelimiter(delimiter[variantIds[step] ]));
                            for (var j = 0; j < new_variant_values.length; j++) {
                                newCombinations.push(combinations[i]+'/'+new_variant_values[j]);
                            }
                        }
                        combinations = newCombinations;
                        step++;
                    }
                    var rownumber = $('table.variant-list tbody tr:last').index();
                    if(rownumber > -1) {
                        oldCombinations = [];
                        oldAdditionalCost = [];
                        oldAdditionalPrice = [];
                        oldProductVariantId = [];
                        $(".variant-name").each(function(i) {
                            oldCombinations.push($(this).val());
                            oldProductVariantId.push($('table.variant-list tbody tr:nth-child(' + (i + 1) + ')').find('.product-variant-id').val());
                            oldAdditionalCost.push($('table.variant-list tbody tr:nth-child(' + (i + 1) + ')').find('.additional-cost').val());
                            oldAdditionalPrice.push($('table.variant-list tbody tr:nth-child(' + (i + 1) + ')').find('.additional-price').val());
                        });
                    }

                    $("table.variant-list tbody").remove();
                    var newBody = $(' <tbody class="tbody">');
                    for(i = 0; i < combinations.length; i++) {
                        var variant_name = combinations[i];
                        var item_code = variant_name+'-'+$("#code").val();
                        var newRow = $("<tr>");
                        var cols = '';
                        cols += '<td>'+variant_name+'<input type="hidden" class="variant-name" name="variant_name[]" value="' + variant_name + '" /></td>';
                        cols += '<td><input type="text" class="form-control item-code" name="item_code[]" value="'+item_code+'" /></td>';
                        //checking if this variant already exist in the variant table
                        oldIndex = oldCombinations.indexOf(combinations[i]);
                        if(oldIndex >= 0) {
                            cols += '<td><input type="number" class="form-control additional-cost" name="additional_cost[]" value="'+oldAdditionalCost[oldIndex]+'" step="any" /></td>';
                            cols += '<td><input type="number" class="form-control additional-price" name="additional_price[]" value="'+oldAdditionalPrice[oldIndex]+'" step="any" /></td>';
                        }
                        else {
                            cols += '<td><input type="number" class="form-control additional-cost" name="additional_cost[]" value="" step="any" /></td>';
                            cols += '<td><input type="number" class="form-control additional-price" name="additional_price[]" value="" step="any" /></td>';
                        }
                        newRow.append(cols);
                        newBody.append(newRow);
                    }
                    $("table.variant-list").append(newBody);
                }
            });

            return false;
        };

        $.fn.removeTag = function(value) {
            value = decodeURI(value);
            
            this.each(function() {
                var id = $(this).attr('id');

                var old = $(this).val().split(_getDelimiter(delimiter[id]));

                $('#' + id + '_tagsinput .tag').remove();
                
                var str = '';
                for (i = 0; i < old.length; ++i) {
                    if (old[i] != value) {
                        str = str + _getDelimiter(delimiter[id]) + old[i];
                    }
                }

                $.fn.tagsInput.importTags(this, str);

                if (callbacks[id] && callbacks[id]['onRemoveTag']) {
                    var f = callbacks[id]['onRemoveTag'];
                    f.call(this, this, value);
                }
            });

            return false;
        };

        $.fn.tagExist = function(val) {
            var id = $(this).attr('id');
            var tagslist = $(this).val().split(_getDelimiter(delimiter[id]));
            return (jQuery.inArray(val, tagslist) >= 0);
        };

        $.fn.importTags = function(str) {
            var id = $(this).attr('id');
            $('#' + id + '_tagsinput .tag').remove();
            $.fn.tagsInput.importTags(this, str);
        };

        $.fn.tagsInput = function(options) {
            var settings = jQuery.extend({
                interactive: true,
                placeholder: variantPlaceholder,
                minChars: 0,
                maxChars: null,
                limit: null,
                validationPattern: null,
                width: 'auto',
                height: 'auto',
                autocomplete: null,
                hide: true,
                delimiter: ',',
                unique: true,
                removeWithBackspace: true
            }, options);

            var uniqueIdCounter = 0;

            this.each(function() {
                if (typeof $(this).data('tagsinput-init') !== 'undefined') return;

                $(this).data('tagsinput-init', true);

                if (settings.hide) $(this).hide();
                
                var id = $(this).attr('id');
                if (!id || _getDelimiter(delimiter[$(this).attr('id')])) {
                    id = $(this).attr('id', 'tags' + new Date().getTime() + (++uniqueIdCounter)).attr('id');
                }

                var data = jQuery.extend({
                    pid: id,
                    real_input: '#' + id,
                    holder: '#' + id + '_tagsinput',
                    input_wrapper: '#' + id + '_addTag',
                    fake_input: '#' + id + '_tag'
                }, settings);

                delimiter[id] = data.delimiter;
                inputSettings[id] = {
                    minChars: settings.minChars,
                    maxChars: settings.maxChars,
                    limit: settings.limit,
                    validationPattern: settings.validationPattern,
                    unique: settings.unique
                };

                if (settings.onAddTag || settings.onRemoveTag || settings.onChange) {
                    callbacks[id] = [];
                    callbacks[id]['onAddTag'] = settings.onAddTag;
                    callbacks[id]['onRemoveTag'] = settings.onRemoveTag;
                    callbacks[id]['onChange'] = settings.onChange;
                }

                var markup = $('<div>', {id: id + '_tagsinput', class: 'tagsinput'}).append(
                    $('<div>', {id: id + '_addTag'}).append(
                        settings.interactive ? $('<input>', {id: id + '_tag', class: 'tag-input', value: '', placeholder: settings.placeholder}) : null
                    )
                );

                $(markup).insertAfter(this);

                $(data.holder).css('width', settings.width);
                $(data.holder).css('min-height', settings.height);
                $(data.holder).css('height', settings.height);

                if ($(data.real_input).val() !== '') {
                    $.fn.tagsInput.importTags($(data.real_input), $(data.real_input).val());
                }
                
                // Stop here if interactive option is not chosen
                if (!settings.interactive) return;
                
                $(data.fake_input).val('');
                $(data.fake_input).data('pasted', false);
                
                $(data.fake_input).on('focus', data, function(event) {
                    $(data.holder).addClass('focus');
                    
                    if ($(this).val() === '') {
                        $(this).removeClass('error');
                    }
                });
                
                $(data.fake_input).on('blur', data, function(event) {
                    $(data.holder).removeClass('focus');
                });

                if (settings.autocomplete !== null && jQuery.ui.autocomplete !== undefined) {
                    $(data.fake_input).autocomplete(settings.autocomplete);
                    $(data.fake_input).on('autocompleteselect', data, function(event, ui) {
                        $(event.data.real_input).addTag(ui.item.value, {
                            focus: true,
                            unique: settings.unique
                        });
                        
                        return false;
                    });
                    
                    $(data.fake_input).on('keypress', data, function(event) {
                        if (_checkDelimiter(event)) {
                            $(this).autocomplete("close");
                        }
                    });
                } else {
                    $(data.fake_input).on('blur', data, function(event) {
                        $(event.data.real_input).addTag($(event.data.fake_input).val(), {
                            focus: true,
                            unique: settings.unique
                        });
                        
                        return false;
                    });
                }
                
                // If a user types a delimiter create a new tag
                $(data.fake_input).on('keypress', data, function(event) {
                    if (_checkDelimiter(event)) {
                        event.preventDefault();
                        
                        $(event.data.real_input).addTag($(event.data.fake_input).val(), {
                            focus: true,
                            unique: settings.unique
                        });
                        
                        return false;
                    }
                });
                
                $(data.fake_input).on('paste', function () {
                    $(this).data('pasted', true);
                });
                
                // If a user pastes the text check if it shouldn't be splitted into tags
                $(data.fake_input).on('input', data, function(event) {
                    if (!$(this).data('pasted')) return;
                    
                    $(this).data('pasted', false);
                    
                    var value = $(event.data.fake_input).val();
                    
                    value = value.replace(/\n/g, '');
                    value = value.replace(/\s/g, '');
                    
                    var tags = _splitIntoTags(event.data.delimiter, value);
                    
                    if (tags.length > 1) {
                        for (var i = 0; i < tags.length; ++i) {
                            $(event.data.real_input).addTag(tags[i], {
                                focus: true,
                                unique: settings.unique
                            });
                        }
                        
                        return false;
                    }
                });
                
                // Deletes last tag on backspace
                data.removeWithBackspace && $(data.fake_input).on('keydown', function(event) {
                    if (event.keyCode == 8 && $(this).val() === '') {
                         event.preventDefault();
                         var lastTag = $(this).closest('.tagsinput').find('.tag:last > span').text();
                         var id = $(this).attr('id').replace(/_tag$/, '');
                         $('#' + id).removeTag(encodeURI(lastTag));
                         $(this).trigger('focus');
                    }
                });

                // Removes the error class when user changes the value of the fake input
                $(data.fake_input).keydown(function(event) {
                    // enter, alt, shift, esc, ctrl and arrows keys are ignored
                    if (jQuery.inArray(event.keyCode, [13, 37, 38, 39, 40, 27, 16, 17, 18, 225]) === -1) {
                        $(this).removeClass('error');
                    }
                });
            });

            return this;
        };
        
        $.fn.tagsInput.updateTagsField = function(obj, tagslist) {
            var id = $(obj).attr('id');
            $(obj).val(tagslist.join(_getDelimiter(delimiter[id])));
        };

        $.fn.tagsInput.importTags = function(obj, val) {
            $(obj).val('');
            
            var id = $(obj).attr('id');
            var tags = _splitIntoTags(delimiter[id], val); 
            
            for (i = 0; i < tags.length; ++i) {
                $(obj).addTag(tags[i], {
                    focus: false,
                    callback: false
                });
            }
            
            if (callbacks[id] && callbacks[id]['onChange']) {
                var f = callbacks[id]['onChange'];
                f.call(obj, obj, tags);
            }
        };
        
        var _getDelimiter = function(delimiter) {
            if (typeof delimiter === 'undefined') {
                return delimiter;
            } else if (typeof delimiter === 'string') {
                return delimiter;
            } else {
                return delimiter[0];
            }
        };
        
        var _validateTag = function(value, inputSettings, tagslist, delimiter) {
            var result = true;
            
            if (value === '') result = false;
            if (value.length < inputSettings.minChars) result = false;
            if (inputSettings.maxChars !== null && value.length > inputSettings.maxChars) result = false;
            if (inputSettings.limit !== null && tagslist.length >= inputSettings.limit) result = false;
            if (inputSettings.validationPattern !== null && !inputSettings.validationPattern.test(value)) result = false;
            
            if (typeof delimiter === 'string') {
                if (value.indexOf(delimiter) > -1) result = false;
            } else {
                $.each(delimiter, function(index, _delimiter) {
                    if (value.indexOf(_delimiter) > -1) result = false;
                    return false;
                });
            }
            
            return result;
        };
     
        var _checkDelimiter = function(event) {
            var found = false;
            
            if (event.which === 13) {
                return true;
            }

            if (typeof event.data.delimiter === 'string') {
                if (event.which === event.data.delimiter.charCodeAt(0)) {
                    found = true;
                }
            } else {
                $.each(event.data.delimiter, function(index, delimiter) {
                    if (event.which === delimiter.charCodeAt(0)) {
                        found = true;
                    }
                });
            }
            
            return found;
         };
         
         var _splitIntoTags = function(delimiter, value) {
             if (value === '') return [];
             
             if (typeof delimiter === 'string') {
                 return value.split(delimiter);
             } else {
                 var tmpDelimiter = '∞';
                 var text = value;
                 
                 $.each(delimiter, function(index, _delimiter) {
                     text = text.split(_delimiter).join(tmpDelimiter);
                 });
                 
                 return text.split(tmpDelimiter);
             }
             
             return [];
         };
    })(jQuery);
    //end of variant related js

    tinymce.init({
      selector: 'textarea',
      height: 130,
      plugins: [
        'advlist autolink lists link image charmap print preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code wordcount'
      ],
      toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
      branding:false
    });

    var barcode_symbology = $("input[name='barcode_symbology_hidden']").val();
    $('select[name=barcode_symbology]').val(barcode_symbology);

    var brand = $("input[name='brand']").val();
    $('select[name=brand_id]').val(brand);

    var cat = $("input[name='category']").val();
    $('select[name=category_id]').val(cat);

    if($("input[name='unit']").val()) {
        $('select[name=unit_id]').val($("input[name='unit']").val());
        populate_unit($("input[name='unit']").val());
    }

    var tax = $("input[name='tax']").val();
    if(tax)
        $('select[name=tax_id]').val(tax);

    var tax_method = $("input[name='tax_method_id']").val();
    $('select[name=tax_method]').val(tax_method);
    $('select[name="type"]').on('change', function() {
        if($(this).val() == 'combo'){
            $("input[name='cost']").prop('required',false);
            $("select[name='unit_id']").prop('required',false);
            hide();
            $("#digital").hide();
            $("#variant-section, #variant-option, #diffPrice-option, #diffPrice-section").hide(300);
            $("#combo").show();
            $("input[name='price']").prop('disabled',true);
        }
        else if($(this).val() == 'digital'){
            $("input[name='cost']").prop('required',false);
            $("select[name='unit_id']").prop('required',false);
            $("input[name='file']").prop('required',true);
            hide();
            $("#combo").hide();
            $("#digital").show();
            $("#variant-section, #variant-option, #diffPrice-option, #diffPrice-section").hide(300);
            $("input[name='price']").prop('disabled',false);
        }
        else if($(this).val() == 'service') {
            $("input[name='cost']").prop('required',false);
            $("select[name='unit_id']").prop('required',false);
            $("input[name='file']").prop('required',true);
            hide();
            $("#combo").hide(300);
            $("#digital").hide(300);
            $("input[name='price']").prop('disabled',false);
            $("#is-variant").prop("checked", false);
            $("#variant-section, #variant-option").hide(300);
        }
        else if($(this).val() == 'standard'){
            $("input[name='cost']").prop('required',true);
            $("select[name='unit_id']").prop('required',true);
            $("input[name='file']").prop('required',false);
            $("#cost").show();
            $("#unit").show();
            $("#alert-qty").show();
            $("#variant-option").show(300);
            $("#diffPrice-option").show(300);
            $("#digital").hide();
            $("#combo").hide();
            $("input[name='price']").prop('disabled',false);
        }
    });

    $('select[name="unit_id"]').on('change', function() {
        unitID = $(this).val();
        if(unitID) {
            populate_unit_second(unitID);
        }else{
            $('select[name="sale_unit_id"]').empty();
            $('select[name="purchase_unit_id"]').empty();
        }
    });

    <?php $productArray = []; ?>
    var ot_crm_product_code = [
        @foreach($ot_crm_product_list_without_variant as $product)
        <?php
            $productArray[] = htmlspecialchars($product->code . ' (' . $product->name . ')');
        ?>
        @endforeach
        @foreach($ot_crm_product_list_with_variant as $product)
            <?php
                $productArray[] = htmlspecialchars($product->item_code . ' (' . $product->name . ')');
            ?>
        @endforeach
            <?php
                echo  '"'.implode('","', $productArray).'"';
            ?> ];

    var ot_crm_productcodeSearch = $('#ot_crm_productcodeSearch');

    ot_crm_productcodeSearch.autocomplete({
        source: function(request, response) {
            var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
            response($.grep(ot_crm_product_code, function(item) {
                return matcher.test(item);
            }));
        },
        select: function(event, ui) {
            var data = ui.item.value;
            $.ajax({
                type: 'GET',
                url: '../ot_crm_product_search',
                data: {
                    data: data
                },
                success: function(data) {
                    var flag = 1;
                    $(".product-id").each(function() {
                        if ($(this).val() == data[8]) {
                            alert('Duplicate input is not allowed!')
                            flag = 0;
                        }
                    });
                    $("input[name='product_code_name']").val('');
                    if(flag){
                        var newRow = $("<tr>");
                        var cols = '';
                        cols += '<td>' + data[0] +' [' + data[1] + ']</td>';
                        cols += '<td><input type="number" class="form-control qty" name="product_qty[]" value="1" step="any"/></td>';
                        cols += '<td><input type="number" class="form-control unit_price" name="unit_price[]" value="' + data[2] + '" step="any"/></td>';
                        cols += '<td><button type="button" class="ibtnDel btn btn-sm btn-danger">X</button></td>';
                        cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[8] + '"/>';
                        cols += '<input type="hidden" class="" name="variant_id[]" value="' + data[9] + '"/>';

                        newRow.append(cols);
                        $("table.order-list tbody").append(newRow);
                        calculate_price();
                    }
                }
            });
        }
    });

    //Change quantity or unit price
    $("#myTable").on('input', '.qty , .unit_price', function() {
        calculate_price();
    });

    //Delete product
    $("table.order-list tbody").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        calculate_price();
    });

    function calculate_price() {
        var price = 0;
        $(".qty").each(function() {
            rowindex = $(this).closest('tr').index();
            quantity =  $(this).val();
            unit_price = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .unit_price').val();
            price += quantity * unit_price;
        });
        $('input[name="price"]').val(price);
    }

    function hide() {
        $("#cost").hide();
        $("#unit").hide();
        $("#alert-qty").hide();
    }

    function populate_unit(unitID){
        $.ajax({
            url: '../saleunit/'+unitID,
            type: "GET",
            dataType: "json",

            success:function(data) {
                  $('select[name="sale_unit_id"]').empty();
                  $('select[name="purchase_unit_id"]').empty();
                  $.each(data, function(key, value) {
                      $('select[name="sale_unit_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                      $('select[name="purchase_unit_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                  });
                  var sale_unit = $("input[name='sale_unit']").val();
                  var purchase_unit = $("input[name='purchase_unit']").val();
                $('#sale-unit').val(sale_unit);
                $('select[name=purchase_unit_id]').val(purchase_unit);
            },
        });
    }

    function populate_unit_second(unitID){
        $.ajax({
            url: '../saleunit/'+unitID,
            type: "GET",
            dataType: "json",
            success:function(data) {
                  $('select[name="sale_unit_id"]').empty();
                  $('select[name="purchase_unit_id"]').empty();
                  $.each(data, function(key, value) {
                      $('select[name="sale_unit_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                      $('select[name="purchase_unit_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                  });
            },
        });
    };

    $("input[name='is_batch']").on("change", function () {
        if ($(this).is(':checked')) {
            $("#variant-option").hide(300);
        }
        else
            $("#variant-option").show(300);
    });

    $("input[name='is_variant']").on("change", function () {
        variantShowHide();
    });

    $("input[name='is_diffPrice']").on("change", function () {
        diffPriceShowHide();
    });

    function variantShowHide() {
         if ($("#is-variant").is(':checked')) {
            $("#variant-section").show(300);
            $("#batch-option").hide(300);
            $(".variant-field").prop("required", true);
        }
        else {
            $("#variant-section").hide(300);
            $("#batch-option").show(300);
            $(".variant-field").prop("required", false);
        }
    };

    function diffPriceShowHide() {
         if ($("#is-diffPrice").is(':checked')) {
            $("#diffPrice-section").show(300);
        }
        else {
            $("#diffPrice-section").hide(300);
        }
    };

    $( "#promotion" ).on( "change", function() {
        if ($(this).is(':checked')) {
            $("#promotion_price").show();
            $("#start_date").show();
            $("#last_date").show();
        }
        else {
            $("#promotion_price").hide();
            $("#start_date").hide();
            $("#last_date").hide();
        }
    });

    var starting_date = $('#starting_date');
    starting_date.datepicker({
     format: "dd-mm-yyyy",
     startDate: "<?php echo date('d-m-Y'); ?>",
     autoclose: true,
     todayHighlight: true
     });

    var ending_date = $('#ending_date');
    ending_date.datepicker({
     format: "dd-mm-yyyy",
     startDate: "<?php echo date('d-m-Y'); ?>",
     autoclose: true,
     todayHighlight: true
     });

    //dropzone portion
    Dropzone.autoDiscover = false;
    myDropzone = new Dropzone('div#imageUpload', {
        addRemoveLinks: true,
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 100,
        maxFilesize: 12,
        paramName: 'image',
        clickable: true,
        method: 'POST',
        url:'../update',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
            return time + file.name;
        },
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        init: function () {
            var myDropzone = this;
            $('#submit-btn').on("click", function (e) {
                e.preventDefault();
                    tinyMCE.triggerSave();
                    if(myDropzone.getAcceptedFiles().length) {
                        myDropzone.processQueue();
                    }
                    else {
                        $.ajax({
                            type:'POST',
                            url:'../update',
                            data: $("#product-form").serialize(),
                            success:function(response){
                                location.href = redirectUrl;
                            },
                            error:function(response) {
                              if(response.responseJSON.errors.name) {
                                  $("#name-error").text(response.responseJSON.errors.name);
                              }
                              else if(response.responseJSON.errors.code) {
                                  $("#code-error").text(response.responseJSON.errors.code);
                              }
                            },
                        });
                    }
            });

            this.on('sending', function (file, xhr, formData) {
                // Append all form inputs to the formData Dropzone will POST
                var data = $("#product-form").serializeArray();
                $.each(data, function (key, el) {
                    formData.append(el.name, el.value);
                });
            });
        },
        error: function (file, response) {
            console.log(response);
        },
        successmultiple: function (file, response) {
            location.href = redirectUrl;
        },
        completemultiple: function (file, response) {
            console.log(file, response, "completemultiple");
        },
        reset: function () {
            this.removeAllFiles(true);
        }
    });
    $('.dropzone .dz-message span').append("<i class='fa fa-upload' aria-hidden='true'></i>");
</script>
@endsection