<?php

class RevisaParametros {

    var $nombre;
    var $titulo;
    var $url;
    var $db;
    var $session;
    var $bufferProyectos;
    var $path_web;
    var $buffer_ano;

    function __construct($db, $session, $path_web) {
        $this->db = $db;
        $this->session = $session;
        $this->path_web = $path_web;
        $this->bufferProyectos = "";
        $this->nombre = "";
        $this->titulo = $this->buffer_ano = "";
        $this->url = $this->nombre = $this->titulo = "";
        $this->url = "aplicacion.php";
        $this->script = "<script languaje='JavaScript'>  location.href='" . $this->path_web . "aplicacion.php'";
        if (($this->session ["userId"] > 0) && (($this->session ["aplicacion"] + 0) >= 0) && (($this->session ["apli_com"] + 0) >= 0)) {
            $this->regresaUrl();
            $this->insertaBitacoraModulos();
            $this->bufferProyectos = "<br>";
            if ($this->session['aplicacion'] == 2) {
                $this->proyectosNoValidados();                
                $this->proyectosRechazados();
                $this->proyectosValidados();
            }
            if ($this->session['aplicacion'] == 4) {                
                $this->avancesNoValidados();
                $this->avancesRechazados();
                $this->avancesValidados();
            }
        }
        $this->selectAno();
    }

    function selectAno(){
        $this->buffer_ano="<select name='ano_id' id='ano_id' style='width:130px;border:1px solid #e5e5e5;'>";
        $sql="SELECT ano FROM cat_anos WHERE active = 1  ORDER BY ano asc;";
        $res = $this->db->sql_query($sql, $this->db) or die($this->script);
        if ($this->db->sql_numrows($res) > 0) {
            while(list ( $ano ) = $this->db->sql_fetchrow($res)){
                $tmp = "";
                if($ano == $this->session['anocaptura'])
                    $tmp = " selected ";
                $this->buffer_ano.="<option value='".$ano."'".$tmp.">".$ano."</option>";
            }
        }
        $this->buffer_ano.="</select>";
    }
    
    function regresaUrl() {
        $sql = "SELECT nombre,titulo,url FROM cat_submenu WHERE menu_id='" . $this->session ["aplicacion"] . "' AND submenu_id='" . $this->session ["apli_com"] . "' LIMIT 1;";
        $res = $this->db->sql_query($sql, $this->db) or die($this->script);
        if ($this->db->sql_numrows($res) > 0) {
            list ( $this->nombre, $this->titulo, $this->url ) = $this->db->sql_fetchrow($res);
        }
    }

    function proyectosValidados() {
        switch ($this->session['rol']) {
            case 1:
                $filtro = "AND active='1'  AND estatus_entrega='4' ";
                break;
            case 2:
                $filtro = "AND active='1'  AND estatus_entrega='7' ";
                break;
            case 3:
                $filtro = "AND active='1'  AND estatus_entrega='10' ";
                break;
            case 4:
            case 5:
                $filtro = "AND active='1'  AND estatus_entrega IN (4,7,10) ";
                break;
        }
        if($this->session['anocaptura'] >= 2015){
            $filtro.= " AND YEAR(fecha_alta) ='".$this->session['anocaptura']."' ";
        }

        if ($this->session['rol'] < 4) {
            if ($this->session['userArea'] != "") {
                $filtro.= " AND unidadResponsable_id in ('" . $this->session['userArea'] . "') ";
            }
            if ($this->session['programas'] != "") {
                $filtro.= " AND programa_id in (" . $this->session['programas'] . ") ";
            }
        }
        if ($this->session['rol'] == 1) {
            $filtro.= " AND userId ='" . $this->session['userId'] . "' ";
        }

        $sql = "SELECT count(*) FROM proyectos_acciones WHERE active=1 " . $filtro . ";";
        $res = $this->db->sql_query($sql, $this->db) or die($this->script);
        list($total) = $this->db->sql_fetchrow($res);
        if ($total > 0) {
            $this->bufferProyectos.='<button class="btn btn-default" type="button" id="pvalidados">Proyectos validados&nbsp;&nbsp;<span class="badge">' . $total . '</span></button>&nbsp;&nbsp;';
        }
    }

