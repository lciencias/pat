<?php
include_once("../config.php");
include_once("../includescript.php");
include_once ($path_cla ."Mysql.class.php");
$db  = new Mysql ( $_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true );

$table="";
$sql = "SELECT a.id,c.nombre as area,d.nombre as programa,a.proyecto,a.ponderacion as ponderacionProyecto,
		b.actividad,b.ponderacion as ponderacionActividad,b.tipo_actividad_id,e.nombre as medida 
		FROM proyectos_acciones as a 
		left join proyectos_actividades as b on a.id=b.proyecto_id
		inner join cat_areas as c on a.unidadResponsable_id = c.area_id 
		inner join cat_programas as d on a.programa_id=d.programa_id
		inner join cat_medidas as e on b.medida_id = e.medida_id
		WHERE a.ponderacion=5 or b.ponderacion=5 ORDER BY a.unidadResponsable_id,a.programa_id,a.id;";
$res = $db->sql_query($sql) or die(print_r($db->sql_error()));
$contador=1;
if($db->sql_numrows($res) > 0)
{
	$table="<table width='100%' align='center' border='2'>
			<tr><th>Consec</th><th>Area</th><th>Programa</th><th>Proyecto</th><th>Ponderacion Proyecto</th>
			<th>Actividad</th><th>Ponderacion Actividad</th><th>Tipo Actividad</th><th>Medida</th></tr>";
	while(list($id,$area,$programa,$proyecto,$pondeP,$actividad,$pondeA,$tipoAct,$medida) = $db->sql_fetchrow($res))
	{
		$table.="<tr>
				<td>".$contador."</td>
				<td>".$area."</td>
				<td>".$programa."</td>
				<td>".$proyecto."</td>
				<td>".$pondeP."</td>
				<td>".$actividad."</td>
				<td>".$pondeA."</td>
				<td>".$tipoAct."</td>
				<td>".$medida."</td>
				</tr>";
		$contador++;
	}
	$table.="</table>";
	echo $table;
}
?>
