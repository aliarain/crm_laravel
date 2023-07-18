<script type="text/javascript">
    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #stock-count-menu").addClass("active");

    $("#category, #brand").hide();

    $('select[name=type]').on('change', function(){
        if($(this).val() == 'partial')
            $("#category, #brand").show(500);
        else
            $("#category, #brand").hide(500);
    });

    $(document).on('click', '.finalize', function(){
        $('input[name="stock_count_id"]').val($(this).data('id'));
        $('#finalizeModal').modal('show');
    });

    $(document).on('click', '.final-report', function(){
        var stock_count = $(this).data('stock_count');
        var htmltext = '<strong>{{_trans("file.Date")}}: </strong>'+stock_count[0]+'<br><strong>{{_trans("file.reference")}}: </strong>'+stock_count[1]+'<br><strong>{{_trans("file.Warehouse")}}: </strong>'+stock_count[2]+'<br><strong>{{_trans("file.Type")}}: </strong>'+stock_count[3];
        if(stock_count[4])
            htmltext += '<br><strong>{{_trans("file.category")}}: </strong>'+stock_count[4];
        if(stock_count[5])
            htmltext += '<br><strong>{{_trans("file.Brand")}}: </strong>'+stock_count[5];
        $.get('stockdif/' + stock_count[8], function(data){
            $(".stockdif-list tbody").remove();
            var name_code = data[0];
            var expected = data[1];
            var counted = data[2];
            var dif = data[3];
            var cost = data[4];
            var newBody = $(' <tbody class="tbody">');
            if(name_code){
                $('.stockdif-list').removeClass('d-none')
                $.each(name_code, function(index){
                    var newRow = $("<tr>");
                    var cols = '';
                    cols += '<td><strong>' + (index+1) + '</strong></td>';
                    cols += '<td>' + name_code[index] + '</td>';
                    cols += '<td>' + parseFloat(expected[index]).toFixed(2) + '</td>';
                    cols += '<td>' + parseFloat(counted[index]).toFixed(2) + '</td>';
                    cols += '<td>' + parseFloat(dif[index]).toFixed(2) + '</td>';
                    cols += '<td>' + parseFloat(cost[index]).toFixed(2) + '</td>';
                    newRow.append(cols);
                    newBody.append(newRow);
                });

                if( !parseInt(data[5]) ) {
                    htmlFooter = '<a class="btn btn-primary d-print-none d-none" href="'+stock_count[8]+'/qty_adjustment"><i class="dripicons-plus"></i> {{_trans("file.Add Adjustment")}}</a>';
                    $('#stock-count-footer').html(htmlFooter);
                }
            }
            else{
                $('.stockdif-list').addClass('d-none');
                $('#stock-count-footer').html('');
            }

            $("table.stockdif-list").append(newBody);
        });

        $('#stock-count-content').html(htmltext);
        $('#stock-count-details').modal('show');
    });

    $(document).on("click", "#print-btn", function(){
          var divToPrint=document.getElementById('stock-count-details');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<link rel="stylesheet" href="<?php echo asset('vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},10);
    });

    $('#stock-count-table').DataTable( {
        "order": [],
        'language': {
            'lengthMenu': '_MENU_ {{_trans("file.records per page")}}',
             "info":      '<small>{{_trans("file.Showing")}} _START_ - _END_ (_TOTAL_)</small>',
            "search":  '{{_trans("file.Search")}}',
            'paginate': {
                    'previous': '<i class="dripicons-chevron-left"></i>',
                    'next': '<i class="dripicons-chevron-right"></i>'
            }
        },
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 7, 8, 9]
            },
            {
                'render': function(data, type, row, meta){
                    if(type === 'display'){
                        data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                    }

                   return data;
                },
                'checkboxes': {
                   'selectRow': true,
                   'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                },
                'targets': [0]
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '<i title="export to pdf" class="fa fa-file-pdf-o"></i>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible',
                },
            },
            {
                extend: 'excel',
                text: '<i title="export to excel" class="dripicons-document-new"></i>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible',
                },
            },
            {
                extend: 'csv',
                text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible',
                },
            },
            {
                extend: 'print',
                text: '<i title="print" class="fa fa-print"></i>',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible',
                },
            },
            {
                extend: 'colvis',
                text: '<i title="column visibility" class="fa fa-eye"></i>',
                columns: ':gt(0)'
            },
        ],
    } );

</script>