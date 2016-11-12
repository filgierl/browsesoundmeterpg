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
        <link rel="stylesheet" type="text/css" href="./static_resources/css/register.css">
    </head>
    <body>
        <div class="container">  
            <?php include("../views/header.php") ?>
            <form id="form" action="register" method="post" name="registration_form" onsubmit="return validForm()">
                 
                <div class="form">
                    Sign up
                    <div class="inputs">
                        <label for="username">Username:</label><br>
                        <input class="input" type="text" name="username" id="username" /><br>
                        <label for="email">Email:</label><br>
                        <input class="input"  type="email" name="email" id="email" /><br>
                        <label for="password">Password:</label><br>
                        <input class="input"  type="password"name="password" id="password"/><br>
                        <label for="confirmpwd">Confirm password:</label><br>
                        <input class="input" type="password" name="confirmpwd" id="confirmpwd" /><br>
                        <div class="terms_and_privacy">
                            <input type="checkbox" name="terms_and_privacy" id="checkbox">
                            <label for="terms_and_privacy"><p id="terms_and_privacy">Do You agree to the SoundMeterPG  <br>
                                <a href="<?php echo PAGE_URL.ABOUT_ACTION ?>">Terms</a> and 
                                <a href="<?php echo PAGE_URL.ABOUT_ACTION ?>">Privacy</a>?</p>
                            </label>
                        </div>
                        
                        <input id="register_button" type="submit" value="Continue" />                       
                    </div>             
                </div>
                <p id="error_msg">Daj</p>
                <?php
                global $ERROR_MSG, $ERRORS;
                if($ERRORS === Errors::REGISTRATION_ERROR){
                    $ERRORS = 100;
                    echo "<p style=\"color:red;font-size:0.5em;display:block;\">".$ERROR_MSG."</p>";
                } ?>
            </form>  
        </div>
    </body>
</html>
<!--
onclick="return regformhash(this.form,
                                           this.form.username,
                                           this.form.email,
                                           this.form.password,
                                           this.form.confirmpwd);"-->