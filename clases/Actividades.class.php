<?php
class Actividades {
  var $db;
  var $data;
  var $buffer;
  var $styles;
  var $stylesc;
  var $stylesm;
  var $stylest;
  
  function __construct($db,$data){
    $this->db    = $db;
    $this->data  = $data;
    $this->buffer= "";
    $this->styles="class='form-control' style='height: 30px;width:380px;' ";
    $this->stylesm=" style='height: 30px;width:380px;' ";
    $this->stylest=" style='height:130px;width:380px;' ";
    $this->stylesc="class='form-control' style='height: 30px;width:140px;' ";
    $this->buffer=$this->Formulario_Actividades($this->data,0);
  }
  
  function Formulario_Actividades($data,$folio_id)
  {
    $ayudas=$this->Regresa_Ayudas();
    $area_id=$data['inv_area'];
    $programa_id=$data['cat_programa_id1'];
    $subprograma_id=$data['subprograma_id'];
    if($data['inv_area'] == 4)
    {
      $data['proy_lug_evento']=$this->Regresa_Direccion_Programa($programa_id);
    }
    $array=array();
    if($folio_id > 0) {
      $data=$this->regresa_registro($folio_id);
      $area_id=$data['area_id'];
      $programa_id=$data['programa_id'];
    }

    $buffer="
      <input type='hidden' name='subprograma_id' id='subprograma_id'  value='".($data['subprograma_id'] + 0)."'>
      <input type='hidden' name='cat_programa_id1' id='cat_programa_id1'  value='".$data['cat_programa_id1']."'>
	  <input type='hidden' name='inv_area' id='inv_area'    value='".$data['inv_area']."'>
	  <input type='hidden' name='eje_tematico' id='eje_tematico'    value='".$data['eje_tematico']."'>
	  <input type='hidden' name='altev' id='altev'    value='2'>";
    $buffer.='<table width="100%" align="center" border="0" class="table" >';
    if($subprograma_id > 0) {
      $buffer.="<tr><td>Sub programa</td><td>";
      $buffer.=$this->regresa_subprograma($subprograma_id);
      $buffer.="</td></tr>";
    }

    if( ($area_id == 2 ) && ($programa_id == 11)) {
      $buffer.="<tr><td>Seleccione el tianguis</td><td>";
      $buffer.=$this->regresa_tianguis($data['tianguis_id']);
      $buffer.="</td></tr>";
    }
    $buffer.="
            <tr>
            <td>Nombre de la actividad</td>
            <td><input type=\"text\" required=\"yes\" $this->styles value=\"".$data['proy_nombre']."\" maxlength=\"200\"  name=\"proy_nombre\"  >
            ".$this->muestraAyuda($ayudas[1])."
            </td>
            </tr>
            <tr>
            <td>Descripci&oacute;n</td>
            <td><textarea $this->stylest  wrap=\"ON\" name=\"proy_descripcion\">".$data['proy_descripcion']."</textarea>".$this->muestraAyuda($ayudas[2])."</td>
            </tr>

            <tr>
            <td>Persona responsable</td>
            <td>";
    $buffer.=$this->Regresa_Responsables($area_id,$programa_id,$data['proy_responsable'],$ayudas);
    $buffer.="</td>
            </tr>
            <tr>
                <td>Programa Meta</td>
                <td>";
    $buffer.=$this->Regresa_Programa($area_id,$programa_id,$data['proy_programa'],$ayudas);
    $buffer.="</td>
                  </tr>
             	  <tr><td>Fecha y hora de inicio</td>
                  <td>
                    <input type=\"text\" id=\"cal-field-1\" name=\"proy_fecha_inicio\" value=\"".$data['proy_fecha_inicio']."\"  required=\"yes\"/>
                    <img src=\"imagenes/calendar.png\" id=\"cal-button-1\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
                    ".$this->muestraAyuda($ayudas[5])."
                      <script type='text/javascript'>
                        /*Calendar.setup({
                            inputField    : 'cal-field-1',
                            ifFormat      : '%Y-%m-%d %H:%M:00',
                            onUpdate      : revisa_fecha,
                            showsTime     : true,
                            button        : 'cal-button-1'});*/
                       </script>
                    &nbsp;&nbsp;<div id='validacacionFecha'></div></td>
                    </tr>
                    <tr>
                    <td>Fecha y hora de t&eacute;rmino</td>
                    <td>
                        <input type=\"text\" id=\"cal-field-2\"  name=\"proy_fecha_termino\" value=\"".$data['proy_fecha_termino']."\" required=\"yes\"/>
                        <img src=\"imagenes/calendar.png\" id=\"cal-button-2\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
                         ".$this->muestraAyuda($ayudas[6])."
                          <script type='text/javascript'>
                           /* Calendar.setup({
                            inputField    : 'cal-field-2',
                            ifFormat      : '%Y-%m-%d %H:%M:00',
                            onUpdate      : revisa_fecha_final,
                            showsTime     : true,
                            button        : 'cal-button-2'});*/
                         </script>
                    &nbsp;&nbsp;<div id='validacacionFechaFin'></div></td>
                    </td>
                    </tr>
                  <tr>
                        <td>Tipo de recinto</td>
                        <td>";
	$buffer.=$this->Regresa_Reciento($data['proy_recinto'],$area_id,$programa_id,1,$ayudas);
    $buffer.="</td>
                  </tr>		
					<tr>
                <td>Lugar de la actividad<br>(domicilio completo)</td>
                <td><textarea $this->stylest wrap=\"ON\"name=\"proy_lug_evento\" >".$data['proy_lug_evento']."</textarea>
                ".$this->muestraAyuda($ayudas[8])."</td>
              </tr>
              <tr>
                <td>Delegaci&oacute;n</td>
                <td>";
    $buffer.=$this->Regresa_Delegacion($data['proy_delegacio'],$area_id,$programa_id,$ayudas);
    $buffer.="</td>
              </tr><tr>
                <td>Tipo de gesti&oacute;n</td>
                <td>";
    $buffer.=$this->Regresa_Gestion($data['proy_gestion'],$ayudas);
    $buffer.="</td>
               </tr>";
    if($area_id==7)
    {
      $buffer.="
                <tr>
                <td>Apoyos</td>
                <td>";
      $buffer.=$this->Regresa_Apoyos($data['apoyo_prd'],$data['apoyo_log'],$data['apoyo_pro'],$data['apoyo_art'],$data['apoyo_eco'],$ayudas);
      $buffer.="</td>
                    </tr>";
    }
    $buffer.="<tr>
                    <td>Tipo de actividad </td>
                    <td>";
    $buffer.=$this->Regresa_Evento($data['proy_tevento'],$ayudas);
    $buffer.="</td>
                  </tr>
                  <tr>
                        <td>Observaciones</td>
                        <td><textarea $this->stylest wrap=\"ON\" name=\"proy_nota\">".$data['proy_nota']."</textarea>
                        ".$this->muestraAyuda($ayudas[13])."</td>
                  </tr>
                  <tr>
                        <td>Recaudaci&oacute;n</td>
                        <td>".$this->Regresa_Recaudacion($data['proy_recauda_evento'],$ayudas)."</td>
                  </tr>
                  <tr>
                        <td>Monto de recaudaci&oacute;n</td>
                        <td>$<input type=\"text\" value=\"".$data['proy_recauda_monto']."\" $this->stylesc maxlength=\"10\" name=\"proy_recauda_monto\">pesos-M.N.
                        ".$this->muestraAyuda($ayudas[15])."</td>
                  </tr>
                  <tr>
                        <th class=\"thcenter\" colspan=\"2\">Estad&iacute;stica de poblaci&oacute;n beneficiada por esta actividad </td>
                        </tr>
                        <tr>
                        <td>Tipo de p&uacute;blico al cual se dirige el evento </td>
                        <td>";
    $buffer.=$this->Regresa_Clasif_Publi($data['proy_clasipubli'],$ayudas);
    $buffer.="</td>
                        </tr>
                		<tr>
                            <td>Frecuencia</td>
                            <td><input type='text' name='frecuencia' id='frecuencia' value='1' size='5'>&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[17]."','prompt');\">[?]</a></td>
                        </tr>
                        <tr>
                        <th colspan=2>";
    $buffer.=$this->Regresa_Proy_Poblacion($data['db'],$data['proy_poblacion'],$data['proy_asis_m0_15'],$data['proy_asis_m16_18'],$data['proy_asis_m19_30'],$data['proy_asis_m31_64'],$data['proy_asis_m_my65'],$data['proy_asis_cap_esp_m'],$data['proy_asis_m_indig'],$data['proy_asis_m_total'],$data['proy_asis_h0_15'],$data['proy_asis_h16_18'],$data['proy_asis_h19_30'],$data['proy_asis_h31_64'],$data['proy_asis_h_my65'],$data['proy_asis_cap_esp_h'],$data['proy_asis_h_indig'],$data['proy_asis_h_total'],$ayudas);
    $buffer.="</th>
                        </tr>";
    $buffer.="<tr>
           <th class='thcenter' colspan=2>Disciplinas y &aacute;mbitos tem&aacute;ticos en las cuales incide la actividad (solo de aplicar)</td>
            </tr>
            <tr>
            <th colspan=2>";
    $buffer.=$this->Temas($data['proy_tema_1'],$data['proy_tema_2'],$data['proy_tema_3'],$data['proy_tema_4'],$data['proy_tema_5'],$data['proy_tema_6'],$data['proy_tema_7'],$data['proy_tema_8'],$data['proy_tema_9'],$data['proy_tema_10'],$data['proy_tema_11'],$data['proy_tema_12'],$data['proy_tema_13'],$data['proy_tema_14'],$data['proy_tema_15'],$data['proy_tema_16'],$ayudas);
    $buffer.="<tr>
                <th class='thcenter' colspan=2>Ejes de pol&iacute;ticas sociales transversales en los cuales esta actividad incide</td>
                </tr>
                <tr>
                <th colspan=2>";
    $buffer.=$this->Regresa_Polticas($data['proy_eje_df_1'],$data['proy_eje_df_2'],$data['proy_eje_df_3'],$data['proy_eje_df_4'],$data['proy_eje_df_5'],$data['proy_eje_df_6'],$data['proy_eje_df_7'],$data['proy_eje_df_8'],$data['proy_eje_df_9'],$data['proy_eje_df_10'],$ayudas);
    $tmp_r_1='';
    $tmp_r_2='';
    if($data['relevancia']==1)
      $tmp_r_1=' CHECKED ';
    if($data['relevancia']==2)
      $tmp_r_2=' CHECKED ';
    if($data['relevancia']==0)
      $tmp_r_3=' CHECKED ';
    if( ($data['relevancia']!=1) &&  ($data['relevancia']!=2) && ($data['relevancia']!=3) )
      $tmp_r_3=' CHECKED ';
    $buffer.="</th></tr>
		<tr>
			<th class='thcenter' colspan=2>En Coordinaci&oacute;n</td>
		</tr>
        <tr>
        <td>Esta actividad se hizo en coordinaci&oacute;n con:</td>
        <td>
            <table width='100%' align='center'>
            <tr><td><input type='checkbox' name='coordinacion1' id='coordinacion1' value='1'>&nbsp;Delegaciones&nbsp;".$this->muestraAyuda($ayudas[58])."</td></tr>
            <tr><td><input type='checkbox' name='coordinacion2' id='coordinacion2' value='1'>&nbsp;Embajada o Representaci&oacute;n Internacional&nbsp;".$this->muestraAyuda($ayudas[59])."</td></tr>
            <tr><td><input type='checkbox' name='coordinacion3' id='coordinacion3' value='1'>&nbsp;Gobierno Local&nbsp;".$this->muestraAyuda($ayudas[60])."</td></tr>
            <tr><td><input type='checkbox' name='coordinacion4' id='coordinacion4' value='1'>&nbsp;Gobierno Federal&nbsp;".$this->muestraAyuda($ayudas[61])."</td></tr>
            <tr><td><input type='checkbox' name='coordinacion5' id='coordinacion5' value='1'>&nbsp;Academia&nbsp;".$this->muestraAyuda($ayudas[62])."</td></tr>
            <tr><td><input type='checkbox' name='coordinacion6' id='coordinacion6' value='1'>&nbsp;Organizaci&oacute;n Civil&nbsp;".$this->muestraAyuda($ayudas[63])."</td></tr>
          
            <tr>
            <td align='left'>Por favor describa brevemente</td>
            </tr>
            <tr>
            <td align='left'><textarea name='coordinacion' id='coordinacion' $this->stylest></textarea>&nbsp;".$this->muestraAyuda($ayudas[64])."</td>
            </tr>
            </table>
        </td>
        </tr>";
    if($data['inv_area']!= 4 )
    {
        $buffer.="
            <tr>
                <th class='thcenter' colspan=2>Actividad Relevante</td>
                </tr>
            <tr>
            <td>Relevancia</td>
            <td>
            &nbsp;Nivel 1&nbsp;<input type='radio' name='relevancia' id='relevancia' value='1' ".$tmp_r_1.">
            &nbsp;".$this->muestraAyuda($ayudas[65])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Nivel 2&nbsp;<input type='radio' name='relevancia' id='relevancia' value='2' ".$tmp_r_1.">
            &nbsp;".$this->muestraAyuda($ayudas[66])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Omitir&nbsp;<input type='radio' name='relevancia' id='relevancia' value='0' ".$tmp_r_3.">
            &nbsp;".$this->muestraAyuda($ayudas[67])."
            </td>
            </tr>";
    }
    else
    {
      $buffer.="<input type='hidden' name='relevancia' id='relevancia' value='0'>";
    }
	$buffer.="<tr><td class='thcenter' colspan='2'><center><button type='submit' class='boton'>GRABAR DATOS</button><center></td></tr>
        </form>
        </table>";
    return $buffer;
  }
 function Regresa_Responsables($area_id,$programa_id,$proy_responsable,$ayudas) {
        $select='<center><b>no se tienen items en el catalogo.</b></center>\n';
        $filtro=' WHERE visible=1 ';
        if($area_id == 4)
              $filtro.=" AND area_id=".$area_id." AND programa_id=".$programa_id;
              
        $qusrespo="SELECT respon_id,respon_nombre,respon_ayuda FROM cat_responsables $filtro ORDER BY respon_nombre;";        
        $reqrespo = $this->db->sql_query($qusrespo);
        $resrespo = $this->db->sql_numrows($reqrespo);
        if ($resrespo > 0) {
            $select="<select name='proy_responsable' required=\"yes\" errormsg=\"seleccione una opcion\" $this->stylesm>
                     <option></option>";
            while(list($respon_id,$respon_nombre,$respon_ayuda) = $this->db->sql_fetchrow($reqrespo)) {
                $tmp='';
                if($respon_id == $proy_responsable) {
                    $tmp=' SELECTED ';
                }
                $select.="<option value='".$respon_id."' title='".$respon_ayuda."' ".$tmp.">".$respon_nombre."</option>";
            }
            $select.="</select>".$this->muestraAyuda($ayudas[3]);
        }
        return $select;
    }

