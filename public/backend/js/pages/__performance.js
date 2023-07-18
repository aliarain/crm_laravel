"use strict";





//goal table show
function goalTable() {
    let data = [];
    data["url"] = $("#goal_table_url").val();
    data["value"] = {
        _token: _token,
        limit: 10,
    };
    data["column"] = [
        'goal_type',
        'subject',
        'target',
        'rating',
        'progress',
        'start_date',
        'end_date',
        'action'
    ];

    data["order"] = [[1, "id"]];
    data["table_id"] = "goal_table_class";
    table(data);
}


$(".goal_table_class").length > 0 && goalTable();

// competence type table 

function competenceTypeTable() {
    let data = [];
    data["url"] = $("#competence_type_table_url").val();
    data["value"] = {
        _token: _token,
        limit: 10,
    };
    data["column"] = [
        "id",
        "name",
        "status",
        "action",
    ];

    data["order"] = [[1, "id"]];
    data["table_id"] = "competence_type_table_class";
    table(data);
}
$(".competence_type_table_class").length > 0 && competenceTypeTable();

// competence table

function competenceTable() {
    let data = [];
    data["url"] = $("#competence_table_url").val();
    data["value"] = {
        _token: _token,
        limit: 10,
    };
    data["column"] = [
        "id",
        "name",
        "type",
        "status",
        "action",
    ];

    data["order"] = [[1, "id"]];
    data["table_id"] = "competence_table_class";
    table(data);
}
$(".competence_table_class").length > 0 && competenceTable();

// goal type table 

function goalTypeTable() {
    let data = [];
    data["url"] = $("#goal_type_table_url").val();
    data["value"] = {
        _token: _token,
        limit: 10,
    };
    data["column"] = [
        "id",
        "name",
        "status",
        "action",
    ];

    data["order"] = [[1, "id"]];
    data["table_id"] = "goal_type_table_class";
    table(data);
}
$(".goal_type_table_class").length > 0 && goalTypeTable();