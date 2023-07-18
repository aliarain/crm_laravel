<script type="text/javascript">
    $("ul#setting").siblings('a').attr('aria-expanded','true');
    $("ul#setting").addClass("show");
    $("ul#setting #warehouse-menu").addClass("active");

    var warehouse_id = [];

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.open-EditWarehouseDialog').on('click', function() {
        var url = ""
        var id = $(this).data('id').toString();
        url = url.concat(id).concat("/edit");

        $.get(url, function(data) {
            $("#editModal input[name='name']").val(data['name']);
            $("#editModal input[name='phone']").val(data['phone']);
            $("#editModal input[name='email']").val(data['email']);
            $("#editModal textarea[name='address']").val(data['address']);
            $("#editModal input[name='warehouse_id']").val(data['id']);

        });
    });
    
</script>