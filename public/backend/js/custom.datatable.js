$(document).ready(function () {
    // base url from meta tag
    var baseUrl = $('meta[name="base-url"]').attr("content");
    //tokens from base meta tag
    var _token = $('meta[name="csrf-token"]').attr("content");

    //start data table for all users

    // end data table for all categories

    function ajaxTable(data, $search_false = null) {

        console.log(data);
        $(`.${data["table_id"]}`).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: data["url"],
                data: data["value"] ? data["value"] : {},
                dataType: 'json',
                // dataSrc: "data"   //Added for checking purpose
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
                                    class="no-data-found-wrapper text-center ">
                                    <img src="${baseUrl}/public/images/no_data.svg"
                                        alt="" class="mb-primary">
                                    <p class="mb-0 text-center">No data found !</p> 
                                </div>`,
            },
            dom: "Blfrtip",
            lengthMenu: [
                [15, 25, 100, -1],
                ["15 rows", "25 rows", "100 rows", "Show all"],
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
            pageLength: 15,
            deferRender: true,
            fixedColumns: true,
            columns: data["column"],
            cache: true,
            order: [0, "desc"],
            searching: $search_false == null ? true : false,
        });
    }

    // default data table for all

    $(".buttons-colvis").on("click", () => {
        $(".dt-button-collection").css("left", "");
    });
    $(".dataTables_filter > label").append(
        '<i class="bi bi-search _search_icon"></i>'
    );

    //user data table start
    function usersDatatable() {
        let data = [];
        let url = $("#user_data_url").val();
        data["url"] = url;

        var from_date = $("#start").val();
        var to_date = $("#end_date").val();
        var user_id = $("#staff_user_id").val();
        var userTypeId = $("#userTypeId").val();
        var userStatus = $("#userStatus").val();

        data["value"] = {
            from: from_date ? from_date : null,
            to: to_date ? to_date : null,
            user_id: user_id ? user_id : null,
            userTypeId: userTypeId ? userTypeId : null,
            userStatus: userStatus ? userStatus : null,
            _token: _token,
        };

        data["column"] = [
            { data: "name", name: "name" },
            { data: "avatar", name: "avatar" },
            { data: "email", name: "email" },
            { data: "phone", name: "phone" },
            { data: "designation", name: "designation" },
            { data: "department", name: "department" },
            { data: "role", name: "role" },
            { data: "shift", name: "shift" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "users_table";
        ajaxTable(data);
    }

    $(".users_table").length > 0 && usersDatatable();
    $(".user_table_search_form").on("click", () => {
        usersDatatable();
        $(".buttons-colvis").html(
            '<i class="fas fa-columns" style="color:#27a580"></i>'
        );
        $(".buttons-colvis").css(
            "background-color",
            "rgba(60, 210, 164,.5) !important"
        );
    });

    //leave type data table start
    function leaveDatatable() {
        let data = [];
        let url = $("#leave_type_data_url").val();
        data["url"] = url;

        var from_date = $("#start").val();
        var to_date = $("#end_date").val();
        var userTypeId = $("#userTypeId").val();

        data["value"] = {
            from: from_date ? from_date : null,
            to: to_date ? to_date : null,
            userTypeId: userTypeId ? userTypeId : null,
            _token: _token,
        };

        data["column"] = [
            { data: "name", name: "name" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "leave_type_table";
        ajaxTable(data);
    }

    $(".leave_type_table").length > 0 && leaveDatatable();
    $(".leave_type_table_search_form").on("click", () => {
        leaveDatatable();
        dataTableUpdate();
    });
    //leave type data table end
    //Payment method datatable start
    function paymentMethodsDatatable() {

        let data = [];
        let url = $("#payment_methods_datatable_url").val();
        data["url"] = url;

        data["value"] = {
            _token: _token,
        };

        data["column"] = [
            { data: "name", name: "name" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "payment_methods_datatable_class";
        ajaxTable(data);
    }
    //Payment method datatable end

    //assign leave data table start
    function assignLeaveDatatable() {
        let data = [];
        let url = $("#assign_leave_data_url").val();
        data["url"] = url;

        var department_id = $("#department_id").val();

        data["value"] = {
            department_id: department_id ? department_id : null,
            _token: _token,
        };

        data["column"] = [
            { data: "department", name: "department" },
            { data: "type", name: "type" },
            { data: "days", name: "days" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "assign_leave_table";
        ajaxTable(data);
    }

    $(".assign_leave_table").length > 0 && assignLeaveDatatable();
    $(".assign_leave_table_search_form").on("click", () => {
        assignLeaveDatatable();
        dataTableUpdate();
    });
    //assign leave data table start

    //leave request data table start
    function requestLeaveDatatable() {
        let data = [];
        let url = $("#leave_request_data_url").val();
        data["url"] = url;

        var daterange = $("#daterange").val();
        var short_by = $("#short_by").val();
        var department_id = $("#department_id").val();
        var user_id = $("#user_id").val();

        data["value"] = {
            daterange: daterange ? daterange : null,
            short_by: short_by ? short_by : null,
            department_id: department_id ? department_id : null,
            user_id: user_id ? user_id : null,
            _token: _token,
        };

        data["column"] = [
            { data: "name", name: "name" },
            { data: "type", name: "type" },
            { data: "date", name: "date" },
            { data: "days", name: "days" },
            { data: "available_days", name: "available_days" },
            { data: "substitute", name: "substitute" },
            { data: "manager_approved", name: "manager_approved" },
            { data: "hr_approved", name: "hr_approved" },
            { data: "file", name: "file" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "leave_request_table";
        ajaxTable(data);
    }

    $(".leave_request_table").length > 0 && requestLeaveDatatable();
    $(".leave_request_table_search_form").on("click", () => {
        requestLeaveDatatable();
        dataTableUpdate();
    });
    //leave request data table start

    //duty designation table start
    function designationDatatable() {
        let data = [];
        let url = $("#designation_data_url").val();
        data["url"] = url;

        var from_date = $("#start").val();
        var to_date = $("#end_date").val();
        var shortBy = $("#short_by").val();

        data["value"] = {
            from: from_date ? from_date : null,
            to: to_date ? to_date : null,
            short_by: shortBy ? shortBy : null,
            _token: _token,
        };

        data["column"] = [
            { data: "title", name: "title" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "designation_table";
        ajaxTable(data);
    }

    $(".designation_table").length > 0 && designationDatatable();
    $(".designation_table_form").on("click", () => {
        designationDatatable();
        dataTableUpdate();
    });
    //duty designation table end


    //duty shift table start
    function shiftDatatable() {
        let data = [];
        let url = $("#shift_data_url").val();
        data["url"] = url;

        var from_date = $("#start").val();
        var to_date = $("#end_date").val();
        var shortBy = $("#short_by").val();

        data["value"] = {
            from: from_date ? from_date : null,
            to: to_date ? to_date : null,
            short_by: shortBy ? shortBy : null,
            _token: _token,
        };

        data["column"] = [
            { data: "name", name: "name" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "shift_table";
        ajaxTable(data);
    }

    $(".shift_table").length > 0 && shiftDatatable();
    $(".shift_table_form").on("click", () => {
        shiftDatatable();
        dataTableUpdate();
    });
    //duty shift table end


    //duty department table start
    function departmentDatatable() {
        let data = [];
        let url = $("#department_data_url").val();
        data["url"] = url;

        var from_date = $("#start").val();
        var to_date = $("#end_date").val();
        var shortBy = $("#short_by").val();

        data["value"] = {
            from: from_date ? from_date : null,
            to: to_date ? to_date : null,
            short_by: shortBy ? shortBy : null,
            _token: _token,
        };

        data["column"] = [
            { data: "title", name: "title" },
            { data: "employees", name: "employees" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "department_table";
        ajaxTable(data);
    }

    $(".department_table").length > 0 && departmentDatatable();
    $(".department_table_form").on("click", () => {
        departmentDatatable();
        dataTableUpdate();
    });
    //duty designation table end

    //roles table start
    function rolesDatatable() {
        let data = [];
        let url = $("#roles_data_url").val();
        data["url"] = url;

        var from_date = $("#start").val();
        var to_date = $("#end_date").val();
        var shortBy = $("#short_by").val();

        data["value"] = {
            from: from_date ? from_date : null,
            to: to_date ? to_date : null,
            short_by: shortBy ? shortBy : null,
            _token: _token,
        };

        data["column"] = [
            { data: "name", name: "name" },
            { data: "permissions", name: "permissions" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "roles_table";
        ajaxTable(data);
    }

    $(".roles_table").length > 0 && rolesDatatable();
    $(".roles_table_form").on("click", () => {
        rolesDatatable();
        dataTableUpdate();
    });

    //benefits table start
    function benefitsTable() {
        let data = [];
        let url = $("#payroll_items_data_url").val();
        data["url"] = url;

        data["value"] = {
            _token: _token,
        };

        data["column"] = [
            { data: "name", name: "name" },
            { data: "type", name: "type" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "payroll_items_table";
        ajaxTable(data);
    }
    // payroll setup table start




    $(".payroll_items_table").length > 0 && benefitsTable();
    $("#benefits_table_reload").on("click", () => {
        benefitsTable();
        dataTableUpdate();
    });
    //duty designation table end

    //client table start
    function clientDatatable() {
        let data = [];
        let url = $("#client_data_url").val();
        data["url"] = url;
        var daterange = $('#daterange').val();
        // var from_date = $('#start').val();
        // var to_date = $('#end_date').val();

        data["value"] = {
            daterange: daterange ? daterange : null,
            // 'from': from_date,
            // 'to': to_date,
            _token: _token,
        };
        data["column"] = [
            { data: "name", name: "name" },
            { data: "email", name: "email" },
            { data: "phone", name: "phone" },
            { data: "website", name: "website" },
            { data: "file", name: "file" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "client_table";
        ajaxTable(data);
    }

    $(".client_table").length > 0 && clientDatatable();
    $(".client_table_form").on("click", () => clientDatatable());

    //company table start
    function companyDatatable() {
        let data = [];
        let url = $("#company_data_url").val();
        data["url"] = url;
        var daterange = null;
        // var from_date = $('#start').val();
        // var to_date = $('#end_date').val();

        data["value"] = {
            daterange: daterange ? daterange : null,
            // 'from': from_date,
            // 'to': to_date,
            _token: _token,
        };

        data["column"] = [
            { data: "company_name", name: "company_name" },
            { data: "name", name: "name" },
            { data: "email", name: "email" },
            { data: "phone", name: "phone" },
            { data: "total_employee", name: "total_employee" },
            { data: "business_type", name: "business_type" },
            { data: "trade_licence_number", name: "trade_licence_number" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "company_table";
        ajaxTable(data);
    }

    $(".company_table").length > 0 && companyDatatable();
    $(".company_table_form").on("click", () => companyDatatable());
    //company table end

    //ip config table start
    function ipConfigDatatable() {
        let data = [];
        let url = $("#ip_list_data_url").val();
        data["url"] = url;

        data["column"] = [
            { data: "location", name: "location" },
            { data: "ip_address", name: "ip_address" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "ip_table";
        ajaxTable(data);
    }

    $(".ip_table").length > 0 && ipConfigDatatable();
    $(".ip_table_form").on("click", () => ipConfigDatatable());
    //ip config table end

    //expense data table start
    function expenseDatatable() {
        let data = [];
        let startEndDate = $("#daterange").val();
        let category_ids = $("#category_id").val();
        let user_ids = $("#employee_id").val();
        let url = $("#expense_list_data_url").val();
        data["url"] = url;
        data["value"] = {
            date: startEndDate ? startEndDate : null,
            category_ids: category_ids ? category_ids : null,
            user_ids: user_ids ? user_ids : null,
            _token: _token,
        };
        data["column"] = [
            { data: "date", name: "date" },
            { data: "employee_name", name: "employee_name" },
            { data: "amount", name: "amount" },
            { data: "file", name: "file" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "expense_table";
        ajaxTable(data);
    }

    $(".expense_table").length > 0 && expenseDatatable();
    $(".expense_table_form").on("click", () => {
        expenseDatatable();
        dataTableUpdate();
    });
    //expense data table end

    //expense data table start
    function expenseClaimDatatable() {
        let data = [];
        let startEndDate = $("#daterange").val();
        let user_ids = $("#employee_id").val();
        let url = $("#expense_claim_list_data_url").val();

        data["url"] = url;
        data["value"] = {
            date: startEndDate ? startEndDate : null,
            user_ids: user_ids ? user_ids : null,
            _token: _token,
        };
        data["column"] = [
            { data: "date", name: "date" },
            { data: "employee_name", name: "employee_name" },
            { data: "remarks", name: "remarks" },
            { data: "amount", name: "amount" },
            { data: "file", name: "file" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "expense_claim_table";
        ajaxTable(data);
    }

    $(".expense_claim_table").length > 0 && expenseClaimDatatable();
    $(".expense_claim_table_form").on("click", () => {
        expenseClaimDatatable();
        dataTableUpdate();
    });
    //expense data table end

    //duty schedule data table
    function dutyScheduleDataTable() {
        let data = [];
        let url = $("#duty_schedule_data_url").val();
        data["url"] = url;

        var from_date = $("#start").val();
        var shift = $("#shift").val();

        data["value"] = {
            from: from_date ? from_date : null,
            shift: shift ? shift : null,
            _token: _token,
        };

        data["column"] = [
            { data: "department", name: "department" },
            { data: "start_time", name: "start_time" },
            { data: "end_time", name: "end_time" },
            { data: "hour", name: "hour" },
            { data: "consider_time", name: "consider_time" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "duty_schedule_table";
        ajaxTable(data);
    }

    $(".duty_schedule_table").length > 0 && dutyScheduleDataTable();
    $(".duty_schedule_form").on("click", () => {
        dutyScheduleDataTable();
        dataTableUpdate();
    });

    //coneent  data table
    function contentDataTable() {
        let data = [];
        let url = $("#content_data_url").val();
        data["url"] = url;

        var from_date = $("#start").val();

        data["value"] = {
            from: from_date ? from_date : null,
            _token: _token,
        };

        data["column"] = [
            { data: "title", name: "title" },
            { data: "slug", name: "slug" },
            // {data: 'status', name: 'status'},
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "content_table";
        ajaxTable(data);
    }

    $(".content_table").length > 0 && contentDataTable();
    $(".content_table_form").on("click", () => contentDataTable());

    //contact  data table
    function contactDataTable() {
        let data = [];
        let url = $("#contact_data_url").val();
        data["url"] = url;

        var from_date = $("#start").val();

        data["value"] = {
            from: from_date ? from_date : null,
            _token: _token,
        };

        data["column"] = [
            { data: "name", name: "name" },
            { data: "email", name: "email" },
            { data: "phone", name: "phone" },
            { data: "contact_for", name: "contact_for" },
            { data: "message", name: "message" },
            { data: "contact_status", name: "contact_status" },
            // {data: 'action', name: 'action', orderable: true, searchable: true},
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "contact_table";
        ajaxTable(data);
    }

    $(".contact_table").length > 0 && contactDataTable();
    $(".contact_table_form").on("click", () => contactDataTable());

    //support ticket  data table
    function supportTicketsDataTable() {
        let data = [];
        let url = $("#support_ticket_data_url").val();
        let user_id = $("#user_id").val();
        let date = $("#daterange").val();
        data["url"] = url;

        data["value"] = {
            date: date ? date : null,
            user_id: user_id ? user_id : null,
            _token: _token,
        };

        data["column"] = [
            { data: "date", name: "date" },
            { data: "code", name: "code" },
            { data: "employee_name", name: "employee_name" },
            { data: "subject", name: "subject" },
            { data: "type", name: "type" },
            { data: "priority", name: "priority" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "support_ticket_table";
        ajaxTable(data);
    }

    $(".support_ticket_table").length > 0 && supportTicketsDataTable();
    $(".support_ticket_table_form").on('click', () => supportTicketsDataTable())

    // notce data table


    function languageDatatable() {
        let data = [];
        let url = $("#language_data_url").val();
        data["url"] = url;


        data["value"] = {
            _token: _token,
        };
        // 'subject', 'date', 'department',  'description', 'file','action'

        data["column"] = [
            { data: "name", name: "name" },
            { data: "native", name: "native" },
            { data: "code", name: "code" },
            { data: "rtl", name: "rtl" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "language_datatable";
        ajaxTable(data);
    }

    $(".language_datatable").length > 0 && languageDatatable();

    function noticeDataTable() {
        let data = [];
        let url = $("#notice_data_url").val();
        let user_id = $("#user_id").val();
        let company_id = $("#company_id").val();
        data["url"] = url;

        var from_date = $("#start").val();

        data["value"] = {

            from: from_date ? from_date : null,
            user_id: user_id ? user_id : null,
            company_id: company_id ? company_id : null,
            _token: _token,
        };
        // 'subject', 'date', 'department',  'description', 'file','action'

        data["column"] = [
            { data: "date", name: "date" },
            { data: "subject", name: "subject" },
            { data: "department", name: "department" },
            { data: "description", name: "description" },
            { data: "file", name: "file" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "notice_table";
        ajaxTable(data);
    }

    $(".notice_table").length > 0 && noticeDataTable();

    $(".notice_table_form").on("click", () => {
        noticeDataTable();
        dataTableUpdate();
    });


    //Profile leave request data table start
    function profileLequestLeaveDatatable() {
        let data = [];
        let url = $("#profile_leave_request_data_url").val();
        data["url"] = url;

        var daterange = $('input[name="daterange"]').val();

        var short_by = $("#short_by").val();
        var user_id = $("#user_id").val();

        data["value"] = {
            daterange: daterange ? daterange : null,
            short_by: short_by ? short_by : null,
            user_id: user_id ? user_id : null,
            _token: _token,
        };

        data["column"] = [
            // {data: "name", name: "name"},
            { data: "type", name: "type" },
            { data: "days", name: "days" },
            { data: "team_leader", name: "team_leader" },
            { data: "file", name: "file" },
            { data: "status", name: "status" },
            // {data: 'action', name: 'action', orderable: true, searchable: true}
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "profile_leave_request_table";
        ajaxTable(data);
    }

    $(".profile_leave_request_table").length > 0 &&
        profileLequestLeaveDatatable();
    $(".profile_leave_request_table_search_form").on("click", () =>
        profileLequestLeaveDatatable()
    );
    //Profile leave request data table end


    //Profile appointment data table start
    function profileAppointmentDatatable() {
        let data = [];
        let url = $("#profile_appointment_data_url").val();
        data["url"] = url;

        var daterange = $('input[name="daterange"]').val();
        var user_id = $("#user_id").val();
        data["value"] = {
            daterange: daterange ? daterange : null,
            user_id: user_id ? user_id : null,
            _token: _token,
        };

        data["column"] = [
            { data: "title", name: "title" },
            { data: "appoinment_with", name: "appoinment_with" },
            { data: "date", name: "date" },
            { data: "start_at", name: "start_at" },
            { data: "end_at", name: "end_at" },
            { data: "location", name: "location" },
            { data: "file", name: "file" },
            { data: "status", name: "status" },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "profile_appointment_table";
        ajaxTable(data);
    }

    $(".profile_appointment_table").length > 0 && profileAppointmentDatatable();
    $(".profile_appointment_table_search_form").on("click", () => profileAppointmentDatatable());
    //Profile appointment data table end


    //Visit  data table start
    function visitDatatable() {
        let data = [];
        let url = $("#visit_report_table_url").val();
        data["url"] = url;

        var daterange = $('input[name="daterange"]').val();
        var status = $("#status").val();
        var department = $("#department").val();
        var __user_id = $("#__user_id").val();
        let employee_select_msg = $("#employee_select_msg");
        if (department != null && __user_id == "") {
            employee_select_msg.removeClass("d-none");
            return false;

        } else {
            employee_select_msg.addClass("d-none");
        }
        data["value"] = {
            daterange: daterange ? daterange : null,
            status: status ? status : null,
            // department: department?department:null,
            user_id: __user_id ? __user_id : null,
            _token: _token,
        };

        data["column"] = [
            { data: "employee_name", name: "employee_name" },
            { data: "date", name: "date" },
            { data: "title", name: "title" },
            { data: "description", name: "description" },
            { data: "cancel_note", name: "cancel_note" },
            { data: "file", name: "file" },
            { data: "status", name: "status" },
            { data: "action", name: "action" },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "visit_report_table";
        ajaxTable(data);
    }

    $(".visit_report_table").length > 0 && visitDatatable();
    $(".visit_table_form").on("click", () => visitDatatable());
    //Visit data table end


    //Profile notice data table start
    function profileNoticeDatatable() {
        let data = [];
        let url = $("#profile_notice_data_url").val();
        data["url"] = url;

        var daterange = $('input[name="daterange"]').val();
        var department_id = $("#department_id").val();
        data["value"] = {
            daterange: daterange ? daterange : null,
            department_id: department_id ? department_id : null,
            _token: _token,
        };

        data["column"] = [
            { data: "date", name: "date" },
            { data: "department", name: "department" },
            { data: "subject", name: "subject" },
            { data: "description", name: "description" },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "profile_notice_table";
        ajaxTable(data);
    }

    $(".profile_notice_table").length > 0 && profileNoticeDatatable();
    $(".profile_notice_table_search_form").on("click", () =>
        profileNoticeDatatable()
    );
    //Profile notice data table end

    //Profile Phonebook data table start
    function profilePhonebookDatatable() {
        let data = [];
        let url = $("#profile_Phonebook_data_url").val();
        data["url"] = url;

        // var daterange = $('input[name="daterange"]').val();
        var department_id = $("#department_id").val();
        data["value"] = {
            // daterange: daterange?daterange:null,
            department_id: department_id ? department_id : null,
            _token: _token,
        };

        data["column"] = [
            { data: "name", name: "name" },
            { data: "email", name: "email" },
            { data: "phone", name: "phone" },
            { data: "designation", name: "designation" },
            { data: "department", name: "department" },
            { data: "role", name: "role" },
            { data: "status", name: "status" },
            // {data: 'action', name: 'action'},
        ];
        data["order"] = [[1, "desc"]];
        data["table_id"] = "profile_Phonebook_table";
        ajaxTable(data);
    }

    $(".profile_Phonebook_table").length > 0 && profilePhonebookDatatable();
    $(".profile_Phonebook_table_search_form").on("click", () =>
        profilePhonebookDatatable()
    );
    //Profile Phonebook data table end
    //Profile visit list data table start
    function profileVisitDatatable() {
        let data = [];
        let url = $("#profile_visit_data_url").val();
        data["url"] = url;

        var daterange = $('input[name="daterange"]').val();
        var user_id = $("#user_id").val();
        data["value"] = {
            daterange: daterange ? daterange : null,
            user_id: user_id ? user_id : null,
            _token: _token,
        };

        data["column"] = [
            { data: "title", name: "title" },
            { data: "date", name: "date" },
            { data: "status", name: "status" },
            // {data: 'action', name: 'action', orderable: true, searchable: true},
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "profile_visit_table";
        ajaxTable(data);
    }

    $(".profile_visit_table").length > 0 && profileVisitDatatable();
    $(".profile_visit_table_search_form").on("click", () =>
        profileVisitDatatable()
    );
    //Profile visit list data table end

    //Profile Attendance list data table start
    function profileVisitDatatable() {
        let data = [];
        let url = $("#attendance_profile_report_data_url").val();
        data["url"] = url;

        // var daterange = $('input[name="daterange"]').val();
        var user_id = $("#user_id").val();
        data["value"] = {
            user_id: user_id ? user_id : null,
            _token: _token,
        };

        data["column"] = [
            { data: "name", name: "name" },
            { data: "department", name: "department" },
            { data: "checkin", name: "checkin" },
            { data: "checkout", name: "checkout" },
            { data: "hours", name: "hours" },
            { data: "overtime", name: "overtime" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "attendance_profile_report_table";
        ajaxTable(data);
    }

    $(".attendance_profile_report_table").length > 0 && profileVisitDatatable();

    //start Team list datatable
    function teamListDatatable() {
        let data = [];
        let url = $("#team_data_url").val();
        data["url"] = url;
        data["value"] = {
            _token: _token,
        };
        data["column"] = [
            { data: "name", name: "name" },
            { data: "team_lead", name: "team_lead" },
            { data: "number_of_persons", name: "number_of_persons" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "team_table";
        ajaxTable(data);
    }

    $(".team_table").length > 0 && teamListDatatable();
    //end Team list datatable

    //start visit report datatable
    $(".attendance_profile_report_table").length > 0 && profileVisitDatatable();

    //employee setup table start
    function payrollSetupDatatable() {
        let data = [];
        let url = $("#payroll_setup_data_url").val();
        data["url"] = url;

        data["value"] = {
            department_id: $("#department").val(),
            user_id: $("#__user_id").val(),
            _token: _token,
        };

        data["column"] = [
            { data: "employee_id", name: "employee_id" },
            { data: "employee", name: "employee" },
            { data: "designation", name: "designation" },
            { data: "department", name: "department" },
            { data: "shift", name: "shift" },
            { data: "basic_salary", name: "basic_salary" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "employee"]];
        data["table_id"] = "payroll_setup_table";
        ajaxTable(data);
    }
    $(".payroll_setup_table").length > 0 && payrollSetupDatatable();
    $(".setup_table_form").on("click", () => payrollSetupDatatable());

    // end employee setup  table
    //start  advance type report datatable
    function advanceTypeReportDatatable() {
        let data = [];
        let url = $("#payroll_advance_type_data_url").val();
        data["url"] = url;
        data["value"] = {
            _token: _token,
        };
        // employee', 'amount', 'month', 'deduction', 'installment', 'status', 'created_at', 'action'
        data["column"] = [
            { data: "name", name: "name" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "payroll_advance_type_table";
        ajaxTable(data);
    }

    $(".payroll_advance_type_table").length > 0 && advanceTypeReportDatatable();

    // end advance salary report datatable

    //start  advance salary report datatable
    function advanceSalaryReportDatatable() {
        let data = [];
        let url = $("#advance_salary_report_data_url").val();
        data["url"] = url;
        data["value"] = {
            _token: _token,
            date: $("#start").val(),
            return_status: $("#return_status").val(),
            status_id: $("#status").val(),
            pay: $("#payment").val(),
            department_id: $("#department").val(),
            user_id: $("#__user_id").val(),
        };
        data["column"] = [
            { data: "employee", name: "employee" },
            { data: "advance_type", name: "advance_type" },
            { data: "amount", name: "amount" },
            { data: "month", name: "month" },
            { data: "deduction", name: "deduction" },
            { data: "return_status", name: "return_status" },
            { data: "installment", name: "installment" },
            { data: "created_at", name: "created_at" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "payroll_advance_table";
        ajaxTable(data);
    }

    $(".payroll_advance_table").length > 0 && advanceSalaryReportDatatable();
    $(".advance_table_form").on("click", () => advanceSalaryReportDatatable());

    // end advance salary report datatable

    //start  account report datatable
    function accountsReportDatatable() {
        let data = [];
        let url = $("#accounts_datatable_url").val();
        let is_transaction = $("#is_transaction").val();
        data["url"] = url;
        data["value"] = {
            _token: _token,
        };
        data["column"] = [
            { data: "name", name: "name" },
            { data: "amount", name: "amount" },
            { data: "ac_name", name: "ac_name" },
            { data: "ac_number", name: "ac_number" },
            { data: "branch", name: "branch" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "accounts_datatable_class";
        ajaxTable(data);
    }

    $(".accounts_datatable_class").length > 0 && accountsReportDatatable();

    // end account report datatable
    //start  transaction report datatable
    function depositReportDatatable() {
        let data = [];
        let url = $("#deposit_datatable_url").val();

        data["url"] = url;
        data["value"] = {
            _token: _token,
            account: $("#account").val(),
        };
        data["column"] = [
            { data: "account", name: "account" },
            { data: "category", name: "category" },
            { data: "amount", name: "amount" },
            { data: "date", name: "date" },
            { data: "payment", name: "payment" },
            { data: "ref", name: "ref" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "deposit_datatable_class";
        ajaxTable(data);
    }

    $(".deposit_datatable_class").length > 0 && depositReportDatatable();
    $(".deposit_datatable_form").on("click", () => depositReportDatatable());
    //start  expense datatable
    function expenseReportDatatable() {
        let data = [];
        let url = $("#expense_datatable_url").val();

        data["url"] = url;
        data["value"] = {
            _token: _token,
            date: $("#__date_range").val(),
            status_id: $("#status").val(),
            payment: $("#payment").val(),
        };
        data["column"] = [
            { data: "employee", name: "employee" },
            { data: "category", name: "category" },
            { data: "amount", name: "amount" },
            { data: "date", name: "date" },
            { data: "payment", name: "payment" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "expense_datatable_class";
        ajaxTable(data);
    }

    $(".expense_datatable_class").length > 0 && expenseReportDatatable();
    $(".expense_table_form").on("click", () => expenseReportDatatable());

    // end account report datatable
    //start  transaction report datatable
    function transactionReportDatatable() {
        let data = [];
        let url = $("#transaction_datatable_url").val();

        data["url"] = url;
        data["value"] = {
            _token: _token,
            date: $("#__date_range").val(),
            transaction_type: $("#transaction_type").val(),
            account: $("#account").val(),
        };
        data["column"] = [
            { data: "account", name: "account" },
            { data: "amount", name: "amount" },
            { data: "type", name: "type" },
            { data: "date", name: "date" },
            { data: "status", name: "status" }
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "transaction_datatable_class";
        ajaxTable(data);
    }

    $(".transaction_datatable_class").length > 0 && transactionReportDatatable();
    $(".transaction_table_form").on("click", () => transactionReportDatatable());
    // end account report datatable
    //start  salary report datatable
    function salaryReportDatatable() {
        let data = [];
        let url = $("#salary_datatable_url").val();

        data["url"] = url;
        data["value"] = {
            _token: _token,
            date: $("#start").val(),
            status_id: $("#status").val(),
            department_id: $("#department").val()
        };
        data["column"] = [
            { data: "employee", name: "employee" },
            { data: "name", name: "name" },
            { data: "type", name: "type" },
            { data: "is_calculated", name: "is_calculated" },
            { data: "salary", name: "salary" },
            { data: "month", name: "month" },
            { data: "status", name: "status" },
            { data: "action", name: "action", orderable: true, searchable: true },
        ];

        data["order"] = [[1, "desc"]];
        data["table_id"] = "salary_datatable_class";
        ajaxTable(data);
    }

    $(".salary_datatable_class").length > 0 && salaryReportDatatable();

    $(".salary_table_form").on("click", () => {
        salaryReportDatatable();
    })

    // end salary report datatable


    function depositCategoryDatatable() {
        let data = [];
        let url = $("#deposit_cat_datatable_url").val();
        data["url"] = url;
        data["value"] = {
            _token: _token,
        };
        data["column"] = [
            { data: "id", name: "id", visible: false },
            // {data: "company", name: "Company"},
            { data: "name", name: "Name" },
            // {data: "attachment", name: "Attachment"},
            { data: "status", name: "Status" },
            { data: "action", name: "Action" }
        ];

        data["order"] = [[1, "id"]];
        data["table_id"] = "deposit_cat_datatable_class";
        ajaxTable(data);
    }

    $(".deposit_cat_datatable_class").length > 0 && depositCategoryDatatable();


    $(".payment_methods_datatable_class").length > 0 && paymentMethodsDatatable();
    // location bind
    function locationBindDatatable() {
        let data = [];
        data["url"] = $("#location_bind_list_data_url").val();
        data["value"] = {
            _token: _token,
        };
        data["column"] = [
            { data: "id", name: "id", visible: false },
            { data: "address", name: "address" },
            { data: "latitude", name: "latitude" },
            { data: "longitude", name: "longitude" },
            { data: "distance", name: "distance" },
            { data: "status", name: "status" },
            { data: "action", name: "Action" }
        ];

        data["order"] = [[1, "id"]];
        data["table_id"] = "location_bind_list_datatable";
        ajaxTable(data);
    }

    $(".location_bind_list_datatable").length > 0 && locationBindDatatable();

    // project data table
    function projectDatatable() {
        let data = [];
        data["url"] = $("#project_report_data_url").val();
        data["value"] = {
            _token: _token,
        };
        data["column"] = [
            { data: "id", name: "id", visible: false },
            { data: "name", name: "name" },
            { data: "client", name: "client" },
            { data: "priority", name: "priority" },
            { data: "start_date", name: "start_date" },
            { data: "end_date", name: "end_date" },
            { data: "members", name: "members" },
            { data: "status", name: "status" },
            { data: "action", name: "Action" }
        ];

        data["order"] = [[1, "id"]];
        data["table_id"] = "project_table";
        ajaxTable(data);
    }

    $(".project_table").length > 0 && projectDatatable();

    // project discussion data table

    function projectDiscussionDatatable() {
        let data = [];
        data["url"] = $("#discussion_datatable_url").val();
        data["value"] = {
            _token: _token,
        };
        data["column"] = [
            { data: "id", name: "id", visible: false },
            { data: "subject", name: "subject" },
            { data: "last_activity", name: "last_activity" },
            { data: "comments", name: "comments" },
            // {data: "visible", name: "visible"},
            { data: "time", name: "time" },
            { data: "action", name: "Action" }
        ];

        data["order"] = [[1, "id"]];
        data["table_id"] = "discussion_table_class";
        ajaxTable(data);
    }

    $(".discussion_table_class").length > 0 && projectDiscussionDatatable();




});

function dataTableUpdate() {
    $(".buttons-colvis").html(
        '<i class="fas fa-columns" style="color:#27a580"></i>'
    );
    $(".buttons-colvis").css(
        "background-color",
        "rgba(60, 210, 164,.5) !important"
    );
}


