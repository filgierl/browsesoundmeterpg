/* 
    Created on : Oct 16, 2016, 9:50:54 PM
    Author     : Daniel
*/
$(document).ready( function() {
    var today = new Date();
    var hours = today.getHours();
    var minutes = today.getMinutes();
    var month = (today.getMonth() + 1);               
    var day = today.getDate();
    if(month < 10) 
        month = "0" + month;
    if(day < 10) 
        day = "0" + day;
    var today = today.getFullYear() + '-' + month + '-' + day;
    if(hours < 10)
        hours = "0" + hours;
    if(minutes < 10)
        minutes = "0" + minutes;
        
    var time = hours + ':' + minutes;
    
    $("#time").val(time);
    $("#date").val(today);
});

$(document).on("focusout","#time",function(){
    var time =  $("#time").val();
    var  msgError = isTimeValid(time);
    if(msgError.length > 0)
        wrongData($("#time"),msgError);
    else
        goodData($("#time"));
});

function isTimeValid(time){
   var regex = new RegExp('^[0-2]{0,1}[0-9]:[0-9]{1,2}$');
    if(!regex.test(time))
        return "Time is not valid HH:MM";
    
   var hours = time.substring(0,time.indexOf(':'));
   var minutes = time.substring(time.indexOf(':')+1,time.length);
   
    if(hours > 24)
       return "Hours > 24";
    if(minutes > 59)
        return "Minutes > 59";
  return "";
}

$(document).on("focusout","#date",function(){
   var date =  $("#date").val();
   var msgError = isDataValid(date);
   if( msgError.length > 0)
       wrongData($("#date"),msgError );
   else
       goodData($("#date"));
   
});

function isDataValid(date){
    var regex = new RegExp('^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$');
    if(!regex.test(date)){
        return "Date is not valid YYYY:MM:DD";
    }
   var year = date.substring(0,date.indexOf('-'));
   var month = date.substring(date.indexOf('-')+1,date.lastIndexOf('-'));
   var day = date.substring(date.lastIndexOf('-')+1,date.length);
   
   if(year < 2016)
       return "year < 2016";
   if(month > 12)  
       return "Month > 12";
   if(day >31)
       return "Day > 31";
   var month30 = [ 2,4,6,9,11];
   for(var i = 0; i< month30.length;i++){
       if(month == month30[i] && day > 30){
           return "Day should be less than 31";
       }
    }
    var tmp = parseInt(year)%4;
    if(tmp === 0 && month == 2 && day > 29){
           return "Day should be less than 30";
    }else if(tmp !== 0 && month == 2 && day > 28){
           return "Day should be less than 29";
    }
   return "";
}

function wrongData(input,msg){
    $('#error_msg').text(msg);
}

function goodData(input){
    $('#error_msg').empty();
}


