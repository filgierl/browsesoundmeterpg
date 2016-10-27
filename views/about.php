<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
       <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&dummy=.js"></script>   
        
        <title><?php echo $web_name ?></title>
        <link rel="stylesheet" type="text/css" href="./static_resources/css/header.css">
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
        About
          </div>
    </body>
</html>

