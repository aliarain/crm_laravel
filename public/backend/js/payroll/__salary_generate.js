"use strict";

$('.modal_select2').select2({
    placeholder: document.getElementById('select_department').value,
})
let department_id = 0;
function makeGenerate() {
    const idDepartment = document.getElementById('department');
    const idMonth = document.getElementById('month');
    let isReturned = true;
    if (idDepartment?.value === '') {
        $('.error_show_department').addClass('invalid-feedback');
        $('.error_show_department').html($('#error_department').val());
        isReturned = false;
    }else{
        idDepartment.classList.remove('is-invalid');
        $('.error_show_department').html('');
    }

    if (idMonth?.value === '') {
        idMonth.classList.add('is-invalid');  
        $('.error_show_month').addClass('invalid-feedback');
        $('.error_show_month').html($('#error_month').val());
        idMonth.focus();
        isReturned = false;
    }else{
        idMonth.classList.remove('is-invalid');
        $('.error_show_month').html('');
    }

    if (isReturned) {
        $.ajax({
            url: $('#__generate').val(),
            type: "POST",
            data: { 
                department: department_id,
                month: idMonth.value,
                _token: $('meta[name="csrf-token"]').attr('content')
             },
            beforeSend: function () {},
            success: function (response) {
                if (response.result) {
                    Swal.fire({
                        title: response.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    setTimeout(function () {
                        window.location.reload();
                    }, 3000);
                }
            },
            error: function (error) {
                if (error) {
                    if (error.responseJSON.error?.month) {
                        $('.error_show_month').addClass('invalid-feedback');
                        $(".error_show_month").html(error.responseJSON.error.month[0]);
                    }
                    if (error.responseJSON.error?.department) {
                        $('.error_show_department').addClass('invalid-feedback');
                        $(".error_show_department").html(error.responseJSON.error.department[0]);
                    }
                }
                if (error.responseJSON.message) {                    
                    Swal.fire({
                        title: error.responseJSON.message,
                        type: 'error',
                        icon: 'error',
                        timer: 3000
                    });
                    setTimeout(function () {
                        window.location.reload();
                    }, 3000);
                };
            },
        });
        
    }
    

}

$('#month').on('change', function () {
    const idMonth = document.getElementById('month');
    if (idMonth?.value === '') {
        idMonth.classList.add('is-invalid');  
        $('.error_show_month').addClass('invalid-feedback');
        $('.error_show_month').html($('#error_month').val());
        idMonth.focus();
        isReturned = false;
    }else{
        idMonth.classList.remove('is-invalid');
        $('.error_show_month').html('');
    }
});


$('.modal_select2').on('select2:select', function (e) {
    department_id = e.params.data.id;
});