    function Regresa_Programa($area_id,$programa_id,$proy_programa,$ayudas) {
        $select='<center><b>no se tienen items en el catalogo.</b></center>\n';
        
        $filtro=" WHERE a.visible=1 " ;

        $qusprog="select DISTINCT b.programa_id,b.programa_nombre,b.programa_ayuda FROM cat_responsables a LEFT JOIN cat_programasp b ON a.respon_coord=b.programa_cecosto $filtro ORDER BY b.programa_nombre;";
        $reqprog = $this->db->sql_query($qusprog);
        $resprog = $this->db->sql_numrows($reqprog);
        if ($resprog > 0) {
            $select="
                <select name=\"proy_programa\" required=\"yes\" errormsg=\"seleccione una opcion\" $this->stylesm >\n
                <option></option>\n";
            while(list($programa_id,$programa_nombre,$programa_ayuda) = $this->db->sql_fetchrow($reqprog)) {
                $tmp='';
                if($programa_id==$proy_programa) {
                    $tmp=' SELECTED ';
                }
                $select.="<option value=\"$programa_id\" title=\"$programa_ayuda\" ".$tmp.">$programa_nombre</option>\n";
            }
            $select.="</select>".$this->muestraAyuda($ayudas[4]);
        }
        return $select;
    }


    function Regresa_Delegacion($proy_delegacion,$area_id,$programa_id,$ayudas)
    {
        if($area_id == 4)
        {
            switch($programa_id)
            {
                case 16:
                    $proy_delegacion=9;
                    break;
                case 17:
                    $proy_delegacion=13;
                    break;
                case 18:
                    $proy_delegacion=12;
                    break;
                case 19:
                    $proy_delegacion=7;
                    break;

            }
        }
        $select='<center><b>no se tienen items en el catalogo.</b></center>\n';
        $qusdele="SELECT delega_id,delega_nom FROM cat_delegaciones ORDER BY delega_nom;";
        $reqsdele = $this->db->sql_query($qusdele);
        $ressdele = $this->db->sql_numrows($reqsdele);
        if ($ressdele > 0) {
            $select="
            <select name=\"proy_delegacio\" required=\"yes\" errormsg=\"seleccione una opcion\" $this->stylesm >
            <option></option>";
            while(list($delega_id,$delega_nom) = $this->db->sql_fetchrow($reqsdele)) {
                $tmp='';
                if($delega_id == $proy_delegacion) {
                    $tmp=' SELECTED ';
                }
                $select.="<option value='".$delega_id."' ".$tmp.">$delega_nom</option>";
            }
            $select.="</select>".$this->muestraAyuda($ayudas[9]);
        }
        return $select;
    }

    function Regresa_Gestion($proy_gestion,$ayudas) {
        $select='<center><b>no se tienen items en el catalogo.</b></center>\n';
        $qusgest="SELECT gest_id,gest_nombre,gest_ayuda FROM cat_tgestion WHERE visible=1 ORDER BY gest_id;";
        $reqsgest = $this->db->sql_query($qusgest);
        $ressgest = $this->db->sql_numrows($reqsgest);
        if ($ressgest > 0) {
            $select="<select name=proy_gestion required=\"yes\" errormsg=\"seleccione una opcion\" $this->stylesm >
                     <option></option>";
            while(list($gest_id,$gest_nombre,$gest_ayuda)= $this->db->sql_fetchrow($reqsgest)) {
                $tmp='';
                if($gest_id==$proy_gestion) {
                    $tmp=' SELECTED ';
                }
                $select.="<option value='".$gest_id."' title='".$gest_ayuda."' ".$tmp." >$gest_nombre</option>";

            }
            $select.="</select>".$this->muestraAyuda($ayudas[10]);
        }
        return $select;
    }

    function Regresa_Apoyos($apoyo_prd,$apoyo_log,$apoyo_pro,$apoyo_art,$apoyo_eco,$ayudas) {
        $tmp_prd='';
		$tmp_log='';
        $tmp_pro='';
        $tmp_art='';
        $tmp_eco='';
		if($apoyo_prd == 1) $tmp_prd=' CHECKED ';
        if($apoyo_log == 1) $tmp_log=' CHECKED ';
        if($apoyo_pro == 1) $tmp_pro=' CHECKED ';
        if($apoyo_art == 1) $tmp_art=' CHECKED ';
        if($apoyo_eco == 1) $tmp_eco=' CHECKED ';
        $buf="
                <table width='100%' align='center' border='0'>
                <tr>
                    <td><input type='checkbox' name='apoyo_prd' id='apoyo_prd' ".$tmp_prd." value='1'>&nbsp;&nbsp;Producci&oacute;n</td>
                    <td><input type='checkbox' name='apoyo_log' id='apoyo_log' ".$tmp_log." value='1'>&nbsp;&nbsp;Log&iacute;stico</td>
                    <td><input type='checkbox' name='apoyo_pro' id='apoyo_pro' ".$tmp_pro." value='1'>&nbsp;&nbsp;Artistas Propios</td>
                    <td><input type='checkbox' name='apoyo_art' id='apoyo_art' ".$tmp_art." value='1'>&nbsp;&nbsp;Artistas Externos</td>
                    <td><input type='checkbox' name='apoyo_eco' id='apoyo_eco' ".$tmp_eco." value='1'>&nbsp;&nbsp;Financiero</td>	
                </tr>
                </table>".$this->muestraAyuda($ayudas[11]);
        return $buf;
    }

