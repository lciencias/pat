<?php
include_once($path_cla."Comunes.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."Acciones.class.php");
include_once($path_cla."RegistraReportes.class.php");
$pages = new Paginador();
$obj = new RegistraReportes($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages,$path_sys);
$content = $obj->obtenBuffer();
$_SESSION['noPasoFormato']=$obj->obtenNoPasoFormato();
if($obj->obtenNoPasoFormato() < 5)
	$_SESSION['folio'] = 0;

