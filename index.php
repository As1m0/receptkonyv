<?php
ini_set('default_charset', 'UTF-8');

/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
*/

require_once("config.php");
require_once("autoload.php");

session_start();
if(isset($_GET['logout']) && $_GET['logout'])
{
    unset($_SESSION);
    session_unset();
    session_destroy();
    header("Location: {$cfg['mainPage']}.php");
}

Controller::Route();