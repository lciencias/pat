<?php
include_once("include.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."Logout.class.php");
$db      = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$objLogout = new Logout($db,$_SESSION,$_SERVER);
session_destroy();
header("Location:  ".$path_web);
?>