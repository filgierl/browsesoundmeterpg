<?php
    include_once '../globalVariables.php';
   
    if(!session_start()){
        $ERROR_MSG = "Internal server error 500 ";
        error_log("can not start session", 0);
        require '../views/error_page.php';
    }else{
       if(isset($_GET["action"])){
           $action =  $_GET["action"];
           require_once '../routing.php';
           if(array_key_exists("{$action}",$routing)){
                $controller_name = $routing[$action];
                $url = "../views/{$conntroler_name}"+".php";
           }else
                $url = "../views/main_page.php";
                
           require $url;
       }else{
            $ERROR_MSG = "Bad request 400";
            error_log("Not action in get", 0);
            require '../views/error_page.php';
       }
        
     
    }
?>