<?php

class AboutPage implements IPageBase
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
        try
        {

            $this->template->AddData("TITLE", "Rólam");
            $this->template->AddData("IMG", $cfg["contentFolder"]."/".Model::LoadText($pageData["page"], "img")["text"]);
            $this->template->AddData("ABOUT", implode("</p><p>", explode("*enter*", Model::LoadText($pageData["page"], "about")["text"])));
            $this->template->AddData("TEL", Model::LoadText($pageData["page"], "tel")["text"]);
            $this->template->AddData("EMAIL", Model::LoadText($pageData["page"], "email")["text"]);
            $this->template->AddData("ADDRESS", Model::LoadText($pageData["page"], "address")["text"]);
        }
        catch (Exception $ex)
        {
            return;
        }
        $this->template->AddData("CONTACT", Controller::RunModule("ContactModule"));

        if (isset($_POST["logout"]))
        {
            session_destroy();
            header("Location: {$cfg['mainPage']}.php");
        }
    }
}
