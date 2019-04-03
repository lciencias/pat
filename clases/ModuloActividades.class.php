<?php
class ModuloActividades extends Comunes{
  var $db;
  var $data;
  var $session;
  var $server;
  var $path;
  var $buffer;
  var $bufferFiltros;
  var $opc;
  var $pages;
  var $arrayRoles;
  var $cadena_error;
  var $cadenaFiltros;
  function __construct($db,$data,$session,$server,$path,$pages){
    $this->db     = $db;
    $this->data   = $data;
    $this->path   = $path;
    $this->server = $server;
    $this->session= $session;
    $this->pages  = $pages;
    $this->cadena_error="<script>location.href='".$this->path_web."'</script>";
    $this->arrayRoles=array();
    $this->buffer="";
    $this->bufferFiltros=$this->cadenaFiltros="";
    $this->opc    = $this->data['opc'] + 0;
    
    if( trim($this->session['areas']) != "")
    {
      switch($this->opc)
      {
        case 0:
          $this->recuperaFiltros();
		  $this->buffer='<div class="container-fluid">
					<div class="row">
						<div class="col-md-3 sidebar">'.$this->muestraFiltros().'</div>
						<div class="col-md-9 main">'.$this->listaActividades().'</div>
					</div>
				</div>';
          break;
		case 1:
			 $this->buffer="";
             $this->Formularios();	
			break;
        default:
          $this->listaActividades();
          break;          
      }
    }
  }

  function Formularios()
  {
    $this->buffer.='<div id="content_tabs" style="padding-left:50px;">';
    $folio_id=0;
    $buffer="";
    $array_formatos=array();
    $sql="SELECT formato_id,formato_nombre FROM cat_formatos ORDER BY formato_id;";
    $res=$this->db->sql_query($sql) or die($this->cadena_error);
    if($this->db->sql_numrows($res) > 0)
    {
      $this->buffer.='<ul id="tabs" class="nav nav-tabs" >';
      while(list($_id,$_nm) = $this->db->sql_fetchrow($res))
      {
        $style="";
        if($_id == 1)
        {
          $style=" class='active'   ";
        }
        $array_formatos[$_id] = $_nm;
        $this->buffer.='<li '.$style.'><a href="#h'.$_id.'" role="tab"  data-toggle="tab">Formato '.$_nm.'</a></li>';
      }
      $this->buffer.='</ul>';
      $this->buffer.='<div id="my-tab-content" class="tab-content">';
      if(count($array_formatos)>0)
      {
        foreach($array_formatos as $_id => $_nm)
        {
          $style=" class='tab-pane' ";
          if($_id == 1)
           $style=" class='tab-pane active' ";
          switch($_id){
            case 1:
              $obj_act= new Actividades($this->db,$this->data);
              $buffer=$obj_act->obtenFormato();
              break;
            case 3:
              $obj_fes = new Festivales($this->db,$this->data);
              $buffer=$obj_fes->obtenFormato();
              break;
            case 4:
              $obj_his = new AHistorico($this->db,$this->data);
              $buffer=$obj_his->obtenFormato();
              break;                    
            case 5:
              $obj_ins = new Institucional($this->db,$this->data);
              $buffer=$obj_ins->obtenFormato();
              break;
            case 6:
              $obj_tal = new Talleres($this->db,$this->data);
              $buffer=$obj_tal->obtenFormato();
              break;
            default:
              $buffer="id:  ".$_id." <br>  ".$_nm;
              break;
              
          }
          $this->buffer.="<div ".$style." id='h".$_id."'>".$buffer."</div>\n";
        }
      }
      $this->buffer.="</div>\n";
    }
    $this->buffer.="</div>\n";
  }
    
