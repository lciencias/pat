<?php
include_once("../includeAjax.php");
include_once($path_sis."config.php");
//include_once($path_sis."revisaSesion.php");
//include_once($path_sis."lang/es.php");
include_once($path_cla."Mysql.class.php");
include_once($path_cla."InsertaAdjuntos.class.php");
$db  = new Mysql($_dbhost, $_dbuname, $_dbpass, $_dbname, $persistency = true);
$error = "";
$msg = "";
$max_file=9999999999;
$filename = $filecurl="";
$random      = $_REQUEST['random'] + 0;
$idProyecto  = $_REQUEST['idProyecto']+ 0;
$idActividad = $_REQUEST['idActividad'] + 0;
$idTrimestre = $_REQUEST['idTrimestre'] + 0;
$id = $_REQUEST['id'] + 0;
$opc         = $_REQUEST['opc'] + 0;
$fileElementName = 'fileToUpload';
$repetido=false;
if($opc == 1){
	if (! empty($_FILES [$fileElementName] ['error'])) {
		switch ($_FILES [$fileElementName] ['error']) {
			case '1' :
				$error = "El archivo subido excede la directiva upload_max_filesize en php.ini";
				break;
			case '2' :
				$error =  "El archivo subido excede la directiva MAX_FILE_SIZE que se especifico en el formulario HTML.";
				break;
			case '3' :
				$error =  "El archivo subido se ha subido solo parcialmente.";
				break;
			case '4' :
				$error =  "No archivo fue subido.";
				break;
			case '6' :
				$error =  "Falta una carpeta temporal.";
				break;
			case '7' :
				$error =  "Error al escribir el archivo en el disco.";
				break;
			case '8' :
				$error =  "Carga de archivos se detuvo por extensi&oacute;n.";
				break;
			case '999' :
			default :
				$error =  "Codigo NO disponible";
		}
	} 
	elseif (empty ( $_FILES ['fileToUpload'] ['tmp_name'] ) || $_FILES ['fileToUpload'] ['tmp_name'] == 'none') {
		$error = "El archivo no se cargo, vuelva a intentarlo.";
	} 
	else{
		$filename = "";
		$msg .= "" . $_FILES ['fileToUpload'] ['name'] . "";
		$array_exts = array ('doc','xls','ppt','jpg','jpeg','pdf','png','gif','bmp','xlsx','docx','pptx','csv','zip','xml','bak','html','txt');
		$name_tmp = $_FILES ['fileToUpload'] ['tmp_name'];
		if ($name_tmp != ''){
			$size = @filesize ( $_FILES ['fileToUpload'] ['tmp_name'] );
			$type = $_FILES ['fileToUpload'] ['type'];
			$name = $_FILES ['fileToUpload'] ['name'];
			$lon_ext = strlen ( $_FILES ['fileToUpload'] ['name'] );
			$array_ext = explode ( '.', $_FILES ['fileToUpload'] ['name'] );
			$l = count ( $array_ext );
			$ext = strtolower($array_ext [($l - 1)]);
			$path = $path_files;
			$file = $path . $name;
			$filec= $path."F-".$idProyecto."-".$idActividad."-".$idTrimestre."-".$name;
			$filec=addslashes(trim($filec));
			$filel=addslashes(trim($filec));
			$filec=utf8_encode($filec);
			
			$filecurl=$path_web."downFiles/F-".$idProyecto."-".$idActividad."-".$idTrimestre."-".$name;
			// checamos el tamaño
			if($size <=0){
				$error = "El archivo debe contener informaci&oacute;n";
			}
			elseif ($size > $max_file){
				$error = "El tamano excede  ". $size;
			}
			else{
				// checamos el tipo de archivo
				if (! in_array ( $ext, $array_exts )) {
					$error = "Extensiones permitidas"." ".implode ( ', ', $array_exts ) . ".";
				}
				else{
					if (file_exists ( $filec )) {
						$repetido=true;
						@unlink ( $filec );
					}
					if (move_uploaded_file ( $name_tmp, $filec )){
						if( ($name != '') && (!$repetido)){						
							$objAdjuntos = new InsertaAdjuntos($db,$idProyecto,$idActividad,$idTrimestre,$filel,$path_img,$name,$opc,$_REQUEST,$path_web,$id,$_SESSION,$filecurl);
							if($objAdjuntos->obtenId() > 0 ){
								$filename=$objAdjuntos->obtenBuffer();
								$msg = EXITO;
							} 
							else{
								$msg="id  ".$objAdjuntos->obtenId();
							}
						}
						else{
							if($repetido)
								$error = "No puede subir un archivo ya cargado";
							else
								$error = "El archivo no contiene nombre, por favor reintente nuevamente";
						}
					}
					else{
						$error = "El archivo no subio correctamente, vuelva a intentarlo.   ".$file;
					}
				}
			}
		}
		else{
			$error = "Favor de seleccionar un archivo";
			$msg = '';
		}
		$msg .= $_FILES ['fileToUpload'] ['name'] . " ";
		@unlink ( $_FILES ['fileToUpload'] );
	}
	echo "{";
	echo "error: \"" . $error . "\",\n";
	echo "msg: \"" . $msg . "\",\n";
	echo "filename: '" . $filename . "'\n";
	echo "}";
}
if($opc != 1){
	$contenido = "";
	$objAdjuntos = new InsertaAdjuntos($db,$idProyecto,$idActividad,$idTrimestre,$path_files,$path_img,"Sin archivo",$opc,$_REQUEST,$path_web,$id,$_SESSION,$filecurl);
	$contenido = $objAdjuntos->obtenBuffer();
	echo $contenido;
}

?>