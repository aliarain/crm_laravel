"use strict";

var tableUrl = $('meta[name="base-url"]').attr("content");
//tokens from base meta tag
var _token = $('meta[name="csrf-token"]').attr("content");

$('#all_check').on('click',function () {
  if ($(this).is(':checked')) {
      // console.clear();
      $('.column_id').prop('checked', true);
  } else {
      $('.column_id').prop('checked', false);
  }
  $('.count').text('('+$('.column_id:checked').length +')');
});

function columnID(id){
  if ($('#column_'+id).is(':checked')) {
      $('.count').text('('+$('.column_id:checked').length +')');
  } else {
      $('.count').text('('+$('.column_id:checked').length +')');
  }
}


//duty designation table start
function designationDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/designation";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "title", "status", "action"];

  data["table_id"] = "designation_table";
  table(data);
}
$(".designation_table").length > 0 ? designationDatatable() : "";

//department_table start
function departmentDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/department";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "title", "status", "action"];

  data["table_id"] = "department_table";
  table(data);
}
$(".department_table").length > 0 ? departmentDatatable() : "";
//department_table end

//users_table start
function usersDatatable(...values) {
  let data = [];
  let url = tableUrl + "/dashboard/user";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    designation: $("#userTypeId").val(),
    userStatus : $("#userStatus").val(),
    _token: _token,
  };
  console.log(data["value"]);
  data["column"] = [
    "id",
    "name",
    "avatar",
    "email",
    "phone",
    "designation",
    "department",
    "role",
    "shift",
    "status",
    "action",
  ];

  data["table_id"] = "users_table";
  table(data);
}
$(".users_table").length > 0 ? usersDatatable() : "";
//users_table end

//role_table start
function roleDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/roles";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "name", "status", "action"];

  data["table_id"] = "role_table";
  table(data);
}
$(".role_table").length > 0 ? roleDatatable() : "";
//role_table end

//leave_type_table start
function leaveTypeDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/leave";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "name", "status", "action"];

  data["table_id"] = "leave_type_table";
  table(data);
}
$(".leave_type_table").length > 0 ? leaveTypeDatatable() : "";
//role_table end

//leave_assign_table start
function leaveAssignDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/leave/assign";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    department_id: $("#department").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "department", "type", "days", "status", "action"];

  data["table_id"] = "leave_assign_table";
  table(data);
}
$(".leave_assign_table").length > 0 ? leaveAssignDatatable() : "";
//leave_assign_table end

//leave_request_table start
function leaveRequestDatatable(...values) {
  let data = [];
  data["url"] = $("#leave_request_table_url").val();
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    user_id: $("#__user_id").val(),
    type: $("#type").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "name",
    "type",
    "date",
    "days",
    "available_days",
    "substitute",
    "manager_approved",
    "hr_approved",
    "file",
    "status",
    "action",
  ];

  data["table_id"] = "leave_request_table";
  table(data);
}
$(".leave_request_table").length > 0 ? leaveRequestDatatable() : "";
//leave_request_table end



//attendance_table start
function attendanceDatatable(...values) {
  let data = [];
  let url = $("#attendance_table_url").val();
  data["url"] = url;
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    department: $("#department_id").val(),
    user_id: $("#__user_id").val() ?? null,
    type: $("#type").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "name",
    "date",
    "department",
    "totalBreak",
    "breakDuration",
    "checkin",
    "checkout",
    "hours",
    "overtime",
    "action",
  ];

  data["table_id"] = "attendance_table";
  table(data);
}
$(".attendance_table").length > 0 ? attendanceDatatable() : "";
//attendance_table end

//salary_set_up_table start
function salarySetUpDatatable(...values) {
  let data = [];
  data["url"] = $("#salary_set_up_url").val();
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    department: $("#department_id").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "name",
    "designation",
    "department",
    "shift",
    "basic_salary",
    "status",
    "action",
  ];
  data["table_id"] = "salary_set_up_table";
  table(data);
}
$(".salary_set_up_table").length > 0 ? salarySetUpDatatable() : "";
//salary_set_up_table end

