<script language="javascript" type="text/javascript">
    var mainMessage = "<?php echo($message); ?>";
    var data = {"checks":<?php echo(json_encode($checks)); ?>};
    var checks = data.checks;
    var counter = 0;
    $(document).ready(function(){
       if(mainMessage > "") {
           var time = GetTime(mainMessage);
           DoWriteMessage("div#messages div.check", mainMessage, time);
           setTimeout('$("div#messages div.action").show();', (time * 1000) + 500);
       } else {
        DoMessage("check");
       }
    });

    function DoMessage(messageType) {
        if(messageType == "check") {
            ClearMessages()
            DoWriteMessage("div#messages div.check", checks[counter].check, GetTime(checks[counter].check));
            setTimeout("DoMessage('wait')", 5000);
        } else if (messageType == "wait") {
            DoWriteMessage("div#messages div.wait", " . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .", 5);
            setTimeout("DoMessage('message')", 5000);
        } else if (messageType == "message") {
            var time = GetTime(checks[counter].message);
            DoWriteMessage("div#messages div.message", checks[counter].message, time);
            if(checks[counter].result != 1) {
                setTimeout('DoMessage("fail")', (time * 1000) + 500);
            }else if(counter < checks.length - 1) {
                counter++;
                setTimeout('DoMessage("check")', (time * 1000) + 500);
            } else {
                setTimeout('DoMessage("sucess")', (time * 1000) + 500);
            }
        } else if (messageType == "sucess") {
            ClearMessages()
            DoWriteMessage(
                "div#messages div.message",
                "Ok, happy days, thats all the checks in this step passed.",
                5);
            setTimeout('$("div#messages div.action").show();', 5500);
        } else if (messageType == "fail") {
            setTimeout('$("div#messages div.fail").show();', 1000);
        }
    }

    function ClearMessages() {
        $("div#messages div:not(.action, .fail)").each(function() {
            $(this).html("");
        });
    }





</script>
    <img id="logo-callout" src="assets/images/logo-callout.png" />
    <div id="baloon">
        <div class="top">&nbsp;</div>
        <div class="mid">
            <div id="messages">
                <div class="check"></div>
                <div class="wait"></div>
                <div class="message"></div>
                <div class="action" style="display:none;">
                    <p><?php echo($nextActionMessage); ?></p>
                    <form action="<?php echo($nextAction); ?>" method="GET">
                        <input type="submit" value="lets go ..." class="button" />
                    </form>
                </div>
                <div class="fail" style="display:none;">
                    <p>Sorry about that! One of the tests I carried out didn't pass.</p>
                    <p>Can you fix this problem? Then, start the installation again.</p>
                </div>
            </div>
        </div>
        <div class="bottom">&nbsp;</div>
    </div>