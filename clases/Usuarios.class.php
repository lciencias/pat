<?php
/**
 * @Author Luis Antonio Hernández Nieto
 * Sistema: Programa Anual Trabajo PAT
 * Cliente: Secretaria de Cultura del Distrito Federal
 * Clase:  Administración de Usuarios
**/

class Usuarios  extends Comunes implements InterfazCatalogos{
    var $ddb;
    var $data;
    var $path;
    var $server;
    var $session;
    var $pages;
    var $opc;
    var $cadenaFiltros;
    var $buffer;
    var $tabla;
    var $cadena_error;
    var $array_datos;
    var $array_programas;
    var $array_areas;
    var $arrayDatos;
    var $campo;
    var $catalogoId;
    var $nmBoton;
    var $idfolio;
    
    function __construct($db,$data,$session,$server,$path,$pages){
        $this->db     = $db;
        $this->data   = $data;
        $this->path   = $path;
        $this->server = $server;
        $this->session= $session;
        $this->pages  = $pages;
        $this->tabla  = "cat_usuarios";
        $this->campo  = "user_id";
        $this->nmBoton="guardarUsuario";
        $this->arrayDatos=array();
        $this->array_datos=array();
        $this->array_menus=array();
        $this->array_areas=array();
        $this->catalogoId=16;
        $this->idfolio=0;
        $this->cadena_error="<script>location.href='".$this->path."aplicacion.php'</script>";
        $this->opc    = $this->data['opc'] + 0;
        $this->cadenaFiltros = "";
        if( trim($this->session['areas']) != "")
        {
            switch($this->opc)
            {
                case 0:
                    $this->recuperaFiltros();
                    $this->buffer=$this->listado();
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
            $this->arrayDatos=$this->array_datos;
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
                $this->insertaBitacoraCatalogo($folio,4);
            }
        }
        return $folio;        
    }
    
    function eliminar(){
        $folio=0;
        if( ($this->data['id']+0) > 0)
        {
            $del="UPDATE ".$this->tabla." SET user_activo='2' WHERE ".$this->campo."='".$this->data['id']."' LIMIT 1;";
            $res=$this->db->sql_query($del) or die($this->cadena_error);
            if($res){
                $folio=$this->data['id'];
                $this->insertaBitacoraCatalogo($folio,3);
            }
        }
        return $folio;
    }
    
    function actualizar(){
        $folio=$this->data['valueId'];
        if(count($this->data) > 0)
        {
        	$array=array();
        	$arrayAreas=array();
        	$array_proyectos=array();
        	if(count($this->data) > 0)
        	{
        		$nombre = trim($this->limpiaCadenas($this->data['user_nombre']));
        		$email  = trim($this->limpiaCadenas($this->data['user_email']));
        		$descripcion = trim($this->limpiaCadenas($this->data['user_datos']));
        		$descripcion2 = trim($this->limpiaCadenas($this->data['user_datos2']));
        		$idunidadoperativa = ($this->data['idunidadoperativa'] + 0);
        		$user_login = trim($this->data['user_login']);
        		$passw  = trim($this->data['user_pass']);
        		$rol    = ($this->data['rol'] + 0);
        		$areaId = ($this->data['idarea'] + 0);
        		$estilo = ($this->data['estilo'] + 0);
        		$ins="UPDATE cat_usuarios 
        				SET user_pass   = \"".$passw."\",
        					user_login  = \"".$user_login."\",
        					user_nombre = \"".$nombre."\",
        					user_email  = \"".$email."\",
        					user_rol    = \"".$rol."\",
        					user_datos  = \"".$descripcion."\",
        					user_datos2 = \"".$descripcion2."\",
        					user_activo = \"1\",
        					area_id     = \"".$areaId."\",
        					unidadOperativaId = \"".$idunidadoperativa."\",
        					estilo_id   = \"".$estilo."\"
        				WHERE user_id='".$folio."';";
        	 	$res=$this->db->sql_query($ins) or die ($this->cadena_error);
        		if($res){
        			$upd="UPDATE cat_usuarios SET user_password = PASSWORD('".$passw."') WHERE user_id='".$folio."'  LIMIT 1 ;";       			
        			$res=$this->db->sql_query($upd) or die ($this->cadena_error);
        			
        			$del="DELETE FROM cat_permisos_menus WHERE usuario_id='".$folio."';";
        			$res=$this->db->sql_query($del) or die ($this->cadena_error);
        			
        			
        			$del="DELETE FROM cat_permisos_areas WHERE usuario_id='".$folio."';";
        			$res=$this->db->sql_query($del) or die ($this->cadena_error);
        			$this->insertaMenus($folio);
        			$this->insertaAreas($folio);
        			$this->insertaBitacoraCatalogo($folio,2);
        		}
        	}
        	$this->idfolio=$folio;
        }
        return $folio;
    }
    
