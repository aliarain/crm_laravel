
var url = $('#url').val();
var _token = $('meta[name="csrf-token"]').attr('content');


function btnHold(){
    let duration = 1600,
        success = button => {
            //Success function
            $('.progress').hide();
            button.classList.add('success');
            checkIn($('#form_url').val());
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
var checkUrl;
var checkIn = (url) => {
    checkUrl = url;
    if (navigator?.geolocation) {
            navigator.geolocation.getCurrentPosition(attendanceStore, positionError, {timeout:10000});
      } else { 
         console.log("Geolocation is not supported by this browser.");
      }


     
}

function positionError(error) {
    Toast.fire({
        icon: 'error',
        title: error.message ?? 'Something went wrong!',
    })
    $('.progress').show();
    $('#button-hold').removeClass('success');
    

    attendanceStore();
}

function attendanceStore(position = null){
    var reason = $('#reason').val();

    $('#reason').val()
    if ($('#reason').length > 0 && (reason == '' || reason == null) ) {
        $('#reason').focus();
        $('#reason').css('border-color', 'red');
        $('.error_show_reason').html('Please enter reason');
        $('.progress').show();
        $('#button-hold').removeClass('success');
        return false;
    }
    $.ajax({
        type: 'GET',
        url: checkUrl,
        data: {
            latitude          : position?.coords?.latitude ?? '23.7909811' ,
            longitude         : position?.coords?.longitude ?? '90.4067015' ,
            remote_mode_in    : parseInt($('input[name="place_mode"]:checked').val() ?? 0) ,
            reason            : reason ?? '',
        },
        success: function (data) {
            if (data?.result) {
                Toast.fire({
                    icon: 'success',
                    title: data.message,
                    timer: 1500,
                })
                setTimeout(function () {
                    window.location.href = data?.data; 
                }, 1500)
            }else{
                Toast.fire({
                    icon: 'error',
                    title: response?.data?.message ?? 'Something went wrong!',
                })
                }
        },
        error: function (data) {
            if(data?.responseJSON?.message){
                Toast.fire({
                    icon: 'error',
                    title: data?.responseJSON?.message ?? 'Something went wrong.',
                })
                $('.progress').show();
                $('#button-hold').removeClass('success');
                // if (data?.responseJSON?.error) {                    
                //     setTimeout(function () {
                //         window.location.href = data?.responseJSON?.error; 
                //     }, 2000)
                // }
            }
        }
    });
}

textAreaValidate = (value,className) => {
    if (value == '' || value == null) {
        $('.'+className).html('Please enter reason');
        $('.'+className).css('color', 'red');
        return false;
    }else{
        $('.'+className).html('');
        return true;
    }
}



