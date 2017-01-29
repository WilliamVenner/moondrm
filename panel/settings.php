<?php
    include("../moondrm.php");
    $MoonDRM = new MoonDRM();
    $MoonDRM -> CheckIfDeveloper();
    ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MoonDRM &middot; Settings</title>
        <link rel="stylesheet" href="/assets/css/settings.css">
        <script type="text/javascript" src="/assets/js/lib/jquery-3.0.0.min.js"></script>
    </head>
    <body>
        
        <?php include_once("inc/header.php"); ?>
        
        <div id="content">
            
            <?php include_once("inc/global_content.php"); ?>
            
            <?php include_once("inc/footer.php"); ?>
            
            <?php
                
                if (isset($_POST["update"])) {
                    
                    $me = $MoonDRM -> GetDeveloper();
                    if ($me["settings"] == null)
                        $me["settings"] = $MoonDRM -> UserSettings;
                        
                    foreach($_POST as $key => $value) {
                        if (substr($key,0,8) != "setting:") continue;
                        $key = substr($key,8);
                        $key = explode("/",$key);
                        if (!isset($me["settings"][$key[0]]))
                            $me["settings"][$key[0]] = [];
                        $me["settings"][$key[0]][$key[1]] = $value;
                    }
                    $MoonDRM -> query("UPDATE `developers` SET `settings`=" . $MoonDRM->e(json_encode($me["settings"])) . " WHERE `steamid64`=" . $MoonDRM->e($_SESSION[$MoonDRM -> Config["CookieName"]]));
                    
                    ?> <div class="announcement green">Your settings have been saved.</div> <?php
                }
            
            ?>
            
            <div id="categories">
                
                <div class="category-header">Settings</div>
                
                <?php
                    
                    if (!isset($_GET["category"]))
                        $_GET["category"] = "General";
                    elseif (empty($_GET["category"]))
                        $_GET["category"] = "General";
                    
                    $settings = $MoonDRM -> UserSettings;
                    foreach($settings as $category => $usersettings) {
                        
                        ?><a href="/settings/<?php echo(htmlentities($category)); ?>"><div class="category<?php
                            
                            if ($category == $_GET["category"])
                                echo(" active");
                            
                        ?>"><?php echo(htmlentities($category)); ?></div></a><?php
                        
                    }
                    
                ?>
            
            </div>
            
            <br id="divider"/>
            
            <div id="settingspanel">
                
                <form method="POST">
                    
                    <?php
                        
                        $me = $MoonDRM -> GetDeveloper();
                        if ($me["settings"] == null)
                            $me["settings"] = $MoonDRM -> UserSettings;
                        
                        foreach($me["settings"] as $category => $usersettings) {
                            
                            if ($category != $_GET["category"]) continue;
                            
                            foreach($usersettings as $name => $value) {
                                
                                ?> <div class="setting">
                                    
                                    <?php
                                        
                                        if (gettype($value) == "string")
                                            
                                            echo(htmlentities(str_replace("_"," ",$name))); ?> <div style="margin-bottom:5px;font-size:0">&nbsp;</div> <input type="text" name="setting:<?php echo(htmlentities($category)); ?>/<?php echo(htmlentities($name)); ?>" placeholder="<?php echo(htmlentities(str_replace("_"," ",$name))); ?>" value="<?php echo(htmlentities($value)); ?>"> <?php
                                    
                                    ?>
                                    
                                </div> <?php
                                
                            }
                            
                        }
                        
                    ?>
                    
                    <input type="submit" class="button green" value="Save" name="update">
                
                </form>
                
            </div>
            
        </div>
        
    </body>
</html>
<?php ob_end_flush(); ?>