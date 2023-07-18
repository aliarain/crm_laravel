"use strict";

$(document).on('click', '.common-key', function () {
    var value = $(this).val();
    var value = value.split("_");
    if (value[1] == 'read') {
        if (!$(this).is(':checked')) {
            $(this).closest('tr').find('.common-key').prop('checked', false);
        }
    } else {
        if ($(this).is(':checked')) {
            $(this).closest('tr').find('.common-key').first().prop('checked', true);
        }

    }
});

$("#check_all").on("click",function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
})
