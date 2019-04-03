<?php
class RegistraActualizaciones extends ComunesEstadisticas {
	var $db;
	var $data;
	var $session;
	var $server;
	var $path;
	var $buffer;
	var $pages;
	var $noPasoFormato;
	var $id;
	var $opc;
	var $arrayDatos;
	var $arrayDatosMetas;
	var $diasLimite;
	var $disabled;
	var $disabledAdmin;
	var $visible;
	var $arrayNotificaciones;
	var $arrayEstatusVisibles;
	function __construct($db, $data, $session, $server, $path, $pages) {
		$this->db = $db;
		$this->data = $data;
		$this->path = $path;
		$this->server = $server;
		$this->session = $session;
		$this->pages = $pages;
		$this->buffer = "";
		$this->noPasoFormato = 0;
		$this->opc = $this->data ['opc'];
		settype ( $this->opc, "integer" );
		$this->opc = ( int ) $this->opc;
		switch ($this->opc) {
			case 0 :
				$this->listadoProyectos ();
				break;
			case 1 :
				$this->BuscaProyectos ();
				break;				
			case 2 :
				$this->actualizaProyecto();
				break;		
			case 3 :
				$this->actualizaActividad();
				break;
			case 4 :
				$this->eliminaProyecto();
				break;				
			case 5 :
				$this->eliminaActividad();
				break;
			case 6:
				$this->actualizaProyectoBasePrincipal();
				break;
			case 7:
				$this->actualizaActividadBasePrincipal();
				break;	
			case 8:
				$this->actualizaVistaBasePrincipal();
				break;				
			default :
				$this->listadoProyectos ();
				break;
		}
	}

	function actualizaVistaBasePrincipal(){
		if((int) $this->opc > 0 && (int) $this->data['idTable'] > 0){
			$this->regresaNombreTabla($this->data['idTable']);
			$this->buffer = $this->actualizaVistaTablaPrincipal();
		}
	}
	
	function actualizaProyectoBasePrincipal(){
		if((int) $this->opc > 0 && (int) $this->data['id'] > 0 ){	
			$this->buffer = $this->actualizaDatosProyectoTablaPrincipal();			
		}
	}
	
	function actualizaActividadBasePrincipal(){
		if((int) $this->opc > 0 && (int) $this->data['id'] > 0  && (int) $this->data['idActividad'] > 0 ){
			$this->buffer = $this->actualizaDatosActividadTablaPrincipal();
		}
	}
	
	function eliminaActividad(){
		if((int) $this->opc > 0 && (int) $this->data['id'] > 0  && (int) $this->data['idTable'] > 0 && (int) $this->data['idActividad'] > 0  ){
 			$this->regresaNombreTabla($this->data['idTable']);
 			$this->buffer = $this->eliminaDatosActividad();
 		}
	}
	
	function eliminaProyecto(){
		if((int) $this->opc > 0 && (int) $this->data['id'] > 0  && (int) $this->data['idTable'] > 0){
			$this->regresaNombreTabla($this->data['idTable']);
			$this->buffer = $this->eliminaDatosProyectos();
		}
	}
	
	function actualizaActividad(){
		if((int) $this->opc > 0 && (int) $this->data['id'] > 0  && (int) $this->data['idActividad'] > 0  && (int) $this->data['idTable'] > 0){
			$this->regresaNombreTabla($this->data['idTable']);
			$this->buffer = $this->actualizaDatosActividad();
		}
	}
	
	function actualizaProyecto(){
		if((int) $this->opc > 0 && (int) $this->data['id'] > 0  && (int) $this->data['idTable'] > 0){
			$this->regresaNombreTabla($this->data['idTable']);
			$this->buffer = $this->actualizaDatosProyectos();
		}
	}
	
	function BuscaProyectos (){
		if((int) $this->opc > 0 && (int) $this->data['id'] > 0  && (int) $this->data['idTable'] > 0){
			$this->regresaNombreTabla($this->data['idTable']);			
			$this->array_datos = $this->regresaDatosTmp();
			if(count($this->array_datos)> 0){
				$this->generaFormulario();				
			}
		}
	}
	
