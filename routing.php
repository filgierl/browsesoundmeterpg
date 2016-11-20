<?php
/* 
    Author     : Daniel
*/
$keys = array(ABOUT_ACTION, ACCOUNT_ACTION, HELP_ACTION, NOISE_ACTION,INSERT_ACTION,CHANGE_PASSWORD_ACTION,REMOVE_DEVICES_ACTION,REMOVE_MEASUREMENTS_ACTION);
$values = array("about","account", "help","noise","insert","change_password","remove_devices","remove_measurements");
$routing =  array_combine($keys, $values);

