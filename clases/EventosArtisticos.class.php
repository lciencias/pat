<?php
class EventosArtisticos  extends Comunes implements InterfazCatalogos{
    var $ddb;
    var $data;
    var $path;
    var $session;
    var $pages;
    var $opc;
    var $cadenaFiltros;
    var $buffer;
    var $tabla;
    var $cadena_error;
    var $array_datos;
    var $campo;
    var $catalogoId;
    var $nmBoton;
    
    function __construct($db,$data,$session,$path,$pages){
        $this->db     = $db;
        $this->data   = $data;
        $this->path   = $path;
        $this->server = $server;
        $this->session= $session;
        $this->pages  = $pages;
        $this->tabla  = "cat_eventos_artisticos";
        $this->campo  = "evento_id";
        $this->nmBoton="guardarEventoArt";
        $this->array_datos=array();
        $this->catalogoId=6;
        $this->cadena_error="<script>location.href='".$this->path."logout.php'</script>";
        $this->opc    = $this->data['opc'] + 0;
        $this->cadenaFiltros = "";
        if( trim($this->session['areas']) != "")
        {
            switch($this->opc)
            {
                case 0:
                    $this->recuperaFiltros();
                    $this->buffer='
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3 sidebar">'.$this->generaFiltros().'</div>
                                <div class="col-md-9 main">'.$this->listado().'</div>
                            </div>
                        </div>';
                    break;
                case 1:
                    $this->buffer=$this->formato();	
                    break;
                case 2:
                    $this->buffer=$this->insertar();
                    break;
                case 3:
                    $this->buffer=$this->eliminar();
                    break;
               case 4:
                    $this->buffer=$this->restaurar();
                    break;
               case 5:
                    $this->regresaDatos();
                    $this->buffer=$this->formato();
                    break;
              case 6:
                    $this->regresaDatos();
                    $this->buffer=$this->formato();
                    break;
               case 7:
                    $this->buffer=$this->actualizar();
                    break;                
                default:
                    $this->listado();
                    break;          
            }
        }
    }
    
    function regresaDatos(){
        $this->array_datos=array();
        $sql="SELECT * FROM ".$this->tabla." WHERE ".$this->campo."='".$this->data['id']."' LIMIT 1;";
        $res=$this->db->sql_query($sql) or die($this->cadena_error);
        if($this->db->sql_numrows($res)>0)
        {
            $this->array_datos=$this->db->sql_fetchrow($res);
        }
    }
    
    function restaurar(){
        $folio=0;
        if( ($this->data['id']+0) > 0)
        {
            $del="UPDATE ".$this->tabla." SET active='1' WHERE ".$this->campo."='".$this->data['id']."' LIMIT 1;";
            $res=$this->db->sql_query($del) or die($this->cadena_error);
            if($res){
                $folio=$this->data['id'];
            }
        }
        return $folio;        
    }
    
    function eliminar(){
        $folio=0;
        if( ($this->data['id']+0) > 0)
        {
            $del="UPDATE ".$this->tabla." SET active='2' WHERE ".$this->campo."='".$this->data['id']."' LIMIT 1;";
            $res=$this->db->sql_query($del) or die($this->cadena_error);
            if($res){
                $folio=$this->data['id'];
            }
        }
        return $folio;
    }
    
    function actualizar(){
        $folio=0;
        if(count($this->data) > 0)
        {
            $this->data=$this->LimpiaValores($this->data);
            $update  = array();
            foreach($this->data as $clave => $valor)
            {
                if( (trim($clave) != "opc") && (trim($clave) != "random") && (trim($clave) != "idSec") )
                {
                    $update[]= $clave." = \"".$valor."\"";
                }
            }
            $upd="UPDATE ".$this->tabla." SET ".implode(',',$update)." WHERE ".$this->campo."='".$this->data['idSec']."';";
            $res=$this->db->sql_query($upd) or die($this->cadena_error);
            if($res){
                $folio=$this->data['idSec'];
            }
        }
        return $folio;
    }
    
