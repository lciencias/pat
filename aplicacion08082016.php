<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="es">
<!--<![endif]-->  
<?php
$var = "aaa";
$cla = "";
include_once ("include.php");
include_once ($path_sis."config.php");
include_once ($path_sis."revisaSesion.php");
include_once ($path_sis."lang/es.php");
include_once ($path_cla."Mysql.class.php");
include_once ($path_cla."Menu.class.php");
include_once ($path_cla."Catalogos.class.php");
include_once ($path_cla."RevisaParametros.class.php");
$tmp_menu = $_REQUEST ["aplicacion"] + 0;
$tmp_submenu = $_REQUEST ["apli_com"] + 0;

$_SESSION ["aplicacion"] = $tmp_menu;
$_SESSION ["apli_com"] = $tmp_submenu;
//numeros de registros en la lista
if($_REQUEST['ipp'] > 0){
	$_SESSION ["regs"] = $_REQUEST['ipp'];
}
else{
	if($_SESSION['regs'] =="")
		$_SESSION ["regs"]=200;
}
//numero de paginas
if($_REQUEST['page'] > 0){
	$_SESSION ["page"] = $_REQUEST['page'];
	if($_SESSION['page'] ==1){
		$_SESSION['page']=0;
	}	
	elseif($_SESSION['page'] ==2){
		$_SESSION['page'] = (1 * $_SESSION['regs']);		
	}
	else{
		$_SESSION['page'] = (($_SESSION['page']-1) * $_SESSION['regs']);
	}
}
else
{
	$_SESSION ['page']=0;
	$_SESSION ['regs']=200;
}

if( ($_SESSION["aplicacion"] == 0) && $_SESSION["apli_com"] == 0){
	$_SESSION["aplicacion"] = 2;
	$_SESSION["apli_com"] = 1;
}
	$_SESSION["letra"] = "";
if($_REQUEST['letra']!=""){
	if($_REQUEST['letra']=="-")
		$_SESSION["letra"]="";
	$_SESSION["letra"]= $_REQUEST['letra'];
}

$db = new Mysql ( $_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true );
$objMenu = new Menu ( $db, $path_web, $_SESSION ['userId'] );
$menus   = $objMenu->Obten_Menu ();
$objPara = new RevisaParametros ( $db, $_SESSION, $path_web );
$url     = $objPara->obtenUrl ();
$aviso   = $objPara->obtenAViso();
if(trim($url) == "estadisticas/index.php"){
	$urlEst = $path_est_web."index.php";
//	echo "<script language=Javascript> location.href='".$urlEst."'; </script>";
	header("Location: ".$urlEst);
}
$urlEst  = "";
if($_SESSION["aplicacion"] == 3 && $_SESSION["apli_com"] == 27){
	$objcat = new Catalogos($db, array('opcion' => 12), $_SESSION, $_SERVER, $path_web);
	$catMeds = $objcat->obtenBuffer();
	
	$objcat = new Catalogos($db, array('opcion' => 13), $_SESSION, $_SERVER, $path_web);
	$catTAct = $objcat->obtenBuffer();
	
	$objcat = new Catalogos($db, array('opcion' => 14), $_SESSION, $_SERVER, $path_web);
	$catPond = $objcat->obtenBuffer();
}
if (file_exists($path_sis.$url)) {
	include_once ($path_sis.$url);
}

if (trim ( $objPara->obtenTitulo () ) == "")
	$content = "<br><br><br><br><br><br><br><br><br><br><br>";

