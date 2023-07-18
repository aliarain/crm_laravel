$(document).ready(function () {
    var baseUrl = $('meta[name="base-url"]').attr('content');


    $(".contact_btn").on('click', function (event) {
        event.preventDefault();
        let formData = $('.contact_form').serialize();
        console.log(formData)
        $('.contact_name').text("")
        $('.contact_phone').text("")
        $('.contact_email').text("")
        $('.contact_service_type').text("")
        $('.contact_message').text("")
        $.ajax({
            url: baseUrl + "/get-contact-info",
            type: "POST",
            data: formData,
            success: function (response) {
                $('.contact_form').trigger("reset");
                iziToast.success({
                    title: 'Success',
                    message: "Information sent successfully",
                    position: 'topRight'
                });
            },
            error: function (error) {

                if (error.responseJSON.errors.name) {
                    $('.contact_name').text(error.responseJSON.errors.name[0])
                }
                if (error.responseJSON.errors.phone) {
                    $('.contact_phone').text(error.responseJSON.errors.phone[0])
                }
                if (error.responseJSON.errors.email) {
                    $('.contact_email').text(error.responseJSON.errors.email[0])
                }
                if (error.responseJSON.errors.service_type) {
                    $('.contact_service_type').text(error.responseJSON.errors.service_type[0])
                }
                if (error.responseJSON.errors.message) {
                    $('.contact_message').text(error.responseJSON.errors.message[0])
                }
            }
        });
    });


});
