"use strict";

$(document).ready(function () {
    var url = $("#data_url").val();
    $.getJSON(url, function (mapD) {
        let mapData = mapD;
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
                });

                if (jQuery('#map').length > 0) {

                    let initialLocation = {
                        lat: mapData[0]?.latitude ?? 23.7947653,
                        lng: mapData[0]?.longitude ?? 90.4013282
                    }

                    map = new google.maps.Map(document.getElementById('map'), {
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        scrollwheel: true,
                        center: initialLocation,
                        zoom: 12
                    });
                    directionsDisplay.setMap(map);

                    var infowindow = new google.maps.InfoWindow();
                    var flightPlanCoordinates = [];
                    var bounds = new google.maps.LatLngBounds();

                    mapData.forEach((element, index) => {
                        var marker = new google.maps.Marker({
                            position: {
                                lat: parseFloat(element.latitude),
                                lng: parseFloat(element.longitude)
                            },
                            map,
                            label: `${element.start_location}`,
                            title: `${element.start_location}`,
                            optimized: false,
                        });
                        flightPlanCoordinates.push(marker.getPosition());
                        bounds.extend(marker.position);
                        google.maps.event.addListener(marker, 'click', (function (marker, i) {
                            return function () {
                                infowindow.setContent(`${element.start_location}`);
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
                            stopover: true,
                        });
                    }
                    calcRoute(start, end, waypts);
                }
            } catch (error) {}
        }

        function calcRoute(start, end, waypts) {
            try {
                var request = {
                    origin: start,
                    destination: end,
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    travelMode: google.maps.TravelMode.DRIVING,
                };
                directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                    }
                });
            } catch (error) {

            }
            var summaryPanel = document.getElementById('directions_panel');
            summaryPanel.innerHTML = mapD['distance'].toFixed(2) + ' km';
        }

        function initMap() {
            let initialLocation = {
                lat:  23.7947653,
                lng: 90.4013282
            };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: initialLocation,
            });
        }

        mapData[0] ?
            initialize() :
            initMap();



    })
});
