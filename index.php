<?php
    if (!file_exists("conf.php"))
    {
        print("Не создан файл конфигурации (conf.php)");
        exit();
    }

    include "conf.php";
    include "server.php";

    $data = getCurrentDate($CONF["timezone"]);
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php print($CONF["company"]); ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/main.css">
    </head>

    <body>
        <div class="clock">
            <div class="time">
                <span id="hours"><?php print($data["hours"]); ?></span>
                <span class="blink-colon">:</span>
                <span id="minutes"><?php print($data["minutes"]); ?></span>
                <span class="blink-colon">:</span>
                <span id="seconds"><?php print($data["seconds"]); ?></span>
            </div>

            <div class="date">
                <span id="date"><?php print($data["date"]); ?></span>
                <span id="day"><?php print($data["day"]); ?></span>
            </div>
        </div>

        <script src="js/main.js"></script>
    </body>
</html>
