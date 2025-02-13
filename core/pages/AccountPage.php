<?php

class AccountPage implements IPageBase
{
    private Template $template;
    
    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        global $cfg;

        if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] ==! false)
        {
            $this->template = Template::Load($pageData["template"]);
        }
        else
        {
            $_SESSION["visitedPage"] = "{$cfg['mainPage']}.php?p=account";
            header("Location: {$cfg['mainPage']}.php?p=login");
        }

        //cover kép
        $this->template->AddData("COVERIMG", $cfg["contentFolder"]."/".Model::LoadText("account-cover"));
       
        //Profil adatok
        if(file_exists($cfg["ProfilKepek"] ."/".$_SESSION["userpic"]. ".jpg"))
        {
            $this->template->AddData("SRC", $cfg["ProfilKepek"] ."/".$_SESSION["userpic"]. ".jpg");
        }
        else
        {
            $this->template->AddData("SRC", $cfg["ProfilKepek"] ."/empty_profilPic.jpg");
        }
        //$this->template->AddData("SRC", $cfg["ProfilKepek"] ."/".$_SESSION["userpic"]. ".jpg");
        $this->template->AddData("NAME", $_SESSION["userfullname"]);
        $this->template->AddData("USERID", $_SESSION["userID"]);
        $this->template->AddData("EMAIL", $_SESSION["usermail"]);


        if(isset($_POST["delete-recepie"]))
        {
            $receptId = filter_var(trim($_POST["delete-recepie"]), FILTER_VALIDATE_INT);
            Model::DeleteRecepie($receptId);
            $feedback= "A recept törlése sikeres!";
        }

        //DB lekérés
        $result = Model::GetRecepies("", 20, $_SESSION["userID"]);

        if ($result["total_count"] !== 0)
        {
        //recept cardok feltöltése
            for ($i = 0 ; $i < count($result["results"]); $i++)
            {
                //card template
                $recept = Template::Load("user-recept-card.html");
                //card feltöltése
                $recept_id = $result["results"][$i]["recept_id"];
                $recept->AddData("RECEPTID", $recept_id);
                $recept->AddData("RECEPTLINK", "{$cfg["mainPage"]}.php?{$cfg["pageKey"]}=recept-aloldal&{$cfg["receptId"]}={$recept_id}");
                if ($result["results"][$i]["pic_name"] !== null && file_exists($cfg["receptKepek"]."/".$result["results"][$i]["pic_name"]."_thumb.jpg")) {
                    $recept->AddData("RECEPTKEP", $cfg["receptKepek"]."/".$result["results"][$i]["pic_name"]."_thumb.jpg");
                } else {
                    $recept->AddData("RECEPTKEP", "{$cfg["receptKepek"]}/no_image_thumb.png");
                }
                $recept->AddData("RECEPTNEV", $result["results"][$i]["recept_neve"]);
                $recept->AddData("IDO", $result["results"][$i]["elk_ido"]);
                $recept->AddData("ADAG", $result["results"][$i]["adag"]);
                $recept->AddData("NEHEZSEG", $result["results"][$i]["nehezseg"]);
                $avrScore = number_format($result["results"][$i]["avg_ertekeles"], 1);
                $recept->AddData("SCORE", $avrScore);
                $recept->AddData("STARSKEP", Template::GetStarImg($avrScore));
                //Card kiküldése
                $this->template->AddData("RECEPTEK", $recept);
            }
        } else {
            $this->template->AddData("RECEPTEK", "<p class=\"text-center small\">még nem töltöttél fel receptet..</p>");
        }

        $this->template->AddData("RECEPTSZAM", $result["total_count"]);


        if(isset($_POST["delete-user"]))
        {
            $userId = filter_var(trim($_POST["delete-user"]), FILTER_VALIDATE_INT);
            if($userId == $_SESSION["userID"])
            {
                Model::DeleteUser($_SESSION["userID"]);
            }
            Header("Location: index.php?logout=true");
        }

        if(isset($feedback) && $feedback !== ""){
            $this->template->AddData("RESULT", $feedback);
            $feedback = "";
            $this->template->AddData("COLOR", "green");
        }
    }
}