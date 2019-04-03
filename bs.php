<?php
error_reporting( E_ALL & ~E_NOTICE );
@set_time_limit( 0 );
ini_set('display_errors', 'on');
ini_set('zlib_output_compression','On');
include_once ("include.php");
$mensaje_final	= "";
$fecha   		= date("Y-m-d");
$fechahd 		= date("Y-m-d H-i-s");
$date    		= date("Y-m-d");
$db_connect 	= mysql_connect($_dbhost,$_dbuname,$_dbpass);
$base_selection = mysql_select_db($_dbname,$db_connect);
$filename     	= $path_sis."tmp/BackupPat".$date.".sql";
$filenameWeb  	= $path_web."tmp/BackupPat".$date.".sql";
$filename_href	= $path_web."tmp/BackupPat".$date.".sql.gz";
define( 'Str_VERS', "1.1.2" );
define( 'Str_DATE', "24 de Abril de 2015" );
//eliminamos el archivo de la semana pasada.
eliminaRespaldos($db_connect,$fecha,$path_sis);
echo"<br>Backup de la Base de datos<br>";
$mensaje_final.="<br>Backup de la Base de datos<br>";
$error = false;
$tablas = 0;
$total_tablas = 0;
$total_rows = 0;

if( !@function_exists( 'gzopen' ) ) {
	$hay_Zlib = false;
    echo( "- Ya que no esta disponible Zlib, salvara la Base de Datos sin comprimir, como <a href='".$filenameWeb."'>BackupPat".$date.".sql</a><br>" );
}
else {
	$filename = $filename . ".gz";
    $hay_Zlib = true;
    echo( "- El nombre del Backup de la Base de Datos se llamara: '$filename_href'<br>" );
    $mensaje_final.="<br>- El nombre del Backup de la Base de Datos se llamara: ".$filename_href."<br><br><br>Tablas:<br>" ;
}
if( !$error ) {
	$dbconnection = @mysql_connect($_dbhost,$_dbuname,$_dbpass);
    if( $dbconnection){
		$db = mysql_select_db( $_dbname );
    }
    if( !$dbconnection || !$db ) {
    	echo( "<br>" );
        echo( "- La conexion con la Base de datos ha fallado: ".mysql_error()."<br>" );
        $error = true;
    }
}

if( !$error ) {
	$result = mysql_query( 'SELECT VERSION() AS version' );
    if( $result != FALSE && @mysql_num_rows($result) > 0 ){
    	$row   = mysql_fetch_array($result);
    }
    else{
    	$result = @mysql_query( 'SHOW VARIABLES LIKE \'version\'' );
        if( $result != FALSE && @mysql_num_rows($result) > 0 ){
        	$row   = mysql_fetch_row( $result );
		}
	}
    if(! isset($row) ) {
    	$row['version'] = '3.21.0';
	}
}

