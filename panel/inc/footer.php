<?php ob_start(); ?>
<link rel="stylesheet" type="text/css" href="/assets/css/<?php echo(isset($footerhome) ? "footer_home" : "footer"); ?>.css">
<div id="footer" class="contentsize" <?php

    if (isset($footer_hide))
        echo('style="display:none"');

?>>
    
    <?php if (!isset($footer_nobranding)) {echo('<a href="https://moondrm.com/"><div id="branding"><img src="/assets/img/logo/text_light.png"/></div></a>');} ?>
    
    <div id="table">
    
        <table class="customtable"><tr>
            
            <td class="section">
                <div class="title">Panel</div>
                <ul class="section-container">
                    <li><a href="https://panel.moondrm.com/">Dashboard</a></li>
                    <li><a href="https://panel.moondrm.com/scripts/">Manage Scripts</a></li>
                    <li><a href="https://panel.moondrm.com/servers/">Servers</a></li>
                    <li><a href="https://panel.moondrm.com/dmca/">DMCA Templates</a></li>
                    <li><a href="https://panel.moondrm.com/tools/">Tools</a></li>
                    <li><a href="https://panel.moondrm.com/documentation/">Documentation</a></li>
                    <li><a href="https://panel.moondrm.com/settings/">Settings</a></li>
                    <?php
                        if (isset($MoonDRM))
                            if (isset($_SESSION[$MoonDRM -> Config["CookieName"]]))
                                echo('<li><a href="https://panel.moondrm.com/logout/">Logout</a></li>');
                    ?>
                </ul>
            </td>
            
            <td class="section">
                <div class="title">Contact</div>
                <ul class="section-container">
                    <li><a href="http://steamid.venner.io/?q=76561198040894045" target="_blank">Founder</a></li>
                    <li><a href="https://facepunch.com/member.php?u=718804" target="_blank">Facepunch</a></li>
                    <li><a href="http://steamcommunity.com/groups/moondrm" target="_blank">Steam Group</a></li>
                    <li><a href="mailto:admin@moondrm.com">Email</a></li>
                    <li><a href="/staff/">Staff</a></li>
                </ul>
            </td>
            
            <td class="section">
                <div class="title">Information</div>
                <ul class="section-container">
                    <li><a href="/features/">Features</a></li>
                    <li><a href="/pricing/">Pricing</a></li>
                    <li><a href="/howitworks/">How it works</a></li>
                    <li><a href="/tutorial/">How to use</a></li>
                    <li><a href="https://trello.com/b/apkGWIpI/" target="_blank">Trello</a></li>
                </ul>
            </td>
            
            <td class="section">
                <div class="title">Legal</div>
                <ul class="section-container">
                    <li><a href="https://moondrm.com/legal/terms/">Terms & Conditions</a></li>
                    <li><a href="https://moondrm.com/legal/privacy/">Privacy Policy</a></li>
                    <li><a href="https://moondrm.com/legal/cookies/">Cookie Policy</a></li>
                </ul>
            </td>
            
        </tr></table>
    
    </div>
    
    <div id="buttons">
        
        <div class="buttonextend" data-contents="1">
            Panel
        </div>
        <div class="contents" data-contents="1">
            <a href="https://panel.moondrm.com/"><div class="buttonclick">
                Dashboard
            </div></a>
            <a href="https://panel.moondrm.com/scripts/"><div class="buttonclick">
                Manage Scripts
            </div></a>
            <a href="https://panel.moondrm.com/servers/"><div class="buttonclick">
                Servers
            </div></a>
            <a href="https://panel.moondrm.com/dmca/"><div class="buttonclick">
                DMCA Templates
            </div></a>
            <a href="https://panel.moondrm.com/tools/"><div class="buttonclick">
                Tools
            </div></a>
            <a href="https://panel.moondrm.com/documentation/"><div class="buttonclick">
                Documentation
            </div></a>
            <a href="https://panel.moondrm.com/settings/"><div class="buttonclick">
                Settings
            </div></a>
            <?php
                if (isset($MoonDRM))
                    if (isset($_SESSION[$MoonDRM -> Config["CookieName"]]))
                        echo('<a href="https://panel.moondrm.com/logout/"><div class="buttonclick">Logout</div></a>');
            ?>
        </div>
        
        <div class="buttonextend" data-contents="2">
            Contact
        </div>
        <div class="contents" data-contents="2">
            <a href="http://steamid.venner.io/?q=76561198040894045" target="_blank"><div class="buttonclick">
                Founder
            </div></a>
            <a href="https://facepunch.com/member.php?u=718804" target="_blank"><div class="buttonclick">
                Facepunch
            </div></a>
            <a href="http://steamcommunity.com/groups/moondrm" target="_blank"><div class="buttonclick">
                Steam Group
            </div></a>
            <a href="mailto:admin@moondrm.com"><div class="buttonclick">
                Email
            </div></a>
            <a href="/staff/"><div class="buttonclick">
                Staff
            </div></a>
        </div>
        
        <div class="buttonextend" data-contents="3">
            Information
        </div>
        <div class="contents" data-contents="3">
            <a href="/features/"><div class="buttonclick">
                Features
            </div></a>
            <a href="/pricing/"><div class="buttonclick">
                Pricing
            </div></a>
            <a href="/howitworks/"><div class="buttonclick">
                How it works
            </div></a>
            <a href="/tutorial/"><div class="buttonclick">
                How to use
            </div></a>
            <a href="https://trello.com/b/apkGWIpI/" target="_blank"><div class="buttonclick">
                Trello
            </div></a>
        </div>
        
        <div class="buttonextend" data-contents="4">
            Legal
        </div>
        <div class="contents" data-contents="4">
            <a href="https://moondrm.com/legal/terms/"><div class="buttonclick">
                Terms & Conditions
            </div></a>
            <a href="https://moondrm.com/legal/privacy/"><div class="buttonclick">
                Privacy Policy
            </div></a>
            <a href="https://moondrm.com/legal/cookies/"><div class="buttonclick">
                Cookie Policy
            </div></a>
        </div>
        
    </div>
    
</div>
<?php
    if ($footer_nobranding)
        echo('<div id="footer_divider">&nbsp;</div>');
?>
<script>
    
    $(".buttonextend").click(function() {
        $(this).toggleClass("toggled");
        $("#buttons .contents[data-contents='" + $(this).data("contents") + "']").slideToggle(500);
    });
    
</script>
<?php
    if (isset($MoonDRM)) {
        if (isset($_SESSION[$MoonDRM -> Config["CookieName"]])) {
            if ($MoonDRM -> API_Key == null) {
                preg_match("/\/settings\/.*/",$_SERVER["REQUEST_URI"],$m);
                if (count($m) != 0) {
                    if ($MoonDRM -> API_Key == "") {
                        ?> <div class="announcement red">You have not entered your API key! MoonDRM needs your API key to communicate with ScriptFodder.</div> <?php
                    } elseif (isset($_POST["setting:ScriptFodder/API_Key"]))
                        if ($_POST["setting:ScriptFodder/API_Key"] == "") {
                            ?> <div class="announcement red">You have not entered your API key! MoonDRM needs your API key to communicate with ScriptFodder.</div> <?php
                        }
                    else {
                        ?> <div class="announcement red">You have not entered your API key! MoonDRM needs your API key to communicate with ScriptFodder.</div> <?php
                    }
                } else {
                    header("LOCATION: /settings/ScriptFodder");
                }
            }
        }
    }
?>
<?php ob_end_flush(); ?>