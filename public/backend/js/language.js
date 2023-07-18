"use strict";

$(document).ready(function () {
    get_translate_file();

});

function get_translate_file() {


    $('#translate_form').empty();
    var file_name = $('#file_name').val();
    var id = $('#id').val();
    var translate_file = $('.translate_file').val();
    $.post(`${translate_file}`, {
        _token: $('meta[name="csrf-token"]').attr('content'),
        file_name: file_name,
        id: id
    }, function (data) {
        $('#translate_form').html(data);
        let translate_form=$('#translate_form').html();
        // console.log(translate_form.length);
        
    });
}
