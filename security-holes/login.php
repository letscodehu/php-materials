<?php 
session_start();

$url = $_GET["url"];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION["loggedIn"] = true;
        if (array_key_exists("url", $_POST) && validRedirectUrl($_POST["url"])) {
            header("Location: ".$_POST["url"]);
        } else {
            header("Location: /redirect.php?url=".$_POST["url"]);
        }
    }

    function validRedirectUrl($url) {
        $validUrls = [
            "/profile.php"
        ];
        return in_array($url, $validUrls);
    }

?>
<html>
    <head>
    </head>
    <body>
        <form method="post">
            <label>Felhasználónév<input type="text" /></label>
            <label>Jelszó<input type="password" /></label>
            <input type="hidden" name="url" value="<?= $url ?>" />
            <input type="submit" value="Belépés">
        </form>
    </body>
</html>
