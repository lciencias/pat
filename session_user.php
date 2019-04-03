<?php
session_start();
session_cache_limiter("nocache");
include_once("include.php");
include_once($path_cla."Mysql.class.php");
$db  = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
if (!isset($_SESSION["usuario"])){    
    include_once($path_cla."Session.class.php");
    $obj_s = new Session($db,$_SESSION,$_SERVER);
    $sesion_valida=$obj_s->Obten_Sesion();     
    $_SESSION["session"]=$sesion_valida;
}
?>
