"use strict";
var url = $("#url").val();
$(document).ready(function () {
  var baseUrl = $('meta[name="base-url"]').attr("content");
  //password reset
  $(".submit_btn").on("click", function (event) {
    event.preventDefault();
    let formData = $(".password_reset_form").serialize();
    $(".__email").text("");
    $(".__old_password").text("");
    $(".__password").text("");
    $(".__password_confirmation").text("");
    $.ajax({
      url: baseUrl + "/change-admin-password",
      type: "POST",
      data: formData,
      success: function (response) {
        iziToast.success({
          title: "Success",
          message: "Password change successfully !!",
          position: "topRight",
        });
        $(".password_reset_form").trigger("reset");
      },
      error: function (error) {
        if (!error.result) {
          iziToast.error({
            title: "Error",
            message: "Password did not matched",
            position: "topRight",
          });
        }
        if (error.responseJSON.error) {
          if (error.responseJSON.error.old_password) {
            $(".__old_password").text(error.responseJSON.error.old_password[0]);
          }

          if (error.responseJSON.error.password) {
            $(".__password").text(error.responseJSON.error.password[0]);
          }
          if (error.responseJSON.error.password_confirmation) {
            $(".__password_confirmation").text(
              error.responseJSON.error.password_confirmation[0]
            );
          }
          if (error.responseJSON.message) {
            $(".__code").text(error.responseJSON.message);
          }
        }
      },
    });
  });
});

$(document).ready(function () {
  $(".change-role").on("change", function (e) {
    e.preventDefault();
    var role_id = $(this).val();

    var formData = {
      role_id: role_id,
    };
    $.ajax({
      type: "POST",
      dataType: "html",
      data: formData,
      url: url + "/" + "hrm/roles/change-role",
      success: function (data) {
        $("#permissions-table").html(data);
      },
      error: function (data) {},
    });
  });

  $("#_country_id").select2({
    placeholder: $("#choose_country_id").val(),
    ajax: {
      url: url + "/" + "get-country",
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
});

$(document).on("click", ".common-key", function () {
  var value = $(this).val();
  var value = value.split("_");
  if (value[1] == "read") {
    if (!$(this).is(":checked")) {
      $(this).closest("tr").find(".common-key").prop("checked", false);
    }
  } else {
    if ($(this).is(":checked")) {
      $(this).closest("tr").find(".common-key").first().prop("checked", true);
    }
  }
});

function clearNotice() {
  var baseUrl = $('meta[name="base-url"]').attr("content");

  $.ajax({
    url: baseUrl + "/dashboard/user/notice/clear",
    type: "GET",

    success: function (response) {
      if (response.result) {
        $(".profile_notice_table_search_form").click();
      }
    },
    error: function (error) {},
  });
}

$(".outer-check-item").on("click", function () {
  let innerCheck = $(this).parents(".accordion-item").find(".inner-check-item");
  innerCheck.prop("checked", !innerCheck.prop("checked"));
});
$(".change-role").on("change", function () {
  if (
    $(".outer-check-item")
      .parents(".accordion-item")
      .find(".inner-check-item")
      .prop("checked") == true
  ) {
    $(".outer-check-item").prop(
      "checked",
      !$(".outer-check-item").prop("checked")
    );
  }
});
