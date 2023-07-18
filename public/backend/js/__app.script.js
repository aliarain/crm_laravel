"use strict";

$(document).ready(function () {
  $('.buttons-colvis').html('<i class="fas fa-columns" style="color:#27a580"></i>');
  $('.buttons-colvis').css('background-color', 'rgba(60, 210, 164,.5) !important');
});


$(document).ready(function () {
  function currentTime() {
    let date = new Date();
    let hh = date.getHours();
    let mm = date.getMinutes();
    let ss = date.getSeconds();
    let session = "AM";

    if (hh === 0) {
      hh = 12;
    }
    if (hh == 12) {
      session = "PM";
    }
    if (hh > 12) {
      hh = hh - 12;
      session = "PM";
    }

    hh = (hh < 10) ? "0" + hh : hh;
    mm = (mm < 10) ? "0" + mm : mm;
    ss = (ss < 10) ? "0" + ss : ss;

    let time = hh + ":" + mm + ":" + ss + " " + session;
    $('.clock').html(time);
    // document.getElementById("clock").innerText = time;
    let t = setTimeout(function () { currentTime() }, 1000);
  }
  currentTime();
});


function searchMenu() {
  let value = $('#search_field').val();
  // ajax 
  $.ajax({
    url: '/dashboard/search',
    type: 'POST',
    data: { search: value },
    success: function (data) {
      $('#autoCompleteData').removeClass('d-none');
      $('#autoCompleteData').addClass('d-block');
      let str = ``;
      if (data.length > 0) {
        $.each(data, function (index, value) {

          str += `
                <li>
                    <a class="suggestion_link" href="${value.route_name}">${value.title}</a>
                </li>
                `;
        });
      } else {
        str += `
                <li>
                    <a class="suggestion_link" href="javascript:void(0)">No Item found !</a>
                </li>
                `;
      }

      $('.search_suggestion').html(str);
    }
  });
}


function getLeadDetails(type) {

  $('._lead_nav').removeClass('active');
  $('._' + type).addClass('active');
  let base_url = $('#base-url').attr('content');
  let ajax_url = base_url + '/admin/leads/ajax/details';
  let data = {
    ajax_url: ajax_url,
    lead_id: $('#lead_id').val(),
    _token: $('meta[name="csrf-token"]').attr('content'),
    type: type
  };

  $.ajax({
    url: ajax_url,
    type: 'POST',
    data: data,
    success: function (viewAsResponse) {
      $('#__lead_content').empty();
      $('#__lead_content').html(viewAsResponse);
    },
    error: function (error) {
    }
  });
}

if ($('#__lead_content').length > 0) {
  getLeadDetails('Profile');
}

// leadDetailsStore 
function leadDetailsStore(type) {
  let base_url = $('#base-url').attr('content');
  let ajax_url = base_url + '/admin/leads/ajax/details/store';

  let form_data = new FormData($('#form_' + type)[0]);

  if (type == 'Profile') {
    let first_name = $('#first_name').val();
    let last_name = $('#last_name').val();
  } else if (type == 'notes') {
    let body = $('#body').val();
    if (body.trim() === '') {
      toastr.error('The "note" field is required.', 'Validation Error');
      return false;
    }
  } else if (type == 'tasks') {
    let subject = $('#subject').val();
    let message = $('#message').val();
    if (subject.trim() === '') {
      toastr.error('The "subject" field is required.', 'Validation Error');
      return false;
    }
    if (message.trim() === '') {
      toastr.error('The "message" field is required.', 'Validation Error');
      return false;
    }
  } else if (type == 'tags') {
    let name = $('#name').val();
    if (name.trim() === '') {
      toastr.error('The "name" field is required.', 'Validation Error');
      return false;
    }
  } else if (type == 'attachments') {

    // if title empty 
    let title = $('#title').val();
    if (title.trim() === '') {
      toastr.error('The "title" field is required.', 'Validation Error');
      return false;
    }

    let fileInput = $('#fileBrouse')[0];
    let file = fileInput.files[0];
    if (!file) {
      toastr.error('Please select a file to upload.', 'Validation Error');
      return false;
    }


    // Check file extension
    let allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx', 'txt', 'csv'];
    let fileExtension = file.name.split('.').pop().toLowerCase();
    if (!allowedExtensions.includes(fileExtension)) {
      toastr.error('Only files with extensions: ' + allowedExtensions.join(', ') + ' are allowed.', 'Validation Error');
      return false;
    }


    form_data.append('file', file);
  } else if (type == 'emails') {
    let subject = $('#subject').val();
    let message = $('#message').val();
    let to_email = $('#to_email').val();
    if (to_email.trim() === '') {
      toastr.error('The "to email" field is required.', 'Validation Error');
      return false;
    }
    // Regular expression to match email format
    let email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email_regex.test(to_email)) {
      toastr.error('Please enter a valid email address.', 'Validation Error');
      return false;
    }


    if (subject.trim() === '') {
      toastr.error('The "subject" field is required.', 'Validation Error');
      return false;
    }
    if (message.trim() === '') {
      toastr.error('The "message" field is required.', 'Validation Error');
      return false;
    }


  }

  $.ajax({
    url: ajax_url,
    type: 'POST',
    data: form_data,
    contentType: false,
    processData: false,
    success: function (viewAsResponse) {
      getLeadDetails(type);
    },
    error: function (error) {
    }
  });
}



function downloadOrShowFile(path) {
  var extension = path.substr(path.lastIndexOf('.') + 1).toLowerCase();
  if (extension == 'pdf' || extension == 'docx' || extension == 'txt' || extension == 'csv' || extension == 'xlsx' || extension == "html") {
    // Download the file
    // window.location.href = path;
    window.open(path, '_blank');
  } else {
    // Show the image
    var image = new Image();
    image.src = path;
    var w = window.open("");
    w.document.write(image.outerHTML);
  }
}


function thisFeatureIsNotIntegratedYet() {
  toastr.error('This Filtering is not integrated yet', 'Error');
}

/**
 * Updates the current table with the number of entries specified in the entries select box.
 */
function updateCurrentTableWithNumber(path) {
  let base_url = $('#base-url').attr('content');
  let currentUrl = base_url + '/' + path + '/';
  // Get the current value of the entries select box, default to 10 if not set.
  let currentValue = $('#entries').val() ?? 10;
  let searchInput = $('#searchInput').val() ?? '';



  // Construct the new URL with the specified number of entries.
  let reloadUrl = currentUrl + '?entries=' + currentValue + '&search=' + searchInput;

  // Redirect to the new URL.
  console.log(reloadUrl);
  console.log(path);
  window.location.href = reloadUrl;
}


function exportCSV(path){
  let base_url = $('#base-url').attr('content');
  let currentUrl = base_url + '/' + path + '/';
  // Get the current value of the entries select box, default to 10 if not set.
  let currentValue = $('#entries').val() ?? 10;
  let searchInput = $('#searchInput').val() ?? '';
  let export_type = 'csv';

  let reloadUrl = currentUrl + '?entries=' + currentValue + '&search=' + searchInput + '&export_type=' + export_type;
  window.location.href = reloadUrl;
 

}


