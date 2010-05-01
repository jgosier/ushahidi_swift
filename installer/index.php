<?php include_once("header.php"); ?>
<?php
    
    $message = 
             "Welcome to the SwiftRiver installer for v0.1 Apala. If you've ".
             "received this message then pat yourself on the back. You are not only ".
             "one of the first people in the world to begin using this version of Swift ".
             "but you have also correctly uploaded the package to your server. ".
             "During the next few minutes we'll run through some ".
             "basic tests and setup steps in an attempt to get you up and running.";
?>
<div id="index">
    <script language="javascript" type="text/javascript">
        $(document).ready(function(){
            var time = GetTime("<?php echo($message); ?>");
            DoWriteMessage(
                "div#baloon div.mid div.message",
                "<?php echo($message); ?>",
                time
            );
            setTimeout("$('div.action').show()", (time * 1000) + 500)
        });
    </script>
    <img id="logo-callout" src="assets/images/logo-callout.png" />
    <div id="baloon">
        <div class="top">&nbsp;</div>
        <div class="mid">
            <div id="messages">
                <div class="skip"><a href="#" onclick="$('div.action').show(); return false;">skip</a></div>
                <div class="message"></div>
                <div class="action" style="display:none;">
                    <p>Are you ready? Then...</p>
                    <form action="step-php-checks.php" method="GET">
                        <input type="submit" value="Let's Go!" class="button" />
                    </form>
                </div>
            </div>
        </div>
        <div class="bottom">&nbsp;</div>
    </div>

</div>
<?php include_once("footer.php"); ?>