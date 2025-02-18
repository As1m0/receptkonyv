<?php

abstract class RecepieHandler
{
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

        if(isset($data["recept_adatok"][0]["felh_id"]))
        {
            $result4 = DBHandler::RunQuery("SELECT `veznev`, `kernev` FROM `felhasznalok` WHERE `felh_id` = ?", [new DBParam(DBTypes::Int, $data["recept_adatok"][0]["felh_id"])]);

            $data["felhasznalo"] = $result4->fetch_all(MYSQLI_ASSOC);
        }


        return $data;
    }

    public static function GetRecepies(string $query = "", int $limit = 50, ?int $userId = null): array
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
            new DBParam(DBTypes::Double, $item["mennyiseg"]),
            new DBParam(DBTypes::String, $item["mertekegyseg"]) ]);
        }
    }

    public static function UpdateRecept(array $data): void
    {

        //update recepie data
        if($data["recept"]["pic_name"] != "")
        {
            DBHandler::RunQuery("UPDATE `recept` SET `recept_neve` = ?, `kategoria` = ?, `leiras` = ?, `elk_ido` = ?, `adag` = ?, `nehezseg` = ?, `pic_name` = ? WHERE `recept_id` = ?",
        [ new DBParam(DBTypes::String, $data["recept"]["recept_neve"]),
                new DBParam(DBTypes::String, $data["recept"]["kategoria"]),
                new DBParam(DBTypes::String, $data["recept"]["leiras"]),
                new DBParam(DBTypes::Int, $data["recept"]["elk_ido"]),
                new DBParam(DBTypes::Int, $data["recept"]["adag"]),
                new DBParam(DBTypes::String, $data["recept"]["nehezseg"]),
                new DBParam(DBTypes::String, $data["recept"]["pic_name"]),
                new DBParam(DBTypes::Int, $data["recept_id"])
                ]);
        }
        elseif($data["recept"]["pic_name"] == "")
        {
            DBHandler::RunQuery("UPDATE `recept` SET `recept_neve` = ?, `kategoria` = ?, `leiras` = ?, `elk_ido` = ?, `adag` = ?, `nehezseg` = ? WHERE `recept_id` = ?",
            [ new DBParam(DBTypes::String, $data["recept"]["recept_neve"]),
                    new DBParam(DBTypes::String, $data["recept"]["kategoria"]),
                    new DBParam(DBTypes::String, $data["recept"]["leiras"]),
                    new DBParam(DBTypes::Int, $data["recept"]["elk_ido"]),
                    new DBParam(DBTypes::Int, $data["recept"]["adag"]),
                    new DBParam(DBTypes::String, $data["recept"]["nehezseg"]),
                    new DBParam(DBTypes::Int, $data["recept_id"])
                    ]);
        }

        //delete current ingredients
        DBHandler::RunQuery("DELETE FROM `hozzavalok` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $data["recept_id"])]);

        //upload new ingredients
        foreach ($data["hozzavalok"] as $item) {
            DBHandler::RunQuery("INSERT INTO `hozzavalok`(`recept_id`, `nev`,`mennyiseg`,`mertekegyseg`) VALUES (?,?,?,?)",
            [ new DBParam(DBTypes::Int, $data["recept_id"]),
            new DBParam(DBTypes::String, $item["nev"]),
            new DBParam(DBTypes::Double, $item["mennyiseg"]),
            new DBParam(DBTypes::String, $item["mertekegyseg"]) ]);
        }
    }

    public static function GetLatestRecepies($limit) : array
    {
        $result = DBHandler::RunQuery("SELECT `recept_id`, `recept_neve`, `elk_ido`, `nehezseg`, `felh_id`, `pic_name`, `adag` FROM `recept` ORDER BY `created_at` DESC LIMIT ?",
                                        [new DBParam(DBTypes::Int, $limit)]);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function GetRecepieImgName(int $receptId): array
    {
       $result = DBHandler::RunQuery("SELECT `pic_name` FROM `recept` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $receptId)]);
       return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function DeleteRecepie(int $receptId): void
    {
        DBHandler::RunQuery("DELETE FROM `hozzavalok` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $receptId)]);
        DBHandler::RunQuery("DELETE FROM `reviews` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $receptId)]);
        DBHandler::RunQuery("DELETE FROM `recept` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $receptId)]);
    }


}