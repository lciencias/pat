<?php
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Logotipo.class.php");
$_REQUEST['opc']=1;
$obj   = new Logotipo($db,$_REQUEST);
$content = $obj->obtenBuffer();
?>
