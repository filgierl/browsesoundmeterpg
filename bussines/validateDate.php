<?php
 /* 
        Author     : Daniel
*/

function validDate($date,$compareWithCurrent){
    if(!isset($date))
        return false;
    $regex = "/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/";
    if(preg_match($regex,$date) === 0)
            return false;
  
    $start = strpos($date,'-');
    $end = strrpos($date,'-');
    $length = strlen($date);
    $year = substr($date,0,$start);
    $month = substr($date,$start+1,$end-$start-1);
    $day = substr($date,$end+1,$length-$end);
    $year = intval($year);
    $month = intval($month);
    $day = intval($day);
    
    if($year < 2016)
        return false;
    if($month > 12)  
        return false;
    if($day >31)
        return false;
   $month30 = array( 2,4,6,9,11);
   $max = sizeof($month30);
   for($i = 0; $i< $max ;$i++){
       if($month == $month30[$i] && $day > 30){
           return false;
       }
    }
    $tmp = $year%4;
    if($tmp === 0 && $month == 2 && $day > 29){
           return false;
    }else if($tmp !== 0 && $month == 2 && $day > 28){
           return false;
    }
    date_default_timezone_set('UTC');
    if($compareWithCurrent)
        if(!compareWithCurrent($year, $day , $month))
                return false;
    return true;
}

function compareWithCurrent($year, $day , $month){
    $currentDate = date("Y-m-d");
    $start = strpos($currentDate,'-');
    $end = strrpos($currentDate,'-');
    $length = strlen($currentDate);
    $yearCurr = substr($currentDate,0,$start);
    $monthCurr = substr($currentDate,$start+1,$end-$start-1);
    $dayCurr = substr($currentDate,$end+1,$length-$end);
    $yearCurr = intval($yearCurr);
    $monthCurr = intval($monthCurr);
    $dayCurr = intval($dayCurr);
    if($year < $yearCurr)
        return false;
    if($year == $yearCurr && $month <  $monthCurr)
        return false;
    if($year == $yearCurr && $month ==  $monthCurr && $day < $dayCurr)
        return false;
    return true;
}

function validTime($time,$comapreWithCurrentHours){
    if(!isset($time))
        return false;
    $regex = "/^[0-2]{0,1}[0-9]:[0-9]{1,2}$/";
    if(preg_match($regex,$time) === 0)
            return false;
    
    $start = strpos($time,':');
    $length = strlen($time);
    $hours = substr($time,0,$start);
    $minutes = substr($time,$start+1,$length - $start);
    $hourse = intval($hours);
    $minutes = intval($minutes);
    
    if($hours > 24)
       return false;
    if($minutes > 59)
        return false;
    
    date_default_timezone_set('UTC');
    $curr = intval(date('G'));
    if($comapreWithCurrentHours)
        if($hours < $curr)
            return false;
    
    return true;
}