  function recuperaFiltros(){
    $this->cadenaFiltros="";
    if( ($this->data['idarea']+0) > 0){
      $this->cadenaFiltros.=" AND area_id = '".$this->data['idarea']."' ";
    }
    else{
      $this->cadenaFiltros.= " AND area_id IN (".$this->session['areas'].") ";
    }
    if( ($this->data['idprograma']+0) > 0){
      $this->cadenaFiltros.=" AND cat_programa_id = '".$this->data['idprograma']."' ";
    }
    if( ($this->data['idproyecto']+0) > 0){
      $this->cadenaFiltros.=" AND subprograma_id = '".$this->data['idproyecto']."' ";
    }
    if( trim($this->data['estatus'])!= ""){
      $this->cadenaFiltros.=" AND proy_status = '".$this->data['estatus']."' ";
    }
    if( ($this->data['idano']+0) > 0){
      $this->cadenaFiltros.=" AND YEAR(proy_fecha_inicio) = '".$this->data['idano']."' ";
    }
    if( ($this->data['idmes']+0) > 0){
      $this->cadenaFiltros.=" AND MONTH(proy_fecha_inicio) = '".$this->data['idmes']."' ";
    }
    if( ($this->data['rol']+0) > 0){
      $this->cadenaFiltros.=" AND proy_rol  = '".$this->data['rol']."' ";
    }
    if( trim($this->data['busqNombre'])!= ""){
      $this->cadenaFiltros.=" AND proy_nombre  like  '".$this->data['busqNombre']."%' ";
    }
    if( trim($this->data['busqFolio'])!= ""){
      $this->cadenaFiltros.=" AND proy_id = '".$this->data['busqFolio']."' ";
    }    
  }
  function muestraFiltros(){
    $bufferFiltros="
            <table width='80%' align='center' border='0'>
              <tr><td class='tdcenter'>".$this->generaEjes($this->db,$this->data['ideje'])."</td></tr>
              <tr><td class='tdcenter'>".$this->generaPoliticas($this->db,$this->data['idpolitica'],2)."</td></tr>
              <tr><td class='tdcenter'>".$this->generaAreas($this->db,'',$this->data['idarea'],2)."</td></tr>
              <tr><td class='tdcenter'>".$this->generaProgramas($this->db,$this->data['idarea'],$this->data['idprograma'],2)."</td></tr>
              <tr><td class='tdcenter'>".$this->generaObjetivosGenerales($this->db,$this->data['idarea'],$this->data['idprograma'],$this->data['idobjetivog'],2)."</td></tr>
              <tr><td class='tdcenter'>".$this->generaProyectos()."</td></tr>
              <tr><td class='tdcenter'>".$this->generaStatus($this->db,$this->data['estatus'])."</td></tr>
              <tr><td class='tdcenter'>".$this->generaAnos($this->db,$this->data['idano'])."</td></tr>
              <tr><td class='tdcenter'>".$this->generaMeses($this->db,$this->data['idmes'])."</td></tr>
              <tr><td class='tdcenter'>".$this->generaRol($this->db,$this->data['rol'])."</td></tr>
              <tr><td class='tdcenter'><input type='text' class='form-control' style='height: 30px;' name='busqNombre' id='busqNombre' placeholder='".NMACTIVIDAD."' value='".$this->data['busqNombre']."'></td></tr>
              <tr><td class='tdcenter'><input type='text' class='form-control' style='height: 30px;' name='busqFolio'  id='busqFolio'  placeholder='".FOLIO."' value='".$this->data['busqFolio']."'></td></tr>
            <tr><td><button class='btn btn-mio' name='buttonBuscar' id='buttonBuscar' type='submit' value='1' >".BTNBUSCR."</button></td>
          </table>";
		  return $bufferFiltros;
  }
  
  
  
