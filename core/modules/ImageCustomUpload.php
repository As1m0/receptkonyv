<?php

class ImageCustomUpload implements IVisibleModuleBase
{
    private Template $template;

    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $data = []): void
    {
        global $cfg;
        $this->template = Template::Load("imgUpload.html");
        $mimes = [];
        if(isset($data["types"]))
        {
            foreach ($data["types"] as $type)
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
        if(isset($_POST[$data["okBtn"]]))
        {
            if(isset($_FILES[$data["img"]]) && $_FILES[$data["img"]]["error"] == 0)
            {
                $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES[$data["img"]]["tmp_name"]);
                if(in_array($mime, $mimes))
                {
                    $name = sha1($_FILES[$data["img"]]["name"].microtime());
                    if(move_uploaded_file($_FILES[$data["img"]]["tmp_name"], $cfg["uploadsFolder"]."/".$name))
                    {
                        $this->ResizeUploadedImage($mime, basename($_FILES[$data["img"]]["name"]), $name, $data["width"], $data["height"]);
                        unlink($cfg["uploadsFolder"]."/".$name);
                        $this->template->AddData("RESULT", "A felöltés és az átméretezés sikeres!");
                    }
                    else
                    {
                        $this->template->AddData("RESULT", "Ismeretlen hiba, a feltöltés megszakadt!");
                    }
                }
                else
                {
                    $this->template->AddData("RESULT", "A megadott kép nem megfelelő fomrátumú!");
                }
            }
            else
            {
                $this->template->AddData("RESULT", "Nem érkezett kép, vagy meghiúsult a feltöltése!");
            }
        }
        $this->template->AddData("IMGNAME", $data["img"]);
        $this->template->AddData("OKBTN", $data["okBtn"]);
        $this->template->AddData("ACCEPT", implode(",", $mimes));
    }
    
    private function ResizeUploadedImage(string $mime,string $name,string $currentName, int $width, int $height) : void
    {
        global $cfg;
        switch ($mime)
        {
            case "image/jpeg":
                $img = imagecreatefromjpeg($cfg["uploadsFolder"]."/".$currentName);
                break;
            case "image/png":
                $img = imagecreatefrompng($cfg["uploadsFolder"]."/".$currentName);
                break;
        }
        $canvas = imagecreatetruecolor($width, $height);
        imagecopyresampled($canvas, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
        switch ($mime)
        {
            case "image/jpeg":
                imagejpeg($canvas, $cfg["uploadsFolder"]."/".$name.".jpg");
                break;
            case "image/png":
                imagepng($canvas, $cfg["uploadsFolder"]."/".$name.".png");
                break;
        }
    }
}