//salary_advance_table start
function salaryAdvanceDatatable(...values) {
  let data = [];
  data["url"] = $("#salary_advance_table_url").val();
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    user_id: $("#__user_id").val(),
    return_status: $("#return_status").val(),
    status: $("#status").val(),
    payment: $("#payment").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "employee",
    "amount",
    "month",
    "advance_type",
    "payment",
    "return_status",
    "installment",
    "status",
    "action",
  ];

  data["table_id"] = "salary_advance_table";
  table(data);
}
$(".salary_advance_table").length > 0 ? salaryAdvanceDatatable() : "";
//salary_advance_table end

//salary_advance_table start
function payrollItemDatatable(...values) {
  let data = [];
  data["url"] = $("#payroll_item_table_url").val();
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    status: $("#status").val(),
    department: $("#department_id").val(),
    _token: _token,
  };
  data["column"] = ["id", "name", "type", "status", "action"];

  data["table_id"] = "table_class";
  table(data);
}
$("#payroll_item_table_url").length > 0 ? payrollItemDatatable() : "";

//salary_table end
//salary_advance_table start
function salaryDatatable(...values) {
  let data = [];
  // let url = tableUrl + '/hrm/payroll/salary';
  // data["url"] = url;
  data["url"] = $("#salary_table_url").val();
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    user_id: $("#__user_id").val(),
    search: $('input[name="search"]').val(),
    status: $("#status").val(),
    department: $("#department_id").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "employee",
    "salary",
    "month",
    "type",
    "is_calculated",
    "status",
    "action",
  ];

  data["table_id"] = "salary_table";
  table(data);
}
$(".salary_table").length > 0 ? salaryDatatable() : "";
//salary_table end

//accounts_datatable start
function accountsDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/accounts";
  data["url"] = url;
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "name",
    "balance",
    "ac_name",
    "ac_number",
    "branch",
    "status",
    "action",
  ];

  data["table_id"] = "accounts_datatable";
  table(data);
}
$(".accounts_datatable").length > 0 ? accountsDatatable() : "";
//accounts_datatable end

//deposit_datatable start
function depositDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/deposit";
  data["url"] = url;
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    account: $("#account").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "account",
    "category",
    "amount",
    "date",
    "payment",
    "ref",
    "action",
  ];

  data["table_id"] = "deposit_datatable";
  table(data);
}
$(".deposit_datatable").length > 0 ? depositDatatable() : "";
//deposit_datatable end

//expense_datatable start
function expenseDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/expenses";
  data["url"] = url;
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    payment: $("#payment").val(),
    status: $("#status").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "employee",
    "category",
    "amount",
    "date",
    "payment",
    "status",
    "action",
  ];

  data["table_id"] = "expense_datatable";
  table(data);
}
$(".expense_datatable").length > 0 ? expenseDatatable() : "";
//expense_datatable end

//transaction_datatable start
function transactionDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/transactions";
  data["url"] = url;
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    account: $("#account").val(),
    transaction_type: $("#transaction_type").val(),
    _token: _token,
  };
  data["column"] = ["id", "account", "amount", "type", "date", "status"];

  data["table_id"] = "transaction_datatable";
  table(data);
}
$(".transaction_datatable").length > 0 ? transactionDatatable() : "";
//transaction_datatable end

//deposit_cat_datatable start
function depositCatDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/account-settings/deposit";
  data["url"] = url;
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from,
    to: to,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    is_income: $("#is_income").val() ?? 0,
    _token: _token,
  };
  data["column"] = ["id", "name", "status", "action"];

  data["table_id"] = "deposit_cat_datatable";
  table(data);
}
$(".deposit_cat_datatable").length > 0 ? depositCatDatatable() : "";

// deposit_cat_datatable end

