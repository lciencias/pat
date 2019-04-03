<?php

include_once("../includeAjax.php");
include_once($path_sis."config.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."Comunes.class.php");
include_once($path_int."InterfazCatalogos.php");
include_once($path_cla."Paginador.class.php");
include_once($path_cla."EjedePolitica.class.php");
include_once($path_cla."PoliticaPublica.class.php");
include_once($path_cla."UnidadResponsable.class.php");
include_once($path_cla."UnidadPrograma.class.php");
include_once($path_cla."ObjetivosGenerales.class.php");
include_once($path_cla."EventosArtisticos.class.php");
include_once($path_cla."Hospitales.class.php");
include_once($path_cla."Recintos.class.php");
include_once($path_cla."Medidas.class.php");
include_once($path_cla."Proyectos.class.php");
include_once($path_cla."UnidadOperativa.class.php");
include_once($path_cla."Anos.class.php");
include_once($path_cla."Usuarios.class.php");
include_once($path_cla."TipoActividad.class.php");
include_once($path_cla."Ponderacion.class.php");
include_once($path_int."InterfazCatalogos.php");
include_once($path_cla."ComunesEstadisticas.class.php");
include_once($path_cla."CrearTablas.class.php");
$db     = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$pages  = new Paginador();
$content = "";

switch($_REQUEST['tableId']){
  case 1:
    $objAct = new EjedePolitica($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;
  case 2:
    $objAct = new PoliticaPublica($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;
  case 3:
    $objAct = new UnidadResponsable($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;
 case 4:
    $objAct = new UnidadPrograma($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;
  case 5:
    $objAct = new ObjetivosGenerales($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;   
  case 6:
    $objAct = new EventosArtisticos($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;
  case 7:
    $objAct = new Hospitales($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;
  case 8:
    $objAct = new Recintos($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;
  case 9:
    $objAct = new Medidas($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;
  case 10:
    $objAct = new Proyectos($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;
  case 11:
    $objAct = new UnidadOperativa($db,$_REQUEST,$_SESSION,$path_web,$pages);
    $content = $objAct->obtenBuffer();
    break;  
  case 12:
  	$objAnt = new InsertaAdjuntos($db,0,0,$path_files,$path_img,"",3,$_REQUEST);
  	$content = $objAct->obtenId();
  	break;
  case 13:
  	$objAct = new Anos($db,$_REQUEST,$_SESSION,$path_web,$pages);
  	$content = $objAct->obtenFolio();
  	break;  	
  case 14:
  	$objAct = new TipoActividad($db,$_REQUEST,$_SESSION,$path_web,$pages);
  	$content = $objAct->obtenBuffer();
  	break;
  case 15:
  	$objAct = new Ponderacion($db,$_REQUEST,$_SESSION,$path_web,$pages);
  	$content = $objAct->obtenBuffer();
  	break;
  case 16:
  	$objAct = new Usuarios($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
  	$content = $objAct->obtenBuffer();
  	break;
  case 17:
  	$objAct = new CrearTablas($db,$_REQUEST,$_SESSION,$_SERVER,$path_web,$pages);
  	$content = $objAct->obtenFolio();
  	break;  		
}
echo $content;
?>