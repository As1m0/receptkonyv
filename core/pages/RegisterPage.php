<?php

class RegisterPage implements IPageBase
{
    private Template $template;
    
    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        global $cfg;

        $this->template = Template::Load($pageData["template"]);

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

        if(isset($_POST["reg"]))
        {
            if(isset($_POST["vezNev"]) && isset($_POST["kerNev"]) && isset($_POST["email"]) && isset($_POST["pass"]))
            {
                if(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))
                {
                $vezNev = htmlspecialchars(trim($_POST["vezNev"]));
                $kerNev = htmlspecialchars(trim($_POST["kerNev"]));
                $email = htmlspecialchars(trim($_POST["email"]));
                $pass = htmlspecialchars(trim($_POST["pass"]));
                

            //Kép átméretezése
            if(isset($_FILES["img"]) && $_FILES["img"]["error"] == 0)
            {
                $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES["img"]["tmp_name"]);
                if(in_array($mime, $mimes))
                {
                    $imgName = sha1($_FILES["img"]["name"].microtime());
                    $imgName = substr($imgName, 0, 14);

                    if(move_uploaded_file($_FILES["img"]["tmp_name"], $cfg["ProfilKepek"]."/".$imgName))
                    {
                        $this->resizeImg($mime, $imgName, $imgName, $cfg["UserPicSize1"], $cfg["UserPicSize2"]);
                        unlink($cfg["ProfilKepek"]."/".$imgName);
                    }
                    else
                    {
                    throw new Exception("Ismeretlen hiba, a feltöltés megszakadt!");
                    }
                }
                else
                {
                throw new Exception("A megadott kép nem megfelelő fomrátumú!");
                }
            }

                if( mb_strlen($vezNev) >= 3
                    && mb_strlen($kerNev) >= 3
                    && mb_strlen($pass) >= 8
                    && mb_strtolower($pass) != $pass
                    && mb_strtoupper($pass) != $pass)
                {
                    $pass = hash("sha256", trim($_POST["pass"]));
                    //Upload to database
                    Model::Connect();
                    Model::RegisterDB(array("veznev" => $vezNev, "kernev" => $kerNev, "email" => $email, "password_hash" => $pass, "pic_name" => $imgName));
                    Model::Disconnect();
                    
                    $result["reg"]["info"] = "Sikeres regisztráció!";
                    $result["reg"]["success"] = true;
                }
                else
                {
                    $result["reg"]["info"] = "Hiányos adatok (Jelszó min. 8 karakter, kis- és nagybetű kötelező)";
                }
            }
            else
            {
                $result["reg"]["info"] = "Hibás email cím!";
            }
        }
        else
        {
            $result["reg"]["info"] = "Hiányzó adatok!";
        }
    }

        global $cfg;

        if(isset($result["reg"]["info"])){
            $this->template->AddData("RESULT", $result["reg"]["info"]);
            if(isset($result["reg"]["success"]) && $result["reg"]["success"] !== false)
            {
                $this->template->AddData("COLOR", "green");
            } else
            {
                $this->template->AddData("COLOR", "red");
            }
        }

        if(isset($result["reg"]["success"]) && $result["reg"]["success"] !== false)
        {
        $this->template->AddData("SCRIPT", "<script>window.setTimeout(function(){window.location.href='{$cfg["mainPage"]}.php?p=login';}, 1500);</script>");
        }
        
    }

    private function resizeImg(string $mime, string $name, string $currentName, int $width1, int $width2): void
    {
        global $cfg;

        $originalImagePath = $cfg["ProfilKepek"] . "/" . $currentName;

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

        // Get the original image dimensions
        $originalWidth = imagesx($img);
        $originalHeight = imagesy($img);

        // Calculate the aspect ratio of the original image
        $aspectRatio = $originalWidth / $originalHeight;

        // Resize the image to width1, maintaining the aspect ratio
        $height1 = intval($width1 / $aspectRatio);  // Calculate height for width1
        $canvas1 = imagecreatetruecolor($width1, $height1);
        imagecopyresampled($canvas1, $img, 0, 0, 0, 0, $width1, $height1, $originalWidth, $originalHeight);
        imagejpeg($canvas1, $cfg["ProfilKepek"] . "/" . $name . ".jpg");  // Save the first resized image

        // Resize the image to width2, maintaining the aspect ratio
        $height2 = intval($width2 / $aspectRatio);  // Calculate height for width2
        $canvas2 = imagecreatetruecolor($width2, $height2);
        imagecopyresampled($canvas2, $img, 0, 0, 0, 0, $width2, $height2, $originalWidth, $originalHeight);
        imagejpeg($canvas2, $cfg["ProfilKepek"] . "/" . $name . "_thumb.jpg");  // Save the second resized image with "_small" suffix

        // Clean up memory
        imagedestroy($canvas1);
        imagedestroy($canvas2);
        imagedestroy($img);
    }



}