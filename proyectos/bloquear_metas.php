<?php
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."LimiteCapturaProyectos.class.php");
$pages = new Paginador();
$obj   = new LimiteCapturaProyectos($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
$content = $obj->obtenBuffer();
?>