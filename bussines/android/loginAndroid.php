<?php
$var = process_login();
if($var === false){
echo "fail";
}
else {
echo 'success : ' . $_SESSION['username'];
}
?>
