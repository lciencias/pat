<?php
include_once("../include.php");
include_once ($path_cla . "Mysql.class.php");
$db = new Mysql ( $_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true );


$array_menus=array();
$array = array();
$sqlr="SELECT id,unidadResponsable_id,programa_id FROM `proyectos_acciones` WHERE 1 order by id;";
echo $sqlr;
$resr=$db->sql_query($sqlr);
if($db->sql_numrows($resr)){
	while(list($id,$area,$programa) = $db->sql_fetchrow($resr)){
		$array[]=$id."|".$area."|".$programa;
	}
	insertaEstatus($db,$array);
}
// echo"<pre>";
// print_r($array);
function insertaEstatus($db,$array){
	if(count($array)>0){
		foreach($array as $ind){
			$tmp=explode("|",$ind);
			$ins_estatus=" INSERT INTO cat_proyectos (proyecto_id,unidad_responsable_id,programa_id) VALUES ('".$tmp[0]."','".$tmp[1]."','".$tmp[2]."');";
			echo"<br>".$ins_estatus;
			$res_estatus=$db->sql_query($ins_estatus) or die($this->cadena_error);
			echo "<br>".$ins_estatus;
		}
	}
}