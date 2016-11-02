<?php include_once '../globalVariables.php'; ?>
<!--    Author     : Daniel-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Error</title>
    </head>
    <body>
        <p class="error"><?php global $ERROR_MSG; echo $ERROR_MSG; $ERROR_MSG = "";?></p>  
    </body>
</html>