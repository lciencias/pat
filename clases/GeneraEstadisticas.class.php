<?php
class GeneraEstadisticas extends ComunesEstadisticas{
    var $db;
    var $data;
    var $session;
    var $path;
    var $filtro;
    var $buffer;
    var $bufferXml;
    var $tabla;
    var $cadena;
    var $arrayDatos;
    var $breadcrumb;
    var $titulo;
    var $xml;
    var $xmlPor;
    var $cien;
    var $contadorProcesadas; 
    var $contadorNoProcesadas;
    function __construct($db,$data,$session,$path){
        $this->db      = $db;
        $this->data    = $data;
        $this->session = $session;
        $this->path    = $path;
        $this->cien    = 100;
        $this->filtro  = "";
        $this->buffer  = "";
        $this->bufferXml = "";
        $this->tabla   = "";
        $this->titulo  = "";
        $this->xml     = "";
        $this->xmlPor  = "";
        $this->contadorProcesadas = $this->contadorNoProcesadas = 0;
        $this->breadcrumb = "<a href='".$this->path."index.php'>  / Inicio </a> ";
        $this->arrayDatos = array();
        $this->cadena  = "<script>location.href='".$this->path."'</script>";
        $this->numericos();
        if((int) $this->data['anoId'] == 0){
            $this->data['anoId'] = date('Y');
        }
        if((int) $this->data['trimestreId'] == 0){
            $this->data['trimestreId'] = 1;
        }
        if((int) $this->data['trimestreId'] > 4){
            $this->data['trimestreId'] = 1;
        }
        if((int) $this->data['tipoId'] == 0){
            $this->data['tipoId'] = 2;
        }
        if((int) $this->data['tipoId'] > 2){
            $this->data['tipoId'] = 2;
        }       
         
        $this->recuperaTabla();
        $this->actualizaTipo2();
        if(trim($this->tabla) != ""){
            if((int) $this->data['tipoId'] <=1){
                $this->catalogoA = $this->catEjes();
                $this->catalogoB = $this->catProgramas();
                if( ((int)$this->data['idEje'] == 0) && ((int)$this->data['idPrograma'] == 0) && ((int)$this->data['idProyecto'] == 0) ){
                    $this->generaFiltro();
                    $this->generaTablaEje();
                }
                if( ((int)$this->data['idEje'] > 0) && ((int)$this->data['idPrograma'] == 0) && ((int)$this->data['idProyecto'] == 0) ){
                    $this->generaFiltro();
                    $this->generaTablaEjePrograma();
                }
                if( ((int)$this->data['idEje'] > 0) && ((int)$this->data['idPrograma'] > 0) && ((int)$this->data['idProyecto'] == 0) ){
                    $this->generaFiltro();
                    $this->generaTablaEjeProgramaProyecto();
                }
                if( ((int)$this->data['idEje'] > 0) && ((int)$this->data['idPrograma'] > 0) && ((int)$this->data['idProyecto'] > 0) ){
                    $this->catalogoD = $this->catProyectos();
                    $this->generaFiltro();
                    $this->generaTablaActividades();
                }
                     
            }else{
                $this->catalogoC = $this->catAreas();
                if( ((int)$this->data['idArea'] == 0)  && ((int)$this->data['idProyecto'] == 0) ){
                    $this->generaFiltro();
                    $this->generaTablaArea();
                }
                if( ((int)$this->data['idArea'] > 0)  && ((int)$this->data['idProyecto'] == 0) ){
                    $this->generaFiltro();
                    $this->generaTablaAreaProyecto();
                }
                if( ((int)$this->data['idArea'] > 0)  && ((int)$this->data['idProyecto'] > 0) ){
                    $this->catalogoD = $this->catProyectos();
                    $this->generaFiltro();
                    $this->generaTablaActividades();
                }
            }
        }else{
            $this->buffer = "Sitio en mantenimiento, en breve regresaremos";
        }
    }
     
    function numericos(){
        if( !is_numeric($this->data['tablaId'])){
            $this->recuperaDefault();
        }
 
        if( !is_numeric($this->data['anoId'])){
            $this->data['anoId'] = date('Y');
        }
         
        if( !is_numeric($this->data['trimestreId'])){
            $this->data['trimestreId'] = 0;
        }
         
        if( !is_numeric($this->data['idEje'])){
            $this->data['idEje']=0;
        }
         
        if( !is_numeric($this->data['idPrograma'])){
            $this->data['idPrograma']=0;
        }
         
        if( !is_numeric($this->data['idArea'])){
            $this->data['idArea'] = 0;
        }
         
        if( !is_numeric($this->data['idProyecto'])){
            $this->data['idProyecto'] = 0;
        }   
    }
    function generaTablaActividades(){
        $this->contadorProcesadas = $this->contadorNoProcesadas = 0;
        $contador = $p5 = $p6 = $pn = 0;
        $contadorPro = $totalAvance = 0;
        $sumP = $sumR = $sumAvanPon = 0;
        $this->titulo  = "<b>Actividades realizadas</b><br>";
        $this->arrayDatos = $arrayAct = $arrayTotales  = array();
        $sql = "SELECT actividadId,actividad,ponderacionActividad,tipo_actividad_id,medida,
                trimestre1,trimestre2,trimestre3,trimestre4,
                Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
                FROM ".$this->tabla." WHERE eje_id >0 and programa_id > 0 and id > 0 and actividadId > 0 ".$this->filtro." ORDER BY actividadId;";
        $res = $this->db->sql_query($sql) or die($this->cadena);
        if($this->db->sql_numrows($res)>0){
            while(list($id,$act,$pon,$tipoAct,$medida,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
                if(!in_array($id,$arrayAct)){
                    $arrayAct[] = $id;
                    switch($this->data['trimestreId']){
                        case 1:
                            $t5 = $t1 + 0; 
                            $a5 = $a1 + 0;
                            break;
                        case 2:
                            $t5 = $t1 + $t2 + 0; 
                            $a5 = $a1 + $a2 + 0;
                            break;
                        case 3:
                            $t5 = $t1 + $t2 + $t3 + 0; 
                            $a5 = $a1 + $a2 + $a3 + 0;
                            break;
                        case 4:
                            $t5 = $t1 + $t2 + $t3 + $t4 + 0; 
                            $a5 = $a1 + $a2 + $a3 + $a4 + 0;
                            break;
                    }
                    $t6 = $t1 + $t2 + $t3 + $t4 + 0;
                    $a6 = $a1 + $a2 + $a3 + $a4 + 0;
                    $p5 = $p5 + $pon + 0;                    
                    $promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$pon);
                    if($promedio > -1){
                        $this->contadorProcesadas++;
                        $p6 = $p6 + number_format($promedio,2) + 0;                     
                        $this->arrayDatos[$id]['avance']  = number_format($promedio,2) ;
                        $this->arrayDatos[$id]['totalPonderacion']  = $this->arrayDatos[$id]['totalPonderacion'] + $pon ;
                        $pn = $pn + $pon;
                        $sumP       = $sumP + $t5;
                        $sumR       = $sumR + $a5;
                        $avanceTt = 0;
                        if((int)$t5 > 0){
                            $avanceTt = ( (int)$a5 / (int)$t5) *100;
                            $avanceTt = number_format($avanceTt,2);
                        }
                        if($avanceTt > 100){
                            $avanceTt = 100;
                        }
                        $totalAvance= $totalAvance + $avanceTt;
                        $contadorPro++;
                    }
                    $this->arrayDatos[$id]['id']  = $id;
                    $this->arrayDatos[$id]['act'] = utf8_encode($act);
                    $this->arrayDatos[$id]['pon'] = $pon;
                    $this->arrayDatos[$id]['tAc'] = $tipoAct;
                    $this->arrayDatos[$id]['med'] = utf8_encode($medida);
                    $this->arrayDatos[$id]['t1']  = (int)$t1;
                    $this->arrayDatos[$id]['t2']  = (int)$t2;
                    $this->arrayDatos[$id]['t3']  = (int)$t3;
                    $this->arrayDatos[$id]['t4']  = (int)$t4;
                    $this->arrayDatos[$id]['t5']  = (int)$t5;
                    $this->arrayDatos[$id]['t6']  = (int)$t6;
                    $this->arrayDatos[$id]['a1']  = (int)$a1;
                    $this->arrayDatos[$id]['a2']  = (int)$a2;
                    $this->arrayDatos[$id]['a3']  = (int)$a3;
                    $this->arrayDatos[$id]['a4']  = (int)$a4;
                    $this->arrayDatos[$id]['a5']  = (int)$a5;
                    $this->arrayDatos[$id]['a6']  = (int)$a6;
                    $contador++;                    
                }
                $arrayTotales['tactividades']  = $contador;
                $arrayTotales['tactividadesp']  = $contadorPro;
                $arrayTotales['tponder']       = $p5;
                $arrayTotales['tpondp']        = $pn;               
                $arrayTotales['tavance']       = $p6;
                $arrayTotales['totalavance']   = $totalAvance;
            }
            $sumAvan    = 0;                
            $sumAvanPon = 0;
            $proAvan    = 0;
            if($sumP > 0){
                $sumAvan    = ($sumR / $sumP) * 100;
            }
            if($pn > 0){
                $sumAvanPon = ($p6 / $pn) * 1;
            }
            if($sumAvanPon > 100){
                $sumAvanPon = 100;
            }
            if((int)$arrayTotales['tactividadesp'] > 0){
                $proAvan = ((float) $arrayTotales['totalavance'] / (float) $arrayTotales['tactividadesp']);
            }
            $arrayTotales['tavanceProyecto']    = $p6;
            $arrayTotales['tavanceProyectoPon'] = $sumAvanPon;
            $arrayTotales['tpromedProyecto'] = $proAvan;
            $this->generaCuadroActividades($arrayTotales);
        }else{
            $this->titulo = "";
            $this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
        }
    }
     