	function generaFormulario(){
		$p1=$p2=$p3=$p4=$p5="";
		switch($this->array_datos[0]['ponderacionProyecto']){
			case 1:
				$p1=" checked ";
				$p2=$p3=$p4=$p5="";
				break;
			case 2:
				$p2=" checked ";
				$p1=$p3=$p4=$p5="";
				break;
			case 3:
				$p3=" checked ";
				$p2=$p1=$p4=$p5="";
				break;
			case 4:
				$p4=" checked ";
				$p2=$p3=$p1=$p5="";
				break;
			case 5:
				$p5=" checked ";
				$p2=$p3=$p4=$p1="";
				break;
			default:
				$p1=$p2=$p3=$p4=$p5="";
				break;
		}		
		$style  = 'class="form-control validanumsM" style="width:60px;text-align:center;" ';
		$styleT = 'style="width:70%;height:150px;"';
		$this->buffer= '
			<input type="hidden" name="idProyectoTmp" id="idProyectoTmp" value="'.$this->data['id'].'">
			<input type="hidden" name="idTable" id="idTable" value="'.$this->data['idTable'].'">					
			'.$this->procesando(2).'			
			<table class="table table-condensed"> 
				<tr class="success">
					<td class="tdleft" style="font-size:18px;"><b><span id="nom">'.$this->array_datos[0]['proyecto'].'</span></b></td>
					<td width="15%" class="tdcenter">
					<button class="btn btn-default" name="cerrar" id="cerrar" onclick="window.close();">'.CERRARVENTANA.'</button>
					</td>
				</tr>
			</table><hr>
			<marquee  behavior="alternate" style="color:#800000;font-size:16px;font-weight:bold;">Al momento de dar click sobre el bot&oacute;n Actualizar Base Original, los datos mostrados se copiar&aacute;n a la base original del sistema.</marquee>
			<div class="alert alert-success" style="color:#000;" id="exito" role="alert"></div>
			<div class="alert alert-info"    style="color:#000;" id="fallo" role="alert"></div>
			<div id="content">
				<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
					<li class="active"><a href="#tabProyecto" data-toggle="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.PROYECTO.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
					<li class="bg-warning"><a href="#tabActividades" data-toggle="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.ACTIVIDAD.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>					
				</ul>
				<div id="my-tab-content" class="tab-content" style="border:1px solid #e5e5e5;">
					<div class="tab-pane active" id="tabProyecto">

						<table class="table table-condensed">
							<tr class="altotitulo">
								<td class="tdleft bold" width="18%">&nbsp;&nbsp;*&nbsp;&nbsp;'.PROYECTO.'</td>
								<td class="tdleft alinea"><input type="text" class="form-control" placeholder="'.PROYECTOS.'" id="inputNombreAct" maxlength="250"  value="'.$this->array_datos[0]['proyecto'].'" style="width:550px;"></td>
							</tr>
							<tr class="altotitulo">
								<td class="tdleft bold">&nbsp;&nbsp;*&nbsp;&nbsp;'.PONDERACION.'</td>
								<td class="tdleft alinea" colspan="2">
									<input type="radio" name="ponderacionc" id="ponderacionc5" '.$p5.' value="5">5&nbsp;&nbsp;
						            <input type="radio" name="ponderacionc" id="ponderacionc4" '.$p4.'  value="4">4&nbsp;&nbsp;
						            <input type="radio" name="ponderacionc" id="ponderacionc3" '.$p3.'  value="3">3&nbsp;&nbsp;
						            <input type="radio" name="ponderacionc" id="ponderacionc2" '.$p2.'  value="2">2&nbsp;&nbsp;
						            <input type="radio" name="ponderacionc" id="ponderacionc1" '.$p1.'  value="1">1
								</td>
							</tr>
							<tr class="altotitulo">
								<td class="tdleft bold">&nbsp;&nbsp;*&nbsp;&nbsp;'.PRESUPUESTO.'</td>
								<td class="tdleft alinea">'.(date ( "Y" ) - 1).'&nbsp;&nbsp;$&nbsp;
									<input type="text" class="form-control-num"  placeholder="'.PRESUPUESTONUMBER.'"  name="presupuestoc_1" id="presupuestoc_1" style="width:150px;" value="'.$this->array_datos[0]['presupuesto_otorgado'].'">&nbsp;&nbsp;<?=OTORGADO?>
								</td>
							</tr>
							<tr class="altotitulo">
								<td class="tdleft bold">&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td class="tdleft alinea">'.(date ( "Y" ) + 0).'&nbsp;&nbsp;$&nbsp;
									<input type="text" class="form-control-num"  placeholder="'.PRESUPUESTONUMBER.'"  name="estimadoc_1" id="estimadoc_1" style="width:150px;" value="'.$this->array_datos[0]['presupuesto_estimado'].'">&nbsp;&nbsp;<?=ESTIMADO?>
								</td>
							</tr>
							<tr class="altotitulo">
								<td class="tdleft bold">&nbsp;</td>
								<td class="tdleft alinea" colspan="2">									
								<button type="button" class="btn btn-primary btn-xs" id="actualizadataTmp" name="actualizadataTmp">
                				<span class="glyphicon glyphicon-floppy-saved"></span>&nbsp;&nbsp;'.GUARDARDATOSPROYECTO.'</button>
                				&nbsp;&nbsp;
								<button class="btn btn-danger actualizarBasetAct" name="actualizaBasePrimaria" id="actualizaBasePrimaria">'.ACTUALIZARBASE.'</button>
                				</td>
                			</tr>
						</table>
					</div>
					<div class="tab-pane" id="tabActividades">
						<div class="panel panel-default">';		
    	foreach($this->array_datos as $tmpData){
    		$arrayMedidas = $this->regresaOpcionesMedidas($tmpData['medida_id']);
    		$arrayPondera = $this->regresaOpcionesPonderacion($tmpData['ponderacionActividad']);
    		$arrayTipos   = $this->regresaOpcionesTipoActividad($tmpData['tipo_actividad_id']);
    		$comentario1  = $comentario2  = $comentario3  = $comentario4  = "";
    		$comentario1  = $tmpData['Comentarios1'];
    		$comentario2  = $tmpData['Comentarios2'];
    		$comentario3  = $tmpData['Comentarios3'];
    		$comentario4  = $tmpData['Comentarios4'];
    		$this->buffer.= '
				<div class="panel-heading" role="tab" id="heading'.$tmpData['actividadId'].'">
					<h4 class="panel-title">
					<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$tmpData['actividadId'].'" aria-expanded="false" aria-controls="collapseOne">
					'.$tmpData['actividadId'].' .- <span id="nmAct">'.$tmpData['actividad'].'</span>
					</a>
					</h4>							
				</div>
				<div id="collapse'.$tmpData['actividadId'].'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'.$tmpData['actividadId'].'">
					<div class="panel-body">
    					<table class="table">
						<tr>
                            <input type="hidden" name="id-'.$tmpData['actividadId'].'" id="id-'.$tmpData['actividadId'].'" value="'.$tmpData['actividadId'].'">
                            <td  class="tdleft bold">Actividad</td>
                            <td colspan="7"><input type="text" name="nm-'.$tmpData['actividadId'].'" id="nm-'.$tmpData['actividadId'].'" value="'.$tmpData['actividad'].'" style="width:100%;"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tdcenter bold">'.MEDIDA.'&nbsp;
                            	<select name="me-'.$tmpData['actividadId'].'" id="me-'.$tmpData['actividadId'].'" class="form-control" style="width:160px;border:1px solid #e5e5e5;">'.$arrayMedidas.'</select>
                            </td>
                            <td colspan="2" class="tdcenter bold">'.PONDERACION.'
                            	<select name="po-'.$tmpData['actividadId'].'" id="po-'.$tmpData['actividadId'].'" class="form-control" style="width:80px;border:1px solid #e5e5e5;">'.$arrayPondera.'</select>
                            </td>
                            <td colspan="2" class="tdcenter bold">'.TIPOACT.'
                            	<select name="ti-'.$tmpData['actividadId'].'" id="ti-'.$tmpData['actividadId'].'" class="form-control" style="width:160px;border:1px solid #e5e5e5;">'.$arrayTipos.'</select>
                            </td>
                            <td colspan="2" class="tdcenter bold">                           		
                            </td>
                        </tr>    
						<tr>
                            <td class="tdcenter"><b>P</b><br><input type="text" name="res['.$tmpData['actividadId'].'][m1]" id="m1-'.$tmpData['actividadId'].'" value="'.$tmpData['trimestre1'].'"  '.$style.'></td>
                            <td class="tdcenter"><b>R</b><br><input type="text" name="res['.$tmpData['actividadId'].'][a1]" id="a1-'.$tmpData['actividadId'].'" value="'.$tmpData['Atrimestre1'].'" '.$style.'></td>
                            <td class="tdcenter"><b>P</b><br><input type="text" name="res['.$tmpData['actividadId'].'][m2]" id="m2-'.$tmpData['actividadId'].'" value="'.$tmpData['trimestre2'].'"  '.$style.'></td>
                            <td class="tdcenter"><b>R</b><br><input type="text" name="res['.$tmpData['actividadId'].'][a2]" id="a2-'.$tmpData['actividadId'].'" value="'.$tmpData['Atrimestre2'].'" '.$style.'></td>
                            <td class="tdcenter"><b>P</b><br><input type="text" name="res['.$tmpData['actividadId'].'][m3]" id="m3-'.$tmpData['actividadId'].'" value="'.$tmpData['trimestre3'].'"  '.$style.'></td>
                            <td class="tdcenter"><b>R</b><br><input type="text" name="res['.$tmpData['actividadId'].'][a3]" id="a3-'.$tmpData['actividadId'].'" value="'.$tmpData['Atrimestre3'].'" '.$style.'></td>
                            <td class="tdcenter"><b>P</b><br><input type="text" name="res['.$tmpData['actividadId'].'][m4]" id="m4-'.$tmpData['actividadId'].'" value="'.$tmpData['trimestre4'].'"  '.$style.'></td>
                            <td class="tdcenter"><b>R</b><br><input type="text" name="res['.$tmpData['actividadId'].'][a4]" id="a4-'.$tmpData['actividadId'].'" value="'.$tmpData['Atrimestre4'].'" '.$style.'></td>
                            </tr>     
						<tr>
                            <td class="default tdleft" colspan="6">
								<div>
								  <ul class="nav nav-tabs" role="tablist">
								    <li role="presentation" class="active"><a href="#home'.$tmpData['actividadId'].'" aria-controls="home" role="tab" data-toggle="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.T1.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
								    <li role="presentation"><a href="#profile'.$tmpData['actividadId'].'" aria-controls="profile" role="tab" data-toggle="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.T2.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
								    <li role="presentation"><a href="#messages'.$tmpData['actividadId'].'" aria-controls="messages" role="tab" data-toggle="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.T3.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
								    <li role="presentation"><a href="#settings'.$tmpData['actividadId'].'" aria-controls="settings" role="tab" data-toggle="tab">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.T4.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
								  </ul>
								  <div class="tab-content">
								    <div role="tabpanel" class="tab-pane active" id="home'.$tmpData['actividadId'].'">
								    	<textarea class="bootstrap-select espTextArea summernote" name="comen1-'.$tmpData['actividadId'].'" id="comen1-'.$tmpData['actividadId'].'" '.$styleT.'>'.htmlentities($comentario1).'</textarea>'.$tmpData['Adjuntos1'].'
								    </div>
								    <div role="tabpanel" class="tab-pane" id="profile'.$tmpData['actividadId'].'">
										<textarea class="bootstrap-select espTextArea summernote" name="comen2-'.$tmpData['actividadId'].'" id="comen2-'.$tmpData['actividadId'].'" '.$styleT.'>'.htmlentities($comentario2).'</textarea>'.$tmpData['Adjuntos2'].'								    		
								    </div>
								    <div role="tabpanel" class="tab-pane" id="messages'.$tmpData['actividadId'].'">
										<textarea class="bootstrap-select espTextArea summernote" name="comen3-'.$tmpData['actividadId'].'" id="comen3-'.$tmpData['actividadId'].'" '.$styleT.'>'.htmlentities($comentario3).'</textarea>'.$tmpData['Adjuntos3'].'			    		
								    </div>
								    <div role="tabpanel" class="tab-pane" id="settings'.$tmpData['actividadId'].'">
								    	<textarea class="bootstrap-select espTextArea summernote" name="comen4-'.$tmpData['actividadId'].'" id="comen4-'.$tmpData['actividadId'].'" '.$styleT.'>'.htmlentities($comentario4).'</textarea>'.$tmpData['Adjuntos4'].'
								    </div>
								  </div>								
								</div>				    			
							</td>
							<td class="default tdcenter" colspan="2"><br>
							<button class="btn btn-success guardaActAct" name="m1-'.$tmpData['actividadId'].'" id="button-'.$tmpData['actividadId'].'">'.GUARDAR.'</button>
							&nbsp;&nbsp;
							<button class="btn btn-default deleteActAct" name="d1-'.$tmpData['actividadId'].'" id="delete-'.$tmpData['actividadId'].'">'.ELIMINARPROYECTO.'</button>
							<br><br><br>
							<button class="btn btn-danger actualizarBasetAct" name="ab-'.$tmpData['actividadId'].'" id="ab-'.$tmpData['actividadId'].'">'.ACTUALIZARBASE.'</button>
							</td>
                       </tr> 
                   </table>
			</div>
        </div>';
    	}
                						
		$this->buffer.= '</div>
					</div>					       
				</div>
			</div>';
	}
	
	
	function obtenFiltrosActividades($folio) {
		$buf = "<form action='aplicacion.php' method='post'>
			<input type='hidden' value='3' id='opc' name='opc'>
			<input type='hidden' name='folio' id='folio' value='" . $folio . "'>	
			<table class='tableSinbordes' align='center'>
			<tr>
				<td class='tdleft' width='34%'>" . $this->regresaMedidas ( 1 ) . "&nbsp;
				<img src='" . $this->path . "imagenes/iconos/help.png' id='a-12' class='help' alt='" . AYUDA . "' title='" . AYUDA . "'>
				</td>
				<td class='tdleft' width='34%'>" . $this->regresaTipoActividad ( 1 ) . "&nbsp;
				<img src='" . $this->path . "imagenes/iconos/help.png' id='a-14' class='help' alt='" . AYUDA . "' title='" . AYUDA . "'>
				</td>
				<td class='tdleft' width='32%'>" . $this->generaPonderacion () . "&nbsp;<img src='" . $this->path . "imagenes/iconos/help.png' id='a-13' class='help' alt='" . AYUDA . "' title='" . AYUDA . "'></td>				
			</tr>
			<tr>
				<td colspan='2' class='tdleft'><input type='text' class='form-control validatextonumero' placeholder='" . BUSCAXACTIVIDAD . "' name='busqNombreA' id='busqNombreA' maxlength='250' value='" . $this->arrayDatos ['actividad'] . "' style='width:430px;' >&nbsp;
						<img src='" . $this->path . "imagenes/iconos/help.png' id='a-15' class='help' alt='" . AYUDA . "' title='" . AYUDA . "'></td>
				<td class='tdleft'>
					<button type='submit' name='btnfiltros' id='btnfiltros' class='btn btn-primary' style='width:140px;'>
					<span class='glyphicon glyphicon-search'></span>&nbsp;" . CONSULTAR . "</button>
					<button type='reset'  name='btnLimpiar' id='btnLimpiar'  class='btn btn-primary' style='width:140px;'>
					<span class='glyphicon glyphicon-refresh'></span>&nbsp;" . LIMPIAR . "</button>
				</td>
			</tr>
		</table></form>";
		return $buf;
	}
	