    function Regresa_Evento($proy_tevento,$ayudas) {
        $select='<center><b>no se tienen items en el catalogo.</b></center>\n';
        $qusteve="SELECT event_id,event_nombre,event_ayuda FROM cat_tevento WHERE visible=1 ORDER BY orden;";
        $reqteve = $this->db->sql_query($qusteve);
        $resteve = $this->db->sql_numrows($reqteve);
        if ($resteve > 0) {
            $select="<select name=proy_tevento required=\"yes\" errormsg=\"seleccione una opcion\" $this->stylesm >
                     <option></option>";
            while(LIST($event_id,$event_nombre,$event_ayuda) = $this->db->sql_fetchrow($reqteve)) {
                $tmp='';
                if($event_id==$proy_tevento) {
                    $tmp=' SELECTED ';
                }
                $select.="<option value='".$event_id."' title='".$event_ayuda."' ".$tmp.">$event_nombre</option>";
            }
            $select.="</select>".$this->muestraAyuda($ayudas[12]);
        }
        return $select;
    }

    function Regresa_Reciento($proy_recinto,$area_id,$programa_id,$mod,$ayudas) {
        $filtro='';
        if($proy_recinto=='')
            $proy_recinto=0;
        if($mod==1)
        {
            if($area_id == 4)
            {
                switch($programa_id)
                {
                    case 16:
                        $proy_recinto=122;
                        break;
                    case 17:
                        $proy_recinto=123;
                        break;
                    case 18:
                        $proy_recinto=124;
                        break;
                    case 19:
                        $proy_recinto=125;
                        break;
                }
            }
        }
        $select='<center><b>no se tienen items en el catalogo.</b></center>\n';
        $qustresin="SELECT recint_id,recint_nombre,recint_ayuda,color FROM cat_recinto WHERE orden > 0 ".$filtro." ORDER BY orden ASC";
        $reqtresin = $this->db->sql_query($qustresin);
        $restresin = $this->db->sql_numrows($reqtresin);
        if ($restresin > 0) {
            $select="<select name=proy_recinto required=\"yes\" errormsg=\"seleccione una opcion\" $this->stylesm >
                     <option></option>";
            while(list($recint_id,$recint_nombre,$recint_ayuda,$color) = $this->db->sql_fetchrow($reqtresin)) {
                $tmp='';
                if($recint_id==$proy_recinto ) {
                    $tmp=' SELECTED ';
                }
                $select.="<option value='".$recint_id."' ".$tmp." title='".$recint_ayuda."' style='color:".$color."'>".$recint_nombre."</option>";
            }
            $select.="</select>".$this->muestraAyuda($ayudas[7]);
        }
        return $select;
    }

    function Regresa_Recaudacion($proy_recauda_evento,$ayudas) {
        $select='<center><b>no se tienen items en el catalogo.</b></center>\n';
        $qrecau="SELECT recau_id,recau_nombre,recau_ayuda FROM cat_recauda ORDER BY recau_id;";
        $rerecau = $this->db->sql_query($qrecau);
        $resrecau = $this->db->sql_numrows($rerecau);
        if ($resrecau > 0) {
            $select="<select name='proy_recauda_evento' required=\"yes\" errormsg=\"seleccione una opcion\">
                     <option></option>";

            while(list($recau_id,$recau_nombre,$recau_ayuda) = $this->db->sql_fetchrow($rerecau)) {
                $tmp='';
                if($recau_id == $proy_recauda_evento) {
                    $tmp=' SELECTED ';
                }
                $select.="<option value='".$recau_id."' ".$tmp." title='".$recau_ayuda."'>$recau_nombre</option>";
            }
            $select.="</select>".$this->muestraAyuda($ayudas[14]);
        }
        return $select;
    }

    function Regresa_Clasif_Publi($proy_clasipubli,$ayudas) {
        $select='<center><b>no se tienen items en el catalogo.</b></center>\n';
        $quspubli="SELECT clasipubli_id,clasipubli_nombre FROM cat_clasipubli WHERE visible=1 ORDER BY orden";
        $reqspubli = $this->db->sql_query($quspubli);
        $resspubli = $this->db->sql_numrows($reqspubli);
        if ($resspubli > 0) {
            $select="<select name='proy_clasipubli'  required=\"yes\" errormsg=\"seleccione una opcion\">
                     <option></option>";
            while(list($clasipubli_id,$clasipubli_nombre) = $this->db->sql_fetchrow($reqspubli)) {
                $tmp='';
                if($clasipubli_id==$proy_clasipubli) {
                    $tmp=' SELECTED ';
                }
                $select.="<option value='".$clasipubli_id."' ".$tmp.">$clasipubli_nombre</option>";
            }
            $select.="</select>".$this->muestraAyuda($ayudas[16]);
        }
        return $select;
    }

