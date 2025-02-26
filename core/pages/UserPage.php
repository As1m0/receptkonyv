<?php

class UserPage implements IPageBase
{
    private Template $template;

    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        global $cfg;

        if(isset($_GET["user"]))
        {
            $userID = htmlspecialchars(trim($_GET["user"]));
        }
        else
        {
            Header("Location: index.php?p=404");
        }

        $userData = UserHandler::GetOneUserData($userID);

        if(empty($userData))
        {
            Header("Location: index.php?p=404");
        }

        $this->template = Template::Load($pageData["template"]);

        //cover kép
        $this->template->AddData("COVERIMG", $cfg["contentFolder"] . "/" . Model::LoadText("account-cover"));

        //Profil adatok
        if (file_exists($cfg["ProfilKepek"] . "/" . $userData[0]["pic_name"] . ".jpg")) {
            $this->template->AddData("SRC", $cfg["ProfilKepek"] . "/" . $userData[0]["pic_name"] . ".jpg");
        } else {
            $this->template->AddData("SRC", $cfg["ProfilKepek"] . "/empty_profilPic.jpg");
        }
        $this->template->AddData("NAME", $userData[0]["veznev"] . " " . $userData[0]["kernev"]);
        $this->template->AddData("EMAIL", $userData[0]["email"]);


        //Favorites script
        $favScript = Template::Load("favApi.js");
        $favScript->AddData("FULLHEART", $cfg["contentFolder"] . "/heart_icons/heart2.png");
        $favScript->AddData("EMPTYHEART", $cfg["contentFolder"] . "/heart_icons/heart1.png");
        $this->template->AddData("SCRIPTS", $favScript);


        //PAGE HANDLING
        $DBresultCount = Model::getDynamicQueryResults(["userID" => $userID], false);
        $results_per_page = $cfg["resultPerPage"];
        $total_results = count($DBresultCount);
        $this->template->AddData("RECEPTSZAM", $total_results);
        $total_pages = ceil($total_results / $results_per_page);

        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $start_from = ($page - 1) * $results_per_page;


        //DB receptek lekérés
        $DBresult = Model::getDynamicQueryResults(["userID" => $userID], true, $start_from, $results_per_page, isset($_SESSION["userID"]) ? $_SESSION["userID"] : null);

        //buttons
        if ($page != 1) {
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

        //cards
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
                    $receptCard->AddData("COLOR", "bold green");
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
            $this->template->AddData("RECEPTCARDS", "<h4 class=\"text-center\">Nincs feltöltött recept!</h4>");
        }

    }




}