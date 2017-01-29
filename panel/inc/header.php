<?php
    if ($MoonDRM -> SteamID64 == null)
        die();
    else {
        $steamtable = $MoonDRM -> GetSteamTable($MoonDRM -> SteamID64);
            $avatar = $steamtable["avatarfull"];
    }
    
    $footer_nobranding = true;
    $footer_hide       = true;
?>
<link rel="stylesheet" type="text/css" href="/assets/css/header.css">
<div id="header" class="contentsize">
    
    <a href="/"><img src="/assets/img/logo/square_light.png" id="header-logo"/></a>
    
    <div id="header-nav">
        
    </div>
    
    <div id="header-expand"><div></div></div>
    
    <?php
        
        if (isset($avatar))
            echo("<a href='/users/" . htmlentities($MoonDRM -> SteamID64) . "'><img id='header-avatar' src='" . htmlentities($avatar) . "'/></a>");
        else
            echo("<img id='header-avatar' src='" . htmlentities($avatar) . "'/>");
        
    ?>
    
</div>
<script>
    
    $("#header-expand > div").click(function() {
        
        if (!$(this).hasClass("lock")) {
            
            $(this).addClass("lock");
            $("#footer").stop().slideDown(500);
            
        } else {
            
            $(this).removeClass("lock");
            $("#footer").stop().slideUp(500);
            
        }
        
    });
    
</script>