$(document).ready(function () {
    var baseUrl = $('meta[name="base-url"]').attr('content');


    $(".change_password_btn").on('click', function (event) {
        event.preventDefault();
        let formData = $('.change_password_form').serialize();
        $('.current_password').text("")
        $('.new_password').text("")
        $('.password_confirmation').text("")
        $.ajax({
            url: baseUrl + "/api/auth/password-update",
            type: "POST",
            data: formData,
            success: function (response) {
                 if (response.success != true) {
                    $('.current_password').text(response.message)
                } else {
                    $('.change_password_form').trigger("reset");
                    iziToast.success({
                        title: 'Success',
                        message: "Password updated 🔑",
                        position: 'topRight'
                    });
                }

            },
            error: function (error) {
                if (error.responseJSON.error.current_password) {
                    $('.current_password').text(error.responseJSON.error.current_password[0])
                }
                if (error.responseJSON.error.password) {
                    $('.new_password').text(error.responseJSON.error.password[0])
                }
                if (error.responseJSON.error.password_confirmation) {
                    $('.password_confirmation').text(error.responseJSON.error.password_confirmation[0])
                }
            }
        });
    });


});
