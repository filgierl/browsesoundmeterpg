<?php
    /* 
        Author     : Daniel
    */
    include_once '../globalVariables.php';
    include_once '../bussines/function.php';
    include_once '../bussines/db_connect.php';
    include_once '../bussines/errors.php';
 
    //TODO uncomment on https server
//    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
//        if(!headers_sent()) {
//            header("Status: 301 Moved Permanently");
//            header(sprintf(
//                'Location: https://%s%s',
//                $_SERVER['HTTP_HOST'],
//                $_SERVER['REQUEST_URI']
//            ));
//            exit();
//        }
//    }
    
    if(!sec_session_start()){
        handleError($ERROR_MSG,$ERRORS);
    }else{ 
       checkURLAndGetNextRedirect();
    }
    
    
    function checkURLAndGetNextRedirect(){
        $host = $_SERVER['HTTP_HOST'];
        $path = $_SERVER['SCRIPT_NAME'];
        $queryString = $_SERVER['QUERY_STRING'];
        if($queryString === "")
            $queryString="action=/action";
        $url = "http://" . $host . $path . "?" . $queryString;
        if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED) === false){
            checkGetAndRedicrect();
        }else{
            handleError("The request has bad syntax url:{$url}", Errors::REQEST_ERROR);
        }
    }
    
    function checkGetAndRedicrect(){
        if(isset($_GET["action"]) && filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING)){
              redirect();
          }else{
               $url = "../views/main_page.php";
               require $url;
          } 
    }
    
    function redirect(){
        global $ERROR_MSG, $ERRORS;
        $action =  $_GET["action"];
        require_once '../routing.php';
        if(array_key_exists("{$action}",$routing)){
             $controller_name = $routing[$action];
             $url = "../views/{$controller_name}.php";
        }else if($action === PROCESS_LOGIN_ACTION){
            if(process_login()){
                $url = "../views/account.php";
            }else{
                handleError($ERROR_MSG, $ERRORS);
                exit();
            }  
        }else if($action === REGISTER_WEB_ACTION){
            $db = DataBaseManager::getInstance();
            $mysqli = $db->getConnection(); 
            if(login_check($mysqli) == true){ 
                $url = "../views/account.php";
            }else{
                $url = "../views/register.php";
            }
        }else if($action === REGISTER_ACTION){
            if(register()){
                $url = "../views/account.php";
            }else{
                handleError($ERROR_MSG, $ERRORS);
                exit();
            }
        }else if( $action === LOGIN_ANDROID){
            $url = "../bussines/android/loginAndroid.php";
        }else if( $action === CHECK_SESSION_ANDROID){
            $url = "../bussines/android/checkSessionAndroid.php";
        }else if($action === LOGOUT_ACTION){
            if(logout()){
                $url = "../views/account.php";
            }else{
                handleError($ERROR_MSG, $ERRORS);
                exit();
            }
        }else if( $action === LOGOUT_ANDROID_ACTION){
           $url = "../bussines/android/logout.php";
         }else if($action === CHANGE_PASSWORD_ACTION_POST){
             if(change_password()){
                $url = "../views/account.php";
            }else{
                handleError($ERROR_MSG, $ERRORS);
                exit();
            }
         }else{
            $url = "../views/main_page.php";
        }
        require $url;
    }
    
    
?>