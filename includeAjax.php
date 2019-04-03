<?php
ini_set('display_errors', 'off');
ini_set('post_max_size', '60M');
ini_set('upload_max_filesize', '60M');
date_default_timezone_set("America/Mexico_City");
set_time_limit (0);
session_start();
session_cache_limiter("nocache");

include_once("config.php");
$title   ="Sistema de Informaci&oacute;n de la Secretaria de Cultura";
$titleEst = "Estadisticas del Sistema de Informaci&oacute;n de la Secretaria de Cultura";
$path_web="http://sisec.cultura.df.gob.mx/pat/";
$path_sis="/var/www/secultura/pat/";
$path_sys="/var/www/secultura/pat/";
$path_sys=$path_sis;
$path_cla=$path_sis."clases/";
$path_int=$path_sis."interfaces/";
$path_lib=$path_sis."lib/";
$path_css=$path_web."css/";
$path_js =$path_web."js/";
$path_img=$path_web."imagenes/";
$path_files=$path_sis."downFiles/";
$path_est_web=$path_web."estadisticas/";
$path_est_sis=$path_sis."estadisticas/";	

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

?>