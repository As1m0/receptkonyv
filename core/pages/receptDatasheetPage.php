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

            print_r($data);
            
            //Recept adatainak betöltése
            $this->template->AddData("NEV", $data["recept_adatok"][0]["recept_neve"]);
            $this->template->AddData("IDO", $data["recept_adatok"][0]["elk_ido"]);
            $this->template->AddData("ADAG", $data["recept_adatok"][0]["adag"]);
            $this->template->AddData("NEHEZSEG", $data["recept_adatok"][0]["nehezseg"]);
            $this->template->AddData("LEIRAS", $data["recept_adatok"][0]["leiras"]);
            $avrScore =  number_format($data["reviews"][0]["avg_ertekeles"], 1);
            $this->template->AddData("ERTEKELESSZAM", $avrScore);

            if ($avrScore >= 4.5){
                $this->template->AddData("STARSPIC", $cfg["StarKepek"]."/5_star.png");
            }
            elseif($avrScore >= 3.5 && $avrScore < 4.5){
                 $this->template->AddData("STARSPIC", $cfg["StarKepek"]."/4_star.png");
            }
            elseif($avrScore >= 2.5 && $avrScore < 3.5){
                 $this->template->AddData("STARSPIC", $cfg["StarKepek"]."/3_star.png");
            }
            elseif($avrScore >= 1.5 && $avrScore < 2.5){
                 $this->template->AddData("STARSPIC", $cfg["StarKepek"]."/2_star.png");
            }
            elseif($avrScore >= 1 && $avrScore < 1.5){
                 $this->template->AddData("STARSPIC", $cfg["StarKepek"]."/1_star.png");
            }
            else {
                 $this->template->AddData("STARSPIC", $cfg["StarKepek"]."/0_star.png");
            }

            $this->template->AddData("KOMMENTSZAM", $data["reviews"][0]["comment_count"]);
            $this->template->AddData("RECEPTKEP", $cfg["receptKepek"]."/".$data["recept_adatok"][0]["pic_name"].".jpg");



            //Hozzávalók
            $hozzavalok = "";
            for ($i=0 ; $i < count($data["hozzavalok"]); $i++)
            {
                $hozzavalok.= "<li>{$data["hozzavalok"][$i]["mennyiseg"]}&nbsp{$data["hozzavalok"][$i]["mertekegyseg"]}&nbsp<span class=\"list-element\">{$data["hozzavalok"][$i]["nev"]}</span></li>";
            }
            $this->template->addData("HOZZAVALOK", $hozzavalok);



            //Reviews
            for ($j=0; $j<count($data["reviews"]); $j++)
            {
                $review = Template::Load("comment-thumbnail.html");
                $review->addData("TIMESTAMP", $data["reviews"][$j]["created_at"]);
                $ratingScore = $data["reviews"][$j]["ertekeles"];

                if ($ratingScore >= 4.5){
                    $review->addData("RATINGPIC", $cfg["StarKepek"]."/5_star.png");
                }
                elseif($ratingScore >= 3.5 && $ratingScore < 4.5){
                     $review->addData("RATINGPIC", $cfg["StarKepek"]."/4_star.png");
                }
                elseif($ratingScore >= 2.5 && $ratingScore < 3.5){
                     $review->addData("RATINGPIC", $cfg["StarKepek"]."/3_star.png");
                }
                elseif($ratingScore >= 1.5 && $ratingScore < 2.5){
                     $review->addData("RATINGPIC", $cfg["StarKepek"]."/2_star.png");
                }
                elseif($ratingScore >= 1 && $ratingScore < 1.5){
                     $review->addData("RATINGPIC", $cfg["StarKepek"]."/1_star.png");
                }
                else {
                     $review->addData("RATINGPIC", $cfg["StarKepek"]."/0_star.png");
                }

                //TODO
                $review->addData("KOMMENTERPIC", "content/pelda.png");
                $review->addData("KOMMENTERNAME", "Kiss Béla");

                $review->addData("KOMMENT", $data["reviews"][$j]["komment"]);
                $this->template->addData("KOMMENTEK", $review);
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
            Header("Location: index.php?p=");
        }




        


        //írt domment feldolgozása
        if(isset($_POST["UserReview"]))
        {
            $review = htmlspecialchars($_POST["review"]);
            $rating = $_POST["rating"];
            if($rating == 0){
                $rating = "N/A";
            }

            print($review);
            print($rating);
        }

    }
}