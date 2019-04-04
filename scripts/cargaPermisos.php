<?php
include_once("../include.php");
include_once ($path_cla . "Mysql.class.php");
$db = new Mysql ( $_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true );


$array_menus=array();
$array = array();
/* $sqlr="SELECT a.menu_id, b.submenu_id
		FROM cat_menu a
		LEFT JOIN cat_submenu AS b ON a.menu_id = b.menu_id
		WHERE b.submenu_id is not null 
		ORDER BY a.menu_id, b.submenu_id;";
 */$sqlr="SELECT area_id,programa_id FROM `cat_area_programa` WHERE 1 order by area_id,programa_id;";
$resr=$db->sql_query($sqlr);
if($db->sql_numrows($resr)){
	//$array[]="1|0";
	while(list($idMenu,$idSubmenu) = $db->sql_fetchrow($resr)){
		$array[]=$idMenu."|".$idSubmenu;
	}
	//$array[]="7|0";
}

$sql="SELECT user_id FROM cat_usuarios WHERE user_nivel='99' ORDER BY user_id";
$res=$db->sql_query($sql);
if($db->sql_numrows($res)){
	while(list($id) = $db->sql_fetchrow($res)){
		foreach($array as $valor){
			$tmp=explode('|',$valor);
			$ins="INSERT INTO  cat_permisos_areas (usuario_id,area_id,programa_id)
				  VALUES ('".$id."','".$tmp[0]."','".$tmp[1]."');";
			$db->sql_query($ins);
			
		}		
	}
}
