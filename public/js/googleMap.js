$(document).ready(function() {
  let baseUrl = $('#url').val();
    var url = $("#data_url").val();
    $.getJSON(url, function(mapD) {


    let mapData = mapD['data'];
    var directionsDisplay;
    var directionsService = new google.maps.DirectionsService();
    var map;
   // Add a marker clusterer to manage the markers.


    function initialize() {
      try {

          directionsDisplay = new google.maps.DirectionsRenderer({
            dragable: true,
            map: map,
            title: google.maps.DirectionsRenderer.title,
            polylineOptions: {
                // strokeColor: "#0080ff",
                strokeColor: "red",
                strokeOpacity: 0.8,
                strokeWeight: 6,
              },
              markerOptions: {
                icon: "lol"
              }
          });

          var start = {
              url: baseUrl+ "/public/images/start.png", // url
              scaledSize: new google.maps.Size(30, 30), // scaled size
              origin: new google.maps.Point(0,0), // origin
              anchor: new google.maps.Point(0, 0) // anchor
          };
          var stop = {
            url: baseUrl+ "/public/images/end.png", // url
            scaledSize: new google.maps.Size(30, 30), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
        };

          if (jQuery('#map').length > 0) {

            map = new google.maps.Map(document.getElementById('map'), {
              mapTypeId: google.maps.MapTypeId.ROADMAP,
              scrollwheel: true,
              center: {lat: 23.7651, lng: 90.4251},
              zoom: 1
            });
            directionsDisplay.setMap(map);

            var infowindow = new google.maps.InfoWindow();
            var flightPlanCoordinates = [];
            var bounds = new google.maps.LatLngBounds();

            mapData.forEach((element,index) => {
                     if (index == 0) {
                      marker = new google.maps.Marker({
                        position: new google.maps.LatLng(element.latitude, element.longitude),
                        map: map,
                        icon: start,
                      });
                     }
                     else if (index == mapData.length - 1) {
                          marker = new google.maps.Marker({
                            position: new google.maps.LatLng(element.latitude, element.longitude),
                            map: map,
                            icon: stop
                          });
                     }else{
                       marker = new google.maps.Marker({
                         position: new google.maps.LatLng(element.latitude, element.longitude),
                       });

                     }
                    flightPlanCoordinates.push(marker.getPosition());
                    bounds.extend(marker.position);

                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                      return function() {
                        infowindow.setContent(element.start_location);
                        infowindow.open(map, marker);
                      }
                    })(marker, i));

              })

            map.fitBounds(bounds);

            // directions service configuration
            var start = flightPlanCoordinates[0];
            var end = flightPlanCoordinates[flightPlanCoordinates.length - 1];
            var waypts = [];
            for (var i = 1; i < flightPlanCoordinates.length - 1; i++) {
              waypts.push({
                location: flightPlanCoordinates[i],
                stopover: true
              });
            }
            calcRoute(start, end, waypts);
          }
        } catch (error) {
        }
    }

    function calcRoute(start, end, waypts) {
      try{
          var request = {
            origin: start,
            destination: end,
            waypoints: waypts,
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING,
          };
          directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(response);
              // var route = response.routes[0];
              // var summaryPanel = document.getElementById('directions_panel');
              // summaryPanel.innerHTML = '';
              // For each route, display summary information.
              // for (var i = 0; i < route.legs.length; i++) {
              //   var routeSegment = i + 1;
              //   summaryPanel.innerHTML += '<b>Route : '+routeSegment+' </b><br>';
              //   summaryPanel.innerHTML += route.legs[i].start_address + ' <span class="text-red" style="font-size:20px" > to </span>';
              //   summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
              //   summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
              // }
            }
          });
        } catch (error) {

        }
    }
    initialize();
    })
});

