"use strict";

$(document).ready(function () {

    function Error(error) {
        var errorCode = error.code;
        var message = error.message;
        $('#check_in_latitude').val(23.7909811);
        $('#check_in_longitude').val(90.4067015);
    }
    
    function LatLng( position = null){
        if(position){
            $('#check_in_latitude').val(position.coords.latitude);
            $('#check_in_longitude').val(position.coords.longitude);
        }
    }

let locationGet = () => {
    if (navigator?.geolocation) {
            navigator.geolocation.getCurrentPosition(LatLng, Error, {timeout:10000});
      } else { 
         console.log("Geolocation is not supported by this browser.");
      }     
}

locationGet();


});