//payment_methods_datatable start
function paymentMethodDatatable(...values) {
  let data = [];
  let url = tableUrl + "/hrm/payment-methods";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "name", "status", "action"];

  data["table_id"] = "payment_methods_datatable";
  table(data);
}
$(".payment_methods_datatable").length > 0 ? paymentMethodDatatable() : "";

// payment_methods_datatable end

//clients_datatable start
function clientDatatable(...values) {
  let data = [];
  let url = tableUrl + "/admin/client";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "name",
    "avatar",
    "email",
    "phone",
    "website",
    "status",
    "action",
  ];

  data["table_id"] = "clients_datatable";
  table(data);
}
$(".clients_datatable").length > 0 ? clientDatatable() : "";

// clients_datatable end

//task_table start
function taskDatatable(...values) {
  let data = [];
  data["url"] = $("#task_table_url").val();
  let { from, to } = __date_range ?? {};

  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from,
    to: to,
    short_by: shortBy ? shortBy : null,
    user_id: $("#__user_id").val() ?? null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "name",
    "description",
    "status",
    "start_date",
    "end_date",
    "assignee",
    "priority",
    "action",
  ];

  data["table_id"] = "task_table";
  table(data);
}
$(".task_table").length > 0 ? taskDatatable() : "";

// task_table end

//task files table
function fileTaskTable() {
  let { from, to } = __date_range ?? {};
  let data = [];
  data["url"] = $("#file_table_url_id").val();
  data["value"] = {
    from: from,
    to: to,
    search: $('input[name="search"]').val(),
    _token: _token,
    limit: $("#entries").val(),
  };
  data["column"] = [
    "id",
    "subject",
    "last_activity",
    "comments",
    "date",
    "action",
  ];

  data["order"] = [[1, "id"]];
  data["table_id"] = "file_table";
  table(data);
}
$("#file_table_url_id").length > 0 ? fileTaskTable() : "";

//task  files table

//discussion table show
function DiscussionTaskTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#discussion_table_url_id").val();
  data["value"] = {
    from: from,
    to: to,
    _token: _token,
    search: $('input[name="search"]').val(),
    limit: $("#entries").val(),
  };
  data["column"] = [
    "id",
    "subject",
    "last_activity",
    "comments",
    "date",
    "action",
  ];

  data["order"] = [[1, "id"]];
  data["table_id"] = "discussion_table";
  table(data);
}

$(".discussion_table").length > 0 && DiscussionTaskTable();
//discussion table show

//project_table table
function projectListTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#project_table_url").val();
  data["value"] = {
    from: from,
    to: to,
    _token: _token,
    search: $('input[name="search"]').val(),
    pay: $("#payment").val(),
    status: $("#status").val(),
    priority: $("#priority").val(),
    limit: $("#entries").val(),
  };
  data["column"] = [
    "id",
    "name",
    "client",
    "priority",
    "start_date",
    "end_date",
    "members",
    "status",
    "action",
  ];

  data["order"] = [[1, "id"]];
  data["table_id"] = "table_class";
  table(data);
}

$("#project_table_url").length > 0 && projectListTable();

//project_table

//award table show
function awardTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#award_table_url").val();
  data["value"] = {
    from: from,
    to: to,
    _token: _token,
    user_id: $("#user_id").val() ?? $("#__user_id").val(),
    limit: $("#entries").val(),
  };
  data["column"] = [
    "id",
    "name",
    "type",
    "gift",
    "amount",
    "date",
    "status",
    "action",
  ];

  data["order"] = [[1, "id"]];
  data["table_id"] = "award_table_class";
  table(data);
}

$(".award_table_class").length > 0 && awardTable();

//award type table show
function awardTypeTable() {
  let data = [];
  data["url"] = $("#award_type_table_url").val();
  data["value"] = {
    _token: _token,
    search: $('input[name="search"]').val(),
    limit: $("#entries").val(),
  };
  data["column"] = ["id", "name", "status", "action"];

  data["order"] = [[1, "id"]];
  data["table_id"] = "award_type_table_class";
  table(data);
}

