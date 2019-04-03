<?php
class CatalogosEst {
	var $db;
	var $data;
	var $session;
	var $server;
	var $path;
	var $opcion;
	var $arrays;
	function __construct($db, $data, $session) {
		$this->db     = $db;
		$this->data   = $data;
		$this->session = $session;
		$this->arrays = array();
		$this->catEjes();
		$this->catProgramas();
		$this->catAreas();
		$this->catAnos();
		$this->catTablas();
		$this->catTipos();
		$this->catTrimestre();
	}

	function catTrimestre(){
		$buffer = $tmp = "";
		for($i = 1; $i <= 4; $i++){
			$tmp="";
			if($i == $this->data['trimestreId']){
				$tmp = " selected";
			}
			$buffer .="<option value ='".$i."' ".$tmp.">Trimestre ".$i."</option>";
		}
		$this->arrays[7] = $buffer;
	}
	function catTipos(){
		$tmp1 = $tmp2 = $buffer = "";
		if((int)$this->data['tipoId'] == 0){
			$tmp2 = "SELECTED ";
			$tmp1 = "";
		}
		
		if((int)$this->data['tipoId'] == 1){
			$tmp1 = "SELECTED ";
			$tmp2 = "";
		}
		if((int)$this->data['tipoId'] == 2){
			$tmp2 = "SELECTED ";
			$tmp1 = "";
		}
		
		$buffer ="<option value ='1' ".$tmp1.">Tablero por Eje</option>
				  <option value ='2' ".$tmp2.">Tablero por Unidad Reponsable</option>";
		$this->arrays[6] = $buffer;
	}
	
	function catTablas(){
		$buffer = $tmp = "";
		if($this->session['userId'] > 0){
			$sql="SELECT id,tabla,defa FROM cat_tablas WHERE activa = '1' ORDER BY id desc;";
			$res=$this->db->sql_query($sql) or die($this->cadena_error);
			if($this->db->sql_numrows ( $res )>0){
				while(list($id,$nm,$defa) = $this->db->sql_fetchrow($res)){
					$tmp = "";
					if((int) $this->data['tablaId'] > 0){					
						if($id == $this->data['tablaId']){
							$tmp = " SELECTED ";
						}
					}
					else{
						if($defa == 1){
							$tmp = " SELECTED ";
						}
					}
					$buffer .= "<option value='".$id."' ".$tmp.">".utf8_encode($nm)."</option>";
				}
				$this->arrays[4] = $buffer;
			}
		}else{
			$sql="SELECT id FROM cat_tablas WHERE defa = '1' LIMIT 1;";
			$res=$this->db->sql_query($sql) or die($this->cadena_error);
			if($this->db->sql_numrows ( $res )>0){
				list($id) = $this->db->sql_fetchrow($res);
				$this->arrays[4] = $id;
			}
		}
	}
	
	function catAnos(){
		$buffer = $tmp = "";
		$sql="SELECT ano FROM cat_anos WHERE active = '1' ORDER BY ano desc;";
		$res=$this->db->sql_query($sql) or die($this->cadena_error);
		if($this->db->sql_numrows ( $res )>0){
			while(list($id) = $this->db->sql_fetchrow($res)){
				$tmp = "";
				if($id == $this->data['anoId']){
					$tmp = " SELECTED ";
				}
				$buffer .= "<option value='".$id."' ".$tmp.">".utf8_encode($id)."</option>";				
			}
			$this->arrays[0] = $buffer;
		}
	}
	
	function catEjes(){
		$buffer = $tmp = "";
		$sql="SELECT eje_id,nombre FROM cat_ejes WHERE active = '1' ORDER BY eje_id;";
		$res=$this->db->sql_query($sql) or die($this->cadena_error);
		if($this->db->sql_numrows ( $res )>0){
			while(list($id,$nm) = $this->db->sql_fetchrow($res)){
				$tmp = "";
				if($id == $this->data['ejeId']){
					$tmp = " SELECTED ";
				}
				$buffer .= "<option value='".$id."' ".$tmp.">".utf8_encode($nm)."</option>";
			}
			$this->arrays[1] = $buffer; 
		}			
	}
	
