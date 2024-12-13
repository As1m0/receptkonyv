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
        

        if(isset($_POST["feltoltes"]))
        {
         if(isset($_POST["name"])
            && isset($_POST["kep"])
            && isset($_POST["category"])
            && isset($_POST["time"])
            && isset($_POST["nehezseg"])
            && isset($_POST["adag"])
            && isset($_POST["ingredients"])
            && isset($_POST["leiras"]))
            {
                $name = htmlspecialchars(trim($_POST["name"]));
                $kep = htmlspecialchars($_POST["kep"]);
                $category = $_POST["category"];
                $elkIdo = $_POST["time"];
                $nehezseg = $_POST["nehezseg"];
                $adag = $_POST["adag"];
                $ingredients = $_POST["ingredients"];
                $leiras = htmlspecialchars(trim($_POST["leiras"]));

                $data = array(null, $name, $category, $leiras, $elkIdo, $adag, $nehezseg, 1, $kep); //TODO: felh_id from SESSION

                if(Model::UploadReceptDB($data) !== false)
                {
                    $this->template->AddData("RESULT", "Sikeres recept feltöltés!");
                    $this->template->AddData("COLOR", "green");
                    $this->template->AddData("SCRIPT", "<script>window.setTimeout(function(){window.location.href='index.php?p=account';}, 1500);</script>");
                }
                else{
                    throw new Exception("Hiba a recept adatbázisba való feltöltése közben");
                    $this->template->AddData("RESULT", "A recept feltöltése közben HIBA lépett fel!");
                    $this->template->AddData("COLOR", "red");
                }
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
}