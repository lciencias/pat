<?php
class ComentariosAvancesAprobacion  extends Comunes{
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
        $this->tabla  = "proyectos_validaciones_avances_comentarios";
        $this->campo  = "id";
        $this->nmBoton="guardarComentarioAvanceValidacion";
        $this->arrayEstatus = array('Alta Comentario Avance' => 1, 'Actualiza Comentario Avance' => 2);
        $this->array_datos=array();
        $this->catalogoId=20;
        $this->buffer=0;
        $this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
        $this->opc    = $this->data['opc'] + 0;
        $this->cadenaFiltros = "";
        if( trim($this->session['areas']) != "")
        {
            switch($this->opc)
            {
                case 1:
                    $this->formato();	
                    break;
                case 2:
                	$this->inserta();
                	break;
				default:
                    $this->formato();
                    break;          
            }
        }
    }
    
   function inserta(){
   		$this->buffer=0;
   		$this->data['content'] = $this->limpiaCadenas($this->data['content']);
		if($this->data['idproyecto'] > 0 && $this->data['idactividad'] > 0){
			$fecha = date("Y-m-d H:i:s");
   			$sql="SELECT id FROM ". $this->tabla." WHERE proyecto_id='".$this->data['idproyecto']."' AND actividad_id='".$this->data['idactividad']."' AND trimestre_id='".$this->data['idtrimestre']."'  LIMIT 1;";
   			$res=$this->db->sql_query($sql) or die("error");
   			if($this->db->sql_numrows($res) == 0){
   				$ins="INSERT INTO ". $this->tabla." (proyecto_id,actividad_id,comentarios,fecha_alta,trimestre_id)
   					  VALUES ('".$this->data['idproyecto']."','".$this->data['idactividad']."','".$this->data['content']."','".$fecha."','".$this->data['idtrimestre']."');";
   				$resins=$this->db->sql_query($ins);
   				if($resins){
   					$this->buffer=$this->db->sql_nextid();
   					$this->insertaBitacoraComentariosAvances($this->data,$this->session,$this->data['idproyecto'],$this->data['idactividad'],$this->data['idtrimestre'],$this->buffer,$this->arrayEstatus['Alta Comentario']);
   				}
   			}
   			else{
   				$upd="UPDATE ". $this->tabla." SET comentarios='".$this->data['content']."'
   					  WHERE proyecto_id='".$this->data['idproyecto']."' 
   					  		AND actividad_id='".$this->data['idactividad']."'
   					  		AND trimestre_id='".$this->data['idtrimestre']."'  
   					  LIMIT 1;";
   				$resins=$this->db->sql_query($upd);
   				if($resins){
   					$this->buffer=$this->data['idproyecto'];
   					$this->insertaBitacoraComentariosAvances($this->data,$this->session,$this->data['idproyecto'],$this->data['idactividad'],$this->data['idtrimestre'],$this->buffer,$this->arrayEstatus['Actualiza Comentario']);   					
   				}
   			}
   		}
   }
    
    function formato(){
    	$this->buffer="";
    	if($this->data['idproyecto'] > 0 && $this->data['idactividad'] > 0){
    		$sql="SELECT comentarios FROM ". $this->tabla." 
    			  WHERE proyecto_id='".$this->data['idproyecto']."' AND actividad_id='".$this->data['idactividad']."' 
    			  AND trimestre_id='".$this->data['idtrimestre']."' 
   				  order by fecha_alta desc;";
    		$res=$this->db->sql_query($sql) or die("error");
    		if($this->db->sql_numrows($res) > 0){
    			while(list($texto) = $this->db->sql_fetchrow($res))
    				$this->buffer.=$texto;
    		}
    	}
    }
    
    function obtenBuffer(){
    	return utf8_encode($this->buffer);
    }
    
}
?>