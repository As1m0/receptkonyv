<?php

class MainSearchModule implements IVisibleModuleBase
{

    private Template $template;

    public function GetTemplate(): \Template
    {
        return $this->template;
    }

    public function Run(array $data = []): void
    {
        global $cfg;
        $this->template = Template::Load("mainSearch.html");

        if(isset($_POST["search"]))
        {
            if(isset($_POST["query"]) && ($_POST["query"] !== ""))
            {
                $query = htmlspecialchars($_POST["query"]);

                //TODO search in Database with Model::

                $talalatokSzama = 3; //from database
                $htmlResult = "<h6 class=\"text-center my-3\">Keresés: <i>{$query}</i> receptre, <span>{$talalatokSzama}</span> találat...</h6>";
                $this->template->AddData("RESULT", $htmlResult);

                Logger::WriteLog("keresés: ".$query, LogLevel::Info);
            }
        }
        $this->template->AddData("IMG", $cfg["contentFolder"]."/".Model::LoadText("mainSearch", "cover")["text"]);
        $this->template->AddData("WELCOME", Model::LoadText("mainSearch", "welcome")["text"]);
        $this->template->addData("ALLRECEPT", "122"); //From database


    }
}