$(".award_type_table_class").length > 0 && awardTypeTable();

//travel type table show
function travelTypeTable() {
  let data = [];
  data["url"] = $("#travel_type_table_url").val();
  data["value"] = {
    _token: _token,
    search: $('input[name="search"]').val(),
    limit: $("#entries").val(),
  };
  data["column"] = ["id", "name", "status", "action"];

  data["order"] = [[1, "id"]];
  data["table_id"] = "travel_type_table_class";
  table(data);
}

$(".travel_type_table_class").length > 0 && travelTypeTable();

//travel table show
function travelTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#travel_table_url").val();
  data["value"] = {
    from: from ?? null,
    to: to ?? null,
    _token: _token,
    user_id: $("#user_id").val() ?? $("#__user_id").val(),
    limit: $("#entries").val(),
  };
  data["column"] = [
    "id",
    "name",
    "type",
    "place",
    "status",
    "amount",
    "date",
    "action",
  ];

  data["order"] = [[1, "id"]];
  data["table_id"] = "travel_table_class";
  table(data);
}
$(".travel_table_class").length > 0 && travelTable();

//indicator table show
function indicatorTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#indicator_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    department: $("#department").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "title",
    "department",
    "designation",
    "shift",
    "rating",
    "added_by",
    "created_at",
    "action",
  ];

  data["order"] = [[1, "id"]];
  data["table_id"] = "indicator_table_class";
  table(data);
}
$(".indicator_table_class").length > 0 && indicatorTable();

//appraisal table show
function appraisalTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#appraisal_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    department: $("#department_id").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "title",
    "user",
    "department",
    "designation",
    "rating",
    "added_by",
    "created_at",
    "action",
  ];

  data["order"] = [[1, "id"]];
  data["table_id"] = "appraisal_table_class";
  table(data);
}
$(".appraisal_table_class").length > 0 && appraisalTable();

//goal table show
function goalTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#goal_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
    limit: 10,
  };
  data["column"] = [
    "id",
    "goal_type",
    "subject",
    "target",
    "rating",
    "progress",
    "start_date",
    "end_date",
    "action",
  ];

  data["order"] = [[1, "id"]];
  data["table_id"] = "goal_table_class";
  table(data);
}

$(".goal_table_class").length > 0 && goalTable();

// competence type  table show
function performanceSettingsTable(tabl_url) {
  let data = [];
  data["url"] = $(`#${tabl_url}`).val();
  data["value"] = {
    _token: _token,
    search: $('input[name="search"]').val(),
    limit: $("#entries").val(),
  };
  data["column"] = ["id", "name", "status", "action"];

  data["order"] = [[1, "id"]];
  data["table_id"] = "table_class";
  table(data);
}
$("#competence_type_table_url").length > 0 &&
  performanceSettingsTable("competence_type_table_url");
// competence type  table show

function competenceTable() {
  let data = [];
  data["url"] = $("#competence_table_url").val();
  data["value"] = {
    _token: _token,
    search: $('input[name="search"]').val(),
    limit: $("#entries").val(),
    type: $("#type_id").val(),
  };
  data["column"] = ["id", "name", "type", "status", "action"];

  data["order"] = [[1, "id"]];
  data["table_id"] = "table_class";
  table(data);
}
$("#competence_table_url").length > 0 && competenceTable();

$("#goal_type_table_url").length > 0 &&
  performanceSettingsTable("goal_type_table_url");

function appointmentDatatable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#appointment_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    user_id: $("#__user_id").val(),
    search: $('input[name="search"]').val(),
    limit: $("#entries").val(),
    _token: _token,
  };

  data["column"] = [
    "id",
    "title",
    "appoinment_with",
    "date",
    "start_at",
    "end_at",
    "location",
    "file",
    "status",
  ];

  data["table_id"] = "table_class";
  table(data);
}