    function Regresa_Proy_Poblacion($proy_poblacion,$proy_asis_m0_15,$proy_asis_m16_18,$proy_asis_m19_30,$proy_asis_m31_64,$proy_asis_m_my65,$proy_asis_cap_esp_m,$proy_asis_m_indig,$proy_asis_m_total,$proy_asis_h0_15,$proy_asis_h16_18,$proy_asis_h19_30,$proy_asis_h31_64,$proy_asis_h_my65,$proy_asis_cap_esp_h,$proy_asis_h_indig,$proy_asis_h_total,$ayudas) {
        if($proy_poblacion=='') {
            $proy_poblacion='0';
        }
        $dproy_poblacion="<input type=\"text\" value=\"$proy_poblacion\" $this->stylesc maxlength=\"10\" name=\"proy_poblacion\" id=\"proy_poblacion\" disabled>
        ".$this->muestraAyuda($ayudas[28]);

        if($proy_asis_m0_15=='') {
            $proy_asis_m0_15='0';
        }
        $dproy_asis_m0_15="<input type=\"text\" value=\"$proy_asis_m0_15\" $this->stylesc maxlength=\"10\" name=\"proy_asis_m0_15\" required=\"yes\" authtype=\"_entero\" onBlur=\"sumam() , suma();\">
        ".$this->muestraAyuda($ayudas[18]);

        if($proy_asis_m16_18=='') {
            $proy_asis_m16_18='0';
        }
        $dproy_asis_m16_18="<input type=\"text\" value=\"$proy_asis_m16_18\" $this->stylesc maxlength=\"10\" name=\"proy_asis_m16_18\" required=\"yes\" authtype=\"_entero\" onBlur=\"sumam() , suma();\">
        ".$this->muestraAyuda($ayudas[20]);

        if($proy_asis_m19_30=='') {
            $proy_asis_m19_30='0';
        }
        $dproy_asis_m19_30="<input type=\"text\" value=\"$proy_asis_m19_30\" $this->stylesc maxlength=\"10\" name=\"proy_asis_m19_30\" required=\"yes\" authtype=\"_entero\" onBlur=\"sumam() , suma();\">
       ".$this->muestraAyuda($ayudas[22]);

        if($proy_asis_m31_64=='') {
            $proy_asis_m31_64='0';
        }
        $tex="Estadística de población femenina beneficiada por actividad.<br> ejemplo:<br> ¿Cuantas Mujeres de 31 A 64 años asistieron a la función del Cascanueces el 17 de Dic.? Asist. 800 personas.<br> (Este campo solo admite números enteros positivos)";
        $dproy_asis_m31_64="<input type=\"text\" value=\"$proy_asis_m31_64\" $this->stylesc maxlength=\"10\" name=\"proy_asis_m31_64\" required=\"yes\" authtype=\"_entero\" onBlur=\"sumam() , suma();\">
        ".$this->muestraAyuda($tex);
        if($proy_asis_m_my65=='') {
            $proy_asis_m_my65='0';
        }
        $dproy_asis_m_my65="<input type=\"text\" value=\"$proy_asis_m_my65\" $this->stylesc maxlength=\"10\" name=\"proy_asis_m_my65\" required=\"yes\" authtype=\"_entero\" onBlur=\"sumam() , suma();\">
        ".$this->muestraAyuda($ayudas[24]);

        if($proy_asis_cap_esp_m=='') {
            $proy_asis_cap_esp_m='0';
        }
        $dproy_asis_cap_esp_m="<input type=\"text\" value=\"$proy_asis_cap_esp_m\" $this->stylesc maxlength=\"10\" name=\"proy_asis_cap_esp_m\" required=\"yes\" authtype=\"_entero\">
        ".$this->muestraAyuda($ayudas[29]);

        if($proy_asis_m_indig=='') {
            $proy_asis_m_indig='0';
        }
        $dproy_asis_m_indig="<input type=\"text\" value=\"$proy_asis_m_indig\" $this->stylesc maxlength=\"10\" name=\"proy_asis_m_indig\" required=\"yes\" authtype=\"_entero\">
        ".$this->muestraAyuda($ayudas[31]);

        if($proy_asis_m_total=='') {
            $proy_asis_m_total='0';
        }
        $dproy_asis_m_total="<input type=\"text\" value=\"$proy_asis_m_total\" $this->stylesc maxlength=\"10\" name=\"proy_asis_m_total\" disabled class=dis>
        ".$this->muestraAyuda($ayudas[26]);
        
        if($proy_asis_h0_15=='') {
            $proy_asis_h0_15='0';
        }
        $dproy_asis_h0_15="<input type=\"text\" value=\"$proy_asis_h0_15\" $this->stylesc maxlength=\"10\" name=\"proy_asis_h0_15\" required=\"yes\" authtype=\"_entero\" onBlur=\"sumah() , suma();\">
        ".$this->muestraAyuda($ayudas[19]);

        if($proy_asis_h16_18=='') {
            $proy_asis_h16_18='0';
        }
        $dproy_asis_h16_18="<input type=\"text\" value=\"$proy_asis_h16_18\" $this->stylesc maxlength=\"10\" name=\"proy_asis_h16_18\" required=\"yes\" authtype=\"_entero\" onBlur=\"sumah() , suma();\">
        ".$this->muestraAyuda($ayudas[21]);

        if($proy_asis_h19_30=='') {
            $proy_asis_h19_30='0';
        }
        $dproy_asis_h19_30="<input type=\"text\" value=\"$proy_asis_h19_30\" $this->stylesc maxlength=\"10\" name=\"proy_asis_h19_30\" required=\"yes\" authtype=\"_entero\" onBlur=\"sumah() , suma();\">
        ".$this->muestraAyuda($ayudas[23]);

        if($proy_asis_h31_64=='') {
            $proy_asis_h31_64='0';
        }
        $tex="Estadística de población masculina beneficiada por actividad.<br> ejemplo:<br> ¿Cuantos hombres de 31 a 64 años asistieron a la función del Cascanueces el 17 de Dic.? Asist. 100 personas.<br> (Este campo solo admite números enteros positivos)";
        $dproy_asis_h31_64="<input type=\"text\" value=\"$proy_asis_h31_64\" $this->stylesc maxlength=\"10\" name=\"proy_asis_h31_64\" required=\"yes\" authtype=\"_entero\" onBlur=\"sumah() , suma();\">
        ".$this->muestraAyuda($tex);

        if($proy_asis_h_my65=='') {
            $proy_asis_h_my65='0';
        }
        $dproy_asis_h_my65="<input type=\"text\" value=\"$proy_asis_h_my65\" $this->stylesc maxlength=\"10\" name=\"proy_asis_h_my65\" required=\"yes\" authtype=\"_entero\" onBlur=\"sumah() , suma();\">
        ".$this->muestraAyuda($ayudas[25]);

        if($proy_asis_cap_esp_h=='') {
            $proy_asis_cap_esp_h='0';
        }
        $dproy_asis_cap_esp_h="<input type=\"text\" value=\"$proy_asis_cap_esp_h\" $this->stylesc maxlength=\"10\" name=\"proy_asis_cap_esp_h\" required=\"yes\" authtype=\"_entero\">
        ".$this->muestraAyuda($ayudas[30]);

        if($proy_asis_h_indig=='') {
            $proy_asis_h_indig='0';
        }
        $dproy_asis_h_indig="<input type=\"text\" value=\"$proy_asis_h_indig\" $this->stylesc maxlength=\"10\" name=\"proy_asis_h_indig\" required=\"yes\" authtype=\"_entero\">
        ".$this->muestraAyuda($ayudas[32]);

        if($proy_asis_h_total=='') {
            $proy_asis_h_total='0';
        }
        $dproy_asis_h_total="<input type=\"text\" value=\"$proy_asis_h_total\" $this->stylesc maxlength=\"10\" name=\"proy_asis_h_total\" disabled>
        ".$this->muestraAyuda($ayudas[27]);
        
        $proy_poblacion = $proy_asis_m_total + $proy_asis_h_total;

        $dproy_poblacion="<input type=\"text\" value=\"$proy_poblacion\" $this->stylesc maxlength=\"10\" name=\"proy_poblacion\" id=\"proy_poblacion\" disabled>
		".$this->muestraAyuda($ayudas[28]);
        $buf="<table width='100%' cellpadding=\"2\" cellspacing=\"2\" border=\"0\">
                <tr>
                    <td class='thcenter'>Grupo</td>
                    <td class='thcenter'>Mujeres</td>
                    <td class='thcenter'>Hombres</td>
                </tr>
                <tr>
                    <td>De 0 a 14 AÑOS </td>
                    <td>$dproy_asis_m0_15</td>
                    <td>$dproy_asis_h0_15</td>
                </tr>
                <tr>
                    <td>De 15 a 29 AÑOS </td>
                    <td>$dproy_asis_m16_18</td>
                    <td>$dproy_asis_h16_18</td>
                </tr>
                <tr>
                    <td>De 30 a 59 AÑOS </td>
                    <td>$dproy_asis_m19_30</td>
                    <td>$dproy_asis_h19_30</td>
                </tr>
                <tr>
                    <td>Mayores de 60 AÑOS</td>
                    <td>$dproy_asis_m_my65</td>
                    <td>$dproy_asis_h_my65</td>
                </tr>
                <tr>
                    <td>Total por sexo</td>
                    <td>$dproy_asis_m_total</td>
                    <td>$dproy_asis_h_total</td>
                </tr>
                <tr>
                    <td><b>Poblaci&oacute;n total</b></td>
                    <td colspan='2' style='text-align:center;'>$dproy_poblacion</td>
                </tr>
                <tr>
                    <td>Personas  con discapacidad</td>
                    <td>$dproy_asis_cap_esp_m</td>
                    <td>$dproy_asis_cap_esp_h</td>
                </tr>
                <tr>
                    <td>Ind&iacute;genas </td>
                    <td>$dproy_asis_m_indig</td>
                    <td>$dproy_asis_h_indig</td>
                </tr>
            </table>";
        return $buf;
    }
    function Temas($proy_tema_1,$proy_tema_2,$proy_tema_3,$proy_tema_4,$proy_tema_5,$proy_tema_6,$proy_tema_7,$proy_tema_8,$proy_tema_9,$proy_tema_10,$proy_tema_11,$proy_tema_12,$proy_tema_13,$proy_tema_14,$proy_tema_15,$proy_tema_16,$ayudas) {
        $cul1='';
        $cul2='';
        $cul3='';
        $cul4='';
        $cul5='';
        $cul6='';
        $cul7='';
        $cul8='';
        $cul9='';
        $cul10='';
        $cul11='';
        $cul12='';
        $cul13='';
        $cul14='';
        $cul15='';
        $cul16='';

        if($proy_tema_1==1) {
            $cul1="checked";
        }else {
            $cul1="";
        }
        if($proy_tema_2==1) {
            $cul2="checked";
        }else {
            $cul2="";
        }
        if($proy_tema_3==1) {
            $cul3="checked";
        }else {
            $cul3="";
        }
        if($proy_tema_4==1) {
            $cul4="checked";
        }else {
            $cul4="";
        }
        if($proy_tema_5==1) {
            $cul5="checked";
        }else {
            $cul5="";
        }
        if($proy_tema_6==1) {
            $cul6="checked";
        }else {
            $cul6="";
        }
        if($proy_tema_7==1) {
            $cul7="checked";
        }else {
            $cul7="";
        }
        if($proy_tema_8==1) {
            $cul8="checked";
        }else {
            $cul8="";
        }
        if($proy_tema_9==1) {
            $cul9="checked";
        }else {
            $cul9="";
        }
        if($proy_tema_10==1) {
            $cul10="checked";
        }else {
            $cul10="";
        }
        if($proy_tema_11==1) {
            $cul11="checked";
        }else {
            $cul11="";
        }
        if($proy_tema_12==1) {
            $cul12="checked";
        }else {
            $cul12="";
        }
        if($proy_tema_13==1) {
            $cul13="checked";
        }else {
            $cul13="";
        }
        if($proy_tema_14==1) {
            $cul14="checked";
        }else {
            $cul14="";
        }
        if($proy_tema_15==1) {
            $cul15="checked";
        }else {
            $cul15="";
        }
        if($proy_tema_16==1) {
            $cul16="checked";
        }else {
            $cul16="";
        }
        $buf="
		<table width=100% cellpadding=\"2\" cellspacing=\"2\" border=\"0\">
		<tr>
			<td>
			<input type='checkbox' value='1' name='proy_tema_1' $cul1 $avilita	 />
			M&uacute;sica".$this->muestraAyuda($ayudas[33])."
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_2' $cul2 $avilita	 />
			Danza".$this->muestraAyuda($ayudas[34])." 
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_3' $cul3 $avilita	 />
            Teatro ".$this->muestraAyuda($ayudas[35])."
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_4' $cul4 $avilita	 />
			Opera".$this->muestraAyuda($ayudas[36])."
			</td>
		</tr>	
		<tr>
			<td>
			<input type='checkbox' value='1' name='proy_tema_5' $cul5 $avilita	 />
			Escultura".$this->muestraAyuda($ayudas[37])."  
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_6' $cul6 $avilita	 />
			Pintura Grabado".$this->muestraAyuda($ayudas[38])."
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_7' $cul7 $avilita	 />
			Cine video".$this->muestraAyuda($ayudas[39])."
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_8' $cul8 $avilita	 />
			Literatura".$this->muestraAyuda($ayudas[40])." 
			</td>
		</tr>	
		<tr>
			<td>
			<input type='checkbox' value='1' name='proy_tema_9' $cul9 $avilita	 />
			Narraci&oacute;n oral".$this->muestraAyuda($ayudas[41])." 
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_10' $cul10 $avilita	 />
			Ecolog&iacute;a".$this->muestraAyuda($ayudas[42])."
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_11' $cul11 $avilita	 />
			Cultura popular".$this->muestraAyuda($ayudas[43])." 
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_12' $cul12 $avilita	 />
			Multidisciplinario".$this->muestraAyuda($ayudas[44])." 
			</td>
		</tr>	
		<tr>
			<td>
			<input type='checkbox' value='1' name='proy_tema_13' $cul13 $avilita	 />
			Ciencia y tecnolog&iacute;a".$this->muestraAyuda($ayudas[45])." 
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_14' $cul14 $avilita	 />
			Instalaci&oacute;n".$this->muestraAyuda($ayudas[46])." 
			</td>
			<td>
			<input type='checkbox' value='1' name='proy_tema_15' $cul15 $avilita	 />
			Otro".$this->muestraAyuda($ayudas[47])." 
			</td>
			<td>
			&nbsp;
			</td>
		</tr>
		</table>";
        return $buf;
    }

    function Regresa_Polticas($proy_eje_df_1,$proy_eje_df_2,$proy_eje_df_3,$proy_eje_df_4,$proy_eje_df_5,$proy_eje_df_6,$proy_eje_df_7,$proy_eje_df_8,$proy_eje_df_9,$proy_eje_df_10,$ayudas) {

        if($proy_eje_df_1==1) {
            $df1="checked";
        }else {
            $df1="";
        }
        if($proy_eje_df_2==1) {
            $df2="checked";
        }else {
            $df2="";
        }
        if($proy_eje_df_3==1) {
            $df3="checked";
        }else {
            $df3="";
        }
        if($proy_eje_df_4==1) {
            $df4="checked";
        }else {
            $df4="";
        }
        if($proy_eje_df_5==1) {
            $df5="checked";
        }else {
            $df5="";
        }
        if($proy_eje_df_6==1) {
            $df6="checked";
        }else {
            $df6="";
        }
        if($proy_eje_df_7==1) {
            $df7="checked";
        }else {
            $df7="";
        }
        if($proy_eje_df_8==1) {
            $df8="checked";
        }else {
            $df8="";
        }
        if($proy_eje_df_9==1) {
            $df9="checked";
        }else {
            $df9="";
        }
        if($proy_eje_df_10==1) {
            $df10="checked";
        }else {
            $df10="";
        }
        $buf="
            <table width='100%' cellpadding=\"2\" cellspacing=\"2\" border=\"0\">
            <tr>
            <td><input type='checkbox' value='1' name='proy_eje_df_1' $df1 $avilita  />
                Equidad de g&eacute;nero".$this->muestraAyuda($ayudas[48])."</td>
            <td><input type='checkbox' value='1' name='proy_eje_df_2' $df2 $avilita  />
                Adultos mayores".$this->muestraAyuda($ayudas[49])."</td>
            <td><input type='checkbox' value='1' name='proy_eje_df_3' $df3 $avilita  />
                Diversidad sexual".$this->muestraAyuda($ayudas[50])."</td>
            </tr>
            <tr>
            <td><input type='checkbox' value='1' name='proy_eje_df_4' $df4 $avilita  />
                Recuperaci&oacute;n de espacios p&uacute;blicos".$this->muestraAyuda($ayudas[51])."</td>
            <td><input type='checkbox' value='1' name='proy_eje_df_5' $df5 $avilita  />
                Derechos humanos".$this->muestraAyuda($ayudas[52])."</td>
            <td><input type='checkbox' value='1' name='proy_eje_df_6' $df6 $avilita  />
                Grupos ind&iacute;genas".$this->muestraAyuda($ayudas[53])."</td>
            </tr>
            <tr>
                <td><input type='checkbox' value='1' name='proy_eje_df_7' $df7 $avilita	 />
                Discapacidad".$this->muestraAyuda($ayudas[54])." </td>
                <td><input type='checkbox' value='1' name='proy_eje_df_8' $df8 $avilita	 />
                Ni&ntilde;ez".$this->muestraAyuda($ayudas[55])." </td>
                <td><input type='checkbox' value='1' name='proy_eje_df_9' $df9 $avilita	 />
                J&oacute;venes".$this->muestraAyuda($ayudas[56])." </td>
            </tr>
            <tr>
              <td><input type='checkbox' value='1' name='proy_eje_df_10' $df10 $avilita	 />
              Multiculturalidad".$this->muestraAyuda($ayudas[57])."</td>
              <td>&nbsp;</td><td>&nbsp;</td>
            </tr>
            </table>";
        return $buf;
    }

