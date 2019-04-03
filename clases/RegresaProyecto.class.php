<?php
class RegresaProyecto
 extends Comunes{
    var $ddb;
    var $data;
    var $path;
    var $session;
    var $opc;
    var $cadenaFiltros;
    var $buffer;
    var $tabla;
    var $cadena_error;
    var $array_datos;
    var $campo;
    var $catalogoId;
    var $nmBoton;
    var $arrayEstatus;
    
    function __construct($db,$data,$session,$path){
        $this->db     = $db;
        $this->data   = $data;
        $this->path   = $path;
        $this->server = $server;
        $this->session= $session;
        $this->opc    = $this->data['opc'] + 0;
        $this->buffer=0;
        $this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
        $this->cadenaFiltros = "";
        if( trim($this->session['areas']) != "")
        {
            switch($this->opc)
            {
                case 1:
                	if($this->data['idtrimestre'] == 0)
                    	$this->regresaProyectoCoordinador();	
                    else 
                    	$this->regresaAvanceCoordinador();
                    break;
                case 2:
                	if($this->data['idtrimestre'] == 0)
                    	$this->regresaProyectoPlaneacion();
                	else 
                		$this->regresaAvancePlaneacion();
                	break;
            }
        }
    }
    
    /**
     * Metodo que actualiza en tabla de avances los estatus de los proyectos e inserta en logs
     * @param int Estatus Actual
     * @param int Estatus Cambia
     */
    
    function actualizaAvances($statusFrom,$statusTo){
    	/*$upd="UPDATE proyectos_actividades SET estatus_entrega='".$statusTo."'
    				  WHERE id_proyecto='".$this->data['idestatus']."' 
    				  		AND estatus_entrega='".$statusFrom."';";
    	$res=$this->db->sql_query($upd) or die($this->cadena_error);
    	
    	$upd="UPDATE proyectos_acciones SET estatus_entrega='".$statusTo."'
    				  WHERE id='".$this->data['idestatus']."' 
    				  AND estatus_entrega='".$statusFrom."';";
    	$res=$this->db->sql_query($upd) or die($this->cadena_error);
    	$this->buffer = $this->data['idproyecto'];
    	$this->insertaLogEstatus($statusFrom,$statusTo);*/
    }
    
    /**
     * Metodo que actualiza en tabla de proyectos los estatus de los proyectos e inserta en logs
     * @param int Estatus Actual
     * @param int Estatus Cambia
     */
    function actualizaProyectos($statusFrom,$statusTo){
    	$upd="UPDATE proyectos_actividades SET estatus_entrega='".$statusTo."'
    				  WHERE proyecto_id='".$this->data['idproyecto']."' 
    				  		AND estatus_entrega='".$statusFrom."';";
    	$res=$this->db->sql_query($upd) or die($this->cadena_error);
    	
    	$upd="UPDATE proyectos_acciones SET estatus_entrega='".$statusTo."'
    				  WHERE id='".$this->data['idproyecto']."' 
    				  AND estatus_entrega='".$statusFrom."';";
    	$res=$this->db->sql_query($upd) or die($this->cadena_error);
    	$this->buffer = $this->data['idproyecto'];
    	$this->insertaLogEstatus($statusFrom,$statusTo);
    }
    
    /**
     * Metodo que regresa el proyecto al estatus 6 rechazado por coordinador
     */    
    function regresaProyectoCoordinador(){
        $statusFrom = $statusTo   = 0;
    	$this->data['idactividad'] = 0;
    	if( $this->data['idproyecto'] > 0 && $this->data['idestatus'] > 0){    		
    		if($this->data['idestatus'] == 9){
    			$statusFrom = $this->data['idestatus'];
    			$statusTo   = 6;
    			$this->actualizaProyectos($statusFrom,$statusTo);    			
    		}
    	}    		
    }
    function regresaAvanceCoordinador(){
    	$statusFrom = $statusTo   = 0;
    	$this->data['idactividad'] = 0;
    	if( $this->data['idproyecto'] > 0 && $this->data['idestatus'] > 0  && $this->data['idtrimestre'] > 0 ){    		
    		if($this->data['idestatus'] == 9){
    			$statusFrom = $this->data['idestatus'];
    			$statusTo   = 6;
    			$this->actualizaAvances($statusFrom,$statusTo);    			
    		}
    	}
    }

    /**
	 * Metodo que regresa el proyecto al estatus 3 rechazado por enlace de planeacion
	 */
    function regresaProyectoPlaneacion(){
    	$statusFrom = $statusTo   = 0;
    	$this->data['idactividad'] = 0;
    	if( $this->data['idproyecto'] > 0 && $this->data['idestatus'] > 0){    		
    		if($this->data['idestatus'] == 6){
    			$statusFrom = $this->data['idestatus'];
    			$statusTo   = 3;
    			$this->actualizaProyectos($statusFrom,$statusTo);
    		}
    	}
    }
    
    function regresaAvancePlaneacion(){
        $statusFrom = $statusTo   = 0;
    	$this->data['idactividad'] = 0;
    	if( $this->data['idproyecto'] > 0 && $this->data['idestatus'] > 0  && $this->data['idtrimestre'] > 0 ){    		
    		if($this->data['idestatus'] == 6){
    			$statusFrom = $this->data['idestatus'];
    			$statusTo   = 3;
    			$this->actualizaAvances($statusFrom,$statusTo);    			
    		}
    	}
    	
    }
        
    function insertaLogEstatus($statusFrom,$statusTo){
    	$ins="INSERT INTO log_estatus (user_id,proyecto_id,actividad_id,ip,estatus_from,estatus_to)
    		  VALUES ('".$this->session['userId']."','".$this->data['idproyecto']."','".$this->data['idactividad']."',
    		  		  '".$this->session['ip']."','".$statusFrom."','".$statusTo."');";    	
    	$this->db->sql_query($ins) or die($this->cadena_error);
    }
        
    function obtenBuffer(){
        return $this->buffer;
    }
}
?>