$("#appointment_table_url").length > 0 && appointmentDatatable();

//Visit  data table start
function visitDatatable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#visit_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    user_id: $("#__user_id").val(),
    status: $("#status").val(),
    _token: _token,
  };

  data["column"] = [
    "id",
    "employee_name",
    "date",
    "title",
    "description",
    "cancel_note",
    "file",
    "status",
    "action",
  ];

  data["table_id"] = "table_class";
  table(data);
}

$("#visit_table_url").length > 0 && visitDatatable();
// visit end

//support ticket  data table
function supportTicketsDataTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#support_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    user_id: $("#__user_id").val(),
    status: $("#status").val(),
    _token: _token,
  };

  data["column"] = [
    "id",
    "date",
    "code",
    "employee_name",
    "subject",
    "type",
    "priority",
    "action",
  ];

  data["table_id"] = "table_class";

  table(data);
}

$("#support_table_url").length > 0 && supportTicketsDataTable();
//support ticket  data table

function noticeTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#notice_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    department: $("#department").val(),
    _token: _token,
  };

  data["column"] = [
    "id",
    "date",
    "subject",
    "department",
    "description",
    "file",
    "action",
  ];

  data["table_id"] = "table_class";
  table(data);
}
$("#notice_table_url").length > 0 && noticeTable();

// report all  table

// break time table
function breakTable(...values) {
  let data = [];
  data["url"] = $("#break_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    user_id: $("#__user_id").val() ?? null,
    type: $("#type").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "date",
    "name",
    "department",
    "start",
    "end",
    "duration",
    "reason",
  ];

  data["table_id"] = "table_class";
  table(data);
}
$("#break_table_url").length > 0 ? breakTable() : "";
//break time table end

// expense payment table
function expensePaymentTable(...values) {
    let data = [];
    data["url"] = $("#expense_payment_table_url").val();
    let { from, to } = __date_range??{};
    data["value"] = {
        from: from?from:null,
        to: to?to:null,
        limit   : $("#entries").val(),
        search  : $('input[name="search"]').val(),
        user_id : $("#user_id").val() ?? null,
        category_id : $("#category_id").val() ?? null,
        type    : $("#type").val(),
        _token: _token,
    };
    data["column"] = [
        "id", 
        "date", 
        "employee_name",
        "amount",
        "file", 
        "status",
        // "action"
    ];

  data["table_id"] = "table_class";
  table(data);
}
$("#expense_payment_table_url").length > 0 ? expensePaymentTable() : "";
//expense payment table end

