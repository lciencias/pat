<?php
class ActualizaEstilo 
{
	var $db;
	var $data;
	var $session;
	var $server;
	var $path;
	var $opcion;
	var $buffer;
	function __construct($db, $data, $session, $server, $path) {
		$this->db = $db;
		$this->data = $data;
		$this->path = $path;
		$this->server = $server;
		$this->session = $session;
		$this->buffer = 0;
		$this->opcion = $this->data ['opcion'];
		switch ($this->opcion) {
			case 1 :
				$this->actualiza();
				break;
		}
	}
	
	function actualiza(){
		$this->buffer="";
		if( ($this->data['id'] > 0) && ($this->session['userId'] > 0)){
			$sql="UPDATE cat_usuarios SET estilo_id='".$this->data['id']."' WHERE user_id='".$this->session['userId']."' LIMIT 1;";
			if($this->db->sql_query($sql) or die($this->cadena_error)){
				$this->buffer =$this->data['id']; 			
			}
		}	
	}
	
	
	function obtenBuffer() {
		return $this->buffer;
	}
}
?>