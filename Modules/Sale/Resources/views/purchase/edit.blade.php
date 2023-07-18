@extends('backend.layouts.app')

@section('content')
{!! breadcrumb([
'title' => @$data['title'],
route('admin.dashboard') => _trans('common.Dashboard'),
'#' => @$data['title'],
]) !!}
<div class="row">
    <div class="col-md-12">
        <div class="card ot-card">
            <div class="card-body">
               
                {!! Form::open(['route' => ['salePurchase.update', $ot_crm_purchase_data->id], 'method' => 'post',
                'files' => true, 'id' => 'purchase-form']) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Date')}}</label>
                                    <input type="text" name="created_at" required
                                        class="form-control ot-form-control ot-input date" value="{{date(" Y-m-d",
                                        strtotime($ot_crm_purchase_data->created_at->toDateString()))}}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Reference No')}}</label>
                                    <p><strong>{{ $ot_crm_purchase_data->reference_no }}</strong> </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Warehouse')}} <span class="text-danger">*</span></label>
                                    <input type="hidden" name="warehouse_id_hidden"
                                        value="{{$ot_crm_purchase_data->warehouse_id}}" />
                                    <select required name="warehouse_id"
                                        class="form-select select2-input ot-input mb-3 modal_select2"
                                        data-live-search="true" title="Select warehouse...">
                                        @foreach($ot_crm_warehouse_list as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Supplier')}}</label>
                                    <input type="hidden" name="supplier_id_hidden"
                                        value="{{ $ot_crm_purchase_data->supplier_id }}" />
                                    <select name="supplier_id" required
                                        class="form-select select2-input ot-input mb-3 modal_select2"
                                        data-live-search="true" id="supplier-id" title="Select supplier...">
                                        @foreach($ot_crm_supplier_list as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name .' ('.
                                            $supplier->company_name .')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Purchase Status')}}</label>
                                    <input type="hidden" name="status_hidden" value="{{$ot_crm_purchase_data->status}}">
                                    <select name="status" required class="form-control ot-form-control ot-input">
                                        <option value="1">{{_trans('file.Recieved')}}</option>
                                        <option value="2">{{_trans('file.Partial')}}</option>
                                        <option value="3">{{_trans('file.Pending')}}</option>
                                        <option value="4">{{_trans('file.Ordered')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Attach Document')}}</label> <i class="dripicons-question"
                                        data-toggle="tooltip"
                                        title="Only jpg, jpeg, png, gif, pdf, csv, docx, xlsx and txt file is supported"></i>
                                    <input type="file" name="document" class="form-control ot-form-control ot-input">
                                    @if($errors->has('extension'))
                                    <span>
                                        <strong>{{ $errors->first('extension') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label>{{_trans('file.Select Product')}}</label>
                                <div class="search-box input-group">
                                    <button type="button" class="btn btn-secondary"><i
                                            class="fa fa-barcode"></i></button>
                                    <input type="text" name="product_code_name" id="ot_crm_productcodeSearch"
                                        placeholder="Please type product code and select..."
                                        class="form-control ot-form-control ot-input" />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5 table-content table-basic">
                            <div class="col-md-12">
                                <h5>{{_trans('file.Order Table')}} *</h5>
                                <div class="table-responsive mt-3">
                                    <table id="myTable" class="table table-hover table-bordered order-list">
                                        <thead class="thead">
                                            <tr>
                                                <th>{{_trans('file.name')}}</th>
                                                <th>{{_trans('file.Code')}}</th>
                                                <th>{{_trans('file.Quantity')}}</th>
                                                <th class="recieved-product-qty d-none">{{_trans('file.Recieved')}}
                                                </th>
                                                <th>{{_trans('file.Batch No')}}</th>
                                                <th>{{_trans('file.Expired Date')}}</th>
                                                <th>{{_trans('file.Net Unit Cost')}}</th>
                                                <th>{{_trans('file.Discount')}}</th>
                                                <th>{{_trans('file.Tax')}}</th>
                                                <th>{{_trans('file.Subtotal')}}</th>
                                                <th><i class="dripicons-trash"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            <?php
                                                    $temp_unit_name = [];
                                                    $temp_unit_operator = [];
                                                    $temp_unit_operation_value = [];
                                                    ?>
                                            @foreach($ot_crm_product_purchase_data as $product_purchase)
                                            <tr>
                                                <?php
                                                        $product_data = DB::table('sale_products')->find($product_purchase->product_id);
                                                        if($product_purchase->variant_id) {
                                                            $product_variant_data = Modules\Sale\Entities\SaleProductVariant::FindExactProduct($product_data->id, $product_purchase->variant_id)->select('item_code')->first();
                                                            $product_data->code = $product_variant_data->item_code;
                                                        }

                                                        $tax = DB::table('sale_taxes')->where('rate', $product_purchase->tax_rate)->first();

                                                        $units = DB::table('sale_units')->where('base_unit', $product_data->unit_id)->orWhere('id', $product_data->unit_id)->get();

                                                        $unit_name = array();
                                                        $unit_operator = array();
                                                        $unit_operation_value = array();

                                                        foreach($units as $unit) {
                                                            if($product_purchase->purchase_unit_id == $unit->id) {
                                                                array_unshift($unit_name, $unit->unit_name);
                                                                array_unshift($unit_operator, $unit->operator);
                                                                array_unshift($unit_operation_value, $unit->operation_value);
                                                            }
                                                            else {
                                                                $unit_name[]  = $unit->unit_name;
                                                                $unit_operator[] = $unit->operator;
                                                                $unit_operation_value[] = $unit->operation_value;
                                                            }
                                                        }
                                                        if($product_data->tax_method == 1){
                                                            $product_cost = ($product_purchase->net_unit_cost + ($product_purchase->discount / $product_purchase->qty)) / $unit_operation_value[0];
                                                        }
                                                        else{
                                                            $product_cost = (($product_purchase->total + ($product_purchase->discount / $product_purchase->qty)) / $product_purchase->qty) / $unit_operation_value[0];
                                                        }


                                                        $temp_unit_name = $unit_name = implode(",",$unit_name) . ',';

                                                        $temp_unit_operator = $unit_operator = implode(",",$unit_operator) .',';

                                                        $temp_unit_operation_value = $unit_operation_value =  implode(",",$unit_operation_value) . ',';

                                                        $product_batch_data = Modules\Sale\Entities\SaleProductBatch::select('batch_no', 'expired_date')->find($product_purchase->product_batch_id);
                                                    ?>
                                                <td>{{$product_data->name}} <button type="button"
                                                        class="edit-product btn btn-link" data-toggle="modal"
                                                        data-target="#editModal"> <i
                                                            class="dripicons-document-edit"></i></button> </td>
                                                <td>{{$product_data->code}}</td>
                                                <td><input type="number"
                                                        class="form-control ot-form-control ot-input qty" name="qty[]"
                                                        value="{{$product_purchase->qty}}" step="any" required /></td>
                                                <td class="recieved-product-qty d-none"><input type="number"
                                                        class="form-control ot-form-control ot-input recieved"
                                                        name="recieved[]" value="{{$product_purchase->recieved}}"
                                                        step="any" /></td>
                                                @if($product_purchase->product_batch_id)
                                                <td>
                                                    <input type="hidden" name="product_batch_id[]"
                                                        value="{{$product_purchase->product_batch_id}}">
                                                    <input type="text"
                                                        class="form-control ot-form-control ot-input batch-no"
                                                        name="batch_no[]" value="{{$product_batch_data->batch_no}}"
                                                        required />
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        class="form-control ot-form-control ot-input expired-date"
                                                        name="expired_date[]"
                                                        value="{{$product_batch_data->expired_date}}" required />
                                                </td>
                                                @else
                                                <td>
                                                    <input type="hidden" name="product_batch_id[]">
                                                    <input type="text"
                                                        class="form-control ot-form-control ot-input batch-no"
                                                        name="batch_no[]" disabled />
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        class="form-control ot-form-control ot-input expired-date"
                                                        name="expired_date[]" disabled />
                                                </td>
                                                @endif
                                                <td class="net_unit_cost">{{
                                                    number_format((float)$product_purchase->net_unit_cost, 2, '.',
                                                    '') }} </td>
                                                <td class="discount">{{
                                                    number_format((float)$product_purchase->discount, 2, '.', '') }}
                                                </td>
                                                <td class="tax">{{ number_format((float)$product_purchase->tax, 2,
                                                    '.', '') }}</td>
                                                <td class="sub-total">{{
                                                    number_format((float)$product_purchase->total, 2, '.', '') }}
                                                </td>
                                                <td><button type="button"
                                                        class="ibtnDel btn btn-md btn-danger">{{_trans("file.delete")}}</button>
                                                </td>
                                                <input type="hidden" class="product-id" name="product_id[]"
                                                    value="{{$product_data->id}}" />
                                                <input type="hidden" class="product-code" name="product_code[]"
                                                    value="{{$product_data->code}}" />
                                                <input type="hidden" class="product-cost" name="product_cost[]"
                                                    value="{{ $product_cost}}" />
                                                <input type="hidden" class="purchase-unit" name="purchase_unit[]"
                                                    value="{{$unit_name}}" />
                                                <input type="hidden" class="purchase-unit-operator"
                                                    value="{{$unit_operator}}" />
                                                <input type="hidden" class="purchase-unit-operation-value"
                                                    value="{{$unit_operation_value}}" />
                                                <input type="hidden" class="net_unit_cost" name="net_unit_cost[]"
                                                    value="{{$product_purchase->net_unit_cost}}" />
                                                <input type="hidden" class="discount-value" name="discount[]"
                                                    value="{{$product_purchase->discount}}" />
                                                <input type="hidden" class="tax-rate" name="tax_rate[]"
                                                    value="{{$product_purchase->tax_rate}}" />
                                                @if($tax)
                                                <input type="hidden" class="tax-name" value="{{$tax->name}}" />
                                                @else
                                                <input type="hidden" class="tax-name" value="No Tax" />
                                                @endif
                                                <input type="hidden" class="tax-method"
                                                    value="{{$product_data->tax_method}}" />
                                                <input type="hidden" class="tax-value" name="tax[]"
                                                    value="{{$product_purchase->tax}}" />
                                                <input type="hidden" class="subtotal-value" name="subtotal[]"
                                                    value="{{$product_purchase->total}}" />
                                                <input type="hidden" class="imei-number" name="imei_number[]"
                                                    value="{{$product_purchase->imei_number}}" />
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="tfoot active">
                                            <th colspan="2">{{_trans('file.Total')}}</th>
                                            <th id="total-qty">{{$ot_crm_purchase_data->total_qty}}</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="recieved-product-qty d-none"></th>
                                            <th id="total-discount">{{
                                                number_format((float)$ot_crm_purchase_data->total_discount, 2, '.',
                                                '') }}</th>
                                            <th id="total-tax">{{
                                                number_format((float)$ot_crm_purchase_data->total_tax, 2, '.', '')}}
                                            </th>
                                            <th id="total">{{ number_format((float)$ot_crm_purchase_data->total_cost,
                                                2, '.', '') }}</th>
                                            <th><i class="dripicons-trash"></i></th>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_qty" value="{{$ot_crm_purchase_data->total_qty}}" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_discount"
                                        value="{{$ot_crm_purchase_data->total_discount}}" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_tax" value="{{$ot_crm_purchase_data->total_tax}}" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_cost"
                                        value="{{$ot_crm_purchase_data->total_cost}}" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="item" value="{{$ot_crm_purchase_data->item}}" />
                                    <input type="hidden" name="order_tax" value="{{$ot_crm_purchase_data->order_tax}}" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="grand_total"
                                        value="{{$ot_crm_purchase_data->grand_total}}" />
                                    <input type="hidden" name="paid_amount"
                                        value="{{$ot_crm_purchase_data->paid_amount}}" />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Order Tax')}}</label>
                                    <input type="hidden" name="order_tax_rate_hidden"
                                        value="{{$ot_crm_purchase_data->order_tax_rate}}">
                                    <select class="form-select select2-input ot-input mb-3 modal_select2"
                                        name="order_tax_rate">
                                        <option value="0">{{_trans('file.No Tax')}}</option>
                                        @foreach($ot_crm_tax_list as $tax)
                                        <option value="{{$tax->rate}}">{{$tax->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>
                                        <strong>{{_trans('file.Discount')}}</strong>
                                    </label>
                                    <input type="number" name="order_discount"
                                        class="form-control ot-form-control ot-input"
                                        value="{{$ot_crm_purchase_data->order_discount}}" step="any" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>
                                        <strong>{{_trans('file.Shipping Cost')}}</strong>
                                    </label>
                                    <input type="number" name="shipping_cost"
                                        class="form-control ot-form-control ot-input"
                                        value="{{$ot_crm_purchase_data->shipping_cost}}" step="any" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{_trans('file.Note')}}</label>
                                    <textarea rows="5" class="form-control ot-form-control ot-input"
                                        name="note">{{ $ot_crm_purchase_data->note }}</textarea>
                                </div>
                            </div>
                        </div>
                        @if (hasPermission('sales_purchase_update'))
                        <div class="form-group">
                            <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn"
                                id="submit-button">
                        </div>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 id="modal_header" class="modal-title"></h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i
                            class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row modal-element">
                        <div class="col-md-4 form-group">
                            <label>{{_trans('file.Quantity')}}</label>
                            <input type="number" name="edit_qty" class="form-control ot-form-control ot-input"
                                step="any">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{_trans('file.Unit Discount')}}</label>
                            <input type="number" name="edit_discount" class="form-control ot-form-control ot-input"
                                step="any">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{_trans('file.Unit Cost')}}</label>
                            <input type="number" name="edit_unit_cost" class="form-control ot-form-control ot-input"
                                step="any">
                        </div>
                        <?php
                            $tax_name_all[] = 'No Tax';
                            $tax_rate_all[] = 0;
                            foreach($ot_crm_tax_list as $tax) {
                                $tax_name_all[] = $tax->name;
                                $tax_rate_all[] = $tax->rate;
                            }
                        ?>
                        <div class="col-md-4 form-group">
                            <label>{{_trans('file.Tax Rate')}}</label>
                            <select name="edit_tax_rate" class="form-select select2-input ot-input mb-3 modal_select2r">
                                @foreach($tax_name_all as $key => $name)
                                <option value="{{$key}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{_trans('file.Product Unit')}}</label>
                            <select name="edit_unit" class="form-select select2-input ot-input mb-3 modal_select2r">
                            </select>
                        </div>
                    </div>
                    <button type="button" name="update_btn" class="crm_theme_btn">{{_trans('file.update')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $("ul#purchase").siblings('a').addClass("active");
    $("ul#purchase").addClass("show");

// array data depend on warehouse
var ot_crm_product_array = [];
var product_code = [];
var product_name = [];
var product_qty = [];

// array data with selection
var product_cost = [];
var product_discount = [];
var tax_rate = [];
var tax_name = [];
var tax_method = [];
var unit_name = [];
var unit_operator = [];
var unit_operation_value = [];
var is_imei = [];

var temp_unit_name = [];
var temp_unit_operator = [];
var temp_unit_operation_value = [];

var rowindex;
var customer_group_rate;
var row_product_cost;

var rownumber = $('table.order-list tbody tr:last').index();

for(rowindex  =0; rowindex <= rownumber; rowindex++){

    product_cost.push(parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-cost').val()));
    var total_discount = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount').text());
    var quantity = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val());
    product_discount.push((total_discount / quantity).toFixed(2));
    tax_rate.push(parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate').val()));
    tax_name.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-name').val());
    tax_method.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-method').val());
    temp_unit_name = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit').val().split(',');
    unit_name.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit').val());
    unit_operator.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit-operator').val());
    unit_operation_value.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit-operation-value').val());
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit').val(temp_unit_name[0]);
}


$('[data-toggle="tooltip"]').tooltip();

$('select[name="supplier_id"]').val($('input[name="supplier_id_hidden"]').val());
$('select[name="warehouse_id"]').val($('input[name="warehouse_id_hidden"]').val());
$('select[name="status"]').val($('input[name="status_hidden"]').val());
$('select[name="order_tax_rate"]').val($('input[name="order_tax_rate_hidden"]').val());

$('#item').text($('input[name="item"]').val() + '(' + $('input[name="total_qty"]').val() + ')');
$('#subtotal').text(parseFloat($('input[name="total_cost"]').val()).toFixed(2));
$('#order_tax').text(parseFloat($('input[name="order_tax"]').val()).toFixed(2));
if($('select[name="status"]').val() == 2){
    $(".recieved-product-qty").removeClass("d-none");

}
if(!$('input[name="order_discount"]').val())
    $('input[name="order_discount"]').val('0.00');
$('#order_discount').text(parseFloat($('input[name="order_discount"]').val()).toFixed(2));
if(!$('input[name="shipping_cost"]').val())
    $('input[name="shipping_cost"]').val('0.00');
$('#shipping_cost').text(parseFloat($('input[name="shipping_cost"]').val()).toFixed(2));
$('#grand_total').text(parseFloat($('input[name="grand_total"]').val()).toFixed(2));

$('select[name="status"]').on('change', function() {
    if($('select[name="status"]').val() == 2){
        $(".recieved-product-qty").removeClass("d-none");
        $(".qty").each(function() {
            rowindex = $(this).closest('tr').index();
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.recieved').val($(this).val());
        });

    }
    else if(($('select[name="status"]').val() == 3) || ($('select[name="status"]').val() == 4)){
        $(".recieved-product-qty").addClass("d-none");
        $(".recieved").each(function() {
            $(this).val(0);
        });
    }
    else {
        $(".recieved-product-qty").addClass("d-none");
        $(".qty").each(function() {
            rowindex = $(this).closest('tr').index();
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.recieved').val($(this).val());
        });
    }
});


var ot_crm_product_code = [
    @foreach($ot_crm_product_list_without_variant as $product)
        <?php
            $productArray[] = htmlspecialchars($product->code . '|' . $product->name);
        ?>
    @endforeach
    @foreach($ot_crm_product_list_with_variant as $product)
        <?php
            $productArray[] = htmlspecialchars($product->item_code . '|' . $product->name);
        ?>
    @endforeach
        <?php
            echo  '"'.implode('","', $productArray).'"';
        ?>
];

    var ot_crm_productcodeSearch = $('#ot_crm_productcodeSearch');

    ot_crm_productcodeSearch.autocomplete({
    source: function(request, response) {
        var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
        response($.grep(ot_crm_product_code, function(item) {
            return matcher.test(item);
        }));
    },
    response: function(event, ui) {
        if (ui.content.length == 1) {
            var data = ui.content[0].value;
            $(this).autocomplete( "close" );
            productSearch(data);
        };
    },
    select: function(event, ui) {
        var data = ui.item.value;
        productSearch(data);
    }
});

$('body').on('focus',".expired-date", function() {
    $(this).datepicker({
        format: "yyyy-mm-dd",
        startDate: "<?php echo date("Y-m-d", strtotime('+ 1 days')) ?>",
        autoclose: true,
        todayHighlight: true
    });
});

//Change quantity
$("#myTable").on('input', '.qty', function() {
    rowindex = $(this).closest('tr').index();
    if($(this).val() < 1 && $(this).val() != '') {
      $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(1);
      alert("Quantity can't be less than 1");
    }
    checkQuantity($(this).val(), true);
});


//Delete product
$("table.order-list tbody").on("click", ".ibtnDel", function(event) {
    rowindex = $(this).closest('tr').index();
    product_cost.splice(rowindex, 1);
    product_discount.splice(rowindex, 1);
    tax_rate.splice(rowindex, 1);
    tax_name.splice(rowindex, 1);
    tax_method.splice(rowindex, 1);
    unit_name.splice(rowindex, 1);
    unit_operator.splice(rowindex, 1);
    unit_operation_value.splice(rowindex, 1);
    $(this).closest("tr").remove();
    calculateTotal();
});

//Edit product
$("table.order-list").on("click", ".edit-product", function() {
    rowindex = $(this).closest('tr').index();
    $(".imei-section").remove();
    var imeiNumbers = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.imei-number').val();
    if(imeiNumbers || is_imei[rowindex]) {
        htmlText = '<div class="col-md-12 form-group imei-section"><label>IMEI or Serial Numbers</label><input type="text" name="imei_numbers" value="'+imeiNumbers+'" class="form-control ot-form-control ot-input imei_number" placeholder="Type imei or serial numbers and separate them by comma. Example:1001,2001" step="any"></div>';
        $("#editModal .modal-element").append(htmlText);
    }
    var row_product_name = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(1)').text();
    var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
    $('#modal_header').text(row_product_name + '(' + row_product_code + ')');

    var qty = $(this).closest('tr').find('.qty').val();
    $('input[name="edit_qty"]').val(qty);

    $('input[name="edit_discount"]').val(parseFloat(product_discount[rowindex]).toFixed(2));

    unitConversion();
    $('input[name="edit_unit_cost"]').val(row_product_cost.toFixed(2));

    var tax_name_all = <?php echo json_encode($tax_name_all) ?>;
    var pos = tax_name_all.indexOf(tax_name[rowindex]);
    $('select[name="edit_tax_rate"]').val(pos);

    temp_unit_name = (unit_name[rowindex]).split(',');
    temp_unit_name.pop();
    temp_unit_operator = (unit_operator[rowindex]).split(',');
    temp_unit_operator.pop();
    temp_unit_operation_value = (unit_operation_value[rowindex]).split(',');
    temp_unit_operation_value.pop();
    $('select[name="edit_unit"]').empty();
    $.each(temp_unit_name, function(key, value) {
        $('select[name="edit_unit"]').append('<option value="' + key + '">' + value + '</option>');
    });
    $('.selectpicker').selectpicker('refresh');
});

//Update product
$('button[name="update_btn"]').on("click", function() {
    var imeiNumbers = $("#editModal input[name=imei_numbers]").val();
    if(imeiNumbers || is_imei[rowindex]) {
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.imei-number').val(imeiNumbers);
    }

    var edit_discount = $('input[name="edit_discount"]').val();
    var edit_qty = $('input[name="edit_qty"]').val();
    var edit_unit_cost = $('input[name="edit_unit_cost"]').val();

    if (parseFloat(edit_discount) > parseFloat(edit_unit_cost)) {
        alert('Invalid Discount Input!');
        return;
    }

    if(edit_qty < 1) {
        $('input[name="edit_qty"]').val(1);
        edit_qty = 1;
        alert("Quantity can't be less than 1");
    }

    var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(","));
    var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[rowindex].indexOf(","));
    row_unit_operation_value = parseFloat(row_unit_operation_value);
    var tax_rate_all = <?php echo json_encode($tax_rate_all) ?>;


    tax_rate[rowindex] = parseFloat(tax_rate_all[$('select[name="edit_tax_rate"]').val()]);
    tax_name[rowindex] = $('select[name="edit_tax_rate"] option:selected').text();


    if (row_unit_operator == '*') {
        product_cost[rowindex] = $('input[name="edit_unit_cost"]').val() / row_unit_operation_value;
    } else {
        product_cost[rowindex] = $('input[name="edit_unit_cost"]').val() * row_unit_operation_value;
    }

    product_discount[rowindex] = $('input[name="edit_discount"]').val();
    var position = $('select[name="edit_unit"]').val();
    var temp_operator = temp_unit_operator[position];
    var temp_operation_value = temp_unit_operation_value[position];
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit').val(temp_unit_name[position]);
    temp_unit_name.splice(position, 1);
    temp_unit_operator.splice(position, 1);
    temp_unit_operation_value.splice(position, 1);

    temp_unit_name.unshift($('select[name="edit_unit"] option:selected').text());
    temp_unit_operator.unshift(temp_operator);
    temp_unit_operation_value.unshift(temp_operation_value);

    unit_name[rowindex] = temp_unit_name.toString() + ',';
    unit_operator[rowindex] = temp_unit_operator.toString() + ',';
    unit_operation_value[rowindex] = temp_unit_operation_value.toString() + ',';
    checkQuantity(edit_qty, false);
});

function productSearch(data) {
    $.ajax({
        type: 'GET',
        url: '../ot_crm_product_search',
        data: {
            data: data
        },
        success: function(data) {
            var flag = 1;
            $(".product-code").each(function(i) {
                if ($(this).val() == data[1]) {
                    rowindex = i;
                    var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val()) + 1;
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
                    if($('select[name="status"]').val() == 1 || $('select[name="status"]').val() == 1) {
                        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .recieved').val(qty);
                    }
                    calculateRowProductData(qty);
                    flag = 0;
                }
            });
            $("input[name='product_code_name']").val('');
            if(flag){
                var newRow = $("<tr>");
                var cols = '';
                temp_unit_name = (data[6]).split(',');
                cols += '<td>' + data[0] + '<button type="button" class="edit-product btn btn-link" data-toggle="modal" data-target="#editModal"> <i class="dripicons-document-edit"></i></button></td>';
                cols += '<td>' + data[1] + '</td>';
                cols += '<td><input type="number" class="form-control ot-form-control ot-input qty" name="qty[]" value="1" step="any" required /></td>';
                if($('select[name="status"]').val() == 1)
                    cols += '<td class="recieved-product-qty d-none"><input type="number" class="form-control ot-form-control ot-input recieved" name="recieved[]" value="1" step="any" /></td>';
                else if($('select[name="status"]').val() == 2)
                    cols += '<td class="recieved-product-qty"><input type="number" class="form-control ot-form-control ot-input recieved" name="recieved[]" value="1" step="any"/></td>';
                else
                    cols += '<td class="recieved-product-qty d-none"><input type="number" class="form-control ot-form-control ot-input recieved" name="recieved[]" value="0" step="any"/></td>';
                if(data[10]) {
                    cols += '<td><input type="text" class="form-control ot-form-control ot-input batch-no" name="batch_no[]" required/></td>';
                    cols += '<td><input type="text" class="form-control ot-form-control ot-input expired-date" name="expired_date[]" required/></td>';
                }
                else {
                    cols += '<td><input type="text" class="form-control ot-form-control ot-input batch-no" name="batch_no[]" disabled/></td>';
                    cols += '<td><input type="text" class="form-control ot-form-control ot-input expired-date" name="expired_date[]" disabled/></td>';
                }
                cols += '<td class="net_unit_cost"></td>';
                cols += '<td class="discount">0.00</td>';
                cols += '<td class="tax"></td>';
                cols += '<td class="sub-total"></td>';
                cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{_trans("file.delete")}}</button></td>';
                cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
                cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[9] + '"/>';
                cols += '<input type="hidden" class="purchase-unit" name="purchase_unit[]" value="' + temp_unit_name[0] + '"/>';
                cols += '<input type="hidden" class="net_unit_cost" name="net_unit_cost[]" />';
                cols += '<input type="hidden" class="discount-value" name="discount[]" />';
                cols += '<input type="hidden" class="tax-rate" name="tax_rate[]" value="' + data[3] + '"/>';
                cols += '<input type="hidden" class="tax-value" name="tax[]" />';
                cols += '<input type="hidden" class="subtotal-value" name="subtotal[]" />';
                cols += '<input type="hidden" class="imei-number" name="imei_number[]" />';

                newRow.append(cols);
                $("table.order-list tbody").prepend(newRow);

                rowindex = newRow.index();
                product_cost.splice(rowindex, 0, parseFloat(data[2]));
                product_discount.splice(rowindex, 0, '0.00');
                tax_rate.splice(rowindex, 0, parseFloat(data[3]));
                tax_name.splice(rowindex, 0, data[4]);
                tax_method.splice(rowindex, 0, data[5]);
                unit_name.splice(rowindex, 0, data[6]);
                unit_operator.splice(rowindex, 0, data[7]);
                unit_operation_value.splice(rowindex, 0, data[8]);
                is_imei.splice(rowindex, 0, data[11]);
                calculateRowProductData(1);
                if(data[11]) {
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.edit-product').click();
                }
            }
        }
    });
}
function checkQuantity(purchase_qty, flag) {
    var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
    var pos = product_code.indexOf(row_product_code);
    var operator = unit_operator[rowindex].split(',');
    var operation_value = unit_operation_value[rowindex].split(',');
    if(operator[0] == '*')
    	total_qty = purchase_qty * operation_value[0];
    else if(operator[0] == '/')
    	total_qty = purchase_qty / operation_value[0];

    $('#editModal').modal('hide');
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(purchase_qty);
    var status = $('select[name="status"]').val();
    if(status == '1' || status == '2' )
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.recieved').val(purchase_qty);
    else
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.recieved').val(0);

    calculateRowProductData(purchase_qty);
}

function calculateRowProductData(quantity) {
    unitConversion();
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount').text((product_discount[rowindex] * quantity).toFixed(2));
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount-value').val((product_discount[rowindex] * quantity).toFixed(2));
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate').val(tax_rate[rowindex].toFixed(2));

    if (tax_method[rowindex] == 1) {
        var net_unit_cost = row_product_cost - product_discount[rowindex];
        var tax = net_unit_cost * quantity * (tax_rate[rowindex] / 100);
        var sub_total = (net_unit_cost * quantity) + tax;

        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').text(net_unit_cost.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').val(net_unit_cost.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax').text(tax.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sub-total').text(sub_total.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total.toFixed(2));
    } else {
        var sub_total_unit = row_product_cost - product_discount[rowindex];
        var net_unit_cost = (100 / (100 + tax_rate[rowindex])) * sub_total_unit;
        var tax = (sub_total_unit - net_unit_cost) * quantity;
        var sub_total = sub_total_unit * quantity;

        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').text(net_unit_cost.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').val(net_unit_cost.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax').text(tax.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sub-total').text(sub_total.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total.toFixed(2));
    }

    calculateTotal();
}