    function proyectosRechazados() {
        switch ($this->session['rol']) {
            case 1:
                $filtro = "AND active='1'  AND estatus_entrega='3' ";
                break;
            case 2:
                $filtro = "AND active='1'  AND estatus_entrega='6' ";
                break;
            case 3:
                $filtro = "AND active='1'  AND estatus_entrega='9' ";
                break;
            case 4:
            case 5:
                $filtro = "AND active='1'  AND estatus_entrega IN (3,6,9) ";
                break;
        }
        if ($this->session['rol'] < 4) {
            if ($this->session['userArea'] != "") {
                $filtro.= " AND unidadResponsable_id in ('" . $this->session['userArea'] . "') ";
            }
            if ($this->session['programas'] != "") {
                $filtro.= " AND programa_id in (" . $this->session['programas'] . ") ";
            }
        }
        if($this->session['anocaptura'] >= 2015){
            $filtro.= "AND YEAR(fecha_alta) ='".$this->session['anocaptura']."' ";
        }
        
        if ($this->session['rol'] == 1) {
            $filtro.= " AND userId =" . $this->session['userId'] . " ";
        }
        $sql = "SELECT count(*) FROM proyectos_acciones WHERE active=1 " . $filtro . ";";
        $res = $this->db->sql_query($sql, $this->db) or die($this->script);
        list($total) = $this->db->sql_fetchrow($res);
        if ($total > 0) {
            $this->bufferProyectos.='<button class="btn btn-default" type="button" id="prechazados">Proyectos rechazados&nbsp;&nbsp;<span class="badge">' . $total . '</span></button>&nbsp;&nbsp;';
        }
    }

    function proyectosNoValidados() {
        $this->bufferProyectos.="";
        $total = 0;
        switch ($this->session['rol']) {
            case 1:
                $filtro = "AND active='1'  AND estatus_entrega='2' ";
                break;
            case 2:
                $filtro = "AND active='1'  AND estatus_entrega IN (2,4) ";
                break;
            case 3:
                $filtro = "AND active='1'  AND estatus_entrega='8' ";
                break;
            case 4:
            case 5:
                $filtro = "AND active='1'  AND estatus_entrega IN (2,5,8) ";
                break;
        }
        if($this->session['anocaptura'] >= 2015){
            $filtro.= " AND YEAR(fecha_alta) ='".$this->session['anocaptura']."' ";
        }
        
        if ($this->session['rol'] < 4) {
            if ($this->session['userArea'] != "") {
                $filtro.= " AND unidadResponsable_id in ('" . $this->session['userArea'] . "') ";
            }
            if ($this->session['programas'] != "") {
                $filtro.= " AND programa_id in (" . $this->session['programas'] . ") ";
            }
        }
        if ($this->session['rol'] == 1) {
            $filtro.= " AND userId ='" . $this->session['userId'] . "' ";
        }
        $sql = "SELECT count(*) FROM proyectos_acciones WHERE active=1 " . $filtro . ";";
        $res = $this->db->sql_query($sql, $this->db) or die($this->script);
        list($total) = $this->db->sql_fetchrow($res);
        if ($total > 0) {
            $this->bufferProyectos.='<button class="btn btn-default" type="button" id="pnovalidados">Proyectos sin validar&nbsp;&nbsp;<span class="badge">' . $total . '</span></button>&nbsp;&nbsp;';
        }
    }

    /*     * ******************* avances ************************ */

    function avancesValidados() {
        switch ($this->session['rol']) {
            case 1:
                $filtro = "AND active='1'  AND estatus_avance_entrega='4' ";
                break;
            case 2:
                $filtro = "AND active='1'  AND estatus_avance_entrega IN (4,7) ";
                break;
            case 3:
                $filtro = "AND active='1'  AND estatus_avance_entrega IN (4,7,10) ";
                break;
            case 4:
            case 5:
                $filtro = "AND active='1'  AND estatus_avance_entrega IN (4,7,10) ";
                break;
        }
        if($this->session['anocaptura'] >= 2015){
            $filtro.= " AND YEAR(fecha_alta) ='".$this->session['anocaptura']."' ";
        }        
        if ($this->session['rol'] < 4) {
            if ($this->session['userArea'] != "") {
                $filtro.= " AND unidadResponsable_id in ('" . $this->session['userArea'] . "') ";
            }
            if ($this->session['programas'] != "") {
                $filtro.= " AND programa_id in (" . $this->session['programas'] . ") ";
            }
        }
        if ($this->session['rol'] == 1) {
            $filtro.= " AND userId ='" . $this->session['userId'] . "' ";
        }
        $sql = "SELECT count(*) FROM proyectos_acciones WHERE active=1 " . $filtro . ";";
        $res = $this->db->sql_query($sql, $this->db) or die($this->script);
        list($total) = $this->db->sql_fetchrow($res);
        if ($total > 0)
            $this->bufferProyectos.='<button class="btn btn-default" type="button" id="avalidados">Avances validados&nbsp;&nbsp;<span class="badge">' . $total . '</span></button>&nbsp;&nbsp;';
    }

