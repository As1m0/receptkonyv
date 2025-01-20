<?php

class UploadPage implements IPageBase
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
            $_SESSION["visitedPage"] = "{$cfg['mainPage']}.php?p=recept-feltoltes";
            header("Location: {$cfg['mainPage']}.php?p=login");
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
        

        if(isset($_POST["feltoltes"]))
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
                $category = htmlspecialchars(trim($_POST["category"]));
                $elkIdo = filter_var($_POST["time"], FILTER_VALIDATE_INT);
                $nehezseg = htmlspecialchars(trim($_POST["nehezseg"]));
                $adag = filter_var($_POST["adag"], FILTER_VALIDATE_INT);
                $hozzavalok = $_POST["ingredients"];
                $leiras = htmlspecialchars(trim($_POST["leiras"]));

                $data["recept"] = ["recept_neve" => $cim, "kategoria" => $category, "leiras" => $leiras, "elk_ido" => $elkIdo, "adag" => $adag, "nehezseg" => $nehezseg, "felh_id" => $_SESSION["userID"], "pic_name" => $imgName];

                //Hozzávalók validálása
                foreach ($hozzavalok as $key => $ingredient) {
                    $hozzavalok[$key]['mennyiseg'] = filter_var($ingredient['mennyiseg'], FILTER_VALIDATE_INT);
                    $hozzavalok[$key]['nev'] = htmlspecialchars(trim($ingredient['nev']));
                    $hozzavalok[$key]['mertekegyseg'] = htmlspecialchars(trim($ingredient['mertekegyseg']));
                }
                $data["hozzavalok"] = $hozzavalok;

                //adatbázis feltöltés
                Model::UploadRecept($data);

                $this->template->AddData("RESULT", "Sikeres recept feltöltés!");
                $this->template->AddData("COLOR", "green");
                $this->template->AddData("SCRIPT", "<script>window.setTimeout(function(){window.location.href='index.php?p=account';}, 1500);</script>");
            }
            else
            {
                $this->template->AddData("RESULT", "Kérjük töltsön ki minden mezőt!");
                $this->template->AddData("COLOR", "red");
            }
            
        }

        try
        {
           $this->template->AddData("CATEGORIES", Template::Load("foodCategories.html"));
        }
        catch (Exception $ex)
        {
            Logger::WriteLog($ex->getMessage(), LogLevel::Error);
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
        imagecopyresampled($canvas1, $img, 0, 0, 0, 0, $width1, $height1, $originalWidth, $originalHeight);
        imagejpeg($canvas1, $cfg["receptKepek"] . "/" . $name . ".jpg"); 

        $height2 = intval($width2 / $aspectRatio);
        $canvas2 = imagecreatetruecolor($width2, $height2);
        imagecopyresampled($canvas2, $img, 0, 0, 0, 0, $width2, $height2, $originalWidth, $originalHeight);
        imagejpeg($canvas2, $cfg["receptKepek"] . "/" . $name . "_thumb.jpg");

        imagedestroy($canvas1);
        imagedestroy($canvas2);
        imagedestroy($img);
    }

    

}