<?php
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."Actividades.class.php");
include_once($path_cla."Festivales.class.php");
include_once($path_cla."Institucional.class.php");
include_once($path_cla."AHistorico.class.php");
include_once($path_cla."Talleres.class.php");

include_once($path_cla."ModuloActividades.class.php");
$pages = new Paginador();
$objAct = new ModuloActividades($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
$content = $objAct->obtenBuffer();
?>