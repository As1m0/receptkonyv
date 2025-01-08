<?php

class searchLogPage implements IPageBase
{
    private Template $template;
    
    public function GetTemplate(): Template
    {
        return $this->template;
    }

    public function Run(array $pageData): void
    {
        $this->template = Template::Load($pageData["template"]);
        $result = "";
        $data = Model::GetSearchLog();
        for ($i=count($data)-1; $i >= 0; $i--)
        {
            $result .= "<tr>";
            if (preg_match('/\[(.*?)\]\[.*?\] - (.*)/', $data[$i], $matches)) {
                $date = $matches[1];
                $text = $matches[2];
            }
            $result .= "<td>{$date}</td>";
            $result .= "<td>{$text}</td>";
            $result .= "</tr>";
        }

        $this->template->AddData("KERESESEK", $result);
    }

}