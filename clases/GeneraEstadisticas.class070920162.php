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
	var $cien;
	
	function __construct($db,$data,$session,$path){
		$this->db      = $db;
		$this->data    = $data;
		$this->session = $session;
		$this->path    = $path;
		$this->cien    = 100;
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
		$contador = $p5 = $p6 = 0;
		$this->titulo  = "<b>Actividades realizadas</b><br>";
		$this->arrayDatos = $arrayAct = $arrayTotales  = array();
		$sql = "SELECT actividadId,actividad,ponderacionActividad,tipo_actividad_id,medida,
				trimestre1,trimestre2,trimestre3,trimestre4,
				Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
				FROM ".$this->tabla." WHERE eje_id >0 and programa_id > 0 and id > 0 and actividadId > 0 ".$this->filtro." ORDER BY actividadId;";

		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($id,$act,$pon,$tipoAct,$medida,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
				if(!in_array($id,$arrayAct)){
					$arrayAct[] = $id;
					$t5 = $t1 + $t2 + $t3 + $t4 + 0; 
					$a5 = $a1 + $a2 + $a3 + $a4 + 0;
					$p5 = $p5 + $pon + 0;
					$promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$pon);
					/*if($promedio > 100){
						$promedio = 100;
					}*/
					$p6 = $p6 + $promedio + 0;
					$this->arrayDatos[$id]['id']  = $id;
					$this->arrayDatos[$id]['act'] = utf8_encode($act);
					$this->arrayDatos[$id]['pon'] = $pon;
					$this->arrayDatos[$id]['tAc'] = $tipoAct;
					$this->arrayDatos[$id]['med'] = utf8_encode($medida);
					$this->arrayDatos[$id]['t1']  = (int)$t1;
					$this->arrayDatos[$id]['t2']  = (int)$t2;
					$this->arrayDatos[$id]['t3']  = (int)$t3;
					$this->arrayDatos[$id]['t4']  = (int)$t4;
					$this->arrayDatos[$id]['t5']  = (int)$t5;
					$this->arrayDatos[$id]['a1']  = (int)$a1;
					$this->arrayDatos[$id]['a2']  = (int)$a2;
					$this->arrayDatos[$id]['a3']  = (int)$a3;
					$this->arrayDatos[$id]['a4']  = (int)$a4;
					$this->arrayDatos[$id]['a5']  = (int)$a5;

					$this->arrayDatos[$id]['avance']  = $promedio ;
					$contador++;
				}
				$arrayTotales['tponder']  = $p5;
				$arrayTotales['tavance']  = $p6;
				$arrayTotales['tactividades']  = $contador;
			}
			//$this->debug($this->arrayDatos);
			$this->generaCuadroActividades($arrayTotales);
		}else{
			$this->titulo = "";
			$this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
		}
	}
	function generaCuadroActividades($arrayTotales){
		$tablaC = str_replace("view_","view_c_",$this->tabla);
    	$tablaA = str_replace("view_","view_a_",$this->tabla);    		
		$this->buffer = $active = $this->xml = "";
		$contador = 0;
		$avanceTotal = 0;
		if((int)$arrayTotales['tponder'] > 0){
			$avanceTotal = ( ($arrayTotales['tavance'] / $arrayTotales['tponder']) *1);
		}
		
		/*if((int)$arrayTotales['tactividades'] > 0){
			$avanceTotal = ( ($arrayTotales['tavance'] / $arrayTotales['tactividades']) *1);
		}*/

		$this->buffer .='<table width="100%" class="table">
                    	<tr class="'.$this->color($avanceTotal).'">
						<td class="tdleft" style="width:30%;">
							<b>P</b> = Programado
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>R</b> = Reportado
                    		<br>
							<b>C</b> = Comentarios
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>A</b> = Adjuntos
                    			
						</td>
						<td class="tdright" style="width:70%;">
							Actividades: '.number_format($arrayTotales['tactividades'],0,".",",").'
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Ponderaci&oacute;n Total: '.number_format($arrayTotales['tponder'],0,".",",").'
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Avance Total: '.number_format($arrayTotales['tavance'],2,".",",").'
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Promedio Total: '.number_format($avanceTotal,2,".",",").'									
						</td>
						</tr></table>';
		$this->buffer .='<table class="table table-bordered">
							<thead><tr class="active"><td colspan="2">Actividad</td>
										<td>Programado</td>
										<td>Realizado</td>
										<td>Ponderaci&oacute;n</td>
										<td>T.Actividad</td>
										<td>Avance</td>
										<td width="5%">M&aacute;s</td>
										<td width="5%">&nbsp;</td>
										<td width="5%">&nbsp;</td>
							</tr></thead><tbody>';
		$totalPro = $totalAva = 0;
		foreach($this->arrayDatos as $idAct => $tmpDatos){
			$contador++;
			$totalPro = $totalPro + $tmpDatos['t5'] + 0; 
			$totalAva = $totalAva + $tmpDatos['a5'] + 0;
			$arrayComentarios = $this->regresaDatosComentarios($this->data['idProyecto'],$tablaC,$idAct);
			$comentarios      = $this->regresaComentarios($arrayComentarios);
			$arrayAdjuntos    = $this->regresaDatosAdjuntos($this->data['idProyecto'],$tablaA,$idAct);
			$adjuntos         = $this->regresaAdjuntos($arrayAdjuntos);
				
			$this->buffer .='<tr>
					<td class="tdcenter" >'.$contador.'</td>
					<td class="tdleft" >'.$tmpDatos['act'].'</td>
					<td class="tdcenter" width="7%">'.number_format($tmpDatos['t5'],0,".",",").'</td>
	                <td class="tdcenter" width="7%">'.number_format($tmpDatos['a5'],0,".",",").'</td>
	                <td class="tdcenter" width="10%">'.$tmpDatos['pon'].'</td>
					<td class="tdcenter" width="10%">Tipo Actividad '.$tmpDatos['tAc'].'</td>	  
                    <td class="tdright '.$this->color($tmpDatos['avance']).'" width="10%">'.number_format($tmpDatos['avance'],2,".",",").'</td>
                    <td class="tdcenter" width="5%"><button type="button" class="btn btn-default  buttonComentarios" id="button-'.$idAct.'" value="1"><b><span class="glyphicon glyphicon-plus"></span></b></button></td>
                    <td class="tdcenter" width="5%">';
			if(trim($comentarios) != ""){
				$this->buffer .='
					<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapseComentarios'.$idAct.'" aria-expanded="false" aria-controls="collapseComentarios'.$idAct.'">&nbsp;&nbsp;C&nbsp;&nbsp;</button>
					<div class="collapse" id="collapseComentarios'.$idAct.'">
					<div class="well">'.trim($comentarios).'</div>
					</div>';
			}
            $this->buffer .='</td><td class="tdcenter" width="5%">';
            if(trim($adjuntos) != ""){
            	$this->buffer.='
				<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapseAdjuntos'.$idAct.'" aria-expanded="false" aria-controls="collapseAdjuntos'.$idAct.'">&nbsp;&nbsp;A&nbsp;&nbsp;</button>
				<div class="collapse" id="collapseAdjuntos'.$idAct.'">
				<div class="well">'.trim($adjuntos).'</div>
				</div>';
            }            
			$this->buffer .='</td></tr>
                    <tr id="renglonActividad'.$idAct.'" class="mas">
                    	<td colspan="10" class="tdcenter">'.$this->masDatos($tablaC,$tablaA,$idAct,$tmpDatos).'</td>
                    </tr>';
		}
		$this->buffer .='</tbody>
				<tfoot><tr class="'.$this->color($avanceTotal).'" >
						<td colspan="2">&nbsp;</td>
						<td class="tdcenter" width="7%">'.number_format($totalPro,0,".",",").'</td>
						<td class="tdcenter" width="7%">'.number_format($totalAva,0,".",",").'</td>
						<td class="tdcenter" width="10%">'.number_format($arrayTotales['tponder'],0,".",",").'</td>
						<td>&nbsp;</td>
						<td class="tdright '.$this->color($tmpDatos['avance']).'" width="10%">'.number_format($arrayTotales['tavance'],2,".",",").'</td>
						<td class="tdright '.$this->color($tmpDatos['avance']).'" width="5%">'.number_format($avanceTotal,2,".",",").'</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
				</tr></tfoot>				
				</table>';
		
		
// 		$contador = 0;
// 		$this->bufferrr .='<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
// 		foreach($this->arrayDatos as $idAct => $tmpDatos){
// 			$arrayComentarios = $this->regresaDatosComentarios($this->data['idProyecto'],$tablaC,$idAct);
// 			$comentarios      = $this->regresaComentarios($arrayComentarios);   
// 			$arrayAdjuntos    = $this->regresaDatosAdjuntos($this->data['idProyecto'],$tablaA,$idAct);
// 			$adjuntos         = $this->regresaAdjuntos($arrayAdjuntos);
// 			$active = "";
// 			/*if($contador == 0){
// 				$active = " in ";
// 			}*/
// 			/*if($tmpDatos['avance'] > 100){
// 				$tmpDatos['avance'] = 100;
// 			}*/
// 			$this->bufferrr.='
// 				<div class="panel panel-default">
//     				<div class="panel-heading tdleft" role="tab" id="heading'.$idAct.'" >
//       					<h4 class="panel-title tdleft">'.($contador + 1).'&nbsp;.-&nbsp;
//         					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idAct.'" aria-expanded="true" aria-controls="collapse'.$idAct.'">
//           						'.$tmpDatos['act'].'
//         					</a>
//       					</h4>
//     				</div>
//     				<div id="collapse'.$idAct.'" class="panel-collapse collapse '.$active.'" role="tabpanel" aria-labelledby="heading'.$idAct.'">
//       					<div class="panel-body tdjustify">
// 						<table width="100%" class="table">
//                     	<tr class="success">
//     	                    <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE1C.'</td>
//         	                <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE2C.'</td>
//             	            <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE3C.'</td>
//                 	        <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE4C.'</td>
//                     	    <td colspan="2" class="tdcenter" width="14%">'.TOTAL.'</td>
//                         	<td class="tdcenter" width="10%">'.MEDIDA.'</td>
//                         	<td class="tdcenter" width="10%">'.PONDERACION.'</td>
//                         	<td class="tdcenter" width="10%">'.TIPOACT.'</td>
//                         	<td class="tdcenter" width="10%">'.TITAVANCE.'</td>
//                     	</tr>
//                     	<tr class="warning">
// 	                        <td class="tdcenter" width="7%">'.P.'</td>
// 	                        <td class="tdcenter" width="7%">'.R.'</td>
// 	                        <td class="tdcenter" width="7%">'.P.'</td>
// 	                        <td class="tdcenter" width="7%">'.R.'</td>
// 	                        <td class="tdcenter" width="7%">'.P.'</td>
// 	                        <td class="tdcenter" width="7%">'.R.'</td>
// 	                        <td class="tdcenter" width="7%">'.P.'</td>
// 	                        <td class="tdcenter" width="7%">'.R.'</td>
// 	                        <td class="tdcenter" width="7%">'.P.'</td>
// 	                        <td class="tdcenter" width="7%">'.R.'</td>
//                         	<td class="tdcenter" width="10%">&nbsp;</td>
//                         	<td class="tdcenter" width="10%">&nbsp;</td>
//                         	<td class="tdcenter" width="10%">&nbsp;</td>
// 	                        <td class="tdcenter" width="10%">&nbsp;</td>
// 	                    </tr>
//                     	<tr>
// 	                        <td class="tdcenter" width="7%">'.$tmpDatos['t1'].'</td>
// 	                        <td class="tdcenter" width="7%">'.$tmpDatos['a1'].'</td>
// 	                        <td class="tdcenter" width="7%">'.$tmpDatos['t2'].'</td>
// 	                        <td class="tdcenter" width="7%">'.$tmpDatos['a2'].'</td>
// 	                        <td class="tdcenter" width="7%">'.$tmpDatos['t3'].'</td>
// 	                        <td class="tdcenter" width="7%">'.$tmpDatos['a3'].'</td>
// 	                        <td class="tdcenter" width="7%">'.$tmpDatos['t4'].'</td>
// 	                        <td class="tdcenter" width="7%">'.$tmpDatos['a4'].'</td>
// 	                        <td class="tdcenter" width="7%">'.$tmpDatos['t5'].'</td>
// 	                        <td class="tdcenter" width="7%">'.$tmpDatos['a5'].'</td>
// 							<td class="tdcenter" width="10%">'.$tmpDatos['med'].'</td>
//                         	<td class="tdcenter" width="10%">'.$tmpDatos['pon'].'</td>
//                         	<td class="tdcenter" width="10%">Tipo Actividad '.$tmpDatos['tAc'].'</td>	  
//                         	<td class="tdright '.$this->color($tmpDatos['avance']).'"" width="10%">'.number_format($tmpDatos['avance'],2,".",",").'</td>	
// 	                    </tr><tr><td colspan="7" class="tdleft">
// 					';
// 			if(trim($comentarios) != ""){
// 				$this->bufferrr.='
// 						<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapseComentarios'.$idAct.'" aria-expanded="false" aria-controls="collapseComentarios'.$idAct.'">&nbsp;&nbsp;Comentarios&nbsp;&nbsp;</button>
// 						<div class="collapse" id="collapseComentarios'.$idAct.'">
// 	  						<div class="well">'.trim($comentarios).'</div>
// 						</div>';
// 			}
// 			$this->bufferrr.='</td><td colspan="7" class="tdleft">';
// 			if(trim($adjuntos) != ""){
// 				$this->bufferrr.='
// 						<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapseAdjuntos'.$idAct.'" aria-expanded="false" aria-controls="collapseAdjuntos'.$idAct.'">&nbsp;&nbsp;Adjuntos&nbsp;&nbsp;</button>
// 						<div class="collapse" id="collapseAdjuntos'.$idAct.'">
// 	  						<div class="well">'.trim($adjuntos).'</div>
// 						</div>';
// 			}
// 			$this->bufferrr.='</td></tr></table>
//       					</div>
//     				</div>
//   				</div>';
// 			$contador++;
// 		}
		$this->buffer .='</div>';
	}
	
	function masDatos($tablaC,$tablaA,$idAct,$tmpDatos){
		$arrayComentarios = $this->regresaDatosComentarios($this->data['idProyecto'],$tablaC,$idAct);
		$comentarios      = $this->regresaComentarios($arrayComentarios);
		$arrayAdjuntos    = $this->regresaDatosAdjuntos($this->data['idProyecto'],$tablaA,$idAct);
		$adjuntos         = $this->regresaAdjuntos($arrayAdjuntos);
		$buf = '
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
						<td class="tdcenter" width="10%">'.TITAVANCE.'</td>
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
						<td class="tdright '.$this->color($tmpDatos['avance']).'"" width="10%">'.number_format($tmpDatos['avance'],2,".",",").'</td>
					</tr>
					<tr><td colspan="7" class="tdleft">';
			if(trim($comentarios) != ""){
				$buf.='
				<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapseComentarios'.$idAct.'" aria-expanded="false" aria-controls="collapseComentarios'.$idAct.'">&nbsp;&nbsp;Comentarios&nbsp;&nbsp;</button>
				<div class="collapse" id="collapseComentarios'.$idAct.'">
				<div class="well">'.trim($comentarios).'</div>
				</div>';
			}
			$buf.='</td><td colspan="7" class="tdleft">';
			if(trim($adjuntos) != ""){
				$buf.='
				<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapseAdjuntos'.$idAct.'" aria-expanded="false" aria-controls="collapseAdjuntos'.$idAct.'">&nbsp;&nbsp;Adjuntos&nbsp;&nbsp;</button>
				<div class="collapse" id="collapseAdjuntos'.$idAct.'">
				<div class="well">'.trim($adjuntos).'</div>
				</div>';
			}
			$buf.='</td></tr></table>
					</div>';
			return $buf;
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
		$sql = "select id,proyecto,actividadId,area,ponderacionActividad,
				trimestre1,trimestre2,trimestre3,trimestre4,
				Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
				FROM ".$this->tabla." WHERE programa_id > 0 and id > 0 ".$this->filtro." ORDER BY programa_id;";		
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($id,$proyecto,$actividadId,$area,$ponderacion,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
				if(!in_array($actividadId,$arrayAct)){
					$arrayAct[] = $actividadId;
					$promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);			
					/*if($promedio > 100){
						$promedio = 100;
					}*/
					$this->arrayDatos[$id]['proyecto']     = utf8_encode($proyecto);
					$this->arrayDatos[$id]['area']         = utf8_encode($area);
					$this->arrayDatos[$id]['actividad_id'] = $this->arrayDatos[$id]['actividad_id'] + 1;
					$this->arrayDatos[$id]['ponderacion']  = $this->arrayDatos[$id]['ponderacion'] + $ponderacion;
					$this->arrayDatos[$id]['promedio']     = $this->arrayDatos[$id]['promedio'] + $promedio;						
				}
			}
			//$this->debug($this->arrayDatos);
			$this->generaCuadroEjeProgramaProyecto();
		}else{
			$this->titulo = "";
			$this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
		}
		
	}
	
	function generaTablaEjePrograma(){
		$this->titulo  = "Resumen por Eje ".$this->data['idEje'].": <b>".$this->catalogoA[$this->data['idEje']]."</b>";
		$this->arrayDatos = $arrayProye = $arrayAct = array();
		$sql = "select programa_id,programa,id,actividadId,ponderacionActividad,
				trimestre1,trimestre2,trimestre3,trimestre4,
				Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
				FROM ".$this->tabla." WHERE programa_id > 0 ".$this->filtro." ORDER BY programa_id;";				
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($programa_id,$programa,$id,$actividadId,$ponderacion,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
				if(!in_array($actividadId,$arrayAct)){
					$arrayAct[] = $actividadId;
					$promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);	
					/*if($promedio > 100){
						$promedio = 100;
					}*/
					$this->arrayDatos[$programa_id]['programa']   = utf8_encode($programa);
					$this->arrayDatos[$programa_id]['actividad_id'] = $this->arrayDatos[$programa_id]['actividad_id'] + 1;
					$this->arrayDatos[$programa_id]['ponderacion']  = $this->arrayDatos[$programa_id]['ponderacion'] + $ponderacion;
					$this->arrayDatos[$programa_id]['promedio']     = $this->arrayDatos[$programa_id]['promedio'] + $promedio;						
					if(!in_array($id, $arrayProye)){
						$arrayProye[] = $id;
						$this->arrayDatos[$programa_id]['proyecto_id'] = $this->arrayDatos[$programa_id]['proyecto_id'] + 1;
					}
				}		
			}
			//$this->debug($this->arrayDatos);
			$this->generaCuadroEjePrograma();
		}else{
			$this->titulo = "";
			$this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
		}
	}
	
	function calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion){
		$sumaProgramada = 0;
		$sumaReales     = 0;
		$avance 		= 0;
		switch($this->data['trimestreId']){
			case 1:
				$sumaProgramada = (int) $t1 + 0;
				$sumaReales     = (int) $a1 + 0;
				break;
			case 2:
				$sumaProgramada = (int) $t1 + (int) $t2 + 0;
				$sumaReales     = (int) $a1 + (int) $a2 + 0;				
				break;
			case 3:
				$sumaProgramada = (int) $t1 + (int) $t2 + (int) $t3 + 0;
				$sumaReales     = (int) $a1 + (int) $a2 + (int) $a3 + 0;				
				break;
			case 4:
				$sumaProgramada = (int) $t1 + (int) $t2 + (int) $t3 + (int) $t4 + 0;
				$sumaReales     = (int) $a1 + (int) $a2 + (int) $a3 + (int) $a4 + 0;				
				break;				
		}
		if($sumaProgramada == 0 && $sumaReales == 0){
			//$avance = 100;
			$avance = ( 100 * $ponderacion);
		}
		if($sumaProgramada > 0 && $sumaReales == 0){
			$avance = ( (($sumaReales / $sumaProgramada) * 100) * $ponderacion);
			//$avance = ( ($sumaReales / $sumaProgramada) * 100);
		}
		if($sumaProgramada == 0 && $sumaReales > 0){
			$avance = ( (($sumaReales / $sumaReales) *100) * $ponderacion);
			//$avance = ( ($sumaReales / $sumaReales) * 100);
		}
		if($sumaProgramada > 0 && $sumaReales > 0){
			$divide = ($sumaReales / $sumaProgramada);
			if($divide > 1){
				$avance = 100 * $ponderacion;
			}else{
				$avance = ( (($sumaReales / $sumaProgramada) *100)  * $ponderacion);
			}
			//$avance = ( ($sumaReales / $sumaProgramada) * 100);
		}
		return $avance;
	}
	
	
	function debug($array){
		echo"<pre>";
		print_r($array);
		die();		
	}
	/**
	 * Metodo que genera las estadisticas por eje
	 */
	function generaTablaEje(){
		$this->titulo  = "Tablero de Control ".$this->data['anoId'].": <b>Resumen General</b>";		
		$this->arrayDatos = $arrayProgr = $arrayProye = $arrayAct = array();
		$sql = "select eje_id,eje,programa_id,id,actividadId,ponderacionActividad,
				trimestre1,trimestre2,trimestre3,trimestre4,
				Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
				FROM ".$this->tabla." WHERE eje_id > 0 ".$this->filtro." ORDER BY eje_id;"; 
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($eje_id,$eje,$programa_id,$id,$actividadId,$ponderacion,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
				if(!in_array($actividadId,$arrayAct)){
					$arrayAct[] = $actividadId;
					$promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);		
					/*if($promedio > 100){
						$promedio = 100;
					}*/
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
					$this->arrayDatos[$eje_id]['promedio']     = $this->arrayDatos[$eje_id]['promedio']    + $promedio;				
				}
			}
			//$this->debug($this->arrayDatos);
			$this->generaCuadroEje();
		}
		else{
			$this->titulo = "";
			$this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
		}		
	}
	
	function generaTablaArea(){
		$this->titulo  = "Resumen General por <b>Unidad Responsable</b>";
		$this->arrayDatos = $arrayProgr = $arrayProye = $arrayAct = array();
		$sql = "select unidadResponsableId,area,programa_id,id,actividadId,ponderacionActividad,
				trimestre1,trimestre2,trimestre3,trimestre4,
				Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
				FROM ".$this->tabla." WHERE unidadResponsableId > 0 ".$this->filtro." ORDER BY area;";	
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($area_id,$area,$programa_id,$id,$actividadId,$ponderacion,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
				if(!in_array($actividadId,$arrayAct)){
					$arrayAct[] = $actividadId;
					$promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);
					/*if($promedio > 100){
						$promedio = 100;
					}*/
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
		$sql = "select id,proyecto,actividadId,ponderacionActividad,
				trimestre1,trimestre2,trimestre3,trimestre4,
				Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
				FROM ".$this->tabla." WHERE unidadResponsableId > 0 ".$this->filtro." ORDER BY proyecto;";		
		$res = $this->db->sql_query($sql) or die($this->cadena);
		if($this->db->sql_numrows($res)>0){
			while(list($id,$proyecto,$actividadId,$ponderacion,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
				if(!in_array($actividadId, $arrayAct)){
					$arrayAct[] = $actividadId;
					$promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);
					/*if($promedio > 100){
						$promedio = 100;
					}*/
					$this->arrayDatos[$id]['proyecto']   = utf8_encode($proyecto);
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
				//if($rand > 100){ $rand = 100;}
				$urlTmp = $this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=2&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$this->data['idArea']."&idProyecto=".$ind."&ponderaId=".$this->data['ponderaId'];
				$valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
				$valorPP  = $valorPP  +  (double) $tmp['ponderacion'] + 0;
				$valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
				//$valorPr  = $valorPr  +  (double) $rand + 0;
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
			//$valorPP = $valorAct;
			if($valorPP > 0){
				$promedioTotal = ($valorPr / $valorPP)*1;
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
				//if($rand > 100){ $rand = 100;}
				$this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand. '%</td>
								</tr>';
				
				$this->xml.="<set label='".$contador."' tooltext='".$tmp['proyecto']." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."' value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."'/>";
				$this->xmlPor.="<set label='".$contador."' tooltext='".$tmp['proyecto']." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
				$contador++;
			}
			$promedioTotal = 0;
			//$valorPP = $valorAct;// quitar esta linea si se hace con la ponderacion
			if($valorPP > 0){
				$promedioTotal = ($valorPr / $valorPP)*1;
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
				//if($rand > 100){ $rand = 100;}
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
			//$valorPP = $valorAct;  // quitar esta linea si se hace con la ponderacion			
			if($valorPP > 0){
				$promedioTotal = ($valorPr / $valorPP)*1;
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
		$valorPg = $valorPy = $valorAct = $totalAvance = 0; 
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
				//if($rand > 100){ $rand = 100;}
				$urlTmp = $this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=1&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$ind."&ponderaId=".$this->data['ponderaId'];
				$valorPg  = $valorPg  +  (int) $tmp['programa_id'] + 0;
				$valorPy  = $valorPy  +  (int) $tmp['proyecto_id'] + 0;
				$valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
				$valorPP  = $valorPP  +  (double) $tmp['ponderacion'] + 0;
				$valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
				$totalAvance = $totalAvance + $rand + 0;
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
				
				$this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand. '%</td></tr>';
				
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
		
			if(count($this->arrayDatos) > 0){
				$promedioTotal = ($totalAvance / count($this->arrayDatos))*1;
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
		$valorPg = $valorPy = $valorAct = $totalAvance = 0; 
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
				$totalAvance = $totalAvance + $rand + 0;
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
			//$valorPP = $valorAct;
			
			if(count($this->arrayDatos) > 0){
				$promedioTotal = ($totalAvance / count($this->arrayDatos))*1;
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
		/*if($tmp['actividad_id'] > 0){
			$rand = (($tmp['promedio'] / $tmp['actividad_id']) *100);
			if($rand > 100){
				$rand = 100;
			}
		}*/
		
		
		if($tmp['ponderacion'] > 0){
			$rand = (($tmp['promedio'] / $tmp['ponderacion']) *1);
			if($rand > 100){
				$rand = 100;
			}			
		}
		
		/*if(count($this->arrayDatos) > 0){
			$rand = (($tmp['promedio'] / count($this->arrayDatos)) *1);
			if($rand > 100){
				$rand = 100;
			}
		}*/
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
			$this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."'> / A&ntilde;o ".$this->data['anoId']."</a>";
		}
		if( (int) $this->data['trimestreId'] > 0){
			$this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."'> / Trimestre ".$this->data['trimestreId']."</a>";
		}
		if( (int) $this->data['idEje'] > 0){
			$this->filtro.= " AND eje_id = '".$this->data['idEje']."' ";
			$this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."'> / ".$this->catalogoA[$this->data['idEje']]."</a> ";
		}
		if( (int) $this->data['idPrograma'] > 0){
			$this->filtro .= " AND programa_id = '".$this->data['idPrograma']."' ";			
			$this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."&idPrograma=".$this->data['idPrograma']."'> / ".$this->catalogoB[$this->data['idPrograma']]."</a> ";
		}
		if( (int) $this->data['idPrograma'] > 0 && (int) $this->data['idProyecto'] > 0 ){
			$this->filtro .= " AND id = '".$this->data['idProyecto']."' ";
			$this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."&idPrograma=".$this->data['idPrograma']."&idProyecto=".$this->data['idProyecto']."'> / ".$this->catalogoD[$this->data['idProyecto']]."</a> ";			
		}		
		if( (int) $this->data['idArea'] > 0 ){			
			$this->filtro .= " AND unidadResponsableId = '".$this->data['idArea']."' ";
			$this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$this->data['idArea']."'> / ".$this->catalogoC[$this->data['idArea']]."</a> ";			
		}
		if( (int) $this->data['idArea'] > 0 && (int) $this->data['idProyecto'] > 0 ){
			$this->filtro .= " AND id = '".$this->data['idProyecto']."' ";
			$this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$this->data['idArea']."&idProyecto=".$this->data['idProyecto']."'> / ".$this->catalogoD[$this->data['idProyecto']]."</a> ";			
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