// weekend table
function weekendTable(...values) {
  let data = [];
  data["url"] = $("#weekend_payment_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "name", "weekend", "status", "action"];

  data["table_id"] = "table_class";
  table(data);
}
$("#weekend_payment_table_url").length > 0 ? weekendTable() : "";
//weekend table end
// holiday table
function holidayTable(...values) {
  let data = [];
  data["url"] = $("#holiday_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "title",
    "description",
    "file",
    "start_date",
    "end_date",
    "status",
    "action",
  ];

  data["table_id"] = "table_class";
  table(data);
}
$("#holiday_table_url").length > 0 ? holidayTable() : "";
//holiday table end

// dutySchedule table
function dutyScheduleTable(...values) {
  let data = [];
  data["url"] = $("#duty_schedule_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "shift",
    "start_time",
    "end_time",
    "hour",
    "consider_time",
    "status",
    "action",
  ];

  data["table_id"] = "table_class";
  table(data);
}
$("#duty_schedule_table_url").length > 0 ? dutyScheduleTable() : "";
//dutySchedule table end

// shift table
function shiftTable(...values) {
  let data = [];
  data["url"] = $("#shift_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "name", "status", "action"];

  data["table_id"] = "table_class";
  table(data);
}
$("#shift_table_url").length > 0 ? shiftTable() : "";

//ip table end
function ipTable(...values) {
  let data = [];
  data["url"] = $("#ip_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "location", "address", "status", "action"];
  data["table_id"] = "table_class";
  table(data);
}
$("#ip_table_url").length > 0 ? ipTable() : "";
//ip table end

//location table end
function locationTable(...values) {
  let data = [];
  data["url"] = $("#location_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "location",
    "latitude",
    "longitude",
    "distance",
    "status",
    "action",
  ];
  data["table_id"] = "table_class";
  table(data);
}
$("#location_table_url").length > 0 ? locationTable() : "";
//location table end

//payroll_item_set_up_table_url table end
function commissionSetTable(...values) {
  let data = [];
  data["url"] = $("#payroll_item_set_up_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "name", "type", "amount", "status", "action"];
  data["table_id"] = "table_class";
  table(data);
}
$("#payroll_item_set_up_table_url").length > 0 ? commissionSetTable() : "";
//payroll_item_set_up_table_url table end

// advanceType table
function advanceTypeTable(...values) {
  let data = [];
  data["url"] = $("#advance_type_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "name", "status", "action"];

  data["table_id"] = "table_class";
  table(data);
}
$("#advance_type_table_url").length > 0 ? advanceTypeTable() : "";
// advanceType table

// language table
function languageTable(...values) {
  let data = [];
  data["url"] = $("#language_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "name", "native", "code", "rtl", "status", "action"];

  data["table_id"] = "table_class";
  table(data);
}
$("#language_table_url").length > 0 ? languageTable() : "";
// language table

// phonebook table
function phonebookTable(...values) {
  let data = [];
  data["url"] = $("#phonebook_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    user_id: $("#__user_id").val(),
    limit: $("#entries").val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "name",
    "phone",
    "designation",
    "department",
    "role",
    "status",
  ];

  data["table_id"] = "table_class";
  table(data);
}
$("#phonebook_table_url").length > 0 ? phonebookTable() : "";
// phonebook table

// contact table
function contactTable(...values) {
  let data = [];
  data["url"] = $("#contact_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "name",
    "email",
    "phone",
    "contact_for",
    "message",
    // "contact_status",
  ];

  data["table_id"] = "table_class";
  table(data);
}
$("#contact_table_url").length > 0 ? contactTable() : "";
// contact table

// content table
function contentTable(...values) {
  let data = [];
  data["url"] = $("#content_table_url").val();
  let { from, to } = __date_range ?? {};
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "title", "slug", "status", "action"];

  data["table_id"] = "table_class";
  table(data);
}
$("#content_table_url").length > 0 ? contentTable() : "";
// content table

//meeting table show
function meetingTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#meeting_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "title", "participants", "date", "time", "action"];

  data["order"] = [[1, "id"]];
  data["table_id"] = "meeting_table_class";
  table(data);
}
$(".meeting_table_class").length > 0 && meetingTable();

//menu table show
function menuTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#menu_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "name", "type", "status", "action"];

  data["order"] = [[1, "id"]];
  data["table_id"] = "table_class";
  table(data);
}
$("#menu_table_url").length > 0 && menuTable();
//service table show
function serviceTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#service_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "title", "status", "action"];

  data["order"] = [[1, "id"]];
  data["table_id"] = "table_class";
  table(data);
}
$("#service_table_url").length > 0 && serviceTable();
//portfolio table show
function portfolioTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#portfolio_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = ["id", "title", "attachment", "status", "action"];

  data["order"] = [[1, "id"]];
  data["table_id"] = "table_class";
  table(data);
}
$("#portfolio_table_url").length > 0 && portfolioTable();

//teamMember table show
function teamMemberTable() {
  let data = [];
  let { from, to } = __date_range ?? {};
  data["url"] = $("#team_member_table_url").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };
  data["column"] = [
    "id",
    "name",
    "designation",
    "attachment",
    "status",
    "action",
  ];
  data["order"] = [[1, "id"]];
  data["table_id"] = "table_class";
  table(data);
}
$("#team_member_table_url").length > 0 && teamMemberTable();





