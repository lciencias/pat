<?php
include_once("../includeAjax.php");
include_once($path_sis."config.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."Comunes.class.php");
include_once($path_cla."RegresaArchivos.class.php");
$db     = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$objAct = new RegresaArchivos($db,$_REQUEST,$_SESSION,$path_web);
$content = $objAct->obtenBuffer();
echo $content;
die();
?>