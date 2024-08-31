<?php
    require_once "start.php";
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

    if (isset($_GET["events"]))
    {
        $timezone = new DateTimeZone($CONF["timezone"]);
        $currentTime = new DateTimeImmutable("now", $timezone);

        $db = new SQLite3($CONF["db_file"]);
        $sql = "SELECT * FROM events WHERE date_evnt = '*' OR date_evnt = '" . $currentTime->format("d.m") . "' OR date_evnt = '" . $currentTime->format("d.m.Y") . "' ORDER BY date_evnt";
        $result = $db->query($sql);

        $eventArray = array();

        while ($event = $result->fetchArray(SQLITE3_ASSOC))
        {
            array_push($eventArray, $event);
        }

        $db->close();

        echo json_encode($eventArray);
    }