    function insertar(){
        $folio=0;
        if(count($this->data) > 0)
        {
            $this->data=$this->LimpiaValores($this->data);
            $campos  = array();
            $valores = array();
            foreach($this->data as $clave => $valor)
            {
                if( (trim($clave) != "opc") && (trim($clave) != "random") && (trim($clave) != "idSec") )
                {
                    $campos[] =$clave;
                    $valores[]="\"".$valor."\"";
                }
            }
            $ins="INSERT INTO ".$this->tabla." (".implode(',',$campos).") VALUES (".implode(',',$valores).");";
            $res=$this->db->sql_query($ins) or die($this->cadena_error);
            if($res){
                $folio=$this->db->sql_nextid();
            }
        }
        return $folio;
    }
    
    function formato(){
        $buffer="
            <div id='resultado' class='error'></div><br>
            <input type='hidden' name='valueId' id='valueId' value='".($this->array_datos['evento_id'] + 0)."'>
            <table width='80%' align='center' border='0'>
            <tr>
            <td>".BTNNUEVOEVEART."</td>
            <td><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:300px;'  value='".$this->array_datos['nombre']."' placeholder='".NMEVEART."' maxlength='200' name='nomCatalogo' id='nomCatalogo'>
            ".$this->muestraAyuda($ayudas[1])."</td>
            <td rowspan='3'>".$this->procesando(2)."</td></tr>
            <tr><td>".ESTATUS."</td><td>".$this->generaActivos($this->array_datos['active'])." ".$this->muestraAyuda($ayudas[2])."</td></tr>
            <tr><td colspan='2' class='tdcenter'><br>";
        if($this->opc != 6){
            $buffer.="<button type='button' class='btn btn-mio savecatalogo' id='".$this->nmBoton."' name='".$this->nmBoton."'>".GRABARDATOS."</button>&nbsp;&nbsp;&nbsp;";
        }
            $buffer.="
            <button class='btn btn-mio' name='buttonBuscar' id='buttonBuscar' type='button' value='1' onclick=\"location='aplicacion.php?aplicacion=".$this->session['aplicacion']."&apli_com=".$this->session['apli_com']."&opc=0'\">".BTNLISTADO."</button>
            </td></tr>
            </table><br><br><br>";
        return $buffer;
    }
    function generaFiltros(){
        $bufferFiltros="
            <table width='80%' align='center' border='0'>
              <tr><td class='tdcenter'>".$this->generaActivos($this->db,$this->data['estatus'])."</td></tr>
              <tr><td class='tdcenter'><input type='text' class='form-control' style='height: 30px;' name='busqNombre' id='busqNombre' placeholder='".NMEVEART."' value='".$this->data['busqNombre']."'></td></tr>
            <tr><td><button class='btn btn-mio' name='buttonBuscar' id='buttonBuscar' type='submit' value='1' >".BTNBUSCR."</button></td>
          </table>".$this->Procesando(1);
		  return $bufferFiltros;
    }
    
    function recuperaFiltros(){
        $this->cadenaFiltros="";
        if( trim($this->data['edoId'])== ""){
            $this->data['edoId']=1;
        }
        if( trim($this->data['edoId'])!= ""){
          $this->cadenaFiltros.=" AND active = '".$this->data['edoId']."' ";
        }
        if( trim($this->data['busqNombre'])!= ""){
          $this->cadenaFiltros.=" AND nombre  like  '".$this->data['busqNombre']."%' ";
        }        
    }
    