function unitConversion() {
    var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(","));
    var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[rowindex].indexOf(","));
    row_unit_operation_value = parseFloat(row_unit_operation_value);
    if (row_unit_operator == '*') {
        row_product_cost = product_cost[rowindex] * row_unit_operation_value;
    } else {
        row_product_cost = product_cost[rowindex] / row_unit_operation_value;
    }
}

function calculateTotal() {
    //Sum of quantity
    var total_qty = 0;
    $(".qty").each(function() {

        if ($(this).val() == '') {
            total_qty += 0;
        } else {
            total_qty += parseFloat($(this).val());
        }
    });
    $("#total-qty").text(total_qty);
    $('input[name="total_qty"]').val(total_qty);

    //Sum of discount
    var total_discount = 0;
    $(".discount").each(function() {
        total_discount += parseFloat($(this).text());
    });
    $("#total-discount").text(total_discount.toFixed(2));
    $('input[name="total_discount"]').val(total_discount.toFixed(2));

    //Sum of tax
    var total_tax = 0;
    $(".tax").each(function() {
        total_tax += parseFloat($(this).text());
    });
    $("#total-tax").text(total_tax.toFixed(2));
    $('input[name="total_tax"]').val(total_tax.toFixed(2));

    //Sum of subtotal
    var total = 0;
    $(".sub-total").each(function() {
        total += parseFloat($(this).text());
    });
    $("#total").text(total.toFixed(2));
    $('input[name="total_cost"]').val(total.toFixed(2));

    calculateGrandTotal();
}

