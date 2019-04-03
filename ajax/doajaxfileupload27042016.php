<?php
include_once("../includeAjax.php");
include_once($path_sis."config.php");
include_once($path_sis."revisaSesion.php");
include_once($path_sis."lang/es.php");
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
				$error = ERROR_1;
				break;
			case '2' :
				$error =  ERROR_2;
				break;
			case '3' :
				$error =  ERROR_3;
				break;
			case '4' :
				$error =  ERROR_4;
				break;
			case '6' :
				$error =  ERROR_5;
				break;
			case '7' :
				$error =  ERROR_6;
				break;
			case '8' :
				$error =  ERROR_7;
				break;
			case '999' :
			default :
				$error =  ERROR_8;
		}
	} 
	elseif (empty ( $_FILES ['fileToUpload'] ['tmp_name'] ) || $_FILES ['fileToUpload'] ['tmp_name'] == 'none') {
		$error = ERROR_9;
	} 
	else{
		$filename = "";
		$msg .= "" . $_FILES ['fileToUpload'] ['name'] . "";
		$array_exts = array ('doc','xls','ppt','jpg','pdf','png','gif','bmp','xlsx','docx','pptx','csv','zip','xml','bak','html','txt');
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
				$error = ERROR_10;
			}
			elseif ($size > $max_file){
				$error = ERROR_11."  ". $size;
			}
			else{
				// checamos el tipo de archivo
				if (! in_array ( $ext, $array_exts )) {
					$error = ERROR_12." ".implode ( ', ', $array_exts ) . ".";
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
								$error = ERROR_16;
							else
								$error = ERROR_13;
						}
					}
					else{
						$error = ERROR_14."   ".$file;
					}
				}
			}
		}
		else{
			$error = ERROR_15;
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