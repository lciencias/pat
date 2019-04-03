<?php
class RegresaArchivos{
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
        $this->session= $session;
        $this->tabla  = "proyectos_avances_adjuntos";
        $this->buffer = "";
        $this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
        $this->opc    = $this->data['opc'] + 0;
        $this->cadenaFiltros = "";
		switch($this->opc)
        {
        	case 1:
            	$this->consulta();	
                break;
            default:
            	$this->consulta();
                break;          
        }
    }
    
    function consulta(){
    	$this->buffer .= " Sin archivos ";
    	if($this->data['idproyecto'] > 0 && $this->data['idactividad'] > 0){
            $sql="SELECT trimestre_id,path,archivo,path_web FROM ". $this->tabla." 
                  WHERE proyecto_id='".$this->data['idproyecto']."' AND actividad_id='".$this->data['idactividad']."' 
    		ORDER BY trimestre_id asc;";
            $res=$this->db->sql_query($sql) or die("error");
            if($this->db->sql_numrows($res) > 0){
            	$this->buffer="<table class='table tableborder' >";
                while(list($trimestre_id,$path_sis,$archivo,$path_web) = $this->db->sql_fetchrow($res)){
                    $this->buffer .= "<tr><td width='20%'>Trimestre: ".$trimestre_id."</td>
                    					  <td class='tdleft'><a href='".$path_web."' target='_blank'>".$archivo."</td></tr>";
                }
                $this->buffer .= "</table>";
            }            
    	}            	
    }
    
    function obtenBuffer(){
        return utf8_encode($this->buffer);
    }
    
}
?>