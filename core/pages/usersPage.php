<?php

class usersPage implements IPageBase
{
    private Template $template;
    
    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        $this->template = Template::Load($pageData["template"]);
        $data= "";
        $userData = Model::GetAllUserData();
        for($i=0; $i<count($userData); $i++)
        {
            $data .= "<tr>";
            $data .= "<td>{$userData[$i]["felh_id"]}</td>";
            $data .= "<td>{$userData[$i]["veznev"]} {$userData[$i]["kernev"]}</td>";
            $data .= "<td>{$userData[$i]["email"]}</td>";
            $data .= "<td>{$userData[$i]["groupMember"]}</td>";
            $data .= "<td>{$userData[$i]["created_at"]}</td>";
            $data .= "<td>{$userData[$i]["pic_name"]}</td>";
            $data .= "<td style=\"width: 100px;\"><input type=\"button\" class=\"btn btn-primary edit-btn\" value=\"módosít\" name=\"edit\"></td>";
            $data .= "<td style=\"width: 100px;\"><form method=\"post\">
            <input type=\"submit\" class=\"btn btn-danger submit-btn\" value=\"törlés\" name=\"delete\">
            <input type=\"hidden\" name=\"user\" value=\"{$userData[$i]["felh_id"]}\">
            </form></td>";
            $data .= "</tr>";
        }
        $this->template->AddData("USERS", $data);
       
        if(isset($_POST["delete"]) && $_POST["delete"] == "ok"){
            if(isset($_POST["user"]))
            {
               Model::DeleteUser($_POST["user"]);
                $result = "A felhasználó törlése sikeres!";
            }
        }

        if(isset($_POST["editUser"]))
        {
            if(isset($_POST["mail"]) && trim($_POST["mail"]) !== "" && isset($_POST["name"]) && trim($_POST["name"]) !== "" && isset($_POST["groupMember"]) && trim($_POST["groupMember"]) !== "" && isset($_POST["userID"]) && $_POST["userID"] !== "")
            {
                $userID = filter_var($_POST["userID"], FILTER_VALIDATE_INT);
                $email = filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL);
                $name = htmlspecialchars(trim($_POST["name"]));
                $nameArray = explode(" ", $name);
                $veznev = $nameArray[0];
                $kernev = $nameArray[1];
                $groupMember = filter_var($_POST["groupMember"], FILTER_VALIDATE_INT);
                Model::UpdateUser($userID, $veznev, $kernev, $email, $groupMember);
                $result = "Felhasználó sikeresen módosítva!";
            }
        }

        if(isset($result)){
            $this->template->AddData("RESULT", "<p style=\"color: green;\">{$result}</p>");
        }
            
        
    }


}