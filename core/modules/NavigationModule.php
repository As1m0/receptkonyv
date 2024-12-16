<?php

class NavigationModule implements IVisibleModuleBase
{

    private Template $template;

    public function GetTemplate(): \Template
    {
        return $this->template;
    }

    public function Run(array $data = []): void
    {
        global $cfg;
        $this->template = Template::Load("navigation.html");
        $this->template->AddData("LOGO", $cfg["contentFolder"]."/".Model::LoadText("navigation", "logo")["text"]);
        if(isset( $_SESSION["loggedIn"]) && $_SESSION["loggedIn"] ==! false)
        {
            $this->template->addData("NAVITEMUPLOAD", Template::Load("navItemUpload.html"));
            $AccountTemplate = Template::Load("navItemLogin.html");
            $AccountTemplate->addData("NAME", $_SESSION["username"]);
            $AccountTemplate->addData("IMG", "content/profil_kepek/a7428d3e851b49_thumb.jpg");
            $this->template->addData("NAVITEMUPLOAD", $AccountTemplate);

        }
        else
        {
            $this->template->addData("NAVITEMLOGIN", Template::Load("navItemRegister.html"));
        }
    }
}