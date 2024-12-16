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
             //write in search log
             Controller::RunModule("SearchKeyLoggerModule", [ "searcKey" => $_GET[$cfg["searchKey"]]]);

            $query = htmlspecialchars($_GET[$cfg["searchKey"]]);
            // DATABASE -> Recept card-ok betöltése
            Model::Connect();
            $result = Model::GetRecepiesDB($query);
            Model::Disconnect();
        }
        else
        {
            Model::Connect();
            $result = Model::GetRecepiesDB();
            Model::Disconnect();
        }

        for ($i = 0 ; $i < count($result); $i++)
        {
            //card template
            $recept = Template::Load("recept-card.html");
            //card feltöltése
            $recept_id = $result[$i]["recept_id"];
            $recept->AddData("RECEPTID", $recept_id);
            $recept->AddData("RECEPTLINK", "{$cfg["mainPage"]}.php?{$cfg["pageKey"]}=recept-aloldal&{$cfg["receptId"]}={$recept_id}");
            if ($result[$i]["pic_name"] !== null)
            {
                $recept->AddData("RECEPTKEP", $cfg["receptKepek"]."/".$result[$i]["pic_name"]."_thumb.jpg");
            }
            else
            {
                $recept->AddData("RECEPTKEP", "{$cfg["receptKepek"]}/no_image_thumb.png");
            }
            $recept->AddData("RECEPTNEV", $result[$i]["recept_neve"]);
            $recept->AddData("IDO", $result[$i]["elk_ido"]);
            $recept->AddData("ADAG", $result[$i]["adag"]);
            $recept->AddData("NEHEZSEG", $result[$i]["nehezseg"]);

            //TODO --> lekérdezni a többi adatot
            $recept->AddData("USER", "Ujvárossy Samu");
            $recept->AddData("SCORE", "4.4");
            $recept->AddData("STARSKEP", "content/stars/4_star.png");

            //Card kiküldése
            $this->template->AddData("RECEPTCARDS", $recept);
        }
            
    
 
    }
}