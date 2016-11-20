<?php
include_once '../bussines/function.php';
include_once '../bussines/db_connect.php';
include_once '../globalVariables.php';
?>
<!--    Author     : Daniel-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="./static_resources/scripts/account_information.js"></script>
        <title><?php echo web_name ?></title>
        <link rel="stylesheet" type="text/css" href="./static_resources/css/header.css">
        <link rel="stylesheet" type="text/css" href="./static_resources/css/login.css">
        <link rel="stylesheet" type="text/css" href="./static_resources/css/account_information.css">
        
        
    </head>
    <body>
          <div class="container">  
            <?php include("../views/header.php") ?>
            <?php
                $db = DataBaseManager::getInstance();
                $mysqli = $db->getConnection(); 
                if(login_check($mysqli) == true){ 
                    require 'account_information.php';
                }else{
                    require 'login.php';
                    
                    global $ERROR_MSG, $ERRORS;
                    if($ERRORS === Errors::LOGIN_ERROR || $ERRORS === Errors::BRUTE_FORCE){
                        $ERRORS = 100;
                        echo "<p style=\"color:red;font-size:0.5em;display:block;text-align:center;\">".$ERROR_MSG."</p>";
                    } 
                }                
            ?>
            
          </div>
    </body>
</html>

