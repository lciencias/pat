<?php

class ComunesEstadisticas {

    var $cadena_error;
    var $arrayEstatus;
	var $style;
	var $nombreTabla;
    function __construct() {
    	$this->nombreTabla = "";
    	$this->style = "style = 'width:200px;' ";
        $this->cadena_error = "<script>location.href='../logout.php'</script>";
        $this->arrayEstatus = array('AltaProyecto' => 1, 'AltaActividad' => 2, 'AltaMeta' => 3, 'AltaAvance' => 4,
            'ActualizaProyecto' => 5, 'ActualizaActividad' => 6, 'ActualizaMeta' => 7, 'ActualizaAvance' => 8,
            'EliminaProyecto' => 9, 'EliminaActividad' => 10, 'RestauraProyecto' => 11, 'RestauraActividad' => 12);
    }

    function debugO($objeto){
    	echo"<pre>";
    	print_r($objeto);
    	die();
    }

    function debugC($cadena){
    	echo"<br>Valor:   ".$cadena."<br>";
    	
    }
    
    function eliminaCaracteresInvalidos($valor) {
        $valor = str_replace("'", "", $valor);
        $valor = str_replace('"', '', $valor);
        $valor = str_replace(' ', '', $valor);
        return $valor;
    }

    function limpiaCadenas($valor) {
    	$valor = trim($valor);
    	$valor = strip_tags($valor);
    	$valor = addslashes($valor);
    	$valor = utf8_decode($valor);
    	return $valor;
    }
    
    function limpiaCadenasC($valor) {
    	$valor = trim($valor);
    	$valor = strip_tags($valor);
    	$valor = addslashes($valor);
    	$valor = utf8_encode($valor);
    	return $valor;
    }
    function generaPonderacion() {
        $comboAnos = "<select name='ponderacion' id='ponderacion' class='bootstrap-select' style='width:140px;'><option value='0' class='franjaSeleccione'>" . PONDERACION . "</option>";
        for ($i = 1; $i <= 5; $i++)
            $comboAnos .= "<option value='" . $i . "'>" . $i . "</option>";
        $comboAnos .= "</select>";
        return $comboAnos;
    }

