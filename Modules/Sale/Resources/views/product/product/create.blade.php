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
                <form id="product-form">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Product Type')}} * </label>
                                <div class="input-group">
                                    <select name="type" required
                                        class="form-select select2-input ot-input mb-3 modal_select2" id="type">
                                        <option value="standard">{{_trans('common.Standard')}}</option>
                                        <option value="combo">{{_trans('common.Combo')}}</option>
                                        <option value="digital">{{_trans('common.Digital')}}</option>
                                        <option value="service">{{_trans('common.Service')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Product Name')}} * </label>
                                <input type="text" name="name" class="form-control ot-form-control ot-input" id="name"
                                    aria-describedby="name" required placeholder="{{_trans('file.Product Name')}}">
                                <span class="validation-msg name text-danger" id="name-error"></span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Product Code')}} * </label>
                                <div class="input-group">
                                    <input type="text" name="code" class="form-control ot-form-control ot-input"
                                        id="code" aria-describedby="code" required placeholder="{{_trans('file.Product Code')}}">
                                    <div class="input-group-append">
                                        <button id="genbutton" type="button" class="btn btn-sm btn-default"
                                            title="{{_trans('file.Generate')}}"><i class="fa fa-refresh"></i></button>
                                    </div>
                                </div>
                                <span class="validation-msg code text-danger" id="code-error"></span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Barcode Symbology')}} * </label>
                                <div class="input-group">
                                    <select name="barcode_symbology" required
                                        class="form-select select2-input ot-input mb-3 modal_select2">
                                        <option value="C128">{{_trans('common.Code 128')}}</option>
                                        <option value="C39">{{_trans('common.Code 39')}}</option>
                                        <option value="UPCA">{{_trans('common.UPC-A')}}</option>
                                        <option value="UPCE">{{_trans('common.UPC-E')}}</option>
                                        <option value="EAN8">{{_trans('common.EAN-8')}}</option>
                                        <option value="EAN13">{{_trans('common.EAN-13')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="digital" class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Attach File')}} * </label>
                                <div class="input-group">
                                    <input type="file" name="file" class="form-control">
                                </div>
                                <span class="validation-msg"></span>
                            </div>
                        </div>
                        <div id="combo" class="col-md-9 mb-1 table-content table-basic">
                            <label class="form-label">{{_trans('file.add_product')}}</label>
                            <div class="search-box input-group mb-3">
                                <button class="btn btn-secondary"><i class="fa fa-barcode"></i></button>
                                <input type="text" name="product_code_name" id="ot_crm_productcodeSearch"
                                    placeholder="Please type product code and select..."
                                    class="form-control ot-form-control ot_input" />
                            </div>
                            <label class="form-label">{{_trans('file.Combo Products')}}</label>
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Brand')}} </label>
                                <div class="input-group">
                                    <select name="brand_id"
                                        class="form-select select2-input ot-input mb-3 modal_select2"
                                        data-live-search="true" data-live-search-style="begins" title="Select Brand...">
                                        @foreach($ot_crm_brand_list as $brand)
                                        <option value="{{$brand->id}}">{{$brand->title}}</option>
                                        @endforeach
                                    </select>
                                    <span class="validation-msg brand text-danger" id="code-error"></span>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.category')}} * </label>
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
                                <span class="validation-msg category text-danger"></span>
                            </div>
                        </div>
                        <div id="unit" class="col-md-12">
                            <div class="row ">
                                <div class="col-md-4 mb-3 form-group">
                                    <label class="form-label">{{_trans('file.Product Unit')}} * </label>
                                    <div class="input-group">
                                        <select required class="form-select select2-input ot-input mb-3 modal_select2"
                                            name="unit_id">
                                            <option value="" disabled selected>Select Product Unit...</option>
                                            @foreach($ot_crm_unit_list as $unit)
                                            @if($unit->base_unit==null)
                                            <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <span class="validation-msg"></span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{_trans('file.Sale Unit')}} </label>
                                    <div class="input-group">
                                        <select class="form-select select2-input ot-input mb-3 modal_select2"
                                            name="sale_unit_id">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">{{_trans('file.Purchase Unit')}} </label>
                                        <div class="input-group">
                                            <select class="form-select select2-input ot-input mb-3 modal_select2"
                                                name="purchase_unit_id">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="cost" class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Product Cost')}} * </label>
                                <input type="number" name="cost" required class="form-control ot-form-control ot-input" placeholder="{{_trans('file.Product Cost')}}"
                                    step="any">
                                <span class="validation-msg"></span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Product Price')}} * </label>
                                <input type="number" name="price" required class="form-control ot-form-control ot-input" placeholder="{{_trans('file.Product Price')}}"
                                    step="any">
                                <span class="validation-msg price text-danger"></span>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="qty" value="0.00">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Daily Sale Objective')}}</label> <i
                                    class="dripicons-question" data-toggle="tooltip"
                                    title="{{_trans('file.Minimum qty which must be sold in a day. If not, you will be notified on dashboard. But you have to set up the cron job properly for that. Follow the documentation in that regard.')}}"></i>
                                <input type="number" name="daily_sale_objective"
                                    class="form-control ot-form-control ot-input" step="any" placeholder="{{_trans('file.Daily Sale Objective')}}">
                            </div>
                        </div>
                        <div id="alert-qty" class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Alert Quantity')}} </label>
                                <input type="number" name="alert_quantity" class="form-control ot-form-control ot-input" placeholder="{{_trans('file.Alert Quantity ')}}"
                                    step="any">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Product Tax')}} </label>
                                <select name="tax_id" class="form-select select2-input ot-input mb-3 modal_select2">
                                    <option value="">No Tax</option>
                                    @foreach($ot_crm_tax_list as $tax)
                                    <option value="{{$tax->id}}">{{$tax->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Tax Method')}} </label> <i class="dripicons-question"
                                    data-toggle="tooltip"
                                    title="{{_trans('file.Exclusive: Poduct price = Actual product price + Tax. Inclusive: Actual product price = Product price - Tax')}}"></i>
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
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="featured" value="1">
                                        <label class="custom-control-label">{{_trans('common.Featured')}}</label>
                                    </div>
                                </div>
                                <div class="timeline-info">
                                    <span class="info-details fw-light">{{_trans('common.Featured product will be displayed in
                                        POS')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio">
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_embeded" value="1">
                                        <label class="custom-control-label">{{_trans('common.Embedded Barcode')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Product Image')}} </label>
                                <div id="imageUpload" class="dropzone "></div>
                                <span class="validation-msg" id="image-error"></span>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{_trans('file.Product Details')}}</label>
                                <textarea name="product_details" class="form-control ot-form-control ot_input"  rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" id="variant-option">
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_variant" id="is-variant" value="1">
                                        <label class="custom-control-label">{{_trans('common.This product has variant')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3" id="variant-section">
                            <div class="row" id="variant-input-section">
                                <div class="col-md-4 form-group mt-2">
                                    <label class="form-label">{{_trans('file.Option')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="variant_option[]"
                                        class="form-control variant-field ot_input" placeholder="Size, Color etc...">
                                </div>
                                <div class="col-md-6 form-group mt-2">
                                    <label class="form-label">{{_trans('file.Value')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="variant_value[]"
                                        class="type-variant form-control variant-field ot_input">
                                </div>
                            </div>
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
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" id="diffPrice-option">
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_diffPrice" id="is-diffPrice" value="1">
                                        <label class="custom-control-label" for="is-diffPrice">{{_trans('common.This product has different price for different warehouse')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="diffPrice-section">
                            <div class="table-responsive ml-2">
                                <table id="diffPrice-table" class="table table-hover">
                                    <thead class="thead">
                                        <tr>
                                            <th>{{_trans('file.Warehouse')}}</th>
                                            <th>{{_trans('file.Price')}}</th>
                                        </tr>
                                        @foreach($ot_crm_warehouse_list as $warehouse)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="warehouse_id[]" value="{{$warehouse->id}}">
                                                {{$warehouse->name}}
                                            </td>
                                            <td><input type="number" name="diff_price[]" class="form-control ot_input">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </thead>
                                    <tbody class="tbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" id="batch-option">
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_batch" id="is-batch" value="1">
                                        <label class="custom-control-label" for="is-batch">{{_trans('common.This product has batch and expired date')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" id="imei-option">
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="is_imei" id="is_imei">
                                        <label class="custom-control-label" for="is_imei">{{_trans('common.This product has IMEI or Serial numbers')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="permission-list-td form-group mb-3">
                                <div class="input-check-radio" id="imei-option">
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input mt-0 mr-4 read common-key"
                                            name="promotion" id="promotion" value="1">
                                        <label class="custom-control-label" for="promotion">{{_trans('common.Add Promotional Price')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-4" id="promotion_price">
                                    <label class="form-label">{{_trans('file.Promotional Price')}}</label>
                                    <input type="number" name="promotion_price" placeholder="{{_trans('file.Promotional Price')}}"
                                        class="form-control ot-form-control ot_input" step="any" />
                                </div>
                                <div class="col-md-4" id="start_date">
                                    <div class="form-group">
                                        <label class="form-label">{{_trans('file.Promotion Starts')}}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="dripicons-calendar"></i>
                                                </div>
                                            </div>
                                            <input type="text" name="starting_date" id="starting_date"
                                                class="form-control ot-form-control ot_input" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" id="last_date">
                                    <div class="form-group">
                                        <label class="form-label">{{_trans('file.Promotion Ends')}}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="dripicons-calendar"></i>
                                                </div>
                                            </div>
                                            <input type="text" name="last_date" id="ending_date"
                                                class="form-control ot-form-control ot_input" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (hasPermission('sales_products_store'))
                    <div class="form-group d-flex justify-content-end">
                        <input type="button" value="{{_trans('file.submit')}}" id="submit-btn" class="crm_theme_btn text-center">
                    </div>
                    @endif
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
    $("ul#product #product-create-menu").addClass("active");

    $("#digital").hide();
    $("#combo").hide();
    $("#variant-section").hide();
    $("#diffPrice-section").hide();
    $("#promotion_price").hide();
    $("#start_date").hide();
    $("#last_date").hide();
    var variantPlaceholder = <?php echo json_encode(_trans('file.Enter variant value seperated by comma')); ?>;
    var variantIds = [];
    var combinations = [];
    var oldCombinations = [];
    var oldAdditionalCost = [];
    var oldAdditionalPrice = [];
    var step;

    $('[data-toggle="tooltip"]').tooltip();

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
                
                //start custom code
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
                    $(".variant-name").each(function(i) {
                        oldCombinations.push($(this).text());
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
                    cols += '<td class="variant-name">'+variant_name+'<input type="hidden" name="variant_name[]" value="' + variant_name + '" /></td>';
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
                //end custom code
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
    
    tinymce.init({
      selector: 'textarea',
      height: 130,
      plugins: [
        'advlist autolink lists link image charmap print preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code wordcount'
      ],
      toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
      branding:false,
      
    content_css: "{{url('Modules/Sale/public/tinymce/js/tinymce/skins/lightgray/content.min.css')}}"
    });

    $('select[name="type"]').on('change', function() {
        if($(this).val() == 'combo'){
            $("input[name='cost']").prop('required',false);
            $("select[name='unit_id']").prop('required',false);
            hide();
            $("#combo").show(300);
            $("input[name='price']").prop('disabled',true);
            $("#is-variant").prop("checked", false);
            $("#is-diffPrice").prop("checked", false);
            $("#variant-section, #variant-option, #diffPrice-option, #diffPrice-section").hide(300);
        }
        else if($(this).val() == 'digital'){
            $("input[name='cost']").prop('required',false);
            $("select[name='unit_id']").prop('required',false);
            $("input[name='file']").prop('required',true);
            hide();
            $("#digital").show(300);
            $("#combo").hide(300);
            $("input[name='price']").prop('disabled',false);
            $("#is-variant").prop("checked", false);
            $("#is-diffPrice").prop("checked", false);
            $("#variant-section, #variant-option, #diffPrice-option, #diffPrice-section").hide(300);
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
        else if($(this).val() == 'standard') {
            $("input[name='cost']").prop('required',true);
            $("select[name='unit_id']").prop('required',true);
            $("input[name='file']").prop('required',false);
            $("#cost").show(300);
            $("#unit").show(300);
            $("#alert-qty").show(300);
            $("#variant-option").show(300);
            $("#diffPrice-option").show(300);
            $("#digital").hide(300);
            $("#combo").hide(300);
            $("input[name='price']").prop('disabled',false);
        }
    });

    $('select[name="unit_id"]').on('change', function() {

        unitID = $(this).val();
        if(unitID) {
            populate_category(unitID);
        }else{
            $('select[name="sale_unit_id"]').empty();
            $('select[name="purchase_unit_id"]').empty();
        }
    });
    <?php $productArray = []; ?>
    var ot_crm_product_code = [
        @foreach($ot_crm_product_list_without_variant as $product)
            <?php
                $productArray[] = htmlspecialchars($product->code) . ' (' . preg_replace('/[\n\r]/', "<br>", htmlspecialchars($product->name)) . ')';
            ?>
        @endforeach
        @foreach($ot_crm_product_list_with_variant as $product)
            <?php
                $productArray[] = htmlspecialchars($product->item_code) . ' (' . preg_replace('/[\n\r]/', "<br>", htmlspecialchars($product->name)) . ')';
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
                url: 'products/ot_crm_product_search',
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

    $("#myTable").on('input', '.qty , .unit_price', function() {
        calculate_price();
    });

    $("table.order-list tbody").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        calculate_price();
    });

    function hide() {
        $("#cost").hide(300);
        $("#unit").hide(300);
        $("#alert-qty").hide(300);
    }

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

    function populate_category(unitID){
        $.ajax({
            url: 'saleunit/'+unitID,
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
    }

    $("input[name='is_batch']").on("change", function () {
        if ($(this).is(':checked')) {
            $("#variant-option").hide(300);
        }
        else
            $("#variant-option").show(300);
    });

    $("input[name='is_variant']").on("change", function () {
        if ($(this).is(':checked')) {
            $("#variant-section").show(300);
            $("#batch-option").hide(300);
            $(".variant-field").prop("required", true);
        }
        else {
            $("#variant-section").hide(300);
            $("#batch-option").show(300);
            $(".variant-field").prop("required", false);
        }
    });

    $("input[name='is_diffPrice']").on("change", function () {
        if ($(this).is(':checked')) {
            $("#diffPrice-section").show(300);
        }
        else
            $("#diffPrice-section").hide(300);
    });

    $( "#promotion" ).on( "change", function() {
        if ($(this).is(':checked')) {
            $("#starting_date").val($.datepicker.formatDate('dd-mm-yy', new Date()));
            $("#promotion_price").show(300);
            $("#start_date").show(300);
            $("#last_date").show(300);
        }
        else {
            $("#promotion_price").hide(300);
            $("#start_date").hide(300);
            $("#last_date").hide(300);
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
    //dropzone portion
    Dropzone.autoDiscover = false;

    $(".dropzone").sortable({
        items:'.dz-preview',
        cursor: 'grab',
        opacity: 0.5,
        containment: '.dropzone',
        distance: 20,
        tolerance: 'pointer',
        stop: function () {
          var queue = myDropzone.getAcceptedFiles();
          newQueue = [];
          $('#imageUpload .dz-preview .dz-filename [data-dz-name]').each(function (count, el) {
                var name = el.innerHTML;
                queue.forEach(function(file) {
                    if (file.name === name) {
                        newQueue.push(file);
                    }
                });
          });
          myDropzone.files = newQueue;
        }
    });

    myDropzone = new Dropzone('div#imageUpload', {
        addRemoveLinks: true,
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 100,
        maxFilesize: 12,
        paramName: 'image',
        clickable: true,
        method: 'POST',
        url: '{{route('saleProduct.store')}}',
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

                        
                        $('.name').text("")
                        $('.code').text("")
                        $('.category').text("")
                        $('.brand').text("")
                        $('.price').text("")
                        $.ajax({
                            type:'POST',
                            url:'{{route('saleProduct.store')}}',
                            data: $("#product-form").serialize(),
                            success:function(response) {
                                location.href = '{{route('saleProduct.index')}}';
                            },
                            error: function (error) {

                                if (error.responseJSON.errors.name) {
                                    $('.name').text(error.responseJSON.errors.name[0])
                                }
                                if (error.responseJSON.errors.code) {
                                    $('.code').text(error.responseJSON.errors.code[0])
                                }
                                if (error.responseJSON.errors.category_id) {
                                    $('.category').text(error.responseJSON.errors.category_id[0])
                                }
                                if (error.responseJSON.errors.brand_id) {
                                    $('.brand').text(error.responseJSON.errors.brand_id[0])
                                }
                                if (error.responseJSON.errors.price) {
                                    $('.price').text(error.responseJSON.errors.price[0])
                                }
                            }
                        });



                    }
            });

            this.on('sending', function (file, xhr, formData) {
                var data = $("#product-form").serializeArray();
                $.each(data, function (key, el) {
                    formData.append(el.name, el.value);
                });
            });
        },
        error: function (file, response) {
            if(response.errors.name) {
              $("#name-error").text(response.errors.name);
              this.removeAllFiles(true);
            }
            else if(response.errors.code) {
              $("#code-error").text(response.errors.code);
              this.removeAllFiles(true);
            }
            else {
              try {
                  var res = JSON.parse(response);
                  if (typeof res.message !== 'undefined' && !$modal.hasClass('in')) {
                      $("#success-icon").attr("class", "fas fa-thumbs-down");
                      $("#success-text").html(res.message);
                      $modal.modal("show");
                  } else {
                      if ($.type(response) === "string")
                          var message = response; //dropzone sends it's own error messages in string
                      else
                          var message = response.message;
                      file.previewElement.classList.add("dz-error");
                      _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                      _results = [];
                      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                          node = _ref[_i];
                          _results.push(node.textContent = message);
                      }
                      return _results;
                  }
              } catch (error) {
                  console.log(error);
              }
            }
        },
        successmultiple: function (file, response) {
            location.href = '{{route('saleProduct.index')}}';
        },
        completemultiple: function (file, response) {
            console.log(file, response, "completemultiple");
        },
        reset: function () {
            this.removeAllFiles(true);
        }
    });

    $('.dropzone .dz-message span').append("<i class='fa fa-upload' aria-hidden='true'></i>");
    $(document).ready(function () {
    var baseUrl = $('meta[name="base-url"]').attr('content');

  


});

 
 

</script>
 

 
@endsection