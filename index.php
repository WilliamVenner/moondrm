<?php
    $footerhome = true;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MoonDRM</title>
        <link rel="stylesheet" type="text/css" href="assets/css/home.css">
        <script type="text/javascript" src="assets/js/lib/jquery-3.0.0.min.js"></script>
    </head>
    <body>
        
        <div id="home"><div>
            
            <span id="typed">
                <span id="verb">Protect</span> your scripts.<br>
            </span>
            
        </div></div>
        
        <?php include_once("panel/inc/footer.php"); ?>
        
        <div id="arrow"><img src="assets/img/arrow.png"/></div>
        
        <script type="text/javascript" src="assets/js/lib/jquery-3.0.0.min.js"></script>
        <script type="text/javascript" src="assets/js/lib/typed.js"></script>
        <script type="text/javascript">
        
            $("#typed > #verb").typed({
                strings: ["Protect","Update","Encrypt","Block","Automate"],
                typeSpeed: 50,
                backSpeed: 50,
                backDelay: 1000,
                loop: true,
            });
            
            var arrowvisible = true;
            $(window).scroll(function() {
                if ($(window).scrollTop() > 0) {
                    if (arrowvisible) {
                        arrowvisible = false;
                        $("#arrow").addClass("invisible");
                    }
                } else if (!arrowvisible) {
                    arrowvisible = true;
                    $("#arrow").removeClass("invisible");
                }
            });
            
        </script>
        
    </body>
</html>