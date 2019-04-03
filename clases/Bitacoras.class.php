<?php
class Bitacoras extends Comunes{
	var $db;
	var $data;
	var $session;
	var $server;
	var $path;
	var $buffer;
	var $pages;
	var $filtro;
	var $tabla;
	var $results;
	var $headers;
	var $widths;
	var $regs;
	var $cadena_error;
	
	function __construct($db, $data, $session, $server, $path, $pages) {
		$this->db      = $db;
		$this->data    = $data;
		$this->path    = $path;
		$this->server  = $server;
		$this->session = $session;
		$this->pages   = $pages;
		$this->buffer  = $this->filtro = $this->tabla = "";
		$this->cadena_error="";
		$this->results = $this->headers = array();
		$this->regs    = $this->widths = 0;
		if($this->data['opc'] > 0){
			$this->construyeFiltros();
			$this->seleccionaTabla();
			$this->generaFiltros();
			$this->ejecutaConsulta();
			$this->generaSalida();			
		}else{
			$this->generaFiltros();
		}
	}
	
	function seleccionaTabla(){
		switch ($this->data['tipo_log']){
			case 1:
				$this->tabla = " log_accesos ";
				$this->headers = array('Id','Usuario','Estatus','Ip','Fecha');
				$this->widths  = array(5,45,20,15,15);
				break;
			case 2:				
				$this->tabla = " log_modulos ";
				$this->headers = array('Id','Usuario','Menu','Submenu','Ip','Fecha');
				$this->widths  = array(5,25,20,20,15,15);
				break;
			case 3:
				$this->tabla = " log_catalogo_areas_bloqueos ";
				$this->headers = array('Id','Usuario','Area','Programa','Ano','Estatus','Ip','Fecha');
				$this->widths  = array(5,20,20,20,5,10,10,15);
				break;
			case 4:
				$this->tabla = " log_catalogos ";
				$this->headers = array('Id','Usuario','Cat&aacute;logo','Folio','Estatus','Ip','Fecha');
				$this->widths  = array(5,20,20,10,20,10,15);
				break;
			case 5:
				$this->tabla = " log_proyectos ";
				$this->headers = array('Id','Usuario','Proyecto','Estatus','Ip','Fecha');
				$this->widths  = array(5,20,20,20,20,15);
				break;
			case 6:
				$this->tabla = " log_proyectos ";
				$this->headers = array('Id','Usuario','Actividades','Estatus','Ip','Fecha');
				$this->widths  = array(5,20,20,20,20,15);
				break;
			case 7:
				$this->tabla   = " log_proyectos ";
				$this->widths  = array(5,20,20,20,20,15);
				$this->headers = array('Id','Usuario','Metas','Estatus','Ip','Fecha');
				break;
			default:
				$this->tabla = " log_accesos ";
				$this->headers = array('Id','Usuario','Estatus','Ip','Fecha');
				break;				
		}
	}
	function generaFiltros(){
		$this->buffer="
				<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>
						<div class='tdleft titulosBlanco' ><span class='titulosBlanco'>".CONSULTABITACORAS."</span></div>						
					</div>
	  				<div class='panel-body'>
						<table width='98%' align='center'>
							<tr>
								<td width='12%'>".TIPOBITACORA."</td>
								<td width='23%'>".$this->generaTiposLogs($this->data['tipo_log'])."</td>
								<td width=' 9%'>".FECHALIMITEINIB."</td>
								<td width='24%'><div class='input-group date' style='width:150px;'>
									<input type='text' class='form-control' placeholder='Calendario' id='fechaLimiteIni' name='fechaLimiteIni' value='".$this->data['fechaLimiteIni']."' style='width:100px;'/>
    								<span class='input-group-addon' style='width:5px;'><span class='glyphicon glyphicon-calendar'></span></span>
									</div>							
								</td>
								<td width='9%'>".FECHALIMITEFINB."</td>
								<td width='23%'><div class='input-group date' style='width:150px;'>
									<input type='text' class='form-control' placeholder='Calendario' id='fechaLimiteFin'  name='fechaLimiteFin' value='".$this->data['fechaLimiteFin']."' style='width:100px;'/>
    								<span class='input-group-addon' style='width:5px;'><span class='glyphicon glyphicon-calendar'></span></span>
									</div>
								</td>		
							</tr>
								<td>".USUARIO."</td>
								<td colspan='4'>".$this->generaUsuarios($this->data['user_id'])."</td>
								<td colspan='2' class='tdcenter' width='8%' align='middle'>
									<button name='consultarBitacora' id='consultarBitacora' class='btn btn-primary'>".CONSULTARBITACORA."</button>
									<input type='hidden' name='opc' id='opc' value='1'>
								</td>								
							</tr>
							<tr><td colspan='6' class='tdcenter'><span id='error' class='error'></span></td></tr>
						</table>			
					</div>
	  			</div>";
	}
	

	function cambiaFormato($fecha){
		return substr($fecha,6,4)."-".substr($fecha,3,2)."-".substr($fecha,0,2);
	}
	