	function obtenFiltros() {
		$width = "width='25%'";
		$buf = "<form action='aplicacion.php' method='post'>
			<input type='hidden' value='0' id='opc' name='opc'>
			<table class='tableSinbordes' align='center' width='100%'>
			<tr>
				<td class='tdleft' ".$width.">".$this->regresaNombreTablas()."</td>
				<td class='tdleft' ".$width.">".$this->regresaNombreAreaAct()."</td>
				<td class='tdleft' ".$width.">".$this->regresaNombrePrograma()."</td>
				<td class='tdleft' ".$width.">".$this->regresaNombreUnidadOperativa()."</td>	
				
									
			</tr>			
			<tr>
				<td colspan='2' class='tdleft'><input type='text' class='form-control validatextonumero' placeholder='" . BUSCAXPROYECTO . "' name='busqNombre' id='busqNombre' maxlength='250' value='" . $this->arrayDatos ['proyecto'] . "' style='width:410px;'></td>						
				<td class='tdcenter'>
				<button type='submit' name='btnfiltros' id='btnfiltros' class='btn btn-primary' style='width:140px;'><span class='glyphicon glyphicon-search'></span>&nbsp;&nbsp;" . CONSULTAR . "</button>							
			</tr>			
		</table></form>";
		return $buf;
	}
	
	
	function divFiltrosProyectos($opcion, $valor, $url, $urlRegreso, $idEstatus) {
		$mens = "";
		if ($opcion == 1)
			$mens .= $this->obtenFiltros ();
		else
			$mens .= $this->obtenFiltrosActividades ( $valor );
		
		$tit = FILTROSBUSQUEDA;
		$botones = "";
		
		$buf = "<div class=\"tdcenter\"><center>
				<button type='button' class='btn btn-primary' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
						<span class='glyphicon glyphicon-download'></span>&nbsp;&nbsp;" . $tit . "</button>&nbsp;" . $botones . "
			</center><br></div>
		<div class='collapse' id='collapseExample'>
  		<div class='well'>" . $mens . "</div>
		</div>";
		
		return $buf;
	}
	
