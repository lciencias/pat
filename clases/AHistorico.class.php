<?php
class AHistorico
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
		$this->buffer=$this->Formulario_Actividades_Historico($this->data);
	}
	function Regresa_Ayudas()
	{
		$array=array();
	    $sql = "SELECT * FROM  cat_ayudas_formato_historico limit 1";
		$res = $this->db->sql_query($sql) or die ("Error en la consulta: ".$sql);
		if($this->db->sql_numrows($res)>0)
	    {
			$array=$this->db->sql_fetchrow($res);
		}
        return $array;
	}

	function Formulario_Actividades_Historico($data)
    {
		$ayudas=$this->Regresa_Ayudas();
        $buffer.="
                <input type='hidden' name='cat_programa_id1' id='cat_programa_id1'  value='".$data['cat_programa_id1']."'>
                <input type='hidden' name='subprograma_id' id='subprograma_id'  value='".($data['subprograma_id'] + 0)."'>
                <input type='hidden' name='inv_area' id='inv_area' value='".$data['inv_area']."'>
                <input type='hidden' name='altev' id='altev' value='2'>";
        $buffer.="
        <table width='98%' align='center' border='0'>
            <tr>
                <td>
                    <table width='100%' align='center' border='0' bordercolor='#cdcdcd'>
                        <tr>
                            <td colspan='3'>Fecha :&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type=\"text\" id=\"cal-field-1\" name=\"fecha_alta\" value=\"".$proy_fecha."\"  $this->stylesc  required=\"yes\">
                            <button  id=\"cal-button-1\">SELECCIONE</button>
                            ".$this->muestraAyuda($ayudas[1])."
                            <script type=\"text/javascript\">
                                Calendar.setup({
                                    inputField    : 'cal-field-1',
                                    ifFormat      : '%Y-%m-%d',
                                    onUpdate      : revisa_fecha_final_Otros,
                                    showsTime     : true,
                                    button        : 'cal-button-1'
                                });
                          </script>
                          &nbsp;&nbsp;<div id='validacacionFecha'></div></td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Taller de Restauraci&oacute;n y Encuadernaci&oacute;n</th>
                        </tr>
                        <tr>
                            <td align='left' width='30%'>Restauraci&oacute;n de: </td>
                            <td align='left' width='15%'><input type='text' $this->stylesc  required='yes' name='taller_restauracion' id='taller_restauracion' value='".($data['taller_restauracion'] + 0)."'></td>
                            <td align='left' width='55%'>documentos, vol&uacute;menes y/o planos, libros
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[2])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Estabilizaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='taller_estabilizacion_planos' id='taller_estabilizacion_planos' value='".($data['taller_estabilizacion_planos'] + 0)."'></td>
                            <td align='left'>documentos, vol&uacute;menes y/o planos, libros
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[3])."</td>
                        </tr>

                        <tr>
                            <td align='left'>Estabilizaci&oacute;n y encuadernaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='taller_estabilizacion' id='taller_estabilizacion' value='".($data['taller_estabilizacion'] + 0)."'></td>
                            <td align='left'>documentos, vol&uacute;menes y/o planos, libros
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[4])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Organizaci&oacute;n documental</th>
                        </tr>
                        <tr>
                            <td align='left'>Ordenaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='documental_ordenacion' id='documental_ordenacion' value='".($data['documental_ordenacion'] + 0)."'></td>
                            <td align='left'>expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[5])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Rotulaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='documental_rotulacion' id='documental_rotulacion' value='".($data['documental_rotulacion'] + 0)."'></td>
                            <td align='left'>expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[6])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Foliaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='documental_folacion' id='documental_folacion' value='".($data['documental_folacion'] + 0)."'></td>
                            <td align='left'>expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[7])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Inventario de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='documental_inventario' id='documental_inventario' value='".($data['documental_inventario'] + 0)."'></td>
                            <td align='left'>expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[8])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Descripci&oacute;n y automatizaci&oacute;n de documentos</th>
                        </tr>
                        <tr>
                            <td align='left'>Descripci&oacute;n y captura de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='automatizados_captura' id='automatizados_captura' value='".($data['automatizados_captura'] + 0)."'></td>
                            <td align='left'>expedientes, vol&uacute;menes y/o planos&nbsp;&nbsp;".$this->muestraAyuda($ayudas[9])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Revisi&oacute;n y complementaci&oacute;n de : </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='automatizados_revision' id='automatizados_revision' value='".($data['automatizados_revision'] + 0)."'></td>
                            <td align='left'>fichas descriptivas&nbsp;&nbsp;".$this->muestraAyuda($ayudas[10])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Cotejo f&iacute;sico de : </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='automatizados_cotejo' id='automatizados_cotejo' value='".($data['automatizados_cotejo'] + 0)."'></td>
                            <td align='left'>expedientes, vol&uacute;menes y/o planos&nbsp;&nbsp;".$this->muestraAyuda($ayudas[11])."</td>

                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Actualizaci&oacute;n del registro central del Archivo</th>
                        </tr>
                        <tr>
                            <td align='left'>Revisi&oacute;n y complementaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='archivo_revision' id='archivo_revision' value='".($data['archivo_revision'] + 0)."'></td>
                            <td align='left'>registros&nbsp;&nbsp;".$this->muestraAyuda($ayudas[12])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Digitalizaci&oacute;n</th>
                        </tr>
                        <tr>
                            <td align='left'>Escan&eacute;o de : </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='digitalizacion_escaneo' id='digitalizacion_escaneo' value='".($data['digitalizacion_escaneo'] + 0)."'></td>
                            <td align='left'>expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[13])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Obtenci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='digitalizacion_obtencion' id='digitalizacion_obtencion' value='".($data['digitalizacion_obtencion'] + 0)."'></td>
                            <td align='left'>im&aacute;genes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[14])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Ensamble de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='digitalizacion_ensamble' id='digitalizacion_ensamble' value='".($data['digitalizacion_ensamble'] + 0)."'></td>
                            <td align='left'>im&aacute;genes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[15])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Sistemas de Informaci&oacute;n</th>
                        </tr>
                        <tr>
                            <td align='left'>Respaldo y control de calidad de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='sis_inf_respaldo' id='sis_inf_respaldo' value='".($data['sis_inf_respaldo'] + 0)."'></td>
                            <td align='left'>im&aacute;genes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[16])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Ordenaci&oacute;n de bibliotecas</th>
                        </tr>
                        <tr>
                            <td align='left'>Se etiquetaron, sellaron e intercalaron : </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='bibliotecas_etiquetaron' id='bibliotecas_etiquetaron' value='".($data['bibliotecas_etiquetaron'] + 0)."'></td>
                            <td align='left'>libros&nbsp;&nbsp;".$this->muestraAyuda($ayudas[17])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Catalogaci&oacute;n de materiales bibliohemerogr&aacute;ficos</th>
                        </tr>
                        <tr>
                            <td align='left'>Catalogaci&oacute;n (de materiales de nuevo ingreso): </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='bibliohemeroteca_catalogo' id='bibliohemeroteca_catalogo' value='".($data['bibliohemeroteca_catalogo'] + 0)."'></td>
                            <td align='left'>libros&nbsp;&nbsp;".$this->muestraAyuda($ayudas[18])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Revisi&oacute;n, cotejo y correcci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='bibliohemeroteca_revision' id='bibliohemeroteca_revision' value='".($data['bibliohemeroteca_revision'] + 0)."'></td>
                            <td align='left'>registros&nbsp;&nbsp;".$this->muestraAyuda($ayudas[19])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Normatividad Archiv&iacute;stica</th>
                        </tr>
                        <tr>
                            <td align='left'>Ordenaci&oacute;on del archivo de tr&aacute;mite del AHDF: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='normatividad_ordenacion' id='normatividad_ordenacion' value='".($data['normatividad_ordenacion'] + 0)."'></td>
                            <td align='left'>documentos y/o expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[20])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Revisi&oacute;n y cotejo: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='normatividad_revision' id='normatividad_revision' value='".($data['normatividad_revision'] + 0)."'></td>
                            <td align='left'>vol&uacute;menes, expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[21])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Valoraci&oacute;n y/o transferencias secundarias: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='normatividad_valoracion' id='normatividad_valoracion' value='".($data['normatividad_valoracion'] + 0)."'></td>
                            <td align='left'>dict&aacute;menes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[22])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Servicios al P&uacute;blico</th>
                        </tr>
                        <tr>
                            <td align='left'>CDs entregados del Cat&aacute;logo Preliminar del AHDF: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='serv_pub_cd' id='serv_pub_cd' value='".($data['serv_pub_cd'] + 0)."'></td>
                            <td align='left'>CD's&nbsp;&nbsp;".$this->muestraAyuda($ayudas[23])."</td>
                        </tr>
                    </table>
                    <br><br>
                    <table width='100%' align='center' border='0' bordercolor='#cdcdcd'>
                        <tr><th colspan='4'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='4' align='center'>Centro de Informaci&oacute;n y Sala de Consulta</th>
                        </tr>
                        <tr>
                            <td width='30%'>Usuarios atendidos en el Centro de Informaci&oacute;n y Sala de Consulta</td>
                            <td width='20%'><input type='text' $this->stylesc  required='yes' name='centro_inf_usuarios' id='centro_inf_usuarios' value='".($data['centro_inf_usuarios'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[24])."
                            </td>
                            <td width='30%'>Servicios de pr&eacute;stamos</td>
                            <td width='20%'><input type='text' $this->stylesc  required='yes' name='centro_prestamos' id='centro_prestamos' value='".($data['centro_prestamos'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[25])."
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>Solicitudes de reproducci&oacute;n documental</td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='centro_reproduccion' id='centro_reproduccion' value='".($data['centro_reproduccion'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[26])."
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>Solicitudes de nuevo ingreso para consultar el acervo</td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='centro_consulta' id='centro_consulta' value='".($data['centro_consulta'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[27])."
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>Solicitudes de b&uacute;squeda de informaci&oacute;n</td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='centro_busqueda' id='centro_busqueda' value='".($data['centro_busqueda'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[28])."
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>Servicios en el centro de informaci&oacute;n</td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='centro_informacion' id='centro_informacion' value='".($data['centro_informacion'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[29])."
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>B&uacute;squedas automatizadas para investigadores</td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='centro_busqueda_automatizada' id='centro_busqueda_automatizada' value='".($data['centro_busqueda_automatizada'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[30])."
                            </td>
                        </tr>
                        <tr>
                            <td>Usuarios Bibliotecas</td>
                            <td><input type='text' $this->stylesc  required='yes' name='centro_bibliotecas_usuarios' id='centro_bibliotecas_usuarios' value='".($data['centro_bibliotecas_usuarios'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[31])."
                            </td>
                            <td>Servicios en Bibliotecas</td>
                            <td><input type='text' $this->stylesc  required='yes' name='centro_biblioteca_busquedas' id='centro_biblioteca_busquedas' value='".($data['centro_biblioteca_busquedas'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[32])."
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2'>".$this->Regresa_Proy_Poblacion($c_proy_poblacion,$c_proy_asis_m0_14,$c_proy_asis_m15_20,$c_proy_asis_m21_65,$c_proy_asis_m66,$c_proy_asis_m_total,$c_proy_asis_h0_14,$c_proy_asis_h15_20,$c_proy_asis_h21_65,$c_proy_asis_h66,$c_proy_asis_h_total,1,$ayudas)."
                            </td>
                            <td colspan='2'>&nbsp;</td>
                        </tr>
                        <tr><th colspan='4'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='4' align='center'>Actividades difusi&oacute;n Patrimonial Documental</th>
                        </tr>
                        <tr>
                            <td>Personas atendidas en visitas guiadas</td>
                            <td><input type='text' $this->stylesc  required='yes' name='personas_atendidas_en_visitas' id='personas_atendidas_en_visitas' value='".($data['personas_atendidas_en_visitas'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[44])."
                            </td>
                            <td>Visitas guiadas</td>
                            <td><input type='text' $this->stylesc  required='yes' name='visitas_guiadas' id='visitas_guiadas' value='".($data['visitas_guiadas'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[45])."
                            </td>
                        </tr>
                        <tr>
                            <td>Personas atendidas en Congresos</td>
                            <td><input type='text' $this->stylesc  required='yes' name='personas_atendidas_en_congresos' id='personas_atendidas_en_congresos' value='".($data['personas_atendidas_en_congresos'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[46])."
                            </td>
                            <td>Congresos</td>
                            <td><input type='text' $this->stylesc  required='yes' name='congresos' id='congresos' value='".($data['congresos'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[47])."
                            </td>
                        </tr>
                        <tr>
                            <td>Personas atendidas en Talleres</td>
                            <td><input type='text' $this->stylesc  required='yes' name='personas_atendidas_en_talleres' id='personas_atendidas_en_talleres' value='".($data['personas_atendidas_en_talleres'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[48])."
                            </td>
                            <td>Talleres</td>
                            <td><input type='text' $this->stylesc  required='yes' name='talleres' id='talleres' value='".($data['talleres'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[49])."
                            </td>
                        </tr>
                        <tr>
                            <td>Personal de otros archivos que recibieron orientaci&oacute;n</td>
                            <td><input type='text' $this->stylesc  required='yes' name='personal_otro_archivo' id='personal_otro_archivo' value='".($data['personal_otro_archivo'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[50])."
                            </td>
                            <td>Orientaci&oacute;n t&eacute;cnica a Instituciones</td>
                            <td><input type='text' $this->stylesc  required='yes' name='orientacion_tecnica' id='orientacion_tecnica' value='".($data['orientacion_tecnica'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[51])."
                            </td>
                        </tr>
                        <tr>
                            <td>Personas atendidas</td>
                            <td><input type='text' $this->stylesc  required='yes' name='personas_atendidas' id='personas_atendidas' value='".($data['personas_atendidas'] + 0)."' onblur='sumam_historico();' >
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[52])."
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>TOTAL GENERAL DE PERSONAS ATENDIDAS</td>
                            <td><input type='text' $this->stylesc  required='yes' name='total_persona_atendidas' id='total_persona_atendidas' value='".($data['total_persona_atendidas'] + 0)."' readonly>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[53])."
                            </td>
                            <td>TOTAL GENERAL DE SERVICIOS</td>
                            <td><input type='text' $this->stylesc  required='yes' name='total_n_servicios' id='total_n_servicios' value='".($data['total_n_servicios'] + 0)."' readonly>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[54])."
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2'>".$this->Regresa_Proy_Poblacion($a_proy_poblacion,$a_proy_asis_m0_14,$a_proy_asis_m15_20,$a_proy_asis_m21_65,$a_proy_asis_m66,$a_proy_asis_m_total,$a_proy_asis_h0_14,$a_proy_asis_h15_20,$a_proy_asis_h21_65,$a_proy_asis_h66,$a_proy_asis_h_total,2,$ayudas)."
                            </td>
                            <td colspan='2'>&nbsp;</td>
                        </tr>
                        <tr>
                        <td valign='middle'>Observaciones:</td>
                        <td colspan='3'><textarea name='observaciones' required='no' id='observaciones' $this->stylest >".$data['observaciones']."</textarea>
                        &nbsp;&nbsp;".$this->muestraAyuda($ayudas[55])."
                        </td>
                        </tr>
						<tr>
			                <th class='tdverde' colspan='4'>Actividad Relevante</td>
				        </tr>
                       <tr>
                        <td>Relevancia</td>
                        <td colspan='2'>
                        &nbsp;Nivel 1&nbsp;<input type='radio' name='relevancia' id='relevancia' value='1' >
                        &nbsp;".$this->muestraAyuda($ayudas[56])."
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Nivel 2&nbsp;<input type='radio' name='relevancia' id='relevancia' value='2' >
                        &nbsp;".$this->muestraAyuda($ayudas[57])."
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Omitir&nbsp;<input type='radio' name='relevancia' id='relevancia' value='0' checked>
                        &nbsp;".$this->muestraAyuda($ayudas[58])."
                        </td>
                        </tr>
						<tr>
				            <td>Frecuencia</td>
				            <td><input type='text' name='frecuencia' id='frecuencia' value='1' $this->stylesc >&nbsp;".$this->muestraAyuda($ayudas[59])."
				            </td>
						</tr>					
						</table>
                </td>
            </tr>
            <tr>
                <td align='center'><input type='submit' name='boton' value='GRABAR DATOS'></td></tr>
        </table>";
        return $buffer;
    }
    function Regresa_Proy_Poblacion($proy_poblacion,$proy_asis_m0_14,$proy_asis_m15_20,$proy_asis_m21_65,$proy_asis_m66,$proy_asis_m_total,$proy_asis_h0_14,$proy_asis_h15_20,$proy_asis_h21_65,$proy_asis_h66,$proy_asis_h_total,$tmp_opc,$ayudas)
    {
        $tmp='c_';
        $blur_m="onBlur='sumamAHC() , sumaAHC()'";
        $blur_h="onBlur='sumahAHC() , sumaAHC()'";
        if($tmp_opc == 2)
        {
            $blur_m="onBlur='sumamAHA() , sumaAHA()'";
            $blur_h="onBlur='sumahAHA() , sumaAHA()'";
            $tmp='a_';
        }

        if($proy_poblacion=='')
        {
            $proy_poblacion='0';
        }
        $dproy_poblacion="<input type=\"text\" value=\"$proy_poblacion\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_poblacion\" id=\"".$tmp."proy_poblacion\" readonly>
        ".$this->muestraAyuda($ayudas[43]);

        if($proy_asis_m0_14==''){$proy_asis_m0_14='0';}
        $dproy_asis_m0_14="<input type=\"text\" value=\"$proy_asis_m0_14\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_asis_m0_14\" required=\"yes\" authtype=\"_entero\" ".$blur_m.">
        ".$this->muestraAyuda($ayudas[33]);

        if($proy_asis_m15_20==''){$proy_asis_m15_20='0';}
        $dproy_asis_m15_20="<input type=\"text\" value=\"$proy_asis_m15_20\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_asis_m15_20\" required=\"yes\" authtype=\"_entero\" ".$blur_m.">
        ".$this->muestraAyuda($ayudas[35]);

        if($proy_asis_m21_65==''){$proy_asis_m21_65='0';}
        $dproy_asis_m21_65="<input type=\"text\" value=\"$proy_asis_m21_65\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_asis_m21_65\" required=\"yes\" authtype=\"_entero\" ".$blur_m.">
        ".$this->muestraAyuda($ayudas[37]);

        if($proy_asis_m66==''){$proy_asis_m66='0';}
        $dproy_asis_m66="<input type=\"text\" value=\"$proy_asis_m66\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_asis_m66\" required=\"yes\" authtype=\"_entero\" ".$blur_m.">
        ".$this->muestraAyuda($ayudas[39]);

        if($proy_asis_m_total==''){$proy_asis_m_total='0';}
        $dproy_asis_m_total="<input type=\"text\" value=\"$proy_asis_m_total\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_asis_m_total\" readonly class=dis>
        ".$this->muestraAyuda($ayudas[41]);



        if($proy_asis_h0_14==''){$proy_asis_h0_14='0';}
        $dproy_asis_h0_14="<input type=\"text\" value=\"$proy_asis_h0_14\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_asis_h0_14\" required=\"yes\" authtype=\"_entero\" ".$blur_h.">
        ".$this->muestraAyuda($ayudas[34]);

        if($proy_asis_h15_20==''){$proy_asis_h15_20='0';}
        $dproy_asis_h15_20="<input type=\"text\" value=\"$proy_asis_h15_20\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_asis_h15_20\" required=\"yes\" authtype=\"_entero\" ".$blur_h.">
        ".$this->muestraAyuda($ayudas[36]);

        if($proy_asis_h21_65==''){$proy_asis_h21_65='0';}
        $dproy_asis_h21_65="<input type=\"text\" value=\"$proy_asis_h21_65\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_asis_h21_65\" required=\"yes\" authtype=\"_entero\" ".$blur_h.">
        ".$this->muestraAyuda($ayudas[38]);

        if($proy_asis_h66==''){$proy_asis_h66='0';}
        $dproy_asis_h66="<input type=\"text\" value=\"$proy_asis_h66\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_asis_h66\" required=\"yes\" authtype=\"_entero\" ".$blur_h.">
        ".$this->muestraAyuda($ayudas[40]);

        if($proy_asis_h_total==''){$proy_asis_h_total='0';}
        $dproy_asis_h_total="<input type=\"text\" value=\"$proy_asis_h_total\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_asis_h_total\" readonly>
        ".$this->muestraAyuda($ayudas[42]);

        $proy_poblacion = $proy_asis_m_total + $proy_asis_h_total;
	    $dproy_poblacion="<input type=\"text\" value=\"$proy_poblacion\" $this->stylesc  maxlength=\"10\" name=\"".$tmp."proy_poblacion\" id=\"".$tmp."proy_poblacion\" readonly>
		".$this->muestraAyuda($ayudas[43]);
        $buf="<table width='100%' cellpadding=\"2\" cellspacing=\"2\" border=\"0\">
                <tr>
                    <td><p align=center>Grupo</p></td>
                    <td><p align=center>Mujeres</p></td>
                    <td><p align=center>Hombres</p></td>
                </tr>
                <tr>
                    <td>De 0 a 14 AÑOS </td>
                    <td>$dproy_asis_m0_14</td>
                    <td>$dproy_asis_h0_14</td>
                </tr>
                <tr>
                    <td>De 15 a 20 AÑOS </td>
                    <td>$dproy_asis_m15_20</td>
                    <td>$dproy_asis_h15_20</td>
                </tr>
                <tr>
                    <td>De 21 a 65 AÑOS </td>
                    <td>$dproy_asis_m21_65</td>
                    <td>$dproy_asis_h21_65</td>
                </tr>";
                $buf.="<tr>
                    <td>Mayores de 65 AÑOS</td>
                    <td>$dproy_asis_m66</td>
                    <td>$dproy_asis_h66</td>
                </tr>
                <tr>
                    <td>Total por sexo</td>
                    <td>$dproy_asis_m_total</td>
                    <td>$dproy_asis_h_total</td>
                </tr>
                <tr>
                    <td><b>Poblaci&oacute;n total</b></td>
                    <td colspan=2><p align=center>$dproy_poblacion</p></td>
                </tr>
            </table>";
            return $buf;
    }

    function Consulta_Actividades_Historico($data,$array_programas,$path_sys,$array_areas)
	{
		$user_id=$data['user_id'];
        $mes_id=$data['mesele'];
		$ano_id=$data['anoele'];
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

		$buffer="No hay actividades registradas en el mes seleccionado";
		if($mes_id == 0)
			$filtro=" WHERE substr(fecha_alta,1,4)='".$ano_id."' AND area_id='".$data['inv_area']."' " ;
		else
			$filtro=" WHERE substr(fecha_alta,1,7)='".$ano_id."-".$mes_id."' AND area_id='".$data['inv_area']."' " ;
		if($data['cat_programa_id1']>0)
		{
			$filtro.=" AND programa_id = ".$data['cat_programa_id1']." ";
		}
		$sql_count="SELECT status,count(status) as total FROM proyectos_ahistoricos ".$filtro." GROUP BY status ORDER BY status;";
		$res_count=$this->db->sql_query($sql_count,$db_connect);
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
        $sql="SELECT folio_id,area_id,programa_id,fecha_alta,observaciones,status,subprograma_id FROM proyectos_ahistoricos ".$filtro." ORDER BY timestamp DESC";
        $res=$this->db->sql_query($sql,$db_connect);
        $num = $this->db->sql_numrows($res);
        if($num > 0) {
            $buffer.="<br><table width='100%' border='0' align='center' class='tablesorter'>";
            $buffer.="<thead>
						<tr bgcolor='#002000'>
							<th width='6%' align='center'>Folio</th>
							<th width='18%' align='center'>Programa</th>
							<th width='38%' align='center'>Observaciones</th>
							<th width='15%' align='center'>Fecha</th>
							<th width='14%'>Estatus</th>
							<th width='3%'  align='center'>A</th>
							<th width='3%'  align='center'>C</th>
                            <th width='5%'  align='center'></th>
						</tr></thead><tbody>";
            while(list($folio_id,$area_id,$programa_id,$timestamp,$tema,$status,$subprograma_id) = $this->db->sql_fetchrow($res))
			{
				$bloqueado=$this->Regresa_Esta_Bloqueado($db_connect,$area_id,$programa_id,$subprograma_id,substr($timestamp,0,4),substr($timestamp,5,2));
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
                        $tmp_status_6='';
						break;
					}
					case 'EN PROCESO':
					{
						$tmp_status_2=' SELECTED ';
						$tmp_status_1='';
						$tmp_status_3='';
						$tmp_status_4='';
						$tmp_status_5='';
                        $tmp_status_6='';
						break;
					}
					case 'TERMINADA':
					{
						$tmp_status_3=' SELECTED ';
						$tmp_status_2='';
						$tmp_status_1='';
						$tmp_status_4='';
						$tmp_status_5='';
                        $tmp_status_6='';
						break;
					}
					case 'CANCELADA':
					{
						$tmp_status_4=' SELECTED ';
						$tmp_status_1='';
						$tmp_status_2='';
						$tmp_status_3='';
						$tmp_status_5='';
                        $tmp_status_6='';
						break;
					}
					case 'DE BAJA':
					{
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
				$select_status="<select name='".$tmp_s."' id='".$tmp_s."' class='status' onChange=\"elimina_institucional('$area_id','$programa_id','$folio_id','$tmp','$tmp_s');\">
					<option value=''></option>
                       <option value='INICIADO' ".$tmp_status_1.">INICIADO</option>
                       <option value='EN PROCESO' ".$tmp_status_2.">EN PROCESO</option>
                       <option value='TERMINADA' ".$tmp_status_3.">TERMINADA</option>
                       <option value='CANCELADA' ".$tmp_status_4.">CANCELADA</option>
                       <option value='DE BAJA' ".$tmp_status_5.">DE BAJA</option>
                        <option value='SUSPENDIDA' ".$tmp_status_6.">SUSPENDIDA</option></select></form>";

                $buffer.="<tr>
					<th align='left'>".str_pad($folio_id,5,'0',STR_PAD_LEFT)."</th>
					<th align='left'>".$array_programas[$programa_id]."</th>
					<th align='left'>".substr($tema,0,50)."</th>
					<th align='left'>".$timestamp."</th>
					<th align='left'>".$select_status."</th>					
					<th align='center'>&nbsp;";
				if($bloqueado == 0)
				{
					$buffer.="<a href=\"javascript:lanza_ventana('$area_id','$programa_id','$folio_id','$ano_id','$mes_id','1','$user_id');\"><img src='imagenes/vcard.png' width='16' height='16' border='0'></a>";
				}
				$buffer.="</th>
					<th align='center'><a href=\"javascript:lanza_ventana('$area_id','$programa_id','$folio_id','$ano_id','$mes_id','2','$user_id');\"><img src='imagenes/magnifier.png' width='16' height='16' border='0'></a></th>
                    <th align='center'><div id='".$tmp."'></div></th></tr>";
            }
            $buffer.="</tbody><thead><tr><th colspan='8' width='100%' align='center'>Total de Registros:  ".$num."</th></tr></thead></table>";
        }
        return $buffer;
    }

    function Actualiza_Actividades_Historico($conn,$data)
    {
        $data['subprograma_id']=$data['subprograma_id'] + 0;
        $folio=0;
		$bloqueado=$this->Regresa_Esta_Bloqueado($conn,$data['inv_area'],$data['cat_programa_id'],$data['subprograma_id'],substr($data['fecha_alta'],0,4),substr($data['fecha_alta'],5,2));
		if($bloqueado == 0)
		{
			$date=date("Y-m-d H:i:s");
		    foreach($data as $clave => $valor)
			{
				if( ($clave!='aplicacion') && ($clave!='apli_com') && ($clave!='altev') && ($clave!='boton') && ($clave!='folio_id') && ($clave!='user_id') && ($clave!='id') && ($clave!='ano') && ($clave!='mes') && ($clave!='inv_area') )
	            {
		            if($clave=='inv_area')  $clave='area_id';
			        if($clave=='cat_programa_id')  $clave='programa_id';
                    $campos.=$clave." = '".$valor."',";
	            }
		    }
	        $campos.="timestamp='".date('Y-m-d H:i:s')."' ";
		    $ins="UPDATE proyectos_ahistoricos  SET ".$campos." WHERE folio_id=".$data['folio_id'].";";
			if($this->db->sql_query($ins,$conn) or die ($ins))
				$folio++;
		}
		else
		{
			$folio = -1;
		}
        return $folio;
    }


    function Inserta_Actividades_Historico($conn,$data)
    {
        $folio=0;
	    $campos='';
		$valores='';
		$bloqueado=$this->Regresa_Esta_Bloqueado($conn,$data['inv_area'],$data['cat_programa_id1'],$data['subprograma_id'],substr($data['fecha_alta'],0,4),substr($data['fecha_alta'],5,2));
		if($bloqueado == 0)
		{
	        foreach($data as $clave => $valor)
		    {
			    if( ($clave!='aplicacion') && ($clave!='apli_com') && ($clave!='altev') && ($clave!='boton') && ($clave!='ficha')&& ($clave!='subprograma_id') )
				{
	                if($clave=='inv_area')  $clave='area_id';
		            if($clave=='cat_programa_id1')  $clave='programa_id';
			        $campos.=$clave.',';
				    $valores.="'".$valor."',";
	            }
		    }
	        $campos.="status";
		    $valores.="'INICIADO'";
	        $ins="INSERT INTO proyectos_ahistoricos (".$campos.") VALUES (".$valores.");";        
		    if($this->db->sql_query($ins,$conn))
			{
	            $folio=mysql_insert_id();
		    }
		}
		else
		{
			$folio = -1;
		}
        return $folio;
    }
    function Elimina_Actividades_Historico($area_id,$programa_id,$folio_id,$status)
    {
        $reg="La actividad no se elimino";
        $del="UPDATE proyectos_ahistoricos SET status='".$status."' WHERE folio_id=".$folio_id.";";
        if($this->db->sql_query($del))
        {
            $reg="<font color='#800000'>".$status."</font>";
        }
        return $reg;
    }
    function Mostrar_Actividades_Historico($data,$tipo_vista,$ano,$mes,$user_id)
    {
		$ayudas=$this->Regresa_Ayudas();
        $disabled='';
        if($tipo_vista == 2)
            $disabled=' disabled = disabled';
        $buffer="<form name='form1' method='post' action='actualiza_eventos.php'>
					<input type='hidden' name='area_id' id='area_id' value='".$data['area_id']."'>
					<input type='hidden' name='inv_area' id='inv_area' value='".$data['area_id']."'>
					<input type='hidden' name='programa_id' id='programa_id' value='".$data['programa_id']."'>
					<input type='hidden' name='cat_programa_id' id='cat_programa_id' value='".$data['programa_id']."'>
                    <input type='hidden' name='subprograma_id' id='subprograma_id'  value='".$data['subprograma_id']."'>
					<input type='hidden' name='ano' id='ano' value='".$ano."'>
					<input type='hidden' name='mes' id='mes' value='".$mes."'>
                    <input type='hidden' name='folio_id' id='folio_id' value='".$data['folio_id']."'>";
          $buffer.="<table width='98%' align='center' border='0'>
            <tr>
                <td>
                    <table width='100%' align='center' border='0' bordercolor='#cdcdcd'>
                        <tr>
                            <td colspan='3'>Fecha :&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type=\"text\" id=\"cal-field-1\" name=\"fecha_alta\" value=\"".substr($data['fecha_alta'],0,10)."\"  $this->stylesc  required=\"yes\">
                            <button  id=\"cal-button-1\">SELECCIONE</button>
                            ".$this->muestraAyuda($ayudas[1])."
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
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Taller de Restauraci&oacute;n y Encuadernaci&oacute;n</th>
                        </tr>
                        <tr>
                            <td align='left' width='30%'>Restauraci&oacute;n de: </td>
                            <td align='left' width='15%'><input type='text' $this->stylesc  required='yes' name='taller_restauracion' id='taller_restauracion' value='".($data['taller_restauracion'] + 0)."'></td>
                            <td align='left' width='55%'>documentos, vol&uacute;menes y/o planos, libros
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[2])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Estabilizaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='taller_estabilizacion_planos' id='taller_estabilizacion_planos' value='".($data['taller_estabilizacion_planos'] + 0)."'></td>
                            <td align='left'>documentos, vol&uacute;menes y/o planos, libros
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[3])."</td>
                        </tr>

                        <tr>
                            <td align='left'>Estabilizaci&oacute;n y encuadernaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='taller_estabilizacion' id='taller_estabilizacion' value='".($data['taller_estabilizacion'] + 0)."'></td>
                            <td align='left'>documentos, vol&uacute;menes y/o planos, libros
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[4])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Organizaci&oacute;n documental</th>
                        </tr>
                        <tr>
                            <td align='left'>Ordenaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='documental_ordenacion' id='documental_ordenacion' value='".($data['documental_ordenacion'] + 0)."'></td>
                            <td align='left'>expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[5])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Rotulaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='documental_rotulacion' id='documental_rotulacion' value='".($data['documental_rotulacion'] + 0)."'></td>
                            <td align='left'>expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[6])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Foliaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='documental_folacion' id='documental_folacion' value='".($data['documental_folacion'] + 0)."'></td>
                            <td align='left'>expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[7])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Inventario de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='documental_inventario' id='documental_inventario' value='".($data['documental_inventario'] + 0)."'></td>
                            <td align='left'>expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[8])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Descripci&oacute;n y automatizaci&oacute;n de documentos</th>
                        </tr>
                        <tr>
                            <td align='left'>Descripci&oacute;n y captura de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='automatizados_captura' id='automatizados_captura' value='".($data['automatizados_captura'] + 0)."'></td>
                            <td align='left'>expedientes, vol&uacute;menes y/o planos&nbsp;&nbsp;".$this->muestraAyuda($ayudas[9])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Revisi&oacute;n y complementaci&oacute;n de : </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='automatizados_revision' id='automatizados_revision' value='".($data['automatizados_revision'] + 0)."'></td>
                            <td align='left'>fichas descriptivas&nbsp;&nbsp;".$this->muestraAyuda($ayudas[10])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Cotejo f&iacute;sico de : </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='automatizados_cotejo' id='automatizados_cotejo' value='".($data['automatizados_cotejo'] + 0)."'></td>
                            <td align='left'>expedientes, vol&uacute;menes y/o planos&nbsp;&nbsp;".$this->muestraAyuda($ayudas[11])."</td>

                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Actualizaci&oacute;n del registro central del Archivo</th>
                        </tr>
                        <tr>
                            <td align='left'>Revisi&oacute;n y complementaci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='archivo_revision' id='archivo_revision' value='".($data['archivo_revision'] + 0)."'></td>
                            <td align='left'>registros&nbsp;&nbsp;".$this->muestraAyuda($ayudas[12])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Digitalizaci&oacute;n</th>
                        </tr>
                        <tr>
                            <td align='left'>Escan&eacute;o de : </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='digitalizacion_escaneo' id='digitalizacion_escaneo' value='".($data['digitalizacion_escaneo'] + 0)."'></td>
                            <td align='left'>expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[13])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Obtenci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='digitalizacion_obtencion' id='digitalizacion_obtencion' value='".($data['digitalizacion_obtencion'] + 0)."'></td>
                            <td align='left'>im&aacute;genes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[14])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Ensamble de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='digitalizacion_ensamble' id='digitalizacion_ensamble' value='".($data['digitalizacion_ensamble'] + 0)."'></td>
                            <td align='left'>im&aacute;genes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[15])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Sistemas de Informaci&oacute;n</th>
                        </tr>
                        <tr>
                            <td align='left'>Respaldo y control de calidad de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='sis_inf_respaldo' id='sis_inf_respaldo' value='".($data['sis_inf_respaldo'] + 0)."'></td>
                            <td align='left'>im&aacute;genes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[16])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Ordenaci&oacute;n de bibliotecas</th>
                        </tr>
                        <tr>
                            <td align='left'>Se etiquetaron, sellaron e intercalaron : </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='bibliotecas_etiquetaron' id='bibliotecas_etiquetaron' value='".($data['bibliotecas_etiquetaron'] + 0)."'></td>
                            <td align='left'>libros&nbsp;&nbsp;".$this->muestraAyuda($ayudas[17])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Catalogaci&oacute;n de materiales bibliohemerogr&aacute;ficos</th>
                        </tr>
                        <tr>
                            <td align='left'>Catalogaci&oacute;n (de materiales de nuevo ingreso): </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='bibliohemeroteca_catalogo' id='bibliohemeroteca_catalogo' value='".($data['bibliohemeroteca_catalogo'] + 0)."'></td>
                            <td align='left'>libros&nbsp;&nbsp;".$this->muestraAyuda($ayudas[18])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Revisi&oacute;n, cotejo y correcci&oacute;n de: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='bibliohemeroteca_revision' id='bibliohemeroteca_revision' value='".($data['bibliohemeroteca_revision'] + 0)."'></td>
                            <td align='left'>registros&nbsp;&nbsp;".$this->muestraAyuda($ayudas[19])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Normatividad Archiv&iacute;stica</th>
                        </tr>
                        <tr>
                            <td align='left'>Ordenaci&oacute;on del archivo de tr&aacute;mite del AHDF: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='normatividad_ordenacion' id='normatividad_ordenacion' value='".($data['normatividad_ordenacion'] + 0)."'></td>
                            <td align='left'>documentos y/o expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[20])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Revisi&oacute;n y cotejo: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='normatividad_revision' id='normatividad_revision' value='".($data['normatividad_revision'] + 0)."'></td>
                            <td align='left'>vol&uacute;menes, expedientes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[21])."</td>
                        </tr>
                        <tr>
                            <td align='left'>Valoraci&oacute;n y/o transferencias secundarias: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='normatividad_valoracion' id='normatividad_valoracion' value='".($data['normatividad_valoracion'] + 0)."'></td>
                            <td align='left'>dict&aacute;menes&nbsp;&nbsp;".$this->muestraAyuda($ayudas[22])."</td>
                        </tr>
                        <tr><th colspan='3'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='3' align='center'>Servicios al P&uacute;blico</th>
                        </tr>
                        <tr>
                            <td align='left'>CDs entregados del Cat&aacute;logo Preliminar del AHDF: </td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='serv_pub_cd' id='serv_pub_cd' value='".($data['serv_pub_cd'] + 0)."'></td>
                            <td align='left'>CD's&nbsp;&nbsp;".$this->muestraAyuda($ayudas[23])."</td>
                        </tr>
                    </table>
                    <br><br>
                    <table width='100%' align='center' border='0' bordercolor='#cdcdcd'>
                        <tr><th colspan='4'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='4' align='center'>Centro de Informaci&oacute;n y Sala de Consulta</th>
                        </tr>
                        <tr>
                            <td width='30%'>Usuarios atendidos en el Centro de Informaci&oacute;n y Sala de Consulta</td>
                            <td width='20%'><input type='text' $this->stylesc  required='yes' name='centro_inf_usuarios' id='centro_inf_usuarios' value='".($data['centro_inf_usuarios'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[24])."
                            </td>
                            <td width='30%'>Servicios de pr&eacute;stamos</td>
                            <td width='20%'><input type='text' $this->stylesc  required='yes' name='centro_prestamos' id='centro_prestamos' value='".($data['centro_prestamos'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[25])."
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>Solicitudes de reproducci&oacute;n documental</td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='centro_reproduccion' id='centro_reproduccion' value='".($data['centro_reproduccion'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[26])."
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>Solicitudes de consulta de acervo</td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='centro_consulta' id='centro_consulta' value='".($data['centro_consulta'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[27])."
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>Solicitudes de b&uacute;squeda de informaci&oacute;n</td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='centro_busqueda' id='centro_busqueda' value='".($data['centro_busqueda'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[28])."
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>Servicios en el centro de informaci&oacute;n</td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='centro_informacion' id='centro_informacion' value='".($data['centro_informacion'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[29])."
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>B&uacute;squedas automatizadas para investigadores</td>
                            <td align='left'><input type='text' $this->stylesc  required='yes' name='centro_busqueda_automatizada' id='centro_busqueda_automatizada' value='".($data['centro_busqueda_automatizada'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[30])."
                            </td>
                        </tr>
                        <tr>
                            <td>Usuarios Bibliotecas</td>
                            <td><input type='text' $this->stylesc  required='yes' name='centro_bibliotecas_usuarios' id='centro_bibliotecas_usuarios' value='".($data['centro_bibliotecas_usuarios'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[31])."
                            </td>
                            <td>Servicios en Bibliotecas</td>
                            <td><input type='text' $this->stylesc  required='yes' name='centro_biblioteca_busquedas' id='centro_biblioteca_busquedas' value='".($data['centro_biblioteca_busquedas'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[32])."
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2'>".$this->Regresa_Proy_Poblacion($data['c_proy_poblacion'],$data['c_proy_asis_m0_14'],$data['c_proy_asis_m15_20'],$data['c_proy_asis_m21_65'],$data['c_proy_asis_m66'],$data['c_proy_asis_m_total'],$data['c_proy_asis_h0_14'],$data['c_proy_asis_h15_20'],$data['c_proy_asis_h21_65'],$data['c_proy_asis_h66'],$data['c_proy_asis_h_total'],1,$ayudas)."
                            </td>
                            <td colspan='2'>&nbsp;</td>
                        </tr>
                        <tr><th colspan='4'>&nbsp;</th></tr>
                        <tr>
                           <th colspan='4' align='center'>Actividades difusi&oacute;n Patrimonial Documental</th>
                        </tr>
                        <tr>
                            <td>Personas atendidas en visitas guiadas</td>
                            <td><input type='text' $this->stylesc  required='yes' name='personas_atendidas_en_visitas' id='personas_atendidas_en_visitas' value='".($data['personas_atendidas_en_visitas'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[44])."
                            </td>
                            <td>Visitas guiadas</td>
                            <td><input type='text' $this->stylesc  required='yes' name='visitas_guiadas' id='visitas_guiadas' value='".($data['visitas_guiadas'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[45])."
                            </td>
                        </tr>
                        <tr>
                            <td>Personas atendidas en Congresos</td>
                            <td><input type='text' $this->stylesc  required='yes' name='personas_atendidas_en_congresos' id='personas_atendidas_en_congresos' value='".($data['personas_atendidas_en_congresos'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[46])."
                            </td>
                            <td>Congresos</td>
                            <td><input type='text' $this->stylesc  required='yes' name='congresos' id='congresos' value='".($data['congresos'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[47])."
                            </td>
                        </tr>
                        <tr>
                            <td>Personas atendidas en Talleres</td>
                            <td><input type='text' $this->stylesc  required='yes' name='personas_atendidas_en_talleres' id='personas_atendidas_en_talleres' value='".($data['personas_atendidas_en_talleres'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[48])."
                            </td>
                            <td>Talleres</td>
                            <td><input type='text' $this->stylesc  required='yes' name='talleres' id='talleres' value='".($data['talleres'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[49])."
                            </td>
                        </tr>
                        <tr>
                            <td>Personal de otros archivos que recibieron orientaci&oacute;n</td>
                            <td><input type='text' $this->stylesc  required='yes' name='personal_otro_archivo' id='personal_otro_archivo' value='".($data['personal_otro_archivo'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[50])."
                            </td>
                            <td>Orientaci&oacute;n t&eacute;cnica a Instituciones</td>
                            <td><input type='text' $this->stylesc  required='yes' name='orientacion_tecnica' id='orientacion_tecnica' value='".($data['orientacion_tecnica'] + 0)."' onblur='sumah_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[51])."
                            </td>
                        </tr>
                        <tr>
                            <td>Personas atendidas</td>
                            <td><input type='text' $this->stylesc  required='yes' name='personas_atendidas' id='personas_atendidas' value='".($data['personas_atendidas'] + 0)."' onblur='sumam_historico();'>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[52])."
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>TOTAL GENERAL DE PERSONAS ATENDIDAS</td>
                            <td><input type='text' $this->stylesc  required='yes' name='total_persona_atendidas' id='total_persona_atendidas' value='".($data['total_persona_atendidas'] + 0)."' readonly>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[53])."
                            </td>
                            <td>TOTAL GENERAL DE SERVICIOS</td>
                            <td><input type='text' $this->stylesc  required='yes' name='total_n_servicios' id='total_n_servicios' value='".($data['total_n_servicios'] + 0)."' readonly>
                            &nbsp;&nbsp;".$this->muestraAyuda($ayudas[54])."
                            </td>
                        </tr>
                        <tr>
                            <td colspan='2'>".$this->Regresa_Proy_Poblacion($data['a_proy_poblacion'],$data['a_proy_asis_m0_14'],$data['a_proy_asis_m15_20'],$data['a_proy_asis_m21_65'],$data['a_proy_asis_m66'],$data['a_proy_asis_m_total'],$data['a_proy_asis_h0_14'],$data['a_proy_asis_h15_20'],$data['a_proy_asis_h21_65'],$data['a_proy_asis_h66'],$data['a_proy_asis_h_total'],2,$ayudas)."
                            </td>
                            <td colspan='2'>&nbsp;</td>
                        </tr>
                        <tr>
                        <td valign='middle'>Observaciones:</td>
                        <td colspan='3'><textarea name='observaciones' required='no' id='observaciones' cols='50' rows='8'>".$data['observaciones']."</textarea>
                        &nbsp;&nbsp;".$this->muestraAyuda($ayudas[55])."
                        </td>luis
                        </tr>";
                      $tmp_r_1='';
                        $tmp_r_2='';
                        if($data['relevancia']==1)
                            $tmp_r_1=' CHECKED ';
                        if($data['relevancia']==2)
                            $tmp_r_2=' CHECKED ';
						if($data['relevancia']==0)
							$tmp_r_3=' CHECKED ';

                        $buffer.="
						<tr>
			                <th class='tdverde' colspan='4'>Actividad Relevante</td>
				        </tr>						
						<tr>
                        <td>Relevancia</td>
                        <td colspan='2'>
                        &nbsp;Nivel 1&nbsp;<input type='radio' name='relevancia' id='relevancia' value='1' ".$tmp_r_1.">
                        &nbsp;".$this->muestraAyuda($ayudas[56])."
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Nivel 2&nbsp;<input type='radio' name='relevancia' id='relevancia' value='2' ".$tmp_r_2.">
                        &nbsp;".$this->muestraAyuda($ayudas[57])."
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Omitir&nbsp;<input type='radio' name='relevancia' id='relevancia' value='0' ".$tmp_r_3.">
                        &nbsp;".$this->muestraAyuda($ayudas[58])."
                        </td>
                        </tr>
						<tr>
			                <td>Frecuencia</td>
			                <td><input type='text' name='frecuencia' id='frecuencia' value='".$data['frecuencia']."' $this->stylesc >&nbsp;".$this->muestraAyuda($ayudas[59])."</td>
						</tr>
						</table>
                </td>
            </tr>
            <tr>
                <td align='center'>";
                if($tipo_vista == 1)
                {
                    $buffer.="<input type='submit' name='boton' value='ACTUALIZAR DATOS'>&nbsp;&nbsp;
                              <input type='button' name='boton' value='Cerrar Ventana' onClick='self.close();'>";
                }
                if($tipo_vista == 2)
                {
                    $buffer.="<input type='button' name='boton' value='Cerrar Ventana' onClick='self.close();'>";
                }
            $buffer.="</td></tr>
        </table>";
        return $buffer;
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
    //   return "&nbsp;&nbsp;<button type=\"button\" style=\"padding-top:0px;width:15px;height:17px;font-size:8px;\" class=\"btn-danger ayudas\" id=\"example\" data-toggle=\"popover\" title=\"Ayuda Sisec\" data-content=\"".$texto."\" >?</button>";
	}
	function obtenFormato(){
		return $this->buffer;
	}
}
?>