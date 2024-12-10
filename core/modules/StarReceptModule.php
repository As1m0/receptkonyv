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

        //Star adatainak betöltése
        $this->template->AddData("LINK", "index.php?p=recept-aloldal");
        $this->template->AddData("NEV", "Ez egy kiemelt recept");
        $this->template->AddData("IDO", "22");
        $this->template->AddData("ADAG", "4");
        $this->template->AddData("NEHEZSEG", "Könnyű");
        $this->template->AddData("STARSPIC", "content/rating.png");
        $this->template->AddData("ERTEKELESSZAM", "6");
        $this->template->AddData("KOMMENTSZAM", "4");
        $this->template->AddData("RECEPTKEP", "https://images.pexels.com/photos/1640772/pexels-photo-1640772.jpeg?cs=srgb&dl=pexels-ella-olsson-572949-1640772.jpg&fm=jpg");
        
    }
}