    function generaCuadroActividades($arrayTotales){
        $tablaC = str_replace("view_","view_c_",$this->tabla);
        $tablaA = str_replace("view_","view_a_",$this->tabla);           
        $this->buffer = $this->xml = "";
        $contador = $totalPon = 0;
        if((double) $arrayTotales['tpromedProyecto'] > 100){
            $arrayTotales['tpromedProyecto'] = 100;
        }
        if((double) $arrayTotales['tavanceProyectoPon']> 100){
            $arrayTotales['tavanceProyectoPon'] = 100;
        }
 
        $this->buffer .='<table width="100%" class="table">
                        <tr class="'.$this->color($arrayTotales['tpromedProyecto']).'">
                            <td class="tdleft"><b>P</b> = Programado</td>
                            <td class="tdleft"><b>R</b> = Reportado</td>
                            <td class="tdleft">Actividades: '.number_format($arrayTotales['tactividades'],0,".",",").'</td>
                            <td class="tdright">Ponderaci&oacute;n Total: '.number_format($arrayTotales['tponder'],0,".",",").'</td>
                            <td class="tdleft" colspan="2">&nbsp;</td>
                        </tr>
                        <tr class="'.$this->color($arrayTotales['tpromedProyecto']).'">
                            <td class="tdleft"><b>C</b> = Comentarios</td>
                            <td class="tdleft"><b>A</b> = Adjuntos</td>
                            <td class="tdleft">Actividades procesadas: '.number_format($this->contadorProcesadas,0,".",",").'</td>
                            <td class="tdright">Ponderaci&oacute;n Total procesadas: '.number_format($arrayTotales['tpondp'],0,".",",").'</td>
                            <td class="tdright">Avance: '.number_format($arrayTotales['tpromedProyecto'],2,".",",").'</td>
                            <td class="tdright">Avance Ponderado del proyecto: '.number_format($arrayTotales['tavanceProyectoPon'],2,".",",").'</td>
                        </tr></table>';
        $this->buffer .='<table class="table table-bordered">
                            <thead><tr class="active"><td colspan="2">Actividad</td>
                                        <td>Programado</td>
                                        <td>Realizado</td>
                                        <td>Ponderaci&oacute;n</td>
                                        <td>T.Actividad</td>
                                        <td>Avance</td>
                                        <td width="5%">M&aacute;s</td>
                                        <td width="5%">&nbsp;</td>
                                        <td width="5%">&nbsp;</td>
                            </tr></thead><tbody>';
        $totalPro = $totalAva = 0;
        foreach($this->arrayDatos as $idAct => $tmpDatos){
            $contador++;
            $totalPro = $totalPro + $tmpDatos['t5'] + 0; 
            $totalAva = $totalAva + $tmpDatos['a5'] + 0;
            $totalPon = $totalPon + $tmpDatos['pon'] + 0;
            $arrayComentarios = $this->regresaDatosComentarios($this->data['idProyecto'],$tablaC,$idAct);
            $comentarios      = trim($this->regresaComentarios($arrayComentarios));
            $arrayAdjuntos    = $this->regresaDatosAdjuntos($this->data['idProyecto'],$tablaA,$idAct);
            $adjuntos         = $this->regresaAdjuntos($arrayAdjuntos);
            $avance = 0;
            if((int)$tmpDatos['t5'] > 0){
                $avance = ( (int)$tmpDatos['a5'] / (int)$tmpDatos['t5']) *100;
            }
            if($avance > 100){
                $avance = 100;
            }
             
            $this->buffer .='<tr>
                    <td class="tdcenter" >'.$contador.'</td>
                    <td class="tdleft" >'.$tmpDatos['act'].'</td>
                    <td class="tdcenter" width="7%">'.number_format($tmpDatos['t5'],0,".",",").'</td>
                    <td class="tdcenter" width="7%">'.number_format($tmpDatos['a5'],0,".",",").'</td>
                    <td class="tdcenter" width="10%">'.$tmpDatos['pon'].'</td>
                    <td class="tdcenter" width="10%">Tipo Actividad '.$tmpDatos['tAc'].'</td>     
                    <td class="tdright '.$this->color($avance).'" width="10%">'.number_format($avance,2,".",",").'</td>
                    <td class="tdcenter" width="5%"><button type="button" class="btn btn-default  buttonComentarios" id="button-'.$idAct.'" value="1"><b><span class="glyphicon glyphicon-plus"></span></b></button></td>
                    <td class="tdcenter" width="5%">';
            if(trim($comentarios) != ""){
                $comentarios = str_replace('"','',$comentarios);
                $this->buffer .='
                    <button class="btn btn-default comentarios" id="c-'.$idAct.'" type="button" >&nbsp;&nbsp;C&nbsp;&nbsp;</button>
                    <input type="hidden" name="coment-'.$idAct.'" id="coment-'.$idAct.'" value="'.utf8_encode($comentarios).'">
                    </div>';
            }
            $this->buffer .='</td><td class="tdcenter" width="5%">';
            if(trim($adjuntos) != ""){
                $this->buffer.='
                <button class="btn btn-default adjuntos" type="button" id="a-'.$idAct.'">&nbsp;&nbsp;A&nbsp;&nbsp;</button>
                <input type="hidden" name="adjuntos-'.$idAct.'" id="adjuntos-'.$idAct.'" value="'.utf8_encode($adjuntos).'"> 
                </div>';
            }            
            $this->buffer .='</td></tr>
                    <tr id="renglonActividad'.$idAct.'" class="mas">
                        <td colspan="10" class="tdcenter">'.$this->masDatos($tablaC,$tablaA,$idAct,$tmpDatos).'</td>
                    </tr>';
        }
        if((double) $arrayTotales['tpromedProyecto'] > 100){
            $arrayTotales['tpromedProyecto'] = 100;
        }
        $this->buffer .='</tbody>
                <tfoot><tr class="'.$this->color($arrayTotales['tavanceProyecto']).'" >
                        <td colspan="2">&nbsp;</td>
                        <td class="tdcenter" width="7%">'.number_format($totalPro,0,".",",").'</td>
                        <td class="tdcenter" width="7%">'.number_format($totalAva,0,".",",").'</td>
                        <td class="tdcenter" width="7%">'.number_format($totalPon,0,".",",").'</td>
                        <td>&nbsp;</td>
                        <td class="tdright" width="10%">'.number_format($arrayTotales['tpromedProyecto'],2,".",",").'</td>
                        <td colspan="3">&nbsp;</td>
                </tr></tfoot>               
                </table>';
        $this->buffer .='</div>';
    }
     
    function masDatos($tablaC,$tablaA,$idAct,$tmpDatos){
        $buf = '
            <div class="panel-body tdjustify">
                <table width="100%" class="table">
                    <tr class="success">
                        <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE1C.'</td>
                        <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE2C.'</td>
                        <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE3C.'</td>
                        <td colspan="2" class="tdcenter" width="14%">'.TRIMESTRE4C.'</td>
                        <td colspan="2" class="tdcenter" width="14%">'.TOTAL.'</td>
                        <td class="tdcenter" width="10%">'.MEDIDA.'</td>
                        <td class="tdcenter" width="10%">'.PONDERACION.'</td>
                        <td class="tdcenter" width="10%">'.TIPOACT.'</td>
                        <td class="tdcenter" width="10%">'.TITAVANCE.'</td>
                    </tr>
                    <tr class="warning">
                        <td class="tdcenter" width="7%">'.P.'</td>
                        <td class="tdcenter" width="7%">'.R.'</td>
                        <td class="tdcenter" width="7%">'.P.'</td>
                        <td class="tdcenter" width="7%">'.R.'</td>
                        <td class="tdcenter" width="7%">'.P.'</td>
                        <td class="tdcenter" width="7%">'.R.'</td>
                        <td class="tdcenter" width="7%">'.P.'</td>
                        <td class="tdcenter" width="7%">'.R.'</td>
                        <td class="tdcenter" width="7%">'.P.'</td>
                        <td class="tdcenter" width="7%">'.R.'</td>
                        <td class="tdcenter" width="10%">&nbsp;</td>
                        <td class="tdcenter" width="10%">&nbsp;</td>
                        <td class="tdcenter" width="10%">&nbsp;</td>
                        <td class="tdcenter" width="10%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="tdcenter" width="7%">'.$tmpDatos['t1'].'</td>
                        <td class="tdcenter" width="7%">'.$tmpDatos['a1'].'</td>
                        <td class="tdcenter" width="7%">'.$tmpDatos['t2'].'</td>
                        <td class="tdcenter" width="7%">'.$tmpDatos['a2'].'</td>
                        <td class="tdcenter" width="7%">'.$tmpDatos['t3'].'</td>
                        <td class="tdcenter" width="7%">'.$tmpDatos['a3'].'</td>
                        <td class="tdcenter" width="7%">'.$tmpDatos['t4'].'</td>
                        <td class="tdcenter" width="7%">'.$tmpDatos['a4'].'</td>
                        <td class="tdcenter" width="7%">'.$tmpDatos['t6'].'</td>
                        <td class="tdcenter" width="7%">'.$tmpDatos['a6'].'</td>
                        <td class="tdcenter" width="10%">'.$tmpDatos['med'].'</td>
                        <td class="tdcenter" width="10%">'.$tmpDatos['pon'].'</td>
                        <td class="tdcenter" width="10%">Tipo Actividad '.$tmpDatos['tAc'].'</td>
                        <td class="tdright '.$this->color($tmpDatos['avance']).'"" width="10%">'.number_format($tmpDatos['avance'],2,".",",").'</td>
                    </tr>
                    </table>
                    </div>';
            return $buf;
    }
    function recuperaDefault(){
        $sql = "SELECT id,tabla FROM cat_tablas WHERE defa = '1' LIMIT 1;";
        $res = $this->db->sql_query($sql) or die($this->cadena);
        if($this->db->sql_numrows($res) > 0){
            list($this->data['tablaId'],$this->tabla) = $this->db->sql_fetchrow($res);
            $this->tabla = strtolower($this->tabla);
        }
    }
     
