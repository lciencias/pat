<?php
class Institucional {
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
		$this->buffer=$this->Formulario_Actividades_Institucional($this->data);
	}
	
	function Regresa_Ayudas(){
		$array=array();
	    $sql = "SELECT * FROM cat_ayudas_formato_interinstitucional limit 1";
		$res = $this->db->sql_query($sql) or die ("Error en la consulta: ".$sql);
		if($this->db->sql_numrows($res)>0)
	    {
			$array=$this->db->sql_fetchrow($res);
		}
        return $array;
	}

    function Formulario_Actividades_Institucional($data) {
		$ayudas=$this->Regresa_Ayudas();
        $programa_id=$data['cat_programa_id1'];
        $eje=$data['eje_tematico'];
        $buffer.="
		<input type='hidden' name='cat_programa_id1' id='cat_programa_id1'  value='".$data['cat_programa_id1']."'>
        <input type='hidden' name='subprograma_id' id='subprograma_id'  value='".( $data['subprograma_id']+ 0)."'>
		<input type='hidden' name='inv_area' id='inv_area'    value='".$data['inv_area']."'>
		<input type='hidden' name='altev' id='altev'    value='2'>
		<input type='hidden' name='eje_tematico' id='eje_tematico' value='".$eje."'>
                    <table width='95%' align='center' border='0'>
                    <tr>
                        <td>
                        <table width='100%' align='center' class='table'>
                        <tr>
                            <td>Fecha :&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type=\"text\" id=\"cal-field-1\" name=\"proy_fecha_inicio\" value=\"".$proy_fecha."\"  $this->stylesc/>
							<img src=\"imagenes/calendar.png\" id=\"cal-button-1\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
							".$this->muestraAyuda($ayudas[1])."
                            <script type=\"text/javascript\">
                                Calendar.setup({
                                    inputField    : 'cal-field-1',
                                    ifFormat      : '%Y-%m-%d',
                                    onUpdate      : revisa_fecha,
                                    showsTime     : true,
                                    button        : 'cal-button-1'
                                });
                          </script>
                          &nbsp;&nbsp;<div id='validacacionFecha'></div></td>
                        </tr>
                        <tr>
                            <td>T&iacute;tulo del tema:&nbsp;&nbsp;
                            ".$this->muestraAyuda($ayudas[2])."<br>
                            <input type='text' id='tema' name='tema' value='' $this->styles maxlength='60'>
                            </td>
                        </tr>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width='100%' align='center' class='table'>
                            <tr>
                                <td colspan='3' align='justify'>
                                Favor de Teclear su bit&aacute;cora:&nbsp;&nbsp;
                                ".$this->muestraAyuda($ayudas[2])."
                                <textarea id='bitacora' name='bitacora' $this->stylest></textarea><br><br>
                                </td>
                            </tr>
                            <tr>
                            <td width='100%'>
                                <table width='100%' align='center' border='0'>
                                <tr>
                                    <td width='33%'>
                                        Discurso de la Secretaria de Cultura:&nbsp;&nbsp;
                                        ".$this->muestraAyuda($ayudas[3])."<br>
                                        <textarea id='discurso_sec' name='discurso_sec' cols='30' rows='30'></textarea><br><br>
                                    </td>
                                    <td width='34%'>
                                        Discurso del Informe:&nbsp;&nbsp;
                                        ".$this->muestraAyuda($ayudas[4])."<br>
                                        <textarea id='discurso_inf' name='discurso_inf' cols='30' rows='30'></textarea><br><br>
                                    </td>
                                    <td width='33%'>
                                        Discurso Coyuntural:&nbsp;&nbsp;
                                        ".$this->muestraAyuda($ayudas[5])."<br>
                                        <textarea id='discurso_coy' name='discurso_coy' cols='30' rows='30'></textarea><br><br>
                                    </td>
                                </tr>
						        <tr>
						        <td>Relevancia&nbsp;&nbsp;&nbsp;".$this->muestraAyuda($ayudas[6])."</td>
						        <td colspan='2'>
						        &nbsp;Nivel 1&nbsp;<input type='radio' name='relevancia' id='relevancia' value='1'>
						        &nbsp;".$this->muestraAyuda($ayudas[7])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						        Nivel 2&nbsp;<input type='radio' name='relevancia' id='relevancia' value='2' >
								".$this->muestraAyuda($ayudas[8])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						        Omitir&nbsp;<input type='radio' name='relevancia' id='relevancia' value='0' checked>
								".$this->muestraAyuda($ayudas[9])."</td>
								</tr>
								<tr>
						            <td>Frecuencia</td>
						            <td><input type='text' name='frecuencia' id='frecuencia' value='1' $this->stylesc >&nbsp;".$this->muestraAyuda($ayudas[10])."</td>
								</tr>
                                </table>
                            </td>
                        </tr>
                        </table>
                        </td>
                    </tr>
					<tr><td class='thcenter' colspan='2'><center><button type='submit' class='boton' onclick='comprobar_campos_institucional();'>GRABAR DATOS</button><center></td></tr>
					
					</table>";
        return $buffer;
    }
    function Cuenta_Proyecto_Eje($campo,$ano_eje) {
        $regs=0;
        $sql_p="SELECT count(*) FROM `proyectos` WHERE substr(proy_fecha_inicio,1,4)='".$ano_eje."' and ".$campo."=1 AND proy_status<>'CANCELADA';";
        $res_p=$this->db->sql_query($sql_p);
        if($this->db->sql_numrows($res_p)>0) {
            $regs=mysql_result($res_p,0,0);
        }

        return "<font size='1'>( ".$regs." )";
    }
    function Revisa_Actividades($eje,$ano_eje) {
        $campo_eje='proy_eje_df_'.$eje;
        switch($eje) {
            case 1:
                $tit='Equidad de g&eacute;nero';
                break;
            case 2:
                $tit='Adultos mayores';
                break;
            case 3:
                $tit='Diversidad sexual';
                break;
            case 4:
                $tit='Recuperación de espacios p&uacute;blicos';
                break;
            case 5:
                $tit='Derechos humanos';
                break;
            case 6:
                $tit='Grupos ind&iacute;genas';
                break;
            case 7:
                $tit='Discapacidad';
                break;
            case 8:
                $tit='Ni&ntilde;ez';
                break;
            case 9:
                $tit='J&oacute;venes';
                break;
            case 10:
                $tit='Multiculturalidad';
                break;
            case 11:
                $tit='Equidad de g&eacute;nero';
                $campo_eje=1;
                break;


        }
        $c_proy_eje_df=$this->Cuenta_Proyecto_Eje($campo_eje,$ano_eje);
        $buf="<table width='100%' align='center' border='0'>
                <tr>
                <th class='tdverde' colspan=2>Ejes de pol&iacute;ticas sociales transversales en los cuales esta actividad incide</td>
		</tr>
		<tr>
		<th colspan=2>
		<table width='100%' cellpadding=\"2\" cellspacing=\"2\" border=\"0\">
                    <tr>
                    <td>
                    <input type='checkbox' value='1' name='proy_eje_df_1' title='' onClick=\"lanza_listado('2009','".$campo_eje."');\"/>".$tit."
                    &nbsp;&nbsp;&nbsp;&nbsp;".$c_proy_eje_df."
                    </td>
                    </tr>
		</table>";
        return $buf;
    }
    function Consulta_Actividades_Institucional($data,$array_programas,$path,$array_areas) {
        $user_id=$data['user_id'];
        $mes_id=$data['mesele'];
        $ano_id=$data['anoele'];
        $eje_tem=$data['eje_tematico'] + 0;
        $mes_id=str_pad($mes_id,2,'0',STR_PAD_LEFT);
        $tmp_mes=$data['mesele'] +0;
        $meses[0]='Anual';
        $meses[1]='Enero';
        $meses[2]='Febrero';
        $meses[3]='Marzo';
        $meses[4]='Abril';
        $meses[5]='Mayo';
        $meses[6]='Junio';
        $meses[7]='Julio';
        $meses[8]='Agosto';
        $meses[9]='Septiembre';
        $meses[10]='Octubre';
        $meses[11]='Noviembre';
        $meses[12]='Diciembre';

        $array_eje[0]='Equidad de g&eacute;nero';
        $array_eje[1]='Equidad de g&eacute;nero';
        $array_eje[2]='Adultos mayores';
        $array_eje[3]='Diversidad sexual';
        $array_eje[4]='Recuperaci&oacute;n de espacios p&uacute;blicos';
        $array_eje[5]='Derechos humanos';
        $array_eje[6]='Grupos ind&iacute;genas';
        $array_eje[7]='Discapacidad';
        $array_eje[8]='Ni&ntilde;ez';
        $array_eje[9]='J&oacute;venes';
        $array_eje[10]='Multiculturalidad';
        $array_eje[11]='Equidad de g&eacute;nero';

        $nom=$array_eje[$eje_tem];

        $buffer="No hay actividades registradas en el mes seleccionado";
        if($mes_id == 0)
            $filtro=" WHERE substr(fecha_alta,1,4)='".$ano_id."' AND area_id='".$data['inv_area']."' " ;
        else
            $filtro=" WHERE substr(fecha_alta,1,7)='".$ano_id."-".$mes_id."' AND area_id='".$data['inv_area']."' " ;
        if($data['cat_programa_id1']>0) {
            $filtro.=" AND programa_id = ".$data['cat_programa_id1']." ";
        }
        if($eje_tem>0)
        {
            $filtro.=" AND eje_tematico='".$nom."' ";
        }

        $sql_count="SELECT status,count(status) as total FROM proyectos_institucional ".$filtro." GROUP BY status ORDER BY status;";

        $res_count=$this->db->sql_query($sql_count);
        $num_count=$this->db->sql_numrows($res_count);
        if( $num_count > 0) {
            $buffer="<center>Actividades realizadas del &aacute;rea: <b>".$array_areas[$data['inv_area']]."</b>, año ".$data['anoele'].",  mes ".$meses[$tmp_mes]."</center>";

            $wid=round(100/$num_count);
            $buffer.="<table width='60%' border='0' align='center'>
					<tr>";
            while(list($status,$total) = $this->db->sql_fetchrow($res_count)) {
                $buffer.="<td width='".$wid."%'>".$status."   (".$total.")</td>";
            }
            $buffer.="</tr></table>";
        }
        $sql="SELECT folio_id,area_id,programa_id,timestamp,tema,status,eje_tematico,subprograma_id FROM proyectos_institucional ".$filtro." ORDER BY timestamp DESC";
        $res=$this->db->sql_query($sql);
        $num = $this->db->sql_numrows($res);
        if($num > 0) {
            $buffer.="<br><table width='100%' border='0' align='center' class='tablesorter'>";
            $buffer.="<thead>
                        <tr bgcolor='#002000'>
			<th width='6%' align='center'>Folio</th>
			<th width='18%' align='center'>Programa</th>
			<th width='38%' align='center'>Tema</th>
			<th width='15%' align='center'>Ult. Act.</th>
			<th width='14%'>Estatus</th>
			<th width='3%'  align='center'>A</th>
			<th width='3%'  align='center'>C</th>
                        <th width='5%'  align='center'></th>
			</tr></thead><tbody>";
            while(list($folio_id,$area_id,$programa_id,$timestamp,$tema,$status,$eje_tematico,$subprograma_id) = $this->db->sql_fetchrow($res))
			{
				$bloqueado=$this->Regresa_Esta_Bloqueado($area_id,$programa_id,$subprograma_id,substr($timestamp,0,4),substr($timestamp,5,2));
                $list='';
                $list=$status;
                switch($list) {
                    case 'INICIADO': {
                            $tmp_status_1=' SELECTED ';
                            $tmp_status_2='';
                            $tmp_status_3='';
                            $tmp_status_4='';
                            $tmp_status_5='';
                            $tmp_status_6='';
                            break;
                        }
                    case 'EN PROCESO': {
                            $tmp_status_2=' SELECTED ';
                            $tmp_status_1='';
                            $tmp_status_3='';
                            $tmp_status_4='';
                            $tmp_status_5='';
                            $tmp_status_6='';
                            break;
                        }
                    case 'TERMINADA': {
                            $tmp_status_3=' SELECTED ';
                            $tmp_status_2='';
                            $tmp_status_1='';
                            $tmp_status_4='';
                            $tmp_status_5='';
                            $tmp_status_6='';
                            break;
                        }
                    case 'CANCELADA': {
                            $tmp_status_4=' SELECTED ';
                            $tmp_status_1='';
                            $tmp_status_2='';
                            $tmp_status_3='';
                            $tmp_status_5='';
                            $tmp_status_6='';
                            break;
                        }
                    case 'DE BAJA': {
                            $tmp_status_5=' SELECTED ';
                            $tmp_status_2='';
                            $tmp_status_3='';
                            $tmp_status_4='';
                            $tmp_status_1='';
                            $tmp_status_6='';
                            break;
                        }
                    case 'SUSPENDIDA': {
                            $tmp_status_6=' SELECTED ';
                            $tmp_status_2='';
                            $tmp_status_3='';
                            $tmp_status_4='';
                            $tmp_status_1='';
                            $tmp_status_5='';
                            break;
                        }

                }
                $tmp=$area_id.$programa_id.$folio_id;
                $tmp_s="status".$tmp;
                $select_status="<form name='formu'><select name='".$tmp_s."' id='".$tmp_s."' class='status' onChange=\"elimina_institucional('$area_id','$programa_id','$folio_id','$tmp','$tmp_s');\">
					<option value=''></option>
                       <option value='INICIADO' ".$tmp_status_1.">INICIADO</option>
                       <option value='EN PROCESO' ".$tmp_status_2.">EN PROCESO</option>
                       <option value='TERMINADA' ".$tmp_status_3.">TERMINADA</option>
                       <option value='CANCELADA' ".$tmp_status_4.">CANCELADA</option>
                       <option value='DE BAJA' ".$tmp_status_5.">DE BAJA</option>
                        <option value='SUSPENDIDA' ".$tmp_status_6.">SUSPENDIDA</option>
                        </select></form>";

                $buffer.="<tr>
					<th align='left'>".str_pad($folio_id,5,'0',STR_PAD_LEFT)."</th>
					<th align='left'>".$array_programas[$programa_id]."<br>".$eje_tematico."</th>
					<th align='left'>".$tema."</th>
					<th align='left'>".$timestamp."</th>
					<th align='left'>".$select_status."</th>
					<th align='center'>";
				if($bloqueado == 0)
				{
					$buffer.="<a href=\"javascript:lanza_ventana_int('$area_id','$programa_id','$folio_id','$ano_id','$mes_id','1','$user_id');\"><img src='imagenes/vcard.png' width='16' height='16' border='0'></a>";
				}
				$buffer.="</th>
					<th align='center'><a href=\"javascript:lanza_ventana_int('$area_id','$programa_id','$folio_id','$ano_id','$mes_id','2','$user_id');\"><img src='imagenes/magnifier.png' width='16' height='16' border='0'></a></th>
                    <th align='center'><div id='".$tmp."'></div></th></tr>";
            }
            $buffer.="</tbody><thead><tr><th colspan='8' width='100%' align='center'>Total de Registros:  ".$num."</th></tr></thead></table>";
        }
        return $buffer;
    }


    function Inserta_Actividades_Institucional($data)
	{
        $folio=0;
        if($this->Regresa_Esta_Bloqueado($data['inv_area'],$data['cat_programa_id1'],$data['subprograma_id'],substr($data['proy_fecha_inicio'],0,4), substr($data['proy_fecha_inicio'],5,2)) == 0)
        {
            $array=array();
            $array_eje[0]='';
            $array_eje[1]='Equidad de g&eacute;nero';
            $array_eje[2]='Adultos mayores';
            $array_eje[3]='Diversidad sexual';
            $array_eje[4]='Recuperaci&oacute;n de espacios p&uacute;blicos';
            $array_eje[5]='Derechos humanos';
            $array_eje[6]='Grupos ind&iacute;genas';
            $array_eje[7]='Discapacidad';
            $array_eje[8]='Ni&ntilde;ez';
            $array_eje[9]='J&oacute;venes';
            $array_eje[10]='Multiculturalidad';
            $nom_eje=$array_eje[$data['eje_tematico']];

            $ins="INSERT INTO proyectos_institucional (area_id,programa_id,fecha_alta,tema,bitacora,discurso_secretaria,discurso_informe,discurso_coyuntural,status,relevancia,eje_tematico,frecuencia)
                  VALUES ('".$data['inv_area']."','".$data['cat_programa_id1']."','".$data['proy_fecha_inicio']."','".$data['tema']."','".$data['bitacora']."','".$data['discurso_sec']."','".$data['discurso_inf']."','".$data['discurso_coy']."','INICIADO','".$data['relevancia']."','".$nom_eje."','".$data['frecuencia']."');";
            $res=$this->db->sql_query($ins,$conn) ;
            $folio=mysql_insert_id();
            $array[0]=$folio;
            $array[1]=$data['eje_tematico'];
        }
        else
        {
            $array[0]=-1;
        }
        return $array;
    }

    function Actualiza_Actividades_Institucional($data) {
        $folio=0;
        $date=date("Y-m-d H:i:s");
        if($this->Regresa_Esta_Bloqueado($conn,$data['inv_area'],$data['cat_programa_id'],$data['subprograma_id'],substr($data['proy_fecha_inicio'],0,4), substr($data['proy_fecha_inicio'],5,2)) == 0)
        {
	        $ins="UPDATE proyectos_institucional
				SET tema='".$data['tema']."',bitacora='".$data['bitacora']."',discurso_secretaria='".$data['discurso_sec']."',discurso_informe='".$data['discurso_inf']."',discurso_coyuntural='".$data['discurso_coy']."',timestamp='".$date."',relevancia='".$data['relevancia']."',frecuencia='".$data['frecuencia']."',subprograma_id=".($data['subprograma_id'] + 0)."  WHERE folio_id=".$data['id'];

		    if($this->db->sql_query($ins,$conn) or die("Error:  ".$ins))
			    $folio++;
		}
		else
		{
			$folio = -1;
		}
        return $folio;
    }


    function Mostrar_Actividades_Institucional($data,$tipo_vista,$ano,$mes,$user_id) {
		$ayudas=$this->Regresa_Ayudas();
        $disabled='';
        if($tipo_vista == 2)
            $disabled=' disabled = disabled';
        $proy_fecha=date('Y-m-d');
        $buffer="<form name='form1' method='post' action='actualiza_eventos.php'>
					<input type='hidden' name='area_id' id='area_id' value='".$data['area_id']."'>
					<input type='hidden' name='inv_area' id='inv_area' value='".$data['area_id']."'>
					<input type='hidden' name='programa_id' id='programa_id' value='".$data['programa_id']."'>
                    <input type='hidden' name='subprograma_id' id='subprograma_id' value='".$data['subprograma_id']."'>
					<input type='hidden' name='cat_programa_id' id='cat_programa_id' value='".$data['programa_id']."'>
					<input type='hidden' name='id' id='id' value='".$data['folio_id']."'>
					<input type='hidden' name='ano' id='ano' value='".$ano."'>
					<input type='hidden' name='mes' id='mes' value='".$mes."'>
                    <table width='100%' align='center' border='0'>
                    <tr>
                        <td>
                        <table width='100%' align='center' border='1' bordercolor='#cdcdcd'>
                        <tr>
                            <td>Fecha :&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"text\" id=\"cal-field-1\" name=\"proy_fecha_inicio\" value=\"".substr($data['fecha_alta'],0,10)."\"  size=\"12\"/>
                            <button  id=\"cal-button-1\">SELECCIONE</button>
                            <a href=\"javascript:showDialog('AYUDA','".$ayudas[1]."','prompt');\">[?]</a>
                            <script type=\"text/javascript\">
                                Calendar.setup({
                                    inputField    : 'cal-field-1',
                                    ifFormat      : '%Y-%m-%d',
                                    onUpdate      : revisa_fecha_Act,
                                    showsTime     : true,
                                    button        : 'cal-button-1'
                                });
                          </script>
                          &nbsp;&nbsp;<div id='validacacionFecha'></div></td>
                        </tr>
                        <tr>
                            <td>T&iacute;tulo del tema:&nbsp;&nbsp;
                            <a href=\"javascript:showDialog('AYUDA','".$ayudas[2]."','prompt');\">[?]</a><br>
                            <input type='text' id='tema' name='tema' size='60' maxlength='60' value='".$data['tema']."' $disabled>
                            </td>
                        </tr>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width='100%' align='center' border='1' bordercolor='#cdcdcd'>
                            <tr>
                                <td colspan='3' align='justify'>
                                Favor de Teclear su bit&aacute;cora:&nbsp;&nbsp;
                                <a href=\"javascript:showDialog('AYUDA','".$ayudas[3]."','prompt');\">[?]</a><br>
                                <textarea id='bitacora' name='bitacora' cols='120' rows='10' $disabled>".$data['bitacora']."</textarea><br><br>
                                </td>
                            </tr>
                            <tr>
                            <td width='100%'>
                                <table width='100%' align='center' border='0'>
                                <tr>
                                    <td width='33%'>
                                        Discurso de la Secretaria de Cultura:&nbsp;&nbsp;
                                        <a href=\"javascript:showDialog('AYUDA','".$ayudas[4]."','prompt');\">[?]</a><br>
                                        <textarea id='discurso_sec' name='discurso_sec' cols='35' rows='30' $disabled>".$data['discurso_secretaria']."</textarea><br><br>
                                    </td>
                                    <td width='34%'>
                                        Discurso del Informe:&nbsp;&nbsp;
                                        <a href=\"javascript:showDialog('AYUDA','".$ayudas[5]."','prompt');\">[?]</a><br>
                                        <textarea id='discurso_inf' name='discurso_inf' cols='35' rows='30' $disabled>".$data['discurso_informe']."</textarea><br><br>
                                    </td>
                                    <td width='33%'>
                                        Discurso Coyuntural:&nbsp;&nbsp;
                                        <a href=\"javascript:showDialog('AYUDA','".$ayudas[6]."','prompt');\">[?]</a><br>
                                        <textarea id='discurso_coy' name='discurso_coy' cols='35' rows='30' $disabled>".$data['discurso_coyuntural']."</textarea><br><br>
                                    </td>
                                </tr>";
			        $tmp_r_1='';
			        $tmp_r_2='';
			        if($data['relevancia']== 1)
			            $tmp_r_1=' CHECKED ';
			        if($data['relevancia']== 2)
						$tmp_r_2=' CHECKED ';
			        if($data['relevancia']== 0)
			            $tmp_r_3=' CHECKED ';
			        $buffer.="
					        <tr>
							    <td>Relevancia</td>
								<td colspan='2'>
						        &nbsp;Nivel 1&nbsp;<input type='radio' name='relevancia' id='relevancia' value='1' ".$tmp_r_1.">
								&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[7]."','prompt');\">[?]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							    Nivel 2&nbsp;<input type='radio' name='relevancia' id='relevancia' value='2' ".$tmp_r_2.">
								<a href=\"javascript:showDialog('AYUDA','".$ayudas[8]."','prompt');\">[?]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							    Omitir&nbsp;<input type='radio' name='relevancia' id='relevancia' value='0' ".$tmp_r_3.">
								<a href=\"javascript:showDialog('AYUDA','".$ayudas[9]."','prompt');\">[?]</a>
								</td>
						        </tr>
								<tr>
					            <td>Frecuencia</td>
					            <td><input type='text' name='frecuencia' id='frecuencia' value='".$data['frecuencia']."' size='5'>&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[10]."','prompt');\">[?]</a>
					            </td></tr>
                                </table>
                            </td>
                        </tr>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        '<td align='center'>";
        if($tipo_vista==1)
            $buffer.="<input type='submit' name='boton' value='GRABAR DATOS'>&nbsp;&nbsp;";
        $buffer.="<input type='button' name='cerrar' value='Cerrar Ventana' onclick='self.close();'></td>
                    </tr>
                    </table></form>";
        return $buffer;
    }

    function Elimina_Actividades_Institucional($area_id,$programa_id,$folio_id,$status) {
        $reg="La actividad no se elimino";
        $del="UPDATE proyectos_institucional SET status='".$status."' WHERE folio_id=".$folio_id.";";
        if($this->db->sql_query($del)) {
            $reg="<font color='#800000'>".$status."</font>";
        }
        return $reg;
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