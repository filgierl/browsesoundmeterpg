/* 
    Author     : Daniel
*/
function validLoginForm(password) {
    
    return validEmail() && validPassword();
    password.value = "";
}
 

function validForm(){
    var isChecked = $("#checkbox").is(':checked');
    $("#error_msg").text("");
    $("#error_msg").css("display", "none");
    if(!isChecked){
        $("#error_msg").text("You are not agree with our Terms and Pravicy");
        $("#error_msg").css("display", "block");
    }
    return validUsername() && validPassword() && validCnfpassword() && validEmail() && isChecked;
}

function errorValid(id, text){
    $("#error_msg").css("display", "block");
    $("#error_msg").text(text);
    $(id).css("box-shadow"," 0px 0px 3px #FF0000");
}

function clearInput(id, text){
    if($("#error_msg").text() === text){
        $("#error_msg").text("");
        $("#error_msg").css("display", "none");
    }
    $(id).css("box-shadow"," 0px 0px 0px #FF0000");
}

$(document).on("focusout","#username",validUsername);
$(document).on("focus","#username",clearUsername);

var lengthUsernameError = "Username should be at least 5 to 20 characters long";
var patternUsernamError = "Username should contains only leter or number";

function validUsername(){
    var username = $("#username").val();
    var regex = new RegExp('^[a-zA-Z0-9_]+$');
    if(username.length <= 5 || username.length >= 20){
        errorValid("#username",lengthUsernameError);
        return false;
    }
    if(!regex.test(username)){
        errorValid("#username",patternUsernamError);
        return false;
    }
    return true;
}

function clearUsername(){
    clearInput("#username",lengthUsernameError);
    clearInput("#username",patternUsernamError);
}

$(document).on("focusout","#email",validEmail);
$(document).on("focus","#email",clearEmail);

var patternEmailError = "Invalid email";

function validEmail(){
    var email = $("#email").val();
    var regex = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
    if(!regex.test(email)){
        errorValid("#email",patternEmailError);
        return false;
    }
    return true;
}

function clearEmail(){
    clearInput("#email",patternEmailError);
}

$(document).on("focusout","#password",validPassword);
$(document).on("focus","#password",clearPassword);

var lengthPasswordError = "Password should be at least 6  characters long";

function validPassword(){
    var password = $("#password").val();
    if(password.length < 6){
        errorValid("#password",lengthPasswordError);
        return false;
    }
    return true;
}

function clearPassword(){
    clearInput("#password",lengthPasswordError);
}

var sameCnfpasswordError = "Passwords are not same";

$(document).on("focusout","#confirmpwd",validCnfpassword);
$(document).on("focus","#confirmpwd",clearCnfpassword);

function validCnfpassword(){
    if($("#password").val() !== $("#confirmpwd").val()){
        errorValid("#confirmpwd",sameCnfpasswordError);
        return false;
    }
    return true;
}

function clearCnfpassword(){
    clearInput("#confirmpwd",sameCnfpasswordError);
}