function calculateGrandTotal() {

    var item = $('table.order-list tbody tr:last').index();

    var total_qty = parseFloat($('#total-qty').text());
    var subtotal = parseFloat($('#total').text());
    var order_tax = parseFloat($('select[name="order_tax_rate"]').val());
    var order_discount = parseFloat($('input[name="order_discount"]').val());
    var shipping_cost = parseFloat($('input[name="shipping_cost"]').val());

    if (!order_discount)
        order_discount = 0.00;
    if (!shipping_cost)
        shipping_cost = 0.00;

    item = ++item + '(' + total_qty + ')';
    order_tax = (subtotal - order_discount) * (order_tax / 100);
    var grand_total = (subtotal + order_tax + shipping_cost) - order_discount;

    $('#item').text(item);
    $('input[name="item"]').val($('table.order-list tbody tr:last').index() + 1);
    $('#subtotal').text(subtotal.toFixed(2));
    $('#order_tax').text(order_tax.toFixed(2));
    $('input[name="order_tax"]').val(order_tax.toFixed(2));
    $('#order_discount').text(order_discount.toFixed(2));
    $('#shipping_cost').text(shipping_cost.toFixed(2));
    $('#grand_total').text(grand_total.toFixed(2));
    $('input[name="grand_total"]').val(grand_total.toFixed(2));
}

