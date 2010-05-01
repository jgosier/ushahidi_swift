<?php include_once("header.php"); ?>
<?php
    if(count($_POST) == 0) {
        $state = "new";
    } else {
        $dbserver = $_POST["dbserver"];
        if (trim($dbserver) == "") {
            $state = "error";
            $errors->dbserver = "You didn't put anything in this box!";
        }
        $dbname = $_POST["dbname"];
        if (trim($dbname) == "") {
            $state = "error";
            $errors->dbname = "You didn't put anything in this box!";
        }
        $dbuser = $_POST["dbuser"];
        if (trim($dbuser) == "") {
            $state = "error";
            $errors->dbuser = "You didn't put anything in this box!";
        }
        $dbpassword = $_POST["dbpassword"];
        if (trim($dbpassword) == "") {
            $state = "error";
            $errors->dbpassword = "You didn't put anything in this box!";
        }
        if(isset($errors)) {
            $state = "inputerror";
        } else {
            //now try to write the lot the config file.
            include_once(dirname(__FILE__)."/../Core/Configuration/ConfigurationHandlers/BaseConfigurationHandler.php");
            include_once(dirname(__FILE__)."/../Core/Modules/DataContext/MySql_V1/DataContextConfigurationHandler.php");
            $configFile = dirname(__FILE__)."/../Core/Modules/DataContext/MySql_V1/Configuration.xml";
            $config = new \Swiftriver\Core\Modules\DataContext\MySql_V1\DataContextConfigurationHandler($configFile);
            $xml = $config->xml;
            $xml->properties->property[0]["value"] = $dbserver;
            $xml->properties->property[1]["value"] = $dbuser;
            $xml->properties->property[2]["value"] = $dbpassword;
            $xml->properties->property[3]["value"] = $dbname;
            $config->xml = $xml;
            $config->Save();
            $state = "finished";
        }
    }

    $welcomeMessage =
             "Ok, time to setup the database. Before you fill in the details ".
             "here, you should have created a database, user, and given ".
             "that user full rights to the database. If you haven't done that already, ".
             "do it before continuing.";

    $errorMessage =
             "There were some issues with the data you entered. Can you check it ".
             "for me and try again?";

    $finishedMessage =
            "That wasn't that bad now was it? I think were done here. As I said ".
            "I'm now going to hand you over to my old friend the Ushahidi installer so it ".
            "can walk you though the final bits of the installation, then you're done! ".
            "Oh, one last thing before I go...In future builds, I'll be handle all ".
            "the setup tasks. Thanks and see you on the other side!"

?>
<div id="index">
    <script language="javascript" type="text/javascript">
        var state = "<?php echo($state); ?>";


        $(document).ready(function(){
            if(state == "new") {
                var time = GetTime("<?php echo($welcomeMessage); ?>");
                DoWriteMessage(
                    "div#baloon div.mid div.message",
                    "<?php echo($welcomeMessage); ?>",
                    time
                );
                setTimeout("$('div.input').show()", (time * 1000) + 500)
            } else if (state == "finished") {
                var time = GetTime("<?php echo($finishedMessage); ?>");
                DoWriteMessage(
                    "div#baloon div.mid div.message",
                    "<?php echo($finishedMessage); ?>",
                    time
                );
                setTimeout("$('div.action').show()", (time * 1000) + 500)
            } else {
                $('div.input').show();
            }
        });
    </script>
    <img id="logo-callout" src="Assets/Images/logo-callout.png" />
    <div id="baloon">
        <div class="top">&nbsp;</div>
        <div class="mid">
            <div id="messages">
                <div class="message"><?php echo(isset($errors)) ? $errorMessage : ''; ?></div>
                <div class="input" style="display:none;">
                    <hr />
                    <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST">
                        <table>
                            <tr>
                                <td colspan="2" class="message">
                                    First, I need you to tell me how to address the 
                                    database server - this is normally 'localhost'.
                                </td>
                            </tr>
                            <td colspan="2" class="error" style="display:<?php echo ($errors->dbserver != null) ? 'block' : 'none'; ?>;">
                                <?php echo($errors->dbserver); ?>
                            </td>
                            <tr>
                                <td class="input">
                                    <input type="text" name="dbserver" value="<?php echo $_POST['dbserver'] ?>" class="<?php echo ($errors->dbserver != null) ? 'error' : ''; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="message">
                                    Now tell us your database's name:
                                </td>
                            </tr>
                            <td colspan="2" class="error" style="display:<?php echo ($errors->dbname != null) ? 'block' : 'none'; ?>;">
                                <?php echo($errors->dbname); ?>
                            </td>
                            <tr>
                                <td class="input">
                                    <input type="text" name="dbname" value="<?php echo $_POST['dbname'] ?>" class="<?php echo ($errors->dbname != null) ? 'error' : ''; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="message">
                                    Now the name of the database user:
                                </td>
                            </tr>
                            <td colspan="2" class="error" style="display:<?php echo ($errors->dbuser != null) ? 'block' : 'none'; ?>;">
                                <?php echo($errors->dbuser); ?>
                            </td>
                            <tr>
                                <td class="input">
                                    <input type="text" name="dbuser" value="<?php echo $_POST['dbuser'] ?>" class="<?php echo ($errors->dbuser != null) ? 'error' : ''; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="message">
                                    And finally the password for that user:
                                </td>
                            </tr>
                            <td colspan="2" class="error" style="display:<?php echo ($errors->dbpassword != null) ? 'block' : 'none'; ?>;">
                                <?php echo($errors->dbpassword); ?>
                            </td>
                            <tr>
                                <td class="input">
                                    <input type="password" name="dbpassword" value="<?php echo $_POST['dbpassword'] ?>" class="<?php echo ($errors->dbpassword != null) ? 'error' : ''; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="submit">
                                    <input type="submit" value="Finished!" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="action" style="display:none;">
                    <form action="../webapp/installer/index.php" method="GET">
                        <input type="submit" value="Bye for now ..." class="button" />
                    </form>
                </div>
            </div>
        </div>
        <div class="bottom">&nbsp;</div>
    </div>

</div>
<?php include_once("footer.php"); ?>