	function construyeFiltros(){
		$this->filtro="";
		if(trim($this->data['tipo_log'])== "")
			$this->data['tipo_log']=1;
		
		if( (trim($this->data['fechaLimiteIni']) != "") && (trim($this->data['fechaLimiteFin']) != "") ){
			$ini = $this->cambiaFormato($this->data['fechaLimiteIni']);
			$fin = $this->cambiaFormato($this->data['fechaLimiteFin']);
			$this->filtro.=" AND a.timestamp BETWEEN '".$ini." 00:00:01' AND '".$fin." 23:59:59' ";
		}
		if( (trim($this->data['fechaLimiteIni']) != "") && (trim($this->data['fechaLimiteFin']) == "") ){
			$ini = $this->cambiaFormato($this->data['fechaLimiteIni']);
			$this->filtro.=" AND a.timestamp >= '".$ini." 00:00:01' ";
		}
		if( (trim($this->data['fechaLimiteIni']) == "") && (trim($this->data['fechaLimiteFin']) != "") ){
			$fin = $this->cambiaFormato($this->data['fechaLimiteFin']);
			$this->filtro.=" AND a.timestamp <= '".$fni." 23:59:59' ";
		}
		if( (trim($this->data['fechaLimiteIni']) == "") && (trim($this->data['fechaLimiteFin']) == "") ){
			$ini = date("Y-m-d");
			$fin = date("Y-m-d");
			$this->filtro.=" AND a.timestamp BETWEEN '".$ini." 00:00:01' AND '".$fin." 23:59:59' ";
		}		
		if($this->data['user_id'] > 0 ){
			$this->filtro.= " AND a.user_id = '".$this->data['user_id']."' ";
		}
	}
	
	function ejecutaConsulta(){
		$this->results = array();
		if($this->data['tipo_log'] == 5){
			$this->filtro.= " AND a.actividad_id = 0 AND meta_id = 0 ";
		}
		if($this->data['tipo_log'] == 6){
			$this->filtro.= " AND a.actividad_id > 0 AND meta_id = 0 ";
		}
		if($this->data['tipo_log'] == 7){
			$this->filtro.= " AND a.actividad_id > 0 AND meta_id > 0 ";
		}		
		$sqlc= "SELECT COUNT(*) FROM ".$this->tabla." as a WHERE 1 ".$this->filtro.";";
		$resc= $this->db->sql_query($sqlc) or die("eroro:  ".$sqlc);
		list($this->regs) = $this->db->sql_fetchrow($resc);
		$this->regs = $this->regs + 0;
		if( $this->regs >0){			
			$this->pages = new Paginador ();
			$this->pages->items_total = $this->regs ;
			$this->pages->mid_range = 25;
			$this->pages->paginate ();	
			$sql = " SELECT * FROM ".$this->tabla." as a WHERE 1 ".$this->filtro." ORDER BY a.timestamp limit ".$this->session['page'].",".$this->session['regs'].";";
			$res = $this->db->sql_query($sql) or die("eroro:  ".$sql);
			if($this->db->sql_numrows($res)>0){
				while($array = $this->db->sql_fetchrow($res)){
					$this->results[]=$array;
				}
			}
		}
	}
	function catalogos($opc){
		switch ($opc){
			case 1:
				$sql= "SELECT user_id,user_nombre FROM cat_usuarios ORDER BY user_id;";
				break;
			case 2:
				$sql= "SELECT menu_id,nombre FROM cat_menu ORDER BY menu_id;";
				break;
			case 3:
				$sql= "SELECT submenu_id,nombre FROM cat_submenu ORDER BY submenu_id;";
				break;
			case 4:
				$sql= "SELECT area_id,nombre FROM cat_areas ORDER BY area_id;";
				break;
			case 5:
				$sql= "SELECT programa_id,nombre FROM cat_programas ORDER BY programa_id;";
				break;
			case 6:
				$sql= "SELECT id,proyecto FROM proyectos_acciones ORDER BY id;";
				break;				
			case 7:
				$sql= "SELECT id,actividad FROM proyectos_actividades ORDER BY id;";
				break;
				
			default:
				$sql= "SELECT user_id,user_nombre FROM cat_usuarios ORDER BY user_id;";
				break;
		}
		$array=array();		
		$res = $this->db->sql_query($sql) or die($this->cadena_error);
		if($this->db->sql_numrows($res)>0){
			while(list($id,$nm) = $this->db->sql_fetchrow($res)){
				$array[$id]=$nm;
			}
		}
		return $array;
	}
	
