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
            throw new  ("A tartalmakat tároló JSON feldolgozása meghiúsult!");
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
            self::$con = new mysqli($cfg["db"]["hostname"], $cfg["db"]["username"], $cfg["db"]["pass"], $cfg["db"]["db"], $cfg["db"]["port"]);
        }
        catch (Exception $ex)
        {
            throw new DBException("Az adatbázis csatlakozás sikertelen!", $ex);
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
            throw new DBException("A recept feltöltése sikertelen!", $ex);
        }
    }

    public static function UploadReviewDB(array $data) : void
    {
        if(!isset(self::$con) || self::$con === false)
        {
            throw new DBException("Az adatbázishoz még nem jött létre kapcsolat!", null);
        }
        
        try
        {
            $stmt = self::$con->prepare("INSERT INTO `reviews` (`recept_id`, `felh_id`, `komment`, `ertekeles`) VALUES (?,?,?,?)");
            $stmt->bind_param("iisi", $data["recept_id"], $data["felh_id"], $data["komment"], $data["ertekeles"]);
            $stmt->execute();
            $stmt->close();
        }
        catch (Exception $ex)
        {
            throw new DBException("A recept feltöltése sikertelen!", $ex);
        }
    }

    public static function GetRecepiesDB(string $query = "", int $limit = 9, ?int $userId = null): array
    {
    if (!isset(self::$con) || self::$con === false) {
        throw new DBException("Az adatbázishoz még nem jött létre kapcsolat!", null);
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
        throw new DBException("A receptek lekérdezése sikertelen!", $ex);
    }
}
    public static function GetOneRecpieDB(int $recept_id) : array
    {
        if(!isset(self::$con) || self::$con === false)
        {
            throw new DBException("Az adatbázishoz még nem jött létre kapcsolat!", null);
        }

        try {

            $data = [];
        

            $queries = [
                "recept_adatok" => "SELECT * FROM `recept` WHERE `recept_id` = ?",
                "hozzavalok" => "SELECT * FROM `hozzavalok` WHERE `recept_id` = ?",
                "reviews" => "
                    SELECT
                        r.*,
                        (SELECT AVG(r2.`ertekeles`) FROM `reviews` r2 WHERE r2.`recept_id` = r.`recept_id`) AS `avg_ertekeles`,
                        (SELECT COUNT(r3.`komment`) FROM `reviews` r3 WHERE r3.`recept_id` = r.`recept_id` AND r3.`komment` != '') AS `comment_count`,
                        (SELECT COUNT(r4.`ertekeles`) FROM `reviews` r4 WHERE r4.`recept_id` = r.`recept_id`) AS `ertekeles_count`,
                        COALESCE(f.`veznev`, null) AS `veznev`,
                        COALESCE(f.`kernev`, null) AS `kernev`,
                        COALESCE(f.`pic_name`, null) AS `pic_name`
                    FROM
                        `reviews` r
                    LEFT JOIN
                        `felhasznalok` f ON r.`felh_id` = f.`felh_id`
                    WHERE
                        r.`recept_id` = ?"
            ];
        
            foreach ($queries as $key => $sql) {
                $stmt = self::$con->prepare($sql);
                $stmt->bind_param("i", $recept_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $data[$key] = $result->fetch_all(MYSQLI_ASSOC);
                $result->close();
                $stmt->close();
            }
        
            return $data;

        }
        catch (Exception $ex)
        {
            throw new DBException("Az adatok lekérdezése sikertelen!", $ex);
        }
    }





    // Use DBHandler


    public static function Login(string $email, string $pass): bool
    {
        $result = DBHandler::RunQuery("SELECT * FROM `felhasznalok` WHERE email = ? AND `password_hash` = ?",
        [ new DBParam(DBTypes::String, $email), new DBParam(DBTypes::String, $pass)] );
        if($result->num_rows > 0)
        {
            $data = $result->fetch_assoc();
            if(!empty($data))
            {
                //save user info to SESSION
                $_SESSION["loggedIn"] = true;
                $_SESSION["userID"] = $data["felh_id"];
                $_SESSION["username"] =  $data["kernev"];
                $_SESSION["userfullname"] = $data["veznev"] . " " . $data["kernev"];
                if ($data["pic_name"] !== null)
                {
                    $_SESSION["userpic"] = $data["pic_name"];
                }
                else
                {
                    $_SESSION["userpic"] = "empty_profilPic";
                }
                $_SESSION["usermail"] = $data["email"];
                return true;
            }
        }
        else
        {
            return false;
        }
    }


    public static function Register(array $data): void
    {
        DBHandler::RunQuery("INSERT INTO `felhasznalok` (`veznev`,`kernev`,`email`,`password_hash`, `pic_name`) VALUES (?,?,?,?,?)",
        [ new DBParam(DBTypes::String, $data["veznev"]),
        new DBParam(DBTypes::String, $data["kernev"]),
        new DBParam(DBTypes::String, $data["email"]),
        new DBParam(DBTypes::String, $data["password_hash"]),
        new DBParam(DBTypes::String, $data["pic_name"]) ]);
    }


}

