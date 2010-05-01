<?php
    error_reporting(E_ERROR);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>SwiftRiver Installer | v0.1.0 Apala</title>
        <script type="text/javascript" language="javascript" src="assets/scripts/jQuery.js"></script>
        <script type="text/javascript" language="javascript" src="assets/scripts/jTypeWriter.js"></script>
        <link rel="stylesheet" media="screen" href="assets/styles/master.css" />
        <script language="javascript" type="text/javascript">
            function GetTime(message) {
                return Math.floor(message.length / 15);
            }
            function DoWriteMessage(target, message, time) {
                $(target).jTypeWriter({duration:time,text:message});
            }
        </script>
    </head>
    <body>
        <div id="page">
