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
        
        $this->template = Template::Load($pageData["template"]);
        $this->template->AddData("MAINSEARCH", Controller::RunModule("MainSearchModule"));

        //Recept card-ok betöltése
        $this->template->AddData("RECEPTCARDS", Template::Load("recept-card.html"));

        //szűróhöz kategóriák
        $this->template->AddData("CATEGORIES", Template::Load("foodCategories.html"));
    }
}