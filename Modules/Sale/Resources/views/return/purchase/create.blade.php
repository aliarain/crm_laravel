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
                
                {!! Form::open(['route' => 'purchaseReturn.store', 'method' => 'post', 'files' => true, 'class' =>
                'payment-form']) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="row  table-content table-basic">
                            <div class="col-md-12">
                                <input type="hidden" name="purchase_id" value="{{$ot_crm_purchase_data->id}}">
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
                                                <th>{{_trans('file.Discount')}}</th>
                                                <th>{{_trans('file.Tax')}}</th>
                                                <th>{{_trans('file.Subtotal')}}</th>
                                                <th>Choose</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            @foreach($ot_crm_product_purchase_data as $product_purchase)
                                            <tr>
                                                <?php
                                                        $product_data = DB::table('sale_products')->find($product_purchase->product_id);
                                                        if($product_purchase->variant_id) {
                                                            $product_variant_data = Modules\Sale\Entities\SaleProductVariant::select('id', 'item_code')->FindExactProduct($product_data->id, $product_purchase->variant_id)->first();
                                                            $product_variant_id = $product_variant_data->id;
                                                            $product_data->code = $product_variant_data->item_code;
                                                        }
                                                        else
                                                            $product_variant_id = null;
                                                        if($product_data->tax_method == 1){
                                                            $product_cost = $product_purchase->net_unit_cost + ($product_purchase->discount / $product_purchase->qty);
                                                        }
                                                        elseif ($product_data->tax_method == 2) {
                                                            $product_cost =($product_purchase->total / $product_purchase->qty) + ($product_purchase->discount / $product_purchase->qty);
                                                        }

                                                        $tax = DB::table('sale_taxes')->where('rate',$product_purchase->tax_rate)->first();
                                                        if($product_data->type == 'standard'){
                                                            $unit = DB::table('sale_units')->select('unit_name')->find($product_data->unit_id);
                                                           $unit_name = $unit->unit_name;
                                                        }
                                                        else {
                                                            $unit_name = 'n/a';
                                                        }
                                                        $product_batch_data = Modules\Sale\Entities\SaleProductBatch::select('batch_no')->find($product_purchase->product_batch_id);
                                                    ?>
                                                <td>{{$product_data->name}}</td>
                                                <td>{{$product_data->code}}</td>
                                                @if($product_batch_data)
                                                <td>
                                                    <input type="hidden" class="product-batch-id"
                                                        name="product_batch_id[]"
                                                        value="{{$product_purchase->product_batch_id}}">
                                                    {{$product_batch_data->batch_no}}
                                                </td>
                                                @else
                                                <td>
                                                    <input type="hidden" class="product-batch-id"
                                                        name="product_batch_id[]">
                                                    N/A
                                                </td>
                                                @endif
                                                <td>
                                                    <input type="hidden" name="actual_qty[]" class="actual-qty"
                                                        value="{{$product_purchase->qty}}">
                                                    <input type="number"
                                                        class="form-control ot-form-control ot-input qty" name="qty[]"
                                                        value="{{$product_purchase->qty}}" required step="any"
                                                        max="{{$product_purchase->qty}}" />
                                                </td>
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
                                                <td><input type="checkbox" class="is-return" name="is_return[]"
                                                        value="{{$product_data->id}}"></td>
                                                <input type="hidden" class="product-code" name="product_code[]"
                                                    value="{{$product_data->code}}" />
                                                <input type="hidden" name="product_id[]" class="product-id"
                                                    value="{{$product_data->id}}" />
                                                <input type="hidden" class="unit-cost"
                                                    value="{{$product_purchase->total/$product_purchase->qty}}">
                                                <input type="hidden" name="product_variant_id[]"
                                                    value="{{$product_variant_id}}" />
                                                <input type="hidden" class="product-cost" name="product_cost[]"
                                                    value="{{$product_cost}}" />
                                                <input type="hidden" class="purchase-unit" name="purchase_unit[]"
                                                    value="{{$unit_name}}" />
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
                                                <input type="hidden" class="unit-tax-value"
                                                    value="{{$product_purchase->tax / $product_purchase->qty}}" />
                                                <input type="hidden" class="tax-value" name="tax[]"
                                                    value="{{$product_purchase->tax}}" />
                                                <input type="hidden" class="subtotal-value" name="subtotal[]"
                                                    value="{{$product_purchase->total}}" />
                                                <input type="hidden" class="imei-number" name="imei_number[]"
                                                    value="{{$product_purchase->imei_number}}" />
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_qty" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_discount" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_tax" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_cost" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="item" />
                                    <input type="hidden" name="order_tax" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="grand_total" />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Account')}}</label>
                                    <select class="form-select select2-input ot-input mb-3 modal_select2"
                                        name="account_id">
                                        @foreach($ot_crm_account_list as $account)
                                        <option value="{{$account->id}}">{{$account->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Order Tax')}}</label>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{_trans('file.Return Note')}}</label>
                                    <textarea rows="5" class="form-control ot-form-control ot-input" 
                                        name="return_note"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{_trans('file.Staff Note')}}</label>
                                    <textarea rows="5" class="form-control ot-form-control ot-input"
                                        name="staff_note"></textarea>
                                </div>
                            </div>
                        </div>
                        @if (hasPermission('sales_return_purchase_store'))
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

@endsection
@section('script')

<script type="text/javascript">
    $("ul#return").siblings('a').attr('aria-expanded','true');
    $("ul#return").addClass("show");
    $("ul#return #purchase-return-menu").addClass("active");

var product_code = [];
var product_cost = [];
var product_discount = [];
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

var rowindex;
var row_product_cost;


$('[data-toggle="tooltip"]').tooltip();

//choosing the returned product
$("#myTable").on("change", ".is-return", function () {
    calculateTotal();
});

//Change quantity
$("#myTable").on('input', '.qty', function() {
    rowindex = $(this).closest('tr').index();
    if($(this).val() < 1 && $(this).val() != '') {
      $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(1);
      alert("Quantity can't be less than 1");
    }
    calculateTotal();
});

$('select[name="order_tax_rate"]').on("change", function() {
    calculateGrandTotal();
});

function calculateTotal() {
    var total_qty = 0;
    var total_discount = 0;
    var total_tax = 0;
    var total = 0;
    var item = 0;
    $(".is-return").each(function(i) {
        if ($(this).is(":checked")) {
            var actual_qty = $('table.order-list tbody tr:nth-child(' + (i + 1) + ') .actual-qty').val();
            var qty = $('table.order-list tbody tr:nth-child(' + (i + 1) + ') .qty').val();
            if(qty > actual_qty) {
                alert('Quantity can not be bigger than the actual quantity!');
                qty = actual_qty;
                $('table.order-list tbody tr:nth-child(' + (i + 1) + ') .qty').val(actual_qty);
            }
            var discount = $('table.order-list tbody tr:nth-child(' + (i + 1) + ') .discount').text();
            var tax = $('table.order-list tbody tr:nth-child(' + (i + 1) + ') .unit-tax-value').val() * qty;
            var unit_cost = $('table.order-list tbody tr:nth-child(' + (i + 1) + ') .unit-cost').val();

            total_qty += parseFloat(qty);
            total_discount += parseFloat(discount);
            total_tax += parseFloat(tax);
            total += parseFloat(unit_cost * qty);
            $('table.order-list tbody tr:nth-child(' + (i + 1) + ') .subtotal-value').val(unit_cost * qty);
            $('table.order-list tbody tr:nth-child(' + (i + 1) + ') .sub-total').text(parseFloat(unit_cost * qty).toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (i + 1) + ') .tax-value').val(parseFloat(tax).toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (i + 1) + ') .tax').text(parseFloat(tax).toFixed(2));
            item++;
        }
    });
    $('input[name="total_qty"]').val(total_qty);

    $('input[name="total_discount"]').val(total_discount.toFixed(2));

    $('input[name="total_tax"]').val(total_tax.toFixed(2));

    $('input[name="total_cost"]').val(total.toFixed(2));
    $('input[name="item"]').val(item);
    item += '(' + total_qty + ')';
    $('#item').text(item);

    calculateGrandTotal();
}

function calculateGrandTotal() {
    var total_qty = parseFloat($('input[name="total_qty"]').val());
    var subtotal = parseFloat($('input[name="total_cost"]').val());
    var order_tax = parseFloat($('select[name="order_tax_rate"]').val());
    var order_tax = subtotal * (order_tax / 100);
    var grand_total = subtotal + order_tax;

    
    $('#subtotal').text(subtotal.toFixed(2));
    $('#order_tax').text(order_tax.toFixed(2));
    $('input[name="order_tax"]').val(order_tax.toFixed(2));
    $('#grand_total').text(grand_total.toFixed(2));
    $('input[name="grand_total"]').val(grand_total.toFixed(2));
}

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

$('.payment-form').on('submit',function(e){
    
    var rownumber = $('table.order-list tbody tr:last').index();
    if (rownumber < 0) {
        alert("Please insert product to order table!")
        e.preventDefault();
    }
    else {
        $("#submit-button").prop('disabled', true);
    }

    if($('.is-return').not(':checked').length == $('.is-return').length) {
        alert("Please select at least one product to return!");
        e.preventDefault();
    }
});

</script>
@endsection