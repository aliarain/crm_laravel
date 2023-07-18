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
             
                {!! Form::open(['route' => ['saleTransfer.update', $ot_crm_transfer_data->id], 'method' => 'post',
                'files' => true, 'id' => 'transfer-form']) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{_trans('file.Date')}}</label>
                                    <input type="date" name="created_at"
                                        class="form-control ot-form-control ot-input date" value="{{date(" Y-m-d",
                                        strtotime($ot_crm_transfer_data->created_at->toDateString()))}}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{_trans('file.reference')}}</label>
                                    <p><strong>{{ $ot_crm_transfer_data->reference_no }}</strong></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{_trans("file.Status")}}</label>
                                    <input type="hidden" name="status_hidden" value="{{ $ot_crm_transfer_data->status }}">
                                    <select name="status" required
                                        class="form-select select2-input ot-input mb-3 modal_select2">
                                        <option value="1">{{_trans('file.Completed')}}</option>
                                        <option value="2">{{_trans('file.Pending')}}</option>
                                        <option value="3">{{_trans('file.Sent')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{_trans('file.From Warehouse')}} <span class="text-danger">*</span></label>
                                    <input type="hidden" name="from_warehouse_id_hidden"
                                        value="{{ $ot_crm_transfer_data->from_warehouse_id }}" />
                                    <select id="from-warehouse-id" required name="from_warehouse_id"
                                        class="selectpicker form-control ot-form-control ot-input"
                                        data-live-search="true" data-live-search-style="begins"
                                        title="Select warehouse...">
                                        @foreach($ot_crm_warehouse_list as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{_trans('file.To Warehouse')}} <span class="text-danger">*</span></label>
                                    <input type="hidden" name="to_warehouse_id_hidden"
                                        value="{{ $ot_crm_transfer_data->to_warehouse_id }}" />
                                    <select required name="to_warehouse_id"
                                        class="selectpicker form-control ot-form-control ot-input"
                                        data-live-search="true" data-live-search-style="begins"
                                        title="Select warehouse...">
                                        @foreach($ot_crm_warehouse_list as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>{{_trans('file.Select Product')}}</label>
                                <div class="search-box input-group">
                                    <button type="button" class="btn btn-secondary btn-lg"><i
                                            class="fa fa-barcode"></i></button>
                                    <input type="text" name="product_code_name" id="ot_crm_productcodeSearch"
                                        placeholder="Please type product code and select..."
                                        class="form-control ot-form-control ot-input" />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 table-content table-basic">
                            <div class="col-md-12">

                                <h5>{{_trans('file.Order Table')}} *</h5>
                                <div class="table-responsive mt-3">
                                    <table id="myTable" class="table table-hover order-list">
                                        <thead class="thead">
                                            <tr>
                                                <th>{{_trans('file.name')}}</th>
                                                <th>{{_trans('file.Code')}}</th>
                                                <th>{{_trans('file.Batch No')}}</th>
                                                <th>{{_trans('file.Quantity')}}</th>
                                                <th>{{_trans('file.Net Unit Cost')}}</th>
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
                                            @foreach($ot_crm_product_transfer_data as $product_transfer)
                                            <tr>
                                                <?php
                                                        $product_data = DB::table('sale_products')->find($product_transfer->product_id);

                                                        if($product_transfer->variant_id) {
                                                            $product_variant_data = Modules\Sale\Entities\SaleProductVariant::select('id', 'item_code')->FindExactProduct($product_data->id, $product_transfer->variant_id)->first();
                                                            $product_variant_id = $product_variant_data->id;
                                                            $product_data->code = $product_variant_data->item_code;
                                                        }
                                                        else
                                                            $product_variant_id = null;

                                                        $tax = DB::table('sale_taxes')->where('rate', $product_transfer->tax_rate)->first();

                                                        $units = DB::table('sale_units')->where('base_unit', $product_data->unit_id)->orWhere('id', $product_data->unit_id)->get();

                                                        $unit_name = array();
                                                        $unit_operator = array();
                                                        $unit_operation_value = array();

                                                        foreach($units as $unit) {
                                                            if($product_transfer->purchase_unit_id == $unit->id) {
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

                                                            $product_cost = $product_transfer->net_unit_cost / $unit_operation_value[0];
                                                        }
                                                        else{
                                                           $product_cost = ($product_transfer->total / $product_transfer->qty) / $unit_operation_value[0];
                                                        }


                                                        $temp_unit_name = $unit_name = implode(",",$unit_name) . ',';

                                                        $temp_unit_operator = $unit_operator = implode(",",$unit_operator) .',';

                                                        $temp_unit_operation_value = $unit_operation_value =  implode(",",$unit_operation_value) . ',';
                                                        $product_batch_data = Modules\Sale\Entities\SaleProductBatch::select('batch_no')->find($product_transfer->product_batch_id);
                                                    ?>
                                                <td>{{$product_data->name}} <button type="button"
                                                        class="edit-product btn btn-link" data-toggle="modal"
                                                        data-target="#editModal"> <i
                                                            class="dripicons-document-edit"></i></button> </td>
                                                <td>{{$product_data->code}}</td>
                                                @if($product_batch_data)
                                                <td>
                                                    <input type="hidden" class="product-batch-id"
                                                        name="product_batch_id[]"
                                                        value="{{$product_transfer->product_batch_id}}">
                                                    <input type="text"
                                                        class="form-control ot-form-control ot-input batch-no"
                                                        name="batch_no[]" value="{{$product_batch_data->batch_no}}"
                                                        required />
                                                </td>
                                                @else
                                                <td>
                                                    <input type="hidden" class="product-batch-id"
                                                        name="product_batch_id[]" value="">
                                                    <input type="text"
                                                        class="form-control ot-form-control ot-input batch-no"
                                                        name="batch_no[]" value="" disabled />
                                                </td>
                                                @endif
                                                <td><input type="number"
                                                        class="form-control ot-form-control ot-input qty" name="qty[]"
                                                        value="{{$product_transfer->qty}}" required step="any" /></td>
                                                <td class="net_unit_cost">{{
                                                    number_format((float)$product_transfer->net_unit_cost, 2, '.',
                                                    '') }} </td>
                                                <td class="tax">{{ number_format((float)$product_transfer->tax, 2,
                                                    '.', '') }}</td>
                                                <td class="sub-total">{{
                                                    number_format((float)$product_transfer->total, 2, '.', '') }}
                                                </td>
                                                <td><button type="button"
                                                        class="ibtnDel btn btn-md btn-danger">{{_trans("file.delete")}}</button>
                                                </td>
                                                <input type="hidden" class="product-id" name="product_id[]"
                                                    value="{{$product_data->id}}" />
                                                <input type="hidden" name="product_variant_id[]"
                                                    value="{{$product_variant_id}}" />
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
                                                    value="{{$product_transfer->net_unit_cost}}" />
                                                <input type="hidden" class="tax-rate" name="tax_rate[]"
                                                    value="{{$product_transfer->tax_rate}}" />
                                                @if($tax)
                                                <input type="hidden" class="tax-name" value="{{$tax->name}}" />
                                                @else
                                                <input type="hidden" class="tax-name" value="No Tax" />
                                                @endif
                                                <input type="hidden" class="tax-method"
                                                    value="{{$product_data->tax_method}}" />
                                                <input type="hidden" class="tax-value" name="tax[]"
                                                    value="{{$product_transfer->tax}}" />
                                                <input type="hidden" class="subtotal-value" name="subtotal[]"
                                                    value="{{$product_transfer->total}}" />
                                                <input type="hidden" class="imei-number" name="imei_number[]"
                                                    value="{{$product_transfer->imei_number}}" />
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="tfoot active">
                                            <th colspan="3">{{_trans('file.Total')}}</th>
                                            <th id="total-qty">{{$ot_crm_transfer_data->total_qty}}</th>
                                            <th></th>
                                            <th id="total-tax">{{
                                                number_format((float)$ot_crm_transfer_data->total_tax, 2, '.', '')}}
                                            </th>
                                            <th id="total">{{ number_format((float)$ot_crm_transfer_data->total_cost,
                                                2, '.', '')}}</th>
                                            <th><i class="dripicons-trash"></i></th>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_qty" value="{{$ot_crm_transfer_data->total_qty}}" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_tax" value="{{$ot_crm_transfer_data->total_tax}}" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_cost"
                                        value="{{$ot_crm_transfer_data->total_cost}}" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="item" value="{{$ot_crm_transfer_data->item}}" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="grand_total"
                                        value="{{$ot_crm_transfer_data->grand_total}}" />
                                    <input type="hidden" name="paid_amount"
                                        value="{{$ot_crm_transfer_data->paid_amount}}" />
                                    <input type="hidden" name="payment_status" value="1" />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>
                                        <strong>{{_trans('file.Shipping Cost')}}</strong>
                                    </label>
                                    <input type="number" name="shipping_cost"
                                        class="form-control ot-form-control ot-input"
                                        value="{{ $ot_crm_transfer_data->shipping_cost }}" step="any" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Attach Document')}}</label>
                                    <i class="dripicons-question" data-toggle="tooltip"
                                        title="Only jpg, jpeg, png, gif, pdf, csv, docx, xlsx and txt file is supported"></i>
                                    <input type="file" name="document" class="form-control ot-form-control ot-input" />
                                    @if($errors->has('extension'))
                                    <span>
                                        <strong>{{ $errors->first('extension') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{_trans('file.Note')}}</label>
                                    <textarea rows="5" class="form-control ot-form-control ot-input"
                                        name="note">{{ $ot_crm_transfer_data->note }}</textarea>
                                </div>
                            </div>
                        </div>
                        @if (hasPermission('sales_transfer_update'))
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
                            <label>{{_trans('file.Unit Cost')}}</label>
                            <input type="number" name="edit_unit_cost" class="form-control ot-form-control ot-input"
                                step="any">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{_trans('file.Product Unit')}}</label>
                            <select name="edit_unit" class="form-select select2-input ot-input mb-3 modal_select2">
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
    $("ul#transfer").siblings('a').attr('aria-expanded','true');
    $("ul#transfer").addClass("show");
// array data depend on warehouse
var ot_crm_product_array = [];
var product_code = [];
var product_name = [];
var product_qty = [];

// array data with selection
var product_cost = [];
var tax_rate = [];
var tax_name = [];
var tax_method = [];
var unit_name = [];
var unit_operator = [];
var unit_operation_value = [];
var is_imei = [];
// temporary array
var temp_unit_name = [];
var temp_unit_operator = [];
var temp_unit_operation_value = [];

var exist_code = [];
var exist_qty = [];
var rowindex;
var row_product_cost;

var rownumber = $('table.order-list tbody tr:last').index();

for(rowindex  =0; rowindex <= rownumber; rowindex++){

    product_cost.push(parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-cost').val()));
    exist_code.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text());
    var quantity = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val());
    exist_qty.push(quantity);
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

//assigning previous value
$('select[name="status"]').val($('input[name="status_hidden"]').val());
$('select[name="from_warehouse_id"]').val($('input[name="from_warehouse_id_hidden"]').val());
$('select[name="to_warehouse_id"]').val($('input[name="to_warehouse_id_hidden"]').val());

$('#item').text($('input[name="item"]').val() + '(' + $('input[name="total_qty"]').val() + ')');
$('#subtotal').text(parseFloat($('input[name="total_cost"]').val()).toFixed(2));
if(!$('input[name="shipping_cost"]').val())
    $('input[name="shipping_cost"]').val('0.00');
$('#shipping_cost').text(parseFloat($('input[name="shipping_cost"]').val()).toFixed(2));
$('#grand_total').text(parseFloat($('input[name="grand_total"]').val()).toFixed(2));
$('select[name="from_warehouse_id"]').prop('disabled', true);

var id = $('select[name="from_warehouse_id"]').val();
    $.get('../getproduct/' + id, function(data) {
        ot_crm_product_array = [];
        product_code = data[0];
        product_name = data[1];
        product_qty = data[2];
        $.each(product_code, function(index) {
            if(exist_code.includes(product_code[index])) {
                pos = exist_code.indexOf(product_code[index]);
                product_qty[index] = product_qty[index] + exist_qty[pos];
            }
            ot_crm_product_array.push(product_code[index] + ' (' + product_name[index] + ')');
        });
    });
//assigning value end

$('select[name="from_warehouse_id"]').on('change', function() {
    var id = $('select[name="from_warehouse_id"]').val();
    $.get('../getproduct/' + id, function(data) {
        ot_crm_product_array = [];
        product_code = data[0];
        product_name = data[1];
        product_qty = data[2];
        $.each(product_code, function(index) {
            ot_crm_product_array.push(product_code[index] + ' (' + product_name[index] + ')');
        });
    });
});

$('#ot_crm_productcodeSearch').on('input', function(){
    var warehouse_id = $('select[name="from_warehouse_id"]').val();
    temp_data = $('#ot_crm_productcodeSearch').val();

    if(!warehouse_id){
        $('#ot_crm_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
        alert('Please select Warehouse!');
    }
});

var ot_crm_productcodeSearch = $('#ot_crm_productcodeSearch');

ot_crm_productcodeSearch.autocomplete({
    source: function(request, response) {
        var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
        response($.grep(ot_crm_product_array, function(item) {
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

//Change quantity
$("#myTable").on('input', '.qty', function() {
    rowindex = $(this).closest('tr').index();
    checkQuantity($(this).val(), true);
});

$("#myTable").on("change", ".batch-no", function () {
    rowindex = $(this).closest('tr').index();
    var product_id = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-id').val();
    var warehouse_id = $('#from-warehouse-id').val();
    $.get('../../check-batch-availability/' + product_id + '/' + $(this).val() + '/' + warehouse_id, function(data) {
        if(data['message'] != 'ok') {
            alert(data['message']);
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.batch-no').val('');
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-batch-id').val('');
        }
        else {
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-batch-id').val(data['product_batch_id']);
            code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-code').val();
            pos = product_code.indexOf(code);
            product_qty[pos] = data['qty'];
        }
    });
});

//Delete product
$("table.order-list tbody").on("click", ".ibtnDel", function(event) {
    rowindex = $(this).closest('tr').index();
    product_cost.splice(rowindex, 1);
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
    edit();
});

//Update product
$('button[name="update_btn"]').on("click", function() {
    var imeiNumbers = $("#editModal input[name=imei_numbers]").val();
    if(imeiNumbers || is_imei[rowindex]) {
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.imei-number').val(imeiNumbers);
    }

    var edit_qty = $('input[name="edit_qty"]').val();
    var edit_unit_cost = $('input[name="edit_unit_cost"]').val();

    var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(","));
    var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[rowindex].indexOf(","));

    if (row_unit_operator == '*') {
        product_cost[rowindex] = $('input[name="edit_unit_cost"]').val() / row_unit_operation_value;
    } else {
        product_cost[rowindex] = $('input[name="edit_unit_cost"]').val() * row_unit_operation_value;
    }

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

function productSearch(data){
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
                    checkQuantity(String(qty), true);
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
                if(data[11])
                    cols += '<td><input type="text" class="form-control ot-form-control ot-input batch-no" required/> <input type="hidden" class="product-batch-id" name="product_batch_id[]"/> </td>';
                else
                    cols += '<td><input type="text" class="form-control ot-form-control ot-input batch-no" disabled/> <input type="hidden" class="product-batch-id" name="product_batch_id[]"/> </td>';
                cols += '<td><input type="number" class="form-control ot-form-control ot-input qty" name="qty[]" value="1" required step="any"/></td>';
                cols += '<td class="net_unit_cost"></td>';
                cols += '<td class="tax"></td>';
                cols += '<td class="sub-total"></td>';
                cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{_trans("file.delete")}}</button></td>';
                cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
                cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[9] + '"/>';
                cols += '<input type="hidden" name="product_variant_id[]" value="' + data[10] + '"/>';
                cols += '<input type="hidden" class="purchase-unit" name="purchase_unit[]" value="' + temp_unit_name[0] + '"/>';
                cols += '<input type="hidden" class="net_unit_cost" name="net_unit_cost[]" />';
                cols += '<input type="hidden" class="tax-rate" name="tax_rate[]" value="' + data[3] + '"/>';
                cols += '<input type="hidden" class="tax-value" name="tax[]" />';
                cols += '<input type="hidden" class="subtotal-value" name="subtotal[]" />';
                cols += '<input type="hidden" class="imei-number" name="imei_number[]" />';

                newRow.append(cols);
                $("table.order-list tbody").prepend(newRow);
                rowindex = newRow.index();
                product_cost.splice(rowindex, 0, parseFloat(data[2]));
                tax_rate.splice(rowindex, 0, parseFloat(data[3]));
                tax_name.splice(rowindex, 0, data[4]);
                tax_method.splice(rowindex, 0, data[5]);
                unit_name.splice(rowindex, 0, data[6]);
                unit_operator.splice(rowindex, 0, data[7]);
                unit_operation_value.splice(rowindex, 0, data[8]);
                is_imei.splice(rowindex, 0, data[12]);
                checkQuantity(1, true);
                if(data[12]) {
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.edit-product').click();
                }
            }
        }
    });
}

function edit() {
    $(".imei-section").remove();
    var imeiNumbers = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.imei-number').val();
    if(imeiNumbers || is_imei[rowindex]) {
        htmlText = '<div class="col-md-12 form-group imei-section"><label>IMEI or Serial Numbers</label><input type="text" name="imei_numbers" value="'+imeiNumbers+'" class="form-control ot-form-control ot-input imei_number" placeholder="Type imei or serial numbers and separate them by comma. Example:1001,2001" step="any"></div>';
        $("#editModal .modal-element").append(htmlText);
    }

    var row_product_name = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(1)').text();
    var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
    $('#modal_header').text(row_product_name + '(' + row_product_code + ')');

    var qty = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val();
    $('input[name="edit_qty"]').val(qty);

    unitConversion();
    $('input[name="edit_unit_cost"]').val(row_product_cost.toFixed(2));

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
    if (total_qty > parseFloat(product_qty[pos])) {
        alert('Quantity exceeds stock quantity!');
        if (flag) {
            purchase_qty = purchase_qty.substring(0, purchase_qty.length - 1);
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(purchase_qty);
        } else {
            edit();
            return;
        }
    }
    else {
        $('#editModal').modal('hide');
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(purchase_qty);
    }
    calculateRowProductData(purchase_qty);
}

function calculateRowProductData(quantity) {
    unitConversion();
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate').val(tax_rate[rowindex].toFixed(2));

    if (tax_method[rowindex] == 1) {
        var net_unit_cost = row_product_cost;
        var tax = net_unit_cost * quantity * (tax_rate[rowindex] / 100);
        var sub_total = (net_unit_cost * quantity) + tax;

        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').text(net_unit_cost.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').val(net_unit_cost.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax').text(tax.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.sub-total').text(sub_total.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total.toFixed(2));
    } else {

        var sub_total_unit = row_product_cost;
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
    var shipping_cost = parseFloat($('input[name="shipping_cost"]').val());

    if (!shipping_cost)
        shipping_cost = 0.00;

    item = ++item + '(' + total_qty + ')';

    var grand_total = (subtotal + shipping_cost);

    $('#item').text(item);
    $('input[name="item"]').val($('table.order-list tbody tr:last').index() + 1);
    $('#subtotal').text(subtotal.toFixed(2));
    $('#shipping_cost').text(shipping_cost.toFixed(2));
    $('#grand_total').text(grand_total.toFixed(2));
    $('input[name="grand_total"]').val(grand_total.toFixed(2));
}

$('input[name="shipping_cost"]').on("input", function() {
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

$('#transfer-form').on('submit',function(e){
    $('select[name="from_warehouse_id"]').prop('disabled', false);
    var rownumber = $('table.order-list tbody tr:last').index();
    if (rownumber < 0) {
        alert("Please insert product to order table!")
        e.preventDefault();
        $('select[name="from_warehouse_id"]').prop('disabled', true);
    }

    else if($('select[name="from_warehouse_id"]').val() == $('select[name="to_warehouse_id"]').val()){
        alert('Both Warehouse can not be same!');
        e.preventDefault();
        $('select[name="from_warehouse_id"]').prop('disabled', true);
    }
    else {
        $("#submit-button").prop('disabled', true);
    }
});
</script>
@endsection