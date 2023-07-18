<script type="text/javascript">
    $("ul#sale").siblings('a').attr('aria-expanded','true');
    $("ul#sale").addClass("show");
    $("ul#sale #coupon-menu").addClass("active");

    var coupon_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#create-modal .expired_date").val($.datepicker.formatDate('yy-mm-dd', new Date()));
    $(".minimum-amount").hide();

    $("#create-modal select[name='type']").on("change", function() {
      if ($(this).val() == 'fixed') {
          $("#create-modal .minimum-amount").show();
          $("#create-modal .minimum-amount").prop('required',true);
          $("#create-modal .icon-text").text('$');
      }
      else {
          $("#create-modal .minimum-amount").hide();
          $("#create-modal .minimum-amount").prop('required',false);
          $("#create-modal .icon-text").text('%');
      }
    });

    $("#editModal select[name='type']").on("change", function() {
      alert('kire?');
      if ($(this).val() == 'fixed') {
          $("#editModal .minimum-amount").show();
          $("#editModal .minimum-amount").prop('required',true);
          $("#editModal .icon-text").text('$');
      }
      else {
          $("#editModal .minimum-amount").hide();
          $("#editModal .minimum-amount").prop('required',false);
          $("#editModal .icon-text").text('%');
      }
    });

    $(document).on("click", '#createModal .genbutton', function(){
      $.get('gencode', function(data){
        $("input[name='code']").val(data);
      });
    });

    $(document).on("click", '#editModal .genbutton', function(){
      $.get('gencode', function(data){
        $("#editModal input[name='code']").val(data);
      });
    });

    $('.edit-btn').on('click',function() {
        var url = ""
        var id = $(this).data('id').toString();
        url = url.concat(id).concat("/edit");
        $.get(url, function(data) {
            $("#editModal input[name='code']").val(data['code']);
            $("#editModal select[name='type']").val(data['type']);
            $("#editModal input[name='amount']").val(data['amount']);
            $("#editModal input[name='minimum_amount']").val(data['minimum_amount']);
            $("#editModal input[name='quantity']").val(data['quantity']);
            $("#editModal input[name='expired_date']").val(data['expired_date']);
            $("#editModal input[name='coupon_id']").val(data['id']);
            if(data['type'] == 'fixed'){
                $("#editModal .minimum-amount").show();
                $("#editModal .minimum-amount").prop('required',true);
                $("#editModal .icon-text").text('$');
            }
            $('.selectpicker').selectpicker('refresh');
        });

    });

    var expired_date = $('.expired_date');
    expired_date.datepicker({
     format: "yyyy-mm-dd",
     startDate: "<?php echo date('Y-m-d'); ?>",
     autoclose: true,
     todayHighlight: true
     });

function confirmDelete() {
    if (confirm("Are you sure want to delete?")) {
        return true;
    }
    return false;
}

</script>