<script type="text/javascript">
    $("ul#sale").siblings('a').attr('aria-expanded','true');
    $("ul#sale").addClass("show");
    $("ul#sale #gift-card-menu").addClass("active");

    var gift_card_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#expired_date").val($.datepicker.formatDate('yy-mm-dd', new Date()));
    $(".user_list").hide();
    $("select[name='user_id']").prop('required',false);

    var expired_date = $('#expired_date');
    expired_date.datepicker({
     format: "yyyy-mm-dd",
     startDate: "<?php echo date('Y-m-d'); ?>",
     autoclose: true,
     todayHighlight: true
     });

    var expired_date = $('#expired_date_edit');
    expired_date.datepicker({
     format: "yyyy-mm-dd",
     startDate: "<?php echo date('Y-m-d'); ?>",
     autoclose: true,
     todayHighlight: true
     });

    $(document).on("click", ".view-btn", function() {
        $("#balance").text($(this).data('amount') - $(this).data('expense'));
        $(".valid-date").text($(this).data('expired_date'));
        $(".client").text($(this).data('client'));
        $(".card-number").text($(this).data('card_no'));
    });

    $(document).on("change", "#user", function() {
        if ($(this).is(':checked')) {
            $(".user_list").show();
            $(".customer_list").hide();
            $("select[name='user_id']").prop('required',true);
            $("select[name='customer_id']").prop('required',false);
        }
        else {
            $(".user_list").hide();
            $(".customer_list").show();
            $("select[name='user_id']").prop('required',false);
            $("select[name='customer_id']").prop('required',true);
        }
    });

    $(document).on("change", "#user_edit", function() {
        if ($(this).is(':checked')) {
            $(".user_list_edit").show();
            $(".customer_list_edit").hide();
            $("select[name='user_id_edit']").prop('required',true);
            $("select[name='customer_id_edit']").prop('required',false);
        }
        else {
            $(".user_list_edit").hide();
            $(".customer_list_edit").show();
            $("select[name='user_id_edit']").prop('required',false);
            $("select[name='customer_id_edit']").prop('required',true);
        }
    });

    $(document).on("click", "#print-btn", function(){
          var divToPrint=document.getElementById('viewModal');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<link rel="stylesheet" href="<?php echo asset('vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},10);
    });

    $(document).on("click", '#createModal .genbutton', function(){
      $.get('gencode', function(data){
        $("input[name='card_no']").val(data);
      });
    });

    $(document).on("click", '#editModal .genbutton', function(){
      $.get('gencode', function(data){
        $("#editModal input[name='card_no_edit']").val(data);
      });
    });

    $('.open-EditgiftcardDialog').on('click', function() {
        var url = ""
        var id = $(this).data('id').toString();
        url = url.concat(id).concat("/edit");
        $.get(url, function(data) {
            $("input[name='gift_card_id']").val(data['id']);
            $("input[name='card_no_edit']").val(data['card_no']);
            $("input[name='amount_edit']").val(data['amount']);
            if(data['user_id']){
                $("#user_edit").prop('checked', true);
                $("select[name='user_id_edit']").val(data['user_id']);
                $("select[name='customer_id_edit']").val('');
                $("select[name='user_id_edit']").prop('required',true);
                $("select[name='customer_id_edit']").prop('required',false);
                $(".user_list_edit").show();
                $(".customer_list_edit").hide();
            }
            else{
                $("#user_edit").prop('checked', false);
                $("select[name='customer_id_edit']").val(data['customer_id']);
                $("select[name='user_id_edit']").val('');
                $("select[name='user_id_edit']").prop('required',false);
                $("select[name='customer_id_edit']").prop('required',true);
                $(".user_list_edit").hide();
                $(".customer_list_edit").show();
            }

            $("input[name='expired_date_edit']").val(data['expired_date']);
            $('.selectpicker').selectpicker('refresh');
        });
    });

        $(document).on('click', '.recharge', function() {
            var id = $(this).data('id').toString();
            $("#rechargeModal input[name='gift_card_id']").val(id);

            var rowindex = $(this).closest('tr').index();
            var card_no = $('#gift_card-table tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
            $('#card-no').text(card_no);
        });
    function datatable_sum(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();
            $( dt_selector.column( 4 ).footer() ).html(dt_selector.cells( rows, 4, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 3 ).footer() ).html(dt_selector.cells( rows, 3, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 4 ).footer() ).html(dt_selector.cells( rows, 4, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 3 ).footer() ).html(dt_selector.cells( rows, 3, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

</script>