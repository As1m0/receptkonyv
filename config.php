<?php
$cfg = [];

$cfg["debug"] = true;

$cfg["heroRecepieID"] = 105;
$cfg["resultPerPage"] = 18;
$cfg["SliderNum"] = 5;

$cfg["debugErrorPage"] = "DebugError.html";
$cfg["maintanceTemplate"] = "maintance.html";
$cfg["PageNotFoundTemplate"] = "404.html";
$cfg["PermissionDeniedTemplate"] = "permission_denied.html";

$cfg["contentFolder"] = "content";
$cfg["templateFolder"] = "template";
$cfg["defaultContentType"] = "text/html; charset=utf-8";
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
$cfg["db"]["db"] = "receptko_ReceptkonyvDB";
$cfg["db"]["hostname"] = "localhost";
$cfg["db"]["username"] = "receptko_user";
$cfg["db"]["pass"] = "Diamond92!";
$cfg["db"]["port"] = 3306;

//Mail
$cfg["serverMailAdress"] = "info@receptkonyved.hu";

//Picture handling
$cfg["foodPicSize1"] = 1200;
$cfg["foodPicSize2"] = 300;
$cfg["receptKepek"] = $cfg["contentFolder"]."/recept_kepek";
$cfg["UserPicSize1"] = 200;
$cfg["UserPicSize2"] = 25;
$cfg["ProfilKepek"] = $cfg["contentFolder"]."/profil_kepek";
$cfg["StarKepek"] = $cfg["contentFolder"]."/stars";