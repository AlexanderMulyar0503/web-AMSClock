<?php
    if (!file_exists("conf.php"))
    {
        print("Не создан файл конфигурации (conf.php)");
        exit();
    }

    include "conf.php";

    if (!file_exists($CONF["db_file"]))
    {
        $db = new SQLite3($CONF["db_file"]);
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
