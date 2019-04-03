<?php
class NotificacionesAvances{
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
	var $arrayPlaneacion;
	var $arrayCoordinadores;
	var $arrayAdministradores;
	var $arrayProyectosClas;
	var $arrayEstatus1;
	var $arrayEstatus2;
	var $arrayEstatus3;
	var $exito;
	
	function __construct($db,$data,$session,$server,$path){
		$this->db     = $db;
		$this->data   = $data;
		$this->path   = $path;
		$this->server = $server;
		$this->session= $session;
		$this->arrayRegreso     = $this->arrayProyectosClas = array();
		$this->arrayPlaneacion  = $this->arrayCoordinadores  = $this->arrayAdministradores = array();
		$this->arrayEstatus1 = $this->arrayEstatus2 = $this->arrayEstatus3 =array();
		$this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
		$this->opc    = $this->data['opc'];
		$this->exito = 0;
		$this->recuperaDatos();
		$this->procesaDatos();
		$this->procesaEmail();
	}
	
	
	function recuperaCorreos(){
		$tmp = array();
		$this->arrayPlaneacion  = $this->arrayCoordinadores  = $this->arrayAdministradores = array();
		for($i = 2; $i <=4; $i++){
			$tmp  = $this->email($i);
			switch($i){
				case 2:
					$this->arrayPlaneacion = $tmp;
					break;
				case 3:
					$this->arrayCoordinadores = $tmp;
					break;
				case 4:
					$this->arrayAdministradores = $tmp;
					break;
			}
		}
	}
	
	
	function email($i){
		$arrayRegreso = array();
		$sql="SELECT a.user_id,a.user_nombre,a.user_email,b.area_id,b.programa_id from cat_usuarios as a LEFT JOIN cat_permisos_areas as b
			  ON a.user_id = b.usuario_id WHERE a.user_rol = '".$i."' ORDER BY a.user_id,b.area_id,b.programa_id;";
		$res=$this->db->sql_query($sql) or die ($this->cadena_error);
		if($this->db->sql_numrows($res) > 0){
 			while(list($user_id,$user_nombre,$user_email,$user_area_id,$user_programa_id) = $this->db->sql_fetchrow($res)){
				if( (trim($user_email)!= "") && (trim($user_area_id)!= "") && (trim($user_programa_id)!="") ){
					$tmp1=$user_area_id."-".$user_programa_id;
					$arrayRegreso[$tmp1] = $user_nombre."|".$user_email; 
				}
 			}
		}
		return $arrayRegreso;
	}

	function enviaEmail($arrayProyectos,$opcion){
		$campoTrimestre = "estatus_avance_entrega_t".$this->data['idtrimestre'];
		$campoEntrega="estatus_avance_entrega";
		if($this->data['idtrimestre'] > 1)
			$campoEntrega="estatus_avance_entrega".$this->data['idtrimestre'];
		$regreso = 0;
		$arrayDestinatarios = $tmpdata = array();
		$estatusFin=0;
		$body_html="";
		$body_html="";
		$sql="SELECT id,titulo,cuerpo FROM cat_emails WHERE active='1' and tipo='2' ORDER BY id;";
		$res=$this->db->sql_query($sql) or die ($this->cadena_error);
		if($this->db->sql_numrows($res) > 0){
			while(list($id,$titulo,$cuerpo) = $this->db->sql_fetchrow($res)){
				$array_email[$id]['id']=$id;
				$array_email[$id]['titulo']=$titulo;
				$array_email[$id]['cuerpo']=$cuerpo;
				$tituloMnesaje=$titulo;
			}
		}
		$opciondb=0;
		switch ($opcion){
			case 1:
				$arrayDestinatarios = $this->arrayPlaneacion;
				$estatusFin= 2;
				$opciondb=4;
				break;
			case 2:
				$arrayDestinatarios = $this->arrayCoordinadores;
				$estatusFin= 5;
				$opciondb=5;
				break;
			case 3:
				$arrayDestinatarios = $this->arrayAdministradores;
				$estatusFin= 8;
				$opciondb=6;
				break;		
		}
		if($this->session['rol'] >=4){
			$estatusFin = 10;
			$fin=10;
		}
		$tituloMensaje = $array_email[$opciondb]['titulo'];
		$body  = $array_email[$opciondb]['cuerpo'];		
		$body_html="<html><head><title>".$tituloMensaje."</title></head><body><p>".$body."</p></body></html>";
		$emailFrom = array ("lciencias@gmail.com" => "Administrador SISEC");		
 		foreach($arrayProyectos as $clave => $tmpProyecto){
 			$data=$arrayDestinatarios[$clave];
 			if(trim($data) == ""){
 				$data="Administrador|lciencias@gmail.com";
 			}
 			$tmp = explode('-',$clave);
			foreach($tmpProyecto as $idProyecto => $nmDataProyecto)
			{				
				$tmpdata = explode("|",$data);					
				$emailTo = array ($tmpdata[1] => $tmpdata[0]);
				$sql="SELECT ".$campoEntrega." FROM proyectos_acciones WHERE id='".$idProyecto."' limit 1;";
				$res= $this->db->sql_query($sql) or die($this->cadena_error);
				$entregaactual=1;
				if($this->db->sql_numrows($res)>0){
					list($entregaactual) = $this->db->sql_fetchrow($res);
				}
 				$upd="UPDATE proyectos_acciones SET ".$campoEntrega." = '".$estatusFin."' WHERE id = '".$idProyecto."'; ";
 				$this->db->sql_query($upd) or die($this->cadena_error);
 				
 				$updEstatus="UPDATE proyectos_actividades SET ".$campoTrimestre." = '".$estatusFin."' WHERE proyecto_id = '".$idProyecto."'; ";
 				$this->db->sql_query($updEstatus) or die($this->cadena_error); 		

 				
 				$sqla="SELECT id FROM proyectos_actividades WHERE proyecto_id = '".$idProyecto."'; ";
 				$resa= $this->db->sql_query($sqla) or die($this->cadena_error);
 				if($this->db->sql_numrows($resa)>0){
 					while(list($tmpIdActividad) = $this->db->sql_fetchrow($resa)){
 						$ins="INSERT INTO log_estatus_avances (user_id,proyecto_id,actividad_id,trimestre_id,ip,estatus_from,estatus_to)
 					  	VALUES ('".$this->session['userId']."','".$idProyecto."','".$tmpIdActividad."','".$this->data['idtrimestre']."','".$this->session['ip']."','".$entregaactual."','".$estatusFin."');";
 						$this->db->sql_query($ins) or die($this->cadena_error);
 					}
 				}
			}
 		}
 		/*try
 		{
 			$transport = Swift_SmtpTransport::newInstance('smtp.df.gob.mx',25)->setUsername('pat@df.gob.mx')->setPassword('gp=a5=d8');
 			$mailer    = Swift_Mailer::newInstance($transport);
 			$message   = Swift_Message::newInstance($tituloMnesaje)->setFrom($emailFrom)->setTo($emailTo)->setBody($body_html,'text/html')->addPart($body_html,'text/plain');
 			if (($mailer->send($message)) > 0)
 			{
 				$this->exito = 1;
 			}
 		}
 		catch(Exception $e){
 			$this->exito = 0;
 			echo "Error:  ".$e->getMessage();
 		}*/
		return $regreso;	
	}
	
