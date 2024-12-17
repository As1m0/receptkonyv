<?php

abstract class Model
{
    private static mysqli $con;

    public static function GetPageData(string $page) : array
    {
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
    


    public static function Connect() : void
    {
        global $cfg;
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        try
        {
            self::$con = new mysqli($cfg["DBhostname"], $cfg["DBusername"], $cfg["DBPass"], $cfg["DB"], $cfg["PORT"]);
        }
        catch (Exception $ex)
        {
            throw new SQLException("Az adatbázis csatlakozás sikertelen!", $ex);
        }
    }
    
    public static function Disconnect() : void
    {
        try
        {
            self::$con->close();
        }
        catch (Exception $ex)
        {
            throw new SQLException("Az adatbázis lecsatlakozása sikertelen!", $ex);
        }
    }

    public static function UploadReceptDB(array $data): void
    {

        if(!isset(self::$con) || self::$con === false)
        {
            throw new SQLException("Az adatbázishoz még nem jött létre kapcsolat!", null);
        }
        
        try
        {
            $stmt = self::$con->prepare("INSERT INTO `recept` (`recept_neve`, `kategoria`, `leiras`, `elk_ido`, `adag`, `nehezseg`, `felh_id`, `pic_name`) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssiisis", $data["recept_neve"],$data["kategoria"],$data["leiras"],$data["elk_ido"],$data["adag"],$data["nehezseg"],$data["felh_id"],$data["pic_name"]);
            $stmt->execute();
            $stmt->close();

        }
        catch (Exception $ex)
        {
            throw new SQLException("A recept feltöltése sikertelen!", $ex);
        }
    }

    public static function UploadIngredientsDB($data): void 
    {
        if (!isset(self::$con) || self::$con === false) {
            throw new SQLException("Az adatbázishoz még nem jött létre kapcsolat!", null);
        }

        try {
            foreach ($data as $item) {
                $stmt = self::$con->prepare("INSERT INTO `hozzavalok` (`nev`,`mennyiseg`,`mertekegyseg`) VALUES (?,?,?)");
                $stmt->bind_param("sss", $item["nev"], $item["mennyiseg"], $item["mertekegyseg"]);
                $stmt->execute();
                $stmt->close();
            }
        } catch (Exception $ex) {
            throw new SQLException("A hozzávalók feltöltése sikertelen!", $ex);
        }
    }


    public static function GetRecepiesDB(string $query = ""): array
    {
        if(!isset(self::$con) || self::$con === false)
        {
            throw new SQLException("Az adatbázishoz még nem jött létre kapcsolat!", null);
        }

        try
        {

            $stmt = self::$con->prepare("SELECT * FROM `recept` WHERE `recept_neve` LIKE ? LIMIT 9");
            $likeQuery = "%" . $query . "%"; // Részleges keresés helyettesítő karakterekkel
            $stmt->bind_param("s", $likeQuery);
            $stmt->execute();

            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);

            $result->close();
            $stmt->close();

            return $data;
        }
        catch (Exception $ex)
        {
            throw new SQLException("A receptek lekérdezése sikertelen!", $ex);
        }
    }

    public static function RegisterDB(array $datas):void
    {
        if(!isset(self::$con) || self::$con === false)
        {
            throw new SQLException("Az adatbázishoz még nem jött létre kapcsolat!", null);
        }
        try
        {
            $stmt = self::$con->prepare("INSERT INTO `felhasznalok` (`veznev`,`kernev`,`email`,`password_hash`, `pic_name`) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $datas["veznev"], $datas["kernev"], $datas["email"],$datas["password_hash"], $datas["pic_name"]);
            $stmt->execute();
            $stmt->close();
        }
        catch (Exception $ex)
        {
            throw new SQLException("A felhasználó rögzítése sikertelen!", $ex);
        }
    }

    public static function LoginDB($datas):bool
    {
        if(!isset(self::$con) || self::$con === false)
        {
            throw new SQLException("Az adatbázishoz még nem jött létre kapcsolat!", null);
        }
        try
        {
            $stmt = self::$con->prepare("SELECT * FROM `felhasznalok` WHERE `email` = ? AND `password_hash` = ?");
            $stmt->bind_param("ss", $datas["email"], $datas["password_hash"]);
            $stmt->execute();

            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);

            $result->close();
            $stmt->close();

            if(!empty($data))
            {
                //save user info to SESSION
                $_SESSION["loggedIn"] = true;
                $_SESSION["userID"] = $data[0]["felh_id"];
                $_SESSION["username"] =  $data[0]["kernev"];
                $_SESSION["userfullname"] = $data[0]["veznev"] . " " . $data[0]["kernev"];
                if ($data[0]["pic_name"] !== null)
                {
                    $_SESSION["userpic"] = $data[0]["pic_name"];
                }
                else
                {
                    $_SESSION["userpic"] = "empty_profilPic";
                }
                $_SESSION["usermail"] = $data[0]["email"];
                return true;
            }
            else
            {
                return false;
            }

        }
        catch (Exception $ex)
        {
            throw new SQLException("A bejelentkezés sikertelen!", $ex);
        }
    }


}