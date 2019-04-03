<?php
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."ModuloAcciones.class.php");
include_once($path_cla."Acciones.class.php");
$pages = new Paginador();
$objAct = new ModuloAcciones($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
$content = $objAct->obtenBuffer();
?>