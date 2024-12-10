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
        
        $this->template = Template::Load($pageData["template"]);

        //Recept adatainak betöltése
        $this->template->AddData("NEV", "Recept neve");
        $this->template->AddData("IDO", "22");
        $this->template->AddData("ADAG", "4");
        $this->template->AddData("NEHEZSEG", "Könnyű");
        $this->template->AddData("STARSPIC", "content/rating.png");
        $this->template->AddData("ERTEKELESSZAM", "6");
        $this->template->AddData("KOMMENTSZAM", "4");
        $this->template->AddData("RECEPTKEP", "https://cdn.mindmegette.hu/2024/04/cNfIfBYPwpvl1tAzwvSqehQ4nF42_Gm7adWhewfYfrI/fill/0/0/no/1/aHR0cHM6Ly9jbXNjZG4uYXBwLmNvbnRlbnQucHJpdmF0ZS9jb250ZW50L2FiNzg4Y2RjNGVmNzQwOGFiMzQ1NWRhZTFkNjc0NmQ0.jpg");
        //TODO --> hozzávalók és leírás



        //Reviews betöltése
        $review = Template::Load("comment-thumbnail.html");
        $review->addData("KOMMENTERPIC", "content/pelda.png");
        $review->addData("KOMMENTERNAME", "Kiss Béla");
        $review->addData("TIMESTAMP", "2024-12-10");
        $review->addData("RATINGPIC", "content/rating.png");
        $review->addData("KOMMENT", "Egy tálban keverjük össze a tejfölt vagy habtejszínt, a tojásokat, a reszelt sajtot, a zúzott fokhagymát, a mustárt, és ha szeretnénk egy kis lisztet a sűrűség kedvéért. Adjuk hozzá a finomra vágott petrezselymet is, keverjük össze.");
        $this->template->addData("KOMMENTEK", $review);


        //Hasonló receptek betöltése
        $receptThumb = Template::Load("recept-thumbnail.html");
        $receptThumb->AddData("RECEPTKEP", "https://cdn.mindmegette.hu/2024/04/cNfIfBYPwpvl1tAzwvSqehQ4nF42_Gm7adWhewfYfrI/fill/0/0/no/1/aHR0cHM6Ly9jbXNjZG4uYXBwLmNvbnRlbnQucHJpdmF0ZS9jb250ZW50L2FiNzg4Y2RjNGVmNzQwOGFiMzQ1NWRhZTFkNjc0NmQ0.jpg");
        $receptThumb->AddData("RECEPTNEV", "Egy hasonló recept neve");
        $receptThumb->AddData("IDO", "25");
        $receptThumb->AddData("ADAG", "4");
        $receptThumb->AddData("NEHEZSEG", "Könnyű");
        $receptThumb->AddData("RATINGPIC", "content/rating.png");

        $this->template->AddData("RECEPTTHUMBNAILS", $receptThumb);


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