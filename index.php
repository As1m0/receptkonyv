<?php
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