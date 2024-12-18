<?php


class RecentRecepiesModule implements IVisibleModuleBase
{
    private Template $template;

    public function GetTemplate(): \Template
    {
        return $this->template;
    }

    public function Run(array $data = []): void
    {
        global $cfg;
        $this->template = Template::Load("recepies-slider.html");

        //ORDER BY `created_at` -> legfrisebb receptek lekérdezése DB-ból
        
    }
}