include_once ($path_sys . "cabeceras.php");
?>
<body class="home-page-body">
	<form name='forma' id='forma' method='post' action='<?=$PHP_SELF?>' enctype='multipart/form-data'>
		<input type='hidden' name='userId' id='userId' value='<?=$_SESSION['userId']?>'> 
		<input type='hidden' name='aplicacion' id='aplicacion' value='<?=$_SESSION['aplicacion']?>'> 
		<input type='hidden' name='apli_com' id='apli_com' value='<?=$_SESSION['apli_com']?>'> 
		<input type='hidden' name='apli_rol' id='apli_rol' value='<?=$_SESSION['rol']?>'>
		<input type='hidden' name='opc' id='opc' value=''> 
		<input type='hidden' name='tableId' id='tableId' value='<?=$_REQUEST['idTabla']?>'> 
		<?php if($_SESSION["aplicacion"] == 3 &&   $_SESSION["apli_com"] == 27){?>
		<input type='hidden' name='catTipoAct' id='catTipoAct' value='<?=$catTAct?>'>
		<input type='hidden' name='catMedidas' id='catMedidas' value='<?=$catMeds?>'>
		<input type='hidden' name='catPondera' id='catPondera' value='<?=$catPond?>'>
		<?php }?>
		<div class="wrapper">
			<!-- <header class="header">  
            <div class="header-main container">
                <div class="logo" id="logo">
				<img id="logo" src="<?=$path_img?>logo_guinda.png" width="100%" title="<?=LOGO?>" alt="<?=LOGO?>">
				</div>        
            </div>
        </header> -->
			<!-- ******NAV****** -->
			<nav class="main-nav container" role="navigation">
				<div class="container">
				<?=$menus?>
            </div>
			</nav>
			<div class="content container cuerpo">
				<div id="nombreUsuario">
					<div id="columna1">
						<span class="negritas"><?=BIENVENIDO?></span>
						<span class="textNombreUsuario"><?=$_SESSION['userNm']?><br><?=date('d-m-Y')?></span>
					</div>
					<div id="columna2" style="text-align: right;">
                                            <span class="textNombreUsuario">Seleccione A&ntilde;o:&nbsp;&nbsp;&nbsp;</span>
                                            <?=$objPara->obtenAno();?>
                                        </div>					
				</div><br>
				<div class="tdright"><?=$aviso?></div><br>
				<div class="row cols-wrapper"><?=$content?></div>
			</div>
			<div class="content container franja"></div>
			<div class="content container footer">
				<span class="titulosFooter"><?=FOOTER?></span>
			</div>
		</div>
	</form>
	
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:650px;">
           	<div class="modal-content" style="width:650px;">
               	<div class="modal-header">
               		<button type="button" class="close" width="30px" data-dismiss="modal">
			  			<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
			  		</button>
               	  	<h5 class="modal-title" id="myModalLabel"><span id="spanmyModalLabel"><?=SCDF?></span></h5>
               	</div>
               	<div class="modal-body"  style="width:650px;">
	               	<input type="hidden" id="idProyecto"  name="idProyecto"  value="0">
	               	<input type="hidden" id="idActividad" name="idActividad" value="0">
	               	<input type="hidden" id="idTrimestre" name="idTrimestre" value="0">
	               	<input type="hidden" id="random" name="random" value="0">
	               	<textarea class="summernote" id="summernote" name="content"></textarea>					
					<table width="90%" class="table table-striped">
					<tr><td colspan="3" class="tdcenter error"><marquee direction="left"><?=MENSAJECOPIADO?></marquee></td></tr>
						<tr>
							<td><label class="tdleft subtitulos" for="inputSuccess1"><?=ADJUNTOS?></label></td>
               				<td><input id="fileToUpload" type="file" size="45" name="fileToUpload" >
               				</td>
               				<td><button class="btn btn-default btn-sm" id="buttonUpload" onclick="return ajaxFileUpload();">Upload</button></td>
               			</tr>
               			<tr><td colspan="3" class="tdcenter"><span id="resultado"></span></td></tr>
               			<tr><td colspan="3" class="tdleft"><span id="downloadFiles"></span></td></tr>
               		</table>                 	
               	</div>
				<div class="modal-footer tdcenter">
            		<button type="button" class="btn btn-success btn-sm"  id="btncomentario" value="0" name="btncomentario"><?=GUARDAR?></button>
                	<button type="button" class="btn btn-primary cerrarAccion" id="cerrarAccion" name="cerrarAccion"><?=CERRARVENTANA?></button>
            	</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="myModalValida" tabindex="-1" role="dialog" aria-labelledby="myModalValidaLabel" aria-hidden="true">
    	<div class="modal-dialog" style="width:650px;">
           	<div class="modal-content" style="width:650px;">
               	<div class="modal-header">
        			<button type="button" class="close" width="30px" data-dismiss="modal">
				  		<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				  	</button>
                	<h5 class="modal-title" id="myModalValidaLabel"><span id="spanmyModalLabel"><?=SCDF?></span></h5>
                </div>
                <div class="modal-body"  style="width:650px;">
                	<input type="hidden" id="idProyectoV" name="idProyectoV" value="0">
                	<input type="hidden" id="idActividadV" name="idActividadV" value="0">
                	<input type="hidden" id="idTrimestreV" name="idTrimestreV" value="0">
                	<input type="hidden" id="randomV" name="randomV" value="0">
					<textarea class="summernote" id="summernoteV" name="contentV" rows="10"></textarea>
					<br>
					<span id="downloadFiles"></span>            	
                </div>
                <div class="modal-footer tdcenter">
                 	<!--  <button type="button" class="btn btn-success btn-sm"  id="btncomentarioA" name="btncomentarioA"><?=APROBAR?></button>-->
                 	<button type="button" class="btn btn-danger btn-sm"   id="btncomentarioNA" name="btncomentarioNP"><?=NOAPROBAR?></button>
                  	<button type="button" class="btn btn-primary cerrarAccion" id="cerrarAccion" name="cerrarAccion"><?=CERRARVENTANA?></button>
                </div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="myModalVisualiza" tabindex="-1" role="dialog" aria-labelledby="myModalValidaLabel" aria-hidden="true">
    	<div class="modal-dialog" style="width:650px;">
           	<div class="modal-content" style="width:650px;">
               	<div class="modal-header">
        			<button type="button" class="close" width="30px" data-dismiss="modal">
				  		<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				  	</button>
                	<h5 class="modal-title" id="myModalValidaLabel"><span id="spanmyModalLabel"><?=SCDF?></span></h5>
                </div>
                <div class="modal-body"  style="width:650px;">
					<textarea class="summernote" id="summernoteVis" name="contentVis" rows="10"></textarea>
					<br>
					<span id="downloadFiles"></span>            	
                </div>
                <div class="modal-footer tdcenter">
                  	<button type="button" class="btn btn-primary cerrarAccion" id="cerrarAccion" name="cerrarAccion"><?=CERRARVENTANA?></button>
                </div>
			</div>
		</div>
	</div>
	
	<!--  modal para ayudas -->
	
	<div class="modal fade" id="myModalAyudas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:650px;">
           	<div class="modal-content" style="width:650px;">
               	<div class="modal-header">
               		<button type="button" class="close" width="30px" data-dismiss="modal">
			  			<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
			  		</button>
               	  	<h5 class="modal-title" id="myModalLabel"><span id="spanmyModalLabel"><?=SCDF?></span></h5>
               	</div>
               	<div class="modal-body"  style="width:650px;">
               		<input type="hidden" id="id"  name="id"  >
               		<input type="hidden" id="opcion"  name="opcion"  >
               		<table class='table table-striped'>
               			<tr>
               				<td width="15%">Titulo:</td>
               				<td><input type="text" placeholder="Titulo" id="titulo" name="titulo" style="width:450px;" ></td>
               			</tr>
               			<tr>
               				<td width="15%">Comentario:</td>
               				<td><textarea id="content" name="content" placeholder="Comentarios" style="width:450px;" rows="8"></textarea></td>
               			</tr>
               		</table>
               	</div>
				<div class="modal-footer tdcenter">
            		<button type="button" class="btn btn-success btn-sm"  id="btnayuda" name="btnayuda"><?=GUARDAR?></button>
                	<button type="button" class="btn btn-primary cerrarAccion" id="cerrarAccion" name="cerrarAccion"><?=CERRARVENTANA?></button>
            	</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="myModalTrimestres" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:650px;">
        	<div class="modal-content" style="width:650px;">
            	<div class="modal-header">
               		<button type="button" class="close" width="30px" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
					</buton>
               	  	<h5 class="modal-title" id="myModalLabel"><span id="spanmyModalLabel"><?=SCDF?></span></h5>
                </div>
                <div class="modal-body"  style="width:650px;">
	               	<table class='table table-striped'>
	                	<tr>
	                    	<td width="15%">Comentario:</td>
	                        <td><textarea class="summernote" id="summernoteMas" name="contentMas"></textarea></td>
						</tr>
	                    <tr>
	                    	<td colspan="2" class="tdcenter"><span id="files"></span></td>
	                    </tr>
	               	</table>
                </div>
                <div class="modal-footer tdcenter">
	               	<button type="button" class="btn btn-primary cerrarAccion" id="cerrarAccion" name="cerrarAccion"><?=CERRARVENTANA?></button>
                </div>
			</div>
        </div>
	</div>
	

	<div class="modal fade" id="myModalActualizaTmp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:850px;height: 500px;">
        	<div class="modal-content" style="width:850px;height: 500px;">
                <div class="modal-body"  style="width:850px;height: 500px;">
					<div id="content">
					    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
					        <li class="active"><a href="#tabProyecto" data-toggle="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=PROYECTO?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
					        <li class="bg-warning"><a href="#tabActividades" data-toggle="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=ACTIVIDAD?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
					        <li class="bg-warning"><a href="#tabComentarios" data-toggle="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=COMENTARIOS?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
					    </ul>
					    <div id="my-tab-content" class="tab-content" style="border:1px solid #e5e5e5;">
					        <div class="tab-pane active" id="tabProyecto">
					           <input type="hidden" name="idProyectoTmp" id="idProyectoTmp" value = "">
								<table class='table table-condensed'>
									<tr class='altotitulo'>
										<td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;<?=PROYECTO?></td>
										<td class='tdleft alinea'><input type='text' class='form-control' placeholder='<?=PROYECTOS?>' id='inputNombreAct' maxlength='250' value='' style='width:350px;'></td>
									</tr>
									<tr class='altotitulo'>
										<td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;<?=PONDERACION?></td>
										<td class='tdleft alinea' colspan='2'>
											
										</td>
									</tr>
									<tr class='altotitulo'>
										<td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;<?=PRESUPUESTO?></td>
										<td class='tdleft alinea'><?=(date ( 'Y' ) - 1)?>&nbsp;&nbsp;$&nbsp;
											<input type='text' class='form-control-num'  placeholder='<?=PRESUPUESTONUMBER?>'  name='presupuestoc_1' id='presupuestoc_1' size='12' value=''>&nbsp;&nbsp;<?=OTORGADO?>
										</td>
									</tr>
									<tr class='altotitulo'>
										<td class='tdleft bold'>&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td class='tdleft alinea'><?=(date ( 'Y' ) + 0)?>&nbsp;&nbsp;$&nbsp;
											<input type='text' class='form-control-num'  placeholder='<?=PRESUPUESTONUMBER?>'  name='estimadoc_1' id='estimadoc_1' value=''>&nbsp;&nbsp;<?=ESTIMADO?>
										</td>
									</tr>
								</table>
					        </div>
					        <div class="tab-pane" id="tabActividades">
					            <span id="resulActAva"></span>
					        </div>
					        <div class="tab-pane" id="tabComentarios">
					             <textarea class="summernote" id="actSummernoteMasc" name="actSummernoteMasc" style="width:450px;"></textarea>
					             <br>
					             <span id="adjuntosM"></span>
					        </div>
					    </div>
					</div>	
                </div>
                <div class="modal-footer tdcenter">
                	<button type='button' class='btn btn-success btn-xs' id='actualizadataTmp' name='actualizadataTmp'>
                		<span class='glyphicon glyphicon-floppy-saved'></span>&nbspGuardar</button>
	               	<button type="button" class="btn btn-default btn-xs cerrarAccion" id="cerrarAccion" name="cerrarAccion"><?=CERRARVENTANA?></button>
                </div>
			</div>
        </div>
	</div>	        
</body>
</html>
