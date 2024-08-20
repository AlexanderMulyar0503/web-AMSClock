<?php
    include "conf.php";

    function getCurrentDate($timezone)
    {
        $timezone = new DateTimeZone($timezone);
        $currentTime = new DateTimeImmutable("now", $timezone);

        $data = array("hours" => $currentTime->format("H"),
            "minutes" => $currentTime->format("i"),
            "seconds" => $currentTime->format("s"),
            "date" => $currentTime->format("d.m.Y"),
            "day" => $currentTime->format("D"));

        return $data;
    }

    if (isset($_GET["date"]))
    {
        $data = getCurrentDate($CONF["timezone"]);
        echo json_encode($data);
    }
