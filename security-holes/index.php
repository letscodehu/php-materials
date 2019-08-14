<?php 

session_start();
if ($_GET["action"] == 'login') {
    session_regenerate_id(true);
    $_SESSION["loggedin"] = true;
    header("Location: /?".SID);
}
if ($_GET["action"] == 'logout') {
    unset($_SESSION["loggedin"]);
    header("Location: /");
}
?>
<html>
    <head>
    </head>
    <body>
        <?php 
         if ($_SESSION["loggedin"]) {
             ?>Bejelentkezve
             <a href="?action=logout">Kijelentkezés<a><?php
         } else {
             ?><a href="?action=login">Bejelentkezés<a><?php
         }
        ?>
    </body>
</html>
