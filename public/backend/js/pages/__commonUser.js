"use strict";

var url = $('#url').val();
var _token = $('meta[name="csrf-token"]').attr('content');
function departmentUsers() {
    let department_id = $("#department").select2("val");
    $('#selected_department').val(department_id);
    $('#__user_id').select2({
        placeholder: 'Choose User',
        ajax: {
            url: url + '/dashboard/user/get-all-user-by-dep-des',
            data: {
                department_id: department_id,
                _token: _token,
            },
            type: 'POST',
            delay: 250,
            processResults: function(data) {
                let users = data.data.users;
                return {
                    results: $.map(users, function(item) {
                        return {
                            text: item.name,
                            id: item.id,
                        }
                    })
                }
            },
            cache: false
        }
    });
}
$('#custom_user').select2({
    placeholder: $('#select_custom_members').val(),
    placement: 'bottom',
    ajax: {
        url: $('#get_custom_user_url').val(),
        dataType: 'json',
        data: {
            _token: _token,
        },
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
