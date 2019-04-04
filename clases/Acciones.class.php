<?php
class Acciones extends Comunes {
	var $db;
	var $data;
	var $session;
	var $server;
	var $path;
	var $buffer;
	function __construct($db, $data, $session, $server, $path, $pages) {
		$this->db = $db;
		$this->data = $data;
		$this->path = $path;
		$this->server = $server;
		$this->session = $session;
		$this->pages = $pages;
		$this->cadena_error = "<script>location.href='" . $this->path_web . "'</script>";
		$this->arrayRoles = array ();
		$this->buffer = "";
		$this->bufferFiltros = $this->cadenaFiltros = "";
		$this->opc = $this->data ['opc'] + 0;
		switch ($this->opc) {
			case 3:
				$this->formularioAcciones ();
				break;
			default:
				$this->formularioAcciones ();
				break;
		}
				
	}
	
	/**
	 * Metodo que se encarga de pintar el formulario de acciones
	 */
	function formularioAcciones() {
		$this->buffer = "
        <div id='resultado' class='error'></div>
        <input type='hidden' name='valueId' id='valueId' value='" . ($this->array_datos ['id'] + 0) . "'>
        <table width='90%' align='center' border='0' class='table-striped'>
            <tr class='altotitulo'>
                <td class='tdcenter legend' colspan='6'>" . ALTADEACCIONES . "</td>
            </tr>
            <tr>
                <td class='tdleft bold' width='20%'>" . EJEPOLITICA . "</td>
                <td colspan='2' class='tdleft alinea'>" . $this->generaEjes ( $this->db, $this->data ['ideje'] ) . "</td>
           
                <td class='tdleft bold'>" . POLITICAPUBLICA . "</td>
                <td colspan='2' class='tdleft alinea'>" . $this->generaPoliticas ( $this->db, $this->data ['idpolitica'], 2 ) . "</td>
            </tr>
            <tr>
                <td class='tdleft bold'>" . AREA . "</td>
                <td colspan='2' class='tdleft alinea'>" . $this->generaAreas ( $this->db, '', $this->data ['idarea'], 2 ) . "</td>
                <td class='tdleft bold'>" . PROGRAMA . "</td>
                <td colspan='2' class='tdleft alinea'>" . $this->generaProgramas ( $this->db, $this->data ['idarea'], $this->data ['idprograma'], 2 ) . "</td>
            </tr>
            <tr>
                <td class='tdleft bold'>" . OBJGEN . "</td>
                <td colspan='5' class='tdleft alinea'>" . $this->generaObjetivosGenerales ( $this->db, $this->data ['idarea'], $this->data ['idprograma'], $this->data ['idobjetivog'], 2 ) . "</td>
            </tr>
            <tr>
                <td class='tdleft bold'>" . PROYECTO . "</td>
                <td colspan='2' class='tdleft alinea'>" . $this->generaProyectos () . "</td>
                <td class='tdright bold'>" . PONDERACION . "&nbsp;&nbsp;</td>
                <td colspan='2' class='tdleft alinea'>&nbsp;&nbsp;
                    <input type='radio' name='pondracion' id='ponderacion5' value='5'>5&nbsp;&nbsp;
                    <input type='radio' name='pondracion' id='ponderacion4' value='4'>4&nbsp;&nbsp;
                    <input type='radio' name='pondracion' id='ponderacion3' value='3'>3&nbsp;&nbsp;
                    <input type='radio' name='pondracion' id='ponderacion2' value='2'>2&nbsp;&nbsp;
                    <input type='radio' name='pondracion' id='ponderacion1' value='1'>1
                </td>
            </tr>
            <tr>
                <td class='tdleft bold'>" . PROBLEMATICA . "</td>
                <td colspan='5' class='tdleft alinea'>
                    <textarea required='yes' class='form-control validatexto' style='height: 130px;width:80%;' placeholder='" . PROBLEMATICA . "'  value='" . $this->array_datos ['problematica'] . "' name='problematica' id='problematica'></textarea>
                </td>
            </tr>
            <tr>
                <td class='tdleft bold'>" . UNIDADOPERATIVA . "</td>
                <td colspan='2' class='tdleft alinea'>" . $this->generaProgramas ( $this->db, $this->data ['idarea'], $this->data ['idprograma'], 2 ) . "</td>
                <td class='tdleft bold'>" . RESPONSABLE . "</td>
                <td colspan='2' class='tdleft alinea'>" . $this->generaProgramas ( $this->db, $this->data ['idarea'], $this->data ['idprograma'], 2 ) . "</td>
            </tr>
            <tr>
                <td class='tdleft bold'>" . OBJESP . "</td>
                <td colspan='5' class='tdleft alinea'>
                    <textarea required='yes' class='form-control validatexto' style='height: 130px;width:80%;' placeholder='" . OBJESP . "'  value='" . $this->array_datos ['objetivoEspecifico'] . "' name='objetivoespecifico' id='objetivoespecifico'></textarea>
                </td>
            </tr>
            <tr>
                <td class='tdleft bold'>" . RESULTADO . "</td>
                <td colspan='5' class='tdleft alinea'>
                    <textarea required='yes' class='form-control validatexto' style='height: 130px;width:80%;' placeholder='" . RESULTADO . "'  value='" . $this->array_datos ['resultadosEsperados'] . "' name='resultadosesperados' id='resultadosesperados'></textarea>
                </td>
            </tr>
            <tr>
                <td class='tdleft bold'>" . MEDIDA . "</td>
                <td colspan='5' class='tdleft alinea'>" . $this->generaProgramas ( $this->db, $this->data ['idarea'], $this->data ['idprograma'], 2 ) . "</td>
            </tr>
            <tr>
                <td class='tdleft  bold fondotable'>" . METAANUAL . "</td>
                <td class='tdcenter fondotable' width='16%'>" . TRIMESTRE1 . "</td>
                <td class='tdcenter fondotable' width='16%'>" . TRIMESTRE2 . "</td>
                <td class='tdcenter fondotable' width='16%'>" . TRIMESTRE3 . "</td>
                <td class='tdcenter fondotable' width='16%'>" . TRIMESTRE4 . "</td>
                <td class='tdcenter fondotable' width='16%'>" . TOTAL . "</td>
            </tr>
            <tr>
                <td class='tdleft bold'>" . PROGRAMADO . "</td>
                <td class='tdcenter alinea' width='16%'><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:100px;' placeholder='" . TECLEAVALOR . "'  value='" . $this->array_datos ['programado1Tri'] . "' name='trimestre1' id='trimestre1'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:100px;' placeholder='" . TECLEAVALOR . "'  value='" . $this->array_datos ['programado2Tri'] . "' name='trimestre2' id='trimestre2'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:100px;' placeholder='" . TECLEAVALOR . "'  value='" . $this->array_datos ['programado3Tri'] . "' name='trimestre3' id='trimestre3'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:100px;' placeholder='" . TECLEAVALOR . "'  value='" . $this->array_datos ['programado4Tri'] . "' name='trimestre4' id='trimestre4'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' disabled class='form-control validatexto' style='height: 30px;width:100px;' placeholder='" . TECLEAVALOR . "'  value='" . $this->array_datos ['programadoTotal'] . "' name='trimestret' id='trimestret'></td>
            </tr>
             <tr>
                <td class='tdleft bold'>" . REALIZADO . "</td>
                <td class='tdcenter alinea' width='16%'><input type='text' disabled class='form-control' style='height: 30px;width:100px;' value='" . $this->array_datos ['realizado1Tri'] . "'  name='rtrimestre1' id='rtrimestre1'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' disabled class='form-control' style='height: 30px;width:100px;' value='" . $this->array_datos ['realizado2Tri'] . "'  name='rtrimestre2' id='rtrimestre2'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' disabled class='form-control' style='height: 30px;width:100px;' value='" . $this->array_datos ['realizado3Tri'] . "'  name='rtrimestre3' id='rtrimestre3'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' disabled class='form-control' style='height: 30px;width:100px;' value='" . $this->array_datos ['realizado4Tri'] . "'  name='rtrimestre4' id='rtrimestre4'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' disabled class='form-control' style='height: 30px;width:100px;' value='" . $this->array_datos ['realizadoTotal'] . "' name='rtrimestret' id='rtrimestret'></td>
            </tr>
            <tr>
                <td class='tdleft bold fondotable'>" . PRESUPUESTO . "</td>
                <td class='tdcenter fondotable' width='16%'>" . PRESUPUESTO1 . "</td>
                <td class='tdcenter fondotable' width='16%'>" . PRESUPUESTO2 . "</td>
                <td class='tdcenter fondotable' width='16%'>" . PRESUPUESTO3 . "</td>
                <td class='tdcenter fondotable' width='16%'>" . PRESUPUESTO4 . "</td>
                <td class='tdcenter fondotable' width='16%'>" . TOTAL . "</td>
            </tr>
            <tr>
                <td class='tdcenter' width='16%'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:100px;' placeholder='" . TECLEAVALOR . "'  value='" . $this->array_datos ['programado1Tri'] . "' name='trimestre1' id='trimestre1'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:100px;' placeholder='" . TECLEAVALOR . "'  value='" . $this->array_datos ['programado2Tri'] . "' name='trimestre2' id='trimestre2'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:100px;' placeholder='" . TECLEAVALOR . "'  value='" . $this->array_datos ['programado3Tri'] . "' name='trimestre3' id='trimestre3'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:100px;' placeholder='" . TECLEAVALOR . "'  value='" . $this->array_datos ['programado4Tri'] . "' name='trimestre4' id='trimestre4'></td>
                <td class='tdcenter alinea' width='16%'><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:100px;' placeholder='" . TECLEAVALOR . "'  value='" . $this->array_datos ['programadoTotal'] . "' name='trimestret' id='trimestret'></td>
            </tr>
             <tr>
                <td class='tdleft bold' valign='top'><br>" . ENCOORDINACION . "</td>
                <td colspan='5' class='tdleft'><br> 
                    &nbsp;&nbsp;<label class='checkbox-inline'><input type='checkbox' name='checkbox1' id='checkbox1'  value='1'>&nbsp;&nbsp;" . COORDINACION1 . "</label><br>
                    &nbsp;&nbsp;<label class='checkbox-inline'><input type='checkbox' name='checkbox2' id='checkbox2'  value='2'>&nbsp;&nbsp;" . COORDINACION2 . "</label><br>
                    &nbsp;&nbsp;<label class='checkbox-inline'><input type='checkbox' name='checkbox3' id='checkbox3'  value='3'>&nbsp;&nbsp;" . COORDINACION3 . "</label><br>
                    &nbsp;&nbsp;<label class='checkbox-inline'><input type='checkbox' name='checkbox4' id='checkbox4'  value='4'>&nbsp;&nbsp;" . COORDINACION4 . "</label><br>
                    &nbsp;&nbsp;<label class='checkbox-inline'><input type='checkbox' name='checkbox5' id='checkbox5'  value='5'>&nbsp;&nbsp;" . COORDINACION5 . "</label><br>
                    &nbsp;&nbsp;<label class='checkbox-inline'><input type='checkbox' name='checkbox6' id='checkbox6'  value='6'>&nbsp;&nbsp;" . COORDINACION6 . "</label><br>
                    &nbsp;&nbsp;<label class='checkbox-inline'><input type='checkbox' name='checkbox7' id='checkbox7'  value='7'>&nbsp;&nbsp;" . COORDINACION7 . "</label><br>
                    &nbsp;&nbsp;<label class='checkbox-inline'><input type='checkbox' name='checkbox8' id='checkbox8'  value='8'>&nbsp;&nbsp;" . COORDINACION8 . "</label><br>
                    &nbsp;&nbsp;" . OBSERVACION . "<br>
                    <textarea required='yes' class='form-control validatexto' style='height: 130px;width:80%;' placeholder='" . OBSERVACION . "'  value='" . $this->array_datos ['observaciones'] . "' name='observaciones' id='observaciones'></textarea>
                </td>
            </tr>
            <tr>
                <td class='tdleft bold' valign='top'><br>" . CONVOCATORIA . "</td>
                <td colspan='5' class='tdleft alinea'>
                    &nbsp;&nbsp;<label class='radio-inline'><input type='radio' name='convocatoriaPublica' id='convocatoriaPublica' value='1'>" . SI . "</label>&nbsp;&nbsp;
                    &nbsp;&nbsp;<label class='radio-inline'><input type='radio' name='convocatoriaPublica' id='convocatoriaPublica' value='2' checked>" . NO . "</label>&nbsp;&nbsp;
                    <br><br>
                    " . ARCHIVO . "&nbsp;<input type='file' name='adjunto' id='adjunto'>
                </td>
            </tr>
            <tr>
                <td class='tdcenter legend' colspan='6'>
                <button type='button' class='btn btn-mio savecatalogo' id='guardarAccion' name='guardarAccion'>" . GRABARDATOS . "</button>&nbsp;&nbsp;&nbsp;";
		if ($this->data ['opc'] > 1) {
			$this->buffer .= "<button type='button' class='btn btn-mio savecatalogo' id='guardarAccion' name='guardarAccion'>" . BAJAPROYECTO . "</button>&nbsp;&nbsp;&nbsp;
                <button type='button' class='btn btn-mio savecatalogo' id='guardarAccion' name='guardarAccion'>" . NUEVOPROYECTO . "</button>&nbsp;&nbsp;&nbsp;
                <button type='button' class='btn btn-mio savecatalogo' id='guardarAccion' name='guardarAccion'>" . VALIDACIONPROYECTO . "</button>&nbsp;&nbsp;&nbsp;";
		}
		$this->buffer .= "
                </td>
            </tr>
        </table>";
	}
	
	/**
	 * Metodo que obtiene el buffer
	 */
	function obtenFormato() {
		return $this->buffer;
	}
}

?>