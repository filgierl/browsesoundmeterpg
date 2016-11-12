<?php
// source http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL 
//     license https://creativecommons.org/licenses/by-nc-sa/3.0/
include_once '../globalVariables.php';
include_once '../globalVariables.php';
include_once '../bussines/db_connect.php';
include_once '../bussines/errors.php';

function errorlog($MSG, $level){
  $db = DataBaseManager::getInstance();
  $mysqli = $db->getConnection();
  if ($stmt = $mysqli->prepare("INSERT INTO LOGS (LOG_TEXT) VALUES (?)") ){
      $stmt->bind_param('s', $MSG);
      $stmt->execute();
  }
}

function handleError($ERROR, $ERROR_NUMBER){
    /* 
        Author     : Daniel
    */
    global $ERROR_MSG;
    switch ($ERROR_NUMBER){
        case Errors::REGISTRATION_ERROR:
            $ERROR_MSG = $ERROR;
            require '../views/register.php';
            break;
        case Errors::DATABASE_ERROR:
            errorlog($ERROR, 0);
            $ERROR_MSG = "Error 500";
            require '../views/error.php';
            break;
        case Errors::UNKNOWN_ERROR:
            errorlog($ERROR, 0);
            $ERROR_MSG = "Error 500";
            require '../views/error.php';
            break;
        case Errors::SESSION_ERROR:
            errorlog($ERROR, 0);
            $ERROR_MSG = "Error 500";
            require '../views/error.php';
            break;
        case Errors::REQEST_ERROR:
            error_log($ERROR, 0);
            $ERROR_MSG = "Error 500";
            require '../views/error.php';
            break;
        case Errors::LOGIN_ERROR:
            $ERROR_MSG = $ERROR;
            require '../views/account.php';
            break;
        case Errors::BRUTE_FORCE:
            errorlog($ERROR, 0);
            $ERROR_MSG = "Your account is block because you pass to many times incorrect password";
            require '../views/account.php';
            break;
        default:
            $ERROR_MSG = "Error 500";
            errorlog("Error msg ".$ERROR." error code".$ERROR_NUMBER, 0);
            require '../views/error.php';
            break;
    }

}
 
function sec_session_start() {
    global $ERRORS, $ERROR_MSG;
    $session_name = 'sec_session_id';   
    session_name($session_name);
 
    $secure = false; //TODO This stops JavaScript being able to access the session id.
    $httponly = true;
    
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        $ERRORS = Errors::SESSION_ERROR;
        $ERROR_MSG = "Can not use only cookies session";
        return false;
    }
    
    $cookieParams = session_get_cookie_params();
    $cookieParams["lifetime"] = 60*60*24; 
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
 
    $session = session_start();            
    $session = $session && session_regenerate_id(true); 
    if($session)
        return $session;
    else{
        $ERRORS = Errors::SESSION_ERROR;
        $ERROR_MSG = "Can not use start session";
    }
}

