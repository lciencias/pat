<?php
class Permisos extends Comunes{
    var $db;
    var $data;
    var $path;
    var $server;
    var $session;
    var $pages;
    var $opc;
    var $buffer;
    var $cadena_error;
	var $arrayRegreso;
	    
    function __construct($db,$data,$session,$server,$path,$pages){
        $this->db     = $db;
        $this->data   = $data;
        $this->path   = $path;
        $this->server = $server;
        $this->session= $session;
        $this->pages  = $pages;
        $this->arrayRegreso = array();
        $this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
        $this->opc    = $this->data['opc'];
        $this->cadenaFiltros = "";
        switch($this->opc)
        {
			case 1:
            	$this->actualizaPermisos();	
                break;
            case 2:
            	$this->actualizaPermisosPrograma();
            	break;
            case 3:
            	$this->actualizaPermisosAvance();
            	break;
            case 4:
            	$this->actualizaPermisosProgramaAvance();
            	break;
            case 5:
            	$this->consultaFechasAvancesPermisos();
            	break;
        }
    }
    
    function consultaFechasAvancesPermisos(){
    	$this->buffer="";
        if($this->data['areaId'] > 0){
    		$this->buffer=$this->consultaPermisosAvances();
    	}
    	
    }
    function actualizaPermisos(){
    	$this->buffer=0;
    	if($this->data['areaId'] > 0){
    		$this->eliminaPermisos();
    		$this->buffer=1;
    	}
    }
    
    function actualizaPermisosPrograma(){
    	$this->buffer=0;
    	if(trim($this->data['id'])!=""){
    		$tmp=explode("-",$this->data['id']);
    		$this->data['areaId']    = $tmp[1];
    		$this->data['programaId']= $tmp[2];
    		$this->eliminaPermisosPrograma();
    		$this->buffer=1;
    	}	 
    }
    
    function actualizaPermisosAvance(){
    	$this->buffer=0;
    	if($this->data['areaId'] > 0){
    		$this->eliminaPermisosAvance();
    		$this->arrayRegreso=$this->consultaPermisosAvances();
    		$this->buffer=1;
    	}
    }
        
    function actualizaPermisosProgramaAvance(){
    	$this->buffer=0;
    	if(trim($this->data['id'])!=""){
    		$tmp=explode("-",$this->data['id']);
    		$this->data['areaId']    = $tmp[1];
    		$this->data['programaId']= $tmp[2];
    		$this->eliminaPermisosProgramaAvance();
    		$this->arrayRegreso=$this->consultaPermisosAvances();
    		$this->buffer=1;
    	}
    }    
    function obtenArray(){
    	return $this->arrayRegreso;
    }
    
    function obtenBuffer(){
        return $this->buffer;
    }    
}
?>