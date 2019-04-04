<?php
session_start();
if (!isset($_SESSION['userId'])){
	$_SESSION ['userNm'] = "Visitante";
	$_SESSION['userId'] = -1;
}
include_once("../include.php");
include_once ($path_sis . "lang/es.php");
include_once ($path_cla . "Mysql.class.php");
include_once ($path_cla . "FusionCharts.php");
include_once ($path_cla . "CatalogosEst.class.php");
include_once ($path_cla . "ComunesEstadisticas.class.php");
include_once ($path_cla . "GeneraEstadisticas.class.php");
include_once ($path_cla . "Out.class.php");
$db     = new Mysql ( $_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true );
$objCat = new CatalogosEst($db, $_REQUEST,$_SESSION);
$array  = $objCat->obtenArray();
$objeto = new GeneraEstadisticas($db,$_REQUEST,$_SESSION,$path_est_web);
$tabla  = $objeto->obtenTabla();
$tablaXls  = $objeto->obtenTablaXls();
$bread  = $objeto->obtenBreadcrumb();
$titulo = $objeto->obtenTitulo();
$xml    = $objeto->obtenXml();
$xmlPor = $objeto->obtenXmlPor();
$nmTabla= $objeto->obtenNombreTabla();
$descar = $grafic =  "";
if($xml != ""){
	$objOut = new Out($path_web,$path_sis,$tablaXls,$xml,$xmlPor);
	$descar = $objOut->getBufferTabla();
	$grafic = $objOut->getBufferGrafica();
	$grafib = $objOut->getBufferGraficaB(); 
}
$select1 = $select2 = "";
if($_REQUEST['ponderaId'] == 1){
	$select1 = "selected ";
	$select2 = "";
}
if($_REQUEST['ponderaId'] == 2){
	$select2 = "selected ";
	$select1 = "";
}
include_once($path_est_sis."cabecerasEst.php");

?>
<body>
<form name="estaditica" id="estadistica" method="post" action="<?=$path_est_web?>index.php">
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-primary navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=$path_est_web?>"><span style="font-weight:bold;color:#337ab7;"><?=$titleEnc?></span></a>
            </div>
            <?php if((int) $_SESSION['userId'] > 0){?>            
			<ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span style="font-weight:none;color:#333;"><?=utf8_encode($_SESSION ['userNm'])?></span>
                       <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?=$path_web?>aplicacion.php"><i class="fa fa-user fa-fw"></i> Regresar a Sistema</a></li>
                        <li class="divider"></li>
                        <li><a href="<?=$path_web?>logout.php"><i class="fa fa-sign-out fa-fw"></i>Cerrar Sesi&oacute;n</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <?php }?>
            <!-- /.navbar-top-links -->
			<br><br>
            <div class="navbar-primary sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                   <!-- <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Buscar...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                        </li>
                    </ul> --> 
                    <center>
                    
                    <?php if((int) $_SESSION['userId'] > 0){?>
	                    <select name="tablaId" id="tablaId" class="form-control">
	                    <option bgcolor="#e5e5e5" value="0">Seleccione Tabla</option>	                    
	                    	<?=$array[4]?>
	                    </select><br>
	                    <select name="ponderaId" id="ponderaId" class="form-control">
	                    
	                         <option bgcolor="#e5e5e5" value="2" <?=$select2?>>No datos extra</option>
	                         <option bgcolor="#e5e5e5" value="1" <?=$select1?>>Datos Extra</option>
	                     </select><br>               
                    <?php
					}
                    else{
                    ?>
                    	<input type="hidden" name="tablaId" id="tablaId" value="<?=$array[4]?>">
                    <?php 
                    }
                    ?>
						<select name="anoId" id="anoId" class="form-control">
	                   	<?=$array[0]?>
	                	</select><br>
						<select name="trimestreId" id="trimestreId" class="form-control">
	                   	<?=$array[7]?>
	                	</select><br>
	                     <select name="tipoId" id="tipoId" class="form-control">
	                    	<?=$array[6]?>
	                    </select><br>
						<button type="submit" name="consultar" id="consultar" class="btn btn-default">Consultar</button>
					</center>                            
                </div>
            </div>
        </nav>

        <div id="page-wrapper">
            <div class="row">
            <br>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>&nbsp;<b>Cuadro Estadistico</b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">                                	
                                 	<div class="dataTable_wrapper">
                                 		<?=$bread?><br/><br/>
                                 		<center>
                                 			<?=$titulo?><br/>
                                 			<?=$tabla?>
                                 			<?=$descar?>
                                 		</center><br>
                                 	</div>
                            	</div>
							</div>
							<?php if(trim($grafic)!= ""){?>
							<div class="row">
							    <div class="col-lg-12">
							    	<table class="table table-bordered">
							    	<tr>
							    	<td class="tdcenter" width="50%"><?=$grafic?></td>
							    	<td class="tdcenter" width="50%"><?=$grafib?></td>
							    	</tr>
							    	</table>
                            	</div>
                            </div>
							<?php }?>
                        </div>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</form>    
<div class="modal fade" id="myModalComentarios" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
               	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b><?=COMENTARIOS?></b></h4>
            </div>
            <div class="modal-body"  style="height:450px;overflow-y: auto;">
            	<p style="text-align:justify" id="comen"></p>
            </div>
            <div class="modal-footer tdcenter">
	        	<button type="button" class="btn btn-default cerrarAccion" id="cerrarAccion" name="cerrarAccion"><?=CERRARVENTANA?></button>
        	</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModalAdjuntos" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            	<h4 class="modal-title"><b><?=ADJUNTOS?></b></h4>
            </div>
            <div class="modal-body"  style="height:450px;overflow-y: auto;">
            	<p style="text-align:justify" id="adjun"></p>
            </div>
            <div class="modal-footer tdcenter">
	        	<button type="button" class="btn btn-default cerrarAccion" id="cerrarAccion1" name="cerrarAccion1"><?=CERRARVENTANA?></button>
        	</div>
		</div>
	</div>
</div>

</body>
</html>