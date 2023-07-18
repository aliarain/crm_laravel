"use strict";

// $('.select2').select2()
$('#datetimepicker1').datetimepicker({
    format: 'L',
    useCurrent: false,
    defaultDate: $("#defaultDate").val(),
});

