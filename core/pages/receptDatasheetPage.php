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

            // DB Recept betöltése
            Model::Connect();
            $data = Model::GetOneRecpieDB($receptID);
            Model::Disconnect();

            //print_r($data["reviews"]);
            
            //Recept adatainak betöltése
            $this->template->AddData("NEV", $data["recept_adatok"][0]["recept_neve"]);
            //TODO -> recept feltöltője (felh_id alapján)
            $this->template->AddData("IDO", $data["recept_adatok"][0]["elk_ido"]);
            $this->template->AddData("ADAG", $data["recept_adatok"][0]["adag"]);
            $this->template->AddData("NEHEZSEG", $data["recept_adatok"][0]["nehezseg"]);
            $this->template->AddData("LEIRAS", $data["recept_adatok"][0]["leiras"]);
            
            if (!empty($data["reviews"][0]["avg_ertekeles"] != null)){
                $this->template->AddData("STARSPIC", Model::GetStarImg($data["reviews"][0]["avg_ertekeles"]));
            } else {
                $this->template->AddData("STARSPIC", Model::GetStarImg(0));
                
            }
            $this->template->AddData("KOMMENTSZAM", $data["reviews"][0]["comment_count"]);
            $this->template->AddData("ERTEKELESSZAM", $data["reviews"][0]["ertekeles_count"]);
            
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
                for ($j=0; $j<count($data["reviews"]); $j++)
                {
                    $review = Template::Load("comment-thumbnail.html");

                    $review->addData("TIMESTAMP", substr($data["reviews"][$j]["created_at"], 0, 10));
                    $ratingScore = $data["reviews"][$j]["ertekeles"];
                    $review->addData("RATINGPIC",  Model::GetStarImg($ratingScore));

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




        //írt domment feldolgozása
        if(isset($_POST["UserReview"]))
        {
            $review = htmlspecialchars(trim($_POST["review"]));
            $rating = filter_var(trim($_POST["rating"]), FILTER_VALIDATE_INT);
            $data = [ "komment" => $review, "ertekeles" => $rating, "recept_id" => $receptID, "felh_id" => $_SESSION["userID"] ];
            //DB insert komment
            Model::Connect();
            Model::UploadReviewDB($data);
            Model::Disconnect();
        }

    }
}