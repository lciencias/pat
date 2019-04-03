<?php
class GeneraEstadisticas extends ComunesEstadisticas{
	var $db;
	var $data;
	var $session;
	var $path;
	var $filtro;
	var $buffer;
	var $bufferXml;
	var $tabla;
	var $cadena;
	var $arrayDatos;
	var $breadcrumb;
	var $titulo;
	var $xml;
	var $xmlPor;
	
	function __construct($db,$data,$session,$path){
		$this->db      = $db;
		$this->data    = $data;
		$this->session = $session;
		$this->path    = $path;
		$this->filtro  = "";
		$this->buffer  = "";
		$this->bufferXml = "";
		$this->tabla   = "";
		$this->titulo  = "";
		$this->xml     = "";
		$this->xmlPor  = "";
		$this->breadcrumb = "<a href='".$this->path."index.php'>  / Inicio </a> ";
		$this->arrayDatos = array();
		$this->cadena  = "<script>location.href='".$this->path."'</script>";
		$this->numericos();
		if((int) $this->data['anoId'] == 0){
			$this->data['anoId'] = date('Y');
		}
		if((int) $this->data['trimestreId'] == 0){
			$this->data['trimestreId'] = 1;
		}
		if((int) $this->data['trimestreId'] > 4){
			$this->data['trimestreId'] = 1;
		}
		if((int) $this->data['tipoId'] == 0){
			$this->data['tipoId'] = 1;
		}
		if((int) $this->data['tipoId'] > 2){
			$this->data['tipoId'] = 1;
		}		
		
		$this->recuperaTabla();
		$this->actualizaTipo2();
		if(trim($this->tabla) != ""){
			if((int) $this->data['tipoId'] <=1){
				$this->catalogoA = $this->catEjes();
				$this->catalogoB = $this->catProgramas();
				if( ((int)$this->data['idEje'] == 0) && ((int)$this->data['idPrograma'] == 0) && ((int)$this->data['idProyecto'] == 0) ){
					$this->generaFiltro();
					$this->generaTablaEje();
				}
				if( ((int)$this->data['idEje'] > 0) && ((int)$this->data['idPrograma'] == 0) && ((int)$this->data['idProyecto'] == 0) ){
					$this->generaFiltro();
					$this->generaTablaEjePrograma();
				}
				if( ((int)$this->data['idEje'] > 0) && ((int)$this->data['idPrograma'] > 0) && ((int)$this->data['idProyecto'] == 0) ){
					$this->generaFiltro();
					$this->generaTablaEjeProgramaProyecto();
				}
				if( ((int)$this->data['idEje'] > 0) && ((int)$this->data['idPrograma'] > 0) && ((int)$this->data['idProyecto'] > 0) ){
					$this->catalogoD = $this->catProyectos();
					$this->generaFiltro();
					$this->generaTablaActividades();
				}
					
			}else{
				$this->catalogoC = $this->catAreas();
				if( ((int)$this->data['idArea'] == 0)  && ((int)$this->data['idProyecto'] == 0) ){
					$this->generaFiltro();
					$this->generaTablaArea();
				}
				if( ((int)$this->data['idArea'] > 0)  && ((int)$this->data['idProyecto'] == 0) ){
					$this->generaFiltro();
					$this->generaTablaAreaProyecto();
				}
				if( ((int)$this->data['idArea'] > 0)  && ((int)$this->data['idProyecto'] > 0) ){
					$this->catalogoD = $this->catProyectos();
					$this->generaFiltro();
					$this->generaTablaActividades();
				}
			}
		}else{
			$this->buffer = "Sitio en mantenimiento, en breve regresaremos";
		}
	}
	
