<script type="text/javascript">
    $("ul#setting").siblings('a').attr('aria-expanded', 'true');
    $("ul#setting").addClass("show");
    $("ul#setting #unit-menu").addClass("active");

    var unit_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')) ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".operator").hide();
    $(".operation_value").hide();

    function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }
    $('.open-EditUnitDialog').on('click', function() {
        var url = ""
        var id = $(this).data('id').toString();
        url = url.concat(id).concat("/edit");

        $.get(url, function(data) {
            $("input[name='unit_code']").val(data['unit_code']);
            $("input[name='unit_name']").val(data['unit_name']);
            $("input[name='operator']").val(data['operator']);
            $("input[name='operation_value']").val(data['operation_value']);
            $("input[name='unit_id']").val(data['id']);
            $("#base_unit_edit").val(data['base_unit']);
            if (data['base_unit'] != null) {
                $(".operator").show();
                $(".operation_value").show();
            } else {
                $(".operator").hide();
                $(".operation_value").hide();
            }
            $('.selectpicker').selectpicker('refresh');

        });
    });

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

    $("#export").on("click", function(e) {
        e.preventDefault();
        var unit = [];
        $(':checkbox:checked').each(function(i) {
            unit[i] = $(this).val();
        });
        $.ajax({
            type: 'POST',
            url: '/exportunit',
            data: {

                unitArray: unit
            },
            success: function(data) {
                alert('Exported to CSV file successfully! Click Ok to download file');
                window.location.href = data;
            }
        });
    });

    $('.open-CreateUnitDialog').on('click', function() {
        $(".operator").hide();
        $(".operation_value").hide();

    });

    $('#base_unit_create').on('change', function() {
        if ($(this).val()) {
            $("#createModal .operator").show();
            $("#createModal .operation_value").show();
        } else {
            $("#createModal .operator").hide();
            $("#createModal .operation_value").hide();
        }
    });

    $('#base_unit_edit').on('change', function() {
        if ($(this).val()) {
            $("#editModal .operator").show();
            $("#editModal .operation_value").show();
        } else {
            $("#editModal .operator").hide();
            $("#editModal .operation_value").hide();
        }
    });
</script>