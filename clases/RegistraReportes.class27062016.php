<?php
class RegistraReportes extends Comunes {
    var $db;
    var $data;
    var $session;
    var $server;
    var $path;
    var $buffer;
    var $pages;
    var $noPasoFormato;
    var $id;
    var $opc;
    var $arrayDatos;
    var $arrayDatosMetas;
    var $arrayAvanceMetas;
    var $arrayEstatusVisibles;
    var $diasLimite;
    var $disabled;
    var $visible;
    function __construct($db, $data, $session, $server, $path, $pages) {
        $this->db = $db;
        $this->data = $data;
        $this->path = $path;
        $this->server = $server;
        $this->session = $session;
        $this->pages = $pages;
        $this->buffer = "";
        $this->noPasoFormato = 0;
        $this->opc = $this->data ['opc'];
        $this->noPasoFormato = $this->opc + 1;
        $this->diasLimite=$this->fechaLimiteCapturaAvances();
        $this->disabled="";
        $this->arrayDatosMetas = $this->arrayAvanceMetas = $this->arrayEstatusVisibles = array();
        $this->visible = false;
        if($this->diasLimite > 0){
            $this->disabled=" disabled ";
            $this->visible = true;
        }
        settype($this->opc,"integer");
        $this->opc = (int) $this->opc;
        switch ($this->opc) {
            case 0 :
                $this->listadoProyectos();
                break;
            case 9:
                $this->recuperaDatosMetas();
                $this->recuperaDatosAvances();
                $this->muestraFormularioMetas();
                break;
            default :
                $this->listadoProyectos ();
                break;
        }
    }
     
    function obtenFiltros(){
    $tmp="";
    $cols = " colspan='2' ";
    $width = "width='28%'";
    $width2 ="width='44%'";
    if($this->session['rol'] == 4){
        $width = "width='28%'";
        $width2 ="width='16%'";     
        $cols="";
        $tmp="<td class='tdleft' ".$width.">".$this->regresaNombreEstatus()."&nbsp;
              <img src='".$this->path."imagenes/iconos/help.png' id='a-18' class='help' alt='".AYUDA."' title='".AYUDA."'></td>";
    }
    $buf="<form action='aplicacion.php' method='post'>
            <input type='hidden' value='0' id='opc' name='opc'>
            <table class='tableSinbordes' align='center' width='100%'>
            <tr>
                <td class='tdleft' ".$width.">".$this->regresaNombreArea(1)."&nbsp;
                    <img src='".$this->path."imagenes/iconos/help.png' id='a-16' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
                <td class='tdleft' ".$width.">".$this->regresaNombrePrograma(1)."&nbsp;
                        <img src='".$this->path."imagenes/iconos/help.png' id='a-17' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
                <td class='tdleft' ".$width.">".$this->generaPonderacion()."&nbsp;
                        <img src='".$this->path."imagenes/iconos/help.png' id='a-21' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
                <td class='tdleft' ".$width2.">".$this->generaAnos()."&nbsp;
                        <img src='".$this->path."imagenes/iconos/help.png' id='a-19' class='help' alt='".AYUDA."' title='".AYUDA."'></td>
            </tr>         
            <tr>
                <td colspan='2' class='tdleft'><input type='text' class='form-control validatextonumero' placeholder='".BUSCAXPROYECTO."' name='busqNombre' id='busqNombre' maxlength='250' value='".$this->arrayDatos ['proyecto']."' style='width:410px;'>&nbsp;
                <img src='".$this->path."imagenes/iconos/help.png' id='a-20' class='help' alt='".AYUDA."' title='".AYUDA."'></td>                        
                ".$tmp."
                <td class='tdcenter' ".$width2." ".$cols.">
                <button type='submit' name='btnfiltros' id='btnfiltros' class='btn btn-primary' style='width:140px;'><span class='glyphicon glyphicon-search'></span>&nbsp;&nbsp;".CONSULTAR."</button>
                <button type='reset'  name='btnLimpiar' id='btnLimpiar' class='btn btn-primary' style='width:140px;'><span class='glyphicon glyphicon-refresh'></span>&nbsp;&nbsp;".LIMPIAR."</button></td>                               
            </tr>
             
        </table></form>";
    return $buf;    
    }
     
