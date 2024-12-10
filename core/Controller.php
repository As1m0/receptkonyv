<?php

abstract class Controller
{
    public static function Route() : void
    {
        global $cfg;
        $page = $cfg["mainPage"];
        if(isset($_GET[$cfg["pageKey"]]))
        {
            $page = htmlspecialchars($_GET[$cfg["pageKey"]]);
        }
        try
        {
            View::setBaseTemplate(Template::Load($cfg["mainPageTemplate"]));
            $pageData = Model::GetPageData($page);
            if(class_exists($pageData["class"]) && in_array("IPageBase", class_implements($pageData["class"])))
            {
                $pageObject = new $pageData["class"]();
                $pageObject->Run($pageData);
                $result = $pageObject->GetTemplate();
                if($result !== null)
                {
                    if($pageData["fullTemplate"])
                    {
                        View::setBaseTemplate(Template::Load($pageData["template"]));
                    }
                    else
                    {
                        View::getBaseTemplate()->AddData($cfg["defaultContentFlag"], $result);
                        View::getBaseTemplate()->AddData("BGIMAGE", $cfg["contentFolder"]."/".Model::LoadText("body", "bg-img")["text"]);
                        View::getBaseTemplate()->AddData($cfg["defaultNavFlag"], Controller::RunModule("NavigationModule"));
                        View::getBaseTemplate()->AddData($cfg["defaultFooterFlag"], Controller::RunModule("FooterModule"));
                    }
                }
                else
                {
                    throw new PageLoadException("A megadott oldal nem generált tartalmat!");
                }
            }
            else
            {
                throw new PageLoadException("A megadott oldalhoz tartozó osztály ({$pageData["class"]}) nem létezik, vagy nem megfelelő");
            }
        }
        catch (NotFoundException $ex)
        {
           View::setBaseTemplate(Template::Load($cfg["PageNotFoundTemplate"]));
        }
        catch (Exception $ex)
        {
            if($cfg["debug"] /*&& (is_a($ex, "TemplateException") || is_a($ex, "PageLoadException"))*/)
            {
                View::setBaseTemplate(Template::Load($cfg["debugErrorPage"]));
                View::getBaseTemplate()->AddData("EXCEPTION", get_class($ex));
                View::getBaseTemplate()->AddData("MESSAGE", $ex->getMessage());
                View::getBaseTemplate()->AddData("TRACE", $ex->getTraceAsString());
            }
            elseif(!$cfg["debug"])
            {
                View::setBaseTemplate(Template::Load($cfg["maintanceTemplate"]));
            }
        }
        finally
        {
            View::PrintFinalTemplate();
        }
    }
    
    public static function RunModule(string $moduleName, array $data = []) : null|Template
    {
        $modules = Model::GetModules();
        if(isset($modules[$moduleName]) && class_exists($moduleName))
        {
            if($modules[$moduleName]["enabled"] === true)
            {
                if(in_array("IVisibleModuleBase", class_implements($moduleName)))
                {
                    $module = new $moduleName();
                    $module->Run($data);
                    return $module->GetTemplate();
                }
                elseif(in_array("IModuleBase", class_implements($moduleName)))
                {
                    $module = new $moduleName();
                    $module->Run($data);
                    return null;
                }
                else
                {
                    throw new ModuleException("A megadott modul szerkezetileg hibás!");
                }
            }
            else
            {
                throw new ModuleException("A megadott modul nem engedélyezett!");
            }
        }
        else
        {
            throw new NotFoundException("A megadott modul nem található!");
        }
    }
}