	/**
	 * Metodo que muestra los headers dependiendo del tipo de usuario
	 * 
	 * @return string regresa las cabeceras como tabla
	 */
	function cabeceras() {
		$buf  = "";
		$buf .="<td class='tdcenter fondotable' width='3%'>" . ID . "</td>
				<td class='tdcenter fondotable' width='20%'>" .PROGRAMA. "</td>
				<td class='tdcenter fondotable' width='20%'>" .AREA. "</td>						
				<td class='tdcenter fondotable' width='20%'>" .UNIDADOPERATIVA. "</td>
			    <td class='tdcenter fondotable' width='20%'>" . PROYECTOS . "</td>
				<td class='tdcenter fondotable' width='10%'>" . FECHAALTA . "</td>
				<td class='tdcenter fondotable' width='7%'>" . ACCIONES . "</td>";
		return $buf;
	}
	/**
	 * Metodo que se encarga de generar el listado de proyectos
	 */
	function listadoProyectos() {
		$class = "";
		$this->buffer = "
					<div class='panel panel-danger spancing'>
						<div class='panel-heading'><span class='titulosBlanco'>" . LISTADODEPROYECTOS . "</span></div>
		  				<div class='panel-body'><center><span id='res'></span></center>" . $this->obtenFiltros(). "";
		$this->buffer .= "<center>" . $this->regresaLetras () . "</center><br>";
		if((int) $this->data['idTabla'] > 0){
			$nomtabla = $this->regresaNombreTabla($this->data['idTabla']);
			$no_registros = $this->consultaNoProyectos ();
			if ($no_registros) {
				$this->pages = new Paginador ();
				$this->pages->items_total = $no_registros;
				$this->pages->mid_range = 25;
				$this->pages->paginate ();
				$resultados = $this->consultaProyectos ();
			}
			
			if (count ( $resultados ) > 0) {
				$arrayAreas = $this->catalogoAreas();
				$arrayOpera = $this->catalogoUnidadesOperativas();
				$arrayProgr = $this->catalogoProgramas();
				$this->buffer .= "<center>" . $nomtabla . "</center>
						<table width='95%' class='table tablesorter table-bordered' align='center' id='MyTableActividades'>
						<thead><tr>" . $this->cabeceras () . "</tr></thead><tbody>";
				$contador = 1;			
				if ($this->session ['page'] <= 1)
					$contadorRen = 1;
				else
					$contadorRen = $this->session ['page'] + 1;
				foreach ( $resultados as $resul ) {
					$class = "";
					if ($contador % 2 == 0)
						$class = "active";
					$this->buffer .= "
							<tr class=' $class alturaComponentesA' id='r-".$resul ['id']."'>
								<td class='tdleft'>".$contadorRen."</td>
								<td class='tdleft'>".trim($arrayProgr[$resul['programa_id']])."</td>
								<td class='tdleft'>".trim($arrayAreas[$resul['unidadResponsableId']]). "</a></td>
								<td class='tdleft'>".trim($arrayOpera[$resul['unidadOperativaId']])."</td>
								<td class='tdleft'>".trim($resul['proyecto'])."</td>	
								<td class='tdcenter'>".substr ( $resul ['fecha_alta'], 0, 10 )."<br>Actividades: ".(int)$resul ['noAcciones']."</td>
								<td class='tdcenter'>"; 
						$this->buffer .= "<button type='button' class='btn btn-default btn-xs actualizaProyectos' id='a-".$resul ['id']."'  name='a-".$resul ['id']."' 
										data-toggle='tooltip' data-placement='bottom' title='" . TOOLTIPEDITARPROYECTO . "'>
										<span class='glyphicon glyphicon-pencil'></span>
									</button>
									<button class='btn btn-default btn-xs eliminarProyectos' id='e-".$resul ['id']."'  name='e-".$resul ['id']."' 
										data-toggle='tooltip' data-placement='bottom' title='" . TOOLTIPROYECTOELIMINAR . "'>
										<span class='glyphicon glyphicon-trash'></span>
									</button>";
					$this->buffer .= "</td></tr>";
					$contador ++;
					$contadorRen ++;
				}
				$this->buffer .= "</body><thead><tr>
							<td class='tdleft' colspan='2'>Total: " . $no_registros . "</td>
							<td colspan='6' class='tdcenter'>&nbsp;</td>						
							</tr></thead></table>
							<table width='100%'><tr>
	                        <td class='tdcenter'>
									".$this->pages->display_jump_menu () . 
									"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									" . $this->pages->display_items_per_page ( $this->session ['regs'] )."
	                   		</td>
	                       </tr></table>";
			}
			
		}else{
			$this->buffer .= "<br><center><h4>Seleccione la tabla temporal a trabajar</h4></center><br>";
		}
		$this->buffer .= "</div></div>";
	}
	
	function regresaPonderacion() {
		$valor = $this->arrayDatos ['ponderacion'] + 0;
		$array = array ();
		for($i = 1; $i <= 5; $i ++) {
			$tmp = "";
			if ($i == $valor)
				$tmp = " checked ";
			$array [$i] = $tmp;
		}
		return $array;
	}
			
	/**
	 * Metodo que regresa la informacion pintada en el navegador
	 *
	 * @return string variable de instancia $this->buffer
	 */
	function obtenBuffer() {
		return $this->buffer;
	}
}