	function catProgramas(){
		$buffer = $tmp = "";
		$sql="SELECT programa_id,nombre FROM cat_programas WHERE active = '1' ORDER BY programa_id;";
		$res=$this->db->sql_query($sql) or die($this->cadena_error);
		if($this->db->sql_numrows ( $res )>0){
			while(list($id,$nm) = $this->db->sql_fetchrow($res)){
				$tmp = "";
				if($id == $this->data['programaId']){
					$tmp = " SELECTED ";
				}
				$buffer .= "<option value='".$id."' ".$tmp.">".utf8_encode($nm)."</option>";
			}
			$this->arrays[2] = $buffer;
		}
	}
	
	function catAreas(){
		$buffer = $tmp = "";
		$sql="SELECT area_id,nombre FROM cat_areas WHERE active = '1' ORDER BY area_id;";
		$res=$this->db->sql_query($sql) or die($this->cadena_error);
		if($this->db->sql_numrows ( $res )>0){
			while(list($id,$nm) = $this->db->sql_fetchrow($res)){
				$tmp = "";
				if($id == $this->data['areaId']){
					$tmp = " SELECTED ";
				}
				$buffer .= "<option value='".$id."' ".$tmp.">".utf8_encode($nm)."</option>";
			}
			$this->arrays[3] = $buffer;
		}
	}
	
	function catProyectos(){
		$buffer = $tmp = "";
		$sql="SELECT id,proyecto FROM proyectos_acciones WHERE active = '1' AND ano_id='".$this->data['anoId']."' ORDER BY proyecto;";
		$res=$this->db->sql_query($sql) or die($this->cadena_error);
		if($this->db->sql_numrows ( $res )>0){
			while(list($id,$nm) = $this->db->sql_fetchrow($res)){
				$tmp = "";
				if($id == $this->data['proyectoId']){
					$tmp = " SELECTED ";
				}
				$buffer .= "<option value='".$id."' ".$tmp.">".utf8_encode($nm)."</option>";
			}
			$this->arrays[5] = $buffer;
		}
	}
	function regresaEjePoliticaProg(){
		$arrayEjes=array();
		$arrayPoli=array();
		$cadenaEjes="";
		$cadenaPoli="";
		if( ($this->data['areaId'] > 0) && ($this->data['programaId'] > 0) ){
			$sql="SELECT b.nombre as politica,c.nombre as eje
				  FROM cat_politica_programa as a LEFT JOIN cat_politicas as b ON a.politica_id=b.politica_id
				  LEFT JOIN cat_ejes as c ON b.eje_id=c.eje_id
				  WHERE a.programa_id='".$this->data['programaId']."' ORDER BY c.nombre,b.nombre;";
			$res=$this->db->sql_query($sql) or die($this->cadena_error);
			if($this->db->sql_numrows ( $res )>0){
				while(list($nmPol,$nmEje) = $this->db->sql_fetchrow($res)){
					if(!in_array($nmPol,$arrayPoli)){
						$cadenaPoli.=utf8_encode($nmPol)."<br>";
						$arrayPoli[]=$nmPol;
					}
					if(!in_array($nmEje,$arrayEjes)){
						$cadenaEjes.=utf8_encode($nmEje)."<br>";
						$arrayEjes[]=$nmEje;
					}
				}
			}			
		}
		$this->buffer=$cadenaEjes."|".$cadenaPoli;
	}
	
	function regresaEjePolitica(){
		$arrayEjes=array();
		$arrayPoli=array();
		$cadenaEjes="";
		$cadenaPoli="";		
		if($this->data['areaId'] > 0){
			$sql="SELECT b.nombre as politica,c.nombre as eje 
				  FROM cat_politica_area as a LEFT JOIN cat_politicas as b ON a.politica_id=b.politica_id 
				  LEFT JOIN cat_ejes as c ON b.eje_id=c.eje_id	
				  WHERE a.area_id='".$this->data['areaId']."' ORDER BY c.nombre,b.nombre;";
			$res=$this->db->sql_query($sql) or die($this->cadena_error);
			if($this->db->sql_numrows ( $res )>0){
				while(list($nmPol,$nmEje) = $this->db->sql_fetchrow($res)){
					if(!in_array($nmPol,$arrayPoli)){
						$cadenaPoli.=utf8_encode($nmPol)."<br>";
						$arrayPoli[]=$nmPol;
					}
					if(!in_array($nmEje,$arrayEjes)){
						$cadenaEjes.=utf8_encode($nmEje)."<br>";
						$arrayEjes[]=$nmEje;
					}	
				}
			}
		}
		$this->buffer=$cadenaEjes."|".$cadenaPoli;
	}
	
