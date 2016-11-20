<?php
include_once '../bussines/validateDate.php';
include_once '../bussines/function.php';   
if(isset($_POST['Max']) && isset($_POST['Min']) && isset($_POST['Weight']) &&
    isset($_POST['AvgNoise']) && isset($_POST['Latitude']) && isset($_POST['Longitude']) &&
    isset($_POST['Date']) && isset($_POST['UserID']) && isset($_POST['DeviceID'])){
    $min = filter_input(INPUT_POST, 'Min', FILTER_SANITIZE_NUMBER_INT);
    $max = filter_input(INPUT_POST, 'Max', FILTER_SANITIZE_NUMBER_INT);
    $avg = filter_input(INPUT_POST, 'AvgNoise', FILTER_SANITIZE_NUMBER_INT);
    $weight = filter_input(INPUT_POST, 'Weight', FILTER_SANITIZE_NUMBER_INT);
    $latitude = filter_var($_POST['Latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $longitude = filter_var($_POST['Longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $date = filter_input(INPUT_POST, 'Date', FILTER_SANITIZE_STRING);
    $userID =  filter_input(INPUT_POST, 'UserID', FILTER_SANITIZE_STRING);
    $deviceID = filter_input(INPUT_POST, 'DeviceID', FILTER_SANITIZE_STRING);    

    if(!isset($min) || !isset($max) || !isset($avg) ||
      !isset($latitude) || !isset($longitude) || !isset($date) ||
      !isset($userID) || !isset($weight) || !isset($deviceID)){
       exit();
    }
    
    $tmp_date = substr($date,0,strpos($date," "));
    $tmp_time = substr($date,strpos($date," ")+1,strlen($date)-strpos($date," "));
    if(!isset($tmp_date) || !validDate($tmp_date, false)){
        exit();
    }
    if(!isset($tmp_time) || !validTime($tmp_time, false)){
        exit();
    }
    $tmp = DateTime::createFromFormat("Y-m-d", $tmp_date);
    $date_obj = strtotime($tmp_date);
    $current_date = time();
    if($current_date - $date_obj < 0 || $current_date - $date_obj > (60*60*24*7)){
        exit();
    }
    
    $tmp_min = intval($min);
    $tmp_max = intval($max);
    $tmp_avg = intval($avg);
    $weight = intval($weight);
    if($tmp_min < 0 || $tmp_min>140){
        exit();
    }
    if($tmp_max < 0 || $tmp_max>140){
        exit();
    }
    if($tmp_avg < 0 || $tmp_avg>140){
        exit();
    }
    if($tmp_max < $tmp_min){
        exit();
    }
    if($tmp_avg < $tmp_min || $tmp_avg > $tmp_max){
        exit();
    }
    if( 0>$weight || $weight>1000){
        exit();
    }
   
    $tmp_latitude = round(floatval($latitude),4);
    $tmp_longitude = round(floatval($longitude),4);
    if($tmp_latitude < -85.0 || $tmp_latitude > 85.0){
        exit();
    }
    if($tmp_longitude < -180.0 || $tmp_longitude > 180.0){
        exit();
    }
   
    if(strlen($userID) < 6){
        exit();
    }
    
    $db = DataBaseManager::getInstance();
    $mysqli = $db->getConnection();
    $username = "";
    $id = -1;
    if ($stmt = $mysqli->prepare("SELECT ID, USERNAME FROM DBO_SMPG_USER WHERE EMAIL = ? LIMIT 1")) 
    {
        $stmt->bind_param('s', $userID); 
        $stmt->execute();    
        $stmt->store_result();
        $stmt->bind_result($id,$username);
        $stmt->fetch();
        if ($stmt->num_rows != 1){
            exit();
        }
    }else{
        exit();
    }
   
    if(login_check($mysqli) == false){ 
        exit();
    }else{
       if($_SESSION['username'] != $username){
            exit();
        }
    }
    $location_id = -1;
    
    if ($stmt = $mysqli->prepare("SELECT ID FROM DBO_SMPG_LOCATION
                                WHERE LATITUDE = ? AND LONGITUDE = ?
                                LIMIT 1")) 
    {
        $stmt->bind_param('dd', $tmp_latitude,$tmp_longitude); 
        $stmt->execute();    
        $stmt->store_result();
        $stmt->bind_result($location_id);
        $stmt->fetch();
        if ($stmt->num_rows != 1){
            if ($stmt = $mysqli->prepare("INSERT INTO DBO_SMPG_LOCATION (LATITUDE, LONGITUDE) VALUES (?, ?)")) 
            {
                $stmt->bind_param('dd', $tmp_latitude,$tmp_longitude); 
                if($stmt->execute())  { 
                    $location_id = $mysqli->insert_id;
                }else{
                    exit();
                }
            }else{
                exit();
            }
        }            
    }else{
        exit();
    }

    if ($stmt = $mysqli->prepare("SELECT ID FROM DBO_SMPG_DEVICE
                                WHERE DEVICEID = ? AND USERID = ?
                                LIMIT 1")) 
    {
        $stmt->bind_param('si', $deviceID,$id); 
        $stmt->execute();    
        $stmt->store_result();
        $stmt->bind_result($device_ID);
        $stmt->fetch();
        if ($stmt->num_rows != 1){
            if ($stmt = $mysqli->prepare("INSERT INTO DBO_SMPG_DEVICE (DEVICEID, USERID) VALUES (?, ?)")) 
            {
                $stmt->bind_param('si', $deviceID,$id); 
                if($stmt->execute())  { 
                    $device_ID = $mysqli->insert_id;
                }else{
                    exit();
                }
            }else{
                exit();
            }
        }            
    }else{
        exit();
    }
    
    
    
    $stmt = "INSERT INTO DBO_SMPG_MEASUREMENT(MIN, MAX, AVG, DATE, WEIGHT, USERID, LOCATIONID, DEVICEID) values (?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($stmt);
    if($stmt){
        echo $stmt->error;
        $stmt->bind_param('iiisiiii', $tmp_min,$tmp_max,$tmp_avg,$date,$weight,$id,$location_id, $device_ID);
        $stmt->execute(); 
    }else{
        exit();
    }
 
}


			