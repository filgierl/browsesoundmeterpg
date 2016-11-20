<?php
/* 
    Author     : Daniel
*/
include_once '../bussines/validateDate.php';
include_once '../bussines/db_connect.php';

$data = json_decode(file_get_contents("php://input"));
$latitude = filter_var($data->latitude,FILTER_VALIDATE_FLOAT);
$longitude = filter_var($data->longitude,FILTER_VALIDATE_FLOAT);
$date = $data->date;
$time = $data->time;
if(isset($data) && isset($latitude) && isset($longitude) && validDate($date,true) && validTime($time,false)){
    $latTmp = round($latitude,4);
    $lonTmp = round($longitude,4);
    $db = DataBaseManager::getInstance();
    $mysqli = $db->getConnection();

    $stmt = "SELECT ID, LATITUDE, LONGITUDE FROM DBO_SMPG_LOCATION WHERE (LATITUDE BETWEEN ? AND ?) AND (LONGITUDE BETWEEN ? AND ?) ORDER BY LATITUDE, LONGITUDE LIMIT 100";
    $stmt = $mysqli->prepare($stmt);
    
    if($stmt){
        $lat1 = $latitude-0.0016; //~-100m
        $lat2 = $latitude+0.0016;//~100m
        $long1 = $longitude-0.0009;//~ -100m
        $long2 = $longitude+0.0009;//~100m
        $stmt->bind_param('dddd',$lat1, $lat2,$long1,$long2);
        $stmt->execute();
        $stmt->bind_result($id,$lat,$lng);
        $locations = array();
        while ( $stmt->fetch() ) {
            $tmp = new MyLocation;
            $tmp->id = $id;
            $tmp->lat = $lat;
            $tmp->lng = $lng;
            $tmp->distance = getDistanceFromLatLonInKm($latitude,$longitude,$lat,$lng);
            if($tmp->distance <= 0.1){ 
                array_push($locations,$tmp); 
            }
        }
        
        usort($locations, function($a, $b){
            if ($a->distance == $b->distance) {
                return 0;
            }
            return ($a->distance < $b->distance) ? -1 : 1;
        });
        if(strlen($time) < 5){
            $diff =strlen($time) - strpos($time,':') ;
            if( $diff == 2){
                $tmp = substr($time, strpos($time,':')+1);
                $time = substr($time, 0, strpos($time,':')+1)."0".$tmp;
            }
        }
        $datetime = "$date $time";
        $datetime = date_create_from_format('Y-m-d H:i', $datetime);
        date_sub($datetime, date_interval_create_from_date_string('7 days'));
        $datetime = $datetime->format('Y-m-d H:i');
        $length = count($locations);
        $results = array();
        for($i =0;$i<$length;$i++){
            $stmt = "SELECT AVG, WEIGHT, DATE FROM DBO_SMPG_MEASUREMENT WHERE LOCATIONID = ? AND DATE > ? ORDER BY AVG DESC LIMIT 1000";
            $stmt = $mysqli->prepare($stmt);
            if($stmt){  
                $stmt->bind_param('is',$locations[$i]->id,$datetime);
                $stmt->execute();
                $stmt->bind_result($avg,$weight,$tmp_date);            
                while ( $stmt->fetch() ) {
                    $tmp = new Response;
                    $tmp->lat = $locations[$i]->lat;
                    $tmp->lng = $locations[$i]->lng;
                    $tmp->noiselevel = $avg;
                    $tmp->weight = $weight;
                    $start = strpos($tmp_date,' ');
                    $tmp_time = substr($tmp_date,$start+1,5);
                    if(compareTime($tmp_time, $time)){
                        array_push($results,$tmp);
                    }
                    if(count($results) == 1000){
                        break;
                    }
                }
            }else{
                echo("Internal Server Error 500");
                exit();
            }
        }
        $length = count($results);
        $response = array();
        if($length >0 ){
            for($i =0;$i<$length;$i++){
                $response[$i] = array('latitude'=>$results[$i]->lat,'longitude'=>$results[$i]->lng,'noiseLevel'=>$results[$i]->noiselevel,'weight'=>$results[$i]->weight);
            }
            echo json_encode($response,true);
        }else{
            echo json_encode("Too little data",true);
            exit();
        }  
    }else{
        echo("Internal Server Error 500");
        exit();
    }
}else{
        echo("Internal Server Error 500");
        exit();
}

function  compareTime($tmp_time, $time){
    $minutes = intval(substr($time, 3));
    $houres = intval(substr($time,0,2));
    $base = 60*$houres +$minutes;
    $tmp_min = intval(substr($tmp_time, 3));
    $tmp_hr = intval(substr($tmp_time,0,2));
    $tmp = 60*$tmp_hr +$tmp_min;
    $diff = abs($base-$tmp);
    if($diff>15){
        return false;
    }
    return true;
}

function getDistanceFromLatLonInKm($x1,$y1,$x2,$y2) {
  //http://www.movable-type.co.uk/scripts/latlong.html
  $R = 6371; // Radius of the earth in km
  $lat = deg2rad($x2-$x1);  // deg2rad below
  $lon = deg2rad($y2-$y1); 
  $a = 
    sin($lat/2) * sin($lat/2) +
    cos(deg2rad($x1)) * cos(deg2rad($x2)) * 
    sin($lon/2) * sin($lon/2); 
  $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
  $d = $R * $c; 
  return $d;
}

class MyLocation {
    public $lat;
    public $lng;
    public $distance;
    public $id;  
} 

class Response {
    public $lat;
    public $lng;
    public $weight;
    public $noiselevel;
} 

// jezeli niewielkie odchylenie standradowe liczenie prawdopodobienstwa mija sie z celem
// jezeli mamy duzo  pomiarow o niskich wagach negatywnie wplyna na wynik
// jezeli wartosci sa roztrzelone wysokie odchylenie standardowe co wtedy?
// duzy roztrzal rownomierny pozyskujemy zwykla srednia 
// przyznawanie wag nie powiino byc funkcja liniowa rosnaca 
// wiecej probek testy na wolniejszych komputerach jak skaluje sie javascript
// 54.35304
// 54.35307
// 18.61185
// 18.61199
// waga od 0 do 1 zbyt mala bo jak bierzmy bliskie probki naszym wzorem to ich sa praktycznie caly czas 1
//54.3534
//18.6125


