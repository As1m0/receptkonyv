<?php

abstract class Model
{
    public static function GetPageData(string $page) : array
    {
        //return ["page" => $page, "template" => "main.html", "fullTemplate" => false, "Class" => "IndexPage"];
        global $cfg;
        $pagesJson = json_decode(file_get_contents($cfg["contentFolder"]."/pages.json"), true);
        if($pagesJson !== null)
        {
            $pageData = null;
            foreach ($pagesJson as $p)
            {
                if($p["page"] == $page)
                {
                    $pageData = $p;
                    break;
                }
            }
            if($pageData !== null)
            {
                return $pageData;
            }
            else
            {
                throw new NotFoundException("A megadott oldal nem található!");
            }
        }
        else
        {
            throw new Exception("Az oldalak feldolgozása hibára futott!");
        }
    }
    
    public static function LoadText(string $page, string $flag) : array
    {
        //return ["flag" => $flag, "text" => "ASD"];
        global $cfg;
        $contentJson = json_decode(file_get_contents($cfg["contentFolder"]."/content.json"), true);
        if($contentJson !== null)
        {
            if(isset($contentJson[$page]) && isset($contentJson[$page][$flag]))
            {
                return ["flag" => $flag, "text" => $contentJson[$page][$flag]];
            }
            else
            {
                throw new NotFoundException("A megadott oldal ($page) és a megadott flag ($flag) nem található a tartlmak között!");
            }
        }
        else
        {
            throw new Exception("A tartalmakat tároló JSON feldolgozása meghiúsult!");
        }
    }
    
    public static function GetModules() : array
    {
        global $cfg;
        $moduleJson = json_decode(file_get_contents($cfg["contentFolder"]."/modules.json"), true);
        if($moduleJson !== null)
        {
            return $moduleJson;
        }
        else
        {
            throw new Exception("A modulokat tartalmazó JSON feldolgozása meghiúsult!");
        }
    }
    
    public static function LoginCheck(string $email, string $pass) : bool
    {
        global $cfg;
        if(file_exists($cfg["contentFolder"]."/login.csv"))
        {
            $csv = fopen($cfg["contentFolder"]."/login.csv", "r");
            $loggedIn = false;
            while (!$loggedIn && !feof($csv))
            //while (!($loggedIn || feof($csv)))
            {
                $csvRow = fgetcsv($csv, null, ";");
                if($csvRow === false)
                {
                    continue;
                }
                if(trim($csvRow[2]) == $email && trim($csvRow[3]) == $pass)
                {
                    $loggedIn = true;
                    $_SESSION["loggedIn"] = true;
                    $_SESSION["username"] = $csvRow[1];
                    //$_SESSION["userPic"] = $csvRow[4];
                    return true;
                }
            }
            fclose($csv);
            if(!$loggedIn)
            {
                return false;
            }
        }
        else
        {
            throw new Exception("A tartalmakat tároló CSV feldolgozása meghiúsult!");
            return false;
        }
    }

    public static function Register(array $data): bool
    {
    global $cfg;


    if (file_exists($cfg["contentFolder"] . "/login.csv")) {

        $csv = fopen($cfg["contentFolder"] . "/login.csv", "r");
        $registered = false;

        while (!$registered && !feof($csv)) {
            $csvRow = fgetcsv($csv, null, ";");
            if ($csvRow === false)
            {
                continue;
            }
            if ($csvRow[2] == $data[2])
            {
                $registered = true;
            }
        }
        fclose($csv);

        if (!$registered) {
            $csv = fopen($cfg["contentFolder"] . "/login.csv", "a");
            if (fputcsv($csv, $data, ";") === false) {
                fclose($csv);
                throw new Exception("Hiba történt az adatok hozzáadása közben!");
            }
            fclose($csv);
            return true;
        } else {
            return false;
        }
    } else {
        throw new Exception("A tartalmakat tároló CSV feldolgozása meghiúsult!");
    }
    }

    public static function UploadReceptDB(array $data): bool
    {
        global $cfg;
        
        try
        {
            $mysqli = new mysqli($cfg["DBhostname"], $cfg["DBusername"], $cfg["DBPass"], $cfg["DB"]);

            // Check connection
            if ($mysqli->connect_error) {
                return false;
            }

            $formattedString = implode(", ", array_map(function($value) {
                if (is_null($value)) {
                    return "NULL";
                } elseif (is_string($value)) {
                    return "'" . addslashes($value) . "'";
                } else {
                    return $value;
                }
            }, $data));

        
            $mysqli->query("INSERT INTO `recept` VALUES ($formattedString) ;");
            return true;
        }
        catch(error_log)
        {
            //print(error_log);
            return false;
        }
        $mysqli->close();
    }


}