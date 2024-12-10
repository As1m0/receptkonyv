<?php

class ContactModule implements IVisibleModuleBase
{
    private Template $template;


    public function GetTemplate(): \Template
    {
        return $this->template;
    }

    public function Run(array $data = []): void
    {
        $this->template = Template::Load("contact.html");
        if(isset($_POST["SendContact"]))
        {
            if(isset($_POST["ContactName"]) && isset($_POST["ContactText"]))
            {
                $name = htmlspecialchars($_POST["ContactName"]);
                $text = htmlspecialchars($_POST["ContactText"]);
                //TODO: email
                Logger::WriteLog("A $name üzenetet küldött: $text", LogLevel::Info);
                $this->template->AddData("RESULT", "Üzenetét rögzítettük!");
            }
            else
            {
                $this->template->AddData("RESULT", "Hiányos kapcsolati adatok!");
            }
        }
    }
}