    function divFiltrosProyectos($opcion,$valor,$url,$urlRegreso){
        $mens.=$this->obtenFiltros();
        $tit=FILTROSBUSQUEDA;
        $botones="<button type='button' class='btn btn-primary btn-lg ninguno' id='cambiaFaseAvance'>
                        <span class='glyphicon glyphicon-envelope' aria-hidden='true'></span>&nbsp;&nbsp;".ENVIARCOORDINADOR."</button>
                      <button type='button' class='btn btn-primary btn-lg todos'><span class='glyphicon glyphicon-check' aria-hidden='true'></span>&nbsp;&nbsp;".TODOS."</button>
                      <button type='button' class='btn btn-primary btn-lg ningunos'><span class='glyphicon glyphicon-unchecked' aria-hidden='true'></span>&nbsp;&nbsp;".NINGUNO."</button>
                      <!--<button type='button' class='btn btn-success btn-lg ningunos'
                            data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPPROYECTO."'
                              onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1'\">
                            <span class='glyphicon glyphicon-play-circle'></span>&nbsp;&nbsp;".AGREGAPROYECTO."
                      </button>-->";
        $buf="<div class=\"tdcenter\"><center>
                <button type='button' class='btn btn-primary' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
                        <span class='glyphicon glyphicon-download'></span>&nbsp;&nbsp;".$tit."</button>&nbsp;".$botones."
            </center><br></div>
        <div class='collapse' id='collapseExample'>
        <div class='well'>".$mens."</div>
        </div>";      
        return $buf;
    }
    /**
     * Metodo que se encarga de generar el listado de proyectos
     */
     
     
    function listadoProyectos(){
        $class="";
        $this->arrayEstatusVisibles = array();
        if($this->session['rol'] == 1)
            $this->arrayEstatusVisibles = array(0,1,2,3,4,5,6,7,8,9,10);
        if ($this->session ['rol'] == 2)
            $this->arrayEstatusVisibles = array (0,1,2,3,4,5,6,7,8,9,10);
         
        if($this->session['rol'] == 3)
            $this->arrayEstatusVisibles = array(5,6,7,8,9,10);
        if($this->session['rol'] == 4)
            $this->arrayEstatusVisibles = array(1,2,3,4,5,6,7,8,9,10);
         
        $no_registros = $this->consultaNoProyectosAvances();
        $this->arrayNotificaciones = $this->notificaciones();
        $arrayDisabled = $this->recuperaPermisos(0,0);
        $trimestreId   = $this->obtenTrimestre($arrayDisabled);
        $noTri = $trimestreId;
        if($trimestreId == 1)
            $noTri ="";
         
        if($no_registros){
            $this->pages = new Paginador();
            $this->pages->items_total = $no_registros;
            $this->pages->mid_range = 25;
            $this->pages->paginate();
            $resultados   = $this->consultaProyectosAvances();
            $this->bufferExcel = $this->generaProyectosExcel();
        }
        $col=6;
        $this->buffer="
                <input type='hidden' name='trimestreId' id='trimestreId' value='".$trimestreId."'>
                <div class='panel panel-danger spancing'>
                    <div class='panel-heading'><span class='titulosBlanco'>".str_replace("#",$trimestreId,REPORTETRIMESTRAL)."</span></div>
                    <div class='panel-body'><center><span id='res'></span></center>".$this->divFiltrosProyectos(1,0,"","")."
                    <center>".$this->regresaLetras()."</center><br>";
            if(count($resultados) > 0){
                $arrayAreas = $this->catalogoAreas();
                $arrayOpera = $this->catalogoUnidadesOperativas();
                $this->buffer.="
                    <table width='95%' class='table tablesorter table-condensed' align='center' id='MyTableActividades'>
                    <thead><tr>
                    <td class='tdcenter fondotable' width='2%' >".NO."</td>
                        <td class='tdcenter fondotable' width='10%' >".AREA."</td>
                        <td class='tdcenter fondotable' width='36%' >".PROYECTOS."</td>
                        <td class='tdcenter fondotable' width='10%' >".METASREPORTE."</td>      
                        <td class='tdcenter fondotable' width='10%' >".FECHAALTA."</td>
                        <td class='tdcenter fondotable' width='12%' >".ESTATUSVALIDACION." Trimestre ".$trimestreId."</td>                              
                        <td clasS='tdcenter fondotable' width='12%' >".ENVIARCOORDINADOR."</td>";
            if($this->session['rol']>=3   ){
                $this->buffer.="<td clasS='tdcenter fondotable' width='10%'>".MARCAVALIDAR."</td>";
                $col++;
            }
                $this->buffer.="</tr></thead><tbody>";
                $contador=1;
                $varTemporal="";
                if($this->session['page']<=1)
                    $contadorRen= 1;
                else
                    $contadorRen=$this->session['page']+1;       
                     
                foreach($resultados as $id => $resul){
                    $rand = rand(1,99999999999999);
                    $class="";
                    if($contador % 2 == 0)
                        $class="active";
                    $campo = "estatus_avance_entrega".$noTri;
                    $idEstatus=$resul[$campo];                  
                    $varTemporal = $resul['id'];
                    if($idEstatus == 0){
                        $this->arrayNotificaciones[0]['act'] = 1;
                    }
                    $this->buffer.="
                        <tr class=' $class alturaComponentesA'>      
                            <td class='tdcenter'>".$contador."</td>
                            <td class='tdcenter'>
                                <a class='negro' href='#'
                                data-toggle='tooltip' data-placement='bottom'
                                title='".$arrayAreas[$resul['unidadResponsable_id']].".    -    Unidad Operativa: ".$arrayOpera[$resul['unidadOperativaId']]."'>
                                &nbsp;" . $resul['unidadResponsable_id']. "</a>
                            </td>                     
                            <td class='tdleft'>".$resul['proyecto']."</td>
                            <td class='tdcenter'>";
                            if($resul['noAcciones'] > 0)
                            {
                                $this->buffer.="
                                        <a class='negro' href='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=9&folio=".$varTemporal."'
                                        data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPMETAREP."' id='".$varTemporal."'>".$resul['noAcciones']."</a>";
                            }
                        $this->buffer.="</td>                          
                            <td class='tdcenter'>".substr($resul['fecha_alta'],0,10)."</td>
                            <td class='tdcenter' style='background-color:".$this->arrayNotificaciones[$idEstatus]['color'].";color:#000000;'>";
                        if($idEstatus  > 2){
                            $this->buffer.="<a class='negro visualizaComentarios' href='#' onclick='return false;' id='".$varTemporal."'
                                data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPMUESTRACOMENTARIOS."'>
                                &nbsp;".$this->arrayNotificaciones[$idEstatus]['nom']."
                                </a>";
                        }
                        else{
                            $this->buffer.=$this->arrayNotificaciones[$idEstatus]['nom'];
                        }
                        $this->buffer.="</td><td class='tdcenter'>";
                        //mostramos el checkbox rol 1
                        if(($this->session['rol'] == 1 ||  $this->session['rol'] == 2) && $idEstatus>0 && $idEstatus < 4){
                            //if( ($this->arrayNotificaciones[$idEstatus]['act'] == 1) && ($resul['userId'] == $this->session['userId']) ){
                            if( ($this->arrayNotificaciones[$idEstatus]['act'] == 1) ){
                                if($resul['noAcciones']>0)
                                    $this->buffer.="<input data-toggle='tooltip' data-placement='bottom' title='".DESMARCAVALIDAR."' type='checkbox' name='enviaId' id='".$varTemporal."' class='enviaIdAvance' value='".$resul['id']."'>";
                            }
                        }
                        //mostramos el checkbox rol 2
                        if($this->session['rol'] == 2 && $idEstatus >= 4 && $idEstatus < 7 && $idEstatus != 5){
                            if($resul['noAcciones']>0)
                                $this->buffer.="<input data-toggle='tooltip' data-placement='bottom' title='".DESMARCAVALIDAR."' type='checkbox' name='enviaId' id='".$varTemporal."' class='enviaIdAvance' value='".$resul['id']."'>";
                        }
                        //mostramos el checkbox rol 3
                        if($this->session['rol'] == 3 && $idEstatus >= 5 && $idEstatus < 10 && $idEstatus != 6 && $idEstatus != 8){
                            if($resul['noAcciones']>0)
                                if($idEstatus == 5)
                                    $this->buffer.=$this->pintaComentarioAvance($idEstatus,$resul['id'],$trimestreId);
                                else
                                    $this->buffer.="<input data-toggle='tooltip' data-placement='bottom' title='".DESMARCAVALIDAR."' type='checkbox' name='enviaId' id='".$varTemporal."' class='enviaIdAvance' value='".$resul['id']."'>";
                        }
                        //mostramos el checkbox rol 4
                        if($this->session['rol'] == 4 && $idEstatus >= 6 && $idEstatus <= 10 && $idEstatus != 7){
                            if($resul['noAcciones']>0)
                                if($idEstatus == 8)
                                    $this->buffer.=$this->pintaComentarioAvance($idEstatus,$resul['id'],$trimestreId);
                        }
                        $this->buffer.="</td>";
                         
                        if ($this->session['rol'] == 3){
                            $this->buffer.="<td class='tdcenter'>";
                            if($idEstatus == 6 ){
                                $this->buffer.="<a class='negro enviaEnlacePlaneacion' id='c-".$varTemporal."-0' href='#' data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPREGRESAPROYECTOPLANEACION."'><span class='glyphicon glyphicon-thumbs-down'></span>&nbsp;</a>";
                            }
                            $this->buffer.="</td>";
                        }
                        if ($this->session['rol'] >= 4){
                            $this->buffer.="<td class='tdcenter'>";
                            if($idEstatus == 9 ){
                                $this->buffer.="<a class='negro enviaCoordinador' id='p-".$varTemporal."-0' href='#' data-toggle='tooltip' data-placement='bottom' title='".TOOLTIPREGRESAPROYECTOCOORDINADOR."'><span class='glyphicon glyphicon-thumbs-down'></span>&nbsp;</a>";
                            }else{
                                if($idEstatus <10 )
                                    $this->buffer .= "<input data-toggle='tooltip' data-placement='bottom' title='" . DESMARCAVALIDAR . "' type='checkbox' name='enviaId' id='" . $varTemporal . "' class='enviaIdAvance' value='" . $resul ['id'] . "'>";
                            }
                            $this->buffer.="</td>";
                        }
                                                 
                    $this->buffer.="</tr>";
                    $contador++;
                }   
                $this->buffer.="<tbody><thead><tr>
                        <td colspan='".($col-1)."' class='tdcenter'>&nbsp;</td>
                        <td colspan='2' class='tdcenter'>".$this->Genera_Archivo($this->bufferExcel)."</td>
                        </tr></thead></table>
                        <table width='100%'><tr><td class='tdcenter'>".$this->pages->display_jump_menu()."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->pages->display_items_per_page()."</td></tr></table>";  
        }
        else{
            $this->buffer.="<table class='table table-condensed'><tr><td class='tdcenter'>".SINREGISTROS."</td></tr></table>";
        }
        $this->buffer.="</div></div>";
    }
     
     
    /** 
     * Metodo que se encarga de recuperar los datos de la actividad de la bd
     */
    function recuperaDatosActividad(){
        if($this->data['folio']!=""){
            $tmp=explode('-',$this->data['folio']);
            if( ($tmp[0] + 0)>0)
                $this->arrayDatos = $this->regresaDatosActividad($tmp[0]);
        }
    }
     
