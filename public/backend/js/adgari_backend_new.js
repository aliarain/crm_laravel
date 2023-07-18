$(document).ready(() => {


    //get vehicle
    $('.vehicle_id').select2({
        placeholder: 'Choose Vehicle',
        ajax: {
            url: $('.vehicle_id').data('url'),
            dataType: 'json',
            type: 'POST',
            delay: 250,
            processResults: function (data) {

                return {
                    results: $.map(data, function (item) {

                        return {
                            text: item.vehicle_no + ' [ ' + item.user.name + ' ]',
                            id: item.id,
                        }
                    })
                }
            },
            cache: false
        }
    });


    //vehicle client
    $('.vehicle_clients').select2({
        placeholder: 'Choose Client',
        ajax: {
            url: $('.vehicle_clients').data('url'),
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
            cache: false
        }
    });
});

//get sticker data by id
function showSticker(url) {
    $('#stickerEditModal').trigger("reset");
    $('#sticker_img').attr('src');
    $('#cliend_id').empty();
    $.ajax({
        type: 'GET', //THIS NEEDS TO BE GET
        url: url,
        success: function (data) {
            $('.stickerEditModal').modal('show')
            $('#sticker_id').val(data.id)
            $('#sticker_name').val(data.name)
            $('#sticker_position').val(data.position)
            $('#sticker_status_id').val(data.status_id)
            $('#sticker_img').attr('src', data.img_path);
            $('#cliend_id').append(`<option value='${data.client_id}'>${data.client_name}</option>`)
        },
        error: function () {
            console.log(data);
        }
    });
}

//initially this section will be hide , when the client has been chosen then this section will be visible to user.
$('.get_sticker').hide();
//get sticker
$('.vehicle_clients').on('change', function () {
    let clientId = $(this).val();
    $('.sticker_id').val('');
    $('.get_sticker').show();

    //pass client id for getting client wise stickers
    let appUrl = $('.sticker_id').data('url');
    stickers(appUrl, clientId);
});

function stickers(url, clientId) {
    $('.sticker_id').select2({
        placeholder: 'Choose Sticker',
        ajax: {
            url: url + '/' + clientId,
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
            cache: false
        }
    });
}

   //Get Driver Users
   $('.users_feedback').select2({
    placeholder: 'Choose Users',
    ajax: {
        url: $('.users_feedback').data('url'),
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
        cache: false
    }
});
