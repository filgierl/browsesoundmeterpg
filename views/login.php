<!--    Author     : Daniel-->
<form id="form" action="process_login" method="post" name="login_form" onsubmit="return validLoginForm(this.password)">  
    <div class="form">
    Log in
        <div class="inputs">

            <label for="email">Email:</label><br>
            <input class="input" type="email" name="email" id="email" /><br>
            <label for="password">Password:</label><br>
            <input class="input" type="password" name="password" id="password"/><br>
            <input id="register_button" type="submit" value="Login"  /> 

        </div>
    </div>
    <p id="error_msg">Daj</p>
    <div id="register_information">
        <p>If you don't have account register <a href="<?php echo PAGE_URL.REGISTER_WEB_ACTION ?>">here</a></p>
    </div>
</form>