if( !$error ) {
	$el_path = getenv("REQUEST_URI");
    $el_path = substr($el_path, strpos($el_path, "/"), strrpos($el_path, "/"));
	$result = mysql_list_tables( $_dbname );
    if( !$result ) {
    	echo "<br>- Error, no puedo obtener la lista de las tablas.<br>";
        echo "<br>- MySQL Error: ".mysql_error()."<br><br>";
        $error = true;
	}
    else{
    	$t_start = time();           
        if( !$hay_Zlib )
        	$filehandle = fopen( $filename, 'w' );
		else
        	$filehandle = gzopen( $filename, 'w6' );    //  nivel de compresion
               
		if( !$filehandle ) {
			$el_path = getenv("REQUEST_URI");
            $el_path = substr($el_path, strpos($el_path, "/"), strrpos($el_path, "/"));
            echo( "<br>" );
            echo( "- No he podido crear '$filename' en '$el_path/'. Por favor, asegï¿½rese de<br>" );
            echo( "&nbsp;&nbsp;que dispone de privilegios de escritura.<br>" );
		}
        else
        {                   
        	$tabledump = "-- Dump de la Base de Datos.\n
        				  -- Fecha:".strftime("%A %d %B %Y - %H:%M:%S", time() )."\n
        				  -- Version:".Str_VERS.", del ".Str_DATE.", lciencias@gmail.com\n";
        	 
            if( !$hay_Zlib )
            	fwrite( $filehandle, $tabledump );
			else
            	gzwrite( $filehandle, $tabledump );   
			setlocale( LC_TIME,"spanish" );
            if( !$hay_Zlib )
            	fwrite( $filehandle, $tabledump );
			else
            	gzwrite( $filehandle, $tabledump );   
            $tabledump = "--\n";
            if( !$hay_Zlib )
            	fwrite( $filehandle, $tabledump );
			else
            	gzwrite( $filehandle, $tabledump );   
			
            if( !$hay_Zlib )
                fwrite( $filehandle, $tabledump );
            else
                gzwrite( $filehandle, $tabledump );   
            if( !$hay_Zlib )
                fwrite( $filehandle, $tabledump );
            else
                gzwrite( $filehandle, $tabledump );   
            if( !$hay_Zlib )
                fwrite( $filehandle, $tabledump );
            else
                gzwrite( $filehandle, $tabledump );   

            if( !$hay_Zlib )
                fwrite( $filehandle, $tabledump );
            else
                gzwrite( $filehandle, $tabledump );   
            $tabledump = "* * * * * * * * * * * * * * * * * * * * * * * * \n";
            if( !$hay_Zlib )
            	fwrite( $filehandle, $tabledump );
            else
                gzwrite( $filehandle, $tabledump );   
            $tabledump = "-- Server version    ". $row['version'] . "\n\n";
            if( !$hay_Zlib )
                fwrite( $filehandle, $tabledump );
            else
                gzwrite( $filehandle, $tabledump );   
            $result = query( 'SHOW tables' );
            while( $currow = fetch_array($result, DBARRAY_NUM) )
            {
				if($currow[0] == 'load')
                {
                	$currow[0] =" `load` ";
                }
                $mensaje_final.="<br>".$currow[0];
                echo"<br>".$currow[0];
                $total_tablas++;
                $st = number_format($total_tablas, 0, ',', '.');
                $total_rows += fetch_table_dump_sql( $currow[0], $filehandle );
                $sc = number_format($total_rows, 0, ',', '.');
                fwrite( $filehandle, "\n" );
                if( !$hay_Zlib )
                	fwrite( $filehandle, "\n" );
                else
                	gzwrite( $filehandle, "\n" );
                    $tablas++;
			}
            $tabledump = "\n-- Dump de la Base de Datos Completo.";
            if( !$hay_Zlib )
            	fwrite( $filehandle, $tabledump );
            else
            	gzwrite( $filehandle, $tabledump );   
            if( !$hay_Zlib )
            	fclose( $filehandle );
            else
            	gzclose( $filehandle );
   
            $t_now = time();
            $t_delta = $t_now - $t_start;
            if( !$t_delta )
            	$t_delta = 1;
                $t_delta = floor(($t_delta-(floor($t_delta/3600)*3600))/60)." minutos y ".floor($t_delta-(floor($t_delta/60))*60)." segundos.";
                $size = filesize($filename);
                $size = number_format($size, 0, ',', '.');
            }
            $mensaje_final.= "<br><br>Se ha creado el respaldo del dia:  ".$fechahd."<br><br>";            
            $header = 'MIME-Version: 1.0' . "\r\n";
            $header.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $header.= "from:lciencias@gmail.com\r\n";            
            mail("lciencias@gmail.com","Respaldo PAT",$mensaje_final,$header);            
        }
	}

    if( $dbconnection )
        mysql_close();
    
        
    /*************   FUNCIONES   *****************/
function eliminaRespaldos($db_connect,$fecha,$path_sis){
  	$fecha_sem_pas="";
   	$sql="SELECT DATE_SUB('".$fecha."', INTERVAL 7 DAY);";
   	$res=mysql_query($sql,$db_connect);
   	if(mysql_num_rows($res) > 0){
   		list($fecha_sem_pas) = mysql_fetch_array($res);
   		if(strlen(trim($fecha_sem_pas)) == 10){
   			$filesem_pas = $path_sis."tmp/BackupPat".$fecha_sem_pas.".sql.gz";
   			if(file_exists($filesem_pas)){
   				unlink($filesem_pas);
   			}
   		}
   	}
} 