    function generaAnos() {
        if (($this->data['idAno'] + 0) == 0) {
            $this->data['idAno'] = date('Y');
        }
        $comboAnos = "<select name='idano' id='idano' class='bootstrap-select' style='width:80px;'>";
        $tmp = "";
        $sql = "SELECT ano FROM cat_anos where active='1' ORDER BY ano;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $ano ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($ano == $this->data['idAno'])
                    $tmp = " SELECTED ";
                $comboAnos .= "<option value='" . $ano . "' " . $tmp . ">" . $ano . "</option>";
            }
        }
        $comboAnos .= "</select>";
        return $comboAnos;
    }

    function generaTrimestre() {
    	$combo = "<select name='idTrimestre' id='idTrimestre' class='bootstrap-select' style='width:160px;'>";
    	$combo .= "<option value='0'>Seleccione</option>";
        for ($i = 1; $i <= 4; $i++)
            $combo .= "<option value='" . $i . "'>Trimestre " . $i . "</option>";
    	
    	$combo .= "</select>";
    	return $combo;
    		
    }
    function generaMeses($idMeses) {
        $comboMeses = "<select name='idmes' id='idmes' class='bootstrap-select'><option value='0' class='franjaSeleccione'>" . MES . "</option>";
        $tmp = "";
        $sql = "SELECT mes_id,mes FROM cat_meses ORDER BY mes_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $idMeses)
                    $tmp = " SELECTED ";
                if ($_id > 0)
                    $comboMeses .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        $comboMeses .= "</select>";
        return $comboMeses;
    }


    function generaActivos($Idstatus) {
        $combo = "<SELECT name='edoId' id='edoId' class='bootstrap-select'>
            <option value='1'>Activo</option>
            <option value='0'>Inactivo</option>
            <option value='2'>Eliminado</option></select>";
        return $combo;
    }

    function catalogoActivos() {
        return array(
            0 => 'Inactivo',
            1 => 'Activo',
            2 => 'Eliminado'
        );
    }


    function Formato_Fecha($fecha) {
        // return trim(substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4)." ".substr($fecha,11,8));
        return trim(substr($fecha, 8, 2) . "-" . substr($fecha, 5, 2) . "-" . substr($fecha, 0, 4));
    }

    function muestraAyuda($texto) {
        //return "&nbsp;&nbsp;<a href='#' class='ayudas' rel='popover' data-content='" . $texto . "' data-original-title='Ayuda SiSec'>&nbsp;?&nbsp;</a>";
        // return "&nbsp;&nbsp;<button type=\"button\" style=\"padding-top:0px;width:15px;height:17px;font-size:8px;\" class=\"btn-mio ayudas\" id=\"example\" data-toggle=\"popover\" title=\"Ayuda Sisec\" data-content=\"".$texto."\" >?</button>";
    }

    function LimpiaValores($datos) {
        if (count($datos) > 0) {
            foreach ($datos as $clave => $valor) {
                $datos [$clave] = utf8_decode(addslashes(trim($valor)));
            }
        }
        return $datos;
    }

    function procesando($opcion) {
        $posiciones = $width = $height = $top = 0;
        switch ($opcion) {
            case 1 :
                $posiciones = 100;
                $width = 135;
                $height = 115;
                $top = 180;
                break;
            case 2 :
                $posiciones = 860;
                $width = 135;
                $height = 115;
                $top = 180;
                break;
            case 3:
                $posiciones = 760;
                $width = 135;
                $height = 115;
                $top = 480;
                break;
            case 4:
                $posiciones = 760;
                $width = 135;
                $height = 115;
                $top = 340;
                break;
            case 5 :
                $posiciones = 860;
                $width = 135;
                $height = 115;
                $top = 340;
                break;
        }
        //load.png
        return "<div id='div_procesando' style='position: absolute;width:" . $width . "px;height:" . $height . "px;z-index: 1;left:" . $posiciones . "px;top:" . $top . "px;overflow: visible;'>
            	<img src='" . $this->path . "imagenes/load.gif' border='0'  id='procesando' ><br>
            	<span id='t_procesando' class='procesando'>Procesando.....</span>
          	</div>";
    }

    function GeneraOrden($consec, $ord, $_id, $catalogoId) {
        $consec = 25;
        $idDiv = "o-" . $catalogoId . "-" . $_id;
        $tmp = "";
        $combo = "<select name='" . $idDiv . "' id='" . $idDiv . "' requerid class='bootstrap-select ordenes' style='width:50px;'>";
        for ($i = 1; $i <= $consec; $i ++) {
            $tmp = "";
            if ($i == $ord) {
                $tmp = " selected ";
            }
            $combo .= "<option value='" . $i . "' " . $tmp . ">" . $i . "</option>";
        }
        $combo .= "</select>";
        return $combo;
    }

   
    function generaProyectos($areaId, $programaId, $opcion) {
        $combo = "<select name='idproyecto' id='idproyecto' class='bootstrap-select' style='width:60%;'>";
        $combo .= "<option value='0' class='franjaSeleccione'>" . SELECCIONE . "</option>";
        $filtro = "";
        if (($areaId > 0) && ($programaId > 0)) {
            $filtro = "AND area_id='" . $areaId . "' AND programa_id='" . $programaId . "' ";
            $combo .= $this->generaQueryProyectos($filtro);
        }
        $combo .= "</select>";
        return $combo;
    }

    function catalogoProyectos($db) {
        $array = array();
        $sql = "SELECT subprograma_id,subprograma FROM cat_subprogramas WHERE active='1' ORDER BY subprograma;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $array [$_id] = $_nm;
            }
        }
        return $array;
    }

    function RegresaProyectos($db, $unidadID) {
        $filtro = "";
        $bf = "";
        if ($unidadID > 0)
            $filtro = " AND a.unidad_id = '" . $unidadID . "'";
        $sql = "SELECT b.subprograma FROM  cat_unidadoperativa_proyecto as a LEFT JOIN cat_subprogramas as b
            	ON a.proyecto_id=b.subprograma_id
       			WHERE b.active='1' " . $filtro . " AND b.subprograma IS NOT NULL ORDER BY b.subprograma;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_nm ) = $this->db->sql_fetchrow($res)) {
                $bf .= "<b>*</b>&nbsp;" . $_nm . "<br>";
            }
        }
        return $bf;
    }

    function generaQueryProyectos($db, $filtro) {
        $buffer = "";
        $sql = "SELECT subprograma_id,subprograma FROM cat_subprogramas    			
    			WHERE active='1' " . $filtro . " AND subprograma IS NOT NULL ORDER BY subprograma;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                $buffer .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        return $buffer;
    }

    function generaCombosAnos() {
        $comboAnos = "<select name='idano' id='idano' class='bootstrap-select'>";
        $sql = "SELECT ano FROM cat_anos WHERE active='1' ORDER BY ano DESC;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ($_nm) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_nm == $this->arrayDatos['ano_id']) {
                    $tmp = " selected ";
                }
                $comboAnos.="<option value='" . $_nm . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        $comboAnos.="</select>";
        return $comboAnos;
    }

    function generaComboProgramas() {
        $comboProgramas = "<select name='idprograma' id='idprograma' class='bootstrap-select'>
				<option value='0' class='franjaSeleccione'>" . PROGRAMAS . "</option></select>";
        return $comboProgramas;
    }

    function generaComboAreas() {
        $filtro = "";
        if (trim($this->session ['areas']) != "") {
            $filtro = " AND area_id IN (" . $this->session ['areas'] . ")  ";
        }
        $comboAreas = "<select name='idarea' id='idarea'  class='bootstrap-select'><option value='0' class='seleccione' >" . AREAS . "</option>";
        $sql = "SELECT area_id,nombre FROM cat_areas WHERE active='1' " . $filtro . " ORDER BY orden;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                $comboAreas .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        $comboAreas .= "</select>";
        return $comboAreas;
    }

    /**
     * Metodo que se encarga de crear el combo de ponderacion
     * @param array $array de datos de session 
     * @param array $array con los datos del proyecto
     * @return string regresa el combo
     */
    function regresaPonderacion($data, $arrayDatos) {
        $combo = "<select name='idPonderacion' id='idpPonderacion'  class='bootstrap-select' style='width:40px;'>";
        $sql = "SELECT id FROM cat_ponderacion WHERE active='1' ORDER BY id asc;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ( $_id ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == ($arrayDatos['ponderacion'] + 0))
                    $tmp = " SELECTED ";
                $combo.="<option value='$_id' class='seleccione' " . $tmp . ">" . $_id . "</option>";
            }
        }
        $combo.="</select>";
        return $combo;
    }

    /**
     * Metodo que se encarga de crear el combo de tipo de actividad
     * @param array $array de datos de session 
     * @param array $array con los datos del proyecto
     * @return string regresa el combo
     */
    function regresaTipoActividad($opcion) {
        $dis = "";
        if ($opcion == 2)
            $dis = $this->disabled;
        $combo = "<select tabindex='4'  name='idTipoActividad'  " . $dis . "  " . $this->disabledAdmin . "  id='idTipoActividad'  class='bordes' style='width:160px;'>
				  <option value='0' class='franjaSeleccione'>" . TIPOACT . "</option>";

        $sql = "SELECT  actividad_id,nombre FROM cat_tipo_actividad WHERE active='1' ORDER BY actividad_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ($_id, $_medida) = $this->db->sql_fetchrow($res)) {
                $tmp = "";

                if ($_id == ($this->arrayDatos['tipo_actividad_id']))
                    $tmp = " SELECTED ";
                if (($_id != 5)) {
                    $combo.="<option value='$_id' class='seleccione' " . $tmp . ">" . $_medida . "</option>";
                } else {
                    if (($this->session['rol'] == 4))
                        $combo.="<option value='$_id' class='seleccione' " . $tmp . ">" . $_medida . "</option>";
                    else
                    if ($this->disabledAdmin != "")
                        $combo.="<option value='$_id' class='seleccione' " . $tmp . ">" . $_medida . "</option>";
                }
            }
        }
        $combo.="</select>";
        return $combo;
    }

    /**
     * Metodo que se encarga de crear el combo de medidas
     * @param array $array de datos de session 
     * @param array $array con los datos del proyecto
     * @return string regresa el combo
     */
    function regresaMedidas($opcion) {
        $dis = "";
        if ($opcion == 2)
            $dis = $this->disabled;
        $combo = "<select name='idMedida'  " . $dis . " " . $this->disabledAdmin . " tabindex='2' id='idMedida'  class='bordes' style='width:160px;'>
				 <option value='0' class='franjaSeleccione'>" . MEDIDA . "</option>";
        $sql = "SELECT medida_id,nombre FROM cat_medidas WHERE active='1' ORDER BY nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ( $_id, $_medida) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == ($this->arrayDatos['medida_id'] + 0))
                    $tmp = " SELECTED ";
                $combo.="<option value='$_id' class='seleccione' " . $tmp . ">" . $_medida . "</option>";
            }
        }
        $combo.="</select>";
        return $combo;
    }

    /**
     * Metodo que regresa el nombre del eje 
     * @param array con parametros de entrada
     */
    function regresaNombreEje($data, $session, $datos) {
        $arrayIdEjes = array();
        $arrayNmEjes = array();
        $nmEjes = "";
        $filtro = "";
        if ($this->session['userArea'] != "") {
            $filtro = " AND a.area_id in ('" . $this->session['userArea'] . "') ";
        }
        $sql = "SELECT b.eje_id,c.nombre
			  FROM cat_politica_area AS a LEFT JOIN cat_politicas AS b ON a.politica_id = b.politica_id
			  LEFT JOIN cat_ejes AS c ON b.eje_id=c.eje_id WHERE b.active=1 AND c.active=1 " . $filtro . " ORDER BY c.nombre;";

        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ($_idEje, $_nmEje ) = $this->db->sql_fetchrow($res)) {
                if (!in_array($_idEje, $arrayIdEjes)) {
                    $arrayIdEjes[] = $_idEje;
                    $arrayNmEjes[$_idEje] = $_nmEje;
                }
            }
            $nmEjes = implode("<br>", $arrayNmEjes);
        }
        return $nmEjes;
    }

    function RecuperaDatosEjes() {
        $arrayIdEjes = array();
        $arrayNmEjes = array();
        $nmEjes = "";
        $filtro = "";
        $exito = 0;
        if (count($this->arrayDatos) > 0) {
            if ($this->arrayDatos['unidadResponsable_id'] > 0) {
                $exito = 1;
                $filtro.= " AND a.area_id ='" . $this->arrayDatos['unidadResponsable_id'] . "' ";
            }
        }
        if (($this->session['areas'] != "") && ($this->session['rol'] != 4)) {
            $filtro.= " AND a.area_id in (" . $this->session['areas'] . ") ";
            $exito = 1;
        }
        if ($exito) {
            $sql = "SELECT b.eje_id,c.nombre
			  FROM cat_politica_area AS a LEFT JOIN cat_politicas AS b ON a.politica_id = b.politica_id
			  LEFT JOIN cat_ejes AS c ON b.eje_id=c.eje_id WHERE b.active=1 AND c.active=1 " . $filtro . " ORDER BY c.nombre;";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            $num = $this->db->sql_numrows($res);
            if ($num > 0) {
                while (list ( $_idEje, $_nmEje ) = $this->db->sql_fetchrow($res)) {
                    if (!in_array($_idEje, $arrayIdEjes)) {
                        $arrayIdEjes[] = $_idEje;
                        $arrayNmEjes[$_idEje] = "*&nbsp;" . $_nmEje;
                    }
                }
                $nmEjes = implode("<br>", $arrayNmEjes);
            }
        }
        return $nmEjes;
    }

    function RecuperaDatosPoliticas() {
        $arrayIdPolitica = array();
        $arrayNmPolitica = array();
        $nmPoliticas = "";
        $filtro = "";
        $exito = 0;
        if (count($this->arrayDatos) > 0) {
            if ($this->arrayDatos['unidadResponsable_id'] > 0) {
                $exito = 1;
                $filtro.= " AND a.area_id ='" . $this->arrayDatos['unidadResponsable_id'] . "' ";
            }
        }

        if (($this->session['areas'] != "") && ($this->session['rol'] != 4)) {
            $filtro = " AND a.area_id in (" . $this->session['areas'] . ") ";
            $exito = 1;
        }

        if ($exito) {
            $sql = "SELECT a.politica_id,b.nombre
				  FROM cat_politica_area AS a LEFT JOIN cat_politicas AS b ON a.politica_id = b.politica_id
				  WHERE b.active=1 " . $filtro . " ORDER BY b.nombre;";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            $num = $this->db->sql_numrows($res);
            if ($num > 0) {
                while (list ( $_idPolitica, $_nmPolitica ) = $this->db->sql_fetchrow($res)) {
                    if (!in_array($_idPolitica, $arrayIdPolitica)) {
                        $arrayIdPolitica[] = $_idPolitica;
                        $arrayNmPolitica[$_idPolitica] = "*&nbsp;" . $_nmPolitica;
                    }
                }
                $nmPoliticas = implode("<br>", $arrayNmPolitica);
            }
        }
        return $nmPoliticas;
    }

    /**
     * Metodo que regresa el nombre de la politica
     * @param array con parametros de entrada
     */
    function regresaNombrePolitica($data, $session, $datos) {
        $arrayIdPolitica = array();
        $arrayNmPolitica = array();
        $nmPoliticas = "";
        $filtro = "";
        /* if($data['idarea'] > 0)
          $filtro= " AND a.area_id='".$data['idarea']."' "; */
        if ($this->session['userArea'] != "") {
            $filtro = " AND a.area_id in ('" . $this->session['userArea'] . "') ";
        }
        $sql = "SELECT a.politica_id,b.nombre
			  FROM cat_politica_area AS a LEFT JOIN cat_politicas AS b ON a.politica_id = b.politica_id
			  WHERE b.active=1 " . $filtro . " ORDER BY b.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ( $_idPolitica, $_nmPolitica ) = $this->db->sql_fetchrow($res)) {
                if (!in_array($_idPolitica, $arrayIdPolitica)) {
                    $arrayIdPolitica[] = $_idPolitica;
                    $arrayNmPolitica[$_idPolitica] = $_nmPolitica;
                }
            }
            $nmPoliticas = implode("<br>", $arrayNmPolitica);
        }
        return $nmPoliticas;
    }

    function regresaNombreAreaAdmin($opcion) {
        $dis = "";
        if ($opcion == 2)
            $dis = $this->disabled;
        $arrayIdArea = array();
        if ($this->session['areas'] != "") {
            $filtro = " AND a.area_id in (" . $this->session['areas'] . ") ";
        }
        $combo = "<select name='idarea' id='idarea'  class='bootstrap-select' style='width:350px;' " . $dis . " >";
        $sql = "SELECT a.area_id,a.nombre FROM cat_areas AS a WHERE a.active=1 " . $filtro . " ORDER BY a.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ( $_idArea, $_nmArea ) = $this->db->sql_fetchrow($res)) {
                if (!in_array($_idArea, $arrayIdArea)) {
                    $arrayIdArea[] = $_idArea;
                    $tmp = "";
                    if ($_idArea == $this->arrayDatos['unidadResponsable_id'])
                        $tmp = " SELECTED ";
                    $combo.="<option value='" . $_idArea . "' " . $tmp . ">" . $_nmArea . "</option>";
                }
            }
        }
        $combo.="</select>";
        return $combo;
    }

    function regresaNombreProgramaAdmin($opcion) {
        $arrayIdPrograma = array();
        $dis = "";
        if ($opcion == 2)
            $dis = $this->disabled;
        $filtro = "";
        if ($this->session['areas'] != "") {
            $filtro = " AND a.area_id in (" . $this->session['areas'] . ") ";
        }
        if ($this->session['programas'] != "") {
            $filtro = " AND a.programa_id in (" . $this->session['programas'] . ") ";
        }
        if ($this->arrayDatos['unidadResponsable_id'] > 0) {
            $filtro.= " AND a.area_id = '" . $this->arrayDatos['unidadResponsable_id'] . "' ";
        }
        if ($this->data['idarea'] > 0)
            $filtro = " AND a.area_id='" . $this->data['idarea'] . "' ";
        if ($this->data['idprograma'] > 0)
            $filtro = " AND a.programa_id='" . $this->data['idprograma'] . "' ";


        $combo = "<select name='idprograma' id='idprograma' multiple  " . $dis . " style='width:350px;heigth=450px;' class='bootstrap-select'>";
        $sql = "SELECT a.programa_id,a.nombre
			  FROM cat_programas AS a
			  WHERE a.active=1 " . $filtro . " ORDER BY a.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ($_idPrograma, $_nmPrograma ) = $this->db->sql_fetchrow($res)) {
                if (!in_array($_idPrograma, $arrayIdPrograma)) {
                    $arrayIdPrograma[] = $_idPrograma;
                    $tmp = "";
                    if ($_idPrograma == $this->arrayDatos['programa_id'])
                        $tmp = " SELECTED ";
                    $combo.="<option value='$_idPrograma' " . $tmp . ">" . html_entity_decode($_nmPrograma) . "</option>";
                }
            }
            $combo.="</select>";
        }
        return $combo;
    }

    
    /**
     * Metodo que regresa el nombre de las tablas temporales
     * @param array con parametros de entrada
     */
    function regresaNombreTablas(){
    	$combo = "<select name='idTabla' id='idTabla' class='bootstrap-select'  " . $this->style . " >";
    	$sql = "SELECT id,tabla FROM cat_tablas WHERE activa=1 ORDER BY id;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	if ($this->db->sql_numrows($res) > 0) {
    		$combo.="<option value='0' class='franjaSeleccione'>" . TABLATEMPORAL . "</option>";
    		while (list ( $_idArea, $_nmArea ) = $this->db->sql_fetchrow($res)) {
    			$tmp = "";
    			if ($_idArea == $this->data['idTabla'])
    				$tmp = " SELECTED ";
    				$combo.="<option value='" . $_idArea . "' " . $tmp . ">" . utf8_encode($_nmArea) . "</option>";
    		}
    	}
    	$combo.="</select>";
    	return $combo;
    	 
    }
    
    function regresaNombreAreaAct() {
        $combo = "<select name='aidarea' id='aidarea' class='bootstrap-select'  " . $this->style . " >";
        $sql = "SELECT a.area_id,a.nombre FROM cat_areas AS a WHERE a.active=1 ORDER BY a.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
        	$combo.="<option value='0' class='franjaSeleccione'>" . AREA . "</option>";
            while (list ( $_idArea, $_nmArea ) = $this->db->sql_fetchrow($res)) {
              	$tmp = "";
                if ($_idArea == $this->data['aidarea'])
                	$tmp = " SELECTED ";
                $combo.="<option value='" . $_idArea . "' " . $tmp . ">" . trim($_nmArea) . "</option>";
            }
        }
        $combo.="</select>";
        return $combo;
    }
    /**
     * Metodo que regresa el nombre de la area
     * @param array con parametros de entrada
     */
    function regresaNombreArea() {
        $combo = "<select name='idarea' id='idarea' class='bootstrap-select'  " . $this->style . " >";
        $sql = "SELECT a.area_id,a.nombre FROM cat_areas AS a WHERE a.active=1 ORDER BY a.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
        	$combo.="<option value='0' class='franjaSeleccione'>" . AREA . "</option>";
            while (list ( $_idArea, $_nmArea ) = $this->db->sql_fetchrow($res)) {
              	$tmp = "";
                if ($_idArea == $this->data['idarea'])
                	$tmp = " SELECTED ";
                $combo.="<option value='" . $_idArea . "' " . $tmp . ">" . trim($_nmArea) . "</option>";
            }
        }
        $combo.="</select>";
        return $combo;
    }

    /**
     * Metodo que regresa el nombre del programa
     * @param array con parametros de entrada
     */
    function regresaNombrePrograma() {
    	$filtro = ""; 
    	if( (int) $this->data['aidarea'] > 0){
    		$filtro = " AND a.area_id like '%".$this->data['aidarea']."%' ";
    	}
        $combo = "<select name='idprograma' id='idprograma'  " . $this->style . " class='bootstrap-select'>";
        $sql = "SELECT a.programa_id,a.nombre FROM cat_programas AS a  
			    WHERE a.active=1 ".$filtro." ORDER BY a.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res)> 0) {
        	$combo.="<option value='0' class='franjaSeleccione'>" . PROGRAMA . "</option>";
            while (list ($_idPrograma, $_nmPrograma ) = $this->db->sql_fetchrow($res)) {
               $tmp = "";
               if ($_idPrograma == $this->data['idprograma'])
               $tmp = " SELECTED ";
               $combo.="<option value='$_idPrograma' " . $tmp . ">" . trim($_nmPrograma) . "</option>";
            }
            $combo.="</select>";
        }
        return $combo;
    }

    /**
     * Metodo que muestra el combo de unidades Operativas
      @param array de parametros
     */
    function regresaNombreUnidadOperativa() {
    	$filtro = "";
    	if( (int) $this->data['aidarea'] > 0){
    		$filtro = " AND a.area_id = '".$this->data['aidarea']."' ";
    	}    	 
        $combo = "<select name='idunidadoperativa' id='idunidadoperativa' class='bootstrap-select'". $this->style." >";
        $sql = "SELECT a.unidad_id,a.nombre FROM cat_unidad_operativas as a
    			WHERE a.active='1' ".$filtro." AND a.nombre IS NOT NULL ORDER BY a.nombre;";
        
		$res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
        	$combo.="<option value='0' class='franjaSeleccione'>" . UNIDADOPERATIVA . "</option>";
			while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
            	$tmp = "";
                if ($_id == $this->data['idunidadoperativa'])
                	$tmp = " SELECTED ";
                $combo .= "<option value='" . $_id . "' " . $tmp . ">" . trim($_nm) . "</option>";
            }
        }
        $combo .= "</select>";
        return $combo;
    }


    /**
     * 
     * @param unknown $id
     * @return string
     */
    function buscaLeyendaProyecto($id) {
        $mensaje = "No se cuenta con la descripci&oacute;n de la ayuda";
        $sql = "SELECT tit_ayuda,msg_ayuda FROM cat_ayuda_proyectos
			  WHERE id_ayuda='" . $id . "' LIMIT 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0)
            list ($tit_ayuda, $mensaje) = $this->db->sql_fetchrow($res);
        return utf8_encode("<b>" . $tit_ayuda . "</b><hr>" . $mensaje);
    }



    function metodoOpcion($data) {
        $comboOpcion = "<select name='idopcion' id='idopcion' class='bootstrap-select' style='width:80px;'>
						<option value='2'>N0</option><option value='1'>SI</option>
						</select>";
        return $comboOpcion;
    }

    function metodoParticipacion() {
        $comboOpcion = "<select name='idopcion' id='idopcion'  " . $this->disabled . " class='bootstrap-select' style='width:260px;'>";
        $sql = "select metodo_id,nombre FROM cat_metodo_participacion where active='1' order by orden;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ( $_id, $metodo) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == ($this->arrayDatos['participacion'] + 0))
                    $tmp = " SELECTED ";
                $comboOpcion.="<option value='$_id' class='seleccione' " . $tmp . ">" . $metodo . "</option>";
            }
        }
        $comboOpcion.= "</select>";
        return $comboOpcion;
    }

    function enCoordinacion() {
        $buffer = "";
        $array = array();
        if (count($this->arrayDatos) > 0) {
            if (trim($this->arrayDatos['en_coordinacion']) != "") {
                $array = explode('|', $this->arrayDatos['en_coordinacion']);
            }
        }
        $sql = "select id,coordinacion FROM cat_en_coordinacion where active='1' order by orden;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list($id, $nm) = $this->db->sql_fetchrow($res)) {
                $checked = "";
                if (in_array($id, $array))
                    $checked = " checked ";
                $buffer.="<br><input type='checkbox'  value='" . $id . "' id='coordinacion" . $id . "' 
						  name='coordinacionname' class='coordinacion' " . $checked . ">&nbsp;&nbsp;" . $nm;
            }
        }
        return $buffer;
    }

    function regresaDatosActividad($id) {
        $array = array();
        $sql = "SELECT * FROM proyectos_actividades WHERE id='" . $id . "' LIMIT 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            $array = $this->db->sql_fetchrow($res);
        }
        return $array;
    }

    function regresaDatosProyecto($id) {
        $array = array();
        $sql = "SELECT * FROM proyectos_acciones WHERE id='" . $id . "' LIMIT 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            $array = $this->db->sql_fetchrow($res);
        }
        return $array;
    }

    function regresaNoAcciones($id) {
        $total = 0;
        if ($id > 0) {
            $sqlc = "SELECT id FROM  proyectos_actividades WHERE active='1' AND proyecto_id='" . $id . "';";
            $resc = $this->db->sql_query($sqlc) or die($this->cadena_error);
            $total = $this->db->sql_numrows($resc);
        }
        return $total;
    }

    function generaFiltrosActividades() {
        $filtro = "";
        $tmp = explode('-', $this->data['folio']);
        if (($tmp[0] + 0) > 0)
            $filtro = " AND a.proyecto_id='" . $tmp[0] . "' ";
        if ($this->data['idMedida'] > 0)
            $filtro.= " AND a.medida_id = '" . $this->data['idMedida'] . "' ";
        if ($this->data['idTipoActividad'] > 0)
            $filtro.= " AND a.tipo_actividad_id = '" . $this->data['idTipoActividad'] . "' ";
        if ($this->data['ponderacion'] > 0)
            $filtro.= " AND a.ponderacion = '" . $this->data['ponderacion'] . "' ";
        if (trim($this->data['busqNombreA']) != "") {
            $filtro.= " AND a.actividad like '" . $this->data['busqNombreA'] . "%' ";
        }
        return $filtro;
    }

    function generaFiltrosAvances() {
        $filtro = "";
        if ($this->session['rol'] < 4) {
            if (trim($this->session['areas']) != "") {
                $filtro.= " AND a.unidadResponsable_id in (" . $this->session['areas'] . ") ";
            }
            if (trim($this->session['programas']) != "") {
                $filtro.= " AND a.programa_id in (" . $this->session['programas'] . ") ";
            }
        }
        if ($this->session['userArea'] != "" && $this->session['userArea'] != 0) {
            $filtro.= " AND a.unidadResponsable_id in (" . $this->session['userArea'] . ") ";
        }
        if ($this->data['idarea'] > 0)
            $filtro.= "AND a.unidadResponsable_id='" . $this->data['idarea'] . "' ";
        
        if ($this->data['aidarea'] > 0)
            	$filtro.= "AND a.unidadResponsable_id='" . $this->data['aidarea'] . "' ";
            
        if ($this->data['idprograma'] > 0)
            $filtro.= "AND a.programa_id='" . $this->data['idprograma'] . "' ";

        if ($this->data['idano'] > 0)
            $filtro.= "AND a.ano_id ='" . $this->data['idano'] . "' ";

        if ($this->session['anocaptura'] > 0)
            $filtro.= "AND a.ano_id ='" . $this->session['anocaptura'] . "' ";

        if ($this->data['idRol'] > 0)
            $filtro.= "AND a.rolId  ='" . $this->data['idRol'] . "' ";
        
        if ($this->data['idEstatus'] > 0)
            $filtro.= "AND a.estatus_avance_entrega  ='" . $this->data['idEstatus'] . "' ";

        if ($this->data['idTrimestre'] == "1") {
            $filtro.= "AND MONTH (a.fecha_alta) IN ('01','02','03') ";
        }
        if ($this->data['idTrimestre'] == "2") {
            $filtro.= "AND MONTH (a.fecha_alta) IN ('04','05','06') ";
        }
        if ($this->data['idTrimestre'] == "3") {
            $filtro.= "AND MONTH (a.fecha_alta) IN ('07','08','09') ";
        }
        if ($this->data['idTrimestre'] == "4") {
            $filtro.= "AND MONTH (a.fecha_alta) IN ('10','11','12') ";
        }
        if (trim($this->data['busqNombre']) != "") {
            $filtro.= "AND a.proyecto  LIKE '" . $this->data['busqNombre'] . "%' ";
        }
        if (trim($this->data['ponderacion']) > 0) {
            $filtro.= "AND a.ponderacion = '" . $this->data['ponderacion'] . "' ";
        }
        if (trim($this->data['tipo']) == 1) {
            $filtro.= "AND a.estatus_avance_entrega IN (2,5,8)";
        }
        if (trim($this->data['tipo']) == 2) {
            $filtro.= "AND a.estatus_avance_entrega IN (3,6,9)";
        }
        if (trim($this->data['tipo']) == 3) {
            $filtro.= "AND a.estatus_avance_entrega IN (4,7,10)";
        }
        /*if (count($this->arrayEstatusVisibles) > 0) {
            $filtro.= "AND a.estatus_avance_entrega IN (" . implode(',', $this->arrayEstatusVisibles) . ")";
        }*/
        if ($this->session['rol'] == 1) {
            $filtro.=" AND a.userId = '" . $this->session['userId'] . "' ";
        }
        if (!empty($this->session['letra'])) {
            $filtro.=" AND upper(a.proyecto) like '" . strtoupper($this->session['letra']) . "%' ";
        }
        return $filtro;
    }

    function generaFiltros() {
        $filtro = " AND activoP = '1' AND activoA = '1' ";
        if ($this->session['rol'] < 4) {
            if (trim($this->session['areas']) != "") {
                $filtro.= " AND a.unidadResponsable_id in (" . $this->session['areas'] . ") ";
            }
            if (trim($this->session['programas']) != "") {
                $filtro.= " AND a.programa_id in (" . $this->session['programas'] . ") ";
            }
        }
        if ($this->session['userArea'] != "" && $this->session['userArea'] != 0) {
            $filtro.= " AND a.unidadResponsableId in (" . $this->session['userArea'] . ") ";
        }
        if ($this->data['idarea'] > 0)
            $filtro.= "AND a.unidadResponsableId='" . $this->data['idarea'] . "' ";
        
        if ($this->data['aidarea'] > 0)
            	$filtro.= "AND a.unidadResponsableId='" . $this->data['aidarea'] . "' ";
        
        if ($this->data['idprograma'] > 0)
            $filtro.= "AND a.programa_id='" . $this->data['idprograma'] . "' ";

        /*if ($this->data['idano'] > 0)
            $filtro.= "AND a.ano_id ='" . $this->data['idano'] . "' ";*/

       /*if ($this->session['anocaptura'] > 0)
            $filtro.= "AND a.ano_id ='" . $this->session['anocaptura'] . "' ";
*/
        if ($this->data['idRol'] > 0)
            $filtro.= "AND a.rolId  ='" . $this->data['idRol'] . "' ";
        if ($this->data['idEstatus'] > 0)
            $filtro.= "AND a.estatus_entrega  ='" . $this->data['idEstatus'] . "' ";

        if ($this->data['idTrimestre'] == "1") {
            $filtro.= "AND MONTH (a.fecha_alta) IN ('01','02','03') ";
        }
        if ($this->data['idTrimestre'] == "2") {
            $filtro.= "AND MONTH (a.fecha_alta) IN ('04','05','06') ";
        }
        if ($this->data['idTrimestre'] == "3") {
            $filtro.= "AND MONTH (a.fecha_alta) IN ('07','08','09') ";
        }
        if ($this->data['idTrimestre'] == "4") {
            $filtro.= "AND MONTH (a.fecha_alta) IN ('10','11','12') ";
        }
        if (trim($this->data['busqNombre']) != "") {
            $filtro.= "AND a.proyecto  LIKE '" . $this->data['busqNombre'] . "%' ";
        }
        if (trim($this->data['ponderacion']) > 0) {
            $filtro.= "AND a.ponderacion = '" . $this->data['ponderacion'] . "' ";
        }
        if (trim($this->data['tipo']) == 1) {
            $filtro.= "AND a.estatus_entrega IN (2,5,8)";
        }
        if (trim($this->data['tipo']) == 2) {
            $filtro.= "AND a.estatus_entrega IN (3,6,9)";
        }
        if (trim($this->data['tipo']) == 3) {
            $filtro.= "AND a.estatus_entrega IN (4,7,10)";
        }
        if (count($this->arrayEstatusVisibles) > 0) {
            $filtro.= "AND a.estatus_entrega IN (" . implode(',', $this->arrayEstatusVisibles) . ")";
        }
        if ($this->session['rol'] == 1) {
            $filtro.=" AND a.userId = '" . $this->session['userId'] . "' ";
        }
        if (!empty($this->session['letra'])) {
            $filtro.=" AND upper(a.proyecto) like '" . strtoupper($this->session['letra']) . "%' ";
        }
        return $filtro;
    }

    function regresaNombreProyecto($filtro) {
        $nmProyecto = "";
        $sql = "SELECT CONCAT(proyecto,'|',estatus_entrega) as proyecto FROM proyectos_acciones where active='1' " . $filtro . " LIMIT 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            list($nmProyecto) = $this->db->sql_fetchrow($res);
        }
        return $nmProyecto;
    }

    function consultaNoActividades() {
        $total = 0;
        $filtro = $this->generaFiltrosActividades();
        $sql = "SELECT id FROM proyectos_actividades as a WHERE a.active='1' " . $filtro . ";";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $total = $this->db->sql_numrows($res);
        return $total;
    }

    function regresaDatosComentarios($id,$tablaC,$idActividad){    	
    	$arrayComentarios = array(1 => '',2 => '',3 => '',4 => '');
    	$filtro = "";
    	if((int) $id > 0){
    		$filtro .= " AND proyecto_id = '".$id."' ";
    	}
    	if((int) $idActividad > 0){
    		$filtro .= " AND actividad_id = '".$idActividad."' ";
    	}
    	$sqlComen = "SELECT trimestre_id,comentarios FROM ".$tablaC."
    				WHERE 1 ".$filtro." ORDER BY actividad_id,trimestre_id;";
    	$resComen = $this->db->sql_query($sqlComen) or die($this->cadena_error);
    	if($this->db->sql_numrows($resComen)>0){
    		while(list($tri,$comen) = $this->db->sql_fetchrow($resComen)){
    			$arrayComentarios[$tri] = trim($comen); 
    		}
    	}
    	return $arrayComentarios;
    }
    
    function regresaDatosAdjuntos($id,$tablaA,$idActividad){
    	$arrayAdjuntos = array(1 => '',2 => '',3 => '',4 => '');
    	$filtro = "";
    	if((int) $id > 0){
    		$filtro .= " AND proyecto_id = '".$id."' ";
    	}
    	if((int) $idActividad > 0){
    		$filtro .= " AND actividad_id = '".$idActividad."' ";
    	}
    	$sqlAdjun = "SELECT trimestre_id,archivo,path_web FROM ".$tablaA." WHERE 1 ".$filtro." ORDER BY actividad_id,trimestre_id;";
    	$resAdjun = $this->db->sql_query($sqlAdjun) or die($this->cadena_error);
    	if($this->db->sql_numrows($resAdjun)>0){
    		while(list($tri,$_file,$_path_web) = $this->db->sql_fetchrow($resAdjun)){
    			$arrayAdjuntos[$tri] = "<a href='".$_path_web."' target='_blank'>".trim($_file)."</a><br>"; 
    		}
    	}
    	return $arrayAdjuntos;
    }
    
    
    function regresaNombreTabla($idTabla){
    	$this->nombreTabla = "";
    	$sql = "SELECT tabla FROM cat_tablas WHERE id='".$idTabla."' LIMIT 1;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	list ($this->nombreTabla) = $this->db->sql_fetchrow($res);
    	
    }

    
    
    function regresaDatosTmp(){
    	$contador = 0;
    	$arrayRegreso = array();
    	if($this->nombreTabla != "" &&  $this->data['id'] > 0){    		
    		$tablaC = strtolower(str_replace("view_","view_c_",$this->nombreTabla));
    		$tablaA = strtolower(str_replace("view_","view_a_",$this->nombreTabla));
    		$sql = "SELECT * FROM ".$this->nombreTabla." WHERE activoP = '1' AND activoA='1' AND id='".$this->data['id']."' ORDER BY id,actividadId;";
    		$res = $this->db->sql_query($sql) or die($this->cadena_error);
    		if($this->db->sql_numrows($res) > 0){
    			while($arrayTmp = $this->db->sql_fetchrow($res)){
    				$idActividad = $arrayTmp['actividadId']; 
    				$arrayTmpComen = $this->regresaDatosComentarios($this->data['id'],$tablaC,$idActividad);
    				$arrayTmp['Comentarios1'] = $arrayTmpComen[1];
    				$arrayTmp['Comentarios2'] = $arrayTmpComen[2];
    				$arrayTmp['Comentarios3'] = $arrayTmpComen[3];
    				$arrayTmp['Comentarios4'] = $arrayTmpComen[4];
    				$arrayTmpAdjun = $this->regresaDatosAdjuntos($this->data['id'],$tablaA,$idActividad);
    				$arrayTmp['Adjuntos1']    = $arrayTmpAdjun[1];
    				$arrayTmp['Adjuntos2']    = $arrayTmpAdjun[2];
    				$arrayTmp['Adjuntos3']    = $arrayTmpAdjun[3];
    				$arrayTmp['Adjuntos4']    = $arrayTmpAdjun[4];
    				$arrayRegreso[$contador] = $arrayTmp;
    				$contador++;
    			}
    		}
    	}
    	return $arrayRegreso;
    }
        
	function actualizaDatosProyectoTablaPrincipal(){
		$this->nombreTabla = " proyectos_acciones ";
		if($this->nombreTabla != "" &&  $this->data['id'] > 0){
			$update = "UPDATE ".$this->nombreTabla."
    				   SET proyecto='".self::limpiaCadenasC($this->data['proyecto'])."',
    				   	   ponderacion = '".$this->data['ponderacion']."',
    				   	   presupuesto_otorgado= '".$this->data['presupuesto']."',
    				   	   presupuesto_estimado= '".$this->data['estimado']."' WHERE id ='".$this->data['id']."';";
			$res = $this->db->sql_query($update) or die ($this->cadena_error);
			if($res){
				return 1;
			}else{
				return 0;
			}
		}
		
	}
    
    function actualizaDatosProyectos(){
    	if($this->nombreTabla != "" &&  $this->data['id'] > 0){
    		$update = "UPDATE ".$this->nombreTabla." 
    				   SET proyecto='".self::limpiaCadenasC($this->data['proyecto'])."',
    				   	   ponderacionProyecto = '".$this->data['ponderacion']."',
    				   	   presupuesto_otorgado= '".$this->data['presupuesto']."',
    				   	   presupuesto_estimado= '".$this->data['estimado']."' WHERE id ='".$this->data['id']."';";
    		$res = $this->db->sql_query($update) or die ($this->cadena_error);
    		if($res){
    			return 1;
    		}else{
    			return 0;
    		}
    	}
    }
    
    function actualizaDatosActividadTablaPrincipal(){
    	$this->nombreTabla = "proyectos_actividades ";
    	if($this->nombreTabla != "" &&  $this->data['id'] > 0 && $this->data['idActividad'] > 0){
    		$total = (int) $this->data['v1'] + (int) $this->data['v2'] + (int) $this->data['v3'] +  (int) $this->data['v4'];
    		$totalA= (int) $this->data['a1'] + (int) $this->data['a2'] + (int) $this->data['a3'] +  (int) $this->data['a4'];
    		$update = "UPDATE ".$this->nombreTabla."
    				   SET actividad='".self::limpiaCadenasC($this->data['actividad'])."',
    				   	   ponderacion          = '".$this->data['ponderacion']."',
    				   	   tipo_actividad_id    = '".$this->data['tipoActividad']."',
    				   	   medida_id			= '".$this->data['medida']."'
    				   	   WHERE proyecto_id ='".$this->data['id']."'
    				   	   AND id = '".$this->data['idActividad']."';";
    		$res = $this->db->sql_query($update) or die ($this->cadena_error);
    		if($res){    			
    			$update = "UPDATE proyectos_acciones_metas
    				   SET trimestre1 = '".$this->data['v1']."',trimestre2 = '".$this->data['v2']."',
						   trimestre3 = '".$this->data['v3']."',trimestre4 = '".$this->data['v4']."',
						   total      = '".$total."'
					   	   WHERE proyecto_id ='".$this->data['id']."'
    				   	   AND actividad_id = '".$this->data['idActividad']."';";
    			
    			$res = $this->db->sql_query($update) or die ($this->cadena_error);
    			
    			$update = "UPDATE proyectos_acciones_avances
    				   	   SET trimestre1 = '".$this->data['a1']."',trimestre2 = '".$this->data['a2']."',
						   	   trimestre3 = '".$this->data['a3']."',trimestre4 = '".$this->data['a4']."',
						   	   total      = '".$totalA."'
    				   	   WHERE proyecto_id = '".$this->data['id']."'
    				   	   AND actividad_id  = '".$this->data['idActividad']."';";
    			
    			$res = $this->db->sql_query($update) or die ($this->cadena_error);
    			 
    			
    			$tablaC = " proyectos_avances_comentarios ";
    			self::actualizaComentarioTablaPrincipal($tablaC,self::limpiaCadenasC($this->data['c1']),$this->data['id'],$this->data['idActividad'],1);
    			self::actualizaComentarioTablaPrincipal($tablaC,self::limpiaCadenasC($this->data['c2']),$this->data['id'],$this->data['idActividad'],2);
    			self::actualizaComentarioTablaPrincipal($tablaC,self::limpiaCadenasC($this->data['c3']),$this->data['id'],$this->data['idActividad'],3);
    			self::actualizaComentarioTablaPrincipal($tablaC,self::limpiaCadenasC($this->data['c4']),$this->data['id'],$this->data['idActividad'],4);
    			return 1;
    		}else{
    			return 0;
    		}
    	}    	 
    }
    
    
    function actualizaDatosActividad(){
    	if($this->nombreTabla != "" &&  $this->data['id'] > 0){
    		$total = (int) $this->data['v1'] + (int) $this->data['v2'] + (int) $this->data['v3'] +  (int) $this->data['v4'];
    		$totalA= (int) $this->data['a1'] + (int) $this->data['a2'] + (int) $this->data['a3'] +  (int) $this->data['a4'];
    		$update = "UPDATE ".$this->nombreTabla."
    				   SET actividad='".self::limpiaCadenasC($this->data['actividad'])."',
    				   	   ponderacionActividad = '".$this->data['ponderacion']."',
    				   	   tipo_actividad_id    = '".$this->data['tipoActividad']."',
    				   	   medida_id			= '".$this->data['medida']."',
    				   	   medida               = '".self::regresaNombreMedida($this->data['medida'])."',
						   trimestre1           = '".$this->data['v1']."',
						   trimestre2           = '".$this->data['v2']."',
						   trimestre3           = '".$this->data['v3']."',
						   trimestre4           = '".$this->data['v4']."',
						   total                = '".$total."',
						   Atrimestre1          = '".$this->data['a1']."',
						   Atrimestre2          = '".$this->data['a2']."',
						   Atrimestre3          = '".$this->data['a3']."',
						   Atrimestre4          = '".$this->data['a4']."',
						   totalAvance          = '".$totalA."'
    				   	   WHERE id ='".$this->data['id']."' 
    				   	   AND actividadId = '".$this->data['idActividad']."';";
    		$res = $this->db->sql_query($update) or die ($this->cadena_error);
    		if($res){
    			$tablaC = strtolower(str_replace("view_","view_c_",$this->nombreTabla));
				self::actualizaComentario($tablaC,self::limpiaCadenasC($this->data['c1']),$this->data['id'],$this->data['idActividad'],1);
				self::actualizaComentario($tablaC,self::limpiaCadenasC($this->data['c2']),$this->data['id'],$this->data['idActividad'],2);
				self::actualizaComentario($tablaC,self::limpiaCadenasC($this->data['c3']),$this->data['id'],$this->data['idActividad'],3);
				self::actualizaComentario($tablaC,self::limpiaCadenasC($this->data['c4']),$this->data['id'],$this->data['idActividad'],4);
    			return 1;
    		}else{
    			return 0;
    		}
    	}    	 
    }

    function actualizaComentarioTablaPrincipal($tabla,$comentario,$idProyecto,$idActividad,$idTrimestre){
    	if(trim($comentario)!= ""){
    		$sql = "SELECT proyecto_id FROM ".$tabla." WHERE proyecto_id = '".$idProyecto."' AND actividad_id = '".$idActividad."' AND trimestre_id = '".$idTrimestre."' LIMIT 1";
    		$res = $this->db->sql_query($sql) or die($this->cadena_error);
    		if($this->db->sql_numrows($res) > 0){
    			$upd1 = "UPDATE ".$tabla. " set comentarios = '".$comentario."'
	    					WHERE proyecto_id ='".$idProyecto."' AND actividad_id = '".$idActividad."'
	    				    AND trimestre_id = '".$idTrimestre."' LIMIT 1;";
    			$this->db->sql_query($upd1) or die ($this->cadena_error);
    		}else{
    			$ins = "INSERT INTO ".$tabla." (proyecto_id,actividad_id,trimestre_id,comentarios,user_id,fecha_alta)
	    				VALUES ('".$idProyecto."','".$idActividad."','".$idTrimestre."','".$comentario."','".$this->session['userId']."','".date("Y-m-d H:i:s")."');";
    			$this->db->sql_query($ins) or die ($this->cadena_error);
    		}
    	}
    	 
    }
    
    function actualizaComentario($tabla,$comentario,$idProyecto,$idActividad,$idTrimestre){
    	if(trim($comentario)!= ""){
	    	$sql = "SELECT proyecto_id FROM ".$tabla." WHERE proyecto_id = '".$idProyecto."' AND actividad_id = '".$idActividad."' AND trimestre_id = '".$idTrimestre."' LIMIT 1";
	    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
	    	if($this->db->sql_numrows($res) == 0){
	    		$ins = "INSERT INTO ".$tabla." (proyecto_id,actividad_id,trimestre_id,comentarios)
	    				VALUES ('".$idProyecto."','".$idActividad."','".$idTrimestre."','".$comentario."');"; 
	    		$this->db->sql_query($ins) or die ($this->cadena_error);
	    	}else{
	    		$upd1 = "UPDATE ".$tabla. " set comentarios = '".$comentario."'
	    					WHERE proyecto_id ='".$idProyecto."' AND actividad_id = '".$idActividad."'
	    				    AND trimestre_id = '".$idTrimestre."' LIMIT 1;";
	    		$this->db->sql_query($upd1) or die ($this->cadena_error);
	    	}
    	}
    }
    
    
    function eliminaDatosProyectos(){
    	if($this->nombreTabla != "" &&  $this->data['id'] > 0){
    		$del= "UPDATE ".$this->nombreTabla." SET activoP = '0' WHERE id = '".$this->data['id']."';";
    		$res = $this->db->sql_query($del) or die ($this->cadena_error);
    		if($res){
    			return 1;
    		}else{
    			return 0;
    		}
    	}
    }
    
    function eliminaDatosActividad(){
    	if($this->nombreTabla != "" &&  $this->data['id'] > 0 && $this->data['idActividad'] > 0 ){
    		$del= "UPDATE ".$this->nombreTabla." SET activoA = '0' WHERE id = '".$this->data['id']."' AND actividadId = '".$this->data['idActividad']."';";
			$res = $this->db->sql_query($del) or die ($this->cadena_error);
    		if($res){
    			return 1;
    		}else{
    			return 0;
    		}    		
    	}
    }
    
    function actualizaVistaTablaPrincipal(){
    	if($this->nombreTabla != "" &&  $this->data['idTable'] > 0){
     		$tablaC = strtolower(str_replace("view_","view_c_",$this->nombreTabla));
     		$proyectos   = self::ejecutaSql("SELECT DISTINCT id,proyecto,ponderacionProyecto,presupuesto_otorgado,presupuesto_estimado FROM ".$this->nombreTabla." WHERE activoP = '1' ORDER BY id;");
     		$actividades = self::ejecutaSql("SELECT id,actividadId,actividad,ponderacionActividad,tipo_actividad_id,medida_id,trimestre1,trimestre2,trimestre3,trimestre4,total,Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4,totalAvance FROM ".$this->nombreTabla." WHERE activoP = '1' AND activoA = '1' ORDER BY id,actividadId;"); 
     		$comentarios = self::ejecutaSql("SELECT proyecto_id,actividad_id,trimestre_id,comentarios FROM ".$tablaC." ORDER BY proyecto_id,actividad_id,trimestre_id;");
 			if(count($proyectos) > 0){
 				self::actualizaProyectosBasePrincipal($proyectos);
 			}
 			if(count($actividades) > 0){
 				self::actualizaActividadesBasePrincipal($actividades);
 			}
 			
 			if(count($comentarios) > 0){
 				self::actualizaComentariosBasePrincipal($comentarios);
 			}		
			return 1;
    	}
    }
    
    function actualizaComentariosBasePrincipal($comentarios){
	    foreach($comentarios as $tmpdata){
	    	$update = "UPDATE proyectos_avances_comentarios SET comentarios = '".self::limpiaCadenasC($tmpdata['comentarios'])."'
    				   WHERE proyecto_id = '".$tmpdata['proyecto_id']."' AND id = '".$tmpdata['actividad_id']."' 
    				   AND trimestre_id = '".$tmpdata['trimestre_id']."' LIMIT 1;";
	    	echo"\n".$update;
	    	//$this->db->sql_query($update)  or die ($this->cadena_error);	    	
    	}
    }
    
    function actualizaActividadesBasePrincipal($actividades){
    	foreach($actividades as $tmpdata){
    		$update = "UPDATE proyectos_actividades SET actividad = '".self::limpiaCadenasC($tmpdata['actividad'])."',
    		 		   ponderacion = '".$tmpdata['ponderacionActividad']."',
    				   medida_id   = '".$tmpdata['medida_id']."',
    				   tipo_actividad_id = '".$tmpdata['tipo_actividad_id']."'  
    				   WHERE proyecto_id = '".$tmpdata['id']."' AND id = '".$tmpdata['actividadId']."' LIMIT 1;";
    		echo"\n".$update;
    		//$this->db->sql_query($update)  or die ($this->cadena_error);
    		    		
    		$sumaM   = $tmpdata['trimestre1'] + $tmpdata['trimestre2'] + $tmpdata['trimestre3'] + $tmpdata['trimestre4'] + 0;
    		$updateM = "UPDATE proyectos_acciones_metas SET trimestre1 = '".$tmpdata['trimestre1']."',
    					trimestre2 = '".$tmpdata['trimestre2']."' , trimestre3 = '".$tmpdata['trimestre3']."',
    					trimestre4 = '".$tmpdata['trimestre4']."',total = '".$sumaM."'
    					WHERE proyecto_id = '".$tmpdata['id']."' AND actividad_id = '".$tmpdata['actividadId']."' LIMIT 1;";
    		echo"\n".$updateM;
    		//$this->db->sql_query($updateM)  or die ($this->cadena_error);
    		
    		$sumaA   = $tmpdata['Atrimestre1'] + $tmpdata['Atrimestre2'] + $tmpdata['Atrimestre3'] + $tmpdata['Atrimestre4'] + 0;
    		$updateA = "UPDATE proyectos_acciones_avances SET trimestre1 = '".$tmpdata['Atrimestre1']."',
    					trimestre2 = '".$tmpdata['Atrimestre2']."', trimestre3 = '".$tmpdata['Atrimestre3']."',
    					trimestre4 = '".$tmpdata['Atrimestre4']."',total = '".$sumaA."'
    					WHERE proyecto_id = '".$tmpdata['id']."' AND actividad_id  = '".$tmpdata['actividadId']."' LIMIT 1;";
    		echo"\n".$updateA;
    		//$this->db->sql_query($updateA)  or die ($this->cadena_error);    		
    	}
    }
    
    function actualizaProyectosBasePrincipal($proyectos){
    	foreach($proyectos as $tmpdata){
    		$update = "UPDATE proyectos_acciones SET proyecto = '".self::limpiaCadenasC($tmpdata['proyecto'])."',
    				   ponderacion = '".$tmpdata['ponderacionProyecto']."',
    				   presupuesto_otorgado = '".$tmpdata['presupuesto_otorgado']."',
    				   presupuesto_estimado = '".$tmpdata['presupuesto_estimado']."'  
    				   WHERE id = '".$tmpdata['id']."' LIMIT 1;";
    		echo"\n".$update;
			//$this->db->sql_query($update)  or die ($this->cadena_error);
    	}
    }
    
    function ejecutaSql($sql){
    	$array = array();
    	$res = $this->db->sql_query($sql) or die ($this->cadena_error);
    	if($this->db->sql_numrows($res) > 0){
    		while($num = $this->db->sql_fetchrow($res)){
    			$array[]=$num;
    		}
    	}
    	return $array;
    }
    
    function utf8_converter($array)
    {
    	array_walk_recursive($array, function(&$item, $key){
    		if(!mb_detect_encoding($item, 'utf-8', true)){
    			$item = utf8_encode($item);
    		}
    	});
    		return $array;
    }
    
    function consultaNoProyectos() {
        $total = 0;
        $filtro = $this->generaFiltros();
        $sql = "SELECT DISTINCT(id) FROM ".$this->nombreTabla." as a WHERE 1 " . $filtro . ";";     
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $total = $this->db->sql_numrows($res);
        return $total;
    }

    function consultaProyectos() {
        $arrayResults = array();
        $array_rol = $this->catalogoRoles();
        $filtro = $this->generaFiltros();
        $sql = "SELECT DISTINCT(a.id),a.ano_id,a.fecha_alta,a.unidadResponsableId,a.area,programa_id,a.proyecto,a.unidadOperativaId FROM ".$this->nombreTabla." as a WHERE 1 " . $filtro . " ORDER BY a.id desc limit " . $this->session['page'] . "," . $this->session['regs'] . ";";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while ($array = $this->db->sql_fetchrow($res)) {
                $array['noAcciones'] = $this->regresaNoAcciones($array['id']);
                $array['nomRol'] = $array_rol[$array['rolId']];
                $arrayResults[] = $array;
            }
        }
        return $arrayResults;
    }



    function regresaMetasActividad($idActividad) {
        $array = array();
        $sql = "SELECT * FROM proyectos_acciones_metas WHERE actividad_id='" . $idActividad . "'LIMIT 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while ($arrayTmp = $this->db->sql_fetchrow($res)) {
                $total = $arrayTmp['total'];
                $array[1] = $arrayTmp['trimestre1'];
                $array[2] = $arrayTmp['trimestre2'];
                $array[3] = $arrayTmp['trimestre3'];
                $array[4] = $arrayTmp['trimestre4'];
                $array[5] = $total;
            }
        }
        return $array;
    }

    function regresaAvances($idproyecto) {
        $array = array();
        $sql = "SELECT * FROM proyectos_acciones_avances WHERE proyecto_id='" . $idproyecto . "' ORDER BY actividad_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while ($arrayTmp = $this->db->sql_fetchrow($res)) {
                $actividad_id = $arrayTmp['actividad_id'];
                $total = $arrayTmp['total'];
                $array[$actividad_id][1] = $arrayTmp['trimestre1'];
                $array[$actividad_id][2] = $arrayTmp['trimestre2'];
                $array[$actividad_id][3] = $arrayTmp['trimestre3'];
                $array[$actividad_id][4] = $arrayTmp['trimestre4'];
                $array[$actividad_id][5] = $total;
            }
        }
        return $array;
    }

    function regresaMetas($idproyecto) {
        $array = array();
        $sql = "SELECT * FROM proyectos_acciones_metas WHERE proyecto_id='" . $idproyecto . "' ORDER BY actividad_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while ($arrayTmp = $this->db->sql_fetchrow($res)) {
                $actividad_id = $arrayTmp['actividad_id'];
                $total = $arrayTmp['total'];
                $array[$actividad_id][1] = $arrayTmp['trimestre1'];
                $array[$actividad_id][2] = $arrayTmp['trimestre2'];
                $array[$actividad_id][3] = $arrayTmp['trimestre3'];
                $array[$actividad_id][4] = $arrayTmp['trimestre4'];
                $array[$actividad_id][5] = $total;
            }
        }
        return $array;
    }

    
    
    function fechaLimiteCaptura() {
    	$fecha = "";
    	$noDias = 0;
    	$sql = " SELECT fecha FROM cat_fecha_limite_metas LIMIT 1;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	if ($this->db->sql_numrows($res) > 0) {
    		list($fecha) = $this->db->sql_fetchrow($res);
    		$sqlf = " SELECT DATEDIFF('" . date('Y-m-d H:i:s') . "','" . $fecha . "');";
    		$resf = $this->db->sql_query($sqlf) or die($this->cadena_error);
    		list($noDias) = $this->db->sql_fetchrow($resf);
    	}
    	return $noDias;
    }
    function regresaLetras() {
    	$array = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "TODOS");
    	$buffer = "<table width='100%' border='0' align='center'><tr>";
    	foreach ($array as $letra) {
    		$letraT = $letra;
    		if ($letra == "TODOS")
    			$letraT = "";    
    			$buffer.="<td width='2%' class='tdcenter'>
 						<a class='negro' href='aplicacion.php?aplicacion=" . $this->session['aplicacion'] . "&apli_com=" . $this->session['apli_com'] . "&opc=0&letra=" . $letraT . "'>" . $letra . "</a>
 					</td>";
    	}
    	$buffer.="</tr></table>";
    	return $buffer;
    }
    
    function catalogoRoles() {
    	$array = array();
    	$sql = "SELECT rol_id,rol FROM cat_rol WHERE 1 ORDER BY rol_id;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	if ($this->db->sql_numrows($res) > 0) {
    		while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
    			$array [$_id] = $_nm;
    		}
    	}
    	return $array;
    }
    
    function catalogoAreas() {
    	$array = array();
    	$sql = "SELECT area_id,nombre FROM cat_areas WHERE active='1' ORDER BY nombre;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	if ($this->db->sql_numrows($res) > 0) {
    		while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
    			$array [$_id] = trim($_nm);
    		}
    	}
    	return $array;
    }
    
    function catalogoUnidadesOperativas() {
    	$array = array();
    	$sql = "SELECT unidad_id,nombre FROM cat_unidad_operativas WHERE active='1' ORDER BY nombre;";    	
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	if ($this->db->sql_numrows($res) > 0) {
    		while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
    			$array [$_id] = trim($_nm);
    		}
    	}
    	return $array;
    }
    
    function catalogoProgramas() {
    	$array = array();
    	$sql = "SELECT programa_id,nombre FROM cat_programas WHERE active='1' ORDER BY nombre;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	if ($this->db->sql_numrows($res) > 0) {
    		while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
    			$array [$_id] = trim($_nm);
    		}
    	}
    	return $array;
    }
  
    
    function catAnos(){
    	$array = array();
    	$sql="SELECT ano FROM cat_anos WHERE active = '1' ORDER BY ano desc;";
    	$res=$this->db->sql_query($sql) or die($this->cadena_error);
    	if($this->db->sql_numrows ( $res )>0){
    		while(list($id) = $this->db->sql_fetchrow($res)){
    			$array[$id] = utf8_encode($id);
    		}
    	}
    	return $array;
    }
    
    function catEjes(){
    	$array = array();
    	$sql="SELECT eje_id,nombre FROM cat_ejes WHERE active = '1' ORDER BY eje_id;";
    	$res=$this->db->sql_query($sql) or die($this->cadena_error);
    	if($this->db->sql_numrows ( $res )>0){
    		while(list($id,$nm) = $this->db->sql_fetchrow($res)){
	    		$array[$id] = utf8_encode($nm);
    		}
    	}
    	return $array;
    }
    
    function catProgramas(){
    	$array = array();
    	$sql="SELECT programa_id,nombre FROM cat_programas WHERE active = '1' ORDER BY programa_id;";
    	$res=$this->db->sql_query($sql) or die($this->cadena_error);
    	if($this->db->sql_numrows ( $res )>0){
    		while(list($id,$nm) = $this->db->sql_fetchrow($res)){    			
    			$array[$id] = utf8_encode($nm);
    		}
    	}
    	return $array;
    }
    
    function catAreas(){
    	$array = array();
    	$sql="SELECT area_id,nombre FROM cat_areas WHERE active = '1' ORDER BY area_id;";
    	$res=$this->db->sql_query($sql) or die($this->cadena_error);
    	if($this->db->sql_numrows ( $res )>0){
    		while(list($id,$nm) = $this->db->sql_fetchrow($res)){
    			$array[$id] = utf8_encode($nm);
    		}
    	}
    	return $array;
    }
    
    function catProyectos(){
    $array = array();
    	$sql="SELECT id,proyecto FROM proyectos_acciones WHERE active = '1'  ORDER BY proyecto;";
    	$res=$this->db->sql_query($sql) or die($this->cadena_error);
    	if($this->db->sql_numrows ( $res )>0){
    		while(list($id,$nm) = $this->db->sql_fetchrow($res)){
    			$array[$id] = utf8_encode($nm);
    		}
    	}
    	return $array;
    }
    
    
    
    /**
     * Metodo que se encarga de crear el combo de ponderacion
     * @param array $array de datos de session
     * @param array $array con los datos del proyecto
     * @return string regresa el combo
     */
    function regresaOpcionesPonderacion($valor) {
    	$combo = "";
    	$sql = "SELECT id FROM cat_ponderacion WHERE active='1' ORDER BY id asc;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	$num = $this->db->sql_numrows($res);
    	if ($num > 0) {
    		while (list ( $_id ) = $this->db->sql_fetchrow($res)) {
    			$tmp = "";
    			if ($_id == $valor)
    				$tmp = " SELECTED ";
    				$combo.="<option value='$_id' class='seleccione' " . $tmp . ">" . $_id . "</option>";
    		}
    	}    	
    	return $combo;
    }
    
    /**
     * Metodo que se encarga de crear el combo de tipo de actividad
     * @param array $array de datos de session
     * @param array $array con los datos del proyecto
     * @return string regresa el combo
     */
    function regresaOpcionesTipoActividad($valor) {
    	$combo = "<option value='0' class='franjaSeleccione'>" . TIPOACT . "</option>";
    	$sql = "SELECT  actividad_id,nombre FROM cat_tipo_actividad WHERE active='1' ORDER BY actividad_id;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	$num = $this->db->sql_numrows($res);
    	if ($num > 0) {
    		while (list ($_id, $_medida) = $this->db->sql_fetchrow($res)) {
    			$tmp = "";
    			if ($_id == $valor)
    				$tmp = " SELECTED ";
    			if (($_id != 5)) {
					$combo.="<option value='$_id' class='seleccione' " . $tmp . ">" . $_medida . "</option>";
    			} else {
    				if (($this->session['rol'] == 4))
    					$combo.="<option value='$_id' class='seleccione' " . $tmp . ">" . $_medida . "</option>";
    				else
    					if ($this->disabledAdmin != "")
    						$combo.="<option value='$_id' class='seleccione' " . $tmp . ">" . $_medida . "</option>";
   				}
   			}
   		}
   		return $combo;
    }
    
    /**
     * Metodo que se encarga de crear el combo de medidas
     * @param array $array de datos de session
     * @param array $array con los datos del proyecto
     * @return string regresa el 
     */
    function regresaOpcionesMedidas($valor) {
   		$combo = "<option value='0' class='franjaSeleccione'>" . MEDIDA . "</option>";
    	$sql = "SELECT medida_id,nombre FROM cat_medidas WHERE active='1' ORDER BY nombre;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	$num = $this->db->sql_numrows($res);
    	if ($num > 0) {
    		while (list ( $_id, $_medida) = $this->db->sql_fetchrow($res)) {
    			$tmp = "";
    			if ($_id == $valor)
    				$tmp = " SELECTED ";
    				$combo.="<option value='$_id' class='seleccione' " . $tmp . ">" . trim($_medida) . "</option>";
    		}
    	}
    	return $combo;
    }

    function regresaNombreMedida($valor) {
    	$_medida = "";
    	$sql = "SELECT nombre FROM cat_medidas WHERE medida_id='".$valor."' LIMIT 1;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	$num = $this->db->sql_numrows($res);
    	if ($num > 0) {
    		list ( $_medida) = $this->db->sql_fetchrow($res);
    	}
    	return trim($_medida);
    }
  
    function regresaComentarios($arrayComentarios){
    	$regreso = "";
    	if(count($arrayComentarios)>0){
    		foreach($arrayComentarios as $idTrimestre => $comen){
    			if(trim($comen)!= "")
    				$regreso.="<b>Trimestre: ".$idTrimestre."</b><br>".utf8_encode($comen)."<hr>";
    		}
    	}
    	return $regreso;
    }
    
    function regresaAdjuntos($arrayAdjuntos){
    	$regreso = "";
    	if(count($arrayAdjuntos)>0){
    		foreach($arrayAdjuntos as $idTrimestre => $adjunto){
    			if(trim($adjunto)!= "")
    				$regreso.="<b>Trimestre: ".$idTrimestre."</b><br>".utf8_encode($adjunto)."<hr>";
    		}
    	}
    	return $regreso;    	 
    }
}
?>