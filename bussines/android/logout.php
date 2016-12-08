<?php
include_once '../../globalVariables.php';
include_once '../db_connect.php';
include_once '../errors.php';
$logout_result = logout();

if( $logout_result === TRUE ){
echo "success";
}
else {
echo "fail";
}

?>

