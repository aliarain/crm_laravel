"use strict";

$(function () {
  const start = moment().subtract(29, "days");
  const end = moment();

  function cb(start, end) {
    __date_range = {
      from: start.format("YYYY-MM-DD"),
      to: end.format("YYYY-MM-DD"),
    }
  }

  $("#daterang").daterangepicker(
    {
      applyButtonClasses: "apply-btn",
      cancelButtonClasses: "cancel-btn",
      locale: {
        cancelLabel: "Cancel",
        applyLabel: "Set Data",
        format: "YYYY-MM-DD",
      },
      startDate: moment().subtract(29, "days"),
      endDate: moment(),
      ranges: {
        Today: [moment(), moment()],
        Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
        "Last 7 Days": [moment().subtract(6, "days"), moment()],
        "Last 30 Days": [moment().subtract(29, "days"), moment()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [
          moment().subtract(1, "month").startOf("month"),
          moment().subtract(1, "month").endOf("month"),
        ],
      },
    },
    cb
  );

  cb(start, end);
});
