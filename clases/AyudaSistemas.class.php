<?php
class AyudaSistemas extends Comunes{
	var $db;
	var $data;
	var $session;
	var $server;
	var $path;
	var $buffer;
	var $pages;
	var $filtro;
	var $tabla;
	var $results;
	var $headers;
	var $widths;
	var $regs;
	var $cadena_error;
	
	function __construct($db, $data, $session, $server, $path, $pages) {
		$this->db      = $db;
		$this->data    = $data;
		$this->path    = $path;
		$this->server  = $server;
		$this->session = $session;
		$this->pages   = $pages;
		$this->buffer  = $this->filtro = $this->tabla = "";
		$this->cadena_error="";
		$this->results = $this->headers = array();
		$this->regs    = $this->widths = 0;
		$this->recuperaAyudas();
		if(count($this->results) > 0){
			$this->generaForma();
		}
	}
	
	function generaForma(){
		$this->buffer="
			<div class='panel panel-danger spancing'>
				<div class='panel-heading titulosBlanco'>
					<div class='tdleft titulosBlanco columna1'><span class='titulosBlanco'>".AYUDA."</span></div>
				</div>
  				<div class='panel-body'>
					<div role='tabpanel'>
					  <ul class='nav nav-tabs' role='tablist'>
					    <li role='presentation' class='active'>
							<a href='#home' aria-controls='home' role='tab' data-toggle='tab'>".PROYECTOS."</a>
						</li>
					    <li role='presentation'>
							<a href='#profile' aria-controls='profile' role='tab' data-toggle='tab'>".ACTIVIDADES."</a>
						</li>
					    <li role='presentation'>
							<a href='#ponderacion' aria-controls='profile' role='tab' data-toggle='tab'>".PONDERACION."</a>
						</li>
					    <li role='presentation'>
							<a href='#tipoActividad' aria-controls='profile' role='tab' data-toggle='tab'>".TIPOACT."</a>
						</li>									
					  </ul>
					  <div class='tab-content'>
					  	<div role='tabpanel' class='tab-pane active' id='home'>".$this->formatoProyectos()."</div>
						<div role='tabpanel' class='tab-pane' id='profile'>".$this->formatoActividades()."</div>
						<div role='tabpanel' class='tab-pane' id='ponderacion'>".$this->formatoPonderaciones()."</div>
						<div role='tabpanel' class='tab-pane' id='tipoActividad'>".$this->formatoTipoActividad()."</div>								
					  </div>
					</div>	
					<div class='tdcenter'><span id='resultado'></span><br></div>
				</div>
			</div><br><br><br>";
	}
	
	function formatoPonderaciones(){
		$buf="";
		$sql="SELECT id,nombre,descripcion FROM cat_ponderacion where active='1' ORDER BY id;";
		$res=$this->db->sql_query($sql) or die($this->cadena_error);
		if($this->db->sql_numrows($res) > 0)
		{
			$buf="<table width='90%' align='center' border='0' class='table-striped'>";
			while(list($id,$nm,$ds) = $this->db->sql_fetchrow($res))
			{
				$buf.=" <tr class='altotitulo'><td colspan='3' class='tamano'><b>".PONDERACION."</b></td></tr>
					<tr class='altotitulo'>
						<td width='15%' class='totales'>".TITULO."</td>
						<td width='75%'><span id='3t-".$id."'>".PONDERACION." ".$nm."</span></td>
						<td width='10%'>&nbsp;</td>
					</tr>
					<tr class='altotitulo'>
						<td class='totales'>".DESCRIPCION."</td>
						<td><span id='3c-".$id."'>".$ds."</span></td>
						<td><button class='btn btn-success actualizaAyuda' id='3-".$id."'><span class='glyphicon glyphicon-plus'></span></button></td>
					</tr>
					<tr class='altotitulo'>
						<td colspan='3' class='tdcenter success'>
							<span id='3r-".$id."'></span>
						</td>
					</tr>
					<tr class='altotitulo'><td colspan='3'><hr></td></tr>";
			}
			$buf.="</table>";
		}		
		return $buf;
	}
	
	function formatoTipoActividad(){
		$buf="";
		$sql="SELECT actividad_id,nombre,descripcion FROM cat_tipo_actividad where 1 ORDER BY actividad_id;";
		$res=$this->db->sql_query($sql) or die();
		if($this->db->sql_numrows($res) > 0)
		{
			$buf="<table width='90%' align='center' border='0' class='table-striped'>";
			while(list($id,$nm,$ds) = $this->db->sql_fetchrow($res))
			{
				$buf.=" <tr class='altotitulo'><td colspan='3' class='tamano'><b>".TIPOACT."</b></td></tr>
					<tr class='altotitulo'>
						<td width='15%' class='totales'>".TITULO."</td>
						<td width='75%'><span id='4t-".$id."'>".$nm."</span></td>
						<td width='10%'>&nbsp;</td>
					</tr>
					<tr class='altotitulo'>
						<td class='totales'>".DESCRIPCION."</td>
						<td><span id='4c-".$id."'>".$ds."</span></td>
						<td><button class='btn btn-success actualizaAyuda' id='4-".$id."'><span class='glyphicon glyphicon-plus'></span></button></td>
					</tr>
					<tr class='altotitulo'>
						<td colspan='3' class='tdcenter success'>
							<span id='4r-".$id."'></span>
						</td>
					</tr>
					<tr class='altotitulo'><td colspan='3'><hr></td></tr>";
			}
			$buf.="</table>";
		}		
		return $buf;		
	}
	
