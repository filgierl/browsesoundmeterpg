<?php
include_once '../bussines/function.php';
include_once '../bussines/db_connect.php';
include_once '../globalVariables.php';
 $db = DataBaseManager::getInstance();
 $mysqli = $db->getConnection(); 


 if(login_check($mysqli) == true){ 
                   echo 'you are still logged';
                }else{
                    echo ' you are logout';
}

 /* if (isset($_SESSION['user_id'], 
                        $_SESSION['username'], 
                        $_SESSION['login_string'])) {
 
        echo 'user id ' .$_SESSION['user_id'];
       echo 'login string '.$_SESSION['login_string'];
      echo 'username' . $_SESSION['username'];
echo ' time' .  $_SESSION["lifetime"];
*/

                        

?>	