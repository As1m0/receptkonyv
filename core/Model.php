<?php

abstract class Model
{

    public static function CheckNewRecepie($lastChecked): array
    {
        $result = DBHandler::RunQuery("SELECT `recept_neve`,`recept_id` FROM `recept` WHERE `created_at` > ? LIMIT 1", [new DBParam(DBTypes::String, $lastChecked)]);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function GetPageData(string $page): array
    {
        $result = DBHandler::RunQuery("SELECT * FROM `pages` WHERE `pageKey` = ?", [new DBParam(DBTypes::String, $page)]);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            throw new NotFoundException("A megadott oldal nem található!");
        }
    }

    public static function LoadText(string|null $flag = null): mixed
    {
        if ($flag != null) {
            $result = DBHandler::RunQuery("SELECT `content` FROM `content` WHERE `flag` = ?", [new DBParam(DBTypes::String, $flag)]);
        } else {
            $result = DBHandler::RunQuery("SELECT * FROM `content` WHERE 1", []);
        }

        if ($result->num_rows > 0 && $flag != null) {
            $data = $result->fetch_assoc();
            return $data["content"];
        } elseif ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new NotFoundException("A  megadott flag ($flag) nem található az adatbázisban!");
        }
    }

    public static function ModifyText(string $flag, string $content): bool
    {
        try {
            DBHandler::RunQuery(
                "UPDATE `content` SET `content` = ? WHERE `flag` = ?",
                [
                    new DBParam(DBTypes::String, $content),
                    new DBParam(DBTypes::String, $flag)
                ]
            );
            return true;
        } catch (Exception) {
            return false;
        }
    }

    public static function GetModules(string|null $module = null): array
    {
        global $cfg;
        if ($module == null) {
            $result = DBHandler::RunQuery("SELECT * FROM `modules`");
        } else {
            $result = DBHandler::RunQuery("SELECT * FROM `modules` WHERE `name` = ?", [new DBParam(DBTypes::String, $module)]);
        }
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new NotFoundException("A megadott modul, vagy összességében modul, nem található!");
        }
    }

    public static function GetSearchLog(): array
    {
        global $cfg;
        $data = array();
        $logFilePath = $cfg["contentFolder"] . "/search_log.log";
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
        try {
            return UserHandler::Login($email, $pass);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }


    public static function Register(array $data): void
    {
        try {
            UserHandler::Register($data);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }

    }

    public static function DeleteUser(int $id): void
    {
        //delete all profil pics and recepie pics
        global $cfg;
        $pictures = UserHandler::GetImages($id);
        if (!empty($pictures[0])) {
            foreach ($pictures[0] as $picture) {
                if (file_exists($cfg["ProfilKepek"] . "/" . $picture . "_thumb.jpg")) {
                    unlink($cfg["ProfilKepek"] . "/" . $picture . "_thumb.jpg");
                }
                if (file_exists($cfg["ProfilKepek"] . "/" . $picture . ".jpg")) {
                    unlink($cfg["ProfilKepek"] . "/" . $picture . ".jpg");
                }
            }
        }
        if (!empty($pictures[1])) {
            foreach ($pictures[1] as $picture) {
                if (file_exists($cfg["receptKepek"] . "/" . $picture["pic_name"] . "_thumb.jpg")) {
                    unlink($cfg["receptKepek"] . "/" . $picture["pic_name"] . "_thumb.jpg");
                }
                if (file_exists($cfg["receptKepek"] . "/" . $picture["pic_name"] . ".jpg")) {
                    unlink($cfg["receptKepek"] . "/" . $picture["pic_name"] . ".jpg");
                }
            }
        }
        //delete from DB
        try
        {
        UserHandler::DeleteUser($id);
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function UpdateUser(int $userID, string $veznev, string $kernev, string $email, int $groupMember): void
    {
        try {
            UserHandler::UpdateUser($userID, $veznev, $kernev, $email, $groupMember);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function GetAllUserData(): array
    {
        try {
            return UserHandler::GetAllUserData();
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }



    public static function UploadReview(array $data): void
    {
        try {
            ReviewHandler::UploadReview($data);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function DeleteReview(int $kommentID): void
    {
        try {
            ReviewHandler::DeleteReview($kommentID);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }


    public static function UploadRecept(array $data): void
    {
        try {
            RecepieHandler::UploadRecept($data);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function UpdateRecept(array $data): void
    {
        global $cfg;
        if($data["recept"]["pic_name"] != "")
        {
            if (file_exists($cfg["receptKepek"] . "/" . $data["prev-img"] . "_thumb.jpg")) {
                unlink($cfg["receptKepek"] . "/" . $data["prev-img"] . "_thumb.jpg");
            }
            if (file_exists($cfg["receptKepek"] . "/" .  $data["prev-img"] . ".jpg")) {
                unlink($cfg["receptKepek"] . "/" . $data["prev-img"] . ".jpg");
            }
        }
        
        try {
            RecepieHandler::UpdateRecept($data);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function GetRecepies(string $query = "", int $limit = 50, ?int $userId = null): array
    {
        try {
            return RecepieHandler::GetRecepies($query, $limit, $userId);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function RecepieFullData(int $recept_id): array
    {
        try {
            return RecepieHandler::RecepieFullData($recept_id);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function GetLatestRecepies($limit): array
    {
        try {
            return RecepieHandler::GetLatestRecepies($limit);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function DeleteRecepie(int $receptId): void
    {
        global $cfg;
        //delere recept pictures
        $picture = RecepieHandler::GetRecepieImgName($receptId);
        if(!empty($picture[0]))
        {
            if (file_exists($cfg["receptKepek"] . "/" . $picture[0]["pic_name"] . "_thumb.jpg")) {
                unlink($cfg["receptKepek"] . "/" . $picture[0]["pic_name"] . "_thumb.jpg");
            }
            if (file_exists($cfg["receptKepek"] . "/" . $picture[0]["pic_name"] . ".jpg")) {
                unlink($cfg["receptKepek"] . "/" . $picture[0]["pic_name"] . ".jpg");
            }
        }
        //delte from DB
        try {
            RecepieHandler::DeleteRecepie($receptId);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }


    public static function GetNumbers(): array
    {
        try {
            $result1 = DBHandler::RunQuery("SELECT `recept_id` FROM `recept` WHERE 1", []);
            $result2 = DBHandler::RunQuery("SELECT `felh_id` FROM `felhasznalok` WHERE 1", []);

            $data["recept"] = count($result1->fetch_all(MYSQLI_ASSOC));
            $data["felh"] = count($result2->fetch_all(MYSQLI_ASSOC));

            return $data;
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function getDynamicQueryResults(array $serachData, bool $fullData = false, int $start_from = 0, int $results_per_page = 100): array
    {

        // Initialize an array for conditions and params
        $conditions = [];
        $params = [];

        // Check for searchKey and add condition
        if (isset($serachData["searchKey"])) {
            $conditions[] = "`recept_neve` LIKE ?";
            $params[] = new DBParam(DBTypes::String, "%" . $serachData["searchKey"] . "%");
        }

        // Check for category and add condition
        if (isset($serachData["category"])) {
            $conditions[] = "`kategoria` = ?";
            $params[] = new DBParam(DBTypes::String, $serachData["category"]);
        }



        // Check for difficulty and add condition
        if (isset($serachData["difficulty"])) {
            $conditions[] = "`nehezseg` = ?";
            $params[] = new DBParam(DBTypes::String, $serachData["difficulty"]);
        }

        // Check for rating and add condition
        if (isset($serachData["rating"])) {
            switch ($serachData["rating"]) {
                case "1":
                    $ratingQuery = "1";
                    break;
                case "2":
                    $ratingQuery = "BETWEEN 2 AND 3";
                    break;
                case "3":
                    $ratingQuery = "BETWEEN 3 AND 4";
                    break;
                case "4":
                    $ratingQuery = "BETWEEN 4 AND 5";
                    break;
                case "5":
                    $ratingQuery = "5";
                    break;
                default:
                    $ratingQuery = "0";
            }
            $ratingCond = "HAVING `avg_ertekeles` >= ?";
            $params[] = new DBParam(DBTypes::String, $ratingQuery);
        } else {
            $ratingCond = "";
        }

        // Check for time and add condition
        if (isset($serachData["time"])) {
            $conditions[] = "`elk_ido` " . $serachData["time"];
        }


        if (count($conditions) > 0) {
            $finalconditions = implode(" AND ", $conditions);
        } else {
            $finalconditions = "1";
        }

        if ($fullData) {
            $fullquery = "
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
                " . $finalconditions . "
            GROUP BY
                r.recept_id, r.recept_neve, r.elk_ido, r.adag, r.nehezseg, r.pic_name
                " . $ratingCond . "
            LIMIT " . $start_from . "," . $results_per_page;
        } else {
            $fullquery = "
            SELECT
                recept_id
            FROM
                recept
            WHERE
                " . $finalconditions . "
                " . $ratingCond . "
            LIMIT " . $start_from . "," . $results_per_page;
        }

        $result = DBHandler::RunQuery($fullquery, $params);
        return $result->fetch_all(MYSQLI_ASSOC);

    }



}

