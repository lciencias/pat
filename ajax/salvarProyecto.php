<?php

include_once("../includeAjax.php");
include_once($path_sis."config.php");
include_once($path_cla."Mysql.class.php");
include_once($path_int."InterfazCatalogos.php");
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_sis."lang/es.php");
include_once($path_cla."InsertaProyectos.class.php");

$content="";
$db     = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$pages  = new Paginador();
$objAct = new InsertaProyectos($db,$_REQUEST,$_SESSION,$path_web,$pages);
$content = $objAct->obtenBuffer();
if($_REQUEST['opc'] == 1 || $_REQUEST['opc'] == 3 || $_REQUEST['opc'] == 5){
	$_SESSION['folio'] = $objAct->obtenBuffer();
}
if($_REQUEST['opc'] == 2 || $_REQUEST['opc'] == 4 || $_REQUEST['opc'] == 6){
	$_SESSION['folio'] = $objAct->obtenIdProyecto();
}
echo $content;
?>