<?php

include_once("../includeAjax.php");
include_once($path_sis."config.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_sis."lang/es.php");
include_once($path_cla."AsignaProyectos.class.php");
$content="";
$db     = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$pages  = new Paginador();
$objAct = new AsignaProyectos($db,$_REQUEST,$_SESSION,$path_web,$pages);
$content = $objAct->obtenBuffer();
echo $content;
?>