    function avancesRechazados() {
        switch ($this->session['rol']) {
            case 1:
                $filtro = "AND active='1'  AND estatus_avance_entrega='3' ";
                break;
            case 2:
                $filtro = "AND active='1'  AND estatus_avance_entrega='6' ";
                break;
            case 3:
                $filtro = "AND active='1'  AND estatus_avance_entrega='9' ";
                break;
            case 4:
            case 5:
                $filtro = "AND active='1'  AND estatus_avance_entrega IN (3,6,9) ";
                break;
        }
        if($this->session['anocaptura'] >= 2015){
            $filtro.= " AND YEAR(fecha_alta) ='".$this->session['anocaptura']."' ";
        }        
        if ($this->session['rol'] < 4) {
            if ($this->session['userArea'] != "") {
                $filtro.= " AND unidadResponsable_id in ('" . $this->session['userArea'] . "') ";
            }
            if ($this->session['programas'] != "") {
                $filtro.= " AND programa_id in (" . $this->session['programas'] . ") ";
            }
        }
        if ($this->session['rol'] == 1) {
            $filtro.= " AND userId ='" . $this->session['userId'] . "' ";
        }
        $sql = "SELECT count(*) FROM proyectos_acciones WHERE active=1 " . $filtro . ";";
        $res = $this->db->sql_query($sql, $this->db) or die($this->script);
        list($total) = $this->db->sql_fetchrow($res);
        if ($total > 0)
            $this->bufferProyectos.='<button class="btn btn-default" type="button" id="arechazados">Avances rechazados&nbsp;&nbsp;<span class="badge">' . $total . '</span></button>&nbsp;&nbsp;';
    }

    function avancesNoValidados() {
        $this->bufferProyectos = "";
        $total = 0;
        switch ($this->session['rol']) {
            case 1:
                $filtro = "AND active='1'  AND estatus_avance_entrega IN (0,1,2) ";
                break;
            case 2:
                $filtro = "AND active='1'  AND estatus_avance_entrega IN (0,1,2,5) ";
                break;
            case 3:
                $filtro = "AND active='1'  AND estatus_avance_entrega in (5,8) ";
                break;
            case 4:
            case 5:
                $filtro = "AND active='1'  AND estatus_avance_entrega IN (0,1,2,5,8) ";
                break;
        }
        if($this->session['anocaptura'] >= 2015){
            $filtro.= " AND YEAR(fecha_alta) ='".$this->session['anocaptura']."' ";
        }

        $filtro.=" AND estatus_entrega = 10 ";
        if ($this->session['rol'] < 4) {
            if ($this->session['userArea'] != "") {
                $filtro.= " AND unidadResponsable_id in ('" . $this->session['userArea'] . "') ";
            }
            if ($this->session['programas'] != "") {
                $filtro.= " AND programa_id in (" . $this->session['programas'] . ") ";
            }
        }
        if ($this->session['rol'] == 1) {
            $filtro.= " AND userId ='" . $this->session['userId'] . "' ";
        }

        $sql = "SELECT count(*) FROM proyectos_acciones WHERE active=1 " . $filtro . ";";
        $res = $this->db->sql_query($sql, $this->db) or die($this->script);
        list($total) = $this->db->sql_fetchrow($res);
        if ($total > 0)
            $this->bufferProyectos.='<button class="btn btn-default" type="button" id="anovalidados">Avances sin validar&nbsp;&nbsp;<span class="badge">' . $total . '</span></button>&nbsp;&nbsp;';
    }

    function insertaBitacoraModulos() {
        $ins = "INSERT INTO  log_modulos (user_id,aplicacion,apli_com,ip)
 			  VALUES ('" . $this->session['userId'] . "','" . $this->session ["aplicacion"] . "','" . $this->session ["apli_com"] . "','" . $this->session['ip'] . "');";
        $res = $this->db->sql_query($ins) or die($this->cadena_error);
    }

    function obtenAViso() {
        return $this->bufferProyectos;
    }

    function obtenNombre() {
        return $this->nombre;
    }

    function obtenTitulo() {
        return $this->titulo;
    }

    function obtenUrl() {
        return $this->url;
    }
    function obtenAno(){
        return $this->buffer_ano;
    }

}

?>