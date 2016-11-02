<?php
$data = json_decode(file_get_contents("php://input"));
if(isset($data) && isset($data->latitude) && isset($data->longitude)){
    $lat = $data->latitude;
    $lon = $data->longitude;
    $response = array();
    
    for($i=0;$i<1000;$i++){
        $noiseLevel = rand(20, 80);
        if($noiseLevel < 65)
            $weight = round(floatval(rand()/  getrandmax()),2);
        else
           $weight = 0.8; 
        $response[$i] = array('latitude'=>$lat,'longitude'=>$lon,'noiseLevel'=>$noiseLevel,'weight'=>$weight);
    }
   echo json_encode($response,true);
}
    
// jezeli niewielkie odchylenie standradowe liczenie prawdopodobienstwa mija sie z celem
// jezeli mamy duzo  pomiarow o niskich wagach negatywnie wplyna na wynik
// jezeli wartosci sa roztrzelone wysokie odchylenie standardowe co wtedy?
// duzy roztrzal rownomierny pozyskujemy zwykla srednia 
// przyznawanie wag nie powiino byc funkcja liniowa rosnaca 
// wiecej probek testy na wolniejszych komputerach jak skaluje sie javascript
