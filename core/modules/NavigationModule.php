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
        $this->template->AddData("LOGO", $cfg["contentFolder"]."/".Model::LoadText("nav-logo"));
        if(isset( $_SESSION["loggedIn"]) && $_SESSION["loggedIn"] ==! false)
        {
            $this->template->addData("NAVITEMUPLOAD", Template::Load("navItemUpload.html"));
            $AccountTemplate = Template::Load("navItemLogin.html");
            $AccountTemplate->addData("NAME", $_SESSION["username"]);
            if(file_exists($cfg["ProfilKepek"] ."/".$_SESSION["userpic"]. "_thumb.jpg"))
            {
                $AccountTemplate->addData("IMG", $cfg["ProfilKepek"] ."/".$_SESSION["userpic"]. "_thumb.jpg");
            }
            else
            {
                $AccountTemplate->addData("IMG", $cfg["ProfilKepek"] ."/empty_profilPic_thumb.jpg");
            }
            if(isset($_SESSION["groupMember"]) && $_SESSION["groupMember"] >= 1)
            {
                $AccountTemplate->addData("ADMIN", Template::Load("nav-admin-item.html"));
            }
            $this->template->addData("NAVITEMUPLOAD", $AccountTemplate);

        }
        else
        {
            $this->template->addData("NAVITEMLOGIN", Template::Load("navItemRegister.html"));
        }
    }
}