	function regresaMetodosParticipacion(){
		$this->buffer = "";
		$sql="select metodo_id,nombre FROM cat_metodo_participacion where active='1' order by orden;";
		$res=$this->db->sql_query($sql) or die($this->cadena_error);
		$num = $this->db->sql_numrows ( $res );
		$this->buffer .= "<option value='0'>Seleccione</option>";
		if ($num > 0) {
			while ( list ( $_id,$metodo) = $this->db->sql_fetchrow ( $res ) ) {
				$tmp = "";
				if ($_id == ($arrayDatos['participacion']+0))
					$tmp = " SELECTED ";
				$this->buffer.="<option value='$_id' class='seleccione' ".$tmp.">".utf8_encode($metodo)."</option>";
			}
		}
	}
	
	function regresaUnidadOperativa() {
		$this->buffer = "";
		$this->buffer .= "<option value='0'>" . SELECCIONE . "</option>";
		if ($this->data ['areaId'] > 0) {
			$sql = "SELECT a.unidad_id,b.nombre FROM cat_area_unidad_operativa as a inner join cat_unidad_operativas as b
					ON a.unidad_id = b.unidad_id 
					WHERE b.active='1' AND a.area_id='" . $this->data ['areaId']."'  
					ORDER BY b.nombre;";
			$res = $this->db->sql_query ( $sql );
			if ($this->db->sql_numrows ( $res ) > 0) {
				while ( list ( $id, $nm ) = $this->db->sql_fetchrow ( $res ) ) {
					$this->buffer .= "<option value='".$id."'>".utf8_encode($nm)."</option>";
				}
			}
		}
	}
	function regresaResponsableUnidadOperativa() {
		$this->buffer = "";
		$this->buffer .= "<option value='0'>" . SELECCIONE . "</option>";
		if ($this->data ['idunidadoperativa'] > 0) {
			$sql = "SELECT id,nombre FROM cat_unidad_operativa_responsables WHERE active='1'
            AND unidad_id='" . $this->data ['idunidadoperativa'] . "' ORDER BY nombre;";
			$res = $this->db->sql_query ( $sql );
			if ($this->db->sql_numrows ( $res ) > 0) {
				while ( list ( $id, $nm ) = $this->db->sql_fetchrow ( $res ) ) {
					$this->buffer .= "<option value='" . $id . "' " . $tmp . ">" . utf8_encode ( $nm ) . "</option>";
				}
			}
		}
	}
	function regresaPoliticas() {
		$this->buffer = "";
		if ($this->data ['ejeId'] > 0) {
			$sql = "SELECT distinct(a.politica_id),a.nombre FROM cat_politicas as a
            WHERE a.active='1' AND a.eje_id='" . $this->data ['ejeId'] . "' ORDER BY a.nombre;";
			$res = $this->db->sql_query ( $sql ) or die ( "error:  " . $sql );
			if ($this->db->sql_numrows ( $res ) > 0) {
				$this->buffer .= "<option value='0'>Seleccione</option>";
				while ( list ( $id, $nm ) = $this->db->sql_fetchrow ( $res ) ) {
					$this->buffer .= "<option value='" . $id . "' " . $tmp . ">" . utf8_encode ( $nm ) . "</option>";
				}
			}
		}
	}
	function regresaAreas() {
		$this->buffer = "";
		if ($this->data ['politicaId'] > 0) {
			$sql = "SELECT distinct(a.area_id),b.nombre FROM cat_politica_area as a LEFT JOIN cat_areas as b
            ON a.area_id=b.area_id WHERE b.active='1' AND a.politica_id='" . $this->data ['politicaId'] . "' ORDER BY b.orden;";
			$res = $this->db->sql_query ( $sql ) or die ( "error:  " . $sql );
			if ($this->db->sql_numrows ( $res ) > 0) {
				$this->buffer .= "<option value='0'>Seleccione</option>";
				while ( list ( $id, $nm ) = $this->db->sql_fetchrow ( $res ) ) {
					$this->buffer .= "<option value='" . $id . "' " . $tmp . ">" . utf8_encode ( $nm ) . "</option>";
				}
			}
		}
	}
	function regresaProgramas() {
		$this->buffer = "";
		$filtro="";
		if($this->session['programas']!= ""){
			$filtro.=" AND a.programa_id IN (".$this->session['programas'].") ";
		}
		if ($this->data ['areaId'] > 0) {
			$sql = "SELECT distinct(a.programa_id),b.nombre FROM cat_area_programa as a LEFT JOIN cat_programas as b
            ON a.programa_id=b.programa_id WHERE b.active='1' AND a.area_id='" . $this->data ['areaId'] . "' ".$filtro." 
            ORDER BY b.nombre;";	
			$res = $this->db->sql_query ( $sql ) or die ( "error:  " . $sql );
			$this->buffer .= "<option value='0'>Seleccione</option>";
			if ($this->db->sql_numrows ( $res ) > 0) {
				
				while ( list ( $id, $nm ) = $this->db->sql_fetchrow ( $res ) ) {
					$this->buffer .= "<option value='" . $id . "' " . $tmp . ">" . utf8_encode ( $nm ) . "</option>";
				}
			}
		}
	}
	function regresaObjetivosGenerales() {
		$this->buffer = "";
		if (($this->data ['areaId'] > 0) && ($this->data ['programaId'] > 0)) {
			$sql = "SELECT objetivo_id,nombre FROM cat_objetivos_generales WHERE active='1' 
            AND area_id='" . $this->data ['areaId'] . "' AND programa_id='" . $this->data ['programaId'] . "'  ORDER BY nombre;";
			$res = $this->db->sql_query ( $sql );
			if ($this->db->sql_numrows ( $res ) > 0) {
				$this->buffer .= "<option value='0'>Seleccione</option>";
				while ( list ( $id, $nm ) = $this->db->sql_fetchrow ( $res ) ) {
					$this->buffer .= "<option value='" . $id . "' " . $tmp . ">" . utf8_encode ( $nm ) . "</option>";
				}
			}
		}
	}
	function regresaProyectos() {
		$this->buffer = "";
		if (($this->data ['areaId'] > 0) && ($this->data ['programaId'] > 0)) {
			$sql = "SELECT subprograma_id,subprograma FROM cat_subprogramas WHERE active='1' 
            AND area_id='" . $this->data ['areaId'] . "' AND programa_id='" . $this->data ['programaId'] . "'
            ORDER BY orden;";
			$res = $this->db->sql_query ( $sql );
			if ($this->db->sql_numrows ( $res ) > 0) {
				$this->buffer .= "<option value='0'>Seleccione</option>";
				while ( list ( $id, $nm ) = $this->db->sql_fetchrow ( $res ) ) {
					$this->buffer .= "<option value='" . $id . "' " . $tmp . ">" . utf8_encode ( $nm ) . "</option>";
				}
			}
		}
	}
	function regresaProyectosTabla(){
		$this->buffer = "";
		$ext="*".rand(1,999999999999);
		$contador=1;
		if (($this->data ['areaId'] > 0) && ($this->data ['programaId'] > 0) && ($this->data['idano'] > 0)) {
			$sql = "SELECT id,proyecto,fecha_alta FROM proyectos_acciones WHERE active='1' 
            AND unidadResponsable_id='" . $this->data ['areaId'] . "' AND programa_id='" . $this->data ['programaId'] . "'
            AND YEAR(fecha_alta) = '".$this->data['idano']."' ORDER BY fecha_alta;";
			$res = $this->db->sql_query ( $sql );
			if ($this->db->sql_numrows ( $res ) > 0) {
				$this->buffer = "
					<table width='60%' align='center' class='table'>
						<thead><tr>
							<td class='tdleft' colspan='2'>".PROYECTOS."</td>
							<td class='tdleft' width='12%'>".FECHAALTA."</td>
							<td class='tdleft' width='12%'>".EDITAR."</td>
							<td class='tdleft' width='12%'>".ACTIVIDADES."</td>
						</tr></thead>";
				while ( list ( $id, $nm, $fec ) = $this->db->sql_fetchrow ( $res ) ) {
					$this->buffer.= "<tr>
									<td class='tdleft' width='5%'>".$contador.".- </td>
									<td class='tdleft'><a href='".$_SELF."?aplicacion=".$this->session['aplicacion']."&
									apli_com=".$this->session['apli_com']."&opc=7&id=".$id.$ext."'>".utf8_encode( $nm )."</a>
									</td>
									<td class='tdcenter'>".substr($fec,0,10)."</td>
									<td class='tdcenter'></td>
									<td class='tdcenter'></td>
									</tr>";
				}
				$this->buffer.="</table>";
			}
		}
	}
	function obtenBuffer() {
		return $this->buffer;
	}
	function obtenArray(){
		return $this->arrays;
	}
}
?>