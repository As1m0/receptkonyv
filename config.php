<?php
$cfg = [];

$cfg["debug"] = true;
$cfg["debugErrorPage"] = "DebugError.html";
$cfg["maintanceTemplate"] = "maintance.html";
$cfg["PageNotFoundTemplate"] = "404.html";

$cfg["contentFolder"] = "content";
$cfg["uploadsFolder"] = $cfg["contentFolder"]."/uploads";
$cfg["templateFolder"] = "template";
$cfg["defaultContentType"] = "text/html";
$cfg["templateFlag"] = "/%!([A-Z]+)!%/";
$cfg["pageKey"] = "p";
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
