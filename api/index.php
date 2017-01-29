<?php

    require_once("../moondrm.php");
    $MoonDRM = new MoonDRM(false);

    $Hash;
    $found;
    $Script;

    function log_fail($reason) {

        global $MoonDRM, $found, $Hash, $Script;

        if ($found) {

            $MoonDRM -> query("

                INSERT INTO `servers` (`script_id`,`user`,`hostname`,`ip`,`port`,`countrycode`,`os`,`version`,`developer`,`legitimate`,`reason`) VALUES (

                	" . $MoonDRM->e($_POST["ScriptID"]) . ",
                	" . $MoonDRM->e($Hash) . ",
                	" . $MoonDRM->e($_POST["Hostname"]) . ",
                	" . $MoonDRM->e($_POST["IP"]) . ",
                	" . $MoonDRM->e($_POST["Port"]) . ",
                	" . $MoonDRM->e($_POST["CC"]) . ",
                	" . $MoonDRM->e($_POST["OS"]) . ",
                	" . $MoonDRM->e($_POST["Version"]) . ",
                	" . $MoonDRM->e($Script["developer"]) . ",
                	0,
                	" . $MoonDRM->e($reason) . "

            	) ON DUPLICATE KEY UPDATE

                	`countrycode`=" . $MoonDRM->e($_POST["CC"]) . ",
                	`legitimate`=     0,
                	`script_id`="   . $MoonDRM->e($_POST["ScriptID"]) . ",
                	`developer`="   . $MoonDRM->e($Script["developer"]) . ",
                	`lastquery`=      CURRENT_TIMESTAMP,
                	`hostname`="    . $MoonDRM->e($_POST["Hostname"]) . ",
                	`version`="     . $MoonDRM->e($_POST["Version"]) . ",
                	`reason`="      . $MoonDRM->e($reason) . ",
                	`user`="        . $MoonDRM->e($Hash) . ",
                	`port`="        . $MoonDRM->e($_POST["Port"]) . ",
                	`ip`="          . $MoonDRM->e($_POST["IP"]) . ",
                	`os`="          . $MoonDRM->e($_POST["OS"]) . "

        	");

        }

    	$MoonDRM -> ThrowError($reason);
    }

    $vars = ["ScriptID","ID","Version","OS","IP","Port","Hostname","SteamID64"];
    $found = true;
    foreach($vars as $key) if (!isset($_POST[$key])) {
        $found = false;
        log_fail("Access denied");
    }

	$q = $MoonDRM -> query("SELECT `varint` FROM `vars` WHERE `varkey`='last_sync'") -> fetch_array();
	if (time() > ($q["varint"] + 600)) {
	    $MoonDRM -> Sync();
	    $MoonDRM -> query("UPDATE `vars` SET `varint`=" . time() . " WHERE `varkey`='last_sync'");
	}

    $_POST["CC"] = $_SERVER["HTTP_CF_IPCOUNTRY"];

    $Hash = $MoonDRM -> query("SELECT * FROM `hashes` WHERE `hash`=" . $MoonDRM->e($_POST["ID"]));

    if ($Hash -> num_rows == 0)

        if (isset($_POST["ScriptID"]))
            log_fail("Invalid details. Please re-download: http://scriptfodder.com/scripts/view/" . $_POST["ScriptID"]);
        else
            log_fail("Access denied");

    else {
        $Hash = $Hash -> fetch_array();
        $Hash = $Hash["steamid64"];
    }

    $Script = $MoonDRM -> query("SELECT * FROM `scripts` WHERE `script_id`=" . $MoonDRM->e($_POST["ScriptID"]));
    if ($Script -> num_rows == 0)
        log_fail("Script does not exist");
    else
        $Script = $Script -> fetch_array();
        if ($Script["enabled"] == 0)
            log_fail("Script is disabled");

    if ($_POST["SteamID64"] != $Hash) {
        log_fail("User has tampered with licensing information.");
    }

    if (!in_array($Hash,$MoonDRM -> Config["RootUsers"]))
        if (!$MoonDRM -> CheckUserAgent())
            log_fail("Access denied");

    if (!in_array($_POST["OS"],["0","1","2"]))
        log_fail("Access denied");

    $r = preg_match("/\d+\.\d+\.\d+\.\d+/",$_POST["IP"]);
    if (count($r) == 0)
        log_fail("Access denied");

    if (!is_numeric($_POST["Port"]))
        log_fail("Access denied");

    $MoonDRM -> query("INSERT IGNORE INTO `scripts_used` (`steamid64`,`script_id`) VALUES(" . $MoonDRM->e($Hash) . "," . $MoonDRM->e($_POST["ScriptID"]) . ")");

    $ScriptData = $MoonDRM -> query("SELECT * FROM `script_data` WHERE `script_id`=" . $MoonDRM->e($_POST["ScriptID"]) . " AND `version`=" . $MoonDRM->e($_POST["Version"])) -> fetch_array();

    $MoonDRM -> query("

        INSERT INTO `servers` (`script_id`,`user`,`hostname`,`ip`,`port`,`countrycode`,`os`,`version`,`developer`) VALUES (

        	" . $MoonDRM->e($_POST["ScriptID"]) . ",
        	" . $MoonDRM->e($Hash) . ",
        	" . $MoonDRM->e($_POST["Hostname"]) . ",
        	" . $MoonDRM->e($_POST["IP"]) . ",
        	" . $MoonDRM->e($_POST["Port"]) . ",
        	" . $MoonDRM->e($_POST["CC"]) . ",
        	" . $MoonDRM->e($_POST["OS"]) . ",
        	" . $MoonDRM->e($_POST["Version"]) . ",
        	" . $MoonDRM->e($Script["developer"]) . "

    	) ON DUPLICATE KEY UPDATE

        	`countrycode`=" . $MoonDRM->e($_POST["CC"]) . ",
        	`script_id`="   . $MoonDRM->e($_POST["ScriptID"]) . ",
        	`developer`="   . $MoonDRM->e($Script["developer"]) . ",
        	`lastquery`=      CURRENT_TIMESTAMP,
        	`hostname`="    . $MoonDRM->e($_POST["Hostname"]) . ",
        	`version`="     . $MoonDRM->e($_POST["Version"]) . ",
        	`user`="        . $MoonDRM->e($Hash) . ",
        	`port`="        . $MoonDRM->e($_POST["Port"]) . ",
        	`ip`="          . $MoonDRM->e($_POST["IP"]) . ",
        	`os`="          . $MoonDRM->e($_POST["OS"]) . "

	");

    echo($MoonDRM -> EncodeLua($ScriptData["success_code"],$_POST["ID"]));

?>