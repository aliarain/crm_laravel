"use strict";
var url = $('#url').val();
var _token = $('meta[name="csrf-token"]').attr('content');


function btnHold(){
    let duration = 1600,
        success = button => {
            //Success function
            $('.progress').hide();
            button.classList.add('success');
            takeBreak($('#form_url').val());
        };
    document.querySelectorAll('.button-hold').forEach(button => {
        button.style.setProperty('--duration', duration + 'ms');
        ['mousedown', 'touchstart', 'keypress'].forEach(e => {
            button.addEventListener(e, ev => {
                if (e != 'keypress' || (e == 'keypress' && ev.which == 32 && !button
                        .classList.contains('process'))) {
                    button.classList.add('process');
                    button.timeout = setTimeout(success, duration, button);
                }
            });
        });
        ['mouseup', 'mouseout', 'touchend', 'keyup'].forEach(e => {
            button.addEventListener(e, ev => {
                if (e != 'keyup' || (e == 'keyup' && ev.which == 32)) {
                    button.classList.remove('process');
                    clearTimeout(button.timeout);
                }
            }, false);
        });
    });

}
btnHold();

var takeBreak = (url) => {
    var data = '';
    data = {
        url
    };
    data['data'] = {
        reason : $('#reason').val(),
    }
    data["value"] = {          
        method: 'POST',
        _token: _token,
        load: 'table',
    };
    http_Request([data]).then(function(response){
        if (response.status == 200) {  
            // console.log(response?.data.data);                              
            Toast.fire({
                icon: 'success',
                title: response.data.message,
                timer: 1500,
            })
            $('.modal').empty();
            $('.modal').modal('toggle');
            $('.modal-backdrop').remove();  
            
             $('.break_back_button').html('');
             $('.break_back_button').html(`<button onclick="breakBack('${response?.data?.data[0]}', '${response?.data?.data[1]}')"
                    class="header_square_box">
                    <i class="las la-history"></i>
                </button>`);
           
        }else {
            $('.progress').show();
            $('#button-hold').removeClass('success');
            Toast.fire({
                icon: 'error',
                title: response?.data?.message ?? 'Something went wrong.',
            })
        }

    }).catch(function(error){
        $('.progress').show();
        $('#button-hold').removeClass('success');
        if (error?.response?.data?.errors) {
            $.each(error?.response?.data?.errors, function (key, value) {
                $('#' + key).removeClass('is-invalid');
                $('#' + key).next().empty();
                $('#' + key).addClass('is-invalid');
                $('#' + key).after('<div class="invalid-feedback">' + value[0] + '</div>');
            });           
        }else if(error?.response?.data?.message) {
            Toast.fire({
                icon: 'error',
                title: error?.response?.data?.message,
            })
        }
    });
}