<?php
include_once("../includeAjax.php");
include_once($path_sis."config.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."Comunes.class.php");
include_once($path_int."InterfazCatalogos.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."EventosArtisticos.class.php");
$db     = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$pages  = new Paginador();
$objAct = new EventosArtisticos($db,$_REQUEST,$_SESSION,$path_web,$pages);
$content = $objAct->obtenBuffer();
echo $content;
?>