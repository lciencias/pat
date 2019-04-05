<?php
class Logotipo{
	var $db;
	var $data;
	var $path_web;
	var $logo;
	var $buffer;
	var $folio;
	
	function __construct($db,$data,$path_web){
		$this->db   = $db;
		$this->data = $data;
		$this->path_web=$path_web;
		$this->logo = $this->buffer = "";
		$this->folio=0;
		if( ($this->data['opc']+ 0) == 0)
			$this->consultaLogotipo();
		if( ($this->data['opc']+ 0) == 1){
			$this->consultaLogotipo();
			$this->formatoLogotipo();
		}
		if( ($this->data['opc']+ 0) == 2){
			$this->actualizaLogotipo();
		}
	}
	
	function actualizaLogotipo(){
		if(trim($this->data['file'])!= ""){
			$upd="UPDATE cat_logo SET logotipo='".$this->data['file']."' where 1 limit 1;";
			if($this->db->sql_query($upd)){
				$this->folio=1;
				$this->buffer = $this->data['file'];
			}
		}
	}
	function formatoLogotipo(){
				$this->buffer="
			<div class='panel panel-danger spancing'>
				<div class='panel-heading titulosBlanco'>
					<div class='tdleft titulosBlanco columna1'><span class='titulosBlanco'>".LOGO."</span></div>
				</div>
  				<div class='panel-body'>
					<table width='60%' align='center' border='0' class='table-striped'>
						<tr>
							<td colspan='3' class='tdcenter'><br>
							<img src='".$this->path_web."imagenes/".$this->logo."' border='0' width='520px;'>
							<br>
							</td>
						</tr>
						<tr>
               				<td colspan='2' class='tdleft'><br><br><input id='fileToUpload' type='file' size='45' name='fileToUpload' >
								<img id='loading' src='".$this->path_web."imagenes/loading.gif' style='display:none;'>
               				</td>
               				<td><button class='btn btn-default btn-sm' id='buttonUpload' onclick=\"return ajaxFileUpload();\">Upload</button></td>									
						</tr>		
						<tr><td colspan='3' class='tdleft'><br><b>".ADJUNTOLOGO."</b></td></tr>
						<tr><td colspan='3' class='tdcenter'><span id='resultado'></span></td></tr>									
					</table>
					<div class='tdcenter'><span id='resultado'></span><br></div>
				</div>
			</div><br><br><br>";
		
	}
	
	function consultaLogotipo(){
		$sql="SELECT logotipo FROM cat_logo limit 1;";
		$res=$this->db->sql_query($sql) or die();
		if($this->db->sql_numrows($res)>0){
			list($this->logo) = $this->db->sql_fetchrow($res);
			
		}
	}
	
	function obtenLogotipo(){
		return $this->logo;
	}
	
	function obtenBuffer(){
		return $this->buffer;
	}
	
	function obtenExito(){
		return $this->folio;
	}
}