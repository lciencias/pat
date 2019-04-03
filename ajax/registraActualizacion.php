<?php

include_once("../includeAjax.php");
include_once($path_sis."config.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."ComunesEstadisticas.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_sis."lang/es.php");
include_once($path_cla."RegistraActualizaciones.class.php");
$content="";
$db     = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$pages  = new Paginador();
$objAct = new RegistraActualizaciones($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
$content = $objAct->obtenBuffer();
echo $content;
?>