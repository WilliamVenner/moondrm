<?php
    include("../moondrm.php");
    $MoonDRM = new MoonDRM();
    ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MoonDRM &middot; Scripts</title>
        <link rel="stylesheet" href="/assets/css/scripts.css">
        <script type="text/javascript" src="/assets/js/lib/jquery-3.0.0.min.js"></script>
    </head>
    <body>
        
        <?php include_once("inc/header.php"); ?>
        
        <div id="content">
            
            <?php include_once("inc/global_content.php"); ?>
            
            <?php include_once("inc/footer.php"); ?>
            
            
            
        </div>
        
    </body>
</html>
<?php ob_end_flush(); ?>