	function numericos(){
		if( !is_numeric($this->data['tablaId'])){
			$this->recuperaDefault();
		}

		if( !is_numeric($this->data['anoId'])){
			$this->data['anoId'] = date('Y');
		}
		
		if( !is_numeric($this->data['trimestreId'])){
			$this->data['trimestreId'] = 0;
		}
		
		if( !is_numeric($this->data['idEje'])){
			$this->data['idEje']=0;
		}
		
		if( !is_numeric($this->data['idPrograma'])){
			$this->data['idPrograma']=0;
		}
		
		if( !is_numeric($this->data['idArea'])){
			$this->data['idArea'] = 0;
		}
		
		if( !is_numeric($this->data['idProyecto'])){
			$this->data['idProyecto'] = 0;
		}	
	}
	function generaTablaActividades(){
		$this->titulo  = "<b>Actividades realizadas</b><br>";
		$this->arrayDatos = array();
		$sql = "SELECT actividadId,actividad,ponderacionActividad,tipo_actividad_id,medida,
				trimestre1,trimestre2,trimestre3,trimestre4,total,
				Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4,totalAvance
				FROM ".$this->tabla." WHERE eje_id >0 and programa_id > 0 ".$this->filtro." ORDER BY actividadId,actividad;";
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($id,$act,$pon,$tipoAct,$medida,$t1,$t2,$t3,$t4,$t5,$a1,$a2,$a3,$a4,$a5) = $this->db->sql_fetchrow($res)){
				$this->arrayDatos[$id]['id']  = $id;
				$this->arrayDatos[$id]['act'] = utf8_encode($act);
				$this->arrayDatos[$id]['pon'] = $pon;
				$this->arrayDatos[$id]['tAc'] = $tipoAct;
				$this->arrayDatos[$id]['med'] = utf8_encode($medida);
				$this->arrayDatos[$id]['t1']  = $t1;
				$this->arrayDatos[$id]['t2']  = $t2;
				$this->arrayDatos[$id]['t3']  = $t3;
				$this->arrayDatos[$id]['t4']  = $t4;
				$this->arrayDatos[$id]['t5']  = $t5;
				$this->arrayDatos[$id]['a1']  = $a1;
				$this->arrayDatos[$id]['a2']  = $a2;
				$this->arrayDatos[$id]['a3']  = $a3;
				$this->arrayDatos[$id]['a4']  = $a4;
				$this->arrayDatos[$id]['a5']  = $a5;
				//$this->arrayDatos[$id]['avance']  = $avance;
			}
			$this->generaCuadroActividades();
		}else{
			$this->titulo = "";
			$this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
		}
	}
	function generaCuadroActividades(){
		$tablaC = str_replace("view_","view_c_",$this->tabla);
    	$tablaA = str_replace("view_","view_a_",$this->tabla);    		
		$this->buffer = $active = $this->xml = "";
		$contador = 0;
		$this->buffer .='<div id="prefijos" class="tdleft">
							<b>P</b> = Programado
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>R</b> = Reportado
						</div><br>';
		$this->buffer .='<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
		foreach($this->arrayDatos as $idAct => $tmpDatos){
			$arrayComentarios = $this->regresaDatosComentarios($this->data['idProyecto'],$tablaC,$idAct);
			$comentarios      = $this->regresaComentarios($arrayComentarios);   
			$arrayAdjuntos    = $this->regresaDatosAdjuntos($this->data['idProyecto'],$tablaA,$idAct);
			$adjuntos         = $this->regresaAdjuntos($arrayAdjuntos);
			$active = "";
			if($contador == 0){
				$active = " in ";
			}
			$this->buffer.='
				<div class="panel panel-default">
    				<div class="panel-heading tdleft" role="tab" id="heading'.$idAct.'" >
      					<h4 class="panel-title tdleft">'.($contador + 1).'&nbsp;.-&nbsp;
        					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idAct.'" aria-expanded="true" aria-controls="collapse'.$idAct.'">
          						'.$tmpDatos['act'].'
        					</a>
      					</h4>
    				</div>
    				<div id="collapse'.$idAct.'" class="panel-collapse collapse '.$active.'" role="tabpanel" aria-labelledby="heading'.$idAct.'">
      					<div class="panel-body tdjustify">
						<table width="100%" class="table">
                    	<tr class="success">
    	                    <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE1C.'</td>
        	                <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE2C.'</td>
            	            <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE3C.'</td>
                	        <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE4C.'</td>
                    	    <td colspan="2" class="tdcenter" width="14%">'.TOTAL.'</td>
                        	<td class="tdcenter" width="10%">'.MEDIDA.'</td>
                        	<td class="tdcenter" width="10%">'.PONDERACION.'</td>
                        	<td class="tdcenter" width="10%">'.TIPOACT.'</td>
                    	</tr>
                    	<tr class="warning">
	                        <td class="tdcenter" width="7%">'.P.'</td>
	                        <td class="tdcenter" width="7%">'.R.'</td>
	                        <td class="tdcenter" width="7%">'.P.'</td>
	                        <td class="tdcenter" width="7%">'.R.'</td>
	                        <td class="tdcenter" width="7%">'.P.'</td>
	                        <td class="tdcenter" width="7%">'.R.'</td>
	                        <td class="tdcenter" width="7%">'.P.'</td>
	                        <td class="tdcenter" width="7%">'.R.'</td>
	                        <td class="tdcenter" width="7%">'.P.'</td>
	                        <td class="tdcenter" width="7%">'.R.'</td>
                        	<td class="tdcenter" width="10%">&nbsp;</td>
                        	<td class="tdcenter" width="10%">&nbsp;</td>
                        	<td class="tdcenter" width="10%">&nbsp;</td>	   
	                    </tr>
                    	<tr>
	                        <td class="tdcenter" width="7%">'.$tmpDatos['t1'].'</td>
	                        <td class="tdcenter" width="7%">'.$tmpDatos['a1'].'</td>
	                        <td class="tdcenter" width="7%">'.$tmpDatos['t2'].'</td>
	                        <td class="tdcenter" width="7%">'.$tmpDatos['a2'].'</td>
	                        <td class="tdcenter" width="7%">'.$tmpDatos['t3'].'</td>
	                        <td class="tdcenter" width="7%">'.$tmpDatos['a3'].'</td>
	                        <td class="tdcenter" width="7%">'.$tmpDatos['t4'].'</td>
	                        <td class="tdcenter" width="7%">'.$tmpDatos['a4'].'</td>
	                        <td class="tdcenter" width="7%">'.$tmpDatos['t5'].'</td>
	                        <td class="tdcenter" width="7%">'.$tmpDatos['a5'].'</td>
							<td class="tdcenter" width="10%">'.$tmpDatos['med'].'</td>
                        	<td class="tdcenter" width="10%">'.$tmpDatos['pon'].'</td>
                        	<td class="tdcenter" width="10%">Tipo Actividad '.$tmpDatos['tAc'].'</td>	  
                        	
	                    </tr>
					</table>';
			if(trim($comentarios) != ""){
				$this->buffer.='<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapseComentarios'.$idAct.'" aria-expanded="false" aria-controls="collapseComentarios'.$idAct.'">&nbsp;&nbsp;Comentarios&nbsp;&nbsp;</button>
						<div class="collapse" id="collapseComentarios'.$idAct.'">
	  						<div class="well">'.$comentarios.'</div>
						</div>';
			}
			$this->buffer.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			if(trim($adjuntos) != ""){
				$this->buffer.='
						<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapseAdjuntos'.$idAct.'" aria-expanded="false" aria-controls="collapseAdjuntos'.$idAct.'">&nbsp;&nbsp;Adjuntos&nbsp;&nbsp;</button>
						<div class="collapse" id="collapseAdjuntos'.$idAct.'">
	  						<div class="well">'.$adjuntos.'</div>
						</div>';
			}
			$this->buffer.='
      					</div>
    				</div>
  				</div>';
			$contador++;
		}
		$this->buffer .='</div>';
	}
	
	function recuperaDefault(){
		$sql = "SELECT id,tabla FROM cat_tablas WHERE defa = '1' LIMIT 1;";
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res) > 0){
			list($this->data['tablaId'],$this->tabla) = $this->db->sql_fetchrow($res);
			$this->tabla = strtolower($this->tabla);
		}
	}
	
	function recuperaTabla(){
		if((int) $this->data['tablaId'] > 0){
			$sql = "SELECT id,tabla FROM cat_tablas WHERE id = '".$this->data['tablaId']."' LIMIT 1;";
		}else{
			$sql = "SELECT id,tabla FROM cat_tablas WHERE defa = '1' LIMIT 1;";
		}		
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res) > 0){
			list($this->data['tablaId'],$this->tabla) = $this->db->sql_fetchrow($res);
			$this->tabla = strtolower($this->tabla);
		}else{
			$this->recuperaDefault();
		}
	}
	function actualizaTipo2(){
		$upd  = "UPDATE ".$this->tabla." set trimestre1 = Atrimestre1,
				trimestre2 = Atrimestre2,
				trimestre3 = Atrimestre3,
				trimestre4 = Atrimestre4,
				total = totalAvance WHERE tipo_actividad_id = '2';";
		$this->db->sql_query($upd) or die($this->cadena);
	}
	
	function generaTablaEjeProgramaProyecto(){
		$this->titulo  = "Resumen por Programa ".$this->data['idPrograma'].": <b>".$this->catalogoB[$this->data['idPrograma']]."</b>";
		$this->arrayDatos = $arrayAct = array();
		$campos = $this->regresaTrimestre($this->data['trimestreId']);
		//--if(v.programadas = 0,0,((v.realizadas / v.programadas) * v.ponderacionActividad)) as ponderacion
		$sql = "select v.id,v.proyecto,v.actividadId,v.area,v.ponderacionActividad,
				
				case 
					when (v.programadas = 0 && v.realizadas = 0) then (1 * v.ponderacionActividad)
					when (v.programadas = 0 && v.realizadas > 0) then ((v.realizadas / v.realizadas) * v.ponderacionActividad)
					when (v.programadas > 0 && v.realizadas = 0) then ((v.realizadas / v.programadas) * v.ponderacionActividad) 
					else ((v.realizadas / v.programadas) * v.ponderacionActividad) 
				end as ponderacion".$campos[2].$campos[3]."
				from (
						SELECT id,proyecto,actividadId,area,ponderacionActividad".$campos[0].$campos[1]."
						FROM ".$this->tabla." WHERE eje_id >0 and programa_id > 0 ".$this->filtro."
					)
			as v ORDER BY v.proyecto;";
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($id,$proyecto,$actividadId,$area,$ponderacion,$promedio) = $this->db->sql_fetchrow($res)){
				$this->arrayDatos[$id]['proyecto']   = utf8_encode($proyecto);
				$this->arrayDatos[$id]['area']   = utf8_encode($area);
				if(!in_array($actividadId, $arrayAct)){
					$arrayAct[] = $actividadId;
					$this->arrayDatos[$id]['actividad_id'] = $this->arrayDatos[$id]['actividad_id'] + 1;
					$this->arrayDatos[$id]['ponderacion']  = $this->arrayDatos[$id]['ponderacion'] + $ponderacion;
					$this->arrayDatos[$id]['promedio']     = $this->arrayDatos[$id]['promedio'] + $promedio;
						
				}
			}
			$this->generaCuadroEjeProgramaProyecto();
		}else{
			$this->titulo = "";
			$this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
		}
		
	}
	
	function generaTablaEjePrograma(){
		$this->titulo  = "Resumen por Eje ".$this->data['idEje'].": <b>".$this->catalogoA[$this->data['idEje']]."</b>";
		$this->arrayDatos = $arrayProye = array();
		$campos = $this->regresaTrimestre($this->data['trimestreId']);
		//--if(v.programadas = 0,0,((v.realizadas / v.programadas) * v.ponderacionActividad)) as ponderacion
		$sql = "select v.programa_id,v.programa,v.id,v.proyecto,v.actividadId,v.actividad,v.ponderacionActividad,
				case 
					when (v.programadas = 0 && v.realizadas = 0) then (1 * v.ponderacionActividad)
					when (v.programadas = 0 && v.realizadas > 0) then ((v.realizadas / v.realizadas) * v.ponderacionActividad)
					when (v.programadas > 0 && v.realizadas = 0) then ((v.realizadas / v.programadas) * v.ponderacionActividad) 
					else ((v.realizadas / v.programadas) * v.ponderacionActividad) 
				end as ponderacion".$campos[2].$campos[3]."
				
				from (
						SELECT programa_id,programa,id,proyecto,actividadId,actividad,ponderacionActividad".$campos[0].$campos[1]."
						FROM ".$this->tabla." WHERE eje_id >0 and programa_id > 0 ".$this->filtro."
					)
			as v ORDER BY v.programa_id,v.proyecto;";		
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($programa_id,$programa,$id,$proyecto,$actividadId,$actividad,$ponderacion,$promedio) = $this->db->sql_fetchrow($res)){
				$this->arrayDatos[$programa_id]['programa']   = utf8_encode($programa);
				if(!in_array($id, $arrayProye)){
					$arrayProye[] = $id;
					$this->arrayDatos[$programa_id]['proyecto_id'] = $this->arrayDatos[$programa_id]['proyecto_id'] + 1;
				}
				$this->arrayDatos[$programa_id]['actividad_id'] = $this->arrayDatos[$programa_id]['actividad_id'] + 1;
				$this->arrayDatos[$programa_id]['ponderacion']  = $this->arrayDatos[$programa_id]['ponderacion'] + $ponderacion;
				$this->arrayDatos[$programa_id]['promedio']     = $this->arrayDatos[$programa_id]['promedio'] + $promedio;
				
			}
			$this->generaCuadroEjePrograma();
		}else{
			$this->titulo = "";
			$this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
		}
	}
	
	function regresaTrimestre($trimestreId){
		$array = array();
		switch($trimestreId){
			case 0;
				$array[0] = ",trimestre1,trimestre2,trimestre3,trimestre4,(trimestre1+trimestre2+trimestre3+trimestre4) as programadas, "; 
				$array[1] = "Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4,(Atrimestre1+Atrimestre2+Atrimestre3+Atrimestre4) as realizadas";
				$array[2] = ",v.trimestre1,v.trimestre2,v.trimestre3,v.trimestre4,(v.trimestre1+v.trimestre2+v.trimestre3+v.trimestre4) as programadas, ";
				$array[3] = "v.Atrimestre1,v.Atrimestre2,v.Atrimestre3,v.Atrimestre4,(v.Atrimestre1+v.Atrimestre2+v.Atrimestre3+v.Atrimestre4) as realizadas";					
				break;
			case 1;
				$array[0] = ",trimestre1,(trimestre1) as programadas, ";
				$array[1] = "Atrimestre1,(Atrimestre1) as realizadas";
				$array[2] = ",v.trimestre1,(v.trimestre1) as programadas, ";
				$array[3] = "v.Atrimestre1,(v.Atrimestre1) as realizadas";				
				break;
			case 2;
				$array[0] = ",trimestre1,trimestre2,(trimestre1+trimestre2) as programadas, ";
				$array[1] = "Atrimestre1,Atrimestre2,(Atrimestre1+Atrimestre2) as realizadas";		
				$array[2] = ",v.trimestre1,v.trimestre2,(v.trimestre1+v.trimestre2) as programadas, ";
				$array[3] = "v.Atrimestre1,v.Atrimestre2,(v.Atrimestre1+v.Atrimestre2) as realizadas";				
				break;			
			case 3;
				$array[0] = ",trimestre1,trimestre2,trimestre3,(trimestre1+trimestre2+trimestre3) as programadas, ";
				$array[1] = "Atrimestre1,Atrimestre2,Atrimestre3,(Atrimestre1+Atrimestre2+Atrimestre3) as realizadas";
				$array[2] = ",v.trimestre1,v.trimestre2,v.trimestre3,(v.trimestre1+v.trimestre2+v.trimestre3) as programadas, ";
				$array[3] = "v.Atrimestre1,v.Atrimestre2,v.Atrimestre3,(v.Atrimestre1+v.Atrimestre2+v.Atrimestre3) as realizadas";				
				break;			
			case 4;
				$array[0] = ",trimestre1,trimestre2,trimestre3,trimestre4,(trimestre1+trimestre2+trimestre3+trimestre4) as programadas, ";
				$array[1] = "Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4,(Atrimestre1+Atrimestre2+Atrimestre3+Atrimestre4) as realizadas";
				$array[2] = ",v.trimestre1,v.trimestre2,v.trimestre3,v.trimestre4,(v.trimestre1+v.trimestre2+v.trimestre3+v.trimestre4) as programadas, ";
				$array[3] = "v.Atrimestre1,v.Atrimestre2,v.Atrimestre3,v.Atrimestre4,(v.Atrimestre1+v.Atrimestre2+v.Atrimestre3+v.Atrimestre4) as realizadas";				
				break;
			default: 
				$array[0] = ",trimestre1,trimestre2,trimestre3,trimestre4,(trimestre1+trimestre2+trimestre3+trimestre4) as programadas, ";
				$array[1] = "Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4,(Atrimestre1+Atrimestre2+Atrimestre3+Atrimestre4) as realizadas";
				$array[2] = ",v.trimestre1,v.trimestre2,v.trimestre3,v.trimestre4,(v.trimestre1+v.trimestre2+v.trimestre3+v.trimestre4) as programadas, ";
				$array[3] = "v.Atrimestre1,v.Atrimestre2,v.Atrimestre3,v.Atrimestre4,(v.Atrimestre1+v.Atrimestre2+v.Atrimestre3+v.Atrimestre4) as realizadas";			
				break;				
		}
		$array[4] = ",v.trimestre1,v.trimestre2,v.trimestre3,v.trimestre4,(v.trimestre1+v.trimestre2+v.trimestre3+v.trimestre4) as programadas, ";
		$array[5] = "v.Atrimestre1,v.Atrimestre2,v.Atrimestre3,v.Atrimestre4,(v.Atrimestre1+v.Atrimestre2+v.Atrimestre3+v.Atrimestre4) as realizadas";
		$array[6] = ",trimestre1,trimestre2,trimestre3,trimestre4,(trimestre1+trimestre2+trimestre3+trimestre4) as programadas, ";
		$array[7] = "Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4,(Atrimestre1+Atrimestre2+Atrimestre3+Atrimestre4) as realizadas";
		
		return $array;
	}
	
	/**
	 * Metodo que genera las estadisticas por eje
	 */
	function generaTablaEje(){
		$this->titulo  = "Tablero de Control ".$this->data['anoId'].": <b>Resumen General</b>";		
		$this->arrayDatos = $arrayProgr = $arrayProye = array();
		$campos = $this->regresaTrimestre($this->data['trimestreId']);
		$sql = "select v.eje_id,v.eje,v.programa_id,v.id,v.actividadId,v.ponderacionActividad,
				case 
					when (v.programadas = 0 && v.realizadas = 0) then (1 * v.ponderacionActividad)
					when (v.programadas = 0 && v.realizadas > 0) then ((v.realizadas / v.realizadas) * v.ponderacionActividad)
					when (v.programadas > 0 && v.realizadas = 0) then ((v.realizadas / v.programadas) * v.ponderacionActividad) 
					else ((v.realizadas / v.programadas) * v.ponderacionActividad) 
				end as ponderacion".$campos[2].$campos[3]."
				from (
						SELECT eje_id,eje,programa_id,id,actividadId,ponderacionActividad".$campos[0].$campos[1]." 
						FROM ".$this->tabla." WHERE eje_id > 0 ".$this->filtro."  
					) 
			as v ORDER BY v.eje_id;";
		//die($sql);
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($eje_id,$eje,$programa_id,$id,$actividadId,$ponderacion,$promedio) = $this->db->sql_fetchrow($res)){
				$this->arrayDatos[$eje_id]['eje']   = utf8_encode($eje);
				if(!in_array($programa_id, $arrayProgr)){
					$arrayProgr[] = $programa_id;
					$this->arrayDatos[$eje_id]['programa_id'] = $this->arrayDatos[$eje_id]['programa_id'] + 1;
				}
				if(!in_array($id, $arrayProye)){
					$arrayProye[] = $id;
					$this->arrayDatos[$eje_id]['proyecto_id'] = $this->arrayDatos[$eje_id]['proyecto_id'] + 1;
				}
				$this->arrayDatos[$eje_id]['actividad_id'] = $this->arrayDatos[$eje_id]['actividad_id'] + 1;
				$this->arrayDatos[$eje_id]['ponderacion']  = $this->arrayDatos[$eje_id]['ponderacion'] + $ponderacion;
				$this->arrayDatos[$eje_id]['promedio']     = $this->arrayDatos[$eje_id]['promedio'] + $promedio;
			}
			$this->generaCuadroEje();
		}else{
			$this->titulo = "";
			$this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
		}
	}
	
	function generaTablaArea(){
		$this->titulo  = "Resumen General por <b>Unidad Responsable</b>";
		$this->arrayDatos = $arrayProgr = $arrayProye = array();
		$campos = $this->regresaTrimestre($this->data['trimestreId']);
		//--if(v.programadas = 0,0,((v.realizadas / v.programadas) * v.ponderacionActividad)) as ponderacion
		$sql = "select v.unidadResponsableId,v.area,v.programa_id,v.id,v.actividadId,v.ponderacionActividad,				
				case 
					when (v.programadas = 0 && v.realizadas = 0) then (1 * v.ponderacionActividad)
					when (v.programadas = 0 && v.realizadas > 0) then ((v.realizadas / v.realizadas) * v.ponderacionActividad)
					when (v.programadas > 0 && v.realizadas = 0) then ((v.realizadas / v.programadas) * v.ponderacionActividad) 
					else ((v.realizadas / v.programadas) * v.ponderacionActividad) 
				end as ponderacion".$campos[2].$campos[3]."
				from (
						SELECT unidadResponsableId,area,programa_id,id,actividadId,ponderacionActividad".$campos[0].$campos[1]."
						FROM ".$this->tabla." WHERE unidadResponsableId > 0 ".$this->filtro."
					)
			as v ORDER BY v.area;";		
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($area_id,$area,$programa_id,$id,$actividadId,$ponderacion,$promedio) = $this->db->sql_fetchrow($res)){
				$this->arrayDatos[$area_id]['area']   = utf8_encode($area);
				if(!in_array($programa_id, $arrayProgr)){
					$arrayProgr[] = $programa_id;
					$this->arrayDatos[$area_id]['programa_id'] = $this->arrayDatos[$area_id]['programa_id'] + 1;
				}
				if(!in_array($id, $arrayProye)){
					$arrayProye[] = $id;
					$this->arrayDatos[$area_id]['proyecto_id'] = $this->arrayDatos[$area_id]['proyecto_id'] + 1;
				}
				$this->arrayDatos[$area_id]['actividad_id'] = $this->arrayDatos[$area_id]['actividad_id'] + 1;
				$this->arrayDatos[$area_id]['ponderacion']  = $this->arrayDatos[$area_id]['ponderacion'] + $ponderacion;
				$this->arrayDatos[$area_id]['promedio']     = $this->arrayDatos[$area_id]['promedio'] + $promedio;
			}
			$this->generaCuadroArea();
		}else{
			$this->titulo = "";
			$this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
		}
	}
	
	function generaTablaAreaProyecto(){
		$this->titulo  = "Resumen por Unidad responsable ".$this->data['idArea'].": <b>".$this->catalogoC[$this->data['idArea']]."</b>";
		$this->arrayDatos = $arrayAct = array();
		$campos = $this->regresaTrimestre($this->data['trimestreId']);
		//--if(v.programadas = 0,0,((v.realizadas / v.programadas) * v.ponderacionActividad)) as ponderacion
		$sql = "select v.id,v.proyecto,v.actividadId,v.ponderacionActividad,				
				case 
					when (v.programadas = 0 && v.realizadas = 0) then (1 * v.ponderacionActividad)
					when (v.programadas = 0 && v.realizadas > 0) then ((v.realizadas / v.realizadas) * v.ponderacionActividad)
					when (v.programadas > 0 && v.realizadas = 0) then ((v.realizadas / v.programadas) * v.ponderacionActividad) 
					else ((v.realizadas / v.programadas) * v.ponderacionActividad) 
				end as ponderacion".$campos[2].$campos[3]."				
				from (
						SELECT id,proyecto,actividadId,ponderacionActividad".$campos[0].$campos[1]."
						FROM ".$this->tabla." WHERE unidadResponsableId > 0 ".$this->filtro."
					)
			as v ORDER BY v.proyecto;";	
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($id,$proyecto,$actividadId,$ponderacion,$promedio) = $this->db->sql_fetchrow($res)){
				$this->arrayDatos[$id]['proyecto']   = utf8_encode($proyecto);
				if(!in_array($actividadId, $arrayAct)){
					$arrayAct[] = $actividadId;
					$this->arrayDatos[$id]['actividad_id'] = $this->arrayDatos[$id]['actividad_id'] + 1;
					$this->arrayDatos[$id]['ponderacion']  = $this->arrayDatos[$id]['ponderacion'] + $ponderacion;
					$this->arrayDatos[$id]['promedio']     = $this->arrayDatos[$id]['promedio'] + $promedio;
				}
			}
			$this->generaCuadroEjeAreaProyecto();
		}else{
			$this->titulo = "";
			$this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
		}
	}
	
	function cabeceras($tipo){
		$buffer=""; 
		switch($tipo){
			case 1:
				$buffer = '<thead>
						<tr>
							<th class="tdcenter" style="width:5%;">Id</th>
							<th class="tdcenter" style="width:47%;">Eje</th>
							<th class="tdcenter" >Programas</th>
							<th class="tdcenter" >Proyectos</th>
							<th class="tdcenter" >Actividades</th>';
				if((int) $this->data['ponderaId'] == 1){
					$buffer .= '<th class="tdcenter" >Ponderacion</th>';
					$buffer .= '<th class="tdcenter" >Promedio</th>';
				}
				$buffer .= '<th class="tdcenter" >% Avance Acumulado</th>
						</tr>
					</thead>'; 
				break;
			case 2:
				$buffer = '<thead>
					<tr>
						<th class="tdcenter" style="width:5%;">Id</th>
						<th class="tdcenter" style="width:50%;">Programa</th>
						<th class="tdcenter" style="width:15%;">Proyectos</th>
						<th class="tdcenter" style="width:15%;">Actividades</th>';
				if((int) $this->data['ponderaId'] == 1){
					$buffer .= '<th class="tdcenter" >Ponderacion</th>';
					$buffer .= '<th class="tdcenter" >Promedio</th>';
				}				
				$buffer .= '<th class="tdcenter" style="width:15%;">% Avance Acumulado</th>
					</tr>
				</thead>';
				break;
			case 3:
				$buffer = '<thead>
					<tr>
						<th class="tdcenter" style="width:5%;">Id</th>
						<th class="tdcenter">Proyecto</th>
						<th class="tdcenter">Unidad Responsable</th>
						<th class="tdcenter">Actividades</th>';
				if((int) $this->data['ponderaId'] == 1){
					$buffer .= '<th class="tdcenter" >Ponderacion</th>';
					$buffer .= '<th class="tdcenter" >Promedio</th>';
				}				
				$buffer .= '<th class="tdcenter">% Avance Acumulado</th>
					</tr>
				</thead>';
					break;		
				case 4:
					$buffer = '<thead>
						<tr>
							<th class="tdcenter" style="width:5%;">Id</th>
							<th class="tdcenter" style="width:47%;">Unidad Responsable</th>
							<th class="tdcenter" >Programas</th>
							<th class="tdcenter" >Proyectos</th>
							<th class="tdcenter" >Actividades</th>';
					if((int) $this->data['ponderaId'] == 1){
						$buffer .= '<th class="tdcenter" >Ponderacion</th>';
						$buffer .= '<th class="tdcenter" >Promedio</th>';
					}						
					$buffer .= '<th class="tdcenter" >% Avance Acumulado</th>
						</tr>
					</thead>';
					break;
				case 5:
						$buffer = '<thead>
						<tr>
							<th class="tdcenter" style="width:5%;">Id</th>
							<th class="tdcenter" style="width:47%;">Proyecto</th>
							<th class="tdcenter" >Actividades</th>';
						if((int) $this->data['ponderaId'] == 1){
							$buffer .= '<th class="tdcenter" >Ponderacion</th>';
							$buffer .= '<th class="tdcenter" >Promedio</th>';
						}						
						$buffer .= '<th class="tdcenter" >% Avance Acumulado</th>
						</tr>
					</thead>';
					break;					
		}
		return $buffer;
	}
	
	function generaCuadroEjeAreaProyecto(){
		$valorAct = $rand = 0;
		$valorPP = $valorPr = 0;
		$contador = 1;
		$this->xml = $urlTmp = "";
		if(count($this->arrayDatos) > 0){
			$this->xml = "<chart palette='2' caption='Grafico de Actividades por Proyecto' labelDisplay='ROTATE' showValues='1' decimals='0' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
			$this->xmlPor="<chart palette='2' caption='Grafico de Ponderacion por Proyecto' labelDisplay='ROTATE' showValues='1' decimals='1' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
			$this->buffer = '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
			$this->buffer.= $this->cabeceras(5);
			$this->buffer.= '<tbody>';
			foreach($this->arrayDatos as $ind => $tmp){
				$rand = (float) $this->calculaPonderacion($tmp);
				$urlTmp = $this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=2&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$this->data['idArea']."&idProyecto=".$ind."&ponderaId=".$this->data['ponderaId'];
				$valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
				$valorPP  = $valorPP  +  (double) $tmp['ponderacion'] + 0;
				$valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
				$this->buffer.= '<tr>
									<td class="tdleft">'.$contador.'</td>
									<td class="tdleft"><a href="'.$urlTmp.'" target="_self" class="liga">'.$tmp['proyecto'].'</a></td>								
									<td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
				if((int) $this->data['ponderaId'] == 1){
					$this->buffer.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
									 <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
				}
				$this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand. '%</td>
								</tr>';				
				
				$this->xml.="<set label='".$contador."' tooltext='".$tmp['proyecto']." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."'  value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."'/>";
				$this->xmlPor.="<set label='".$contador."' tooltext='".$tmp['proyecto']." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
				$contador++;
			}
			$promedioTotal = 0;
			if($valorPP > 0){
				$promedioTotal = ($valorPr / $valorPP)*100;
				$promedioTotal = number_format($promedioTotal,2,'.',',');
			}
			if($promedioTotal> 100){
				$promedioTotal = number_format(100,2,'.',',');
			}
				
			$this->buffer.= '</tbody>
							<thead>
								<tr>
									<th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
									<th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
			if((int) $this->data['ponderaId'] == 1){
				$this->buffer.= '<th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
								 <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
			}
			$this->buffer.= '<th class="tdright '.$this->color($promedioTotal).'">'.$promedioTotal.'%</th>
								</tr>
							</thead></table>';
			$this->xml .= "</chart>";		
			$this->xmlPor.="</chart>";
			$this->bufferXml = $this->buffer;
		}
		
	}
	function generaCuadroEjeProgramaProyecto(){
		$valorAct = $valorPP = $valorPr = 0;
		$contador = 1;
		$this->xml = $urlTmp = "";
		if(count($this->arrayDatos) > 0){
			$this->xml = "<chart palette='2' caption='Grafico de Actividades por Proyecto' labelDisplay='ROTATE' showValues='1' decimals='0' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
			$this->xmlPor="<chart palette='2' caption='Grafico de Ponderacion por Proyecto' labelDisplay='ROTATE' showValues='1' decimals='1' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
			$this->buffer = '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
			$this->buffer.= $this->cabeceras(3);
			$this->buffer.= '<tbody>';
			foreach($this->arrayDatos as $ind => $tmp){
				$rand = (float) $this->calculaPonderacion($tmp);
				$urlTmp = $this->path."index.php?index.php?tablaId=".$this->data['tablaId']."&tipoId=1&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."&idPrograma=".$this->data['idPrograma']."&idProyecto=".$ind."&ponderaId=".$this->data['ponderaId'];
				$valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
				$valorPP  = $valorPP  +  (double) $tmp['ponderacion'] + 0;
				$valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
				$this->buffer.= '<tr>
									<td class="tdleft">'.$contador.'</td>
									<td class="tdleft"><a href="'.$urlTmp.'" target="_self" class="liga">'.$tmp['proyecto'].'</a></td>											
									<td class="tdleft">'.$tmp['area'].'</td>
									<td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
				if((int) $this->data['ponderaId'] == 1){
					$this->buffer.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
									 <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
				}
				$this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand. '%</td>
								</tr>';
				
				$this->xml.="<set label='".$contador."' tooltext='".$tmp['proyecto']." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."' value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."'/>";
				$this->xmlPor.="<set label='".$contador."' tooltext='".$tmp['proyecto']." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
				$contador++;
			}
			$promedioTotal = 0;
			if($valorPP > 0){
				$promedioTotal = ($valorPr / $valorPP)*100;
				$promedioTotal = number_format($promedioTotal,2,'.',',');
			}
			if($promedioTotal> 100){
				$promedioTotal = number_format(100,2,'.',',');
			}
				
			$this->buffer.= '</tbody>
							<thead>
								<tr>
									<th class="tdleft" colspan="3">Totales: '.count($this->arrayDatos).' registros.</th>
									<th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
			if((int) $this->data['ponderaId'] == 1){
				$this->buffer.= '<th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
								 <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
			}
			$this->buffer.= '<th class="tdright '.$this->color($promedioTotal).'">'.$promedioTotal.'%</th>
								</tr>
							</thead></table>';
			$this->xml .= "</chart>";
			$this->xmlPor.="</chart>";
			$this->bufferXml = $this->buffer;
		}
	}
	
	function generaCuadroEjePrograma(){
		$this->xml = $urlTmp = "";
		$valorPy = $valorAct = 0;
		$valorPP = $valorPr = 0;
		$contador = 1;
		if(count($this->arrayDatos) > 0){
			$this->xml = "<chart palette='2' caption='Grafico de Actividades por Programa' labelDisplay='ROTATE' showValues='1' decimals='0' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
			$this->xmlPor="<chart palette='2' caption='Grafico de Ponderacion por Programa' labelDisplay='ROTATE' showValues='1' decimals='1' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
			$this->buffer = '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
			$this->buffer.= $this->cabeceras(2);
			$this->buffer.= '<tbody>';
			$this->bufferXml = $this->buffer;
			foreach($this->arrayDatos as $ind => $tmp){
				$rand = (float) $this->calculaPonderacion($tmp);
				$urlTmp = $this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=1&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."&idPrograma=".$ind."&ponderaId=".$this->data['ponderaId'];
				$valorPy  = $valorPy  +  (int) $tmp['proyecto_id'] + 0;
				$valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
				$valorPP  = $valorPP  +  (double) $tmp['ponderacion'] + 0;
				$valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
				$this->buffer.= '<tr>
									<td class="tdleft">'.$contador.'</td>
									<td class="tdleft"><a href="'.$urlTmp.'" target="_self" class="liga">'.$tmp['programa'].'</a></td>
									<td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
									<td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
				if((int) $this->data['ponderaId'] == 1){
					$this->buffer.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
										<td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
				}
				$this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand. '%</td>
								</tr>';
				$this->bufferXml .='<tr>
									<td class="tdleft">'.$contador.'</td>
									<td class="tdleft">'.$tmp['programa'].'</td>
									<td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
									<td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
				if((int) $this->data['ponderaId'] == 1){
					$this->bufferXml.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
										<td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
				}
				$this->bufferXml.= '<td class="tdright">'.$rand. '%</td>
								</tr>';
				
				$this->xml.="<set label='".$contador."' tooltext='".$this->catalogoB[$ind]." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."' value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."' />";
				$this->xmlPor.="<set label='".$contador."' tooltext='".$this->catalogoB[$ind]." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
				$contador++;
			}
			$promedioTotal = 0;
			if($valorPP > 0){
				$promedioTotal = ($valorPr / $valorPP)*100;
				$promedioTotal = number_format($promedioTotal,2,'.',',');
			}
			if($promedioTotal> 100){
				$promedioTotal = number_format(100,2,'.',',');
			}				
			$this->buffer.= '</tbody>
							<thead>
								<tr>
									<th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
									<th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
			if((int) $this->data['ponderaId'] == 1){
				$this->buffer.= '<th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
								 <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
			}
			$this->buffer.= '<th class="tdright '.$this->color($promedioTotal).'">'.$promedioTotal.'%</th>
								</tr>
							</thead></table>';
			$this->bufferXml .='</tbody>
							<thead>
								<tr>
									<th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
									<th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
			if((int) $this->data['ponderaId'] == 1){
				$this->bufferXml.= '<th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
			}			
			$this->bufferXml.= '<th class="tdright">'.number_format($promedioTotal,0,'.',',').'%</th>
								</tr>
							</thead></table>';
			$this->xml .= "</chart>";			
			$this->xmlPor.="</chart>";
		}
	}
	
	
	function generaCuadroEje(){
		$this->xml = $urlTmp = "";
		$contador = 1;
		$valorPg = $valorPy = $valorAct = 0; 
		$valorPP = $valorPr = 0;
		if(count($this->arrayDatos) > 0){
			$this->xml = "<chart palette='2' caption='Grafico de Actividades por Eje' labelDisplay='ROTATE' showValues='1' decimals='0' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
			$this->xmlPor="<chart palette='2' caption='Grafico de Ponderacion por Eje' labelDisplay='ROTATE' showValues='1' decimals='1' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
			$this->buffer = '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
			$this->buffer.= $this->cabeceras(1);
			$this->buffer.= '<tbody>';
			$this->bufferXml = $this->buffer;
			foreach($this->arrayDatos as $ind => $tmp){
				$rand = (float) $this->calculaPonderacion($tmp);				
				$urlTmp = $this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=1&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$ind."&ponderaId=".$this->data['ponderaId'];
				$valorPg  = $valorPg  +  (int) $tmp['programa_id'] + 0;
				$valorPy  = $valorPy  +  (int) $tmp['proyecto_id'] + 0;
				$valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
				$valorPP  = $valorPP  +  (double) $tmp['ponderacion'] + 0;
				$valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
				
				//Html
				$this->buffer.= '<tr>
									<td class="tdleft">'.$contador.'</td>
									<td class="tdleft"><a href="'.$urlTmp.'" target="_self" class="liga">'.$tmp['eje'].'</a></td>
									<td class="tdcenter">'.number_format($tmp['programa_id'],0,'.',',').'</td>
									<td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
									<td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
				if((int) $this->data['ponderaId'] == 1){
					$this->buffer.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
									 <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
				}
				$this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand. '%</td>
								</tr>';
				
				//Excel
				$this->bufferXml .='<tr>
									<td class="tdleft">'.$contador.'</td>
									<td class="tdleft">'.$tmp['eje'].'</td>
									<td class="tdcenter">'.number_format($tmp['programa_id'],0,'.',',').'</td>
									<td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
									<td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
				if((int) $this->data['ponderaId'] == 1){
					$this->bufferXml.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
									 	<td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
				}
				$this->bufferXml.= '<td class="tdright">'.$rand. '%</td></tr>';
				
				$this->xml.="<set label='".$contador."' tooltext='".$this->catalogoA[$ind]." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."' value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."' />";
				$this->xmlPor.="<set label='".$contador."' tooltext='".$this->catalogoA[$ind]." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
				$contador++;
			}
			$promedioTotal = 0;
			if($valorPP > 0){				
				$promedioTotal = ($valorPr / $valorPP)*100;
				$promedioTotal = number_format($promedioTotal,2,'.',',');
			}
			if($promedioTotal> 100){
				$promedioTotal = number_format(100,2,'.',',');
			}
			$this->buffer.= '</tbody>
							<thead>
								<tr>
									<th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
									<th class="tdcenter">'.number_format($valorPg,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
			if((int) $this->data['ponderaId'] == 1){
				$this->buffer.= '<th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
								 <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
			}
			$this->buffer.= '<th class="tdright '.$this->color($promedioTotal).'">'.$promedioTotal.'%</th>
								</tr>
							</thead></table>';
			$this->bufferXml .='</tbody>
							<thead>
								<tr>
									<th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
									<th class="tdcenter">'.number_format($valorPg,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
			if((int) $this->data['ponderaId'] == 1){
				$this->bufferXml.= '<th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
			}
			
			$this->bufferXml.= '<th class="tdright">'.number_format($promedioTotal,0,'.',',').'%</th>
								</tr>
							</thead></table>';
			$this->xml .= "</chart>";
			$this->xmlPor.="</chart>";
		}
	}
	
	
	function generaCuadroArea(){
		$this->xml = $urlTmp = "";
		$contador = 1;
		$valorPg = $valorPy = $valorAct = 0;
		$valorPP = $valorPr = 0;
		if(count($this->arrayDatos) > 0){
			$this->xml = "<chart palette='2' caption='Grafico de Actividades por Unidad Responsable' labelDisplay='ROTATE' showValues='1' decimals='0' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
			$this->xmlPor="<chart palette='2' caption='Grafico de Ponderacion por Unidad Responsable' labelDisplay='ROTATE' showValues='1' decimals='1' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
			$this->buffer = '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
			$this->buffer.= $this->cabeceras(4);
			$this->buffer.= '<tbody>';
			$this->bufferXml = $this->buffer;
			foreach($this->arrayDatos as $ind => $tmp){
				$rand = (float) $this->calculaPonderacion($tmp);
				$urlTmp = $this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=2&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$ind."&ponderaId=".$this->data['ponderaId'];
				$valorPg  = $valorPg  +  (int) $tmp['programa_id'] + 0;
				$valorPy  = $valorPy  +  (int) $tmp['proyecto_id'] + 0;
				$valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
				$valorPP  = $valorPP  +  (double) $tmp['ponderacion'] + 0;
				$valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
				$this->buffer.= '<tr>
									<td class="tdleft">'.$contador.'</td>
									<td class="tdleft"><a href="'.$urlTmp.'" target="_self" class="liga">'.$tmp['area'].'</a></td>
									<td class="tdcenter">'.number_format($tmp['programa_id'],0,'.',',').'</td>
									<td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
									<td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
				if((int) $this->data['ponderaId'] == 1){
					$this->buffer.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
									 <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
				}
				$this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand. '%</td>
								</tr>';
				$this->bufferXml .= '<tr>
									<td class="tdleft">'.$contador.'</td>
									<td class="tdleft">'.$tmp['area'].'</td>
									<td class="tdcenter">'.number_format($tmp['programa_id'],0,'.',',').'</td>
									<td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
									<td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
				if((int) $this->data['ponderaId'] == 1){
					$this->bufferXml.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
									 	<td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
				}
				$this->bufferXml.= '<td class="tdright">'.$rand. '%</td>
								</tr>';		
				
				$this->xml.="<set label='".$contador."' tooltext='".$this->catalogoC[$ind]." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."'  value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."' />";
				$this->xmlPor.="<set label='".$contador."' tooltext='".$this->catalogoC[$ind]." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
				$contador++;
			}
			$promedioTotal = 0;
			if($valorPP > 0){
				$promedioTotal = ($valorPr / $valorPP)*100;
				$promedioTotal = number_format($promedioTotal,2,'.',',');
			}
			if($promedioTotal> 100){
				$promedioTotal = number_format(100,2,'.',',');
			}
				
			$this->buffer.= '</tbody>
							<thead>
								<tr>
									<th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
									<th class="tdcenter">'.number_format($valorPg,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
			if((int) $this->data['ponderaId'] == 1){
				$this->buffer.= '<th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
								 <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
			}
			$this->buffer.= '<th class="tdright '.$this->color($promedioTotal).'">'.$promedioTotal.'%</th>
								</tr>
							</thead></table>';
			$this->bufferXml .='</tbody>
							<thead>
								<tr>
									<th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
									<th class="tdcenter">'.number_format($valorPg,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
			if((int) $this->data['ponderaId'] == 1){
				$this->bufferXml.= '<th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
									<th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
			}			
			$this->bufferXml.= '<th class="tdright">'.number_format($promedioTotal,0,'.',',').'%</th>
								</tr>
							</thead></table>';
			$this->xml .= "</chart>";
			$this->xmlPor.="</chart>";
		}
	}
	
	function calculaPonderacion($tmp){
		$rand = 0;
		if($tmp['ponderacion'] > 0){
			$rand = (($tmp['promedio'] / $tmp['ponderacion']) *100);
			if($rand > 100){
				$rand = 100;
			}			
		}
		$rand = number_format($rand,2,'.',',');
		return $rand;
	}
	function color($rand){
		$color = "";
		if($rand >=80)
			$color = " success ";
		elseif($rand >=60 && $rand <80){
			$color = " warning ";
		}else{
			$color = " danger ";
		}
		return $color;
	}
	/**
	 * Metodo que se encarga de crear el filtro segun la eleccion del usuario
	 */
	function generaFiltro(){
		$this->filtro = "AND activoP = '1' AND activoA = '1' ";
		if( (int) $this->data['anoId'] > 0){
			$this->filtro .= " AND ano_id = '".$this->data['anoId']."' ";
			$this->breadcrumb .= "<a href='".$this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."'> / A&ntilde;o ".$this->data['anoId']."</a>";
		}
		if( (int) $this->data['trimestreId'] > 0){
			$this->breadcrumb .= "<a href='".$this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."'> / Trimestre ".$this->data['trimestreId']."</a>";
		}
		if( (int) $this->data['idEje'] > 0){
			$this->filtro.= " AND eje_id = '".$this->data['idEje']."' ";
			$this->breadcrumb .= "<a href='".$this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."'> / ".$this->catalogoA[$this->data['idEje']]."</a> ";
		}
		if( (int) $this->data['idPrograma'] > 0){
			$this->filtro .= " AND programa_id = '".$this->data['idPrograma']."' ";			
			$this->breadcrumb .= "<a href='".$this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."&idPrograma=".$this->data['idPrograma']."'> / ".$this->catalogoB[$this->data['idPrograma']]."</a> ";
		}
		if( (int) $this->data['idPrograma'] > 0 && (int) $this->data['idProyecto'] > 0 ){
			$this->filtro .= " AND id = '".$this->data['idProyecto']."' ";
			$this->breadcrumb .= "<a href='".$this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."&idPrograma=".$this->data['idPrograma']."&idProyecto=".$this->data['idProyecto']."'> / ".$this->catalogoD[$this->data['idProyecto']]."</a> ";			
		}		
		if( (int) $this->data['idArea'] > 0 ){			
			$this->filtro .= " AND unidadResponsableId = '".$this->data['idArea']."' ";
			$this->breadcrumb .= "<a href='".$this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$this->data['idArea']."'> / ".$this->catalogoC[$this->data['idArea']]."</a> ";			
		}
		if( (int) $this->data['idArea'] > 0 && (int) $this->data['idProyecto'] > 0 ){
			$this->filtro .= " AND id = '".$this->data['idProyecto']."' ";
			$this->breadcrumb .= "<a href='".$this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$this->data['idArea']."&idProyecto=".$this->data['idProyecto']."'> / ".$this->catalogoD[$this->data['idProyecto']]."</a> ";			
		}
	}
	
	function pintaGrafico($array,$array_asis,$array_areas,$opc_tit) {
		$strXML='';
		if(count($array) > 0) {
			$strXML  = "<chart caption='".$this->titulo."' xAxisName='' yAxisName='Total' showValues='0' formatNumberScale='1' showBorder='1'>";
			foreach($array as  $key => $value) {
				$color=$this->genera_color();
				$strXML .= "<set label='Act' value='".$value."' color='".$color."' showValues='0' toolText='Actividades: ".$array_areas[$key]."  ".$value."'/>
				<set label='Asi' value='".$array_asis[$key]."' color='".$color."' showValues='0' toolText='Asistentes: ".$array_areas[$key]."  ".$array_asis[$key]."'/>";
			}
			$strXML .= "</chart>";
		}
		return $strXML;	
	}
	
	function genera_color(){
		mt_srand((double) microtime() * 1000000);
		$color = '';
		while (strlen($color) < 6)
		{
			$color .= sprintf("%02X", mt_rand(0, 255));
		}
		return $color;
	}
	
	function obtenNombreTabla(){
		return $this->tabla;
	}
	
	function obtenTabla(){
		return $this->buffer;
	}
	
	function obtenBreadcrumb(){
		return $this->breadcrumb;
	}
	
	function obtenTitulo(){
		return $this->titulo;
	}
	function obtenXml(){
		return $this->xml;
	}
	function obtenXmlPor(){
		return $this->xmlPor;
	}
	function obtenTablaXls(){
		return $this->bufferXml;
	}
}
?>