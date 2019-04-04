<?php
include_once("../includeAjax.php");
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_int."InterfazCatalogos.php");
include_once($path_cla."Anos.class.php");
$pages   = new Paginador();
$objAct  = new Anos($db,$_REQUEST,$_SESSION,$path_web,$pages);
$content = $objAct->obtenFolio();
echo $content;
?>