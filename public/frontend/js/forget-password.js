$(document).ready(function () {
    var baseUrl = $('meta[name="base-url"]').attr('content');

    $(".forget-password").on("click", function (event) {
        event.preventDefault();
        let formData = $('#forget-password').serialize();
        $('.reset-password').text("");
        $.ajax({
            url: baseUrl + "/api/auth/forget-password-request",
            type: "POST",
            data: formData,
            success: function (response) {
                $('#number').val("")
                iziToast.success({
                    title: 'Success',
                    message: "Please check your phone for OTP credentials",
                    position: 'topRight'
                });
                setTimeout(() => {
                    window.location.replace(baseUrl + '/login');
                }, 1500)
            },
            error: function (error) {
                $('.reset-password').text('Please enter a valid phone number')
            }
        });
    });

});

$(document).on('input', '#number', function(){
    let number = $(this).val();
    let numberValidateReg = /^(?:\+88|88)?(01[3-9]\d{8})$/;

    if(!number.match(numberValidateReg)){
        console.log("problem ariesed");
        $(this).addClass("border-danger")
        $(".reset-password").text("Enter a valid number")
    }else{
        $(".reset-password").text("")
        $(this).removeClass("border-danger")
    }
    
})