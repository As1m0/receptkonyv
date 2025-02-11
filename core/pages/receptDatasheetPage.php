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



        if(isset($_GET[$cfg["receptId"]]) && $_GET[$cfg["receptId"]] !== "")
        {
            $receptID = intval(htmlspecialchars($_GET[$cfg["receptId"]]));

            //írt comment feldolgozása
            if(isset($_POST["UserReview"]))
            {
                $review = htmlspecialchars(trim($_POST["review"]));
                $rating = filter_var(trim($_POST["rating"]), FILTER_VALIDATE_INT);
                $data = [ "komment" => $review, "ertekeles" => $rating, "recept_id" => $receptID, "felh_id" => $_SESSION["userID"] ];
                Model::UploadReview($data);
            }

            //Komment törlése
            if(isset($_POST["delete"]))
            {
                if(isset($_POST["comment_id"]) && $_POST["comment_id"] != "")
                {
                    $kommentID = htmlspecialchars(trim($_POST["comment_id"]));
                    Model::DeleteReview($kommentID);
                }
            }

            // DB Recept betöltése
            $data = Model::RecepieFullData($receptID);
            
            if(empty($data["recept_adatok"]))
            {
                Header("Location: index.php?p=404");
            }
            
            //Recept adatainak betöltése
            $this->template->AddData("NEV", $data["recept_adatok"][0]["recept_neve"]);
            $this->template->AddData("IDO", $data["recept_adatok"][0]["elk_ido"]);
            $this->template->AddData("ADAG", $data["recept_adatok"][0]["adag"]);
            $this->template->AddData("NEHEZSEG", $data["recept_adatok"][0]["nehezseg"]);
            $this->template->AddData("LEIRAS", nl2br($data["recept_adatok"][0]["leiras"])); //nl2br -> add line brakes to HTML
            
            if ( isset($data["reviews"][0])) {
                $this->template->AddData("STARSPIC", Template::GetStarImg($data["reviews"][0]["avg_ertekeles"]));
                $this->template->AddData("KOMMENTSZAM", $data["reviews"][0]["comment_count"]);
                $this->template->AddData("ERTEKELESSZAM", $data["reviews"][0]["ertekeles_count"]);
                } else {
                $this->template->AddData("STARSPIC", Template::GetStarImg(0));
                $this->template->AddData("KOMMENTSZAM", "0");
                $this->template->AddData("ERTEKELESSZAM", "0");
                }

            if ($data["recept_adatok"][0]["pic_name"] != null){
                $this->template->AddData("RECEPTKEP", $cfg["receptKepek"]."/".$data["recept_adatok"][0]["pic_name"].".jpg");
            } else {
                $this->template->AddData("RECEPTKEP", $cfg["receptKepek"]."/no_image.png");
            }




            //Hozzávalók
            $hozzavalok = "";
            for ($i=0 ; $i < count($data["hozzavalok"]); $i++)
            {
                $hozzavalok.= "<li>{$data["hozzavalok"][$i]["mennyiseg"]}&nbsp{$data["hozzavalok"][$i]["mertekegyseg"]}&nbsp<span class=\"list-element\">{$data["hozzavalok"][$i]["nev"]}</span></li>";
            }
            $this->template->addData("HOZZAVALOK", $hozzavalok);



            //Reviews
            if (!empty($data["reviews"])){
                //print_r($data["reviews"]);
                for ($j=0; $j<count($data["reviews"]); $j++)
                {
                    $review = Template::Load("comment-thumbnail.html");

                    $review->AddData("REVID", $data["reviews"][$j]["review_id"]);
                    if((isset($_SESSION["userID"]) && $_SESSION["userID"] == $data["reviews"][$j]["felh_id"]) || (isset($_SESSION["groupMember"]) && $_SESSION["groupMember"] == 1))
                    {
                        $review->AddData("DISP", "block");
                    }
                    else
                    {
                        $review->AddData("DISP", "none");
                    }

                    $review->addData("TIMESTAMP", substr($data["reviews"][$j]["created_at"], 0, 10));
                    $ratingScore = $data["reviews"][$j]["ertekeles"];
                    $review->addData("RATINGPIC",  Template::GetStarImg($ratingScore));

                    if( $data["reviews"][$j]["pic_name"] != null){
                        $review->addData("KOMMENTERPIC", $cfg["ProfilKepek"]."/".$data["reviews"][$j]["pic_name"].".jpg");
                    } else {
                        $review->addData("KOMMENTERPIC", $cfg["ProfilKepek"]."/empty_profilPic.jpg");
                    }

                    $review->addData("KOMMENTERNAME", $data["reviews"][$j]["kernev"]." ".$data["reviews"][$j]["veznev"]);
                    $review->addData("KOMMENT", $data["reviews"][$j]["komment"]);
                    $this->template->addData("KOMMENTEK", $review);
                }
            } else {
                $this->template->addData("KOMMENTEK", "<p class=\"text-center small my-5\"><i>nem érkezett még hozzászólás...</i></p>");
            }


            if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] !== false)
            {
                $this->template->addData("WRITEREVIEW", Template::Load("write-review-block.html"));
            }


            // TODO
            //Hasonló receptek betöltése
            $receptThumb = Template::Load("recept-thumbnail.html");
            $receptThumb->AddData("RECEPTKEP", "https://cdn.mindmegette.hu/2024/04/cNfIfBYPwpvl1tAzwvSqehQ4nF42_Gm7adWhewfYfrI/fill/0/0/no/1/aHR0cHM6Ly9jbXNjZG4uYXBwLmNvbnRlbnQucHJpdmF0ZS9jb250ZW50L2FiNzg4Y2RjNGVmNzQwOGFiMzQ1NWRhZTFkNjc0NmQ0.jpg");
            $receptThumb->AddData("RECEPTNEV", "Egy hasonló recept neve");
            $receptThumb->AddData("IDO", "25");
            $receptThumb->AddData("ADAG", "4");
            $receptThumb->AddData("NEHEZSEG", "Könnyű");
            $receptThumb->AddData("RATINGPIC", "content/rating.png");

            $this->template->AddData("RECEPTTHUMBNAILS", $receptThumb);
        }
        else
        {
            //A recept nem található
            Header("Location: index.php?p=404");
        }

    }
}