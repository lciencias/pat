<?php
class AsignaProyectos extends Comunes {
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
		$this->diasLimite = $this->fechaLimiteCaptura ();
		$this->arrayNotificaciones = array ();
		$this->disabled = "";
		$this->disabledAdmin = "";
		$this->arrayDatosMetas = array ();
		$this->visible = false;
		settype ( $this->opc, "integer" );
		$this->opc = ( int ) $this->opc;
		switch ($this->opc) {
			case 0 :
				$this->listadoProyectos ();
				break;
			case 1:
				$this->asignProyectos();
				break;
			default :
				$this->listadoProyectos ();
				break;
		}
	}
	
	function asignProyectos (){
		$arrayTmp = array();
		if($this->data['idUsuario'] > 0 && trim($this->data['proyectos']) != ""){
			$arrayTmp = explode('|',trim($this->data['proyectos']));
			if(count($arrayTmp) > 0){
				foreach($arrayTmp as $id){
					if( ($id + 0)  > 0){
						$upd="UPDATE proyectos_acciones set userId='".$this->data['idUsuario']."' WHERE id='".$id."' LIMIT 1;";
						$res=$this->db->sql_query($upd);
						$this->buffer.="\n".$upd;
					}
				}
			}
		}
	}
	
	function obtenFiltros() {
		$width = "width='30%'";
		$width2 = "width='20%'";
		$buf = "<form action='aplicacion.php' method='post'>
					<input type='hidden' value='0' id='opc' name='opc'>
					<table class='tableSinbordes' align='center' width='100%'>
					<tr>
						<td class='tdleft' " . $width . ">" . $this->regresaNombreArea ( 1 ) . "&nbsp;
							<img src='" . $this->path . "imagenes/iconos/help.png' id='a-16' class='help' alt='" . AYUDA . "' title='" . AYUDA . "'></td>
						<td class='tdleft' " . $width . ">" . $this->regresaNombrePrograma ( 1 ) . "&nbsp;
							<img src='" . $this->path . "imagenes/iconos/help.png' id='a-17' class='help' alt='" . AYUDA . "' title='" . AYUDA . "'></td>
						<td class='tdleft' " . $width2 . ">" . $this->generaAnos () . "&nbsp;
							<img src='" . $this->path . "imagenes/iconos/help.png' id='a-19' class='help' alt='" . AYUDA . "' title='" . AYUDA . "'></td>
						<td class='tdcenter' " . $width2 . ">
				<button type='submit' name='btnfiltros' id='btnfiltros' class='btn btn-primary' style='width:140px;'><span class='glyphicon glyphicon-search'></span>&nbsp;&nbsp;" . CONSULTAR . "</button>								
					</tr>
					</table></form>";
		return $buf;
	}
	
	
	/**
	 * Metodo que muestra los headers dependiendo del tipo de usuario
	 * 
	 * @return string regresa las cabeceras como tabla
	 */
	function cabeceras() {
		$buf = "";
		$buf .= " <td class='tdcenter fondotable' width='3%'>" . ID . "</td>
				<td class='tdcenter fondotable' width='21%'>" .AREA. "</td>
				<td class='tdcenter fondotable' width='21%'>" .PROGRAMA. "</td>
			    <td class='tdcenter fondotable' width='25%'>" . PROYECTOS . "</td>
				<td class='tdcenter fondotable' width='25%'>" . USUARIO . "</td>
				<td class='tdcenter fondotable' width='5%'>" . MARCAR . "</td>";
		return $buf;
	}
	/**
	 * Metodo que se encarga de generar el listado de proyectos
	 */
	function listadoProyectos() {
		$class = "";
		$no_registros = $this->consultaNoProyectos ();
		$this->arrayNotificaciones = $this->notificaciones ();
		if ($no_registros) {
			$this->pages = new Paginador ();
			$this->pages->items_total = $no_registros;
			$this->pages->mid_range = 25;
			$this->pages->paginate ();
			$resultados = $this->consultaProyectos ();
			$this->bufferExcel = $this->generaProyectosExcel ();
		}
		$this->buffer = "
				<div class='panel panel-danger spancing'>
					<div class='panel-heading'><span class='titulosBlanco'>" . LISTADODEPROYECTOSPARAASIGNACION . "</span></div>
	  				<div class='panel-body'><br>".$this->obtenFiltros();
		if (count ( $resultados ) > 0) {
			$arrayAreas = $this->catalogoAreas();
			$arrayProgramas = $this->catalogoProgramas();
			$arrayOpera = $this->catalogoUnidadesOperativas();
			$arrayUsuarios = $this->catalogoUsuarios();
			$this->buffer .= "<br><div id='resAsignacion'></div>
					<table width='95%' class='table tablesorter table-bordered' align='center' id='MyTableActividades'>
					<thead><tr>" . $this->cabeceras () . "</tr></thead><tbody>";
			$contador = 1;
			if ($this->session ['page'] <= 1)
				$contadorRen = 1;
			else
				$contadorRen = $this->session ['page'] + 1;
			$varTemporal = $respuestaC = "";
			foreach ( $resultados as $id => $resul ) {
				$rand = rand ( 1, 99999999999999 );
				$class = "";
				if ($contador % 2 == 0)
					$class = "active";
				$varTemporal = $resul ['id'];
				$this->buffer .= "
						<tr class=' $class alturaComponentesA'>
							<td class='tdleft'>".$contadorRen."</td>
							<td class='tdleft'>".$arrayAreas[$resul['unidadResponsable_id']]."</td>
							<td class='tdleft'>".$arrayProgramas[$resul['programa_id']]."</td>
							<td class='tdleft'>".$resul['proyecto']."</td>	
							<td class='tdleft'>".$arrayUsuarios[$resul['userId']]."</td>
							<td class='tdcenter'><input type='checkbox' name='checkbox' id='".$varTemporal."' value='".$varTemporal."' class='asignacion'></td></tr>";
				$contador ++;
				$contadorRen ++;
			}
			$this->buffer .= "</body></table><br>
					<table class='tableSinbordes' align='center' width='100%'>
					<tr>
						<td class='tdleft' width='20%'>Total: " . $no_registros . "</td>
						<td class='tdleft'>".USERASIGNACION." ".$this->comboUsuarios()."</td>
						<td class='tdleft' width='20%'>
						<input type='button' name='asignaProyecto' id='asignaProyecto' class='btn btn-success btn-lg' value='" . ASIGNAPROYECTO . "'>
						</td>
					</tr></table>";
		}
		$this->buffer .= "</div></div>"; 
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