//travel type table show
function travelTypeTable() {
    let data = [];
    data["url"] = window.location.origin;
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
    data['fake_data'] = [
        {
            id: 1,
            name: "Travel Type 1",
            status: "Active",
            action: "<a href='#' class='btn btn-primary btn-sm'>Edit</a>",
        },
    ];
    table(data);
}

$(".travel_type_table_class").length > 0 && travelTypeTable();
