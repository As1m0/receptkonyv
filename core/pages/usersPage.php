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
            $data .= "<td><form method=\"post\">
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

        if(isset($result)){
            $this->template->AddData("RESULT", "<p>{$result}</p>");
        }
            
        
    }


}