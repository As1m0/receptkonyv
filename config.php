<?php
$cfg = [];

$cfg["debug"] = false;

$cfg["heroRecepieID"] = 50;
$cfg["SliderNum"] = 5;

$cfg["debugErrorPage"] = "DebugError.html";
$cfg["maintanceTemplate"] = "maintance.html";
$cfg["PageNotFoundTemplate"] = "404.html";
$cfg["PermissionDeniedTemplate"] = "permission_denied.html";

$cfg["contentFolder"] = "content";
$cfg["templateFolder"] = "template";
$cfg["defaultContentType"] = "text/html";
$cfg["templateFlag"] = "/%!([A-Z]+)!%/";

//Keys
$cfg["pageKey"] = "p";
$cfg["receptId"] = "r";
$cfg["searchKey"] = "q";

//Navigation
$cfg["mainPage"] = "index";
$cfg["mainPageTemplate"] = "index.html";
$cfg["defaultContentFlag"] = "CONTENT";
$cfg["defaultNavFlag"] = "NAVIGATION";
$cfg["defaultFooterFlag"] = "FOOTER";

//Database
$cfg["db"]["db"] = "ReceptkonyvDB";
$cfg["db"]["hostname"] = "localhost";
$cfg["db"]["username"] = "webpage";
$cfg["db"]["pass"] = "receptkonyv";
$cfg["db"]["port"] = 3306;

//Mail
$cfg["serverMailAdress"] = "receptkonyv@mail.hu";

//Picture handling
$cfg["foodPicSize1"] = 1200;
$cfg["foodPicSize2"] = 300;
$cfg["receptKepek"] = $cfg["contentFolder"]."/recept_kepek";
$cfg["UserPicSize1"] = 200;
$cfg["UserPicSize2"] = 25;
$cfg["ProfilKepek"] = $cfg["contentFolder"]."/profil_kepek";
$cfg["StarKepek"] = $cfg["contentFolder"]."/stars";