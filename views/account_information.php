
<!--    Author     : Daniel-->

<div id="account_title"> <h2> Hello <p id="username_account"><?php echo $_SESSION['username']?></p>!</h2></div>

<div id="logout_container">
    <div id="logout_button" class="account_button"><a href="<?php echo PAGE_URL.LOGOUT_ACTION ?>">Logout</a></div>
    <div id="change_password" class="account_button"><a href="<?php echo PAGE_URL.CHANGE_PASSWORD_ACTION ?>">Change password</a></div>
    <div class="clear"></div>
</div>
<div class="account_div list" id="list_measurments">
    <table>
        <thead>
            <th>#</th>
            <th>Avg</th>
            <th>Min</th>
            <th>Max</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Weight</th>
            <th>Device</th>
            <th>Date</th>
        </thead>
        <tbody>
        <?php
        include_once '../bussines/function.php';
        $measurements = takeMeasurements($_SESSION['username']);
        $length = count($measurements);
        for($i=0;$i<$length;$i++){
            $index = $i +1;
            echo   "<tr>
                    <td>".$index."</td>
                    <td>".$measurements[$i]["Avg"]."</td>
                    <td>".$measurements[$i]["Min"]."</td>
                    <td>".$measurements[$i]["Max"]."</td>
                    <td>".$measurements[$i]["Latitude"]."</td>
                    <td>".$measurements[$i]["Longitude"]."</td>
                    <td>".$measurements[$i]["Weight"]."</td>
                    <td>".$measurements[$i]["Device"]."</td>
                    <td>".$measurements[$i]["Date"]."</td> 
                </tr>";
        }
        ?>
        </tbody>
    </table>
    <div id="remove_measurments" class="remove_button">Remove</div>
</div>
<div class="account_div list" id="list_android">
    <table id="list_android">
        <thead>
          <th>#</th>
          <th>DeviceID</th>
        <thead>
        <tbody>
             <?php
                include_once '../bussines/function.php';
                $devices = takeDevice($_SESSION['username']);
                $length = count($devices);
                for($i=0;$i<$length;$i++){
                    $index = $i +1;
                    echo   "<tr>
                            <td>".$index."</td>
                            <td>".$devices[$i]["Device"]."</td>
                        </tr>";
                }
            ?>
        </tbody>   
         
    </table>
    <div id="remove_device" class="remove_button">Remove</div>
    <div class="clear"></div>
</div>
<div class="clear"></div>