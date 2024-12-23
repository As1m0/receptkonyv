<?php

abstract class Model
{

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
    


    // Use DBHandler class


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

    public static function RecepieFullData(int $recept_id): array
    {
        $data = [];

        $result1 = DBHandler::RunQuery("SELECT * FROM recept WHERE recept_id = ?", [new DBParam(DBTypes::Int, $recept_id)]);
        $data["recept_adatok"] = $result1->fetch_all(MYSQLI_ASSOC);

        $result2 = DBHandler::RunQuery("SELECT * FROM hozzavalok WHERE recept_id = ?", [new DBParam(DBTypes::Int, $recept_id)]);
        $data["hozzavalok"] = $result2->fetch_all(MYSQLI_ASSOC);


        $result3= DBHandler::RunQuery("
                                   SELECT
                                    r.*,
                                    (SELECT AVG(r2.ertekeles) FROM reviews r2 WHERE r2.recept_id = r.recept_id) AS avg_ertekeles,
                                    (SELECT COUNT(r3.komment) FROM reviews r3 WHERE r3.recept_id = r.recept_id AND r3.komment != '') AS comment_count,
                                    (SELECT COUNT(r4.ertekeles) FROM reviews r4 WHERE r4.recept_id = r.recept_id) AS ertekeles_count,
                                    COALESCE(f.veznev, null) AS veznev,
                                    COALESCE(f.kernev, null) AS kernev,
                                    COALESCE(f.pic_name, null) AS pic_name
                                    FROM
                                    reviews r
                                     LEFT JOIN
                                    felhasznalok f ON r.felh_id = f.felh_id
                                     WHERE
                                    r.recept_id = ?
                                ", [new DBParam(DBTypes::Int, $recept_id)]);

        $data["reviews"] = $result3->fetch_all(MYSQLI_ASSOC);

        return $data;
    }


    public static function UploadReview(array $data) : void
    {
        DBHandler::RunQuery(
        "INSERT INTO `reviews` (`recept_id`, `felh_id`, `komment`, `ertekeles`) VALUES (?,?,?,?)",
        [ new DBParam(DBTypes::Int, $data["recept_id"]),
        new DBParam(DBTypes::Int, $data["felh_id"]),
        new DBParam(DBTypes::String, $data["komment"]),
        new DBParam(DBTypes::Int, $data["ertekeles"])] );
    }


    public static function UploadRecept(array $data): void
    {
            $insert_id = DBHandler::RunQuery("INSERT INTO `recept` (`recept_neve`, `kategoria`, `leiras`, `elk_ido`, `adag`, `nehezseg`, `felh_id`, `pic_name`) VALUES (?,?,?,?,?,?,?,?)",
            [ new DBParam(DBTypes::String, $data["recept"]["recept_neve"]),
            new DBParam(DBTypes::String, $data["recept"]["kategoria"]),
            new DBParam(DBTypes::String, $data["recept"]["leiras"]),
            new DBParam(DBTypes::Int, $data["recept"]["elk_ido"]),
            new DBParam(DBTypes::Int, $data["recept"]["adag"]),
            new DBParam(DBTypes::String, $data["recept"]["nehezseg"]),
            new DBParam(DBTypes::Int, $data["recept"]["felh_id"]),
            new DBParam(DBTypes::String, $data["recept"]["pic_name"])
            ],
            true);

            foreach ($data["hozzavalok"] as $item) {
                DBHandler::RunQuery("INSERT INTO `hozzavalok`(`recept_id`, `nev`,`mennyiseg`,`mertekegyseg`) VALUES (?,?,?,?)",
                [ new DBParam(DBTypes::Int, $insert_id),
                new DBParam(DBTypes::String, $item["nev"]),
                new DBParam(DBTypes::String, $item["mennyiseg"]),
                new DBParam(DBTypes::String, $item["mertekegyseg"]) ]);
            }
    }

    public static function GetRecepies(string $query = "", int $limit = 9, ?int $userId = null): array
    {

        // Keresési kifejezés előkészítése
        $searchQuery = "%$query%";

        // Dinamikus WHERE feltétel létrehozása
        $userCondition = $userId !== null ? "AND r.felh_id = ?" : "";

        if ($userId !== null) {
            $param = [new DBParam(DBTypes::String, $searchQuery), new DBParam(DBTypes::Int, $userId)];
            }
            else
            {
            $param =  [new DBParam(DBTypes::String, $searchQuery)];
            }
            
        // Találatok számának lekérdezése
        $countStmt = DBHandler::RunQuery("
            SELECT COUNT(*) AS total_count
            FROM recept r
            LEFT JOIN felhasznalok f ON r.felh_id = f.felh_id
            WHERE r.recept_neve LIKE ? $userCondition
            ", $param);
    
        $countResult = $countStmt->fetch_assoc();
        $totalCount = $countResult['total_count'];

        // Adatok lekérdezése LIMIT-el
        if ($userId !== null) {
            $param2 = [new DBParam(DBTypes::String, $searchQuery), new DBParam(DBTypes::Int, $userId), new DBParam(DBTypes::Int, $limit)];
            }
            else
            {
            $param2 =  [new DBParam(DBTypes::String, $searchQuery), new DBParam(DBTypes::Int, $limit)];
            }
        
        $result = DBHandler::RunQuery("
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
        ",
        $param2
        );

        // Adatok beolvasása
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Eredmények visszaadása találatok számával együtt
        return [
            'total_count' => $totalCount,
            'results' => $data
        ];
    }

    public static function GetRecentRecepies($limit = 4) : array
    {
        $result = DBHandler::RunQuery("SELECT `recept_id`, `recept_neve`, `elk_ido`, `nehezseg`, `felh_id`, `pic_name`, `adag` FROM `recept` ORDER BY `created_at` LIMIT ?", [new DBParam(DBTypes::Int, $limit)]);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

}

