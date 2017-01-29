<?php
    
    if (!isset($_GET["script_id"])) {
        die();
    }
    
    include("../moondrm.php");
    $MoonDRM = new MoonDRM(false);
    
    echo("#MoonDRM#\n");
    
    $q = $MoonDRM -> query("SELECT `version` FROM `script_data` WHERE `script_id`=" . $MoonDRM->e($_GET["script_id"]) . " AND `latest`=1");
    if ($q -> num_rows == 1) {
        $q = $q -> fetch_array();
        echo($q["version"]);
    }

?>