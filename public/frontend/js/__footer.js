$(".newsletter_btn").on("click", function (event) {
    event.preventDefault();
    let newsLetterEmail = $('#newsletter_email').val()
    $.ajax({
        url: baseUrl + "/get-news-letter",
        type: "POST",
        data: {_token: _token, email: newsLetterEmail},
        success: function (response) {
            $('#newsletter_email').val('');
            iziToast.success({
                title: 'Success',
                message: "Subscribed successfully 😃",
                position: 'topRight'
            });
        },
        error: function (error) {
            if (error.responseJSON.errors.email) {
                iziToast.warning({
                    title: 'Warning',
                    message: error.responseJSON.errors.email[0],
                    position: 'topRight'
                });
            }
        }
    });
});
