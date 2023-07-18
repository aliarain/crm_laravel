"use strict"

//Add New Payroll Commission
$('#benefit_add_btn').on('click', function(e) {
    e.preventDefault();
    var form=$('#benefit_add_form');
    var formData = $('#benefit_add_form').serialize();
    $.ajax({
        url: form.attr('data-url'),
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(data) {
            if (data.result) {
                $('#addCommissionSetup').modal('hide');
                $('#benefit_add_form')[0].reset();
                $('#benefit_add_form').find('.form-group').removeClass('has-error');
                $('#benefit_table').DataTable().ajax.reload();
                $('.error-message').html('');
                $('#benefits_table_reload').click();
                toastr.success(data.message,'Success');
            }else{
                $('#addCommissionSetup').modal('hide');
                $('#benefit_add_form')[0].reset();
                $('#benefit_add_form').find('.form-group').removeClass('has-error');
                $('#benefit_add_form').find('.form-group').addClass('has-error');
                toastr.error(data.message,'Error');
            }
        },
        error: function(data) {
            $.each(data.responseJSON.error, function(key, value){
                $('#'+key).closest('.form-group').addClass('has-error');
                $('#'+key).closest('.form-group').find('.error-message').text(value);
            });
        }
    });
});
function editBenifit(data){
    console.log(data);
}

