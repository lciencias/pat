<?php
class Estadistica
{
	var $db;
	function __construct($db){
		$this->db=$db;
	}
	function regresa_un_tianguis($tianguis_id)
	{
		$array_tianguis = array();
		$sql="SELECT * FROM cat_tianguis WHERE tianguis_id = ".$tianguis_id." ORDER BY tianguis_id;";
		$res=$this->db->sql_query($sql);
		$num=$this->db->sql_numrows($res);
		if($num > 0)
		{
			$array_tianguis = mysql_fetch_assoc($res);

		}
		return $array_tianguis;
	}

	function regresa_tianguis()
	{
		$array_tianguis = array();
		$sql="SELECT * FROM cat_tianguis ORDER BY tianguis_id;";
		$res=$this->db->sql_query($sql);
		$num=$this->db->sql_numrows($res);
		if($num > 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$array_tianguis[$fila['tianguis_id']]=$fila['nombre'];
			}
		}
		return $array_tianguis;
	}



	function regresa_delegacion($delegacion_id)
	{
		$nombre="";
		$sql_del="SELECT * FROM cat_delegaciones WHERE delega_id=".$delegacion_id.";";
		$res_del=$this->db->sql_query($sql_del);
		$num_del=$this->db->sql_numrows($res_del);
		if($num_del > 0)
		{
			$nombre=mysql_result($res_del,0,'delega_nom');
		}
		return $nombre;
	}

	function regresa_programa($programa_id)
	{
		$nombre_programa="";
		$sql_prg="SELECT * FROM cat_programas WHERE programa_id=".$programa_id." ORDER BY programa_id;";
		$res_prg=$this->db->sql_query($sql_prg);
		$num_prg=$this->db->sql_numrows($res_prg);
		if($num_prg > 0)
		{
			$nombre_programa=mysql_result($res_prg,0,'nombre');
		}
		return $nombre_programa;
	}

	function regresa_datos_area($area_id)
	{
		$nombre_area="";
		$sql_area="SELECT * FROM cat_areas WHERE area_id=".$area_id.";";
		$res_area=$this->db->sql_query($sql_area);
		$num_area=$this->db->sql_numrows($res_area);
		if($num_area > 0)
		{
			$nombre_area=mysql_result($res_area,0,'nombre');
		}
		return $nombre_area;
	}
	function programas($area_id)
	{
		$array_programas=array();
		$sql="SELECT * FROM cat_programas WHERE area_id=".$area_id." ORDER BY programa_id;";
		$res=$this->db->sql_query($sql);
		$num=$this->db->sql_numrows($res);
		if( $num > 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$array_programas[$fila['programa_id']]=$fila['nombre'];
			}
		}
		return $array_programas;
	}
	function indicadores($cat_programa_id)
	{
		$array_indicares=array();
		$sql="SELECT * FROM cat_indicadores WHERE programa_id=".$cat_programa_id." ORDER BY indicador_id;";
		$res=$this->db->sql_query($sql);
		$num=$this->db->sql_numrows($res);
		if( $num > 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$array_indicares[$fila['indicador_id']]=$fila['nombre'];
			}
		}
		return $array_indicares;
	}



	function indicadores_modales($cat_programa_id)
	{
		$array_indicares=array();
		$sql="SELECT * FROM cat_indicadores WHERE programa_id=".$cat_programa_id." ORDER BY indicador_id;";
		$res=$this->db->sql_query($sql);
		$num=$this->db->sql_numrows($res);
		if( $num > 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$array_indicares[$fila['indicador_id']]=$fila['modal'];
			}
		}
		return $array_indicares;
	}



	function programas_unicos($programa_id)
	{
		$array_programas=array();
		$sql="SELECT * FROM cat_programas WHERE programa_id=".$programa_id." ORDER BY programa_id;";
		$res=$this->db->sql_query($sql);
		$num=$this->db->sql_numrows($res);
		if( $num > 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$array_programas[$fila['programa_id']]=$fila['nombre'];
			}
		}
		return $array_programas;
	}

	function meses()
	{
		$array_mes[1]="Ene";
		$array_mes[2]="Feb";
		$array_mes[3]="Mar";
		$array_mes[4]="Abr";
		$array_mes[5]="May";
		$array_mes[6]="Jun";
		$array_mes[7]="Jul";
		$array_mes[8]="Ago";
		$array_mes[9]="Sep";
		$array_mes[10]="Oct";
		$array_mes[11]="Nov";
		$array_mes[12]="Dic";
		return $array_mes;
	}

	function regresa_delegaciones($delegacion)
	{
		$select='';
		$sql="SELECT * FROM cat_delegaciones ORDER BY delega_id;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res) > 0)
		{
			$select.="<select name='delegacion_id' id='delegacion_id'><option value=''></option>";
			while($fila = mysql_fetch_assoc($res))
			{
				$tmp="";
				if($fila['delega_nom']==$delegacion)
				$tmp=" SELECTED ";
				$select.="<option value='".$fila['delega_id']."' ".$tmp.">".$fila['delega_nom']."</option>";
			}
			$select.="</select>";
		}
		return $select;
	}
	function regresa_metas($area,$programa,$ano,$tipo)
	{
		$array_datos=array();
		$sql="SELECT * FROM proyectos_metas WHERE area_id=".$area." AND cat_programa_id=".$programa." AND ano='".$ano."' AND tipo='".$tipo."';";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$array_datos[$fila['cat_programa_id']]['1']=$fila['ene'];
				$array_datos[$fila['cat_programa_id']]['2']=$fila['feb'];
				$array_datos[$fila['cat_programa_id']]['3']=$fila['mar'];
				$array_datos[$fila['cat_programa_id']]['4']=$fila['abr'];
				$array_datos[$fila['cat_programa_id']]['5']=$fila['may'];
				$array_datos[$fila['cat_programa_id']]['6']=$fila['jun'];
				$array_datos[$fila['cat_programa_id']]['7']=$fila['jul'];
				$array_datos[$fila['cat_programa_id']]['8']=$fila['ago'];
				$array_datos[$fila['cat_programa_id']]['9']=$fila['sep'];
				$array_datos[$fila['cat_programa_id']]['10']=$fila['oct'];
				$array_datos[$fila['cat_programa_id']]['11']=$fila['nov'];
				$array_datos[$fila['cat_programa_id']]['12']=$fila['dic'];
			}
		}
		return $array_datos;
	}

	function regresa_actividades_subprogramas($area_id,$programa_id,$subprograma_id,$ano,$estatus_filtro)
	{
		if($estatus_filtro !='')
			$estatus_filtro= "AND ".$estatus_filtro." ";

		$campo =" substr( proy_fecha_inicio, 1, 7 )";
		$filtro_ano= " substr( proy_fecha_inicio, 1, 4 ) = '".$ano."' ";
		$sql="SELECT proy_tevento,".$campo." as meses, count(".$campo." ) AS total,sum(frecuencia) as frecuencia
			  FROM proyectos
			  WHERE area_id =".$area_id." AND cat_programa_id = ".$programa_id." AND subprograma_id =".$subprograma_id." AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY proy_tevento,".$campo." ORDER BY proy_tevento,".$campo." ASC;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$tmp_proy_evento=$fila['proy_tevento'];
				$mes_id=substr($fila['meses'],5,2) + 0;
				$array_actividades[$tmp_proy_evento][$mes_id]=$fila['frecuencia'];
			}
		}
		return $array_actividades;
	}



	function regresa_actividades($area,$programa,$ano,$estatus)
	{
		$estatus_filtro='';
		if($estatus!='')
			$estatus_filtro= "AND proy_status in (".$estatus.")";

		$campo =" substr( proy_fecha_inicio, 1, 7 )";
		$filtro_ano= " substr( proy_fecha_inicio, 1, 4 ) = '".$ano."' ";
		if( ($programa>=83) && ($programa<=87))
		{
			switch($programa)
			{
				case 83:
					$estatus_filtro.= " AND proy_recinto = 101";
					break;
				case 84:
					$estatus_filtro.= " AND proy_recinto = 105";
					break;
				case 85:
					$estatus_filtro.= " AND proy_recinto = 128";
					break;
				case 86:
					$estatus_filtro.= " AND proy_recinto = 142";
					break;
				case 87:
					$estatus_filtro.= " AND proy_recinto IN  (2,143,136)";
					break;
			}

			$sql="SELECT proy_tevento as tema,".$campo." as meses, count(".$campo." ) AS total, sum(frecuencia) as frecuencia
			FROM proyectos
			  WHERE area_id =".$area." AND cat_programa_id = 35 AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY proy_tevento,".$campo." ORDER BY proy_tevento,".$campo." ASC;";
		}
		else
		{
			$sql="SELECT proy_tevento as tema,".$campo." as meses, count(".$campo." ) AS total,sum(frecuencia) as frecuencia
			FROM proyectos
			  WHERE area_id =".$area." AND cat_programa_id = ".$programa." AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY proy_tevento,".$campo." ORDER BY proy_tevento,".$campo." ASC;";
		}
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$tmp_proy_evento=$fila['tema'];
				$mes_id=substr($fila['meses'],5,2) + 0;
				$array_actividades[$tmp_proy_evento][$mes_id]=$fila['frecuencia'];
			}
		}
		return $array_actividades;
	}



	function regresa_asistentes_subprogramas($area_id,$programa_id,$subprograma_id,$ano,$estatus_filtro)
	{
		$array_asistentes=array();
		if($estatus_filtro !='')
			$estatus_filtro= "AND ".$estatus_filtro." ";

		$campo =" substr( proy_fecha_inicio, 1, 7 )";
		$filtro_ano= " substr( proy_fecha_inicio, 1, 4 ) = '".$ano."' ";
		$sql="SELECT proy_tevento,".$campo." as meses,  (sum( proy_asis_m_total ) + sum( proy_asis_h_total )) AS total
			  FROM proyectos
			  WHERE area_id =".$area_id." AND cat_programa_id = ".$programa_id." AND subprograma_id =".$subprograma_id." AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY proy_tevento,".$campo." ORDER BY proy_tevento,".$campo." ASC;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$tmp_proy_evento=$fila['proy_tevento'];
				$mes_id=substr($fila['meses'],5,2) + 0;
				$array_asistentes[$tmp_proy_evento][$mes_id]=$fila['total'];
			}
		}
		return $array_asistentes;
	}



	function regresa_asistentes($area,$programa,$ano,$estatus)
	{
		$array_asistentes=array();
		$estatus_filtro='';
		if($estatus!='')
			$estatus_filtro= "AND proy_status IN (".$estatus.")";

		$campo =" substr( proy_fecha_inicio, 1, 7 )";
		$filtro_ano= " substr( proy_fecha_inicio, 1, 4 ) = '".$ano."' ";
		if( ($programa>=83) && ($programa<=87))
		{
			switch($programa)
			{
				case 83:
					$estatus_filtro.= " AND proy_recinto = 101";
					break;
				case 84:
					$estatus_filtro.= " AND proy_recinto = 105";
					break;
				case 85:
					$estatus_filtro.= " AND proy_recinto = 128";
					break;
				case 86:
					$estatus_filtro.= " AND proy_recinto = 142";
					break;
				case 87:
					$estatus_filtro.= " AND proy_recinto = (2,143,136)";
					break;
			}
			$sql="SELECT proy_tevento as tema,".$campo." as meses,  (sum( proy_asis_m_total ) + sum( proy_asis_h_total )) AS total
			FROM proyectos
			  WHERE area_id =".$area." AND cat_programa_id = 35 AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY proy_tevento,".$campo." ORDER BY proy_tevento,".$campo." ASC;";

		}
		else
		{
			$sql="SELECT proy_tevento  as tema,".$campo." as meses,  (sum( proy_asis_m_total ) + sum( proy_asis_h_total )) AS total
			  FROM proyectos
			  WHERE area_id =".$area." AND cat_programa_id = ".$programa." AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY proy_tevento,".$campo." ORDER BY proy_tevento,".$campo." ASC;";
		}
		$res=$this->db->sql_query($sql);

		if($this->db->sql_numrows($res)> 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$tmp_proy_evento=$fila['tema'];
				$mes_id=substr($fila['meses'],5,2) + 0;
				$array_asistentes[$tmp_proy_evento][$mes_id]=$fila['total'];
			}
		}

		return $array_asistentes;

	}


	function regresa_teventos()
	{
		$array_teventos=array();
		$sql="SELECT event_id,event_nombre FROM cat_tevento ORDER BY event_id;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$array_teventos[($fila['event_id'] + 0)]=$fila['event_nombre'];
			}
		}
		return $array_teventos;
	}



	function regresa_talleres()
	{
		$array_talleres=array();
		$sql="SELECT taller_id,nombre FROM cat_talleres WHERE visible=1 ORDER BY taller_id;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while(list($taller_id,$nombre)= $this->db->sql_fetchrow($res))
			{
				$array_talleres[$taller_id]=$nombre;
			}
		}
		return $array_talleres;
	}



	function normaliza_a_meses($array)
	{
		$array_regreso=array();
		if(count($array) > 0)
		{
			$tmp_evento=-1;
			foreach($array as $tevento => $data)
			{
				$array_regreso[$tevento]=$this->inicializa_arreglo();
			}
			$total=0;
			foreach($array as $tevento => $data)
			{
				foreach($data as $clave => $valor)
				{
					$array_regreso[$tevento][$clave]=$valor;
					$total = $total + $valor;
				}
				$array_regreso[$tevento][13]=$total;
				$total=0;
			}
		}
		return $array_regreso;
	}



	function inicializa_arreglo()
	{
		$max=12;
		for($pos=1; $pos<=$max; $pos++)
		{
			$array_tmp[$pos]=0;
		}
		return $array_tmp;
	}



	function regresa_indicadores($programa)
	{
		$array_indicadores=array();
		$sql_con="SELECT indicador_id,programa_id,nombre  FROM cat_indicadores WHERE programa_id=".$programa.";";
		$res_con=$this->db->sql_query($sql_con);
		$num_con=$this->db->sql_numrows($res_con);
		if($num_con > 0)
		{
			while($fila = $this->db->sql_fetchrow ($res_con))
			{
				$array_indicadores[$fila['indicador_id']]=$fila['nombre'];
			}
		}
		return $array_indicadores;
	}

	function regresa_valores_indicador($programa,$ano)
	{
		$filtro='';
		$array_res_indicadores=array();
		if( ($programa>=83) && ($programa<=87))
		{
			$sql_con="SELECT * FROM proyectos_indicadores WHERE cat_programa_id = 35 AND ano=".$ano." ";
		}
		else
		{
			$sql_con="SELECT * FROM proyectos_indicadores WHERE cat_programa_id=".$programa." AND ano=".$ano." ;";
		}
		$res_con=$this->db->sql_query($sql_con);
		$num_con=$this->db->sql_numrows($res_con);
		if($num_con > 0)
		{
			while($fila = $this->db->sql_fetchrow($res_con,MYSQL_ASSOC))
			{
				$x=$fila['indicador_id'];
				$array_res_indicadores[$x]['0']=$fila['ano'];
				$array_res_indicadores[$x]['1']=$fila['ene'];
				$array_res_indicadores[$x]['2']=$fila['feb'];
				$array_res_indicadores[$x]['3']=$fila['mar'];
				$array_res_indicadores[$x]['4']=$fila['abr'];
				$array_res_indicadores[$x]['5']=$fila['may'];
				$array_res_indicadores[$x]['6']=$fila['jun'];
				$array_res_indicadores[$x]['7']=$fila['jul'];
				$array_res_indicadores[$x]['8']=$fila['ago'];
				$array_res_indicadores[$x]['9']=$fila['sep'];
				$array_res_indicadores[$x]['10']=$fila['oct'];
				$array_res_indicadores[$x]['11']=$fila['nov'];
				$array_res_indicadores[$x]['12']=$fila['dic'];
			}
		}
		return $array_res_indicadores;
	}



	function pinta_check($area_id,$cl,$key,$array_res_indicadores,$tmp_modal,$ano)
	{
		$buf='';
		for($mes=1; $mes<=12; $mes++)
		{
			$valor_mes= 0 + $array_res_indicadores[$key][$mes];
			$buf.="<th align='center'>".$valor_mes;
			if( ($tmp_modal >0) && ($tmp_modal <4) )
			{
				if ($tmp_modal==1)
				$tmp_tit="hos";
				if ($tmp_modal==2)
				$tmp_tit="rec";
				if ($tmp_modal==3)
				$tmp_tit="lib";
				$url=$tmp_tit."|".$area_id."|".$cl."|".$key."|".$mes."|".$ano."|".$valor_mes;
				$ventana_modal="<input type='button' name='link' value='Ver' class='link' title='$url'/> ";
				$buf.="<br>".$ventana_modal;
			}
			$buf.="</th>";
		}
		return $buf;
	}



	function pinta_check_lectura($area_id,$cl,$key,$array_res_indicadores,$tmp_modal,$ano)
	{
		$buf='';
		for($mes=1; $mes<=12; $mes++)
		{
			$valor_mes= 0 + $array_res_indicadores[$key][$mes];
			$buf.="<th align='center'>".$valor_mes;
			$buf.="</th>";
		}
		return $buf;
	}

	function regresa_actividades_eventos_subprogramas($data)
	{
		$filtro_status='';
		$cadena_status='';
		if($data['status']!='')
		{
			$array_tmp=explode('|',$data['status']);
			foreach($array_tmp as $vll)
			{
				$cadena_status.="'".$vll."',";
			}
			$cadena_status=substr($cadena_status,0,(strlen($cadena_status)-1));
			$filtro_status="and proy_status in(".$cadena_status.") ";
	   }
		$ano_id=$data['ano'];
		$mes=str_pad($data['mes'],2,"0",STR_PAD_LEFT);
		if($mes=='13')
		{
			$filtro= "and substr(proy_fecha_inicio,1,4)='".$data['ano']."'";
		}
		else
		{
			$filtro= "and substr(proy_fecha_inicio,1,7)='".$data['ano'].'-'.$mes."'";
		}
		$buffer='';
		$sql="SELECT proy_id,proy_coordinacion_id,proy_area_id,proy_responsable,proy_nombre,proy_fecha_inicio,area_id,cat_programa_id,proy_status ,proy_asis_m_total ,proy_asis_h_total FROM proyectos WHERE area_id=".$data['area']." and cat_programa_id=".$data['pro']." and subprograma_id=".$data['sub']." ".$filtro." and proy_tevento = '".$data['ind']."' ".$filtro_status."  ORDER BY area_id,cat_programa_id,proy_coordinacion_id,proy_area_id,proy_responsable;";
		$res=$this->db->sql_query($sql);
		$num=$this->db->sql_numrows($res);
		if($num > 0)
		{
			$conta=0;
			while(list($proy_id,$proy_coordinacion_id,$proy_area_id,$proy_responsable,$proy_nombre,$proy_fecha_inicio,$area_id,$cat_programa_id,$proy_status,$proy_asis_m_total,$proy_asis_h_total) = $this->db->sql_fetchrow($res))
			{
				if($conta == 0)
				{
					$buffer.="<table width='100%' align='center' border='0'><tr><td>
					Coordinaci&oacute;n:&nbsp;/ Direcci&oacute;n:&nbsp;".$proy_coordinacion_id."  ".$this->consulta('coor_nombre','cat_coordinaciones','coor_id',$proy_coordinacion_id)."</td><td>
					&Aacute;rea:&nbsp;".$area_id."  ".$this->consulta('nombre','cat_areas','area_id',$area_id)."</td></tr></table>";
					$buffer.="<table width='100%' align='center' border='0' class='tablesorter'>
					<thead>
					<tr>
					<td>Folio</td>
					<td>Programa</td>
					<td>Responsable</td>
					<td>Nombre</td>
					<td>Fecha</td>
					<td>Poblaci&oacute;n</td>
					<td>Estatus</td>
					<td>Consultar</td>
					</tr></thead><tbody>";
				}
				$proy_poblacion=$proy_asis_m_total + $proy_asis_h_total;
				$buffer.="<tr>
					<th align='left'>".$proy_id."</th>
					<th align='left'>".$cat_programa_id."  ".$this->consulta('nombre','cat_programas','programa_id',$cat_programa_id)."</th>
					<th align='left'>".$proy_responsable."  ".$this->consulta('respon_nombre','cat_responsables','respon_id',$proy_responsable)."</th>
					<th align='left'>".$proy_nombre."</th>
					<th align='left'>".$proy_fecha_inicio."</th>
					<th align='left'>".$proy_poblacion."</th>
					<th align='left'>".$proy_status."</th>
					<th align='center'><a href=\"javascript:lanza_ventana_visualizar('$area_id','$cat_programa_id','$proy_id','$ano_id','$mes','2','$user_id');\"><img src='../imagenes/magnifier.png' width='16' height='16' border='0'></a></th>
					</tr>";
				$conta++;
			}
			$buffer.="</tbody><thead><tr><td colspan='9'>Total de registros:  ".$num."</td></tr><thead></table>";
		}
		else
		{
			$buffer="No hay actividades registradas";
		}
		return $buffer;
	}
	function regresa_actividades_eventos_coproduccion($data)
	{
		$sum_act=0;
		$sum_asis=0;
		$sum_total=0;
		$sum_act_2=0;
		$sum_asis_2=0;
		$sum_total_2=0;

		$user_id=1;
		$filtro_status='';
		$cadena_status='';
		if($data['status']!='')
		{
			$array_tmp=explode('|',$data['status']);
			foreach($array_tmp as $vll)
			{
				$cadena_status.="'".$vll."',";
			}
			$cadena_status=substr($cadena_status,0,(strlen($cadena_status)-1));
			$filtro_status="and proy_status in(".$cadena_status.") ";
		}
		$ano_id=$data['ano'];
		$mes=str_pad($data['mes'],2,"0",STR_PAD_LEFT);
		if($mes=='13')
		{
			$filtro= "and substr(proy_fecha_inicio,1,4)='".$data['ano']."'";
		}
		else
		{
			$filtro= "and substr(proy_fecha_inicio,1,7)='".$data['ano'].'-'.$mes."'";
		}
		$buffer='';
		$sql="SELECT folio_id,proy_tema,proy_fecha_inicio,area_id,programa_id,proy_status,proy_act_1,proy_act_2,proy_asis_1,proy_asis_2,
		proy_poblacion_act,proy_poblacion_asis FROM proyectos_festivales WHERE area_id=".$data['area']." and programa_id=".$data['pro']." ".$filtro." ".$filtro_status."  ORDER BY area_id,programa_id,folio_id;";
		$res=$this->db->sql_query($sql);
		$num=$this->db->sql_numrows($res);
		if($num > 0)
		{
			$conta=0;
			while(list($folio_id,$descripcion,$fecha,$area_id,$programa_id,$status,$act_1,$act_2,$asi_1,$asi_2,$t_act,$t_asi) = $this->db->sql_fetchrow($res))
			{
				if($conta == 0)
				{
					$buffer.="<table width='100%' align='center' border='0'><tr><td>
					 &Aacute;rea:&nbsp;".$area_id."  ".$this->consulta('nombre','cat_areas','area_id',$area_id)."</td>
					 <td>
					 Programa:&nbsp;".$programa_id."  ".$this->consulta('nombre','cat_programas','programa_id',$programa_id)."</td>
					 </tr></table>";
					$buffer.="<table width='100%' align='center' border='0' class='tablesorter'>
					<thead>
					<tr>
					<td>Folio</td>
					<td>Descripci&oacute;n</td>
					<td>Act Fest</td>
					<td>Act SC</td>
					<td>Total de actividades del evento/festival</td>
					<td>Asist Fest</td>
					<td>Asist SC</td>
					<td>Total de asistentes del evento/festival</td>
					<td>Consultar</td>
					</tr></thead><tbody>";
				}
				$buffer.="<tr>
					<th align='left'>".$folio_id."</th>
					<th align='left'>".$fecha."<br>".$descripcion."</th>
					<th align='center'>".$act_1."</th>
					<th align='center'>".$act_2."</th>
					<th align='center'>".$t_act."</th>
					<th align='center'>".$asi_1."</th>
					<th align='center'>".$asi_2."</th>
					<th align='center'>".$t_asi."</th>
					<th align='center'><a href=\"javascript:lanza_ventana_visualizar('$area_id','$programa_id','$folio_id','$ano_id','$mes','2','$user_id');\"><img src='../imagenes/magnifier.png' width='16' height='16' border='0'></a></th>
					</tr>";
				$conta++;
				$sum_act_1=$sum_act_1 + $act_1;
				$sum_asis_1=$sum_asis_1 + $asi_1;
				$sum_total_1=$sum_total_1 + $t_act;
				$sum_act_2=$sum_act_2 + $act_2;
				$sum_asis_2=$sum_asis_2 + $asi_2;
				$sum_total_2=$sum_total_2 + $t_asi;
			}
			$buffer.="</tbody><thead><tr><td colspan='2'>Total de registros:  ".$num."</td>
						<td>".$sum_act_1."</td><td>".$sum_act_2."</td><td bgcolor='#fbcd33'>".$sum_total_1."</td><td>".$sum_asis_1."</td><td>".$sum_asis_2."</td>
						<td bgcolor='#fbcd33'>".$sum_total_2."</td><td>&nbsp;</td>					
						</tr><thead></table>";
		}
		else
		{
			$buffer="No hay actividades registradas";
		}
		return $buffer;
	}

	function regresa_actividades_eventos($data)
	{
		$filtro_status='';
		$cadena_status='';
		if($data['status']!='')
		{
			$array_tmp=explode('|',$data['status']);
			foreach($array_tmp as $vll)
			{
				$cadena_status.="'".$vll."',";
			}
			$cadena_status=substr($cadena_status,0,(strlen($cadena_status)-1));
			$filtro_status="and proy_status in(".$cadena_status.") ";
		}
		$ano_id=$data['ano'];
		$mes=str_pad($data['mes'],2,"0",STR_PAD_LEFT);
		if($mes=='13')
		{
			$filtro= "and substr(proy_fecha_inicio,1,4)='".$data['ano']."'";
		}
		else
		{
			$filtro= "and substr(proy_fecha_inicio,1,7)='".$data['ano'].'-'.$mes."'";
		}
		$buffer='';
		if(($data['pro']>=83) && ($data['pro']<=87))
		{
			switch($data['pro'])
			{
				case 83:
					$filtro.= " AND proy_recinto = 101";
					break;
				case 84:
					$filtro.= " AND proy_recinto = 105";
					break;
				case 85:
					$filtro.= " AND proy_recinto = 128";
					break;
				case 86:
					$filtro.= " AND proy_recinto = 142";
					break;
				case 87:
					$filtro.= " AND proy_recinto IN (2,143,136)";
					break;
			}
			$filtro.= " AND proy_tevento =".$data['ind']." ";
			$sql="SELECT proy_id,proy_coordinacion_id,proy_area_id,proy_responsable,proy_nombre,proy_fecha_inicio,area_id,cat_programa_id,proy_status ,proy_asis_m_total ,proy_asis_h_total 
				  FROM proyectos WHERE area_id=".$data['area']." and cat_programa_id =35 ".$filtro."
				   ".$filtro_status."  ORDER BY area_id,cat_programa_id,proy_coordinacion_id,proy_area_id,proy_responsable;";
		}
		else
		{
			$sql="SELECT proy_id,proy_coordinacion_id,proy_area_id,proy_responsable,proy_nombre,proy_fecha_inicio,area_id,cat_programa_id,proy_status ,proy_asis_m_total ,proy_asis_h_total
				  FROM proyectos WHERE area_id=".$data['area']." and cat_programa_id=".$data['pro']." ".$filtro." and proy_tevento = '".$data['ind']."' ".$filtro_status."  ORDER BY area_id,cat_programa_id,proy_coordinacion_id,proy_area_id,proy_responsable;";
		}
		$res=$this->db->sql_query($sql);
		$num=$this->db->sql_numrows($res);
		if($num > 0)
		{
			$conta=0;
			while(list($proy_id,$proy_coordinacion_id,$proy_area_id,$proy_responsable,$proy_nombre,$proy_fecha_inicio,$area_id,$cat_programa_id,$proy_status,$proy_asis_m_total,$proy_asis_h_total) = $this->db->sql_fetchrow($res))
			{
				if($conta == 0)
				{
					$buffer.="<table width='100%' align='center' border='0'><tr><td>
					Coordinaci&oacute;n:&nbsp;/ Direcci&oacute;n:&nbsp;".$proy_coordinacion_id."  ".$this->consulta('coor_nombre','cat_coordinaciones','coor_id',$proy_coordinacion_id)."</td><td>
					&Aacute;rea:&nbsp;".$area_id."  ".$this->consulta('nombre','cat_areas','area_id',$area_id)."</td></tr></table>";
					$buffer.="<table width='100%' align='center' border='0' class='tablesorter'>
					<thead>
					<tr>
					<td>Folio</td>
					<td>Programa</td>
					<td>Responsable</td>
					<td>Nombre</td>
					<td>Fecha</td>
					<td>Poblaci&oacute;n</td>
					<td>Estatus</td>
					<td>Consultar</td>
					</tr></thead><tbody>";
				}
				$proy_poblacion=$proy_asis_m_total + $proy_asis_h_total;
				$buffer.="<tr>
					<th align='left'>".$proy_id."</th>
					<th align='left'>".$cat_programa_id."  ".$this->consulta('nombre','cat_programas','programa_id',$cat_programa_id)."</th>
					<th align='left'>".$proy_responsable."  ".$this->consulta('respon_nombre','cat_responsables','respon_id',$proy_responsable)."</th>
					<th align='left'>".$proy_nombre."</th>
					<th align='left'>".$proy_fecha_inicio."</th>
					<th align='left'>".$proy_poblacion."</th>
					<th align='left'>".$proy_status."</th>
					<th align='center'><a href=\"javascript:lanza_ventana_visualizar('$area_id','$cat_programa_id','$proy_id','$ano_id','$mes','2','$user_id');\"><img src='../imagenes/magnifier.png' width='16' height='16' border='0'></a></th>
					</tr>";
				$conta++;
			}
			$buffer.="</tbody><thead><tr><td colspan='9'>Total de registros:  ".$num."</td></tr><thead></table>";
		}
		else
		{
			$buffer="No hay actividades registradas";
		}
		return $buffer;
	}

	function consulta($campo_regreso,$tabla,$campo_filtro,$valor)
	{
		$regreso="";
		$sql_tmp="SELECT ".$campo_regreso." FROM ".$tabla." WHERE ".$campo_filtro."=".$valor.";";
		$res_tmp=$this->db->sql_query($sql_tmp);
		if($this->db->sql_numrows($res_tmp) > 0 )
		{
			$regreso=mysql_result($res_tmp,0,$campo_regreso);
		}
		return $regreso;
   }
	function Consulta_Ficha($nombre_area,$array_programas,$array_mes,$data)
	{

		$buffer="<input type='hidden'>
		<p align='left'>&nbsp;".$nombre_area."&nbsp;&nbsp;&nbsp;".$data['anoele']."</p>";
		$buffer.='<div align="justify" class="basic" style="float:left; font-size:8px; margin-left: 2em; margin-rigth: 2em; width:94%;" id="list1b">';
		$buffer.='<br><div id="VentanaModal">';
		$total_m1=0;
		$total_m2=0;
		$total_m3=0;
		$total_m4=0;
		$total_m5=0;
		$total_m6=0;
		$total_m7=0;
		$total_m8=0;
		$total_m9=0;
		$total_m10=0;
		$total_m11=0;
		$total_m12=0;
		foreach($array_programas as $cl => $vl)
		{
			$buffer.=$div;
			$buffer.="<a>".$vl."</a>";
			$array_modales=$this->indicadores_modales($cl);
			$array_indicadores=$this->regresa_indicadores($cl);
			$array_res_indicadores=$this->regresa_valores_indicador($cl,$data['anoele']);
			$buffer.="<div><p><table width='100%' class='tablesorter' border='0'>";
			if(count($array_indicadores)>0)
			{
				$buffer.="<thead><tr><th width='28%'>Indicador</th>";
				for ($n_mes=1; $n_mes<=12; $n_mes++)
				{
					$buffer.="<th width='6%' align='center'>".$array_mes[$n_mes]."</th>";
				}
				$buffer.="</tr></thead><tbody>";
			}

		   foreach($array_indicadores as $key_ind => $value_ind )
			{
				$buffer.="<tr height='30px'>
				<th align='left'>".$value_ind."</th>";
				$tmp_modal=$array_modales[$key_ind];
				$buffer.=$this->pinta_check($data['inv_area'],$cl,$key_ind,$array_res_indicadores,$tmp_modal,$data['anoele']);
				$buffer.="</tr>";
			}
			$buffer.="</tbody>";
			$buffer.="</table>
			</p>
			</div>";
			$buffer.="<div>";
		}
		$buffer.="</div>
			<div id='VentanaModalContent' style='display:none'><label id='contentTabla'></label></div>";
		return $buffer;
	}

	function Regresa_Subprogramas($area_id,$cl)
	{
		$tmp_array=array();
		$sql_sub="SELECT subprograma_id,subprograma FROM cat_subprogramas WHERE titulo=0 AND area_id=".$area_id." AND programa_id=".$cl." ORDER BY orden;";
		$res_sub=$this->db->sql_query($sql_sub);
		if($this->db->sql_numrows($res_sub) > 0 )
		{
			while(list($id,$nombre) = $this->db->sql_fetchrow($res_sub))
			{
				$tmp_array[$id]=$nombre;
			}
		}
		return $tmp_array;
	}

	
	function Consulta_Subprogramas_Estadistica($area_id,$programa_id,$ano,$estatus)
	{
		$tmp_cadena='';
		$array_subprogramas=array();
		$array_subprogramas=$this->Regresa_Subprogramas($area_id,$programa_id);
		$nm_programa=$this->regresa_programa($programa_id);
		$nm_area=$this->regresa_datos_area($area_id);
		$array_meses=$this->meses();
		$array_teventos=$this->regresa_teventos();
		$array_talleres=$this->regresa_talleres();
		if($estatus != '')
	   {
		   $tmp=explode('|',$estatus);
		   foreach($tmp as $tmp_cmp)
		   {
			   $tmp_cadena.="'".$tmp_cmp."',";
			}
			$tmp_estatus=substr($tmp_cadena,0,(strlen($tmp_cadena)-1));
			$filtro_estatus=" proy_status IN (".$tmp_estatus.") ";
		}
		$buffer='<div align="justify" class="basic" style="float:left; font-size:7px; margin-left: 1em; margin-rigth: 1em; width:98%;" id="list1b">';
		$buffer.='<br><span class="tit">&Aacute;rea:</span>&nbsp;<b>'.$nm_area.'</b>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tit">Programa:</span>&nbsp;&nbsp;<b>'.$nm_programa.'</b>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tit">Ano:</span>&nbsp;&nbsp;<b>'.$ano.'</b>';
		foreach($array_subprogramas as $cl => $vl)
		{
			$array_totales_actividades=$this->inicializa_arreglo();
			$array_totales_asistentes=$this->inicializa_arreglo();
			$array_totales_actividades_talleres=$this->inicializa_arreglo();
			$array_totales_asistentes_talleres=$this->inicializa_arreglo();
			$buffer.="<a>".$vl."</a>";
			$array_metas_actividades=$this->regresa_metas($area_id,$programa_id,$ano,'Actividades');
			$array_metas_asistentes =$this->regresa_metas($area_id,$programa_id,$ano,'Asistentes');
			$array_actividades=$this->regresa_actividades_subprogramas($area_id,$programa_id,$cl,$ano,$filtro_estatus);
			$array_actividades=$this->normaliza_a_meses($array_actividades);
			$array_asistentes =$this->regresa_asistentes_subprogramas($area_id,$programa_id,$cl,$ano,$filtro_estatus);
			$array_asistentes =$this->normaliza_a_meses($array_asistentes);
			$buffer.="<div>
				<table width='100%' class='tablesorter' border='0'>
				<thead><tr><th width='15%'>Actividad</th>";
				$contador=0;
				$cont_t=1;
				for ($n_mes=1; $n_mes<=12; $n_mes++)
				{
					$contador++;
					$buffer.="<th width='5%' align='center'>".$array_meses[$n_mes]."</th>";
					if($contador==3)
					{
						$buffer.="<th width='5%' align='center' style='background-color:#aa2b00;color:#fff;text-align:center;'>
							<table><tr><th>T".$cont_t."</th><th>A".$cont_t."</th></tr></table>";
						$contador=0;
						$cont_t++;
					}
				}
				$buffer.="<th width='5%' align='center'>Anual</th></tr></thead><tbody>";
				foreach($array_actividades as $key_ind => $value_ind )
				{
					$buffer.="<tr height='30px'><th>
					  <table border='0' width='100%' align='center'>
						<tr>
							<th rowspan='2' align='left' style='font-size:9px;'>".$array_teventos[($key_ind)+0]."</th>
							<th align='right'><font color='#aa2b00' size='1'>Act</font></th>
						</tr>
						<tr>
							<th align='right'><font color='#aa2b00' size='1'>Asi</font></th>
						</tr>
						</table></th>";
					$contador=0;
					$suma_act=0;
					$suma_asi=0;
					$suma_act_t=0;
					$suma_asi_t=0;
					foreach($value_ind as $k => $v)
					{
						$array_totales_actividades[$k]=($array_totales_actividades[$k] + $v);
						$array_totales_asistentes[$k]=($array_totales_asistentes[$k] + $array_asistentes[$key_ind][$k]);
						$suma_act = $suma_act + $v;
						$suma_asi = $suma_asi + $array_asistentes[$key_ind][$k];
						$suma_act_t = $suma_act_t + $v;
						$suma_asi_t = $suma_asi_t + $array_asistentes[$key_ind][$k];

						$buffer.="<th>
						<table border='0' width='100%' align='center'>
						<tr><th align='center'><input type='button' onclick=\"ventana_actividades_subprograma('".$cl."','".$key_ind."','".$k."','".$v."','".$area_id."','".$programa_id."','".$ano."','".$estatus."');\" style='border:0px;color:#333333;font-size:9px;text-decoration:underline;' value='".(0 + $v)."'></th></tr>
						<tr><th align='center' style='font-size:9px;'>".$array_asistentes[$key_ind][$k]."</th></tr>
						</table></th>";
						$contador++;
						if($contador  == 3)
						{
							$buffer.="<th align='center'>
								<table border='0' width='100%' align='center'>
								<tr>
								<th align='center' style='background-color:#fff;font-size:9px;border: 1px solid #3e4f88;'>
								".$suma_act_t."
								</th>
								<th align='center' style='background-color:#fff;font-size:9px;border: 1px solid #3e4f88;'>
								".$suma_act."
								</th>
								</tr>
								<tr>
								<th align='center' style='background-color:#fff;font-size:9px;border: 1px solid #3e4f88;'>
								".$suma_asi_t."
								</th>
								<th align='center' style='background-color:#fff;font-size:9px;border: 1px solid #3e4f88;'>
								".$suma_asi."
								</th>
								</tr>
								</table></th>";
							$contador=0;
							$suma_act_t=0;
							$suma_asi_t=0;
						}
					}
					$buffer.="</tr>";
				}
				if(count($array_totales_actividades) > 0)
				{
					$temp_total_actividades=0;
					$contador=0;
					$suma=0;
					$suma_actividades=0;
					$buffer.="<thead><tr><td style='color:#3e4f88;font-size:9px;'>Tot Ac</th>";
					foreach($array_totales_actividades as $mk => $mv)
					{
						$contador++;
						$suma=$suma + $mv;
						$suma_actividades=$suma_actividades+$mv;
						$buffer.="<td align='center' style='font-size:9px;'>".$mv."</td>";
						if($contador  == 3)
						{
							$buffer.="<td align='center' class='tdceldatri'>".$suma."</td>";
							$contador=0;
							$suma=0;
						}
					}
					$temp_total_actividades=$suma;
					$buffer.="</tr></thead>";
				}
				if(count($array_totales_asistentes) > 0)
				{
					$temp_total_asistentes=0;
					$contador=0;
					$suma=0;
					$suma_asistentes=0;
					$buffer.="<thead><tr><td style='color:#3e4f88;font-size:9px;'>Tot As</td>";
					foreach($array_totales_asistentes as $mk => $mv)
					{
						$contador++;
						$suma_asistentes=$suma_asistentes + $mv;
						$suma=$suma + $mv;
						$buffer.="<td align='center' style='font-size:9px;'>".$mv."</td>";
						if($contador  == 3)
						{
							$buffer.="<td align='center' class='tdceldatri'>".$suma."</td>";
							$contador=0;
							$suma=0;
						}
					}
					$temp_total_asistentes=$suma;
					$buffer.="</tr></thead>";
				}
				$meta_anual_actividades=0;
				$meta_anual_actividades_parcial=0;
				if(count($array_metas_actividades) > 0)
				{
					$contador=0;
					$buffer.="<thead><tr><td class='tdceldaamarillot'>Meta Ac</td>";
					foreach($array_metas_actividades[$cl] as $mk => $mv)
					{
						$contador++;
						$meta_anual_actividades = $meta_anual_actividades + $mv;
						$meta_anual_actividades_parcial= $meta_anual_actividades_parcial  + $mv;
						$buffer.="<td align='center' class='tdceldaamarillo'>".$mv."</td>";
						if($contador  == 3)
						{
							$buffer.="<td align='center' class='tdceldaamarillo'>".$meta_anual_actividades_parcial."</td>";
							$contador=0;
							$meta_anual_actividades_parcial=0;
						}
					}
					$buffer.="<td class='tdceldaamarillo'>".$meta_anual_actividades."</td></tr></thead>";
				}
				$meta_anual_asistentes=0;
				$meta_anual_asistentes_parcial=0;
				if(count($array_metas_asistentes) > 0)
				{
					$contador=0;
					$buffer.="<thead><tr><td class='tdceldaamarillot'>Meta As</td>";
					foreach($array_metas_asistentes[$cl] as $mk => $mv)
					{
						$contador++;
						$meta_anual_asistentes = $meta_anual_asistentes + $mv;
						$meta_anual_asistentes_parcial=$meta_anual_asistentes_parcial + $mv;
						$buffer.="<td align='center' class='tdceldaamarillo'>".$mv."</td>";
						if($contador  == 3)
						{
							$buffer.="<td align='center' class='tdceldaamarillo'>".$meta_anual_asistentes_parcial."</td>";
							$contador=0;
							$meta_anual_asistentes_parcial=0;
						}
					}
					$buffer.="<td class='tdceldaamarillo'>".$meta_anual_asistentes."</td></tr></thead>";
				}

			   $contador=0;
				$suma=0;
				$valor=0;
				$suma_actividades=0;
				$suma_metas_actividades=0;
				$buffer.="<thead><tr><td class='tdmensual'>% Mensual Ac</td>";
				foreach($array_metas_actividades[$cl] as $mk => $mv)
				{
					if( $mv <= 0 )
					{
						$promedio=($array_totales_actividades[$mk] / 1)* 100;
					}
					else
					{
						$promedio=($array_totales_actividades[$mk] / $mv)* 100;
					}
					$suma_actividades=$suma_actividades+$array_totales_actividades[$mk];
					$suma_metas_actividades=$suma_metas_actividades + $mv;
					$contador++;
					$buffer.="<td align='center' class='tdmensual'>".number_format($promedio,1,'.','')."</td>";
					if($contador  == 3)
					{
						if($suma_metas_actividades<=0)
							$valor=($suma_actividades/ 1)*100;
						else
							$valor=($suma_actividades/ $suma_metas_actividades)*100;
						$buffer.="<td align='center' class='tdmensual'>".number_format($valor,1,'.','')."</td>";
						$suma=$suma + $valor;
						$contador=0;
						$valor=0;
						$suma_actividades=0;
						$suma_metas_actividades=0;
					}
				}

				if($meta_anual_actividades == 0)
					$total_avance_mensual_actividades=($temp_total_actividades / 1)*100;
				else
					$total_avance_mensual_actividades=($temp_total_actividades / $meta_anual_actividades)*100;
				$buffer.="<td class='tdmensual'>".number_format($total_avance_mensual_actividades,1,'.','')."</td></tr></thead>";
				$contador=0;
				$suma=0;
				$suma_asistentes=0;
				$suma_metas_asistentes=0;
				$buffer.="<thead><tr><td class='tdmensual'>% Mensual As</td>";
				foreach($array_metas_asistentes[$cl] as $mk => $mv)
				{
					if( $mv <= 0)
					{
						$promedio=($array_totales_asistentes[$mk] / 1)* 100;
					}
					else
					{
						$promedio=($array_totales_asistentes[$mk] / $mv)* 100;
					}
					$suma_asistentes=$suma_asistentes+$array_totales_asistentes[$mk];
					$suma_metas_asistentes=$suma_metas_asistentes + $mv;
					$contador++;
					$buffer.="<td align='center' class='tdmensual'>".number_format($promedio,1,'.','')."</td>";
					if($contador  == 3)
					{
						if($suma_metas_asistentes<=0)
							$valor= ($suma_asistentes/ 1)*100;
						else
							$valor= ($suma_asistentes / $suma_metas_asistentes)*100;
						$buffer.="<td align='center' class='tdmensual'>".number_format($valor,1,'.','')."</td>";
						$suma=$suma + $valor;
						$valor=0;
						$suma_asistentes=0;
						$suma_metas_asistentes=0;
						$contador=0;
					}
				}

				if( $meta_anual_asistentes == 0)
					$total_avance_mensual_asistentes=($temp_total_asistentes / 1) *100;
				else
					$total_avance_mensual_asistentes=($temp_total_asistentes / $meta_anual_asistentes) *100;

				$buffer.="<td class='tdmensual'>".number_format($total_avance_mensual_asistentes,1,'.','')."</td></tr></thead>";
				$buffer.="<thead><tr><td class='tdrojoceldat'>% Anual Ac</td>";
				$promedio=0;
				$contador=0;
				$suma=0;
				$valor=0;
				foreach($array_metas_actividades[$cl] as $mk => $mv)
				{
					if( $meta_anual_actividades <= 0 )
						$promedio=($array_totales_actividades[$mk] / 1)* 100;
					else
						$promedio=($array_totales_actividades[$mk] / $meta_anual_actividades)* 100;

					$valor=$valor + $promedio;
					$contador++;
					$buffer.="<td align='center' class='tdrojocelda'>".number_format($promedio,1,'.',',')."</td>";
					if($contador  == 3)
					{
						$buffer.="<td align='center' class='tdrojocelda'>".number_format($valor,1,'.','')."</td>";
						$suma=$suma + $valor;
						$valor=0;
						$contador=0;
					}
				}
				$buffer.="<td class='tdrojocelda'>".number_format($suma,1,'.','')."</td></tr></thead>";
				$buffer.="<thead><tr><td class='tdrojoceldat'>% Anual Ac</td>";
				$promedio=0;
				$contador=0;
				$suma=0;
				$valor=0;
				foreach($array_metas_asistentes[$cl] as $mk => $mv)
				{
					if($meta_anual_asistentes <= 0)
						$promedio=($array_totales_asistentes[$mk] / 1)* 100;
					else
						$promedio=($array_totales_asistentes[$mk] / $meta_anual_asistentes)* 100;

					$valor=$valor + $promedio;
					$contador++;
					$buffer.="<td align='center' class='tdrojocelda'>".number_format($promedio,1,'.','')."</td>";
					if($contador  == 3)
					{
						$buffer.="<td align='center' class='tdrojocelda'>".number_format($valor,1,'.','')."</td>";
						$suma=$suma + $valor;
						$valor=0;
						$contador=0;
					}
				}
				$buffer.="<td class='tdrojocelda'>".number_format($suma,1,'.','')."</td>";
				$buffer.="</tr></tbody></table></p></div>";
	   }
		$buffer.="</div>";
		return $buffer;
	}


	function Consulta_Estadistica($area_id,$nombre_area,$array_programas,$array_mes,$data)
	{
		$array_subprogramas=array();
		$estatus='';
		$mod='';
		if(count($data['estatus'])> 0 )
		{
			foreach($data['estatus'] as $val)
			{
				$estatus.="'".$val."',";
				$mod.=$val."|";
			}
		}

		$estatus=substr($estatus,0,(strlen($estatus)-1));
		$mod=substr($mod,0,(strlen($mod)-1));
		$array_teventos=$this->regresa_teventos();
		$array_talleres=$this->regresa_talleres();
		$buffer='<div align="justify" class="basic" style="float:left; font-size:7px; margin-left: 1em; margin-rigth: 1em; width:98%;" id="list1b">';
		$buffer.='<br><span class="tit">&Aacute;rea:</span>&nbsp;<b>'.$nombre_area.'</b>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="tit">A&ntilde;o seleccionado:</span>&nbsp;<b>'.$data['anoele']."</b>";
		$array_totales=array();
		foreach($array_programas as $cl => $vl)
		{
			$boton_sub="";
			if($vl== 'Transversales')
			$vl= 'Transversales   (Equidad de G&eacute;nero)';
			if( ($cl!=1) && ($cl!=2) && ($cl!=3)  && ($cl!=61) && ($cl!=62) && ($cl!=63) && ($cl!=64) && ($cl!=65) )
			{
				$array_subprogramas=$this->Regresa_Subprogramas($area_id,$cl);
				if(count($array_subprogramas) > 0)
				{
					$boton_sub="<input type='button' name='b_subp' id='b_subp' value='Ver Subprogramas de ".$vl."' 
								onclick=\"lanza_subprogramas('".$area_id."','".$cl."','".$data['anoele']."','".$mod."');\" 
								class=\"ventana_subprogramas\">";
				}
				$array_totales_actividades=$this->inicializa_arreglo();
				$array_totales_asistentes=$this->inicializa_arreglo();
				$array_totales_actividades_talleres=$this->inicializa_arreglo();
				$array_totales_asistentes_talleres=$this->inicializa_arreglo();
				$buffer.="<a>".$vl."</a>";
				$array_metas_actividades=$this->regresa_metas($data['inv_area'],$cl,$data['anoele'],'Actividades');
				$array_metas_asistentes=$this->regresa_metas($data['inv_area'],$cl,$data['anoele'],'Asistentes');
				if( $cl != 32)
				{
					$array_actividades=$this->regresa_actividades($data['inv_area'],$cl,$data['anoele'],$estatus);
					$array_asistentes=$this->regresa_asistentes($data['inv_area'],$cl,$data['anoele'],$estatus);
				}
				if($cl == 32)
				{
				$array_actividades=$this->regresa_actividades_cooproduccion($data['inv_area'],$cl,$data['anoele'],'',0);
				$array_asistentes =$this->regresa_asistentes_cooproduccion($data['inv_area'],$cl,$data['anoele'],'',0);
				
				$array_actividades1=$this->regresa_actividades_cooproduccion($data['inv_area'],$cl,$data['anoele'],'',1);
				$array_asistentes1=$this->regresa_asistentes_cooproduccion($data['inv_area'],$cl,$data['anoele'],'',1);
				
				$array_actividades2=$this->regresa_actividades_cooproduccion($data['inv_area'],$cl,$data['anoele'],'',2);
				$array_asistentes2=$this->regresa_asistentes_cooproduccion($data['inv_area'],$cl,$data['anoele'],'',2);
				
				$array_actividades1=$this->normaliza_a_meses($array_actividades1);
				$array_asistentes1 =$this->normaliza_a_meses($array_asistentes1);
				$array_actividades2=$this->normaliza_a_meses($array_actividades2);
				$array_asistentes2 =$this->normaliza_a_meses($array_asistentes2);
				}
				$array_actividades=$this->normaliza_a_meses($array_actividades);
				$array_asistentes=$this->normaliza_a_meses($array_asistentes);

				$array_actividades_talleres=$this->regresa_actividades_talleres($data['inv_area'],$cl,$data['anoele'],'');
				$array_asistentes_talleres=$this->regresa_asistentes_talleres($data['inv_area'],$cl,$data['anoele'],'');
				$array_actividades_talleres=$this->normaliza_a_meses($array_actividades_talleres);
				$array_asistentes_talleres=$this->normaliza_a_meses($array_asistentes_talleres);
				$buffer.="
				<div align='center'>
					<input type='button' value='Visualizar fichas de programa' name='fichas' onclick=\"ventana_fichas('".$cl."','".$data['inv_area']."','".$data['anoele']."','".$mod."');\"  class=\"ventana_fichas\">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$boton_sub."</p>
					<table width='100%' class='tablesorter' border='0'>";
				$buffer.="<thead><tr><th width='15%'>Actividad</th>";
				$contador=0;
				$cont_t=1;
				for ($n_mes=1; $n_mes<=12; $n_mes++)
				{
					$contador++;
					$buffer.="<th width='5%' align='center'>".$array_mes[$n_mes]."</th>";
					if($contador==3)
					{
						$buffer.="<th width='5%' align='center'>
							<table><tr><th>T".$cont_t."</th><th>A".$cont_t."</th></tr></table>";
						$contador=0;
						$cont_t++;
					}
				}
				$buffer.="<th width='5%' align='center'>Anual</th></tr></thead><tbody>";
				foreach($array_actividades as $key_ind => $value_ind )	
				{
					$nom=$array_teventos[($key_ind)+0];
					$buffer.="<tr height='30px'><th>
					  <table border='0' width='100%' align='center'>
						<tr>
							<th rowspan='2' class='tevento' width='90%'>".ucfirst(strtolower($nom))."</th>
							<th class='letrero_act_asi' width='10%'>Act</th>
						</tr>
						<tr>
							<th class='letrero_act_asi' width='10%'>Asi</th>
						</tr>
						</table></th>";
					$contador=0;
					$suma_act=0;
					$suma_asi=0;
					$suma_act_t=0;
					$suma_asi_t=0;
					foreach($value_ind as $k => $v)
					{
						$array_totales_actividades[$k]=($array_totales_actividades[$k] + $v);
						$array_totales_asistentes[$k]=($array_totales_asistentes[$k] + $array_asistentes[$key_ind][$k]);
						$suma_act = $suma_act + $v;
						$suma_asi = $suma_asi + $array_asistentes[$key_ind][$k];
						$suma_act_t = $suma_act_t + $v;
						$suma_asi_t = $suma_asi_t + $array_asistentes[$key_ind][$k];
						if($cl != 32)
						{
							$buffer.="<th>
							<table border='0' width='100%' align='center'>
							<tr><th align='center'>
								<input type='button' onclick=\"ventana_actividades('".$cl."','".$key_ind."','".$k."','".$v."','".$data['inv_area']."','".$data['anoele']."','".$mod."');\" class='number_link' value='".(0 + $v)."'></th></tr>
							<tr><th class='letra_chica'>".$array_asistentes[$key_ind][$k]."</th></tr>
							</table></th>";
						}
						if($cl == 32)
						{
							$buffer.="<th>
							<table border='0' width='100%' align='center'>
							<tr><th align='center'><input type='button' onclick=\"ventana_actividades_coproduccion('".$cl."','".$key_ind."','".$k."','".$v."','".$data['inv_area']."','".$data['anoele']."','".$mod."');\" class='number_link' value='".(0 + $v)."'></th></tr>
							<tr><th class='letra_chica'>".$array_asistentes[$key_ind][$k]."</th></tr>
							</table></th>";
						}
						$contador++;
						if($contador  == 3)
						{
							$buffer.="<th align='center'>
								<table border='0' width='100%' align='center'>
								<tr>
								<th align='center' class='suma_act_t'>
								".($suma_act_t + 0)."
								</th>
								<th align='center' class='suma_act_t'>
								".($suma_act + 0)."
								</th>
								</tr>
								<tr>
								<th align='center' class='suma_act_t'>
								".($suma_asi_t + 0)."
								</th>
								<th align='center' class='suma_act_t'>
								".($suma_asi + 0)."
								</th>
								</tr>
								</table></th>";
							$contador=0;
							$suma_act_t=0;
							$suma_asi_t=0;
						}
					}
					$buffer.="</tr>";
				}
				if(count($array_totales_actividades) > 0)
				{
					$temp_total_actividades=0;
					$contador=0;
					$suma=0;
					$suma_actividades=0;
					$buffer.="<thead><tr><td class='tevento'>Tot Ac</th>";
					$max_trimestre=0;
					foreach($array_totales_actividades as $mk => $mv)
					{
						$contador++;
						$suma=$suma + $mv;
						$suma_actividades=$suma_actividades+$mv;
						$buffer.="<td class='letra_chica_t'>".$mv."</td>";
						if($contador  == 3)
						{
							$backg="";
							if($area_id == 5)
							{
								if($max_trimestre > $suma )
									$backg=" style='background-color:#ff0000;' ";
							}
							$buffer.="<td align='center' class='letra_chica_t' ".$backg.">".$suma."</td>";
							$max_trimestre=$suma;
							$contador=0;
							$suma=0;
						}
					}
					$temp_total_actividades=$suma;
					$buffer.="</tr></thead>";
					if($cl == 32)
					{
						$contador_1=0;
						$buffer.="<thead><tr><td class='tevento'>Tot Ac Pr</th>";
						$max_trimestre1=0;
						$suma_1=0;
						$suma_actividades_1=0;
						foreach($array_actividades1 as $tmnom => $array)
						{
							foreach($array as $mk => $mv)
							{
								$contador_1++;
								$suma_1=$suma_1 + $mv + 0;
								$suma_actividades_1=$suma_actividades_1 + $mv + 0;
								$buffer.="<td  align='right'>".$mv."</td>";
								if($contador_1  == 3)
								{
									$buffer.="<td align='right'>".$suma_1."</td>";
									$contador_1=0;
									$suma_1=0;
								}
							}
						}
						$contador_2=0;
						$buffer.="<thead><tr><td class='tevento'>Tot Ac Pa</th>";
						$suma_2=0;
						$suma_actividades2=0;
						foreach($array_actividades2 as $tmnom => $array)
						{
							foreach($array as $mk => $mv)
							{
								$contador_2++;
								$suma_2=$suma_2 + $mv + 0;
								$suma_actividades_2=$suma_actividades_2 + $mv + 0;
								$buffer.="<td align='right'>".$mv."</td>";
								if($contador_2  == 3)
								{
									$buffer.="<td align='right'>".$suma_2."</td>";
									$contador_2=0;
									$suma_2=0;
								}
							}
						}
					}
				}
				if(count($array_totales_asistentes) > 0)
				{
					$temp_total_asistentes=0;
					$contador=0;
					$suma=0;
					$suma_asistentes=0;
					$buffer.="<thead><tr><td class='tevento'>Tot As</td>";
					foreach($array_totales_asistentes as $mk => $mv)
					{
						$contador++;
						$suma_asistentes=$suma_asistentes + $mv;
						$suma=$suma + $mv;
						$buffer.="<td class='letra_chica_t'>".$mv."</td>";
						if($contador  == 3)
						{
							$buffer.="<td align='center' class='letra_chica_t'>".$suma."</td>";
							$contador=0;
							$suma=0;
						}
					}
					$temp_total_asistentes=$suma;
					$buffer.="</tr></thead>";
					if($cl == 32)
					{
						$contador_1=0;
						$buffer.="<thead><tr><td class='tevento'>Tot As Pr</th>";
						$max_trimestre1=0;
						$suma_1=0;
						$suma_actividades1=0;
						foreach($array_asistentes1 as $tmnom => $array)
						{
							foreach($array as $mk => $mv)
							{
								$contador_1++;
								$suma_1=$suma_1 + $mv + 0;
								$suma_actividades_1=$suma_actividades_1 + $mv + 0;
								$buffer.="<td align='right'>".$mv."</td>";
								if($contador_1  == 3)
								{
									$buffer.="<td align='right'>".$suma_1."</td>";
									$contador_1=0;
									$suma_1=0;
								}
							}
						}
						$contador_2=0;
						$buffer.="<thead><tr><td class='tevento'>Tot As Pa</th>";
						$suma_2=0;
						$suma_actividades2=0;
						foreach($array_asistentes2 as $tmnom => $array)
						{
							$mv=0;
							foreach($array as $mk => $mv)
							{
								$contador_2++;
								$suma_2=$suma_2 + $mv + 0;
								$suma_actividades_2=$suma_actividades_2 + $mv + 0;
								$buffer.="<td align='right'>".$mv."</td>";
								if($contador_2  == 3)
								{
									$buffer.="<td align='right'>".$suma_2."</td>";
									$contador_2=0;
									$suma_2=0;
								}
							}
						}
					}
				}
				$meta_anual_actividades=0;
				$meta_anual_actividades_parcial=0;
				if(count($array_metas_actividades) > 0)
				{
					$contador=0;
					$buffer.="<thead><tr><td class='tevento'>Meta Ac</td>";
					foreach($array_metas_actividades[$cl] as $mk => $mv)
					{
                                            
                                            $fondo=" class='tdmetas' ";
                                            if($mv > $array_totales_actividades[$mk])
                                            {
                                                $fondo=" style='background: #ff0000;font-weight: bold;font-family: Arial, Helvetica, sans-serif;font-size: 9px;color: #ffffff;text-align: right;' ";
                                                
                                            }
						$contador++;
						$meta_anual_actividades = $meta_anual_actividades + $mv + 0;
						$meta_anual_actividades_parcial= $meta_anual_actividades_parcial  + $mv + 0;
						$buffer.="<td ".$fondo.">".($mv + 0)."</td>";
						if($contador  == 3)
						{
							$buffer.="<td class='tdmetas'>".$meta_anual_actividades_parcial."</td>";
							$contador=0;
							$meta_anual_actividades_parcial=0;
						}
					}
					$buffer.="<td class='tdmetas'>".$meta_anual_actividades."</td></tr></thead>";
				}
				$meta_anual_asistentes=0;
				$meta_anual_asistentes_parcial=0;
				if(count($array_metas_asistentes) > 0)
				{
					$contador=0;
					$buffer.="<thead><tr><td class='tevento'>Meta As</td>";
					foreach($array_metas_asistentes[$cl] as $mk => $mv)
					{
                                            $fondo=" class='tdmetas' ";
                                            if($mv > $array_totales_asistentes[$mk])
                                            {
                                                $fondo=" style='background: #ff0000;font-weight: bold;font-family: Arial, Helvetica, sans-serif;font-size: 9px;color: #ffffff;text-align: right;' ";
                                                
                                            }                                           
						$contador++;
						$meta_anual_asistentes = $meta_anual_asistentes + $mv;
						$meta_anual_asistentes_parcial=$meta_anual_asistentes_parcial + $mv;
						$buffer.="<td ".$fondo.">".$mv."</td>";
						if($contador  == 3)
						{
							$buffer.="<td align='center' class='tdmetas'>".$meta_anual_asistentes_parcial."</td>";
							$contador=0;
							$meta_anual_asistentes_parcial=0;
						}
					}
					$buffer.="<td class='tdmetas'>".$meta_anual_asistentes."</td></tr></thead>";
				}
				$contador=0;
				$suma=0;
				$valor=0;
				$suma_actividades=0;
				$suma_metas_actividades=0;
				$buffer.="<thead><tr><td class='tevento'>% Mensual Ac</td>";
				foreach($array_metas_actividades[$cl] as $mk => $mv)
				{
				   if( $mv <= 0 ) 
				   {
						$promedio=($array_totales_actividades[$mk] / 1)* 100;
					}
					else
					{
						$promedio=($array_totales_actividades[$mk] / $mv)* 100;
					}
					$suma_actividades=$suma_actividades+$array_totales_actividades[$mk];
					$suma_metas_actividades=$suma_metas_actividades + $mv;
					$contador++;
					$tmp_1=number_format($promedio,1,'.','');
					if($array_metas_actividades[$cl][$mk] < 1)
						$tmp_1=' - ';
					$buffer.="<td align='center' class='tdmensual'>".$tmp_1."</td>";
					if($contador  == 3)
					{
						if($suma_metas_actividades<=0)
							$valor=($suma_actividades/ 1)*100;
						else
							$valor=($suma_actividades/ $suma_metas_actividades)*100;
						$tmp=number_format($valor,1,'.','');
						if($suma_metas_actividades==0)
							$tmp=' - ';
						$buffer.="<td align='center' class='tdmensual'>".$tmp."</td>";
						$suma=$suma + $valor;
						$contador=0;
						$valor=0;
						$suma_actividades=0;
						$suma_metas_actividades=0;
					}
				}
		if($meta_anual_actividades == 0)
					$total_avance_mensual_actividades=($temp_total_actividades / 1)*100;
		else
					$total_avance_mensual_actividades=($temp_total_actividades / $meta_anual_actividades)*100;
				$buffer.="<td class='tdmensual'>".number_format($total_avance_mensual_actividades,1,'.','')."</td></tr></thead>";
				$contador=0;
				$suma=0;
				$suma_asistentes=0;
				$suma_metas_asistentes=0;
				$buffer.="<thead><tr><td class='tevento'>% Mensual As</td>";
				foreach($array_metas_asistentes[$cl] as $mk => $mv)
				{
					if( $mv <= 0)
					{
						$promedio=($array_totales_asistentes[$mk] / 1)* 100;
					}
					else
					{
						$promedio=($array_totales_asistentes[$mk] / $mv)* 100;
					}
					$suma_asistentes=$suma_asistentes+$array_totales_asistentes[$mk];
					$suma_metas_asistentes=$suma_metas_asistentes + $mv;
					$contador++;
					$tmp_1=number_format($promedio,1,'.','');
					if($array_metas_asistentes[$cl][$mk] < 1)
						$tmp_1=' - ';
					$buffer.="<td align='center' class='tdmensual'>".$tmp_1."</td>";
					if($contador  == 3)
					{
						if($suma_metas_asistentes<=0)
							$valor= ($suma_asistentes/ 1)*100;
						else
							$valor= ($suma_asistentes / $suma_metas_asistentes)*100;
						$tmp=number_format($valor,1,'.','');
						if($suma_metas_asistentes==0)
							$tmp=' - ';
						$buffer.="<td align='center' class='tdmensual'>".$tmp."</td>";
						$suma=$suma + $valor;
						$valor=0;
						$suma_asistentes=0;
						$suma_metas_asistentes=0;
						$contador=0;
					}
				}

				if( $meta_anual_asistentes == 0)
					$total_avance_mensual_asistentes=($temp_total_asistentes / 1) *100;
				else
					$total_avance_mensual_asistentes=($temp_total_asistentes / $meta_anual_asistentes) *100;
				$buffer.="<td class='tdmensual'>".number_format($total_avance_mensual_asistentes,1,'.','')."</td></tr></thead>";
				$buffer.="<thead><tr><td class='tevento'>% Anual Ac</td>";
				$promedio=0;
				$contador=0;
				$suma=0;
				$valor=0;
				foreach($array_metas_actividades[$cl] as $mk => $mv)
				{
					if( $meta_anual_actividades <= 0 )
						$promedio=($array_totales_actividades[$mk] / 1)* 100;
					else
						$promedio=($array_totales_actividades[$mk] / $meta_anual_actividades)* 100;

					$valor=$valor + $promedio;
					$contador++;
					$tmp_1=number_format($promedio,1,'.','');
					if($array_metas_actividades[$cl][$mk] < 1)
						$tmp_1=' - ';
					$buffer.="<td align='center' class='tdanual'>".$tmp_1."</td>";
					if($contador  == 3)
					{
						$tmp=number_format($valor,1,'.','');
						if($suma_metas_actividades==0)
							$tmp=' - ';
						$buffer.="<td align='center' class='tdanual'>".$tmp."</td>";
						$suma=$suma + $valor;
						$valor=0;
						$contador=0;
					}
				}
				$buffer.="<td class='tdanual'>".number_format($suma,1,'.','')."</td></tr></thead>";
				$buffer.="<thead><tr><td class='tevento'>% Anual As</td>";
				$promedio=0;
				$contador=0;
				$suma=0;
				$valor=0;
				foreach($array_metas_asistentes[$cl] as $mk => $mv)
				{
					if($meta_anual_asistentes <= 0)
						$promedio=($array_totales_asistentes[$mk] / 1)* 100;
					else
						$promedio=($array_totales_asistentes[$mk] / $meta_anual_asistentes)* 100;
					$valor=$valor + $promedio;
					$contador++;
					$tmp_1=number_format($promedio,1,'.','');
					if($array_metas_asistentes[$cl][$mk] < 1)
						$tmp_1=' - ';
					$buffer.="<td align='center' class='tdanual'>".$tmp_1."</td>";
					if($contador  == 3)
					{
						$tmp=number_format($valor,1,'.','');
						if($suma_metas_asistentes==0)
							$tmp=' - ';
						$buffer.="<td align='center' class='tdanual'>".$tmp."</td>";
						$suma=$suma + $valor;
						$valor=0;
						$contador=0;
					}
				}
				$buffer.="<td class='tdanual'>".number_format($suma,1,'.','')."</td>";
				$buffer.="</tr>
				<tr>
				<td colspan='18' class='tevento'>TALLERES</td>
				</tr>";
			$buffer.="</thead><tbody>";
			foreach($array_actividades_talleres as $key_ind => $value_ind )
			{
				$buffer.="<tr height='30px'><th>
							<table border='0' width='100%' align='center'>
								<tr>
									<th rowspan='2' align='left' style='font-size:9px;'>".$array_talleres[($key_ind)+0]."</th>
									<th align='right'><font color='#aa2b00' size='1'>Act</font></th>
								</tr>
								<tr>
									<th align='right'><font color='#aa2b00' size='1'>Asi</font></th>
								</tr>
							</table></th>";
				$contador=0;
				$suma_act=0;
				$suma_asi=0;
				$suma_act_t=0;
				$suma_asi_t=0;
				foreach($value_ind as $k => $v)
				{
					$array_totales_actividades_talleres[$k]=($array_totales_actividades_talleres[$k] + $v);
					$array_totales_asistentes_talleres[$k] =($array_totales_asistentes_talleres [$k] + $array_asistentes_talleres[$key_ind][$k]);
					$suma_act = $suma_act + $v;
					$suma_asi = $suma_asi + $array_asistentes_talleres[$key_ind][$k];
					$suma_act_t = $suma_act_t + $v;
					$suma_asi_t = $suma_asi_t + $array_asistentes_talleres[$key_ind][$k];
					$buffer.="<th>
								<table border='0' width='100%' align='center'>
								<tr><th align='center'><input type='button' onclick=\"ventana_actividades_talleres('".$cl."','".$key_ind."','".$k."','".$v."','".$data['inv_area']."','".$data['anoele']."','".$data['estatus']."');\" style='border:0px;color:#333333;font-size:9px;text-decoration:underline;' value='".(0 + $v)."'></th></tr>
								<tr><th align='center' style='font-size:9px;'>".$array_asistentes_talleres[$key_ind][$k]."</th></tr>
								</table>
							 </th>";
					$contador++;
					if($contador  == 3)
					{
						$buffer.="<th align='center'>
								   <table border='0' width='100%' align='center'>
									<tr>
										<th align='center' style='background-color:#fff;font-size:9px;border: 1px solid #3e4f88;'>".$suma_act_t."</th>
										<th align='center' style='background-color:#fff;font-size:9px;border: 1px solid #3e4f88;'>".$suma_act."</th>
									</tr>
									<tr>
										<th align='center' style='background-color:#fff;font-size:9px;border: 1px solid #3e4f88;'>".$suma_asi_t."</th>
										<th align='center' style='background-color:#fff;font-size:9px;border: 1px solid #3e4f88;'>".$suma_asi."</th>
									</tr>
									</table>
								  </th>";
						$contador=0;
						$suma_act_t=0;
						$suma_asi_t=0;
					}
				}
				$buffer.="</tr>";
			}

			if(count($array_totales_actividades_talleres) > 0)
			{
				$contador=0;
				$suma=0;
				$buffer.="<thead><tr><td style='color:#3e4f88;font-size:9px;'>Tot Ac</th>";
				foreach($array_totales_actividades_talleres as $mk => $mv)
				{
					$contador++;
					$suma=$suma + $mv;
					$buffer.="<td align='center' style='font-size:9px;'>".$mv."</td>";
					if($contador  == 3)
					{
						$buffer.="<td align='center' class='tdceldatri'>".$suma."</td>";
						$contador=0;
					}
				}
				$buffer.="</tr></thead>";
			}
			if(count($array_totales_asistentes_talleres) > 0)
			{
				$contador=0;
				$suma=0;
				$buffer.="<thead><tr><td style='color:#3e4f88;font-size:9px;'>Tot As</td>";
				foreach($array_totales_asistentes_talleres as $mk => $mv)
				{
					$contador++;
					$suma=$suma + $mv;
					$buffer.="<td align='center' style='font-size:9px;'>".$mv."</td>";
					if($contador  == 3)
					{
						$buffer.="<td align='center' class='tdceldatri'>".$suma."</td>";
						$contador=0;
					}
				}
				$buffer.="</tr></thead>";
			}
			$buffer.="</tbody></table></p></div>";
			$div="<div>";
			}
		}

		$buffer.="</div>";
		if($area_id == 1)
		{
			$buffer.="<br><br><hr><br>
					<table width='100%' align='center'>
					  <tr>
						<th><input type='radio' name='radiotransversales' id='radiotransversales' value='1' onClick=\"lanza_listado('".$data['anoele']."','1');\">Equidad de g&eacute;nero</th>
						<th><input type='radio' name='radiotransversales' id='radiotransversales' value='2' onClick=\"lanza_listado('".$data['anoele']."','2');\">Adultos mayores</th>
						<th><input type='radio' name='radiotransversales' id='radiotransversales' value='3' onClick=\"lanza_listado('".$data['anoele']."','3');\">Diversidad sexual</th>
						<th><input type='radio' name='radiotransversales' id='radiotransversales' value='4' onClick=\"lanza_listado('".$data['anoele']."','4');\">Recuperación de espacios p&uacute;blicos</th>
						<th><input type='radio' name='radiotransversales' id='radiotransversales' value='5' onClick=\"lanza_listado('".$data['anoele']."','5');\">Derechos humanos</th>
						</tr>
						<tr>
						<th><input type='radio' name='radiotransversales' id='radiotransversales' value='6' onClick=\"lanza_listado('".$data['anoele']."','6');\">Grupos ind&iacute;genas</th>
						<th><input type='radio' name='radiotransversales' id='radiotransversales' value='7' onClick=\"lanza_listado('".$data['anoele']."','7');\">Discapacidad</th>
						<th><input type='radio' name='radiotransversales' id='radiotransversales' value='8' onClick=\"lanza_listado('".$data['anoele']."','8');\">Ni&ntilde;ez</th>
						<th><input type='radio' name='radiotransversales' id='radiotransversales' value='9' onClick=\"lanza_listado('".$data['anoele']."','9');\">J&oacute;venes</th>
						<th><input type='radio' name='radiotransversales' id='radiotransversales' value='10' onClick=\"lanza_listado('".$data['anoele']."','10');\">Multiculturalidad</th>
					  </tr>
					  </table>";
		}
		if($area_id == 9)
		{
			$buffer.="<br><br><hr><br><br><br>
					<table width='100%' align='center' border='0'>
					  <tr>
						<th>Estadisticas de Teatros</th>
					  </tr>
					</table>";
		}
		$buffer.="<br><br><hr><br><br>
				<p align='justify'>
					Act.  Actividades<br>
					Asi.  Asistentes<br><br><br>
					T1.   Trimestre 1<br>
					T2.   Trimestre 2<br>
					T3.   Trimestre 3<br>
					T4.   Trimestre 4<br><br><br>
					A1.   Acumulado 1<br>
					A2.   Acumulado 2<br>
					A3.   Acumulado 3<br>
					A4.   Acumulado 4<br>
				</p><br>";
		return $buffer;
	}

	function regresa_actividades_talleres_subprogramas($area_id,$programa_id,$subprograma_id,$ano,$estatus)
	{
		$array_actividades=array();
		$estatus_filtro='';
		if($estatus!='')
			$estatus_filtro= "AND status='".$estatus."'";

		$campo =" substr( fecha_inicio, 1, 7 )";
		$filtro_ano= " substr( fecha_inicio, 1, 4 ) = '".$ano."' ";
		$sql="SELECT taller_id,".$campo." as meses, count(".$campo." ) AS total
			  FROM proyectos_talleres
			  WHERE area_id =".$area_id." AND programa_id = ".$programa_id." AND subprograma=".$subprograma_id." AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY taller_id,".$campo." ORDER BY taller_id,".$campo." ASC;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while(list($taller_id,$meses,$total) = $this->db->sql_fetchrow($res))
			{
				$tmp_taller_id=$taller_id;
				$mes_id=substr($meses,5,2) + 0;
				$array_actividades[$tmp_taller_id][$mes_id]=$total;
			}
		}
		return $array_actividades;
	}

	function regresa_asistentes_cooproduccion($area,$programa,$ano,$estatus,$tipo_coo)
	{
		switch($tipo_coo)
		{
			case 0:
				$campo_coo='proy_poblacion_asis';
				break;
			case 1:
				$campo_coo='proy_asis_1';
				break;
			case 2:
				$campo_coo='proy_asis_2';
			break;
		}
		$array_asistentes=array();
		$estatus_filtro='';
		if($estatus!='')
			$estatus_filtro= "AND proy_status IN (".$estatus.")";
		$campo =" substr( proy_fecha_inicio, 1, 7 )";
		$filtro_ano= " substr( proy_fecha_inicio, 1, 4 ) = '".$ano."' ";
		$sql="SELECT 'Coproducción de Festivales' as proy_tevento,".$campo." as meses, sum(".$campo_coo.") AS total FROM proyectos_festivales
			  WHERE area_id =".$area." AND programa_id = ".$programa." AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY proy_tevento,".$campo." ORDER BY proy_tevento,".$campo." ASC;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$tmp_proy_evento=$fila['proy_tevento'];
				$mes_id=substr($fila['meses'],5,2) + 0;
				$array_asistentes[$tmp_proy_evento][$mes_id]=$fila['total'];
			}
		}
		return $array_asistentes;
	}

	function regresa_actividades_cooproduccion($area,$programa,$ano,$estatus,$tipo_coo)
	{
		switch($tipo_coo)
		{
			case 0:
				$campo_coo='proy_poblacion_act';
				break;
			case 1:
				$campo_coo='proy_act_1';
				break;
			case 2:
			$campo_coo='proy_act_2';
			break;
		}
		$estatus_filtro='';
		if($estatus!='')
		$estatus_filtro= "AND proy_status in (".$estatus.")";
			$campo =" substr( proy_fecha_inicio, 1, 7 )";
		$filtro_ano= " substr( proy_fecha_inicio, 1, 4 ) = '".$ano."' ";
		$sql="SELECT 'Coproducción de Festivales' as proy_tevento,".$campo." as meses, count(".$campo." ) AS total,sum(".$campo_coo.") as frecuencia
			  FROM proyectos_festivales
			  WHERE area_id =".$area." AND programa_id = ".$programa." AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY proy_tevento,".$campo." ORDER BY proy_tevento,".$campo." ASC;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while($fila = mysql_fetch_assoc($res))
			{
				$tmp_proy_evento=$fila['proy_tevento'];
				$mes_id=substr($fila['meses'],5,2) + 0;
				$array_actividades[$tmp_proy_evento][$mes_id]=$fila['frecuencia'];
			}
		}
		return $array_actividades;
	}

	
	function regresa_actividades_talleres($area,$programa,$ano,$estatus)
	{
		$array_actividades=array();
		$estatus_filtro='';
		if($estatus!='')
			$estatus_filtro= "AND status='".$estatus."'";
		$campo =" substr( fecha_inicio, 1, 7 )";
		$filtro_ano= " substr( fecha_inicio, 1, 4 ) = '".$ano."' ";
		$sql="SELECT taller_id,".$campo." as meses, count(".$campo." ) AS total
			  FROM proyectos_talleres
			  WHERE area_id =".$area." AND programa_id = ".$programa." AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY taller_id,".$campo." ORDER BY taller_id,".$campo." ASC;";

		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while(list($taller_id,$meses,$total) = $this->db->sql_fetchrow($res))
			{
				$tmp_taller_id=$taller_id;
				$mes_id=substr($meses,5,2) + 0;
				$array_actividades[$tmp_taller_id][$mes_id]=$total;
			}
		}
		return $array_actividades;
	}

	function regresa_asistentes_talleres_subprogramas($area_id,$programa_id,$subprograma_id,$ano,$estatus)
	{
		$array_asistentes=array();
		$estatus_filtro='';
		if($estatus!='')
			$estatus_filtro= "AND status='".$estatus."'";

		$campo =" substr( fecha_alta, 1, 7 )";
		$filtro_ano= " substr( fecha_alta, 1, 4 ) = '".$ano."' ";
		$sql="SELECT taller_id,".$campo." as meses,  (sum( total ) + sum( atotal ) + sum( btotal ) + sum( ctotal )) AS totales
			  FROM proyectos_asistentes_talleres
			  WHERE area_id =".$area." AND programa_id = ".$programa." AND subprograma=".$subprograma_id." AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY taller_id,".$campo." ORDER BY taller_id,".$campo." ASC;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while(list($taller_id,$meses,$totales) = $this->db->sql_fetchrow($res))
			{
				$tmp_taller_id=$taller_id;
				$mes_id=substr($meses,5,2) + 0;
				$array_asistentes[$tmp_taller_id][$mes_id]=$totales;
			}
		}
		return $array_asistentes;
	}

	function regresa_asistentes_talleres($area,$programa,$ano,$estatus)
	{
		$array_asistentes=array();
		$estatus_filtro='';
		if($estatus!='')
			$estatus_filtro= "AND status='".$estatus."'";
		$campo =" substr( fecha_alta, 1, 7 )";
		$filtro_ano= " substr( fecha_alta, 1, 4 ) = '".$ano."' ";
		$sql="SELECT taller_id,".$campo." as meses,  dtotal AS totales
			  FROM proyectos_asistentes_talleres
			  WHERE area_id =".$area." AND programa_id = ".$programa." AND ".$filtro_ano." AND ".$campo." != '0000-00' ".$estatus_filtro."
			  GROUP BY taller_id,".$campo." ORDER BY taller_id,".$campo." ASC;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)> 0)
		{
			while(list($taller_id,$meses,$totales) = $this->db->sql_fetchrow($res))
			{
				$tmp_taller_id=$taller_id;
				$mes_id=substr($meses,5,2) + 0;
				$array_asistentes[$tmp_taller_id][$mes_id]=$totales;
			}
		}
		return $array_asistentes;
	}

   function regresa_listado_actividades_talleres($data)
	{
		$filtro_status='';
		if($data['status']!='')
		$filtro_status="and a.status='".$data['status']."'";

		$ano_id=$data['ano'];
		$mes=str_pad($data['mes'],2,"0",STR_PAD_LEFT);

		if($mes=='13')
		{
			$filtro= "and substr(a.fecha_inicio,1,4)='".$data['ano']."'";
		}
		else
		{
			$filtro= "and substr(a.fecha_inicio,1,7)='".$data['ano'].'-'.$mes."'";
		}

		$buffer='';
		$sql="SELECT a.folio_id,a.taller_id,a.tipo_taller_id,a.nombre,a.fecha_inicio,a.fecha_termina,a.area_id,a.programa_id,a.status,
			  b.pob_h_0_15, b.pob_h_16_18, b.pob_h_19_30, b.pob_h_31_64, b.pob_h_65, b.pob_m_0_15, b.pob_m_16_18, b.pob_m_19_30, b.pob_m_31_64, b.pob_m_65, b.total, 
			  b.apob_h_0_15, b.apob_h_16_18, b.apob_h_19_30, b.apob_h_31_64, b.apob_h_65, b.apob_m_0_15, b.apob_m_16_18, b.apob_m_19_30, b.apob_m_31_64, b.apob_m_65, b.atotal, 
			  b.bpob_h_0_15, b.bpob_h_16_18, b.bpob_h_19_30, b.bpob_h_31_64, b.bpob_h_65, b.bpob_m_0_15, b.bpob_m_16_18, b.bpob_m_19_30, b.bpob_m_31_64, b.bpob_m_65, b.btotal, 
			  b.cpob_h_0_15, b.cpob_h_16_18, b.cpob_h_19_30, b.cpob_h_31_64, b.cpob_h_65, b.cpob_m_0_15, b.cpob_m_16_18, b.cpob_m_19_30, b.cpob_m_31_64, b.cpob_m_65, b.ctotal
			 FROM proyectos_talleres a, proyectos_asistentes_talleres b WHERE a.folio_id=b.folio_id AND a.area_id=".$data['area']." and a.programa_id=".$data['pro']." ".$filtro." and a.taller_id = '".$data['ind']."' ".$filtro_status."  GROUP BY folio_id ORDER BY a.area_id,a.programa_id,a.taller_id;";
		$res=$this->db->sql_query($sql);
		$num=$this->db->sql_numrows($res);
		if($num > 0)
		{
			$conta=0;
			while(list($folio,$taller_id,$tipo_taller,$nombre,$fecha_inicio,$fecha_termina,$area_id,$programa_id,$status,
			$pob_h_0_15, $pob_h_16_18, $pob_h_19_30, $pob_h_31_64, $pob_h_65, $pob_m_0_15, $pob_m_16_18, $pob_m_19_30, $pob_m_31_64, $pob_m_65, $total,
			$apob_h_0_15, $apob_h_16_18, $apob_h_19_30, $apob_h_31_64, $apob_h_65, $apob_m_0_15, $apob_m_16_18, $apob_m_19_30, $apob_m_31_64, $apob_m_65, $atotal, 
			$bpob_h_0_15, $bpob_h_16_18, $bpob_h_19_30, $bpob_h_31_64, $bpob_h_65, $bpob_m_0_15, $bpob_m_16_18, $bpob_m_19_30, $bpob_m_31_64, $bpob_m_65, $btotal, 
			$cpob_h_0_15, $cpob_h_16_18, $cpob_h_19_30, $cpob_h_31_64, $cpob_h_65, $cpob_m_0_15, $cpob_m_16_18, $cpob_m_19_30, $cpob_m_31_64, $cpob_m_65, $ctotal)
			= $this->db->sql_fetchrow($res))
			{
				if($conta == 0)
				{
					$buffer.="<table width='100%' align='center' border='0'><tr><td>
					<td>
					&Aacute;rea:&nbsp;".$area_id."  ".$this->consulta('nombre','cat_areas','area_id',$area_id)."</td></tr></table>";
					$buffer.="<table width='100%' align='center' border='0' class='tablesorter'>
					<thead>
					<tr>
					<td>Folio</td>
					<td>Programa</td>
					<td>Nombre del Taller</td>
					<td>Fecha Inicio</td>
					<td>Fecha Termina</td>
					<td>Poblaci&oacute;n</td>
					<td>Estatus</td>
					<td>Consultar</td>
					</tr></thead><tbody>";
				}
				$dpob_h_0_15 =$this->Calcula_promedio($pob_h_0_15,$apob_h_0_15,$bpob_h_0_15,$cpob_h_0_15,$total,$atotal,$btotal,$ctotal);
				$dpob_h_16_18=$this->Calcula_promedio($pob_h_16_18,$apob_h_16_18,$bpob_h_16_18,$cpob_h_16_18,$total,$atotal,$btotal,$ctotal);
				$dpob_h_19_30=$this->Calcula_promedio($pob_h_19_30,$apob_h_19_30,$bpob_h_19_30,$cpob_h_19_30,$total,$atotal,$btotal,$ctotal);
				$dpob_h_31_64=$this->Calcula_promedio($pob_h_31_64,$apob_h_31_64,$bpob_h_31_64,$cpob_h_31_64,$total,$atotal,$btotal,$ctotal);
				$dpob_h_65   =$this->Calcula_promedio($pob_h_65,$apob_h_65,$bpob_h_65,$cpob_h_65,$total,$atotal,$btotal,$ctotal);
				$dtotal_h	= $dpob_h_0_15 + $dpob_h_16_18 + $dpob_h_19_30 + $dpob_h_31_64 +  $dpob_h_65 ;

				$dpob_m_0_15 =$this->Calcula_promedio($pob_m_0_15,$apob_m_0_15,$bpob_m_0_15,$cpob_m_0_15,$total,$atotal,$btotal,$ctotal);
				$dpob_m_16_18=$this->Calcula_promedio($pob_m_16_18,$apob_m_16_18,$bpob_m_16_18,$cpob_m_16_18,$total,$atotal,$btotal,$ctotal);
				$dpob_m_19_30=$this->Calcula_promedio($pob_m_19_30,$apob_m_19_30,$bpob_m_19_30,$cpob_m_19_30,$total,$atotal,$btotal,$ctotal);
				$dpob_m_31_64=$this->Calcula_promedio($pob_m_31_64,$apob_m_31_64,$bpob_m_31_64,$cpob_m_31_64,$total,$atotal,$btotal,$ctotal);
				$dpob_m_65   =$this->Calcula_promedio($pob_m_65,$apob_m_65,$bpob_m_65,$cpob_m_65,$total,$atotal,$btotal,$ctotal);
				$dtotal_m	= $dpob_m_0_15 + $dpob_m_16_18 + $dpob_m_19_30 + $dpob_m_31_64 +  $dpob_m_65;
				$dtotal	  =($dtotal_h + $dtotal_m);

				$buffer.="<tr>
					<th align='left'>".$folio."</th>
					<th align='left'>".$programa_id."  ".$this->consulta('nombre','cat_programas','programa_id',$programa_id)."</th>
					<th align='left'>".$nombre."</th>
					<th align='left'>".substr($fecha_inicio,0,10)."</th>
					<th align='left'>".substr($fecha_termina,0,10)."</th>
					<th align='left'>".$dtotal."</th>
					<th align='left'>".$status."</th>
					<th align='center'><a href=\"javascript:lanza_ventana_visualizar_taller('$area_id','$programa_id','$folio','$ano_id','$mes','2','1');\"><img src='../imagenes/magnifier.png' width='16' height='16' border='0'></a></th>
					</tr>";
				$conta++;
			}
			$buffer.="</tbody><thead><tr><td colspan='9'>Total de registros:  ".$num."</td></tr><thead></table>";
		}
		else
		{
			$buffer="No hay actividades registradas";
		}
		return $buffer;
	}

	function Calcula_promedio($x,$ax,$bx,$cx,$t,$at,$bt,$ct)
	{
		$no_tri = 0;
		$total=0;
		if($t  > 0) $no_tri++;
		if($at > 0) $no_tri++;
		if($bt > 0) $no_tri++;
		if($ct > 0) $no_tri++;
		if($no_tri > 0)
		{
			$total=( (($x + $ax + $bx + $cx) / $no_tri) + 0.00);
			$total=number_format($total, 2, '.', '0');
			$total=round($total);
		}
		return $total;
	}
}
?>