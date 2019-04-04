<?php
include_once("../config.php");
include_once("../includescript.php");
include_once ($path_cla ."Mysql.class.php");
$db  = new Mysql ( $_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true );

$table="";
$sql = "SELECT a.id,e.nm_eje,c.nombre as area,d.nombre as programa,a.proyecto,
		a.ponderacion as ponderacionProyecto,b.actividad,b.ponderacion as ponderacionActividad
		FROM proyectos_acciones as a 
		left join proyectos_actividades as b on a.id=b.proyecto_id		
		inner join cat_areas as c on a.unidadResponsable_id = c.area_id 
		inner join cat_programas as d on a.programa_id=d.programa_id
		inner join viewTemporal as e on a.unidadResponsable_id = e.area_id
		WHERE a.active='1' ORDER BY a.unidadResponsable_id,a.programa_id,a.id;";
$res = $db->sql_query($sql) or die(print_r($db->sql_error()));
$contador=1;
if($db->sql_numrows($res) > 0)
{
	$table="<table width='100%' align='center' border='2'>
			<tr><th>Id Proyecto</th><th>Eje</th><th>Area</th><th>Programa</th><th>Proyecto</th>
			<th>Ponderacion Proyecto</th><th>Actividad</th><th>Ponderacion Actividad</th></tr>";
	while(list($id,$eje,$area,$programa,$proyecto,$pondeP,$actividad,$pondeA) = $db->sql_fetchrow($res))
	{
		$table.="<tr>
				<td>".$id."</td>
				<td>".$eje."</td>
				<td>".$area."</td>
				<td>".$programa."</td>
				<td>".$proyecto."</td>
				<td>".$pondeP."</td>
				<td>".$actividad."</td>
				<td>".$pondeA."</td>
				</tr>";
		$contador++;
	}
	$table.="</table>";
	echo $table;
}
?>
