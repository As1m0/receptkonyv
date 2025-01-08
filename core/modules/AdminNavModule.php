<?php

class AdminNavModule implements IVisibleModuleBase
{

    private Template $template;

    public function GetTemplate(): \Template
    {
        return $this->template;
    }

    public function Run(array $data = []): void
    {
        global $cfg;

        $this->template = Template::Load("admin-navigation.html");
        $this->template->addData("NAME", $_SESSION["userfullname"]);
        $this->template->addData("PIC", $cfg["ProfilKepek"] ."/".$_SESSION["userpic"]. "_thumb.jpg");

        
    }
}