$('input[name="order_discount"]').on("input", function() {
    calculateGrandTotal();
});

$('input[name="shipping_cost"]').on("input", function() {
    calculateGrandTotal();
});

$('select[name="order_tax_rate"]').on("change", function() {
    calculateGrandTotal();
});

$(window).keydown(function(e){
    if (e.which == 13) {
        var $targ = $(e.target);
        if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
            var focusNext = false;
            $(this).find(":input:visible:not([disabled],[readonly]), a").each(function(){
                if (this === e.target) {
                    focusNext = true;
                }
                else if (focusNext){
                    $(this).focus();
                    return false;
                }
            });
            return false;
        }
    }
});

$('#purchase-form').on('submit',function(e){
    var rownumber = $('table.order-list tbody tr:last').index();
    if (rownumber < 0) {
        alert("Please insert product to order table!")
        e.preventDefault();
    }

    else if($('select[name="status"]').val() != 1)
    {
        flag = 0;
        $(".qty").each(function() {
            rowindex = $(this).closest('tr').index();
            quantity =  $(this).val();
            recieved = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.recieved').val();

            if(quantity != recieved){
                flag = 1;
                return false;
            }
        });
        if(!flag){
            alert('Quantity and Recieved value is same! Please Change Purchase Status or Recieved value');
            e.preventDefault();
        }
        else
            $(".batch-no, .expired-date").prop('disabled', false);
    }
    else {
        $("#submit-button").prop('disabled', true);
        $(".batch-no, .expired-date").prop('disabled', false);
    }
});
</script>
@endsection