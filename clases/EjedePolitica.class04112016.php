<?php
class EjedePolitica  extends Comunes implements InterfazCatalogos{
    var $db;
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
    
    function __construct($db,$data,$session,$path,$pages){
        $this->db     = $db;
        $this->data   = $data;
        $this->path   = $path;
        $this->server = $server;
        $this->session= $session;
        $this->pages  = $pages;
        $this->tabla  = "cat_ejes";
        $this->campo  = "eje_id";
        $this->catalogoId=1;
        $this->array_datos=array();
        $this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
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
                    $this->listaActividades();
                    break;          
            }
        }
    }
    
    function regresaDatos(){
        $this->array_datos=array();
        $sql="SELECT * FROM ".$this->tabla." WHERE eje_id='".$this->data['id']."' LIMIT 1;";
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
            $del="UPDATE ".$this->tabla." SET active='1' WHERE eje_id='".$this->data['id']."' LIMIT 1;";
            $res=$this->db->sql_query($del) or die($this->cadena_error);
            if($res){
                $folio=$this->data['id'];
                $this->insertaBitacoraCatalogo($folio,4);
            }
        }
        return $folio;        
    }
    
    function eliminar(){
        $folio=0;
        if( ($this->data['id']+0) > 0)
        {
            $del="UPDATE ".$this->tabla." SET active='2' WHERE eje_id='".$this->data['id']."' LIMIT 1;";
            $res=$this->db->sql_query($del) or die($this->cadena_error);
            if($res){
                $folio=$this->data['id'];
                $this->insertaBitacoraCatalogo($folio,3);
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
                if( (trim($clave) != "opc") && (trim($clave) != "random") && (trim($clave) != "eje_id") && (trim($clave) != "idSec")  && (trim($clave) != "idTmp") )
                {
                    $update[]= $clave." = \"".$valor."\"";
                }
            }
            $upd="UPDATE ".$this->tabla." SET ".implode(',',$update)." WHERE eje_id='".$this->data['idSec']."';";
            $res=$this->db->sql_query($upd) or die($this->cadena_error);
            if($res){
                $folio=$this->data['idSec'];
                $this->insertaBitacoraCatalogo($folio,2);
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
                if( (trim($clave) != "opc") && (trim($clave) != "random") && (trim($clave) != "idSec")  && (trim($clave) != "idTmp") )
                {
                    $campos[] =$clave;
                    $valores[]="\"".$valor."\"";
                }
            }
            $ins="INSERT INTO ".$this->tabla." (".implode(',',$campos).") VALUES (".implode(',',$valores).");";
            $res=$this->db->sql_query($ins) or die($this->cadena_error);
            if($res){
                $folio=$this->db->sql_nextid();
                $this->insertaBitacoraCatalogo($folio,1);
            }
        }
        return $folio;
    }
    
    function formato(){
        $buffer="
            <div id='resultado' class='error'></div><br>
            <input type='hidden' name='valueId' id='valueId' value='".($this->array_datos['eje_id'] + 0)."'>
            <table width='80%' align='center' border='0'>
            <tr>
            <td>".NMEJEPOLITICA."</td>
            <td><input type='text' required='yes' class='form-control validatexto' style='height: 30px;width:300px;' placeholder='".NMEJEPOLITICA."'  value='".$this->array_datos['nombre']."' maxlength='200' name='nomCatalogo' id='nomCatalogo'>
            ".$this->muestraAyuda($ayudas[1])."</td>
            <td rowspan='3'>".$this->procesando(2)."</td></tr>
            <tr><td>".ESTATUS."</td><td>".$this->generaActivos($this->array_datos['eje_activo'])." ".$this->muestraAyuda($ayudas[2])."</td></tr>
            <tr><td colspan='2' class='tdcenter'><br>";
        if($this->opc != 6){
            $buffer.="<button type='button' class='btn btn-success savecatalogo' id='guardarEje' name='guardarEje'>".GRABARDATOS."</button>&nbsp;&nbsp;&nbsp;";
        	}
            $buffer.="
            <button class='btn btn-primary' name='buttonBuscar' id='buttonBuscar' type='button' value='1' onclick=\"location='aplicacion.php?aplicacion=".$this->session['aplicacion']."&apli_com=".$this->session['apli_com']."&opc=0'\">".BTNLISTADO."</button>
            </td></tr>
            </table><br><br><br>";
        return $buffer;
    }
    
    function generaFiltros(){
        $bufferFiltros="
            <table width='80%' align='center' border='0'>
              <tr><td class='tdcenter'>".$this->generaActivos($this->data['estatus'])."</td></tr>
              <tr><td class='tdcenter'><input type='text' class='form-control' style='height: 30px;' name='busqNombre' id='busqNombre' placeholder='".NMEJEPOLITICA."' value='".$this->data['busqNombre']."'></td></tr>
            <tr><td><button class='btn btn-primary' name='buttonBuscar' id='buttonBuscar' type='submit' value='1' >".BTNBUSCR."</button></td>
          </table>".$this->procesando(1);
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
        <table width='95%' ><tr><td class='tdright'><button class='btn btn-success' name='buttonBuscar' id='buttonBuscar' type='button' value='1' onclick=\"location='aplicacion.php?aplicacion=".$this->session['aplicacion']."&apli_com=".$this->session['apli_com']."&opc=1'\">".BTNNUEVAEJE."</button></td></tr></table>";
        $buf=$imgAct=$imgCon=$imgEli="";
        $arrayActivos = $this->catalogoActivos();
        $sql_count="SELECT eje_id FROM cat_ejes WHERE 1 ".$this->cadenaFiltros.";";
        $res_count=$this->db->sql_query($sql_count) or die($this->cadena_error);
        $no_registros=$this->db->sql_numrows($res_count);
        if($no_registros > 0)
        {
            $this->pages = new Paginador();
            $this->pages->items_total = $no_registros;
            $this->pages->mid_range = 25;
            $this->pages->paginate();
            $width=0;
      
            $sql="SELECT eje_id,nombre,active FROM cat_ejes WHERE 1 ".$this->cadenaFiltros." ORDER BY eje_id ASC limit ".$this->session['page'].",".$this->session['regs'].";";
            $res=$this->db->sql_query($sql) or die($this->cadena_error);
            if($this->db->sql_numrows($res) > 0)
            {
                $buffer.="<table width='95%' class='table tablesorter table-bordered' align='center' id='MyTableActividades'>
                    <thead>
                        <tr class='alturaTableHeader'>
                            <td width='10%' class='tdcenter cabecerasTable'>".ID."</td>
                            <td width='58%' class='tdcenter cabecerasTable'>".EJEPOLITICA."</td>
                            <td width='20%' class='tdcenter cabecerasTable'>".ESTATUS."</td>
                            <td width='12%' class='tdcenter cabecerasTable' colspan='3'>".ACCIONES."</td>
                        </tr>
                    </thead>
                    <tbody>";
                $c=0;
                while(list($eje_id,$eje,$eje_activo) = $this->db->sql_fetchrow($res))
                {
                    $imgAct="<a href='#'><img src='".$this->path."imagenes/iconos/pencil.png'    class='actualiza' id='a-".$this->catalogoId."-".$eje_id."' width='13' alt='".ACTUALIZAR."'  title='".ACTUALIZAR."'></a>";
                    $imgCon="<a href='#'><img src='".$this->path."imagenes/iconos/magnifier.png' class='consulta'  id='c-".$this->catalogoId."-".$eje_id."' width='13' alt='".CONSULTAR."'  title='".CONSULTAR."'></a>";
                    $imgEli="<a href='#'><img src='".$this->path."imagenes/iconos/delete.png'    class='elimina'   id='d-".$this->catalogoId."-".$eje_id."' width='13' alt='".ELIMINAR."'   title='".ELIMINAR."'></a>";
                    $imgRes="<a href='#'><img src='".$this->path."imagenes/iconos/deshacer.png'  class='deshacer'   width='16' id='r-".$this->catalogoId."-".$eje_id."' width='13' alt='".ELIMINAR."'   title='".ELIMINAR."'></a>";
                    $tmp=$imgRes;
                    if($eje_activo==1)
                        $tmp=$imgEli;
                    
                    $buffer.="<tr class=\"row".(($c++%2)+1)."\">
                        <td class='tdleft'>".$eje_id."</td>
                        <td class='tdleft'>".$eje."</td>
                        <td class='tdcenter'>".$arrayActivos[$eje_activo]."</td>
                        <td class='tdcenter' width='4%'>".$imgAct."</td>
                        <td class='tdcenter' width='4%'>".$imgCon."</td>
                        <td class='tdcenter' width='4%'>".$tmp."</td>
                    </tr>";
                }
                $buffer.="</tbody><tfoot><tr><td colspan='10' class='tdcenter'><br>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page($this->session['regs'])."</td></tr></foot></table>";  
            }
        }
        else
        {
            $buffer.="<center><span class='tituloMediano'><b>".NORESULTADOS."</b></span></center>";
        }
        return $buffer;
    }
    
    function insertaBitacoraCatalogo($folio,$estatus){
    	// 1 alta, 2 actualizar, 3 eliminar  4 restaura
    	$ins="INSERT INTO log_catalogos (user_id,catalogo_id,catalogo,folio,estatus,ip)
 			  VALUES ('".$this->session['userId']."','". $this->catalogoId."','Usuarios','".$folio."','".$estatus."','".$this->session['ip']."');";
    	$res=$this->db->sql_query($ins) or die($this->cadena_error);
    }
    
    function obtenBuffer(){
        return $this->buffer;
    }
    
    function obtenFiltos(){
        return $this->bufferFiltros;
    }
}
?>