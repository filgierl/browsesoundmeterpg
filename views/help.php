<!--    Author     : Daniel-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
 
        
        <title><?php echo web_name ?></title>
        <link rel="stylesheet" type="text/css" href="./static_resources/css/header.css">
    </head>
    <body>
          <div class="container">  
            <?php include("../views/header.php") ?>
            <h2> Add point to map</h2>
              First set time and date in left panel site - pointed by red number 3.
              <br>
              Next find your localization. Press left button and move mouse to set position of map.
              <br>
              Finally to check noise level click left mouse button.
              <br>
              Site draw on map colorful point. To check value of noise level look up legend.
              <br>
              To remove circle from map, right click on it.
              <img>
              <img id="maps_help" src="./static_resources/icons/mapa1.png"/>
          </div>
    </body>
</html>

