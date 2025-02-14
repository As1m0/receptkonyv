<?php


class RecentRecepiesModule implements IVisibleModuleBase
{
    private Template $template;

    public function GetTemplate(): \Template
    {
        return $this->template;
    }

    public function Run(array $data = []): void
    {
        global $cfg;
        
        $this->template = Template::Load("recepies-slider.html");

        $data = Model::GetLatestRecepies($cfg["SliderNum"]);

        foreach ($data as $recept){
            $card = Template::Load("slider-recepie-card.html");

            if ($recept["pic_name"] !== null && file_exists($cfg["receptKepek"]."/".$recept["pic_name"]."_thumb.jpg")) {
                $card->AddData("KEP", $cfg["receptKepek"]."/".$recept["pic_name"]."_thumb.jpg");
            } else {
                $card->AddData("KEP", "{$cfg["receptKepek"]}/no_image_thumb.png");
            }
            $card->AddData("NEV", $recept["recept_neve"]);
            $card->AddData("IDO", $recept["elk_ido"]);
            $card->AddData("ADAG", $recept["adag"]);
            $card->AddData("NEHEZSEG", $recept["nehezseg"]);
            $card->AddData("LINK", "{$cfg["mainPage"]}.php?{$cfg["pageKey"]}=recept-aloldal&{$cfg["receptId"]}={$recept["recept_id"]}");

            $this->template->AddData("RECEPIES", $card);
        }
        
    }
}