    function regresaOrden(){
        $consec=0;
        $sql="SELECT MAX(orden) FROM ".$this->tabla." WHERE active='1' AND area_id='".$this->data['area_id']."'
        AND programa_id='".$this->data['programa_id']."' AND objetivo_id='".$this->data['objetivo_id']."' 
        AND proyecto_id='".$this->data['proyecto_id']."'LIMIT 1;";
        $res=$this->db->sql_query($sql) or die($this->cadena_error);
        if($this->db->sql_numrows($res)>0)
        {
            list($consec) = $this->db->sql_fetchrow($res);
            $consec++;
            $this->data['orden']=$consec;
        }
    }
    
    function generaAreasProgramasUsuario(){
    	$filtro="";
    	$array=array();
//     	if($this->array_datos['area_id']>0)
//     		$filtro=" AND a.area_id = '".$this->array_datos['area_id']."' ";
//     	$sqlm="SELECT a.area_id, b.programa_id FROM cat_areas a LEFT JOIN cat_area_programa AS b ON a.area_id = b.area_id
//     		   WHERE a.active='1' AND b.programa_id is not null ".$filtro." ORDER BY a.area_id, b.programa_id ;";
//     	$resm=$this->db->sql_query($sqlm) or die($this->cadena_error);
//     	if($this->db->sql_numrows($resm)>0){
//     		while(list($idarea,$idprograma) = $this->db->sql_fetchrow($resm)){
//     			$array[] = $idprograma;
//     		}
//     	}
    	if($this->array_datos['user_id']>0)
    		$filtro=" AND a.usuario_id = '".$this->array_datos['user_id']."' ";
    	$sqlm="SELECT a.area_id, a.programa_id FROM cat_permisos_areas a 
    		   WHERE a.usuario_id > 0".$filtro." ORDER BY a.programa_id ;";
    	$resm=$this->db->sql_query($sqlm) or die($this->cadena_error);
    	if($this->db->sql_numrows($resm)>0){
    		while(list($idarea,$idprograma) = $this->db->sql_fetchrow($resm)){
    			$array[] = $idprograma;
    		}
    	}
    	 
    	return $array;
    }
    
    function generaAreasUsuario(){
    	$sqlm="SELECT a.area_id, b.programa_id FROM cat_areas a LEFT JOIN cat_programas AS b ON a.area_id = b.area_id 
    		   WHERE a.active='1' AND b.programa_id is not null ORDER BY a.area_id, b.programa_id ;";
    	$resm=$this->db->sql_query($sqlm) or die($this->cadena_error);
    	if($this->db->sql_numrows($resm)>0){
    		while(list($idarea,$idprograma) = $this->db->sql_fetchrow($resm)){
    			$array[] = $idarea.'|'.$idprograma;
    		}
    	}
    	return $array;    	 
    }
    
    function generaMenus(){
    	$array=array();
    	$sqlm="SELECT a.menu_id, b.submenu_id FROM cat_menu a LEFT JOIN cat_submenu AS b ON a.menu_id = b.menu_id 
    		  WHERE b.submenu_id is not null  AND a.menu_id>1 AND a.menu_id != 7 AND b.submenu_id != 24 ORDER BY a.menu_id, b.submenu_id ;";
    	$resm=$this->db->sql_query($sqlm) or die($this->cadena_error);
    	if($this->db->sql_numrows($resm)>0){
    		while(list($idmenu,$idsubmenu) = $this->db->sql_fetchrow($resm)){
    			$array[] = $idmenu.'|'.$idsubmenu;
    		}
    	}
    	return $array;
    }
    
