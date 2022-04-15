/**
 * ref:https://medium.com/@limichelle21/integrating-google-maps-api-for-multiple-locations-a4329517977a
 */
function initMap() {
    initialize();
}

function initialize() {
    // $.each(locations, function (indexInArray, valueOfElement) {
    //     console.log(
    //         "🚀 ~ file: main.js ~ line 10 ~ valueOfElement",
    //         valueOfElement
    //     );
    // });

    let center = { lat: 45.424721, lng: -75.695 };
    let map = new google.maps.Map(document.getElementById("map"), {
        zoom: 9,
        center: center,
    });
    setMarkers(map);
}
/**
 * INIT MARKERS
 * ref:https://stackoverflow.com/questions/51073255/set-bounds-on-google-map-by-markers
 */
function setMarkers(map) {
    let infoWindow = new google.maps.InfoWindow({});
    let bounds = new google.maps.LatLngBounds();
    let marker, count;
    for (count = 0; count < locations.length; count++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(
                locations[count]["lat"],
                locations[count]["lng"]
            ),
            map: map,
            title: locations[count]["name"],
            /**
             * ref:https://developers.google.com/maps/documentation/javascript/markers#complex_icons
             */
            // icon: {
            //     url: locations[count][3],
            //     // This marker is 30 pixels wide by 40 pixels high.
            //     scaledSize: new google.maps.Size(30, 40),
            // },
        });
        /**
         * Set marker to bounds
         */
        bounds.extend(marker.getPosition());
        /**
         * Used to show marker info
         */
        google.maps.event.addListener(
            marker,
            "click",
            (function (marker, count) {
                return function () {
                    infoWindow.setContent(locations[count]["name"]);
                    infoWindow.open(map, marker);
                };
            })(marker, count)
        );
    }
    /**
     * auto-zoom
     */
    map.fitBounds(bounds);
}
