<?php
include_once($path_cla."ComunesEstadisticas.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."Mysql.class.php");
include_once($path_int."InterfazCatalogos.php");
include_once($path_cla."ComunesEstadisticas.class.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."CrearTablas.class.php");

$pages = new Paginador();
$obj = new CrearTablas($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
$content = $obj->obtenBuffer();

