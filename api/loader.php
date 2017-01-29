<?php

    require_once("../moondrm.php");
    $MoonDRM = new MoonDRM(false);

    if (!$MoonDRM -> CheckUserAgent())
        $MoonDRM -> ThrowError("Access denied");

    echo("--#MoonDRM#\n" . file_get_contents("../lua/moondrm.min.lua"));

?>