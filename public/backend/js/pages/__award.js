"use strict";

//Get Users
$('#members').select2({
    placeholder: $('#select_members').val(),
    placement: 'bottom',
    width: "100%",
    ajax: {
        url: $('#get_user_url').val(),
        dataType: 'json',
        type: 'POST',
        delay: 250,
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id,
                    }
                })
            }
        },
        cache: true
    }
});






// image tooltip


$('[data-toggle="tooltip"]').tooltip({
    animated: 'fade',
    placement: 'bottom',
    html: true
});

