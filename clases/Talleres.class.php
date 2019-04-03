	<?php
class Talleres
{
	var $db;
	var $data;
	var $buffer;
	var $styles;
	var $stylesc;
	var $stylesm;
	var $stylest;	
	
	function __construct($db,$data){
		$this->db=$db;
		$this->data = $data;
		$this->styles="class='form-control' style='height: 30px;width:380px;' ";
		$this->stylesm=" style='height: 30px;width:380px;' ";
		$this->stylest=" style='height:130px;width:380px;' ";
		$this->stylesc="class='form-control' style='height: 30px;width:140px;' ";
		$this->buffer=$this->Captura_Taller($this->data);
	}
	
	function Regresa_Ayudas(){
		$array=array();
	    $sql = "SELECT * FROM cat_ayudas_formato_talleres limit 1";
		$res = $this->db->sql_query($sql) or die ("Error en la consulta: ".$sql);
		if($this->db->sql_numrows($res)>0)
	    {
			$array=$this->db->sql_fetchrow($res);
		}
        return $array;
	}

	function regresa_talleres()
	{
        $array_taller = array();
        $sql="SELECT  taller_id,nombre FROM cat_talleres ORDER BY taller_id;";
        $res=$this->db->sql_query($sql);
        $num=$this->db->sql_numrows($res);
        if($num > 0)
        {
            while(list($taller_id,$nombre) = $this->db->sql_fetchrow($res))
            {
                $array_taller[$taller_id]=$nombre;
            }
        }
        return $array_taller;

	}

    function Regresa_Nombre_Periodo($periodo_id)
    {
        $nombre='';
        $sql="SELECT periodo FROM cat_talleres_periodo WHERE periodo_id=".$periodo_id.";";
        $res=$this->db->sql_query($sql);
        if($this->db->sql_numrows($res)>0)
        {
            list($nombre)= $this->db->sql_fetchrow($res);
        }
        return $nombre;
    }
    function Regresa_Nombre_Tipo($tipo_id)
    {
        $nombre='';
        $sql="SELECT nombre FROM cat_tipo_taller WHERE tipo_taller_id=".$tipo_id.";";
        $res=$this->db->sql_query($sql);
        if($this->db->sql_numrows($res)>0)
        {
            list($nombre)= $this->db->sql_fetchrow($res);
        }
        return $nombre;
    }

    function Regresa_Nombre_Taller($id_taller)
    {
        $nombre='';
        $sql="SELECT nombre FROM cat_talleres WHERE taller_id=".$id_taller.";";
        $res=$this->db->sql_query($sql);
        if($this->db->sql_numrows($res)>0)
        {
            list($nombre)= $this->db->sql_fetchrow($res);
        }
        return $nombre;

    }

	function Listado_Taller_Elimina($data,$array_areas,$array_programas,$meses)
	{
        $user_id=$data['user_id'];
        $mes_id=$data['mesele'];
        $ano_id=$data['anoele'];
        $array_areas[0]='Todos';
        $array_programas[0]='Todos';
        $buf="No hay talleres especificados";
        if($data['inv_area'] > 0)
            $filtro[]=" area_id='".$data['inv_area']."'";

        if($data['cat_programa_id1'] > 0)
            $filtro[]=" programa_id='".$data['cat_programa_id1']."'";

        if($data['anoele'] > 0)
        {
            if($data['mesele']=='00')
                $filtro[]=" substr(fecha_inicio,1,4)='".$data['anoele']."' ";
            else
                $filtro[]=" substr(fecha_inicio,1,7)='".$data['anoele']."-".$data['mesele']."' ";
        }
        if(count($filtro)>0)
        {
            $filtros=" AND ".implode(" AND ",$filtro);
        }
		$sql_count="SELECT status,count(status) as total FROM proyectos_talleres WHERE visible=1 ".$filtros." GROUP BY status ORDER BY status;";
		$res_count=$this->db->sql_query($sql_count);
		$num_count=$this->db->sql_numrows($res_count);
		if( $num_count > 0)
		{
            $buf="<center>Actividades realizadas del &aacute;rea: <b>".$array_areas[$data['inv_area']]."</b>, año ".$data['anoele'].",  mes ".$meses[($data['mesele']+0)]."</center><br>";
			$wid=round(100/$num_count);
			$buf.="<table width='95%' border='0' align='center'>
					<tr>";
			while(list($status,$total) = $this->db->sql_fetchrow($res_count))
			{
				$buf.="<td width='".$wid."%'>".$status."   (".$total.")</td>";
			}
			$buf.="</tr></table><br>";
		}
        $sql="SELECT folio_id,area_id,programa_id,tipo_taller_id,periodo_id,nombre,fecha_inicio,fecha_termina,visible,status
              FROM proyectos_talleres
              WHERE visible=1 ".$filtros." ORDER BY folio_id;";
        $res=$this->db->sql_query($sql);
        $num=$this->db->sql_numrows($res);
        if($num > 0)
        {
        $buf.="
              <table width='100%' border='0' align='center' class='tablesorter'>
              <thead><tr>
              <th width='5%'>No.</th>
              <th width='14%'>Area</th>
              <th width='19%'>Programa</th>
              <th width='29%'>Taller</th>
              <th width='10%'>Status</th>
              <th width='5%'>Poblacion</th>
              <th width='3%'>A</th>
              <th width='3%'>C</th>
              <th width='3%'>B</th>
              <th width='3%'>E</th>
              </tr></thead>
              <tbody>";
            $con_talleres=1;
            while(list(	$folio_id,$area_id,$programa_id,$tipo_taller_id,$periodo_id,$nombre,$fecha_inicio,$fecha_termina,$visible,$status)= $this->db->sql_fetchrow($res))
            {
                $total=0;
                $sql_pob="SELECT (total+atotal+btotal+ctotal) as total_pob FROM `proyectos_asistentes_talleres`
                          WHERE folio_id=".$folio_id.";";
                $res_pob=$this->db->sql_query($sql_pob);
                if($this->db->sql_numrows($res_pob) > 0)
                {
                    $total=mysql_result($res_pob,0,0);
                }
				$list='';
				$list=$status;
				switch($list)
				{
					case 'INICIADO':
					{
						$tmp_status_1=' SELECTED ';
						$tmp_status_2='';
						$tmp_status_3='';
						$tmp_status_4='';
						$tmp_status_5='';
						break;
					}
					case 'EN PROCESO':
					{
						$tmp_status_2=' SELECTED ';
						$tmp_status_1='';
						$tmp_status_3='';
						$tmp_status_4='';
						$tmp_status_5='';
						break;
					}
					case 'TERMINADA':
					{
						$tmp_status_3=' SELECTED ';
						$tmp_status_2='';
						$tmp_status_1='';
						$tmp_status_4='';
						$tmp_status_5='';
						break;
					}
					case 'CANCELADA':
					{
						$tmp_status_4=' SELECTED ';
						$tmp_status_1='';
						$tmp_status_2='';
						$tmp_status_3='';
						$tmp_status_5='';
						break;
					}
					case 'DE BAJA':
					{
						$tmp_status_5=' SELECTED ';
						$tmp_status_2='';
						$tmp_status_3='';
						$tmp_status_4='';
						$tmp_status_1='';
						break;
					}
				}
                $tmp=$area_id.$programa_id.$folio_id;
				$tmp_s="status".$tmp;
				$select_status="<select name='".$tmp_s."' id='".$tmp_s."' class='status' onChange=\"elimina_datos_del_taller('$area_id','$programa_id','$folio_id','$tmp','$tmp_s');\">
					<option value=''></option>
                       <option value='INICIADO' ".$tmp_status_1.">INICIADO</option>
                       <option value='EN PROCESO' ".$tmp_status_2.">EN PROCESO</option>
                       <option value='TERMINADA' ".$tmp_status_3.">TERMINADA</option>
                       <option value='CANCELADA' ".$tmp_status_4.">CANCELADA</option>
                       <option value='DE BAJA' ".$tmp_status_5.">DE BAJA</option></select></form>";

                $buf.="<tr>
                <th align='left'>".$con_talleres."</th>
                <th align='left'>".$array_areas[$area_id]."</th>
                <th align='left'>".$array_programas[$programa_id]."</th>
                <th align='left'>".$nombre."</th>
                <th align='left'>".$select_status."</th>
                <th align='left'>".$total."</th>
					<th align='center'><a href=\"javascript:lanza_ventana_taller('$area_id','$programa_id','$folio_id','$ano_id','$mes_id','1','$user_id');\"><img src='imagenes/vcard.png' width='16' height='16' border='0'></a></th>
					<th align='center'><a href=\"javascript:lanza_ventana_taller('$area_id','$programa_id','$folio_id','$ano_id','$mes_id','2','$user_id');\"><img src='imagenes/magnifier.png' width='16' height='16' border='0'></a></th>
                    <th align='center'><a href=\"javascript:Borra_Taller_BD('$folio_id','$tmp');\"><img src='imagenes/delete.png' width='16' height='16' border='0'></a></th>
                    <th align='center'><div id='".$tmp."'></div></th></tr>";
                $con_talleres++;
		    }
	        $buf.="</tbody><thead><tr><td colspan='10'>Total de Registros:&nbsp;".$num."</td></tr></thead></table>";
		}
        return $buf;
    }


