<?php
class Validaciones extends Comunes{
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
        $this->tabla  = "proyectos_validaciones_comentarios";
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
                	if($this->data['idtrimestre'] == 0)
                    	$this->formato();	
                    else 
                    	$this->formatoAvance();
                    break;
                case 2:
                	if($this->data['idtrimestre'] == 0)
                		$this->inserta();
                	else 
                		$this->insertaAvance();
                	break;
                	
                case 3: //validacion de enlace de planeacion
                	if($this->data['idtrimestre'] == 0)
                		$this->insertaValidacionActividad();
                	else
                		$this->insertaAvanceActividad();
                	break;
                case 4: // validacion de coordinacion
                	if($this->data['idtrimestre'] == 0)
                		$this->insertaValidacionProyecto();
                	else
                		$this->insertaAvanceActividad();
                	break;
                case 5: // validacion de administrador
                	if($this->data['idtrimestre'] == 0)
                		$this->insertaValidacionAdministrador();
                	else
                		$this->insertaAvanceAdministrador();
                	break;
                case 6:
                	$this->insertaAvanceProyecto();
                	break;
				default:
                   if($this->data['idtrimestre']==0)
                    	$this->formato();	
                    else 
                    	$this->formatoAvance();
                    break;          
            }
        }
    }
    
    function insertaValidacionAdministrador(){    
    	$arrayProyectos = $arrayActividades = $tmp = $arrayTmp = array();	
    	if(trim($this->data['proyectosIds']) != ""){
    		$arrayTmp=explode('|',$this->data['proyectosIds']);
    		if(count($arrayTmp) > 0){
    			foreach($arrayTmp as $idTmp){
    				$tmp=explode('-',$idTmp);
    				if( (trim($tmp[0])!= "") && (trim($tmp[1])!= "")){ 
    					if(!in_array($tmp[0],$arrayActividades)){
    						$arrayActividades[]=$tmp[0];
    					}
    					if(!in_array($tmp[1],$arrayProyectos)){
	    					$arrayProyectos[]=$tmp[1];
    					}
    				}
    			}
    			$this->data['idproyecto'] = $arrayProyectos[0];
    			$this->data['aprobado']   = 1;
    			
    			$fecha = date("Y-m-d H:i:s");
    			$statusFrom = $this->regresaEstatus(2);
    			$statusTo   = $this->regresaEstatusActualiza($this->data['aprobado'],$statusFrom);
    			//actualizo estatus
    			if($statusTo > 0){
	    			$update = "UPDATE proyectos_actividades set estatus_entrega='".$statusTo."'
	    				   WHERE id IN (".implode(',',$arrayActividades).");";
	    			$resupd = $this->db->sql_query($update)  or die($this->cadena_error);
	    			$this->actualizaProyecto();
	    			if(count($arrayActividades)>0){
	    				foreach($arrayActividades as $id){
	    					$this->data['idactividad']= $id;
	    					$this->insertaLogEstatus($statusFrom,$statusTo);
	    				}
	    			}
	    			$this->buffer=$this->data['aprobado'];
    			}
    		}
    	}
    }
    
    
    function insertaAvanceAdministrador(){
    	
    }
    
    function regresaEstatusActualiza($opcion,$entregaactual){
    	$statusTo=1;
		switch($entregaactual){
    		case 2:{
    			if($opcion == 1)
    				$statusTo = 4;
    			else 
    				$statusTo = 3;
    			break;
    		}
    		case 3:{
    			if($opcion == 1)
    				$statusTo = 4;
    			else
    				$statusTo = 3;
    			break;
    		}
    		case 4:{
    			if($opcion == 1)
    				$statusTo = 4;
    			else
    				$statusTo = 3;
    			break;
    		}
    		case 5:{
    			if($opcion == 1)
	    			$statusTo = 7;
    			else 
    				$statusTo = 6;
    			break;
    		}
    		
    		case 8:{
    			if($opcion == 1)
    				$statusTo = 10;
    			else
    				$statusTo = 9;
    			break;
    		}
		}
		return $statusTo;
    }

    
    function regresaEstatus($opcion){
    	$entregaactual=2;   	
    	switch($opcion){
    		case 1:
 				$sql="SELECT estatus_entrega FROM proyectos_actividades 
 					  WHERE id='".$this->data['idactividad']."' 
 					  AND proyecto_id='".$this->data['idproyecto']."' 
 					  limit 1;";
 				break;
    		case 2:
    			$sql="SELECT estatus_entrega FROM proyectos_acciones
 					  WHERE id='".$this->data['idproyecto']."' limit 1;";    			 
 				break;
 				
    	}
    	$res= $this->db->sql_query($sql) or die($this->cadena_error);    	
    	if($this->db->sql_numrows($res) > 0){
    		list($entregaactual) = $this->db->sql_fetchrow($res);
    	}
    	return $entregaactual;
    }

    function regresaEstatusAvance($opcion){
    	$entregaactual=2;
    	$campo = "estatus_avance_entrega_t".$this->data['idtrimestre'];
    	$campob="estatus_avance_entrega";
    	if($this->data['idtrimestre']>1){
    		$campob="estatus_avance_entrega".$this->data['idtrimestre'];
    	}
    	switch($opcion){
    		case 1:
    			$sql="SELECT ".$campo." FROM proyectos_actividades
 					  WHERE id = '".$this->data['idactividad']."'
 					  AND proyecto_id  = '".$this->data['idproyecto']."' 					  
 					  limit 1;";
    			break;
    		case 2:
    			$sql="SELECT ".$campob." FROM proyectos_acciones
 					  WHERE id='".$this->data['idproyecto']."' limit 1;";
    			break;
    				
    	}
    	//echo $sql;
    	$res= $this->db->sql_query($sql) or die($this->cadena_error);
    	if($this->db->sql_numrows($res) > 0){
    		list($entregaactual) = $this->db->sql_fetchrow($res);
    	}
    	return $entregaactual;
    }
    
    function insertaComentarioNoAprobado(){
    	$fecha = date("Y-m-d H:i:s");
    	$ins="INSERT INTO proyectos_validaciones_comentarios 
    		  (proyecto_id,actividad_id,comentarios,aprobado,fecha_alta)
			  VALUES 
    		  ('".$this->data['idproyecto']."','".$this->data['idactividad']."',
    		   '".$this->data['content']."','".$this->data['aprobado']."','".$fecha."');";
    	$resins=$this->db->sql_query($ins)  or die($this->cadena_error);
    	 
    }
    function insertaValidacionProyecto(){
    	$fecha = date("Y-m-d H:i:s");
    	$this->buffer = $statusFrom = $statusTo =0;
    	if( $this->data['idproyecto'] > 0 )
    	{
    		$fecha = date("Y-m-d H:i:s");
    		$statusFrom = $this->regresaEstatus(2);
    		$statusTo   = $this->regresaEstatusActualiza($this->data['aprobado'],$statusFrom);
    		if($statusTo > 0){
	    		if($this->data['aprobado'] == 2){
	    			$this->insertaComentarioNoAprobado();
	    		}
	    		
	    		$update = "UPDATE proyectos_actividades set estatus_entrega='".$statusTo."'
	    				   WHERE proyecto_id='".$this->data['idproyecto']."';";
	    		$resupd = $this->db->sql_query($update)  or die($this->cadena_error);
	    		$update = "UPDATE proyectos_acciones set estatus_entrega='".$statusTo."'
	    				   WHERE id='".$this->data['idproyecto']."';";
	    		$resupd = $this->db->sql_query($update)  or die($this->cadena_error);    		
	    		$this->insertaLogEstatus($statusFrom,$statusTo);    		
	    		$this->buffer=$this->data['aprobado'];
    		}
    	}
    }
    function insertaValidacionActividad(){
    	$fecha = date("Y-m-d H:i:s");
    	$this->buffer = $statusFrom = $statusTo =0;
    	if( ($this->data['idproyecto'] > 0) && ($this->data['idactividad'] > 0))
    	{
    		$fecha = date("Y-m-d H:i:s");
    		$statusFrom = $this->regresaEstatus(1);
    		
    		$statusTo   = $this->regresaEstatusActualiza($this->data['aprobado'],$statusFrom);
    		if ( $this->data['aprobado'] == 2){
    			$this->insertaComentarioNoAprobado();
    		}
    		if($statusTo > 0){
	    		$update = "UPDATE proyectos_actividades set estatus_entrega='".$statusTo."' 
	    				   WHERE id='".$this->data['idactividad']."' and 
	    		   		   proyecto_id='".$this->data['idproyecto']."' limit 1;";
	    		$resupd=$this->db->sql_query($update)  or die($this->cadena_error);
	    		$this->insertaLogEstatus($statusFrom,$statusTo);
				$this->actualizaProyecto();    		
	    		$this->buffer=$this->data['aprobado'];
    		}
    	}
    }
    
    function actualizaProyectoAvance(){
    	$idEstatusExito=0;
    	switch($this->session['rol']){
    		case 2:
    			$idEstatusExito=4;
    			break;
    		case 3:
    			$idEstatusExito=7;
    			break;
    		case 4:
    			$idEstatusExito=10;
    			break;
    	}
    	if($idEstatusExito > 0){
    		$totalActividades = $totalActividadesS = 0;
    		$campo ="estatus_avance_entrega_t".$this->data['idtrimestre'];
    		$sql = "SELECT COUNT(id) FROM proyectos_actividades WHERE proyecto_id='".$this->data['idproyecto']."';";
    		$res = $this->db->sql_query($sql) or die($this->cadena_error);
    		list($totalActividades)  = $this->db->sql_fetchrow($res);

    		$campob="estatus_avance_entrega";
    		if($this->data['idtrimestre']>1){
    			$campob="estatus_avance_entrega".$this->data['idtrimestre'];
    		}
    		
    		$sql1 = "SELECT COUNT(id) FROM proyectos_actividades WHERE proyecto_id='".$this->data['idproyecto']."' 
    				AND ".$campo."='".$idEstatusExito."';";
    		$res1 = $this->db->sql_query($sql1) or die($this->cadena_error);
    		list($totalActividadesS) = $this->db->sql_fetchrow($res1);
    		if( ($totalActividades > 0) && ($totalActividadesS > 0) && ($totalActividades == $totalActividadesS) ){
    			if($idEstatusExito){
	    			$update = "UPDATE proyectos_acciones set ".$campob."='".$idEstatusExito."'
		    				   	   WHERE id='".$this->data['idproyecto']."' limit 1;";
	    			$resupd=$this->db->sql_query($update)  or die($this->cadena_error);
    			}
    		}
    	}
    	 
    }
    
    function actualizaProyecto(){
    	$idEstatusExito=0;
    	switch($this->session['rol']){
    		case 2:
    			$idEstatusExito=4;
    			break;
    		case 3:
    			$idEstatusExito=7;
    			break;
    		case 4:
    			$idEstatusExito=10;
    			break;	 
    	}
    	if($idEstatusExito > 0){
    		$totalActividades = $totalActividadesS = 0; 
	    	$sql = "SELECT COUNT(id) FROM proyectos_actividades WHERE proyecto_id='".$this->data['idproyecto']."';";
	    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
	    	list($totalActividades)  = $this->db->sql_fetchrow($res);
	    	
	    	$sql1 = "SELECT COUNT(id) FROM proyectos_actividades WHERE proyecto_id='".$this->data['idproyecto']."' AND estatus_entrega='".$idEstatusExito."';";
	    	$res1 = $this->db->sql_query($sql1) or die($this->cadena_error);
			list($totalActividadesS) = $this->db->sql_fetchrow($res1);
	    	if( ($totalActividades > 0) && ($totalActividadesS > 0) && ($totalActividades == $totalActividadesS) ){
	    		if($idEstatusExito > 0){
	    			$update = "UPDATE proyectos_acciones set estatus_entrega='".$idEstatusExito."'
	    				   	   WHERE id='".$this->data['idproyecto']."' limit 1;";
	    			$resupd=$this->db->sql_query($update)  or die($this->cadena_error);
	    		}
	    	}
    	}
    }
    function insertaLogEstatusAvances($statusFrom,$statusTo){
    	$ins="INSERT INTO log_estatus_avances (user_id,proyecto_id,actividad_id,trimestre_id,ip,estatus_from,estatus_to)
    		  VALUES ('".$this->session['userId']."','".$this->data['idproyecto']."','".$this->data['idactividad']."',
    		  		  '".$this->data['idtrimestre']."','".$this->session['ip']."','".$statusFrom."','".$statusTo."');";
    	$this->db->sql_query($ins) or die($this->cadena_error);
    	$this->insertaBitacoraComentariosAvances($this->data,$this->session,$this->data['idproyecto'],$this->data['idactividad'],$this->data['idtrimestre'],$this->buffer,$this->arrayEstatus['Validar']);
    }
    function insertaLogEstatus($statusFrom,$statusTo){
    	$ins="INSERT INTO log_estatus (user_id,proyecto_id,actividad_id,ip,estatus_from,estatus_to) 
    		  VALUES ('".$this->session['userId']."','".$this->data['idproyecto']."','".$this->data['idactividad']."',
    		  		  '".$this->session['ip']."','".$statusFrom."','".$statusTo."');";
    	$this->db->sql_query($ins) or die($this->cadena_error);
    	$this->insertaBitacoraComentarios($this->data,$this->session,$this->data['idproyecto'],$this->data['idactividad'],$this->buffer,$this->arrayEstatus['Validar']);
    }
    
    function insertaAvanceProyecto(){
    	$fecha = date("Y-m-d H:i:s");
    	$this->buffer = $statusFrom = $statusTo =0;
    	if( ($this->data['idproyecto'] > 0) && ($this->data['idactividad'] >= 0) && ($this->data['idtrimestre'] > 0))
    	{
    		$fecha = date("Y-m-d H:i:s");
    		$campo ="estatus_avance_entrega_t".$this->data['idtrimestre'];
    		$statusFrom = $this->regresaEstatusAvance(2);
    		$statusTo   = $this->regresaEstatusActualiza($this->data['aprobado'],$statusFrom);
    		//die($statusFrom."  ****  ".$statusTo);
    		if($statusFrom >=0 && $statusTo >0){
    			$update = "UPDATE proyectos_actividades set $campo='".$statusTo."'
    					   WHERE proyecto_id  = '".$this->data['idproyecto']."' ;";
    			$this->db->sql_query($update)  or die($this->cadena_error);
    			$this->insertaLogEstatusAvances($statusFrom,$statusTo);
    			$this->actualizaProyectoAvance();
     			$this->buffer=$this->data['aprobado'];
    		}
    	}
    	 
    }
    
	function insertaAvanceActividad(){	
		$fecha = date("Y-m-d H:i:s");
    	$this->buffer = $statusFrom = $statusTo =0;
    	if( ($this->data['idproyecto'] > 0) && ($this->data['idactividad'] > 0) && ($this->data['idtrimestre'] > 0))
    	{
    		$fecha = date("Y-m-d H:i:s");
    		$campo ="estatus_avance_entrega_t".$this->data['idtrimestre'];
    		$statusFrom = $this->regresaEstatusAvance(1);
    		$statusTo   = $this->regresaEstatusActualiza($this->data['aprobado'],$statusFrom);
    		if($statusFrom >=0 && $statusTo >0){
	    		$update = "UPDATE proyectos_actividades set $campo='".$statusTo."' 
	    				   WHERE id = '".$this->data['idactividad']."' and 
	    		   		   proyecto_id  = '".$this->data['idproyecto']."' limit 1;";
	    		$resupd=$this->db->sql_query($update)  or die($this->cadena_error);   		
	    		$this->insertaLogEstatusAvances($statusFrom,$statusTo);
				$this->actualizaProyectoAvance();    		
	    		$this->buffer=$this->data['aprobado'];
    		}
    	}
	}
    
    
   function inserta(){
   		$statusTo=0;
   		$this->buffer=0;
		if($this->data['idproyecto'] > 0)
		{
			$fecha = date("Y-m-d H:i:s");
			$entregaactual=2;
			if($this->data['idactividad'] > 0)
				$sql="SELECT estatus_entrega FROM proyectos_actividades 
				  WHERE id='".$this->data['idactividad']."' and proyecto_id='".$this->data['idproyecto']."' limit 1;";
			else
				$sql="SELECT estatus_entrega FROM proyectos_acciones
				  WHERE id='".$this->data['idproyecto']."' limit 1;";				
			$res= $this->db->sql_query($sql) or die($this->cadena_error);			
			if($this->db->sql_numrows($res)>0){
				list($entregaactual) = $this->db->sql_fetchrow($res);
			}			
			$statusFrom = $entregaactual;
			if($this->data['aprobado'] == 1){				
				switch($entregaactual)
				{
					case 2:
						$statusTo   = 4;
						break;
					case 3:
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
				switch($entregaactual)
				{
					case 2:
						$statusTo   = 3;
						break;
					case 4:
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
			if($statusTo > 0){
				$ins="INSERT INTO proyectos_actividades_comentarios (user_id,proyecto_id,actividad_id,comentarios,aprobado,fecha_alta)
					  VALUES ('".$this->session['userId']."','".$this->data['idproyecto']."','".$this->data['idactividad']."','".$this->data['content']."','".$this->data['aprobado']."','".$fecha."');";
				$resins=$this->db->sql_query($ins)  or die($this->cadena_error);
				if($resins){
					$this->buffer=$this->db->sql_nextid();
					if($this->data['idactividad'] > 0){
						$update = "UPDATE proyectos_actividades set estatus_entrega='".$statusTo."' 
							  	   WHERE id='".$this->data['idactividad']."' and proyecto_id='".$this->data['idproyecto']."' limit 1;";
					}
					else{
						$update = "UPDATE proyectos_actividades set estatus_entrega='".$statusTo."'
						  		   WHERE proyecto_id='".$this->data['idproyecto']."' ;";
					}
					$resupd=$this->db->sql_query($update)  or die($this->cadena_error);
					if($this->data['aprobado'] == 2){
						$update = "UPDATE proyectos_acciones set estatus_entrega='".$statusTo."'
							       WHERE id='".$this->data['idproyecto']."' limit 1;";
						$resupd=$this->db->sql_query($update)  or die($this->cadena_error);
					}
					$this->insertaLogEstatus($statusFrom,$statusTo);
					if($this->data['idactividad'] > 0){
						$this->actualizaProyecto();
					}
					else{
						$update = "UPDATE proyectos_acciones set estatus_entrega='".$statusTo."'
						  		   WHERE id='".$this->data['idproyecto']."' limit 1;";
						$resupd=$this->db->sql_query($update)  or die($this->cadena_error);
					}
					$this->buffer=$this->data['aprobado'];
   				}
			}
   		}
   }
    
   function insertaAvance()
   {
	    $this->buffer=0;
	   	$statusTo=0;
	   	if($this->data['idproyecto'] > 0)
	   	{
	   		$campo="estatus_avance_entrega_t".$this->data['idtrimestre'];
	   		$campob="estatus_avance_entrega";
	   		if($this->data['idtrimestre']>1){
	   			$campob="estatus_avance_entrega".$this->data['idtrimestre'];
	   		}
	   		$fecha = date("Y-m-d H:i:s");
	   		if($this->data['idactividad'] > 0)
	   			$sql="SELECT $campo FROM proyectos_actividades WHERE proyecto_id='".$this->data['idproyecto']."' AND id ='".$this->data['idactividad']."' limit 1;";
	   		else
	   			$sql="SELECT ".$campob." FROM proyectos_acciones WHERE id='".$this->data['idproyecto']."' limit 1;";
			$res= $this->db->sql_query($sql) or die($this->cadena_error);
	   		$entregaactual=2;
	   		if($this->db->sql_numrows($res)>0){
	   			list($entregaactual) = $this->db->sql_fetchrow($res);
	   		}
	   		$statusFrom = $entregaactual;
	   		if($this->data['aprobado'] == 1){
	   			switch($entregaactual)
	   			{
	   				case 2:
	   					$statusTo   = 4;
	   					break;
	   				case 3:
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
	   				case 4:
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
	   		if($statusTo > 0){
		   		$ins="INSERT INTO proyectos_validaciones_avances_comentarios (user_id,proyecto_id,actividad_id,trimestre_id,comentarios,aprobado,fecha_alta)
						  VALUES ('".$this->session['userId']."','".$this->data['idproyecto']."','".$this->data['idactividad']."','".$this->data['idtrimestre']."','".$this->data['content']."','".$this->data['aprobado']."','".$fecha."');";
		   		$resins=$this->db->sql_query($ins)  or die($this->cadena_error);
		   		if($resins)
		   		{
		   			$this->buffer=$this->db->sql_nextid();
		   			$update = "UPDATE proyectos_acciones set ".$campob." ='".$statusTo."' WHERE id='".$this->data['idproyecto']."'  limit 1;";
		   			$this->db->sql_query($update)  or die($this->cadena_error);		   			
		   			$update = "UPDATE proyectos_actividades set ".$campo."='".$statusTo."'
							   WHERE proyecto_id='".$this->data['idproyecto']."' AND id='".$this->data['idactividad']."' LIMIT 1;";
		   			$this->db->sql_query($update) or die($this->cadena_error);
		   			$ins="INSERT INTO log_estatus_avances (user_id,proyecto_id,actividad_id,trimestre_id,ip,estatus_from,estatus_to) VALUES ('".$this->session['userId']."','".$this->data['idproyecto']."','".$this->data['idactividad']."','".$this->data['idtrimestre']."','".$this->session['ip']."','".$entregaactual."','".$statusTo."');";
		   			$this->db->sql_query($ins) or die($this->cadena_error);
		   			$this->insertaBitacoraComentariosAvances($this->data,$this->session,$this->data['idproyecto'],$this->data['idactividad'],$this->data['idtrimestre'],$this->buffer,$this->arrayEstatus['Validar']);
		   		}
	   		}
		}
   }   
   function formatoAvance(){
   	$this->buffer="";
   	if($this->data['idproyecto'] > 0){
   		$sql="SELECT comentarios FROM proyectos_validaciones_avances_comentarios
    			  WHERE proyecto_id='".$this->data['idproyecto']."' AND trimestre_id='".$this->data['idtrimestre']."' order by id desc
   				  LIMIT 1;";
   		$res=$this->db->sql_query($sql) or die("error");
   		if($this->db->sql_numrows($res) > 0){
   			list($this->buffer) = $this->db->sql_fetchrow($res);
   		}
   	}
   }
    function formato(){
    	$this->buffer="";
    	if($this->data['idproyecto'] > 0){
    		$sql="SELECT comentarios FROM proyectos_validaciones_comentarios 
    			  WHERE proyecto_id='".$this->data['idproyecto']."'  order by id desc
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