    /** 
     * Metodo que se encarga de recuperar los datos del proyecto de la bd
     */
    function recuperaDatos(){
        if($this->data['folio']!=""){
            $tmp=explode('-',$this->data['folio']);
            if( ($tmp[0] + 0)>0)
                $this->arrayDatos = $this->regresaDatosProyecto($tmp[0]); 
        }
         
    }
     
    function regresaPonderacion(){
        $valor=$this->arrayDatos['ponderacion'] + 0;
        $array=array();
        for($i=1; $i<=5;$i++){
            $tmp="";
            if($i == $valor)
                $tmp=" checked ";
            $array[$i]=$tmp;
        }
        return $array;
    }
 
    /**
     * Metodo para recuperar las metas
     */
    function recuperaDatosMetasId(){
        $folio=0;
        $tmp=array();
        if(trim($this->data['folio'])!= ""){
            $tmp=explode('-',$this->data['folio']);
            $folio= $tmp[0] + 0;
            if($folio > 0){
                $this->arrayDatosMetas=$this->regresaMetasActividad($folio);
            }
        }
    }
     
    function recuperaDatosAvances(){
        $folio=0;
        $tmp=array();
        if(trim($this->data['folio'])!= ""){
            $tmp=explode('-',$this->data['folio']);
            $folio= $tmp[0] + 0;
            if($folio > 0){
                $this->arrayAvanceMetas=$this->regresaAvances($folio);
            }
        }
    }
    /**
     * Metodo para recuperar las metas
     */
    function recuperaDatosMetas(){
        $folio=0;
        $tmp=array();
        if(trim($this->data['folio'])!= ""){
            $tmp=explode('-',$this->data['folio']);
            $folio= $tmp[0] + 0;
            if($folio > 0){
                $this->arrayDatos=$this->regresaMetas($folio);
            }
        }
    }
     
