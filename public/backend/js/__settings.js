
function databaseBackup() {
    $.ajax({
        url: baseUrl + "/database/export",
        type: "GET",
        beforeSend: function () {
        },
        success: function (response) {
            $('#__database_tbody').append()
        },
        error: function (error) {
        }
    });
}

