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
        $this->template->AddData("COVERIMG", $cfg["contentFolder"]."/".Model::LoadText("account", "cover")["text"]);
       
        //Profil adatok
        $this->template->AddData("SRC", $cfg["ProfilKepek"] ."/".$_SESSION["userpic"]. ".jpg");
        $this->template->AddData("NAME", $_SESSION["userfullname"]);
        $this->template->AddData("EMAIL", $_SESSION["usermail"]);

        //DB lekérés
        $result = Model::GetRecepiesDB("", 9, $_SESSION["userID"]);
        //print_r($result);

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
                if ($result["results"][$i]["pic_name"] !== null) {
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


    }
}