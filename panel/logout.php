<?php
    require_once("../moondrm.php");
    $MoonDRM = new MoonDRM();
    $MoonDRM -> Logout();
    header("LOCATION: https://moondrm.com");
?>