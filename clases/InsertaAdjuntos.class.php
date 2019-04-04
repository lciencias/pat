<?php
class InsertaAdjuntos{
	var $db;
	var $folio;
	var $random;
	var $path;
	var $path_img;
	var $filename;
	var $exito;
	var $opc;
	var $buffer;
	var $bufferConsulta;
	var $path_web;
	var $cadena_error;
	var $id;
	var $arrayEstatus;
	var $session;
	var $pathfileurl;
		
	function __construct($db,$idProyecto,$idActividad,$idTrimestre,$path,$path_img,$filename,$opc,$data,$path_web,$id,$session,$pathfileurl){
		$this->db = $db;
		$this->idProyecto = $idProyecto;
		$this->idActividad = $idActividad;
		$this->idTrimestre = $idTrimestre;
		$this->session=$session;
		$this->id=$id;
		$this->path = $path;
		$this->path_img = $path_img;
		$this->pathfileurl=$pathfileurl;
		$this->filename = $filename;
		$this->data = $data;
		$this->path_web = $path_web;
		$this->opc = $opc; 
		$this->exito = 0;
		$this->arrayEstatus = array('Alta Adjunto' => 1, 'Elimina Adjunto' => 2);
		$this->cadena_error="<script>location.href='".$this->path_web."'</script>";
		$this->buffer =0;
		$this->bufferConsulta="";
		switch($this->opc){
			case 1:
				if($this->idTrimestre == 0){
					$this->insertaAdjunto();
					$this->consultaAdjuntos();
				}
				else{
					$this->insertaAdjuntoAvance();
					$this->consultaAdjuntosAvances();
				}
				break;
			case 2:
				if($this->idTrimestre == 0){
					$this->consultaAdjuntos();
				}else{
					$this->consultaAdjuntosAvances();
				}
				break;
			case 3:
				if($this->idTrimestre == 0){
					$this->eliminaAdjunto();
				}else{
					$this->eliminaAdjuntoAvance();
				}
				break;
				
		}
	}
	
	/**
	 * Metodo que se encarga de registrar los adjuntos seleccionados por el usuario para avances
	 */
	function insertaAdjuntoAvance(){
		$this->buffer=0;
		$this->filename=addslashes(trim($this->filename));
		
		$this->path=addslashes(trim($this->path));
		
		$this->pathfileurl=addslashes(trim($this->pathfileurl));
		
		if($this->idProyecto > 0 && $this->idActividad > 0){
			$sql="SELECT id FROM proyectos_avances_adjuntos WHERE proyecto_id='".$this->idProyecto."' AND actividad_id = '".$this->idActividad."' AND trimestre_id='".$this->idTrimestre."' AND path='".$this->path."' LIMIT 1;";
			$res = $this->db->sql_query ( $sql ) or die ($this->cadena_error);
			if ($this->db->sql_numrows ( $res ) == 0) {
				$ins = "INSERT INTO proyectos_avances_adjuntos(proyecto_id,actividad_id,trimestre_id,path,archivo,path_web)
						VALUES ('".$this->idProyecto."','".$this->idActividad."','".$this->idTrimestre."','".$this->path."','".$this->filename."','".$this->pathfileurl."');";
				if($this->db->sql_query($ins)){
					$this->buffer=$this->db->sql_nextid();
					$this->insertaBitacoraAdjuntosAvances($this->data,$this->session,$this->idProyecto,$this->idActividad,$this->idTrimestre,$this->buffer,$this->arrayEstatus['Alta Adjunto']);
				}
			}
		}		
	}
	
