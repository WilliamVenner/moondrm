<?php
    
    require_once("../moondrm.php");
    $MoonDRM = new MoonDRM(false);
    
    $vars = ["ID"];
    foreach($vars as $var) {
        if (!isset($_POST[$var])) {
            die();
        }
    }
    
    $MoonDRM -> query("INSERT INTO `detours` (`steamid64`) VALUES(" . $MoonDRM->e($q["steamid64"]) . ")");
    
?>