    function recuperaTabla(){
        if((int) $this->data['tablaId'] > 0){
            $sql = "SELECT id,tabla FROM cat_tablas WHERE id = '".$this->data['tablaId']."' LIMIT 1;";
        }else{
            $sql = "SELECT id,tabla FROM cat_tablas WHERE defa = '1' LIMIT 1;";
        }       
        $res = $this->db->sql_query($sql) or die($this->cadena);
        if($this->db->sql_numrows($res) > 0){
            list($this->data['tablaId'],$this->tabla) = $this->db->sql_fetchrow($res);
            $this->tabla = strtolower($this->tabla);
        }else{
            $this->recuperaDefault();
        }
    }
    function actualizaTipo2(){
        $upd  = "UPDATE ".$this->tabla." set trimestre1 = Atrimestre1,
                trimestre2 = Atrimestre2,
                trimestre3 = Atrimestre3,
                trimestre4 = Atrimestre4,
                total = totalAvance WHERE tipo_actividad_id = '2';";
        $this->db->sql_query($upd) or die($this->cadena);
    }
     
    function generaTablaEjeProgramaProyecto(){
        $this->titulo  = "Resumen por Programa ".$this->data['idPrograma'].": <b>".$this->catalogoB[$this->data['idPrograma']]."</b>";
        $this->arrayDatos = $arrayAct = array();
        $sql = "select id,proyecto,actividadId,area,ponderacionActividad,ponderacionProyecto,
                trimestre1,trimestre2,trimestre3,trimestre4,
                Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
                FROM ".$this->tabla." WHERE programa_id > 0  and id > 0 ".$this->filtro." ORDER BY programa_id,id;";
        $res = $this->db->sql_query($sql) or die($this->cadena);
        if($this->db->sql_numrows($res)>0){
            while(list($id,$proyecto,$actividadId,$area,$ponderacion,$ponderacionProy,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
                if(!in_array($actividadId,$arrayAct)){
                    $arrayAct[] = $actividadId;
                    $promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);      
                    if($promedio > -1){
                        $this->arrayDatos[$id]['procesadas'] = $this->arrayDatos[$id]['procesadas']    + 1;
                        $this->arrayDatos[$id]['promedio']   = $this->arrayDatos[$id]['promedio'] + $promedio;
                        $this->arrayDatos[$id]['totalPonderacion']   = $this->arrayDatos[$id]['totalPonderacion'] + $ponderacion;
                        $this->arrayDatos[$id]['ponderacionProy']  = $ponderacionProy;
                    }                       
                    $this->arrayDatos[$id]['proyecto']        = utf8_encode($proyecto);
                    $this->arrayDatos[$id]['area']            = utf8_encode($area);
                    $this->arrayDatos[$id]['actividad_id']    = $this->arrayDatos[$id]['actividad_id'] + 1;
                    $this->arrayDatos[$id]['ponderacion']      = $this->arrayDatos[$id]['ponderacion'] + $ponderacion;
                    
                }
            }
            $this->generaCuadroEjeProgramaProyecto();
        }else{
            $this->titulo = "";
            $this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
        }
         
    }
    function generaTablaEjePrograma(){
        $proyectos = array();
        $this->titulo  = "Resumen por Eje ".$this->data['idEje'].": <b>".$this->catalogoA[$this->data['idEje']]."</b>";
        $this->arrayDatos = $arrayProye = $arrayAct = array();
        $sql = "select programa_id,programa,id,actividadId,ponderacionActividad,ponderacionProyecto,ponderacionPrograma,
                trimestre1,trimestre2,trimestre3,trimestre4,
                Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4,id
                FROM ".$this->tabla." WHERE eje_id > 0 and programa_id > 0 ".$this->filtro." ORDER BY programa_id,id;";
        $res = $this->db->sql_query($sql) or die($this->cadena);
        if($this->db->sql_numrows($res)>0){
            while(list($programa_id,$programa,$id,$actividadId,$ponderacion,$ponderacionSub,$ponderacionPrograma,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$id) = $this->db->sql_fetchrow($res)){
                if(!in_array($actividadId,$arrayAct)){
                    $arrayAct[] = $actividadId;
                    $promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);                    
                    if($promedio > -1){
                    	$proyectos[$programa_id][$id]['ponderacionSub']  = $ponderacionSub;
                        $proyectos[$programa_id][$id]['promedio']         = $proyectos[$programa_id][$id]['promedio'] + $promedio;
                        $proyectos[$programa_id][$id]['totalPonderacion'] = $proyectos[$programa_id][$id]['totalPonderacion'] + $ponderacion;
                        $this->arrayDatos[$programa_id]['procesadas']      = $this->arrayDatos[$programa_id]['procesadas']    + 1;
                        $this->arrayDatos[$programa_id]['promedio']        = $this->arrayDatos[$programa_id]['promedio'] + $promedio;
                        $this->arrayDatos[$programa_id]['totalPonderacion']= $this->arrayDatos[$programa_id]['totalPonderacion'] + $ponderacion;
                        $this->arrayDatos[$programa_id]['ponderacionSub']  = $ponderacionSub;
                        $this->arrayDatos[$programa_id]['ponderacionProg'] = $ponderacionPrograma;
                    }
                    
                    $this->arrayDatos[$programa_id]['programa']        = utf8_encode($programa);
                    $this->arrayDatos[$programa_id]['programa_id']     = utf8_encode($programa_id);
                    $this->arrayDatos[$programa_id]['actividad_id']    = $this->arrayDatos[$programa_id]['actividad_id'] + 1;
                    $this->arrayDatos[$programa_id]['ponderacion']     = $this->arrayDatos[$programa_id]['ponderacion'] + $ponderacion;
                    
                         
                    //agrupaar por proyecto
                    if(!in_array($id, $arrayProye)){
                        $arrayProye[] = $id;
                        $this->arrayDatos[$programa_id]['proyecto_id'] = $this->arrayDatos[$programa_id]['proyecto_id'] + 1;
                    }
                }
            }
            // calculamos la pondracion del proyecto
            $totales = array();
            $pondera = array();
            if(count($proyectos) > 0){
                foreach($proyectos as $idPrograma => $tmp){
                    foreach($tmp as $idProyecto => $data){
                        $valor = 0;
                        if($data['totalPonderacion'] > 0){
                            $valor = (($data['promedio'] / $data['totalPonderacion']) * 1);                         
                            if($valor > 100){
                                $valor = 100;
                            }
                            $valor = number_format($valor,2);
                            $valor = ($valor * $data['ponderacionSub']);                            
                        }
                        $totales[$idPrograma] = (double) $totales[$idPrograma] + $valor;
                        $pondera[$idPrograma] = $pondera[$idPrograma] + $data['ponderacionSub'];
                    }
                }
                foreach($totales as $idPrograma => $value){
                    $this->arrayDatos[$programa_id]['avanceFinal']  = 0; 
                    if((int) $pondera[$idPrograma] > 0)
                        $this->arrayDatos[$idPrograma]['avanceFinal'] = number_format($totales[$idPrograma] / $pondera[$idPrograma],2);
                }
                 
            }
            $this->generaCuadroEjePrograma();
        }else{
            $this->titulo = "";
            $this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
        }
    }
     
     
    function generaTablaEjeProgramaSS(){
        $this->titulo  = "Resumen por Eje ".$this->data['idEje'].": <b>".$this->catalogoA[$this->data['idEje']]."</b>";
        $this->arrayDatos = $arrayProye = $arrayAct = array();
        $sql = "select programa_id,programa,id,actividadId,ponderacionActividad,ponderacionProyecto,
                trimestre1,trimestre2,trimestre3,trimestre4,
                Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4,id,proyecto
                FROM ".$this->tabla." WHERE eje_id = 1 and programa_id > 0 and programa_id =1 ".$this->filtro." ORDER BY programa_id,id;";             
        $res = $this->db->sql_query($sql) or die($this->cadena);
        if($this->db->sql_numrows($res)>0){
            while(list($programa_id,$programa,$id,$actividadId,$ponderacion,$ponderacionSub,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$id,$proyecto) = $this->db->sql_fetchrow($res)){
                if(!in_array($actividadId,$arrayAct)){
                    $arrayAct[] = $actividadId;
                    $promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);                      
                    if($promedio > -1){
                        $x .= "<tr><td>".$programa_id."</td><td>".$promedio."</td><td>".$ponderacion."</td></tr>";  
                        $this->arrayDatos[$programa_id]['procesadas']        = $this->arrayDatos[$programa_id]['procesadas']    + 1;
                        $this->arrayDatos[$programa_id]['promedio']           = $this->arrayDatos[$programa_id]['promedio'] + $promedio;
                        $this->arrayDatos[$programa_id]['totalPonderacion']   = $this->arrayDatos[$programa_id]['totalPonderacion'] + $ponderacion;
                        $this->arrayDatos[$programa_id][$id] = $this->arrayDatos[$programa_id][$id] + $ponderacion;
                    }                                               
                    $this->arrayDatos[$programa_id]['programa']   = utf8_encode($programa);
                    $this->arrayDatos[$programa_id]['actividad_id'] = $this->arrayDatos[$programa_id]['actividad_id'] + 1;
                    $this->arrayDatos[$programa_id]['ponderacion']  = $this->arrayDatos[$programa_id]['ponderacion'] + $ponderacion;
                    $this->arrayDatos[$programa_id]['ponderacionSub']  = $ponderacionSub;                    
                    if(!in_array($id, $arrayProye)){
                        $arrayProye[] = $id;
                        $this->arrayDatos[$programa_id]['proyecto_id'] = $this->arrayDatos[$programa_id]['proyecto_id'] + 1;
                    }
                }       
            }
            $this->generaCuadroEjePrograma();
        }else{
            $this->titulo = "";
            $this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
        }
    }
     
    function calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion){
        $sumaProgramada = 0;
        $sumaReales     = 0;
        $avance         = 0;
        switch($this->data['trimestreId']){
            case 1:
                $sumaProgramada = (int) $t1 + 0;
                $sumaReales     = (int) $a1 + 0;
                break;
            case 2:
                $sumaProgramada = (int) $t1 + (int) $t2 + 0;
                $sumaReales     = (int) $a1 + (int) $a2 + 0;                
                break;
            case 3:
                $sumaProgramada = (int) $t1 + (int) $t2 + (int) $t3 + 0;
                $sumaReales     = (int) $a1 + (int) $a2 + (int) $a3 + 0;                
                break;
            case 4:
                $sumaProgramada = (int) $t1 + (int) $t2 + (int) $t3 + (int) $t4 + 0;
                $sumaReales     = (int) $a1 + (int) $a2 + (int) $a3 + (int) $a4 + 0;                
                break;              
        }
        if((double)$sumaProgramada == 0 && (double)$sumaReales == 0){
            $avance = -1;
        }
        if((double)$sumaProgramada == 0 && (double)$sumaReales > 0){
            $avance = -1;
        }       
        if((double)$sumaProgramada > 0 && (double)$sumaReales == 0){
            $avance = (double)( $sumaReales / $sumaProgramada ) * 100;          
            if($avance > 100){
                $avance = 100;
            }
            $avance = ( $avance * $ponderacion);
        }       
        if((double)$sumaProgramada > 0 && (double)$sumaReales > 0){
            $avance = (double)( $sumaReales / $sumaProgramada ) * 100;
            if($avance > 100){                
                $avance = 100;
            }
            $avance = ( $avance * $ponderacion);                
        }
        return $avance;
    }
     
     
    function debug($array){
        echo"<pre>";
        print_r($array);
        die();      
    }
    /**
     * Metodo que genera las estadisticas por eje
     */
    function generaTablaEje(){
        $proyectos = array();
        $this->titulo  = "Tablero de Control ".$this->data['anoId'].": <b>Resumen General</b>";       
        $this->arrayDatos = $arrayProgr = $arrayProye = $arrayAct = array();
        $sql = "select eje_id,eje,programa_id,id,actividadId,ponderacionActividad,ponderacionProyecto,ponderacionPrograma,ponderacionEje,
                trimestre1,trimestre2,trimestre3,trimestre4,
                Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
                FROM ".$this->tabla." WHERE eje_id > 0 and  programa_id > 0 and id> 0  ".$this->filtro."
                ORDER BY eje_id,programa_id,id,actividadId;";
        $res = $this->db->sql_query($sql) or die($this->cadena);
        if($this->db->sql_numrows($res)>0){
            while(list($eje_id,$eje,$programa_id,$id,$actividadId,$ponderacion,$ponderacionProy,$ponderacionProg,$ponderacionEje,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
                if(!in_array($actividadId,$arrayAct)){
                    $arrayAct[] = $actividadId;
                    $promedio   = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);    
                    if($promedio > -1){
                    	$proyectos[$eje_id][$programa_id][$id]['ponderacionProy']  = $ponderacionProy;
                    	$proyectos[$eje_id][$programa_id][$id]['ponderacionProg']  = $ponderacionProg;
                    	$proyectos[$eje_id][$programa_id][$id]['ponderacionEje']   = $ponderacionEje;
                    	 
                        $proyectos[$eje_id][$programa_id][$id]['promedio']         = $proyectos[$eje_id][$programa_id][$id]['promedio'] + $promedio;
                        $proyectos[$eje_id][$programa_id][$id]['totalPonderacion'] = $proyectos[$eje_id][$programa_id][$id]['totalPonderacion'] + $ponderacion;                     
                        $this->arrayDatos[$eje_id]['procesadas']       = $this->arrayDatos[$eje_id]['procesadas'] + 1;
                        $this->arrayDatos[$eje_id]['promedio']         = $this->arrayDatos[$eje_id]['promedio'] + $promedio;
                        $this->arrayDatos[$eje_id]['totalPonderacion'] = $this->arrayDatos[$eje_id]['totalPonderacion'] + $ponderacion;
                    }
                    $this->arrayDatos[$eje_id]['eje']   = utf8_encode($eje);
                    if(!in_array($programa_id, $arrayProgr)){
                        $arrayProgr[] = $programa_id;
                        $this->arrayDatos[$eje_id]['programa_id'] = $this->arrayDatos[$eje_id]['programa_id'] + 1;
                    }
                    if(!in_array($id, $arrayProye)){
                        $arrayProye[] = $id;
                        $this->arrayDatos[$eje_id]['proyecto_id'] = $this->arrayDatos[$eje_id]['proyecto_id'] + 1;
                    }
                    $this->arrayDatos[$eje_id]['actividad_id']    = $this->arrayDatos[$eje_id]['actividad_id'] + 1;
                    $this->arrayDatos[$eje_id]['ponderacion']     = $this->arrayDatos[$eje_id]['ponderacion'] + $ponderacion;
                    $this->arrayDatos[$eje_id]['ponderacionEje']  = $ponderacionEje;
                }
            }
            //genera totales por proyecto
            $this->generaTotalesProyecto($proyectos);
            $this->generaCuadroEje();
        }
        else{
            $this->titulo = "";
            $this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
        }       
    }
     
    function generaTablaArea(){
        $proyectos = array();
        $this->titulo  = "Resumen General por <b>Unidad Responsable</b>";
        $this->arrayDatos = $arrayProgr = $arrayProye = $arrayAct = array();
        $sql = "select unidadResponsableId,area,programa_id,id,actividadId,ponderacionActividad,ponderacionProyecto,ponderacionArea,
                trimestre1,trimestre2,trimestre3,trimestre4,
                Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
                FROM ".$this->tabla." WHERE unidadResponsableId > 0 ".$this->filtro." ORDER BY area;"; 
        $res = $this->db->sql_query($sql) or die($this->cadena);
        if($this->db->sql_numrows($res)>0){
            while(list($area_id,$area,$programa_id,$id,$actividadId,$ponderacion,$ponderacionProy,$ponderacionArea,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
                if(!in_array($actividadId,$arrayAct)){
                    $arrayAct[] = $actividadId;
                    $promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);
                    if($promedio > -1){
                    	$proyectos[$area_id][$id]['ponderacionProy'] = $ponderacionProy;
                    	$proyectos[$area_id][$id]['ponderacionArea'] = $ponderacionArea;                    	 
                        $proyectos[$area_id][$id]['promedio']         = $proyectos[$area_id][$id]['promedio'] + $promedio;
                        $proyectos[$area_id][$id]['totalPonderacion'] = $proyectos[$area_id][$id]['totalPonderacion'] + $ponderacion;                       
                        $this->arrayDatos[$area_id]['procesadas']     = $this->arrayDatos[$area_id]['procesadas']    + 1;
                        $this->arrayDatos[$area_id]['promedio']       = $this->arrayDatos[$area_id]['promedio'] + $promedio;
                        $this->arrayDatos[$area_id]['totalPonderacion']   = $this->arrayDatos[$area_id]['totalPonderacion'] + $ponderacion;
                        $this->arrayDatos[$area_id]['ponderacionArea'] = (int) $ponderacionArea;
                        $this->arrayDatos[$area_id]['ponderacionProy'] = $ponderacionProy;
                    }                       
                    $this->arrayDatos[$area_id]['area']   = utf8_encode($area);
                    $this->arrayDatos[$area_id]['actividad_id'] = $this->arrayDatos[$area_id]['actividad_id'] + 1;
                    $this->arrayDatos[$area_id]['ponderacion']  = $this->arrayDatos[$area_id]['ponderacion'] + $ponderacion;
                         
                    if(!in_array($programa_id, $arrayProgr)){
                        $arrayProgr[] = $programa_id;
                        $this->arrayDatos[$area_id]['programa_id'] = $this->arrayDatos[$area_id]['programa_id'] + 1;
                    }
                    if(!in_array($id, $arrayProye)){
                        $arrayProye[] = $id;
                        $this->arrayDatos[$area_id]['proyecto_id'] = $this->arrayDatos[$area_id]['proyecto_id'] + 1;
                    }
                }
            }
            // calculamos la pondracion del proyecto            
            $totales = array();
            $pondera = array();
            //$this->debug($proyectos);
            if(count($proyectos) > 0){
                foreach($proyectos as $idArea => $tmp){
                    foreach($tmp as $idProyecto => $data){
                        $valor = 0;
                        if($data['totalPonderacion'] > 0){
                            $valor = (($data['promedio'] / $data['totalPonderacion']) * 1);
                            if($valor > 100){
                                $valor = 100;
                            }
                            $valor = number_format($valor,2);
                            $valor = ($valor * $data['ponderacionProy']);
                        }
                        $totales[$idArea] = (double) $totales[$idArea] + $valor;
                        $pondera[$idArea] = $pondera[$idArea] + $data['ponderacionProy'];
                    }
                }
                foreach($totales as $idArea => $value){
                    $this->arrayDatos[$idArea]['avanceFinal']  = 0;
                    if((int) $pondera[$idArea] > 0){
                        $this->arrayDatos[$idArea]['avanceFinal'] = number_format($totales[$idArea] / $pondera[$idArea],2);
                    }
                }           
            }
            $this->generaCuadroArea();
        }else{
            $this->titulo = "";
            $this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
        }
    }
     
    function generaTablaAreaProyecto(){
        $this->titulo  = "Resumen por Unidad responsable ".$this->data['idArea'].": <b>".$this->catalogoC[$this->data['idArea']]."</b>";
        $this->arrayDatos = $arrayAct = array();
        $sql = "select id,proyecto,actividadId,ponderacionActividad,ponderacionProyecto,
                trimestre1,trimestre2,trimestre3,trimestre4,
                Atrimestre1,Atrimestre2,Atrimestre3,Atrimestre4
                FROM ".$this->tabla." WHERE unidadResponsableId > 0 ".$this->filtro." ORDER BY proyecto;";     
        $res = $this->db->sql_query($sql) or die($this->cadena);
        if($this->db->sql_numrows($res)>0){
            while(list($id,$proyecto,$actividadId,$ponderacion,$ponderacionProy,$t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4) = $this->db->sql_fetchrow($res)){
                if(!in_array($actividadId, $arrayAct)){
                    $arrayAct[] = $actividadId;
                    $promedio = $this->calculaAvance($t1,$t2,$t3,$t4,$a1,$a2,$a3,$a4,$ponderacion);
                    if($promedio > -1){
                        $this->arrayDatos[$id]['procesadas']       = $this->arrayDatos[$id]['procesadas']    + 1;
                        $this->arrayDatos[$id]['promedio']         = $this->arrayDatos[$id]['promedio'] + $promedio;
                        $this->arrayDatos[$id]['totalPonderacion'] = $this->arrayDatos[$id]['totalPonderacion'] + $ponderacion;
                        $this->arrayDatos[$id]['ponderacionProy']  = $ponderacionProy;
                    }                       
                    $this->arrayDatos[$id]['proyecto']   = utf8_encode($proyecto);
                    $this->arrayDatos[$id]['actividad_id'] = $this->arrayDatos[$id]['actividad_id'] + 1;
                    $this->arrayDatos[$id]['ponderacion']  = $this->arrayDatos[$id]['ponderacion'] + $ponderacion;
                   
                }
            }
            $this->generaCuadroEjeAreaProyecto();
        }else{
            $this->titulo = "";
            $this->buffer = "No se encuentran proyectos con la busqueda seleccionada";
        }
    }
     
    function cabeceras($tipo){
        $buffer=""; 
        switch($tipo){
            case 1:
                $buffer = '<thead>
                        <tr>
                            <th class="tdcenter" style="width:5%;">Id</th>
                            <th class="tdcenter" style="width:47%;">Eje</th>
                            <th class="tdcenter" >Programas</th>
                            <th class="tdcenter" >Proyectos</th>
                            <th class="tdcenter" >Actividades</th>';
                if((int) $this->data['ponderaId'] == 1){
                    $buffer .= '<th class="tdcenter" >Ponderacion</th>
                                <th class="tdcenter" >Actividades Procesadas</th>
                                <th class="tdcenter" >Ponderacion Procesadas</th>
                                <th class="tdcenter" >Promedio</th>';
                }
                $buffer .= '<th class="tdcenter" >% Avance Acumulado</th>
                        </tr>
                    </thead>'; 
                break;
            case 2:
                $buffer = '<thead>
                    <tr>
                        <th class="tdcenter" style="width:5%;">Id</th>
                        <th class="tdcenter" style="width:50%;">Programa</th>
                        <th class="tdcenter" style="width:15%;">Proyectos</th>
                        <th class="tdcenter" style="width:15%;">Actividades</th>';
                if((int) $this->data['ponderaId'] == 1){
                    $buffer .= '<th class="tdcenter" >Ponderacion</th>
                                <th class="tdcenter" >Actividades Procesadas</th>
                                <th class="tdcenter" >Ponderacion Procesadas</th>
                                <th class="tdcenter" >Promedio</th>';
                }               
                $buffer .= '<th class="tdcenter" style="width:15%;">% Avance Acumulado</th>
                    </tr>
                </thead>';
                break;
            case 3:
                $buffer = '<thead>
                    <tr>
                        <th class="tdcenter" style="width:5%;">Id</th>
                        <th class="tdcenter">Proyecto</th>
                        <th class="tdcenter">Unidad Responsable</th>
                        <th class="tdcenter">Actividades</th>';
                if((int) $this->data['ponderaId'] == 1){
                    $buffer .= '<th class="tdcenter" >Ponderacion</th>
                                <th class="tdcenter" >Actividades Procesadas</th>
                                <th class="tdcenter" >Ponderacion Procesadas</th>
                                <th class="tdcenter" >Promedio</th>';
                }               
                $buffer .= '<th class="tdcenter">% Avance Acumulado</th>
                    </tr>
                </thead>';
                    break;      
                case 4:
                    $buffer = '<thead>
                        <tr>
                            <th class="tdcenter" style="width:5%;">Id</th>
                            <th class="tdcenter" style="width:47%;">Unidad Responsable</th>
                            <th class="tdcenter" >Programas</th>
                            <th class="tdcenter" >Proyectos</th>
                            <th class="tdcenter" >Actividades</th>';
                    if((int) $this->data['ponderaId'] == 1){
                        $buffer .= '<th class="tdcenter" >Ponderacion</th>
                                <th class="tdcenter" >Actividades Procesadas</th>
                                <th class="tdcenter" >Ponderacion Procesadas</th>
                                <th class="tdcenter" >Promedio</th>';
                    }                       
                    $buffer .= '<th class="tdcenter" >% Avance Acumulado</th>
                        </tr>
                    </thead>';
                    break;
                case 5:
                        $buffer = '<thead>
                        <tr>
                            <th class="tdcenter" style="width:5%;">Id</th>
                            <th class="tdcenter" style="width:47%;">Proyecto</th>
                            <th class="tdcenter" >Actividades</th>';
                        if((int) $this->data['ponderaId'] == 1){
                            $buffer .= '<th class="tdcenter" >Ponderacion</th>
                                <th class="tdcenter" >Actividades Procesadas</th>
                                <th class="tdcenter" >Ponderacion Procesadas</th>
                                <th class="tdcenter" >Promedio</th>';
                        }                       
                        $buffer .= '<th class="tdcenter" >% Avance Acumulado</th>
                        </tr>
                    </thead>';
                    break;                  
        }
        return $buffer;
    }
     
    function generaCuadroEjeAreaProyecto(){
        $valorAct = $rand = $sumRand = 0;
        $valorPP = $valorPr = $valorPn = 0;
        $rand = $randPonderada = 0;
        $contador = 1;
        $this->xml = $urlTmp = "";
        if(count($this->arrayDatos) > 0){
            $this->xml = "<chart palette='2' caption='Grafico de Actividades por Proyecto' labelDisplay='ROTATE' showValues='1' decimals='0' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
            $this->xmlPor="<chart palette='2' caption='Grafico de Ponderacion por Proyecto' labelDisplay='ROTATE' showValues='1' decimals='1' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
            $this->buffer = '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
            $this->buffer.= $this->cabeceras(5);
            $this->buffer.= '<tbody>';
            foreach($this->arrayDatos as $ind => $tmp){               
                $rand = (float) $this->calculaPonderacion($tmp);
                $randPonderada = $rand * $tmp['ponderacionProy'];
                $urlTmp = $this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=2&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$this->data['idArea']."&idProyecto=".$ind."&ponderaId=".$this->data['ponderaId'];
                $valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
                $valorPP  = $valorPP  +  (double) $tmp['totalPonderacion'] + 0;
                $valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
                $valorPc  = $valorPc  +  (double) $tmp['procesadas'] + 0;
                $valorPn  = $valorPn  +  (double) $tmp['ponderacion'] + 0;
                $valorPs  = $valorPs  +  (double) $tmp['ponderacionProy'] + 0;
                $this->buffer.= '<tr>
                                    <td class="tdleft">'.$contador.'</td>
                                    <td class="tdleft"><a href="'.$urlTmp.'" target="_self" class="liga">'.$tmp['proyecto'].'</a></td>                              
                                    <td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
                if((int) $this->data['ponderaId'] == 1){
                    $this->buffer.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
                                     <td class="tdcenter">'.number_format($tmp['procesadas'],0,'.',',').'</td>
                                     <td class="tdcenter">'.number_format($tmp['totalPonderacion'],0,'.',',').'</td>
                                     <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
                }
                $this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand. '%</td></tr>';                                
                $this->xml.="<set label='".$contador."' tooltext='".$tmp['proyecto']." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."'  value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."'/>";
                $this->xmlPor.="<set label='".$contador."' tooltext='".$tmp['proyecto']." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
                $sumRand = $sumRand + $randPonderada;
                $contador++;
                 
            }
            $promedioTotal = 0;         
            if($valorPs > 0){
                $promedioTotal = ($sumRand / $valorPs)*1;
                $promedioTotal = number_format($promedioTotal,2,'.',',');
            }
            if($promedioTotal> 100){
                $promedioTotal = number_format(100,2,'.',',');
            }
                 
            $this->buffer.= '</tbody>
                            <thead>
                                <tr>
                                    <th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
                                    <th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
            if((int) $this->data['ponderaId'] == 1){
                $this->buffer.= '<th class="tdcenter">'.number_format($valorPn,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPc,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
            }
            $this->buffer.= '<th class="tdright '.$this->color($promedioTotal).'">'.$promedioTotal.'%</th>
                                </tr>
                            </thead></table>';
            $this->xml .= "</chart>";      
            $this->xmlPor.="</chart>";
            $this->bufferXml = $this->buffer;
        }
         
    }
    function generaCuadroEjeProgramaProyecto(){
        $valorAct = $valorPP = $valorPr = $valorPn = $sumRand = $sumRand2 = 0;
        $contador = 1;
        $rand = $randPonderada = $valorPc = $valorPs = 0;
        $this->xml = $urlTmp = "";
        if(count($this->arrayDatos) > 0){
            $this->xml = "<chart palette='2' caption='Grafico de Actividades por Proyecto' labelDisplay='ROTATE' showValues='1' decimals='0' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
            $this->xmlPor="<chart palette='2' caption='Grafico de Ponderacion por Proyecto' labelDisplay='ROTATE' showValues='1' decimals='1' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
            $this->buffer = '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
            $this->buffer.= $this->cabeceras(3);
            $this->buffer.= '<tbody>';
            foreach($this->arrayDatos as $ind => $tmp){               
                $rand = (float) $this->calculaPonderacion($tmp);
                $randPonderada = $rand * $tmp['ponderacionProy'];
                $urlTmp = $this->path."index.php?index.php?tablaId=".$this->data['tablaId']."&tipoId=1&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."&idPrograma=".$this->data['idPrograma']."&idProyecto=".$ind."&ponderaId=".$this->data['ponderaId'];
                $valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
                $valorPP  = $valorPP  +  (double) $tmp['totalPonderacion'] + 0;
                $valorPn  = $valorPn  +  (double) $tmp['ponderacion'] + 0;
                $valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
                $valorPc  = $valorPc  +  (double) $tmp['procesadas'] + 0;
                $valorPs  = $valorPs  +  (double) $tmp['ponderacionProy'] + 0;
                $this->buffer.= '<tr>
                                    <td class="tdleft">'.$contador.'</td>
                                    <td class="tdleft"><a href="'.$urlTmp.'" target="_self" class="liga">'.$tmp['proyecto'].'</a></td>                                          
                                    <td class="tdleft">'.$tmp['area'].'</td>
                                    <td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
                if((int) $this->data['ponderaId'] == 1){
                    $this->buffer.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
                                     <!--<td class="tdcenter">'.number_format($tmp['procesadas'],0,'.',',').'</td>-->
                                     <td class="tdcenter">'.number_format($tmp['procesadasSup'],0,'.',',').'</td>
                                     <td class="tdcenter">'.number_format($tmp['totalPonderacion'],0,'.',',').' * '.$tmp['ponderacionSup'].'</td>
                                     <td class="tdcenter">'.number_format($tmp['promedio'] * $tmp['ponderacionSup'],2,'.',',').'</td>';
                }
                $this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand. '%</td></tr>';                
                $this->xml.="<set label='".$contador."' tooltext='".$tmp['proyecto']." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."' value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."'/>";
                $this->xmlPor.="<set label='".$contador."' tooltext='".$tmp['proyecto']." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
                $contador++;
                $sumRand = $sumRand + $randPonderada; 
                $sumRand2 = $sumRand2 +$rand;               
            }
            $promedioTotal = 0;
            if($valorPs > 0){
                $promedioTotal = ($sumRand / $valorPs)*1;
                $promedioTotal = number_format($promedioTotal,2,'.',',');
            }
            $this->buffer.= '</tbody>
                            <thead>
                                <tr>
                                    <th class="tdleft" colspan="3">Totales: '.count($this->arrayDatos).' registros.</th>
                                    <th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
            if((int) $this->data['ponderaId'] == 1){
                $this->buffer.= '<th class="tdcenter">'.number_format($valorPn,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPc,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
            }
            $this->buffer.= '<th class="tdright '.$this->color($promedioTotal).'">'.$promedioTotal.'%</th>
                                </tr>
                            </thead></table>';
            $this->xml .= "</chart>";
            $this->xmlPor.="</chart>";
            $this->bufferXml = $this->buffer;
        }
    }
     
    function generaCuadroEjePrograma(){
        $this->xml = $urlTmp = "";
        $valorPy = $valorAct = $valorPc = $valorPs = 0;
        $valorPP = $valorPr = $valorPn = 0;
        $rand = $randPonderada = $sumRand = 0;
        $contador = 1;
        if(count($this->arrayDatos) > 0){
            $this->xml = "<chart palette='2' caption='Grafico de Actividades por Programa' labelDisplay='ROTATE' showValues='1' decimals='0' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
            $this->xmlPor="<chart palette='2' caption='Grafico de Ponderacion por Programa' labelDisplay='ROTATE' showValues='1' decimals='1' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
            $this->buffer = '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
            $this->buffer.= $this->cabeceras(2);
            $this->buffer.= '<tbody>';
            $this->bufferXml = $this->buffer;
            foreach($this->arrayDatos as $ind => $tmp){
                $rand = (double) $tmp['avanceFinal'];
                $randPonderada = $rand * $tmp['ponderacionProg'];
                $urlTmp = $this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=1&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."&idPrograma=".$ind."&ponderaId=".$this->data['ponderaId'];
                $valorPy  = $valorPy  +  (int) $tmp['proyecto_id'] + 0;
                $valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
                $valorPP  = $valorPP  +  (double) $tmp['totalPonderacion'] + 0;
                $valorPn  = $valorPn  +  (double) $tmp['ponderacion'] + 0;
                $valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
                $valorPc  = $valorPc  +  (double) $tmp['procesadas'] + 0;
                $valorPs  = $valorPs  +  (double) $tmp['ponderacionProg'] + 0;      
                if((int) $tmp['programa_id'] > 0){
                    $this->buffer.= '<tr>
                                        <td class="tdleft">'.$contador.'</td>
                                        <td class="tdleft"><a href="'.$urlTmp.'" target="_self" class="liga">'.$tmp['programa'].'</a></td>
                                        <td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
                                        <td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
                    if((int) $this->data['ponderaId'] == 1){
                        $this->buffer.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
                                         <td class="tdcenter">'.number_format($tmp['procesadas'],0,'.',',').'</td>
                                         <td class="tdcenter">'.number_format($tmp['totalPonderacion'],0,'.',',').'</td>
                                         <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
                    }
                    $this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand.'%</td></tr>';
                    $this->bufferXml .='<tr>
                                        <td class="tdleft">'.$contador.'</td>
                                        <td class="tdleft">'.$tmp['programa'].'</td>
                                        <td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
                                        <td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
                    if((int) $this->data['ponderaId'] == 1){
                        $this->bufferXml.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
                                            <td class="tdcenter">'.number_format($tmp['procesadas'],0,'.',',').'</td>
                                            <td class="tdcenter">'.number_format($tmp['totalPonderacion'],0,'.',',').'</td>
                                            <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
                    }
                    $this->bufferXml.= '<td class="tdright">'.$rand. '%</td></tr>';
                }
                 
                $this->xml.="<set label='".$contador."' tooltext='".$this->catalogoB[$ind]." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."' value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."' />";
                $this->xmlPor.="<set label='".$contador."' tooltext='".$this->catalogoB[$ind]." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
                $sumRand = $sumRand + $randPonderada;
                $contador++;
            }
            $promedioTotal = 0;             
            if($valorPs > 0){
                $promedioTotal = ($sumRand / $valorPs)*1;
                $promedioTotal = number_format($promedioTotal,2,'.',',');
            }
            if($promedioTotal> 100){
                $promedioTotal = number_format(100,2,'.',',');
            }               
            $this->buffer.= '</tbody>
                            <thead>
                                <tr>
                                    <th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
                                    <th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
            if((int) $this->data['ponderaId'] == 1){
                $this->buffer.= '<th class="tdcenter">'.number_format($valorPn,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPc,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
            }
            $this->buffer.= '<th class="tdright '.$this->color($promedioTotal).'">'.$promedioTotal.'%</th>
                                </tr>
                            </thead></table>';
            $this->bufferXml .='</tbody>
                            <thead>
                                <tr>
                                    <th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
                                    <th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
            if((int) $this->data['ponderaId'] == 1){
                $this->bufferXml.= '<th class="tdcenter">'.number_format($valorPn,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPc,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
            }           
            $this->bufferXml.= '<th class="tdright">'.number_format($promedioTotal,0,'.',',').'%</th>
                                </tr>
                            </thead></table>';
            $this->xml .= "</chart>";          
            $this->xmlPor.="</chart>";
        }
    }
     
     
    function generaCuadroEje(){
        $this->xml = $urlTmp = "";
        $contador = 1;
        $valorPg = $valorPy = $valorAct = $totalAvance = $sumRand = $valorPc= $valorPs = 0; 
        $valorPP = $valorPr = $valorPn =0;
        $rand = $randPonderada = 0;
        if(count($this->arrayDatos) > 0){
            $this->xml = "<chart palette='2' caption='Grafico de Actividades por Eje' labelDisplay='ROTATE' showValues='1' decimals='0' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
            $this->xmlPor="<chart palette='2' caption='Grafico de Ponderacion por Eje' labelDisplay='ROTATE' showValues='1' decimals='1' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
            $this->buffer = '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
            $this->buffer.= $this->cabeceras(1);
            $this->buffer.= '<tbody>';
            $this->bufferXml = $this->buffer;
            foreach($this->arrayDatos as $ind => $tmp){
                $rand   = $tmp['avanceFinal'];
                $randPonderada = $rand * $tmp['ponderacionEje'];
                $urlTmp = $this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=1&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$ind."&ponderaId=".$this->data['ponderaId'];
                $valorPg  = $valorPg  +  (int) $tmp['programa_id'] + 0;
                $valorPy  = $valorPy  +  (int) $tmp['proyecto_id'] + 0;
                $valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
                $valorPn  = $valorPn  +  (double) $tmp['ponderacion'] + 0;
                $valorPP  = $valorPP  +  (double) $tmp['totalPonderacion'] + 0;
                $valorPc  = $valorPc  +  (double) $tmp['procesadas'] + 0;
                $valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
                $valorPs  = $valorPs  +  (double) $tmp['ponderacionEje'] + 0;
                $totalAvance = $totalAvance + $rand + 0;
                $this->buffer.= '<tr>
                                    <td class="tdleft">'.$contador.'</td>
                                    <td class="tdleft"><a href="'.$urlTmp.'" target="_self" class="liga">'.$tmp['eje'].'</a></td>
                                    <td class="tdcenter">'.number_format($tmp['programa_id'],0,'.',',').'</td>
                                    <td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
                                    <td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
                if((int) $this->data['ponderaId'] == 1){
                    $this->buffer.= ' <td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
                                     <td class="tdcenter">'.number_format($tmp['procesadas'],0,'.',',').'</td>
                                     <td class="tdcenter">'.number_format($tmp['totalPonderacion'],0,'.',',').'</td>
                                     <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
                }               
                $this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand.'%</td></tr>';             
                //Excel
                $this->bufferXml .='<tr>
                                    <td class="tdleft">'.$contador.'</td>
                                    <td class="tdleft">'.$tmp['eje'].'</td>
                                    <td class="tdcenter">'.number_format($tmp['programa_id'],0,'.',',').'</td>
                                    <td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
                                    <td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
                if((int) $this->data['ponderaId'] == 1){     
                    $this->bufferXml.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
                                        <td class="tdcenter">'.number_format($tmp['procesadas'],0,'.',',').'</td>
                                        <td class="tdcenter">'.number_format($tmp['totalPonderacion'],0,'.',',').'</td>
                                        <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
                }
                $this->bufferXml.= '<td class="tdright">'.$rand. '%</td></tr>';
                 
                $this->xml.="<set label='".$contador."' tooltext='".$this->catalogoA[$ind]." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."' value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."' />";
                $this->xmlPor.="<set label='".$contador."' tooltext='".$this->catalogoA[$ind]." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
                $contador++;
                $sumRand = (double) $sumRand + $randPonderada;
            }
            $promedioTotal = 0;
            if($valorPs > 0){
                $promedioTotal = ($sumRand / $valorPs)*1;
                $promedioTotal = number_format($promedioTotal,2,'.',',')*1;
            }
            if($promedioTotal> 100){
                $promedioTotal = number_format(100,2,'.',',');
            }
            $this->buffer.= '</tbody>
                            <thead>
                                <tr>
                                    <th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
                                    <th class="tdcenter">'.number_format($valorPg,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
            if((int) $this->data['ponderaId'] == 1){
                $this->buffer.= '<td class="tdcenter">'.number_format($valorPn,0,'.',',').'</td>
                                 <th class="tdcenter">'.number_format($valorPc,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
            }
            $this->buffer.= '<th class="tdright '.$this->color($promedioTotal).'">'.$promedioTotal.'%</th>
                                </tr>
                            </thead></table>';
            $this->bufferXml .='</tbody>
                            <thead>
                                <tr>
                                    <th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
                                    <th class="tdcenter">'.number_format($valorPg,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
            if((int) $this->data['ponderaId'] == 1){
                $this->bufferXml.= '<th class="tdcenter">'.number_format($valorPc,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
            }
             
            $this->bufferXml.= '<th class="tdright">'.number_format($promedioTotal,0,'.',',').'%</th>
                                </tr>
                            </thead></table>';
            $this->xml .= "</chart>";
            $this->xmlPor.="</chart>";
        }
    }
     
     
    function generaCuadroArea(){
        $this->xml = $urlTmp = "";
        $contador = 1;
        $valorPg = $valorPy = $valorAct = $totalAvance =$valorPc = $valorPs = 0; 
        $valorPP = $valorPr = $valorPn  = 0;
        $rand = $randPonderada = 0;
        if(count($this->arrayDatos) > 0){
            $this->xml = "<chart palette='2' caption='Grafico de Actividades por Unidad Responsable' labelDisplay='ROTATE' showValues='1' decimals='0' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
            $this->xmlPor="<chart palette='2' caption='Grafico de Ponderacion por Unidad Responsable' labelDisplay='ROTATE' showValues='1' decimals='1' formatNumberScale='0' exportEnabled='1' exportAtClient='1' exportHandler='fcExporter301' exportType='PNG=Exportar como imagen'>";
            $this->buffer = '<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
            $this->buffer.= $this->cabeceras(4);
            $this->buffer.= '<tbody>';
            $this->bufferXml = $this->buffer;
            foreach($this->arrayDatos as $ind => $tmp){
                $rand = (double) $tmp['avanceFinal'];
                $randPonderada = $rand * $tmp['ponderacionArea'];
                $urlTmp = $this->path."index.php?tablaId=".$this->data['tablaId']."&tipoId=2&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$ind."&ponderaId=".$this->data['ponderaId'];
                $valorPg  = $valorPg  +  (int) $tmp['programa_id'] + 0;
                $valorPy  = $valorPy  +  (int) $tmp['proyecto_id'] + 0;
                $valorAct = $valorAct +  (int) $tmp['actividad_id'] + 0;
                $valorPP  = $valorPP  +  (double) $tmp['totalPonderacion'] + 0;
                $valorPn  = $valorPn  +  (double) $tmp['ponderacion'] + 0;
                $valorPr  = $valorPr  +  (double) $tmp['promedio'] + 0;
                $valorPc  = $valorPc  +  (double) $tmp['procesadas'] + 0;
                $valorPs  = $valorPs  +  (double) $tmp['ponderacionArea'] + 0;
                $totalAvance = $totalAvance + $rand + 0;
                $this->buffer.= '<tr>
                                    <td class="tdleft">'.$contador.'</td>
                                    <td class="tdleft"><a href="'.$urlTmp.'" target="_self" class="liga">'.$tmp['area'].'</a></td>
                                    <td class="tdcenter">'.number_format($tmp['programa_id'],0,'.',',').'</td>
                                    <td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
                                    <td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
                if((int) $this->data['ponderaId'] == 1){
                    $this->buffer.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
                                     <td class="tdcenter">'.number_format($tmp['procesadas'],0,'.',',').'</td>
                                     <td class="tdcenter">'.number_format($tmp['totalPonderacion'],0,'.',',').'</td>
                                     <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
                }
                 
                $this->buffer.= '<td class="tdright '.$this->color($rand).'">'.$rand. '%</td></tr>';
                $this->bufferXml .= '<tr>
                                    <td class="tdleft">'.$contador.'</td>
                                    <td class="tdleft">'.$tmp['area'].'</td>
                                    <td class="tdcenter">'.number_format($tmp['programa_id'],0,'.',',').'</td>
                                    <td class="tdcenter">'.number_format($tmp['proyecto_id'],0,'.',',').'</td>
                                    <td class="tdcenter">'.number_format($tmp['actividad_id'],0,'.',',').'</td>';
                if((int) $this->data['ponderaId'] == 1){
                    $this->bufferXml.= '<td class="tdcenter">'.number_format($tmp['ponderacion'],0,'.',',').'</td>
                                        <td class="tdcenter">'.number_format($tmp['procesadas'],0,'.',',').'</td>
                                        <td class="tdcenter">'.number_format($tmp['totalPonderacion'],0,'.',',').'</td>
                                        <td class="tdcenter">'.number_format($tmp['promedio'],2,'.',',').'</td>';
                }
                $this->bufferXml.= '<td class="tdright">'.$rand. '%</td>
                                </tr>';       
                 
                $this->xml.="<set label='".$contador."' tooltext='".$this->catalogoC[$ind]." - Actividades: ".number_format($tmp['actividad_id'],0,'.',',')."'  value='".number_format($tmp['actividad_id'],0,'.',',')."' showvalue='1' link='".$urlTmp."' />";
                $this->xmlPor.="<set label='".$contador."' tooltext='".$this->catalogoC[$ind]." - Ponderacion: ".number_format($rand,0,'.',',')."' value='".number_format($rand,2,'.',',')."' showvalue='1' link='".$urlTmp."' />";
                $contador++;
                $sumRand = $sumRand + $randPonderada;
            }
            $promedioTotal = 0;
            if($valorPs > 0){
                $promedioTotal = ($sumRand / $valorPs)*1;               
                $promedioTotal = number_format($promedioTotal,2,'.',',');
            }
            if($promedioTotal> 100){
                $promedioTotal = number_format(100,2,'.',',');
            }
                 
            $this->buffer.= '</tbody>
                            <thead>
                                <tr>
                                    <th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
                                    <th class="tdcenter">'.number_format($valorPg,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
            if((int) $this->data['ponderaId'] == 1){
                $this->buffer.= '<th class="tdcenter">'.number_format($valorPn,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPc,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
                                 <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
            }
            $this->buffer.= '<th class="tdright '.$this->color($promedioTotal).'">'.$promedioTotal.'%</th>
                                </tr>
                            </thead></table>';
            $this->bufferXml .='</tbody>
                            <thead>
                                <tr>
                                    <th class="tdleft" colspan="2">Totales: '.count($this->arrayDatos).' registros.</th>
                                    <th class="tdcenter">'.number_format($valorPg,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPy,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorAct,0,'.',',').'</th>';
            if((int) $this->data['ponderaId'] == 1){
                $this->bufferXml.= '<th class="tdcenter">'.number_format($valorPn,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPc,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPP,0,'.',',').'</th>
                                    <th class="tdcenter">'.number_format($valorPr,2,'.',',').'</th>';
            }           
            $this->bufferXml.= '<th class="tdright">'.number_format($promedioTotal,0,'.',',').'%</th>
                                </tr>
                            </thead></table>';
            $this->xml .= "</chart>";
            $this->xmlPor.="</chart>";
        }
    }
     
    function generaTotalesProyecto($proyectos){
        $totales = array();
        $pondera = array();
        $arrayProgramas = $ponderaProg = array();       
        if(count($proyectos) > 0){
            foreach($proyectos as $idEje => $tmpEje){
                foreach($tmpEje as $idPrograma => $tmpProg){
                    foreach($tmpProg as $data){
                        $valor = 0;
                        if($data['totalPonderacion'] > 0){
                            $valor = (($data['promedio'] / $data['totalPonderacion']) * 1);
                            if($valor > 100){
                                $valor = 100;
                            }
                            $valor = number_format($valor,2);
                            $valor = ($valor * $data['ponderacionProy']);
                        }
                        $totales[$idEje][$idPrograma] = (double) $totales[$idEje][$idPrograma] + $valor;
                        $pondera[$idEje][$idPrograma] = (int) $pondera[$idEje][$idPrograma] + $data['ponderacionProy'];
                        $ponderaProg[$idEje][$idPrograma] = (int)  $data['ponderacionProg'];
                    }
                }
            }
            $sumAvanceProg = $sumPonderacionProg = 0;
            if(count($totales) > 0){
                foreach($totales as $idEje => $tmpEje){
                    $sumAvanceProg = $sumPonderacionProg = 0;
                    foreach($tmpEje as $idPrograma => $value){
                        $arrayProgramas[$idEje][$idPrograma]['avanceFinal']  = 0;
                        if((int) $pondera[$idEje][$idPrograma] > 0){                         
                            $valor = (double) $totales[$idEje][$idPrograma] / $pondera[$idEje][$idPrograma];
                            $valor = number_format($valor,2) * $ponderaProg[$idEje][$idPrograma];
                            $sumAvanceProg = $sumAvanceProg + $valor;
                            $sumPonderacionProg = $sumPonderacionProg + $ponderaProg[$idEje][$idPrograma];
                            $arrayProgramas[$idEje][$idPrograma]['avanceFinal'] = ($valor *  $ponderaProg[$idEje][$idPrograma]);
                        }                       
                    }
                    $this->arrayDatos[$idEje]['avanceFinal'] = number_format(($sumAvanceProg / $sumPonderacionProg ),2);
                }               
            }
        }       
    }
    function calculaPonderacion($tmp){
        $rand = 0;      
        if($tmp['totalPonderacion'] > 0){
            $rand = (($tmp['promedio'] / $tmp['totalPonderacion']) *1);
            if($rand > 100){
                $rand = 100;
            }
        }
        $rand = number_format($rand,2,'.',',');
        return $rand;
    }
    function color($rand){
        $color = "";
        if($rand >=80)
            $color = " success ";
        elseif($rand >=60 && $rand <80){
            $color = " warning ";
        }else{
            $color = " danger ";
        }
        return $color;
    }
    /**
     * Metodo que se encarga de crear el filtro segun la eleccion del usuario
     */
    function generaFiltro(){
        $this->filtro = "AND activoP = '1' AND activoA = '1' ";
        if( (int) $this->data['anoId'] > 0){
            $this->filtro .= " AND ano_id = '".$this->data['anoId']."' ";
            $this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."'> / A&ntilde;o ".$this->data['anoId']."</a>";
        }
        if( (int) $this->data['trimestreId'] > 0){
            $this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."'> / Trimestre ".$this->data['trimestreId']."</a>";
        }
        if( (int) $this->data['idEje'] > 0){
            $this->filtro.= " AND eje_id = '".$this->data['idEje']."' ";
            $this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."'> / ".$this->catalogoA[$this->data['idEje']]."</a> ";
        }
        if( (int) $this->data['idPrograma'] > 0){
            $this->filtro .= " AND programa_id = '".$this->data['idPrograma']."' ";           
            $this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."&idPrograma=".$this->data['idPrograma']."'> / ".$this->catalogoB[$this->data['idPrograma']]."</a> ";
        }
        if( (int) $this->data['idPrograma'] > 0 && (int) $this->data['idProyecto'] > 0 ){
            $this->filtro .= " AND id = '".$this->data['idProyecto']."' ";
            $this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idEje=".$this->data['idEje']."&idPrograma=".$this->data['idPrograma']."&idProyecto=".$this->data['idProyecto']."'> / ".$this->catalogoD[$this->data['idProyecto']]."</a> ";            
        }       
        if( (int) $this->data['idArea'] > 0 ){            
            $this->filtro .= " AND unidadResponsableId = '".$this->data['idArea']."' ";
            $this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$this->data['idArea']."'> / ".$this->catalogoC[$this->data['idArea']]."</a> ";          
        }
        if( (int) $this->data['idArea'] > 0 && (int) $this->data['idProyecto'] > 0 ){
            $this->filtro .= " AND id = '".$this->data['idProyecto']."' ";
            $this->breadcrumb .= "<a href='".$this->path."index.php?ponderaId=".$this->data['ponderaId']."&tablaId=".$this->data['tablaId']."&tipoId=".$this->data['tipoId']."&anoId=".$this->data['anoId']."&trimestreId=".$this->data['trimestreId']."&idArea=".$this->data['idArea']."&idProyecto=".$this->data['idProyecto']."'> / ".$this->catalogoD[$this->data['idProyecto']]."</a> ";          
        }
    }
     
    function pintaGrafico($array,$array_asis,$array_areas,$opc_tit) {
        $strXML='';
        if(count($array) > 0) {
            $strXML  = "<chart caption='".$this->titulo."' xAxisName='' yAxisName='Total' showValues='0' formatNumberScale='1' showBorder='1'>";
            foreach($array as  $key => $value) {
                $color=$this->genera_color();
                $strXML .= "<set label='Act' value='".$value."' color='".$color."' showValues='0' toolText='Actividades: ".$array_areas[$key]."  ".$value."'/>
                <set label='Asi' value='".$array_asis[$key]."' color='".$color."' showValues='0' toolText='Asistentes: ".$array_areas[$key]."  ".$array_asis[$key]."'/>";
            }
            $strXML .= "</chart>";
        }
        return $strXML; 
    }
     
    function genera_color(){
        mt_srand((double) microtime() * 1000000);
        $color = '';
        while (strlen($color) < 6)
        {
            $color .= sprintf("%02X", mt_rand(0, 255));
        }
        return $color;
    }
     
    function obtenNombreTabla(){
        return $this->tabla;
    }
     
    function obtenTabla(){
        return $this->buffer;
    }
     
    function obtenBreadcrumb(){
        return $this->breadcrumb;
    }
     
    function obtenTitulo(){
        return $this->titulo;
    }
    function obtenXml(){
        return $this->xml;
    }
    function obtenXmlPor(){
        return $this->xmlPor;
    }
    function obtenTablaXls(){
        return $this->bufferXml;
    }
}
?>