    /**
     * Metodo que se encarga  de registrar las metas
     */
    function muestraFormularioMetas(){
        $name = $titulo = $urlfolio = "";
        $folio = $random = 0;
        if($this->data['folio']  != ""){
            $tmp=explode('-',$this->data['folio']);
            if($this->opc == 9){
                $name="guardaAvance";
                $arrayProyecto = $this->regresaDatosProyecto($tmp[0]);
                $folio = $tmp[0];
                $urlfolio=$this->data['folio'];
                $titulo=CAPTURAREPORTEDEMETAS;
                $random=rand(1,10000000);
            }
            else{
                $name="actualizaAvance";
                $arrayProyecto = $this->regresaDatosProyecto($tmp[1]);
                $folio = $tmp[1];
                $urlfolio=$tmp[1]."-".$tmp[2];
                $random=$tmp[0];
            }
            $this->arrayNotificaciones = $this->notificaciones ();
            $titulo = $arrayProyecto['proyecto'];
            $resultados    = $this->consultaActividades($this->pages->limit);
            $arrayDisabled = $this->recuperaPermisos($arrayProyecto['unidadResponsable_id'],$arrayProyecto['programa_id']); 
            $trimestreId   = $this->obtenTrimestre($arrayDisabled);
            $arrayUnidadOperativas=$this->catalogoUnidadesOperativas($this->db);
			$campoTrimestre="estatus_avance_entrega";
			if($campoTrimestre > 1){
				$campoTrimestre="estatus_avance_entrega".$campoTrimestre;
			}
			
            $this->buffer="
                    <input type='hidden' name='noAtributos' id='noAtributos' value='".( count($resultados) + 0)."'>
                    <input type='hidden' name='valueId' id='valueId' value='".($this->arrayAvanceMetas['id'] + 0)."'>
                    <input type='hidden' name='folio' id='folio' value='".$folio."'>
                    <input type='hidden' name='random' id='random' value='".$random."'>
                    <input type='hidden' name='trimestreId' id='trimestreId' value='".$trimestreId."'>
                    <div class='panel panel-danger spancing'>
                    <div class='panel-heading titulosBlanco'>".$titulo."</div>
                    <div class='panel-body'>
                        <table align='center' border='0' class='table table-condensed'>
                        <tr class='active alturaComponentesA'>
                            <td class='tdleft' colspan='2' width='25%'>".PROYECTO."</td>
                            <td class='tdleft' colspan='2'>".$arrayProyecto['proyecto']."</td>
                        </tr>
                        <tr class='alturaComponentesA'>
                            <td class='tdleft' colspan='2' >".UNIDADOPERATIVA."</td>
                            <td class='tdleft' colspan='2'>".$arrayUnidadOperativas[$arrayProyecto['unidadOperativaId']]."</td>
                        </tr>
                        <tr class='alturaComponentesA'>
                            <td class='tdleft' colspan='2' >".TRIMESTRE."</td>
                            <td class='tdleft' colspan='2'>".$trimestreId."</td>
                        </tr>
                                     
                    </table>
                    <table width='100%' class='table'>
                    <tr>
                        <td class='tdcenter fondotable' rowspan='2' width='30%'>".ACTIVIDAD."</td>
                        <td colspan='2' class='tdcenter fondotable' width='10%'>".TRIMESTRE1C."</td>
                        <td colspan='2' class='tdcenter fondotable' width='10%'>".TRIMESTRE2C."</td>
                        <td colspan='2' class='tdcenter fondotable' width='10%'>".TRIMESTRE3C."</td>
                        <td colspan='2' class='tdcenter fondotable' width='10%'>".TRIMESTRE4C."</td>
                        <td colspan='2' class='tdcenter fondotable' width='10%'>".TOTAL."</td>
                        <td class='tdcenter fondotable' rowspan='2' width='14%'>".MEDIDA."</td>
                        <td class='tdcenter fondotable' rowspan='2' width=' 8%'>".ucfirst(substr(PONDERACION,0,4))."</td>
                        <td class='tdcenter fondotable' rowspan='2' width=' 8%'>".ucfirst(substr(TIPOACT,8,3))."</td>
                    </tr>
                    <tr>
                        <td class='tdcenter fondotable' width='5%'>".P."</td>
                        <td class='tdcenter fondotable' width='5%'>".R."</td>
                        <td class='tdcenter fondotable' width='5%'>".P."</td>
                        <td class='tdcenter fondotable' width='5%'>".R."</td>
                        <td class='tdcenter fondotable' width='5%'>".P."</td>
                        <td class='tdcenter fondotable' width='5%'>".R."</td>
                        <td class='tdcenter fondotable' width='5%'>".P."</td>
                        <td class='tdcenter fondotable' width='5%'>".R."</td>
                        <td class='tdcenter fondotable' width='5%'>".P."</td>
                        <td class='tdcenter fondotable' width='5%'>".R."</td>
                    </tr>";
                $contadorTab1=1;
                $contadorTab2=2;
                $contadorTab3=3;
                $contadorTab4=4;                
                $contadorRen = $total = $totales = $rtotal = $rtotales = 0;
                $disabled_t1 = $disabled_t2 = $disabled_t3 = $disabled_t4 = "";
                $fondo_t1 = 'background-color:#ffff99;';
                $fondo_t2 = 'background-color:#ffff99;';
                $fondo_t3 = 'background-color:#ffff99;';
                $fondo_t4 = 'background-color:#ffff99;';
                if($arrayDisabled[1]['dis'] + 0 == 0){
                    $disabled_t1=" readonly ='true' ";
                    $fondo_t1 = '';
                }
                if($arrayDisabled[2]['dis'] + 0 == 0){
                    $disabled_t2=" readonly ='true' ";
                    $fondo_t2 = '';
                }
                if($arrayDisabled[3]['dis'] + 0 == 0){
                    $disabled_t3=" readonly ='true' ";
                    $fondo_t3 = '';
                }
                if($arrayDisabled[4]['dis'] + 0 == 0){
                    $disabled_t4=" readonly ='true' ";
                    $fondo_t4 = '';
                }
                $arrayEditable=array(1,3,4,6,7,8,9);
                 
                foreach($resultados as $id => $resul){
                    $rand = rand(1,99999999999999);
                    $class="";
                    if($contador % 2 == 0)
                        $class="active";
                    $campo="estatus_avance_entrega_t".$trimestreId;                 
                    $idEstatusActividad =$resul[$campo] ;
                    $varTemporalId = $resul['id']."-".$arrayProyecto['id']."-".$trimestreId;
                    $varTemporalIdE = $resul ['id'] . "-" . $arrayProyecto['id']."-".$trimestreId."-".$idEstatusActividad;                      
                    $idact= $resul['id'];
                    $totales = $totales + $this->arrayDatos[$idact][5] + 0;
                    $tmp="";
                     
                    if($resul['tipo_actividad_id'] != 0){
                        $this->buffer.="
                            <tr class=' $class alturaComponentesA'>
                            <td class='tdleft' rowspan='2'>".$resul['actividad']."</td>
                            <td class='tdcenter numMetas form-control'>".($this->arrayDatos[$idact][1] + 0)."</td>
                            <td class='tdcenter'>
                            <input type='text' class='form-control validanumsMA' tabindex='".$contadorTab1."'
                            id='r-".$contadorRen."-".$resul['id']."-".$contadorTab1."-1-".$resul['tipo_actividad_id']."' maxlength='10' value='".($this->arrayAvanceMetas[$idact][1] + 0)."' style='width:35px;$fondo_t1' ".$disabled_t1.">
                            </td>
                            <td class='tdcenter numMetas form-control'>".($this->arrayDatos[$idact][2] + 0)."</td>
                            <td class='tdcenter'>
                            <input type='text' class='form-control validanumsMA'  ".$this->disabled." tabindex='".$contadorTab2."'
                            id='r-".$contadorRen."-".$resul['id']."-".$contadorTab2."-2-".$resul['tipo_actividad_id']."' maxlength='10' value='".($this->arrayAvanceMetas[$idact][2] + 0)."' style='width:35px;$fondo_t2' ".$disabled_t2."> 
                            </td>
                            <td class='tdcenter numMetas form-control'>".($this->arrayDatos[$idact][3] + 0)."</td>
                            <td class='tdcenter'>
                            <input type='text' class='form-control validanumsMA'  ".$this->disabled." tabindex='".$contadorTab3."'
                            id='r-".$contadorRen."-".$resul['id']."-".$contadorTab3."-3-".$resul['tipo_actividad_id']."' maxlength='10' value='".($this->arrayAvanceMetas[$idact][3] + 0)."' style='width:35px;$fondo_t3' ".$disabled_t3.">
                            </td>
                            <td class='tdcenter numMetas form-control'>".($this->arrayDatos[$idact][4] + 0)."</td>
                            <td class='tdcenter'>
                            <input type='text' class='form-control validanumsMA'  ".$this->disabled."  tabindex='".$contadorTab4."'
                            id='r-".$contadorRen."-".$resul['id']."-".$contadorTab4."-4-".$resul['tipo_actividad_id']."' maxlength='10' value='".($this->arrayAvanceMetas[$idact][4] + 0)."' style='width:35px;$fondo_t4'  ".$disabled_t4.">
                            </td>
                            <td class='tdcenter' rowspan='2'>
                                <span id='total".$contadorRen."' class='totales'>".number_format(($this->arrayDatos[$idact][1] + $this->arrayDatos[$idact][2] + $this->arrayDatos[$idact][3] + $this->arrayDatos[$idact][4] + 0),0,',','.')."</span>
                            </td>
                            <td class='tdcenter' rowspan='2'>
                                <span id='rtotal".$contadorRen."' class='totales'>".number_format($this->arrayAvanceMetas[$idact][1] + $this->arrayAvanceMetas[$idact][2] + $this->arrayAvanceMetas[$idact][3] +$this->arrayAvanceMetas[$idact][4],0,',','.')."</span>
                            </td>
                            <td class='tdcenter'>".$resul['medida']."</td>
                            <td class='tdcenter'>".$resul['ponderacion']."</td>
                            <td class='tdcenter'>".$resul['tipo_actividad_id']."</td>
                        </tr>
                        <tr>
                            <td colspan='8' class='tdleft $class'><span id='comment-".$resul['id']."'>".$this->regresaUltimoComentario($arrayProyecto['id'],$resul['id'])."<br>".$this->regresaNoAdjuntos($arrayProyecto['id'],$resul['id'])."</span><span id='avance'></span></td>";
                        $rtotales = $rtotales + $this->arrayAvanceMetas[$idact][5];                        
                            if($this->session['rol'] == 1 || $this->session['rol'] >=3){
                                $this->buffer.="<td class='tdcenter $class' colspan='3'>";                            
                                if($this->session['rol'] == 1 || $this->session['rol'] >=4){
                                    $classb="mComentariosConsulta";
                                    if(in_array($arrayProyecto[$campoTrimestre],$arrayEditable)){
                                        $classb="mComentarios";
                                    }
                                    //if($this->opc == 9){
                                    	$this->buffer.="<button type='button' class='btn btn-success btn-sm $classb' id='".$resul['proyecto_id']."-".$resul['id']."-".$trimestreId."'><span class='glyphicon glyphicon-pencil'></span>&nbsp;&nbsp;Comentarios</button>";
                                    //}
                                }
                                if($this->session['rol'] >=3){
                                    $this->buffer.="<button type='button' class='btn btn-warning btn-sm masFile' id='m-".$resul['proyecto_id']."-".$resul['id']."-".$trimestreId."'>&nbsp;&nbsp;M&aacute;s</button>";
                                }
                                $this->buffer.="</td>";
                            }
                            if($this->session['rol'] == 2){
                                $this->buffer.="
                                        <td class='tdcenter $class'><button type='button' class='btn btn-success btn-sm mComentariosConsulta' id='".$resul['proyecto_id']."-".$resul['id']."-".$trimestreId."'><span class='glyphicon glyphicon-pencil'></span>&nbsp;&nbsp;Comentarios</button></td>
                                        <td class='tdcenter $class'>
                                            <button type='button' class='btn btn-default aprobadosavances'  data-toggle='tooltip' data-placement='bottom'
                                                title='".PROYECTOAPROBADO."' id='aaa-".$varTemporalIdE."'><span class='glyphicon glyphicon-ok'></span>
                                            </button>
                                        </td>
                                        <td class='tdcenter $class'>
                                            <button type='button' class='btn btn-default noaprobadosavances' data-toggle='tooltip' data-placement='bottom'
                                            title='".PROYECTONOAPROBADO."' id='ann-".$varTemporalIdE."'><span class='glyphicon glyphicon-remove'></span>
                                        </button></td>";
                            }
                             
                             
                        $this->buffer.="</tr><tr><td colspan='11'>&nbsp;</td>";
                        if( ($idEstatusActividad!= 3) && ($idEstatusActividad!= 6) && ($idEstatusActividad!= 9)){
                            $this->buffer .= "<td class='tdleft' colspan='3'  id='v-".$varTemporalIdE."' style='background-color:" . $this->arrayNotificaciones [$idEstatusActividad] ['color'] . ";color:#000000;'>" . $this->arrayNotificaciones [$idEstatusActividad] ['nom'] . "</td>";
                        }
                        else{
                            $this->buffer .= "<td class='tdleft verComentariosNoAprobados' colspan='3' id='v-".$varTemporalIdE."' style='cursor:pointer;background-color:" . $this->arrayNotificaciones [$idEstatusActividad] ['color'] . ";color:#000000;' data-toggle='tooltip' data-placement='bottom' title='" . TOOLTIPMUESTRACOMENTARIOS . "'>" . $this->arrayNotificaciones [$idEstatusActividad] ['nom'] . "</td>";
                        }
                        $this->buffer .= "</tr>";
                        $contadorTab1 = $contadorTab1 + 4;
                        $contadorTab2 = $contadorTab2 + 4;
                        $contadorTab3 = $contadorTab3 + 4;
                        $contadorTab4 = $contadorTab4 + 4;
                        $contadorRen++;
                        $contador++;
                    }
                }
                $contadorTab4++;
                 
                /*$this->buffer.="<tr><td colspan='8'></td>
                        <td class='tdleft'>Total:</td><td class='tdcenter'><span id='totales' class='totales'>".($totales  + 0)."</span></td>
                        <td class='tdcenter'><span id='rtotales' class='totales'>".($rtotales  + 0)."</span></td>
                        <td colspan='3'>&nbsp;</td></tr></table>*/
                $this->buffer.="</table>
                    </div>
                    <div class=\"central\"><br>";               
                if( (in_array($arrayProyecto[$campoTrimestre],$arrayEditable)) or ($this->session['rol']<=2 or $this->session['rol']<=5) ){
                 
                    $this->buffer.="<button type='button' tabindex='".$contadorTab4."'  class='btn btn-success btn-sm' id='".$name."' name='".$name."'><span class='glyphicon glyphicon-floppy-saved'></span>&nbsp;".AGREGAREPORTEMETA."</button>&nbsp;&nbsp;";
                }
                $this->buffer.="<button type='button' class='btn btn-primary btn-sm'
                 onclick=\"location='".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=0'\">".REGRESA."</button>
                </div>".$this->procesando(4)."<br></div>";
        }else{
            header("Location: ".$this->path."aplicacion.php?aplicacion=".$this->session ['aplicacion']."&apli_com=".$this->session ['apli_com']."&opc=1");
        }       
    }
 
     
    /**
     * Metodo que regresa la informacion pintada en el navegador
     *
     * @return string variable de instancia $this->buffer
     */
    function obtenBuffer() {
        return $this->buffer;
    }
    function obtenNoPasoFormato() {
        return $this->noPasoFormato;
    }
}