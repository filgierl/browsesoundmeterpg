<?php
$keys = array($ABOUT_ACTION, $ACCOUNT_ACTION, $HELP_ACTION);
$values = array("about","account", "help" );
$routing =  array_combine($keys, $values);
print_r($routing);
?>
