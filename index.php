<html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script type="text/javascript">
		
	$(document).ready(function(){
		$('#output').html("daj");
		$.ajax({  
			url: 'api.php',
			type: 'GET',
			dataType: 'json',
			
		   error: function() {
			  $('#output').html("error");
		   },		   
		   success: function(data) {
			  
			var lengthData = data.length;
			var text = "<ul>";
			for (i = 0; i < lengthData; i++) {
				text += "<li>" + data[i] + "</li>";
} 			text += "</ul>";
			  $('#output').html(text);
		   }  
		});
	});
</script>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Rectangles</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 80%;
      }
    </style>
  <script>

      // This example adds a red rectangle to a map.

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: {lat: 33.678, lng: -116.243},
          mapTypeId: google.maps.MapTypeId.TERRAIN
        });

        var rectangle = new google.maps.Rectangle({
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#FF0000',
          fillOpacity: 0.35,
          map: map,
          bounds: {
            north: 33.685,
            south: 33.671,
            east: -116.234,
            west: -116.251
          }
        });
      }
    </script>
  </head>
  <body>
    <div id="map"></div>
	<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIZ6lrh5AIwOz-Vaihe2hMN2kvFkbOp2o&callback=initMap">
    </script>
	<div id="output">this element will be accessed by jquery and this text replaced</div>
  </body>
</html>