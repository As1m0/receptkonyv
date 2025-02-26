<?php

abstract class UserHandler
{
    public static function Login(string $email, string $pass, $keepLogin = false): bool
    {
        if($pass == "")
        {
            $result = DBHandler::RunQuery("SELECT * FROM `felhasznalok` WHERE `email` = ?", [ new DBParam(DBTypes::String, $email)]);
        }
        else
        {
            $result = DBHandler::RunQuery("SELECT * FROM `felhasznalok` WHERE email = ? AND `password_hash` = ?",
            [ new DBParam(DBTypes::String, $email), new DBParam(DBTypes::String, $pass)] );
        }
        
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

                if($keepLogin)
                {
                    setcookie("keeplogin", sha1($_SERVER["REMOTE_ADDR"]), time()+60*60*24*30*12);
                    setcookie("usermail", $data["email"], time()+60*60*24*30*12);
                }
                return true;
            }
        }
        else
        {
            return false;
        }
    }
    public static function Register(array $data): bool
    {
        //Check if the user exists
        $result = DBHandler::RunQuery("SELECT `email` from `felhasznalok` WHERE `email` = ?",
        [new DBParam(DBTypes::String, $data["email"])]);
        if(!empty($result->fetch_all(MYSQLI_ASSOC)))
        {
            return false;
        }

        DBHandler::RunQuery("INSERT INTO `felhasznalok` (`veznev`,`kernev`,`email`,`password_hash`, `pic_name`) VALUES (?,?,?,?,?)",
        [ new DBParam(DBTypes::String, $data["veznev"]),
        new DBParam(DBTypes::String, $data["kernev"]),
        new DBParam(DBTypes::String, $data["email"]),
        new DBParam(DBTypes::String, $data["password_hash"]),
        new DBParam(DBTypes::String, $data["pic_name"]) ]);
        return true;
    }
    public static function GetAllUserData(): array
    {
        $result = DBHandler::RunQuery("SELECT * FROM `felhasznalok` WHERE 1", []);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function GetOneUserData($userId): array
    {
        $result = DBHandler::RunQuery("SELECT * FROM `felhasznalok` WHERE `felh_id` = ?", [ new DBParam(DBTypes::Int, $userId)]);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function DeleteUser(int $id): void
    {
        DBHandler::RunQuery("DELETE FROM `favorites` WHERE `user_id` = ?", [new DBParam(DBTypes::Int, $id)]);
        DBHandler::RunQuery("DELETE FROM `reviews` WHERE `felh_id` = ?", [new DBParam(DBTypes::Int, $id)]);
        $result = DBHandler::RunQuery("SELECT `recept_id` FROM `recept` WHERE `felh_id` = ?", [new DBParam(DBTypes::Int, $id)]);
        $recept_ids = $result->fetch_all(MYSQLI_ASSOC);
        if(!empty($recept_ids)){
            foreach($recept_ids as $recept_id){
                DBHandler::RunQuery("DELETE FROM `hozzavalok` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $recept_id["recept_id"])]);
                DBHandler::RunQuery("DELETE FROM `reviews` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $recept_id["recept_id"])]);
                DBHandler::RunQuery("DELETE FROM `favorites` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $recept_id["recept_id"])]);
                DBHandler::RunQuery("DELETE FROM `recept` WHERE `recept_id` = ?", [new DBParam(DBTypes::Int, $recept_id["recept_id"])]);
            }
        }
        DBHandler::RunQuery("DELETE FROM `felhasznalok` WHERE `felh_id` = ?", [new DBParam(DBTypes::Int, $id)]);
    }

    public static function GetImages(int $userID): array
    {
        $data = array();
        $result = DBHAndler::RunQuery("SELECT `pic_name` FROM `felhasznalok` WHERE `felh_id` = ?", [new DBParam(DBTypes::Int, $userID)]);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $result = DBHAndler::RunQuery("SELECT `pic_name` FROM `recept` WHERE `felh_id` = ?", [new DBParam(DBTypes::Int, $userID)]);
        $data[] = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
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

    public static function UpdateUserImg(string $imgName, int $felh_id):void
    {
        DBHandler::RunQuery("UPDATE `felhasznalok` SET `pic_name` = ? WHERE `felh_id` = ?",
        [
            new DBParam(DBTypes::String, $imgName),
            new DBParam(DBTypes::Int, $felh_id),
        ]);
    }
}