	function formatoProyectos(){
		$leyendas = array(1 => UNIDADOPERATIVA,2 => PROYECTO,3 => PONDERACION, 4 => DESCRIPCIONDELPROYECTO,
						  5 => RESULTADOSESPERADOS, 6 => PRESUPUESTO." ".OTORGADO, 7 => PRESUPUESTO." ".ESTIMADO,
						  8 => ENCOORDINACION, 9 => ESPECIFIQUE, 10 => METODO);
		
		$buf="<table width='90%' align='center' border='0' class='table-striped'>";
		foreach($leyendas as $ind => $titulo){
			if($this->results[$ind]['tipo'] == 1){
				$buf.=" <tr class='altotitulo'><td colspan='3' class='tamano'><b>".$titulo."</b></td></tr>
					<tr class='altotitulo'>
						<td width='15%' class='totales'>".TITULO."</td>
						<td width='75%'><span id='1t-".$this->results[$ind]['id']."'>".$this->results[$ind]['tit_ayuda']."</span></td>
						<td width='10%'>&nbsp;</td>
					</tr>
					<tr class='altotitulo'>
						<td class='totales'>".DESCRIPCION."</td>
						<td><span id='1c-".$this->results[$ind]['id']."'>".$this->results[$ind]['msg_ayuda']."</span></td>
						<td><button class='btn btn-success actualizaAyuda' id='1-".$this->results[$ind]['id']."'><span class='glyphicon glyphicon-plus'></span></button></td>
					</tr>
					<tr class='altotitulo'>
						<td colspan='3' class='tdcenter success'>
							<span id='1r-".$this->results[$ind]['id']."'></span>
						</td>
					</tr>
					<tr class='altotitulo'><td colspan='3'><hr></td></tr>";
			}					
		}
		$buf.="</table>";
		return $buf;
	}	

	
	function formatoActividades(){
		$leyendas = array(11 => AGREGUE,12 => UNIDADMEDIDA,13 => PONDERACION,14 => TIPOACT);		
		$buf="<table width='90%' align='center' border='0' class='table-striped'>";
		foreach($leyendas as $ind => $titulo){
			if($this->results[$ind]['tipo'] == 2){
				$buf.=" <tr class='altotitulo'><td colspan='3' class='tamano'><b>".$titulo."</b></td></tr>
					<tr class='altotitulo'>
						<td width='15%' class='totales'>".TITULO."</td>
						<td width='75%'><span id='2t-".$this->results[$ind]['id']."'>".$this->results[$ind]['tit_ayuda']."</span></td>
						<td width='10%'>&nbsp;</td>
					</tr>
					<tr class='altotitulo'>
						<td class='totales'>".DESCRIPCION."</td>
						<td><span id='2c-".$this->results[$ind]['id']."'>".$this->results[$ind]['msg_ayuda']."</span></td>
						<td><button class='btn btn-success actualizaAyuda' id='2-".$this->results[$ind]['id']."'><span class='glyphicon glyphicon-plus'></span></button></td>
					</tr>
					<tr class='altotitulo'>
						<td colspan='3' class='tdcenter success'>
							<span id='2r-".$this->results[$ind]['id']."'></span>
						</td>
					</tr>
					<tr class='altotitulo'><td colspan='3'><hr></td></tr>";
			}
		}
		$buf.="</table>";
		return $buf;
		
	}

	
	function recuperaAyudas(){
		$this->results = array();
		$sql = " SELECT id,id_ayuda,tit_ayuda,msg_ayuda,tipo FROM cat_ayuda_proyectos WHERE 1 ORDER BY id;";		
		$res = $this->db->sql_query($sql) or die("eroro:  ".$sql);
		if($this->db->sql_numrows($res)>0){
			while(list($id,$id_ayuda,$tit_ayuda,$msg_ayuda,$tipo) = $this->db->sql_fetchrow($res)){
				$this->results[$id]['id'] = $id;
				$this->results[$id]['id_ayuda']  = $id_ayuda;
				$this->results[$id]['tit_ayuda'] = $tit_ayuda;
				$this->results[$id]['msg_ayuda'] = $msg_ayuda;				
				$this->results[$id]['tipo'] = $tipo;
			}
		}
	}
	function obtenBuffer() {
		return $this->buffer;
	}
}
?>