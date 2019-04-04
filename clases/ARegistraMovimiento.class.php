<?php
class RegistraMovimiento extends Comunes {
	var $db;
	var $data;
	var $session;
	var $server;
	var $path;
	var $buffer;
	var $noPasoFormato;
	var $id;
	var $opc;
	var $arrayDatos;
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
		$this->arrayDatos = array (
				0 => '',
				1 => ACCIONESANUALES,
				2 => MODEVENTOSCULTURALES 
		);
		$this->noPasoFormato = $this->opc + 1;
		settype($this->opc,"integer");
		$this->opc = (int) $this->opc;
		switch ($this->opc) {
			case 0 :
				$this->muestraPantalla1();
				break;
			case 1 :
				$this->muestraPantalla2();
				break;
			case 2 :
				$this->muestraPantalla3();
				break;
			case 3 :
				$this->muestraFormularioProyecto();
				break;
			case 4:
				$this->muestraOpciones();
				break;
			case 5:
				$this->muestraFormularioActividades();
				break;
			case 6:
				$this->muestraPantalla4();
				break;
			case 7:
				$this->recuperaDatos();
				$this->muestraFormularioProyecto();
				break;
			case 8:
				$this->listadoProyectos();
				break;
			default :
				$this->muestraPantalla1 ();
				break;
		}
	}
	
	/**
	 * Metodo que se encarga de generar el listado de proyectos
	 */
	function listadoProyectos(){
		$class="";
		$no_registros = $this->consultaNoProyectos($data);
		if($no_registros){
			$this->pages = new Paginador();
			$this->pages->items_total = $no_registros;
			$this->pages->mid_range = 25;
			$this->pages->paginate();
			$resultados   = $this->consultaProyectos($data,$this->pages->limit);
			
		}
		
		$this->buffer="<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>".LISTADODEACTIVIDADES."</div>
	  				<div class='panel-body'>";
		if(count($resultados) > 0){
			$this->buffer.="
					<div class=\"central\">
						<input type='button' value='".AGREGAPROYECTO."' class='btn btn-default btn-sm' onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=2'\">
						<input type='button' value='".TODOS."' 			class='btn btn-default btn-sm todos'>
						<input type='button' value='".NINGUNO."' 		class='btn btn-default btn-sm ningunos'>		
						<input type='button' value='".ENVIARCOORDINADOR."' class='btn btn-default btn-sm ninguno' id='cambiaFase2'>						
					</div><br>
					<table align='center' border='0' class='table table-condensed'>
					<tr>
						<td class='tdcenter fondotable' width='30%'>".PROYECTOS."</td>
						<td class='tdcenter fondotable' width=' 8%'>".PONDERACION."</td>
						<td class='tdcenter fondotable' width='16%'>".ROL."</td>		
						<td class='tdcenter fondotable' width='10%'>".FECHAALTA."</td>
						<td class='tdcenter fondotable' width='10%'>".NOACCIONES."</td>
						<td class='tdcenter fondotable' width='8%'>".EDITAR."</td>		
						<td clasS='tdcenter fondotable' width='8%'>".EDITAACTIVIDADES."</td>
						<td clasS='tdcenter fondotable' width='10%'>".ENVIARCOORDINADOR."</td>
					</tr>";
				$contador=1;
				foreach($resultados as $id => $resul){
					$class="";
					if($contador % 2 == 0)
						$class="active";
						
					$this->buffer.="
						<tr class=' $class alturaComponentesA'>
							<td class='tdleft'>".$resul['proyecto']."</td>
							<td class='tdcenter'>".$resul['ponderacion']."</td>
							<td class='tdcenter'>".$resul['nomRol']."</td>
							<td class='tdcenter'>".substr($resul['fecha_alta'],0,10)."</td>
							<td class='tdcenter'>".$resul['noAcciones']."</td>
							<td class='tdcenter'><img src='".$this->path."imagenes/iconos/pencil.png' border='0'></td>
							<td class='tdcenter'><img src='".$this->path."imagenes/iconos/new.png' border='0'></td>
							<td class='tdcenter'><input type='checkbox' name='enviaId' id='env-".$resul['id']."' class='enviaId'></td>
						</tr>";
					$contador++;
				}	
				$this->buffer.="<tr><td colspan='8' class='tdcenter'>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page()."</td></tr></table>";		
		}
		else{
			$this->buffer.="<table class='table table-condensed'><tr><td>".SINREGISTROS."</td></tr></table>";
		}
	}
	
	/**
	 * Formulario de actividades
	 */
	function muestraFormularioActividades(){
		if($this->session['folio']  >0){
			$this->arrayDatos = $this->regresaDatosProyecto($this->session['folio']);	
			$arrayUnidadOperativas=$this->catalogoUnidadesOperativas($this->db);
			$folio = $this->session['folio'];
			$random=rand(1,10000000);
			$this->buffer="
					<input type='hidden' name='folio' id='folio' value='".($this->session['folio'] + 0)."'>
					<input type='hidden' name='random' id='random' value='".$random."'>
					<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>".NUEVAACTIVIDAD."</div>
	  				<div class='panel-body'>
						<table align='center' border='0' class='table table-condensed'>
						<tr class='active alturaComponentesA'>
							<td class='tdleft' colspan='2' width='25%'>".PROYECTO."</td>
							<td class='tdleft' colspan='2'>".$this->arrayDatos['proyecto']."</td>
						</tr>
						<tr class='alturaComponentesA'>
							<td class='tdleft' colspan='2' >".UNIDADOPERATIVA."</td>
							<td class='tdleft' colspan='2'>".$arrayUnidadOperativas[$this->arrayDatos['unidadOperativaId']]."</td>
						</tr>
						<tr class='active'>
							<td class='tdleft' width='20%'>".AGREGUE."</td>
							<td class='tdcenter' width='5%'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-11' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft' width='75%' colspan='2'>
								<input type='text' required='yes' class='bootstrap-select validatextonumero espTextArea' placeholder='".ACTIVIDAD."'  value='".$this->arrayDatos ['actividad']."' name='actividad' id='actividad'> 
							</td>
						</tr>
						<tr>
							<td class='tdleft'>".UNIDADMEDIDA."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-12' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>		
							<td class='tdleft' colspan='2'>".$this->regresaMedidas($this->data,$this->arrayDatos)."</td>
						</tr>
						<tr class='active alturaComponentesA'>
							<td class='tdleft'>".PONDERACION."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-13' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft' colspan='2'>
								<input type='radio' name='ponderacion' id='Aponderacion5' value='5'>5&nbsp;&nbsp;
	                    		<input type='radio' name='ponderacion' id='Aponderacion4' value='4'>4&nbsp;&nbsp;
	                    		<input type='radio' name='ponderacion' id='Aponderacion3' value='3'>3&nbsp;&nbsp;
	                    		<input type='radio' name='ponderacion' id='Aponderacion2' value='2'>2&nbsp;&nbsp;
	                    		<input type='radio' name='ponderacion' id='Aponderacion1' value='1' checked>1
							</td>
						</tr>
						<tr>								
							<td class='tdleft'>".TIPOACT."</td>
							<td class='tdcenter'>
								<img src='".$this->path."imagenes/iconos/help.png' id='a-14' class='help' alt='".AYUDA."' title='".AYUDA."'>
							</td>
							<td class='tdleft' colspan='2'>".$this->regresaTipoActividad($this->data,$this->arrayDatos)."</td>		
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
							<td class='tdleft'><button class='btn btn-default btn-sm' id='buttonUpload' onclick='return ajaxFileUpload(".$folio.",".$random.");'>Upload</button></td>
						</tr>
						<tr>
							<td class='tdleft bold' colspan='2'><span id='downloadFiles'></span></td>
						</tr>				
						<tr>
	                		<td class='tdleft bold' colspan='2'><span id='resultado' class='error'></span></td>
	            		</tr>    
					</table></div>
					<div class=\"central\"><br>
						<input type='button' value='".AGREGAACTIVIDAD."'  class='btn btn-default btn-sm' id='guardaActividad' name='guardaActividad'>
					</div><br></div>";
		}else{
			header("Location: ".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1");
		}
	}
	
	/**
	 * Muestra opciones despues de guardar el proyecto
	 */
	function muestraOpciones(){
		$this->buffer = "
				<div class=\"central titulos\">".GUARDADOEXITOSAMENTE."</div><br>
				<div class=\"central subtitulos\">".GRACIAS."</div>
				<div id=\"contenidoFormato\">
					<br>
					<div class=\"central\"><br>
						<input type='button' value='".AGREGAACTIVIDAD."'   class='btn btn-default btn-sm' onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=5&folio=".$this->session['folio']."'\">
						<input type='button' value='".AGREGAPROYECTO."'    class='btn btn-default btn-sm' onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=2'\">
						<input type='button' value='".REGRESARAPROYECTO."' class='btn btn-default btn-sm' onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1'\">
						<input type='button' value='".REGRESAAMODULO."' class='btn btn-default btn-sm' onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=0'\">							
					</div>
				</div>
				<br><br><br><br><br>";
	}
	
	/** 
	 * Metodo que se encarga de recuperar los datos del proyecto de la bd
	 */
	function recuperaDatos(){
		if($this->data['id']!=""){
			$tmp=explode('*',$this->data['id']);
			if( ($tmp[0] + 0)>0)
				$this->arrayDatos = $this->regresaDatosProyecto($tmp[0]);	
		}
	}
	/**
	 * Muestra formulario de alta de proyecto
	 */
	function muestraFormularioProyecto() {
		$tit=EDITAACCION;
		if($this->data['opc'] ==3)
			$tit=ALTADEACCIONES;
		$this->buffer = "
        <input type='hidden' name='valueId' id='valueId' value='".($this->arrayDatos ['id'] + 0)."'>
        		<div class='panel panel-danger spancing'>
				<div class='panel-heading'>".$tit."</div>
  				<div class='panel-body'>
        	<table align='center' border='0' class='table table-condensed'>
            <tr class='altorenglon'>
                <td class='tdleft bold' width='20%'>".EJEPOLITICA."</td>
                <td class='tdleft alinea negritas'>".$this->regresaNombreEje ( $this->data,$this->arrayDatos )."</td>
                <td class='tdleft bold'>".POLITICAPUBLICA."</td>
                <td class='tdleft alinea negritas'>".$this->regresaNombrePolitica ( $this->data )."</td>
            </tr>
            <tr class='altorenglon'>
                <td class='tdleft bold'>".AREA."<input type='hidden' name='idarea' id='idarea' value='".$this->data['idarea']."'></td>
                <td class='tdleft alinea negritas'>".$this->regresaNombreArea ( $this->data )."</td>
                <td class='tdleft bold'>".PROGRAMA."<input type='hidden' name='idprograma' id='idprograma' value='".$this->data['idprograma']."'></td>
                <td class='tdleft alinea negritas'>".$this->regresaNombrePrograma ( $this->data )."</td>
            </tr>
            </table><br>
            <table width='90%' align='center' border='0' class='table-striped'>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".PROYECTO."</td>
                <td class='tdcenter' width='8%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-1' class='help' alt='".AYUDA."' title='".AYUDA."'>	
                </td>
                <td class='tdleft alinea'>".$this->generaProyectos ($this->db,$this->data['idarea'],$this->data['idprograma'],2)."&nbsp;&nbsp;<button class='ui-icon-add' data-toggle='modal' data-target='#myModalProyecto'></button></td>
			</tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".PONDERACION."&nbsp;&nbsp;</td>
                <td class='tdcenter' width='5%'><img src='".$this->path."imagenes/iconos/help.png' id='a-2' class='help'  alt='".AYUDA."' title='".AYUDA."'></td>
                <td class='tdleft alinea' colspan='2'>
                    <input type='radio' name='ponderacion' id='ponderacion5' value='5'>5&nbsp;&nbsp;
                    <input type='radio' name='ponderacion' id='ponderacion4' value='4'>4&nbsp;&nbsp;
                    <input type='radio' name='ponderacion' id='ponderacion3' value='3'>3&nbsp;&nbsp;
                    <input type='radio' name='ponderacion' id='ponderacion2' value='2'>2&nbsp;&nbsp;
                    <input type='radio' name='ponderacion' id='ponderacion1' value='1' checked>1
                </td>
            </tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".DESCRIPCIONDELPROYECTO."</td>
                <td class='tdcenter' width='5%'><img src='".$this->path."imagenes/iconos/help.png' id='a-3' class='help'  alt='".AYUDA."' title='".AYUDA."'></td>
                <td class='tdleft alinea'>
                    <textarea required='yes' maxlength='2000' class='bootstrap-select validatextonumero espTextArea' placeholder='".DESCRIPCIONDELPROYECTO."'  value='".$this->arrayDatos ['descripcion']."' name='descripcion' id='descripcion'></textarea>
                </td>
            </tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".RESULTADOSESPERADOS."</td>
                <td class='tdcenter' width='5%'><img src='".$this->path."imagenes/iconos/help.png' id='a-4' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
                <td class='tdleft alinea'>
                    <textarea required='yes' maxlength='2000' class='bootstrap-select validatextonumero espTextArea' placeholder='".RESULTADOSESPERADOS."'  value='".$this->arrayDatos ['resultados']."' name='resultados' id='resultados'></textarea>
                </td>
            </tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".PRESUPUESTO."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-7' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>".(date('Y') - 1)."&nbsp;&nbsp;$&nbsp
                	<input type='text' class='bootstrap-select validanums' placeholder='".PRESUPUESTO."'  name='presupuesto_1' id='presupuesto_1' size='12'>&nbsp;&nbsp;".OTORGADO."
                </td>
            </tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-8' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>".(date('Y') + 0)."&nbsp;&nbsp;$&nbsp;
                	<input type='text' class='bootstrap-select validanums' placeholder='".ESTIMADO."'   name='estimado_1' id='estimado_1' size='12'>&nbsp;&nbsp;".ESTIMADO."
                </td>
            </tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".ENCOORDINACION."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-9' class='help' alt='".AYUDA."' title='".AYUDA."'>
               	</td>
               	<td class='tdleft alinea'></td>
            </tr>
            <tr class='altotitulo'>
               <td colspan='3' class='tdleft bold'>".$this->enCoordinacion($this->data)."</td>
            </tr>
            <tr class='altotitulo' id='trespecifique'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".ESPECIFIQUE."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-10' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>
                    <textarea required='yes' class='bootstrap-select validatextonumero espTextArea2' placeholder='".ESPECIFIQUE."'  value='".$this->arrayDatos ['especifique']."' name='especifique' id='especifique'></textarea>
                </td>
            </tr>
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".METODO."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-17' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
				<td class='tdleft alinea'>".$this->metodoParticipacion($this->data,$this->arrayDatos)."&nbsp;&nbsp;
              		<button class='ui-icon-add' data-toggle='modal' data-target='#myModalParticipacion' id='btn-5'></button></td>
            </tr>						
            <tr class='altotitulo'>
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".UNIDADOPERATIVA."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-5' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>".$this->generaUnidadesOperativas($this->data)."&nbsp;&nbsp;
              		<button class='ui-icon-add' data-toggle='modal' data-target='#myModalUOperativa' id='btn-5'></button></td>
            </tr>
            <tr class='altotitulo'>		
                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;".RESPONSABLE."</td>
                <td class='tdcenter' width='5%'>
                	<img src='".$this->path."imagenes/iconos/help.png' id='a-6' class='help' alt='".AYUDA."' title='".AYUDA."'>
                </td>
                <td class='tdleft alinea'>".$this->generaResponsables ($this->data)."&nbsp;&nbsp;
                <button class='ui-icon-add' data-toggle='modal' data-target='#myModalResponsable' id='btn-6'></button></td>		
            </tr>
            <tr>
                <td class='tdcenter bold' colspan='6'><span id='resultado' class='error'></span></td>
            </tr>    		
            <tr>
                <td class='tdcenter legend' colspan='6'><br><br>
                <button type='button' class='btn btn-default savecatalogoProyecto' id='saveProyecto' name='saveProyecto'>".GUARDARPROYECTO."</button>                		
                <BR><BR>
                </td>
            </tr>
        </table></div></div>";
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
                     <button type="button" class="btn btn-default btn-sm pSsaveProyecto"  id="pSsaveProyecto'.$opc.'" name="pSaveProyecto">'.GUARDAR.'</button>
                     <button type="button" class="btn btn-default cerrarAccion" id="cerrarAccion'.$opc.'" name="cerrarAccion">'.CERRARVENTANA.'</button>
                </div>
              </div>
            </div>
          </div>';
		return $buf;
	}
	
	/**
	 * Metodo que se encarga de pintar la primera opcion
	 */
	function muestraPantalla1() {
		$this->buffer = "
			<div class='panel panel-danger spancing'>
				<div class='panel-heading titulosBlanco'>".MODULOS."</div>
	  			<div class='panel-body'>
					<div class=\"container-registra\">
						<div class=\"central subtitulos\">".ELIJAUNAOPCION."</div>
						<div id=\"contenidoFormato\">
							<br>
							<ol>
								<li>
									<a href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1' id='1' class='pasoslink'>".ACCIONESANUALES."</a>
								</li>
								<li>
									<a href='".$this->path."aplicacion.php?aplicacion=1&apli_com=1&opc=1' id='2' class='pasoslink'>".MODEVENTOSCULTURALES."</a>
								</li>
							</ol>
						</div>
					</div>
				</div>
			</div><br><br><br>";
	}
	
	/**
	 * Metodo que se encarga de pintar la segunda opcion
	 */
	function muestraPantalla2() {
		$this->buffer = "
				<div class='panel panel-danger spancing'>
				<div class='panel-heading titulosBlanco'>".PROYECTOSYACTIVIDADES."</div>
	  			<div class='panel-body'>
				<div class=\"container-registra\">
				<div class=\"central subtitulos\">".ELIJAUNAOPCION."</div>
				<div id=\"contenidoFormato\">
				<br>
				<ol>
				<li>
				<a href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=2' id='2' class='pasoslink'>".ALTADEPROYECTOS."</a>
				</li>
				<li>
				<a href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=6' id='3' class='pasoslink'>".CONSULTADEACTIVIDADES."</a>
				</li>
				</ol>
				</div>
				<div class=\"central\"><br><input type='button' value='".REGRESA."' class='btn btn-default btn-sm' onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."'\"></div>						
				</div></div></div><br><br><br>";
	}
	
	/**
	 * Metodo que se encarga de pintar la tercera opcion
	 */
	function muestraPantalla3() {
		$comboAreas = $this->generaComboAreas ();
		$comboProgramas = $this->generaComboProgramas ();
		$this->buffer = "
				<div class='panel panel-danger spancing'>
				<div class='panel-heading titulosBlanco'>".CATALOGODEPROGRAMAS."</div>
	  			<div class='panel-body'>
				<div class=\"container-registra\">
					<div class=\"central subtitulos\">".FAVORDESELECCIONAR."</div><br>
					<div id=\"contenidoFormato\">
						<div class=\"central\">".$comboAreas."</div>
						<div class=\"central\">".$comboProgramas."</div>
					</div>
					<div class=\"central\"><br>
						<input type='hidden' value='".$this->session ['aplicacion']."' id='aplicacion' name='aplicacion'>
						<input type='hidden' value='".$this->session ['apli_com']."'   id='apli_com'   name='apli_com'>
						<input type='button' value='".NUEVOPROYECTOB."' class='btn btn-default btn-sm' id='btnNvoProyecto' name='btnNvoProyecto' >
						<input type='button' value='".EDITARPROYECTO."' class='btn btn-default btn-sm' id='btnConProyecto' name='btnConProyecto' >
						<input type='button' value='".REGRESA."'        class='btn btn-default btn-sm' onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."'\">
						<br><br>
				</div>
				<div class=\"central\" id=\"error\"><br><br></div>
				</div></div></div><br><br><br>";
	}
	
	function muestraPantalla4() {
		$comboAreas = $this->generaComboAreas ();
		$comboProgramas = $this->generaComboProgramas ();
		$comboAnos = $this->generaCombosAnos();
		$this->buffer = "
				<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>".CATALOGODEPROGRAMAS."</div>
	  				<div class='panel-body'>
					<div class=\"central subtitulos\">".FAVORDESELECCIONAR."</div><br>
					<div id=\"contenidoFormato\">
						<div class=\"central\">".$comboAreas."</div>
						<div class=\"central\">".$comboProgramas."</div>
						<div class=\"central\">".$comboAnos."</div>
					</div>
					<div class=\"central\"><br>
						<input type='hidden' value='".$this->session ['aplicacion']."' id='aplicacion' name='aplicacion'>
						<input type='hidden' value='".$this->session ['apli_com']."'   id='apli_com'   name='apli_com'>
						<input type='button' value='".BUSCARPROYECTO."' class='btn btn-default btn-sm' id='btnBuscarProyecto' name='btnBuscarProyecto'>
						<input type='button' value='".REGRESA."'        class='btn btn-default btn-sm' onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."'\">
						<br><br>
						<span id='resProyecto'></span>
					</div>
					<div class=\"central\" id=\"error\"><br><br></div>
					</div></div>
				";
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