<?php

class UsersPage2 implements IPageBase
{
    private Template $template;

    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        global $cfg;

        $this->template = Template::Load($pageData["template"]);
        //cover kép
        $this->template->AddData("COVERIMG", $cfg["contentFolder"] . "/" . Model::LoadText("account-cover"));



        $usersData = Model::GetAllUserData();

        //print_r($usersData);
        if (!empty($usersData)) {

            foreach ($usersData as $user) {

                $userCard = Template::Load("user-card.html");

                if($user["groupMember"] == 1)
                {
                    $userCard->AddData("NAME", $user["veznev"] . " " . $user["kernev"] ."<small> (admin)</small>");
                }
                else{
                    $userCard->AddData("NAME", $user["veznev"] . " " . $user["kernev"]);
                }
                
                $userCard->AddData("LINK", "index.php?p=user-page&user=" . $user["felh_id"]);
                $userCard->AddData("NUMBER", $user["recipe_count"]);
                if ($user["pic_name"] != null && file_exists($cfg["ProfilKepek"] . "/" . $user["pic_name"] . ".jpg")) {
                    $userCard->AddData("PIC", $cfg["ProfilKepek"] . "/" . $user["pic_name"] . ".jpg");
                } else {
                    $userCard->AddData("PIC", $cfg["ProfilKepek"] . "/empty_profilPic.jpg");
                }

        
                $this->template->AddData("USERS", $userCard);
            }
        } else {
            $this->template->AddData("USERS", "<p>Nincs regisztrált felhasználó az oldalon...</p>");
        }

    }
}