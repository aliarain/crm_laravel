$(document).ready(function () {
    var baseUrl = $('meta[name="base-url"]').attr('content');

    $(".send-link").on('click', function (event) {
        event.preventDefault();
        let formData = {token: _token, phone: $("#phone_number").val()};
        $.ajax({
            url: baseUrl + "/send-app-link",
            type: "POST",
            data: formData,
            success: function (response) {
                $('#phone_number').val("")
                iziToast.success({
                    title: 'Success',
                    message: "Please check your phone for the link",
                    position: 'topRight'
                });
            },
            error: function (error) {
                iziToast.warning({
                    title: 'Warning',
                    message: "Please enter a valid phone number",
                    position: 'topRight'
                });
            }
        });
    });

    $(".leading_agency_btn").on("click",function (event) {
        event.preventDefault();
        let formData = $('#leading_agency_form').serialize();
        $('.legal_name').text("")
        $('.legal_phone').text("")
        $('.legal_email').text("")
        $('.legal_message').text("")
        $.ajax({
            url: baseUrl + "/leading-agency-info",
            type: "POST",
            data: formData,
            success: function (response) {
                console.log("response")
                console.log(response)
                $('#leading_agency_form').trigger("reset");
                iziToast.success({
                    title: 'Success',
                    message: "Information sent successfully",
                    position: 'topRight'
                });
            },
            error: function (error) {
                console.log(error);
                iziToast.error({
                    title: 'Error',
                    message: "Invalid Information",
                    position: 'topRight'
                });
                if (error.responseJSON.errors.name) {
                    $('.legal_name').text(error.responseJSON.errors.name[0])
                }
                if (error.responseJSON.errors.phone) {
                    $('.legal_phone').text(error.responseJSON.errors.phone[0])
                }
                if (error.responseJSON.errors.email) {
                    $('.legal_email').text(error.responseJSON.errors.email[0])
                }
                if (error.responseJSON.errors.message) {
                    $('.legal_message').text(error.responseJSON.errors.message[0])
                }
            }
        });
    });
});