	function Listado_Taller($data,$array_areas,$array_programas,$meses)
	{
        $menu=$data['aplicacion'];
        $submenu=$data['apli_com'];
        $modulo=$data['vertal'];
        $user_id=$data['user_id'];
        $mes_id=$data['mesele'];        
        $ano_id=$data['anoele'];
        $array_areas[0]='Todos';
        $array_programas[0]='Todos';
        $buf="No hay talleres especificados";
        if($data['inv_area'] > 0)
            $filtro[]=" area_id='".$data['inv_area']."'";

        if($data['cat_programa_id1'] > 0)
            $filtro[]=" programa_id='".$data['cat_programa_id1']."'";

        if($data['anoele'] > 0)
        {
            if($data['mesele']=='00')
                $filtro[]=" substr(fecha_inicio,1,4)='".$data['anoele']."' ";
            else
                $filtro[]=" substr(fecha_inicio,1,7)='".$data['anoele']."-".$data['mesele']."' ";
        }
        if(count($filtro)>0)
        {
            $filtros=" AND ".implode(" AND ",$filtro);
        }
		$sql_count="SELECT status,count(status) as total FROM proyectos_talleres WHERE visible=1 ".$filtros." GROUP BY status ORDER BY status;";
		$res_count=$this->db->sql_query($sql_count);
		$num_count=$this->db->sql_numrows($res_count);
		if( $num_count > 0)
		{
            $buf="<center>Actividades realizadas del &aacute;rea: <b>".$array_areas[$data['inv_area']]."</b>, año ".$data['anoele'].",  mes ".$meses[($data['mesele']+0)]."</center><br>";

			$wid=round(100/$num_count);
			$buf.="<table width='95%' border='0' align='center'>
					<tr>";
			while(list($status,$total) = $this->db->sql_fetchrow($res_count))
			{
				$buf.="<td width='".$wid."%'>".$status."   (".$total.")</td>";
			}
			$buf.="</tr></table><br>";
		}
        $sql="SELECT folio_id,area_id,programa_id,subprograma_id,tipo_taller_id,periodo_id,nombre,fecha_inicio,fecha_termina,visible,status
              FROM proyectos_talleres
              WHERE visible=1 ".$filtros." ORDER BY folio_id;";
        $res=$this->db->sql_query($sql);
        $num=$this->db->sql_numrows($res);
        if($num > 0)
        {
        $buf.="
              <table width='100%' border='0' align='center' class='tablesorter'>              
              <thead><tr>
              <th width='5%'>No.</th>
              <th width='14%'>Area</th>
              <th width='22%'>Programa</th>
              <th width='29%'>Taller</th>
              <th width='10%'>Status</th>              
              <th width='5%' align='center'>Poblacion</th>
              <th width='3%'>A</th>
              <th width='3%'>C</th>
              <th width='3%'>E</th>
              </tr></thead>
              <tbody>";
            $con_talleres=1;
            while(list(	$folio_id,$area_id,$programa_id,$subprograma_id,$tipo_taller_id,$periodo_id,$nombre,$fecha_inicio,$fecha_termina,$visible,$status)= $this->db->sql_fetchrow($res))
            {
                $bloqueado=$this->Regresa_Esta_Bloqueado($area_id,$programa_id,$subprograma_id,substr($fecha_inicio,0,4),substr($fecha_inicio,5,2));
                $div_taller='divtexto'.$area_id.$programa_id.$folio_id;
                $data_folio_id['folio_id']=$folio_id;
                $datos_pob=$this->Obten_datos_Poblacion($data_folio_id);
                $totales=$datos_pob['dtotal'];
				$list='';
				$list=$status;
				switch($list)
				{
					case 'INICIADO':
					{
						$tmp_status_1=' SELECTED ';
						$tmp_status_2='';
						$tmp_status_3='';
						$tmp_status_4='';
						$tmp_status_5='';
						break;
					}
					case 'EN PROCESO':
					{
						$tmp_status_2=' SELECTED ';
						$tmp_status_1='';
						$tmp_status_3='';
						$tmp_status_4='';
						$tmp_status_5='';
						break;
					}
					case 'TERMINADA':
					{
						$tmp_status_3=' SELECTED ';
						$tmp_status_2='';
						$tmp_status_1='';
						$tmp_status_4='';
						$tmp_status_5='';
						break;
					}
					case 'CANCELADA':
					{
						$tmp_status_4=' SELECTED ';
						$tmp_status_1='';
						$tmp_status_2='';
						$tmp_status_3='';
						$tmp_status_5='';
						break;
					}
					case 'DE BAJA':
					{
						$tmp_status_5=' SELECTED ';
						$tmp_status_2='';
						$tmp_status_3='';
						$tmp_status_4='';
						$tmp_status_1='';
						break;
					}
				}
                $tmp=$area_id.$programa_id.$folio_id;
				$tmp_s="status".$tmp;
				$select_status="<select name='".$tmp_s."' id='".$tmp_s."' class='status' onChange=\"elimina_datos_del_taller('$area_id','$programa_id','$folio_id','$tmp','$tmp_s');\">
					<option value=''></option>
                       <option value='INICIADO' ".$tmp_status_1.">INICIADO</option>
                       <option value='EN PROCESO' ".$tmp_status_2.">EN PROCESO</option>
                       <option value='TERMINADA' ".$tmp_status_3.">TERMINADA</option>
                       <option value='CANCELADA' ".$tmp_status_4.">CANCELADA</option>
                       <option value='DE BAJA' ".$tmp_status_5.">DE BAJA</option></select></form>";

                $buf.="<tr>
                <th align='left'>".$con_talleres."</th>
                <th align='left'>".$array_areas[$area_id]."</th>
                <th align='left'>".$array_programas[$programa_id]."</th>
                <th align='left'>".$nombre."</th>
                <th align='left'>".$select_status."</th>               
                <th align='center'><input type='text' name='".$div_taller."'  id='".$div_taller."' value='".$totales."' readonly size='6' style='background-color:#ffffff;border:0px;'></div></th>
                <th align='center'>&nbsp;";
                if($bloqueado==0)
                {
                    $buf.="
					<a href=\"javascript:lanza_ventana_taller('$area_id','$programa_id','$folio_id','$ano_id','$mes_id','1','$user_id','$div_taller');\"><img src='imagenes/vcard.png' width='16' height='16' border='0'></a>";
                }
                $buf.="</th><th align='center'><a href=\"javascript:lanza_ventana_taller('$area_id','$programa_id','$folio_id','$ano_id','$mes_id','2','$user_id','$div_taller');\"><img src='imagenes/magnifier.png' width='16' height='16' border='0'></a></th>
                <th align='center'><div id='".$tmp."'></div></th></tr>";
                $con_talleres++;
		    }
	        $buf.="</tbody><thead><tr><td colspan='9'>Total de Registros:&nbsp;".$num."</td></tr></thead></table>";
		}
        return $buf;
    }
    function regresa_periodo_taller($id_periodo)
    {
        $select='';
		$sql="SELECT periodo_id,periodo FROM cat_talleres_periodo ORDER BY periodo;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res) > 0)
		{
            $select.="<select name='periodo_id' id='periodo_id'  $this->stylesm required=\"yes\" errormsg=\"seleccione una opcion\">
                    <option value=''></option>";
            while(list($periodo_id,$nombre) = $this->db->sql_fetchrow($res))
			{
                $tmp='';
				if($periodo_id == $id_periodo)
                    $tmp=' SELECTED ';
                $select.="<option value='".$periodo_id."' ".$tmp.">".$nombre."</option>";
            }
		    $select.="</select>";
        }
		return $select;
    }
		function regresa_tipo_taller($id_taller)
		{
		    $select='';
			$sql="SELECT tipo_taller_id,nombre FROM cat_tipo_taller ORDER BY nombre;";
		    $res=$this->db->sql_query($sql);
		    if($this->db->sql_numrows($res) > 0)
			{
		        $select.="<select name='tipo_taller_id' id='tipo_taller_id' $this->stylesm  required=\"yes\" errormsg=\"seleccione una opcion\">
						  <option value=''></option>";
			    while(list($tipo_taller_id,$nombre) = $this->db->sql_fetchrow($res))
				{
					$tmp='';
					if($tipo_taller_id == $id_taller)
						$tmp=' SELECTED ';
				    $select.="<option value='".$tipo_taller_id."' ".$tmp.">".$nombre."</option>";
		        }
		        $select.="</select>";
		    }
		    return $select;
		}
		function regresa_talleres_combo($id_taller,$filtro)
		{
			$select='';
			$sql="SELECT taller_id,nombre FROM  cat_talleres WHERE visible='1' ".$filtro." ORDER BY nombre;";
			$res=$this->db->sql_query($sql);
			$num=$this->db->sql_numrows($res);
			if($num>0)
			{
                $select.="<select name='taller_id' id='taller_id' $this->stylesm required=\"yes\" errormsg=\"seleccione una opcion\">";
                while(list($taller_id,$nombre) = $this->db->sql_fetchrow($res))
                {
                    $tmp='';
                    if($taller_id == $id_taller)
                        $tmp=' selected';
                    $select.="<option value='".$taller_id."' ".$tmp.">".$nombre."</option>";
                }
                $select.="</select>";
			}
			return $select;
		}
		function Captura_Taller($data,$tmp)
	    {
			$ayudas=$this->Regresa_Ayudas();
            $alt_taller='alt_tal';
            if($tmp == 1 )
			{
                $alt_taller='altev';
				$data['anoele']=date("Y");
			}
            $filtro=" AND area_id=0 AND programa_id=0 ";
            if($data['inv_area'] == 4)
            {
                $filtro=" AND area_id=".$data['inv_area']." AND programa_id=".$data['cat_programa_id1']." ";
            }
            $select=$this->regresa_tipo_taller($data['tipo_taller_id']);
            $select_periodo=$this->regresa_periodo_taller($data['periodo_id']);
	        $buffer="
	        	<input type='hidden' name='inv_area'         id='inv_area' value='".$data['inv_area']."'>
	            <input type='hidden' name='cat_programa_id1' id='cat_programa_id1' value='".$data['cat_programa_id1']."'>
	            <input type='hidden' name='subprograma_id' id='subprograma_id' value='".($data['subprograma_id']+0)."'>
				<input type='hidden' value='2' name='".$alt_taller."'>";
        if($data['subprograma_id'] > 0) {
            $buffer.="<p align='left'>Sub programa:&nbsp;&nbsp;".$this->regresa_subprograma($data['subprograma_id'])."</p>";
        }

            $buffer.="<table width='100%' align='center' border='0'>
			    <tr><td colspan='4' class='tdverde'>Registro de Taller</td></tr>
				<tr>
			    <th>";
				$buffer.=$select_area."</th></tr><tr><th>
					<table width='100%' align='center' border='0'>
						<tr>
		                    <td width='35%'>Nombre del Taller</td>
		                    <td>".$this->regresa_talleres_combo($data['taller_id'],$filtro)."&nbsp;&nbsp
                            ".$this->muestraAyuda($ayudas[1])."
                            </td>
		                </tr>
		                <tr>
				            <td>Tipo de Taller</td>
		                    <td>".$select."&nbsp;&nbsp
                            ".$this->muestraAyuda($ayudas[2])."
                            </td>
		                </tr>
		                <tr>
				            <td>Fecha de Inicio &nbsp;&nbsp;<div id='validacacionFecha'></div></td>
                            <td><input type=\"text\" id=\"cal-field-1\" name=\"fecha_inicio\" value=\"".$data['fecha_inicio']."\"  $this->stylesc  required=\"yes\" errormsg=\"Favor de seleccionar la fecha de inicio\"/>
                            <img src=\"imagenes/calendar.png\" id=\"cal-button-1\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
                            ".$this->muestraAyuda($ayudas[3])."
                            <script type=\"text/javascript\">
                                Calendar.setup({
                                    inputField    : 'cal-field-1',
                                    ifFormat      : '%Y-%m-%d',
                                    onUpdate      : revisa_fecha,
                                    showsTime     : true,
                                    button        : 'cal-button-1'
                                });
                              </script>
                            </td>
		                </tr>
		                <tr>
				            <td>Fecha de Termino</td>
		                    <td><input type=\"text\" id=\"cal-field-2\" name=\"fecha_termina\" value=\"".$data['fecha_termina']."\"  $this->stylesc  required=\"yes\" errormsg=\"Favor de seleccionar la fecha de termino\"/>
                            <img src=\"imagenes/calendar.png\" id=\"cal-button-2\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
                            ".$this->muestraAyuda($ayudas[4])."
                            <script type=\"text/javascript\">
                                Calendar.setup({
                                    inputField    : 'cal-field-2',
                                    ifFormat      : '%Y-%m-%d',
                                    onUpdate      : revisa_fecha_final,
                                    showsTime     : true,
                                    button        : 'cal-button-2'
                                });
                              </script>
                            &nbsp;&nbsp;<div id='validacacionFechaFin'></div></td>
		                </tr>
                        <tr>
                            <td width='15%'>No de Trimestre</td>
                            <td width='35%'>
                                <select name='trimestre_id' $this->stylesm id='trimestre_id' required=\"yes\" errormsg=\"Favor de seleccionar una opcion\">
                                    <option value=''></option>
                                    <option value='".$data['anoele']."-01'>1 Trimestre ".$data['anoele']."</option>
                                    <option value='".$data['anoele']."-02'>2 Trimestre ".$data['anoele']."</option>
                                    <option value='".$data['anoele']."-03'>3 Trimestre ".$data['anoele']."</option>
                                    <option value='".$data['anoele']."-04'>4 Trimestre ".$data['anoele']."</option>
                                    <option value='".$data['anoele']."-05'>No aplica   </option>
                                </select>
                            &nbsp;&nbsp
                            ".$this->muestraAyuda($ayudas[5])."
                            </td>
                       </tr>
                       </table>";
            $buffer.='
            <table width="100%" align="center" border="0">
            <tr><td><br><br>
            <div id="content_tabs">
            <ul id="tabs2" class="nav nav-tabs">
                <li class="active"><a href="#fragment-1" role="tab"  data-toggle="tab"><span color="#ffffff">Primer Registro</span></a></li>
                <li><a href="#fragment-2" role="tab"  data-toggle="tab"><span color="#ffffff">Segundo Registro</span></a></li>
                <li><a href="#fragment-3" role="tab"  data-toggle="tab"><span color="#ffffff">Tercer Registro</span></a></li>
                <li><a href="#fragment-4" role="tab"  data-toggle="tab"><span color="#ffffff">Cuarto Registro</span></a></li>
                <li><a href="#fragment-5" role="tab"  data-toggle="tab"><span color="#ffffff">Total Beneficiarios</span></a></li>
            </ul>
			<div id="my-tab-content" class="tab-content">
				<div id="fragment-1" class="tab-pane active">';
            $disable='';
            if (($data['cat_programa_id1'] >=16 ) || ($data['cat_programa_id1'] <=19) )
                $disable=" readonly='true'";

           $buffer.=$this->formato_poblacion($data,1,'',$ayudas);
           $buffer.='</div>
            <div id="fragment-2" class="tab-pane">';
           $buffer.=$this->formato_poblacion($data,2,$disable,$ayudas);
           $buffer.='</div>
            <div id="fragment-3" class="tab-pane">';
           $buffer.=$this->formato_poblacion($data,3,$disable,$ayudas);
           $buffer.='</div>
            <div id="fragment-4" class="tab-pane">';
           $buffer.=$this->formato_poblacion($data,4,'',$ayudas);
           $buffer.='</div>
           <div id="fragment-5" class="tab-pane">';
           $buffer.=$this->formato_poblacion($data,5,'',$ayudas);
           $buffer.='</div>

            </div></div></td></tr></table>';


           $buffer.="<table width='100%' align='center' border='0'>
			            <tr><td colspan='4'>&nbsp;</td></tr>
				        <tr><td colspan='4' align='center'><input type='submit' name='btn1' value='Guardar Datos' class='boton'></td>
						</tr>
				    </table>
		        </th>
		        </tr>
		        </table>";
        return $buffer;
		}

