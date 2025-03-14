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
        $categorySelect = Template::Load("category-select.html");
        $categorySelect->AddData("CATEGORIES", Template::Load("foodCategories.html"));

        $searchData = [];

        if (isset($_GET["cat"]) && $_GET["cat"] !== "") {
            $encodedCategory = urldecode($_GET["cat"]);
            $searchData["category"] = strtolower($encodedCategory);
            if(!isset($_POST["page"]))
            {
                $this->template->AddData("HIDDENCATEGORY", $_GET["cat"]);
            }
            $this->template->AddData("RECEPTCARDS", "<h2 class=\"primary-color my-3 pb-2\">{$encodedCategory} receptek</h2>");
        }

        if (!isset($_GET["cat"]) && !isset($_POST["category"]) && !isset($_POST["page"])) {
            $this->template->AddData("CATEGORYFILTER", $categorySelect);
        }

        if (isset($_POST["category"]) && $_POST["category"] == "") {
            $this->template->AddData("CATEGORYFILTER", $categorySelect);
        }


        if (isset($_POST["category"]) && $_POST["category"] !== "") {
            $categorySelect->AddData("CATEGORY", $_POST["category"]);
            $searchData["category"] = strtolower(htmlspecialchars($_POST["category"]));
            $this->template->AddData("CATEGORYFILTER", $categorySelect);
            $this->template->AddData("HIDDENCATEGORY", $searchData["category"]);
        }


        if (isset($_GET[$cfg["searchKey"]])) {
            $searchData["searchKey"] = htmlspecialchars(trim($_GET[$cfg["searchKey"]]));
            Controller::RunModule("SearchKeyLoggerModule", ["searcKey" => $_GET[$cfg["searchKey"]]]);
        }

        //szurok
        if (isset($_POST["time"]) && $_POST["time"] !== "") {
            $this->template->AddData("TIME", $_POST["time"]);
            switch ($_POST["time"]) {
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

        if (isset($_POST["nehezseg"]) && $_POST["nehezseg"] !== "") {
            $this->template->AddData("DIFF", $_POST["nehezseg"]);
            $searchData["difficulty"] = strtolower(htmlspecialchars($_POST["nehezseg"]));
        }

        if (isset($_POST["review"]) && $_POST["review"] !== "") {
            $this->template->AddData("RATING", $_POST["review"]);
            $searchData["rating"] = strlen(htmlspecialchars($_POST["review"]));

        }

        

        //PAGE HANDLING
        $DBresultCount = Model::getDynamicQueryResults($searchData);

        $results_per_page = $cfg["resultPerPage"];
        $total_results = count($DBresultCount);
        $this->template->AddData("RESULTNUM", $total_results);
        $total_pages = ceil($total_results / $results_per_page);

        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $start_from = ($page - 1) * $results_per_page;
        $DBresult = Model::getDynamicQueryResults($searchData,true, $start_from,$results_per_page, isset($_SESSION["userID"]) ? $_SESSION["userID"] : null);

        //buttons
        if ($total_pages != 1){
            for ($i = 1; $i <= $total_pages; $i++) {
                $this->template->AddData("PAGES", "<input type=\"submit\" class=\"btn\" style=\"color:" . ($page == $i ? 'var(--primary-color);' : '') . "\" name=\"page\" value=\"{$i}\">");
            }
        }
        if ($page != 1) {
            $this->template->AddData("PREV", "<button type=\"submit\" class=\"btn\" name=\"page\" value=\"" . ($page - 1) . "\"><</button>");
        }
        if ($page != $total_pages && $total_pages != 0) {
            $this->template->AddData("NEXT", "<button type=\"submit\" class=\"btn\" name=\"page\" value=\"" . ($page + 1) . "\">></button>");
        }
            


        //recept cards
        if (count($DBresult) != 0) {
            foreach ($DBresult as $recept) {
                $receptCard = Template::Load("recept-card.html");

                //heart icon
                if(isset($recept["is_favorite"])){
                    $heartElement = Template::Load("favorite-icon.html");
                    if($recept["is_favorite"] == "true")
                    {
                        $heartElement->AddData("HEARTIMG", $cfg["contentFolder"]."/heart_icons/heart2.png");
                    }
                    elseif ($recept["is_favorite"] == "false")
                    {
                        $heartElement->AddData("HEARTIMG", $cfg["contentFolder"]."/heart_icons/heart1.png");
                    }
                    $heartElement->AddData("RECEPTID", $recept["recept_id"]);
                    $receptCard->AddData("HEART", $heartElement);
                }
                
                $receptCard->AddData("RECEPTID", $recept["recept_id"]);
                $receptCard->AddData("USERID", $recept["felh_id"]);
                $receptCard->AddData("RECEPTLINK", "{$cfg["mainPage"]}.php?{$cfg["pageKey"]}=recept-aloldal&{$cfg["receptId"]}={$recept["recept_id"]}");
                if ($recept["pic_name"] !== null && file_exists($cfg["receptKepek"] . "/" . $recept["pic_name"] . "_thumb.jpg")) {
                    $receptCard->AddData("RECEPTKEP", $cfg["receptKepek"] . "/" . $recept["pic_name"] . "_thumb.jpg");
                } else {
                    $receptCard->AddData("RECEPTKEP", "{$cfg["receptKepek"]}/no_image_thumb.png");
                }
                $receptCard->AddData("RECEPTNEV", ucfirst($recept["recept_neve"]));
                $receptCard->AddData("IDO", $recept["elk_ido"]);
                $receptCard->AddData("ADAG", $recept["adag"]);
                $receptCard->AddData("NEHEZSEG", $recept["nehezseg"]);
                if(isset($_SESSION["userfullname"]) && $_SESSION["userfullname"] == $recept["veznev"] . " " . $recept["kernev"])
                {
                    $receptCard->AddData("USER", "Saját recept");
                    $receptCard->AddData("COLOR", "green");
                }
                else
                {
                    $receptCard->AddData("USER", $recept["veznev"] . " " . $recept["kernev"]);
                }
                $avrScore = number_format($recept["avg_ertekeles"], 1);
                $receptCard->AddData("SCORE", $avrScore);
                $receptCard->AddData("STARSKEP", Template::GetStarImg($avrScore));

                $this->template->AddData("RECEPTCARDS", $receptCard);
            }
        } else {
            $this->template->AddData("RECEPTCARDS", "<h4 class=\"text-center\">Nincs találat!</h4>");
        }

        //Favorites script
        $favScript = Template::Load("favApi.js");
        $favScript->AddData("FULLHEART", $cfg["contentFolder"]."/heart_icons/heart2.png");
        $favScript->AddData("EMPTYHEART", $cfg["contentFolder"]."/heart_icons/heart1.png");
        $this->template->AddData("SCRIPTS", $favScript);

    }


}