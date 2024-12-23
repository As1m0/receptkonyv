<?php


class ReceptekPage implements IPageBase
{
    private Template $template;
    
    public function GetTemplate(): \Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        global $cfg;

        $this->template = Template::Load($pageData["template"]);
        $this->template->AddData("MAINSEARCH", Controller::RunModule("MainSearchModule"));
        //szűróhöz kategóriák
        $this->template->AddData("CATEGORIES", Template::Load("foodCategories.html"));

        
        if (isset($_GET[$cfg["searchKey"]]))
        {
            Controller::RunModule("SearchKeyLoggerModule", [ "searcKey" => $_GET[$cfg["searchKey"]]]);
            $query = htmlspecialchars($_GET[$cfg["searchKey"]]);
            $result = Model::GetRecepies($query);
        }
        else
        {
            $result = Model::GetRecepies();
        }
        //var_dump($result);
        
        //kapott DB adatok feldolgozása
        for ($i = 0 ; $i < count($result["results"]); $i++)
        {
            //card template
            $recept = Template::Load("recept-card.html");
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
            $recept->AddData("USER", $result["results"][$i]["veznev"]." ".$result["results"][$i]["kernev"]);
            $avrScore = number_format($result["results"][$i]["avg_ertekeles"], 1);
            $recept->AddData("SCORE", $avrScore);
            $recept->AddData("STARSKEP", Template::GetStarImg($avrScore));



            //Card kiküldése
            $this->template->AddData("RECEPTCARDS", $recept);
        }


        $this->template->AddData("PAGES", $result["total_count"]);
    }



}