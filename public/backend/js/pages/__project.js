"use strict";

var url = $('#url').val();
var _token = $('meta[name="csrf-token"]').attr('content');

//Get Users
$('#members').select2({
    placeholder: $("#select_members").val(),
    placement: "bottom",
    width: "100%",
    search: true,
    ajax: {
      url: $("#get_user_url").val(),
      dataType: "json",
      data: function (params) {
        return {
          _token: _token,
          term: params.term,
        };
      },
      type: "POST",
      delay: 250,
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              text: item.name,
              id: item.id,
            };
          }),
        };
      },
      cache: true,
    },
});

let oldMembers = $('#members').select2('val');

const newMembers = () => {
    let newMembers = $('#members').select2('val');
    let remove = oldMembers.filter(function(item) { return newMembers.indexOf(item) < 0; });
    let add = newMembers.filter(function(item) { return oldMembers.indexOf(item) < 0; });
    $('#remove_members').val(remove);
    $('#new_members').val(add);
    
}

const progressValue = (val) => {
    $('#progress_percentage').html(val + '%');
}
const billingType = (val) => {
    $('#per_rate').attr('required', false);
    $('#total_rate').attr('required', false);
    if (val == 'hourly') {
        $('#per_rate').attr('required', true);
        $('.per_rate').removeClass('d-none');
        $('.total_rate').addClass('d-none');
    }else{
        $('#total_rate').attr('required', true);
        $('.per_rate').addClass('d-none');
        $('.total_rate').removeClass('d-none');
    }
}
const calculateAmount = () => {
    $('#amount').attr('readonly', true);
    if ($('#billing_type').val() == 'hourly') {
        $('#amount').val(parseFloat($('#per_rate').val() ? $('#per_rate').val() : 0) * parseFloat($('#estimated_hour').val() ? $('#estimated_hour').val() : 0));
    }else{
        $('#amount').val(parseFloat($('#total_rate').val() ? $('#total_rate').val() : 0));
    }
}

let subjectTouched = false;
let descriptionTouched = false;

function discussionValidate() {
   let subject      = $('#subject').val();
   let description  = $('#description').val();
   let isReturned   = true;

    if (subject == '') {
       if (subjectTouched) {
           $('#subject').addClass('is-invalid');
           $('.error_show_subject').addClass('invalid-feedback');
           $('.error_show_subject').html($('#error_subject').val());
           isReturned = false; 
       }   
    } else {
        $('#subject').removeClass('is-invalid');
        $('.error_show_subject').html('');
    }
    if (description == '') {
        if (descriptionTouched) {
            $('#description').addClass('is-invalid');
            $('.error_show_description').addClass('invalid-feedback');
            $('.error_show_description').html($('#error_description').val());
            isReturned = false;
        }
    } else {
        $('#description').removeClass('is-invalid');
        $('.error_show_description').html('');
    }
    return isReturned;
}

$('#subject').on('input', function() {
    subjectTouched = true;
    discussionValidate();
});

$('#description').on('input', function() {
    descriptionTouched = true;
    discussionValidate();
});


const submit_discussion = () => {

    let isValid = discussionValidate();
    if (isValid) {
       
        $.ajax({
            url: $('#form_url').val(),
            type: "POST",
            data: { 
                subject          : $('#subject').val(),
                description      : $('#description').val(),
                show_to_customer : $('#show_to_customer').val(),
                _token           : _token
             },
            success: function (response) {
                if (response.result) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message,
                        timer: 1500,
                    })
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                }
            },
            error: function (error) {
                if (error) {
                    if (error.responseJSON.error?.subject) {
                        $('.error_show_subject').addClass('invalid-feedback');
                        $(".error_show_subject").html(error.responseJSON.error.subject[0]);
                    }
                    if (error.responseJSON.error?.description) {
                        $('.error_show_description').addClass('invalid-feedback');
                        $(".error_show_description").html(error.responseJSON.error.description[0]);
                    }
                }
                if (error.responseJSON.message) {      
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message,
                    }) 
                };
            },
        });

        
    }


}
$(".ck_editor").length > 0 ? CKEDITOR.replaceAll("ck_editor") : "";

const showComments = (id) => {
    $('.dis-'+id).toggle('d-none');
    $('.dis-'+id).css('display', 'flow-root');
}
const commentReply = (id = null, form_url) => {
    $.ajax({
        url: form_url,
        type: "POST",
        data: { 
            comment          : CKEDITOR.instances[id ? 'comment-'+id : 'comment-'].getData(),
            comment_id       : id,
            _token           : _token
            },
        success: function (response) {
            if (response.result) {
                Toast.fire({
                    icon: 'success',
                    title: response.message,
                    timer: 1500,
                })
                setTimeout(function () {
                    window.location.reload();
                }, 1500);
            }
        },
        error: function (error) {
            console.log(error);
            if (error) {
                let err = id ? 'error-message-'+id : 'error-message-';
                console.log(err);
                console.log(error.responseJSON.errors?.comment[0]);
                if (error.responseJSON) {
                    $('.'+err).addClass('invalid-feedback');
                    $("."+err).html(error.responseJSON.errors.comment[0]);
                }
            }
            if (error.responseJSON.message) { 
                Toast.fire({
                    icon: 'error',
                    title: error.responseJSON.message,
                }) 
            };
        },
    });
}
// $('.summernote').summernote()

// files table show 


function fileTable() {
    let data = [];
    data["url"] = $("#file_table_url").val();
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
    data["table_id"] = "file_table_data";
    table(data);
}
$('#file_table_url').val() ? fileTable() : '';


// CKEDITOR.replace('content', {
//     filebrowserUploadUrl: '/upload',
//     filebrowserImageUploadUrl: '/upload',
//     filebrowserUploadMethod: 'form',
//     fileNameGenerator: function(originalFileName) {
//         var extension = originalFileName.substr(originalFileName.lastIndexOf('.') + 1);
//         if (extension === 'pdf') {
//             var newFileName = 'my_new_filename.pdf';
//             return newFileName;
//         } else {
//             return originalFileName;
//         }
//     }
// });

  






