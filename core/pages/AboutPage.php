<?php


class AboutPage implements IPageBase
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

        $this->template->AddData("IMG", $cfg["contentFolder"]."/".Model::LoadText("about-cover"));
        $this->template->AddData("ABOUT", nl2br(Model::LoadText("about-text")));

        $data = Model::GetNumbers();
        $this->template->AddData("USER", $data["felh"]);
        $this->template->AddData("RECEPT", $data["recept"]);

        if(isset($_SESSION["usermail"]))
        {
            $this->template->AddData("EMAIL",$_SESSION["usermail"]);
        }

        if(isset($_POST["ok"]))
        {
            if(isset($_POST["email"]) && filter_input(INPUT_POST,"email", FILTER_VALIDATE_EMAIL) &&
            isset($_POST["subj"]) && trim($_POST["subj"]) !== "" && isset($_POST["message"]) && trim($_POST["message"]) !== "")
            {
                $from = filter_input(INPUT_POST,"email", FILTER_SANITIZE_EMAIL);
                $subject = htmlspecialchars($_POST["subj"]);
                $message = htmlspecialchars($_POST["message"]);

                require_once("PHPMailer-master/src/Exception.php");
                require_once("PHPMailer-master/src/PHPMailer.php");
                require_once("PHPMailer-master/src/SMTP.php");
                $mailer = new PHPMailer\PHPMailer\PHPMailer(true);
                try
                {
                    $mailer->From = $from;
                    $mailer->addAddress($cfg["serverMailAdress"]);
                    $mailer->Subject = $subject;
                    $mailer->Body = "<p>$message</p>";
                    $mailer->CharSet = "utf-8";
                    $mailer->isHTML();
                    $mailer->send();
                    $this->template->AddData("RESULT", "Levél sikeresen elküldve");
                    $this->template->AddData("COLOR", "green");
                }
                catch (Exception $ex)
                {
                    $this->template->AddData("RESULT", "Levél küldése sikertelen! Hiba: {$ex->getMessage()}");
                    $this->template->AddData("COLOR", "red");
                }
                
            }
        }




    }

}