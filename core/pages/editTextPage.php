<?php

class editTextPage implements IPageBase
{
    private Template $template;
    
    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        $this->template = Template::Load($pageData["template"]);

        if(isset($_POST["edit"]))
        {
            if(isset($_POST["content"]) && isset($_POST["flag"]) && trim($_POST["flag"]) != "")
            {
                $flag = htmlspecialchars(trim($_POST["flag"]));
                $content = htmlspecialchars(trim($_POST["content"]));


                if(Model::ModifyText($flag, $content))
                {
                    $this->template->AddData("COLOR", "green");
                    $this->template->AddData("RESULT", "Sikeres módosítás");
                }
                else
                {
                    $this->template->AddData("RESULT", "A módosítás hibába ütközött!");
                    $this->template->AddData("COLOR", "red");
                }

            }
        }


        $contents = Model::LoadText();
        if(!empty($contents))
        {
            foreach($contents as $content)
            {
                $row = Template::Load("content-row.html");
                $row->AddData("FLAG", $content["flag"]);
                $row->AddData("CONTENT", $content["content"]);
                $this->template->AddData("ROWS", $row);
            }
        }
    }

}