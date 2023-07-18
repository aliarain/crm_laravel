"use strict";

var url    = $('#url').val();
var _token = $('meta[name="csrf-token"]').attr('content');


function TaskTable() {
    let data = [];
    data["url"] = $("#task_table_url").val();
    data["value"] = {
        _token: _token,
        limit: 10,
    };
    data["column"] = [
        "id",
        "name",
        "status",
        "start_date",
        "end_date",
        "assignee",
        "priority",
        "action",
    ];

    data["order"] = [[1, "id"]];
    data["table_id"] = "task_table_class";
    table(data);
}

$(".task_table_class").length > 0 && TaskTable();

//discussion table show
function DiscussionTable() {
    let data = [];
    data["url"] = $("#discussion_table_url").val();
    data["value"] = {
        _token: _token,
        limit: 10,
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
    data["table_id"] = "discussion_table_class";
    table(data);
}

$(".discussion_table_class").length > 0 && DiscussionTable();
