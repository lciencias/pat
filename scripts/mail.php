<?php
include_once("../swift/lib/swift_required.php");
$body="esta es una prueba de correo, que sale desde la cuenta de pat@df.gob.mx";
$body_html="<html><head><title>PRUEBA DE CORREO</title></head><body><p>".$body."</p></body></html>";
$usuario='pat@df.gob.mx';
$passwor='gp=a5=d8';
$tituloMnesaje="Test de correo";
$emailFrom = array ("pat@df.gob.mx" => "Administrador SISEC");
$emailTo   = array ("lciencias@gmail.com" => "Administrador SISEC");
try
{
	$transport = Swift_SmtpTransport::newInstance('smtp.df.gob.mx',25)->setUsername($usuario)->setPassword($passwor);
	$mailer    = Swift_Mailer::newInstance($transport);
	$message   = Swift_Message::newInstance($tituloMnesaje)->setFrom($emailFrom)->setTo($emailTo)->setBody($body_html,'text/html')->addPart($body_html,'text/plain');
	if (($mailer->send($message)) > 0){
		echo"El correo se ha enviado satisfactoriamente";
	}
}
catch(Exception $e){
	echo "Error:  ".$e->getMessage();
}

