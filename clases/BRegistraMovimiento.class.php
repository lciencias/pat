<?php
class RegistraMovimiento extends Comunes {
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
	var $diasLimite;
	var $disabled;
	var $visible;
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
		$this->disabled="";
		$this->visible = false;
		if($this->diasLimite > 0){
			$this->disabled=" disabled ";
			$this->visible = true;
		}
		settype($this->opc,"integer");
		$this->opc = (int) $this->opc;
		switch ($this->opc) {
			case 0 :
				$this->listadoProyectos();
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
				$this->buffer="Actividades:  ".$tmp[0];
			}
			$url=$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=4&folio=".$this->data['folio'];
			$this->buffer="
				<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>
						<div class='tdleft titulosBlanco columna1'><span class='titulosBlanco'>".LISTADODEACTIVIDADES." &quot;".$nmProyecto."&quot;</span></div>
						<div class='tdright columna2'><input type='button' value='".AGREGAACTIVIDAD."' class='btn btn-danger btn-sm' 
								onclick=\"location='".$url."'\"></div>
					</div>
	  				<div class='panel-body'>".$this->divFiltrosProyectos(2,$tmp[0]);
			if(count($resultados) > 0){
				$this->buffer.="
					<table align='center' border='0' class='table table-condensed'>
					<tr>
						<td class='tdcenter fondotable' width='40%'>".ACTIVIDAD."</td>
						<td class='tdcenter fondotable' width='23%'>".UNIDADMEDIDA."</td>
						<td class='tdcenter fondotable' width=' 8%'>".PONDERACION."</td>
						<td class='tdcenter fondotable' width='17%'>".TIPOACT."</td>
						<td class='tdcenter fondotable' width='6%'>".EDITAR."</td>
						<td clasS='tdcenter fondotable' width='6%'>".ELIMINARPROYECTO."</td>
					</tr>";
				$this->bufferExcel="
					<table align='center' border='0' class='table table-condensed'>
					<tr>
						<td class='tdcenter fondotable' width='45%'>".ACTIVIDAD."</td>
						<td class='tdcenter fondotable' width='23%'>".UNIDADMEDIDA."</td>
						<td class='tdcenter fondotable' width=' 8%'>".PONDERACION."</td>
						<td class='tdcenter fondotable' width='12%'>".TIPOACT."</td>
						<td class='tdcenter fondotable' width='6%'>".EDITAR."</td>
						<td clasS='tdcenter fondotable' width='6%'>".ELIMINARPROYECTO."</td>
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
							<td class='tdcenter'>
								<a href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=6&folio=".$varTemporal."'>
									<img src='".$this->path."imagenes/iconos/pencil.png' border='0'>
								</a>
							</td>						
							<td class='tdcenter'>
								<a href='#' onclick='return false;'>
									<img src='".$this->path."imagenes/iconos/delete.png' class='deleteActividadesProyecto' id='".$varTemporal."' border='0'></a>
							</td>
						</tr>";
					$this->bufferExcel.="<tr'>
							<td class='tdleft'>".$resul['actividad']."</td>
							<td class='tdleft'>".$resul['medida']."</td>
							<td class='tdcenter'>".$resul['ponderacion']."</td>
							<td class='tdleft'>".$resul['tipoAct']."</td></tr>";
					$contador++;
				}
				$this->bufferExcel.="</table>";
				$this->buffer.="<tr>
								<td colspan='4' class='tdcenter'>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page()."</td>
								<td colspan='2' class='tdcenter'>".$this->Genera_Archivo($this->bufferExcel)."</td>
								</tr></table>";
				
		}
		else{
			$this->buffer.="<table class='table table-condensed'><tr><td class='tdcenter'>".SINREGISTROSACTIVIDAD."</td></tr></table>";
		}
		$urlRegreso=$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com'];
		$this->buffer.="<div class=\"central\">
							<input type='button' value='".REGRESA."' class='btn btn-danger btn-sm' 
							onclick=\"location='".$urlRegreso."'\">
						</div></div>".$this->procesando(4)."</div>";
						
		}
	}
	
	function obtenFiltrosActividades($folio){
		$buf="<form action='aplicacion.php' method='post'>
			<input type='hidden' value='3' id='opc' name='opc'>
			<input type='hidden' name='folio' id='folio' value='".$folio."'>
			<table class='tableSinbordes' align='center'>
			<tr>
				<td class='tdcenter'>".$this->regresaMedidas(1)."</td>
				<td class='tdcenter'>".$this->regresaTipoActividad(1)."</td>
				<td class='tdcenter'>".$this->generaPonderacion()."</td>
				<td class='tdcenter'><input type='submit' name='btnfiltros' id='btnfiltros'  value='".CONSULTAR."' class='btn btn-danger' style='width:120px;'></td>
			</tr>
			<tr>
				<td colspan='3' class='tdleft'><input type='text' class='form-control validatextonumero' placeholder='".BUSCAXACTIVIDAD."' name='busqNombreA' id='busqNombreA' maxlength='250' value='".$this->arrayDatos ['actividad']."' style='width:380px;' ></td>
				<td class='tdcenter'><input type='reset'  name='btnLimpiar' id='btnLimpiar'  value='".LIMPIAR."'   class='btn btn-danger' style='width:120px;'></td>
				<td class='tdcenter'></td>
			</tr>
		</table></form>";
		return $buf;
	}
	
	function obtenFiltros(){
	$buf="<form action='aplicacion.php' method='post'>
			<input type='hidden' value='0' id='opc' name='opc'>
			<table class='tableSinbordes' align='center'>
			<tr>
				<td class='tdcenter'>".$this->regresaNombreArea(1)."</td>
				<td class='tdcenter'>".$this->regresaNombrePrograma(1)."</td>
				<td class='tdcenter'>".$this->regresaNombreRol()."</td>
				<td class='tdcenter'>".$this->generaAnos()."</td>
				<td class='tdcenter'>".$this->regresaNombreTrimestre()."</td>
			</tr>
			<tr>
				<td colspan='2' class='tdleft'><input type='text' class='form-control validatextonumero' placeholder='".BUSCAXPROYECTO."' name='busqNombre' id='busqNombre' maxlength='250' value='".$this->arrayDatos ['proyecto']."' style='width:380px;'></td>
				<td class='tdcenter'>".$this->generaPonderacion()."</td>
				<td class='tdcenter'><input type='submit' name='btnfiltros' id='btnfiltros'  value='".CONSULTAR."' class='btn btn-danger' style='width:120px;'></td>
				<td class='tdcenter'><input type='reset'  name='btnLimpiar' id='btnLimpiar'  value='".LIMPIAR."'   class='btn btn-danger' style='width:120px;'></td>
			</tr>
		</table></form>";
	return $buf;	
	}
	
	function divFiltrosProyectos($opcion,$valor){
		$mens = "";
		if($opcion == 1)
			$mens.=$this->obtenFiltros();
		else
			$mens.=$this->obtenFiltrosActividades($valor);
		
		$tit=FILTROSBUSQUEDA;
		if($opcion = 1)
			$tit=FILTROSBUSQUEDA;
		
		$buf="<div class='panel-group' id='accordion' role='tablist' aria-multiselectable='true'>
  				<div class='panel panel-default'>
    				<div class='panel-heading' role='tab' id='headingOne'>
      					<h4 class='panel-title'>
        				<a data-toggle='collapse' data-parent='#accordion' href='#collapseOne' aria-expanded='true' aria-controls='collapseOne'>".$tit."</a>
				      </h4>
    				</div>
    				<div id='collapseOne' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='headingOne'>
      					<div class='panel-body'>".$mens."</div>
    				</div>
  				</div>
      		</div>";
		return $buf;
	}
	/**
	 * Metodo que se encarga de generar el listado de proyectos
	 */
	function listadoProyectos(){
		$class="";
		
		$no_registros = $this->consultaNoProyectos();
		if($no_registros){
			$this->pages = new Paginador();
			$this->pages->items_total = $no_registros;
			$this->pages->mid_range = 25;
			$this->pages->paginate();
			$resultados   = $this->consultaProyectos();
		}
		$this->buffer="
				<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>
						<div class='tdleft titulosBlanco columna1' ><span class='titulosBlanco'>".LISTADODEPROYECTOS."</span></div>
						<div class='tdright columna2' ><input type='button' value='".AGREGAPROYECTO."' class='btn btn-danger btn-sm' onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1'\"></div>
					</div>
	  				<div class='panel-body'>".$this->divFiltrosProyectos(1,0);
			if(count($resultados) > 0){
			$this->buffer.="
					<table align='center' border='0' class='table table-condensed'>
					<tr>
						<td class='tdcenter fondotable' width='28%'>".PROYECTOS."</td>
						<td class='tdcenter fondotable' width=' 8%'>".PONDERACION."</td>
						<td class='tdcenter fondotable' width='16%'>".ROL."</td>		
						<td class='tdcenter fondotable' width='12%'>".FECHAALTA."</td>
						<td class='tdcenter fondotable' width='10%'>".NOACCIONES."</td>
						<td class='tdcenter fondotable' width='8%'>".METAS."</td>
						<td class='tdcenter fondotable' width='8%'>".EDITAR."</td>		
						<td clasS='tdcenter fondotable' width='8%'>".EDITAACTIVIDADES."</td>
						<td clasS='tdcenter fondotable' width='8%'>".ELIMINARPROYECTO."</td>
						<td clasS='tdcenter fondotable' width='10%'>".ENVIARCOORDINADOR."</td>
					</tr>";
				$this->bufferExcel = $this->buffer;
				$contador=1;
				$varTemporal="";
				foreach($resultados as $id => $resul){
					$rand = rand(1,99999999999999);
					$class="";
					if($contador % 2 == 0)
						$class="active";
					$varTemporal = $resul['id']."-".$rand;
					$this->buffer.="
						<tr class=' $class alturaComponentesA'>
							<td class='tdleft'>".$resul['proyecto']."</td>
							<td class='tdcenter'>".$resul['ponderacion']."</td>
							<td class='tdleft'>".$resul['nomRol']."</td>
							<td class='tdcenter'>".substr($resul['fecha_alta'],0,10)."</td>
							<td class='tdcenter'>".$resul['noAcciones']."</td>
							<td class='tdcenter'>";
					if($resul['noAcciones'] > 0)
						$this->buffer.="
								<a href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=9&folio=".$varTemporal."'>
									M
								</a>";
						$this->buffer.="
							</td>
							<td class='tdcenter'>
								<a href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=5&folio=".$varTemporal."'>
									<img src='".$this->path."imagenes/iconos/pencil.png' border='0'>
								</a>
							</td>
							<td class='tdcenter'>
								<a href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=3&folio=".$varTemporal."'>
									<img src='".$this->path."imagenes/iconos/new.png' border='0'>
								</a>
							</td>											
							<td class='tdcenter'>
								<a href='#' onclick='return false;'><img src='".$this->path."imagenes/iconos/delete.png' class='deleteProyecto' id='".$varTemporal."' border='0'></a>
							</td>
							<td class='tdcenter'>";
				if(!$this->visible)
					$this->buffer.="<input type='checkbox' name='enviaId' id='".$varTemporal."' class='enviaId'>";
					$this->buffer.="</td>
						</tr>";
					$this->bufferExcel .="<tr>
							<td class='tdleft'>".$resul['proyecto']."</td>
							<td class='tdcenter'>".$resul['ponderacion']."</td>
							<td class='tdcenter'>".$resul['nomRol']."</td>
							<td class='tdcenter'>".substr($resul['fecha_alta'],0,10)."</td>
							<td class='tdcenter'>".$resul['noAcciones']."</td></tr>";
					$contador++;
				}	
				$this->bufferExcel .="</table>";
				$this->buffer.="<tr>
						<td colspan='8' class='tdcenter'>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page()."</td>
						<td colspan='2' class='tdcenter'>".$this->Genera_Archivo($this->bufferExcel)."</td>
						</tr></table>
					<div class=\"central\">
						<input type='button' value='".TODOS."' 			class='btn btn-danger btn-sm todos'>
						<input type='button' value='".NINGUNO."' 		class='btn btn-danger btn-sm ningunos'>		
						<input type='button' value='".ENVIARCOORDINADOR."' class='btn btn-danger btn-sm ninguno' id='cambiaFase2'>						
					</div>";		
		}
		else{
			$this->buffer.="<table class='table table-condensed'><tr><td class='tdcenter'>".SINREGISTROS."</td></tr></table>";
		}
		$this->buffer.="</div>".$this->procesando(4)."</div>";
	}
	
	/**
	 * Formulario de actividades
	 */
	function muestraFormularioActividades(){
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
							<td class='tdleft' colspan='2'>".$arrayProyecto['proyecto']."</td>
						</tr>
						<tr class='alturaComponentesA'>
							<td class='tdleft' colspan='2' >".UNIDADOPERATIVA."</td>
							<td class='tdleft' colspan='2'>".$arrayUnidadOperativas[$arrayProyecto['unidadOperativaId']]."</td>
						</tr>
						<tr class='active'>
							<td class='tdleft' width='20%'>".AGREGUE."</td>
							<td class='tdcenter' width='5%'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-11' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft' width='75%' colspan='2'>
								<input type='text' required='yes'  ".$this->disabled." class='bootstrap-select validatextonumero espTextArea' placeholder='".ACTIVIDAD."'  value='".$this->arrayDatos['actividad']."' name='actividad' id='actividad'> 
							</td>
						</tr>
						<tr>
							<td class='tdleft'>".TRIMESTRE."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-21' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>		
							<td class='tdleft' colspan='2'>".$this->regresaNombreTrimestre()."</td>
						</tr>
						<tr>
							<td class='tdleft'>".UNIDADMEDIDA."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-12' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>		
							<td class='tdleft' colspan='2'>".$this->regresaMedidas(2)."</td>
						</tr>
						<tr class='active alturaComponentesA'>
							<td class='tdleft'>".PONDERACION."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-13' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft' colspan='2'>
								<input type='radio' name='ponderacion' id='Aponderacion5' ".$arrayPonderacion[5]."  ".$this->disabled." value='5'>5&nbsp;&nbsp;
	                    		<input type='radio' name='ponderacion' id='Aponderacion4' ".$arrayPonderacion[4]."  ".$this->disabled." value='4'>4&nbsp;&nbsp;
	                    		<input type='radio' name='ponderacion' id='Aponderacion3' ".$arrayPonderacion[3]."  ".$this->disabled." value='3'>3&nbsp;&nbsp;
	                    		<input type='radio' name='ponderacion' id='Aponderacion2' ".$arrayPonderacion[2]."  ".$this->disabled." value='2'>2&nbsp;&nbsp;
	                    		<input type='radio' name='ponderacion' id='Aponderacion1' ".$arrayPonderacion[1]."  ".$this->disabled." value='1'>1
							</td>
						</tr>
						<tr>								
							<td class='tdleft'>".TIPOACT."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-14' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft' colspan='2'>".$this->regresaTipoActividad(2)."</td>		
						</tr>
						<!--<tr class='active'>
							<td class='tdleft background-white' >".COMENTARIOS."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-15' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft' colspan='2'><textarea maxlength='2000' required='yes' class='bootstrap-select validatextonumero espTextArea' placeholder='".OBSERVACION."'  value='".$this->arrayDatos ['observacion']."' name='observacion' id='observacion'></textarea></td>
						</tr>-->
						<tr>
							<td class='tdleft' rowspan='3'>".ADJUNTOS."</td>
							<td class='tdcenter' rowspan='3'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-16' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft'>
								<input id='fileToUpload' type='file' size='45' name='fileToUpload' class='input'>
								<img id='loading' src='loading.gif' style='display:none;'>
							</td>
							<td class='tdleft'>";
			if(!$this->visible)
				$this->buffer.="<button class='btn btn-danger btn-sm' id='buttonUpload' onclick='return ajaxFileUpload(".$folio.",".$random.");'>Upload</button>";
			
			$this->buffer.="</td>
						</tr>
						<tr>
							<td class='tdleft bold' colspan='2'><span id='downloadFiles'>".$adjuntos."</span></td>
						</tr>				
						<tr>
	                		<td class='tdleft bold' colspan='2'><span id='resultado' class='error'></span></td>
	            		</tr>    
					</table></div>
					<div class=\"central\"><br>";
			if(!$this->visible)
				$this->buffer.="<input type='button' value='".AGREGAACTIVIDAD."'  class='btn btn-danger btn-sm' id='".$name."' name='".$name."'>
						&nbsp;&nbsp;";
            $this->buffer.="<button type='button' class='btn btn-danger btn-sm'
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
					<input type='hidden' name='folio' id='folio' value='".$urlfolio."'>
			
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
					if($contador % 2 == 0)
						$class="active";
					$varTemporal = $resul['id']."-".$rand;
					$idact= $resul['id'];
					$totales = $totales + $this->arrayDatos[$idact][5] + 0;
					$this->buffer.="
					<tr class=' $class alturaComponentesA'>
						<td class='tdleft' rowspan='2'>".$resul['actividad']."</td>
						<td class='tdcenter'>
						<input type='text' class='form-control validanumsM' ".$this->disabled." tabindex='".$contadorTab1."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-".$resul['id']."-".$contadorTab1."-1' maxlength='10' value='".($this->arrayDatos[$idact][1] + 0)."' style='width:35px;'>		
						</td>
						<td class='tdcenter'>
						<input type='text' class='form-control'  disabled id='r-".$contadorRen."-".$resul['id']."-".$contadorTab1."-1' maxlength='10' value='' style='width:35px;'>
						</td>
						<td class='tdcenter'>
						<input type='text' class='form-control validanumsM'  ".$this->disabled." tabindex='".$contadorTab2."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-".$resul['id']."-".$contadorTab2."-2' maxlength='10' value='".($this->arrayDatos[$idact][2] + 0)."' style='width:35px;'>		
						</td>
						<td class='tdcenter'>
						<input type='text' class='form-control'  disabled id='r-".$contadorRen."-".$resul['id']."-".$contadorTab1."-1' maxlength='10' value='' style='width:35px;'>
						</td>
						<td class='tdcenter'>
						<input type='text' class='form-control validanumsM'  ".$this->disabled." tabindex='".$contadorTab3."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-".$resul['id']."-".$contadorTab3."-3' maxlength='10' value='".($this->arrayDatos[$idact][3] + 0)."' style='width:35px;'>		
						</td>
						<td class='tdcenter'>
						<input type='text' class='form-control'  disabled id='r-".$contadorRen."-".$resul['id']."-".$contadorTab1."-1' maxlength='10' value='' style='width:35px;'>
						</td>
						<td class='tdcenter'>
						<input type='text' class='form-control validanumsM'  ".$this->disabled." tabindex='".$contadorTab4."' placeholder='".NOPROYECTOS."' id='p-".$contadorRen."-".$resul['id']."-".$contadorTab4."-4' maxlength='10' value='".($this->arrayDatos[$idact][4] + 0)."' style='width:35px;'>		
						</td>
						<td class='tdcenter'>
						<input type='text' class='form-control'  disabled id='r-".$contadorRen."-".$resul['id']."-".$contadorTab1."-1' maxlength='10' value='' style='width:35px;'>
						</td>
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
						$this->buffer.="<input type='button' tabindex='".$contadorTab4."' value='".AGREGAMETA."'  class='btn btn-danger btn-sm' id='".$name."' name='".$name."'>
						&nbsp;&nbsp;";
                $this->buffer.="<button type='button' class='btn btn-danger btn-sm'
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
			$name="saveProyecto";
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
		$this->buffer = "
        	<input type='hidden' name='valueId' id='valueId' value='".($this->arrayDatos ['id'] + 0)."'>
        	<input type='hidden' name='idproyecto' id='idproyecto' value='".($this->arrayDatos ['proyecto_id'] + 0)."'>
        	<div class='panel panel-danger spancing'>
				<div class='panel-heading'>".$tit."</div>
  				<div class='panel-body' id='panelFormaProyecto'>
        		<table align='center' border='0' class='table table-condensed'>
            <tr class='altorenglon'>
                <td class='tdleft bold' width='20%'>".EJEPOLITICA."</td>
                <td class='tdleft alinea'><span id='nmejes'>".$this->RecuperaDatosEjes()."</span></td>
                <td class='tdleft bold'>".POLITICAPUBLICA."</td>
                <td class='tdleft alinea'><span id='nmpoliticas'>".$this->RecuperaDatosPoliticas()."</span></td>
            </tr>
            <tr class='altorenglon'>
                <td class='tdleft bold'>".AREA."</td>
                <td class='tdleft alinea'>".$this->regresaNombreArea(2)."</td>
                <td class='tdleft bold'>".PROGRAMA."</td>
                <td class='tdleft alinea'>".$this->regresaNombrePrograma(2)."</td>
            </tr>
            </table><br>
            <table width='90%' align='center' border='0' class='table-striped'>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".PROYECTO."</td>
                <td class='tdcenter' width='8%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-1' class='help' alt='".AYUDA."' title='".AYUDA."'>	
                </td>
                <td class='tdleft alinea'><input type='text' ".$this->disabled." class='form-control validatextonumero' placeholder='".PROYECTOS."' id='inputNombre' maxlength='250' value='".$this->arrayDatos ['proyecto']."' style='width:350px;'></td>
			</tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".ANO."</td>
                <td class='tdcenter' width='8%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-18' class='help' alt='".AYUDA."' title='".AYUDA."'>	
                </td>
                <td class='tdleft alinea'>".$this->generaCombosAnos()."</td>
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
                    <textarea required='yes' maxlength='2000'  ".$this->disabled." class='bootstrap-select validatextonumero espTextArea' placeholder='".DESCRIPCIONDELPROYECTO."' name='descripcion' id='descripcion'>".$this->arrayDatos ['descripcion']."</textarea>
                </td>
            </tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".RESULTADOSESPERADOS."</td>
                <td class='tdcenter' width='5%'><img src='".$this->path."imagenes/iconos/help.png' id='a-4' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
                <td class='tdleft alinea'>
                    <textarea required='yes' maxlength='2000'  ".$this->disabled." class='bootstrap-select validatextonumero espTextArea' placeholder='".RESULTADOSESPERADOS."' name='resultados' id='resultados'>".$this->arrayDatos ['resultados']."</textarea>
                </td>
            </tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".PRESUPUESTO."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-7' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>".(date('Y') - 1)."&nbsp;&nbsp;$&nbsp
                	<input type='text' class='form-control-num validanums'  ".$this->disabled." placeholder='".PRESUPUESTO."'  name='presupuesto_1' id='presupuesto_1' size='12' value='".number_format($this->arrayDatos['presupuesto_otorgado'],2,'.',',')."'>&nbsp;&nbsp;".OTORGADO."
                </td>
            </tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-8' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>".(date('Y') + 0)."&nbsp;&nbsp;$&nbsp;
                	<input type='text' class='form-control-num validanums'  ".$this->disabled." placeholder='".ESTIMADO."'   name='estimado_1' id='estimado_1' value='".number_format($this->arrayDatos['presupuesto_estimado'],2,'.',',')."'>&nbsp;&nbsp;".ESTIMADO."
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
                    <textarea required='yes' class='bootstrap-select validatextonumero espTextArea2'  ".$this->disabled." placeholder='".ESPECIFIQUE."'  name='especifique' id='especifique'>".$this->arrayDatos ['especifique']."'</textarea>
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
              		<button class='ui-icon-add' data-toggle='modal' data-target='#myModalUOperativa' id='btn-5'></button></td>
            </tr>
            <tr class='altotitulo'>		
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".RESPONSABLE."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-6' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>".$this->generaResponsables()."&nbsp;&nbsp;
                <button class='ui-icon-add' data-toggle='modal' data-target='#myModalResponsable' id='btn-6'></button></td>		
            </tr>
            <tr>
                <td class='tdcenter bold' colspan='6'><span id='resultado' class='error'></span></td>
            </tr>    		
            <tr>
                <td class='tdcenter legend' colspan='6'><br>";
		if(!$this->visible)
                $this->buffer.="<button type='button' class='btn btn-danger' id='".$name."' name='".$name."'>".$boton."</button>
                &nbsp;&nbsp;";
		
        $this->buffer.="<button type='button' class='btn btn-danger btn-sm'
                 onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=0'\">".REGRESA."</button>              		
                <BR><BR>
                </td>
            </tr>
        </table></div>".$this->procesando(3)."</div>";
		$this->buffer.=$this->Modal("myModalProyecto",1);
		$this->buffer.=$this->Modal("myModalParticipacion",4);
		$this->buffer.=$this->Modal("myModalUOperativa",2);
		$this->buffer.=$this->Modal("myModalResponsable",3);
		
	}
	
	/**
	 * Metodo que pinta el modal
	 */
	function Modal($name,$opc){
		$tit="Agregar proyecto";
		$tittxt="Teclee el nombre del proyecto";
		switch ($opc){
			case 1:
				$tit=AGREGRAPROYECTO;
				$tittxt=TECLEENOMBREPROYECTO;
				break;
			case 2:
				$tit=AGREGRAUOPERATIVA;
				$tittxt=TECLEEUNIDADOPERATIVA;
				break;
			case 3:
				$tit=AGREGRARESPONSABLE;
				$tittxt=TECLEERESPONSABLE;
				break;
			case 4:
				$tit=AGREGRARPARTICIPACION;
				$tittxt=TECLEEPARTICIPACION;
				break;				
				
		}
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
                     <button type="button" class="btn btn-danger btn-sm pSsaveProyecto"  id="pSsaveProyecto'.$opc.'" name="pSaveProyecto">'.GUARDAR.'</button>
                     <button type="button" class="btn btn-danger cerrarAccion" id="cerrarAccion'.$opc.'" name="cerrarAccion">'.CERRARVENTANA.'</button>
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