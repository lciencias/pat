<?php
class Menu
{
    var $db;
    var $menu;
    var $path_web;
    var $array_niveles;
    var $cadena_error;
	var $user_id;
	var $arrayMenus;
    function __construct($db,$path_web,$user_id)
    {
        $this->db=$db;
        $this->path_web=$path_web;
		$this->user_id=$user_id;
        $this->cadena_error="<script>location.href='".$this->path_web."'</script>";
        $this->array_niveles=array();        
        $this->menu="
                <div class='navbar-header'>
                    <button class='navbar-toggle' type='button' data-toggle='collapse' data-target='#navbar-collapse'>
                        <span class='sr-only'>Toggle navigation</span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                    </button><!--//nav-toggle-->
                </div>";
		$this->arrayMenus=array();
        $this->Genera();
    }
    
    function Recupera_Menus($_id)
    {
        $tmp="";
		$sql="SELECT a.menu_id,a.submenu_id,b.nombre,b.titulo,b.url 
			  FROM cat_permisos_menus a LEFT JOIN cat_submenu b ON a.submenu_id=b.submenu_id 
			  WHERE a.usuario_id='".$this->user_id."' AND a.menu_id=".$_id." 
			  AND b.active='1' AND b.submenu_id > 0 AND b.nombre IS NOT NULL 
			  ORDER BY a.menu_id,b.orden;";
        $res=$this->db->sql_query($sql) or die ($this->cadena_error);
        if($this->db->sql_numrows($res)>0)
        {
            $this->menu.="<ul class='dropdown-menu'>";
            while(list($menu_id,$subMenuId,$nombre,$titulo,$url) = $this->db->sql_fetchrow($res))
            {
               
				$url=$this->path_web."aplicacion.php?aplicacion=".$menu_id."&apli_com=".$subMenuId;
				$this->menu.='<li><a href="'.$url.'">'.trim($nombre).'</a></li>';
            }
            $this->menu.="</ul>";
        }
    }
	function Genera()
    {
        $tmp=$_idd="";
        $sql="SELECT DISTINCT a.menu_id,b.nombre,b.titulo,b.url,b.url_trabajo 
			  FROM cat_permisos_menus a LEFT JOIN cat_menu b ON a.menu_id=b.menu_id 
			  WHERE a.usuario_id=".$this->user_id." AND b.activo='1' AND b.nombre IS NOT NULL ORDER BY b.orden,a.menu_id;";
		$this->menu="";
        $res=$this->db->sql_query($sql,$this->db) or die ($this->cadena_error);
        if($this->db->sql_numrows($res)>0)
        {
            $this->menu.="
				<div class='navbar-collapse collapse' id='navbar-collapse'>
                    <ul class='nav navbar-nav'>";
            while(list($menu_id,$nombre,$titulo,$url,$url_trabajo) = $this->db->sql_fetchrow($res))
            {
                $link="#";
				$class="";
                if( ($menu_id == 1) || ($menu_id == 7) ){
					  $url="aplicacion.php";
					  $class=' class="active nav-item" ';
					  if($menu_id == 7){
						$url="logout.php";
						$class=" class='nav-item' ";
					  }
					  $this->menu.='<li '.$class.'><a href="'.$url.'">'.trim($nombre).'</a></li>';
				}
				else{
					$this->menu.='
						<li class="nav-item dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="0" data-close-others="false" href="#">
							'.trim($nombre).'<i class="fa fa-angle-down"></i>
						</a>';
					$this->Recupera_Menus($menu_id);
					$this->menu.="</li>";
				}
            }
            $this->menu.="</ul></div>";
        }
    }
    
    
    function Obten_Menu()
    {
        return $this->menu;
    }
}
?>