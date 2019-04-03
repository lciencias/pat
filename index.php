<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->  
<?php
$logo = "logo_guinda.png";
$mensaje = "";
include_once ("include.php");
include_once ($path_sis . "lang/es.php");
if ((trim ( $_REQUEST ["usuario"] ) != "") && (trim ( $_REQUEST ["clave"] ) != "")) {
	include_once ($path_cla . "Mysql.class.php");
	include_once ($path_cla . "Comunes.class.php");
	include_once ($path_cla . "ValidaUsuario.class.php");
	include_once ($path_cla . "Session.class.php");
	$db = new Mysql ( $_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true );
	$objVal = new ValidaUsuario ( $db, $_REQUEST, $_SERVER, $path_web );
	if ($objVal->obtenExito ()) {
		$obj_s = new Session ( $db, $_SESSION, $_SERVER );
		$sesion_valida = $obj_s->Obten_Sesion ();
		$_SESSION ["session"] 	 = $sesion_valida;
		$_SESSION ['userId'] 	 = $objVal->obtenIdUser ();
		$_SESSION ['userNm'] 	 = $objVal->obtenNmUser ();
		$_SESSION ['userNivel']  = $objVal->obtenNivelUser ();
		$_SESSION ['userArea'] 	 = $objVal->obtenAreaUser ();
		$_SESSION ['estilo'] 	 = $objVal->obtenEstilo ();
		$_SESSION ['banner'] 	 = $objVal->obtenBanner ();
		$_SESSION ['areas'] 	 = $objVal->obtenAreas ();
		$_SESSION ['programas']  = $objVal->obtenProgramas();
		$_SESSION ['anocaptura'] = $objVal->obtenAnoCaptura();
		$_SESSION ['regs'] = 200;
		$_SESSION ['page'] = 1;
		$_SESSION ['letra'] = "";
		$_SESSION ['folio'] = 0;
		$_SESSION ['rol']   = $objVal->obtenRol();
		$_SESSION ['noPasoFormato'] = 0;
		$_SESSION ['aplicacion'] = 0;
		$_SESSION ['apli_com'] = 0;
		$_SESSION ['ip'] = $_SERVER['REMOTE_ADDR'];
		header ( "Location: " . $path_web . "aplicacion.php" );
	} else {
		$mensaje = $objVal->obtenMensaje ();
	}
}
else{
	include_once ($path_cla."Mysql.class.php");
	include_once ($path_cla."Logotipo.class.php");
	$db  = new Mysql ( $_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true );
	$obj = new Logotipo($db,$_REQUEST,$path_web);
	$logo= $obj->obtenLogotipo();
}
include_once ($path_sys . "cabeceras.php");
?>
<body class="home-page-body">
	<div class="wrapper">
		<!-- ******HEADER****** -->
		<header class="header">
			<div class="header-main container">
				<div class="logo" id="logo">
					<img src="<?=$path_img?><?=$logo?>" border="0" width="100%">
				</div>
			</div>
		</header>
		<!-- *******MENU******** -->
		<div class="franja"></div>
		<!-- ******CONTENT****** -->
		<div class="content container cuerpo">
			<div class="row cols-wrapper">
				<br>
				<br>
				<div class="container-login">
					<form class="form-signin" role="form" method="post" action="index.php">
					<h3 class="form-signin-heading titulos">Sistema del Programa Anual de Trabajo (PAT)</h3>
						<h6 class="form-signin-heading" style="text-align:center;"><?=INICIO?></h6>
						<center>
							<input type="text" id="usuario" style='height: 30px;'
							name="usuario" style="width:120px;" class="form-control"
							placeholder="<?=USUARIO?>" required autofocus>
							<br> 
							<input type="password" id="clave" style='height: 30px;' name="clave"
							size="25" class="form-control"
							placeholder="<?=CLAVE?>" required> <br>
							<button class="btn btn-danger" name="buttonValida"
							id="buttonValida" type="submit" value="1">
							<span class="glyphicon glyphicon-hand-right"></span>&nbsp;&nbsp;<?=BTNSESION?></button>
						<br><?=$mensaje?>
						</center>
					</form>
				</div>
				<br>
				<br>
			</div>
		</div>
		<div class="content container franja"></div>
		<!-- *******FOOTER******** -->
		<div class="content container footer">
			<span class="titulosFooter"><?=FOOTER?></span>
		</div>

	</div>
</body>
</html>