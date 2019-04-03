<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="es">
<!--<![endif]-->  
<?php
$cla = "";
include_once ("include.php");
include_once ($path_sis."config.php");
include_once ($path_sis."revisaSesion.php");
include_once ($path_sis."lang/es.php");
include_once ($path_cla."Mysql.class.php");
include_once ($path_cla."ComunesEstadisticas.class.php");
include_once ($path_cla."Mysql.class.php");
include_once ($path_cla."RegistraActualizaciones.class.php");
$db = new Mysql ( $_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true );

$pages = null;
$_REQUEST['opc'] = 1;
$obj = new RegistraActualizaciones($db, $_REQUEST, $_SESSION, $_SERVER, $path_web, $pages);
$content = $obj->obtenBuffer();
include_once ($path_sys . "cabeceras.php");
?>
<!--<body class="home-page-body"><br><br>  -->
<body ><br><br>
	<form name='forma' id='forma' method='post' action='<?=$PHP_SELF?>' enctype='multipart/form-data'>
		<input type='hidden' name='userId' id='userId' value='<?=$_SESSION['userId']?>'> 
		<input type='hidden' name='aplicacion' id='aplicacion' value='<?=$_SESSION['aplicacion']?>'> 
		<input type='hidden' name='apli_com' id='apli_com' value='<?=$_SESSION['apli_com']?>'> 
		<input type='hidden' name='apli_rol' id='apli_rol' value='<?=$_SESSION['rol']?>'>
		<input type='hidden' name='opc' id='opc' value=''> 
		<div class="wrapper">
			<div class="content  cuerpo"><?=$content?></div>
		</div>
	</form>
</body>
</html>