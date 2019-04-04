<?php
class RegistraProyectosAdmin extends Comunes {
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
		$this->noPasoFormato = $this->opc + 1;
		$this->diasLimite=$this->fechaLimiteCaptura();
		$this->arrayNotificaciones = $this->arrayEstatusVisibles = array();
		$this->disabled="";
		$this->arrayDatosMetas=array();
		$this->visible = false;
		if($this->diasLimite > 0){
			$this->disabled=" disabled ";
			$this->visible = true;
		}
		settype($this->opc,"integer");
		$this->opc = (int) $this->opc;
		switch ($this->opc) {
			case 0 :
				//$this->listadoProyectos();
				$this->muestraFormularioProyecto();
				break;
			case 1 :
				$this->muestraFormularioProyecto();
				break;
			case 3 :
				$this->listadoActividades();
				break;
			case 4:
				$this->muestraFormularioActividades();
				break;
			case 5:
				$this->recuperaDatos();
				$this->muestraFormularioProyecto();
				break;
			case 6:
				$this->recuperaDatosActividad();
				$this->recuperaDatosMetasId();				
				$this->muestraFormularioActividades();
				break;
			case 7:
				$this->recuperaDatos();
				$this->muestraFormularioProyecto();
				break;
			case 8:
				$this->listadoProyectos();
				break;
			case 9:
				$this->recuperaDatosMetas();
				$this->muestraFormularioMetas();
				break;
			default :
				$this->listadoProyectos ();
				break;
		}
	}
	
	function listadoActividades(){
		if(trim($this->data['folio']) != ""){
			$tmp=explode('-',$this->data['folio']);
			if( ($tmp[0]+0) > 0){
				$filtroNm     = " AND id='".$tmp[0]."' ";
				$nmProyecto   = $this->regresaNombreProyecto($filtroNm);
				$no_registros = $this->consultaNoActividades();
				if($no_registros){
					$this->pages = new Paginador();
					$this->pages->items_total = $no_registros;
					$this->pages->mid_range = 25;
					$this->pages->paginate();
					$resultados   = $this->consultaActividades($this->pages->limit);
				}
				$urlRegreso=$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com'];
				$this->buffer="Actividades:  ".$tmp[0];
			}
			$url=$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=4&folio=".$this->data['folio'];
			$this->buffer=$titulo."
				<div class='panel panel-danger spancing'>
					<div class='panel-heading'>".$nmProyecto."
					</div>
	  				<div class='panel-body'>".$this->divFiltrosProyectos(2,$tmp[0],$url,$urlRegreso);
			if(count($resultados) > 0){
				$this->buffer.="
					<table align='center' border='0' class='table table-condensed'>
					<tr>
						<td class='tdcenter fondotable' width='23%'>".ACTIVIDAD."</td>
						<td class='tdcenter fondotable' width='20%'>".UNIDADMEDIDA."</td>
						<td class='tdcenter fondotable' width=' 8%'>".PONDERACION."</td>
						<td class='tdcenter fondotable' width='14%'>".TIPOACT."</td>
						<td class='tdcenter fondotable' width='5%'>".T1."</td>								
						<td class='tdcenter fondotable' width='5%'>".T2."</td>
						<td class='tdcenter fondotable' width='5%'>".T3."</td>
						<td class='tdcenter fondotable' width='5%'>".T4."</td>
						<td class='tdcenter fondotable' width='5%'>".T5."</td>								
						<td class='tdcenter fondotable' width='5%'>".EDITAR."</td>
						<td clasS='tdcenter fondotable' width='5%'>".ELIMINARPROYECTO."</td>
					</tr>";
				$this->bufferExcel="
					<table align='center' border='0' class='table table-condensed'>
					<tr>
						<td class='tdcenter fondotable' width='25%'>".ACTIVIDAD."</td>
						<td class='tdcenter fondotable' width='20%'>".UNIDADMEDIDA."</td>
						<td class='tdcenter fondotable' width=' 8%'>".PONDERACION."</td>
						<td class='tdcenter fondotable' width='12%'>".TIPOACT."</td>
						<td class='tdcenter fondotable' width='4%'>".T1."</td>								
						<td class='tdcenter fondotable' width='4%'>".T2."</td>
						<td class='tdcenter fondotable' width='4%'>".T3."</td>
						<td class='tdcenter fondotable' width='4%'>".T4."</td>
						<td class='tdcenter fondotable' width='4%'>".T5."</td>								
					</tr>";
				$contador=1;
				foreach($resultados as $id => $resul){
					$rand = rand(1,99999999999999);
					$class="";
					if($contador % 2 == 0)
						$class="active";
					$varTemporal = $resul['id']."-".$this->data['folio'];
					$this->buffer.="
					<tr class=' $class alturaComponentesA'>
							<td class='tdleft'>".$resul['actividad']."</td>
							<td class='tdleft'>".$resul['medida']."</td>
							<td class='tdcenter'>".$resul['ponderacion']."</td>
							<td class='tdleft'>".$resul['tipoAct']."</td>
							<td class='tdcenter'>".$resul['trimestre1']."</td>
							<td class='tdcenter'>".$resul['trimestre2']."</td>
							<td class='tdcenter'>".$resul['trimestre3']."</td>
							<td class='tdcenter'>".$resul['trimestre4']."</td>
							<td class='tdcenter'>".$resul['total']."</td>
							<td class='tdcenter'>
								<a class='negro' href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=6&folio=".$varTemporal."'
									data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPEDITARACTIVIDAD."'>
										<span class='glyphicon glyphicon-pencil'></span>&nbsp;
								</a>
							</td>						
							<td class='tdcenter'>
								<a class='negro deleteActividadesProyecto' href='#' onclick='return false;' id='".$varTemporal."'
									data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPACTIVIDADELIMINAR."'>
									<span class='glyphicon glyphicon-trash'></span>&nbsp;									
							</td>
						</tr>";
					$this->bufferExcel.="<tr'>
							<td class='tdleft'>".$resul['actividad']."</td>
							<td class='tdleft'>".$resul['medida']."</td>
							<td class='tdcenter'>".$resul['ponderacion']."</td>
							<td class='tdleft'>".$resul['tipoAct']."</td>
							<td class='tdleft'>".$resul['trimestre1']."</td>
							<td class='tdleft'>".$resul['trimestre2']."</td>
							<td class='tdleft'>".$resul['trimestre3']."</td>
							<td class='tdleft'>".$resul['trimestre4']."</td>
							<td class='tdleft'>".$resul['total']."</td></tr>";
					$contador++;
				}
				$this->bufferExcel.="</table>";
				$this->buffer.="<tr>
								<td colspan='9' class='tdcenter'>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page()."</td>
								<td colspan='2' class='tdcenter'>".$this->Genera_Archivo($this->bufferExcel)."</td>
								</tr></table>";
				
		}
		else{
			$this->buffer.="<table class='table table-condensed'><tr><td class='tdcenter'>".SINREGISTROSACTIVIDAD."</td></tr></table>";
		}

		$this->buffer.="</div></div>";
						
		}
	}
	
	function obtenFiltrosActividades($folio){
		$buf="<form action='aplicacion.php' method='post'>
			<input type='hidden' value='3' id='opc' name='opc'>
			<input type='hidden' name='folio' id='folio' value='".$folio."'>
			<table class='tableSinbordes' align='center'>
			<tr>
				<td class='tdleft' width='34%'>".$this->regresaMedidas(1)."&nbsp;
				<img src='".$this->path."imagenes/iconos/help.png' id='a-12' class='help' alt='".AYUDA."' title='".AYUDA."'>
				</td>
				<td class='tdleft' width='34%'>".$this->regresaTipoActividad(1)."&nbsp;
				<img src='".$this->path."imagenes/iconos/help.png' id='a-14' class='help' alt='".AYUDA."' title='".AYUDA."'>
				</td>
				<td class='tdleft' width='32%'>".$this->generaPonderacion()."&nbsp;
						<img src='".$this->path."imagenes/iconos/help.png' id='a-13' class='help' alt='".AYUDA."' title='".AYUDA."'>
				</td>
				
			</tr>
			<tr>
				<td colspan='2' class='tdleft'><input type='text' class='form-control validatextonumero' placeholder='".BUSCAXACTIVIDAD."' name='busqNombreA' id='busqNombreA' maxlength='250' value='".$this->arrayDatos ['actividad']."' style='width:430px;' >&nbsp;
						<img src='".$this->path."imagenes/iconos/help.png' id='a-15' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
				<td class='tdleft'>
					<button type='submit' name='btnfiltros' id='btnfiltros' class='btn btn-primary' style='width:140px;'>
					<span class='glyphicon glyphicon-search'></span>&nbsp;".CONSULTAR."</button>
					<button type='reset'  name='btnLimpiar' id='btnLimpiar'  class='btn btn-primary' style='width:140px;'>
					<span class='glyphicon glyphicon-refresh'></span>&nbsp;".LIMPIAR."</button>
				</td>
			</tr>
		</table></form>";
		return $buf;
	}
	
	function obtenFiltros(){
	$tmp="";
	$cols = " colspan='2' ";
	$width = "width='28%'";
	$width2 ="width='44%'";
	if($this->session['rol'] == 4){
		$width = "width='28%'";
		$width2 ="width='16%'";		
		$cols="";
		$tmp="<td class='tdleft' ".$width.">".$this->regresaNombreEstatus()."&nbsp;
			  <img src='".$this->path."imagenes/iconos/help.png' id='a-18' class='help' alt='".AYUDA."' title='".AYUDA."'></td>";
	}
	$buf="<form action='aplicacion.php' method='post'>
			<input type='hidden' value='0' id='opc' name='opc'>
			<table class='tableSinbordes' align='center' width='100%'>
			<tr>
				<td class='tdleft' ".$width.">".$this->regresaNombreArea(1)."&nbsp;
					<img src='".$this->path."imagenes/iconos/help.png' id='a-16' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
				<td class='tdleft' ".$width.">".$this->regresaNombrePrograma(1)."&nbsp;
						<img src='".$this->path."imagenes/iconos/help.png' id='a-17' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
				<td class='tdleft' ".$width.">".$this->generaPonderacion()."&nbsp;
						<img src='".$this->path."imagenes/iconos/help.png' id='a-21' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
				<td class='tdleft' ".$width2.">".$this->generaAnos()."&nbsp;
						<img src='".$this->path."imagenes/iconos/help.png' id='a-19' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
			</tr>			
			<tr>
				<td colspan='2' class='tdleft'><input type='text' class='form-control validatextonumero' placeholder='".BUSCAXPROYECTO."' name='busqNombre' id='busqNombre' maxlength='250' value='".$this->arrayDatos ['proyecto']."' style='width:410px;'>&nbsp;
				<img src='".$this->path."imagenes/iconos/help.png' id='a-20' class='help' alt='".AYUDA."' title='".AYUDA."'></td>						
				".$tmp."
				<td class='tdcenter' ".$width2." ".$cols.">
				<button type='submit' name='btnfiltros' id='btnfiltros' class='btn btn-primary' style='width:140px;'><span class='glyphicon glyphicon-search'></span>&nbsp;&nbsp;".CONSULTAR."</button>
				<button type='reset'  name='btnLimpiar' id='btnLimpiar' class='btn btn-primary' style='width:140px;'><span class='glyphicon glyphicon-refresh'></span>&nbsp;&nbsp;".LIMPIAR."</button></td>								
			</tr>
			
		</table></form>";
	return $buf;	
	}
	
	function divFiltrosProyectos($opcion,$valor,$url,$urlRegreso){
		$mens = "";
		if($opcion == 1)
			$mens.=$this->obtenFiltros();
		else
			$mens.=$this->obtenFiltrosActividades($valor);
		
		$tit=FILTROSBUSQUEDA;
		$botones="";
		if($opcion == 1){
			$tit=FILTROSBUSQUEDA;
			$urlAlta=
			/*$botones="
					  <button type='button' class='btn btn-warning  ninguno' id='cambiaFase2'>".ENVIARCOORDINADOR."</button>&nbsp;&nbsp;
					  <button type='button' class='btn btn-warning  todos'>".TODOS."</button>&nbsp;&nbsp;
					  <button type='button' class='btn btn-warning  ningunos'>".NINGUNO."</button>";*/
			
			$botones="<button type='button' class='btn btn-primary btn-lg ninguno' id='cambiaFase2'>
						<span class='glyphicon glyphicon-envelope' aria-hidden='true'></span>&nbsp;&nbsp;".ENVIARCOORDINADOR."</button>
					  <button type='button' class='btn btn-primary btn-lg todos'><span class='glyphicon glyphicon-check' aria-hidden='true'></span>&nbsp;&nbsp;".TODOS."</button>
					  <button type='button' class='btn btn-primary btn-lg ningunos'><span class='glyphicon glyphicon-unchecked' aria-hidden='true'></span>&nbsp;&nbsp;".NINGUNO."</button>
					  <button type='button' class='btn btn-success btn-lg ningunos' 
					  		data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPPROYECTO."'
					  		  onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1'\">
					  		<span class='glyphicon glyphicon-play-circle'></span>&nbsp;&nbsp;".AGREGAPROYECTO."
					  </button>";
		}
		if($opcion == 2){
			$botones="
					  <button type='button' class='btn btn-primary ' onclick=\"location='".$urlRegreso."'\"><span class='glyphicon glyphicon-arrow-left'></span>&nbsp;&nbsp;".REGRESAPROYECTOS."</button>
					  <button type='button' class='btn btn-success btn-lg ningunos' data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPACTIVIDAD."' 
					  		onclick=\"location='".$url."'\"><span class='glyphicon glyphicon-play-circle'></span>&nbsp;&nbsp;".AGREGAACTIVIDAD."</button>";
		}
		$buf="<div class=\"tdcenter\"><center>
				<button type='button' class='btn btn-primary' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
						<span class='glyphicon glyphicon-download'></span>&nbsp;&nbsp;".$tit."</button>&nbsp;".$botones."
			</center><br><br><br></div>
		<div class='collapse' id='collapseExample'>
  		<div class='well'>".$mens."</div>
		</div>";
		
		return $buf;
	}
	/**
	 * Metodo que se encarga de generar el listado de proyectos
	 */
	function listadoProyectos(){
		$class="";
		$this->arrayEstatusVisibles = array();
		if($this->session['rol'] == 1)
			$this->arrayEstatusVisibles = array(1,2,3,4,5,6,7,8,9,10);
		if($this->session['rol'] == 2)
			$this->arrayEstatusVisibles = array(2,3,4);
		if($this->session['rol'] == 3)
			$this->arrayEstatusVisibles = array(5,6,7);
		if($this->session['rol'] == 2)
			$this->arrayEstatusVisibles = array(1,2,3,4,5,6,7,8,9,10);
		
		$no_registros = $this->consultaNoProyectosAdmin();
		$this->arrayNotificaciones = $this->notificaciones();	

		if($no_registros){
			$this->pages = new Paginador();
			$this->pages->items_total = $no_registros;
			$this->pages->mid_range = 25;
			$this->pages->paginate();
			$resultados   = $this->consultaProyectosAdmin();
		}		
		$this->buffer="
				<div class='panel panel-danger spancing'>
					<div class='panel-heading'><span class='titulosBlanco'>".LISTADODEPROYECTOS."</span></div>
	  				<div class='panel-body'><center><span id='res'></span></center><br>".$this->divFiltrosProyectos(1,0,"","")."";
			if(count($resultados) > 0){
			$this->buffer.="
					<table align='center' border='0' class='table table-condensed'>
					<tr>
						<td class='tdcenter fondotable' width='28%'>".PROYECTOS."</td>
						<td class='tdcenter fondotable' width='8%'>".NOACCIONES."</td>		
						<td class='tdcenter fondotable' width='12%'>".FECHAALTA."</td>						
						<td class='tdcenter fondotable' width='8%'>".EDITAR."</td>								
						<td clasS='tdcenter fondotable' width='8%'>".ELIMINARPROYECTO."</td>
						<td class='tdcenter fondotable' width='16%'>".ROL."</td>
						<td class='tdcenter fondotable' width='10%'>".ESTATUSVALIDACION."</td>
						<td clasS='tdcenter fondotable' width='10%'>".VALIDAR."</td>
					</tr>";
				$this->bufferExcel = "
					<table align='center' border='0' class='table table-condensed'>
					<tr>
						<td class='tdcenter fondotable' width='28%'>".PROYECTOS."</td>
						<td class='tdcenter fondotable' width=' 10%'>".ESTATUSVALIDACION."</td>
						<td class='tdcenter fondotable' width='16%'>".ROL."</td>		
						<td class='tdcenter fondotable' width='12%'>".FECHAALTA."</td>
						<td class='tdcenter fondotable' width='8%'>".NOACCIONES."</td>
					</tr>";
				$contador=1;
				$varTemporal = $respuestaC = "";
				foreach($resultados as $id => $resul){
					$rand = rand(1,99999999999999);
					$class="";
					if($contador % 2 == 0)
						$class="active";
					$varTemporal = $resul['id']."-".$resul['estatus_entrega'];
					$idEstatus=$resul['estatus_entrega'];
					$editable =$this->arrayNotificaciones[$idEstatus]['edita'];
					$this->buffer.="
						<tr class=' $class alturaComponentesA'>
							<td class='tdleft'>".$resul['proyecto']."</td>	
							<td class='tdcenter'>";
					if($editable == 1){
						$this->buffer.="
								<a class='negro' href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=3&folio=".$varTemporal."'
										data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPACTIVIDADALTA."'>
										&nbsp;".$resul['noAcciones']."
								</a>";
					}
					else{
						$this->buffer.=$resul['noAcciones'];
					}
					$this->buffer.="	</td>
							<td class='tdcenter'>".substr($resul['fecha_alta'],0,10)."</td>
							<td class='tdcenter'>";
					if($editable == 1){
						$this->buffer.="
								<a class='negro' href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=5&folio=".$varTemporal."' 
										data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPEDITARPROYECTO."'>
										<span class='glyphicon glyphicon-pencil'>&nbsp;</span>
								</a>";
					}
					else{
						$this->buffer.="<span class='glyphicon glyphicon-pencil'>&nbsp;</span>";
					}
					$this->buffer.="</td>
							<td class='tdcenter'>";
					if($editable == 1){
						$this->buffer.="
								<a class='negro deleteProyecto' href='#' onclick='return false;' id='".$varTemporal."'
								data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPROYECTOELIMINAR."'>
								<span class='glyphicon glyphicon-trash'></span>&nbsp;
								</a>";
					}
					else{
						$this->buffer.="<span class='glyphicon glyphicon-trash'></span>&nbsp;";
					}
					$this->buffer.="</td>
							<td class='tdleft'>".$resul['nomRol']."</td>
							<td class='tdleft' style='background-color:".$this->arrayNotificaciones[$idEstatus]['color'].";color:#000000;'>";
							if($idEstatus  > 2){
								$this->buffer.="<a class='negro visualizaComentarios' href='#' onclick='return false;' id='".$varTemporal."'
								data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPMUESTRACOMENTARIOS."'>
								&nbsp;".$this->arrayNotificaciones[$idEstatus]['nom']."
								</a>";
							}
							else{
								$this->buffer.=$this->arrayNotificaciones[$idEstatus]['nom'];
							}									
							$this->buffer.="</td>
							<td class='tdcenter'>&nbsp;";
							if($this->arrayNotificaciones[$idEstatus]['act'] == 1)
								$this->buffer.="<input type='checkbox' name='enviaId' id='".$varTemporal."' class='enviaId' value='".$resul['id']."'>";
							else {
								if($this->session['rol']>1)
									$this->buffer.=$this->pintaComentario($idEstatus,$resul['id']);
							}
					$this->buffer.="</td>
						</tr>";
					
					$this->bufferExcel .="<tr>
							<td class='tdleft'>".$resul['proyecto']."</td>
							<td class='tdcenter'>".$arrayEstatus[$resul['ponderacion']]."</td>
							<td class='tdcenter'>".$resul['nomRol']."</td>
							<td class='tdcenter'>".substr($resul['fecha_alta'],0,10)."</td>
							<td class='tdcenter'>".$resul['noAcciones']."</td></tr>";
					$contador++;
				}	
				$this->bufferExcel .="</table>";
				$this->buffer.="<tr>
						<td colspan='6' class='tdcenter'>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page()."</td>
						<td colspan='2' class='tdcenter'>".$this->Genera_Archivo($this->bufferExcel)."</td>
						</tr></table>";		
		}
		else{
			$this->buffer.="<table class='table table-condensed'><tr><td class='tdcenter'>".SINREGISTROS."</td></tr></table>";
		}
		$this->buffer.="</div></div>";
	}
	
	
	/**
	 * Formulario de actividades
	 */
	function muestraFormularioActividades(){
		$this->disabled="";
		$adjuntos = $name = $titulo = $urlfolio = "";
		$folio = $random = 0;
		if($this->data['folio']  != ""){
			$tmp=explode('-',$this->data['folio']);
			if($this->opc == 4){
				$name="guardaActividad";
				$arrayProyecto = $this->regresaDatosProyecto($tmp[0]);
				$folio = $tmp[0];
				$urlfolio=$this->data['folio'];
				$titulo=NUEVAACTIVIDAD;
				$random=rand(1,10000000);
			}
			else{
				$name="actualizaActividad";
				$arrayProyecto = $this->regresaDatosProyecto($tmp[1]);
				$folio = $tmp[1];
				$urlfolio=$tmp[1]."-".$tmp[2];
				$titulo=ACTUALIZAACTIVIDAD;
				$adjuntos=$this->regresaAdjuntosActividad($tmp[1],$tmp[0]);
				$random=$tmp[0];
			}
			$arrayUnidadOperativas=$this->catalogoUnidadesOperativas($this->db);
			$arrayPonderacion[1]=" checked ";
			if($this->data['opc'] >4){
				$arrayPonderacion = $this->regresaPonderacion();
			}
			
			$this->buffer="
					<input type='hidden' name='valueId' id='valueId' value='".($this->arrayDatos ['id'] + 0)."'>
					<input type='hidden' name='folio' id='folio' value='".$urlfolio."'>
					
					<input type='hidden' name='random' id='random' value='".$random."'>
					<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>".$titulo."</div>
	  				<div class='panel-body'>
						<table align='center' border='0' class='table table-condensed'>
						<tr class='active alturaComponentesA'>
							<td class='tdleft' colspan='2' width='25%'>".PROYECTO."</td>
							<td class='tdleft' colspan='3'>".$arrayProyecto['proyecto']."</td>
						</tr>
						<tr class='alturaComponentesA'>
							<td class='tdleft' colspan='2' >".UNIDADOPERATIVA."</td>	
							<td class='tdleft' colspan='3'>".$arrayUnidadOperativas[$arrayProyecto['unidadOperativaId']]."</td>
						</tr>
						<tr class='active'>
							<td class='tdleft' width='20%'>".AGREGUE."</td>
							<td class='tdcenter' width='5%'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-11' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft' width='75%' colspan='3'>
								<input type='text' tabindex='1' required='yes'  ".$this->disabled." class='bootstrap-select validatextonumero espTextArea' placeholder='".ACTIVIDAD."'  value='".$this->arrayDatos['actividad']."' name='actividad' id='actividad'> 
							</td>
						</tr>
						<tr>
							<td class='tdleft'>".UNIDADMEDIDA."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-12' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>		
							<td class='tdleft' colspan='3'>".$this->regresaMedidas(2)."</td>
						</tr>
						<tr class='active alturaComponentesA'>
							<td class='tdleft'>".PONDERACION."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-13' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft' colspan='2'>
								<input type='radio' class='pondera' name='ponderacion' id='Aponderacion5' ".$arrayPonderacion[5]."  ".$this->disabled." value='5'>5&nbsp;&nbsp;
	                    		<input type='radio' class='pondera' name='ponderacion' id='Aponderacion4' ".$arrayPonderacion[4]."  ".$this->disabled." value='4'>4&nbsp;&nbsp;
	                    		<input type='radio' class='pondera' name='ponderacion' id='Aponderacion3' ".$arrayPonderacion[3]."  ".$this->disabled." value='3'>3&nbsp;&nbsp;
	                    		<input type='radio' class='pondera' name='ponderacion' id='Aponderacion2' ".$arrayPonderacion[2]."  ".$this->disabled." value='2'>2&nbsp;&nbsp;
	                    		<input type='radio' tabindex='3' class='pondera' name='ponderacion' id='Aponderacion1' ".$arrayPonderacion[1]."  ".$this->disabled." value='1'>1
							</td>
	                    	<td class='tdleft' width='55%'><span id='comentariopodenracion'></span></td>
						</tr>
						<tr>								
							<td class='tdleft'>".TIPOACT."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-14' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft' colspan='2'>".$this->regresaTipoActividad(2)."</td>
							<td class='tdleft' width='55%'><span id='comentarioTipoActividad'></span></td>		
						</tr>
					</table>
					<br>
					<table width='100%' class='table'>
					<tr>
						<td class='tdcenter fondotable' width='10%'>".TRIMESTRE1C."</td>
						<td class='tdcenter fondotable' width='10%'>".TRIMESTRE2C."</td>
						<td class='tdcenter fondotable' width='10%'>".TRIMESTRE3C."</td>
						<td class='tdcenter fondotable' width='10%'>".TRIMESTRE4C."</td>
						<td class='tdcenter fondotable' width='10%'>".TOTAL."</td>
					</tr>
					<tr>
						<td class='tdcenter fondotable' width='5%'>".P1."</td>
						<!--<td class='tdcenter fondotable' width='5%'>".R."</td>-->
						<td class='tdcenter fondotable' width='5%'>".P1."</td>
						<!--<td class='tdcenter fondotable' width='5%'>".R."</td>-->
						<td class='tdcenter fondotable' width='5%'>".P1."</td>
						<!--<td class='tdcenter fondotable' width='5%'>".R."</td>-->
						<td class='tdcenter fondotable' width='5%'>".P1."</td>
						<!--<td class='tdcenter fondotable' width='5%'>".R."</td>-->
						<td class='tdcenter fondotable' width='5%'>".P1."</td>
						<!--<td class='tdcenter fondotable' width='5%'>".R."</td>-->
					</tr>";
				$contadorTab1=5;
				$contadorTab2=6;
				$contadorTab3=7;
				$contadorTab4=8;
				$contadorRen = $total = $totales = $rtotal = $rtotales = 0;
				//foreach($resultados as $id => $resul){
				$rand = rand(1,99999999999999);
				$class="";
				$varTemporal = $resul['id']."-".$rand;
				$idact= $resul['id'];
				$totales = $totales + $this->arrayDatos[$idact][5] + 0;
				if($this->arrayDatos['tipo_actividad_id'] != 2){
					$this->buffer.="
						<tr class=' $class alturaComponentesA' id='regmetas'>
							<td class='tdcenter'>
								<input type='text' class='form-control validanumsMR' ".$this->disabled." tabindex='".$contadorTab1."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-1' maxlength='10' value='".($this->arrayDatosMetas[1] + 0)."' style='width:35px;'>		
							</td>
							<td class='tdcenter'>
								<input type='text' class='form-control validanumsMR'  ".$this->disabled." tabindex='".$contadorTab2."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-2' maxlength='10' value='".($this->arrayDatosMetas[2] + 0)."' style='width:35px;'>		
							</td>
							<td class='tdcenter'>
								<input type='text' class='form-control validanumsMR'  ".$this->disabled." tabindex='".$contadorTab3."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-3' maxlength='10' value='".($this->arrayDatosMetas[3] + 0)."' style='width:35px;'>		
							</td>
							<td class='tdcenter'>
								<input type='text' class='form-control validanumsMR'  ".$this->disabled." tabindex='".$contadorTab4."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-4' maxlength='10' value='".($this->arrayDatosMetas[4] + 0)."' style='width:35px;'>		
							</td>
							<td class='tdcenter'><span id='total".$contadorRen."' class='totales'>".number_format(($this->arrayDatosMetas[5] + 0),0,',','.')."</span></td>
						</tr>";
				}
					$contadorTab1 = $contadorTab1 + 4;
					$contadorTab2 = $contadorTab2 + 4;
					$contadorTab3 = $contadorTab3 + 4;
					$contadorTab4 = $contadorTab4 + 4;
					$contadorRen++;
					$contadorTab4++;
					$this->buffer.="</table>				
				</div>
					<div class=\"central\">";
			if(!$this->visible)
				$this->buffer.="<button type='button' tabIndex='9' class='btn btn-success btn-sm' 
						data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPGUARDARACTIVIDAD."' id='".$name."' name='".$name."'><span class='glyphicon glyphicon-floppy-saved'></span>&nbsp;".AGREGAACTIVIDAD."</butto>
						&nbsp;&nbsp;";
            $this->buffer.="<button tabIndex='10' type='button' class='btn btn-primary btn-sm'
                 		onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=3&folio=".$urlfolio."'\">".REGRESA."</button>
					</div>".$this->procesando(4)."<br></div>";
		}else{
			header("Location: ".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1");
		}
	}
	
	/** 
	 * Metodo que se encarga de recuperar los datos de la actividad de la bd
	 */
	function recuperaDatosActividad(){
		if($this->data['folio']!=""){
			$tmp=explode('-',$this->data['folio']);
			if( ($tmp[0] + 0)>0)
				$this->arrayDatos = $this->regresaDatosActividad($tmp[0]);
		}
	}
	
	/** 
	 * Metodo que se encarga de recuperar los datos del proyecto de la bd
	 */
	function recuperaDatos(){
		if($this->data['folio']!=""){
			$tmp=explode('-',$this->data['folio']);
			if( ($tmp[0] + 0)>0)
				$this->arrayDatos = $this->regresaDatosProyecto($tmp[0]);	
		}
		
	}
	
	function regresaPonderacion(){
		$valor=$this->arrayDatos['ponderacion'] + 0;
		$array=array();
		for($i=1; $i<=5;$i++){
			$tmp="";
			if($i == $valor)
				$tmp=" checked ";
			$array[$i]=$tmp;
		}
		return $array;
	}

	/**
	 * Metodo para recuperar las metas
	 */
	function recuperaDatosMetasId(){
		$folio=0;
		$tmp=array();
		if(trim($this->data['folio'])!= ""){
			$tmp=explode('-',$this->data['folio']);
			$folio= $tmp[0] + 0;
			if($folio > 0){
				$this->arrayDatosMetas=$this->regresaMetasActividad($folio);
			}
		}
	}
	
	/**
	 * Metodo para recuperar las metas
	 */
	function recuperaDatosMetas(){
		$folio=0;
		$tmp=array();
		if(trim($this->data['folio'])!= ""){
			$tmp=explode('-',$this->data['folio']);
			$folio= $tmp[0] + 0;
			if($folio > 0){
				$this->arrayDatos=$this->regresaMetas($folio);
			}
		}
	}
	
	/**
	 * Metodo que se encarga  de registrar las metas
	 */
	function muestraFormularioMetas(){
		$name = $titulo = $urlfolio = "";
		$folio = $random = 0;
		if($this->data['folio']  != ""){
			$tmp=explode('-',$this->data['folio']);
			if($this->opc == 9){
				$name="guardaMeta";
				$arrayProyecto = $this->regresaDatosProyecto($tmp[0]);
				$folio = $tmp[0];
				$urlfolio=$this->data['folio'];
				$titulo=CAPTURADEMETAS;
				$random=rand(1,10000000);
			}
			else{
				$name="actualizaMeta";
				$arrayProyecto = $this->regresaDatosProyecto($tmp[1]);
				$folio = $tmp[1];
				$urlfolio=$tmp[1]."-".$tmp[2];
				$titulo=MODIFICARMETAS;
				$random=$tmp[0];
			}
			$resultados = $this->consultaActividades($this->pages->limit);
			$arrayUnidadOperativas=$this->catalogoUnidadesOperativas($this->db);	
			$this->buffer="
					<input type='hidden' name='noAtributos' id='noAtributos' value='".( count($resultados) + 0)."'>
					<input type='hidden' name='valueId' id='valueId' value='".($this->arrayDatos ['id'] + 0)."'>
					<input type='hidden' name='folio' id='folio' value='".$folio."'>
			
					<input type='hidden' name='random' id='random' value='".$random."'>
					<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>".$titulo."</div>
	  				<div class='panel-body'>
						<table align='center' border='0' class='table table-condensed'>
						<tr class='active alturaComponentesA'>
							<td class='tdleft' colspan='2' width='25%'>".PROYECTO."</td>
							<td class='tdleft' colspan='2'>".$arrayProyecto['proyecto']."</td>
						</tr>
						<tr class='alturaComponentesA'>
							<td class='tdleft' colspan='2' >".UNIDADOPERATIVA."</td>
							<td class='tdleft' colspan='2'>".$arrayUnidadOperativas[$arrayProyecto['unidadOperativaId']]."</td>
						</tr></table>
					<table width='100%' class='table'>
					<tr>
						<td class='tdcenter fondotable' rowspan='2' width='30%'>".ACTIVIDAD."</td>
						<td colspan='2' class='tdcenter fondotable' width='10%'>".TRIMESTRE1C."</td>
						<td colspan='2' class='tdcenter fondotable' width='10%'>".TRIMESTRE2C."</td>
						<td colspan='2' class='tdcenter fondotable' width='10%'>".TRIMESTRE3C."</td>
						<td colspan='2' class='tdcenter fondotable' width='10%'>".TRIMESTRE4C."</td>
						<td colspan='2' class='tdcenter fondotable' width='10%'>".TOTAL."</td>
						<td class='tdcenter fondotable' rowspan='2' width='14%'>".MEDIDA."</td>
						<td class='tdcenter fondotable' rowspan='2' width=' 8%'>".ucfirst(substr(PONDERACION,0,4))."</td>
						<td class='tdcenter fondotable' rowspan='2' width=' 8%'>".ucfirst(substr(TIPOACT,8,3))."</td>
					</tr>
					<tr>
						<td class='tdcenter fondotable' width='5%'>".P."</td>
						<td class='tdcenter fondotable' width='5%'>".R."</td>
						<td class='tdcenter fondotable' width='5%'>".P."</td>
						<td class='tdcenter fondotable' width='5%'>".R."</td>
						<td class='tdcenter fondotable' width='5%'>".P."</td>
						<td class='tdcenter fondotable' width='5%'>".R."</td>
						<td class='tdcenter fondotable' width='5%'>".P."</td>
						<td class='tdcenter fondotable' width='5%'>".R."</td>
						<td class='tdcenter fondotable' width='5%'>".P."</td>
						<td class='tdcenter fondotable' width='5%'>".R."</td>
					</tr>";
				$contadorTab1=1;
				$contadorTab2=2;
				$contadorTab3=3;
				$contadorTab4=4;
				$contadorRen = $total = $totales = $rtotal = $rtotales = 0;
				foreach($resultados as $id => $resul){
					$rand = rand(1,99999999999999);
					$class="";
					$tmp="";
					if($contador % 2 == 0)
						$class="active";
					$varTemporal = $resul['id']."-".$rand;
					$idact= $resul['id'];
					$totales = $totales + $this->arrayDatos[$idact][5] + 0;
					if($resul['tipo_actividad_id'] == 2){
						$tmp=" disabled ";
					}
					$this->buffer.="
						<tr class=' $class alturaComponentesA'>
						<td class='tdleft' rowspan='2'>".$resul['actividad']."</td>
						<td class='tdcenter'>
						<input type='text' class='form-control validanumsM' ".$tmp." ".$this->disabled." tabindex='".$contadorTab1."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-".$resul['id']."-".$contadorTab1."-1' maxlength='10' value='".($this->arrayDatos[$idact][1] + 0)."' style='width:35px;'>		
						</td>
						<td class='tdcenter numMetas form-control'>0</td>
						<td class='tdcenter'>
						<input type='text' class='form-control validanumsM' ".$tmp." ".$this->disabled." tabindex='".$contadorTab2."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-".$resul['id']."-".$contadorTab2."-2' maxlength='10' value='".($this->arrayDatos[$idact][2] + 0)."' style='width:35px;'>		
						</td>
						<td class='tdcenter numMetas form-control'>0</td>
						<td class='tdcenter'>
						<input type='text' class='form-control validanumsM' ".$tmp." ".$this->disabled." tabindex='".$contadorTab3."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-".$resul['id']."-".$contadorTab3."-3' maxlength='10' value='".($this->arrayDatos[$idact][3] + 0)."' style='width:35px;'>		
						</td>
						<td class='tdcenter numMetas form-control'>0</td>
						<td class='tdcenter'>
						<input type='text' class='form-control validanumsM' ".$tmp." ".$this->disabled." tabindex='".$contadorTab4."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-".$resul['id']."-".$contadorTab4."-4' maxlength='10' value='".($this->arrayDatos[$idact][4] + 0)."' style='width:35px;'>		
						</td>
						<td class='tdcenter numMetas form-control'>0</td>
						<td class='tdcenter' rowspan='2'><span id='total".$contadorRen."' class='totales'>".number_format(($this->arrayDatos[$idact][5] + 0),0,',','.')."</span></td>
						<td class='tdcenter' rowspan='2'><span id='rtotal".$contadorRen."' class='totales'>".number_format($rtotal,0,',','.')."</span></td>
						<td class='tdcenter' rowspan='2'>".$resul['medida']."</td>
						<td class='tdcenter' rowspan='2'>".$resul['ponderacion']."</td>
						<td class='tdcenter' rowspan='2'>".$resul['tipo_actividad_id']."</td>
					</tr>
					<tr><td colspan='8' class='tdleft avances'>Avance: </td></tr>";
					$contadorTab1 = $contadorTab1 + 4;
					$contadorTab2 = $contadorTab2 + 4;
					$contadorTab3 = $contadorTab3 + 4;
					$contadorTab4 = $contadorTab4 + 4;
					$contadorRen++;
				}
				$contadorTab4++;
				$this->buffer.="<tr><td colspan='8'></td>
						<td class='tdleft'>Total:</td><td class='tdcenter'><span id='totales' class='totales'>".($totales  + 0)."</span></td>
						<td class='tdcenter'><span id='rtotales' class='totales'>".($rtotales  + 0)."</span></td>
						<td colspan='3'>&nbsp;</td></tr></table>				
					</div>
					<div class=\"central\"><br>";
				if(!$this->visible)
						$this->buffer.="<button type='button' tabindex='".$contadorTab4."'   class='btn btn-success btn-sm' id='".$name."' name='".$name."'><span class='glyphicon glyphicon-floppy-saved'></span>&nbsp;".AGREGAMETA."</button>
						&nbsp;&nbsp;";
                $this->buffer.="<button type='button' class='btn btn-primary btn-sm'
                 onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=0'\">".REGRESA."</button>
               	</div>".$this->procesando(4)."<br></div>";
		}else{
			header("Location: ".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1");
		}		
	}
	
	/**
	 * Muestra formulario de alta de proyecto
	 */
	function muestraFormularioProyecto() {
		$arrayPonderacion= array();
		$tit=EDITAACCION;
		$name="updateProyecto";
		$boton=UPDATEPROYECTO;
		if($this->data['opc'] ==1){
			$tit=ALTADEACCIONES;
			$name="saveProyectoAdmin";
			$boton=GUARDARPROYECTO;
		}
		if(count($this->arrayDatos)>0){
			if(trim($this->arrayDatos ['especifique']) == "")
				$this->arrayDatos ['especifique']='NOAPLICA';
		}
		$arrayPonderacion[1]=" checked ";
		if($this->data['opc'] >1){
			$arrayPonderacion = $this->regresaPonderacion();
		}
		if(trim($this->arrayDatos['presupuesto_otorgado'])!= "")
			$presu = number_format($this->arrayDatos['presupuesto_otorgado'],2,'.',',');
		if(trim($this->arrayDatos['presupuesto_estimado'])!= "")
		$estim = number_format($this->arrayDatos['presupuesto_estimado'],2,'.',',');
		$random = rand(1,1000000);
		$this->buffer = "
        	<input type='hidden' name='valueId' id='valueId' value='".($this->arrayDatos ['id'] + 0)."'>
        	<input type='hidden' name='idproyecto' id='idproyecto' value='".($this->arrayDatos ['proyecto_id'] + 0)."'>
        	<input type='hidden' name='idano' id='idano' value='".$this->session['anocaptura']."'>
        	<div class='panel panel-danger spancing'>
				<div class='panel-heading'>".$tit."</div>
  				<div class='panel-body' id='panelFormaProyecto'>
          		<table width='90%' align='center' border='0' class='table-striped'>        
				<tr class='altotitulo'>
	                <td class='tdleft bold'>".ANOCORRESPONDIENTE."</td>
                	 <td class='tdcenter' width='8%'>&nbsp;</td>
                	<td class='tdleft alinea'>".$this->session['anocaptura']."</b></td>
				</tr>
                <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".AGREGARAREAYPROGRAMAS."</td>
                <td class='tdcenter' width='8%'>&nbsp;</td>
                <td class='tdleft alinea'><button class='ui-icon-add' data-toggle='modal' data-target='#myModalAreas' id='btn-5'></button></td>
				</tr>
            	<tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".AREAYPROGRAMAS."</td>
                <td class='tdcenter' width='8%'>&nbsp;</td>
                <td class='tdleft alinea'><span id='NmAreas'></span></td>
				</tr>
            	
            	<tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".PROYECTO."</td>
                <td class='tdcenter' width='8%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-1' class='help' alt='".AYUDA."' title='".AYUDA."'>	
                </td>
                <td class='tdleft alinea'><input type='text' ".$this->disabled." class='form-control validatextonumero' placeholder='".PROYECTOS."' id='inputNombre' maxlength='250' value='".$this->arrayDatos ['proyecto']."' style='width:350px;'></td>
				</tr>
            	<tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".PONDERACION."&nbsp;&nbsp;</td>
                <td class='tdcenter' width='5%'><img src='".$this->path."imagenes/iconos/help.png' id='a-2' class='help'  alt='".AYUDA."' title='".AYUDA."'></td>
                <td class='tdleft alinea' colspan='2'>
                    <input type='radio' name='ponderacion' id='ponderacion5' ".$arrayPonderacion[5]."  ".$this->disabled." value='5'>5&nbsp;&nbsp;
                    <input type='radio' name='ponderacion' id='ponderacion4' ".$arrayPonderacion[4]."  ".$this->disabled." value='4'>4&nbsp;&nbsp;
                    <input type='radio' name='ponderacion' id='ponderacion3' ".$arrayPonderacion[3]."  ".$this->disabled." value='3'>3&nbsp;&nbsp;
                    <input type='radio' name='ponderacion' id='ponderacion2' ".$arrayPonderacion[2]."  ".$this->disabled." value='2'>2&nbsp;&nbsp;
                    <input type='radio' name='ponderacion' id='ponderacion1' ".$arrayPonderacion[1]."  ".$this->disabled." value='1'>1
                </td>
            	</tr>
            	<tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".DESCRIPCIONDELPROYECTO."</td>
                <td class='tdcenter' width='5%'><img src='".$this->path."imagenes/iconos/help.png' id='a-3' class='help'  alt='".AYUDA."' title='".AYUDA."'></td>
                <td class='tdleft alinea'>
                    <textarea required='yes' maxlength='2000'  ".$this->disabled." class='bootstrap-select validatextonumero espTextArea summernote' placeholder='".DESCRIPCIONDELPROYECTO."' name='descripcion' id='descripcion'>".$this->arrayDatos ['descripcion']."</textarea>
                </td>
            	</tr>
            	<tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".RESULTADOSESPERADOS."</td>
                <td class='tdcenter' width='5%'><img src='".$this->path."imagenes/iconos/help.png' id='a-4' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
                <td class='tdleft alinea'>
                    <textarea required='yes' maxlength='2000'  ".$this->disabled." class='bootstrap-select validatextonumero espTextArea summernote' placeholder='".RESULTADOSESPERADOS."' name='resultados' id='resultados'>".$this->arrayDatos ['resultados']."</textarea>
                </td>
            	</tr>
            	<tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".PRESUPUESTO."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-7' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>".(date('Y') - 1)."&nbsp;&nbsp;$&nbsp
                	<input type='text' class='form-control-num validanums'  ".$this->disabled." placeholder='".PRESUPUESTONUMBER."'  name='presupuesto_1' id='presupuesto_1' size='12' value='".$presu."'>&nbsp;&nbsp;".OTORGADO."
                </td>
            	</tr>
            	<tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-8' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>".(date('Y') + 0)."&nbsp;&nbsp;$&nbsp;
                	<input type='text' class='form-control-num validanums'  ".$this->disabled." placeholder='".PRESUPUESTONUMBER."'   name='estimado_1' id='estimado_1' value='".$estim."'>&nbsp;&nbsp;".ESTIMADO."
                </td>
            	</tr>
            	<tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".ENCOORDINACION."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-9' class='help' alt='".AYUDA."' title='".AYUDA."'>
               	</td>
               	<td class='tdleft alinea'>".$this->enCoordinacion()."</td>
            	</tr>
            
            	<tr class='altotitulo' id='trespecifique'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".ESPECIFIQUE."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-10' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>
                    <textarea class='bootstrap-select espTextArea2'  ".$this->disabled." placeholder='".ESPECIFIQUE."'  name='especifique' id='especifique'>".$this->arrayDatos ['especifique']."'</textarea>
                </td>
            	</tr>
            	<tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".METODO."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-17' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
				<td class='tdleft alinea'>".$this->metodoParticipacion()."</td>
            	</tr>						
            	<tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".UNIDADOPERATIVA."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-5' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>".$this->generaUnidadesOperativas()."&nbsp;&nbsp;
                	<input type='hidden' value='1' name='idresponsableunidado' id='idresponsableunidado'>
              		<button class='ui-icon-add' data-toggle='modal' data-target='#myModalUOperativa' id='btn-5'></button></td>
            	</tr>
            	<tr>
                <td class='tdcenter bold' colspan='6'><span id='resultado' class='error'></span></td>
            	</tr>    		
           		<tr>
                <td class='tdcenter legend' colspan='6'><br>";
				if(!$this->visible)
                	$this->buffer.="<button type='button' class='btn btn-success' id='".$name."' name='".$name."'
                		data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPGUARDARPROYECTO."'><span class='glyphicon glyphicon-floppy-saved'></span>&nbsp;".$boton."</button>";
		
        			$this->buffer.="<button type='button' class='btn btn-primary btn-sm'
                 	onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=0'\">".REGRESA."</button>              		
                </td>
            	</tr>
        		</table></div>".$this->procesando(3)."</div>";
			$this->buffer.=$this->ModalAreas("myModalAreas",1,$random);
			$this->buffer.=$this->Modal("myModalUOperativa",2);
			
	}
	
	
	/**
	 * Metodo que pinta el modal
	 */
	function ModalAreas($name,$opc,$random){
		$buf='<div class="modal fade" id="'.$name.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width:650px;">
              <div class="modal-content" style="width:650px;">
                <div class="modal-header">
                  <button type="button" class="close" width="30px" data-dismiss="modal">
				  	<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				  </button>
                  <h5 class="modal-title" id="myModalLabel"><span id="spanmyModalLabel">'.SCDF.'</span></h5>
                </div>
                <div class="modal-body ">
                <div id="error"></div>
                  <input type="hidden" id="randomAP" name="randomAP" value="'.$random.'">
                  <div class="form-group has-default subtitulos">
                  		<table  class="table">
                  			<tr>
                  				<td class="tdleft">'.AREA.'</td>
                  				<td class="tdleft">'.$this->regresaNombreAreaAdmin(2).'</td>
                  				<td class="tdleft">&nbsp;&nbsp;</td>
                  			</tr>
							<tr>
                  				<td class="tdleft">'.PROGRAMA.'</td>
                  				<td class="tdleft">'.$this->regresaNombreProgramaAdmin(2).'</td>
                  				<td class="tdcenter" width="40%">
                  					<button type="button" class="btn btn-success btn-sm"  id="pProyectoAdmin" name="pProyectoAdmin">'.GUARDAR.'</button>
                  					&nbsp;&nbsp;
                  					<button type="button" class="btn btn-primary cerrarAccion" id="cerrarAccion'.$opc.'" name="cerrarAccion">'.CERRARVENTANA.'</button>				
                  				</td>
                  			</tr>              						
                  		</table>
                  </div>
                  <div id="resultadoModalAdmin"></div>
                </div>
              </div>
            </div>
          </div>';
		return $buf;
	}
	
	
	function Modal($name,$opc){
		$tit="Agregar proyecto";
		$tittxt="Teclee el nombre del proyecto";
		$tit=AGREGRAUOPERATIVA;
		$tittxt=TECLEEUNIDADOPERATIVA;
		$buf='<div class="modal fade" id="'.$name.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" width="30px" data-dismiss="modal">
				  	<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				  </button>
                  <h5 class="modal-title" id="myModalLabel"><span id="spanmyModalLabel">'.SCDF.'</span></h5>
                </div>
                <div class="modal-body ">
                <div id="error"></div>
                  <div class="form-group has-default subtitulos">
                  		<label class="tdleft subtitulos" for="inputSuccess1">'.$tit.'</label>
                  		<input type="hidden" name="opcModulo'.$opc.'" id="opcModulo'.$opc.'" value="'.$opc.'">
                      	<input type="text" class="form-control tdleft" placeholder="'.$tittxt.'" id="inputNombre'.$opc.'" maxlength="250" value="" style="width:400px;">
                      	<br><span id="resultadoModal'.$opc.'"></span>
                  </div>
                </div>
                <div class="modal-footer tdcenter">            
                     <button type="button" class="btn btn-success btn-sm pSsaveProyecto"  id="pSsaveProyecto'.$opc.'" name="pSaveProyecto">'.GUARDAR.'</button>
                     <button type="button" class="btn btn-primary cerrarAccion" id="cerrarAccion'.$opc.'" name="cerrarAccion">'.CERRARVENTANA.'</button>
                </div>
              </div>
            </div>
          </div>';
		return $buf;
	}
	
	/**
	 * Metodo que regresa la informacion pintada en el navegador
	 *
	 * @return string variable de instancia $this->buffer
	 */
	function obtenBuffer() {
		return $this->buffer;
	}
	function obtenNoPasoFormato() {
		return $this->noPasoFormato;
	}
}