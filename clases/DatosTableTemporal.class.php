<?php
class DatosTableTemporal extends ComunesEstadisticas {
    var $db;
    var $data;
    var $path;
    var $session;
    var $pages;
    var $opc;
    var $cadenaFiltros;
    var $buffer;
    var $tabla;
    var $cadena_error;
    var $array_datos;
    var $campo;
    var $catalogoId;
    var $folio;
    
    function __construct($db, $data, $session, $server, $path, $pages) {
        $this->db     = $db;
        $this->data   = $data;
        $this->path   = $path;
        $this->server = $server;
        $this->session= $session;
        $this->pages  = $pages;
        $this->tabla  = "";
        $this->array_datos=array();
        $this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
        $this->opc    = $this->data['opc'] + 0;
        $this->cadenaFiltros = "";
        $this->folio = 0;
        if((int) $this->opc > 0 && (int) $this->data['id'] > 0  && (int) $this->data['idTable'] > 0){
        	$this->regresaNombreTabla($this->data['idTable']);
	        switch($this->opc){
				case 1:
	            	$this->array_datos = $this->regresaDatosTmp();
	            	
	                break;           
	        }
        }
    }
    
    function obtenBuffer(){
        return $this->buffer;
    }
    
    function obtenFolio(){
    	return $this->folio;
    }
    
    function obtenDatos(){    	
        return json_encode($this->array_datos);
    }
}
?>