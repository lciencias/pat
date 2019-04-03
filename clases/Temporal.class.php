<?php
class Temporal{
	var $db;
	var $data;
	var $buffer;
	var $cadena_error;
	
	function __construct($db,$data){
		$this->db     = $db;
		$this->data   = $data;
		$this->buffer ="";
		$this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
		switch ($this->data['opcion']){
			case 1:
				$this->inserta();
				break;
			case 2:
				$this->elimina();
		}
	}
	
	function inserta(){
		$sql="SELECT proyecto_id FROM temporal 
			  WHERE proyecto_id='".$this->data['random']."' AND unidad_responsable_id='".$this->data['areaId']."';";	
		$res=$this->db->sql_query($sql) or ($this->cadena_error);
		if($this->db->sql_numrows($res) > 0){
			
		}
	}
	
	function elimina(){
		
	}
}