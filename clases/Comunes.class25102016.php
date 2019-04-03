<?php
class Comunes {

    var $cadena_error;
    var $arrayEstatus;

    function __construct() {
        $this->cadena_error = "<script>location.href='../logout.php'</script>";
        $this->arrayEstatus = array('AltaProyecto' => 1, 'AltaActividad' => 2, 'AltaMeta' => 3, 'AltaAvance' => 4,
            'ActualizaProyecto' => 5, 'ActualizaActividad' => 6, 'ActualizaMeta' => 7, 'ActualizaAvance' => 8,
            'EliminaProyecto' => 9, 'EliminaActividad' => 10, 'RestauraProyecto' => 11, 'RestauraActividad' => 12);
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
        $comboAnos = "<select name='idano' id='idano' class='bootstrap-select' style='width:80px;'><option value='0' class='franjaSeleccione'>" . ANO . "</option>";
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

    function generaAreaUser() {
        $combo = "<select name='idarea' id='idarea'  class='bootstrap-select'><option value='0' class='franjaSeleccione' >" . TODASAREAS . "</option>";
        $sql = "SELECT area_id,nombre FROM cat_areas WHERE active='1' " . $filtro . " ORDER BY nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $this->array_datos['area_id'])
                    $tmp = " SELECTED ";
                $combo .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        $combo.="</select>";
        return $combo;
    }

    function generaProgramaUser() {
        $filtro = "";
        if ($this->array_datos['area_id'] > 0) {
            $filtro = " AND a.area_id = '" . $this->array_datos['area_id'] . "' ";
        }
        $combo = "<select name='idprograma' multiple id='idprograma'  class='bootstrap-select' style='width:400px;height:130px;'>";
        $sql = "SELECT a.programa_id,b.nombre FROM cat_area_programa as a left join cat_programas as b
				 ON  a.programa_id  = b.programa_id 
			  	 WHERE a.programa_id  > 0 " . $filtro . " ORDER BY a.area_id,a.programa_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ($idprograma, $nmprograma) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if (in_array($idprograma, $this->array_areas))
                    $tmp = " SELECTED ";
                $combo.="<option value='" . $idprograma . "' " . $tmp . ">" . $nmprograma . "</option>";
            }
        }
        $combo.="</select>";
        return $combo;
    }

    function generaMenuUser() {
        $combo = "<select name='idmenu' id='idmenu'  class='bootstrap-select'><option value='0' class='franjaSeleccione' >" . MENU . "</option>";
        if (count($this->arrayDatos) > 0) {
            
        }
        $combo.="</select>";
        return $combo;
    }

    function generaSubMenuUser() {
        $combo = "<select name='idsubmenu' id='idsubmenu'  class='bootstrap-select'><option value='0' class='franjaSeleccione' >" . SUBMENU . "</option>";
        if (count($this->arrayDatos) > 0) {
            
        }
        $combo.="</select>";
        return $combo;
    }

    function regresaMenuUser() {
        $array = array();
        $array2 = array();
        $sql = "SELECT a.menu_id,a.nombre  
			  FROM cat_menu as a
			  WHERE a.menu_id != 1 AND a.menu_id !=7
			  ORDER BY a.menu_id";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ($idmenu, $nmmenu) = $this->db->sql_fetchrow($res)) {
                $array[$idmenu] = $nmmenu;
            }
        }
        return $array;
    }

    function regresaSubMenuUser($id) {
        $combo = $div = "";
        $contador = $contadorMenus = 0;
        if ($id > 0) {
            $sql = "SELECT a.submenu_id,a.nombre as nmSubmnu FROM cat_submenu as a 
			  	  WHERE a.menu_id  = '" . $id . "' ORDER BY a.submenu_id;";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            $num = $this->db->sql_numrows($res);
            if ($num > 0) {
                $combo = "<table width='100%' align='center'>";
                while (list ($idsubmenu, $nmsubmenu ) = $this->db->sql_fetchrow($res)) {
                    $contadorMenus++;
                    $div = "m-" . $id . "-s-" . $idsubmenu;
                    $tmp = "";
                    if (in_array($idsubmenu, $this->array_menus))
                        $tmp = " checked";
                    if ($contador % 4 == 0) {
                        $contador = 0;
                        $combo.="<tr>";
                    }
                    $combo.="<td>" . $contadorMenus . ".-&nbsp;&nbsp;&nbsp;<input type='checkbox' name='" . $div . "' id='" . $div . "' class='checkMenus' value='" . $div . "' " . $tmp . ">&nbsp;&nbsp;" . $nmsubmenu . "</td>";
                    $contador++;
                }
                $combo.="</table>";
            }
        }
        return $combo;
    }

    function generaAreas($cadenaAreas, $idArea, $opcion) {
        $comboAreas = "<select name='idarea' id='idarea'  class='bootstrap-select'><option value='0' class='franjaSeleccione' >" . AREAS . "</option>";
        if ($opcion == 1) {
            $comboAreas = "<select name='idarea' multiple id='idarea' class='bootstrap-select' style='width:400px;height:230px;'>";
        }
        $tmp = "";
        $filtro = "";
        if (trim($cadenaAreas) != "")
            $filtro = " AND area_id IN (" . $cadenaAreas . ") ";

        if ($idArea > 0) {
            
        }
        $sql = "SELECT area_id,nombre FROM cat_areas WHERE active='1' " . $filtro . " ORDER BY nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if (in_array($_id, $this->array_datosB))
                    $tmp = " SELECTED ";
                $comboAreas .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }

        $comboAreas .= "</select>";
        return $comboAreas;
    }

    function generaPoliticas($idPolitica, $opcion) {
        if ($opcion == 1)
            $combo = "<select multiple name='idpolitica' required id='idpolitica' class='bootstrap-select' style='width:500px;height:300px;'>";
        elseif ($opcion == 2)
            $combo = "<select name='idpolitica' id='idpolitica' class='bootstrap-select'><option value='0' class='franjaSeleccione'>" . POLITICAPUBLICA . "</option>";
        else
            $combo = "<select name='idpoliticap' id='idpoliticap' class='bootstrap-select' style='width:400px;'><option value='0' class='franjaSeleccione'>" . POLITICAPUBLICA . "</option>";
        $tmp = "";
        $filtro = "";
        if ($idPolitica >= 0) {
            $sql = "SELECT politica_id,nombre FROM cat_politicas WHERE active='1' " . $filtro . " ORDER BY nombre;";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            if ($this->db->sql_numrows($res) > 0) {
                while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                    $tmp = "";
                    if (in_array($_id, $this->array_datosB))
                        $tmp = " SELECTED ";
                    $combo .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
                }
            }
        }
        $combo .= "</select>";
        return $combo;
    }

    function generaEjes($idEje) {
        $combo = "<select name='ideje' id='ideje' class='bootstrap-select'><option value='0' class='franjaSeleccione'>" . EJEPOLITICA . "</option>";
        $tmp = "";
        $filtro = "";
        $sql = "SELECT eje_id,nombre FROM cat_ejes WHERE active='1' " . $filtro . " ORDER BY nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $idEje)
                    $tmp = " SELECTED ";
                $combo .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        $combo .= "</select>";
        return $combo;
    }

    function generaProgramas($areaId, $programaId, $opcion) {
        $comboProgramas = "<select name='idprograma' id='idprograma' class='bootstrap-select'>";
        $filtro = "";
        if ($opcion != 1) {
            $comboProgramas .= "<option value='0' class='franjaSeleccione'>" . PROGRAMAS . "</option>";
            if ($areaId > 0) {
                $filtro = "AND a.area_id='" . $areaId . "' ";
                $comboProgramas .= $this->generaQueryPrograma($db, $filtro, $programaId);
            }
        } else {
            if ($areaId > 0)
                $filtro = "AND a.area_id='" . $areaId . "' ";

            if ($programaId > 0)
                $filtro = "AND a.programa_id='" . $programaId . "' ";
            $comboProgramas .= $this->generaQueryPrograma($db, $filtro, $programaId);
        }
        $comboProgramas .= "</select>";
        return $comboProgramas;
    }

    function generaQueryPrograma($filtro) {
        $buffer = "";
        $sql = "SELECT DISTINCT(a.programa_id),b.nombre FROM cat_area_programa as a left join cat_programas as b
      ON a.programa_id=b.programa_id WHERE b.active='1' " . $filtro . " AND b.nombre IS NOT NULL ORDER BY b.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $programaId)
                    $tmp = " SELECTED ";
                $buffer .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        return $buffer;
    }

    function generaStatus() {
        $comboEstatus = "<select name='estatus' id='estatus' class='bootstrap-select'><option value='0' class='franjaSeleccione'>" . ESTATUS . "</option>";
        $tmp = "";
        $sql = "SELECT DISTINCT(estatus) FROM cat_estatus WHERE 1 ORDER BY estatus_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $idStatus)
                    $tmp = " SELECTED ";
                $comboEstatus .= "<option value='" . $_id . "' " . $tmp . ">" . $_id . "</option>";
            }
        }
        $comboEstatus .= "</select>";
        return $comboEstatus;
    }

    function generaActivos($Idstatus) {
        $tmp1 = $tmp2 = $tmp3 = "";
        switch ($Idstatus) {
            case 0 :
                $tmp1 = "INACTIVO";
                $tmp2 = $tmp3 = "";
                break;
            case 1 :
                $tmp2 = "ACTIVO";
                $tmp1 = $tmp3 = "";
                break;
            case 2 :
                $tmp3 = "ELIMINADO";
                $tmp1 = $tmp2 = "";
                break;
        }
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

    function generaStatusValor() {
        $tmp = "";
        $sql = "SELECT DISTINCT(estatus) FROM cat_estatus WHERE 1 ORDER BY estatus_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $idStatus)
                    $tmp = " SELECTED ";
                $comboEstatus .= "<option value='" . $_id . "' " . $tmp . ">" . $_id . "</option>";
            }
        }
        return $comboEstatus;
    }

    function generaEstilos() {
        $comboRol = "<select name='estilo' id='estilo' class='bootstrap-select' tabindex='8'>";
        $tmp = "";
        $sql = "SELECT id,nombre FROM cat_estilos WHERE 1 ORDER BY id;";
        $res = $this->db->sql_query($sql) or die($sql);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $this->array_datos['estilo_id'])
                    $tmp = " SELECTED ";
                $comboRol .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        $comboRol .= "</select>";
        return $comboRol;
    }

    function generaRol() {
        $comboRol = "<select name='rol' id='rol' class='bootstrap-select' tabindex='7'>
					<option value='0' class='franjaSeleccione'  >" . ROL . "</option>";
        $tmp = "";
        $sql = "SELECT rol_id,rol_catalogo FROM cat_rol WHERE 1 ORDER BY rol_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $this->array_datos['user_rol'])
                    $tmp = " SELECTED ";
                $comboRol .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        $comboRol .= "</select>";
        return $comboRol;
    }

    function generaRolValor() {
        $tmp = "";
        $sql = "SELECT rol_id,rol_catalogo FROM cat_rol WHERE 1 ORDER BY rol_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $idRol)
                    $tmp = " SELECTED ";
                $comboRol .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        return $comboRol;
    }

    function catalogoPoliticas() {
        $array = array();
        $sql = "SELECT politica_id,nombre FROM cat_politicas WHERE active='1' " . $filtro . " ORDER BY politica_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $array [$_id] = $_nm;
            }
        }
        return $array;
    }

    function catalogoEjes() {
        $array = array();
        $sql = "SELECT eje_id,nombre FROM cat_ejes WHERE active='1' " . $filtro . " ORDER BY nombre;";
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
        $sql = "SELECT area_id,nombre FROM cat_areas WHERE active='1' " . $filtro . " ORDER BY nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $array [$_id] = $_nm;
            }
        }
        return $array;
    }

    function catalogoProgramasP($idArea) {
        $array = array();
        $filtro = "";
        if ($idArea > 0)
            $filtro = " AND a.area_id = '" . $idArea . "' ";
        $sql = "SELECT a.programa_id,b.nombre FROM cat_area_programa as a LEFT JOIN cat_programas as b ON 
					a.programa_id=b.programa_id WHERE b.active='1' " . $filtro . " ORDER BY b.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $array [$_id] = $_nm;
            }
        }
        return $array;
    }

    function catalogoUsuarios() {
        $array = array();
        $sql = "SELECT user_id,user_nombre FROM cat_usuarios WHERE user_activo='1' ORDER BY user_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $array [$_id] = $_nm;
            }
        }
        return $array;
    }

    function comboUsuarios() {
        $combo = "<select name='idUsuario' id='idUsuario' tab='1' class='bootstrap-select' style='width:350px;'>";
        $combo.="<option value='0'>Seleccione</option>";
        $sql = "SELECT user_id,user_nombre FROM cat_usuarios WHERE user_activo='1' AND user_rol=1 ORDER BY user_nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $combo.="<option value='" . $_id . "'>" . $_nm . "</option>";
            }
        }
        $combo.="</select>";
        return $combo;
    }

    function catalogoProgramas() {
        $array = array();
        $sql = "SELECT programa_id,nombre FROM cat_programas WHERE active='1' " . $filtro . " ORDER BY nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $array [$_id] = $_nm;
            }
        }
        return $array;
    }

    function catalogoObjetivosG() {
        $array = array();
        $sql = "SELECT objetivo_id,nombre FROM cat_objetivos_generales WHERE active='1' " . $filtro . " ORDER BY nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $array [$_id] = $_nm;
            }
        }
        return $array;
    }

    function catalogoUnidadesOperativas() {
        $array = array();
        $sql = "SELECT unidad_id,nombre FROM cat_unidad_operativas WHERE active='1' " . $filtro . " ORDER BY nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $array [$_id] = $_nm;
            }
        }
        return $array;
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

    function catalogoMedidas() {
        $array = array();
        $sql = "SELECT medida_id,nombre FROM cat_medidas WHERE 1 ORDER BY medida_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $array [$_id] = $_nm;
            }
        }
        return $array;
    }

    function catalogoTipoActividad() {
        $array = array();
        $sql = "SELECT actividad_id,nombre FROM cat_tipo_actividad WHERE 1 ORDER BY actividad_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $array [$_id] = $_nm;
            }
        }
        return $array;
    }

    function catalogoNivelUser() {
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

    function procesaPoliticas($db, $_politicas) {
        $bf = "";
        if (strlen($_politicas) > 0) {
            $sql = " select nombre FROM cat_politicas WHERE politica_id IN (" . $_politicas . ") ORDER BY nombre";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            if ($this->db->sql_numrows($res) > 0) {
                while (list ( $_nm ) = $this->db->sql_fetchrow($res)) {
                    $bf .= "<b>*</b>&nbsp;" . $_nm . "<br>";
                }
            }
        }
        return $bf;
    }

    function RegresaPoliticas($db, $areaId) {
        $filtro = "";
        $bf = "";
        if ($areaId > 0)
            $filtro = " AND a.area_id = '" . $areaId . "'";
        $sql = "SELECT b.nombre FROM  cat_politica_area as a LEFT JOIN cat_politicas as b ON a.politica_id=b.politica_id
        WHERE 1 " . $filtro . " AND b.nombre IS NOT NULL ORDER BY b.nombre";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_nm ) = $this->db->sql_fetchrow($res)) {
                $bf .= "<b>*</b>&nbsp;" . $_nm . "<br>";
            }
        }
        return $bf;
    }

    function RegresaAreas($programaId) {
        $filtro = "";
        $bf = "";
        if ($programaId > 0)
            $filtro = " AND a.programa_id = '" . $programaId . "'";
        $sql = "SELECT b.nombre FROM  cat_area_programa as a LEFT JOIN cat_areas as b ON a.area_id=b.area_id
        WHERE 1 " . $filtro . " AND b.nombre IS NOT NULL ORDER BY b.nombre";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_nm ) = $this->db->sql_fetchrow($res)) {
                $bf .= "<b>*</b>&nbsp;" . $_nm . "<br>";
            }
        }
        return $bf;
    }

    function RegresaAreasUOperativas($unidadId) {
        $filtro = "";
        $bf = "";
        if ($unidadId > 0)
            $filtro = " AND a.unidad_id = '" . $unidadId . "'";
        $sql = "SELECT b.nombre FROM  cat_area_unidad_operativa as a LEFT JOIN cat_areas as b ON a.area_id=b.area_id
        WHERE 1 " . $filtro . " AND b.nombre IS NOT NULL ORDER BY b.nombre";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_nm ) = $this->db->sql_fetchrow($res)) {
                $bf .= "<b>*</b>&nbsp;" . $_nm . "<br>";
            }
        }
        return $bf;
    }

    function generaQueryObjetivosGenerales($filtro, $objetivoId) {
        $buffer = "";
        $sql = "SELECT objetivo_id,nombre FROM cat_objetivos_generales WHERE active='1' " . $filtro . " ORDER BY nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $objetivoId)
                    $tmp = " SELECTED ";
                $buffer .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        return $buffer;
    }

    function generaObjetivosGenerales($areaId, $programaId, $objetivoId, $opcion) {
        $combo = "<select name='idobjetivog' id='idobjetivog' class='bootstrap-select'>";
        $filtro = "";
        if ($opcion != 1) {
            $combo .= "<option value='0' class='franjaSeleccione'>" . OBJGEN . "</option>";
            if (($areaId > 0) && ($programaId > 0)) {
                $filtro = "AND area_id='" . $areaId . "' AND programa_id='" . $programaId . "' ";
                $combo .= $this->generaQueryObjetivosGenerales($filtro, $objetivoId);
            }
        } else {
            if ($areaId > 0)
                $filtro = "AND area_id='" . $areaId . "' ";
            if ($programaId > 0)
                $filtro .= "AND programa_id='" . $programaId . "' ";
            $combo .= $this->generaQueryObjetivosGenerales($filtro, $objetivoId);
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
        $sql = "SELECT subprograma_id,subprograma FROM cat_subprogramas WHERE active='1' " . $filtro . " ORDER BY subprograma;";
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
                if ($_id == $proyectoId)
                    $tmp = " SELECTED ";
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
                if ($_id == $idArea)
                    $tmp = " SELECTED ";
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
        $sql = "SELECT id FROM cat_ponderacion WHERE active='1' " . $filtro . " ORDER BY id asc;";
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

        $sql = "SELECT  actividad_id,nombre FROM cat_tipo_actividad WHERE active='1' " . $filtro . " ORDER BY actividad_id;";
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
        $sql = "SELECT medida_id,nombre FROM cat_medidas WHERE active='1' " . $filtro . " ORDER BY nombre;";
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
        $sql = "SELECT a.politica_id,b.eje_id,c.nombre
			  FROM cat_politica_area AS a LEFT JOIN cat_politicas AS b ON a.politica_id = b.politica_id
			  LEFT JOIN cat_ejes AS c ON b.eje_id=c.eje_id WHERE b.active=1 AND c.active=1 " . $filtro . " ORDER BY c.nombre;";

        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ( $_idPolitica, $_idEje, $_nmEje ) = $this->db->sql_fetchrow($res)) {
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
            $sql = "SELECT a.politica_id,b.eje_id,c.nombre
			  FROM cat_politica_area AS a LEFT JOIN cat_politicas AS b ON a.politica_id = b.politica_id
			  LEFT JOIN cat_ejes AS c ON b.eje_id=c.eje_id WHERE b.active=1 AND c.active=1 " . $filtro . " ORDER BY c.nombre;";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            $num = $this->db->sql_numrows($res);
            if ($num > 0) {
                while (list ( $_idPolitica, $_idEje, $_nmEje ) = $this->db->sql_fetchrow($res)) {
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
        $arrayNmArea = array();
        $nmAreas = "";
        if ($this->session['areas'] != "") {
            $filtro = " AND a.area_id in (" . $this->session['areas'] . ") ";
        }
        $combo = "<select name='idarea' id='idarea'  class='bootstrap-select' style='width:350px;' " . $dis . " >";
        $sql = "SELECT a.area_id,a.nombre FROM cat_areas AS a WHERE a.active=1 " . $filtro . " ORDER BY a.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        /* if($num > 1){
          $combo.="<option value='0' class='franjaSeleccione'>".AREA."</option>";
          } */
        if ($num > 0) {
            while (list ( $_idArea, $_nmArea ) = $this->db->sql_fetchrow($res)) {
                if (!in_array($_idArea, $arrayIdArea)) {
                    $arrayIdArea[] = $_idArea;
                    $arrayNmArea[$_idArea] = $_nmArea;
                    $tmp = "";
                    if ($_idArea == $this->arrayDatos['unidadResponsable_id'])
                        $tmp = " SELECTED ";
                    $combo.="<option value='" . $_idArea . "' " . $tmp . ">" . $_nmArea . "</option>";
                }
            }
            $nmAreas = implode("<br>", $arrayNmArea);
        }
        $combo.="</select>";
        return $combo;
    }

    function regresaNombreProgramaAdmin($opcion) {
        $arrayIdPrograma = array();
        $arrayNmPrograma = array();
        $nmPrograma = "";
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
// 		if($num > 1){
// 			$combo.="<option value='0' class='franjaSeleccione'>".PROGRAMA."</option>";
// 		}
        if ($num > 0) {
            while (list ($_idPrograma, $_nmPrograma ) = $this->db->sql_fetchrow($res)) {
                if (!in_array($_idPrograma, $arrayIdPrograma)) {
                    $arrayIdPrograma[] = $_idPrograma;
                    $arrayNmPrograma[$_idPrograma] = $_nmPrograma;
                    $tmp = "";
                    if ($_idPrograma == $this->arrayDatos['programa_id'])
                        $tmp = " SELECTED ";
                    $combo.="<option value='$_idPrograma' " . $tmp . ">" . html_entity_decode($_nmPrograma) . "</option>";
                }
            }
            $nmPrograma = implode("<br>", $arrayNmPrograma);
            $combo.="</select>";
        }
        return $combo;
    }

    /**
     * Metodo que regresa el nombre de la area
     * @param array con parametros de entrada
     */
    function regresaNombreArea($opcion) {
        $dis = "";
        if ($opcion == 2)
            $dis = $this->disabled;
        $arrayIdArea = array();
        $arrayNmArea = array();
        $nmAreas = "";
        if ($this->session['areas'] != "") {
            $filtro = " AND a.area_id in (" . $this->session['areas'] . ") ";
        }
        $combo = "<select name='idarea' id='idarea' class='bootstrap-select'  " . $dis . " >";
        $sql = "SELECT a.area_id,a.nombre FROM cat_areas AS a WHERE a.active=1 " . $filtro . " ORDER BY a.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 1) {
            $combo.="<option value='0' class='franjaSeleccione'>" . AREA . "</option>";
        }
        if ($num > 0) {
            while (list ( $_idArea, $_nmArea ) = $this->db->sql_fetchrow($res)) {
                if (!in_array($_idArea, $arrayIdArea)) {
                    $arrayIdArea[] = $_idArea;
                    $arrayNmArea[$_idArea] = $_nmArea;
                    $tmp = "";
                    if ($_idArea == $this->arrayDatos['unidadResponsable_id'])
                        $tmp = " SELECTED ";
                    $combo.="<option value='" . $_idArea . "' " . $tmp . ">" . $_nmArea . "</option>";
                }
            }
            $nmAreas = implode("<br>", $arrayNmArea);
        }
        $combo.="</select>";
        return $combo;
    }

    function regresaNombreAreaORI($data, $session, $datos) {
        $arrayIdArea = array();
        $arrayNmArea = array();
        $nmAreas = "";
        $filtro = "";
        if ($data['idarea'] > 0)
            $filtro = " AND a.area_id='" . $data['idarea'] . "' ";
        $sql = "SELECT a.area_id,a.nombre
			  FROM cat_areas AS a 
			  WHERE a.active=1 " . $filtro . " ORDER BY a.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ( $_idArea, $_nmArea ) = $this->db->sql_fetchrow($res)) {
                if (!in_array($_idArea, $arrayIdArea)) {
                    $arrayIdArea[] = $_idArea;
                    $arrayNmArea[$_idArea] = $_nmArea;
                }
            }
            $nmAreas = implode("<br>", $arrayNmArea);
        }
        return $nmAreas;
    }

    /**
     * Metodo que regresa el nombre del programa
     * @param array con parametros de entrada
     */
    function regresaNombrePrograma($opcion) {
        $arrayIdPrograma = array();
        $arrayNmPrograma = array();
        $nmPrograma = "";
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


        $combo = "<select name='idprograma' id='idprograma'  " . $dis . " class='bootstrap-select'>";
        $sql = "SELECT a.programa_id,b.nombre
			  FROM cat_area_programa as a left join cat_programas AS b on a.programa_id=b.programa_id 
			  WHERE b.active=1 " . $filtro . " ORDER BY b.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 1) {
            $combo.="<option value='0' class='franjaSeleccione'>" . PROGRAMA . "</option>";
        }
        if ($num > 0) {
            while (list ($_idPrograma, $_nmPrograma ) = $this->db->sql_fetchrow($res)) {
                if (!in_array($_idPrograma, $arrayIdPrograma)) {
                    $arrayIdPrograma[] = $_idPrograma;
                    $arrayNmPrograma[$_idPrograma] = $_nmPrograma;
                    $tmp = "";
                    if ($_idPrograma == $this->arrayDatos['programa_id'])
                        $tmp = " SELECTED ";
                    $combo.="<option value='$_idPrograma' " . $tmp . ">" . html_entity_decode($_nmPrograma) . "</option>";
                }
            }
            $nmPrograma = implode("<br>", $arrayNmPrograma);
            $combo.="</select>";
        }
        return $combo;
    }

    function regresaNombreProgramaORI($data) {
        $arrayIdPrograma = array();
        $arrayNmPrograma = array();
        $nmPrograma = "";
        $filtro = "";
        if ($data['idarea'] > 0)
            $filtro = " AND a.area_id='" . $data['idarea'] . "' ";
        if ($data['idprograma'] > 0)
            $filtro = " AND a.programa_id='" . $data['idprograma'] . "' ";

        $sql = "SELECT a.programa_id,a.nombre
			  FROM cat_programas AS a
			  WHERE a.active=1 " . $filtro . " ORDER BY a.nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ($_idPrograma, $_nmPrograma ) = $this->db->sql_fetchrow($res)) {
                if (!in_array($_idPrograma, $arrayIdPrograma)) {
                    $arrayIdPrograma[] = $_idPrograma;
                    $arrayNmPrograma[$_idPrograma] = $_nmPrograma;
                }
            }
            $nmPrograma = implode("<br>", $arrayNmPrograma);
        }
        return $nmPrograma;
    }

    /**
     * Metodo que regresa el nombre del objetivo general
     * @param array con parametros de entrada
     */
    function regresaNombreObjetivosGenerales($data) {
        
    }

    /**
     * Metodo que muestra el combo de unidades Operativas
      @param array de parametros
     */
    function generaUnidadesOperativas() {

        $combo = "<select name='idunidadoperativa'  " . $this->disabled . " id='idunidadoperativa' class='bootstrap-select' style='width:60%;'>";
        $exito = 0;
        if (count($this->arrayDatos) > 0) {
            $filtro = "";
            if ($this->data['idarea'] > 0) {
                $filtro = "AND a.area_id='" . $this->data['idarea'] . "' ";
            }
            if ($this->arrayDatos['unidadResponsable_id'] > 0) {
                $filtro = "AND a.area_id='" . $this->arrayDatos['unidadResponsable_id'] . "' ";
            }
            $exito = 1;
        }
        if ($this->session['areas'] != "") {
            $filtro.= " AND a.area_id in (" . $this->session['areas'] . ") ";
            $exito = 1;
        }
        if ($exito) {
            $sql = "SELECT a.unidad_id,b.nombre FROM cat_area_unidad_operativa as a 
			        LEFT JOIN cat_unidad_operativas as b
			        ON a.unidad_id = b.unidad_id
	    			WHERE b.active='1' " . $filtro . " AND b.nombre IS NOT NULL ORDER BY b.nombre;";

            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            $num = $this->db->sql_numrows($res);
            if ($num > 1) {
                $combo .= "<option value='0' class='franjaSeleccione'>" . SELECCIONEUNIDAD . "</option>";
            }
            if ($num > 0) {
                while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                    $tmp = "";
                    if ($_id == $this->arrayDatos['unidadOperativaId'])
                        $tmp = " SELECTED ";
                    $combo .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
                }
            }
        }
        $combo .= "</select>";
        return $combo;
    }

    function generaUnidadesOperativasOri() {
        $combo = "<select name='idunidadoperativa'  " . $this->disabled . " id='idunidadoperativa' class='bootstrap-select' style='width:60%;'>";
        $combo .= "<option value='0' class='franjaSeleccione'>" . SELECCIONEUNIDAD . "</option>";
        if (count($this->arrayDatos) > 0) {
            $filtro = "";
            if (($this->data['idarea'] > 0) && ($this->data['idprograma'] > 0)) {
                $filtro = "AND area_id='" . $this->data['idarea'] . "' AND programa_id='" . $this->data['idprograma'] . "' ";
            }
            if (($this->arrayDatos['unidadResponsable_id'] > 0) && ($this->arrayDatos['idprograma'] > 0)) {
                $filtro = "AND area_id='" . $this->arrayDatos['unidadResponsable_id'] . "' AND programa_id='" . $this->arrayDatos['idprograma'] . "' ";
            }

            $sql = "SELECT unidad_id,nombre FROM cat_unidad_operativas
	    			WHERE active='1' " . $filtro . " AND nombre IS NOT NULL ORDER BY nombre;";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            if ($this->db->sql_numrows($res) > 0) {
                while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                    $tmp = "";
                    if ($_id == $this->arrayDatos['unidadOperativaId'])
                        $tmp = " SELECTED ";
                    $combo .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
                }
            }
        }
        $combo .= "</select>";
        return $combo;
    }

    /**
     * Metodo que muestra el combo de responsables
      @param array de parametros
     */
    function generaResponsables() {

        $combo = $filtro = "";
        $combo = "<select name='idresponsableunidado'  " . $this->disabled . " id='idresponsableunidado' class='bootstrap-select' style='width:60%;'>
				 		<option value='0' class='franjaSeleccione'>" . SELECCIONERESPONSABLE . "</option>";
        if (count($this->arrayDatos) > 0) {
            if ($this->arrayDatos['personaResposanbleId'] > 0)
                $filtro = " AND id = '" . $this->arrayDatos['personaResposanbleId'] . "' ";
            $sql = "SELECT id,nombre FROM cat_unidad_operativa_responsables WHERE active='1' " . $filtro . " ORDER BY nombre;";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            if ($this->db->sql_numrows($res) > 0) {
                while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                    $tmp = "";
                    if ($_id == $this->arrayDatos['personaResposanbleId'])
                        $tmp = " SELECTED ";
                    $combo .= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
                }
            }
        }
        $combo.="</select>";
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

    function altaMetodosP($data) {
        $regreso = 0;
        $max = 0;
        $sql = "SELECT metodo_id FROM cat_metodo_participacion WHERE nombre='" . trim($data['inputNombre']) . "' LIMIT 1;";
        $res = $this->db->sql_query($res);
        if ($this->db->sql_numrows($res) == 0) {
            $sql = "SELECT max(orden) FROM cat_metodo_participacion WHERE 1;";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            if ($this->db->sql_numrows($res) > 0) {
                list($max) = $this->db->sql_fetchrow($res);
            }
            $max++;
            $ins = "INSERT INTO cat_metodo_participacion (nombre,orden,active)
    				  VALUES ('" . trim($data['inputNombre']) . "','" . $max . "','1')";
            $res = $this->db->sql_query($ins);
            if ($res) {
                $regreso = 1;
            }
        }
        return $regreso;
    }

    function altaUnidadOperativa($data) {
        $regreso = 0;
        $max = 0;
        if ($this->data['idarea'] > 0) {
            $sql = "SELECT unidad_id FROM cat_unidad_operativas WHERE nombre='" . trim($data['inputNombre']) . "' LIMIT 1;";
            $res = $this->db->sql_query($sql) or die(print_r($this->db->sql_error()));
            if ($this->db->sql_numrows($res) == 0) {
                $regreso = 1;
                $sql = "SELECT max(orden) FROM cat_unidad_operativas
	    				  WHERE area_id='" . $data['idarea'] . "' ;";
                $res = $this->db->sql_query($sql) or die($this->cadena_error);
                if ($this->db->sql_numrows($res) > 0) {
                    list($max) = $this->db->sql_fetchrow($res);
                }
                $max++;
                $ins = "INSERT INTO cat_unidad_operativas (area_id,nombre,orden,active)
	    				  VALUES ('" . $data['idarea'] . "','" . $data['inputNombre'] . "','" . $max . "','1');";
                $res = $this->db->sql_query($ins) or die($this->cadena_error);
                if ($res) {
                    $folio = $this->db->sql_nextid();
                    $regreso = 1;
                    $ins1 = "INSERT INTO cat_area_unidad_operativa(unidad_id,area_id) values ('" . $folio . "','" . $data['idarea'] . "');";
                    $res1 = $this->db->sql_query($ins1) or die($this->cadena_error);
                }
            } else {
                $regreso = -1;
            }
        }
        return $regreso;
    }

    function altaUnidadOperativaResponsable($data) {
        $regreso = 0;
        $max = 0;
        $sql = "SELECT id FROM cat_unidad_operativa_responsables WHERE nombre='" . trim($data['inputNombre']) . "' LIMIT 1;";
        $res = $this->db->sql_query($res);
        if ($this->db->sql_numrows($res) == 0) {
            $sql = "SELECT max(orden) FROM cat_unidad_operativa_responsables
    				  WHERE unidad_id='" . $data['idunidadoperativa'] . "';";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            if ($this->db->sql_numrows($res) > 0) {
                list($max) = $this->db->sql_fetchrow($res);
            }
            $max++;
            $ins = "INSERT INTO cat_unidad_operativa_responsables (unidad_id,nombre,orden,active)
    				  VALUES ('" . $data['idunidadoperativa'] . "','" . $data['inputNombre'] . "','" . $max . "','1')";
            $res = $this->db->sql_query($ins);
            if ($res) {
                $regreso = 1;
            }
        }
        return $regreso;
    }

    function altaProyecto($data) {
        $regreso = 0;
        $max = 0;
        $sql = "SELECT subprograma_id FROM cat_subprogramas WHERE subprograma='" . trim($data['inputNombre']) . "'
			  AND area_id='" . $data['idarea'] . "' AND programa_id='" . $data['idprograma'] . "' LIMIT 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) == 0) {
            $sql = "SELECT max(orden) FROM cat_subprogramas
    				  WHERE area_id='" . $data['idarea'] . "' AND programa_id='" . $data['idprograma'] . "';";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            if ($this->db->sql_numrows($res) > 0) {
                list($max) = $this->db->sql_fetchrow($res);
            }
            $max++;
            $ins = "INSERT INTO cat_subprogramas (area_id,programa_id,subprograma,titulo,orden,active)
    				  VALUES ('" . $data['idarea'] . "','" . $data['idprograma'] . "','" . $data['inputNombre'] . "','0','" . $max . "','1')";
            $res = $this->db->sql_query($ins) or die($this->cadena_error);
            if ($res) {
                $regreso = $this->db->sql_nextid();
            }
        }
        return $regreso;
    }

    function altaProyectoCompleto($data, $session) {
        $folio = 0;
        $fecha = date("Y-m-d H:i:s");
        $presupuesto = str_replace(",", "", $data['presupuesto']);
        $estimado = str_replace(",", "", $data['estimado']);
        $presupuesto = $presupuesto + 0.00;
        $estimado = $estimado + 0.00;
        $folioProyecto = $this->altaProyecto($data);
        $folioProyecto = 1;
        if ($folioProyecto > 0) {
            $ins = "INSERT INTO proyectos_acciones(userId,rolId,ano_id,unidadResponsable_id,programa_id,proyecto_id,proyecto,ponderacion,
											 descripcion,resultados,unidadOperativaId,personaResposanbleId,active,
											 en_coordinacion,especifique,participacion,describa,fecha_alta,fecha_modificacion,
											 presupuesto_otorgado,presupuesto_estimado,fecha_cambia_rol,estatus_entrega,estatus_avance_entrega,trimestre_avance_entrega)
			  	VALUES(\"" . $session['userId'] . "\",\"" . $session['rol'] . "\",\"" . $session['anocaptura'] . "\",\"" . $data['idarea'] . "\",\"" . $data['idprograma'] . "\",\"" . $folioProyecto . "\",\"" . $data['inputNombre'] . "\",
			  		\"" . $data['ponderacion'] . "\",\"" . $data['descripcion'] . "\",\"" . $data['resultados'] . "\",
			  		\"" . $data['idunidadoperativa'] . "\",\"" . $data['idresponsableunidado'] . "\",\"1\",
			  		\"" . $data['en_coordinacion'] . "\",\"" . $data['especifique'] . "\",\"" . $data['participacion'] . "\",
			  		\"" . $data['describa'] . "\",\"" . $fecha . "\",\"" . $fecha . "\",\"" . $presupuesto . "\",\"" . $estimado . "\",\"" . $fecha . "\",\"1\",\"1\",\"1\");";
            $res = $this->db->sql_query($ins) or die($this->cadena_error);
            if ($res)
                $folio = $this->db->sql_nextid();
            $ins_proyect = " INSERT INTO cat_proyectos (proyecto_id,unidad_responsable_id,programa_id) VALUES ('" . $folio . "','" . $data['idarea'] . "','" . $data['idprograma'] . "');";
            $res_proyect = $this->db->sql_query($ins_proyect) or die($this->cadena_error);

            $ins_estatus = " INSERT INTO proyectos_avances_estatus (proyecto_id) VALUES ('" . $folio . "');";
            $res_estatus = $this->db->sql_query($ins_estatus) or die($this->cadena_error);
        }else {
            $folio = -1;
        }
        $this->insertaBitacora($data, $session, $folio, 0, 0, 0, $this->arrayEstatus['AltaProyecto']);
        return $folio;
    }

    function actualizaDatosProyecto($data) {
        $regreso = 0;
        $upd = "UPDATE cat_subprogramas SET area_id='" . $data['idarea'] . "', programa_id='" . $data['idprograma'] . "', subprograma='" . $data['inputNombre'] . "' WHERE subprograma_id='" . $data['idproyecto'] . "' LIMIT 1;";
        $res = $this->db->sql_query($upd) or die($this->cadena_error);
        if ($res) {
            $regreso = $this->data['idproyecto'];
        }
        return $regreso;
    }

    function actualizaProyectoCompleto($data, $session) {
        $folio = 0;
        $fecha = date("Y-m-d H:i:s");
        $presupuesto = str_replace(",", "", $data['presupuesto']);
        $estimado = str_replace(",", "", $data['estimado']);
        $presupuesto = $presupuesto + 0.00;
        $estimado = $estimado + 0.00;
        if ($this->actualizaDatosProyecto($data) > 0) {
            $upd = "UPDATE proyectos_acciones
				  SET userId = \"" . $session['userId'] . "\",
				  	rolId = \"" . $session['rol'] . "\",
				  	ano_id= \"" . $data['idano'] . "\",
					unidadResponsable_id = \"" . $data['idarea'] . "\",
					programa_id = \"" . $data['idprograma'] . "\",
					proyecto_id = \"" . $data['idproyecto'] . "\",
					proyecto = \"" . $data['inputNombre'] . "\",
					ponderacion = \"" . $data['ponderacion'] . "\",
					descripcion = \"" . $data['descripcion'] . "\",
					resultados =  \"" . $data['resultados'] . "\",
					unidadOperativaId = \"" . $data['idunidadoperativa'] . "\",
					personaResposanbleId = \"" . $data['idresponsableunidado'] . "\",
					active = \"1\",
					en_coordinacion = \"" . $data['en_coordinacion'] . "\",
					especifique = \"" . $data['especifique'] . "\",
					participacion = \"" . $data['participacion'] . "\",
					fecha_modificacion = \"" . $fecha . "\",
					presupuesto_otorgado = \"" . $presupuesto . "\",
					presupuesto_estimado = \"" . $estimado . "\" WHERE id=\"" . $data['valor'] . "\" LIMIT 1;";
            $res = $this->db->sql_query($upd) or die($this->cadena_error);
            if ($res)
                $folio = $data['valor'];
        }
        $this->insertaBitacora($data, $session, $folio, 0, 0, 0, $this->arrayEstatus['ActualizaProyecto']);
        return $folio;
    }

    function eliminaProyectoCompleto($data, $session) {
        $regreso = 0;
        if (trim($data['folio']) != "") {
            $tmp = explode('-', $this->data['folio']);
            if (($tmp[0] + 0) > 0) {
                $del = "UPDATE proyectos_acciones SET active='0' WHERE id='" . ($tmp[0] + 0) . "' limit 1;";
                $res = $this->db->sql_query($del) or die($this->cadena_error);
                if ($res) {
                    $regreso = $tmp[0] + 0;
                    $del = "UPDATE cat_subprogramas SET active='0' WHERE subprograma_id='" . ($tmp[0] + 0) . "' limit 1;";
                    $res = $this->db->sql_query($del) or die($this->cadena_error);
                }
            }
        }
        $this->insertaBitacora($data, $session, $tmp[0], 0, 0, 0, $this->arrayEstatus['EliminaProyecto']);
        return $regreso;
    }

    function eliminaActividadCompleto($data, $session) {
        $regreso = 0;
        if (trim($data['folio']) != "") {
            $tmp = explode('-', $this->data['folio']);
            if (($tmp[0] + 0) > 0) {
                $del = "UPDATE proyectos_actividades SET active='0' WHERE id='" . ($tmp[0] + 0) . "' limit 1;";
                $res = $this->db->sql_query($del) or die($this->cadena_error);
                if ($res)
                    $regreso = $tmp[0] + 0;
            }
        }
        $this->insertaBitacora($data, $session, $folio, $tmp[0], 0, 0, $this->arrayEstatus['EliminaActividad']);
        return $regreso;
    }

    function actualizactividadCompleto($data, $session) {
        $folio = 0;
        if ($data['valor'] > 0) {
            $fecha = date("Y-m-d H:i:s");
            $upd = "UPDATE proyectos_actividades SET 
					userId = \"" . $session['userId'] . "\",
				  	rol_id = \"" . $session['rol'] . "\",
				  	trimestre_id = \"" . $data['idtrimestre'] . "\",
					actividad = \"" . $data['actividad'] . "\",
					medida_id = \"" . $data['idMedida'] . "\",
					ponderacion = \"" . $data['ponderacion'] . "\",
					tipo_actividad_id =\"" . $data['idTipoActividad'] . "\",
					fecha_modificacion = \"" . $fecha . "\"
				WHERE id=\"" . $data['valor'] . "\" AND proyecto_id=\"" . $data['idProyecto'] . "\"LIMIT 1;";
            $res = $this->db->sql_query($upd) or die($this->cadena_error);
            if ($res) {
                $folio = $data['valor'];
                $sql = "SELECT id FROM proyectos_acciones_metas WHERE actividad_id='" . $folio . "' limit 1;";
                $res = $this->db->sql_query($sql) or die($this->cadena_error);
                $num = $this->db->sql_numrows($res);

                if (($data['idTipoActividad'] == 1) || ($data['idTipoActividad'] == 5))
                    $total = $this->data['valor1'] + $this->data['valor2'] + $this->data['valor3'] + $this->data['valor4'] + 0;
                if ($data['idTipoActividad'] == 3) {
                    if ($this->data['valor1'] < $this->data['valor2'])
                        $menor = $this->data['valor1'];
                    else
                        $menor = $this->data['valor2'];
                    if ($menor > $this->data['valor3'])
                        $menor = $this->data['valor3'];
                    if ($menor > $this->data['valor4'])
                        $menor = $this->data['valor4'];
                    $total = $menor;
                }
                if ($data['idTipoActividad'] == 4) {
                    $total = $this->data['valor1'];
                }

                if ($num > 0) {
                    $total = $this->data['valor1'] + $this->data['valor2'] + $this->data['valor3'] + $this->data['valor4'] + 0;
                    $upd = "UPDATE proyectos_acciones_metas 
						SET trimestre1='" . $this->data['valor1'] . "',
							trimestre2='" . $this->data['valor2'] . "',
							trimestre3='" . $this->data['valor3'] . "',
							trimestre4='" . $this->data['valor4'] . "',
							total = '" . $total . "',
							user_id   ='" . $session['userId'] . "',
							rol_id    ='" . $session['rol'] . "'
						WHERE actividad_id ='" . $folio . "' AND proyecto_id='" . $data['idProyecto'] . "' limit 1;";
                    $res = $this->db->sql_query($upd) or die($this->cadena_error);
                    if ($data['idTipoActividad'] != 2) {
                        $this->insertaBitacora($data, $session, $data['idProyecto'], $folio, 0, 0, $this->arrayEstatus['ActualizaMeta']);
                    }
                } else {
                    $ins = "INSERT INTO proyectos_acciones_metas(user_id,rol_id,proyecto_id,actividad_id,trimestre1,trimestre2,trimestre3,trimestre4,total,fecha_alta,active)
							  VALUES('" . $session['userId'] . "','" . $session['rol'] . "','" . $data['idProyecto'] . "',
										 '" . $folio . "','" . $this->data['valor1'] . "','" . $this->data['valor2'] . "',
									 	'" . $this->data['valor3'] . "','" . $this->data['valor4'] . "','" . $total . "',
								 		'" . $fecha . "','1');";
                    $res = $this->db->sql_query($ins) or die($this->cadena_error);
                    if ($data['idTipoActividad'] != 2) {
                        $this->insertaBitacora($data, $session, $data['idProyecto'], $folio, 0, 0, $this->arrayEstatus['AltaMeta']);
                    }
                }
            }
        }
        $this->insertaBitacora($data, $session, $data['idProyecto'], $folio, 0, 0, $this->arrayEstatus['ActualizaActividad']);
        return $folio;
    }

    function altaActividadCompleto($data, $session) {
        $idProyecto = $data['idProyecto'] + 0;
        $folio = 0;
        $menor = 0;
        $random = $data['random'] + 0;
        if (($idProyecto > 0) && ($random > 0)) {
            $fecha = date("Y-m-d H:i:s");
            $sqlc = "SELECT id FROM proyectos_actividades WHERE actividad = '" . $data['actividad'] . "' AND proyecto_id='" . $idProyecto . "' AND active='1' limit 1;";

            $resc = $this->db->sql_query($sqlc) or die($this->cadena_error);
            if ($this->db->sql_numrows($resc) == 0) {
                $ins = "INSERT INTO proyectos_actividades(userId,rol_id,trimestre_id,proyecto_id,actividad,medida_id,ponderacion,
								 tipo_actividad_id,comentarios,active,fecha_alta,fecha_modificacion,estatus_entrega)
					  VALUES (\"" . $session['userId'] . "\",\"" . $session['rol'] . "\",\"" . $data['idtrimestre'] . "\",\"" . $idProyecto . "\",\"" . $data['actividad'] . "\",
							  \"" . $data['idMedida'] . "\",\"" . $data['ponderacion'] . "\",\"" . $data['idTipoActividad'] . "\",
							  \"" . $data['observacion'] . "\",\"1\",\"" . $fecha . "\",\"" . $fecha . "\",\"1\");";
                $res = $this->db->sql_query($ins) or die($this->cadena_error);
                if ($res) {
                    $folio = $this->db->sql_nextid();
                    if (($data['idTipoActividad'] == 1) || ($data['idTipoActividad'] == 5))
                        $total = $this->data['valor1'] + $this->data['valor2'] + $this->data['valor3'] + $this->data['valor4'] + 0;
                    if ($data['idTipoActividad'] == 3) {
                        if ($this->data['valor1'] < $this->data['valor2'])
                            $menor = $this->data['valor1'];
                        else
                            $menor = $this->data['valor2'];
                        if ($menor > $this->data['valor3'])
                            $menor = $this->data['valor3'];
                        if ($menor > $this->data['valor4'])
                            $menor = $this->data['valor4'];
                        $total = $menor;
                    }
                    if ($data['idTipoActividad'] == 4) {
                        $total = $this->data['valor1'];
                    }
                    if ($total > 0) {
                        $ins = "INSERT INTO proyectos_acciones_metas(user_id,rol_id,proyecto_id,actividad_id,trimestre1,trimestre2,trimestre3,trimestre4,total,fecha_alta,active)
							  VALUES('" . $session['userId'] . "','" . $session['rol'] . "','" . $idProyecto . "',
										 '" . $folio . "','" . $this->data['valor1'] . "','" . $this->data['valor2'] . "',
									 	'" . $this->data['valor3'] . "','" . $this->data['valor4'] . "','" . $total . "',
								 		'" . $fecha . "','1');";
                        $res = $this->db->sql_query($ins) or die($this->cadena_error);
                    }
                }
            } else {
                $folio = -1;
            }
        }
        $this->insertaBitacora($data, $session, $idProyecto, $folio, 0, 0, $this->arrayEstatus['AltaActividad']);
        return $folio;
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
            $filtro.= " AND a.actividad like '%" . $this->data['busqNombreA'] . "%' ";
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
            $filtro.= "AND a.proyecto  LIKE '%" . $this->data['busqNombre'] . "%' ";
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

        if ($this->data['idprograma'] > 0)
            $filtro.= "AND a.programa_id='" . $this->data['idprograma'] . "' ";

        if ($this->data['idano'] > 0)
            $filtro.= "AND a.ano_id ='" . $this->data['idano'] . "' ";

        if ($this->session['anocaptura'] > 0)
            $filtro.= "AND a.ano_id ='" . $this->session['anocaptura'] . "' ";

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
            $filtro.= "AND a.proyecto  LIKE '%" . $this->data['busqNombre'] . "%' ";
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

    function consultaActividades($limit) {
        $arrayResults = array();
        $arrayMedidas = $this->catalogoMedidas();
        $arrayTiposAct = $this->catalogoTipoActividad();
        $filtro = $this->generaFiltrosActividades();
        $sql = "SELECT a.id,a.proyecto_id, a.actividad, a.medida_id, a.ponderacion, 
			a.tipo_actividad_id, b.trimestre1, b.trimestre2, 
			b.trimestre3, b.trimestre4, b.total,a.estatus_entrega,a.estatus_avance_entrega_t1,
			a.estatus_avance_entrega_t2,a.estatus_avance_entrega_t3,a.estatus_avance_entrega_t4
			FROM proyectos_actividades as a
			LEFT JOIN proyectos_acciones_metas AS b ON a.id = b.actividad_id
			WHERE a.active = 1 " . $filtro . " ORDER BY id desc " . $limit . ";";
        $res = $this->db->sql_query($sql) or die($sql);
        if ($this->db->sql_numrows($res) > 0) {
            while ($array = $this->db->sql_fetchrow($res)) {
                $array['medida'] = $arrayMedidas[$array['medida_id']];
                $array['tipoAct'] = $arrayTiposAct[$array['tipo_actividad_id']];
                $array['idtipoAct'] = $array['tipo_actividad_id'];
                $arrayResults[] = $array;
            }
        }
        return $arrayResults;
    }

    function regresaNombreTrimestre() {
        $tmp1 = $tmp2 = $tmp3 = $tmp4 = "";
        if ($this->arrayDatos['trimestre_id'] > 0) {
            switch ($this->arrayDatos['trimestre_id']) {
                case 1:
                    $tmp1 = " SELECTED ";
                    $tmp2 = $tmp3 = $tmp4 = "";
                    break;
                case 2:
                    $tmp2 = " SELECTED ";
                    $tmp1 = $tmp3 = $tmp4 = "";
                    break;
                case 3:
                    $tmp3 = " SELECTED ";
                    $tmp2 = $tmp1 = $tmp4 = "";
                    break;
                case 4:
                    $tmp4 = " SELECTED ";
                    $tmp2 = $tmp3 = $tmp1 = "";
                    break;
            }
        }
        $comboTrimestre = "<select name='idtrimestre' id='idtrimestre' class='bootstrap-select' style='width:130px;'>
						<option value='1' " . $tmp1 . ">" . TRIMESTRE1 . "</option>
						<option value='2' " . $tmp2 . ">" . TRIMESTRE2 . "</option>
						<option value='3' " . $tmp3 . ">" . TRIMESTRE3 . "</option>
						<option value='4' " . $tmp4 . ">" . TRIMESTRE4 . "</option></select>";
        return $comboTrimestre;
    }

    function regresaNombreRol() {
        $comboOpcion = "<select name='idRol' id='idRol' class='bootstrap-select' style='width:180px;'><option value='0'>Seleccione Rol</option>";
        $sql = "select rol_id,rol FROM  cat_rol where activo='1' order by rol_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ( $_id, $metodo) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == ($this->arrayDatos['rolId'] + 0))
                    $tmp = " SELECTED ";
                $comboOpcion.="<option value='$_id' class='seleccione' " . $tmp . ">" . $metodo . "</option>";
            }
        }
        $comboOpcion.= "</select>";
        return $comboOpcion;
    }

    function regresaNombreEstatus() {
        $comboOpcion = "<select name='idEstatus' id='idEstatus' class='bootstrap-select' style='width:180px;'><option value='0'>Seleccione Estatus</option>";
        $sql = "select notificacion_id,notificacion FROM  cat_estatus_notificaciones where 1 order by notificacion_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $num = $this->db->sql_numrows($res);
        if ($num > 0) {
            while (list ( $_id, $metodo) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == ($this->arrayDatos['rolId'] + 0))
                    $tmp = " SELECTED ";
                $comboOpcion.="<option value='$_id' class='seleccione' " . $tmp . ">" . $metodo . "</option>";
            }
        }
        $comboOpcion.= "</select>";
        return $comboOpcion;
    }

    function consultaNoProyectos() {
        $total = 0;
        $filtro = $this->generaFiltros();
        $sql = "SELECT id FROM proyectos_acciones as a WHERE a.active='1' " . $filtro . ";";
        $res = $this->db->sql_query($sql) or die("error:   " . $sql);
        $total = $this->db->sql_numrows($res);
        return $total;
    }

    function consultaProyectos() {
        $arrayResults = array();
        $array_rol = $this->catalogoRoles();
        $filtro = $this->generaFiltros();
        $sql = "SELECT * FROM proyectos_acciones as a WHERE a.active='1' " . $filtro . " ORDER BY a.id desc limit " . $this->session['page'] . "," . $this->session['regs'] . ";";
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

    function generaAvancesExcel() {
        $buffer = "";
        $arrayResults = array();
        $filtro = $this->generaFiltrosAvances();
        $sql = "SELECT a.id,a.ano_id,c.nombre as area,d.nombre as programa,a.proyecto,a.ponderacion as ponderacionProyecto,
		a.presupuesto_otorgado,a.presupuesto_estimado,b.actividad,b.ponderacion as ponderacionActividad,
		b.tipo_actividad_id,e.nombre as medida,f.trimestre1,f.trimestre2,f.trimestre3,f.trimestre4,f.total,
                g.nombre as politica, h.nombre as eje
		FROM proyectos_acciones as a
		left join proyectos_actividades as b on a.id=b.proyecto_id
		left join proyectos_acciones_metas as f on b.proyecto_id = f.proyecto_id and b.id = f.actividad_id
		inner join cat_areas as c on a.unidadResponsable_id = c.area_id
		inner join cat_programas as d on a.programa_id=d.programa_id
		inner join cat_medidas as e on b.medida_id = e.medida_id
                inner join cat_politica_programa as j on a.programa_id = j.programa_id
                inner join cat_politicas as g on j.politica_id = g.politica_id
                inner join cat_ejes as h on h.eje_id = g.eje_id
		WHERE a.active='1' " . $filtro . " ORDER BY a.unidadResponsable_id,a.programa_id,a.id,b.id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while ($array = $this->db->sql_fetchrow($res)) {
                $arrayResults[] = $array;
            }
            $buffer = $this->exportaDatos($arrayResults);
        }
        return $buffer;
    }

    function generaProyectosExcel() {
        $buffer = "";
        $arrayResults = array();
        $filtro = $this->generaFiltros();
        $sql = "SELECT a.id,a.ano_id,c.nombre as area,d.nombre as programa,a.proyecto,a.ponderacion as ponderacionProyecto,
		a.presupuesto_otorgado,a.presupuesto_estimado,b.actividad,b.ponderacion as ponderacionActividad,
		b.tipo_actividad_id,e.nombre as medida,
                f.trimestre1,f.trimestre2,f.trimestre3,f.trimestre4,(f.trimestre1 + f.trimestre2 +f.trimestre3 +f.trimestre4) as total,
		h.trimestre1 as Atrimestre1,h.trimestre2 as Atrimestre2,h.trimestre3 as Atrimestre3,h.trimestre4 as Atrimestre4,
                (h.trimestre1 + h.trimestre2 + h.trimestre3 + h.trimestre4) as totalAvance,g.nombre as politica, w.nombre as eje
		FROM proyectos_acciones as a
		left join proyectos_actividades as b on b.proyecto_id = a.id
		left join proyectos_acciones_metas as f on f.proyecto_id = b.proyecto_id and f.actividad_id = b.id
		left join proyectos_acciones_avances as h on h.proyecto_id = b.proyecto_id and h.actividad_id = b.id
		inner join cat_areas as c on a.unidadResponsable_id = c.area_id
		inner join cat_programas as d on a.programa_id=d.programa_id
		inner join cat_medidas as e on b.medida_id = e.medida_id
                inner join cat_politica_programa as j on a.programa_id = j.programa_id
                inner join cat_politicas as g on j.politica_id = g.politica_id
                inner join cat_ejes as w on w.eje_id = g.eje_id                
		WHERE a.active='1' " . $filtro . " ORDER BY a.unidadResponsable_id,a.programa_id,a.id,b.id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while ($array = $this->db->sql_fetchrow($res)) {
                $arrayResults[] = $array;
            }
            $buffer = $this->exportaDatos($arrayResults);
        }
        return $buffer;
    }

    function exportaDatos($arrayResults) {
        $buffer = "";
        $border = " style='border:1px solid #000; '";
        $bordera = " style='border:1px solid #000;background-color:#F4FA58;text-align:center; '";
        $borderb = " style='border:1px solid #000;background-color:#FE9A2E;text-align:center; '";
        if (count($arrayResults) > 0) {

            $buffer = "<table>
					<tr>
					<td>Id</td><td>A&ntilde;o</td>
                                        <td>Eje</td><td>Pol&iacute;tica</td>
                                        <td>&Aacute;rea</td><td>Programa</td><td>Proyecto</td><td>Ponderaci&oacute;n Proyecto</td>
					<td>Presupuesto Otorgado</td><td>Presupuesto Estimado</td><td>Actividad</td><td>Ponderaci&oacute;n Actividad</td>
					<td>Tipo de actividad</td><td>Medida</td><td>Meta Trimestre 1</td><td>Avance Trimestre 1</td><td>Meta Trimestre2</td><td>Avance Trimestre 2</td>
					<td>Meta Trimestre3</td><td>Avance Trimestre 3</td><td>Meta Trimestre4</td><td>Avance Trimestre 4</td><td>Metas Total</td><td>Avance Total</td>
					</tr>";
            foreach ($arrayResults as $ind => $array) {
                $buffer.="<tr>
					<td " . $border . ">" . $array['id'] . "</td>
					<td " . $border . ">" . $array['ano_id'] . "</td>
					<td " . $border . ">" . $array['eje'] . "</td>
					<td " . $border . ">" . $array['politica'] . "</td>                                            
					<td " . $border . ">" . $array['area'] . "</td>
					<td " . $border . ">" . $array['programa'] . "</td>
					<td " . $border . ">" . $array['proyecto'] . "</td>
					<td " . $border . ">" . $array['ponderacionProyecto'] . "</td>
					<td " . $border . ">" . $array['presupuesto_otorgado'] . "</td>
					<td " . $border . ">" . $array['presupuesto_estimado'] . "</td>
					<td " . $border . ">" . $array['actividad'] . "</td>
					<td " . $border . ">" . $array['ponderacionActividad'] . "</td>
					<td " . $border . ">" . $array['tipo_actividad_id'] . "</td>
					<td " . $border . ">" . $array['medida'] . "</td>
					<td " . $bordera . ">" . ($array['trimestre1'] + 0) . "</td>
					<td " . $borderb . ">" . ($array['Atrimestre1'] + 0) . "</td>
					<td " . $bordera . ">" . ($array['trimestre2'] + 0) . "</td>
					<td " . $borderb . ">" . ($array['Atrimestre2'] + 0) . "</td>
					<td " . $bordera . ">" . ($array['trimestre3'] + 0) . "</td>
					<td " . $borderb . ">" . ($array['Atrimestre3'] + 0) . "</td>
					<td " . $bordera . ">" . ($array['trimestre4'] + 0) . "</td>
					<td " . $borderb . ">" . ($array['Atrimestre4'] + 0) . "</td>
					<td " . $bordera . ">" . ($array['trimestre1'] + $array['trimestre2'] + $array['trimestre3'] + $array['trimestre4'] + 0 ) . "</td>
					<td " . $borderb . ">" . ($array['Atrimestre1'] + $array['Atrimestre2'] + $array['Atrimestre3'] + $array['Atrimestre4'] + 0 ) . "</td>
					</tr>";
            }
            $buffer.="<tr><td colspan='22'>Total de registros:  " . count($arrayResults) . "</td></tr></table>";
        }
        return $buffer;
    }

    //function consultaNoProyectos($data,$session,$datos){
    function consultaNoProyectosAvances() {
        $total = 0;
        $filtro = $this->generaFiltrosAvances();
        $sql = "SELECT id FROM proyectos_acciones as a WHERE a.active='1' AND a.estatus_entrega=10 " . $filtro . ";";
        
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        $total = $this->db->sql_numrows($res);
        return $total;
    }

    function consultaProyectosAvances() {
        /* $campos="distinct(a.id),a.userId,a.rolId,a.ano_id,a.unidadResponsable_id,a.programa_id,a.proyecto_id,a.proyecto,a.ponderacion,a.unidadOperativaId,a.personaResposanbleId,a.active,a.fecha_alta,a.estatus_avance_entrega,a.trimestre_avance_entrega,a.unidad_responsables,
          b.estatus_avance_entrega_t1,b.estatus_avance_entrega_t2,b.estatus_avance_entrega_t3,b.estatus_avance_entrega_t4"; */
        $campos = "distinct(a.id),a.userId,a.rolId,a.ano_id,a.unidadResponsable_id,a.programa_id,a.proyecto_id,a.proyecto,a.ponderacion,a.unidadOperativaId,a.personaResposanbleId,a.active,a.fecha_alta,a.estatus_avance_entrega,a.estatus_avance_entrega2,a.estatus_avance_entrega3,a.estatus_avance_entrega4,a.trimestre_avance_entrega,a.unidad_responsables";
        $arrayResults = array();
        $array_rol = $this->catalogoRoles();
        $filtro = $this->generaFiltrosAvances();
        /* 		$sql="SELECT $campos FROM proyectos_acciones AS a LEFT JOIN proyectos_actividades AS b ON a.id=b.proyecto_id
          WHERE a.active='1' AND a.estatus_entrega=10 ".$filtro." ORDER BY a.id desc limit ".$this->session['page'].",".$this->session['regs'].";";
         */
        $sql = "SELECT $campos FROM proyectos_acciones AS a 
		WHERE a.active='1' AND a.estatus_entrega=10 " . $filtro . " ORDER BY a.id desc limit " . $this->session['page'] . "," . $this->session['regs'] . ";";
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

    /* function consultaProyectos(){
      $arrayResults=array();
      $array_rol = $this->catalogoRoles();
      $filtro = $this-> generaFiltros();
      $sql="SELECT * FROM proyectos_acciones WHERE active='1' ".$filtro." ORDER BY id desc ".$this->pages->limit.";";
      $res=$this->db->sql_query($sql) or die($this->cadena_error);
      if($this->db->sql_numrows($res)>0){
      while($array = $this->db->sql_fetchrow($res)){
      $array['noAcciones']=$this->regresaNoAcciones($array['id']);
      $array['nomRol'] = $array_rol[$array['rolId']];
      $arrayResults[]=$array;
      }
      }
      return $arrayResults;
      } */

	function eliminaTemporales($path_sis){
		$pathTtmp=$path_sis."tmp/";
    	$directorio=opendir($pathTtmp) or die("Falta la carpeta Tmp");
    	while($archivo=readdir($directorio))
    	{
    		$trozos = explode(".", $archivo);
    		$extension = end($trozos);
    		if(trim($extension)== "xls"){
    			@unlink($pathTtmp.$archivo);
    		}
    	}
    }
    function Genera_Archivo($bufferExcel,$path_sis) {
	$this->eliminaTemporales($path_sis);
        $num = rand(1, 100000);
        $archivo = "tmp/file" . $num . ".xls";
        $buffer_salida = '<br><a href="' . $archivo . '" target="_blank" class="btn btn-primary exportar"><span class="glyphicon glyphicon-book"></span>&nbsp;&nbsp;Exportar a Excel</a>';
        $f1 = fopen($archivo, "w+");
        fwrite($f1, $bufferExcel);
        fclose($f1);
        return $buffer_salida;
    }

    function regresaAdjuntosActividad($idProyecto, $idActividad) {
        $buf = "";
        if ($idProyecto > 0 && $idActividad > 0) {
            $sql = "SELECT id,archivo,proyecto_id,actividad_id FROM proyectos_actividades_adjuntos WHERE proyecto_id='" . $idProyecto . "' AND actividad_id = '" . $idActividad . "' ORDER BY id;";
            $res = $this->db->sql_query($sql) or die($this->cadena_error);
            if ($this->db->sql_numrows($res) > 0) {
                while (list($id, $file, $proyectoId, $actividadId) = $this->db->sql_fetchrow($res)) {
                    $div = "d-" . $id . "-" . $idProyecto . "-" . $idActividad;
                    $div2 = "e-" . $id . "-" . $idProyecto . "-" . $idActividad;
                    //$buf.='<a href="#" id="'.$div2.'" onclick="return eliminaAdjunto(".$div.");" ><img src="'.$this->path_img.'iconos/delete_16.png" id="'.$div2.'" class="btneliminaAdjunto"  border="0" alt="'.ELIMINAARCHIVO.'" title="'.ELIMINAARCHIVO.'"></a>'.utf8_encode($file).'<br>';
                    $buf.='<a href="#" id="' . $div . '" class="btneliminaAdjunto" ><img src="' . $this->path . 'imagenes/iconos/delete_16.png"  border="0" alt="' . ELIMINAARCHIVO . '" title="' . ELIMINAARCHIVO . '"></a>' . utf8_encode($file) . '<br>';
                }
            }
        }
        return $buf;
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

    function fechaLimiteCapturaAvances() {
        //luis
        $fecha = "";
        $noDias = 0;

        $sql = " SELECT fecha FROM cat_fecha_limite_avances LIMIT 1;";
        $sql = "SELECT distinct(trimestre_id), IF( NOW( )
		BETWEEN fecha_inicial AND fecha_final, '1', '0' ) AS respuesta
		FROM cat_fecha_limite_avances WHERE 1 " . $filtro . "
		ORDER BY ano_id,trimestre_id desc limit 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            list($fecha) = $this->db->sql_fetchrow($res);
            $sqlf = " SELECT DATEDIFF('" . date('Y-m-d H:i:s') . "','" . $fecha . "');";
            $resf = $this->db->sql_query($sqlf) or die($this->cadena_error);
            list($noDias) = $this->db->sql_fetchrow($resf);
        }
        return $noDias;
    }

    function altaMetaCompleto($data, $session) {
        $folio = 0;
        $arrayTrimestre = array();
        $tmp = explode('-', $data['proyectoId']);
        $tmp_datos = explode('|', $data['valor']);
        $tmp_val = array();
        $fecha = date("Y-m-d H:i:s");
        $con = 0;
        if (($tmp[0] + 0) > 0) {
            $del = "DELETE FROM proyectos_acciones_metas WHERE proyecto_id ='" . $tmp[0] . "';";
            $res = $this->db->sql_query($del) or die($this->cadena_error);
            if (count($tmp_datos) > 0) {
                foreach ($tmp_datos as $cadena) {
                    if (trim($cadena) != "") {
                        $tmp_val = array();
                        $tmp_val = explode('-', $cadena);
                        $proyecto_id = $tmp[0];
                        $indice_id = $tmp_val[1];
                        $actividad_id = $tmp_val[2];
                        $trimestre_id = $tmp_val[4];
                        $valor_meta = $tmp_val[5];
                        $arrayTrimestre[$indice_id][$trimestre_id] = $valor_meta;
                        $arrayActividad[$indice_id] = $actividad_id;
                    }
                }
                $valor_total = 0;
                if (count($arrayTrimestre) > 0) {
                    foreach ($arrayTrimestre as $ind => $arrayDatos) {
                        if ($ind >= 0) {
                            if ($tipo_actividad == 1 || $tipo_actividad == 5)
                                $total = $arrayDatos[1] + $arrayDatos[2] + $arrayDatos[3] + $arrayDatos[4] + 0;
                            if ($tipo_actividad == 3) {
                                if ($arrayDatos[1] < $arrayDatos[2])
                                    $total = $arrayDatos[1];
                                else
                                    $total = $arrayDatos[2];

                                if ($total > $arrayDatos[3])
                                    $total = $arrayDatos[3];
                                if ($total > $arrayDatos[4])
                                    $total = $arrayDatos[4];
                            }

                            if ($tipo_actividad == 4) {
                                $total = $arrayDatos[1];
                            }
                            if ($total > 0) {
                                $ins = "INSERT INTO proyectos_acciones_metas(user_id,rol_id,proyecto_id,actividad_id,trimestre1,trimestre2,trimestre3,trimestre4,total,fecha_alta,active)
						  		VALUES('" . $session['userId'] . "','" . $session['rol'] . "','" . $proyecto_id . "',
										 '" . $arrayActividad[$ind] . "','" . $arrayDatos[1] . "','" . $arrayDatos[2] . "',
									 	'" . $arrayDatos[3] . "','" . $arrayDatos[4] . "','" . $total . "',
								 		'" . $fecha . "','1');";
                                $res = $this->db->sql_query($ins) or die($this->cadena_error);
                                if ($res) {
                                    $folio = $tmp[0];
                                    $this->insertaBitacora($data, $session, $proyecto_id, $arrayActividad[$ind], $folio, 0, $this->arrayEstatus['AltaMeta']);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $folio;
    }

    function regresaMetasActividad($idActividad) {
        $array = array();
        $sql = "SELECT * FROM proyectos_acciones_metas WHERE actividad_id='" . $idActividad . "'LIMIT 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while ($arrayTmp = $this->db->sql_fetchrow($res)) {
                $actividad_id = $arrayTmp['actividad_id'];
                $trimestre1 = $arrayTmp['trimestre1'];
                $trimestre2 = $arrayTmp['trimestre2'];
                $trimestre3 = $arrayTmp['trimestre3'];
                $trimestre4 = $arrayTmp['trimestre4'];
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
                $trimestre1 = $arrayTmp['trimestre1'];
                $trimestre2 = $arrayTmp['trimestre2'];
                $trimestre3 = $arrayTmp['trimestre3'];
                $trimestre4 = $arrayTmp['trimestre4'];
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
                $trimestre1 = $arrayTmp['trimestre1'];
                $trimestre2 = $arrayTmp['trimestre2'];
                $trimestre3 = $arrayTmp['trimestre3'];
                $trimestre4 = $arrayTmp['trimestre4'];
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

    function consultaPermisosAvances() {
        $arrayRegreso = array();
        $filtro = "";
        if ($this->data['idano'] > 0)
            $filtro.= "AND ano_id = '" . $this->data['idano'] . "' ";
        if ($this->data['areaId'] > 0)
            $filtro.= "AND area_id = '" . $this->data['areaId'] . "' ";
        if ($this->data['idtrimestre'] > 0)
            $filtro.= "AND trimestre_id = '" . $this->data['idtrimestre'] . "' ";
        $sql = "SELECT ano_id,area_id,programa_id,trimestre_id,fecha_inicial,fecha_final,activo 
 			  FROM cat_fecha_limite_avances WHERE 1 " . $filtro . ";";

        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list($ano_id, $area_id, $programa_id, $trimestre_id, $fecha_ini, $fecha_fin, $activo) = $this->db->sql_fetchrow($res)) {
                $tmp = $area_id . "-" . $programa_id . "-" . $trimestre_id;
                $arrayRegreso[$tmp] = "";
                if ($activo == 1)
                    $arrayRegreso[$tmp] = substr($fecha_ini, 0, 10) . "<br>" . substr($fecha_fin, 0, 10);
            }
        }
        return $arrayRegreso;
    }

    function eliminaPermisosAvance() {
        $filtro = "";
        if ($this->data['idano'] > 0)
            $filtro.= "AND ano_id = '" . $this->data['idano'] . "' ";
        if ($this->data['areaId'] > 0)
            $filtro.= "AND area_id = '" . $this->data['areaId'] . "' ";
        if ($this->data['idtrimestre'] > 0)
            $filtro.= "AND trimestre_id = '" . $this->data['idtrimestre'] . "' ";

        $sql = "SELECT id FROM cat_fecha_limite_avances WHERE area_id > 0 " . $filtro . ";";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) == 0) {
            $this->insertaPermisosAvance();
        } else {
            $this->actualizaPermisosPAvance();
        }
    }

    function eliminaPermisosProgramaAvance() {
        $filtro = "";
        if ($this->data['idano'] > 0)
            $filtro.= "AND ano_id = '" . $this->data['idano'] . "' ";
        if ($this->data['areaId'] > 0)
            $filtro.= "AND area_id = '" . $this->data['areaId'] . "' ";
        if ($this->data['programaId'] > 0)
            $filtro.= "AND programa_id = '" . $this->data['programaId'] . "' ";
        if ($this->data['idtrimestre'] > 0)
            $filtro.= "AND trimestre_id = '" . $this->data['idtrimestre'] . "' ";

        $sql = "SELECT id FROM cat_fecha_limite_avances WHERE area_id > 0 " . $filtro . ";";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) == 0) {
            $this->insertaPermisosAreaProgramaAvance();
        } else {
            $this->actualizaPermisosAreaProgramaAvance();
        }
    }

    function insertaPermisosAreaProgramaAvance() {
        $fechaIni = substr($this->data['fechaLimiteIni'], 6, 4) . "-" . substr($this->data['fechaLimiteIni'], 3, 2) . "-" . substr($this->data['fechaLimiteIni'], 0, 2) . " 00:00:01";
        $fechaFin = substr($this->data['fechaLimiteFin'], 6, 4) . "-" . substr($this->data['fechaLimiteFin'], 3, 2) . "-" . substr($this->data['fechaLimiteFin'], 0, 2) . " 23:59:59";
        if ($this->data['programaId'] > 0) {
            $ins = "INSERT INTO cat_fecha_limite_avances (ano_id,area_id,programa_id,fecha_inicial,fecha_final,trimestre_id,activo)
 				  VALUES ('" . $this->data['idano'] . "','" . $this->data['areaId'] . "','" . $this->data['programaId'] . "','" . $fechaIni . "','" . $fechaFin . "','" . $this->data['idtrimestre'] . "','" . $this->data['status'] . "');";
            $rins = $this->db->sql_query($ins) or die($this->cadena_error);
        }
    }

    function actualizaPermisosAreaProgramaAvance() {
        $fechaIni = substr($this->data['fechaLimiteIni'], 6, 4) . "-" . substr($this->data['fechaLimiteIni'], 3, 2) . "-" . substr($this->data['fechaLimiteIni'], 0, 2) . " 00:00:01";
        $fechaFin = substr($this->data['fechaLimiteFin'], 6, 4) . "-" . substr($this->data['fechaLimiteFin'], 3, 2) . "-" . substr($this->data['fechaLimiteFin'], 0, 2) . " 23:59:59";
        if ($this->data['programaId'] > 0) {
            $upd = "UPDATE cat_fecha_limite_avances SET fecha_inicial='" . $fechaIni . "',fecha_final='" . $fechaFin . "',trimestre_id='" . $this->data['idtrimestre'] . "',activo = '" . $this->data['status'] . "'
 				  WHERE ano_id = '" . $this->data['idano'] . "' AND area_id='" . $this->data['areaId'] . "' AND programa_id ='" . $this->data['programaId'] . "';";
            $rupd = $this->db->sql_query($upd) or die($this->cadena_error);
        }
    }

    function insertaPermisosAvance() {
        $fechaIni = substr($this->data['fechaLimiteIni'], 6, 4) . "-" . substr($this->data['fechaLimiteIni'], 3, 2) . "-" . substr($this->data['fechaLimiteIni'], 0, 2) . " 00:00:01";
        $fechaFin = substr($this->data['fechaLimiteFin'], 6, 4) . "-" . substr($this->data['fechaLimiteFin'], 3, 2) . "-" . substr($this->data['fechaLimiteFin'], 0, 2) . " 23:59:59";
        $arrayProgramas = $this->catalogoProgramasP($this->data['areaId']);
        if (count($arrayProgramas) > 0) {
            foreach ($arrayProgramas as $id => $value) {
                $ins = "INSERT INTO cat_fecha_limite_avances (ano_id,area_id,programa_id,fecha_inicial,fecha_final,trimestre_id,activo)
 					  VALUES ('" . $this->data['idano'] . "','" . $this->data['areaId'] . "','" . $id . "','" . $fechaIni . "','" . $fechaFin . "','" . $this->data['idtrimestre'] . "','" . $this->data['status'] . "');";
                $rins = $this->db->sql_query($ins) or die($this->cadena_error);
            }
        }
    }

    function actualizaPermisosPAvance() {
        $fechaIni = substr($this->data['fechaLimiteIni'], 6, 4) . "-" . substr($this->data['fechaLimiteIni'], 3, 2) . "-" . substr($this->data['fechaLimiteIni'], 0, 2) . " 00:00:01";
        $fechaFin = substr($this->data['fechaLimiteFin'], 6, 4) . "-" . substr($this->data['fechaLimiteFin'], 3, 2) . "-" . substr($this->data['fechaLimiteFin'], 0, 2) . " 23:59:59";
        $arrayProgramas = $this->catalogoProgramasP($this->data['areaId']);
        if (count($arrayProgramas) > 0) {
            foreach ($arrayProgramas as $id => $value) {
                $upd = "UPDATE cat_fecha_limite_avances SET fecha_inicial='" . $fechaIni . "',fecha_final='" . $fechaFin . "',trimestre_id='" . $this->data['idtrimestre'] . "',activo = '" . $this->data['status'] . "'
 					  WHERE ano_id = '" . $this->data['idano'] . "' AND area_id='" . $this->data['areaId'] . "' AND programa_id ='" . $id . "';";
                $rupd = $this->db->sql_query($upd) or die($this->cadena_error);
            }
        }
    }

    function eliminaPermisos() {
        $filtro = "";
        if ($this->data['idano'] > 0)
            $filtro.= "AND ano_id = '" . $this->data['idano'] . "' ";
        if ($this->data['areaId'] > 0)
            $filtro.= "AND area_id = '" . $this->data['areaId'] . "' ";

        $sql = "SELECT id FROM cat_fecha_limite_metas WHERE area_id > 0 " . $filtro . ";";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) == 0) {
            $this->insertaPermisos();
        } else {
            $this->actualizaPermisosP();
        }
    }

    function eliminaPermisosPrograma() {
        $filtro = "";
        if ($this->data['idano'] > 0)
            $filtro.= "AND ano_id = '" . $this->data['idano'] . "' ";
        if ($this->data['areaId'] > 0)
            $filtro.= "AND area_id = '" . $this->data['areaId'] . "' ";
        if ($this->data['programaId'] > 0)
            $filtro.= "AND programa_id = '" . $this->data['programaId'] . "' ";

        $sql = "SELECT id FROM cat_fecha_limite_metas WHERE area_id > 0 " . $filtro . ";";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) == 0) {
            $this->insertaPermisosAreaPrograma();
        } else {
            $this->actualizaPermisosAreaPrograma();
        }
    }

    function insertaPermisosAreaPrograma() {
        $fecha = substr($this->data['fechaLimite'], 6, 4) . "-" . substr($this->data['fechaLimite'], 3, 2) . "-" . substr($this->data['fechaLimite'], 0, 2) . " 23:59:59";
        if ($this->data['programaId'] > 0) {
            $ins = "INSERT INTO cat_fecha_limite_metas (ano_id,area_id,programa_id,fecha,activo)
 				  VALUES ('" . $this->data['idano'] . "','" . $this->data['areaId'] . "','" . $this->data['programaId'] . "','" . $fecha . "','" . $this->data['status'] . "');";
            $rins = $this->db->sql_query($ins) or die($this->cadena_error);
            $this->insertaBitacoraBloqueos($this->data['idano'], $this->data['areaId'], $this->data['programaId'], $this->data['status']);
        }
    }

    function actualizaPermisosAreaPrograma() {
        $fecha = substr($this->data['fechaLimite'], 6, 4) . "-" . substr($this->data['fechaLimite'], 3, 2) . "-" . substr($this->data['fechaLimite'], 0, 2) . " 23:59:59";
        if ($this->data['programaId'] > 0) {
            $upd = "UPDATE cat_fecha_limite_metas SET fecha='" . $fecha . "',activo = '" . $this->data['status'] . "'
 				  WHERE ano_id = '" . $this->data['idano'] . "' AND area_id='" . $this->data['areaId'] . "' AND programa_id ='" . $this->data['programaId'] . "';";
            $rupd = $this->db->sql_query($upd) or die($this->cadena_error);
            $this->insertaBitacoraBloqueos($this->data['idano'], $this->data['areaId'], $this->data['programaId'], $this->data['status']);
        }
    }

    function insertaPermisos() {
        $fecha = substr($this->data['fechaLimite'], 6, 4) . "-" . substr($this->data['fechaLimite'], 3, 2) . "-" . substr($this->data['fechaLimite'], 0, 2) . " 23:59:59";
        $arrayProgramas = $this->catalogoProgramasP($this->data['areaId']);
        if (count($arrayProgramas) > 0) {
            foreach ($arrayProgramas as $id => $value) {
                $ins = "INSERT INTO cat_fecha_limite_metas (ano_id,area_id,programa_id,fecha,activo)
 					  VALUES ('" . $this->data['idano'] . "','" . $this->data['areaId'] . "','" . $id . "','" . $fecha . "','" . $this->data['status'] . "');";
                $rins = $this->db->sql_query($ins) or die($this->cadena_error);
                $this->insertaBitacoraBloqueos($this->data['idano'], $this->data['areaId'], $id, $this->data['status']);
            }
        }
    }

    function actualizaPermisosP() {
        $fecha = substr($this->data['fechaLimite'], 6, 4) . "-" . substr($this->data['fechaLimite'], 3, 2) . "-" . substr($this->data['fechaLimite'], 0, 2) . " 23:59:59";
        $arrayProgramas = $this->catalogoProgramasP($this->data['areaId']);
        if (count($arrayProgramas) > 0) {
            foreach ($arrayProgramas as $id => $value) {
                $upd = "UPDATE cat_fecha_limite_metas SET fecha='" . $fecha . "',activo = '" . $this->data['status'] . "' 
 					  WHERE ano_id = '" . $this->data['idano'] . "' AND area_id='" . $this->data['areaId'] . "' AND programa_id ='" . $id . "';";
                $rupd = $this->db->sql_query($upd) or die($this->cadena_error);
                $this->insertaBitacoraBloqueos($this->data['idano'], $this->data['areaId'], $id, $this->data['status']);
            }
        }
    }

    function regresaDescripcionTipoActividad() {
        $regreso = "";
        $sql = " SELECT descripcion FROM cat_tipo_actividad WHERE actividad_id='" . $this->data['idTipoActividad'] . "' limit 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            list($regreso) = $this->db->sql_fetchrow($res);
        }
        return utf8_encode($regreso);
    }

    function regresaDescripcionPonderacion() {
        $regreso = "";
        $sql = " SELECT descripcion FROM cat_ponderacion WHERE id='" . $this->data['idPonderacion'] . "' limit 1;";
        $res = $this->db->sql_query($sql) or die($sql);
        if ($this->db->sql_numrows($res) > 0) {
            list($regreso) = $this->db->sql_fetchrow($res);
        }
        return utf8_encode($regreso);
    }

    function catalogoEstatus() {
        $array = array();
        $sql = "SELECT estatus_id,estatus,color FROM cat_estatus WHERE active='1' " . $filtro . " ORDER BY estatus_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm, $_col ) = $this->db->sql_fetchrow($res)) {
                $array [$_id]['nombre'] = $_nm;
                $array [$_id]['color'] = $_col;
            }
        }
        return $array;
    }

    function altaAvanceCompleto($data, $session) {
        $folio = 0;
        $arrayTrimestre = array();
        $tmp = explode('-', $data['proyectoId']);
        $trimestreId = $data['idtrimestre'];
        $campoAvance = "estatus_avance_entrega";
        if ($trimestreId > 1)
            $campoAvance = "estatus_avance_entrega" . $trimestreId;
        $tmp_datos = explode('|', $data['valor']);
        $tmp_val = array();
        $fecha = date("Y-m-d H:i:s");
        $con = 0;
        if (($tmp[0] + 0) > 0) {
            $del = "DELETE FROM proyectos_acciones_avances WHERE proyecto_id ='" . $tmp[0] . "';";
            $res = $this->db->sql_query($del) or die($this->cadena_error);
            if (count($tmp_datos) > 0) {

                foreach ($tmp_datos as $cadena) {
                    if (trim($cadena) != "") {
                        $tmp_val = array();
                        $tmp_val = explode('-', $cadena);
                        $proyecto_id = $tmp[0];
                        $indice_id = $tmp_val[1];
                        $actividad_id = $tmp_val[2];
                        $trimestre_id = $tmp_val[4];
                        $tipo_actividad = $tmp_val[5];
                        $valor_meta = $tmp_val[6];
                        $arrayTrimestre[$indice_id][$trimestre_id] = $valor_meta;
                        $arrayActividad[$indice_id] = $actividad_id;
                        $arrayTipoActiv[$indice_id] = $tipo_actividad;
                    }
                }
                $valor_total = 0;
                $minimo = 0;
                if (count($arrayTrimestre) > 0) {
                    foreach ($arrayTrimestre as $ind => $arrayDatos) {
                        $total = 0;
                        if ($ind >= 0) {
                            if ($tipo_actividad == 1 || $tipo_actividad == 5)
                                $total = $arrayDatos[1] + $arrayDatos[2] + $arrayDatos[3] + $arrayDatos[4] + 0;
                            if ($tipo_actividad == 3) {
                                if ($arrayDatos[1] < $arrayDatos[2])
                                    $total = $arrayDatos[1];
                                else
                                    $total = $arrayDatos[2];

                                if ($total > $arrayDatos[3])
                                    $total = $arrayDatos[3];
                                if ($total > $arrayDatos[4])
                                    $total = $arrayDatos[4];
                            }

                            if ($tipo_actividad == 4) {
                                $total = $arrayDatos[1];
                            }
                            if ($total >= 0) {
                                $ins = "INSERT INTO proyectos_acciones_avances(user_id,rol_id,proyecto_id,actividad_id,trimestre1,trimestre2,trimestre3,trimestre4,total,fecha_alta,active,trimestre_id)
						  		VALUES('" . $session['userId'] . "','" . $session['rol'] . "','" . $proyecto_id . "',
										 '" . $arrayActividad[$ind] . "','" . $arrayDatos[1] . "','" . $arrayDatos[2] . "',
									 	'" . $arrayDatos[3] . "','" . $arrayDatos[4] . "','" . $total . "',
								 		'" . $fecha . "','1','" . $trimestreId . "');";
                                $res = $this->db->sql_query($ins) or die($this->cadena_error);
                                if ($res) {
                                    $folio = $tmp[0];
                                    $sqlc = "SELECT " . $campoAvance . " FROM proyectos_acciones WHERE id ='" . $tmp[0] . "' AND " . $campoAvance . " = '0' limit 1;";
                                    $resc = $this->db->sql_query($sqlc) or die($this->cadena_error);
                                    if ($this->db->sql_numrows($resc) == 1) {
                                        $upd = "UPDATE proyectos_acciones SET " . $campoAvance . " = '1' WHERE id ='" . $tmp[0] . "';";
                                        $resu = $this->db->sql_query($upd) or die($this->cadena_error);
                                    }
                                    $this->insertaBitacora($data, $session, $proyecto_id, $arrayActividad[$ind], 0, $folio, $this->arrayEstatus['AltaAvance']);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $folio;
    }

    function insertaBitacora($data, $session, $idProyecto, $idActividad, $idMeta, $idAvance, $estatus) {
        $ins = "INSERT INTO log_proyectos (user_id,proyecto_id,actividad_id,meta_id,avance_id,estatus,ip)
 			  VALUES ('" . $session['userId'] . "','" . $idProyecto . "','" . $idActividad . "','" . $idMeta . "','" . $idAvance . "','" . $estatus . "','" . $session['ip'] . "');";
        $res = $this->db->sql_query($ins) or die($this->cadena_error);
    }

    function insertaBitacoraComentarios($data, $session, $idProyecto, $idActividad, $id, $estatus) {
        $ins = "INSERT INTO log_proyectos_comentarios (user_id,proyecto_id,actividad_id,comentario_id,estatus,ip)
 			  VALUES ('" . $session['userId'] . "','" . $idProyecto . "','" . $idActividad . "','" . $id . "','" . $estatus . "','" . $session['ip'] . "');";
        $res = $this->db->sql_query($ins) or die($this->cadena_error);
    }

    function insertaBitacoraComentariosAvances($data, $session, $idProyecto, $idActividad, $idTrimestre, $id, $estatus) {
        $ins = "INSERT INTO log_proyectos_avances_comentarios (user_id,proyecto_id,actividad_id,trimestre_id,comentario_id,estatus,ip)
 			  VALUES ('" . $session['userId'] . "','" . $idProyecto . "','" . $idActividad . "','" . $idTrimestre . "','" . $id . "','" . $estatus . "','" . $session['ip'] . "');";
        $res = $this->db->sql_query($ins) or die($this->cadena_error);
    }

    function insertaBitacoraBloqueos($idano, $areaId, $programaId, $estatus) {
        $ins = "INSERT INTO log_catalogo_areas_bloqueos (user_id,area_id,programa_id,ano_id,estatus,ip)
 			  VALUES ('" . $this->session['userId'] . "','" . $areaId . "','" . $programaId . "','" . $idano . "','" . $estatus . "','" . $this->session['ip'] . "');";
        $res = $this->db->sql_query($ins) or die($this->cadena_error);
    }

    function recuperaPermisos($area_id, $programa_id) {
        $array = array();
        $filtro = "";
        if ($area_id > 0)
            $filtro.= "AND area_id = '" . $area_id . "' ";
        if ($programa_id > 0)
            $filtro.= "AND programa_id IN ('" . $programa_id . "') ";

        $sql = "SELECT distinct(trimestre_id), IF( NOW( )
			  BETWEEN fecha_inicial AND fecha_final, '1', '0' ) AS respuesta
			  FROM cat_fecha_limite_avances WHERE 1 " . $filtro . " 
			  ORDER BY respuesta DESC,ano_id,trimestre_id,area_id, programa_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $trimestre, $respuesta) = $this->db->sql_fetchrow($res)) {
                $array[$trimestre]['dis'] = $respuesta;
                $array[$trimestre]['tri'] = $trimestre;
            }
        }
        return $array;
    }

    function notificaciones() {
        $array = array();
        $sql = "SELECT notificacion_id,notificacion,activo,color,editable FROM cat_estatus_notificaciones WHERE 1 ORDER BY notificacion_id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm, $_act, $_color, $_edi) = $this->db->sql_fetchrow($res)) {
                $array [$_id]['nom'] = $_nm;
                $array [$_id]['act'] = $_act;
                $array [$_id]['color'] = $_color;
                $array [$_id]['edita'] = $_edi;
            }
        }
        return $array;
    }

    function obtenTrimestreBase(){
    	$array = array();
    	$sql = "SELECT ano_id,trimestre_id,fecha_inicio,fecha_final FROM cat_trimestre_validar WHERE activo='1' LIMIT 1;";
    	$res = $this->db->sql_query($sql) or die($this->cadena_error);
    	if ($this->db->sql_numrows($res) > 0) {
    		while (list ( $ano, $tri, $fechaI, $fechaF) = $this->db->sql_fetchrow($res)) {
    			$array ['ano']       = $ano;
    			$array ['trimestre'] = $tri;
    			$array ['fechaI'] 	 = $fechaI;
    			$array ['fechaF'] 	 = $fechaF;
    		}
    	}
    	return $array;
    
    }
    
    function obtenTrimestre($arrayDisabled) {        
        $idTrimestre = 0;
        if (count($arrayDisabled) > 0) {
            foreach ($arrayDisabled as $ind => $tmp) {
                if($tmp['dis'] == 1)
                 $idTrimestre = $ind;
            }
        }
        return $idTrimestre;
    }

    function pintaComentarioAvance($idEstatus, $id, $trimestreId) {
        $titulo = $regreso = "";
        switch ($idEstatus) {
            case 2:
                $titulo = VALIDAA1;
                break;
            case 4:
                $titulo = VALIDAA2;
                break;
            case 8:
                $titulo = VALIDAA3;
                break;
        }
        if ($idEstatus > 0 && $idEstatus < 10) {
            $regreso = "<button id='va-" . $id . "-" . $idEstatus . "-" . $trimestreId . "' class='btn btn-default btn-xs aprobadosProyAvan' data-toggle='tooltip' data-placement='bottom'   title='" . $titulo . "'  ><span class='glyphicon glyphicon-ok'></span></button>
 						&nbsp;&nbsp;
 					  <button id='vn-" . $id . "-" . $idEstatus . "-" . $trimestreId . "' class='btn btn-default btn-xs noaprobadosProyAvan' data-toggle='tooltip' data-placement='bottom' title='" . $titulo . "'  ><span class='glyphicon glyphicon-remove'></span></button>";
        }
//  			$regreso="<button id='".$id."-".$idEstatus."-".$trimestreId."' class='btn btn-default btn-xs validarAvance' data-toggle='tooltip' data-placement='bottom' title='".$titulo."'  ><span class='glyphicon glyphicon-ok'></span></button>";
        return $regreso;
    }

    function pintaComentario($idEstatus, $id) {
        $titulo = $regreso = "";
        switch ($idEstatus) {
            case 2:
                $titulo = VALIDA1;
                break;
            case 4:
                $titulo = VALIDA2;
                break;
            case 8:
                $titulo = VALIDA3;
                break;
        }
        if ($idEstatus > 0 && $idEstatus < 10)
            $regreso = "<button id='a-" . $id . "-" . $idEstatus . "' class='btn btn-default btn-xs aprobadosProy' data-toggle='tooltip' data-placement='bottom' title='" . $titulo . "' style='width:30px;' ><span class='glyphicon glyphicon-ok'></span></button>
 					  <button id='n-" . $id . "-" . $idEstatus . "' class='btn btn-default btn-xs noaprobadosProy' data-toggle='tooltip' data-placement='bottom' title='" . $titulo . "' style='width:30px;' ><span class='glyphicon glyphicon-remove'></span></button>
 					";
        return $regreso;
    }

    function revisaProyectosSinActividades() {
        $bufferProyectos = "";
        if ($this->session['rol'] <= 2) {
            if ($this->session['userArea'] != "") {
                $filtro.= " AND a.unidadResponsable_id in ('" . $this->session['userArea'] . "') ";
            }
            if ($this->session['programas'] != "") {
                $filtro.= " AND a.programa_id in (" . $this->session['programas'] . ") ";
            }
            if ($this->session['rol'] == 1) {
                $filtro.= " AND a.userId = " . $this->session['userId'];
            }
            $sql = "SELECT a.id, COUNT( b.proyecto_id ) AS total
			 	  FROM proyectos_acciones AS a
			  	  LEFT JOIN proyectos_actividades AS b ON a.id = b.proyecto_id
			  	  WHERE a.active='1' AND a.estatus_entrega = 1 " . $filtro . " GROUP BY b.proyecto_id
			  	  HAVING (COUNT( b.proyecto_id ) = 0) ORDER BY a.id;";
            $res = $this->db->sql_query($sql, $this->db) or die($this->script);
            if ($this->db->sql_numrows($res) > 0) {
                $bufferProyectos.='<span class="tituloactvidades">' . NOOLVIDES . '</span>';
            }
        }
        return $bufferProyectos;
    }

    function regresaAdmins() {
        $arrayAdmins = array();
        $sql = "SELECT user_id FROM cat_usuarios WHERE user_rol>='4' ORDER BY user_id;";
        $res = $this->db->sql_query($sql, $this->db) or die($this->script);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ($id) = $this->db->sql_fetchrow($res)) {
                $arrayAdmins[] = $id;
            }
        }
        return $arrayAdmins;
    }

    function regresaLetras() {
        $array = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "TODOS");
        $buffer = "<table width='100%' border='0' align='center'><tr>";
        foreach ($array as $ind => $letra) {
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

    function generaTiposLogs($idTipoLog) {
        $combo = "<select name='tipo_log' id='tipo_log' class='bootstrap-select'  style='width:150px;'><option value='0' class='franjaSeleccione'>" . SELECCIONE . "</option>";
        $tmp = "";
        $sql = "SELECT id,nombre FROM cat_tipo_logs where activo='1' ORDER BY id;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $idTipoLog)
                    $tmp = " SELECTED ";
                if ($_id > 0)
                    $combo.= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        $combo.= "</select>";
        return $combo;
    }

    function generaUsuarios($idUser) {
        $combo = "<select name='user_id' id='user_id' class='bootstrap-select'  style='width:550px;'><option value='0' class='franjaSeleccione'>" . SELECCIONE . "</option>";
        $tmp = "";
        $sql = "SELECT user_id,user_nombre FROM cat_usuarios where 1 ORDER BY user_rol desc,user_nombre;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            while (list ( $_id, $_nm ) = $this->db->sql_fetchrow($res)) {
                $tmp = "";
                if ($_id == $idUser)
                    $tmp = " SELECTED ";
                if ($_id > 0)
                    $combo.= "<option value='" . $_id . "' " . $tmp . ">" . $_nm . "</option>";
            }
        }
        $combo.= "</select>";
        return $combo;
    }

    function generaTiposEstatus($tipo_status) {
        $combo = "<select name='tipo_status' id='tipo_status' class='bootstrap-select'  style='width:150px;'><option value='0' class='franjaSeleccione'>" . SELECCIONE . "</option></select>";
        return $combo;
    }

    function regresaUltimoComentario($id, $idActividad) {
        $comentario = "";
        $sql = "SELECT comentarios FROM proyectos_avances_comentarios 
 				WHERE proyecto_id='" . $id . "' and actividad_id ='" . $idActividad . "' ORDER BY id DESC LIMIT 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            list ( $comentario) = $this->db->sql_fetchrow($res);
            $comentario = "<b>&Uacute;ltimo comentario:</b>  " . substr($comentario, 0, 35);
        }
        return $comentario;
    }

    function regresaNoAdjuntos($id, $idActividad) {
        $cadena = "";
        $sql = "SELECT count(id) as total FROM proyectos_avances_adjuntos 
 				WHERE proyecto_id='" . $id . "' and actividad_id ='" . $idActividad . "';";
        $res = $this->db->sql_query($sql) or die($this->cadena_error);
        if ($this->db->sql_numrows($res) > 0) {
            list ( $total) = $this->db->sql_fetchrow($res);
            if ($total > 0)
                $cadena = "<b>No. de adjuntos:</b>	" . $total;
        }
        return $cadena;
    }

    function actualizaAyudaProyecto() {
        if ($this->data['id'] > 0) {
            $this->data['titulo'] = $this->limpiaCadenas($this->data['titulo']);
            $this->data['content'] = $this->limpiaCadenas($this->data['content']);
            $upd = "UPDATE cat_ayuda_proyectos SET tit_ayuda='" . $this->data['titulo'] . "',
 					msg_ayuda='" . $this->data['content'] . "' 
 					WHERE id='" . $this->data['id'] . "' LIMIT 1;";
            $this->db->sql_query($upd) or die($this->cadena_error);
        }
    }

    function actualizaAyudaActividad() {
        if ($this->data['id'] > 0) {
            $this->data['titulo'] = $this->limpiaCadenas($this->data['titulo']);
            $this->data['content'] = $this->limpiaCadenas($this->data['content']);
            $upd = "UPDATE cat_ayuda_proyectos SET tit_ayuda='" . $this->data['titulo'] . "',
 					msg_ayuda='" . $this->data['content'] . "' 
 					WHERE id='" . $this->data['id'] . "' LIMIT 1;";
            $this->db->sql_query($upd) or die($this->cadena_error);
        }
    }

    function actualizaAyudaPonderacion() {
        if ($this->data['id'] > 0) {
            $this->data['titulo'] = $this->limpiaCadenas($this->data['titulo']);
            $this->data['content'] = $this->limpiaCadenas($this->data['content']);
            $upd = "UPDATE cat_ponderacion SET descripcion='" . $this->data['content'] . "' 
 				   WHERE id='" . $this->data['id'] . "' LIMIT 1;";
            $this->db->sql_query($upd) or die($this->cadena_error);
        }
    }

    function actualizaAyudaTipoActividad() {
        if ($this->data['id'] > 0) {
            $this->data['titulo'] = $this->limpiaCadenas($this->data['titulo']);
            $this->data['content'] = $this->limpiaCadenas($this->data['content']);
            $upd = "UPDATE cat_tipo_actividad SET nombre='" . $this->data['titulo'] . "',
 					descripcion='" . $this->data['content'] . "' 
 					WHERE actividad_id='" . $this->data['id'] . "' LIMIT 1;";
            $this->db->sql_query($upd) or die($this->cadena_error);
        }
    }
    
    function regresaTrimestreDefault(){
    	$mes = date('m');
    	$array = array('01' => 1,'02' => 1,'03' => 1,'04' => 2,'05' => 2,'06' => 2,'07' => 3,'08' => 3,'09' => 3,'10' => 4,'11' => 4,'12' => 4);
    	return $array[$mes];
    }    
}
?>