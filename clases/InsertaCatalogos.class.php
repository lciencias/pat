<?php
class InsertaCatalogos extends Comunes {
    var $db;
    var $data;
    var $path;
    var $session;
    var $opcion;
    var $buffer;
        
    function __construct($db,$data,$session,$path,$pages){
        $this->db     = $db;
        $this->data   = $data;
        $this->path   = $path;
        $this->server = $server;
        $this->session= $session;
        $this->opcion = $this->data['opcion'] + 0;
        $this->tabla  = "";
        switch($this->opcion){
        	case 1:
        		$this->insertaProyecto();
        		break;
        	case 2:
        		$this->insertaUnidadOperativa();
        		break;
        	case 3:
        		$this->insertaResponsableUnidadOperativa();
        		break;
        	case 4:
        		$this->insertaMetodoParticipacion();
        		break;
        	case 5:
        		switch($this->data['tipo']){
        			case 1:
        				$this->actualizaAyudaProyecto();
        				break;
        			case 2:
        				$this->actualizaAyudaActividad();
        				break;
        			case 3:
        				$this->actualizaAyudaPonderacion();
        				break;
        			case 4:
        				$this->actualizaAyudaTipoActividad();
        				break;
        				
        		}
        		break;
        }
    }
    
    /**
     * Funcion que inserta un proyecto en el catalogo desde el alta de proyectos
     */
    function insertaProyecto(){
    	$this->buffer=0;
    	if( ( $this->data['idarea'] > 0) && ($this->data['idprograma'] > 0) && (trim($this->data['inputNombre']))!= ""){
    		$this->data['inputNombre'] = $this->limpiaCadenas($this->data['inputNombre']);
    		$this->buffer=$this->altaProyecto($this->data);
    	}
    }
	
    /**
     * Funcion que inserta una Unidad Operativa en el catalogo desde el alta de proyectos
     */
	function insertaUnidadOperativa(){
		$this->buffer=0;
		if( ( $this->data['idarea'] > 0) && ($this->data['idprograma'] > 0) && (trim($this->data['inputNombre']))!= ""){
			$this->data['inputNombre'] = $this->limpiaCadenas($this->data['inputNombre']);
			$this->buffer=$this->altaUnidadOperativa($this->data);
		}
	}
	
	/**
	 * Funcion que inserta un responsable de una Unidad Operativa en el catalogo desde el alta de proyectos
	 */
	function insertaResponsableUnidadOperativa(){
		$this->buffer=0;
		if( ( $this->data['idunidadoperativa'] > 0) && (trim($this->data['inputNombre']))!= ""){
			$this->data['inputNombre'] = $this->limpiaCadenas($this->data['inputNombre']);
			$this->buffer=$this->altaUnidadOperativaResponsable($this->data);
		}
	}
	
	/**
	 * Funcion que inserta un metodo de participacion en el catalogo desde el alta de proyectos
	 */
	function insertaMetodoParticipacion(){
		$this->buffer=0;
	   	if( ( $this->data['idarea'] > 0) && ($this->data['idprograma'] > 0) && (trim($this->data['inputNombre']))!= ""){
    		$this->data['inputNombre'] = $this->limpiaCadenas($this->data['inputNombre']);
    		$this->buffer=$this->altaMetodosP($this->data);
		}
	}
	
	function obtenBuffer(){
		return $this->buffer;	
	}
}
?>