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
       
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">{{_trans('file.Add Product')}} <span class="text-danger">*</span></label>
                                <div class="search-box input-group">

                                    <button type="button" class="btn btn-secondary btn-lg"><i
                                            class="fa fa-barcode"></i></button>
                                    <input type="text" name="product_code_name" id="ot_crm_productcodeSearch"
                                        placeholder="Please type product code and select..."
                                        class="form-control ot-form-control ot-input">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 table-content table-basic">
                            <div class="col-md-12">
                                <div class="table-responsive mt-3">
                                    <table id="myTable" class="table table-hover order-list">
                                        <thead class="thead">
                                            <tr>
                                                <th>{{_trans('file.Name')}}</th>
                                                <th>{{_trans('file.Code')}}</th>
                                                <th>{{_trans('file.Quantity')}}</th>
                                                <th><i class="dripicons-trash"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            @if($preLoadedproduct)
                                            <tr data-imagedata="{{$preLoadedproduct[3]}}"
                                                data-price="{{$preLoadedproduct[2]}}"
                                                data-promo-price="{{$preLoadedproduct[4]}}"
                                                data-currency="{{$preLoadedproduct[5]}}"
                                                data-currency-position="{{$preLoadedproduct[6]}}">
                                                <td>{{$preLoadedproduct[0]}}</td>
                                                <td class="product-code">{{$preLoadedproduct[1]}}</td>
                                                <td><input type="number"
                                                        class="form-control ot-form-control ot-input qty" name="qty[]"
                                                        value="1" /></td>
                                                <td><button type="button"
                                                        class="ibtnDel btn btn-md btn-danger">Delete</button></td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label">{{_trans('file.Print')}}:</label>
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" >
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="name" checked>
                                        <label class="custom-control-label">{{_trans('file.Product Name')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" >
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="price" checked>
                                        <label class="custom-control-label">{{_trans('file.Price')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" >
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="promo_price">
                                        <label class="custom-control-label">{{_trans('file.Promotional
                                            Price')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Paper Size *</label>
                                <select class="form-select select2-input ot-input mb-3 modal_select2" name="paper_size"
                                    required id="paper-size">
                                    <option data-display="Select" value="">Select paper size...</option>
                                    <option value="36">36 mm (1.4 inch)</option>
                                    <option value="24">24 mm (0.94 inch)</option>
                                    <option value="18">18 mm (0.7 inch)</option>
                                </select>
                            </div>
                        </div>
                        @if (hasPermission('sales_product_barcode_generate'))

                        <div class="form-group mt-3 d-flex justify-content-end">
                            <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn"
                                id="submit-button">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="print-barcode" tabindex="-1" role="dialog" aria-labelledby="print-barcode" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-image mb-3">
                <h5 id="modal_header" class="modal-title">{{_trans('file.Barcode')}}</h5>&nbsp;&nbsp;
                <button id="print-btn" type="button" class="btn btn-default btn-sm"><i class="dripicons-print"></i>
                    {{_trans('file.Print')}}</button>
                <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close"><span
                        aria-hidden="true"><i class="dripicons-cross"></i></span></button>
            </div>
            <div class="modal-body">
                <div id="label-content">
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
<script type="text/javascript">
    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #printBarcode-menu").addClass("active");
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
    select: function(event, ui) {
        var data = ui.item.value;
        $.ajax({
            type: 'GET',
            url: 'ot_crm_product_search',
            data: {
                data: data
            },
            success: function(data) {
                var flag = 1;
                $(".product-code").each(function() {
                    if ($(this).text() == data[1]) {
                        alert('Duplicate input is not allowed!')
                        flag = 0;
                    }
                });
                $("input[name='product_code_name']").val('');
                if(flag){
                    var newRow = $('<tr data-imagedata="'+data[3]+'" data-price="'+data[2]+'" data-promo-price="'+data[4]+'" data-currency="'+data[5]+'" data-currency-position="'+data[6]+'">');
                    var cols = '';
                    cols += '<td>' + data[0] + '</td>';
                    cols += '<td class="product-code">' + data[1] + '</td>';
                    cols += '<td><input type="number" class="form-control ot-form-control ot-input qty" name="qty[]" value="1" /></td>';
                    cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">Delete</button></td>';

                    newRow.append(cols);
                    $("table.order-list tbody").append(newRow);
                }
            }
        });
    }
});

    //Delete product
    $("table.order-list tbody").on("click", ".ibtnDel", function(event) {
        rowindex = $(this).closest('tr').index();
        $(this).closest("tr").remove();
    });

    $("#submit-button").on("click", function(event) {
        paper_size = ($("#paper-size").val());
        if(paper_size != "0") {
            var product_name = [];
            var code = [];
            var price = [];
            var promo_price = [];
            var qty = [];
            var barcode_image = [];
            var currency = [];
            var currency_position = [];
            var rownumber = $('table.order-list tbody tr:last').index();
            for(i = 0; i <= rownumber; i++){
                product_name.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').find('td:nth-child(1)').text());
                code.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').find('td:nth-child(2)').text());
                price.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('price'));
                promo_price.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('promo-price'));
                currency.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('currency'));
                currency_position.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('currency-position'));
                qty.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').find('.qty').val());
                barcode_image.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('imagedata'));
            }
            var htmltext = '<table class="barcodelist" style="width:378px;" cellpadding="5px" cellspacing="10px">';
            $.each(qty, function(index){
                i = 0;
                while(i < qty[index]) {
                    if(i % 2 == 0)
                        htmltext +='<tr>';
                    // 36mm
                    if(paper_size == 36)
                        htmltext +='<td style="width:164px;height:88%;padding-top:7px;vertical-align:middle;text-align:center">';
                    //24mm
                    else if(paper_size == 24)
                        htmltext +='<td style="width:164px;height:100%;font-size:12px;text-align:center">';
                    //18mm
                    else if(paper_size == 18)
                        htmltext +='<td style="width:164px;height:100%;font-size:10px;text-align:center">';

                    if($('input[name="name"]').is(":checked"))
                        htmltext += product_name[index] + '<br>';

                    if(paper_size == 18)
                        htmltext += '<img style="max-width:150px;" src="data:image/png;base64,'+barcode_image[index]+'" alt="barcode" /><br>';
                    else
                        htmltext += '<img style="max-width:150px;" src="data:image/png;base64,'+barcode_image[index]+'" alt="barcode" /><br>';

                    htmltext += code[index] + '<br>';
                    if($('input[name="code"]').is(":checked"))
                        htmltext += '<strong>'+code[index]+'</strong><br>';
                    if($('input[name="promo_price"]').is(":checked")) {
                        if(currency_position[index] == 'prefix')
                            htmltext += 'Price: '+currency[index]+'<span style="text-decoration: line-through;"> '+price[index]+'</span> '+promo_price[index]+'<br>';
                        else
                            htmltext += 'Price: <span style="text-decoration: line-through;">'+price[index]+'</span> '+promo_price[index]+' '+currency[index]+'<br>';
                    }
                    else if($('input[name="price"]').is(":checked")) {
                        if(currency_position[index] == 'prefix')
                            htmltext += 'Price: '+currency[index]+' '+price[index];
                        else
                            htmltext += 'Price: '+price[index]+' '+currency[index];
                    }
                    htmltext +='</td>';
                    if(i % 2 != 0)
                        htmltext +='</tr>';
                    i++;
                }
            });
            htmltext += '</table">';
            $('#label-content').html(htmltext);
            $('#print-barcode').modal('show');
        }
        else
            alert('Please select paper size');
    });

    $("#print-btn").on("click", function() {
          var divToPrint=document.getElementById('print-barcode');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<style type="text/css">@media print { #modal_header { display: none } #print-btn { display: none } #close-btn { display: none } } table.barcodelist { page-break-inside:auto } table.barcodelist tr { page-break-inside:avoid; page-break-after:auto }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},10);
    });

</script>
@endsection