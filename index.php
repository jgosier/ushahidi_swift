<?php
$uri = (file_exists(dirname(__FILE__)."/webapp/application/config/database.php"))
    ? "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]."/webapp"
    : "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]."/installer";
?>
<html>
    <head>
        <script language="javascript" type="text/javascript">
            window.location ="<?php echo($uri); ?>";
        </script>
    </head>
</html>