<?php

class UploadPage implements IPageBase
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
            $_SESSION["visitedPage"] = "{$cfg['mainPage']}.php?p=recept-feltoltes";
            header("Location: {$cfg['mainPage']}.php?p=login");
        }
        

        if(isset($_POST["feltoltes"]))
        {
         //TODO -> save data into database
        }

        try
        {
           $this->template->AddData("CATEGORIES", Template::Load("foodCategories.html"));
        }
        catch (Exception $ex)
        {
            Logger::WriteLog($ex->getMessage(), LogLevel::Error);
        }
    }
}