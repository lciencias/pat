<?php
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."Acciones.class.php");
include_once($path_cla."RegistraProyectosAdmin.class.php");
$pages = new Paginador();
$obj = new RegistraProyectosAdmin($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
$content = $obj->obtenBuffer();
?>
