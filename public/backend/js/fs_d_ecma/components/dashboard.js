"use strict";
import {
  appointmentSummary,
  attendanceChart,
  payrollChart,
  projectSummary,
  revenueChart,
  taskSummary,
} from "./chartService.js";

// load dashboard
window.dashboardAdmin = (e) => {
  $(".c-dropdown-menu .dropdown-item").removeClass("active");
  let selected_dashboard = "";
  if (e == "Company Dashboard") {
    $(".company_option").addClass("active");
    selected_dashboard = $("#company_dashboard_used_for_translation").val();
  } else if (e == "Superadmin Dashboard") {
    $(".super_dashboard").addClass("active"); 

    selected_dashboard = $("#super_dashboard_used_for_translation").val();
  } else {
    $(".profile_option").addClass("active");
    selected_dashboard = $("#my_dashboard_used_for_translation").val(); 
  }
  $("#__selected_dashboard").html(selected_dashboard);
  $(".c-dropdown-menu").removeClass("show");
  let dashboardType = e;
  var data = {
    url: $("#profileWiseDashboard").val(),
    method: "POST",
    data: {
      _token: _token,
      userID: $("#fire_base_authenticate").val(),
      dashboardType: dashboardType,
    },
  };
  custom_http_request(data)
    .then(function (response) {
      if (response.status == 200) {
        $("#__MyProfileDashboardView").html(response?.data?.dashboard);
        if (response?.data?.dashboardType == "Company Dashboard") {
          revenueChart(response?.data?.expense?.original?.data);
          attendanceChart(response?.data?.attendance_summary);
          // userChart(response?.data?.department_staff);
          payrollChart(response?.data?.payroll);
          appointmentSummary(
            response?.data?.appointment?.original?.data,
            "appointment_schedule"
          );
          appointmentSummary(
            response?.data?.meeting?.original?.data,
            "meeting_schedule"
          );
          projectSummary(response?.data?.project);
          taskSummary(response?.data?.task);
          
        } else if (response?.data?.dashboardType == "Dashboard") {
          appointmentSummary(
            response?.data?.appointment?.original?.data,
            "appointment_schedule"
          );
          appointmentSummary(
            response?.data?.meeting?.original?.data,
            "meeting_schedule"
          );
          projectSummary(response?.data?.project);
          taskSummary(response?.data?.task);
          
        }
      } else {
        Toast.fire({
          icon: "error",
          title: response?.data?.message ?? "Something went wrong.",
        });
      }
    
    })

    .catch(function (error) {
      if (error?.response?.data?.message) {
        Toast.fire({
          icon: "error",
          title: error?.response?.data?.message,
        });
      }
    });

    $(".table_scrollbar_active").mCustomScrollbar({
      axis:"yx", // vertical and horizontal scrollbar
    });
    
};

$(function () {
    let user_slug = $("#user_slug").val();
    if (user_slug == "superadmin") {
        $('.super_dashboard').addClass('active');
        dashboardAdmin("My Dashboard");
        $('#__selected_dashboard').html('My Dashboard');
    } else if (user_slug == "admin") {

        $('.company_option').addClass('active');
        dashboardAdmin("Company Dashboard");
        $('#__selected_dashboard').html('Company Dashboard');
    } else {

        $('.profile_option').addClass('active');
        dashboardAdmin("My Dashboard");
        $('#__selected_dashboard').html('My Dashboard');
    }
});
 