    function listado(){
        $buffer="<div id='resultado' class='error'></div><br>
        <table width='95%' ><tr><td class='tdright'><button class='btn btn-mio' name='buttonBuscar' id='buttonBuscar' type='button' value='1' onclick=\"location='aplicacion.php?aplicacion=".$this->session['aplicacion']."&apli_com=".$this->session['apli_com']."&opc=1'\">".BTNNUEVOEVEART."</button></td></tr></table>";
        $buf=$imgAct=$imgCon=$imgEli="";
        $arrayActivos = $this->catalogoActivos();
        $sql_count="SELECT ".$this->campo." FROM ".$this->tabla." WHERE 1 ".$this->cadenaFiltros.";";
        $res_count=$this->db->sql_query($sql_count) or die($this->cadena_error);
        $no_registros=$this->db->sql_numrows($res_count);
        if($no_registros > 0)
        {
            $this->pages = new Paginador();
            $this->pages->items_total = $no_registros;
            $this->pages->mid_range = 25;
            $this->pages->paginate();
            $width=0;
      
            $sql="SELECT ".$this->campo.",nombre,active FROM ".$this->tabla."
                  WHERE 1 ".$this->cadenaFiltros." ORDER BY ".$this->campo." ASC ".$this->pages->limit.";";
            $res=$this->db->sql_query($sql) or die($this->cadena_error);
            if($this->db->sql_numrows($res) > 0)
            {                
                    $buffer.="<table width='95%' class='table tablesorter table-bordered' align='center' id='MyTableActividades'>
                    <thead>
                        <tr class='alturaTableHeader'>
                            <td width='10%' class='tdcenter cabecerasTable'>".ID."</td>
                            <td width='58%' class='tdcenter cabecerasTable'>".EVEART."</td>
                            <td width='20%' class='tdcenter cabecerasTable'>".ESTATUS."</td>
                            <td width='12%' class='tdcenter cabecerasTable' colspan='3'>".ACCIONES."</td>
                        </tr>
                    </thead>
                    <tbody>";
                $c=0;
                while(list($_id,$_nm,$_act) = $this->db->sql_fetchrow($res))
                {
                    $imgAct="<a href='#'><img src='".$this->path."imagenes/iconos/pencil.png'    class='actualiza' id='a-".$this->catalogoId."-".$_id."' width='13' alt='".ACTUALIZAR."'  title='".ACTUALIZAR."'></a>";
                    $imgCon="<a href='#'><img src='".$this->path."imagenes/iconos/magnifier.png' class='consulta'  id='c-".$this->catalogoId."-".$_id."' width='13' alt='".CONSULTAR."'  title='".CONSULTAR."'></a>";
                    $imgEli="<a href='#'><img src='".$this->path."imagenes/iconos/delete.png'    class='elimina'   id='d-".$this->catalogoId."-".$_id."' width='13' alt='".ELIMINAR."'   title='".ELIMINAR."'></a>";
                    $imgRes="<a href='#'><img src='".$this->path."imagenes/iconos/deshacer.png'  class='deshacer'   width='16' id='r-".$this->catalogoId."-".$_id."' width='13' alt='".ELIMINAR."'   title='".ELIMINAR."'></a>";
                    $tmp=$imgRes;
                    if($_act==1)
                        $tmp=$imgEli;
                    
                    $buffer.="<tr class=\"row".(($c++%2)+1)."\">
                        <td class='tdleft'>".$_id."</td>
                        <td class='tdleft'>".$_nm."</td>
                        <td class='tdcenter'>".$arrayActivos[$_act]."</td>
                        <td class='tdcenter' width='4%'>".$imgAct."</td>
                        <td class='tdcenter' width='4%'>".$imgCon."</td>
                        <td class='tdcenter' width='4%'>".$tmp."</td>
                    </tr>";
                }
                $buffer.="</tbody><tfoot><tr><td colspan='10' class='tdcenter'><br>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page()."</td></tr></foot></table>";  
            }
        }
        else
        {
            $buffer.="<center><span class='tituloMediano'><b>".NORESULTADOS."</b></span></center>";
        }
        return $buffer;
    }
    
    function obtenBuffer(){
        return $this->buffer;
    }
    
    function obtenFiltos(){
        return $this->bufferFiltros;
    }
}
?>