<?php

class FavoritesPage implements IPageBase
{
    private Template $template;

    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        global $cfg;

        if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == !false) {
            $this->template = Template::Load($pageData["template"]);
        } else {
            $_SESSION["visitedPage"] = "{$cfg['mainPage']}.php?p=account";
            header("Location: {$cfg['mainPage']}.php?p=login");
        }

        //cover kép
        $this->template->AddData("COVERIMG", $cfg["contentFolder"] . "/" . Model::LoadText("account-cover"));

        //DB lekérés
        $result = Model::GetFavRecepies($_SESSION["userID"]);

        //cards
        if (!empty($result)) {
            foreach ($result as $receptdata) {
                $recept = Template::Load("recept-card.html");

                $heartElement = Template::Load("favorite-icon.html");
                $heartElement->AddData("HEARTIMG", $cfg["contentFolder"]."/heart_icons/heart2.png");
                $heartElement->AddData("RECEPTID", $receptdata["recept_id"]);
                $recept->AddData("HEART", $heartElement);
                $recept->AddData("RECEPTID", $receptdata["recept_id"]);
                $recept->AddData("RECEPTLINK", "{$cfg["mainPage"]}.php?{$cfg["pageKey"]}=recept-aloldal&{$cfg["receptId"]}={$receptdata["recept_id"]}");
                if ($receptdata["pic_name"] !== null && file_exists($cfg["receptKepek"] . "/" . $receptdata["pic_name"] . "_thumb.jpg")) {
                    $recept->AddData("RECEPTKEP", $cfg["receptKepek"] . "/" . $receptdata["pic_name"] . "_thumb.jpg");
                } else {
                    $recept->AddData("RECEPTKEP", "{$cfg["receptKepek"]}/no_image_thumb.png");
                }
                $recept->AddData("RECEPTNEV", $receptdata["recept_neve"]);
                $recept->AddData("IDO", $receptdata["elk_ido"]);
                $recept->AddData("ADAG", $receptdata["adag"]);
                $recept->AddData("NEHEZSEG", $receptdata["nehezseg"]);
                $avrScore = number_format($receptdata["avg_ertekeles"], 1);
                $recept->AddData("SCORE", $avrScore);
                $recept->AddData("STARSKEP", Template::GetStarImg($avrScore));
                //Card kiküldése
                $this->template->AddData("RECEPTEK", $recept);
            }
        } else {
            $this->template->AddData("RECEPTEK", "<p class=\"text-center small\">még nem mentettél el kedvenc receptet..</p>");
        }

    }
}