// report table


//report_attendance_table start
function reportAttendanceDatatable(...values) {
  let data = [];
  let url = $("#report_attendance_table_url").val();
  data["url"] = url;
  let { from, to } = __date_range ?? {};
  var shortBy = $("#short_by").val();
  data["value"] = {
    from: from ? from : null,
    to: to ? to : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    department: $("#department_id").val(),
    user_id: $("#__user_id").val() ?? null,
    type: $("#type").val(),
    is_report : 1,
    _token: _token,
  };
  data["column"] = [
    "id",
    "name",
    "date",
    "department",
    "totalBreak",
    "breakDuration",
    "checkin",
    "checkout",
    "hours",
    "overtime",
  ];

  data["table_id"] = "report_attendance_table";
  table(data);
}
$(".report_attendance_table").length > 0 ? reportAttendanceDatatable() : "";
//report_attendance_table end





//leads_datatable start
function leads_datatable(...values) {
  let data = [];
  let url = tableUrl + "/admin/leads";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();
  var lead_types = $("#lead_types").val();
  var lead_sources = $("#lead_sources").val();
  var lead_statuses = $("#lead_statuses").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
    lead_types: lead_types ? lead_types : null,
    lead_sources: lead_sources ? lead_sources : null,
    lead_statuses: lead_statuses ? lead_statuses : null,
  };

  data["column"] = [
    "id",
    "type",
    "source", 
    "project_status",
    "date",
    "name", 
    "title",
    "company", 
    "address", 
    "next_follow_up", 
    "status",
    "action",
  ];

  data["table_id"] = "leads_datatable";
  table(data);
}
$(".leads_datatable").length > 0 ? leads_datatable() : "";

if($("#lead_types").length > 0){
  $('#lead_types').on('change', function() {
    leads_datatable();
  });
}
if($("#lead_sources").length > 0){
  $('#lead_sources').on('change', function() {
    leads_datatable();
  });
}
if($("#lead_statuses").length > 0){
  $('#lead_statuses').on('change', function() {
    leads_datatable();
  });
}
// leads_datatable end







// source_datatable start
function sources_datatable(...values) {
  let data = [];
  let url = tableUrl + "/admin/sources";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };

  data["column"] = [
    "id",
    "name",
    "status",
    "action",
  ];

  data["table_id"] = "sources_datatable";
  table(data);
}
$(".sources_datatable").length > 0 ? sources_datatable() : "";



// lead types start
function types_datatable(...values) {
  let data = [];
  let url = tableUrl + "/admin/types";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };

  data["column"] = [
    "id",
    "title",
    "order",
    "status",
    "action",
  ];

  data["table_id"] = "types_datatable";
  table(data);
}
$(".types_datatable").length > 0 ? types_datatable() : "";
0


// lead sources start
function sources_datatable(...values) {
  let data = [];
  let url = tableUrl + "/admin/sources";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };

  data["column"] = [
    "id",
    "title",
    "order",
    "status",
    "action",
  ];

  data["table_id"] = "sources_datatable";
  table(data);
}
$(".sources_datatable").length > 0 ? sources_datatable() : "";


// lead statuses start
function statuses_datatable(...values) {
  let data = [];
  let url = tableUrl + "/admin/statuses";
  data["url"] = url;

  var from_date = $("#start").val();
  var to_date = $("#end_date").val();
  var shortBy = $("#short_by").val();

  data["value"] = {
    from: from_date ? from_date : null,
    to: to_date ? to_date : null,
    short_by: shortBy ? shortBy : null,
    limit: $("#entries").val(),
    search: $('input[name="search"]').val(),
    _token: _token,
  };

  data["column"] = [
    "id",
    "title",
    "order",
    "border_color",
    "background_color",
    "text_color",
    "status",
    "action",
  ];

  data["table_id"] = "statuses_datatable";
  table(data);
}
$(".statuses_datatable").length > 0 ? statuses_datatable() : "";