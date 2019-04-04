<?php
class ValidaUsuario extends Comunes{
  var $db;
  var $data;
  var $mensaje;
  var $path;
  var $cadena_error;
  var $user_id;
  var $user_nm;
  var $user_nivel;
  var $user_area;
  var $exito;
  var $server;
  var $estilo;
  var $banner;
  var $array_estilos;
  var $areas;
  var $programas;
  var $idunidadOperativa;
  var $nmunidadOperativa;
  var $nmUnidadResponsable;
  var $userRol;
  var $anoCapturaMetas;
  
  
  function __construct($db,$data,$server,$path){
    $this->db   = $db;
    $this->data = $data;
    $this->path = $path;
    $this->server = $server;
    $this->user_id=$this->user_nivel=$this->user_area=0;
    $this->user_nm="";
    $this->mensaje= "Favor de teclear las claves de acceso";
    $this->exito=0;
    $this->estilo="";
    $this->banner="";
    $this->areas   = $this->programas=array();
    $this->userRol = $this->idunidadOperativa = 0;
    $this->nmunidadOperativa="";
    $this->nmUnidadResponsable="";
    $this->anoCapturaMetas=2015;
    $this->array_estilos=array();
    $this->cadena_error="<script>location.href='".$this->path_web."'</script>";    
    if( (trim($this->data['usuario'])!= "") && (trim($this->data['clave'])!= "") )
    {
      $this->data['usuario']=trim($this->eliminaCaracteresInvalidos($this->data['usuario']));
      $this->data['clave']  =trim($this->eliminaCaracteresInvalidos($this->data['clave']));
      $this->estilos();
      $this->valida();
    }
  }
  
  function estilos()
  {
    $sql="SELECT id,url,style,logo FROM cat_estilos ORDER BY id;";
    $res = $this->db->sql_query($sql) or die ($this->cadena_error);
    if($this->db->sql_numrows($res)>0)
    {
      while(list($_id,$_url,$_style,$_logo) = $this->db->sql_fetchrow($res))
      {
        $this->array_estilos[$_id]['url']=$_url;
        $this->array_estilos[$_id]['style']=$_style;
        $this->array_estilos[$_id]['logo']=$_logo;
      }
    }
  }
  
  function revisaAreasAsignadas(){
    $arrayAsignadas=array();
    $sql="SELECT DISTINCT area_id FROM cat_permisos_areas WHERE usuario_id='".$this->user_id."';";
    $res = $this->db->sql_query($sql) or die ($this->cadena_error);
    if($this->db->sql_numrows($res)>0)
    {
      while(list($_idArea) = $this->db->sql_fetchrow($res))
      {
        $arrayAsignadas[]=$_idArea;
      }
    }
    if(count($arrayAsignadas) == 1)
    	$this->regresaUnidadResponsable($arrayAsignadas[0]);
    $this->areas=implode(',',$arrayAsignadas);
  }

  function revisaProgramasAsignados(){
  	$arrayAsignadas=array();
  	$sql="SELECT DISTINCT programa_id FROM cat_permisos_areas WHERE usuario_id='".$this->user_id."';";
  	$res = $this->db->sql_query($sql) or die ($this->cadena_error);
  	if($this->db->sql_numrows($res)>0)
  	{
  		while(list($_idArea) = $this->db->sql_fetchrow($res))
  		{
  			$arrayAsignadas[]=$_idArea;
  		}
  	}
  	$this->programas=implode(',',$arrayAsignadas);
  }
  
  function revisaAnoCaptura(){
  	$this->anoCapturaMetas=date('Y');
  	$sql="SELECT ano FROM  cat_anos WHERE active='1' order by ano desc;";
  	$res = $this->db->sql_query($sql) or die ($this->cadena_error);
  	if($this->db->sql_numrows($res)>0){
  		list($this->anoCapturaMetas) = $this->db->sql_fetchrow($res);
  	} 
  }
  
  function valida(){
    $sql="SELECT user_id,concat(user_nombre,'<br>',user_datos2) as user_nombre,user_rol,area_id,estilo_id,user_rol,unidadOperativaId 
    	  FROM cat_usuarios WHERE user_activo='1'
          AND user_login='".$this->data['usuario']."' AND user_password =PASSWORD('".$this->data['clave']."') 
          LIMIT 1;";
    $res = $this->db->sql_query($sql) or die ($this->cadena_error);
    if($this->db->sql_numrows($res)>0){
      $this->exito=1;
      list($this->user_id,$this->user_nm,$this->user_nivel,$this->user_area,$estilo_id,$this->userRol,$this->idunidadOperativa) = $this->db->sql_fetchrow($res);
      $this->estilo=$this->array_estilos[$estilo_id]['style'];
      $this->regresaUnidaOperativa();     
      $this->banner=$this->array_estilos[$estilo_id]['logo'];
      $this->revisaAreasAsignadas();
      $this->revisaProgramasAsignados();
      $this->revisaAnoCaptura();
      
      $fecha=date('Y-m-d H:i:s');
      $insLog="INSERT INTO log_accesos(user_id,estatus,timestamp,ip)
               VALUES ('".$this->user_id."','1','".$fecha."','".$this->server['REMOTE_ADDR']."');";
      $this->db->sql_query($insLog) or die ($this->cadena_error);
    }
    else{
      $this->mensaje="Las claves son incorrectas";
    }
  }
  function regresaUnidadResponsable($areaId){
  	$sql="SELECT nombre FROM cat_areas where area_id='".$areaId."' LIMIT 1;";
  	$res=$this->db->sql_query($sql) or die ($this->cadena_error);
  	if($this->db->sql_numrows($res)>0){
  		list($this->nmUnidadResponsable)=$this->db->sql_fetchrow($res);		
  	}
  	
  }
  function regresaUnidaOperativa(){
  	if($this->idunidadOperativa > 0){
  		$sql="SELECT nombre FROM cat_unidad_operativas where unidad_id='".$this->idunidadOperativa."' LIMIT 1;";
  		$res=$this->db->sql_query($sql) or die ($this->cadena_error);
  		if($this->db->sql_numrows($res)>0){
	  		list($this->nmunidadOperativa)=$this->db->sql_fetchrow($res);
  		}
  	}
  }
  
  function obtenIdUser(){
    return $this->user_id;
  }
  
  function obtenNmUser(){
  	$tmp[]=$this->nmUnidadResponsable;
  	$tmp[]=$this->nmunidadOperativa;
  	$leyenda="";
  	foreach($tmp as $tit){
  		if(trim($tit)!= ""){
  			$leyenda.="<br>".$tit;
  		}
  	}
    return $this->user_nm.$leyenda;
  }
  
  function obtenNivelUser(){
    return $this->user_nivel;
  }
  
  function obtenAreaUser(){
    return $this->user_area;
  }
  
  function obtenExito(){
    return $this->exito;
  }
  
  function obtenMensaje(){
    return $this->mensaje;
  }
  function obtenEstilo(){
    return $this->estilo;
  }
  function obtenBanner(){
    return $this->banner;
  }
  function obtenAreas(){
    return $this->areas;
  }
  function obtenProgramas(){
  	return $this->programas;
  }
  function obtenRol(){
  	return $this->userRol;
  }
  function obtenAnoCaptura(){
  	return $this->anoCapturaMetas;
  }
}
?>