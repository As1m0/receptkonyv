<?php

class UpdateRecepiePage implements IPageBase
{
    private Template $template;
    
    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        global $cfg;

        if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] ==! false)
        {
            $this->template = Template::Load($pageData["template"]);
        }
        else
        {
            $_SESSION["visitedPage"] = "{$cfg['mainPage']}.php?p=update-recepie";
            header("Location: {$cfg['mainPage']}.php?p=login");
        }

        if(isset($_GET["id"]) && $_GET["id"] != "")
        {
            $receptID = htmlspecialchars(trim($_GET["id"]));
        }
        else
        {
            header("Location: {$cfg['mainPage']}.php?p=404");
        }




        $mimes = [];
        if(isset($pageData["types"]))
        {
            foreach ($pageData["types"] as $type)
            {
                if(is_a($type, "AllowedMimes"))
                {
                    $mimes[] = $type->value;
                }
            }
        }
        else
        {
            $mimes = ["image/jpeg", "image/png"];
        }


        $this->template->AddData("ACCEPT", implode(",", $mimes));
        

        //Load current data
        $data = Model::RecepieFullData($receptID);
        if(empty($data["recept_adatok"]))
        {
            Header("Location: index.php?p=404");
        }

        $this->template->AddData("NEV", ucfirst($data["recept_adatok"][0]["recept_neve"]));
        $categories = Template::Load("foodCategories.html");
        $this->template->AddData("CATEGORIES", $categories);
        $this->template->AddData("KATEGORIA", $data["recept_adatok"][0]["kategoria"]);
        $this->template->AddData("IDO", $data["recept_adatok"][0]["elk_ido"]);
        $this->template->AddData("ADAG", $data["recept_adatok"][0]["adag"]);
        $this->template->AddData("NEHEZSEG", $data["recept_adatok"][0]["nehezseg"]);
        $this->template->AddData("LEIRAS", $data["recept_adatok"][0]["leiras"]);
        
        //picture
        if ($data["recept_adatok"][0]["pic_name"] != null && file_exists($cfg["receptKepek"]."/".$data["recept_adatok"][0]["pic_name"].".jpg")){
            $newdata["prev-img"] = $data["recept_adatok"][0]["pic_name"];
            $this->template->AddData("RECEPTKEP", $cfg["receptKepek"]."/".$data["recept_adatok"][0]["pic_name"].".jpg");
        } else {
            $this->template->AddData("RECEPTKEP", $cfg["receptKepek"]."/no_image.png");
        }
        //Hozzávalók
        $this->template->AddData("INGNUM", count($data["hozzavalok"]));
        for ($i=0 ; $i < count($data["hozzavalok"]); $i++)
        {
            $ingredientsForm = Template::Load("ingredients-form.html");
            $ingredientsForm->AddData("NUM", $i);
            $ingredientsForm->AddData("NEV", $data["hozzavalok"][$i]["nev"]);
            $ingredientsForm->AddData("MENNY", $data["hozzavalok"][$i]["mennyiseg"]);
            $ingredientsForm->AddData("MERTEKEGYS", $data["hozzavalok"][$i]["mertekegyseg"]);
            $this->template->addData("HOZZAVALOK", $ingredientsForm);
        }


        if(isset($_POST["update"]))
        {
         if(isset($_POST["name"]) && $_POST["name"] !== ""
            && isset($_POST["category"]) && $_POST["category"] !== ""
            && isset($_POST["time"]) && $_POST["time"] !== ""
            && isset($_POST["nehezseg"]) && $_POST["nehezseg"] !== ""
            && isset($_POST["adag"]) && $_POST["adag"] !== ""
            && isset($_POST["ingredients"]) && count($_POST["ingredients"]) !== 0
            && isset($_POST["leiras"]) && $_POST["leiras"] !== "")
            {
            $imgName = null;
                
                //Kép átméretezése
                if(isset($_FILES["img"]) && $_FILES["img"]["error"] == 0)
                {
                    $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES["img"]["tmp_name"]);
                    if(in_array($mime, $mimes))
                    {
                        $imgName = sha1($_FILES["img"]["name"].microtime());
                        $imgName = substr($imgName, 0, 14);

                        if(move_uploaded_file($_FILES["img"]["tmp_name"], $cfg["receptKepek"]."/".$imgName))
                        {
                            $this->resizeImg($mime, $imgName, $imgName, $cfg["foodPicSize1"], $cfg["foodPicSize2"]);
                            unlink($cfg["receptKepek"]."/".$imgName);
                        }
                        else
                        {
                        throw new Exception("Ismeretlen hiba, a kép feltöltése megszakadt!");
                        }
                    }
                    else
                    {
                    throw new Exception("A megadott kép nem megfelelő fomrátumú!");
                    }
                }


                $cim = htmlspecialchars(trim($_POST["name"]));
                $category = strtolower(htmlspecialchars(trim($_POST["category"])));
                $elkIdo = filter_var($_POST["time"], FILTER_VALIDATE_INT);
                $nehezseg = htmlspecialchars(trim($_POST["nehezseg"]));
                $adag = filter_var($_POST["adag"], FILTER_VALIDATE_INT);
                $hozzavalok = $_POST["ingredients"];
                $leiras = htmlspecialchars(trim($_POST["leiras"]));

                $newdata["recept"] = ["recept_neve" => $cim, "kategoria" => $category, "leiras" => $leiras, "elk_ido" => $elkIdo, "adag" => $adag, "nehezseg" => $nehezseg, "felh_id" => $_SESSION["userID"], "pic_name" => $imgName];

                //Hozzávalók validálása
                foreach ($hozzavalok as $key => $ingredient) {
                    $hozzavalok[$key]['mennyiseg'] = floatval($ingredient['mennyiseg']);
                    $hozzavalok[$key]['nev'] = htmlspecialchars(trim($ingredient['nev']));
                    $hozzavalok[$key]['mertekegyseg'] = htmlspecialchars(trim($ingredient['mertekegyseg']));
                }
                $newdata["hozzavalok"] = $hozzavalok;
                $newdata["recept_id"] = $receptID;

                //adatbázis feltöltés
                Model::UpdateRecept($newdata);

                $this->template->AddData("RESULT", "Sikeres recept módosítás!");
                $this->template->AddData("COLOR", "green");
                $this->template->AddData("SCRIPT", "<script>window.setTimeout(function(){window.location.href='index.php?p=recept-aloldal&r={$receptID}';}, 1500);</script>");
            }
            else
            {
                $this->template->AddData("RESULT", "Kérjük töltsön ki minden mezőt!");
                $this->template->AddData("COLOR", "red");
            }
            
        }

    }

    private function resizeImg(string $mime, string $name, string $currentName, int $width1, int $width2): void
    {
        global $cfg;

        $originalImagePath = $cfg["receptKepek"] . "/" . $currentName;

        switch ($mime) {
            case "image/jpeg":
                $img = imagecreatefromjpeg($originalImagePath);
                break;
            case "image/png":
                $img = imagecreatefrompng($originalImagePath);
                break;
            default:
                die("Unsupported MIME type: " . $mime);
        }

        $originalWidth = imagesx($img);
        $originalHeight = imagesy($img);

        $aspectRatio = $originalWidth / $originalHeight;

        $height1 = intval($width1 / $aspectRatio);
        $canvas1 = imagecreatetruecolor($width1, $height1);
        $white = imagecolorallocate($canvas1, 255, 255, 255);
        imagefill($canvas1, 0, 0, $white);
        imagecopyresampled($canvas1, $img, 0, 0, 0, 0, $width1, $height1, $originalWidth, $originalHeight);
        imagejpeg($canvas1, $cfg["receptKepek"] . "/" . $name . ".jpg");

        $height2 = intval($width2 / $aspectRatio);
        $canvas2 = imagecreatetruecolor($width2, $height2);
        $white = imagecolorallocate($canvas2, 255, 255, 255);
        imagefill($canvas2, 0, 0, $white);
        imagecopyresampled($canvas2, $img, 0, 0, 0, 0, $width2, $height2, $originalWidth, $originalHeight);
        imagejpeg($canvas2, $cfg["receptKepek"] . "/" . $name . "_thumb.jpg");

        imagedestroy($canvas1);
        imagedestroy($canvas2);
        imagedestroy($img);
    }

    

}