    function insertaAreas($folio){
    	$arrayAreas = array();
    	$tmp = array();
    	if($this->data['rol'] >= 4){
    		$arrayAreas=$this->generaAreasUsuario();
    		if(count($arrayAreas)>0){
    			foreach($arrayAreas as $ind => $valores){
    				$tmp=explode('|',$valores);
    				$areaid = $tmp[0];
    				$programaid = $tmp[1];
    				$insM="INSERT INTO cat_permisos_areas(usuario_id,area_id,programa_id) VALUES ('".$folio."','".$areaid."','".$programaid."');";
    				$res=$this->db->sql_query($insM) or die($this->cadena_error);
    			}
    		}
    	}
    	else{
    		$arrayAreas=$this->data['idprograma'];
    		if(count($arrayAreas)>0){
    			foreach($arrayAreas as $valor){    					
    				if(($valor + 0 )>0){
    					$insM="INSERT INTO cat_permisos_areas(usuario_id,area_id,programa_id) VALUES ('".$folio."','".$this->data['idarea']."','".$valor."');";
    					$res=$this->db->sql_query($insM) or die($this->cadena_error);
    				}
    			}
    		}
    	}
    }
    
    function insertaMenus($folio){
    	$arrayMenus=array();
    	$tmp=array();
    	if($this->data['rol'] >= 4)
    	{
    		$arrayMenus=$this->generaMenus();
    		if(count($arrayMenus)>0)
    		{
    			foreach($arrayMenus as $ind => $valores)
    			{
    				$tmp=explode('|',$valores);
    				$menuid    = $tmp[0];
    				$submenuid = $tmp[1];
    				$insM="INSERT INTO cat_permisos_menus(usuario_id,menu_id,submenu_id) VALUES ('".$folio."','".$menuid."','".$submenuid."');";   				
    				$res=$this->db->sql_query($insM) or die($this->cadena_error);
    			}
    		}
    	}
    	else
    	{
    		$arrayMenus = explode('*',trim($this->data['cadenaCoordinacion']));
    		if(count($arrayMenus) > 0)
    		{
    			foreach($arrayMenus as $cadena)
    			{
    				if(trim($cadena) != ""){
    					$tmp=explode('-',$cadena);
    					if(count($tmp)>0)
    					{
	    					$insM="INSERT INTO cat_permisos_menus(usuario_id,menu_id,submenu_id) VALUES ('".$folio."','".$tmp[1]."','".$tmp[3]."');";
    						$res=$this->db->sql_query($insM) or die($this->cadena_error);
    					}
    				}
    			}
    		}
    	}
    	$insM="INSERT INTO cat_permisos_menus(usuario_id,menu_id,submenu_id) VALUES ('".$folio."','1','0');";
    	$res=$this->db->sql_query($insM) or die($this->cadena_error);
    	$insM="INSERT INTO cat_permisos_menus(usuario_id,menu_id,submenu_id) VALUES ('".$folio."','7','0');";
    	$res=$this->db->sql_query($insM) or die($this->cadena_error);
    }
    
