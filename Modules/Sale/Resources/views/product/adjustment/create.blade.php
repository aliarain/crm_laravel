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
       
                {!! Form::open(['route' => 'saleAdjustment.store', 'method' => 'post', 'files' => true, 'id' =>
                'adjustment-form']) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{_trans('file.Warehouse')}} <span class="text-danger">*</span></label>
                                    <select required id="warehouse_id" name="warehouse_id"
                                        class="form-select select2-input ot-input mb-3 modal_select2"
                                        data-live-search="true" data-live-search-style="begins">
                                        <option data-display="Select" value="" disabled>Select One</option>
                                        @foreach($ot_crm_warehouse_list as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{_trans('file.Attach Document')}}</label>
                                    <input type="file" name="document" class="form-control ot-form-control ot-input">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="form-label">{{_trans('file.Select Product')}}</label>
                                <div class="search-box input-group">
                                    <button type="button" class="btn btn-secondary btn-lg"><i
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
                                        </tbody>
                                        <tfoot class="tfoot active">
                                            <tr>
                                                <th colspan="2">{{_trans('file.Total')}}</th>
                                                <th id="total-qty" colspan="2">0</th>
                                                <th><i class="dripicons-trash"></i></th>
                                            </tr>
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
                                    <label class="form-label">{{_trans('file.Note')}}</label>
                                    <textarea rows="5" class="form-control ot-form-control ot_input"
                                        name="note"></textarea>
                                </div>
                            </div>
                        </div>
                        @if (hasPermission('sales_product_stock_adjustment_store'))
                        <div class="form-group d-flex justify-content-end mt-20">
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
    $("ul#product #adjustment-create-menu").addClass("active");
    var ot_crm_product_array = [];
    var product_code = [];
    var product_name = [];
    var product_qty = [];
	$('select[name="warehouse_id"]').on('change', function() {
	    var id = $(this).val();
	    $.get('getproduct/' + id, function(data) {
	        ot_crm_product_array = [];
	        product_code = data[0];
	        product_name = data[1];
	        product_qty = data[2];
	        $.each(product_code, function(index) {
	            ot_crm_product_array.push(product_code[index] + ' (' + product_name[index] + ')');
	        });
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
            url: 'ot_crm_product_search',
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
                    cols += '<td><input type="number" class="form-control qty" name="qty[]" value="1" required step="any" /></td>';
                    cols += '<td class="action"><select name="action[]" class="form-control act-val"><option value="-">{{_trans("file.Subtraction")}}</option><option value="+">{{_trans("file.Addition")}}</option></select></td>';
                    cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{_trans("file.delete")}}</button></td>';
                    cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
                    cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[2] + '"/>';

                    newRow.append(cols);
                    $("table.order-list tbody").append(newRow);
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