<?php
$data = json_decode(file_get_contents("php://input"));
if(!isset($data)){
    exit();
}
$length = count($data);
if($length < 2){
    exit();
}
$username = filter_var($data[0]->username,FILTER_SANITIZE_STRING);
if(!isset($data)){
    exit();
}

$db = DataBaseManager::getInstance();
$mysqli = $db->getConnection();

if(login_check($mysqli) == false){ 
        exit();
}else{
   if($_SESSION['username'] != $username){
        exit();
    }
}

for($i=1;$i<$length;$i++){
    $min = filter_var($data[$i]->min, FILTER_SANITIZE_NUMBER_INT);
    $max = filter_var($data[$i]->max, FILTER_SANITIZE_NUMBER_INT);
    $avg = filter_var($data[$i]->avg, FILTER_SANITIZE_NUMBER_INT);
    $weight = filter_var($data[$i]->weight, FILTER_SANITIZE_NUMBER_INT);
    $latitude = filter_var($data[$i]->latitude, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $longitude = filter_var($data[$i]->longitude, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $date = filter_var($data[$i]->date, FILTER_SANITIZE_STRING);
    $device= filter_var($data[$i]->device, FILTER_SANITIZE_STRING);
    if(!isset($min) || !isset($max) || !isset($avg) ||
      !isset($latitude) || !isset($longitude) || !isset($date) ||
      !isset($weight)){
       exit();
    }
    if ($stmt = $mysqli->prepare("SELECT ID FROM DBO_SMPG_USER WHERE USERNAME = ? LIMIT 1")) 
    {
        $stmt->bind_param('s', $username); 
        $stmt->execute();    
        $stmt->store_result();
        $stmt->bind_result($userID);
        $stmt->fetch();
        if ($stmt->num_rows != 1){
            exit();
        }
    }else{
        exit();
    }
    
    if ($stmt = $mysqli->prepare("SELECT ID FROM DBO_SMPG_LOCATION
                                WHERE LATITUDE = ? AND LONGITUDE = ?
                                LIMIT 1")) 
    {
        $stmt->bind_param('dd', $latitude,$longitude); 
        $stmt->execute();    
        $stmt->store_result();
        $stmt->bind_result($location_id);
        $stmt->fetch();
        if ($stmt->num_rows != 1){
            exit();
        }            
    }else{
        exit();
    }
    
    if(isset($device) && $device != ""){
        if ($stmt = $mysqli->prepare("SELECT ID FROM DBO_SMPG_DEVICE WHERE DEVICEID = ? AND USERID = ? LIMIT 1")) 
        {
            $stmt->bind_param('si', $device,$userID); 
            $stmt->execute();    
            $stmt->store_result();
            $stmt->bind_result($deviceID);
            $stmt->fetch();
            if ($stmt->num_rows != 1){
                exit();
            }            
        }else{
            exit();
        }
        $stm = "DELETE FROM DBO_SMPG_MEASUREMENT WHERE MIN = ? AND MAX = ? AND AVG = ? AND WEIGHT = ? AND DATE = ? AND LOCATIONID = ? AND USERID = ? AND DEVICEID = ?";
    }else{
        $stm = "DELETE FROM DBO_SMPG_MEASUREMENT WHERE MIN = ? AND MAX = ? AND AVG = ? AND WEIGHT = ? AND DATE = ? AND LOCATIONID = ? AND USERID = ? AND DEVICEID IS NULL";
    }
    
    $stmt = $mysqli->prepare($stm);
    if ($stmt){
        if(isset($device) && $device != ""){
            $stmt->bind_param('iiiisiii', $min,$max,$avg,$weight,$date,$location_id,$userID,$deviceID); 
        }else{
            $stmt->bind_param('iiiisii', $min,$max,$avg,$weight,$date,$location_id,$userID); 
        }
        $stmt->execute();                    
    }else{
        exit();
    }
    
    
}


echo json_encode("{}",true);

