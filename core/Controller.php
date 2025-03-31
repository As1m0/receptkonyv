<?php

abstract class Controller
{

    public static function Route(): void
    {
        global $cfg;



        $page = $cfg["mainPage"];
        if (isset($_GET[$cfg["pageKey"]])) {
            $page = htmlspecialchars($_GET[$cfg["pageKey"]]);
        }

        try {
            DBHandler::Init();

            // Logout
            if (isset($_GET['logout']) && $_GET['logout']) {
                Model::DeleteLoginToken();

                session_unset();
                session_destroy();

                if (isset($_COOKIE['login_token'])) {
                    setcookie('login_token', '', time() - 3600);
                }

                header("Location: {$cfg['mainPage']}.php");
                exit();
            }

            //check keep logged in cookie
            if (!isset($_SESSION["loggedIn"])) {
                if (isset($_COOKIE["login_token"])) {
                    $token = htmlspecialchars($_COOKIE["login_token"]);
                    if (Model::Login("", "", true, $token) == false) {
                        if (isset($_COOKIE['login_token'])) {
                            setcookie('login_token', '', time() - 3600);
                            header("Location: {$cfg['mainPage']}.php");
                            exit();
                        }
                    }
                }
            }

            View::setBaseTemplate(Template::Load($cfg["mainPageTemplate"]));
            $pageData = Model::GetPageData($page);
            if ($pageData["enabled"] !== 1) {
                throw new PermissionDeniedException("A megadott oldal elérése tiltott!");
            }
            if (isset($_SESSION["groupMember"]) && $_SESSION["groupMember"] < $pageData["permission"]) {
                throw new PermissionDeniedException("A megadott oldal eléréséhez magasabb felhasználói szint szükséges!");
            }
            if (!isset($_SESSION["groupMember"]) && $pageData["permission"] !== 0) {
                throw new PermissionDeniedException("A megadott oldal eléréséhez be kell jelentkezned!");
            }
            if (class_exists($pageData["class"]) && in_array("IPageBase", class_implements($pageData["class"]))) {
                $pageObject = new $pageData["class"]();
                $pageObject->Run($pageData);
                $result = $pageObject->GetTemplate();
                if ($result !== null) {
                    if ($pageData["fullTemplate"] === 1) {
                        View::setBaseTemplate($result);
                    } else {
                        $parentData = Model::GetPageData($pageData["parent"]);
                        if ($parentData["enabled"] !== 1) {
                            throw new PermissionDeniedException("A megadott oldal elérése tiltott!");
                        }
                        View::setBaseTemplate(Template::Load($parentData["template"]));
                        View::getBaseTemplate()->AddData($cfg["defaultContentFlag"], $result);

                        if ($pageData["parent"] == "indexGroup") {
                            View::getBaseTemplate()->AddData("BGIMAGE", $cfg["contentFolder"] . "/" . Model::LoadText("bg-img"));
                            View::getBaseTemplate()->AddData($cfg["defaultNavFlag"], Controller::RunModule("NavigationModule"));
                            View::getBaseTemplate()->AddData($cfg["defaultFooterFlag"], Controller::RunModule("FooterModule"));
                            View::getBaseTemplate()->AddData("POPUP", Template::Load("pop-up-block.html"));
                            View::getBaseTemplate()->AddData("COOKIEPOPUP", Template::Load("cookie-popup.html"));
                        } elseif ($pageData["parent"] == "adminGroup") {
                            View::getBaseTemplate()->AddData($cfg["defaultNavFlag"], Controller::RunModule("AdminNavModule"));
                        }
                    }
                } else {
                    throw new PageLoadException("A megadott oldal nem generált tartalmat!");
                }
            } else {
                throw new PageLoadException("A megadott oldalhoz tartozó osztály ({$pageData["class"]}) nem létezik, vagy nem megfelelő");
            }
        } catch (NotFoundException $ex) {
            View::setBaseTemplate(Template::Load($cfg["PageNotFoundTemplate"]));
            View::getBaseTemplate()->AddData($cfg["defaultContentFlag"], $ex->getMessage());
        } catch (PermissionDeniedException $ex) {
            View::setBaseTemplate(Template::Load($cfg["PermissionDeniedTemplate"]));
            View::getBaseTemplate()->AddData($cfg["defaultContentFlag"], $ex->getMessage());
        } catch (Exception $ex) {
            if ($cfg["debug"] /*&& (is_a($ex, "TemplateException") || is_a($ex, "PageLoadException"))*/) {
                View::setBaseTemplate(Template::Load($cfg["debugErrorPage"]));
                View::getBaseTemplate()->AddData("EXCEPTION", get_class($ex));
                View::getBaseTemplate()->AddData("MESSAGE", $ex->getMessage());
                View::getBaseTemplate()->AddData("TRACE", $ex->getTraceAsString());
            } elseif (!$cfg["debug"]) {
                View::setBaseTemplate(Template::Load($cfg["maintanceTemplate"]));
            }
        } finally {
            try {
                DBHandler::Disconnect();
            } catch (Exception $ex) {
                //do nothing...
            }
            View::PrintFinalTemplate();
        }

    }

    public static function RunModule(string $moduleName, array $data = []): null|Template
    {
        $modules = Model::GetModules($moduleName);
        if (class_exists($moduleName)) {
            if ($modules[0]["enabled"] === 1) {
                if (in_array("IVisibleModuleBase", class_implements($moduleName))) {
                    $module = new $moduleName();
                    $module->Run($data);
                    return $module->GetTemplate();
                } elseif (in_array("IModuleBase", class_implements($moduleName))) {
                    $module = new $moduleName();
                    $module->Run($data);
                    return null;
                } else {
                    throw new ModuleException("A megadott modul szerkezetileg hibás!");
                }
            } else {
                throw new ModuleException("A megadott modul nem engedélyezett!");
            }
        } else {
            throw new NotFoundException("A megadott modul nem található!");
        }
    }

}
