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
            <div class="card-header d-flex align-items-center">
                <h4>{{_trans('file.Update Product')}}</h4>
            </div>
            <div class="card-body">
             
                {!! Form::open(['route' => ['saleAdjustment.update', $ot_crm_adjustment_data->id], 'method' =>
                'post', 'files' => true, 'id' => 'adjustment-form']) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.reference')}}</label>
                                    <p><strong>{{$ot_crm_adjustment_data->reference_no}}</strong></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Warehouse')}} <span class="text-danger">*</span></label>
                                    <select required id="warehouse_id" name="warehouse_id"
                                        class="form-select select2-input ot-input mb-3 modal_select2"
                                        data-live-search="true" data-live-search-style="begins"
                                        title="Select warehouse...">
                                        @foreach($ot_crm_warehouse_list as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="warehouse_id_hidden"
                                        value="{{$ot_crm_adjustment_data->warehouse_id}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{_trans('file.Attach Document')}}</label>
                                    <input type="file" name="document" class="form-control ot-form-control ot-input">
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
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <h5>{{_trans('file.Order Table')}} *</h5>
                                <div class="table-responsive mt-3">
                                    <table id="myTable" class="table table-hover order-list">
                                        <thead class="thead">
                                            <tr>
                                                <th>{{_trans('file.name')}}</th>
                                                <th>{{_trans('file.Code')}}</th>
                                                <th>{{_trans('file.Quantity')}}</th>
                                                <th>{{_trans('file.action')}}</th>
                                                <th><i class="dripicons-trash"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            @foreach($ot_crm_product_adjustment_data as $product_adjustment_data)
                                            <tr>
                                                <?php
                                                	   $product = DB::table('sale_products')->find($product_adjustment_data->product_id);
                                                       if($product_adjustment_data->variant_id) {
                                                            $product_variant = Modules\Sale\Entities\SaleProductVariant::select('id', 'item_code')->FindExactProduct($product_adjustment_data->product_id, $product_adjustment_data->variant_id)->first();
                                                            $product->code = $product_variant->item_code;
                                                            $product_variant_id = $product_variant->id;
                                                       }
                                                       else
                                                            $product_variant_id = null;
                                                	?>
                                                <td>{{$product->name}}</td>
                                                <td>{{$product->code}}</td>
                                                <td><input type="number"
                                                        class="form-control ot-form-control ot-input qty" name="qty[]"
                                                        value="{{$product_adjustment_data->qty}}" required step="any" />
                                                </td>
                                                <td class="action">
                                                    <select name="action[]"
                                                        class="form-select select2-input ot-input mb-3 modal_select2 act-val">
                                                        @if($product_adjustment_data->action == '+')
                                                        <option value="+">{{_trans("file.Addition")}}</option>
                                                        <option value="-">{{_trans("file.Subtraction")}}
                                                        </option>
                                                        @else
                                                        <option value="-">{{_trans("file.Subtraction")}}
                                                        </option>
                                                        <option value="+">{{_trans("file.Addition")}}</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td><button type="button"
                                                        class="ibtnDel btn btn-md btn-danger">{{_trans("file.delete")}}</button>
                                                    <input type="hidden" name="product_code[]" class="product-code"
                                                        value="{{$product->code}}" />
                                                    <input type="hidden" class="product-id" name="product_id[]"
                                                        value="{{$product->id}}" />
                                                    <input type="hidden" name="product_variant_id[]"
                                                        value="{{$product_variant_id}}" />
                                                </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                        <tfoot class="tfoot active">
                                            <th colspan="2">{{_trans('file.Total')}}</th>
                                            <th id="total-qty" colspan="2">0</th>
                                            <th><i class="dripicons-trash"></i></th>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_qty" />
                                    <input type="hidden" name="item" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{_trans('file.Note')}}</label>
                                    <textarea rows="5" class="form-control ot-form-control ot-input"
                                        name="note">{{$ot_crm_adjustment_data->note}}</textarea>
                                </div>
                            </div>
                        </div>
                        @if (hasPermission('sales_product_stock_adjustment_update'))
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
    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
var ot_crm_product_array = [];
var product_code = [];
var product_name = [];
var product_qty = [];

var exist_code = [];
var exist_qty = [];

var rownumber = $('table.order-list tbody tr:last').index();

for(rowindex  =0; rowindex <= rownumber; rowindex++){
    exist_code.push($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text());
    var quantity = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val());
    exist_qty.push(quantity);
}

	$('.selectpicker').selectpicker({
	    style: 'btn-link',
	});
	$('select[name="warehouse_id"]').val($('input[name="warehouse_id_hidden"]').val());
	$('.selectpicker').selectpicker('refresh');
	calculateTotal();

	$('#ot_crm_productcodeSearch').on('input', function(){
	    var warehouse_id = $('#warehouse_id').val();
	    temp_data = $('#ot_crm_productcodeSearch').val();

	    if(!warehouse_id){
	        $('#ot_crm_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
	        alert('Please select Warehouse!');
	    }
	});

	var id = $('#warehouse_id').val();
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

	$('select[name="warehouse_id"]').on('change', function() {
	    var id = $('#warehouse_id').val();
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

	$("#myTable").on('input', '.qty', function() {
	    rowindex = $(this).closest('tr').index();
	    checkQuantity($(this).val(), true);
	});

	$("table.order-list tbody").on("click", ".ibtnDel", function(event) {
	    rowindex = $(this).closest('tr').index();
	    $(this).closest("tr").remove();
	    calculateTotal();
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

    $('#adjustment-form').on('submit',function(e){
        var rownumber = $('table.order-list tbody tr:last').index();
        if (rownumber < 0) {
            alert("Please insert product to order table!")
            e.preventDefault();
        }
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
                        checkQuantity(qty);
                        flag = 0;
                    }
                });
                $("input[name='product_code_name']").val('');
                if(flag){
                    var newRow = $("<tr>");
                    var cols = '';
                    cols += '<td>' + data[0] + '</td>';
                    cols += '<td>' + data[1] + '</td>';
                    cols += '<td><input type="number" class="form-control ot-form-control ot-input qty" name="qty[]" value="1" required step="any"/></td>';
                    cols += '<td class="action"><select name="action[]" class="form-control ot-form-control ot-input act-val"><option value="-">{{_trans("file.Subtraction")}}</option><option value="+">{{_trans("file.Addition")}}</option></select></td>';
                    cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{_trans("file.delete")}}</button></td>';
                    cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
                    cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[2] + '"/>';
                    cols += '<input type="hidden" name="product_variant_id[]" value="' + data[3] + '"/>';

                    newRow.append(cols);
                    $("table.order-list tbody").append(newRow);
                    $('.selectpicker').selectpicker('refresh');
                    rowindex = newRow.index();
                    calculateTotal();
                }
            }
        });
    }

	function checkQuantity(qty) {
	    var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
        var action = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.act-val').val();
	    var pos = product_code.indexOf(row_product_code);
	    if ( (qty > parseFloat(product_qty[pos])) && (action == '-') ) {
	        alert('Quantity exceeds stock quantity!');
            var row_qty = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val();
            row_qty = row_qty.substring(0, row_qty.length - 1);
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(row_qty);
	    }
	    else {
	        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(qty);
	    }
        calculateTotal();
	}

	function calculateTotal() {
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
	    $('input[name="item"]').val($('table.order-list tbody tr:last').index() + 1);
	}
</script>
@endsection