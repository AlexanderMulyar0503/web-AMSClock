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

    function createDB($fileName)
    {
        $db = new SQLite3($fileName);
        $sql = "CREATE TABLE events
                (
                    id_evnt INTEGER PRIMARY KEY AUTOINCREMENT,
                    date_evnt TEXT,
                    title_evnt TEXT,
                    text_evnt TEXT
                )";
        $db->exec($sql);

        $db->close();
    }

    if (isset($_GET["events"]))
    {
        $timezone = new DateTimeZone($CONF["timezone"]);
        $currentTime = new DateTimeImmutable("now", $timezone);

        if (!file_exists($CONF["db_file"]))
        {
            createDB($CONF["db_file"]);
        }

        $db = new SQLite3($CONF["db_file"]);
        $sql = "SELECT * FROM events WHERE date_evnt = '*' OR date_evnt = '" . $currentTime->format("d.m.Y") . "'";
        $result = $db->query($sql);

        $eventArray = array();

        while ($event = $result->fetchArray(SQLITE3_ASSOC))
        {
            array_push($eventArray, $event);
        }

        $db->close();

        echo json_encode($eventArray);
    }