	function uneMensajes($array){
		$arraytmp=array();
		$arrayIds=array();
		foreach($array as $ind => $tmp){
			$arrayIds[]=$ind;
			$areaId = $tmp['area_id'];
			$progId = $tmp['programa_id'];
			$var    = $areaId."-".$progId;
			$id     = $tmp['id'];
			$arraytmp[$var][$id] = $tmp['proyecto']."-".$tmp['estatus'];
		}		
		return $arraytmp;		
	}
	
	
	function procesaEmail(){
		if(count($this->arrayEstatus1) > 0){
			$arraytmp=$this->uneMensajes($this->arrayEstatus1);
			$this->enviaEmail($arraytmp,1);
		}
		if(count($this->arrayEstatus2) > 0){
			$arraytmp=$this->uneMensajes($this->arrayEstatus2);
			$this->enviaEmail($arraytmp,2);			
		}
		if(count($this->arrayEstatus3) > 0){
			$arraytmp=$this->uneMensajes($this->arrayEstatus3);
			$this->enviaEmail($arraytmp,3);
		}		
	}
	
	function procesaDatos(){		
		if(count($this->arrayProyectosClas) > 0){
			foreach($this->arrayProyectosClas as $ind => $tmp)
			{
				if( ($this->arrayProyectosClas[$ind]['estatus'] == 0) || ($this->arrayProyectosClas[$ind]['estatus'] == 1) || (($this->arrayProyectosClas[$ind]['estatus'] == 3))){					
					$this->arrayEstatus1[]=$this->arrayProyectosClas[$ind];
				}
				if( ($this->arrayProyectosClas[$ind]['estatus'] == 4) || (($this->arrayProyectosClas[$ind]['estatus'] == 6))){
					$this->arrayEstatus2[]=$this->arrayProyectosClas[$ind];
				}
				if( ($this->arrayProyectosClas[$ind]['estatus'] >= 7) && (($this->arrayProyectosClas[$ind]['estatus'] <= 9))){
					$this->arrayEstatus3[]=$this->arrayProyectosClas[$ind];
				}
			}
		}		
	}
	
	function recuperaDatos(){
		$campoEntrega="a.estatus_avance_entrega";
		if($this->data['idtrimestre'] > 1)
			$campoEntrega="a.estatus_avance_entrega".$this->data['idtrimestre'];
		$tmp = array();
		$arrayProyectos = array();
		$this->arrayProyectosClas = array();
		$filtro="";
		if(trim($this->data['proyectosIds']) != ""){
			$arrayProyectos = explode ("|",$this->data['proyectosIds']);
			if(count($arrayProyectos) > 0){
				foreach ($arrayProyectos as $ind){
					if($ind + 0){
						$tmp[]=$ind;
					}
				}
				$filtro = "AND a.id IN (".implode(',',$tmp)." ) ";
				$sql="SELECT a.id,a.unidadResponsable_id,a.programa_id,a.proyecto,".$campoEntrega."
					  FROM proyectos_acciones a
					  WHERE 1 ".$filtro." 
					  ORDER BY ".$campoEntrega.",id;";
				$res = $this->db->sql_query($sql) or die($this->cadena_error);
				if($this->db->sql_numrows($res) > 0){
					while(list($id,$area_id,$programa_id,$proyecto,$estatus) = $this->db->sql_fetchrow($res)){
						$this->arrayProyectosClas[$id]['area_id']     = $area_id;
						$this->arrayProyectosClas[$id]['programa_id'] = $programa_id;
						$this->arrayProyectosClas[$id]['proyecto']    = $proyecto;
						$this->arrayProyectosClas[$id]['estatus']     = $estatus;
						$this->arrayProyectosClas[$id]['id']     = $id;
					}
				}
			}
		}
	}
	
	function obtenExito(){
		return $this->exito;
	}
	//fin de clase
}