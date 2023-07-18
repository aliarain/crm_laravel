"use strict";

function getSummary() {
    let month = $(".selected_month").val();
    $.ajax({
        url: $("#singleAttendanceSummaryReport").val(),
        type: "POST",
        data: { month: month },
        success: function (response) {
            $("#attendance_summary").html(response);
        },
    });
}

$(document).ready(function() {
    getSummary();
});
