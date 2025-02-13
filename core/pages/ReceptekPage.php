<?php


class ReceptekPage implements IPageBase
{
    private Template $template;
    
    public function GetTemplate(): \Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        global $cfg;
        
        $this->template = Template::Load($pageData["template"]);
        $this->template->AddData("MAINSEARCH", Controller::RunModule("MainSearchModule"));
        //szűróhöz kategóriák
        $this->template->AddData("CATEGORIES", Template::Load("foodCategories.html"));

        $searchData= [];

        if(isset($_GET["cat"]) && $_GET["cat"] !== "")
        {
            $encodedCategory= urldecode($_GET["cat"]);
            $this->template->AddData("CATEGORY", $encodedCategory);
            $searchData["category"] = strtolower($encodedCategory);
        }

        if (isset($_GET[$cfg["searchKey"]]))
        {
            $searchData["searchKey"] = htmlspecialchars(trim($_GET[$cfg["searchKey"]]));
            Controller::RunModule("SearchKeyLoggerModule", [ "searcKey" => $_GET[$cfg["searchKey"]]]);
        }

        if(isset($_POST["category"]) && $_POST["category"] !== "")
        {
            $this->template->AddData("CATEGORY", $_POST["category"]);
            $searchData["category"] = strtolower(htmlspecialchars($_POST["category"]));
        }

        if(isset($_POST["time"]) && $_POST["time"] !== "")
        {
            $this->template->AddData("TIME", $_POST["time"]);
            switch ($_POST["time"])
            {
                case "30 perc alatti":
                    $searchData["time"] = "< 30";
                    break;
                case "30-60 perces":
                    $searchData["time"] = "BETWEEN 30 AND 60";
                    break;
                case "60-90 perces":
                    $searchData["time"] = "BETWEEN 60 AND 90";
                    break;
                case "90 perc feletti":
                    $searchData["time"] = "> 90";
                    break;
                default:
                $searchData["time"] = "";
            }
        }

        if(isset($_POST["nehezseg"]) && $_POST["nehezseg"] !== "")
        {
            $this->template->AddData("DIFF", $_POST["nehezseg"]);
            $searchData["difficulty"] = strtolower(htmlspecialchars($_POST["nehezseg"]));
        }

        if(isset($_POST["review"]) && $_POST["review"] !== "")
        {
            $this->template->AddData("RATING", $_POST["review"]);
            $searchData["rating"] = strlen(htmlspecialchars($_POST["review"]));
            
        }

        $DBresult = Model::getDynamicQueryResults($searchData);


        if(count($DBresult) != 0)
        {
            foreach($DBresult as $recept)
            {
                $receptCard = Template::Load("recept-card.html");
                $receptCard->AddData("RECEPTID", $recept["recept_id"]);
                $receptCard->AddData("RECEPTLINK", "{$cfg["mainPage"]}.php?{$cfg["pageKey"]}=recept-aloldal&{$cfg["receptId"]}={$recept["recept_id"]}");
                if ($recept["pic_name"] !== null) {
                    $receptCard->AddData("RECEPTKEP", $cfg["receptKepek"]."/".$recept["pic_name"]."_thumb.jpg");
                } else {
                    $receptCard->AddData("RECEPTKEP", "{$cfg["receptKepek"]}/no_image_thumb.png");
                }
                $receptCard->AddData("RECEPTNEV", $recept["recept_neve"]);
                $receptCard->AddData("IDO", $recept["elk_ido"]);
                $receptCard->AddData("ADAG", $recept["adag"]);
                $receptCard->AddData("NEHEZSEG", $recept["nehezseg"]);
                $receptCard->AddData("USER", $recept["veznev"]." ".$recept["kernev"]);
                $avrScore = number_format($recept["avg_ertekeles"], 1);
                $receptCard->AddData("SCORE", $avrScore);
                $receptCard->AddData("STARSKEP", Template::GetStarImg($avrScore));

                $this->template->AddData("RECEPTCARDS", $receptCard);
            }
        }
        
    }
       



}