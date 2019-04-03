<?php
class EliminaArchivos
{
  var $path;
  var $buffer;
  
  function __construct($path){
    
    $this->path=$path;
    $this->buffer="";
    $this->Elimina();
  }
  
  function Elimina(){
    
    $pathTtmp=$this->path."tmp/";
    $directorio=opendir($pathTtmp) or die("error");;
    $this->buffer="<div class='panel-body'><center><br><span class='tdcenter btn-default'>Se han eliminado los archivos temporales</span><br>";
    while($archivo=readdir($directorio))
    {
		$trozos = explode(".", $archivo); 
		$extension = end($trozos);  
        if(trim($extension)== "xls")
        {
          $this->buffer.="<br>".$archivo;
           unlink($pathTtmp.$archivo);
        }
    }
    $this->buffer.="</center></div>";
  }
	
  function obtenBuffer(){
    return $this->buffer;
  }
}
?>