<?php
class ValidacionesAvances extends Comunes{
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
        $this->nmBoton="guardarValidacion";
        $this->arrayEstatus = array('Validar' => 1, 'Actualiza Validacion' => 2);
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
		if($this->data['idproyecto'] > 0)
		{
			$fecha = date("Y-m-d H:i:s");
			$sql="SELECT estatus_avance_entrega FROM proyectos_acciones WHERE id='".$this->data['idproyecto']."' limit 1;";
			$res= $this->db->sql_query($sql) or die($this->cadena_error);
			$entregaactual=2;
			if($this->db->sql_numrows($res)>0){
				list($entregaactual) = $this->db->sql_fetchrow($res);
			}			
			if($this->data['aprobado'] == 1){
				$statusFrom = $this->data['aprobado'];
				switch($entregaactual)
				{
					case 2:
						$statusTo   = 4;
						break;
					case 5:
						$statusTo   = 7;
						break;
					case 8:
						$statusTo   = 10;
						break;						
				}				 
			}
			if($this->data['aprobado'] == 2){
				$statusFrom = $this->data['aprobado'];
				switch($entregaactual)
				{
					case 2:
						$statusTo   = 3;
						break;
					case 5:
						$statusTo   = 6;
						break;
					case 8:
						$statusTo   = 9;
						break;
				}
			}
			$ins="INSERT INTO proyectos_validaciones_avances_comentarios (proyecto_id,trimestre_id,comentarios,aprobado,fecha_alta)
				  VALUES ('".$this->data['idproyecto']."','".$this->data['idtrimestre']."','".$this->data['content']."','".$this->data['aprobado']."','".$fecha."');";
			$resins=$this->db->sql_query($ins)  or die($this->cadena_error);
			if($resins){
				$this->buffer=$this->db->sql_nextid();
				$update = "UPDATE proyectos_acciones set estatus_avance_entrega='".$statusTo."' WHERE id='".$this->data['idproyecto']."' limit 1;";
				$resupd=$this->db->sql_query($update)  or die($this->cadena_error);
				$ins="INSERT INTO log_estatus_avances (user_id,proyecto_id,trimestre_id,ip,estatus_from,estatus_to) VALUES ('".$this->session['userId']."','".$this->data['idproyecto']."','".$this->data['idtrimestre']."','".$this->session['ip']."','".$entregaactual."','".$statusTo."');";
				$this->db->sql_query($ins) or die($this->cadena_error);				
				$this->insertaBitacoraComentariosAvances($this->data,$this->session,$this->data['idproyecto'],0,$this->data['idtrimestre'],$this->buffer,$this->arrayEstatus['Validar']);
   			}
   		}
   }
    
    function formato(){
    	$this->buffer="";
    	if($this->data['idproyecto'] > 0){
    		$sql="SELECT comentarios FROM proyectos_validaciones_avances_comentarios 
    			  WHERE proyecto_id='".$this->data['idproyecto']."'  AND trimestre_id ='".$this->data['idtrimestre']."' order by id desc
   				  LIMIT 1;";
    		$res=$this->db->sql_query($sql) or die("error");
    		if($this->db->sql_numrows($res) > 0){
    			list($this->buffer) = $this->db->sql_fetchrow($res);
    		}
    	}
    }
    
    function obtenBuffer(){
        return $this->buffer;
    }
}
?>