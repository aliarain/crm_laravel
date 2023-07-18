"use strict";

$(document).ready(function () {
    var url = $("#data_url").val();
    $.getJSON(url, function (mapD) {
        let mapData = mapD;
        function initMap() {
            let initialLocation = {
                lat:    parseFloat(mapData[0]?.location_log?.latitude) ?? 23.7947653,
                lng:    parseFloat(mapData[0]?.location_log?.longitude) ?? 90.4013282
            };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: initialLocation,
            });
            const infoWindow = new google.maps.InfoWindow();
            mapData.forEach((element, index) => {
                const marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(element.location_log.latitude),
                        lng: parseFloat(element.location_log.longitude)
                    },
                    map,
                    label: `${element.name}`,
                    title: `${element.location_log.address}`,
                    optimized: false,
                });

                // Add a click listener for each marker, and set up the info window.
                marker.addListener("click", () => {
                    infoWindow.close();
                    infoWindow.setContent(marker.getTitle());
                    infoWindow.open(marker.getMap(), marker);
                });
            });
        }

        initMap();
    });
});
