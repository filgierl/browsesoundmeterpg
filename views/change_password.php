<?php
include_once '../bussines/function.php';
include_once '../globalVariables.php';
?>
<!--    Author     : Daniel-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="./static_resources/scripts/form.js"></script>
        <title><?php echo web_name ?></title>
        <link rel="stylesheet" type="text/css" href="./static_resources/css/header.css">
        <link rel="stylesheet" type="text/css" href="./static_resources/css/change_password.css">
    </head>
    <body>
        <div class="container">  
            <?php include("../views/header.php") ?>
            <form id="form" action="change_password_post" method="post" name="registration_form" onsubmit="return validChangeForm()">
                 
                <div class="form">
                    Change password
                    <div class="inputs">
                        <label for="old_password">Old Password:</label><br>
                        <input class="input"  type="password" name="old_password" id="old_password"/><br>
                        <label for="password">New Password:</label><br>
                        <input class="input"  type="password" name="password" id="password"/><br>
                        <label for="confirmpwd">Confirm password:</label><br>
                        <input class="input" type="password" name="confirmpwd" id="confirmpwd" /><br>
 
                        <input id="change_button" type="submit" value="Change" />                       
                    </div>             
                </div>
                <p id="error_msg">Daj</p>
                <?php
                global $ERROR_MSG, $ERRORS;
                if($ERRORS === Errors::CHANGE_PASSWORD_ERROR){
                    $ERRORS = 100;
                    echo "<p style=\"color:red;font-size:0.5em;display:block;\">".$ERROR_MSG."</p>";
                } ?>
            </form>  
        </div>
    </body>
</html>