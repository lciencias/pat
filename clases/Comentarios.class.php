<?php
class Comentarios  extends Comunes{
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
        $this->tabla  = "proyectos_actividades_comentarios";
        $this->campo  = "id";
        $this->nmBoton="guardarComentario";
        $this->arrayEstatus = array('Alta Comentario' => 1, 'Actualiza Comentario' => 2);
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
                	if( ($this->data['idtrimestre'] + 0) == 0)
                    	$this->formato();	
                    else
                    	$this->formatoAvance();
                    break;
                case 2:
                	if( ($this->data['idtrimestre'] + 0) == 0)
                			$this->inserta();
                		else 
                			$this->insertaAvance();
                	break;
				default:
					if( ($this->data['idtrimestre'] + 0) == 0)
                    	$this->formato();
                    else 
                    	$this->formatoAvance();
                    break;          
            }
        }
    }
    
    function insertaAvance(){
    	$this->buffer=0;
    	$this->data['content'] = $this->limpiaCadenas($this->data['content']);
    	if($this->data['idproyecto'] > 0 && $this->data['idactividad'] > 0 && $this->data['idtrimestre'] > 0)  {
    		$fecha = date("Y-m-d H:i:s");
    		$sql="SELECT id FROM proyectos_avances_comentarios WHERE proyecto_id='".$this->data['idproyecto']."' AND actividad_id='".$this->data['idactividad']."' AND trimestre_id='".$this->data['idtrimestre']."' LIMIT 1;";
    		$res=$this->db->sql_query($sql) or die($this->cadena_error);
    		if($this->db->sql_numrows($res) == 0){
    			$ins="INSERT INTO proyectos_avances_comentarios (user_id,proyecto_id,actividad_id,trimestre_id,comentarios,fecha_alta)
   					  VALUES ('".$this->session['userId']."','".$this->data['idproyecto']."','".$this->data['idactividad']."','".$this->data['idtrimestre']."','".$this->data['content']."','".$fecha."');";
    			$resins=$this->db->sql_query($ins) or die($this->cadena_error);
    			if($resins){
    				$this->buffer=$this->db->sql_nextid();
    				$this->insertaBitacoraComentariosAvances($this->data,$this->session,$this->data['idproyecto'],$this->data['idactividad'],$this->data['idtrimestre'],$this->buffer,$this->arrayEstatus['Alta Comentario']);
    			}
    		}
    		else{
    			$upd="UPDATE  proyectos_avances_comentarios SET comentarios='".$this->data['content']."'
   					  WHERE proyecto_id='".$this->data['idproyecto']."' AND actividad_id='".$this->data['idactividad']."'
   					  		AND trimestre_id='".$this->data['idtrimestre']."'
   					  LIMIT 1;";
    			$resins=$this->db->sql_query($upd) or die($this->cadena_error);
    			if($resins){
    				$this->buffer=$this->data['idproyecto'];
    				$this->insertaBitacoraComentariosAvances($this->data,$this->session,$this->data['idproyecto'],$this->data['idactividad'],$this->data['idtrimestre'],$this->buffer,$this->arrayEstatus['Actualiza Comentario']);
    			}
    		}
    	}    	
    }
    
   function inserta(){
   		$this->buffer=0;
   		$this->data['content'] = $this->limpiaCadenas($this->data['content']);
		if($this->data['idproyecto'] > 0 && $this->data['idactividad'] > 0){
			$fecha = date("Y-m-d H:i:s");
   			$sql="SELECT id FROM proyectos_actividades_comentarios WHERE proyecto_id='".$this->data['idproyecto']."' AND actividad_id='".$this->data['idactividad']."' LIMIT 1;";
   			$res=$this->db->sql_query($sql)  or die($this->cadena_error);
   			if($this->db->sql_numrows($res) == 0){
   				$ins="INSERT INTO proyectos_actividades_comentarios (user_id,proyecto_id,actividad_id,comentarios,fecha_alta)
   					  VALUES ('".$this->session['userId']."','".$this->data['idproyecto']."','".$this->data['idactividad']."','".$this->data['content']."','".$fecha."');";
   				$resins=$this->db->sql_query($ins) or die($this->cadena_error);
   				if($resins){
   					$this->buffer=$this->db->sql_nextid();
   					$this->insertaBitacoraComentarios($this->data,$this->session,$this->data['idproyecto'],$this->data['idactividad'],$this->buffer,$this->arrayEstatus['Alta Comentario']);
   				}
   			}
   			else{
   				$upd="UPDATE  proyectos_actividades_comentarios SET comentarios='".$this->data['content']."'
   					  WHERE proyecto_id='".$this->data['idproyecto']."' AND actividad_id='".$this->data['idactividad']."' 
   					  LIMIT 1;";
   				$resins=$this->db->sql_query($upd) or die($this->cadena_error);
   				if($resins){
   					$this->buffer=$this->data['idproyecto'];
   					$this->insertaBitacoraComentarios($this->data,$this->session,$this->data['idproyecto'],$this->data['idactividad'],$this->buffer,$this->arrayEstatus['Actualiza Comentario']);   					
   				}
   			}
   		}
   }
    
    function formato(){
    	$this->buffer = $filtro = "";
    	if($this->data['idproyecto'] > 0 && $this->data['idactividad'] >= 0){
    		if($this->data['idactividad'] > 0){
    			$filtro .= " AND actividad_id='".$this->data['idactividad']."' ";
    		}
    		else{
    			$filtro .= " AND actividad_id ='0' ";
    		}
    		$sql="SELECT comentarios FROM proyectos_actividades_comentarios
    			  WHERE proyecto_id='".$this->data['idproyecto']."' 
    			  ORDER BY fecha_alta;";
    		
    		$res=$this->db->sql_query($sql) or die($this->cadena_error);
    		if($this->db->sql_numrows($res) > 0){
    			while(list($texto) = $this->db->sql_fetchrow($res)){
    				$this->buffer.=$texto."";
    			}
    		}
    	}
    }
    function formatoAvance(){
    	$this->buffer="";
    	if($this->data['idproyecto'] > 0 && $this->data['idactividad'] > 0 && $this->data['idtrimestre'] >0){
    		$sql="SELECT comentarios FROM proyectos_avances_comentarios
    			  WHERE proyecto_id='".$this->data['idproyecto']."' AND actividad_id='".$this->data['idactividad']."'
    			  AND trimestre_id='".$this->data['idtrimestre']."'  LIMIT 1;";
    		$res=$this->db->sql_query($sql)  or die($this->cadena_error);
    		if($this->db->sql_numrows($res) > 0){
    			list($this->buffer) = $this->db->sql_fetchrow($res);
    		}
    	}
    }
   
    
    function obtenBuffer(){
        return utf8_encode($this->buffer);
    }
    
}
?>