<?php
class Vistas  extends ComunesEstadisticas {
    var $db;
    var $data;
    var $path;
    var $session;
    var $opc;
    var $cadenaFiltros;
    var $buffer;
    var $tabla;
    var $cadena_error;
    var $campo;
    var $catalogoId;
    
    function __construct($db, $data, $session, $server, $path) {
        $this->db     = $db;
        $this->data   = $data;
        $this->path   = $path;
        $this->server = $server;
        $this->session= $session;
        $this->tabla  = "cat_tablas";
        $this->buffer = "";
        $this->campo  = "id";
        $this->catalogoId=17;
        $this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
        $this->opc    = $this->data['opc'] + 0;
        switch($this->opc){
                case 1:
                    $this->buffer=$this->asignaDefault();	
                    break;
        }
    }
    
    
    function asignaDefault(){
        if((int) $this->data['id']>0){
        	$sql =" UPDATE ".$this->tabla." SET defa = '0';";
        	$this->db->sql_query($sql) or die($this->cadena_error);
        	$sql =" UPDATE ".$this->tabla." SET defa = '1' WHERE id='".$this->data['id']."';";
        	$this->db->sql_query($sql) or die($this->cadena_error);
        	$this->buffer = 1;
        }
    }
        
    function obtenBuffer(){
        return $this->buffer;
    }    
}
?>