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
        Model::Connect();
        $result = Model::GetRecepiesDB("", 9, $_SESSION["userID"]);
        Model::Disconnect();

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

            if ($avrScore >= 4.5){
                $recept->AddData("STARSKEP", $cfg["StarKepek"]."/5_star.png");
            }
            elseif($avrScore >= 3.5 && $avrScore < 4.5){
                 $recept->AddData("STARSKEP", $cfg["StarKepek"]."/4_star.png");
            }
            elseif($avrScore >= 2.5 && $avrScore < 3.5){
                 $recept->AddData("STARSKEP", $cfg["StarKepek"]."/3_star.png");
            }
            elseif($avrScore >= 1.5 && $avrScore < 2.5){
                 $recept->AddData("STARSKEP", $cfg["StarKepek"]."/2_star.png");
            }
            elseif($avrScore >= 1 && $avrScore < 1.5){
                 $recept->AddData("STARSKEP", $cfg["StarKepek"]."/1_star.png");
            }
            else {
                 $recept->AddData("STARSKEP", $cfg["StarKepek"]."/0_star.png");
            }


            //Card kiküldése
            $this->template->AddData("RECEPTEK", $recept);
        }

        $this->template->AddData("RECEPTSZAM", $result["total_count"]);


    }
}