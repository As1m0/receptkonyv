<?php

class AccountPage implements IPageBase
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

        $mimes = [];
        if (isset($pageData["types"])) {
            foreach ($pageData["types"] as $type) {
                if (is_a($type, "AllowedMimes")) {
                    $mimes[] = $type->value;
                }
            }
        } else {
            $mimes = ["image/jpeg", "image/png"];
        }


        //profilkép feltöltése
        if (isset($_POST["upload"])) {
            if (isset($_FILES["img"]) && $_FILES["img"]["error"] == 0) {
                $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES["img"]["tmp_name"]);
                if (in_array($mime, $mimes)) {
                    $imgName = sha1($_FILES["img"]["name"] . microtime());
                    $imgName = substr($imgName, 0, 14);

                    if (move_uploaded_file($_FILES["img"]["tmp_name"], $cfg["ProfilKepek"] . "/" . $imgName)) {
                        $this->resizeImg($mime, $imgName, $imgName, $cfg["UserPicSize1"], $cfg["UserPicSize2"]);
                        unlink($cfg["ProfilKepek"] . "/" . $imgName);
                        // Update database
                        if (isset($_SESSION["userpic"]) && $_SESSION["userpic"] !== "empty_profilPic" && $_SESSION["userpic"] !== "") {
                            Model::UpdateUserImg($imgName, $_SESSION["userID"], $_SESSION["userpic"]);
                        } else {
                            Model::UpdateUserImg($imgName, $_SESSION["userID"]);
                        }

                        $_SESSION["userpic"] = $imgName;
                        $feedback = "A profilképed feltöltése sikeres!";

                    } else {
                        throw new Exception("Ismeretlen hiba, a feltöltés megszakadt!");
                    }
                } else {
                    throw new Exception("A megadott kép nem megfelelő fomrátumú!");
                }
            }
        }


        $this->template->AddData("ACCEPT", implode(",", $mimes));

        //cover kép
        $this->template->AddData("COVERIMG", $cfg["contentFolder"] . "/" . Model::LoadText("account-cover"));

        //Profil adatok
        if (file_exists($cfg["ProfilKepek"] . "/" . $_SESSION["userpic"] . ".jpg")) {
            $this->template->AddData("SRC", $cfg["ProfilKepek"] . "/" . $_SESSION["userpic"] . ".jpg");
        } else {
            $this->template->AddData("SRC", $cfg["ProfilKepek"] . "/empty_profilPic.jpg");
        }
        $this->template->AddData("NAME", $_SESSION["userfullname"]);
        $this->template->AddData("USERID", $_SESSION["userID"]);
        $this->template->AddData("EMAIL", $_SESSION["usermail"]);


        //delete recepie
        if (isset($_POST["delete-recepie"])) {
            $receptId = filter_var(trim($_POST["delete-recepie"]), FILTER_VALIDATE_INT);
            Model::DeleteRecepie($receptId);
            $feedback = "A recept törlése sikeres!";
        }

        //Favorites script
        $favScript = Template::Load("favApi.js");
        $favScript->AddData("FULLHEART", $cfg["contentFolder"]."/heart_icons/heart2.png");
        $favScript->AddData("EMPTYHEART", $cfg["contentFolder"]."/heart_icons/heart1.png");
        $this->template->AddData("SCRIPTS", $favScript);


        //PAGE HANDLING
        $DBresultCount = Model::getDynamicQueryResults(["userID" => $_SESSION["userID"]], false);
        $results_per_page = $cfg["resultPerPage"];
        $total_results = count($DBresultCount);
        $this->template->AddData("RECEPTSZAM", $total_results);
        $total_pages = ceil($total_results / $results_per_page);

        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $start_from = ($page - 1) * $results_per_page;


        //DB lekérés
        $result = Model::getDynamicQueryResults(["userID" => $_SESSION["userID"]], true, $start_from, $results_per_page, $_SESSION["userID"]);

        //buttons
        for ($i = 1; $i <= $total_pages; $i++) {
            $this->template->AddData("PAGES", "<input type=\"submit\" class=\"btn\" style=\"color:" . ($page == $i ? 'var(--primary-color);' : '') . "\" name=\"page\" value=\"{$i}\">");
        }
        if ($page != 1) {
            $this->template->AddData("PREV", "<button type=\"submit\" class=\"btn\" name=\"page\" value=\"" . ($page - 1) . "\"><</button>");
        }
        if ($page != $total_pages && $total_pages != 0) {
            $this->template->AddData("NEXT", "<button type=\"submit\" class=\"btn\" name=\"page\" value=\"" . ($page + 1) . "\">></button>");
        }

        //cards
        if (!empty($result)) {
            foreach ($result as $receptdata) {
                $recept = Template::Load("user-recept-card.html");

                //heart icon
                if (isset($receptdata["is_favorite"])) {
                    $heartElement = Template::Load("favorite-icon.html");
                    if ($receptdata["is_favorite"] == "true") {
                        $heartElement->AddData("HEARTIMG", $cfg["contentFolder"]."/heart_icons/heart2.png");
                    } else if ($receptdata["is_favorite"] == "false") {
                        $heartElement->AddData("HEARTIMG", $cfg["contentFolder"]."/heart_icons/heart1.png");
                    }
                    $heartElement->AddData("RECEPTID", $receptdata["recept_id"]);
                    $recept->AddData("HEART", $heartElement);
                }

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
            $this->template->AddData("RECEPTEK", "<p class=\"text-center small\">még nem töltöttél fel receptet..</p>");
        }


        //delete user
        if (isset($_POST["delete-user"])) {
            $userId = filter_var(trim($_POST["delete-user"]), FILTER_VALIDATE_INT);
            if ($userId == $_SESSION["userID"]) {
                Model::DeleteUser($_SESSION["userID"]);
            }
            Header("Location: index.php?logout=true");
        }

        if (isset($feedback) && $feedback !== "") {
            $this->template->AddData("RESULT", $feedback);
            $feedback = "";
            $this->template->AddData("COLOR", "green");
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


        $originalWidth = imagesx($img);
        $originalHeight = imagesy($img);

        $aspectRatio = $originalWidth / $originalHeight;

        $height1 = intval($width1 / $aspectRatio);
        $canvas1 = imagecreatetruecolor($width1, $height1);
        $white = imagecolorallocate($canvas1, 255, 255, 255);
        imagefill($canvas1, 0, 0, $white);
        imagecopyresampled($canvas1, $img, 0, 0, 0, 0, $width1, $height1, $originalWidth, $originalHeight);
        imagejpeg($canvas1, $cfg["ProfilKepek"] . "/" . $name . ".jpg");

        $height2 = intval($width2 / $aspectRatio);
        $canvas2 = imagecreatetruecolor($width2, $height2);
        $white = imagecolorallocate($canvas2, 255, 255, 255);
        imagefill($canvas2, 0, 0, $white);
        imagecopyresampled($canvas2, $img, 0, 0, 0, 0, $width2, $height2, $originalWidth, $originalHeight);
        imagejpeg($canvas2, $cfg["ProfilKepek"] . "/" . $name . "_thumb.jpg");

        imagedestroy($canvas1);
        imagedestroy($canvas2);
        imagedestroy($img);
    }
}