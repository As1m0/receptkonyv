<?php

class IndexPage implements IPageBase
{
    private Template $template;
    
    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        $this->template = Template::Load($pageData["template"]);
        try
        {
            $this->template->AddData("MAINSEARCH", Controller::RunModule("MainSearchModule"));
            $this->template->AddData("SLIDER", Controller::RunModule("RecentRecepiesModule"));
            $this->template->AddData("STARRECEPT", Controller::RunModule("StarReceptModule"));
        }
        catch (Exception $ex)
        {
            Logger::WriteLog($ex->getMessage(), LogLevel::Error);
        }
    }
}