  function listaActividades(){
    $buffer="";
    $buf=$imgAct=$imgCon=$imgEli="";
    $sql_count="SELECT proy_id FROM proyectos WHERE proy_status!='' ".$this->cadenaFiltros.";";
    $res_count=$this->db->sql_query($sql_count) or die($this->cadena_error);
    $no_registros=$this->db->sql_numrows($res_count);
    if($no_registros > 0)
    {
      $this->pages = new Paginador();
      $this->pages->items_total = $no_registros;
      $this->pages->mid_range = 25;
      $this->pages->paginate();
      $width=0;
      $arrayRoles=$this->catalogoRoles($this->db);
      $sqlEst="SELECT proy_status,count(proy_status) as total FROM proyectos WHERE proy_status!=''  ".$this->cadenaFiltros." GROUP BY proy_status ORDER BY proy_status;";
      $resEst=$this->db->sql_query($sqlEst) or die($this->cadena_error);
      if($this->db->sql_numrows($resEst) > 0){
		  $width=round(100/($this->db->sql_numrows($resEst)+1));
        $buffer.="<table width='95%' ><tr>";
        while(list($status,$total) =$this->db->sql_fetchrow($resEst)){
          $buffer.="<td class='tdleft'><b>".$status."</b> (".$total.")</td>";
        }
        $buffer.="<td class='tdright'><button class='btn btn-mio' name='buttonBuscar' id='buttonBuscar' type='button' value='1' onclick=\"location='aplicacion.php?aplicacion=1&apli_com=1&opc=1'\">".BTNNUEVAACTIVIDAD."</button></td>";
        $buffer.="</tr></table>";
      }
      
      $sql="SELECT proy_id,proy_nombre,proy_fecha_inicio,proy_fecha_termino,proy_status,proy_rol,relevancia,proy_poblacion 
          FROM proyectos WHERE proy_status!='' ".$this->cadenaFiltros." ORDER BY proy_id DESC ".$this->pages->limit.";";
      $res=$this->db->sql_query($sql) or die($this->cadena_error);
      if($this->db->sql_numrows($res) > 0)
      {
        $buffer.="          
          <table width='95%' class='table tablesorter table-bordered' align='center' id='MyTableActividades'>
          <thead>
          <tr class='alturaTableHeader'>
			<td width=' 6%' class='tdcenter cabecerasTable'>".FOLIO."</td>
			<td width='30%' class='tdcenter cabecerasTable'>".ACTIVIDAD."</td>
			<td width='10%' class='tdcenter cabecerasTable'>".FECHAS."</td>
			<td width='12%' class='tdcenter cabecerasTable'>".ESTATUS."</td>
            <td width=' 6%' class='tdcenter cabecerasTable'>".PB."</td>
			<td width=' 16%' class='tdcenter cabecerasTable'>".ROL."</td>
            <td width=' 8%' class='tdcenter cabecerasTable'>".RELEVANCIA."</td>
			<td width='12%' class='tdcenter cabecerasTable' colspan='3'>".ACCIONES."</td>
          </tr>
          </thead>
          <tbody>";
        $c=0;
        while(list($folio,$nombre,$fecha_inicio,$fecha_final,$estatus,$rol,$revelancia,$proy_poblacion) = $this->db->sql_fetchrow($res))
        {
          $imgAct="<img src='".$this->path."imagenes/iconos/pencil.png'    id='a-".$folio."' width='13' alt='".ACTUALIZAR."'  title='".ACTUALIZAR."'>";
          $imgCon="<img src='".$this->path."imagenes/iconos/magnifier.png' id='c-".$folio."' width='13' alt='".CONSULTAR."'  title='".CONSULTAR."'>";
          $imgEli="<img src='".$this->path."imagenes/iconos/delete.png'    id='d-".$folio."' width='13' alt='".ELIMINAR."'   title='".ELIMINAR."'>";
          $buffer.="<tr class=\"row".(($c++%2)+1)."\">
              <td class='tdleft'>".$folio."</td>
              <td class='tdleft'>".$nombre."</td>
			  <td class='tdcenter'>".$this->Formato_Fecha($fecha_inicio)."</td>
			  <td class='tdcenter'><select name='s-".$folio."' id='s-".$folio."' class='bootstrap-select status' style='width:100px;'>".$this->generaStatusValor($this->db,$estatus)."</select></td>
              <td class='tdcenter'>".$proy_poblacion."</td>
              <td class='tdcenter'>".$arrayRoles[$rol]."</td>
              <td class='tdcenter'>".$revelancia."</td>
              <td class='tdcenter' width='4%'>".$imgAct."</td>
              <td class='tdcenter' width='4%'>".$imgCon."</td>
              <td class='tdcenter' width='4%'>".$imgEli."</td>
              </tr>";
        }
        $buffer.="</tbody><tfoot><tr><td colspan='10' class='tdcenter'><br>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page()."</td></tr></foot></table>";  
      }
    }else{
      $buffer="<center><span class='tituloMediano'><b>".NORESULTADOS."</b></span></center>";
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