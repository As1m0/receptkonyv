<?php

abstract class Model
{

    public static function CheckNewRecepie($lastChecked): array
    {
        $result = DBHandler::RunQuery("SELECT `recept_neve`,`recept_id` FROM `recept` WHERE `created_at` > ? LIMIT 1", [ new DBParam( DBTypes::String, $lastChecked) ]);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function GetPageData(string $page) : array
    {
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

    public static function ModifyText(string $flag, string $content) : bool
    {
        try
        {
            DBHandler::RunQuery("UPDATE `content` SET `content` = ? WHERE `flag` = ?",
                [
                new DBParam(DBTypes::String, $content),
                new DBParam(DBTypes::String, $flag)
                ]);
            return true;
        }
        catch (Exception)
        {
            return false;
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
        try
        {
        return UserHandler::Login($email, $pass);
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }


    public static function Register(array $data): void
    {
        try
        {
            UserHandler::Register( $data);
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
        
    }

    public static function DeleteUser(int $id): void
    {
        try
        {
        UserHandler::DeleteUser($id);
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function UpdateUser(int $userID, string $veznev, string $kernev, string $email, int $groupMember) : void
    {
        try
        {
        UserHandler::UpdateUser($userID, $veznev, $kernev, $email, $groupMember);
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function GetAllUserData(): array
    {
        try
        {
       return UserHandler::GetAllUserData();
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }

  

    public static function UploadReview(array $data) : void
    {
        try
        {
        ReviewHandler::UploadReview($data);
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function DeleteReview(int $kommentID) : void
    {
        try
        {
        ReviewHandler::DeleteReview($kommentID);
    }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }


    public static function UploadRecept(array $data): void
    {
        try
        {
        RecepieHandler::UploadRecept( $data );
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function GetRecepies(string $query = "", int $limit = 50, ?int $userId = null): array
    {
        try
        {
        return RecepieHandler::GetRecepies($query, $limit, $userId);
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function RecepieFullData(int $recept_id): array
    {
        try
        {
        return RecepieHandler::RecepieFullData( $recept_id );
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function GetLatestRecepies($limit) : array
    {
        try
        {
        return RecepieHandler::GetLatestRecepies($limit);
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function DeleteRecepie(int $receptId): void
    {
        try
        {
        RecepieHandler::DeleteRecepie($receptId);
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }
 

    public static function GetNumbers() : array
    {
        try
        {
        $result1 = DBHandler::RunQuery("SELECT `recept_id` FROM `recept` WHERE 1", []);
        $result2 = DBHandler::RunQuery("SELECT `felh_id` FROM `felhasznalok` WHERE 1", []);

        $data["recept"] = count($result1->fetch_all(MYSQLI_ASSOC));
        $data["felh"] = count($result2->fetch_all(MYSQLI_ASSOC));

        return $data;
        }
        catch (Exception $ex)
        {
            throw new DBException($ex->GetMessage());
        }
    }

    public static function getDynamicQueryResults(array $serachData): mixed
    {
        // Base query
        $query = "SELECT * FROM recept";
        
        // Initialize an array for conditions and params
        $conditions = [];
        $params = [];

        // Add conditions for the dynamic search data

        // Check for searchKey and add condition
        if (isset($serachData["searchKey"])) {
            $conditions[] = "name LIKE ?";
            $params[] = new DBParam(DBTypes::String, "%" . $serachData["searchKey"] . "%");
        }

        // Check for category and add condition
        if (isset($serachData["category"])) {
            $conditions[] = "kategoria = ?";
            $params[] = new DBParam(DBTypes::String, $serachData["category"]);
        }

        // Check for time and add condition
        if (isset($serachData["time"])) {
            $conditions[] = "elk_ido = ?";
            $params[] = new DBParam(DBTypes::Int, $serachData["time"]);
        }

        // Check for difficulty and add condition
        if (isset($serachData["difficulty"])) {
            $conditions[] = "nehezseg = ?";
            $params[] = new DBParam(DBTypes::String, $serachData["difficulty"]);
        }

        // Check for rating and add condition
        if (isset($serachData["rating"])) {
            $conditions[] = "rating >= ?";
            $params[] = new DBParam(DBTypes::Int, $serachData["rating"]);
        }

        // If there are conditions, add them to the query
        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // Call the RunQuery method with the constructed query and parameters
        return [$query, $params];
        //return DBHandler::RunQuery($query, $params);
    }



}

