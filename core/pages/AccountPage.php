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
        $this->template->AddData("SRC", "content/profil_kepek/a7428d3e851b49.jpg");
        $this->template->AddData("NAME", "Ujvárossy Ábel");
        $this->template->AddData("EMAIL", "ujvarossyabel@gmail.com");
        $this->template->AddData("RECEPTSZAM", "12");


        //Receptek listázása
        $receptCard = Template::Load("user-recept-card.html");
        //feltöltés
        $receptCard->AddData("RECEPTKEP", "https://cdn.mindmegette.hu/2024/04/cNfIfBYPwpvl1tAzwvSqehQ4nF42_Gm7adWhewfYfrI/fill/0/0/no/1/aHR0cHM6Ly9jbXNjZG4uYXBwLmNvbnRlbnQucHJpdmF0ZS9jb250ZW50L2FiNzg4Y2RjNGVmNzQwOGFiMzQ1NWRhZTFkNjc0NmQ0.jpg");
        $receptCard->AddData("RECEPTNEV", "Tiramisu");
        $receptCard->AddData("IDO", "20");
        $receptCard->AddData("ADAG", "4");
        $receptCard->AddData("NEHEZSEG", "Közepes");
        $receptCard->AddData("SCORE", "8");
        $receptCard->AddData("STARSKEP", "content/stars/4_star.png");

        $this->template->AddData("RECEPTEK", $receptCard);

    }
}