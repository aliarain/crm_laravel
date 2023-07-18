<script type="text/javascript">
    $("ul#expense").siblings('a').attr('aria-expanded','true');
    $("ul#expense").addClass("show");
    $("ul#expense #exp-list-menu").addClass("active");

    var expense_id = [];

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    $('.open-EditexpenseDialog').on('click', function() {
        var url = "";
        var id = $(this).data('id').toString();
        url = url.concat(id).concat("/edit");
        $.get(url, function(data) {
            $('#editModal #reference').text(data['reference_no']);
            $("#editModal input[name='created_at']").val(data['date']);
            $("#editModal select[name='warehouse_id']").val(data['warehouse_id']);
            $("#editModal select[name='expense_category_id']").val(data['expense_category_id']);
            $("#editModal select[name='account_id']").val(data['account_id']);
            $("#editModal input[name='amount']").val(data['amount']);
            $("#editModal input[name='expense_id']").val(data['id']);
            $("#editModal textarea[name='note']").val(data['note']);
        });
    });
    
</script>