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
                $this->template->AddData("RESULT", "Felhasználó sikeresen módosítva!");
                $this->template->AddData("COLOR", "green");
            }
        }

        if(isset($_POST["delete"]) && $_POST["delete"] == "ok"){
            if(isset($_POST["user"]))
            {
               Model::DeleteUser($_POST["user"]);
               $this->template->AddData("RESULT", "Felhasználó törlése sikeres!");
               $this->template->AddData("COLOR", "green");
            }
        }

        $userData = Model::GetAllUserData();
        if(!empty($userData))
        {
            foreach( $userData as $data )
            {
                $row = Template::Load("user-row.html");
                $row->AddData("ID", $data["felh_id"]);
                $row->AddData("NEV", $data["veznev"]." ".$data["kernev"]);
                $row->AddData("EMAIL", $data["email"]);
                $row->AddData("GROUP", $data["groupMember"]);
                $row->AddData("DATE", $data["created_at"]);
                if($data["pic_name"] != null)
                {
                    $row->AddData("PIC", $data["pic_name"]);
                }
                
                $this->template->AddData("USERS", $row);
            }
        }
        else
        {
            $this->template->AddData("RESULT", "Nem taláható felhasználó");
            $this->template->AddData("COLOR", "red");
        }
        
    }


}