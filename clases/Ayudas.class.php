<?php
class Ayudas extends Comunes {
	var $db;
	var $data;
	var $session;
	var $path;
	
	var $buffer;
	var $id;
	
	function __construct($db, $data, $session, $path) {
		$this->db   = $db;
		$this->data = $data;
		$this->path = $path;
		$this->buffer = "";
		$this->id = $this->data ['id'];
		$this->buscaAyuda();
	}
	
	/**
	 * Metodo que va a la base de datos y hace la busqueda
	 */
	function buscaAyuda(){
		if(trim($this->id) != "")
			$this->buffer=$this->buscaLeyendaProyecto($this->id);
	}
	
	/**
	 * Metodo que regresa la informacion pintada en el navegador
	 *
	 * @return string variable de instancia $this->buffer
	 */
	function obtenBuffer() {
		return $this->buffer;
	}
}