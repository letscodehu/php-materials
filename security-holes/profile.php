<?php 
session_start();

    if (!array_key_exists("loggedIn", $_SESSION)) {
        header("Location: /login.php?url=/profile.php");
        die;
    } 
    
?>
<html>
    <head>
    </head>
    <body>
        <h1>Profile</h1>
    </body>
</html>
