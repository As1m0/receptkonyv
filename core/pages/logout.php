<?php
session_destroy();
header("Location: {$cfg['mainPage']}.php");