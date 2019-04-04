<?php
class InsertaProyectos extends Comunes {
    var $db;
    var $data;
    var $path;
    var $session;
    var $opcion;
    var $buffer;
    var $idProyecto;
    var $arrayEstatus;
        
    function __construct($db,$data,$session,$path,$pages){
        $this->db     = $db;
        $this->data   = $data;
        $this->path   = $path;
        $this->session= $session;
        $this->idProyecto = 0;
        $this->arrayEstatus = array('AltaProyecto' => 1,'AltaActividad' => 2,'AltaMeta' => 3,'AltaAvance' => 4,
        		'ActualizaProyecto' => 5,'ActualizaActividad' => 6,'ActualizaMeta' => 7,'ActualizaAvance' => 8,
        		'EliminaProyecto' => 9,'EliminaActividad' => 10,'RestauraProyecto' => 11,'RestauraActividad' => 12);
        $this->opcion = $this->data['opc'] + 0;
        $this->tabla  = "";
        switch($this->opcion){
        	case 1:
        		$this->insertaProyecto();
        		break;
        	case 2:
        		$this->insertaActividad();
        		break;
        	case 3:
        		$this->actualizaProyecto();
        		break;
        	case 4:
        		$this->actualizaActividadesProyecto();
        		break;
        	case 5:
        		$this->eliminaProyecto();
        		break;
        	case 6:
        		$this->eliminaActividadProyecto();
        		break;	
        	case 7:
        		$this->insertaMetas();
        		break;
        	case 8:
        		$this->insertaAvances();
        		break;
        		
        	
        }
    }
    
    /**
     * Funcion que inserta los avances en BD
     */
    function insertaAvances(){
    	$this->buffer=0;
    	if(count($this->data) > 0){
    		$this->buffer = $this->altaAvanceCompleto($this->data,$this->session);
    	}
    }
    
    /**
     * Funcion que inserta las metas en BD
     */
    function insertaMetas(){
    	$this->buffer=0;
    	if(count($this->data) > 0){
    		$this->buffer = $this->altaMetaCompleto($this->data,$this->session);
    	}
    }
    
    /**
     * Funcion que inserta un proyecto en el catalogo desde el alta de proyectos
     */
    function insertaProyecto(){
    	$this->buffer=0;
    	if(count($this->data) > 0){
    		$this->data['inputNombre'] = $this->limpiaCadenas($this->data['inputNombre']);
    		$this->data['descripcion'] = $this->limpiaCadenas($this->data['descripcion']);
    		$this->data['resultados']  = $this->limpiaCadenas($this->data['resultados']);
    		$this->buffer = $this->altaProyectoCompleto($this->data,$this->session);
    	}
    }
    
    /**
    * Funcion que actualiza un proyecto en el catalogo desde el alta de proyectos
    */
    function actualizaProyecto(){
    	$this->buffer=0;
    	if(count($this->data) > 0){
    		$this->data['inputNombre'] = $this->limpiaCadenas($this->data['inputNombre']);
    		$this->data['descripcion'] = $this->limpiaCadenas($this->data['descripcion']);
    		$this->data['resultados']  = $this->limpiaCadenas($this->data['resultados']);
    		$this->buffer = $this->actualizaProyectoCompleto($this->data,$this->session);
    	}
    }
	
    /**
     * Metodo que elimina un proyecto
     */
    function eliminaProyecto(){
    	$this->buffer=0;
    	if(count($this->data) > 0){
    		$this->buffer = $this->eliminaProyectoCompleto($this->data,$this->session);
    	}
    }
    /**
     * Metodo que inserta una actividad al proyecto
     */
    function insertaActividad(){
    	$this->buffer=0;
    	if(count($this->data) > 0){
    		$this->data['actividad']    = $this->limpiaCadenas($this->data['actividad']);
    		$this->data['observacion']  = $this->limpiaCadenas($this->data['observacion']);
    		$this->idProyecto = $this->data['idProyecto'] + 0;
    		$this->buffer     = $this->altaActividadCompleto($this->data,$this->session);
    	}
    }
    
    /**
     * Metodo que actualiza una actividad del proyecto
     */
    function actualizaActividadesProyecto(){
    	$this->buffer=0;
    	$tmp=array();
    	if(count($this->data) > 0){
    		$tmp=explode('-',$this->data['idProyecto']);
    		$this->data['idProyecto']=$tmp[0];
    		$this->data['actividad']    = $this->limpiaCadenas($this->data['actividad']);
    		$this->data['observacion']  = $this->limpiaCadenas($this->data['observacion']);
    		$this->idProyecto = $this->data['idProyecto'] + 0;    		
    		$this->buffer     = $this->actualizactividadCompleto($this->data,$this->session);
    	}
    }
    /**
     * Metodo que elimina una actividad del proyecto
     */
    function eliminaActividadProyecto(){
    	$this->buffer=0;
    	if(count($this->data) > 0){
    		$this->buffer = $this->eliminaActividadCompleto($this->data,$this->session);
    	}
    }
    
    function obtenIdProyecto(){
    	return $this->idProyecto;
    }
	function obtenBuffer(){
		return $this->buffer;	
	}
}
?>