	/**
	 * Metodo que se encarga de registrar los adjuntos seleccionados por el usuario
	 */
	function insertaAdjunto(){
		$this->buffer=0;
		$this->filename=addslashes(trim($this->filename));
		$this->filename=utf8_decode($this->filename);
		$this->path=addslashes(trim($this->path));
		$this->path=utf8_decode($this->path);		
		if($this->idProyecto > 0 && $this->idActividad > 0){
			$sql="SELECT id FROM proyectos_actividades_adjuntos WHERE proyecto_id='".$this->idProyecto."' AND actividad_id = '".$this->idActividad."' AND path='".$this->path."' LIMIT 1;";
			$res = $this->db->sql_query ( $sql ) or die ($this->cadena_error);
			if ($this->db->sql_numrows ( $res ) == 0) {
				$ins = "INSERT INTO proyectos_actividades_adjuntos(proyecto_id,actividad_id,path,archivo) 
						VALUES ('".$this->idProyecto."','".$this->idActividad."','".$this->path."','".$this->filename."');";
				if($this->db->sql_query($ins)){
					$this->buffer=$this->db->sql_nextid();
					$this->insertaBitacoraAdjuntos($this->data,$this->session,$this->idProyecto,$this->idActividad,$this->buffer,$this->arrayEstatus['Alta Adjunto']);
				}
			}
		}
	}
	
	/**
	 * Metodo que se encarga de consultar los archivos de x proyecto
	 * @return string  listado de archivos
	 */
	function consultaAdjuntosAvances(){		
		$this->bufferConsulta="";
		$div=$url=$link="";
		if($this->idProyecto > 0 && $this->idActividad > 0){
			$this->bufferConsulta='<script type="text/javascript" src="'.$this->path_web.'js/eliminaAdjuntoAvances.js"></script>';
			$sql="SELECT id,archivo,proyecto_id,actividad_id,trimestre_id,path_web,path
				  FROM proyectos_avances_adjuntos 
				  WHERE proyecto_id='".$this->idProyecto."' 
				  		AND actividad_id = '".$this->idActividad."'
				  		AND trimestre_id = '".$this->idTrimestre."'
				  ORDER BY id;";
			$res=$this->db->sql_query($sql) or die ($this->cadena_error);
			if($this->db->sql_numrows($res) > 0){
		        while(list($id,$file,$proyectoId,$actividadId,$trimestreId,$urlweb,$url_sis) = $this->db->sql_fetchrow($res)) {
		        	$div=$id."-".$proyectoId."-".$actividadId."-".$trimestreId;
		        	
		        	if($this->opc == 1){
		        		$link=utf8_encode($file);
		        		if(file_exists(utf8_encode($url_sis))){
		        			$link="<a href='".utf8_encode($urlweb)."' target='_blank'>".utf8_encode($file)."</a>";
		        		}
		        		$this->bufferConsulta.='<button id="'.$div.'" class="btneliminaAdjunto btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span>&nbsp;</button>&nbsp;&nbsp;'.$link.'<br>';
		        		
		        	}
		        	else{
		        		if(file_exists(utf8_encode($url_sis))){
		        			$link="<a href='".utf8_encode($urlweb)."' target='_blank'>".utf8_encode($file)."</a>";
		        		}
		        		else{
		        			$link=utf8_encode($file);		        			 
		        		}
		        		$this->bufferConsulta.='<button id="'.$div.'" class="btneliminaAdjunto btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span>&nbsp;</button>&nbsp;&nbsp;'.$link.'<br>';		        		
		        	}		        				      
				}				
			}
		}
	}
	
