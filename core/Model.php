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
            $stmt->bind_param("sssiisis", $data["recept"]["recept_neve"],$data["recept"]["kategoria"],$data["recept"]["leiras"],$data["recept"]["elk_ido"],$data["recept"]["adag"],$data["recept"]["nehezseg"],$data["recept"]["felh_id"],$data["recept"]["pic_name"]);
            $stmt->execute();
            $newId = self::$con->insert_id;
            $stmt->close();

            foreach ($data["hozzavalok"] as $item) {
                $ing_stmt = self::$con->prepare("INSERT INTO `hozzavalok`(`recept_id`, `nev`,`mennyiseg`,`mertekegyseg`) VALUES (?,?,?,?)");
                $ing_stmt->bind_param("isss", $newId, $item["nev"], $item["mennyiseg"], $item["mertekegyseg"]);
                $ing_stmt->execute();
                $ing_stmt->close();
            }

        }
        catch (Exception $ex)
        {
            throw new SQLException("A recept feltöltése sikertelen!", $ex);
        }
    }


    public static function GetRecepiesDB(string $query = "", int $limit = 9, ?int $userId = null): array
{
    if (!isset(self::$con) || self::$con === false) {
        throw new SQLException("Az adatbázishoz még nem jött létre kapcsolat!", null);
    }

    try {
        // Keresési kifejezés előkészítése
        $searchQuery = "%$query%";

        // Dinamikus WHERE feltétel létrehozása
        $userCondition = $userId !== null ? "AND r.felh_id = ?" : "";

        // Teljes találatok számának lekérdezése
        $countStmt = self::$con->prepare("
            SELECT COUNT(*) AS total_count
            FROM recept r
            LEFT JOIN felhasznalok f ON r.felh_id = f.felh_id
            WHERE r.recept_neve LIKE ? $userCondition
        ");
        
        if ($userId !== null) {
            $countStmt->bind_param("si", $searchQuery, $userId);
        } else {
            $countStmt->bind_param("s", $searchQuery);
        }
        
        $countStmt->execute();
        $countResult = $countStmt->get_result()->fetch_assoc();
        $totalCount = $countResult['total_count'];
        $countStmt->close();

        // Adatok lekérdezése LIMIT-el
        $stmt = self::$con->prepare("
            SELECT
                r.recept_id,
                r.recept_neve,
                r.elk_ido,
                r.adag,
                r.nehezseg,
                r.pic_name,
                COALESCE(f.veznev, 'Nincs adat') AS veznev,
                COALESCE(f.kernev, 'Nincs adat') AS kernev,
                COALESCE(AVG(rv.ertekeles), 0) AS avg_ertekeles
            FROM
                recept r
            LEFT JOIN
                felhasznalok f ON r.felh_id = f.felh_id
            LEFT JOIN
                reviews rv ON r.recept_id = rv.recept_id
            WHERE
                r.recept_neve LIKE ? $userCondition
            GROUP BY
                r.recept_id, r.recept_neve, r.elk_ido, r.adag, r.nehezseg, r.pic_name
            LIMIT ?
        ");
        
        if ($userId !== null) {
            $stmt->bind_param("sii", $searchQuery, $userId, $limit);
        } else {
            $stmt->bind_param("si", $searchQuery, $limit);
        }

        $stmt->execute();

        // Adatok beolvasása
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();

        // Eredmények visszaadása találatok számával együtt
        return [
            'total_count' => $totalCount,
            'results' => $data
        ];
    } catch (Exception $ex) {
        throw new SQLException("A receptek lekérdezése sikertelen!", $ex);
    }
}
    public static function GetOneRecpieDB(int $recept_id) : array
    {
        if(!isset(self::$con) || self::$con === false)
        {
            throw new SQLException("Az adatbázishoz még nem jött létre kapcsolat!", null);
        }

        try
        {
            $stmt = self::$con->prepare("SELECT * FROM `recept` WHERE `recept_id` = ?");
            $stmt->bind_param("i", $recept_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data["recept_adatok"] = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
            $stmt->close();

            $stmt = self::$con->prepare("SELECT * FROM `hozzavalok` WHERE `recept_id` = ?");
            $stmt->bind_param("i", $recept_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data["hozzavalok"] = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
            $stmt->close();

            $stmt = self::$con->prepare("SELECT
                                            r.*,
                                            AVG(r.`ertekeles`) AS `avg_ertekeles`,
                                             COUNT(r.`komment`) AS `comment_count`
                                        FROM
                                            `reviews` r
                                        WHERE
                                            `recept_id` = ?
                                        GROUP BY
                                            r.`review_id`
                                        ");
            $stmt->bind_param("i", $recept_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data["reviews"] = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
            $stmt->close();

            return $data;
        }
        catch (Exception $ex)
        {
            throw new SQLException("Az adatok lekérdezése sikertelen!", $ex);
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
            $stmt->bind_param("sssss", $datas["veznev"], $datas["kernev"], $datas["email"], $datas["password_hash"], $datas["pic_name"]);
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