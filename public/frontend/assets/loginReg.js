$(document).ready(function () {
    var baseUrl = $('meta[name="base-url"]').attr('content');
    let URL = $('#url').val();

    $(".driver_reg_btn").on("click", function (event) {
        event.preventDefault();
        let formData = $('#registerAsDriver').serialize();
        $('.driver_name').text("")
        $('.driver_email').text("")
        $('.driver_phone').text("")
        $('.driver_password').text("")
        $('.driver_confirm_password').text("")
        $.ajax({
            url: baseUrl + "/driver-register",
            type: "POST",
            data: formData,
            success: function (response) {
                iziToast.success({
                    title: 'Success',
                    message: "Registered successfully",
                    position: 'topRight'
                });
                $('#registerAsDriver').trigger('reset');
                $('.driver_reg_btn').removeAttr('type', true);
                $('.driver_reg_btn').empty().append('<div class="dots-container-reg"><div class="pulse-dot-white pulse-dot-1"></div><div class="pulse-dot-white pulse-dot-2"></div><div class="pulse-dot-white pulse-dot-3"> </div> </div>')
                setTimeout(() => {
                    window.location.replace(baseUrl + "/login");
                }, 1500)
            },
            error: function (error) {
                if (error.responseJSON.errors.email) {
                    $('.driver_email').text(error.responseJSON.errors.email[0])
                }
                if (error.responseJSON.errors.name) {
                    $('.driver_name').text(error.responseJSON.errors.name[0])
                }
                if (error.responseJSON.errors.phone) {
                    $('.driver_phone').text(error.responseJSON.errors.phone[0])
                }

                if (error.responseJSON.errors.password) {
                    $('.driver_password').text(error.responseJSON.errors.password[0])
                }
                if (error.responseJSON.errors.password_confirmation) {
                    $('.driver_confirm_password').text(error.responseJSON.errors.password_confirmation[0])
                }
            }
        });
    });


});