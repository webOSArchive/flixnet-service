<?php
//This file is only used for advertising on a hosting webserver

//Figure out what protocol the client wanted
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
	$PROTOCOL = "https";
} else {
	$PROTOCOL = "http";
}
$docRoot = "./";
$appTitle = "Flixnet";
echo file_get_contents("https://www.webosarchive.org/app-template/header.php?docRoot=" . $docRoot . "&appTitle=" . $appTitle . "&protocol=" . $PROTOCOL);
?>
    <style>
        body { background-color: white;}
    </style>
    <div style="font-family:arial,helvetica,sans-serif;margin:15px;" align="center">
        <p>Flixnet is a public domain movie browser hosted and created by provided by <a href="http://www.webosarchive.org">webOS Archive</a> for retro and modern devices.<br/>
        Choose the experience that's best for your platform...</p>
        <table style="margin-left:15%;margin-right:20%;font-size:small;">
            <tr><td width="22%" align="right"><b><a href="app/" target="_blank">PWA</a></b></td><td style="padding-left:18px">Progressive Web Apps work on modern browsers, and can be pinned to your home screen or dock on modern platforms.</td>
            <tr><td width="22%" align="right"><b><a href="https://appcatalog.webosarchive.org/app/Flixnet">webOS</a></b></td><td style="padding-left:18px">Versions built for legacy (mobile) webOS and modern LuneOS.</td></tr>
        </table>
        <p>Flixnet is open source! Code and Releases can be found here:
        <p align="center">
            <li><a href="https://github.com/webosarchive/flixnet-service">Back-end code</a><br>
            <li><a href="https://github.com/webosarchive/EnyoFlixnet">Front-end PWA/webOS code</a>
        </p>
    </div>
</body>
</html>