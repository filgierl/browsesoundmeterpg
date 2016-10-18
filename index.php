<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php include 'globalVariables.php';?>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
       <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&dummy=.js"></script>   
        
        <title><?php echo $web_name ?></title>
        <link rel="stylesheet" type="text/css" href="./css/header.css">
        <link rel="stylesheet" type="text/css" href="./css/content.css">
        <script src="./scripts/index_scripts.js"></script>
        <script src="./scripts/google_map_api_scripts.js"></script>
    </head>
    <body>
      
        <header>
            
            <div id="banner">
                <h1 id="banner_text"><?php echo $web_name ?></h1>
            </div>
            
            <nav>
                <ul id="menu_list">
                    <li><a href="index.php"> <img src="./icons/home_button.png" alt="Home"></a></li>
                    <li><a href="index.php">Account</a></li>
                    <li><a href="index.php">About</a></li>
                    <li><a href="index.php">Help</a></li>
                </ul>
            </nav>
            
        </header>
        <article>
            <div id="date_form">
                <form >
                    <p class="date_form">Date</p>
                    <input type="date" name="date" min="17-10-2016" class="input_date" id="date"><br>
                    <p class="date_form">Time</p>
                    <input type="time" name="time" class="input_date" id="time"><br>
                    <p class="date_form" id="error_msg"></p>
                </form>
            </div>
           
            <div id="map" > 
                 
            </div>
        </article>
     
        
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIZ6lrh5AIwOz-Vaihe2hMN2kvFkbOp2o&callback=initMap">
        </script>
    </body>
</html>
