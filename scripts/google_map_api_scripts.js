/* 
    Created on : Oct 16, 2016, 9:50:54 PM
    Author     : Daniel
*/
var map;

function initMap() {
    var mapCanvas = document.getElementById("map");
    var mapOptions = {
      center: new google.maps.LatLng(54.352761,18.6127404),
      zoom: 17
    };
     map = new google.maps.Map(mapCanvas, mapOptions);

    google.maps.event.addDomListener(window, "resize", function() {
        var center = map.getCenter();
        google.maps.event.trigger(map, "resize");
        map.setCenter(center); 
    });

    google.maps.event.addListener(map, 'click', function(event) {
                addPoint(event.latLng);
    });

}
      
function addPoint(location) {
//    var marker = new google.maps.Marker({
//        position: location,
//        map: map,
//        icon: './icons/red.png'
//    });
    var circle = new google.maps.Circle({
        center:location,
        map: map,
        radius:10,
        strokeWeight: 0,
        fillColor:"#FF0000",
        fillOpacity:0.4
    });

    
    google.maps.event.addListener(circle, 'rightclick', function(event) {
        circle.setMap(null);
    });

}
      