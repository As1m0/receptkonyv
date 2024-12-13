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

        if (isset($_GET[$cfg["searchKey"]]) && $_GET[$cfg["searchKey"]] !== "") {
            $query = urldecode(htmlspecialchars($_GET[$cfg["searchKey"]]));
            //SEARCH IN DATABASE


            $talalatokSzama = 3; //from database
            $this->template->AddData("RESULT", "Keresés: <i>{$query}</i> receptre <span>{$talalatokSzama}</span> találat...");
        }
        else
        {
            $this->template->addData("RESULT", "122 recept közül..."); //From database
        }
 

        if(isset($_POST["search"]))
        {
            if(isset($_POST["query"]) && ($_POST["query"] !== ""))
            {
                $query = htmlspecialchars($_POST["query"]);
                $encodedQuery = urlencode($query);
                header("Location: {$cfg["mainPage"]}.php?{$cfg["pageKey"]}=receptek&{$cfg["searchKey"]}={$encodedQuery}");
                //Logger::WriteLog("keresés: ".$query, LogLevel::Info);
            }
        }


        $this->template->AddData("IMG", $cfg["contentFolder"]."/".Model::LoadText("mainSearch", "cover")["text"]);
        $this->template->AddData("WELCOME", Model::LoadText("mainSearch", "welcome")["text"]);
    }
}