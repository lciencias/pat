<?php
class LimiteCapturaAvances extends Comunes {
	var $db;
	var $data;
	var $session;
	var $server;
	var $path;
	var $pages;
	var $buffer;
		
	function __construct($db, $data, $session, $server, $path, $pages) {
		$this->db = $db;
		$this->data = $data;
		$this->path = $path;
		$this->server = $server;
		$this->session = $session;
		$this->pages = $pages;
		$this->buffer = "";
		
		$this->registraFechaLimite();
	}
	
	/**
	 * Metodo que se encarga de pintar la fecha limite para capturar proyectos
	 */
	function registraFechaLimite(){
		$this->buffer="
				<div class='panel panel-danger spancing'>
					<div class='panel-heading titulosBlanco'>
						<div class='tdleft titulosBlanco columna1' ><span class='titulosBlanco'>".BLOQUEOAVANCES."</span></div>
						<div class='tdright columna2'><br></div>
					</div>
	  				<div class='panel-body'>".$this->procesando(5)."
					<table align='center' border='0' class='table table-condensed'>
					<tr><td width='20%'>".ANO."</td><td>".$this->generaAnos()."</td></tr>
					<tr><td width='20%'>".TRIMESTRE."</td><td>".$this->regresaNombreTrimestre()."</td></tr>
					<tr><td>".FECHALIMITEINI."</td><td>
						<div class='input-group date' style='width:260px;'>
							<input type='text' class='form-control' placeholder='click para mostrar el calendario' id='fechaLimiteIni' name'fechaLimiteIni'/>
    						<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>							
						</div>						
					</td></tr>
					<tr><td>".FECHALIMITEFIN."</td><td>
						<div class='input-group date' style='width:260px;'>
							<input type='text' class='form-control' placeholder='click para mostrar el calendario' id='fechaLimiteFin' name'fechaLimiteFin'/>
    						<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>							
						</div>						
					</td></tr>					
					<tr><td colspan='2'>".$this->listadoAreas()."</td></tr>
					</table>			
					</div>
				</div>";
	}
	
	function listadoAreas(){
		$arrayAreas=$this->catalogoAreas();
		$buf="<div class='panel-group' id='accordion' role='tablist' aria-multiselectable='true'>";
		if(count($arrayAreas)>0){
			foreach($arrayAreas as $idArea => $nmArea){
				$buf.="<div class='panel panel-default'>
    						<div class='panel-heading' role='tab' id='heading".$idArea."'>
      							<h4 class='panel-title testimonials'>
      								<input type='checkbox' name='p-".$idArea."' id='p-".$idArea."' value='".$idArea."' class='FechaAreaA'>
      								&nbsp;&nbsp; 
        							<a ref='#' data-toggle='collapse' data-parent='#accordion' href='#collapse".$idArea."' aria-expanded='true' aria-controls='collapse' >
        							".$nmArea."</a>
        							&nbsp;&nbsp;<span id='resultado".$idArea."' class='error'></span>
        						</h4>
    						</div>
    						<div id='collapse".$idArea."' class='panel-collapse collapse out' role='tabpanel' aria-labelledby='heading".$idArea."'>
      							<div class='panel-body'>".$this->listadoProgramas($idArea)."</div>
				      		</div>
				      	</div>";
			}
		}
		$buf.="</div>";
		return $buf;
	}
	
	function listadoProgramas($idArea){
		$buf="";
		$arrayProgramas=$this->catalogoProgramasP($idArea);
		if(count($arrayProgramas)  >0){
			//$buf="<ul>";
			$buf="<table width='100%' class='table table-border'>
					<tr>
						<td width='52%'></td>
						<td class='tdcenter'>".TRIMESTRE1C."</td>
						<td class='tdcenter'>".TRIMESTRE2C."</td>
						<td class='tdcenter'>".TRIMESTRE3C."</td>
						<td class='tdcenter'>".TRIMESTRE4C."</td>
					</tr>";
			foreach($arrayProgramas as $idPrograma => $nmPrograma){
				$buf.="<tr>
							<td><input type='checkbox' name='p-".$idArea."-".$idPrograma."' id='p-".$idArea."-".$idPrograma."' value='".$idPrograma."' class='FechaProgramaA".$idArea." programasFechaA'>&nbsp;&nbsp;".$nmPrograma."</td>
							<td><span id='".$idArea."-".$idPrograma."-1'></span></td>
							<td><span id='".$idArea."-".$idPrograma."-2'></span></td>
							<td><span id='".$idArea."-".$idPrograma."-3'></span></td>
							<td><span id='".$idArea."-".$idPrograma."-4'></span></td>
					</tr>";
			}

			$buf.="</table>";
		}
		return $buf;
	}
	
	function obtenBuffer(){
		return $this->buffer;
	}
}