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
    var date =  $('#date').val();
    var time = $("#time").val();
    
    var msgError = isTimeValid(time);
    if(msgError.length > 0){
        wrongData($("#time"),msgError);
        return;
    }
    else
        goodData($("#time"));
    
    msgError = isDataValid(date);
    if( msgError.length > 0){
        wrongData($("#date"),msgError );
       return;
    }
    else
        goodData($("#date"));
    
    var locationJSON = { "latitude" : location.lat().toFixed(4), 
                        "longitude"  : location.lng().toFixed(4), 
                        "date" : date,
                        "time" : time   };
    $.ajax({
        url:'/browsesoundmeterpg/web/noise',
        type:'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(locationJSON),
        success: function(data){
            handleResponse(location,data);
        }
    });
   
}

function handleResponse(location,data){
    if(data === "Too little data"){
        $("#error_msg_map").text(data);
        return;
    }
    $("#error_msg_map").text("");
    var noiseLevel = 0.0;
    var lat = location.lat().toFixed(4);
    var long = location.lng().toFixed(4);
    var weight = 0.0;
 
   noiseLevel = calculateNoiseLevel(data,lat,long);

    drawCircle(location,parseInt(noiseLevel));
}

function idw(x1,y1,x2,y2){
    var max = 100000000;
    var min = 1000;
    var d = getDistanceFromLatLonInKm(x1,y1,x2,y2);
    var wk = parseFloat((1/(Math.pow(d,4))).toFixed(6));
    wk = ((wk-min)/(max-min))*(1000-0)+0;
    wk = parseFloat(wk.toFixed(2));
    return wk;
}

function calculateNoiseLevel(data, baseLat, baseLong){
    var tmpNoise, tmpWeight; 
    var noiseLevel = 0.0 ;
    var weight= 0.0;
    for(var i=0;i<data.length;i++){
        if(data[i].latitude == baseLat && data[i].longitude == baseLong){
            tmpWeight = parseFloat(data[i].weight);
            tmpNoise = parseFloat(data[i].noiseLevel);
        }else{
            var tmp = calculateNoiseLevelInDifferentLocation(data[i].latitude, data[i].longitude, data, i, baseLat, baseLong);
            i = tmp['i'];
            tmpWeight = tmp["weight"];
            tmpNoise = tmp["noiseLevel"];
        }
        noiseLevel += tmpNoise * tmpWeight;
        weight += tmpWeight;
    }
    return noiseLevel /weight;
}

function calculateNoiseLevelInDifferentLocation(lat, long, data, i, baseLat, baseLong){
    var tmpNoise, tmpWeight; 
    var noiseLevel = 0.0 ;
    var weight= 0.0;
    for(;i<data.length;i++){
        if(data[i].latitude == lat && data[i].longitude == long){
            tmpWeight = parseFloat(data[i].weight);
            tmpNoise = parseFloat(data[i].noiseLevel);
        }else{
            break;
        }
        noiseLevel += tmpNoise * tmpWeight;
        weight += tmpWeight;
    }
    noiseLevel = noiseLevel /weight;
    noiseLevel = Math.round(noiseLevel);
    weight = idw(lat,long,baseLat,baseLong);
    return {'i': --i,
    "weight":weight,
    "noiseLevel":noiseLevel};
}

function getDistanceFromLatLonInKm(x1,y1,x2,y2) {
  //http://stackoverflow.com/questions/27928/calculate-distance-between-two-latitude-longitude-points-haversine-formula
  var R = 6371; // Radius of the earth in km
  var dLat = deg2rad(x2-x1);  // deg2rad below
  var dLon = deg2rad(y2-y1); 
  var a = 
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(x1)) * Math.cos(deg2rad(x2)) * 
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = R * c; 
  return d;
}

function deg2rad(deg) {
  return deg * (Math.PI/180);
}

function drawCircle(location,noiseLevel){
    var color = getColor(noiseLevel);
    var circle;
    if($('#color1').css("color") === color){
        circle = new google.maps.Circle({
        center:location,
        map: map,
        radius:11,
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
        radius:11,
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
      