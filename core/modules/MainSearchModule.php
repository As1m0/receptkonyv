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

            $result = Model::GetRecepies($query);
            $this->template->AddData("PLACEHOLDER", $query);
            $this->template->AddData("VALUE", $query);
            $this->template->AddData("RESULT", "Keresés: <i>{$query}</i> receptre <span>{$result["total_count"]}</span> találat...");
        }
        else
        {
            $this->template->addData("RESULT", ""); //From database
            $this->template->AddData("PLACEHOLDER", "Keresés...");
        }
 

        if(isset($_POST["search"]))
        {
            if(isset($_POST["query"]) && ($_POST["query"] !== ""))
            {
                $query = htmlspecialchars($_POST["query"]);
                $encodedQuery = urlencode($query);
                header("Location: {$cfg["mainPage"]}.php?{$cfg["pageKey"]}=receptek&{$cfg["searchKey"]}={$encodedQuery}");
            }
        }


        $this->template->AddData("IMG", $cfg["contentFolder"]."/".Model::LoadText("search-cover"));
        $this->template->AddData("WELCOME", Model::LoadText("welcome-text"));
    }
}