<?php include_once("header.php"); ?>
<?php
    
    $message = 
             "So you know that web apps normally need a database, right? . . . ".
             "I'm no different. Well, actually I am different!  You see I ".
             "only need one database (nothing odd there) . . . But I'm going to have ".
             "to ask you to enter your db details twice. Sorry about that. . . ".
             "Why? Because for the moment I'm a bit like two peas in a pod ".
             "rather then just one. . . So I need to take the database details ".
             "from you, then pass you on to my good friend, the Rumba installer ".
             "who will need to ask for them again - make sure you give both installers ".
             "the same details though so it's not all bad . . . Now you're ready!"

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
                    <form action="step-db-setup.php" method="GET">
                        <input type="submit" value="lets go ..." class="button" />
                    </form>
                </div>
            </div>
        </div>
        <div class="bottom">&nbsp;</div>
    </div>

</div>
<?php include_once("footer.php"); ?>