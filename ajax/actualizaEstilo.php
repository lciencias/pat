<?php
include_once("../includeAjax.php");
 include_once($path_sis."config.php");
 include_once($path_sis."revisaSesion.php");
 include_once($path_sis."lang/es.php");
 include_once($path_cla."Mysql.class.php");
 include_once($path_cla."ActualizaEstilo.class.php");
 $db  = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
 $obj = new ActualizaEstilo($db,$_REQUEST,$_SESSION,$_SERVER,$path_web);
 $_SESSION['estilo']=$_REQUEST['style'];
 echo $obj->obtenBuffer();
?>