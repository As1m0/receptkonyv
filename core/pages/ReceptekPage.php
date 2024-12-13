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

        if (isset($_GET[$cfg["searchKey"]])) {
            $query = htmlspecialchars($_GET[$cfg["searchKey"]]);


            // DATABASE -> Recept card-ok betöltése

            //card template
            $recept = Template::Load("recept-card.html");
            //card feltöltése
            $recept_id = 8;
            $recept->AddData("RECEPTID", $recept_id);
            $recept->AddData("RECEPTLINK", "{$cfg["mainPage"]}.php?{$cfg["pageKey"]}=recept-aloldal&{$cfg["receptId"]}={$recept_id}");
            $recept->AddData("RECEPTKEP", "https://cdn.mindmegette.hu/2024/04/cNfIfBYPwpvl1tAzwvSqehQ4nF42_Gm7adWhewfYfrI/fill/0/0/no/1/aHR0cHM6Ly9jbXNjZG4uYXBwLmNvbnRlbnQucHJpdmF0ZS9jb250ZW50L2FiNzg4Y2RjNGVmNzQwOGFiMzQ1NWRhZTFkNjc0NmQ0.jpg");
            $recept->AddData("RECEPTNEV", "Túrógombóc");
            $recept->AddData("IDO", "30");
            $recept->AddData("ADAG", "4");
            $recept->AddData("NEHEZSEG", "közepes");
            $recept->AddData("USER", "Ujvárossy Samu");
            $recept->AddData("SCORE", "4.4");
            $recept->AddData("STARSKEP", "content/stars/4_star.png");
            //kiküldés
            $this->template->AddData("RECEPTCARDS", $recept);
        }
        else
        {
            $this->template->AddData("RECEPTCARDS", "<h5 class='text-center p-5'>írjon be keresőszót...</h5>");
        }
       
        
    }
}