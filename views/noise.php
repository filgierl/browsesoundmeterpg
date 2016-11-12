<?php
include_once '../bussines/validateDate.php';

$data = json_decode(file_get_contents("php://input"));
$latitude = filter_var($data->latitude,FILTER_VALIDATE_FLOAT);
$longitude = filter_var($data->longitude,FILTER_VALIDATE_FLOAT);
$date = $data->date;
$time = $data->time;        
if(isset($data) && isset($latitude) && isset($longitude) && validDate($date,true) && validTime($time,true)){
    $latTmp = round($latitude,4);
    $lonTmp = round($longitude,4);
    $response = array();
    for($i=0;$i<10;$i++){
        for($j=0; $j<100;$j++){
            $noiseLevel = rand(20, 80);
            if($noiseLevel < 80)
               $weight = round(floatval(rand()/  getrandmax()),2);
            else
               $weight = 0.8; 
            $response[$i*100+$j] = array('latitude'=>$latTmp,'longitude'=>$lonTmp,'noiseLevel'=>$noiseLevel,'weight'=>$weight);
        }  
        $latTmp = $latTmp + 0.0001;
        $lonTmp = $lonTmp + 0.0001;
    }
   echo json_encode($response,true);
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

