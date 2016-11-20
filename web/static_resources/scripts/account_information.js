/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var selectedDevice = [];
var selectedMeasurements = [];

$(document).ready(function() {
    $("#list_android table tbody tr").on( "click", function( event ) {
        markTr(event.currentTarget, $(this).index(), selectedDevice);
      });
    $("#list_measurments table tbody tr").on( "click", function( event ) {
        markTr(event.currentTarget, $(this).index(), selectedMeasurements);
      });
      
    $("#remove_measurments").on( "click", function( event ) {
      removeMeasurments();
   });
   
    $("#remove_device").on( "click", function( event ) {
      removeDevices();
   });
});


function markTr(target, index, table){
    var found = $.inArray(index, table) > -1;
    if(found){
       if((index+1)%2 == 0)
            $(target).css("background-color","white");
        else
            $(target).css("background-color","rgba(255,200,187,0.2)");
       var toRmv =  table.indexOf(index);
       table.splice(toRmv);
    }else{
       table.push(index);
       $(target).css("background-color","rgba(255,200,187,1)");
    }
    
}

function removeMeasurments(){
    if(selectedMeasurements.length == 0)
        return;
    var measurements = [{"username" : $("#username_account").html()}];
    for( var i=0; i<selectedMeasurements.length;i++){
        var selector = "#list_measurments table tbody";
        var measurement = { "avg" : $(selector).children().eq(selectedMeasurements[i]).children().eq(1).html(), 
                        "min"  : $(selector).children().eq(selectedMeasurements[i]).children().eq(2).html(), 
                        "max" : $(selector).children().eq(selectedMeasurements[i]).children().eq(3).html(),
                        "latitude" :$(selector).children().eq(selectedMeasurements[i]).children().eq(4).html(),
                        "longitude" : $(selector).children().eq(selectedMeasurements[i]).children().eq(5).html(),
                        "weight" : $(selector).children().eq(selectedMeasurements[i]).children().eq(6).html(),
                        "device" : $(selector).children().eq(selectedMeasurements[i]).children().eq(7).html(),
                        "date" : $(selector).children().eq(selectedMeasurements[i]).children().eq(8).html()
                    };
        measurements.push(measurement);
    }
    $.ajax({
        url:'/browsesoundmeterpg/web/remove_measurements',
        type:'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(measurements),
        success: function(data){
           location.reload(true); 
        }
       
    });
}

function removeDevices(){
    if(selectedDevice.length ==0)
        return;
    var devices = [{"username" : $("#username_account").html()}];
    for( var i=0; i<selectedDevice.length;i++){
        var selector = "#list_android table tbody";
        var device = { "device" : $(selector).children().eq(selectedDevice[i]).children().eq(1).html() 
                    };
        devices.push(device);
    }
    $.ajax({
        url:'/browsesoundmeterpg/web/remove_devices',
        type:'POST',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(devices),
        success: function(data){
           location.reload(true); 
        }
    });
    
    
}
