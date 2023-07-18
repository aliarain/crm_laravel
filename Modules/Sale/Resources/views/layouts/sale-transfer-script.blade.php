<script type="text/javascript">
    $("ul#transfer").siblings('a').attr('aria-expanded','true');
    $("ul#transfer").addClass("show");
    $("ul#transfer #transfer-list-menu").addClass("active");

    $(".daterangepicker-field").daterangepicker({
      callback: function(startDate, endDate, period){
        var starting_date = startDate.format('YYYY-MM-DD');
        var ending_date = endDate.format('YYYY-MM-DD');
        var title = starting_date + ' To ' + ending_date;
        $(this).val(title);
        $('input[name="starting_date"]').val(starting_date);
        $('input[name="ending_date"]').val(ending_date);
      }
    });

    var all_permission = <?php echo json_encode(@$all_permission) ?>;
    var transfer_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }

     $(document).on("click", "tr.transfer-link td:not(:first-child, :last-child)", function() {
        var transfers = $(this).parent().data('transfer');
        transferDetails(transfers);
    });

    $(".view").on("click", function() {
        var transfers = $(this).data('id').toString();
        transferDetails(transfers);
    });

    $("#print-btn").on("click", function(){
        var divContents = document.getElementById("transfer-details").innerHTML;
        var a = window.open('');
        a.document.write('<html>');
        a.document.write('<body>');
        a.document.write('<style>body{font-family: sans-serif;line-height: 1.15;-webkit-text-size-adjust: 100%;}.d-print-none{display:none}.text-center{text-align:center}.row{width:100%;margin-right: -15px;margin-left: -15px;}.col-md-12{width:100%;display:block;padding: 5px 15px;}.col-md-6{width: 50%;float:left;padding: 5px 15px;}table{width:100%;margin-top:30px;}th{text-aligh:left}td{padding:10px}table,th,td{border: 1px solid black; border-collapse: collapse;}</style><style>@media print {.modal-dialog { max-width: 1000px;} }</style>');
        a.document.write(divContents);
        a.document.write('</body></html>');
        a.document.close();
        setTimeout(function(){a.close();},10);
        a.print();
    });

    var starting_date = $("input[name=starting_date]").val();
    var ending_date = $("input[name=ending_date]").val();
    var from_warehouse_id = $("#from_warehouse_id").val();
    var to_warehouse_id = $("#to_warehouse_id").val();


    function datatable_sum(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();

            $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    function transferDetails(transfer) {
        $.get('product_transfer/' + transfer, function(data) {
            $(".product-transfer-list tbody").remove();
            var name_code = data['product_transfer'][0];
            var qty = data['product_transfer'][1];
            var unit_code = data['product_transfer'][2];
            var tax = data['product_transfer'][3];
            var tax_rate = data['product_transfer'][4];
            var subtotal = data['product_transfer'][5];
            var batch_no = data['product_transfer'][6];
            var newBody = $(' <tbody class="tbody">');
            $.each(name_code, function(index) {
                var newRow = $("<tr>");
                var cols = '';
                cols += '<td><strong>' + (index+1) + '</strong></td>';
                cols += '<td>' + name_code[index] + '</td>';
                cols += '<td>' + batch_no[index] + '</td>';
                cols += '<td>' + qty[index] + ' ' + unit_code[index] + '</td>';
                cols += '<td>' + (subtotal[index] / qty[index]) + '</td>';
                cols += '<td>' + tax[index] + '(' + tax_rate[index] + '%)' + '</td>';
                cols += '<td>' + subtotal[index] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=5><strong>{{_trans("file.Total")}}:</strong></td>';
            cols += '<td>' + data['transfer'].total_tax + '</td>';
            cols += '<td>' +data['transfer'].total_cost + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{_trans("file.Shipping Cost")}}:</strong></td>';
            cols += '<td>' + data['transfer'].shipping_cost + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{_trans("file.grand total")}}:</strong></td>';
            cols += '<td>' + data['transfer'].grand_total + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

             $("table.product-transfer-list").append(newBody);
            //  date 
            $('#transfer_date').text(data['transfer'].date);
            $('#reference').text(data['transfer'].reference_no);
            $('#from').text(data['transfer'].from);
            $('#from_phone').text(data['transfer'].from_phone);
            $('#from_address').text(data['transfer'].from_address);
            $('#to').text(data['transfer'].to);
            $('#to_phone').text(data['transfer'].to_phone);
            $('#to_address').text(data['transfer'].to_address);
            $('#note').text(data['transfer'].note);
            $('#created_by').text(data['transfer'].created_by);
        });

        $('#transfer-details').modal('show');
    }

    if(all_permission.indexOf("transfers-delete") == -1)
        $('.buttons-delete').addClass('d-none');

</script>