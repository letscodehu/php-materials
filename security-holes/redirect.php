<?php 
session_start();

$url = htmlentities($_GET["url"]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: ".$_POST["url"]);
}
?>
<html>
    <head>
    </head>
    <body>
        <h1>A link, amire kattintottál külső címre mutat: <?= $url ?></h1>
        <form method="post">
            <input type="hidden" name="url" value="<?= $url ?>" />
            <input type="submit" value="Folytatom">
        </form>
    </body>
</html>