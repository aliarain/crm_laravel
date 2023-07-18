$(document).ready(function () {
    var baseUrl = $('meta[name="base-url"]').attr('content');


    $(".change_password_btn").on('click', function (event) {
        event.preventDefault();
        let formData = $('.change_password_form').serialize();
        $('.current_password').text("")
        $('.new_password').text("")
        $('.password_confirmation').text("")
        $.ajax({
            url: baseUrl + "/hrm/leave/show",
            type: "POST",
            data: formData,
            success: function (response) {
                console.log(response)
                    // $('.current_password').text(response.message)
            }
        });
    });


});
