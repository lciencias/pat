<?php
class ActualizaAreasProgramas{
	var $db;
	var $session;
	var $arrayAreas;
	var $arrayProgramas;
	var $cadena_error;
	
	function __construct($db,$session){
		$this->db = $db;
		$this->session = $session;
		$this->arrayAreas     = array();
		$this->arrayProgramas = array();
		$this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
		if($this->session['userId'] > 0){
			$this->actualizaDatos();
		}
	}
	
	function actualizaDatos(){
		$arrayUnicoArea  = $arrayUnicoPrograma = array();
		$sql="SELECT area_id,programa_id FROM cat_permisos_areas where usuario_id='".$this->session['userId']."' ORDER BY area_id,programa_id;";
		$res=$this->db->sql_query($sql) or die($this->cadena_error);
		if($this->db->sql_numrows($res) > 0){
			while(list($area_id,$programa_id) = $this->db->sql_fetchrow($res)){
				if(!in_array($area_id,$arrayUnicoArea)){
					$this->arrayAreas[] = $area_id;
					$arrayUnicoArea[]   = $area_id;
				}
				if(!in_array($programa_id,$arrayUnicoPrograma)){
					$this->arrayProgramas[] = $programa_id;
					$arrayUnicoPrograma[]  = $programa_id;
				}
			}
		}
	}
	
	function obtenAreas(){
		return implode(',',$this->arrayAreas);
	}
	
	function obtenProgramas(){
		return implode(',',$this->arrayProgramas);
	}
	
}
?>