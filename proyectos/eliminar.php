<?php
include_once($path_cla."EliminaArchivos.class.php");
$obj = new EliminaArchivos($path_sis);
$content = $obj->obtenBuffer();
?>
