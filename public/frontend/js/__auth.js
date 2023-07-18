function _authenticate() {
    let name =  $('#name').val();
    let _phone =  $('#_phone').val();
    let email =  $('#_email').val();
    let _password =  $('#_password').val();
    let _password_confirmation =  $('#_password_confirmation').val();
    let _terms_condition_input =  $('#_terms_condition_input').val();

    // all data should be filled
      if (name == '') {
          $('#name').addClass('error');
          $('.name_error').text('Name is required');
      }
      else {
          $('#name').removeClass('error');
          $('.name_error').remove();
      }
      // phone is mandatory && lable error show for phone field
      if (_phone == '') {
          $('#_phone').addClass('error');
          $('._guest_error_phone').remove();
          $('#_phone').after('<small class="error _guest_error_phone">Phone is required</small>');
          $('.phone_no').html('');
      }else {
          $('#_phone').removeClass('error');
          $('._guest_error_phone').remove();
      }
      if (email == '') {
          $('#_email').addClass('error');
          $('.email_error').remove();
          $('#_email').after('<small class="error email_error">Email is required</small>');
          $('.phone_no').html('');
      }else {
          $('#_email').removeClass('error');
          $('.email_error').remove();
      }
      if (_password == '') {
          $('#_password').addClass('error');
          $('#present_address').after('<small class="error _guest_error_address">Password is required</small>');
      }else {
          $('#_password').removeClass('error');
          $('._password_error').text('');
      }
      // _password_confirmation is mandatory && lable error show for _password_confirmation field
      if (_password_confirmation == '') {
          $('#_password_confirmation_id').addClass('error');
          $('._guest_error__password_confirmation').text('Password confirmation is required');
      }
      else {
          $('#_password_confirmation_id').removeClass('error');
          $('._guest_error__password_confirmation').text('');
      }
      if (_terms_condition_input == '' || _terms_condition_input == 0) {
          $('#_terms_condition_input').addClass('error');
          $('._terms_condition_input_error').text('Upazilla is required');
      }
      else {
          $('#_terms_condition_input').removeClass('error');
          $('._terms_condition_input_error').text('');
      }
      // check all data for blood group field is not empty or not required and retur true
        if (name != '' && phone != '' && email != '' && address != '' && _password != '' && _password_confirmation != '' && _terms_condition_input != '' && date_of_birth != '' && last_donation_date != '' && blood_group != '') {
            return true;
        }
        return false;
      

  }
$('.datepicker').on('change', function() {
    _authenticate();
});


$("#sub_button").on("click", submitFormFunction);

function submitFormFunction(event) {
    event.preventDefault(); 
    let address_data = _authenticate();
    console.log(address_data);
    if(address_data){
        $("#_form_submit").submit();
    }
    return false;
}


 let baseUrl = $('#_url').val();
getDivision = function(val) {
   _authenticate();
    $.ajax({
        url: baseUrl + "/_password_confirmation-get/" + val,
        type: "GET",
        dataType: "json",
        success: function(data) {
            $('#_password_confirmation_id').empty();
            $('#_password_confirmation_id').append('<option value="0">Select _password_confirmation</option>');
            $.each(data, function(key, value) {
                $('#_password_confirmation_id').append('<option value="' + value.id + '">' + value.name + '</option>');
            });
        }
    });
}