        function formato_poblacion($data,$tri,$disabled,$ayudas)
        {
            switch($tri)
            {
                case 1:
                  $div_tit="Primer Registro de Asistentes";
                  $distintivo='';
                  break;
                case 2:
                  $div_tit="Segundo Registro de Asistentes";
                  $distintivo='a';
                  break;
                case 3:
                  $div_tit="Tercer Registro de Asistentes";
                  $distintivo='b';
                  break;
                case 4:
                  $div_tit="Cuarto Registro de Asistentes";
                  $distintivo='c';
                  break;
                case 5:
                  $div_tit="Total Beneficiarios";
                  $distintivo='d';
                  break;

            }
            $funcion_m="sumam_taller".$distintivo."();";
            $funcion_h="sumah_taller".$distintivo."();";
            $buf="
                <table width='100%' align='center' border='0'>
                    <tr><th colspan='4' align='center'>".$div_tit."</th></tr>";
            if($tri==5)
            {
             $buf.="<tr><td colspan='4' align='rigth'><input type='button' name='recalcula' id='recalcula' value='Recalcular Promedios' onclick='Recalcula();' class='boton'></td></tr>";
            }

            $buf.="<tr>
                        <td colspan='2' width='50%' align='left'>Hombres</td>
                        <td colspan='2' width='50%' align='left'>Mujeres</td>
                    <tr>
                        <td width='25%'>Poblaci&oacute;n 0 - 14 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$distintivo."pob_h_0_15' id='".$distintivo."pob_h_0_15' value='".($data['pob_h_0_15'] + 0)."' onblur='".$funcion_h."' required=\"yes\" ".$disabled.">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[6])."</td>
                        <td width='25%'>Poblaci&oacute;n 0 - 14 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$distintivo."pob_m_0_15' id='".$distintivo."pob_m_0_15' value='".($data['pob_m_0_15'] + 0)."' onblur='".$funcion_m."' required=\"yes\"  ".$disabled.">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[7])."</td>
                    </tr>
                    <tr>
                        <td width='25%'>Poblaci&oacute;n 15 - 29 A&ntilde;os </td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$distintivo."pob_h_16_18' id='".$distintivo."pob_h_16_18' value='".($data['pob_h_16_18'] + 0)."' onblur='".$funcion_h."' required=\"yes\"  ".$disabled.">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[8])."</td>
                        <td width='25%'>Poblaci&oacute;n 15 - 29 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$distintivo."pob_m_16_18' id='".$distintivo."pob_m_16_18' value='".($data['pob_m_16_18'] + 0)."' onblur='".$funcion_m."' required=\"yes\" ".$disabled.">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[9])."</td>
                    </tr>
                    <tr>
                        <td width='25%'>Poblaci&oacute;n 30 - 59 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$distintivo."pob_h_19_30' id='".$distintivo."pob_h_19_30' value='".($data['pob_h_19_30'] + 0)."' onblur='".$funcion_h."' required=\"yes\" ".$disabled.">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[10])."</td>
                        <td width='25%'>Poblaci&oacute;n 30 - 59 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$distintivo."pob_m_19_30' id='".$distintivo."pob_m_19_30' value='".($data['pob_m_19_30'] + 0)."' onblur='".$funcion_m."' required=\"yes\" ".$disabled.">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[11])."</td>
                    </tr>
                    <tr>
                        <td width='25%'>Mayores de 60 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$distintivo."pob_h_65' id='".$distintivo."pob_h_65' value='".($data['pob_h_65'] + 0)."' onblur='".$funcion_h."' required=\"yes\" ".$disabled.">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[12])."</td>
                        <td width='25%'>Mayores de 60 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$distintivo."pob_m_65' id='".$distintivo."pob_m_65' value='".($data['pob_m_65'] + 0)."' onblur='".$funcion_m."' required=\"yes\" ".$disabled.">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[13])."</td>
                    </tr>
                    <tr>
                        <td width='25%'>Total Hombres</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$distintivo."total_h' id='".$distintivo."total_h' value='".($data['total_h'] + 0)."' readonly>&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[14])."</td>
                        <td width='25%'>Total Mujeres</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$distintivo."total_m' id='".$distintivo."total_m' value='".($data['total_m'] + 0)."' readonly>&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[15])."</td>
                        </tr>
                    <tr>
                        <td colspan='4' align='center'>Total de Poblaci&oacute:n:&nbsp;&nbsp;
                        <input type='text' $this->stylesc  name='".$distintivo."total' id='".$distintivo."total' value='".($data['total'] + 0)."' readonly>&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[16])."</td>
                    </tr>
                </table>";
            return $buf;
        }
		function Inserta_Taller($data)
		{
            $folio=0;
            $this->Regresa_Esta_Bloqueado($data['inv_area'], substr($data['fecha_inicio'],0,4), substr($data['fecha_inicio'],5,2));
            if( $bloqueado== 0)
            {
                $nombre_taller=$this->Regresa_Nombre_Taller($data['taller_id']);
        		$buffer="<center>Error: No se inserto el registro, favor de intertarlo nuevamente.</center>";
                $inser="INSERT INTO proyectos_talleres (area_id,programa_id,taller_id,tipo_taller_id,periodo_id,nombre,fecha_inicio,fecha_termina,visible,status)
                values ('".$data['inv_area']."','".$data['cat_programa_id1']."','".$data['taller_id']."','".$data['tipo_taller_id']."','0','".$nombre_taller."','".$data['fecha_inicio']."','".$data['fecha_termina']."','1','INICIADO');";
    	        if($this->db->sql_query($inser,$db_connect))
                {
                    $folio=mysql_insert_id();
                    $inser_asist="INSERT INTO proyectos_asistentes_talleres
                    (folio_id,area_id,programa_id,taller_id,
                    pob_h_0_15,pob_h_16_18,pob_h_19_30,pob_h_31_64,pob_h_65,pob_m_0_15,pob_m_16_18,pob_m_19_30,pob_m_31_64,pob_m_65,total_h,total_m,total,
                    apob_h_0_15,apob_h_16_18,apob_h_19_30,apob_h_31_64,apob_h_65,apob_m_0_15,apob_m_16_18,apob_m_19_30,apob_m_31_64,apob_m_65,atotal_h,atotal_m,atotal,
                    bpob_h_0_15,bpob_h_16_18,bpob_h_19_30,bpob_h_31_64,bpob_h_65,bpob_m_0_15,bpob_m_16_18,bpob_m_19_30,bpob_m_31_64,bpob_m_65,btotal_h,btotal_m,btotal,
                    cpob_h_0_15,cpob_h_16_18,cpob_h_19_30,cpob_h_31_64,cpob_h_65,cpob_m_0_15,cpob_m_16_18,cpob_m_19_30,cpob_m_31_64,cpob_m_65,ctotal_h,ctotal_m,ctotal,
                    fecha_alta,trimestre_id,dtotal)
                    VALUES
                    ('".$folio."','".$data['inv_area']."','".$data['cat_programa_id1']."','".$data['taller_id']."',
                     '".$data['pob_h_0_15']."','".$data['pob_h_16_18']."','".$data['pob_h_19_30']."','".$data['pob_h_31_64']."','".$data['pob_h_65']."',
                     '".$data['pob_m_0_15']."','".$data['pob_m_16_18']."','".$data['pob_m_19_30']."','".$data['pob_m_31_64']."','".$data['pob_m_65']."',
                     '".$data['total_h']."','".$data['total_m']."','".$data['total']."',
                     '".$data['apob_h_0_15']."','".$data['apob_h_16_18']."','".$data['apob_h_19_30']."','".$data['apob_h_31_64']."','".$data['apob_h_65']."',
                     '".$data['apob_m_0_15']."','".$data['apob_m_16_18']."','".$data['apob_m_19_30']."','".$data['apob_m_31_64']."','".$data['apob_m_65']."',
                     '".$data['atotal_h']."','".$data['atotal_m']."','".$data['atotal']."',
                     '".$data['bpob_h_0_15']."','".$data['bpob_h_16_18']."','".$data['bpob_h_19_30']."','".$data['bpob_h_31_64']."','".$data['bpob_h_65']."',
                     '".$data['bpob_m_0_15']."','".$data['bpob_m_16_18']."','".$data['bpob_m_19_30']."','".$data['bpob_m_31_64']."','".$data['bpob_m_65']."',
                     '".$data['btotal_h']."','".$data['btotal_m']."','".$data['btotal']."',
                     '".$data['cpob_h_0_15']."','".$data['cpob_h_16_18']."','".$data['cpob_h_19_30']."','".$data['cpob_h_31_64']."','".$data['cpob_h_65']."',
                     '".$data['cpob_m_0_15']."','".$data['cpob_m_16_18']."','".$data['cpob_m_19_30']."','".$data['cpob_m_31_64']."','".$data['cpob_m_65']."',
                     '".$data['ctotal_h']."','".$data['ctotal_m']."','".$data['ctotal']."','".$data['fecha_inicio']."','".$data['trimestre_id']."','".$data['dtotal']."');";

                    $res_ins_asis=$this->db->sql_query($inser_asist);
                }
            }
            else
            {
                $folio= -1 ;
            }
			return $folio;
		}

		function Selecciona_Taller($data,$select_area)
		{			
			$buffer="
			    <form action='".$PHP_SELF."' method='POST'>
        		<input type='hidden' name='user_id' id='user_id' value='".$data['user_id']."'>
                <input type='hidden' name='aplicacion' id='aplicacion'  value='".$data['aplicacion']."'>
                <input type='hidden' name='apli_com' id='apli_com'    value='".$data['apli_com']."'>
                <input type='hidden' value='4' name='cat_taller'>
				<table width='80%' align='center' border='0'>
			    <tr><td colspan='4' class='tdverde'>Actualizaci&oacute;n de Talleres</td></tr>
				<tr>
			    <th colspan='4'>";
		        $buffer.=$select_area."</th></tr>
                <tr>
		        <td align='left' width='35%'>Taller</td>
		        <td align='left' width='65%' colspan='3'>
		            <select name='taller_id' id='taller_id' $this->stylesmf>
		            <option value='0'></option></select>
		            </td></tr>
		            <tr><th colspan='4' align='center'><input type='submit' name='btn1' id='btn1' value='Consultar' class='boton'></th></tr>
				    </table></form>";
			return $buffer;
		}
		function Actualiza_Taller($data)
		{
			$buffer="<center>Error: No se actualizo el registro, favor de intertarlo nuevamente.</center>";
            $this->Regresa_Esta_Bloqueado($data['inv_area'], substr($data['fecha_inicio'],0,4), substr($data['fecha_inicio'],5,2));
            if( $bloqueado== 0)
            {
			    $campos="";
				$valores="";
			    foreach($data as $clave => $valor)
				{
				    if( ($clave!='apli_com') && ($clave!='cat_taller') && ($clave!='aplicacion')  && ($clave!='user_id')  &&	($clave!='taller_id') && ($clave!='area_id') && ($clave!='programa_id') && ($clave!='inv_area'))
					{
		                $campos.=$clave."='".$valor."',";
			        }
				}
		        $campos=$campos."visible=1";
			    $update="UPDATE cat_talleres SET ".$campos." WHERE taller_id=".$data['taller_id'].";";
	            if($this->db->sql_query($update))
				{
				    $buffer="<center>Se ha actualizado el registro</center>";
				}
			}
			return $buffer;
		}

        function Regresa_Catalogo_Talleres($user_id,$area_id,$programa_id)
        {
	        $select='';
            $sql="SELECT taller_id,nombre FROM cat_talleres WHERE area_id=".$area_id." AND programa_id=".$programa_id." ORDER BY nombre ASC";
		    $res=$this->db->sql_query($sql);
	        if($this->db->sql_numrows($res) > 0)
		    {
                $select.="<option value=''></option>";
				while(list($taller_id,$xnombre) = $this->db->sql_fetchrow($res))
	            {
					$nombre=str_replace('á','&aacute;',$xnombre);
					$nombre=str_replace('é','&eacute;',$nombre);
					$nombre=str_replace('í','&iacute;',$nombre);
					$nombre=str_replace('ó','&oacute;',$nombre);
					$nombre=str_replace('ú','&uacute;',$nombre);
					$nombre=str_replace('ñ','&ntilde;',$nombre);
                    $select.="<option value='".$taller_id."' ".$tmp.">".$nombre."</option>";
		        }
	        }
		    return $select;
        }

        function Obten_datos_Taller($data)
        {
            $datos=array();
            $sql="SELECT * FROM  proyectos_talleres WHERE folio_id=".$data['folio_id'].";";
            $res=$this->db->sql_query($sql);
            if($this->db->sql_numrows($res) > 0)
            {
                $datos=$this->db->sql_fetchrow($res);
            }
            return $datos;
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
        function Obten_datos_Poblacion($data)
        {
            $no_tri=0;
            $no_tri_2=0;
            $no_tri_3=0;
            $no_tri_4=0;
            $datos=array();
            $sql="SELECT * FROM proyectos_asistentes_talleres WHERE folio_id=".$data['folio_id'].";";
            $res=$this->db->sql_query($sql);
            if($this->db->sql_numrows($res) > 0)
            {
                $datos=$this->db->sql_fetchrow($res);
            }
            $datos['dpob_h_0_15'] =$this->Calcula_promedio($datos['pob_h_0_15'],$datos['apob_h_0_15'],$datos['bpob_h_0_15'],$datos['cpob_h_0_15'],$datos['total'],$datos['atotal'],$datos['btotal'],$datos['ctotal']);
            $datos['dpob_h_16_18']=$this->Calcula_promedio($datos['pob_h_16_18'],$datos['apob_h_16_18'],$datos['bpob_h_16_18'],$datos['cpob_h_16_18'],$datos['total'],$datos['atotal'],$datos['btotal'],$datos['ctotal']);
            $datos['dpob_h_19_30']=$this->Calcula_promedio($datos['pob_h_19_30'],$datos['apob_h_19_30'],$datos['bpob_h_19_30'],$datos['cpob_h_19_30'],$datos['total'],$datos['atotal'],$datos['btotal'],$datos['ctotal']);
            $datos['dpob_h_31_64']=$this->Calcula_promedio($datos['pob_h_31_64'],$datos['apob_h_31_64'],$datos['bpob_h_31_64'],$datos['cpob_h_31_64'],$datos['total'],$datos['atotal'],$datos['btotal'],$datos['ctotal']);
            $datos['dpob_h_65']   =$this->Calcula_promedio($datos['pob_h_65'],$datos['apob_h_65'],$datos['bpob_h_65'],$datos['cpob_h_65'],$datos['total'],$datos['atotal'],$datos['btotal'],$datos['ctotal']);
            $datos['dtotal_h']    = $datos['dpob_h_0_15'] + $datos['dpob_h_16_18'] + $datos['dpob_h_19_30'] + $datos['dpob_h_31_64'] +  $datos['dpob_h_65'] ;

            $datos['dpob_m_0_15'] =$this->Calcula_promedio($datos['pob_m_0_15'],$datos['apob_m_0_15'],$datos['bpob_m_0_15'],$datos['cpob_m_0_15'],$datos['total'],$datos['atotal'],$datos['btotal'],$datos['ctotal']);
            $datos['dpob_m_16_18']=$this->Calcula_promedio($datos['pob_m_16_18'],$datos['apob_m_16_18'],$datos['bpob_m_16_18'],$datos['cpob_m_16_18'],$datos['total'],$datos['atotal'],$datos['btotal'],$datos['ctotal']);
            $datos['dpob_m_19_30']=$this->Calcula_promedio($datos['pob_m_19_30'],$datos['apob_m_19_30'],$datos['bpob_m_19_30'],$datos['cpob_m_19_30'],$datos['total'],$datos['atotal'],$datos['btotal'],$datos['ctotal']);
            $datos['dpob_m_31_64']=$this->Calcula_promedio($datos['pob_m_31_64'],$datos['apob_m_31_64'],$datos['bpob_m_31_64'],$datos['cpob_m_31_64'],$datos['total'],$datos['atotal'],$datos['btotal'],$datos['ctotal']);
            $datos['dpob_m_65']   =$this->Calcula_promedio($datos['pob_m_65'],$datos['apob_m_65'],$datos['bpob_m_65'],$datos['cpob_m_65'],$datos['total'],$datos['atotal'],$datos['btotal'],$datos['ctotal']);
            $datos['dtotal_m']    = $datos['dpob_m_0_15'] + $datos['dpob_m_16_18'] + $datos['dpob_m_19_30'] + $datos['dpob_m_31_64'] +  $datos['dpob_m_65'];
            $datos['dtotal']      =($datos['dtotal_h'] + $datos['dtotal_m']);
            return $datos;
        }
        function Mostrar_Talleres($data,$data_taller,$data_poblacion)
        {
			$ayudas=$this->Regresa_Ayudas();
            $area_id=$data['area_id'];
            $programa_id=$data['programa_id'];
            $user_id=$data['user_id'];
            $ano=$data['ano'];
            $mes=$data['mes'];
            $div_taller=$data['div_taller'];
            $filtro=" AND area_id=0 AND programa_id=0 ";
            if($data['area_id'] == 4)
            {
                $filtro=" AND area_id=".$data['area_id']." AND programa_id=".$data['programa_id']." ";
            }

            switch($data_poblacion['trimestre_id'])
            {
                case $data['ano']."-01":
                    $tmp1=" SELECTED ";
                    $tmp2=" ";
                    $tmp3=" ";
                    $tmp4=" ";
                    break;
                case $data['ano']."-02":
                    $tmp2=" SELECTED ";
                    $tmp1=" ";
                    $tmp3=" ";
                    $tmp4=" ";
                    break;
                case $data['ano']."-03":
                    $tmp3=" SELECTED ";
                    $tmp1=" ";
                    $tmp2=" ";
                    $tmp4=" ";
                    break;
                case $data['ano']."-04":
                    $tmp4=" SELECTED ";
                    $tmp1=" ";
                    $tmp2=" ";
                    $tmp3=" ";
                    break;
            }
            $select=$this->regresa_tipo_taller($data_taller['tipo_taller_id']);
            $select_periodo=$this->regresa_periodo_taller($data_taller['periodo_id']);
            $buffer="<form name='form1' method='post' action='actualiza_talleres.php'>
					<input type='hidden' name='area_id' id='area_id' value='".$data['area_id']."'>
                    <input type='hidden' name='inv_area' id='inv_area' value='".$data['area_id']."'>
					<input type='hidden' name='programa_id' id='programa_id' value='".$data['programa_id']."'>
					<input type='hidden' name='cat_programa_id' id='cat_programa_id' value='".$data['programa_id']."'>
					<input type='hidden' name='subprograma_id' id='subprograma_id' value='".($data['subprograma_id']+0)."'>
					<input type='hidden' name='id' id='id' value='".$data['folio_id']."'>
					<input type='hidden' name='ano' id='ano' value='".$ano."'>
					<input type='hidden' name='mes' id='mes' value='".$mes."'>
                    <input type='hidden' name='folio_id' id='folio_id' value='".$data['folio_id']."'>
                    <table width='100%' align='center' border='0'>
			    <tr><td colspan='4' class='tdverde'>Registro de Taller</td></tr>
				<tr>
			    <th>";
				$buffer.=$select_area."</th></tr><tr><th>
					<table width='100%' align='center' border='0'>
						<tr>
		                    <td width='35%'>Nombre del Taller</td>
		                    <td>".$this->regresa_talleres_combo($data_taller['taller_id'],$filtro)."&nbsp;&nbsp
                            ".$this->muestraAyuda($ayudas[1])."
                            </td>
		                </tr>
		                <tr>
				            <td>Tipo de Taller</td>
		                    <td>".$select."&nbsp;&nbsp
                            ".$this->muestraAyuda($ayudas[2])."
                            </td>
		                </tr>
		                <tr>
				            <td>Fecha de Inicio</td>
                            <td><input type=\"text\" id=\"cal-field-1\" name=\"fecha_inicio\" value=\"".substr($data_taller['fecha_inicio'],0,10)."\"  $this->stylesc  required=\"yes\" errormsg=\"Favor de seleccionar la fecha de inicio\"/>
                            <img src=\"../imagenes/calendar.png\" id=\"cal-button-1\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
                            ".$this->muestraAyuda($ayudas[3])."
                            <script type=\"text/javascript\">
                                Calendar.setup({
                                    inputField    : 'cal-field-1',
                                    ifFormat      : '%Y-%m-%d',
                                    onUpdate      : revisa_fecha_Act,
                                    showsTime     : true,
                                    button        : 'cal-button-1'
                                });
                              </script>
                            &nbsp;&nbsp;<div id='validacacionFecha'></div>
                        </td>
		                </tr>
		                <tr>
				            <td>Fecha de Termino</td>
		                    <td><input type=\"text\" id=\"cal-field-2\" name=\"fecha_termina\" value=\"".substr($data_taller['fecha_termina'],0,10)."\"  $this->stylesc  required=\"yes\" errormsg=\"Favor de seleccionar la fecha de termino\"/>
                            <img src=\"../imagenes/calendar.png\" id=\"cal-button-2\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
                            ".$this->muestraAyuda($ayudas[4])."
                            <script type=\"text/javascript\">
                                Calendar.setup({
                                    inputField    : 'cal-field-2',
                                    ifFormat      : '%Y-%m-%d',
                                    onUpdate      : revisa_fecha_final_Act,
                                    showsTime     : true,
                                    button        : 'cal-button-2'
                                });
                              </script>
                            &nbsp;&nbsp;<div id='validacacionFechaFin'></td>
		                </tr>
                        <tr>
                            <td width='15%'>No de Trimestre</td>
                            <td width='35%'>
                                <select name='trimestre_id' id='trimestre_id' $this->stylesm required=\"yes\" errormsg=\"Favor de seleccionar una opcion\">
                                    <option value=''></option>
                                    <option value='".$data['ano']."-01' ".$tmp1.">1 Trimestre ".$data['ano']."</option>
                                    <option value='".$data['ano']."-02' ".$tmp2.">2 Trimestre ".$data['ano']."</option>
                                    <option value='".$data['ano']."-03' ".$tmp3.">3 Trimestre ".$data['ano']."</option>
                                    <option value='".$data['ano']."-04' ".$tmp4.">4 Trimestre ".$data['ano']."</option>
                                    <option value='".$data['anoele']."-05'>No aplica   </option>
                                </select>
                            &nbsp;&nbsp
                            ".$this->muestraAyuda($ayudas[5])."
                            </td>
                       </tr>
                       </table>";
            $buffer.='
            <table width="100%" align="center" border="0">
            <tr><td>
            <div id="content_tabs" >
            <ul id="tabs" class="nav nav-tabs">
                <li class="active"><a href="#fragment-1" role="tab"  data-toggle="tab">Primer Registro</a></li>
                <li><a href="#fragment-2" role="tab"  data-toggle="tab">Segundo Registro</a></li>
                <li><a href="#fragment-3" role="tab"  data-toggle="tab">Tercer Registro</a></li>
                <li><a href="#fragment-4" role="tab"  data-toggle="tab">Cuarto Registro</a></li>
                <li><a href="#fragment-5" role="tab"  data-toggle="tab">Total Beneficiarios</a></li>
            </ul><div id="my-tab-content" class="tab-content">
            <div id="fragment-1" class="tab-pane active">';
           $buffer.=$this->formato_poblacion_mostrar($data_poblacion,1,$ayudas);
           $buffer.='</div>
            <div id="fragment-2" class="tab-pane">';
           $buffer.=$this->formato_poblacion_mostrar($data_poblacion,2,$ayudas);
           $buffer.='</div>
            <div id="fragment-3" class="tab-pane">';
           $buffer.=$this->formato_poblacion_mostrar($data_poblacion,3,$ayudas);
           $buffer.='</div>
            <div id="fragment-4" class="tab-pane">';
           $buffer.=$this->formato_poblacion_mostrar($data_poblacion,4,$ayudas);
           $buffer.='</div>
            <div id="fragment-5" class="tab-pane">';
           $buffer.=$this->formato_poblacion_mostrar($data_poblacion,5,$ayudas);
           $buffer.='</div>
            </div></div></td></tr></table>';

            $buffer.="<table width='100%' align='center' border='0'>
			            <tr><td colspan='4'>&nbsp;</td></tr>
				        <tr><td colspan='4' align='center'>";
            if($data['tipo'] == 1)
            {
                $buffer.="<input type='button' name='btn1' value='Guardar Datos' class='boton' onclick=\"guarda_datos('".$div_taller."');\">";
            }
            $buffer.="&nbsp;<input type='button' name='btn2' value='Cerrar Ventana' class='boton' onClick=\"self.close();\">";
            $buffer.="</tr></table>
		        </th>
		        </tr>
		        </table>";
        return $buffer;
      }

        function formato_poblacion_mostrar($data,$tri,$ayudas)
        {
			$funcion_m='';
            $funcion_h='';
            switch($tri)
            {
                case 1:
                  $div_tit="Primer Registro de Asistentes";
                  $distintivo='';
                  break;
                case 2:
                  $div_tit="Segundo Registro de Asistentes";
                  $distintivo='a';
                  break;
                case 3:
                  $div_tit="Tercer Registro de Asistentes";
                  $distintivo='b';
                  break;
                case 4:
                  $div_tit="Cuarto Registro de Asistentes";
                  $distintivo='c';
                  break;
                case 5:                    
                  $div_tit="Total Beneficiarios";
                  $distintivo='d';
                  break;
            }

           if($tri!=5){
            $funcion_m="onblur='sumam_taller".$distintivo."();'";
            $funcion_h="onblur='sumah_taller".$distintivo."();'";
           }
            $campo_h1=$distintivo."pob_h_0_15";
            $campo_h2=$distintivo."pob_h_16_18";
            $campo_h3=$distintivo."pob_h_19_30";
            $campo_h5=$distintivo."pob_h_65";
            $campo_h6=$distintivo."total_h";

            $campo_m1=$distintivo."pob_m_0_15";
            $campo_m2=$distintivo."pob_m_16_18";
            $campo_m3=$distintivo."pob_m_19_30";
            $campo_m5=$distintivo."pob_m_65";
            $campo_m6=$distintivo."total_m";

            $campo_t=$distintivo."total";
            $buf="
                <table width='100%' align='center' border='0'>
                    <tr><th colspan='4' align='center'>".$div_tit."</th></tr>";
            if($tri==5)                    
            {
             $buf.="<tr><td colspan='4' align='rigth'><input type='button' name='recalcula' id='recalcula' value='Recalcular Promedios' onclick='Recalcula();' class='boton'></td></tr>";
            }
            $buf.="
                    <tr>
                        <td colspan='2' width='50%' align='left'>Hombres</td>
                        <td colspan='2' width='50%' align='left'>Mujeres</td>
                    <tr>
                        <td width='25%'>Poblaci&oacute;n 0 a 14 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$campo_h1."' id='".$campo_h1."' value='".($data[$campo_h1] + 0)."' ".$funcion_h." required=\"yes\">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[6])."</td>
                        <td width='25%'>Poblaci&oacute;n 0 -14 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$campo_m1."' id='".$campo_m1."' value='".($data[$campo_m1] + 0)."' ".$funcion_m." required=\"yes\">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[7])."</td>
                    </tr>
                    <tr>
                        <td width='25%'>Poblaci&oacute;n 15 - 29 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$campo_h2."' id='".$campo_h2."' value='".($data[$campo_h2] + 0)."' ".$funcion_h." required=\"yes\">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[8])."</td>
                        <td width='25%'>Poblaci&oacute;n 15 - 29 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$campo_m2."' id='".$campo_m2."' value='".($data[$campo_m2] + 0)."' ".$funcion_m." required=\"yes\">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[9])."</td>
                    </tr>
                    <tr>
                        <td width='25%'>Poblaci&oacute;n 30 - 59 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$campo_h3."' id='".$campo_h3."' value='".($data[$campo_h3] + 0)."' ".$funcion_h." required=\"yes\">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[10])."</td>
                        <td width='25%'>Poblaci&oacute;n 30 - 59 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$campo_m3."' id='".$campo_m3."' value='".($data[$campo_m3] + 0)."' ".$funcion_m." required=\"yes\">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[11])."</td>
                    </tr>
                    <tr>
                        <td width='25%'>Mayores de 60 A&ntilde;os</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$campo_h5."' id='".$campo_h5."' value='".($data[$campo_h5] + 0)."' ".$funcion_h." required=\"yes\">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[12])."</td>
                        <td width='25%'>M&aacute;s de 60</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$campo_m5."' id='".$campo_m5."' value='".($data[$campo_m5] + 0)."' ".$funcion_m." required=\"yes\">&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[13])."</td>
                    </tr>
                    <tr>
                        <td width='25%'>Total Hombres</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$campo_h6."' id='".$campo_h6."' value='".($data[$campo_h6] + 0)."' readonly>&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[14])."</td>
                        <td width='25%'>Total Mujeres</td>
                        <td width='25%'><input type='text' $this->stylesc  name='".$campo_m6."' id='".$campo_m6."' value='".($data[$campo_m6] + 0)."' readonly>&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[15])."</td>
                    </tr>
                    <tr>
                        <td colspan='4' align='center'>Total de Poblaci&oacute:n:&nbsp;&nbsp;
                        <input type='text' $this->stylesc  name='".$campo_t."' id='".$campo_t."' value='".($data[$campo_t] + 0)."' readonly>&nbsp;&nbsp;
                        ".$this->muestraAyuda($ayudas[16])."</td>
                    </tr>
                </table>";
            return $buf;
        }

        function Actualiza_Talleres($data)
        {
            $folio=0;
            $date=date("Y-m-d H:i:s");
            $bloqueado=$this->Regresa_Esta_Bloqueado($data['area_id'],$data['cat_programa_id'],$data['subprograma_id'],substr($data['fecha_inicio'],0,4), substr($data['fecha_inicio'],5,2));;
            if( $bloqueado== 0)
            {
                $nombre_taller=$this->Regresa_Nombre_Taller($data['taller_id']);
                $inser="UPDATE proyectos_talleres SET
                    area_id='".$data['area_id']."',
                    programa_id='".$data['programa_id']."',
                    taller_id='".$data['taller_id']."',
                    tipo_taller_id='".$data['tipo_taller_id']."',
                    nombre='".$nombre_taller."',
                    fecha_inicio='".$data['fecha_inicio']." 0000-00-00',
                    fecha_termina='".$data['fecha_termina']." 0000-00-00' where folio_id=".$data['folio_id'].";";

                if($this->db->sql_query($inser))
                {
                	$folio++;
                    $upda_asist="UPDATE proyectos_asistentes_talleres set
                    area_id='".$data['area_id']."',programa_id='".$data['programa_id']."',taller_id='".$data['taller_id']."',
                    pob_h_0_15 ='".$data['pob_h_0_15']."',pob_m_0_15='".$data['pob_m_0_15']."',
                    pob_h_16_18='".$data['pob_h_16_18']."',pob_m_16_18='".$data['pob_m_16_18']."',
                    pob_h_19_30='".$data['pob_h_19_30']."',pob_m_19_30='".$data['pob_m_19_30']."',
                    pob_h_31_64='".$data['pob_h_31_64']."',pob_m_31_64='".$data['pob_m_31_64']."',
                    pob_h_65   ='".$data['pob_h_65']."'   ,pob_m_65   ='".$data['pob_m_65']."',
                    total_h='".$data['total_h']."',total_m='".$data['total_m']."',total='".$data['total']."',
                    apob_h_0_15 ='".$data['apob_h_0_15']."',apob_m_0_15='".$data['apob_m_0_15']."',
                    apob_h_16_18='".$data['apob_h_16_18']."',apob_m_16_18='".$data['apob_m_16_18']."',
                    apob_h_19_30='".$data['apob_h_19_30']."',apob_m_19_30='".$data['apob_m_19_30']."',
                    apob_h_31_64='".$data['apob_h_31_64']."',apob_m_31_64='".$data['apob_m_31_64']."',
                    apob_h_65   ='".$data['apob_h_65']."'   ,apob_m_65   ='".$data['apob_m_65']."',
                    atotal_h='".$data['atotal_h']."',atotal_m='".$data['atotal_m']."',atotal='".$data['atotal']."',
                    bpob_h_0_15 ='".$data['bpob_h_0_15']."',bpob_m_0_15='".$data['bpob_m_0_15']."',
                    bpob_h_16_18='".$data['bpob_h_16_18']."',bpob_m_16_18='".$data['bpob_m_16_18']."',
                    bpob_h_19_30='".$data['bpob_h_19_30']."',bpob_m_19_30='".$data['bpob_m_19_30']."',
                    bpob_h_31_64='".$data['bpob_h_31_64']."',bpob_m_31_64='".$data['bpob_m_31_64']."',
                    bpob_h_65   ='".$data['bpob_h_65']."'   ,bpob_m_65   ='".$data['bpob_m_65']."',
                    btotal_h='".$data['btotal_h']."',btotal_m='".$data['btotal_m']."',btotal='".$data['btotal']."',
                    cpob_h_0_15 ='".$data['cpob_h_0_15']."' ,cpob_m_0_15='".$data['cpob_m_0_15']."',
                    cpob_h_16_18='".$data['cpob_h_16_18']."',cpob_m_16_18='".$data['cpob_m_16_18']."',
                    cpob_h_19_30='".$data['cpob_h_19_30']."',cpob_m_19_30='".$data['cpob_m_19_30']."',
                    cpob_h_31_64='".$data['cpob_h_31_64']."',cpob_m_31_64='".$data['cpob_m_31_64']."',
                    cpob_h_65   ='".$data['cpob_h_65']."'   ,cpob_m_65   ='".$data['cpob_m_65']."',
                    ctotal_h='".$data['ctotal_h']."',ctotal_m='".$data['ctotal_m']."',ctotal='".$data['ctotal']."',
                    fecha_alta='".$data['fecha_inicio']." 0000-00-00',trimestre_id='".$data['trimestre_id']."',dtotal='".$data['dtotal']."' WHERE folio_id=".$data['folio_id'].";";
                    $res_upda=$this->db->sql_query($upda_asist);
                }
            }
            else
            {
                $folio=-1;
            }
            return $folio;
        }

        function Delete_Taller($folio_id)
        {
            $reg="La actividad no se elimino";
            $del="DELETE FROM proyectos_talleres WHERE folio_id=".$folio_id.";";
            if($this->db->sql_query($del))
            {
                $reg="<font color='#800000'>Eliminado</font>";
            }
            return $reg;

        }
        function Elimina_Taller($area_id,$programa_id,$folio_id,$status)
        {
            $reg="La actividad no se elimino";
            $del="UPDATE proyectos_talleres SET status='".$status."' WHERE folio_id=".$folio_id.";";
            if($this->db->sql_query($del))
            {
                $reg="<font color='#800000'>".$status."</font>";
            }
            return $reg;
        }

        function regresa_subprograma($subprograma_id)
        {
            $nombre='';
            $qusprog="SELECT subprograma FROM cat_subprogramas WHERE subprograma_id=".$subprograma_id.";";
            $reqprog = $this->db->sql_query($qusprog);
            if( $this->db->sql_numrows($reqprog)> 0)
                $nombre=mysql_result($reqprog,0,0);
            return $nombre;
        }
    function Regresa_Esta_Bloqueado($area_id,$programa_id,$subprograma_id,$ano_id,$mes_id)
    {
        $area_id=$area_id + 0;
        $programa_id=$programa_id + 0;
        $subprograma_id=$subprograma_id + 0;
        $mes_id=$mes_id + 0;
        $reg=0;
        $sql="SELECT * FROM  cat_areas_bloquedas
              WHERE area_id = '".$area_id."' AND programa_id = ".$programa_id." AND
              subprograma_id=".$subprograma_id." AND mes_id='".$mes_id."' AND ano_id='".$ano_id."';";
        $res=$this->db->sql_query($sql,$db_connect) or die($sql);
        if($this->db->sql_numrows($res) > 0)
            $reg=1;

        return $reg;
    }
	function muestraAyuda($texto){
		return "&nbsp;&nbsp;<a href='#' class='ayudas' rel='popover' data-content='".$texto."' data-original-title='Ayuda SiSec'>&nbsp;?&nbsp;</a>";
    //   return "&nbsp;&nbsp;<button type=\"button\" style=\"padding-top:0px;width:15px;height:17px;font-size:8px;\" class=\"btn-mio ayudas\" id=\"example\" data-toggle=\"popover\" title=\"Ayuda Sisec\" data-content=\"".$texto."\" >?</button>";
	}
	function obtenFormato(){
		return $this->buffer;
	}
}
?>