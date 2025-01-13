<?php

abstract class Model
{

    public static function GetPageData(string $page) : array
    {
          //return ["page" => $page, "template" => "main.html", "fullTemplate" => false, "Class" => "IndexPage", "parent" => null | <pageKey>];
          $result = DBHandler::RunQuery("SELECT * FROM `pages` WHERE `pageKey` = ?", [new DBParam(DBTypes::String, $page)]);
          if($result->num_rows > 0)
          {
              return $result->fetch_assoc();
          }
          else
          {
              throw new NotFoundException("A megadott oldal nem található!");
          }
    }
    
    public static function LoadText(string|null $flag = null) : mixed
    {       
            if($flag != null)
            {
                $result = DBHandler::RunQuery("SELECT `content` FROM `content` WHERE `flag` = ?", [new DBParam(DBTypes::String, $flag)]);
            }
            else
            {
                $result = DBHandler::RunQuery("SELECT * FROM `content` WHERE 1", []);
            }
            
            if($result->num_rows > 0 && $flag != null)
            {
                $data = $result->fetch_assoc();
                return $data["content"];
            }
            elseif($result->num_rows > 0)
            {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
            else
            {
                throw new NotFoundException("A  megadott flag ($flag) nem található az adatbázisban!");
            }
    }
    
    public static function GetModules(string|null $module = null) : array
    {
        global $cfg;
        if($module == null)
        {
            $result = DBHandler::RunQuery("SELECT * FROM `modules`");
        }
        else
        {
            $result = DBHandler::RunQuery("SELECT * FROM `modules` WHERE `name` = ?", [new DBParam(DBTypes::String, $module)]);
        }
        if($result->num_rows > 0)
        {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            throw new NotFoundException("A megadott modul, vagy összességében modul, nem található!");
        }
    }

    public static function GetSearchLog(): array
    {
        global $cfg;
        $data = array();
        $logFilePath = $cfg["contentFolder"]."/search_log.log";
        if (file_exists($logFilePath)) {
            $handle = fopen($logFilePath, "r");
        
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                   $data[] = $line;
                }
                fclose($handle);
                return $data;
            } else {
                throw new Exception("Sikertelen a fájl olvasása.");
            }
        } else {
            throw new NotFoundException("A search log fájl nem elérhető");
        }

    }
    


    public static function Login(string $email, string $pass): bool
    {
        $result = DBHandler::RunQuery("SELECT * FROM `felhasznalok` WHERE email = ? AND `password_hash` = ?",
        [ new DBParam(DBTypes::String, $email), new DBParam(DBTypes::String, $pass)] );
        if($result->num_rows === 1)
        {
            $data = $result->fetch_assoc();
            if(!empty($data))
            {
                //save user info to SESSION
                $_SESSION["loggedIn"] = true;
                $_SESSION["userID"] = $data["felh_id"];
                $_SESSION["username"] =  $data["kernev"];
                $_SESSION["userfullname"] = $data["veznev"] . " " . $data["kernev"];
                $_SESSION["groupMember"] = $data["groupMember"];
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

    public static function GetLatestRecepies($limit) : array
    {
        $result = DBHandler::RunQuery("SELECT `recept_id`, `recept_neve`, `elk_ido`, `nehezseg`, `felh_id`, `pic_name`, `adag` FROM `recept` ORDER BY `created_at` DESC LIMIT ?",
                                        [new DBParam(DBTypes::Int, $limit)]);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function GetAllUserData(): array
    {
        $result = DBHandler::RunQuery("SELECT * FROM `felhasznalok` WHERE 1", []);
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public static function DeleteUser(int $id): void
    {
        DBHandler::RunQuery("DELETE FROM `reviews` WHERE `felh_id` = ?", [new DBParam(DBTypes::Int, $id)]);
        $result = DBHandler::RunQuery("SELECT `recept_id` FROM `recept` WHERE `felh_id` = ?", [new DBParam(DBTypes::Int, $id)]);
        $recept_ids = $result->fetch_all(MYSQLI_ASSOC);
        if(!empty($recept_ids)){
            foreach($recept_ids as $recept_id){
                DBHandler::RunQuery("DELETE FROM `hozzavalok` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $recept_id["recept_id"])]);
                DBHandler::RunQuery("DELETE FROM `recept` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $recept_id["recept_id"])]);
            }
        }
        DBHandler::RunQuery("DELETE FROM `felhasznalok` WHERE `felh_id` = ?", [new DBParam(DBTypes::Int, $id)]);
    }

    public static function UpdateUser(int $userID, string $veznev, string $kernev, string $email, int $groupMember) : void
    {
        DBHandler::RunQuery("UPDATE `felhasznalok` SET `veznev` = ?, `kernev` = ?, `email` = ?,  `groupMember` = ? WHERE `felh_id` = ?", [
            new DBParam(DBTypes::String, $veznev),
            new DBParam(DBTypes::String, $kernev),
            new DBParam(DBTypes::String, $email),
            new DBParam(DBTypes::Int, $groupMember),
            new DBParam(DBTypes::Int, $userID)
        ]);
    }

    public static function DeleteRecepie(int $receptId): void
    {
        DBHandler::RunQuery("DELETE FROM `hozzavalok` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $receptId)]);
        DBHandler::RunQuery("DELETE FROM `reviews` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $receptId)]);
        DBHandler::RunQuery("DELETE FROM `recept` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $receptId)]);
    }

    public static function ModifyText(string $flag, string $content) : bool
    {
        try{
            DBHandler::RunQuery("UPDATE `content` SET `content` = ? WHERE `flag` = ?",
                [
                new DBParam(DBTypes::String, $content),
                new DBParam(DBTypes::String, $flag)
                ]);
            return true;
            }
            catch(Exception)
            {
            return false;
            }
    }





}

