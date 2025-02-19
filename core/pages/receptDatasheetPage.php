<?php


class receptDatasheetPage implements IPageBase
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


        if (!isset($_GET[$cfg["receptId"]]) && $_GET[$cfg["receptId"]] == "") {
            //A recept nincs beallitva
            Header("Location: index.php?p=404");
        }

        $receptID = intval(htmlspecialchars($_GET[$cfg["receptId"]]));

        //írt comment feldolgozása
        if (isset($_POST["UserReview"])) {
            $review = htmlspecialchars(trim($_POST["review"]));
            $rating = filter_var(trim($_POST["rating"]), FILTER_VALIDATE_INT);
            $data = ["komment" => $review, "ertekeles" => $rating, "recept_id" => $receptID, "felh_id" => $_SESSION["userID"]];
            Model::UploadReview($data);
        }

        //Komment törlése
        if (isset($_POST["delete"])) {
            if (isset($_POST["comment_id"]) && $_POST["comment_id"] != "") {
                $kommentID = htmlspecialchars(trim($_POST["comment_id"]));
                Model::DeleteReview($kommentID);
            }
        }

        //recept törlése
        if (isset($_POST["delete-recepie"])) {
            print ($_POST["delete-recepie"]);
            $receptIdDel = filter_var(trim($_POST["delete-recepie"]), FILTER_VALIDATE_INT);
            Model::DeleteRecepie($receptIdDel);
            Header("Location: index.php?p=account");
        }







        // DB Recept betöltése
        $data = Model::RecepieFullData($receptID);

        if (empty($data["recept_adatok"])) {
            Header("Location: index.php?p=404");
        }

        //Recept adatainak betöltése
        $this->template->AddData("NEV", ucfirst($data["recept_adatok"][0]["recept_neve"]));
        $this->template->AddData("TIME", substr($data["recept_adatok"][0]["created_at"], 0, 10));

        //szerkesztési lehetőségek jogosultságának vizsgálata
        if (isset($_SESSION["userID"]) && $_SESSION["userID"] === $data["recept_adatok"][0]["felh_id"] || (isset($_SESSION["groupMember"]) && $_SESSION["groupMember"] == 1)) {
            $buttons = Template::Load("recepie-datapage-buttons.html");
            $buttons->AddData("RECEPTID", $receptID);
            $this->template->AddData("BUTTONS", $buttons);
        }
        
        if(isset($_SESSION["userID"]) && $_SESSION["userID"] === $data["recept_adatok"][0]["felh_id"])
        {
            $this->template->AddData("USER", "Saját recept");
        }
        else
        {
            $this->template->AddData("USER", $data["felhasznalo"][0]["veznev"] . " " . $data["felhasznalo"][0]["kernev"] . " receptje");
            $this->template->AddData("COLOR", "primary-color");
        }

        $this->template->AddData("IDO", $data["recept_adatok"][0]["elk_ido"]);
        $this->template->AddData("ADAG", $data["recept_adatok"][0]["adag"]);
        $this->template->AddData("NEHEZSEG", $data["recept_adatok"][0]["nehezseg"]);
        $this->template->AddData("LEIRAS", nl2br($data["recept_adatok"][0]["leiras"])); //nl2br -> add line brakes to HTML

        if (isset($data["reviews"][0])) {
            $this->template->AddData("STARSPIC", Template::GetStarImg($data["reviews"][0]["avg_ertekeles"]));
            $this->template->AddData("KOMMENTSZAM", $data["reviews"][0]["comment_count"]);
            $this->template->AddData("ERTEKELESSZAM", $data["reviews"][0]["ertekeles_count"]);
        } else {
            $this->template->AddData("STARSPIC", Template::GetStarImg(0));
            $this->template->AddData("KOMMENTSZAM", "0");
            $this->template->AddData("ERTEKELESSZAM", "0");
        }

        if ($data["recept_adatok"][0]["pic_name"] != null && file_exists($cfg["receptKepek"] . "/" . $data["recept_adatok"][0]["pic_name"] . ".jpg")) {
            $this->template->AddData("RECEPTKEP", $cfg["receptKepek"] . "/" . $data["recept_adatok"][0]["pic_name"] . ".jpg");
        } else {
            $this->template->AddData("RECEPTKEP", $cfg["receptKepek"] . "/no_image.png");
        }

        //Hozzávalók
        $hozzavalok = "";
        for ($i = 0; $i < count($data["hozzavalok"]); $i++) {
            $hozzavalok .= "<li>{$data["hozzavalok"][$i]["mennyiseg"]}&nbsp{$data["hozzavalok"][$i]["mertekegyseg"]}&nbsp<span class=\"list-element\">{$data["hozzavalok"][$i]["nev"]}</span></li>";
        }
        $this->template->addData("HOZZAVALOK", $hozzavalok);


        //Reviews betöltése
        if (!empty($data["reviews"])) {
            //print_r($data["reviews"]);
            for ($j = 0; $j < count($data["reviews"]); $j++) {
                $review = Template::Load("comment-thumbnail.html");

                $review->AddData("REVID", $data["reviews"][$j]["review_id"]);
                if ((isset($_SESSION["userID"]) && $_SESSION["userID"] == $data["reviews"][$j]["felh_id"]) || (isset($_SESSION["groupMember"]) && $_SESSION["groupMember"] == 1)) {
                    $review->AddData("DISP", "block");
                } else {
                    $review->AddData("DISP", "none");
                }

                $review->addData("TIMESTAMP", substr($data["reviews"][$j]["created_at"], 0, 10));
                $ratingScore = $data["reviews"][$j]["ertekeles"];
                $review->addData("RATINGPIC", Template::GetStarImg($ratingScore));

                if ($data["reviews"][$j]["pic_name"] != null && file_exists($cfg["ProfilKepek"] . "/" . $data["reviews"][$j]["pic_name"] . ".jpg")) {
                    $review->addData("KOMMENTERPIC", $cfg["ProfilKepek"] . "/" . $data["reviews"][$j]["pic_name"] . ".jpg");
                } else {
                    $review->addData("KOMMENTERPIC", $cfg["ProfilKepek"] . "/empty_profilPic.jpg");
                }

                $review->addData("KOMMENTERNAME", $data["reviews"][$j]["kernev"] . " " . $data["reviews"][$j]["veznev"]);
                $review->addData("KOMMENT", $data["reviews"][$j]["komment"]);
                $this->template->addData("KOMMENTEK", $review);
            }
        } else {
            $this->template->addData("KOMMENTEK", "<p class=\"text-center small my-5\"><i>nem érkezett még hozzászólás...</i></p>");
        }

        if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] !== false) {
            $this->template->addData("WRITEREVIEW", Template::Load("write-review-block.html"));
        }



        //Hasonló receptek betöltése
        $similarRecepies = Model::getDynamicQueryResults(["category" => $data["recept_adatok"][0]["kategoria"]], true, 0, 5);

        if (!empty($similarRecepies)) {
            foreach ($similarRecepies as $recepie) {

                $receptThumb = Template::Load("recept-thumbnail.html");

                if ($recepie["pic_name"] != null && file_exists($cfg["receptKepek"] . "/" . $recepie["pic_name"] . ".jpg")) {
                    $receptThumb->AddData("RECEPTKEPTHUMB", $cfg["receptKepek"] . "/" . $recepie["pic_name"] . ".jpg");
                } else {
                    $receptThumb->AddData("RECEPTKEPTHUMB", $cfg["receptKepek"] . "/no_image.png");
                }
                $receptThumb->AddData("RECEPTNEV", $recepie["recept_neve"]);
                $receptThumb->AddData("IDOTH", $recepie["elk_ido"]);
                $receptThumb->AddData("ADAGTH", $recepie["adag"]);
                $receptThumb->AddData("NEHEZSEGTH", $recepie["nehezseg"]);

                $this->template->AddData("RECEPTTHUMBNAILS", $receptThumb);
            }
        }
    }




}