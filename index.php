<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once("config.php");
require_once("autoload.php");

session_start();
if(isset($_GET['logout']) && $_GET['logout'] == true)
{
    session_unset();
    session_destroy();
    header("Location: {$cfg['mainPage']}.php");
}

Controller::Route();