	function generaSalida(){
		$catUsuarios = $this->catalogos(1);
		if($this->data['tipo_log'] == 1){			
			$catAccesos  = array(0 => 'Sin Acceso', 1 => 'Login',2 => 'Logout');
		}
		if($this->data['tipo_log'] == 2){		
			$catMenus    = $this->catalogos(2);
			$catSUbMenus = $this->catalogos(3);
		}
		if($this->data['tipo_log'] == 3){
			$catAreas    = $this->catalogos(4);
			$catProgramas= $this->catalogos(5);
			$catAccesos  = array(0 => 'Sin Movimiento', 1 => 'Bloqueo',2 => 'Desbloqueo');
		}
		if($this->data['tipo_log'] == 4){
			$catAccesos  = array(0 => 'Sin Movimiento', 1 => 'Alta',2 => 'Actualiza',3 => 'Eliminar',4 => 'Restaurar');
		}
		if($this->data['tipo_log'] == 5){
			$catProyectos= $this->catalogos(6);
			$catAccesos = array(1 => 'Alta Proyecto', 2 => 'Alta Actividad',3 => 'Alta Meta',4 => 'Alta Avance',
							    5 => 'ActualizaProyecto', 6 => 'ActualizaActividad', 7 => 'ActualizaMeta', 8 => 'ActualizaAvance',
								9 => 'EliminaProyecto',10 => 'EliminaActividad', 11 => 'RestauraProyecto',12 => 'RestauraActividad');
				
		}
		if($this->data['tipo_log'] == 6 || $this->data['tipo_log'] == 7 ){
			$catActividades= $this->catalogos(7);
			$catAccesos = array(1 => 'Alta Proyecto', 2 => 'Alta Actividad',3 => 'Alta Meta',4 => 'Alta Avance',
					5 => 'ActualizaProyecto', 6 => 'ActualizaActividad', 7 => 'ActualizaMeta', 8 => 'ActualizaAvance',
					9 => 'EliminaProyecto',10 => 'EliminaActividad', 11 => 'RestauraProyecto',12 => 'RestauraActividad');
		
		}
		
		$this->buffer.="<br><table align='center' border='0' class='table table-condensed'>";
		$col=0;
		if($this->regs > 0){
			$this->buffer.="<tr>";
			foreach($this->headers as $header){
				$this->buffer.="<td  class='tdcenter fondotable' width='".$this->widths[$col]."%'>".$header."</td>";
				$col++;
			}
			$this->buffer.="</tr>";
			foreach($this->results as $ind => $tmp){
					switch($this->data['tipo_log']){
						case 1:
							$this->buffer.="<tr><td>".$tmp['id']."</td><td>".$catUsuarios[$tmp['user_id']]."</td><td>".$catAccesos[$tmp['estatus']]."</td><td>".$tmp['ip']."</td><td>".$tmp['timestamp']."</td></tr>";
							break;
						case 2:
							$this->buffer.="<tr><td>".$tmp['id']."</td><td>".$catUsuarios[$tmp['user_id']]."</td><td>".$catMenus[$tmp['aplicacion']]."</td><td>".$catSUbMenus[$tmp['apli_com']]."</td><td>".$tmp['ip']."</td><td>".$tmp['timestamp']."</td></tr>";
							break;
						case 3:
							$this->buffer.="<tr><td>".$tmp['id']."</td><td>".$catUsuarios[$tmp['user_id']]."</td><td>".$catAreas[$tmp['area_id']]."</td><td>".$catProgramas[$tmp['programa_id']]."</td><td>".$tmp['ano_id']."</td><td>".$catAccesos[$tmp['estatus']]."</td><td>".$tmp['ip']."</td><td>".$tmp['timestamp']."</td></tr>";
							break;
						case 4:
							$this->buffer.="<tr><td>".$tmp['id']."</td><td>".$catUsuarios[$tmp['user_id']]."</td><td>".$tmp['catalogo']."</td><td>".$tmp['folio']."</td><td>".$catAccesos[$tmp['estatus']]."</td><td>".$tmp['ip']."</td><td>".$tmp['timestamp']."</td></tr>";
							break;
						case 5:
							$this->buffer.="<tr><td>".$tmp['id']."</td><td>".$catUsuarios[$tmp['user_id']]."</td><td>".$catProyectos[$tmp['proyecto_id']]."</td><td>".$catAccesos[$tmp['estatus']]."</td><td>".$tmp['ip']."</td><td>".$tmp['timestamp']."</td></tr>";
							break;
						case 6:
							$this->buffer.="<tr><td>".$tmp['id']."</td><td>".$catUsuarios[$tmp['user_id']]."</td><td>".$catActividades[$tmp['actividad_id']]."</td><td>".$catAccesos[$tmp['estatus']]."</td><td>".$tmp['ip']."</td><td>".$tmp['timestamp']."</td></tr>";
							break;
						case 7:
							$this->buffer.="<tr><td>".$tmp['id']."</td><td>".$catUsuarios[$tmp['user_id']]."</td><td>".$catActividades[$tmp['meta_id']]."</td><td>".$catAccesos[$tmp['estatus']]."</td><td>".$tmp['ip']."</td><td>".$tmp['timestamp']."</td></tr>";
							break;									
					}				
			}
			$this->buffer.="<tr>
								<td colspan='2'>Total de registros: ".$this->regs."</td>
								<td colspan='".(count($this->headers) - 4)."'>".$this->pages->display_jump_menu()."</td>
								<td colspan='2'>".$this->pages->display_items_per_page($this->session['regs'])."</td>
							</tr>";
		}
		else{
			$this->buffer.="<tr><td class='tdcenter'>".NORESULTADOS."</td></tr>";
		}
		$this->buffer.="</table>";
	}
	function obtenBuffer() {
		return $this->buffer;
	}
}
?>