
<?php 
/* 
    Created on : Oct 16, 2016, 4:45:23 PM
    Author     : Daniel
*/

$ERRORS = null;
$ERROR_MSG = "";
define("web_name","SoundMeterPG");
define("PAGE_URL","/browsesoundmeterpg/web");
define("ABOUT_ACTION","/about");
define("ACCOUNT_ACTION","/account");
define("HELP_ACTION","/help");
define("REGISTER_ACTION","/register");
define("PROCESS_LOGIN_ACTION","/process_login");
define("REGISTER_WEB_ACTION","/register_web");
define("NOISE_ACTION","/noise");

define("EMPTY_FORM","User send empty values in register POST");
define("FORM_WITHOUT_DATA","User send empty register POST");
define("INVALID_EMAIL","Email is not valid");
define("EMAIL_EXIST","Account with this email exist");
define("USER_EXIST","User with this name exist");


define("EMPTY_FORM_LOGIN","User send empty values in register POST");
define("FORM_WITHOUT_DATA_LOGIN","User send empty register POST");
define("BRUTE_FORCE","User login to many times :");
define("INVALID_PASSWORD","Password or user name is not correct");
define("USER_NOT_FOUND",INVALID_PASSWORD);