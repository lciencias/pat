<?php
include_once("../includeAjax.php");
include_once("../config.php");
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."Mysql.class.php");
include_once($path_sis."swift/lib/swift_required.php");
include_once($path_cla."Notificaciones.class.php");
$db  = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$objAct = new Notificaciones($db,$_REQUEST,$_SESSION,$_SERVER,$path_web);
echo $objAct->obtenExito();
?>