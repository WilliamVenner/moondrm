<?php
    include("../moondrm.php");
    $MoonDRM = new MoonDRM();
    
    if (isset($_POST["sync"])) {
        $MoonDRM -> Sync();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MoonDRM &middot; Dashboard</title>
        <link rel="stylesheet" href="/assets/css/dashboard.css">
        <script type="text/javascript" src="assets/js/lib/jquery-3.0.0.min.js"></script>
    </head>
    <body>
        
        <?php include_once("inc/header.php"); ?>
        
        <div id="content">
            
            <?php include_once("inc/global_content.php"); ?>
            
            <?php
                
                $admin_query = $MoonDRM -> query("SELECT NULL FROM `developers` WHERE `steamid64`=" . $MoonDRM->e($MoonDRM -> SteamID64) . " AND `admin`=TRUE");
                
                if ($admin_query -> num_rows > 0) {
                    
                    ?> <form method="POST">
                    
                        <input type="submit" class="button blue" value="Sync" name="sync">
                    
                    </form> <?php
                    
                }
                
            ?>
            
            <?php include_once("inc/footer.php"); ?>
            
        </div>
        
    </body>
</html>