<?php
ini_set('display_errors', 'off');
$title   ="Sistema de Informaci&oacute;n de la Secretaria de Cultura";

$path_web="http://localhost/secultura/";
$path_sis="c:/wamp/www/secultura/";
//$path_sis="c:/xampp/htdocs/secultura/";
$path_sys=$path_sis;
$path_cla=$path_sis."clases/";
$path_lib=$path_sis."lib/";
$path_css=$path_web."css/";
$path_js =$path_web."js/";
$path_img=$path_web."img/";

$meses[0]='Anual';
$meses[1]='Enero';
$meses[2]='Febrero';
$meses[3]='Marzo';
$meses[4]='Abril';
$meses[5]='Mayo';
$meses[6]='Junio';
$meses[7]='Julio';
$meses[8]='Agosto';
$meses[9]='Septiembre';
$meses[10]='Octubre';
$meses[11]='Noviembre';
$meses[12]='Diciembre';
include_once("config.php");
include_once($path_sis."session_user.php");
?>