<?php

require_once("core/enums.php");
spl_autoload_register(function(string $type)
{
    if(strpos($type, "Exception") !== false && file_exists("core/exceptions/$type.php"))
    {
        require_once("core/exceptions/$type.php");
    }
    elseif(file_exists("core/$type.php"))
    {
        require_once("core/$type.php");
    }
    elseif(file_exists("core/modules/$type.php"))
    {
        require_once("core/modules/$type.php");
    }
    elseif(file_exists("core/pages/$type.php"))
    {
        require_once("core/pages/$type.php");
    }
    elseif(file_exists("PHPMailer-master/src/$type.php"))
    {
        require_once("PHPMailer-master/src/$type.php");
    }
});