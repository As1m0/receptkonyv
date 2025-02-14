<?php


class StarReceptModule implements IVisibleModuleBase
{
    private Template $template;

    public function GetTemplate(): \Template
    {
        return $this->template;
    }

    public function Run(array $data = []): void
    {
        global $cfg;
        $this->template = Template::Load("star-recept-module.html");

        $result = Model::RecepieFullData($cfg["heroRecepieID"]);
        
        //Star adatainak betöltése
        $this->template->AddData("LINK", "{$cfg["mainPage"]}.php?{$cfg["pageKey"]}=recept-aloldal&{$cfg["receptId"]}={$result["recept_adatok"][0]["recept_id"]}");
        $this->template->AddData("NEV", $result["recept_adatok"][0]["recept_neve"]);
        $this->template->AddData("IDO", $result["recept_adatok"][0]["elk_ido"]);
        $this->template->AddData("ADAG", $result["recept_adatok"][0]["adag"]);
        $this->template->AddData("NEHEZSEG", $result["recept_adatok"][0]["nehezseg"]);

        if (!empty($result["reviews"][0]))
        {
            $this->template->AddData("ERTEKELESSZAM", $result["reviews"][0]["ertekeles_count"]);
            $this->template->AddData("KOMMENTSZAM", $result["reviews"][0]["comment_count"]);
            $this->template->AddData("STARSPIC", Template::GetStarImg($result["reviews"][0]["avg_ertekeles"]));
        }
        else
        {
            $this->template->AddData("ERTEKELESSZAM", 0);
            $this->template->AddData("KOMMENTSZAM", 0);
            $this->template->AddData("STARSPIC", Template::GetStarImg(0));
        }


        if ($result["recept_adatok"][0]["pic_name"] !== null && file_exists($cfg["receptKepek"]."/".$result["recept_adatok"][0]["pic_name"].".jpg")) {
            $this->template->AddData("RECEPTKEP", $cfg["receptKepek"]."/".$result["recept_adatok"][0]["pic_name"].".jpg");
        } else {
            $this->template->AddData("RECEPTKEP", "{$cfg["receptKepek"]}/no_image_thumb.png");
        }
        
    }
}
