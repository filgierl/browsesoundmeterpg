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
    var locationJSON = { "latitude" : location.lat(), "longitude"  : location.lng() };
    $.ajax({
        url:'/browsesoundmeterpg/web/noise',
        type:'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(locationJSON),
        success: function(data){
            handleResponse(location,data);
        },
        failure: function(errMsg) {
             //TODO
        }
    });
   
}

function handleResponse(location,data){
    var noiseLevel = 0.0;
    var deviation = 0;
    var weight = 0.0;
    var  probability = [];
    for(var i=0;i<141;i++)
        probability[i] = 0;

    for(var i=0;i<data.length;i++){
        noiseLevel += parseInt(data[i].noiseLevel) * parseFloat(data[i].weight);
        weight += parseFloat(data[i].weight);
    }
    noiseLevel = noiseLevel /weight;
    for(var i=0;i<data.length;i++){
        deviation += Math.abs(parseInt(data[i].noiseLevel) - parseInt(noiseLevel));
        probability[parseInt(data[i].noiseLevel)] += 1;
    }
    deviation = parseInt(deviation/data.length);
    var prob = probability[parseInt(noiseLevel)]/data.length;
    drawCircle(location,parseInt(noiseLevel));
}

function drawCircle(location,noiseLevel){
    var color = getColor(noiseLevel);
    var circle;
    if($('#color1').css("color") === color){
        circle = new google.maps.Circle({
        center:location,
        map: map,
        radius:10,
        strokeWeight: 1,
        strokeColor: '#000000',
        strokeOpacity: 0.4,
        fillColor:color,
        fillOpacity:0.4
        });
    }else{
        circle = new google.maps.Circle({
        center:location,
        map: map,
        radius:10,
        strokeWeight: 0,
        fillColor:color,
        fillOpacity:0.4
        });
    }   
    google.maps.event.addListener(circle, 'rightclick', function(event) {
        circle.setMap(null);
    });
}

function getColor(noiseLevel){
    if(noiseLevel >= 100){
        return $('#color10').css("color");
    }else if(noiseLevel >= 90 && noiseLevel < 100){
        return $('#color9').css("color");
    }else if(noiseLevel >= 80 && noiseLevel < 90){
        return $('#color8').css("color");
    }else if(noiseLevel >= 70 && noiseLevel < 80){
        return $('#color7').css("color");
    }else if(noiseLevel >= 60 && noiseLevel < 70){
        return $('#color6').css("color");
    }else if(noiseLevel >= 50 && noiseLevel < 60){
        return $('#color5').css("color");
    }else if(noiseLevel >= 40 && noiseLevel < 50){
        return $('#color4').css("color");
    }else if(noiseLevel >= 30 && noiseLevel < 40){
        return $('#color3').css("color");
    }else if(noiseLevel >= 20 && noiseLevel < 30){
        return $('#color2').css("color");
    }else if(noiseLevel < 20){
        return $('#color1').css("color");
    }
}
      