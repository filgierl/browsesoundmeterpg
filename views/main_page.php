<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
       
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
       <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&dummy=.js"></script>   
        
        <title><?php echo $web_name ?></title>
        <link rel="stylesheet" type="text/css" href="./static_resources/css/header.css">
        <link rel="stylesheet" type="text/css" href="./static_resources/css/content.css">
        <script src="./static_resources/scripts/index_scripts.js"></script>
        <script src="./static_resources/scripts/google_map_api_scripts.js"></script>
    </head>
    <body>
        <div class="container">  
            <header>

                <div id="banner">
                    <h1 id="banner_text"><?php echo $web_name ?></h1>
                </div>

                <nav>

                    <div id="menu_list"><a href="<?php echo $PAGE_URL?>"> <img src="./static_resources/icons/home_button.png" alt="Home"></a>
              </div><div id="menu_list"><a href="<?php echo "{$PAGE_URL}{$ACCOUNT_ACTION}" ?>">Account</a>
              </div><div id="menu_list"><a href="<?php echo "{$PAGE_URL}{$ABOUT_ACTION}" ?>">About</a> 
              </div><div id="menu_list"><a href="<?php echo "{$PAGE_URL}{$HELP_ACTION}" ?>">Help</a></div>                          
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
                    <br>
                    <p id="p_legend">Legend(db)</p>
                    <ul id="legend">
                        <li id="color10"> > 100</li>
                        <li id="color9">90-99</li>
                        <li id="color8">80-89</li>
                        <li id="color7">70-79</li>
                        <li id="color6">60-69</li>
                        <li id="color5">50-59</li>
                        <li id="color4">40-49</li>
                        <li id="color3">30-39</li>
                        <li id="color2">20-29</li>
                        <li > <p id="color1">< 20</p></li>
                    </ul>
                </div>


                <div id="map" > 

                </div>
            </article>
        </div>
        
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIZ6lrh5AIwOz-Vaihe2hMN2kvFkbOp2o&callback=initMap">
        </script>
    </body>
</html>

