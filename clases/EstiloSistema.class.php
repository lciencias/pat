<?php
class EstiloSistema extends Comunes{
	var $db;
	var $data;
	var $session;
	var $server;
	var $path;
	var $buffer;
	var $filtro;
	var $tabla;
	var $results;
	var $headers;
	var $widths;
	var $regs;
	var $cadena_error;
	
	function __construct($db, $data, $session, $server, $path) {
		$this->db      = $db;
		$this->data    = $data;
		$this->path    = $path;
		$this->server  = $server;
		$this->session = $session;
		$this->buffer  = $this->filtro = $this->tabla = "";
		$this->cadena_error="";
		$this->results = $this->headers = array();
		$this->regs    = $this->widths = 0;
		$this->recuperaEstilo();
		if(count($this->results) > 0){
			$this->generaEstilos();
			$this->generaForma();
		}
	}
	
	function generaForma(){
		$this->buffer="
			<div class='panel panel-danger spancing'>
				<div class='panel-heading titulosBlanco'>
					<div class='tdleft titulosBlanco columna1'><span class='titulosBlanco'>".ESTILO."</span></div>
				</div>
  				<div class='panel-body'>
					<table width='60%' align='center' border='0' class='table-striped'>
						<tr><td colspan='3' class='tdcenter tamano seleccionaEstilos'><br>".USUARIO.":&nbsp;&nbsp;".$this->results[1]."<br><br></td></tr>
						".$this->generaEstilos()."
					</table>
					<div class='tdcenter'><span id='resultado'></span><br></div>
				</div>
			</div><br><br><br>";
	}
	
	function generaEstilos(){
		$buf=$tmp="";
		$sql="SELECT id,nombre,color,style FROM cat_estilos where active='1' ORDER BY nombre;";
		$res=$this->db->sql_query($sql) or die($this->cadena_error);
		if($this->db->sql_numrows($res) > 0)
		{
			while(list($id,$nm,$color,$style) = $this->db->sql_fetchrow($res))
			{
				$tmp="";
				if($id == $this->results[2]){
					$tmp=" checked ";
				}
				$buf.=" 
					<tr class='altotitulo'>
						<td width='40%' class='tdcenter' style='background-color:".$color.";'><span style='color:#ffffff'>".$nm."</span></td>						
						<td width='20%' class='tdcenter' ><input type='radio' name='estilo' id='".$id."-".$style."' value='".$id."' ".$tmp." class='seleccionaEstilos'></td>
						<td width='30%' class='tdcenter' ><span id='res-".$id."'></span></td>								
					</tr>";
			}
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
	

	
	function recuperaEstilo(){
		$this->results = array();
		if($this->session['userId'] > 0){
			$sql = " SELECT user_id,user_nombre,estilo_id FROM cat_usuarios WHERE user_id='".$this->session['userId']."' limit 1;";		
			$res = $this->db->sql_query($sql) or die("eroro:  ".$sql);
			if($this->db->sql_numrows($res)>0){
				list($id,$nombre,$style) = $this->db->sql_fetchrow($res);
				$this->results = array($id,$nombre,$style);
			}
		}
	}
	function obtenBuffer() {
		return $this->buffer;
	}
}
?>