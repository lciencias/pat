<?php
include_once($path_cla."Comunes.class.php");
include_once($path_cla."EstiloSistema.class.php");
$obj   = new EstiloSistema($db,$_REQUEST,$_SESSION,$_SERVER,$path_web);
$content = $obj->obtenBuffer();
?>
