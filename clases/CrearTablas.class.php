<?php
class CrearTablas  extends ComunesEstadisticas implements InterfazCatalogos{
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
    var $folio;
    
    function __construct($db, $data, $session, $server, $path, $pages) {
        $this->db     = $db;
        $this->data   = $data;
        $this->path   = $path;
        $this->server = $server;
        $this->session= $session;
        $this->pages  = $pages;
        $this->tabla  = "cat_tablas";
        $this->campo  = "id";
        $this->catalogoId=17;
        $this->array_datos=array();
        $this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
        $this->opc    = $this->data['opc'] + 0;
        $this->cadenaFiltros = "";
        $this->folio = 0;
        switch($this->opc){
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
    
    
    function regresaDatos(){
        $this->array_datos=array();
        $sql="SELECT * FROM ".$this->tabla." WHERE ano='".$this->data['id']."' LIMIT 1;";
        $res=$this->db->sql_query($sql) or die($this->cadena_error.$sql);
        if($this->db->sql_numrows($res)>0)
        {
            $this->array_datos=$this->db->sql_fetchrow($res);
        }
    }
    
    function restaurar(){
        $folio=0;
        if( ($this->data['id']+0) > 0)
        {
            $del="UPDATE ".$this->tabla." SET activa='1' WHERE id='".$this->data['id']."' LIMIT 1;";
            $res=$this->db->sql_query($del) or die($this->cadena_error.$del);
            $folio=$this->data['id'];
        }
        $this->folio = $folio;       
    }
    
    function eliminar(){
        $folio=0;
        if( ($this->data['id']+0) > 0)
        {
        	$folio=$this->data['id'];
        	$sql= " SELECT tabla FROM ".$this->tabla." WHERE id='".$this->data['id']."' limit 1;";
        	$res=$this->db->sql_query($sql) or die("Error al consultar");
        	if($this->db->sql_numrows($res) > 0){
        		list($tabla) = $this->db->sql_fetchrow($res);
        		$tabla = strtolower($tabla);
        		$tablaC = strtolower(str_replace("view_","view_C_",$tabla));
        		$tablaA = strtolower(str_replace("view_","view_A_",$tabla));
        		
        		$delA   = "DROP TABLE IF EXISTS ".$tablaA.";";
        		$this->db->sql_query($delA) or die($this->cadena_error.$delA);
        		
        		$delC   = "DROP TABLE IF EXISTS ".$tablaC.";";        		
        		$this->db->sql_query($delC) or die($this->cadena_error.$delC);
        		
        		$del   = "DROP TABLE IF EXISTS ".$tabla.";";
        		$this->db->sql_query($del) or die($this->cadena_error.$delC);        		
        		
        		$del    = "DELETE FROM ".$this->tabla." WHERE id='".$this->data['id']."' limit 1;";
        		$this->db->sql_query($del) or die($this->cadena_error.$del);

        	}
        
        }
        $this->folio = $folio;        
    }
    
    function actualizar(){
    }
    
    function insertar(){
        $this->folio = 0;
        if(count($this->data) > 0)
        {
            $this->data=$this->LimpiaValores($this->data);
            $campos  = array();
            $valores = array();
            foreach($this->data as $clave => $valor)
            {
                if( (trim($clave) != "opc") && (trim($clave) != "random") && (trim($clave) != "idSec") && (trim($clave) != "eje_id") && (trim($clave) != "nombre") && (trim($clave) != "idTmp") && (trim($clave) != "PHPSESSID"))
                {
                    $campos[] =$clave;
                    $valores[]="\"".strtolower($valor)."\"";
                }
            }
            if($this->generaTabla() == 1){
            	$ins="INSERT INTO ".strtolower($this->tabla)." (".implode(',',$campos).") VALUES (".implode(',',$valores).");";
            	$res=$this->db->sql_query($ins) or die($this->cadena_error.$ins);
            	$this->folio=$this->db->sql_nextid();            
            	$this->insertaBitacoraCatalogo($this->folio,1);
            }
        }
    }
    
    function generaTabla(){
    	$exito = 0;
    	$temporal = str_replace("Temporal","",$this->data['tabla']);
    	$tmpFecha = explode("_",$temporal);
    	$fecha_ini = substr($tmpFecha[1],4,4)."-".substr($tmpFecha[1],2,2).'-'.substr($tmpFecha[1],0,2)." 00:00:01";
    	$fecha_fin = substr($tmpFecha[2],4,4)."-".substr($tmpFecha[2],2,2).'-'.substr($tmpFecha[2],0,2)." 23:59:59";
    	$tablaC = strtolower(str_replace("View_","view_C_",$this->data['tabla']));
    	$tablaA = strtolower(str_replace("View_","view_A_",$this->data['tabla']));
    	$this->data['tabla'] = strtolower($this->data['tabla']);
    	$sql= "DROP TABLE IF EXISTS ".$this->data['tabla'].";";
    	$res = $this->db->sql_query($sql) or die ($this->cadena_error.$sql);
    	$sql= "CREATE TABLE ".$this->data['tabla']." as 
				select a.id AS id,a.ano_id AS ano_id,a.fecha_alta AS fecha_alta,c.area_id AS unidadResponsableId,c.nombre
				 AS area,d.programa_id AS programa_id,d.nombre AS programa,a.proyecto AS proyecto,a.ponderacion AS ponderacionProyecto
				,a.presupuesto_otorgado AS presupuesto_otorgado,a.presupuesto_estimado AS presupuesto_estimado,a.unidadOperativaId
				,b.id as actividadId,b.actividad AS actividad,b.ponderacion AS ponderacionActividad,b.tipo_actividad_id AS tipo_actividad_id
    			,b.medida_id,e.nombre AS medida,f.trimestre1 AS trimestre1,f.trimestre2 AS trimestre2,f.trimestre3 AS trimestre3,f.trimestre4
				 AS trimestre4,(f.trimestre1 + f.trimestre2 + f.trimestre3 + f.trimestre4) AS total,h.trimestre1 AS Atrimestre1,h.trimestre2 AS Atrimestre2,h.trimestre3 AS Atrimestre3,h.trimestre4
				 AS Atrimestre4,(h.trimestre1 + h.trimestre2 + h.trimestre3 + h.trimestre4) AS totalAvance,g.politica_id AS politica_id,g.nombre AS politica,w.eje_id AS eje_id,w.nombre AS eje,
    			a.active as activoP, b.active as activoA,c.ponderacion as ponderacionArea,d.ponderacion as ponderacionPrograma,w.ponderacion as ponderacionEje
				from proyectos_acciones a left join proyectos_actividades b on b.proyecto_id = a.id  
				left join proyectos_acciones_metas f on f.proyecto_id = b.proyecto_id and f.actividad_id = b.id 
				left join proyectos_acciones_avances h on h.proyecto_id = b.proyecto_id and h.actividad_id = b.id 
				join cat_areas c on a.unidadResponsable_id = c.area_id 
				join cat_programas d on a.programa_id = d.programa_id
				join cat_medidas e on b.medida_id = e.medida_id 
				join cat_politica_programa j on a.programa_id = j.programa_id 
				join cat_politicas g on j.politica_id = g.politica_id 
				join cat_ejes w on w.eje_id = g.eje_id 
				where a.active = '1' and a.fecha_alta between '".$fecha_ini."' AND '".$fecha_fin."'  
				order by a.unidadResponsable_id,a.programa_id,a.id,b.id;";    	
    	$res = $this->db->sql_query($sql) or die ($this->cadena_error.$sql);
    	if($res){    		
    		$exito = 1;
    		$sqlc = "DROP TABLE IF EXISTS ".$tablaC."";
    		$this->db->sql_query($sqlc) or die ($this->cadena_error.$sqlc);
    		$sqlc = "CREATE TABLE ".$tablaC." as SELECT a.proyecto_id,a.actividad_id,a.trimestre_id,a.comentarios 
    				 FROM proyectos_avances_comentarios as a WHERE a.actividad_id IN 
    				(SELECT b.actividadId FROM ".$this->data['tabla']." as b WHERE b.actividadId = a.actividad_id) 
    				 ORDER BY a.proyecto_id,a.actividad_id,a.trimestre_id;"; 
    		$this->db->sql_query($sqlc) or die ($this->cadena_error.$sqlc);
    		
    		$sqla = "DROP TABLE IF EXISTS ".$tablaA.";";
    		$this->db->sql_query($sqla) or die ($this->cadena_error.$sqla);
    		$sqla = "CREATE TABLE ".$tablaA." as SELECT a.proyecto_id,a.actividad_id,a.trimestre_id,
    				 a.path,a.archivo,a.path_web 
    				 FROM proyectos_avances_adjuntos as a WHERE a.actividad_id IN 
    				(SELECT b.actividadId FROM ".$this->data['tabla']." as b WHERE b.actividadId = a.actividad_id) 
    				ORDER BY a.proyecto_id,a.actividad_id,a.trimestre_id;";
    		$this->db->sql_query($sqla) or die ($this->cadena_error.$sqla);    		
    	}
    	return $exito;
    	
    }
    
    function formato(){
        $buffer="
            <div id='resultado' class='error'></div><br>
            <input type='hidden' name='valueId' id='valueId' value='".($this->array_datos['eje_id'] + 0)."'>";
           $name  ="crearTabla";
        $buffer .= "
        	<div id = 'error' style='width:100%;text-align:center;'></div>".$this->procesando(2)."
            <div class='panel panel-danger spancing'>
                <div class='panel-heading'>
                    <span class='titulosBlanco'>" . CREARVISTAS . "</span>
                </div>
                <div class='panel-body'>
                    <table align='center' border='0' class='table' >
					<tr class='altotitulo'>
                		<td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;" . ANO . "</td>
                		<td class='tdcenter' width='5%'>&nbsp;</td>
                		<td class='tdleft alinea'>".$this->generaAnos()."</td>
		            </tr>    	                    	
					<tr class='altotitulo'>
                		<td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;" . TRIMESTRE . "</td>
                		<td class='tdcenter' width='5%'>&nbsp;</td>
                		<td class='tdleft alinea'>".$this->generaTrimestre()."</td>
		            </tr>    	                    	
                   	<tr class='altotitulo'>
                		<td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;" . PERIODOINI . "</td>
						<td class='tdcenter' width='5%'>&nbsp;</td>
                		<td class='tdleft alinea'>
                        	<div class='input-group date' style='width:220px;'>
                            	<input type='text' class='form-control' placeholder='".AVISO."' id='fechaLimiteIni' name'fechaLimiteIni'/>
    							<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>							
                             </div>						
                        </td>
		            </tr>    		
                    <tr class='altotitulo'>
                		<td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;" . PERIODOFIN . "</td>
						<td class='tdcenter' width='5%'>&nbsp;</td>
                		<td class='tdleft alinea'>
                			<div class='input-group date' style='width:220px;'>
                            	<input type='text' class='form-control' placeholder='".AVISO."' id='fechaLimiteFin' name'fechaLimiteFin'/>
    							<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>							
                            </div>						
                        </td>
		            </tr>    		
                    <tr class='altotitulo'>
		                <td class='tdleft bold'>&nbsp;&nbsp;*&nbsp;&nbsp;" . NOMBREVISTA . "</td>
						<td class='tdcenter' width='5%'>&nbsp;</td>
                		<td class='tdleft alinea'>
			                <input type='text' class='form-control validatextonumero' placeholder='" . NOMBREVISTA . "' id='inputNombre' maxlength='250' value='view" . $this->arrayDatos ['proyecto'] . "' style='width:350px;'>
                		</td>
                    </tr>
                    <tr>
                    	<td class='tdcenter legend' colspan='4'>
                				<button type='button' class='btn btn-success' id='" . $name . "' name='" . $name . "'
    		            		data-toggle='tooltip' data-placement='bottom' title='" . TOOLTIPGENERARTABLA . "'>
                				<span class='glyphicon glyphicon-floppy-saved'></span>&nbsp;" . GENERAR . "</button>&nbsp;<button type='button' class='btn btn-primary btn-sm'
                                onclick=\"location='" . $this->path . "aplicacion.php?aplicacion=" . $this->session ['aplicacion'] . "&apli_com=" . $this->session ['apli_com'] . "&opc=0'\">" . REGRESA . "</button>              		
                            <br>
                            </td>
                    </tr>
                				
                    </table>                    
                    		
                </div>
            </div><br><br><br>";
        return $buffer;
    }
    
    function generaFiltros(){
        $bufferFiltros="
            <table width='80%' align='center' border='0'>
              <tr><td class='tdcenter'>".$this->generaActivos($this->data['estatus'])."</td></tr>
              <tr><td class='tdcenter'><input type='text' class='form-control' style='height: 30px;' name='busqNombre' id='busqNombre' placeholder='".NMANO."' value='".$this->data['busqNombre']."'></td></tr>
            <tr><td><button class='btn btn-primary' name='buttonBuscar' id='buttonBuscar' type='submit' value='1' >".BTNBUSCR."</button></td>
          </table>".$this->procesando(1);
		  return $bufferFiltros;
    }
    
    function recuperaFiltros(){
        $this->cadenaFiltros="";
        if( trim($this->data['edoId'])== ""){
            $this->cadenaFiltros.=" AND activa = '1' ";
        }
        if( trim($this->data['busqNombre'])!= ""){
          $this->cadenaFiltros.=" AND tabla  like  '".$this->data['busqNombre']."%' ";
        }        
    }
    
    function listado(){
        $buffer="<div id='resultado' class='error'></div><br>
        <table width='95%' ><tr><td class='tdright'>
        	<button class='btn btn-success' name='buttonBuscar' id='buttonBuscar' type='button' value='1' onclick=\"location='aplicacion.php?aplicacion=".$this->session['aplicacion']."&apli_com=".$this->session['apli_com']."&opc=1'\">".CREARVISTAS."</button>			
		</td></tr></table>";
        $imgCon=$imgEli="";
        $arrayActivos = $this->catalogoActivos();
        $sql_count="SELECT $this->campo FROM ".$this->tabla." WHERE 1 ".$this->cadenaFiltros.";";
        $res_count=$this->db->sql_query($sql_count) or die($this->cadena_error.$this->tabla);
        $no_registros=$this->db->sql_numrows($res_count);
        if($no_registros > 0)
        {
            $this->pages = new Paginador();
            $this->pages->items_total = $no_registros;
            $this->pages->mid_range = 25;
            $this->pages->paginate();      
            $sql="SELECT $this->campo,tabla,activa,defa FROM ".$this->tabla." WHERE 1 ".$this->cadenaFiltros." ORDER BY ".$this->campo." ASC ".$this->pages->limit.";";
            $res=$this->db->sql_query($sql) or die($this->cadena_error);
            if($this->db->sql_numrows($res) > 0)
            {
            	$buffer.="<center><span id='respVista' class='tdcenter'></span></center>";
                $buffer.="<table width='95%' class='table tablesorter table-bordered' align='center' id='MyTableActividades'>
                    <thead>
                        <tr class='alturaTableHeader'>
                            <td width='18%' class='tdcenter cabecerasTable'>".ID."</td>
                            <td width='40%' class='tdcenter cabecerasTable'>".TABLA."</td>
							<td width='20%' class='tdcenter cabecerasTable'>".ESTATUS."</td>                            		
                            <td width='12%' class='tdcenter cabecerasTable' colspan='4'>".ACCIONES."</td>
                        </tr>
                    </thead>
                    <tbody>";
                $c=0;
                while(list($id,$tabla,$activo,$defa) = $this->db->sql_fetchrow($res))
                {
                    $imgCon="<a href='#' class='consulta' id='c-".$this->catalogoId."-".$id."' alt='".CONSULTAR."'  title='".CONSULTAR."'><span class='glyphicon glyphicon-pencil'></span></a>";
                    $imgEli="<a href='#' class='elimina'  id='d-".$this->catalogoId."-".$id."' alt='".ELIMINAR."'   title='".ELIMINAR."'><span class='glyphicon glyphicon-trash'></span></a>";
                    if($defa == 0){
                    	$imgDef="<a href='#' class='default' id='r-".$this->catalogoId."-".$id."' alt='".CAMBIARPREDERTEMINADO."'   title='".CAMBIARPREDERTEMINADO."'><span class='glyphicon glyphicon-ok'></span></a>";
                    }else{
	                   	$imgDef="<a href='#' class='default' id='r-".$this->catalogoId."-".$id."' alt='".PREDERTEMINADO."'   title='".PREDERTEMINADO."'><span class='glyphicon glyphicon-asterisk'></span></a>";                    	
                    }
                    $imgExp="<a href='#' class='cargar'  id='r-".$this->catalogoId."-".$id."' alt='".SUBIR."'   title='".SUBIR."'><span class='glyphicon glyphicon-export'></span></a>";
                    $tmp=$imgDef;
                    if($activo==1)
                        $tmp=$imgEli;
                    
                    $buffer.="<tr class=\"row".(($c++%2)+1)."\">
                        <td class='tdleft'>".$id."</td>
                       	<td class='tdleft'>".$tabla."</td>
                        <td class='tdcenter'>".$arrayActivos[$activo]."</td>                        
                        <td class='tdcenter' width='4%'>".$imgCon."</td>
                        <td class='tdcenter' width='4%'>".$tmp."</td>
						<td class='tdcenter' width='4%'>".$imgDef."</td>
						<td class='tdcenter' width='4%'>".$imgExp."</td>								
                    </tr>";
                }
                $buffer.="</tbody><tfoot><tr><td colspan='7' class='tdcenter'><br>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page($this->session['regs'])."</td></tr></foot></table>";  
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
 			  VALUES ('".$this->session['userId']."','". $this->catalogoId."','Anos','".$folio."','".$estatus."','".$this->session['ip']."');";
    	$res=$this->db->sql_query($ins) or die($this->cadena_error);
    }
        
    function obtenBuffer(){
        return $this->buffer;
    }
    
    function obtenFolio(){
    	return $this->folio;
    }
    
    function obtenFiltos(){
        return $this->bufferFiltros;
    }
}
?>