   function Regresa_Ayudas()
  {
        $array=array();
        $sql = "SELECT * FROM cat_ayuda_actividades_basica limit 1";
        $res = $this->db->sql_query($sql,$db) or die ("Error en la consulta: ".$sql);
        if($this->db->sql_numrows($res)>0)
        {
            $array=$this->db->sql_fetchrow($res);
        }
        return $array;
  } 
  
  function muestraAyuda($texto){
    return "&nbsp;&nbsp;<a href='#' class='ayudas' rel='popover' data-content='".$texto."' data-original-title='Ayuda SiSec'>&nbsp;?&nbsp;</a>";
    //   return "&nbsp;&nbsp;<button type=\"button\" style=\"padding-top:0px;width:15px;height:17px;font-size:8px;\" class=\"btn-danger ayudas\" id=\"example\" data-toggle=\"popover\" title=\"Ayuda Sisec\" data-content=\"".$texto."\" >?</button>";
  }
  
   function Inserta_Actividades($conn,$data) {
        $id_area=$data['inv_area'];
        $id_prog=$data['cat_programa_id1'];
        $id_subp=$data['subprograma_id'];
        $id_ano=substr($data['proy_fecha_inicio'],0,4);
        $id_mes=substr($data['proy_fecha_inicio'],5,2);
        if($this->Regresa_Esta_Bloqueado($conn, $id_area, $id_prog, $id_subp, $id_ano, $id_mes) == 0)
        {
            $folio=0;
            $campos='';
            $valores='';
            $date=date("Y-m-d H:i:s");
            $total_1=$data['proy_asis_m0_15']+$data['proy_asis_m16_18']+$data['proy_asis_m19_30']+$data['proy_asis_m_my65'];
            $total_2=$data['proy_asis_h0_15']+$data['proy_asis_h16_18']+$data['proy_asis_h19_30']+$data['proy_asis_h_my65'];
            $sumproy_poblacion=$total_1 + $total_2;
            $ins="INSERT INTO proyectos ";
            foreach($data as $campo => $valor) {
                if( ($campo!='user_id') && ($campo!='aplicacion') && ($campo!='apli_com') && ($campo!='altev') && ($campo!='proy_coordinacion_id') && ($campo!='proy_area_id') && ($campo!='ficha') && ($campo!='eje_tematico')) {
                    if($campo=='cat_programa_id1')
                        $campo='cat_programa_id';
                    if($campo=='inv_area')
                        $campo='area_id';

                    $campos.=$campo.",";
                    $valores.="'".$valor."',";
                }
            }
            $campos.="proy_capturo_user,proy_capturo_time,proy_capturo_ip,proy_status,proy_apoyo,proy_cecostos,proy_monto_asignado,proy_monto_ejercido,proy_coordinacion_id,proy_area_id,proy_asis_m31_64,proy_asis_h31_64,proy_asis_m_total,proy_asis_h31_30,proy_asis_h_total,proy_tinform,proy_poblacion";
            $valores.="'".$data['user_id']."','".$date."','".$_SERVER[REMOTE_ADDR]."','INICIADO','0','0','0.00','0.00','0000','".$data['inv_area']."','0','0','".$total_1."','0','".$total_2."','00','".$sumproy_poblacion."'";
            $sql=$ins."(".$campos.") VALUES (".$valores.");";
            $res=$this->db->sql_query($sql);
            if($res) {
                $folio = mysql_insert_id();
            }
        }
        else
        {
            $folio=-1;
        }
        return $folio;

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
        $res=$this->db->sql_query($sql,$db);
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

    function regresa_tianguis($tianguis_id) {
        $buffer="<center><b>no se tienen items en el catalogo.</b></center>\n";
        $qusprog="SELECT tianguis_id,nombre FROM cat_tianguis ORDER BY nombre;";
        $reqprog = $this->db->sql_query($qusprog);
        $resprog = $this->db->sql_numrows($reqprog);
        if ($resprog > '0') {
            $buffer="<select name='tianguis_id' errormsg=\"seleccione una opcion\">\n
                          <option></option>\n";
            while($fila = $this->db->sql_fetchrow($reqprog,MYSQL_ASSOC)) {
                $tmp="";
                if($fila['tianguis_id']==$tianguis_id) {
                    $tmp=" selected ";
                }
                $buffer.="<option value=\"".$fila['tianguis_id']."\" ".$tmp.">".$fila['nombre']."</option>";
            }
            $buffer.="</select>\n<a href=\"javascript:showDialog('AYUDA','ayuda texto','prompt');\">[?]</a>";
        }
        return $buffer;
    }

    function Consulta_Actividades($data,$array_programas,$path_sys,$array_areas) {
        $user_id=$data['user_id'];
        $mes_id=$data['mesele'];
        $subprograma_id=$data['subprograma_id'];
        $eje_tem=$data['eje_tematico'];
        $array_eje[0]='proy_eje_df_1';
        $array_eje[1]='proy_eje_df_1';
        $array_eje[2]='proy_eje_df_2';
        $array_eje[3]='proy_eje_df_3';
        $array_eje[4]='proy_eje_df_4';
        $array_eje[5]='proy_eje_df_5';
        $array_eje[6]='proy_eje_df_6';
        $array_eje[7]='proy_eje_df_7';
        $array_eje[8]='proy_eje_df_8';
        $array_eje[9]='proy_eje_df_9';
        $array_eje[10]='proy_eje_df_10';
        $array_eje[11]='proy_eje_df_1';


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
            $filtro.=" AND cat_programa_id = ".$data['cat_programa_id1']." ";
        }
        if($subprograma_id > 0){
            $filtro.=" AND subprograma_id = ".$subprograma_id." ";
        }
        if($eje_tem > 0)
            $filtro.=" AND ".$array_eje[$eje_tem]." = 1";


        $sql_count="SELECT proy_status,count(proy_status) as total FROM proyectos ".$filtro." GROUP BY proy_status ORDER BY proy_status;";
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
        $sql="SELECT proy_id,proy_nombre,area_id,cat_programa_id,proy_fecha_inicio,proy_status,proy_asis_h_total,proy_asis_m_total,subprograma_id FROM proyectos ".$filtro." ORDER BY proy_fecha_inicio";
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
                $bloqueado=$this->Regresa_Esta_Bloqueado($area_id,$cat_programa_id,$subprograma_id,substr($proy_fecha_inicio,0,4),substr($proy_fecha_inicio,5,2));
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
                            <th>".($total_h + $total_m)."</th>
                            <th align='center'>&nbsp;";
            if($bloqueado==0)
                $buffer.="<a href=\"javascript:lanza_ventana('$area_id','$cat_programa_id','$proy_id','$ano_id','$mes_id','1','$user_id');\"><img src='imagenes/vcard.png' width='16' height='16' border='0'></a>";

				$buffer.="</th><th align='center'><a href=\"javascript:lanza_ventana('$area_id','$cat_programa_id','$proy_id','$ano_id','$mes_id','2','$user_id');\"><img src='imagenes/magnifier.png' width='16' height='16' border='0'></a></th>
						    <th align='center'><div id='".$tmp."'></div></th></tr>";
            }
            $buffer.="</tbody><thead><tr><td colspan='9' width='100%' align='center'>Total de Registros:  ".$num."</td></tr></thead></table>";
        }
        return $buffer;
    }

    function Elimina_Actividades($area_id,$programa_id,$folio_id,$status) {
        $reg="La actividad no se elimino";
        $del="UPDATE proyectos SET proy_status='".$status."' WHERE proy_id=".$folio_id.";";
        if($this->db->sql_query($del)) {
            $reg="<font color='#800000'>".$status."</font>";
        }
        return $reg;
    }

    function Mostrar_Actividades_Folio($data,$tipo_vista) {
        $ayudas=$this->Regresa_Ayudas($db);
        $disabled='';
        if($tipo_vista == 2)
            $disabled=' disabled = disabled';
        $proy_fecha=date('Y-m-d');
        $area_id=$data['area_id'];
        $programa_id=$data['cat_programa_id'];

        $buffer="
            <form name='form1' method='post' action='actualiza_eventos.php' >
            <input type='hidden' name='area_id' id='area_id' value='".$data['area_id']."'>
            <input type='hidden' name='cat_programa_id' id='cat_programa_id' value='".$data['cat_programa_id']."'>
            <input type='hidden' name='subprograma_id' id='subprograma_id' value='".$data['subprograma_id']."'>
            <input type='hidden' name='ano' id='ano' value='".$ano."'>
            <input type='hidden' name='mes' id='mes' value='".$mes."'>
            <input type='hidden' name='user_id' id='user_id' value='".$user_id."'>";
        $buffer.='
            <input type="hidden"  name="proy_id" id="proy_id" value="'.$data['proy_id'].'">
            <table width="100%" align="center" border="0"><tr><td colspan="2">No de Folio:   '.$data['proy_id'].'</td></tr>';
        if( ($area_id == 2 ) && ($programa_id == 11)) {
            $buffer.="<tr><td>Seleccione el tianguis</td><td>";
            $buffer.=$this->regresa_tianguis($data['tianguis_id']);
            $buffer.="</td></tr>";
        }
        $buffer.="
            <tr>
            <td>Nombre de la actividad</td>
            <td><input type=\"text\" required=\"yes\" value=\"".$data['proy_nombre']."\" size=\"50\"  maxlength=\"200\"  name=\"proy_nombre\" TITLE=\"\">
            <a href=\"javascript:showDialog('AYUDA','".$ayudas[1]."','prompt');\">[?]</a></td>
            </tr>
            <tr>
            <td>Descripci&oacute;n</td>
            <td><textarea cols=\"60\"   rows=\"4\" wrap=\"ON\" name=\"proy_descripcion\">".$data['proy_descripcion']."</textarea>
            <a href=\"javascript:showDialog('AYUDA','".$ayudas[2]."','prompt');\">[?]</a></td>
            </tr>

            <tr>
            <td>Persona responsable</td>
			<td>";
        $buffer.=$this->Regresa_Responsables($area_id,$programa_id,$data['proy_responsable'],$ayudas);
        $buffer.="</td>
            </tr>
            <tr>
            <td>Programa Meta</td>
            <td>";
        $buffer.=$this->Regresa_Programa($area_id,$programa_id,$data['proy_programa'],$ayudas);
        $buffer.="</td>
            </tr>
         	<tr><td>Fecha y hora de inicio</td>
            <td>
                    <input type=\"text\" id=\"cal-field-1\" name=\"proy_fecha_inicio\" value=\"".$data['proy_fecha_inicio']."\" required=\"yes\"/>
                    <img src=\"../imagenes/calendar.png\" id=\"cal-button-1\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
                      <a href=\"javascript:showDialog('AYUDA','".$ayudas[5]."','prompt');\">[?]</a>
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
                    <td>Fecha y hora de t&eacute;rmino</td>
                    <td>
                        <input type=\"text\" id=\"cal-field-2\"  name=\"proy_fecha_termino\" value=\"".$data['proy_fecha_termino']."\" required=\"yes\"/>
                        <img src=\"../imagenes/calendar.png\" id=\"cal-button-2\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
                          <a href=\"javascript:showDialog('AYUDA','".$ayudas[6]."','prompt');\">[?]</a>
                          <script type='text/javascript'>
                            Calendar.setup({
                            inputField    : 'cal-field-2',
                            ifFormat      : '%Y-%m-%d %H:%M:00',
                            onUpdate      : revisa_fecha_final,
                            showsTime     : true,
                            button        : 'cal-button-2'});
                         </script>
                    &nbsp;&nbsp;<div id='validacacionFechaFin'></div></td>
                    </tr>
                  <tr>
                        <td>Tipo de recinto</td>
                        <td>";
        $buffer.=$this->Regresa_Reciento($data['proy_recinto'],$area_id,$programa_id,2,$ayudas);
        $buffer.="</td>
                  </tr>					
                  <tr>
                <td>Lugar de la actividad<br>(domicilio completo)</td>
                <td><textarea cols=\"60\" rows=\"4\" wrap=\"ON\"name=\"proy_lug_evento\" >".$data['proy_lug_evento']."</textarea>
                <a href=\"javascript:showDialog('AYUDA','".$ayudas[8]."','prompt');\">[?]</a></td>
              </tr>
              <tr>
                <td>Delegaci&oacute;n</td>
                <td>";
        $buffer.=$this->Regresa_Delegacion($data['proy_delegacio'],$area_id,$programa_id,$ayudas);
        $buffer.="</td>
              </tr><tr>
                <td>Tipo de gesti&oacute;n</td>
                <td>";
        $buffer.=$this->Regresa_Gestion($data['proy_gestion'],$ayudas);
        $buffer.="</td>
               </tr>";
        if($area_id==7) {
            $buffer.="
                <tr>
                <td>Apoyos</td>
                <td>";
            $buffer.=$this->Regresa_Apoyos($data['apoyo_prd'],$data['apoyo_log'],$data['apoyo_pro'],$data['apoyo_art'],$data['apoyo_eco'],$ayudas);
            $buffer.="</td>
                    </tr>";
        }
        $buffer.="
                <tr>
                    <td>Tipo de actividad </td>
                    <td>";
        $buffer.=$this->Regresa_Evento($data['proy_tevento'],$ayudas);
        $buffer.="</td>
                  </tr>
                  <tr>
                        <td>Observaciones</td>
                        <td><textarea cols=\"60\"   rows=\"4\" wrap=\"ON\" name=\"proy_nota\">".$data['proy_nota']."</textarea>
                        <a href=\"javascript:showDialog('AYUDA','".$ayudas[13]."','prompt');\">[?]</a></td>
                  </tr>
                  <tr>
                        <td>Recaudaci&oacute;n</td>
                        <td>";
        $buffer.=$this->Regresa_Recaudacion($data['proy_recauda_evento'],$ayudas);
        $buffer.="</td>
                  </tr>
                  <tr>
                        <td>Monto de recaudaci&oacute;n</td>
                        <td>$<input type=\"text\" value=\"".$data['proy_recauda_monto']."\" size=\"10\" maxlength=\"10\" name=\"proy_recauda_monto\">pesos-M.N.
                        <a href=\"javascript:showDialog('AYUDA','".$ayudas[15]."','prompt');\">[?]</a></td>
                  </tr>
                  <tr>
                        <th class=\"tdverde\" colspan=2>Estad&iacute;stica de poblaci&oacute;n beneficiada por esta actividad </td>
                        </tr>
                        <tr>
                        <td>Tipo de p&uacute;blico al cual se dirige el evento </td>
                        <td>";
        $buffer.=$this->Regresa_Clasif_Publi($data['proy_clasipubli'],$ayudas);
        $buffer.="</td>
                    </tr>
                    <tr>
                        <td>Frecuencia</td>
                        <td><input type='text' name='frecuencia' id='frecuencia' value='".$data['frecuencia']."' size='5'>&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[17]."','prompt');\">[?]</a></td>
            		</tr>
                        <tr>
                        <th colspan=2>";
        $buffer.=$this->Regresa_Proy_Poblacion($data['db'],$data['proy_poblacion'],$data['proy_asis_m0_15'],$data['proy_asis_m16_18'],$data['proy_asis_m19_30'],$data['proy_asis_m31_64'],$data['proy_asis_m_my65'],$data['proy_asis_cap_esp_m'],$data['proy_asis_m_indig'],$data['proy_asis_m_total'],$data['proy_asis_h0_15'],$data['proy_asis_h16_18'],$data['proy_asis_h19_30'],$data['proy_asis_h31_64'],$data['proy_asis_h_my65'],$data['proy_asis_cap_esp_h'],$data['proy_asis_h_indig'],$data['proy_asis_h_total'],$ayudas);
        $buffer.="</th>
                        </tr>";
        $buffer.="<tr>
            <th class='tdverde' colspan=2>Disciplinas y &aacute;mbitos tem&aacute;ticos en las cuales incide la actividad (solo de aplicar)</td>
            </tr>
            <tr>
            <th colspan=2>";
        $buffer.=$this->Temas($data['proy_tema_1'],$data['proy_tema_2'],$data['proy_tema_3'],$data['proy_tema_4'],$data['proy_tema_5'],$data['proy_tema_6'],$data['proy_tema_7'],$data['proy_tema_8'],$data['proy_tema_9'],$data['proy_tema_10'],$data['proy_tema_11'],$data['proy_tema_12'],$data['proy_tema_13'],$data['proy_tema_14'],$data['proy_tema_15'],$data['proy_tema_16'],$ayudas);
        $buffer.="<tr>
                <th class='tdverde' colspan=2>Ejes de pol&iacute;ticas sociales transversales en los cuales esta actividad incide</td>
                </tr>
                <tr>
                <th colspan=2>";
        $buffer.=$this->Regresa_Polticas($data['proy_eje_df_1'],$data['proy_eje_df_2'],$data['proy_eje_df_3'],$data['proy_eje_df_4'],$data['proy_eje_df_5'],$data['proy_eje_df_6'],$data['proy_eje_df_7'],$data['proy_eje_df_8'],$data['proy_eje_df_9'],$data['proy_eje_df_10'],$ayudas);
        $tmp_r_1='';
        $tmp_r_2='';
        $tmp_r_3='';
        if($data['relevancia']==1)
            $tmp_r_1=' CHECKED ';
        if($data['relevancia']==2)
            $tmp_r_2=' CHECKED ';
        if($data['relevancia']==0)
            $tmp_r_3=' CHECKED ';
        $tmp_c1='';
        $tmp_c2='';
        $tmp_c3='';
        $tmp_c4='';
        $tmp_c5='';
        $tmp_c6='';

        if($data['coordinacion1']==1)
            $tmp_c1=" checked ";
        if($data['coordinacion2']==1)
            $tmp_c2=" checked ";
        if($data['coordinacion3']==1)
            $tmp_c3=" checked ";
        if($data['coordinacion4']==1)
            $tmp_c4=" checked ";
        if($data['coordinacion5']==1)
            $tmp_c5=" checked ";
        if($data['coordinacion6']==1)
            $tmp_c6=" checked ";

        $buffer.="</th></tr>
	        <tr>
                <th class='tdverde' colspan=2>En Coordinaci&oacute;n</td>
		    </tr>
	        <tr>
		    <td>Esta actividad se hizo en coordinaci&oacute;n con:</td>
			<td>
	            <table width='100%' align='center'>
		        <tr>
                        <td>
				<input type='checkbox' name='coordinacion1' id='coordinacion1' ".$tmp_c1." value='1'>Delegaciones&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[58]."','prompt');\">[?]</a><br>
                                <input type='checkbox' name='coordinacion2' id='coordinacion2' ".$tmp_c2." value='1'>Embajada o Representaci&oacute;n Internacional&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[59]."','prompt');\">[?]</a><br>
                                <input type='checkbox' name='coordinacion3' id='coordinacion3' ".$tmp_c3." value='1'>Gobierno Local&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[60]."','prompt');\">[?]</a><br>
                                <input type='checkbox' name='coordinacion4' id='coordinacion4' ".$tmp_c4." value='1'>Gobierno Federal&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[61]."','prompt');\">[?]</a><br>
                                <input type='checkbox' name='coordinacion5' id='coordinacion5' ".$tmp_c5." value='1'>Academia&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[62]."','prompt');\">[?]</a><br>
                                <input type='checkbox' name='coordinacion6' id='coordinacion6' ".$tmp_c6." value='1'>Organizaci&oacute;n Civil&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[63]."','prompt');\">[?]</a></td>
			    </tr>
	            <tr>
		        <td align='left'>Por favor describa brevemente</td>
			    </tr>
	            <tr>
		        <td align='left'><textarea name='coordinacion' id='coordinacion' cols='80' rows='5'>".$data['coordinacion']."</textarea>&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[64]."','prompt');\">[?]</a></td>
			    </tr>
	            </table>
		    </td>
			</tr>
	        <tr>
                <th class='tdverde' colspan=2>Actividad Relevante</td>
		    </tr>
		    <tr>
	        <td>Relevancia</td>
		    <td>
			&nbsp;Nivel I&nbsp;<input type='radio' name='relevancia' id='relevancia' value='1' ".$tmp_r_1.">
	        &nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[65]."','prompt');\">[?]</a>
	        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    Nivel 2&nbsp;<input type='radio' name='relevancia' id='relevancia' value='2' ".$tmp_r_2.">
		    &nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[66]."','prompt');\">[?]</a>
	        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    Omitir&nbsp;<input type='radio' name='relevancia' id='relevancia' value='0' ".$tmp_r_3.">
		    &nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[67]."','prompt');\">[?]</a>
			</td>
			</tr>
	        <tr>
	        <th class=\"tdverde\" colspan=2>";
        if($tipo_vista==1)
            $buffer.="<input type=\"submit\" name=\"boton\" value=\"GRABAR DATOS\" class=\"boton\">&nbsp;&nbsp;";
        $buffer.="<input type='button' name='cerrar' value='Cerrar Ventana' onclick='self.close();' class=\"boton\"></td>
		    </tr>
	        </table></form>";
        return $buffer;
    }

    function Mostrar_Actividades($data,$tipo_vista,$ano,$mes,$user_id) {
        $ayudas=$this->Regresa_Ayudas($db);
        $disabled='';
        if($tipo_vista == 2)
            $disabled=' disabled = disabled';
        $proy_fecha=date('Y-m-d');
        $area_id=$data['area_id'];
        $programa_id=$data['cat_programa_id'];

        $buffer="
            <form name='form1' method='post' action='actualiza_eventos.php' >
            <input type='hidden' name='area_id' id='area_id' value='".$data['area_id']."'>
            <input type='hidden' name='inv_area' id='inv_area' value='".$data['area_id']."'>
            <input type='hidden' name='cat_programa_id' id='cat_programa_id' value='".$data['cat_programa_id']."'>
            <input type='hidden' name='subprograma_id' id='subprograma_id' value='".$data['subprograma_id']."'>
            <input type='hidden' name='ano' id='ano' value='".$ano."'>
            <input type='hidden' name='mes' id='mes' value='".$mes."'>
            <input type='hidden' name='user_id' id='user_id' value='".$user_id."'>";
        $buffer.='
            <input type="hidden"  name="proy_id" id="proy_id" value="'.$data['proy_id'].'">
	    <table width="100%" align="center" border="0"><tr><td >No de Folio:</td><td>'.$data['proy_id'].'</td></tr>';
        if( ($area_id == 2 ) && ($programa_id == 11)) {
            $buffer.="<tr><td>Seleccione el tianguis</td><td>";
            $buffer.=$this->regresa_tianguis($data['tianguis_id']);
            $buffer.="</td></tr>";
        }
        $buffer.="
            <tr>
            <td>Nombre de la actividad</td>
            <td><input type=\"text\" required=\"yes\" value=\"".$data['proy_nombre']."\" size=\"50\"  maxlength=\"200\"  name=\"proy_nombre\" TITLE=\"\">
            <a href=\"javascript:showDialog('AYUDA','".$ayudas[1]."','prompt');\">[?]</a></td>
            </tr>
            <tr>
            <td>Descripci&oacute;n</td>
            <td><textarea cols=\"60\"   rows=\"4\" wrap=\"ON\" name=\"proy_descripcion\">".$data['proy_descripcion']."</textarea>
            <a href=\"javascript:showDialog('AYUDA','".$ayudas[2]."','prompt');\">[?]</a></td>
            </tr>
            <tr>
            <td>Persona responsable</td>
            <td>";
        $buffer.=$this->Regresa_Responsables($area_id,$programa_id,$data['proy_responsable'],$ayudas);
        $buffer.="</td>
            </tr>
            <tr>
            <td>Programa Meta</td>
            <td>";
        $buffer.=$this->Regresa_Programa($area_id,$programa_id,$data['proy_programa'],$ayudas);
        $buffer.="</td>
            </tr>
         	<tr><td>Fecha y hora de inicio</td>
            <td>
                    <input type=\"text\" id=\"cal-field-1\" name=\"proy_fecha_inicio\" value=\"".$data['proy_fecha_inicio']."\" required=\"yes\"/>
                    <img src=\"../imagenes/calendar.png\" id=\"cal-button-1\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
                      <a href=\"javascript:showDialog('AYUDA','".$ayudas[5]."','prompt');\">[?]</a>
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
                    <td>Fecha y hora de t&eacute;rmino</td>
                    <td>
                        <input type=\"text\" id=\"cal-field-2\"  name=\"proy_fecha_termino\" value=\"".$data['proy_fecha_termino']."\" required=\"yes\"/>
                        <img src=\"../imagenes/calendar.png\" id=\"cal-button-2\" style=\"border: 1px solid white; cursor: pointer;\" title=\"Fecha\" onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">
                          <a href=\"javascript:showDialog('AYUDA','".$ayudas[6]."','prompt');\">[?]</a>
                          <script type='text/javascript'>
                            Calendar.setup({
                            inputField    : 'cal-field-2',
                            ifFormat      : '%Y-%m-%d %H:%M:00',
                            onUpdate      : revisa_fecha_final_Act,
                            showsTime     : true,
                            button        : 'cal-button-2'});
                         </script>
                    &nbsp;&nbsp;<br><div id='validacacionFechaFin'></div></td>
                    </tr>
                  <tr>
                        <td>Tipo de recinto</td>
                        <td>";
        $buffer.=$this->Regresa_Reciento($data['proy_recinto'],$area_id,$programa_id,2,$ayudas);
        $buffer.="</td>
                  </tr>					
					<tr>
                <td>Lugar de la actividad<br>(domicilio completo)</td>
                <td><textarea cols=\"60\" rows=\"4\" wrap=\"ON\"name=\"proy_lug_evento\" >".$data['proy_lug_evento']."</textarea>
                <a href=\"javascript:showDialog('AYUDA','".$ayudas[8]."','prompt');\">[?]</a></td>
              </tr>
              <tr>
                <td>Delegaci&oacute;n</td>
                <td>";
        $buffer.=$this->Regresa_Delegacion($data['proy_delegacio'],$area_id,$programa_id,$ayudas);
        $buffer.="</td>
              </tr><tr>
                <td>Tipo de gesti&oacute;n</td>
                <td>";
        $buffer.=$this->Regresa_Gestion($data['proy_gestion'],$ayudas);
        $buffer.="</td>
               </tr>";
        if($area_id==7) {
            $buffer.="
                <tr>
                <td>Apoyos</td>
                <td>";
            $buffer.=$this->Regresa_Apoyos($data['apoyo_prd'],$data['apoyo_log'],$data['apoyo_pro'],$data['apoyo_art'],$data['apoyo_eco'],$ayudas);
            $buffer.="</td>
                    </tr>";
        }
        $buffer.="
                <tr>
                    <td>Tipo de actividad </td>
                    <td>";
        $buffer.=$this->Regresa_Evento($data['proy_tevento'],$ayudas);
        $buffer.="</td>
                  </tr>
                  <tr>
                        <td>Observaciones</td>
                        <td><textarea cols=\"60\"   rows=\"4\" wrap=\"ON\" name=\"proy_nota\">".$data['proy_nota']."</textarea>
                        <a href=\"javascript:showDialog('AYUDA','".$ayudas[13]."','prompt');\">[?]</a></td>
                  </tr>
                  <tr>
                        <td>Recaudaci&oacute;n</td>
                        <td>";
        $buffer.=$this->Regresa_Recaudacion($data['proy_recauda_evento'],$ayudas);
        $buffer.="</td>
                  </tr>
                  <tr>
                        <td>Monto de recaudaci&oacute;n</td>
                        <td>$<input type=\"text\" value=\"".$data['proy_recauda_monto']."\" size=\"10\" maxlength=\"10\" name=\"proy_recauda_monto\">pesos-M.N.
                        <a href=\"javascript:showDialog('AYUDA','".$ayudas[15]."','prompt');\">[?]</a></td>
                  </tr>
                        <tr>
                        <th class=\"tdverde\" colspan=2>Estad&iacute;stica de poblaci&oacute;n beneficiada por esta actividad </td>
                        </tr>
                        <tr>
                        <td>Tipo de p&uacute;blico al cual se dirige el evento </td>
                        <td>";
        $buffer.=$this->Regresa_Clasif_Publi($data['proy_clasipubli'],$ayudas);
        $buffer.="</td>
                        </tr>
            			<tr>
                            <td>Frecuencia</td>
                            <td><input type='text' name='frecuencia' id='frecuencia' value='".$data['frecuencia']."' size='5'>&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[17]."','prompt');\">[?]</a></td>
            			</tr>
                        <tr>
                        <th colspan=2>";
        $buffer.=$this->Regresa_Proy_Poblacion($data['db'],$data['proy_poblacion'],$data['proy_asis_m0_15'],$data['proy_asis_m16_18'],$data['proy_asis_m19_30'],$data['proy_asis_m31_64'],$data['proy_asis_m_my65'],$data['proy_asis_cap_esp_m'],$data['proy_asis_m_indig'],$data['proy_asis_m_total'],$data['proy_asis_h0_15'],$data['proy_asis_h16_18'],$data['proy_asis_h19_30'],$data['proy_asis_h31_64'],$data['proy_asis_h_my65'],$data['proy_asis_cap_esp_h'],$data['proy_asis_h_indig'],$data['proy_asis_h_total'],$ayudas);
        $buffer.="</th>
                </tr>
                <tr>
            <th class='tdverde' colspan=2>Disciplinas y &aacute;mbitos tem&aacute;ticos en las cuales incide la actividad (solo de aplicar)</td>
            </tr>
            <tr>
            <th colspan=2>";
        $buffer.=$this->Temas($data['proy_tema_1'],$data['proy_tema_2'],$data['proy_tema_3'],$data['proy_tema_4'],$data['proy_tema_5'],$data['proy_tema_6'],$data['proy_tema_7'],$data['proy_tema_8'],$data['proy_tema_9'],$data['proy_tema_10'],$data['proy_tema_11'],$data['proy_tema_12'],$data['proy_tema_13'],$data['proy_tema_14'],$data['proy_tema_15'],$data['proy_tema_16'],$ayudas);
        $buffer.="<tr>
                <th class='tdverde' colspan=2>Ejes de pol&iacute;ticas sociales transversales en los cuales esta actividad incide</td>
                </tr>
                <tr>
                <th colspan=2>";
        $buffer.=$this->Regresa_Polticas($data['proy_eje_df_1'],$data['proy_eje_df_2'],$data['proy_eje_df_3'],$data['proy_eje_df_4'],$data['proy_eje_df_5'],$data['proy_eje_df_6'],$data['proy_eje_df_7'],$data['proy_eje_df_8'],$data['proy_eje_df_9'],$data['proy_eje_df_10'],$ayudas);
        $tmp_r_1='';
        $tmp_r_2='';
        $tmp_r_3='';
        if($data['relevancia']==1)
            $tmp_r_1=' CHECKED ';
        if($data['relevancia']==2)
            $tmp_r_2=' CHECKED ';
        if($data['relevancia']==0)
            $tmp_r_3=' CHECKED ';
        $tmp_c1='';
        $tmp_c2='';
        $tmp_c3='';
        $tmp_c4='';
        $tmp_c5='';
        $tmp_c6='';

        if($data['coordinacion1']==1)
            $tmp_c1=" checked ";
        if($data['coordinacion2']==1)
            $tmp_c2=" checked ";
        if($data['coordinacion3']==1)
            $tmp_c3=" checked ";
        if($data['coordinacion4']==1)
            $tmp_c4=" checked ";
        if($data['coordinacion5']==1)
            $tmp_c5=" checked ";
        if($data['coordinacion6']==1)
            $tmp_c6=" checked ";

        $buffer.="</th></tr>
	        <tr>
                <th class='tdverde' colspan=2>En Coordinaci&oacute;n</td>
		    </tr>
	        <tr>
		    <td>Esta actividad se hizo en coordinaci&oacute;n con:</td>
			<td>
	            <table width='100%' align='center'>
		        <tr>
			    <td>
				<input type='checkbox' name='coordinacion1' id='coordinacion1' ".$tmp_c1." value='1'>Delegaciones&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[58]."','prompt');\">[?]</a><br>
                                <input type='checkbox' name='coordinacion2' id='coordinacion2' ".$tmp_c2." value='1'>Embajada o Representaci&oacute;n Internacional&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[59]."','prompt');\">[?]</a><br>
                                <input type='checkbox' name='coordinacion3' id='coordinacion3' ".$tmp_c3." value='1'>Gobierno Local&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[60]."','prompt');\">[?]</a><br>
                                <input type='checkbox' name='coordinacion4' id='coordinacion4' ".$tmp_c4." value='1'>Gobierno Federal&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[61]."','prompt');\">[?]</a><br>
                                <input type='checkbox' name='coordinacion5' id='coordinacion5' ".$tmp_c5." value='1'>Academia&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[62]."','prompt');\">[?]</a><br>
                                <input type='checkbox' name='coordinacion6' id='coordinacion6' ".$tmp_c6." value='1'>Organizaci&oacute;n Civil&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[63]."','prompt');\">[?]</a></td>
                                </tr>
	            <tr>
		        <td align='left'>Por favor describa brevemente</td>
			    </tr>
	            <tr>
		        <td align='left'><textarea name='coordinacion' id='coordinacion' cols='80' rows='5'>".$data['coordinacion']."</textarea>&nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[64]."','prompt');\">[?]</a></td>
                    </tr>
	            </table>
		    </td>
			</tr>
	        <tr>
                <th class='tdverde' colspan=2>Actividad Relevante</td>
		    </tr>
		    <tr>
	        <td>Relevancia</td>
		    <td>
			&nbsp;Nivel I&nbsp;<input type='radio' name='relevancia' id='relevancia' value='1' ".$tmp_r_1.">
	        &nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[65]."','prompt');\">[?]</a>
	        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    Nivel 2&nbsp;<input type='radio' name='relevancia' id='relevancia' value='2' ".$tmp_r_2.">
		    &nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[66]."','prompt');\">[?]</a>
	        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    Omitir&nbsp;<input type='radio' name='relevancia' id='relevancia' value='0' ".$tmp_r_3.">
		    &nbsp;<a href=\"javascript:showDialog('AYUDA','".$ayudas[67]."','prompt');\">[?]</a>			
			</td>
			</tr>
                </table>
                <table width='30%' align='center'>
	        <tr>
	        <td align='rigth' width='50%'>&nbsp;";
        if($tipo_vista==1){
            $buffer.="<input type=\"submit\" name=\"boton\" value=\"GRABAR DATOS\" class=\"boton\">&nbsp;";
        }
        $buffer.="</td><td class=\"\" align='left'><input type='button' name='cerrar' value='Cerrar Ventana' onclick='self.close();' class=\"boton\">
        </td>
		    </tr>
	        </table></form>";
        return $buffer;
    }

    function Actualiza_Actividades($db_connect,$data) {
        $id_area=$data['area_id'];
        $id_prog=$data['cat_programa_id'];
        $id_subp=$data['subprograma_id'];
        $id_ano=substr($data['proy_fecha_inicio'],0,4);
        $id_mes=substr($data['proy_fecha_inicio'],5,2);
        $bloquea=$this->Regresa_Esta_Bloqueado($db_connect,$id_area,$id_prog,$id_subp,$id_ano,$id_mes);
        if($bloquea == 0)
        {
            $folio=0;
            $campos='';
            $date=date("Y-m-d H:i:s");
            $upd_checkbox="update proyectos SET
            proy_tema_1=0,proy_tema_2=0,proy_tema_3=0,proy_tema_4=0,proy_tema_5=0,
            proy_tema_6=0,proy_tema_7=0,proy_tema_8=0,proy_tema_9=0,proy_tema_10=0,
            proy_tema_11=0,proy_tema_12=0,proy_tema_13=0,proy_tema_14=0,proy_tema_15=0,
            proy_eje_df_1=0,proy_eje_df_2=0,proy_eje_df_3=0,proy_eje_df_4=0,proy_eje_df_5=0,
            proy_eje_df_6=0,proy_eje_df_7=0,proy_eje_df_8=0,proy_eje_df_9=0,proy_eje_df_10=0,
            coordinacion1=0,coordinacion2=0,coordinacion3=0,coordinacion4=0,coordinacion5=0,coordinacion6=0,apoyo_log=0,apoyo_pro=0,apoyo_art=0,  	apoyo_eco=0,apoyo_prd=0
            where proy_id='".$data['proy_id']."';";
            $this->db->sql_query($upd_checkbox,$db_connect);

            $total_1=$data['proy_asis_m0_15']+$data['proy_asis_m16_18']+$data['proy_asis_m19_30']+$data['proy_asis_m_my65'];
            $total_2=$data['proy_asis_h0_15']+$data['proy_asis_h16_18']+$data['proy_asis_h19_30']+$data['proy_asis_h_my65'];
            $total_3=$total_1 + $total_2;
            $sumproy_poblacion=$total_1 + $total_2;

            $ins="UPDATE proyectos SET  ";
            foreach($_POST as $campo => $valor) {
                if( ($campo!='user_id') && ($campo!='aplicacion') && ($campo!='apli_com') && ($campo!='altev') && ($campo!='proy_coordinacion_id') && ($campo!='proy_area_id') && ($campo!='proy_id') && ($campo!='ano') && ($campo!='mes') && ($campo!='boton') && ($campo!='inv_area')) {
                    $campos.=$campo."='".$valor."',";
                }
            }
            $campos.="proy_capturo_user='".$data['user_id']."',proy_capturo_time='".$date."',proy_capturo_ip='".$_SERVER[REMOTE_ADDR]."',proy_apoyo='0',proy_cecostos='0',proy_monto_asignado='0',proy_monto_ejercido='0.00',proy_coordinacion_id='0000',proy_area_id='".$data['area_id']."',proy_asis_m31_64='0',proy_asis_h31_64='0',proy_asis_m_total='".$total_1."',proy_asis_h31_30='0',proy_asis_h_total='".$total_2."',proy_tinform='00',proy_poblacion='".$sumproy_poblacion."' where proy_id='".$data['proy_id']."';";
            $ins.=$campos;
            if($this->db->sql_query($ins,$db_connect) or die($del))
            {
                $del="DELETE FROM proyectos_historia WHERE proy_id='".$data['proy_id']."';";
                if($this->db->sql_query($del,$db_connect)) {
                    $sql_ins="INSERT INTO proyectos_historia select * from proyectos where proy_id='".$data['proy_id']."';";
                    $this->db->sql_query($sql_ins,$db_connect);
                }
                $folio++;
            }
        }
        else
        {
            $folio = -1;
        }
        return $folio;
    }
    function Regresa_Direccion_Programa($programa_id)
    {
        $direccion='';
        switch($programa_id)
        {
            case 16:
                $direccion='Calzada Ignacio Zaragoza s/n. Col. Fuentes de Zaragoza. Entre las estaciones del Metro Acatitla y Pe&ntilde;&oacute;n Viejo';
                break;
            case 17:
                $direccion='Av. La Turba s/n (interior bosque de Tl&aacute;huac), Col. Miguel Hidalgo, Del. Tl&aacute;huac, C.P. 13200.<br>
                            Mart&iacute;n Gonz&aacute;lez Mercado. ';
                break;
            case 18:
                $direccion='Av. Dr. Gast&oacute;n Melo No 40<br>Poblado de San Antonio Tec&oacute;mitl CP 12100';
                break;
            case 19:
                $direccion='Av. Huitzilihuitl (antes Av. De las Torres). N&uacute;m. 51, Col. Santa Isabel Tola, Delegaci&oacute;n Gustavo A. Madero, C. P. 07010.';
                break;

        }
        return $direccion;
    }

    function Regresa_Esta_Bloqueado($db_connect,$area_id,$programa_id,$subprograma_id,$ano_id,$mes_id)
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
  
  function obtenFormato(){
    return $this->buffer;
  }
}

?>