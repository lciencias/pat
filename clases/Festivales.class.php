<?php
class Festivales {
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
		$this->buffer=$this->Formulario_Festivales($this->data,$folio_id);
	}
	
    function Regresa_Proy_Poblacion($proy_poblacion_asis,$proy_asis_1,$proy_asis_2,$proy_poblacion_act,$proy_act_1,$proy_act_2,$ayudas) 
	{
        if($proy_poblacion_asis=='') {
            $proy_poblacion_asis='0';
        }
        $dproy_poblacion="<input type=\"text\" value=\"$proy_poblacion_asis\" $this->stylesc maxlength=\"10\" name=\"proy_poblacion_asis\" id=\"proy_poblacion_asis\" disabled>
		".$this->muestraAyuda($ayudas[9]);

        if($proy_asis_1=='') {
            $proy_asis_1='0';
        }
        $dproy_asis_1="<input type=\"text\" value=\"$proy_asis_1\" $this->stylesc size=\"10\" maxlength=\"10\" name=\"proy_asis_1\" required=\"yes\" authtype=\"_entero\" onBlur=\"suma_asis()\">
        ".$this->muestraAyuda($ayudas[7]);

        if($proy_asis_2=='') {
            $proy_asis_2='0';
        }
        $dproy_asis_2="<input type=\"text\" value=\"$proy_asis_2\" $this->stylesc maxlength=\"10\" name=\"proy_asis_2\" required=\"yes\" authtype=\"_entero\" onBlur=\"suma_asis();\">
        ".$this->muestraAyuda($ayudas[8]);

        
        if($proy_poblacion_act=='') {
            $proy_poblacion_act='0';
        }
		$dproy_poblacion_act="<input type=\"text\" value=\"$proy_poblacion_act\" $this->stylesc maxlength=\"10\" name=\"proy_poblacion_act\" id=\"proy_poblacion_act\" disabled>
        ".$this->muestraAyuda($ayudas[6]);

        if($proy_act_1=='') {
            $proy_act_1='0';
        }
        $dproy_act_1="<input type=\"text\" value=\"$proy_act_1\" $this->stylesc maxlength=\"10\" name=\"proy_act_1\" required=\"yes\" authtype=\"_entero\" onBlur=\"suma_acti();\">
        ".$this->muestraAyuda($ayudas[4]);

        if($proy_act_2=='') {
            $proy_act_2='0';
        }
        $dproy_act_2="<input type=\"text\" value=\"$proy_act_2\" $this->stylesc maxlength=\"10\" name=\"proy_act_2\" required=\"yes\" authtype=\"_entero\" onBlur=\"suma_acti();\">
        ".$this->muestraAyuda($ayudas[5]);

        $buf="<table width='100%' class='table' cellpadding=\"2\" cellspacing=\"2\" border=\"0\">
                <tr>
                    <td width='33%'>Actividades del evento/festival</td>
                    <td width='34%'>Actividades itineradas del evento/festival</td>
                    <td width='33%'>Total de actividades del evento/festival</td>
                </tr>
                <tr>
                    <td>".$dproy_act_1."</td>
                    <td>".$dproy_act_2."</td>
                    <td>".$dproy_poblacion_act."</td>
                </tr>
                <tr><td colspan='3' hegith='40'>&nbsp;</td></tr>

                <tr>
                    <td>Asistentes del evento/festival</td>
                    <td>Asistentes itineradas del evento/festival</td>
                    <td>Total de asistentes del evento/festival</td>
                </tr>
                <tr>
                    <td>".$dproy_asis_1."</td>
                    <td>".$dproy_asis_2."</td>
                    <td>".$dproy_poblacion."</td>
                </tr>
            </table>";
        return $buf;
    }

	function Regresa_Ayudas(){
		$array=array();
	    $sql = "SELECT * FROM cat_ayudas_formato_festivales limit 1";
		$res = $this->db->sql_query($sql) or die ("Error en la consulta: ".$sql);
		if($this->db->sql_numrows($res)>0)
	    {
			$array=$this->db->sql_fetchrow($res);
		}
        return $array;
	}

    function Formulario_Festivales($data,$folio_id)
	{
		$ayudas=$this->Regresa_Ayudas();
        $area_id=$data['inv_area'];
        $programa_id=$data['cat_programa_id1'];
        $subprograma_id=$data['subprograma_id'];
        $array=array();
        if($folio_id > 0)
		{
            $data=$this->regresa_registro($folio_id);
            $area_id=$data['area_id'];
            $programa_id=$data['programa_id'];
        }

        $buffer="
        <input type='hidden' name='subprograma_id' id='subprograma_id'  value='".($data['subprograma_id'] + 0)."'>
		<input type='hidden' name='cat_programa_id1' id='cat_programa_id1'  value='".$data['cat_programa_id1']."'>
		<input type='hidden' name='inv_area' id='inv_area'    value='".$data['inv_area']."'>
		<input type='hidden' name='altev' id='altev'    value='2'>";
        $buffer.="<table width='100%' align='center' border='0' class='table'>
	    <tr>
			<td>Tema</td>
            <td><input type='text' name='proy_tema' id='proy_tema' value='".$data['proy_tema']."' required=\"yes\" $this->styles >
            ".$this->muestraAyuda($ayudas[1])."</td>
            </tr>

		<tr><td>Fecha y hora de inicio</td>
			<td>
				<input type=\"text\" id=\"cal-field-1\" name=\"proy_fecha_inicio\" value=\"".$data['proy_fecha_inicio']."\" required=\"yes\" $this->stylesc/>
                <button  id=\"cal-button-1\">SELECCIONE</button>
                ".$this->muestraAyuda($ayudas[2])."
                <script type='text/javascript'>
					Calendar.setup({
						inputField    : 'cal-field-1',
                        ifFormat      : '%Y-%m-%d %H:%M:00',
                        onUpdate      : revisa_fecha,
                        showsTime     : true,
                        button        : 'cal-button-1'});
				</script>
			&nbsp;&nbsp;<div id='validacacionFecha'></div></td>
		</tr>
	    <tr>
			<td>Descripci&oacute;n</td>
            <td><textarea $this->stylest wrap=\"ON\" name=\"proy_descripcion\">".$data['proy_descripcion']."</textarea>
            ".$this->muestraAyuda($ayudas[3])."</td>
            </tr>
			<tr>
				<th class=\"thcenter\" colspan=2> Total de actividades del Evento</td>
            </tr>
            <tr>
            <th colspan=2>".$this->Regresa_Proy_Poblacion($data['proy_poblacion_asis'],$data['proy_asis_1 	'],$data['proy_asis_2'],$data['proy_poblacion_act'],$data['proy_act_1'],$data['proy_act_2'],$ayudas);
        $buffer.="</th>
			</tr>
			<tr><th class='thcenter' colspan='2'><center><button type='submit' class='boton'>GRABAR DATOS</button><center></th></tr>
        </form>
        </table>";
        return $buffer;
    }

    function Inserta_Festivales($data) {
        $folio=0;
        if($this->Regresa_Esta_Bloqueado($conn,$data['inv_area'],$data['cat_programa_id1'],$data['subprograma_id'],substr($data['proy_fecha_inicio'],0,4), substr($data['proy_fecha_inicio'],5,2)) == 0)
        {
            $campos='';
            $valores='';
            $date=date("Y-m-d H:i:s");
            $ins="INSERT INTO proyectos_festivales ";
            $total_act=$data['proy_act_1'] + $data['proy_act_2'];
            $total_asis=$data['proy_asis_1'] + $data['proy_asis_2'];
            foreach($data as $campo => $valor) {
                if( ($campo!='user_id') && ($campo!='aplicacion') && ($campo!='apli_com') && ($campo!='altev') && ($campo!='proy_coordinacion_id') && ($campo!='proy_area_id') && ($campo!='ficha') && ($campo!='eje_tematico')) {
                    if($campo=='cat_programa_id1')
                        $campo='programa_id';
                    if($campo=='inv_area')
                        $campo='area_id';

                    $campos.=$campo.",";
                    $valores.="'".$valor."',";
                }
            }
            $campos.="proy_poblacion_act,proy_poblacion_asis,proy_status,timestamp";
            $valores.="'".$total_act."','".$total_asis."','INICIADO','".date('Y-m-d H:i:s')."' ";
            $sql=$ins."(".$campos.") VALUES (".$valores.");";
            if($this->db->sql_query($sql)) {
                $folio = $this->Calcula_Maximo();
            }
        }
        else
        {
            $folio=-1;
        }
        return $folio;
    }

	function Calcula_Maximo()
	{
		$max=0;
		$sql="SELECT MAX(folio_id) FROM proyectos_festivales ;";
		$res=$this->db->sql_query($sql);
		if($this->db->sql_numrows($res)>0)
		{
			$max=mysql_result($res,0,0);
		}
		return $max;
	}

    /**** funciones para los catalogos  *****/
    /**
     * Metodo que regresa los datos de la actividad del folio
     * @param int $db conexion a la bd
     * @param int $folio_id no de registro
     * @return array datos del registro
     */
    function regresa_registro($folio_id) {
        $data=array();
        $sql="SELECT * FROM proyectos WHERE proy_id=".$folio_id.";";
        $res=$this->db->sql_query($sql);
        if($this->db->sql_numrows($res) > 0) {
            $data= $this->db->sql_fetchrow($res);
        }
        return $data;
    }

    function regresa_subprograma($subprograma_id) {
        $nombre='';
        $qusprog="SELECT subprograma FROM cat_subprogramas WHERE subprograma_id=".$subprograma_id.";";
        $reqprog = $this->db->sql_query($qusprog);
        if( $this->db->sql_numrows($reqprog)> 0)
            $nombre=mysql_result($reqprog,0,0);
        return $nombre;
    }


    function Consulta_Festivales($data,$array_programas,$path_sys,$array_areas) {
        $user_id=$data['user_id'];
        $mes_id=$data['mesele'];
        $subprograma_id=$data['subprograma_id'];
        
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
        $ano_id=$data['anoele'];
        $buffer="No hay actividades registradas en el mes seleccionado";

        if($mes_id == 0)
            $filtro=" WHERE substr(proy_fecha_inicio,1,4)='".$ano_id."' AND area_id='".$data['inv_area']."' ".$filtro ;
        else
            $filtro=" WHERE substr(proy_fecha_inicio,1,7)='".$ano_id."-".$mes_id."' AND area_id='".$data['inv_area']."' ".$filtro ;

        if($data['cat_programa_id1']>0) {
            $filtro.=" AND programa_id = ".$data['cat_programa_id1']." ";
        }
        if($subprograma_id > 0){
            $filtro.=" AND subprograma_id = ".$subprograma_id." ";
        }

        $sql_count="SELECT proy_status,count(proy_status) as total FROM proyectos_festivales ".$filtro." GROUP BY proy_status ORDER BY proy_status;"; $res_count=$this->db->sql_query($sql_count);
        $num_count=$this->db->sql_numrows($res_count);
        if( $num_count > 0) 
		{
            $buffer="<center>Actividades realizadas del &aacute;rea: <b>".$array_areas[$data['inv_area']]."</b>, año ".$data['anoele'].",  mes ".$meses[$tmp_mes]."</center>";
            $wid=round(100/$num_count);
            $buffer.="<table width='60%' border='0' align='center'>
					<tr>";
            while(list($status,$total) = $this->db->sql_fetchrow($res_count))
			{
                $buffer.="<td width='".$wid."%'>".$status."   (".$total.")</td>";
            }
            $buffer.="</tr></table>";
        }

        $sql="SELECT folio_id,proy_tema,area_id,programa_id,proy_fecha_inicio,proy_status,proy_poblacion_act,proy_poblacion_asis,subprograma_id FROM proyectos_festivales ".$filtro." ORDER BY proy_fecha_inicio";
        $res=$this->db->sql_query($sql);
        $num = $this->db->sql_numrows($res);
        if($num > 0) {
            $buffer.="<br><table width='100%' border='0' align='center' class='tablesorter'>";
            $buffer.="<thead>
						<tr bgcolor='#002000'>
						<th width='7%' >Folio</th>
						<th width='17%' >Programa</th>
						<th width='37%' >Nombre</th>
						<th width='10%' >Fecha de Alta</th>
						<th width='13%' >Estatus</th>
						<th width=' 5%' align='center'>PB</th>
						<th width=' 3%' align='center'>A</th>
						<th width=' 3%' align='center'>C</th>
                        <th width=' 5%' align='center'></th></tr>
						</thead><tbody>";
            while(list($proy_id,$proy_nombre,$area_id,$cat_programa_id,$proy_fecha_inicio,$proy_status,$total_h,$total_m,$subprograma_id) = $this->db->sql_fetchrow($res)) {
                $bloqueado=$this->Regresa_Esta_Bloqueado($area_id,$programa_id,$subprograma_id,substr($timestamp,0,4),substr($timestamp,5,2));
                switch($proy_status) {
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
                $tmp=$area_id.$cat_programa_id.$proy_id;
                $tmp_s="status".$tmp;
                $select_status="<form name='formu'><select name='".$tmp_s."' id='".$tmp_s."' class='status' onChange=\"elimina_institucional('$area_id','$cat_programa_id','$proy_id','$tmp','$tmp_s');\">
						<option value=''></option>
                        <option value='INICIADO' ".$tmp_status_1.">INICIADO</option>
                        <option value='EN PROCESO' ".$tmp_status_2.">EN PROCESO</option>
                        <option value='TERMINADA' ".$tmp_status_3.">TERMINADA</option>
                        <option value='CANCELADA' ".$tmp_status_4.">CANCELADA</option>
                        <option value='DE BAJA' ".$tmp_status_5.">DE BAJA</option>
                        <option value='SUSPENDIDA' ".$tmp_status_6.">SUSPENDIDA</option></select></form>";

                $buffer.="<tr>
                            <th align='left'>".$proy_id."</th>
							<th align='left'>".$array_programas[$cat_programa_id]."</th>
							<th align='left'>".strtoupper(trim($proy_nombre))."</th>
							<th>".substr($proy_fecha_inicio,0,10)."</th><th>".$select_status."</th>
                            <th>".($total_m)."</th>
							<th align='center'>&nbsp;";
				if($bloqueado == 0)
				{
                    $buffer.="<a href=\"javascript:lanza_ventana('$area_id','$cat_programa_id','$proy_id','$ano_id','$mes_id','1','$user_id');\"><img src='imagenes/vcard.png' width='16' height='16' border='0'></a>";
                }
                $buffer.="</th>
							<th align='center'><a href=\"javascript:lanza_ventana('$area_id','$cat_programa_id','$proy_id','$ano_id','$mes_id','2','$user_id');\"><img src='imagenes/magnifier.png' width='16' height='16' border='0'></a></th>
						    <th align='center'><div id='".$tmp."'></div></th></tr>";
            }
            $buffer.="</tbody><thead><tr><td colspan='9' width='100%' align='center'>Total de Registros:  ".$num."</td></tr></thead></table>";
        }
        return $buffer;
    }

    function Elimina_Festivales($area_id,$programa_id,$folio_id,$status) {
        $reg="La actividad no se elimino";
        $del="UPDATE proyectos_festivales SET proy_status='".$status."' WHERE folio_id=".$folio_id.";";
        if($this->db->sql_query($del)) {
            $reg="<font color='#800000'>".$status."</font>";
        }
        return $reg;
    }


    function Mostrar_Festivales($data,$tipo_vista,$ano,$mes,$user_id) {
		$ayudas=$this->Regresa_Ayudas();		
        $disabled='';
        if($tipo_vista == 2)
            $disabled=' disabled = disabled';
        $proy_fecha=date('Y-m-d');
        $area_id=$data['area_id'];
        $programa_id=$data['programa_id'];

        $buffer="
					<form name='form1' method='post' action='actualiza_eventos.php' >
					<input type='hidden' name='area_id' id='area_id' value='".$data['area_id']."'>
                    <input type='hidden' name='inv_area' id='inv_area' value='".$data['area_id']."'>
					<input type='hidden' name='cat_programa_id' id='cat_programa_id' value='".$data['programa_id']."'>
					<input type='hidden' name='subprograma_id' id='subprograma_id' value='".$data['subprograma_id']."'>
					<input type='hidden' name='ano' id='ano' value='".$ano."'>
					<input type='hidden' name='mes' id='mes' value='".$mes."'>
					<input type='hidden' name='user_id' id='user_id' value='".$user_id."'>";
        $buffer.='
			<input type="hidden"  name="folio_id" id="folio_id" value="'.$data['folio_id'].'">
			<table width="100%" align="center" border="0">';
        $buffer.="
	    <tr>
			<td>Tema</td>
            <td>
            <input type='text' name='proy_tema' id='proy_tema' value='".$data['proy_tema']."' size='60'>
            <a href=\"javascript:showDialog('AYUDA','".$ayudas[1]."','prompt');\">[?]</a></td>
            </tr>
		<tr><td>Fecha y hora de inicio</td>
			<td>
				<input type=\"text\" id=\"cal-field-1\" name=\"proy_fecha_inicio\" value=\"".$data['proy_fecha_inicio']."\" required=\"yes\"/>
                <button  id=\"cal-button-1\">SELECCIONE</button>
                <a href=\"javascript:showDialog('AYUDA','".$ayudas[2]."','prompt');\">[?]</a>
                <script type='text/javascript'>
					Calendar.setup({
						inputField    : 'cal-field-1',
                        ifFormat      : '%Y-%m-%d %H:%M:00',
                        onUpdate      : revisa_fecha_Act,
                        showsTime     : true,
                        button        : 'cal-button-1'});
				</script>
			&nbsp;&nbsp;<div id='validacacionFecha'></div></td>
		</tr>
	    <tr>
			<td>Descripci&oacute;n</td>
            <td><textarea cols=\"60\"   rows=\"4\" wrap=\"ON\" name=\"proy_descripcion\">".$data['proy_descripcion']."</textarea>
            <a href=\"javascript:showDialog('AYUDA','".$ayudas[3]."','prompt');\">[?]</a></td>
            </tr>
			<tr>
				<th class=\"thcenter\" colspan=2>Estad&iacute;stica de poblaci&oacute;n beneficiada por esta actividad </td>
            </tr>
            <tr>
            <th colspan=2>";
			$buffer.=$this->Regresa_Proy_Poblacion($data['proy_poblacion_asis'],$data['proy_asis_1'],$data['proy_asis_2'],$data['proy_poblacion_act'],$data['proy_act_1'],$data['proy_act_2'],$ayudas);
        $buffer.="</th>
			</tr>
	        <tr>
	        <th class=\"thcenter\" colspan=2>";
        if($tipo_vista==1)
            $buffer.="<input type=\"submit\" name=\"boton\" value=\"GRABAR DATOS\" >&nbsp;&nbsp;";
        $buffer.="<input type='button' name='cerrar' value='Cerrar Ventana' onclick='self.close();'></td>
		    </tr>
	        </table></form>";
        return $buffer;
    }

    function Actualiza_Festivales($data) {

        $folio=0;
        if($this->Regresa_Esta_Bloqueado($data['inv_area'],$data['cat_programa_id'],$data['subprograma_id'],substr($data['proy_fecha_inicio'],0,4), substr($data['proy_fecha_inicio'],5,2)) == 0)
        {
            $campos='';
            $date=date("Y-m-d H:i:s");
    		$total_act=$data['proy_act_1'] + $data['proy_act_2'];
    		$total_asis=$data['proy_asis_1'] + $data['proy_asis_2'];

            $ins="UPDATE proyectos_festivales SET  ";
            foreach($_POST as $campo => $valor) {
                if( ($campo!='user_id') && ($campo!='aplicacion') && ($campo!='apli_com') && ($campo!='altev') && ($campo!='cat_programa_id') && ($campo!='area_id') && ($campo!='folio_id') && ($campo!='ano') && ($campo!='mes') && ($campo!='boton') && ($campo!='inv_area')) {
                    $campos.=$campo."='".$valor."',";
                }
            }
            $campos.="proy_poblacion_act='".$total_act."',proy_poblacion_asis='".$total_asis."',timestamp='".date('Y-m-d H:i:s')."'  where folio_id='".$data['folio_id']."';";
            $ins.=$campos;
            if($this->db->sql_query($ins))
            {
                $folio++;
            }
        }
        else
        {
            $folio = -1;
        }

        return $folio;
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
        $res=$this->db->sql_query($sql) or die($sql);
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