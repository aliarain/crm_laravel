"use strict";


 
$(function () {
    let user_slug = $("#user_slug").val();
    if (user_slug == "superadmin") {
        loadDashboard("My Dashboard");
        $('#__selected_dashboard').html('My Dashboard');
    } else if (user_slug == "admin") {
        loadDashboard("Company Dashboard");
        $('#__selected_dashboard').html('Company Dashboard');
    } else {
        loadDashboard("My Dashboard");
        $('#__selected_dashboard').html('My Dashboard');
    }

});
 

function loadDashboard(e) {
    console.log("EE");
    console.log(e);
    $("#__selected_dashboard").html(e);
    let dashboardType = e;
    let url = $("#profileWiseDashboard").val();
    let userID = $("#userID").val();
    // ajax
    $.ajax({
        url: url,
        type: "POST",
        data: {
            userID: userID,
            dashboardType: dashboardType,
        },
        success: function (data) {
            if (data.status == "success") {
                $("#__MyProfileDashboardView").html(data.dashboard);
                console.log("user_slug");
                if(data?.expense?.original?.data) {
                    expenseChart(data.expense.original.data);
                }else{
                    $("#lineChart").html("");
                }
                if (data?.attendance_summary) {
                    let series = [];
                    let labels = [];
                    for (let key in data.attendance_summary) {
                        if(data.attendance_summary.total_employee ){
                            data.attendance_summary.absent = data.attendance_summary.total_employee - data.attendance_summary.present;
                            delete data.attendance_summary.total_employee;
                        }
                        series.push(data.attendance_summary[key]);
                        labels.push(key);
                    }
                    drawPieChart(series, labels);
                }else{
                    $("#employeeActivityChart2").html("");
                }
            }
        },
    });
    //add active class
    $(".profile_option").addClass("active");
    $(".company_option").removeClass("active");
    $("#profile_statistic").show();
    $("#company_statistic").hide();
}

 