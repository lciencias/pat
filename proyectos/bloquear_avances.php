<?php
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."LimiteCapturaAvances.class.php");
$pages = new Paginador();
$obj   = new LimiteCapturaAvances($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
$content = $obj->obtenBuffer();
?>