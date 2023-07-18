$(document).ready(function () {
  $(".screen").css("display", "block");
  var baseUrl = $('meta[name="base-url"]').attr("content");

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

//swal toaster
const Toast = Swal.mixin({
  toast: true,
  position: "top-right",
  animation: false,
  iconColor: "white",
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

  $(".__first_step").show();
  $(".__second_step").hide();
  $(".__third_step").hide();
  $(".__forth_step").hide();
  $(".__forth_second_step").hide();
  $(".__fifth_step").hide();
  $(".__sixth_step").hide();
  $(".__seventh_step").hide();

  if ($("#phone").val() == 1) {
    $(".__first_step").hide();
    $(".__second_step").show();
    $(".__third_step").hide();
    $(".__forth_step").hide();
    $(".__forth_second_step").hide();
    $(".__fifth_step").hide();
    $(".__sixth_step").hide();
    $(".__seventh_step").hide();
  }

  if ($("#name").val() == 1) {
    $(".__first_step").hide();
    $(".__second_step").hide();
    $(".__third_step").show();
    $(".__forth_step").hide();
    $(".__forth_second_step").hide();
    $(".__fifth_step").hide();
    $(".__sixth_step").hide();
    $(".__seventh_step").hide();
  }

  if ($("#email").val() == 1) {
    $(".__first_step").hide();
    $(".__second_hide").hide();
    $(".__third_step").hide();
    $(".__forth_step").show();
    $(".__forth_second_step").hide();
    $(".__fifth_step").hide();
    $(".__sixth_step").hide();
    $(".__seventh_step").hide();
  }

  if ($("#company_name").val() == 1) {
    $(".__first_step").hide();
    $(".__second_hide").hide();
    $(".__third_step").hide();
    $(".__forth_step").hide();
    $(".__forth_second_step").show();
    $(".__fifth_step").hide();
    $(".__sixth_step").hide();
    $(".__seventh_step").hide();
  }

  if ($("#total_employee").val() == 1) {
    $(".__first_step").hide();
    $(".__second_hide").hide();
    $(".__third_step").hide();
    $(".__forth_step").hide();
    $(".__forth_second_step").hide();
    $(".__fifth_step").show();
    $(".__sixth_step").hide();
    $(".__seventh_step").hide();
  }

  if ($("#business_type").val() == 1) {
    $(".__first_step").hide();
    $(".__second_hide").hide();
    $(".__third_step").hide();
    $(".__forth_step").hide();
    $(".__forth_second_step").hide();
    $(".__fifth_step").hide();
    $(".__sixth_step").show();
    $(".__seventh_step").hide();
  }

  if ($("#trade_licence_number").val() == 1) {
    $(".__first_step").show();
    $(".__second_step").show();
    $(".__third_step").show();
    $(".__forth_step").show();
    $(".__forth_second_step").show();
    $(".__fifth_step").show();
    $(".__sixth_step").show();
    $(".__seventh_step").show();
  }

  //first step
  $(".__first_btn").on("click", () => {
    let value = $("#__phone").val();
    $(".__phone").text("");
    $.ajax({
      url: baseUrl + "/add-phone",
      type: "POST",
      data: { phone: value },
      success: function (response) {
        $(".__first_step").hide();
        $(".__second_step").show();
      },
      error: function (error) {
        if (error.responseJSON.error) {
          if (error.responseJSON.error.phone) {
            $(".__phone").text(error.responseJSON.error.phone[0]);
          }
        }
      },
    });
  });

  //second step
  $(".__second_btn").on("click", () => {
    let value = $("#__name").val();
    $(".__name").text("");
    $.ajax({
      url: baseUrl + "/add-name",
      type: "POST",
      data: { name: value },
      beforeSend: function () {},
      success: function (response) {
        $(".__second_step").hide();
        $(".__third_step").show();
      },
      error: function (error) {
        if (error.responseJSON.error) {
          if (error.responseJSON.error.name) {
            $(".__name").text(error.responseJSON.error.name[0]);
          }
        }
      },
    });
  });

  //third step
  $(".__third_btn").on("click", () => {
    let value = $("#__email").val();
    $(".__email").text("");
    $.ajax({
      url: baseUrl + "/add-email",
      type: "POST",
      data: { email: value },
      success: function (response) {
        $(".__third_step").hide();
        $(".__forth_step").show();
      },
      error: function (error) {
        if (error.responseJSON.error) {
          if (error.responseJSON.error.email) {
            $(".__email").text(error.responseJSON.error.email[0]);
          }
        }
      },
    });
  });

  //forth step
  $(".__forth_btn").on("click", () => {
    let value = $("#__company_name").val();
    $(".__company_name").text("");
    $.ajax({
      url: baseUrl + "/add-company-name",
      type: "POST",
      data: { company_name: value },
      success: function (response) {
        $(".__forth_step").hide();
        $(".__forth_second_step").show();
      },
      error: function (error) {
        if (error.responseJSON.error) {
          if (error.responseJSON.error.company_name) {
            $(".__company_name").text(error.responseJSON.error.company_name[0]);
          }
        }
      },
    });
  });
  //forth step
  $(".__forth_second_btn").on("click", () => {
    let value = $("#__total_employee").val();
    $(".__total_employee").text("");
    $.ajax({
      url: baseUrl + "/add-total-employee",
      type: "POST",
      data: { total_employee: value },
      success: function (response) {
        $(".__forth_second_step").hide();
        $(".__fifth_step").show();
      },
      error: function (error) {
        if (error.responseJSON.error) {
          if (error.responseJSON.error.total_employee) {
            $(".__total_employee").text(
              error.responseJSON.error.total_employee[0]
            );
          }
        }
      },
    });
  });

  //fifth step
  $(".__fifth_btn").on("click", () => {
    let value = $("#__business_type").val();
    $(".__business_type").text("");
    $.ajax({
      url: baseUrl + "/add-business-type",
      type: "POST",
      data: { business_type: value },
      success: function (response) {
        $(".__fifth_step").hide();
        $(".__sixth_step").show();
      },
      error: function (error) {
        if (error.responseJSON.error) {
          if (error.responseJSON.error.business_type) {
            $(".__business_type").text(
              error.responseJSON.error.business_type[0]
            );
          }
        }
      },
    });
  });

  //sixth step
  $(".__sixth_btn").on("click", () => {
    let value = $("#__trade_licence_number").val();
    $(".__trade_licence_number").text("");
    $(".screen__content").addClass("bg-white text-center");
    $(".cus__field").removeClass("login__field");
    $(".text-left").addClass("mx-auto");

    $.ajax({
      url: baseUrl + "/add-trade-licence",
      type: "POST",
      data: { trade_licence_number: value },
      success: function (response) {
        $(".__trade_licence_number").text("");
        $(".__previous_btn").hide();
        $(".__first_step").show();
        $(".__second_step").show();
        $(".__third_step").show();
        $(".__forth_step").show();
        $(".__forth_second_step").show();
        $(".__fifth_step").show();
        $(".__sixth_step").show();
        $(".__seventh_step").show();
      },
      error: function (error) {
        if (error.responseJSON.error) {
          if (error.responseJSON.error.trade_licence_number) {
            $(".__trade_licence_number").text(
              error.responseJSON.error.trade_licence_number[0]
            );
          }
        }
      },
    });
  });

  //seventh step
  $(".__seventh_btn").on("click", () => {
    $(".screen__content").addClass("bg-white text-center");
    $(".cus__field").removeClass("login__field");
    $(".text-left").addClass("mx-auto");

    let formData = $(".__user_signup_form").serialize();
    $(".__phone").text("");
    $(".__email").text("");
    $(".__name").text("");
    $(".__company_name").text("");
    $(".__business_type").text("");
    $(".__trade_licence_number").text("");
    $(".__total_employee").text("");
    $(".__password").text("");
    $(".__password_confirmation").text("");
    $(".__country_id").text("");
    $.ajax({
      url: baseUrl + "/add-user-finally",
      type: "POST",
      data: formData,
      success: function (response) {
        $("#__phone").val("");
        $("#__email").val("");
        $("#__name").val("");
        $("#__company_name").val("");
        $("#__business_type").val("");
        $("#__trade_licence_number").val("");
        $("#__total_employee").val("");
        $("#__password").val("");
        $("#__password_confirmation").val("");
        $(".__country_id").text("");
        iziToast.success({
          title: "Success",
          message: "Registered successfully !!",
          position: "topRight",
        });
        $(".__trade_licence_number").text("");
        $(".__previous_btn").hide();
        $(".__first_step").show();
        $(".__second_step").show();
        $(".__third_step").show();
        $(".__forth_step").show();
        $(".__fifth_step").show();
        $(".__sixth_step").show();
        $(".__seventh_step").show();
        setTimeout(() => {
          window.location.replace(baseUrl + "/sign-in");
        }, 1500);
      },
      error: function (error) {
        if (error.responseJSON.error) {
          if (error.responseJSON.error.phone) {
            $(".__phone").text(error.responseJSON.error.phone[0]);
          }
          if (error.responseJSON.error.email) {
            $(".__email").text(error.responseJSON.error.email[0]);
          }
          if (error.responseJSON.error.name) {
            $(".__name").text(error.responseJSON.error.name[0]);
          }
          if (error.responseJSON.error.company_name) {
            $(".__company_name").text(error.responseJSON.error.company_name[0]);
          }
          if (error.responseJSON.error.business_type) {
            $(".__business_type").text(
              error.responseJSON.error.business_type[0]
            );
          }
          if (error.responseJSON.error.trade_licence_number) {
            $(".__trade_licence_number").text(
              error.responseJSON.error.trade_licence_number[0]
            );
          }
          if (error.responseJSON.error.total_employee) {
            $(".__total_employee").text(
              error.responseJSON.error.total_employee[0]
            );
          }
          if (error.responseJSON.error.password) {
            $(".__password").text(error.responseJSON.error.password[0]);
          }
          if (error.responseJSON.error.password_confirmation) {
            $(".__password_confirmation").text(
              error.responseJSON.error.password_confirmation[0]
            );
          }
          if (error.responseJSON.error.country) {
            $(".__country_id").text(error.responseJSON.error.country[0]);
          }
        }
      },
    });
  });

  $("#_country_id").select2({
    placeholder: $("#choose_country_id").val(),
    ajax: {
      url: $("#addGetCountry").val(),
      dataType: "json",
      type: "POST",
      delay: 250,
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              text: item.name,
              id: item.id,
            };
          }),
        };
      },
      cache: false,
    },
  });

  // for login
  var baseUrl = $('meta[name="base-url"]').attr("content");
  let URL = $("#url").val();
  //user login

  $(".__demo_login_btn").on("dblclick", function (event) {
    event.preventDefault();
  });
  $(".__login_btn").on("dblclick", function (event) {
    event.preventDefault();
  });

  $(".__login_btn").on("click", function (event) {
    event.preventDefault();
    let formData = $(".__login_form").serialize();

    $(".__phone").text("");
    $(".__password").text("");
    $.ajax({
      url: baseUrl + "/admin-login",
      type: "POST",
      data: formData,
      success: function (response) {
       console.log(response);
      //  return false;
        if (response === "0" || response == 0) {
          // Toast.fire({
          //   title: $("#something_wrong").val(),
          //   type: "error",
          //   icon: "error",
          // });
          Toast.fire({
            title: $("#login_success_fully").val(),
            type: "success",
            icon: "success",
          });

          $(".__phone").text("");
          $(".__password").text("");
          $(".__login_form").trigger("reset");
          $(".__login_btn").text("Redirecting..");
          setTimeout(() => {
            window.location.replace(baseUrl + "/dashboard");
          }, 1500);
        } else {
          window.location.replace(baseUrl);
        }
      },
      error: function (error) {
         console.log(error);
        if (error?.responseJSON?.error?.email) {
          $(".__phone").text(error.responseJSON.error.email[0]);
        }
        if (error?.responseJSON?.error?.password) {
          $(".__password").text(error.responseJSON.error.password[0]);
        }
      },
    });
  });

  $(".__demo_login_btn").on("click", function () {
    event.preventDefault();
    let formData = $(this).closest("form").serialize();
    // console.log(formData);
    // console.log(baseUrl + "/admin-login");
    $.ajax({
      url: baseUrl + "/admin-login",
      type: "POST",
      data: formData,
      success: function (response) {

        // console.log(response);
        if (response == "0" || response == 0) {
          console.log("lol");
          // iziToast.success({
          //   title: "Success",
          //   message: "Login successfully",
          //   position: "topRight",
          // });
          Toast.fire({
            title: $("#login_success_fully").val(),
            type: "success",
            icon: "success",
          });

          $(".__phone").text("");
          $(".__password").text("");
          $(".__login_form").trigger("reset");
          $(".__login_btn").text("Redirecting..");
          setTimeout(() => {
            window.location.replace(baseUrl + "/dashboard");
          }, 1500);
        } else {
          window.location.replace(baseUrl);
        }
      },
      error: function (error) {
        if (error?.responseJSON?.error?.email) {
          $(".__phone").text(error.responseJSON.error.email[0]);
        }
        if (error?.responseJSON?.error?.password) {
          $(".__password").text(error.responseJSON.error.password[0]);
        }
      },
    });
  });
  var display;
  if($(".cus__field").length > 0) {
    display = $(".cus__field .__seventh_step").prop("style").display;
  }
  if (display === "") {
    $(".screen__content").addClass("bg-white text-center");
    $(".cus__field").removeClass("login__field");
    $(".text-left").addClass("mx-auto");
  }
});
