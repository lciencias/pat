<?php
include_once("../include.php");
include_once ($path_cla . "Mysql.class.php");
$db = new Mysql ( $_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true );


$array_menus=array();
$array = array();
$sqlr="SELECT id FROM `proyectos_acciones` WHERE 1 order by id;";
$resr=$db->sql_query($sqlr);
if($db->sql_numrows($resr)){
	while(list($id) = $db->sql_fetchrow($resr)){
		$array[]=$id;
	}
	insertaEstatus($db,$array);
}

function insertaEstatus($db,$array){
	if(count($array)>0){
		foreach($array as $ind){
			$ins_estatus=" INSERT INTO proyectos_avances_estatus (proyecto_id) VALUES ('".$ind."');";
			$res_estatus=$db->sql_query($ins_estatus) or die($this->cadena_error);
			echo "<br>".$ins_estatus;
		}
	}
}