    function insertar(){
    	$folio=0;
        $array=array();
        
        $arrayAreas=array();
        $array_proyectos=array();
        if(count($this->data) > 0)
        {
        	$nombre = trim($this->limpiaCadenas($this->data['user_nombre']));
        	$email  = trim($this->limpiaCadenas($this->data['user_email']));
        	$descripcion = trim($this->limpiaCadenas($this->data['user_datos']));
        	$descripcion2 = trim($this->limpiaCadenas($this->data['user_datos2']));
        	$user_login = trim($this->data['user_login']);
        	$passw  = trim($this->data['user_pass']);
        	$rol    = ($this->data['rol'] + 0);
        	$areaId = ($this->data['idarea'] + 0);
        	$estilo = ($this->data['estilo'] + 0);
        	$idunidadoperativa = ($this->data['idunidadoperativa'] + 0);
        	$sql="SELECT user_id FROM cat_usuarios WHERE user_nombre='".$nombre."' LIMIT 1;";
        	$res=$this->db->sql_query($sql) or die($this->cadena_error);
        	if($this->db->sql_numrows($res)>0){
        		$folio = -2;
        	}
        	
           	$sql="SELECT user_id FROM cat_usuarios WHERE user_login='".$user_login."' LIMIT 1;";
        	$res=$this->db->sql_query($sql) or die($this->cadena_error);
        	if($this->db->sql_numrows($res)>0){
        		$folio = -1;
        	}
        	if($folio == 0){
        	 	$ins="INSERT INTO cat_usuarios (user_login,user_pass,user_nombre,user_email,user_rol,user_activo,area_id,estilo_id,user_datos,user_datos2,unidadOperativaId)
        	 		 VALUES (\"".$user_login."\",\"".$passw."\",\"".$nombre."\",\"".$email."\",\"".$rol."\",\"1\",\"".$areaId."\",\"".$estilo."\",\"".$descripcion."\",\"".$descripcion2."\",\"".$idunidadoperativa."\");";
				$res=$this->db->sql_query($ins) or die ($this->cadena_error);
        	 	if($res){
        	 		$folio = $this->db->sql_nextid();
        	 		$upd="UPDATE cat_usuarios SET user_password = PASSWORD('".$passw."') WHERE user_id='".$folio."'  LIMIT 1 ;";
        	 		$res=$this->db->sql_query($upd) or die ($this->cadena_error);
        	 		$this->insertaMenus($folio);
        	 		$this->insertaAreas($folio);
        	 		$this->insertaBitacoraCatalogo($folio,1);
        	 	}        	 	 
        	 }
        }
        $this->idfolio=$folio;
        
    }
    
    function formato(){
    	$arrayMenus=$this->regresaMenuUser();
    	
    	$opc=2;
    	$titulo=ALTAUSUARIO;
    	$nameboton=GUARDADUSUARIO;
    	$read = " ";
    	if(count($this->array_datos)>0){
    		$opc=7;
    		$titulo= $this->array_datos['user_nombre'];
    		$nameboton=ACTUALIZADUSUARIO;
    		$this->array_menus=$this->generaMenus();
    		$this->array_areas=$this->generaAreasProgramasUsuario();
    		//$read = "readonly= 'true' ";
    	}
        $buffer="
        		<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>
						<div class='tdleft titulosBlanco columna1'><span class='titulosBlanco'>".$titulo."</span></div>
						<div class='tdright columna2'>
							<br>
						</div>
					</div>
	  				<div class='panel-body'>
						<input type='hidden' name='valueId' id='valueId' value='".($this->array_datos['user_id'] + 0)."'>
						<input type='hidden' name='opcion' id='opcion' value='".$opc."'>
						<div role='tabpanel'>
						  <!-- Nav tabs -->
						  <ul class='nav nav-tabs' role='tablist'>
						    <li role='presentation' class='active'>
								<a href='#home' aria-controls='home' role='tab' data-toggle='tab'>".DATOSUSUARIO."</a>
							</li>
						    <li role='presentation'>
								<a href='#profile' aria-controls='profile' role='tab' data-toggle='tab'>".DATOSMENU."</a>
							</li>
						    <li role='presentation'>
								<a href='#messages' aria-controls='messages' role='tab' data-toggle='tab'>".DATOSAREAS."</a>
							</li>
						  </ul>
						  <!-- Tab panes -->
						  <div class='tab-content'>
						    <div role='tabpanel' class='tab-pane active' id='home'>".$this->formatoUser($read)."</div>
						    <div role='tabpanel' class='tab-pane' id='profile'>".$this->formatoUserMenu($arrayMenus)."</div>
						    <div role='tabpanel' class='tab-pane' id='messages'>".$this->formatoUserAreas()."</div>
						  </div>
						</div>	
						<div class='tdcenter'><span id='resultado'></span><br></div>
						<div class='tdcenter'>
						<button type='button' class='btn btn-success' id='".$this->nmBoton."' name='".$this->nmBoton."'><span class='glyphicon glyphicon-play-circle'></span>&nbsp;&nbsp;".$nameboton."</button>
						&nbsp;&nbsp;&nbsp;
						<button class='btn btn-primary' name='buttonBuscar' id='buttonBuscar' type='button' value='1' onclick=\"location='aplicacion.php?aplicacion=".$this->session['aplicacion']."&apli_com=".$this->session['apli_com']."&opc=0'\"><span class='glyphicon glyphicon-arrow-left'></span>&nbsp;&nbsp;".REGRESA."</button>		    		
						</div>							
  					</div>
				</div><br><br><br>";
        return $buffer;
    }

    function formatoUserMenu($arrayMenus){
    	$buf="<div class='panel-group' id='accordion' role='tablist' aria-multiselectable='true'>";
    	foreach($arrayMenus as $idMenu => $nmMenu){
    		$buf.="<div class='panel panel-default'>
    				<div class='panel-heading' role='tab' id='menu".$idMenu."'>
    				<h4 class='panel-title'>
    					<a data-toggle='collapse' data-parent='#accordion' href='#collapse".$idMenu."' aria-expanded='false' aria-controls='collapse".$idMenu."'>".ucwords(strtolower($nmMenu))."</a>
    				</h4>
    				</div>
					<div id='collapse".$idMenu."' class='panel-collapse collapse out' role='tabpanel' aria-labelledby='menu".$idMenu."'>
      					<div class='panel-body'>".$this->regresaSubMenuUser($idMenu)."</div>
    				</div>
					</div>";
    	}
    	$buf.="</div>";
    	return $buf;
    }
    
    function formatoUserAreas(){
    	$buf="<table width='80%' align='center' border='0'>
    			<tr class='altura'><td>".AREA."</td><td>".$this->generaAreaUser()."</td></tr>
    			<tr class='altura'><td>".UNIDADOPERATIVA."</td><td>".$this->generaUnidadesOperativas()."</td></tr>
            	<tr class='altura'><td>".PROGRAMA."</td><td>".$this->generaProgramaUser()."</td></tr>
            </table>";
    	return $buf;
    }
    
    function formatoUser($read){
    	$buf="<table width='80%' align='center' border='0'>
            <tr class='altura active'>
            <td>".NMUSUARIO."</td>
            <td><input type='text' required='yes' tabindex='1' class='form-control validatexto' style='height: 30px;width:300px;' placeholder='".NMUSUARIO."'  value='".$this->array_datos['user_nombre']."' maxlength='200' name='user_nombre' id='user_nombre'></td>
            <td rowspan='3'>".$this->procesando(2)."</td></tr>
            <tr class='altura'><td>".USUARIO."</td><td>
            	<input type='text' required='yes' tabindex='2' class='form-control validatexto' 
            		   style='height: 30px;width:180px;' ".$read." placeholder='".USUARIO."'  
            		   value='".$this->array_datos['user_login']."' maxlength='16' name='user_login' id='user_login'>
            </td></tr>
             <tr class='altura'><td>".PASSWORD."</td><td>
            	<input type='text' required='yes' tabindex='3' class='form-control validatextonumero' 
            		   style='height: 30px;width:180px;' placeholder='".PASSWORD."'  
            		   value='".$this->array_datos['user_pass']."' maxlength='16' name='user_pass' id='user_pass'>
            </td></tr>		
           	<tr class='altura'><td>".EMAIL."</td><td>
            	<input type='text' required='yes' tabindex='4' class='form-control validatextonumero' placeholder='".EMAIL."'  
            		   value='".$this->array_datos['user_email']."' style='height: 30px;width:300px;' maxlength='50' name='user_email' id='user_email'>
            </td></tr>	   		
           	<tr class='altura'><td>".DATOS."</td><td>
           			<textarea required='yes' tabindex='5' maxlength='2000'  ".$this->disabled." class='bootstrap-select validatextonumero espTextArea' placeholder='".DESCRIPCIONDEUSUARIO."' name='user_datos' id='user_datos'>".$this->array_datos['user_datos']."</textarea>
            </td></tr>	   		
           	<tr class='altura'><td>".DATOS2."</td><td>
           			<textarea required='yes' tabindex='6' maxlength='2000'  ".$this->disabled." class='bootstrap-select validatextonumero espTextArea' placeholder='".DESCRIPCIONDEUSUARIO."' name='user_datos2' id='user_datos2'>".$this->array_datos['user_datos2']."</textarea>
            </td></tr>	   		

            <tr class='altura'><td>".ROL."</td><td>".$this->generaRol()."</td></tr>
            <tr class='altura'><td>".ESTILO."</td><td>".$this->generaEstilos()."</td></tr>
            </table>";
    	return $buf;
    }
    
    
    function generaFiltros(){
        $bufferFiltros="
            <table width='95%' align='center' border='0'>
              <tr>
        		<td class='tdleft'>".$this->generaAreas('',$this->array_datos['area_id'],2)."</td>
              	<td class='tdleft'>".$this->generaProgramas($this->array_datos['area_id'],$this->array_datos['programa_id'],2)."</td>
              	<td class='tdleft'>".$this->generaRol()."</td>
              	<td class='tdleft'>".$this->generaActivos($this->data['estatus'])."</td>
              </tr>
              <tr>
              	<td class='tdleft' colspan='2'><input type='text' class='form-control' style='height: 30px;width:360px;' name='busqNombre' id='busqNombre' placeholder='".NMUSUARIO."' value='".$this->data['busqNombre']."'></td>
            	<td class='tdcenter' colspan='2'>
              		<button class='btn btn-primary' name='buttonBuscar' id='buttonBuscar' type='submit' value='1' ><span class='glyphicon glyphicon-search'></span>&nbsp;".BTNBUSCR."</button>
              				&nbsp;&nbsp;
            		<button type='reset'  name='btnLimpiar' id='btnLimpiar'  class='btn btn-primary' style='width:120px;'><span class='glyphicon glyphicon-refresh'></span>&nbsp;".LIMPIAR."</td>
            </tr>
          </table>".$this->Procesando(1);
		  return $bufferFiltros;
    }
    
    function recuperaFiltros(){
        $this->cadenaFiltros="";
       
        if( trim($this->data['edoId'])== ""){
            $this->data['edoId']=1;
        }
        if(($this->data['idarea'] + 0) > 0){
            $this->cadenaFiltros.=" AND area_id = '".$this->data['idarea']."' ";
        }
        if(($this->data['idprograma'] + 0) > 0){
            $this->cadenaFiltros.=" AND programa_id = '".$this->data['idprograma']."' ";
        }
        if(($this->data['rol'] + 0) > 0){
            $this->cadenaFiltros.=" AND user_rol = '".$this->data['rol']."' ";
        }

        if( trim($this->data['edoId'])!= ""){
        		$this->cadenaFiltros.=" AND user_activo = '".$this->data['edoId']."' ";
        }
        if( trim($this->data['busqNombre'])!= ""){
          $this->cadenaFiltros.=" AND user_nombre  like  '".$this->data['busqNombre']."%' ";
        }        
        if(!empty($this->session['letra'])){
        	$this->cadenaFiltros.=" AND user_nombre like '".$this->session['letra']."%' ";
        }
    }
    
    function divFiltrosUsuarios(){
		$urlUser=$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1";
		$mens=$this->generaFiltros();
			$buf="<div class=\"tdcenter\"><center>
				<button type='button' class='btn btn-primary' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
						<span class='glyphicon glyphicon-download'></span>&nbsp;&nbsp;".FILTROUSUARIO."</button>
				<button type='button'  class='btn btn-success btn-sm'  onclick=\"location='".$urlUser."'\"><span class='glyphicon glyphicon-play-circle'></span>&nbsp;&nbsp;".ALTAUSUARIO."</button>

			</center></div>
		<div class='collapse' id='collapseExample'>
  		<div class='well'>".$mens."</div>
		</div>";
/*		$buf="<div class='panel-group' id='accordion' role='tablist' aria-multiselectable='true'>
  				<div class='panel panel-default'>
    				<div class='panel-heading' role='tab' id='headingOne'>
      					<h4 class='panel-title'>
        				<a data-toggle='collapse' data-parent='#accordion' href='#collapseOne' aria-expanded='true' aria-controls='collapseOne'>".FILTROUSUARIO."</a>
				      </h4>
    				</div>
    				<div id='collapseOne' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='headingOne'>
      					<div class='panel-body'>".$mens."</div>
    				</div>
  				</div>
      		</div>";*/
		return $buf;
	}
    
    function listado(){
    	$urlUser=$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1";
        $buffer="<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>
						<div class='tdleft titulosBlanco'><span class='titulosBlanco'>".LISTADODEUSUARIOS."</span></div>
					</div>
	  				<div class='panel-body'>".$this->divFiltrosUsuarios()."<br><center>".$this->regresaLetras()."</center>";
        $buf=$imgAct=$imgCon=$imgEli="";
        $arrayActivos   = $this->catalogoActivos();
        $arrayNivelUser = $this->catalogoNivelUser();
        $sql_count="SELECT ".$this->campo." FROM ".$this->tabla." WHERE 1 ".$this->cadenaFiltros.";";
        $res_count=$this->db->sql_query($sql_count) or die($sql_count);
        $no_registros=$this->db->sql_numrows($res_count);
        if($no_registros > 0)
        {
            $this->pages = new Paginador();
            $this->pages->items_total = $no_registros;
            $this->pages->mid_range = 25;
            $this->pages->paginate();
            $width=0;
      
            $sql="SELECT ".$this->campo.",user_nombre,user_login,user_rol,area_id,user_activo,user_email FROM ".$this->tabla."
                  WHERE 1 ".$this->cadenaFiltros." ORDER BY user_nivel DESC,".$this->campo." ASC limit ".$this->session['page'].",".$this->session['regs'].";";
            $res=$this->db->sql_query($sql) or die($sql);
            if($this->db->sql_numrows($res) > 0)
            {
                $buffer.="<div id='resultado' class='error'></div>
                    <table width='95%' class='table tablesorter table-bordered' align='center' id='MyTableActividades'>
                    <thead>
                        <tr class='alturaTableHeader'>
                            <td width='5%' class='tdcenter cabecerasTable'>".ID."</td>
                            <td width='37%' class='tdcenter cabecerasTable'>".NOMBREUSUARIO."</td>
                            <td width='25%' class='tdcenter cabecerasTable'>".EMAIL."</td>
                            <td width='10%' class='tdcenter cabecerasTable'>".NIVEL."</td>                            		
                            <td width='12%' class='tdcenter cabecerasTable' colspan='2'>".ACCIONES."</td>
                        </tr>
                    </thead>
                    <tbody>";
                $c=0;
                $consec=0;
                while(list($_id,$_nm,$_log,$_niv,$_area,$_act,$_email) = $this->db->sql_fetchrow($res))
                {
                    $imgAct="<a href='#'><img src='".$this->path."imagenes/iconos/pencil.png'    class='actualiza' id='a-".$this->catalogoId."-".$_id."' width='18' alt='".ACTUALIZAR."'  title='".ACTUALIZAR."'></a>";
                    $imgCon="<a href='#'><img src='".$this->path."imagenes/iconos/magnifier.png' class='consulta'  id='c-".$this->catalogoId."-".$_id."' width='18' alt='".CONSULTAR."'  title='".CONSULTAR."'></a>";
                    $imgEli="<a href='#'><img src='".$this->path."imagenes/iconos/delete.png'    class='elimina'   id='d-".$this->catalogoId."-".$_id."' width='18' alt='".ELIMINAR."'   title='".ELIMINAR."'></a>";
                    $imgRes="<a href='#'><img src='".$this->path."imagenes/iconos/deshacer.png'  class='deshacer'  id='r-".$this->catalogoId."-".$_id."' width='18' alt='".ELIMINAR."'   title='".ELIMINAR."'></a>";
                    $tmp=$imgRes;
                    if($_act==1)
                        $tmp=$imgEli;
                    $consec++;
                    $buffer.="<tr class=\"row".(($c++%2)+1)."\">
                        <td class='tdleft'>".$consec."</td>
                        <td class='tdleft'>".$_nm."</td>
                        <td class='tdleft'>".strtolower(trim($_email))."</td>
                         <td class='tdcenter'>".$arrayNivelUser[$_niv]."</td>
                        <td class='tdcenter' width='4%'>".$imgAct."</td>
                        <td class='tdcenter' width='4%'>".$tmp."</td>
                    </tr>";
                }
                $buffer.="</tbody><tfoot><tr><td colspan='6' class='tdcenter'><br>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page($this->session['regs'])."</td></tr></foot></table>";  
            }
        }
        else
        {
            $buffer.="<br><br><center><span class='tituloMediano'><b>".NORESULTADOS."</b></span></center>";
        }
        $buffer.="</div></div>";
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
	function idFolio(){
		return $this->idfolio;
	}
}
?>