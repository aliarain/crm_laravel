"use strict";

//Get Users
$('#members').select2({
    placeholder: $('#select_members').val(),
    placement: 'bottom',
    width: "100%",
    ajax: {
        url: $('#get_user_url').val(),
        dataType: 'json',
        type: 'POST',
        delay: 250,
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id,
                    }
                })
            }
        },
        cache: true
    }
});

//travel type table show
function travelTypeTable() {
    let data = [];
    data["url"] = $("#travel_type_table_url").val();
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
    data["table_id"] = "travel_type_table_class";
    table(data);
}

$(".travel_type_table_class").length > 0 && travelTypeTable();

//travel table show
function travelTable() {
    let data = [];
    data["url"] = $("#travel_table_url").val();
    data["value"] = {
        _token: _token,
        limit: 10,
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
