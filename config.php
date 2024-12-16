<?php
$cfg = [];

$cfg["debug"] = true;

$cfg["debugErrorPage"] = "DebugError.html";
$cfg["maintanceTemplate"] = "maintance.html";
$cfg["PageNotFoundTemplate"] = "404.html";

$cfg["contentFolder"] = "content";
$cfg["templateFolder"] = "template";
$cfg["defaultContentType"] = "text/html";
$cfg["templateFlag"] = "/%!([A-Z]+)!%/";

//Keys
$cfg["pageKey"] = "p";
$cfg["receptId"] = "r";
$cfg["searchKey"] = "q";

//navigation
$cfg["mainPage"] = "index";
$cfg["mainPageTemplate"] = "index.html";
$cfg["defaultContentFlag"] = "CONTENT";
$cfg["defaultNavFlag"] = "NAVIGATION";
$cfg["defaultFooterFlag"] = "FOOTER";

//Database
$cfg["DB"] = "ReceptkonyvDB";
$cfg["DBhostname"] = "localhost";
$cfg["DBusername"] = "webpage";
$cfg["DBPass"] = "receptkonyv";

//Picture handling
$cfg["foodPicSize1"] = 1200;
$cfg["foodPicSize2"] = 300;
$cfg["receptKepek"] = $cfg["contentFolder"]."/recept_kepek";
$cfg["UserPicSize1"] = 200;
$cfg["UserPicSize2"] = 25;
$cfg["ProfilKepek"] = $cfg["contentFolder"]."/profil_kepek";