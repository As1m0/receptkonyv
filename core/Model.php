<?php

abstract class Model
{


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







    //                          USER FUNCTIONS                        //


    public static function Login(string $email, string $pass, bool $keep = false, string $token = null): bool
    {
        try {
            return UserHandler::Login($email, $pass, $keep, $token);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function Register(array $data): bool
    {
        try {
            return UserHandler::Register($data);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function DeleteLoginToken(): void{
        DBHandler::RunQuery("UPDATE `felhasznalok` SET `token` = NULL WHERE `felh_id` = ?", [new DBParam(DBTypes::Int, $_SESSION["userID"])]);
    }

    public static function DeleteUser(int $id): void
    {
        global $cfg;
        //delete all profil pics and recepie pics
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
        try {
            UserHandler::DeleteUser($id);
        } catch (Exception $ex) {
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

    public static function UpdateUserImg(string $imgName, int $felh_id, string $oldImgName = null): void
    {
        try {
            UserHandler::UpdateUserImg($imgName, $felh_id);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }

        //remove old images
        global $cfg;
        if ($oldImgName !== null) {
            if (file_exists($cfg["ProfilKepek"] . "/" . $oldImgName . "_thumb.jpg")) {
                unlink($cfg["ProfilKepek"] . "/" . $oldImgName . "_thumb.jpg");
            }
            if (file_exists($cfg["ProfilKepek"] . "/" . $oldImgName . ".jpg")) {
                unlink($cfg["ProfilKepek"] . "/" . $oldImgName . ".jpg");
            }
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










    //                      REVIEW FUNCTIONS                        //


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









    //                        RECEPIE FUNCTIONS                       //


    public static function CheckNewRecepie($lastChecked): array
    {
        $result = DBHandler::RunQuery("SELECT `recept_neve`,`recept_id` FROM `recept` WHERE `created_at` > ? LIMIT 1", [new DBParam(DBTypes::String, $lastChecked)]);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function UploadRecept(array $data): void
    {
        try {
            RecepieHandler::UploadRecept($data);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function saveToFavorites(int $userId, int $receptId): string
    {
        try {
            return RecepieHandler::saveToFavorites($userId, $receptId);
        } catch (Exception $e) {
            throw new DBException("Hiba a kedvenc recept rögzitése során!", 0, $e);
        }
    }

    public static function UpdateRecept(array $data): void
    {
        global $cfg;

        try {
            RecepieHandler::UpdateRecept($data);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }

        if ($data["recept"]["pic_name"] != "") {
            if (file_exists($cfg["receptKepek"] . "/" . $data["prev-img"] . "_thumb.jpg")) {
                unlink($cfg["receptKepek"] . "/" . $data["prev-img"] . "_thumb.jpg");
            }
            if (file_exists($cfg["receptKepek"] . "/" . $data["prev-img"] . ".jpg")) {
                unlink($cfg["receptKepek"] . "/" . $data["prev-img"] . ".jpg");
            }
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

    public static function RecepieFullData(int $recept_id, $user_id = null): array
    {
        try {
            return RecepieHandler::RecepieFullData($recept_id, $user_id);
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
        if (!empty($picture[0])) {
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

    public static function GetFavRecepies(int $userID): array
    {
        try {
            return RecepieHandler::GetFavRecepies($userID);
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

    public static function getDynamicQueryResults(array $serachData, bool $fullData = false, int $start_from = 0, int $results_per_page = 100, int $userID = null): array
    {
        try {
            return RecepieHandler::getDynamicQueryResults($serachData, $fullData, $start_from, $results_per_page, $userID);
        } catch (Exception $ex) {
            throw new DBException($ex->GetMessage());
        }
    }


}

