"use strict"

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const Toast = Swal.mixin({
        toast: true,
        position: "top-right",
        iconColor: "white",
        animation: false,
        customClass: {
          popup: "colored-toast",
        },
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      });

    var baseUrl = $('meta[name="base-url"]').attr('content');
    let URL = $('#url').val();
    //password reset
    $(".submit_btn").on("click",function (event) {
        event.preventDefault();
        // let formData = $('.password_reset_form').serialize();
        let email = $('#email').val();
        $('.__email').text("")
        $('.submit_btn').text('');
        $.ajax({
            url: baseUrl + "/reset-password",
            type: "POST",
            data: {'email':email},
            beforeSend: function () {
                $('.submit_btn').text('Sending...');
            },
            success: function (response) {
                Toast.fire({
                    title: $("#send_reset_link_successfully").val(),
                    type: "success",
                    icon: "success",
                  });

                $('.password_reset_form').trigger('reset');
                $('.submit_btn').text('Redirecting..');
                setTimeout(() => {
                    window.location.replace(baseUrl + "/reset-password");
                }, 1500)
            },
            error: function (error) {
                // console.log(error)
                $('.submit_btn').text('Send Request');
                if (error.responseJSON.error) {
                    if (error.responseJSON.error.email) {
                        $('.__email').text(error.responseJSON.error.email[0])
                    }
                    if (error.responseJSON.message) {
                        $('.__email').text(error.responseJSON.message)
                    }
                }
            }
        });
    });


    //password reset
    $(".submit_btn_change").on("click",function (event) {
        event.preventDefault();
        let formData = $('.password_reset_form').serialize();
        $('.__email').text('')
        $('.__code').text('')
        $('.__password').text('')
        $('.__password_confirmation').text('')
        $.ajax({
            url: baseUrl + "/change-password",
            type: "POST",
            data: formData,
            success: function (response) {
                Toast.fire({
                    title: $("#password_change_successfully").val(),
                    type: "success",
                    icon: "success",
                  });

                $('.password_reset_form').trigger('reset');
                $('.submit_btn').text('Redirecting..');
                setTimeout(() => {
                    window.location.replace(baseUrl + "/sign-in");
                }, 1500)
            },
            error: function (error) {
                if (error.responseJSON.error) {
                    if (error.responseJSON.error.code) {
                        $('.__code').text(error.responseJSON.error.code[0])
                    }
                    if (error.responseJSON.error.email) {
                        $('.__email').text(error.responseJSON.error.email[0])
                    }
                    if (error.responseJSON.error.password) {
                        $('.__password').text(error.responseJSON.error.password[0])
                    }
                    if (error.responseJSON.error.password_confirmation) {
                        $('.__password_confirmation').text(error.responseJSON.error.password_confirmation[0])
                    }
                    if (error.responseJSON.message) {
                        $('.__code').text(error.responseJSON.message)
                    }
                }
            }
        });
    });
});
