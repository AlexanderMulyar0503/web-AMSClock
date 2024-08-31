<?php
    require_once "start.php";
    include "conf.php";

    session_start();

    if (!isset($_SESSION["login"]))
    {
        $_SESSION["login"] = false;
    }

    if (isset($_POST["action"]))
    {
        if ($_POST["action"] == "signin")
        {
            if ($CONF["password"] == $_POST["passwd"])
            {
                $_SESSION["login"] = true;
            }
        }
        
        if ($_POST["action"] == "signout")
        {
            $_SESSION["login"] = false;
        }

        if ($_POST["action"] == "delete")
        {
            $db = new SQLite3($CONF["db_file"]);
            $sql = "DELETE FROM events WHERE id_evnt = " . $_POST["id_evnt"];
            $result = $db->query($sql);
            $db->close();
        }

        if ($_POST["action"] == "add")
        {
            if (($_POST["addDate"] != "") && ($_POST["addTitle"] != "") && ($_POST["addText"] != ""))
            {
                $db = new SQLite3($CONF["db_file"]);
                $sql = "INSERT INTO events (date_evnt, title_evnt, text_evnt) VALUES (:date_evnt, :title_evnt, :text_evnt)";
                $stmp = $db->prepare($sql);
                $stmp->bindValue(":date_evnt", $_POST["addDate"], SQLITE3_TEXT);
                $stmp->bindValue(":title_evnt", $_POST["addTitle"], SQLITE3_TEXT);
                $stmp->bindValue(":text_evnt", $_POST["addText"], SQLITE3_TEXT);
                $result = $stmp->execute();
                $stmp->close();
                $db->close();                
            }
        }
    }
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
        <?php
            if ($_SESSION["login"] == false)
            {
                print("<div class='forms border'>");
                print("    <form action='events.php' method='post'>");
                print("        <input type='text' name='action' value='signin' hidden>");
                print("        <p>Введите пароль</p>");
                print("        <input type='password' name='passwd'>");
                print("        <input type='submit' value='Войти'>");
                print("    </form>");
                print("</div>");
            }
        
            if ($_SESSION["login"] == true)
            {
                print("<div class='forms border'>");
                print("    <form action='events.php' method='post'>");
                print("        <input type='text' name='action' value='signout' hidden>");
                print("        <input type='submit' value='Выйти'>");
                print("    </form>");
                print("</div>");

                print("<div class='forms border'>");
                print("    <form action='events.php' method='post'>");
                print("        <input type='text' name='action' value='add' hidden>");
                print("        <p>Дата:</p>");
                print("        <p>* - все дни</p>");
                print("        <p>ДД.ММ - ежегодное событие</p>");
                print("        <p>ДД.ММ.ГГГГ - дата</p>");
                print("        <input type='text' name='addDate' value=''>");
                print("        <p>Заголовок:</p>");
                print("        <input type='text' name='addTitle' value=''>");
                print("        <p>Текст:</p>");
                print("        <input type='text' name='addText' value=''>");
                print("        <input type='submit' value='Добавить'>");
                print("    </form>");
                print("</div>");


                print("<div class='events border'>");

                $db = new SQLite3($CONF["db_file"]);
                $sql = "SELECT * FROM events ORDER BY date_evnt";
                $result = $db->query($sql);

                if ($result->fetchArray(SQLITE3_ASSOC) != false)
                {
                    $result->reset();

                    while ($event = $result->fetchArray(SQLITE3_ASSOC))
                    {
                        print("<div class='event'>");
                        print("    <div class='eventHead'>");
                        print("        <div class='title'>" . $event["date_evnt"] . " - " . $event["title_evnt"] . "</div>");
                        print("        <div class='action'>");
                        print("            <form action='events.php' method='post'>");
                        print("                <input type='text' name='action' value='delete' hidden>");
                        print("                <input type='text' name='id_evnt' value='" . $event["id_evnt"] . "' hidden>");
                        print("                <input type='submit' value='Удалить'>");
                        print("            </form>");
                        print("        </div>");
                        print("    </div>");
                        print("    <div class='text'>" . $event["text_evnt"] . "</div>");
                        print("</div>");
                    }
                }
                else
                {
                    print("<div class='event'>");
                    print("    <div class='title'>");
                    print("        <div class='itemTitle'>Нет событий</div>");
                    print("    </div>");
                    print("    <div class='text'>Не добавлены события</div>");
                    print("</div>");
                }                

                $db->close();

                print("</div>");
            }
        ?>
    </body>
</html>