function process_login(){
    global $ERROR_MSG,$ERRORS;
    if (isset($_POST['email'], $_POST['password'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        if(!($email == null || $email === "" || $password == null || $password === "")){

            $db = DataBaseManager::getInstance();
            $mysqli = $db->getConnection();
            return login($email, $password, $mysqli);

        }else{
            $ERRORS = Errors::UNKNOWN_ERROR;
            $ERROR_MSG  = FORM_WITHOUT_DATA_LOGIN;
            return false;
        }
    } else {
        $ERRORS = Errors::UNKNOWN_ERROR;
        $ERROR_MSG  = EMPTY_FORM_LOGIN;
        return false;
    }
}

function login($email, $password, $mysqli) {
    global $ERROR_MSG,$ERRORS;
    if ($stmt = $mysqli->prepare("SELECT ID, USERNAME, PASSWORD
        FROM DBO_SMPG_USER
        WHERE EMAIL = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $email); 
        $stmt->execute();    
        $stmt->store_result();
        $stmt->bind_result($user_id, $username, $db_password);
        $stmt->fetch();
        if ($stmt->num_rows == 1) {
            
            if (checkbrute($user_id, $mysqli) == true) {
                $ERRORS = Errors::BRUTE_FORCE;
                $ERROR_MSG  = BRUTE_FORCE.$user_id;
                return false;
            } else {
                if (password_verify($password, $db_password)) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                "", 
                                                                $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', $db_password . $user_browser);
                 
                    return true;
                } else {
                    $now = time();
                    $mysqli->query("INSERT INTO DBO_SMPG_LOGIN_ATTEMPTS(USERID, TIME)
                                    VALUES ('$user_id', '$now')");
                    $ERRORS = Errors::LOGIN_ERROR;
                    $ERROR_MSG  = INVALID_PASSWORD;
                    return false;
                }
            }
        }else{
            $ERRORS = Errors::LOGIN_ERROR;
            $ERROR_MSG  = USER_NOT_FOUND;
            return false;
        }
    }else{
        $ERRORS = Errors::DATABASE_ERROR;
        $ERROR_MSG  = "Can not prepare select user";
        return false;
    }
}

function checkbrute($user_id, $mysqli) {
    $now = time();
 
    $valid_attempts = $now - (2 * 60 * 60);
 
    if ($stmt = $mysqli->prepare("SELECT TIME
                             FROM DBO_SMPG_LOGIN_ATTEMPTS
                             WHERE USERID = ? 
                             AND TIME > ?")) {
        $stmt->bind_param('ii', $user_id,$valid_attempts);
 
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

function login_check($mysqli) {
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], 
                        $_SESSION['username'], 
                        $_SESSION['login_string'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT PASSWORD
                                      FROM DBO_SMPG_USER
                                      WHERE ID = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if (hash_equals($login_check, $login_string) ){ 
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function register(){
    global $ERRORS, $ERROR_MSG;
    if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
           $ERRORS = Errors::REGISTRATION_ERROR;
           $ERROR_MSG = INVALID_EMAIL;
           return false;
        }
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
         if( $username == null || $username === '' || $email == null || $email === '' 
                || $password == null || $password === '' ){
            $ERRORS = Errors::UNKNOWN_ERROR;
            $ERROR_MSG = EMPTY_FORM;
            return false;
         }
         
         if( strlen($username) <  6 || strlen($username) > 20){
            $ERRORS = Errors::REGISTRATION_ERROR;
            $ERROR_MSG = "Username should be at least 5 to 20 characters long";
            return false;
         }
         
         if( strlen($password) <  6 ){
            $ERRORS = Errors::REGISTRATION_ERROR;
            $ERROR_MSG = "Password should be at least 6  characters long";
            return false;
         }
         
        
        $db = DataBaseManager::getInstance();
        $mysqli = $db->getConnection();

        $prep_stmt = "SELECT USERNAME FROM DBO_SMPG_USER WHERE EMAIL = ? LIMIT 1";
        $stmt = $mysqli->prepare($prep_stmt);
  
        if($stmt){
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows == 1){
                $ERRORS = Errors::REGISTRATION_ERROR;
                $ERROR_MSG = EMAIL_EXIST;
                return false;
            }
        }else{
            $ERRORS = Errors::DATABASE_ERROR;
            $ERROR_MSG = "Can not prepare select in register-where email = ?";
            return false;
        }

        $prep_stmt = "SELECT * FROM DBO_SMPG_USER WHERE USERNAME = ? LIMIT 1";
        $stmt = $mysqli->prepare($prep_stmt);

        if($stmt){
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows == 1) {
                $ERRORS = Errors::REGISTRATION_ERROR;
                $ERROR_MSG = USER_EXIST;
                return false;
            }
        }else{
            $ERRORS = Errors::DATABASE_ERROR;
            $ERROR_MSG = "Can not prepare select in register-where username = ?";
            return false;
        }
    
        $options = array( 'cost' => 13);
           
        $password = password_hash($password, PASSWORD_BCRYPT, $options);

        if ($insert_stmt = $mysqli->prepare("INSERT INTO DBO_SMPG_USER(USERNAME, EMAIL, PASSWORD) VALUES (?, ?, ?)")) {
            $insert_stmt->bind_param('sss', $username, $email, $password);
            // Execute the prepared query.
            if (!$insert_stmt->execute()) {
                $ERRORS = Errors::DATABASE_ERROR;
                $ERROR_MSG = "Can not execute stmt".$insert_stmt;
                return false;
            }else{
                return true;
            }
        }else{
            $ERRORS = Errors::DATABASE_ERROR;
            $ERROR_MSG = "Can not prepare insert user";
            return false;
        }

    }else{
        $ERRORS = Errors::UNKNOWN_ERROR;
        $ERROR_MSG = FORM_WITHOUT_DATA;
        return false;
    }
}
        
