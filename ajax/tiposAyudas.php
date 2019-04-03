<?php
include_once("../includeAjax.php");
include_once($path_sis."config.php");
include_once($path_sis."revisaSesion.php");
include_once($path_sis."lang/es.php");
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."Ayudas.class.php");
$db  = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$obj = new Ayudas($db,$_REQUEST,$_SESSION,$path_web);
echo $obj->obtenBuffer(); 
?>