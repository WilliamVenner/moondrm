<?php
    
    require_once("../moondrm.php");
    $MoonDRM = new MoonDRM(false);
    
    $vars = ["steamid","script_id","version_name","extra"];
    foreach($vars as $var) {
        if (!isset($_POST[$var]))
            die("[Error MoonDRM] Invalid request.");
    }
    
    $Developer = false;
    $q = $MoonDRM -> query("SELECT * FROM `developers`");
    while($row = $q -> fetch_assoc()) {
        $apikey = json_decode($row["settings"],true);
        $apikey = $apikey["ScriptFodder"]["API_Key"];
        if ($apikey == $_POST["extra"]) {
            $Developer = $row;
            break;
        }
    }
    if (!$Developer)
        die("[Error MoonDRM] Invalid API key.");
    
    $q = $MoonDRM -> query("SELECT * FROM `scripts` WHERE `script_id`=" . $MoonDRM->e($_POST["script_id"]) . " AND `developer`=" . $MoonDRM->e($Developer["steamid64"]));
    
    if ($q -> num_rows == 0)
        die("[Error MoonDRM] Wrong API key or script does not exist.");
    else
        $q = $q -> fetch_array();
        if ($q["enabled"] == 0)
            die("[Error MoonDRM] This script has been disabled on MoonDRM.");
    
    $q = $MoonDRM -> query("SELECT * FROM `hashes` WHERE `steamid64`=" . $MoonDRM->e($_POST["steamid"]) . " AND `script_id`=" . $MoonDRM->e($_POST["script_id"]));
    
    if ($q -> num_rows == 0) {
        
        $hash = $MoonDRM -> GenerateHash($_POST["steamid"],$_POST["script_id"],1);
        $MoonDRM -> query("INSERT IGNORE INTO `hashes` (`script_id`,`developer`,`steamid64`,`revoked`,`hash`) VALUES(" . $MoonDRM->e($_POST["script_id"]) . "," . $MoonDRM->e($Developer["steamid64"]) . "," . $MoonDRM->e($_POST["steamid"]) . ",0," . $MoonDRM->e($hash) . ")");
        echo($hash);
        
    } else {
        
        $q = $q -> fetch_array();
        
        echo($q["hash"]);
        
    }
    
?>