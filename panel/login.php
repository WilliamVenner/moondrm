<?php

    require_once("../moondrm.php");
    $MoonDRM = new MoonDRM(false);

    $BillySteamAuth = new BillySteamAuth($MoonDRM -> Config["CookieName"]);
    
    if (isset($_GET["redirect"])) {
        if ($_GET["redirect"] != "/")
            $_SESSION["redirect"] = $_GET["redirect"];
    }
    
    if (!isset($_SESSION[$MoonDRM -> Config["CookieName"]])) {
        $url     = $BillySteamAuth -> loginURL();
        $subject = "Steam";
    } else {
        if (isset($_SESSION["redirect"])) {
            $url = $_SESSION["redirect"];
            $subject = "MoonDRM";
        } else {
            $url     = "/";
            $subject = "MoonDRM";
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="refresh" content="0;URL=<?php echo($url); ?>">
        <title>MoonDRM &middot; Logging in</title>
        <link rel="stylesheet" type="text/css" href="/assets/css/login.css">
    </head>
    <body>
        <div class="center full"><div>
            
            <h2>Redirecting to <?php echo($subject); ?>...</h2>
            If you're not redirected, please <a href="<?php echo($url); ?>">click here</a>.<br><br>
            <h3>Steam down?</h3>
            <?php if ($subject == "Steam") echo('<a href="/login/email/">Log in with email</a>'); ?>
            
        </div></div>
    </body>
</html>