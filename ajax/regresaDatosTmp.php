<?php
include_once("../includeAjax.php");
include_once($path_sis."config.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."ComunesEstadisticas.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."DatosTableTemporal.class.php");

$db  	 = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$pages   = new Paginador();
$objAct  = new DatosTableTemporal($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
echo $objAct->obtenDatos();
?>