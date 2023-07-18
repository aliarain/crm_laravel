<script>
    $("ul#product").siblings('a').attr('aria-expanded', 'true');
    $("ul#product").addClass("show");
    $("ul#product #product-list-menu").addClass("active");

    function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }

    var warehouse = [];
    var variant = [];
    var qty = [];
    var htmltext;
    var slidertext;
    var product_id = [];
    var role_id = '';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#select_all").on("change", function() {
        if ($(this).is(':checked')) {
            $("tbody input[type='checkbox']").prop('checked', true);
        } else {
            $("tbody input[type='checkbox']").prop('checked', false);
        }
    });

    function productDetails(product_id) {
        $.ajax({
            url: 'details-data/' + product_id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#product-details').modal('show');
                $('#image').attr('src', data['product'].image);
                $('#type').text(data['product'].type);
                $('#name').text(data['product'].name);
                $('#code').text(data['product'].code);
                $('#brand').text(data['product'].brand);
                $('#category').text(data['product'].category);
                $('#quantity').text(data['product'].qty);
                $('#unit').text(data['product'].unit);
                $('#cost').text(data['product'].cost);
                $('#price').text(data['product'].price);
                $('#tax').text(data['product'].tax);
                $('#tax_method').text(data['product'].tax_method);
                $('#alert_quantity').text(data['product'].alert_quantity);
                $('#product_details').text(data['product'].product_details);

                var warehouseTableBody = $('#warehouseTable tbody');
                warehouseTableBody.html('');
                var rowsHtml = '';
                if (data['warehouses'].length > 0) {
                    $.each(data['warehouses'], function(index, item) {
                        var newRow = $('<tr>');
                        newRow.append($('<td>').text(item['name']));
                        newRow.append($('<td>').text(item['batch_no']));
                        newRow.append($('<td>').text(item['qty']));
                        newRow.append($('<td>').text(item['imei_number']));
                        rowsHtml += newRow.prop('outerHTML');
                    });
                }
                warehouseTableBody.append(rowsHtml);

                var variantTableBody = $('#variantTable tbody');
                variantTableBody.html('');
                var rowsHtml = '';

                if (data['variants'].length > 0) {
                    $.each(data['variants'], function(index, item) {
                        var newRow = $('<tr>');
                        newRow.append($('<td>').text(item['variant']));
                        newRow.append($('<td>').text(item['item_code']));
                        newRow.append($('<td>').text(item['additional_cost']));
                        newRow.append($('<td>').text(item['additional_price']));
                        newRow.append($('<td>').text(item['qty']));
                        rowsHtml += newRow.prop('outerHTML');
                    });
                    variantTableBody.append(rowsHtml);
                }
                var variantTableBody = $('#warehouseVariantTable tbody');
                variantTableBody.html('');
                var rowsHtml = '';

                if (data['warehouse_variants'].length > 0) {
                    $.each(data['warehouse_variants'], function(index, item) {
                        var newRow = $('<tr>');
                        newRow.append($('<td>').text(item['name']));
                        newRow.append($('<td>').text(item['variant']));
                        newRow.append($('<td>').text(item['qty']));
                        rowsHtml += newRow.prop('outerHTML');
                    });
                    variantTableBody.append(rowsHtml);
                }
            }
        });
    }

    $("#print-btn").on("click", function() {
        var divToPrint = document.getElementById('product-details');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write(
            '<link rel="stylesheet" href="<?php echo asset('vendor/bootstrap/css/bootstrap.min.css'); ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">' +
            divToPrint.innerHTML + '</body>');
        newWin.document.close();
        setTimeout(function() {
            newWin.close();
        }, 10);
    });
</script>