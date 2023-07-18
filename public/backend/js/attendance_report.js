$(document).ready(function () {
    // base url from meta tag
    var baseUrl = $('meta[name="base-url"]').attr("content");
    //tokens from base meta tag
    var _token = $('meta[name="csrf-token"]').attr("content");

    function ajaxTable(data, $search_false = null) {
        $(`.${data["table_id"]}`).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: data["url"],
                data: data["value"],
            },
            //  bLengthChange: true,
            bDestroy: true,
            language: {
                paginate: {
                    next: "<i class='ti-arrow-right'></i>",
                    previous: "<i class='ti-arrow-left'></i>",
                },
                processing:
                    '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                sEmptyTable: `<div
                                    class="no-data-found-wrapper text-center p-primary">
                                    <img src="${baseUrl}/public/images/no_data.svg"
                                        alt="" class="mb-primary">
                                    <p class="mb-0 text-center">No data found !</p> 
                                </div>`,
            },
            dom: "Blfrtip",
            lengthMenu: [
                [10, 25, 100, -1],
                ["10 rows", "25 rows", "100 rows", "Show all"],
            ],
            buttons: [
                {
                    extend: "copyHtml5",
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: "Copy",
                    exportOptions: {
                        columns: ":visible",
                    },
                },
                {
                    extend: "excelHtml5",
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: "Excel",
                    exportOptions: {
                        columns: ":visible",
                        order: "applied",
                    },
                },
                {
                    extend: "csvHtml5",
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: "CSV",
                    exportOptions: {
                        columns: ":visible",
                    },
                },
                {
                    extend: "pdfHtml5",
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: "PDF",
                    orientation: "landscape",
                    pageSize: "A5",
                    alignment: "center",
                    header: true,
                    margin: 20,
                },
                "colvis",
            ],
            responsive: true,
            pageLength: 10,
            deferRender: true,
            fixedColumns: true,
            columns: data["column"],
            order: [0, "asc"],
            searching: $search_false == null ? true : false,
        });
    }

    //attendance data table start
    function attendanceDatatable() {
        let data = [];
        let url = $("#attendance_report_data_url").val();
        data["url"] = url;

        var startEndDate = $("#daterange").val();
        var user_id = "";
        var department = "";

       
        if ($("#__user_id").length != 0) {
            user_id = $("#__user_id").val();
        }
        if ($("#department").length != 0) {
            department = $("#department").val();
        }

        data["value"] = {
            date: startEndDate?startEndDate:null,
            department: department,
            user_id: user_id,
            _token: _token,
        };

        console.log(data["value"]);

        data["column"] = [
            { data: "date", name: "date" },
            { data: "name", name: "name" },
            { data: "department", name: "department" },
            { data: "totalBreak", name: "totalBreak" },
            { data: "breakDuration", name: "breakDuration" },
            { data: "checkin", name: "checkin" },
            { data: "checkout", name: "checkout" },
            { data: "hours", name: "hours" },
            { data: "overtime", name: "overtime" },
            {
                data: "action",
                name: "action",
                orderable: true,
                searchable: true,
            },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "attendance_report_table";
        ajaxTable(data);
    }

    $(".attendance_report_table").length > 0 && attendanceDatatable();

    $(".attendance_table_form").on("click", () => {
        attendanceDatatable();
        dataTableUpdate();
    });
    //attendance table end

    //single attendance data table start
    function singleAttendanceDatatable() {
        let data = [];
        let url = $("#single_attendance_report_data_url").val();
        data["url"] = url;

        var selected_month = $(".selected_month").val();

        data["value"] = {
            month: selected_month,
            _token: _token,
        };

        data["column"] = [
            { data: "date", name: "date" },
            { data: "checkin", name: "checkin" },
            { data: "checkout", name: "checkout" },
            { data: "totalBreak", name: "totalBreak" },
            { data: "breakDuration", name: "breakDuration" },
            { data: "hours", name: "hours" },
            {
                data: "overtime",
                name: "overtime",
                orderable: true,
                searchable: true,
            },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "single_attendance_report_table";
        ajaxTable(data);
    }

    $(".single_attendance_report_table").length > 0 &&
        singleAttendanceDatatable();
    $(".selected_month").on("change", () => singleAttendanceDatatable());
    //single attendance table end

    //break data table start
    function breakDatatable() {
        let data = [];
        let url = $("#break_report_data_url").val();
        data["url"] = url;

        var from_date = $("#start_date").val();
        var to_date = $("#end_date").val();
        var department = $("#department").val();
        var user_id = $("#__user_id").val();

        data["value"] = {
            user_id: user_id,
            from_date: from_date,
            to_date: to_date,
            department: department,
            _token: _token,
        };

        data["column"] = [
            { data: "date", name: "date" },
            { data: "name", name: "name" },
            { data: "department", name: "department" },
            { data: "break_time", name: "break_time" },
            { data: "back_time", name: "break_time" },
            { data: "duration", name: "duration" },
            {
                data: "reason",
                name: "reason",
                orderable: true,
                searchable: true,
            },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "break_report_table";
        ajaxTable(data);
    }

    $(".break_report_table").length > 0 && breakDatatable();
    $(".break_table_form").on("click", () => {
        breakDatatable();
        dataTableUpdate();
    });
    //attendance table end
});
