
$(document).ready(function() {
  let latitude = $('#latitude').val() ?? 40.7127753;
  let longitude = $('#longitude').val() ?? -74.0059728;
  let distance = $('#distance').val() ?? 0;
  var map;
  var marker;
  var circle;

 function circleMap(){
    // remove old circle map
    if (circle) {
        circle.setMap(null);
    }
    //  radius_map = parseFloat( parseFloat(3.14159 * (parseFloat(distance) * parseFloat(distance))) / 1000 );
    circle = new google.maps.Circle({
        map: map,
        radius: parseFloat(distance),    // 10 miles in metres
        fillColor: '#36AA4A',
        strokeColor: '#36AA4A',
        strokeOpacity: 0.5,
        strokeWeight: 2,
        fillOpacity: 0.5
      });
      circle.bindTo('center', marker, 'position');
 }
    
  function mapInit(){
    defaultLatLong = {
        lat: parseFloat(latitude) ,
        lng: parseFloat(longitude) 
      };

             
       map = new google.maps.Map(document.getElementById('map'), {
        center: defaultLatLong,
        zoom: distance > 200 ? 12 : 16,
        mapTypeId: 'roadmap'
      });
      map.setOptions({
        scrollwheel: true, //
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: true,
        streetViewControl: true,
        rotateControl: true,
        fullscreenControl: true,
      });
      var input = document.getElementById('pac-input');
      
      var autocomplete = new google.maps.places.Autocomplete(input);
      
      autocomplete.bindTo('bounds', map);
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
      
       marker = new google.maps.Marker({
        map: map,
        position: defaultLatLong,
        draggable: true,
        clickable: true,
        animation: google.maps.Animation.DROP

      });
      circleMap();
      google.maps.event.addListener(marker, 'dragend', function(marker) {
        var latLng = marker.latLng;
        currentLatitude = latLng.lat();
        currentLongitude = latLng.lng();
        var latlng = {
          lat: currentLatitude,
          lng: currentLongitude
        };
        latitude = currentLatitude;
        longitude = currentLongitude;
        var geocoder = new google.maps.Geocoder;
        geocoder.geocode({
          'location': latlng
        }, function(results, status) {
          if (status === 'OK') {
            if (results[0]) {
              input.value = results[0].formatted_address;
              circleMap();
            } else {
              window.alert('No results found');
            }
          } else {
            window.alert('Geocoder failed due to: ' + status);
          }
        });
      });
      
      autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
          return;
        }
        if (place.geometry.viewport) {
          map.fitBounds(place.geometry.viewport);
        } else {
          map.setCenter(place.geometry.location);
        }
      
        marker.setPosition(place.geometry.location);
      
        currentLatitude = place.geometry.location.lat();
        currentLongitude = place.geometry.location.lng();
        latitude = currentLatitude;
        longitude = currentLongitude;  
        circleMap();
      });



   }


  locationPicker = (val,ur) => {
    $.get(ur +'?q='+val, function (data) {
        if (data == 'fail') {
            setTimeout(function () {
                toastr.error('Something went wrong!', 'Error!', {
                    timeOut: 2000
                });
            }, 500);
        } else {
            $(data).appendTo('body').modal({
                backdrop: 'static',
                keyboard: false
            });
            // map();

        }
    })

}
mapInit();

locationPickerStore = (ur) => {
    let is_submit = true;
    distance =  $('#distance').val();
    let status_id =  $('#status_id').val();
    let location = $('.location').val();
    if (distance == '' || distance == null || distance == undefined || distance == 0) {
        //error message
        $('#distance').next('.invalid-feedback').remove();
        $('#distance').addClass('is-invalid');
        is_submit = false;
    }
    if (status_id == '') {
        //error message
        $('.status_error').html('');
        is_submit = false;
    }

    if (location == '') {
        //error message
        $('.location').addClass('is-invalid');
        is_submit = false;
    }
    if(is_submit){
      $.ajax({
          url: ur,
          type: 'POST',
          data: {
              distance: distance,
              status: status_id,
              location: location,
              latitude: latitude,
              longitude: longitude,
              _token: $('meta[name="csrf-token"]').attr("content")
          },
          success: function (data) {
              if(data?.result == true){
                   toastr.success(data.message, "Success", () => {timeOut : 3000});
                    setTimeout(function () {
                      window.location.href = data?.data; 
                  }, 3000);
              }
          },
          error: function (data) {
             if (data?.responseJSON?.error?.distance) {            
              $('#distance').next('.invalid-feedback').remove();
              $('#distance').addClass('is-invalid');
              $('#distance').after('<div class="invalid-feedback">'+ data.responseJSON?.error?.distance+'</div>');
             }
            if (data?.responseJSON?.error?.status) {              
              $('.status_error').html('');
              $('.status_error').append('<div class="invalid-feedback">'+ data.responseJSON?.error?.status+'</div>');
            }
            if (data?.responseJSON?.error?.location) {
                $('.location').addClass('is-invalid');
            }
            if(data?.responseJSON?.message){
              toastr.error(data.responseJSON.message, "Error", {
                  timeOut: 2000
              });
            } else{
                toastr.error('Something went wrong!', 'Error!', {
                    timeOut: 3000
                });
            }
          }
      });
    }

}
$('#distance').on('keyup', function () {
    if ($(this).val() != '') {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    }
    distance =  $('#distance').val();
    circleMap();
   
});

$('.location').on('keyup', function () {
    if ($(this).val() != '') {
        $(this).removeClass('is-invalid');        
    }
});



});


  
  