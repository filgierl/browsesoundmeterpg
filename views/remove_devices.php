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
    $device = filter_var($data[$i]->device, FILTER_SANITIZE_STRING);
    if(!isset($device)){
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
    
    $stm = "DELETE FROM DBO_SMPG_DEVICE  WHERE ID = ?";
    $stmt = $mysqli->prepare($stm);
    if ($stmt){
        $stmt->bind_param('i',$deviceID); 
        $stmt->execute();                    
    }else{
        exit();
    }

    
    //todo uncomment if databse not support relations
//    $stmt = "UPDATE DBO_SMPG_MEASUREMENT SET DEVICEID = NULL WHERE DEVICEID = ?";
//    $stmt = $mysqli->prepare($stmt);
//    if ($stmt){
//        $stmt->bind_param('i',$deviceID); 
//        $stmt->execute();                    
//    }else{
//        exit();
//    }
}


echo json_encode("{}",true);



