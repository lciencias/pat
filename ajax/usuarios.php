<?php
include_once("../includeAjax.php");
include_once("../config.php");
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_int."InterfazCatalogos.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."Usuarios.class.php");
$db      = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$pages   = new Paginador();
$objAct  = new Usuarios($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
$content = $objAct->idFolio();
echo $content;
?>