function fetch_table_dump_sql($table, $fp = 0)
{
	$rows_en_tabla = 0;
	$tabledump = "--\n";
	if( !$hay_Zlib )
		fwrite($fp, $tabledump);
	else
		gzwrite($fp, $tabledump);
	$tabledump = "-- Table structure for table `$table`\n";
	if( !$hay_Zlib )
		fwrite($fp, $tabledump);
	else
		gzwrite($fp, $tabledump);
	$tabledump = "--\n\n";
	if( !$hay_Zlib )
		fwrite($fp, $tabledump);
	else
		gzwrite($fp, $tabledump);

	$tabledump = query_first("SHOW CREATE TABLE $table");
	strip_backticks($tabledump['Create Table']);
	$tabledump = "DROP TABLE IF EXISTS $table;\n" . $tabledump['Create Table'] . ";\n\n";
	if( !$hay_Zlib )
		fwrite($fp, $tabledump);
	else
		gzwrite($fp, $tabledump);

	$tabledump = "--\n";
	if( !$hay_Zlib )
		fwrite($fp, $tabledump);
	else
		gzwrite($fp, $tabledump);
	$tabledump = "-- Dumping data for table `$table`\n";
	if( !$hay_Zlib )
		fwrite($fp, $tabledump);
	else
		gzwrite($fp, $tabledump);
	$tabledump = "--\n\n";
	if( !$hay_Zlib )
		fwrite($fp, $tabledump);
	else
		gzwrite($fp, $tabledump);

	$tabledump = "LOCK TABLES $table WRITE;\n";
	if( !$hay_Zlib )
		fwrite($fp, $tabledump);
	else
		gzwrite($fp, $tabledump);

	$rows = query("SELECT * FROM $table");
	$numfields=mysql_num_fields($rows);
	while ($row = fetch_array($rows, DBARRAY_NUM))
	{
		$tabledump = "INSERT INTO $table VALUES(";
		$fieldcounter = -1;
		$firstfield = 1;
		// campos
		while (++$fieldcounter < $numfields)
		{
			if( !$firstfield)
			{
				$tabledump .= ', ';
			}
			else
			{
				$firstfield = 0;
			}
			if( !isset($row["$fieldcounter"]))
			{
				$tabledump .= 'NULL';
			}
			else
			{
				$tabledump .= "'" . mysql_escape_string($row["$fieldcounter"]) . "'";
			}
		}
		$tabledump .= ");\n";
		if( !$hay_Zlib )
			fwrite($fp, $tabledump);
		else
			gzwrite($fp, $tabledump);
		$rows_en_tabla++;
	}
	free_result($rows);
	$tabledump = "UNLOCK TABLES;\n";
	if( !$hay_Zlib )
		fwrite($fp, $tabledump);
	else
		gzwrite($fp, $tabledump);
	return $rows_en_tabla;
}

function strip_backticks(&$text)
{
	return $text;
}

function fetch_array($query_id=-1){
	if( $query_id!=-1){
		$query_id=$query_id;
	}
	$record = mysql_fetch_array($query_id);
	return $record;
}

function problemas($msg){
	$errdesc = mysql_error();
	$errno = mysql_errno();
	$message  = "<br>";
	$message .= "- Ha habido un problema accediendo a la Base de Datos<br>";
	$message .= "- Error $appname: $msg<br>";
	$message .= "- Error mysql: $errdesc<br>";
	$message .= "- Error n&uacute;mero mysql: $errno<br>";
	$message .= "- Script: ".getenv("REQUEST_URI")."<br>";
	$message .= "- Referer: ".getenv("HTTP_REFERER")."<br>";
	echo( "</strong><br><br><hr><center><small>" );
	setlocale( LC_TIME,"spanish" );
	echo strftime( "%A %d %B %Y&nbsp;-&nbsp;%H:%M:%S", time() );
	echo( "vers." . Str_VERS . "<br>" );
	echo( "</small></center>" );
	echo( "</BODY>" );
	echo( "</HTML>" );
	die("");
}

function free_result($query_id=-1){
	if( $query_id!=-1){
		$query_id=$query_id;
	}
	return @mysql_free_result($query_id);
}

function query_first($query_string){
	$res = query($query_string);
	$returnarray = fetch_array($res);
	free_result($res);
	return $returnarray;
}

function query($query_string){
	$query_id = mysql_query($query_string);
	if( !$query_id){
		problemas("Invalid SQL: ".$query_string);
	}
	return $query_id;
}

?>