	/**
	 * Metodo que se encarga de consultar los archivos de x proyecto
	 * @return string  listado de archivos
	 */
	function consultaAdjuntos(){
		$this->bufferConsulta="";
		$div="";
		if($this->idProyecto > 0 && $this->idActividad > 0){
			$this->bufferConsulta='<script type="text/javascript" src="'.$this->path_web.'js/eliminaAdjunto.js"></script>';
			$sql="SELECT id,archivo,proyecto_id,actividad_id FROM proyectos_actividades_adjuntos
				  WHERE proyecto_id='".$this->idProyecto."' AND actividad_id = '".$this->idActividad."'
				  ORDER BY id;";
			$res=$this->db->sql_query($sql) or die ($this->cadena_error);
			if($this->db->sql_numrows($res) > 0){
				while(list($id,$file,$proyectoId,$actividadId) = $this->db->sql_fetchrow($res)) {
					$div=$id."-".$proyectoId."-".$actividadId;
					$this->bufferConsulta.='<button id="'.$div.'" class="btneliminaAdjunto btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span>&nbsp;</button>&nbsp;&nbsp;'.$file.'<br>';
				}
			}
		}
	}	
	/**
	 * Metodo que se encarga de eliminar el archivo adjunto
	 */
	function eliminaAdjunto(){
		$this->buffer= $nombreArchivo ="";
		if( $this->id > 0){
			$sql="SELECT path FROM proyectos_actividades_adjuntos WHERE id='".$this->id."' LIMIT 1;";
			$res=$this->db->sql_query($sql) or die ($this->cadena_error);
			if($this->db->sql_numrows($res) > 0){
				list($nombreArchivo)  = $this->db->sql_fetchrow($res);
				if(file_exists(utf8_encode($nombreArchivo))){
					unlink(utf8_encode($nombreArchivo));
				}
				$del="DELETE FROM proyectos_actividades_adjuntos WHERE id='".$this->id."' LIMIT 1;";
				$res=$this->db->sql_query($del) or die ($this->cadena_error);
				$this->insertaBitacoraAdjuntos($this->data,$this->session,$this->idProyecto,$this->idActividad,$this->id,$this->arrayEstatus['Elimina Adjunto']);
			}
			$this->consultaAdjuntos();
		}
	}
	
	/**
	 * Metodo que se encarga de eliminar el archivo adjunto
	 */
	function eliminaAdjuntoAvance(){
		$this->buffer= $nombreArchivo ="";
		if( $this->id > 0){
			$sql="SELECT path FROM proyectos_avances_adjuntos WHERE id='".$this->id."' LIMIT 1;";
			$res=$this->db->sql_query($sql) or die ($this->cadena_error);
			if($this->db->sql_numrows($res) > 0){
				list($nombreArchivo)  = $this->db->sql_fetchrow($res);
				if(file_exists(utf8_encode($nombreArchivo))){
					unlink(utf8_encode($nombreArchivo));
				}
				$del="DELETE FROM proyectos_avances_adjuntos WHERE id='".$this->id."' LIMIT 1;";
				$res=$this->db->sql_query($del) or die ($this->cadena_error);
				$this->insertaBitacoraAdjuntosAvances($this->data,$this->session,$this->idProyecto,$this->idActividad,$this->idTrimestre,$this->id,$this->arrayEstatus['Elimina Adjunto']);
			}
			$this->consultaAdjuntosAvances();
		}
	}	
	
	function insertaBitacoraAdjuntos($data,$session,$idProyecto,$idActividad,$id,$estatus){
		$ins="INSERT INTO log_proyectos_adjuntos (user_id,proyecto_id,actividad_id,adjunto_id,estatus,ip)
 			  VALUES ('".$session['userId']."','".$idProyecto."','".$idActividad."','".$id."','".$estatus."','".$session['ip']."');";
		$res=$this->db->sql_query($ins) or die($this->cadena_error);
	}
	function insertaBitacoraAdjuntosAvances($data,$session,$idProyecto,$idActividad,$idTrimestre,$id,$estatus){
		$ins="INSERT INTO log_proyectos_avances_adjuntos (user_id,proyecto_id,actividad_id,trimestre_id,adjunto_id,estatus,ip)
 			  VALUES ('".$this->session['userId']."','".$idProyecto."','".$idActividad."','".$idTrimestre."','".$id."','".$estatus."','".$this->session['ip']."');";
		$res=$this->db->sql_query($ins) or die($this->cadena_error);
	}	
	/**
	 * Metodo que regresa el id del registro insertado
	 * @return number id de la tabla
	 */
	function obtenId(){
		return $this->buffer;
	}	
	
	function obtenBuffer(){
		return utf8_decode($this->bufferConsulta);
	}
}