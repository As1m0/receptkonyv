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
        $this->template = Template::Load($pageData["template"]);

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
                

                if($_POST["profilePic"] !== "")
                {
                    $kepUrl = $_POST["profilePic"];
                }
                else
                {
                    $kepUrl = "none";
                }

                if( mb_strlen($vezNev) >= 3
                    && mb_strlen($kerNev) >= 3
                    && mb_strlen($pass) >= 8
                    && mb_strtolower($pass) != $pass
                    && mb_strtoupper($pass) != $pass)
                {
                    $pass = hash("sha256", trim($_POST["pass"]));

                    if( Model::Register(array($vezNev, $kerNev, $email, $pass, $kepUrl)) ==! false)
                    {
                        $result["reg"]["info"] = "Sikeres regisztráció!";
                        $result["reg"]["success"] = true;
                    } else {
                        $result["reg"]["info"] = "Már regisztráltak ezzel az email címmel";
                        $result["reg"]["success"] = false;
                    }
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
}