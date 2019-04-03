<?php
include_once("../includeAjax.php");
include_once("../config.php");
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."Permisos.class.php");
$db  = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$pages = new Paginador();
$objAct = new Permisos($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
if($_REQUEST['opc'] < 3){
	$content = $objAct->obtenBuffer();
	echo $content;
}
else{
	$content = $objAct->obtenArray();
	echo json_encode($content, JSON_FORCE_OBJECT);
}
?>